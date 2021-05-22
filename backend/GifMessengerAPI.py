import json

from simple_websocket_server import WebSocketServer, WebSocket

import tenorAPI


class SimpleChat(WebSocket):
    def handle(self):
        parameters = json.loads(self.data)

        if parameters["type"] == "client hello!":
            print("CLIENT HELLO!: " + parameters["username"])
            clients[parameters["username"]] = clients[str(self)]
            clients.pop(str(self))
            self.send_message("{ \"type\": \"server hello!\" }")

        if parameters["type"] == "request":
            print("REQUEST: ")
            self.send_message("{ \"type\": \"request return\", \"data\": " + tenorAPI.searchForGIFS(parameters["numOfGifs"], parameters["keywords"]) + " }")
            # Get GIFS and send them back to whom requested them.

        if parameters["type"] == "who online":
            print("WHO ONLINE: ")
            clients_usernames = clients.keys()
            self.send_message("{ \"type\": \"who online\", \"data\": " + json.dumps(clients_usernames) + " }")

        if parameters["type"] == "send":
            print("SEND: ")
            try:
                clients[parameters["username"]].send_message(self.data)
                self.send_message("{ \"type\": \"send\", \"status\": \"OK\" }")
            except:
                self.send_message(
                    "{ \"type\": \"error\", \"status\": \"User not found\", \"username\": \"" + parameters["username"] + "\" }")

    def connected(self):
        print(self.address, 'connected')
        clients[str(self)] = self
        # Send who online to all users
        for client_name, client_sock in clients.items():
            clients_usernames = [x for x in clients.keys()]
            client_sock.send_message("{ \"type\": \"who online\", \"data\": " + json.dumps(clients_usernames) + " }")

    def handle_close(self):
        # Odstanimo uporabnika izmed prijavljenih
        for client_key, client_socket in clients.items():
            if self == client_socket:
                clients.pop(client_key)
                break

        print(self.address, 'closed')
        # Send who online to all users
        for client_name, client_sock in clients.items():
            clients_usernames = [x for x in clients.keys()]
            client_sock.send_message("{ \"type\": \"who online\", \"data\": " + json.dumps(clients_usernames) + " }")


clients = dict()

server = WebSocketServer('', 81, SimpleChat)
server.serve_forever()
