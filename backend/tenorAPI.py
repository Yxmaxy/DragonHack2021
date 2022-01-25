import json
import requests
import random

apikey = "H75LSQOFMAKG"  # Key for Tenor API


# This function searches for GIFS by specific keywords and returns specified number of GIFS and their metadata.
def searchForGIFS(numOfGIFs, keywords):
    gifs = []

    # For each keyword find relevant GIFS on Tenor using their API.
    for keyword in keywords:

        # get the top noOfGIFs GIFs related to keyword
        r = requests.get("https://g.tenor.com/v1/search?q=%s&key=%s&limit=%s" % (keyword, apikey, numOfGIFs))

        # If query was made successfully retrieve usefull information about GIFs.
        if r.status_code == 200:
            top_gifs = json.loads(r.content)
            # For each retrieven GIF get construct tinyGif (used for previews) and bigGif, a higher resolution GIF for
            # sending. Append them into an array so both can be used as one.
            for x in range(int(numOfGIFs)):
                tinyGif = top_gifs["results"][x]["media"][0]["tinygif"]
                bigGif = top_gifs["results"][x]["media"][0]["gif"]
                gifs.append([tinyGif, bigGif])
        else:
            top_gifs = None
    # Return requested GIFs in JSON format.
    random.shuffle(gifs)
    return json.dumps(gifs)
