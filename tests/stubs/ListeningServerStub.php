<?php

/**
 * [Not used for the moment]
 *
 * http://stackoverflow.com/questions/9136366/how-do-i-unit-test-socket-code-with-phpunit
 */
class ListeningServerStub
{
    protected $client;

    public function listen()
    {
        $sock = socket_create(AF_INET, SOCK_STREAM, 0);

        // Bind the socket to an address/port
        socket_bind($sock, 'localhost', 0) or throw new RuntimeException('Could not bind to address');

        // Start listening for connections
        socket_listen($sock);

        // Accept incoming requests and handle them as child processes.
        $this->client = socket_accept($sock);
    }

    public function read()
    {
        // Read the input from the client chunked 1024 bytes
        $input = socket_read($client, 1024);

        return $input;
    }
}
