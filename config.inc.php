<?php

require 'statsdb.inc.php'; // Set to the location of your account settings file

$logpath = './Logs/'; // Set to the path of your log files
$backupdir = ''; // Set to path to backup good log files to (no backup if empty)

$ftpserver = ''; // Set to your ftp server and path
// Note: Files are downloaded into your $logpath directory
// require __DIR__ . '/:##' on the end of the server name to include port number

// Use 'ftps://' for ssl-ftp
// Examples: $ftpserver = "ftp://server.com"
//           $ftpserver = "ftp://server.com/ut2003/System/UserLogs"
//           $ftpserver = "ftp://server.com:2021/ut2003/System/UserLogs"
//           $ftpserver = "ftps://server.com:2021/UserLogs"
$ftppassive = 0; // Set to 1 to enable passive mode

$maxgames = '0'; // Set to maximum number of individual game stats you wish saved (0 = Infinite)

$title_msg = '
Welcome to UT2003 StatsDB.</p>
<p>This site is running the Unreal Tournament 2003 local stats database program.<br>
For more information on UT2003 StatsDB visit the homepage at
<a HREF="http://ut2003stats.sourceforge.net">http://ut2003stats.sourceforge.net</a>.
';

$minchgames = '1'; // Minimum games required for player to be included in career highs
$minchtime = '1'; // Minimum time (minutes) required for player to be included in career highs
$savesingle = '1'; // Set to 1 to save games with less than two players.
$ignorelogtype = '1'; // Set to 1 to drop "Log " from the beginning of game types.
$usestatsname = '1'; // Set to 1 to track users by global stats name and password

// $query_server1 = ""; // Set to server IP or domain name for query on main page
// $query_port1 = 7777; // Set to server's port number for main page query
$query_spectators = 0; // Set to 1 to include spectators in status

// Use $query_server2/$query_port2, etc. for additional servers.  No limit on number.

$altlayout = '1'; // Selects alternate stylesheet and logo
// 0 = Default ngStats look
// 1 = Same as default colors with smaller fonts
// 2 = Dark color scheme with small fonts
