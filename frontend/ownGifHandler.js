// to je za on gif wrapper ki je za delat svoje captione
function changeText(value, which) {
    document.getElementById(which + "Text").textContent = value;
}

function changeFont(value, which) {
    document.getElementById(which + "Text").style.fontSize = value + "px";
}

function changeIsBold(value, which) {
    if (value)
        document.getElementById(which + "Text").style.fontWeight = "bold";
    else
        document.getElementById(which + "Text").style.fontWeight = "unset";
}

function changeColor(value, which) {
    document.getElementById(which + "Text").style.color = value;
}

function cancelOwnGif(element) {
    element.style.display='none';
}