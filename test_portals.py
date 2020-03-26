import time
from siaskynet import Skynet
import os

f = open("2MBfile.txt", "w+")
text = ''
# generate 2MB text
for i in range(262144):
    text += 'SkyLive '
f.write(text)
f.close()

portals = [
    'https://skynet.luxor.tech',
    'https://siacdn.com',
    'https://vault.lightspeedhosting.com',
    'https://skydrain.net',
    'https://skynethub.io',
    'https://sialoop.net',
    'https://skynet.utxo.no',
    'https://skynet.tutemwesi.com',
    '',
    'https://siasky.net',
    'https://siasky.net',
    'https://siasky.net',
    'https://siasky.net',
    'https://siasky.net',
    'https://siasky.net',
    'https://siasky.net',
]

for portal in portals:
    start_time = time.time()
    opts = type('obj', (object,), {
        'portal_url': portal,
        'portal_upload_path': 'skynet/skyfile',
        'portal_file_fieldname': 'file',
        'portal_directory_file_fieldname': 'files[]',
        'custom_filename': ''
    })
    try:
        skylink = Skynet.upload_file('2MBfile.txt', opts)
        uploadtime = round(time.time() - start_time, 2)
        print(uploadtime, portal)
    except Exception as e:
        print('error', portal)


os.remove("2MBfile.txt")