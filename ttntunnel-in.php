#!/usr/bin/php
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

if(file_exists("ttntunnel-in.ini") && is_readable("ttntunnel-in.ini")) {
    $config = parse_ini_file("ttntunnel-in.ini");
}
if(isset($config['logfile'])) {
    error_reporting(E_ALL | E_STRICT);
    ini_set("error_log", $config['logfile']);
}
$url = $config['peerurl'];
$port = $config['port'];

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
socket_bind($socket, 0, $port);

$from = '';
$port = 0;
while(true) {
    socket_recvfrom($socket, $buf, 32768, 0, $from, $port);
    $buf = base64_encode($buf);

    $postData = array('buf' => $buf);

    $handle = curl_init();
    $ch = curl_init($url);
    $curlopts = array(
        CURLOPT_URL => $url
        , CURLOPT_POST       => true
        , CURLOPT_POSTFIELDS => $postData
        , CURLOPT_RETURNTRANSFER     => true
    );
    $newCurlopts = Array();
    if(isset($config['curlopts'])) {
        $newCurlopts = $config['curlopts'];
        foreach($curlopts as $key => $value) {
            $newCurlopts[$key] = $value; 
        }
        $curlopts = $newCurlopts;
    }
    curl_setopt_array($handle, $curlopts);

    $data = curl_exec($handle);
    curl_close($handle);

    $data = base64_decode($data);

    socket_sendto($socket, $data, strlen($data), 0, $from, $port);
    
}