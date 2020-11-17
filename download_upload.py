import youtube_dl
import json
import functools
print = functools.partial(print, flush=True)
import argparse
import atexit
import os
import stat
import shutil
import logging
import platform
import requests
import urllib

# Not really sure
def touchDir(dir):
	if (os.path.isdir(dir)):
		return False
	else:
		os.mkdir(dir)
		return True

# Removes a dir
def rmdir(dir):
	if os.path.isdir(dir):
		shutil.rmtree(dir)

# Runs command in bash
def runBash(command):
	return os.system(command)

# I stole this hook from here: https://www.bogotobogo.com/VideoStreaming/YouTube/youtube-dl-embedding.php
def my_hook(d):
	if d['status'] == 'finished':
		print('Done downloading, now converting ...')

def get_youtube_m3u8(video_url):
	# Actually downloads it
	ydl_opts = {
		'format': 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/mp4',       
		'outtmpl': '%(id)s',        
		'noplaylist' : True,        
		'progress_hooks': [my_hook],  
	}

	with youtube_dl.YoutubeDL(ydl_opts) as ydl:
		ydl.download([video_url])

	ydl = youtube_dl.YoutubeDL({'outtmpl': '%(id)s%(ext)s'})
	# Grabs info 
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
	# print(video)
	array = [video['id'], video['title'], video['description'], video['thumbnails'][-1]]
	return array

def uploadToSkynet(url, loc):
	files = {'file': open(loc, 'rb')}
	response = requests.post(url, files=files)
	return response.json()["skylink"]

def main(passedUrl):
	print("starting")
	# Downloads the file in not wanted format and gets metadata
	url = get_youtube_m3u8(passedUrl)
	print("done downloading video")
	# Downloads thumbnail
	thumbnailUrl = json.loads(json.dumps(url[3]))["url"]
	print(thumbnailUrl)
	urllib.request.urlretrieve(thumbnailUrl, "thumbnail.jpg")
	# Uploads thumbnail & video to skynet
	videoName = url[0] + ".mp4"
	skynetUrl = "https://siasky.net/skynet/skyfile"
	videoSkylink = uploadToSkynet(skynetUrl, videoName)
	thumbnailSkylink = uploadToSkynet(skynetUrl, "thumbnail.jpg")
	print("video skylink sia://" + videoSkylink)
	print("thumnail skylink sia://" + thumbnailSkylink)
	# Makes json file of thumnail link, description, title, date created, 
	# id, and link to video file
	print(url[0], " ", type(url[0]))
	metadata = {"id": url[0], "title": url[1], "description": url[2], "thumbnail": thumbnailSkylink, "video": videoSkylink}
	metadataJson = json.loads(json.dumps(metadata))
	print("json object:\n", metadataJson)
	with open((str(url[0]) + ".json"), 'w') as outfile:
		json.dump(metadataJson, outfile)
	# Uploads it to skynet
	finalSkylink = uploadToSkynet(skynetUrl, (str(url[0]) + ".json"))
	os.remove((str(url[0]) + ".json"))
	os.remove((str(url[0]) + ".mp4"))
	os.remove("thumbnail.jpg")
	return finalSkylink

if __name__ == "__main__":
	parser = argparse.ArgumentParser(description="Restream Youtube/Twitch live to SkyLive")
	parser.add_argument('--url', help='Video url (for example https://www.youtube.com/watch?v=ASD123', required=True)
	args = parser.parse_args()
	if(args.url):
		finalSkylink = main(args.url)
		print("final skylink: sia://" + finalSkylink)