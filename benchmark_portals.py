import time
from siaskynet import Skynet
import os
import eventlet
eventlet.monkey_patch()

def time_to_str(value):
    if value == 20:
        time = 'timeout'
    elif value == 999:
        time = 'error'
    else:
        time = value
    return time

print('Starting benchmark')

f = open("2MBfile.txt", "w+")
text = ''
# generate 2MB text
for i in range(262144):
    text += 'SkyLive '
f.write(text)
f.close()

portals = [
    'https://skynet.coolhd.hu',
    'https://skynet.luxor.tech',
    'https://vault.lightspeedhosting.com',
    'https://skydrain.net',
    'https://skynethub.io',
    'https://sialoop.net',
    'https://skynet.utxo.no',
    'https://skynet.tutemwesi.com',
    'https://skyportal.xyz',
    'https://skynet.cloudloop.io',
    'https://siacdn.com',
    'https://siasky.net',
    'https://germany.siasky.net',
    'https://helsinki.siasky.net',
    'https://us-west.siasky.net/',
]
results = []

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
        try:
            with eventlet.Timeout(20):
                skylink = Skynet.upload_file('2MBfile.txt', opts)
            uploadtime = round(time.time() - start_time, 2)
            current_result = [uploadtime, portal]
            
        except eventlet.timeout.Timeout:
            current_result = [20, portal]
    except Exception as e:
        current_result = [999, portal]
    results.append(current_result)
    print('Benchmarking', str(len(results)) + '/' + str(len(portals)), 'portal. Current:', time_to_str(current_result[0]), current_result[1])

print('\nRESULTS:\n')
results.sort(key=lambda x: x[0])
for elem in results:
    time = time_to_str(elem[0])
    print(time, elem[1])

os.remove("2MBfile.txt")