# Skylive

Live HLS video streams hosted on Skynet. SkyLive is under heavy development, streaming will be much more easyer in some weeks for non-tech users.

[Check out our roadmap](https://github.com/DaWe35/SkyLive/projects/3)

Explore streams: https://skylive.coolhd.hu

# How to start stream

- Download & extract the latest binaries from [releases](https://github.com/DaWe35/SkyLive/releases)

- Register a SkyLive account and scheule a new stream.

- Open command prompt and start the uploader with this command: `"C:\\path\to\stream_hls.exe" --record_folder "C:\\path\to\record_here"`

- Enter the generated stream token from https://SkyLive.coolhd.hu/studio

- Setup OBS (below) and start recording into the 'record_here' folder!

# How to finish the stream

- Click Stop recording in OBS

- Wait for every segment uploaded

- Close stream_hls.exe

- Open https://SkyLive.coolhd.hu/studio and `Finish` the stream (after this, it will be seekable)

# Setup OBS:

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

# Restream m3u8 from Youtube/Twitch

You can restream any public Youtube/Twitch stream to SkyLive without any transcoding or screen recording. The `stream_downloader.py` will download live stream on-demand, so you can re-upload it to SkyLive with `stream_hls.py`.

Usage: 

`cd C:\\path\to\SkyLive`

Start downloader:

`stream_downloader.exe --url https://www.youtube.com/watch?v=kG5araSvvLI`

Start uploader:

`"C:\\path\to\stream_hls.exe" --record_folder "C:\\path\to\team_restream_0"`

# Other tricks, notes

### Export HLS to mp4

After the stream, you can easily convert the .ts chunks into one mp4 file:

`ffmpeg -i "http://host/folder/input.m3u8" -bsf:a aac_adtstoasc -vcodec copy -c copy output.mp4`

### Optional: setup you own playlist server

- Running a you-own SkyLive portal needs PHP and MySQL. On Windows, I recommend wamp.net. The root directory of the website needs to be the `server` folder.
- Copy `server/config_default.php` to `server/config.php` and change the settings.
- Import skylive.sql into MySQL.