import os
from datetime import datetime as dt
from datetime import timedelta as td


def runBash(command):
	os.system(command)

def crop(start, end, input, output):
	str = "ffmpeg -ss " + start + " -i " + input + " -to " + end + " -c copy " + output
	print(str)
	runBash(str)



fmtString = '%H:%M:%S'
increment = td(0,10)

fromTime = 0

i = 0
for count in range(20):
    vidCnt = "{:02d}".format(i)
    fromStr = str(fromTime)
    endStr = str(fromTime + 10.099989)
    crop(fromStr, endStr, "noise.mov", "noisecopy/" + vidCnt + ".mp4")
    fromTime += 10.099989
    i += 1
exit(0)