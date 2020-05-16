import requests

# This script converts any m3u8 file into the new MySQL format
token = ''
m3u8_source = ''

def share(length, link, is_first_chunk):
	global token
	post = {
		'token': token,
		'url': link,
		'length': length,
		'is_first_chunk': is_first_chunk
		}
	x = requests.post('http://skylive.local/write', data = post)
	if (x.text != 'ok'):
		print(x.text)
		exit(0)

x = requests.get(m3u8_source)
lines = x.text.splitlines()

length = 0
link = ''
is_first_chunk = 1

for line in lines:
	if line.startswith('#EXT-X-DISCONTINUITY'):
		is_first_chunk = 1
	if line.startswith('#EXTINF:'):
		length = line.replace('#EXTINF:', '')
		length = length.replace(',', '')
	elif line.startswith('https://siasky.net/'):
		link = line.replace('https://siasky.net/', '')
		share(length, link, is_first_chunk)
		is_first_chunk = 0

print('Done')