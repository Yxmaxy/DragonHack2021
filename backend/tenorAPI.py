import json
import requests

apikey = "H75LSQOFMAKG"  # Key for Tenor API

# This function searches for GIFS by specific keywords and returns specified number of GIFS and their metadata.
def searchForGIFS(numOfGIFs, keywords):
    gifs = []

    for search_term in keywords:
        #print(search_term)

        # get the top noOfGIFs GIFs for the search term
        r = requests.get("https://g.tenor.com/v1/search?q=%s&key=%s&limit=%s" % (search_term, apikey, numOfGIFs))

        if r.status_code == 200:
            # load the GIFs using the urls for the smaller GIF sizes
            top_gifs = json.loads(r.content)
            for x in range(int(numOfGIFs)):
                tinyGif = top_gifs["results"][x]["media"][0]["tinygif"]
                bigGif = top_gifs["results"][x]["media"][0]["gif"]
                gifs.append([tinyGif, bigGif])
        else:
            top_gifs = None
    return json.dumps(gifs)
