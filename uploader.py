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

def runBash(command):
	os.system(command)

def touchDir(dir, strict = False):
	if (strict == True and os.path.isdir(dir)):
		raise Exception('Folder already exists: ' + dir)
	if not os.path.isdir(dir):
		os.mkdir(dir)

def folderIsEmpty(folder):
	if os.listdir(folder):
		return False
	else:    
		return True

def rmdir(dir):
	if os.path.isdir(dir):
		shutil.rmtree(dir)


def share(saveTo, filename, length):
	print('\nSharing', filename, 'len:', length, end='', flush=True)
	start_time = time.time()
	opts = type('obj', (object,), {
		'portal_url': config.upload_portal_url,
		'portal_upload_path': 'skynet/skyfile',
		'portal_file_fieldname': 'file',
		'portal_directory_file_fieldname': 'files[]',
		'custom_filename': ''
	})
	skylink = Skynet.upload_file(saveTo, opts)
	siaskylink = skylink.replace("sia://", "")
	siaskylink = 'https://siasky.net/' + siaskylink

	post = {
		'password': config.m3u8_list_upload_password,
		'streamid': config.streamId,
		'length': length,
		'url': siaskylink
		}
	x = requests.post(config.m3u8_list_upload_path, data = post)
	if (x.text != 'ok'):
		print('Error: posting failed', x.text)
	else:
		print("\nVideo uploaded in", (time.time() - start_time))

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



projectPath = r'C:\Wamp.NET\sites\archive\Skylive'
recordFolder = os.path.join(projectPath, "record_here")
streamedTime = 0
nextStreamFilename = 0

touchDir(recordFolder)
if not folderIsEmpty(recordFolder):
	print('Record folder is not empty: ' + recordFolder)
	exit(0)

while True:
	if not chech_m3u8(recordFolder):
		print('Waiting for recording - no m3u8 file found')
		time.sleep(1)
	else:
		break

while True:
	nextAfterFile = os.path.join(recordFolder, "live" + str(nextStreamFilename + 1) + ".ts")
	if os.path.isfile(nextAfterFile):
		nextFile = os.path.join(recordFolder, "live" + str(nextStreamFilename) + ".ts")
		nextLen = get_length(nextFile)
		share(nextFile, nextStreamFilename, nextLen)
		nextStreamFilename += 1
	else:
		time.sleep(1)
		print('.', end='', flush=True)