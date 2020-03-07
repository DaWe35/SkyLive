import cv2
 
def save_webcam(outPath,fps,mirror=False):
    # Capturing video from webcam:
    cap = cv2.VideoCapture(1, cv2.CAP_DSHOW)
 
    currentFrame = 0
 
    cap.set(cv2.CAP_PROP_FRAME_WIDTH,1280)
    cap.set(cv2.CAP_PROP_FRAME_HEIGHT,720)
 
    # Define the codec and create VideoWriter object
    fourcc = cv2.VideoWriter_fourcc(*"h256")
    out = cv2.VideoWriter(outPath, fourcc, fps, (1280,720))
 
    while (cap.isOpened()):
 
        # Capture frame-by-frame
        ret, frame = cap.read()
 
        if ret == True:
            if mirror == True:
                # Mirror the output video frame
                frame = cv2.flip(frame, 1)
            # Saves for video
            out.write(frame)
 
            # Display the resulting frame
            cv2.imshow('frame', frame)
        else:
            break
 
        if cv2.waitKey(1) & 0xFF == ord('q'):  # if 'q' is pressed then quit
            break
 
        # To stop duplicate images
        currentFrame += 1
 
    # When everything done, release the capture
    cap.release()
    out.release()
    cv2.destroyAllWindows()
 
def main():
    save_webcam('output.mp4', 30.0,mirror=False)
 
if __name__ == '__main__':
    main()