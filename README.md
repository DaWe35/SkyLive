# Skylive
Live video streams on Skynet

# Install

- Install python 3.7+

- `cd Skylive && pip install -r requirements.txt`

- Setup a lightweight PHP server for storing the file name only. Upload /server/ contents to a webserver, and `chmod 777 streams`

- Download & config OBS [image from docs folder]

    - Settings -> Output -> Recording

        Recording path: `...../Skylive/record_here`

        Recording format: `m3u8`

        Custom muxer settings: `hls_time=12 hls_list_size=0`

    - Settings -> Advanced -> Recording

        Filename Formatting: `live` - that's enought (and important!)

- Start uploader.py: `python uploader.py`

- Start OBS recording! Enjoy!