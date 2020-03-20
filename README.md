# Skylive
Live HLS video streams hosted on Skynet

![How SkyLive works](https://raw.githubusercontent.com/DaWe35/Skylive/master/docs/how%20it%20works.jpg)

Demo: https://siasky.net/AAA5tBYYnuMhMl1qRV-bphSTyPsZ9JlAtKkJ21gJhSEu2g/index.html

First live: https://siasky.net/EACSRCJLMtS-P6tpGNr1ZMCGFBbWXKoNKTHV_3l81jLE1Q

Download first live in mp4: https://siasky.net/CADUOqGUR0us09iZrSAAq6Qj5MrI2GrFqtdEiUKwkyZllA

``` diff
- Currently, there is a bug in OBS, so you can't record more than 16 chunks into m3u8 (tested on win10).
- With some trick, you can stream ~15 minutes, but when they fix the issue, of course it will be unlimited.
+ Tricks: use OBS 24.0.3 (for some reason, it works until 33 chunk recorded) with "hls_time=30".
```

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

# Start

- Start uploader.py: `python uploader.py`

- Start OBS recording! Enjoy!

# Stop

- Stop OBS

- Uploader.py uploads only the *current chunk - 1*th file. So if your last chunk is `live44.ts`, only `live43.ts` has been uploaded. You need to create an empty file `live45.ts`, if you want to upload the 44th chunk.

- Afte all chunk are uploaded, close `uploader.py`

- If you want to make the whole playlist replayable, you need to insert `#EXT-X-ENDLIST` to the end of the playlist. Open the `streams` folder on your webserver, and paste it to the current stream file (filename depends on configured *streamid*).

## Export

After the stream, you can easily convert the .ts chunks into one mp4 file:

`ffmpeg -i "http://host/folder/input.m3u8" -bsf:a aac_adtstoasc -vcodec copy -c copy output.mp4`