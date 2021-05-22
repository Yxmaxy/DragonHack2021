# set the apikey and limit
import json
import requests
import sys

apikey = "H75LSQOFMAKG"  # Key za Tenor API


def searchForGIFS(numOfGIFs, keywords):
    gifs = []
    for search_term in keywords:
        #print(search_term)

        # get the top noOfGIFs GIFs for the search term
        r = requests.get(
            "https://g.tenor.com/v1/search?q=%s&key=%s&limit=%s" % (search_term, apikey, numOfGIFs))

        if r.status_code == 200:
            # load the GIFs using the urls for the smaller GIF sizes
            top_gifs = json.loads(r.content)
            for x in range(int(numOfGIFs)):
                tinyGif = top_gifs["results"][x]["media"][0]["tinygif"]
                bigGif = top_gifs["results"][x]["media"][0]["gif"]
                gifs.append([tinyGif, bigGif])
        else:
            top_gifs = None
        # continue a similar pattern until the user makes a selection or starts a new search.
    return json.dumps(gifs)
"""
sys.argv = [2, "Cat"]
if len(sys.argv) >= 2:
    ret = searchForGIFS(sys.argv[0], sys.argv[1:])
    print(ret)
else:
    print("Napačna raba API-ja.\nPravilna raba: tenorAPI numOfRequestedGifs keyword,...")
"""