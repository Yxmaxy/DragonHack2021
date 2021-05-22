from PIL import Image, ImageDraw, ImageSequence, ImageFont
import io

im = Image.open('tenor.gif')

# A list of the frames to be outputted
frames = []
# Loop over each frame in the animated image
for frame in ImageSequence.Iterator(im):
    # Draw the text on the frame
    d = ImageDraw.Draw(frame)
    d.text((im.width / 4, im.height - 60), "Ne vem js zej...", font=ImageFont.truetype("arial.ttf", 50))
    del d

    b = io.BytesIO()
    frame.save(b, format="GIF")
    frame = Image.open(b)

    # Then append the single frame image to a list of frames
    frames.append(frame)
# Save the frames as a new image
frames[0].save('out.gif', save_all=True, append_images=frames[1:])
