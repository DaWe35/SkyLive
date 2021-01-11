# Quick note for those who would care, due to the lack of skyid
# integration, this script actually is missing the component of 
# the userid and posting under skyid, but that can be absolved later

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
import re
import ffmpeg 
import blurhash
import time

# Creates a directory if not exist
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

def getYoutubeMP4(video_url):
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

def makeVideoJson(downloadUrl, iterator):
	print("-------------------")
	print("starting download of ", downloadUrl)
	# Downloads the file in not wanted format and gets metadata
	url = getYoutubeMP4(downloadUrl)
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
	# Now find out a bunch of info
	probe = ffmpeg.probe(url[0]+".mp4")
	video_streams = [stream for stream in probe["streams"] if stream["codec_type"] == "video"]
	print(video_streams[0])
	# content = {
	# 	
	# 	"id": url[0], 
	# 	"title": url[1], 
	# 	"description": url[2], 
	# 	"thumbnail": thumbnailSkylink, 
	# 	"video": videoSkylink
	# }
	videoTitle = "mp4+" + str(video_streams[0]["width"]) + "x" + str(video_streams[0]["height"])
	content = {
		"$type": "post",
		"id": iterator,
		"content": {
			"mediaDuration": video_streams[0]["duration_ts"],
			"video": {
				videoTitle: "sia://" + videoSkylink,
			},
			"image": {
				"jpeg": "sia://" + thumbnailSkylink,
			},
			"text": url[1],
			"description": url[2],
			"aspectRatio": video_streams[0]["width"] / video_streams[0]["height"],
			"blurHash": blurhash.encode('thumbnail.jpg', x_components=4, y_components=3),
		},
		"ts": int(time.time()*1000),
	}
	# Now dump it to json
	metadataJson = json.loads(json.dumps(content))
	print("json object:\n", metadataJson)
	with open((str(url[0]) + ".json"), 'w') as outfile:
		json.dump(metadataJson, outfile)
	# Uploads it to skynet
	finalSkylink = uploadToSkynet(skynetUrl, (str(url[0]) + ".json"))
	os.remove((str(url[0]) + ".json"))
	os.remove((str(url[0]) + ".mp4"))
	os.remove("thumbnail.jpg")
	return finalSkylink

# Grabs all videos from channel
def grabLinks(channelUrl):
	print("grabbing from this channel:", channelUrl)
	# uses youtubedl to grab the things
	ydl = youtube_dl.YoutubeDL({'outtmpl': '%(id)s.%(ext)s'})
	try:
		with ydl:
			result = ydl.extract_info(
				channelUrl,
				download=False # We just want to extract the info
			)
	except:
		print("youtube_dl didn't work")

	#make list
	videoList = []
	for i in result["entries"]:
		videoList.append(i["id"])
	return videoList


def main(passedUrl):
	# Now check if it's a single video or a channel 
	# Checks format of link
	# if in /c/ it takes a bit more work to get the id
	if "youtube.com/c/" not in passedUrl and "youtube.com/channel/" not in passedUrl:
		# if it's just one video you pass it
		return makeVideoJson(passedUrl)
	else: 
		# Append /videos
		if "video" in passedUrl:
			linkList = grabLinks(passedUrl, 0)
		else: 
			linkList = grabLinks(passedUrl + "/videos")
	# If it hasn't returned that means linkList has been defined
	# Anad we can go through the list and re-upload them 
	skyLinkList = []
	counter = 0
	for i in linkList:
		skyLinkList.append(makeVideoJson(i, counter))
		if counter == 15:
			counter = 0
		else:
			counter += 1
	
	# Now return
	return skyLinkList


if __name__ == "__main__":
	parser = argparse.ArgumentParser(description="Restream Youtube/Twitch live to SkyLive")
	parser.add_argument('--url', help='Video url (for example https://www.youtube.com/watch?v=ASD123', required=True)
	args = parser.parse_args()
	if (args.url):
		finalSkylink = main(args.url)
		if isinstance(finalSkylink, list) == False:
			print("final skylink: sia://" + finalSkylink)
		else:
			for i in finalSkylink:
				print(i)

