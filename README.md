# Skylive
Live HLS video streams hosted on Skynet

![How SkyLive works](https://raw.githubusercontent.com/DaWe35/Skylive/master/docs/how%20it%20works.jpg)

Demo: https://siasky.net/AAA5tBYYnuMhMl1qRV-bphSTyPsZ9JlAtKkJ21gJhSEu2g/index.html

First live: https://siasky.net/EACSRCJLMtS-P6tpGNr1ZMCGFBbWXKoNKTHV_3l81jLE1Q

Download first live in mp4: https://siasky.net/CADUOqGUR0us09iZrSAAq6Qj5MrI2GrFqtdEiUKwkyZllA

# Setup

- Install python 3.7+

- `cd Skylive && pip install -r requirements.txt`

- Setup a lightweight PHP server for storing the file names only. Upload /server/ contents to a webserver, and `cd public_server_folder & chmod 777 streams`

- Download & config OBS

  TL;DR: use the settings what you see below
  
  Currently, there is a [bug](https://github.com/obsproject/obs-studio/issues/2500) in OBS, so you can't record more than 14 chunks into m3u8 (tested on Win10 only). It is recommended to try it out on your system, maybe you can record more than 16 chunks (please write your experience [in a comment](https://github.com/obsproject/obs-studio/issues/2500). But while the issue is not fixed, we need to use the custom ffmpeg output:
  
  ![OBS settings](https://raw.githubusercontent.com/DaWe35/Skylive/master/docs/obs_settings.jpg)

    - Output mode: *Advanced*
    
    - Type: *Custom output (FFmpeg)*
    
      *Without FFmpeg, the recording may be stuck after 14 or 33 chunks*
    
    - File path: *.../Skylive/record_here*
    
      *This is important, the python script will search for new files in 'record_here'*
    
    - Container format: *hls*
    
      *It records small .ts chunks, compatible with HTTP Live Streaming*
    
    - Muxer settings: *hls_time=10*
    
      *10 seconds chunks are acceptable - too large cause more delay, too small causes upload congestion.*
      
    - Video Encoder Settings: *preset=veryfast*
    
      *Saves the CPU. Available presets: ultrafast, superfast, veryfast, faster, fast, medium, slow, slower, veryslow, placebo*
      
      *Try out some, but be careful: if you see this warning, viewers will experience buffering*
      
      ![OBS recording overloaded](https://raw.githubusercontent.com/DaWe35/Skylive/master/docs/overload.jpg)
    
    - Under Settings -> Advanced -> Recording, change the Filename Formatting to `live` - that's enough (and important!)
    
      ![OBS filename](https://raw.githubusercontent.com/DaWe35/Skylive/master/docs/obs_filename.jpg)

# Start

- Start stream_hls.py: `python stream_hls.py`

- Start OBS recording! Enjoy!

# Stop

- Stop OBS

- stream_hls.py uploads only the *current chunk - 1*th file. So if your last chunk is `live44.ts`, only `live43.ts` has been uploaded. You need to create an empty file `live45.ts`, if you want to upload the 44th chunk.

- After each chunk was uploaded, close `stream_hls.py`

- If you want to make the whole playlist replayable, you need to insert `#EXT-X-ENDLIST` to the end of the playlist. Open the `streams` folder on your webserver, and paste it to the current stream file (filename depends on configured *streamid*).

## Export

After the stream, you can easily convert the .ts chunks into one mp4 file:

`ffmpeg -i "http://host/folder/input.m3u8" -bsf:a aac_adtstoasc -vcodec copy -c copy output.mp4`
