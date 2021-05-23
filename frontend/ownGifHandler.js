// to je za on gif wrapper ki je za delat svoje captione
function changeText(value, which) {
    document.getElementById(which + "TextID").textContent = value;
}

function changeFont(value, which) {
    document.getElementById(which + "TextID").style.fontSize = value + "px";
}

function changeIsBold(value, which) {
    if (value)
        document.getElementById(which + "TextID").style.fontWeight = "bold";
    else
        document.getElementById(which + "TextID").style.fontWeight = "unset";
}

function changeColor(value, which) {
    document.getElementById(which + "TextID").style.color = value;
}

function doggieSend() {
    Send("https://media.tenor.com/images/d7afbeb5c3b3efc48a86eb2c3450ceb8/tenor.gif", document.getElementById("upperTextInput").value, document.getElementById("lowerTextInput").value)
    resetDoggie();
}

function resetDoggie() {
    document.getElementById("upperTextInput").value = "";
    document.getElementById("lowerTextInput").value = "";
    document.getElementById("upperTextID").innerText = "";
    document.getElementById("lowerTextID").innerText = "";
    
    document.getElementById("makeOwnGifWrapper").style.display = "none";
}