# TTN Tunnel
These two scripts enable you tu run a LoRaWAN TTN gateway behind a http proxy.
Imagine you are in secure site where no direct socket-connections to the internet are allowed and even if you are the IT-departement an don't want to open your network or even one port you can use this project to forward traffic from port 1700 to a ttn-gateway through a http proxy.

I could not find any solution for this problem so i made two php-script that pick up UDP traffic and send it to a server in the internet (even through a http proxy) which sends it via UDP to a configured server.

Of course, this script can be modifeid to forward any UDP-traffic for other use-cases.

This is really brand new, not in production yet and only testet at my network at home on a raspberry pi.
Use at your own risk.
There are scripts missing to run as deamon, etc. maybe this will come in the next few weeks.

To configure: use the ini-files.
"curlopts" can be everything, curl for php supports.

To run: Use ttntunnel-out.php on the server in the internet
Use ttntunnel-in.php (maybe in a screen session until deamonize-function is there) local.

I use it on a raspberry pi based gateway so started in.php locally and edited local_conf.json of the gateway to use "localhost" as server-address.

There is no authentication-stuff in out.php because this can be done via the webserver you use.

You need of course php-curl installed on the in-side and permission to open sockets with php on both in and outside.