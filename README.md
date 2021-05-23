# DragonHack2021
A repository of our project, GIF Messenger, submitted at DragonHack 2021. It is available on this website: http://gifmessenger.online.

## A brief summary of folder and file structure
    
	.
    ├── PHP		Code that was used for backend.
    │   ├── api.php		Returns a value if a username is already taken.
    │   ├── mysql.sql		MySQL tables.
    │   ├── index.php		Empty file.
    │   └── redirect.php		Main page for communicating with Google API.
    ├── Posnetek.mp3		Recording for presentation.
    ├── Text.docx		Text for presentation.
    ├── backend			Backend code for WebSocket and Tenor API.
    │   ├── GifMessengerAPI.py		Main server file.
    │   ├── GifMessengerAPI_original.py		Some backup file.
    │   ├── client.html		Website for testing WebSocket connection.
    │   ├── input.mp4		File for testing mp4 to gif.
    │   ├── tenor.gif		Output gif.
    │   ├── tenorAPI.py		Program, that contacts Tenor API and returns GIFs.
    │   └── videoToGif.py		Program for translating mp4 to gif.
    └── frontend		The main folder of our project.
        ├── alert.mp3		Sound file for new messages.
        ├── chat.php		Page for chatting with other people.
        ├── chat_old.php		Backup file.
        ├── database		Folder containing database and Google login functions.
        │   ├── allUsers.php		Returns all logged-in users.
        │   ├── db.php		MySQL database connection.
        │   ├── exists.php		Checks, if the username exists.
        │   ├── logout.php		Webpage for logging out.
        │   ├── redirect.php		Main login with Google file.
        │   ├── src		GoogleClient files.
        │   └── vendor		GoogleClient files.
        ├── index.php		Landing page for new users.
        ├── ownGifHandler.js		JS file for creating own GIF.
        ├── setUsername.php		Webpage for setting username.
        ├── style		CSS styles.
        │   ├── AppleTea-jELql.otf		Chosen font.
        │   ├── chat.css		CSS for chat file.
        │   ├── landing.css		CSS for the landing page.
        │   ├── main.css		Main CSS.
        │   ├── ownGif.css		CSS for creating own GIF.
        │   └── setUsername.css		CSS for setting username.
        └── tmp		Some testing files.
            ├── count.gif
            ├── earth.gif
            └── jajca.gif


## Code
We used three languages for our project:
- **Python** for backend – Web sockets and GIF API,
- **JavaScript** and HTML for frontend – Webpage design and functionality,
- **PHP** for website backend – This was used for communicating with database and Google login API.

