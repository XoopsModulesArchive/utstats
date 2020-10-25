UT2003 StatsDB
  Copyright (C) 2002,2003  Paul Gallier

UT2003 StatsDB is designed to work with PHP 4.2 and MySQL 3.2 or newer. The 
latest version can always be found at http://ut2003stats.sourceforge.net.  You 
can check the version of your file in the file "VERSION.txt". Visit the homepage 
for more information on bug reporting, feature requests and general support.

Please review the LICENSE.txt file included with this program.

In order to use this program you will need to download a local stats logging 
program.  I know of two good programs at this time:

LocalStats by Michiel 'El Muerte' Hendriks
 -Server actor that works completely transparent to the clients.
 -Supports remote host via a TCP connection.
 -Bots can be used but are not logged - i.e. bot logins, kills, and deaths do 
  not show up in logs.
 -Item pickups not logged.
 Home Page: http://www.drunksnipers.com/services/ut/ut2003/localstats

LocalLog (Mod) by ^(Hellraiser)^ & McNaz
 -Mod that creates a mirrored set of game types (Log Deathmatch, etc.).
 -Fully supports bots.
 -Includes item pickups, chat log.
 -Clients download mod upon connection.

Local logging utilities are available in the downloads section at:
http://ut2003stats.sourceforge.net.

PHP is available from http://www.php.net
MySQL is available at http://www.mysql.com

===============================================================================
========== UT2003 StatsDB installation: =======================================
===============================================================================

Extract the contents of this archive to a directory within your web server's 
public path. Preferably you'll want to copy the 'statsdb.inc.php' file to a 
location outside of your public path.  This file must be accessible by your web 
server (but not from the Internet).  Edit the following contents:

$AdminPass = "adminpass"; // This is the password for both the table creation
                          // program and the log parser.
$SQLhost = "localhost";   // The MySQL database host 

$SQLdb = "utstatsdb";     // The MySQL database

$SQLus = "utstats";       // A MySQL user with SELECT, INSERT, UPDATE,
                          //                   DELETE, CREATE, and INDEX grants.
$SQLpw = "statspass";     // The password for the above MySQL user.


Next, edit the config.inc.php file:

$title_msg = "Welcome to UT2003 StatsDB."; // This is the message that shows on the main page.

$logpath = "/ut2003/System/Logs"; // Set to the location of your UT2003 logs
$backupdir = ""; // Set to path to backup good log files to (no backup if empty)

$maxgames = "200";                // Number of individual game logs to retain (0 = infinite)

$query_server1 = "ut.domain.com"; // Set to server IP or domain name for query on main page
$query_port1 = 7777; // Set to server's port number for main page query

$query_server2 = "10.1.1.5"; // Increment the number in query_server and
$query_port2 = 7797; // query_port for additional servers.


To build the tables run the following:
http://yourwebsite.com/utstats/createtables.php?pass=test

Change "test" to whatever you change the AdminPass to.
Verify that all the tables were successfully created.

===============================================================================
========== FTP Notes ==========================================================
===============================================================================
As of version 1.11 UT2003 StatsDB can download log files from an FTP server. 
You must configure the FTP settings in both config.inc.php (server address, 
port, and passive/non-passive) and statsdb.inc.php (user name and password).
See the config.inc.php file for configuration options and examples.
Your web server must have FTP support included in PHP (standard on Windows) and 
must have ftp access through the firewall.  Some game hosts do not have ftp 
ports open or do not have the necessary firewall configuration to support ftp.

===============================================================================
========== MySQL Database =====================================================
===============================================================================

For the most part this assumes familiarity with MySQL, however, here's a brief 
step-by-step on setting up a MySQL database for use with UT2003 StatsDB.

If you already have a user and database you can use, or if you have no options 
of such, such as when leasing space on a shared server, just enter your MySQL 
username and database information into utstatsdb.inc.php.  Since the MySQL 
server is usually running on the same computer as the web server, you can 
generally leave the hostname ($SQLhost) as its default 'localhost'.  If the 
database is running on a different server you will need to change this value.

If you're running the server on your own system then you will probably want to 
create a seperate user and database to use with UT2003 StatsDB.  To create a new 
database, login to MySQL with an account that has full privileges (such as root) 
and enter:

CREATE utstatsdb;

This will create a new database called 'utstatsdb'.  Next create a new user for 
the UT2003 StatsDB program and give them the necessary rights:

GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP,ALTER,INDEX
ON utstatsdb.*
TO utsuser@localhost
IDENTIFIED BY 'password';

Change 'password' to something more secure and change it to match in 
utstatsdb.inc.php.  You're now ready to run createtables.php to setup your 
tables.

===============================================================================
========== Running the Log Parser =============================================
===============================================================================

To parse the log files run:
http://yourwebsite.com/utstats/logs.php?pass=test
(change "test" to your AdminPass)

