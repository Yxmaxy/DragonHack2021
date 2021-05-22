import json

from simple_websocket_server import WebSocketServer, WebSocket

import tenorAPI


class SimpleChat(WebSocket):
    def handle(self):
        parameters = json.loads(self.data)

        if parameters["type"] == "client hello!":
            clients[parameters["username"]] = clients[str(self)]
            clients.pop(str(self))
            self.send_message("{ \"type\": \"server hello!\" }")

        if parameters["type"] == "request":
            self.send_message("{ \"type\": \"request return\", \"data\": " + tenorAPI.searchForGIFS(parameters["numOfGifs"], parameters["keywords"]) + " }")
            # Get GIFS and send them back to whom requested them.

        if parameters["type"] == "who online":
            clients_usernames = clients.keys()
            self.send_message("{ \"type\": \"who online\", \"data\": " + json.dumps(clients_usernames) + " }")

        if parameters["type"] == "send":
            try:
                clients[parameters["username"]].send_message(self.data)
                self.send_message("{ \"type\": \"send\", \"status\": \"OK\" }")
            except:
                self.send_message(
                    "{ \"type\": \"error\", \"status\": \"User not found\", \"username\": \"" + parameters["username"] + "\" }")

    def connected(self):
        print(self.address, 'connected')
        for client in clients.values():
            client.send_message(self.address[0] + u' - connected')
        clients[str(self)] = self

    def handle_close(self):
        # Odstanimo uporabnika izmed prijavljenih
        for client_key, client_socket in clients.items():
            if self == client_socket:
                clients.pop(client_key)
                break

        print(self.address, 'closed')
        for client in clients.values():
            client.send_message(self.address[0] + u' - disconnected')


clients = dict()

server = WebSocketServer('', 80, SimpleChat)
server.serve_forever()
