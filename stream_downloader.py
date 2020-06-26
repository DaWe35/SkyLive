print('Initializing...')
import functools
print = functools.partial(print, flush=True)
import youtube_dl
import ffmpeg
import argparse
import atexit
import os
import shutil
import logging

def touchDir(dir):
	if (os.path.isdir(dir)):
		return False
	else:
		os.mkdir(dir)
		return True

def rmdir(dir):
	if os.path.isdir(dir):
		shutil.rmtree(dir)

logFile = os.path.join(projectPath, "stream_downloader.log")
logging.basicConfig(filename=logFile,
	filemode='a',
	format='%(asctime)s,%(msecs)d %(name)s %(levelname)s %(message)s',
	datefmt='%H:%M:%S',
	level=logging.DEBUG)
logging.info('LOGGING STARTED')

parser = argparse.ArgumentParser(description="Restream Youtube/Twitch live to SkyLive")
parser.add_argument('--url', help='Video url (for example https://www.youtube.com/watch?v=ASD123', required=True)
parser.add_argument('--record_folder', help='The stream will be downloaded here, and uploaded from this folder')
# parser.add_argument('--token', help='SkyLive live stream token. You need to create a new stream on https://skylive.coolhd.hu', required=True)
args = parser.parse_args()

# create temp folder
projectPath = os.path.expanduser( os.path.join('~', '.SkyLive'))
touchDir(projectPath)

if args.record_folder:
	if (os.path.isabs(args.record_folder)):
		recordFolder = args.record_folder
	else:
		recordFolder = os.path.join(projectPath, args.record_folder)
	recordFile = os.path.join(projectPath, str(args.record_folder), 'live.m3u8')
else:
	dirNumb = 0
	while True:
		recordFolder = os.path.join(projectPath, "temp_restream_" + str(dirNumb))
		recordFile = os.path.join(projectPath, "temp_restream_" + str(dirNumb), 'live.m3u8')
		if touchDir(recordFolder):
			break
		dirNumb += 1

touchDir(recordFolder)

def exit_handler():
	pass
	# print('Removing', recordFolder, 'folder...')
	# rmdir(recordFolder)

atexit.register(exit_handler)

def get_youtube_m3u8(video_url):
	ydl = youtube_dl.YoutubeDL({'outtmpl': '%(id)s%(ext)s'})

	with ydl:
		result = ydl.extract_info(
			video_url,
			download=False # We just want to extract the info
		)

	if 'entries' in result:
		# Can be a playlist or a list of videos
		video = result['entries'][0]
	else:
		# Just a video
		video = result
	return video['url']


m3u8 = get_youtube_m3u8(args.url)
logging.debug('Found m3u8 file: ' + m3u8)
input_stream = ffmpeg.input(m3u8)
logging.debug('Recording file: ' + recordFile)
output_stream = ffmpeg.output(input_stream, recordFile, vcodec="copy", acodec="copy", hls_time=10)
ffmpeg.run(output_stream)
