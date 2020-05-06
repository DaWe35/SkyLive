import os
from siaskynet import Skynet
import time
import sys
import logging
import shutil
import config
import json
import subprocess
import requests
from tabulate import tabulate
import curses
from threading import Thread
import argparse

def runBash(command):
	os.system(command)

def touchDir(dir, strict = False):
	if (strict == True and os.path.isdir(dir)):
		raise Exception('Folder already exists: ' + dir)
	if not os.path.isdir(dir):
		os.mkdir(dir)

def folderIsEmpty(folder):
	if not os.path.isdir(folder):
		return True
	if os.listdir(folder):
		return False
	else:    
		return True

def rmdir(dir):
	if os.path.isdir(dir):
		shutil.rmtree(dir)

def upload_file(saveTo, portal):
	opts = type('obj', (object,), {
		'portal_url': portal,
		'portal_upload_path': 'skynet/skyfile',
		'portal_file_fieldname': 'file',
		'portal_directory_file_fieldname': 'files[]',
		'custom_filename': ''
	})
	try:
		return Skynet.upload_file(saveTo, opts)
	except:
		logging.error('Uploading failed with ', portal)
		return False

def upload(saveTo, fileId, length, filearr):
	global concurrent_uploads
	filearr[fileId].status = 2
	start_time = time.time()

	# upload and retry if fails with backup portals
	skylink = upload_file(saveTo, config.upload_portal_url)
	for backup_portal in config.backup_portals:
		if skylink != False:
			break
		skylink = upload_file(saveTo, backup_portal)

	if (skylink == False):
		logging.error('Upload finally failed for', saveTo)
		filearr[fileId].status = 6

	siaskylink = skylink.replace("sia://", "")
	siaskylink = 'https://siasky.net/' + siaskylink
	filearr[fileId].skylink = siaskylink
	if filearr[fileId].status != 6:
		filearr[fileId].status = 3
	filearr[fileId].uploadTime = round(time.time() - start_time)
	concurrent_uploads -= 1


def get_length(filename):
	if not os.path.isfile(filename):
		return False
	result = subprocess.run(["ffprobe", "-v", "panic", "-show_entries",
							 "format=duration", "-of",
							 "default=noprint_wrappers=1:nokey=1", filename],
		stdout=subprocess.PIPE,
		stderr=subprocess.STDOUT)
	if result.stdout:
		return float(result.stdout)
	else:
		return False

def chech_m3u8(recordFolder):
	for file in os.listdir(recordFolder):
		if file.endswith(".m3u8"):
			return True
	return False

def isPlaylistFinished(recordFolder):
	playlistFile = os.path.join(recordFolder, "live.m3u8")
	with open(playlistFile, 'r') as f:
		lines = f.read().splitlines()
		last_line = lines[-1]
		if last_line == '#EXT-X-ENDLIST':
			return True
		else:
			return False

def updateDisplay(window, filearr, symbols):
	window.addstr(0, 0, 'Status symbols:\n')
	symbarray = []
	idx = 0
	
	for symb in symbols:
		symbarray.append([symb.symbol, symb.description, idx])
		idx += 1
	table = (tabulate(symbarray, headers=['symbol', 'status', 'code'], tablefmt='orgtbl'))
	window.addstr(table + '\n\n\n')

	file = ['File']
	status = ['Status']
	length = ['Length']
	uptime = ['Upload time']
	terminalColumns, terminalRows = shutil.get_terminal_size()
	showRows = int(terminalColumns/6) - 3
	ran = len(filearr) if (len(filearr) < showRows) else showRows
	for i in range(ran):
		ind = -ran+i
		symbolCode = filearr[ind].status
		file.append(filearr[ind].fileId)
		status.append(symbols[symbolCode].symbol)
		videoLength = round(filearr[ind].length)
		if (videoLength == -1):
			length.append('')
		else:
			length.append(str(videoLength) + 's')
		uploadTime = filearr[ind].uploadTime
		if (uploadTime == -1):
			uptime.append('')
		else:
			uptime.append(str(uploadTime) + 's')

	table = (tabulate([file, status, length, uptime], tablefmt='orgtbl'))
	window.addstr(table)
	window.refresh()

