<?php
error_reporting(0);
ini_set('display_errors', 0);

/* Get the port for the WWW service. */
$service_port = 1234;

/* Get the IP address for the target host. */
$address = 'localhost';

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() error: " . socket_strerror(socket_last_error()) . "\n";
}

$result = socket_connect($socket, $address, $service_port);
if ($result === false) {
    echo "socket_connect() error: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
}

socket_write($socket, getenv('username'), strlen(''));

while ($out = socket_read($socket, 2048)) {
    echo $out;
}

echo "Closing socket...";
socket_close($socket);
echo "OK.\n\n";