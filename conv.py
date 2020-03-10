import os
from siaskynet import Skynet
from datetime import datetime as dt
from datetime import timedelta as td
import time
import sys, os
import logging
import shutil
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

def touchDir(dir):
	if not os.path.isdir(dir):
		os.mkdir(dir)

def rmdir(dir):
	if os.path.isdir(dir):
		shutil.rmtree(dir)

projectPath = r'C:\Wamp.NET\sites\archive\Skylive'
recordVideo = r'C:\Users\Hp\Videos\2020-03-10 15-45-48.flv'
streamedTime = 0
nextStreamFilename = 0

segmentsPath = os.path.join(projectPath, "segments")
convertedPath = os.path.join(projectPath, "converted")
rmdir(segmentsPath)
touchDir(convertedPath)


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
	
	nextSegmentToUse = 0
	while True:
		chunks = searchFor10sSums(nextSegmentToUse)
		if (chunks == False):
			break
		segmentSum = chunks[0]
		nextSegmentToUse = chunks[1]
		saveTo = os.path.join(convertedPath, str(nextStreamFilename) + '.mp4')
		runBash('ffmpeg -loglevel panic -f concat -safe 0 -i concat.txt -c copy ' + saveTo)
		streamedTime += segmentSum
		nextStreamFilename += 1


	rmdir(segmentsPath)
	print('Sleep 1')
	time.sleep(1)