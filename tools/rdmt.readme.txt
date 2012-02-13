RDMT Perl Server

About:
Crossplatform and asynchronous rdmt-server helps you to monitor database queries and other load.

Installation:
1. Install EV, IO::Socket, Config::IniFiles modules for perl:
    cpan install EV IO::Socket Config::IniFiles
2. Change rdmt.ini configuration (port, timeout, etc...)
3. Run rdmt.pl as daemon ($, nohup, etc...).
	Also you may be just simple server run by double click on server script.
4. Route rdmt port in iptables (LINUX):

# here RDMTPORT is a rdmt port, YOURSERVERIP - your site/server external ip, of necessity

# accept localhost
    iptables -I INPUT -p tcp -s 127.0.0.1 --dport RDMTPORT -j ACCEPT

# accept external host (of necessity)
    iptables -I INPUT -p tcp -s YOURSERVERIP --dport RDMTPORT -j ACCEPT

# drop all other connections
    iptables -A INPUT -p tcp --dport RDMTPORT -j DROP
	
API Development:
1. Create socket AF_INET family and SOCK_STREAM type
2. Connect to server (or make lazy-connection)
[[may be repeated:
	3. Server waiting for socket writing - write string in format DATA:X to server
		DATA - string (e.g. md5-hash)
		X - max requests (signals) per minute
	4. Server wait for socket reading - read 1 byte from socket and analyze it
		1 - ACCEPT
		0 - NOT ACCEPT
]]
5. Close connection with server