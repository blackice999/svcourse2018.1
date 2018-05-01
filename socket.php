<?php
include "autoload.php";
use Amp\Loop;
use Amp\Socket\ServerSocket;
use function Amp\asyncCall;
use Course\Api\Exceptions\ApiException;
use Course\Api\Model\Events;

//Source: https://github.com/amphp/getting-started/blob/master/3-broadcasting/server.php
Loop::run(function () {

    $server = new class
    {
        private $uri = "tcp://127.0.0.1:1337";
        // We use a property to store a map of $clientAddr => $client
        private $clients = [];
        private $roomId;

        public function listen()
        {
            asyncCall(function () {
                $server = Amp\Socket\listen($this->uri);
                print "Listening on " . $server->getAddress() . " ..." . PHP_EOL;
                while ($socket = yield $server->accept()) {
                    $this->handleClient($socket);
                }
            });
        }

        public function repeat()
        {
            Loop::repeat($msInterval = 2000, function ($watcherId) {
                if (\Course\Api\Model\RoomUsersModel::isThereAtleastOneUser($this->roomId)) {
                    $this->broadcast("Sending message to all clients");
                    echo "tick\n";
                } else {
                    Loop::cancel($watcherId);
                }
            });
        }

        private function handleClient(ServerSocket $socket)
        {
            asyncCall(function () use ($socket) {
                $fullCommand = '';
                $remoteAddr = $socket->getRemoteAddress();
                while (null !== $chunk = yield $socket->read()) {
                    $fullCommand .= $chunk;
                    if (strpos($chunk, "\n") !== false) {
                        try {
                            $response = Events::getEvent($fullCommand);
                            if ($response) {
                                $this->roomId = $response->roomId;
                                // We print a message on the server and send a message to each client
                                print "Accepted new client: {$remoteAddr}" . PHP_EOL;
                                $this->broadcast($remoteAddr . " joined the chat." . PHP_EOL);
                                // We only insert the client afterwards, so it doesn't get its own join message
                                $this->clients[$remoteAddr] = $socket;

                                yield $socket->write($response);
                            }
                            $fullCommand = '';
                        } catch (ApiException $e) {
                            yield $socket->write(json_encode(['errorMessage' => $e->getMessage()]));
                        }
                    }
                }

                // We remove the client again once it disconnected.
                // It's important, otherwise we'll leak memory.
                unset($this->clients[$remoteAddr]);
                // Inform other clients that that client disconnected and also print it in the server.
                print "Client disconnected: {$remoteAddr}" . PHP_EOL;
                $this->broadcast($remoteAddr . " left the chat." . PHP_EOL);
            });
        }

        private function broadcast(string $message)
        {
            foreach ($this->clients as $client) {
                // We don't yield the promise returned from $client->write() here as we don't care about
                // other clients disconnecting and thus the write failing.
                $client->write($message);
            }
        }
    };

    $server->listen();
    $server->repeat();
});