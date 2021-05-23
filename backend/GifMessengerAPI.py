import json
import ssl
import tenorAPI
from simple_websocket_server import WebSocketServer, WebSocket


class SimpleChat(WebSocket):

    # Function handle handles incomming socket communication
    def handle(self):
        # Decodes JSON string into object parameters.
        parameters = json.loads(self.data)

        # If type of message is client hello!, which means client identifies itself with username, current name
        # (key of dictionary of websockets) is replaced with users username, which is later used for communication
        # and queries of online users.
        if parameters["type"] == "client hello!":
            clients[parameters["username"]] = clients[str(self)]
            clients.pop(str(self))
            self.send_message("{ \"type\": \"server hello!\" }")
            # Sends a list of online users to all connected users
            for client_name, client_sock in clients.items():
                clients_usernames = [x for x in clients.keys()]
                client_sock.send_message(
                    "{ \"type\": \"who online\", \"data\": " + json.dumps(clients_usernames) + " }")

        # If type of message is request, an end user has requested some GIFS for display to chose from. User defines
        # how many GIFS he/she wants to get and categories/keywords for searching GIFS. List of GIFS is send back
        # to whom requested them.
        if parameters["type"] == "request":
            self.send_message(
                "{ \"type\": \"request return\", \"data\": " + tenorAPI.searchForGIFS(parameters["numOfGifs"],
                                                                                      parameters["keywords"]) + " }")

        # If type of message is who online, a list of online users is send back to whom requested them.
        # Although this functionality is mostly used when a new user comes online or goes offline it is nice to
        # have an option for manual request of online users at any time.
        if parameters["type"] == "who online":
            clients_usernames = clients.keys()
            self.send_message("{ \"type\": \"who online\", \"data\": " + json.dumps(clients_usernames) + " }")

        # If type of message is send, a sender (self) is sendig a GIF image to a receiver which is defined by
        # username in JSON object containing details of GIF image sent.
        if parameters["type"] == "send":
            # In case of error (sending GIF to offline user or not registered user) sender is informed that user is
            # not found or available.
            try:
                clients[parameters["username"]].send_message(self.data)
                self.send_message("{ \"type\": \"send\", \"status\": \"OK\" }")
            except:
                self.send_message(
                    "{ \"type\": \"error\", \"status\": \"User not found\", \"username\": \"" + parameters[
                        "username"] + "\" }")

    # Function connected is executed when a new user connects to the server (comes online). Socket that is used for
    # communication with this specific user get a temporary unique name, which is later changed to users username.
    def connected(self):
        print(self.address, 'connected')
        clients[str(self)] = self

    # Function handle_close is executed when a user disconnects from server. He/She is removed from list of online
    # users and that list is then send to all users, so they know that user is not reachable anymore. Socket is removed.
    def handle_close(self):
        # Remove user from online users.
        for client_key, client_socket in clients.items():
            if self == client_socket:
                clients.pop(client_key)
                break

        print(self.address, 'closed')
        # Send list of online userst to all online users
        for client_name, client_sock in clients.items():
            clients_usernames = [x for x in clients.keys()]
            client_sock.send_message("{ \"type\": \"who online\", \"data\": " + json.dumps(clients_usernames) + " }")


# List of online users and their WebSockets is initialized
clients = dict()

# WebSocketServer is set up and left running. Accepting and ending connections is done automatically.
#server = WebSocketServer('', 81, SimpleChat, "public.pem", "private.pem", ssl.PROTOCOL_TLSv1_2)
server = WebSocketServer('', 81, SimpleChat)
server.serve_forever()
