const mobileThreshold = 600;
var menuOpen = false;

// called on window load and resize to check if page is mobile
function handleMobile() {
    const menuButton = document.querySelector("header").querySelector("a");
    const usersHeader = document.getElementById("users").querySelector("h3");

    const users = document.getElementById("users");

    // Website is in mobile view
    if (window.innerWidth <= mobileThreshold) {
        menuButton.innerText = "Menu";
        menuButton.href = "javascript:menuClick();";
        usersHeader.style.display = "none";
    }
    // Not in mobile
    else {
        menuButton.innerText = "Log out";
        menuButton.href = "./database/logout.php";
        usersHeader.innerText = "Users";

        if (menuOpen) {
            menuClick();
        }

        users.style.removeProperty("display");
        usersHeader.style.removeProperty("display")
    }
}

// shows menu
function menuClick() {
    const chat = document.getElementById("chat");
    const users = document.getElementById("users");
    const logOut = users.querySelector("a");

    menuOpen = !menuOpen;

    if (menuOpen) {
        logOut.style.display = "block";
        users.style.display = "flex";
        chat.style.display = "none";
    } else {
        logOut.style.display = "none";
        users.style.display = "none";
        chat.style.display = "flex";
    }

}

// checking window resize
window.onresize = () => {
    handleMobile();
}