def share(fileId, filearr):
	filearr[fileId].status = 4
	post = {
		'password': config.m3u8_list_upload_password,
		'streamid': streamId,
		'url': filearr[fileId].skylink,
		'length': filearr[fileId].length
		}
	x = requests.post(config.m3u8_list_upload_path, data = post)
	if (x.text != 'ok'):
		logging.error('Error: posting failed', x.text)
		filearr[fileId].status = 6
	else:
		filearr[fileId].status = 5

def worker(window):
	global concurrent_uploads, projectPath, recordFolder
	streamedTime = 0
	nextStreamFilename = 0
	lastSharedFileId = -1
	class VideoFile:
		def __init__(self, fileId):
			self.fileId = fileId
			self.status = 0
			self.uploadTime = -1
			self.length = -1
			self.skylink = 'skylink'
		def __str__(self):
			return str(self.__dict__)

	filearr = [
		# file, status, upload time, length, skylink
		VideoFile(nextStreamFilename)
	]

	class Symbol:
		def __init__(self, symbol, description):
			self.symbol = symbol
			self.description = description

	symbols = [
		Symbol(" ", 'waiting for file'),
		Symbol(".", 'upload queued'),
		Symbol("↑", 'uploading'),
		Symbol("▒", 'share queued'),
		Symbol("▓", 'sharing'),
		Symbol("█", 'shared'),
		Symbol("X", 'share failed'),
	]
	touchDir(recordFolder)

	cntr = 0
	while True:
		if not chech_m3u8(recordFolder):
			if args.record_folder:
				record_folder_name = args.record_folder
			else:
				record_folder_name = 'record_here'
			window.addstr(0, 0, 'Waiting for recording, no m3u8 file found in ' + record_folder_name + ' folder (%ds)' %(cntr))
			window.refresh()
			cntr += 1
			time.sleep(1)
		else:
			break

	while True:
		nextFile = os.path.join(recordFolder, "live" + str(nextStreamFilename) + ".ts")
		nextAfterFile = os.path.join(recordFolder, "live" + str(nextStreamFilename + 1) + ".ts")
		updateDisplay(window, filearr, symbols)
		if concurrent_uploads < 10 and ( os.path.isfile(nextAfterFile) or ( isPlaylistFinished(recordFolder) and os.path.isfile(nextFile) ) ):
			filearr.append(VideoFile(nextStreamFilename + 1))
			filearr[nextStreamFilename].status = 1
			nextLen = get_length(nextFile)
			filearr[nextStreamFilename].length = nextLen
			Thread(target=upload, args=(nextFile, nextStreamFilename, nextLen, filearr)).start()
			concurrent_uploads += 1
			nextStreamFilename += 1
		else:
			time.sleep(1)
		
		# check_share_queue(check_share_queue, filearr)
		nextToShare = lastSharedFileId + 1
		if (filearr[nextToShare].status == 3):
			share(nextToShare, filearr)
			lastSharedFileId += 1

if config.m3u8_list_upload_password == '':
	print('Playlist server password did not set, please setup config.py (more info in readme.md)')
	exit(0)

parser = argparse.ArgumentParser('Upload HLS (m3u8) live stream to SkyLive')
parser.add_argument('--record_folder', help='Record folder, where m3u8 and ts files are (will be) located (default: record_here)')
args = parser.parse_args()

concurrent_uploads = 0
projectPath = os.path.dirname(os.path.abspath(__file__))

# get recordFolder
if (args.record_folder):
	if (os.path.isabs(args.record_folder)):
		recordFolder = args.record_folder
	else:
		recordFolder = os.path.join(projectPath, args.record_folder)
else:
	recordFolder = os.path.join(projectPath, "record_here")

if not folderIsEmpty(recordFolder):
	print('Record folder is not empty: ' + recordFolder)
	print('Are you sure, you want to continue?')
	input("Press Enter to continue...")

streamId = input("Enter stream id: ")

	
logFile = os.path.join(projectPath, "error_log.txt")
logging.basicConfig(filename=logFile,
	filemode='a',
	format='%(asctime)s,%(msecs)d %(name)s %(levelname)s %(message)s',
	datefmt='%H:%M:%S',
	level=logging.DEBUG)

curses.wrapper(worker)