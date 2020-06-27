print('Initializing...')
import functools
print = functools.partial(print, flush=True)
import youtube_dl
import argparse
import atexit
import os
import shutil
import logging
import platform
import requests

def touchDir(dir):
	if (os.path.isdir(dir)):
		return False
	else:
		os.mkdir(dir)
		return True

def rmdir(dir):
	if os.path.isdir(dir):
		shutil.rmtree(dir)
	
def runBash(command):
	return os.system(command)

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

# create temp folder
projectPath = os.path.expanduser( os.path.join('~', '.SkyLive'))
touchDir(projectPath)

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

# get record folder
if args.record_folder:
	if (os.path.isabs(args.record_folder)):
		recordFolder = args.record_folder
	else:
		recordFolder = os.path.join(projectPath, args.record_folder)
else:
	dirNumb = 0
	while True:
		recordFolder = os.path.join(projectPath, "temp_restream_" + str(dirNumb))
		if touchDir(recordFolder):
			break
		dirNumb += 1

touchDir(recordFolder)

# get record file
fileNumb = 1
while True:
	recordFile = os.path.join(recordFolder, str(fileNumb) + '_.m3u8')
	if (os.path.exists(recordFile)):
		fileNumb += 1
	else:
		break

open(recordFile, 'a').close()

# if ffmpeg is not installed
if (runBash('ffmpeg -version') == 0):
	ffmpeg_command = 'ffmpeg'
	logging.info("Preinstalled ffmpeg found, using 'ffmpeg' command")
else:
	system = platform.system()
	machine = platform.machine()
	if system == 'Windows':
		# https://ffmpeg.zeranoe.com/builds/win64/static/ffmpeg-4.3-win64-static.zip
		ffmpeg_filename = 'ffmpeg.exe'
	elif system == 'Linux':
		if machine == 'AMD64' or machine == 'x86_64':
			# https://johnvansickle.com/ffmpeg/releases/ffmpeg-release-amd64-static.tar.xz
			ffmpeg_filename = 'ffmpeg_linux_amd64'
		elif machine == 'i686':
			# https://johnvansickle.com/ffmpeg/releases/ffmpeg-release-i686-static.tar.xz
			ffmpeg_filename = 'ffmpeg_linux_i686'
		elif machine == 'arm':
			# https://johnvansickle.com/ffmpeg/releases/ffmpeg-release-arm64-static.tar.xz
			ffmpeg_filename = 'ffmpeg_linux_arm64'
		else:
			print('No ffmpeg found for your architecture (' + machine + '), please install ffmpeg manually!')
			logging.error('No ffmpeg found for architecture: ' + machine)
	elif system == 'Darwin':
		# https://evermeet.cx/ffmpeg/ffmpeg-4.3.7z
		ffmpeg_filename = 'ffmpeg_darwin'
	else:
		print('No ffmpeg found for your system (' + system + '), please install ffmpeg manually!')
		logging.error('No ffmpeg found for system: ' + system)

	ffmpeg_command = os.path.join(projectPath, ffmpeg_filename)
	if not os.path.isfile(ffmpeg_command):
		url = 'https://github.com/DaWe35/SkyLive/raw/master/bin/' + ffmpeg_filename
		logging.info("No ffmpeg found, downlaoding from " + url)
		print("Downloading ffmpeg... Please be patient :)")
		r = requests.get(url, allow_redirects=True)
		open(ffmpeg_command, 'wb').write(r.content)
	# https://github.com/DaWe35/SkyLive/raw/master/bin/ffmpeg.exe
		
	# check .SkyLive
	# if not in .SkyLive

m3u8 = get_youtube_m3u8(args.url)
logging.debug('Found m3u8 file: ' + m3u8)

ffresp = runBash(ffmpeg_command + ' -i ' + m3u8 + ' -c copy -hls_time 10 ' + recordFile)

if (ffresp != 0):
	logging.error('ffmpeg exited with code ' + str(ffresp))
