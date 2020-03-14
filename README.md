# Skylive
Live HLS video streams hosted on Skynet

![How SkyLive works](https://raw.githubusercontent.com/DaWe35/Skylive/master/docs/how%20it%20works.jpg)

# Install

- Install python 3.7+

- `cd Skylive && pip install -r requirements.txt`

- Setup a lightweight PHP server for storing the file name only. Upload /server/ contents to a webserver, and `chmod 777 streams`

- Download & config OBS

    - Settings -> Output -> Recording

        - Recording path: `.../Skylive/record_here`

        - Recording format: `m3u8`

        - Custom muxer settings: `hls_time=12 hls_list_size=0`

    ![OBS settings](https://raw.githubusercontent.com/DaWe35/Skylive/master/docs/obs_settings.jpg)

    - Settings -> Advanced -> Recording

        - Filename Formatting: `live` - that's enought (and important!)

- Start uploader.py: `python uploader.py`

- Start OBS recording! Enjoy!