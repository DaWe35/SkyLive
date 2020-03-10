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
def blockPrint():
	sys.stdout = open(os.devnull, 'w')
def enablePrint():
	sys.stdout = sys.__stdout__
blockPrint()
from moviepy.editor import VideoFileClip 
enablePrint()


def runBash(command):
	print(command)
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
	ws = create_connection('ws://' + config.websocket_ip + ':' + config.websocket_port)
	jdata = {
		"password": config.websocket_password,
		"data": data
	}
	jdata = json.dumps(jdata)
	status = ws.send(jdata)
	print('Ws send, wait for answer')
	result =  ws.recv()
	result1 =  ws.recv()
	result2 =  ws.recv()
	if (result == 'wrong_pass' or result1 == 'wrong_pass' or result2 == 'wrong_pass'):
		raise Exception('Wrong websocket password set in config.py')
	elif (result != data and result1 != data and result2 != data):
		raise Exception('Websocket send failed. Unexpected error: ' + result)
	ws.close()
	print('Ws success')

def share(saveTo):
	print('Uploading...')
	skylink = Skynet.upload_file(saveTo)
	siaskylink = skylink.replace("sia://", "")
	siaskylink = 'https://siasky.net/' + siaskylink
	sendSocket(siaskylink)

projectPath = r'C:\Wamp.NET\sites\archive\Skylive'
if (len(sys.argv) != 2):
	raise Exception('Please enter one argunemt: livestream flv path')
recordVideo = sys.argv[1]
streamedTime = 0
nextStreamFilename = 0

segmentsPath = os.path.join(projectPath, "segments")
convertedPath = os.path.join(projectPath, "converted")
rmdir(segmentsPath)
touchDir(convertedPath, True)


def searchFor10sSums(segmentToUse):
	segm = []
	sumdur = 0
	while True:
		videoFile = os.path.join(segmentsPath, str(segmentToUse) + ".mp4")
		if os.path.isfile(videoFile):
			clip = VideoFileClip(videoFile)
		else:
			print('Clip not found: ' + videoFile)
			return False
		dur = clip.duration
		clip.close()
		segm.append(segmentToUse)
		sumdur += dur
		segmentToUse += 1
		if sumdur >= 10:
			with open('concat.txt', 'w+') as concatTXT:
				for segment in segm:
					concatTXT.write("file '" + os.path.join(segmentsPath, str(segment) + ".mp4") + "'\n")
			nextSegmentToUse = segm[len(segm)-1] + 1
			print('Next segments to convert: ')
			print(segm)
			return [sumdur, nextSegmentToUse]


while True:
	# convert flv Livestream into mp4 segments
	touchDir(segmentsPath)
	saveTo = os.path.join(segmentsPath, "%d.mp4")
	runBash('ffmpeg -loglevel panic -ss ' + str(streamedTime) + ' -i "' + recordVideo + '" -acodec copy -f segment -vcodec copy -reset_timestamps 1 -map 0 "' + saveTo + '"')
	print('prev ffmpeg finished')

	nextSegmentToUse = 0
	while True:
		chunks = searchFor10sSums(nextSegmentToUse)
		if (chunks == False):
			break
		segmentSum = chunks[0]
		nextSegmentToUse = chunks[1]
		saveTo = os.path.join(convertedPath, str(nextStreamFilename) + '.mp4')
		runBash('ffmpeg -loglevel panic -f concat -safe 0 -i concat.txt -c copy ' + saveTo)
		x = threading.Thread(target=share, args=(saveTo,))
		x.start()
		streamedTime += segmentSum
		nextStreamFilename += 1


	rmdir(segmentsPath)
	print('Sleep 1')
	time.sleep(1)