from websocket import create_connection
import os
from siaskynet import Skynet
from datetime import datetime as dt
from datetime import timedelta as td
import time
import sys, os
import logging
import shutil
import config
import json
import threading
import subprocess

def runBash(command):
	os.system(command)

def touchDir(dir, strict = False):
	if (strict == True and os.path.isdir(dir)):
		raise Exception('Folder already exists: ' + dir)
	if not os.path.isdir(dir):
		os.mkdir(dir)

def rmdir(dir):
	if os.path.isdir(dir):
		shutil.rmtree(dir)

def sendSocket(data):
	ws = create_connection('wss://' + config.websocket_ip + ':' + config.websocket_port)
	jdata = {
		"password": config.websocket_password,
		"data": data
	}
	jdata = json.dumps(jdata)
	status = ws.send(jdata)
	result =  ws.recv()
	result1 =  ws.recv()
	result2 =  ws.recv()
	if (result == 'wrong_pass' or result1 == 'wrong_pass' or result2 == 'wrong_pass'):
		raise Exception('Wrong websocket password set in config.py')
	elif (result != data and result1 != data and result2 != data):
		raise Exception('Websocket send failed. Unexpected error: ' + result)
	ws.close()
	return True

def share(saveTo):
	# print('Uploading', saveTo)
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
	if (sendSocket(siaskylink)):
		print("Video uploaded in", (time.time() - start_time))

def get_length(filename):
    result = subprocess.run(["ffprobe", "-v", "error", "-show_entries",
                             "format=duration", "-of",
                             "default=noprint_wrappers=1:nokey=1", filename],
        stdout=subprocess.PIPE,
        stderr=subprocess.STDOUT)
    return float(result.stdout)


def searchFor10sSums(segmentToUse):
	segm = []
	sumdur = 0
	while True:
		videoFile = os.path.join(segmentsPath, str(segmentToUse) + ".mp4")
		if os.path.isfile(videoFile):
			dur = get_length(videoFile)
		else:
			# print('Clip not found: ' + videoFile)
			return False
		segm.append(segmentToUse)
		sumdur += dur
		segmentToUse += 1
		if sumdur >= 10:
			with open('concat.txt', 'w+') as concatTXT:
				for segment in segm:
					concatTXT.write("file '" + os.path.join(segmentsPath, str(segment) + ".mp4") + "'\n")
			nextSegmentToUse = segm[len(segm)-1] + 1
			# print('Next segments to convert:', segm)
			return [sumdur, nextSegmentToUse]

def fileExist(file):
	if os.path.isfile(videoFile):
		return True
	else:
		return False

projectPath = r'C:\Wamp.NET\sites\archive\Skylive'
if (len(sys.argv) != 2):
	raise Exception('Please enter one argunemt: livestream flv path')
recordVideo = sys.argv[1]
streamedTime = 0
nextStreamFilename = 0

segmentsPath = os.path.join(projectPath, "segments")
touchDir(segmentsPath, True)

while True:
	# convert .flv stream into .ts HLS segments
	saveTo = os.path.join(segmentsPath, "live.m3u8")
	start_time = time.time()
	runBash('ffmpeg -ss ' + str(streamedTime) + ' -i "' + recordVideo + '" -profile:v -c copy baseline -level 3.0 -start_number 0 -hls_time 10 -hls_list_size 0 -f hls "' + saveTo + '"')
	print('Hls cutting finished in', (time.time() - start_time))

	nextSegmentToUse = 0
	while True:

		nextSegmentToCheck = os.path.join(segmentsPath, 'live' + str(nextStreamFilename) + '.ts')
		if fileExist(nextSegmentToCheck):
			print('Ch next semgm:', nextSegmentToCheck)
			""" upload
			append to m3u8
					print('#EXTINF:' + str(segmentSum) + ',')
					print(str(nextStreamFilename) + '.ts')
			update m3u8 """
			streamedTime += get_length(nextSegmentToCheck)
			nextStreamFilename += 1
		else:
			break


	# print('Waiting for new segments')
	time.sleep(1)