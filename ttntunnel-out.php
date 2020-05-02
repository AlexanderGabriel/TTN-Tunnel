<?php
/*
    This file is part of TTNTunnel.

    TTNTunnel is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    TTNTunnel is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with TTNTunnel.  If not, see <http://www.gnu.org/licenses/>.
*/

if(file_exists("ttntunnel-out.ini") && is_readable("ttntunnel-out.ini")) {
    $config = parse_ini_file("ttntunnel-out.ini");
}
if(isset($config['logfile'])) {
    error_reporting(E_ALL | E_STRICT);
    ini_set("error_log", $config['logfile']);
}
$peer = $config['peer'];
$port = $config['port'];

if(isset($_POST['buf'])) {
    $buf = base64_decode($_POST['buf']);

    $socket = stream_socket_client("udp://$peer:$port", $errno, $errstr);
    $socket_name = stream_socket_get_name($socket, FALSE);

    if (!$socket) {
        error_log("ERROR: $errno - $errstr");
    } else {
        fwrite($socket, $buf);
        $response = fread($socket, 32768);
        $response = base64_encode($response);
        fclose($socket);
        echo $response;
    }
}