Optional Parameters:
&savelogs=1 - Doesn't delete the log files (if you run it again it
              will attempt to parse the same files over again)
&multi=1    - Calculates multi-kills - use only for logs generated by
              older versions of LocalLog (pre 0.93)
&nohtml=1   - Doesn't display html tags (for command line use).

The logs.php file can be run from the command line.
You can run it using:
php logs.php pass=test

You can easily add this to your crontab or task scheduler to run periodically.

When the log parser is run, it will delete all but the last two incomplete logs
for each individual server name.  This is to prevent the system from deleting
logs for games that are still in session.

Only games that have and EndGame (EG) tag line for a frag/score limit or time
limit reached will be added to the database.  If the server is shut down or the
map changed mid-game it will not be logged.

The main viewer page is accessed via index.php.  Set your web server to service 
the index.php page in your utstats directory by default.

===============================================================================
========== Upgrading ==========================================================
===============================================================================

If you are upgrading from previous versions you will need to run the table 
updates with your admin password in order.
For example, if upgrading from version 1.02 or older run:
  http://mysite.com/utstats/updatetables103.php?pass=adminpass
  http://mysite.com/utstats/updatetables104.php?pass=adminpass
Substituting the path and admin password as necessary.

If you are running a version earlier than 1.00 you will need to remove your 
database tables and create them using the createtables.php utility listed above.
To remove existing tables from a command line of MySQL, first connect to your
database:
USE <database name>;
Example: USE utstats;

Next type:
DROP TABLE <table name>;

For example, to drop all old UTStats tables from version 0.80 type:
DROP TABLE ut_games,ut_gkills,ut_gplayers,ut_mutators,ut_players,ut_pwk,ut_tkills,ut_totals,ut_type,ut_weapons;

Most files were renamed, so remove all old files.

===============================================================================
========== Server Query =======================================================
===============================================================================

The server query (see the notes on the config.inc.php file) supports server 
queries via the GameSpy protocol.  Support is included for El Muerte's ServQuery 
protocol (version 1.07 as of this release).  ServQuery is an extension to the 
existing GameSpy query and allows for more information to be attained.  You can 
download ServQuery from the UT2003 StatsDB site or directly from the TDS website 
at 'http://www.drunksnipers.com/services/ut/ut2003/servquery'.

===============================================================================
========== Platform Notes =====================================================
===============================================================================

Windows servers:
 You may need to manually enable the gd library extension my editing your 
 php.ini file.  Look for and entry under ";Windows Extensions" that looks
 like this:
 ;extension=php_gd.dll
 Remove the semicolon and restart your web server.

===============================================================================
========== Database File ======================================================
===============================================================================

Here is a list of all associated database tables:

ut_games	Individual game logs
ut_gchat	Individual game chat logs
ut_gevents	Individual game event logs
ut_gitems	Individual game item pickups
ut_gkills	Individual game kill log
ut_gplayers	Individual game player list
ut_items	Item list (each unique item found in the game)
ut_pitems	Individual player item pickups
ut_players	Player list (all players, both humans and bots)
ut_pwkills	Individual player weapon stats
ut_tkills	Individual game team scoring
ut_totals	Global totals and high scores
ut_type		Game types
ut_weapons	Glboal weapons list with stats

There are a few tables that you may want to edit if you install certain mods:

ut_type - This stores a list of each game description (Deathmatch, CTF, etc.)
          and the type of game.  If you were to install a mod that includes a
          deathmatch style game called "Death Arena" for example, you'd want
          to include an entry in the ut_type table with tp_desc set to match
          the game type description ("Death Arena") and tp_type set to "1"
          which corresponds to the type deathmatch.  The game will automatically
          add any non-existent game types it encounters, but all stats will be
          added to the "Other" type category until modified.

ut_weapons - This is a list of all weapons found in the game including the
             descriptors for each as found in the logs.  Again, the game will
             automatically add any new weapons found in the logs here, but
             preferably these should be added before you process the logs.
             wp_type is the log description for the weapon, such as
             "DamTypeFlakShell".  In the wp_desc field you would want to set
             this to "Flak Cannon".  Since the flak shell is the weapon's
             secondary function you'll want to set wp_secondary to 1.
             Set wp_secondary to 2 for tertiary functions such as the shock
             combo, though this will still be calculated into the totals for
             secondary functions.

ut_items - A list of any items picked up in the game, including weapons and ammo.
           If new items are added to your game you'll want to edit the item's
           description (it_desc) to an appropriate name (such as "Damage Amplifer"
           for the "UDamagePickup").

The rest of the tables read descriptions from these three tables and should never
require any modification.

I may write an administrative utility to simplify editing the tables and other
functions, so check our web site occasionally.

There is a partially completed step-by-step installation guide included with 
this archive.  See SETUP_GUIDE.txt for details.
