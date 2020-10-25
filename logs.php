<?php

/*
    UT2003 StatsDB
    Copyright (C) 2002,2003  Paul Gallier

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

require 'xoopsmtables.php';
require 'config.inc.php';
require 'logsql.php';

$password = '';
$save = $multicheck = $test = $nohtml = 0;

$magic = get_magic_quotes_gpc();
if ($magic) {
    if (isset($_GET['pass'])) {
        $password = stripslashes($_GET['pass']);
    }

    if (isset($_GET['savelogs'])) {
        $save = stripslashes($_GET['savelogs']);
    }

    if (isset($_GET['multi'])) {
        $multicheck = stripslashes($_GET['multi']);
    }

    if (isset($_GET['debug'])) {
        $test = stripslashes($_GET['debug']);
    }
} else {
    if (isset($_GET['pass'])) {
        $password = $_GET['pass'];
    }

    if (isset($_GET['savelogs'])) {
        $save = $_GET['savelogs'];
    }

    if (isset($_GET['multi'])) {
        $multicheck = $_GET['multi'];
    }

    if (isset($_GET['debug'])) {
        $test = $_GET['debug'];
    }
}

if (isset($_SERVER['argc']) && isset($_SERVER['argv'])) {
    $argc = $_SERVER['argc'];

    $argv = $_SERVER['argv'];
}
for ($i = 1; $i < $argc; $i++) {
    $pos = mb_strpos($argv[$i], '=');

    if (false !== $pos && mb_strlen($argv[$i]) > $pos) {
        $param = mb_strtoupper(mb_substr($argv[$i], 0, $pos));

        $val = mb_substr($argv[$i], $pos + 1);

        switch ($param) {
            case 'PASS':
                $password = $val;
                break;
            case 'SAVELOGS':
                $save = $val;
                break;
            case 'MULTI':
                $multicheck = $val;
                break;
            case 'DEBUG':
                $test = $val;
                break;
            case 'NOHTML':
                $nohtml = $val;
                break;
            default:
                echo "Invalid parameter.\n";
                die();
        }
    }
}

if ($nohtml) {
    $break = '';

    $bold = '';

    $ebold = '';
} else {
    $break = '<br>';

    $bold = '<b>';

    $ebold = '</b>';
}

if ('' == $password || '' == $AdminPass || $password != $AdminPass) {
    echo "Access error.{$break}\n";

    exit;
}

require 'logparse.php';
require 'logsave.php';

function checkfile($file, &$fdate)
{
    $stattype = 0;

    if (mb_strstr($file, 'LocalStats_') && ('.txt' == mb_substr($file, -4) || '.log' == mb_substr($file, -4))) { // LocalStats format
        $stattype = 1;

        $i = mb_strpos($file, 'LocalStats_');

        $file = mb_substr($file, $i + 11);

        $tok = strtok($file, '_'); // <port>

        if ('' != $tok) {
            $tok = strtok('_'); // <year>

            $fd_year = (int)$tok;

            if ($fd_year < 1000) {
                $fd_year += 2000;
            }

            if ('' != $tok) {
                $tok = strtok('_'); // <month>

                $fd_month = (int)$tok;

                if ('' != $tok) {
                    $tok = strtok('_'); // <day>

                    $fd_day = (int)$tok;

                    if ('' != $tok) {
                        $tok = strtok('_'); // <hour>

                        $fd_hour = (int)$tok;

                        if ('' != $tok) {
                            $tok = strtok('_'); // <min>

                            $fd_min = (int)$tok;

                            if ('' != $tok) {
                                $tok = strtok('.'); // <sec>

                                $fd_sec = (int)$tok;

                                $fdate = sprintf(
                                    '%04u-%02u-%02u %02u:%02u:%02u',
                                    $fd_year,
                                    $fd_month,
                                    $fd_day,
                                    $fd_hour,
                                    $fd_min,
                                    $fd_sec
                                ); // 2001-12-08 18:52:15

                                $logs[$files] = $file;

                                $logdate[$files++] = $fdate;
                            }
                        }
                    }
                }
            }
        }
    } elseif (mb_strlen($file) >= 18 && ('.txt' == mb_substr($file, -4) || '.log' == mb_substr($file, -4))) { // MutLocalLog format
        $stattype = 2;

        if (false !== ($i = mb_strpos($file, 'Logs_'))) {
            $file = mb_substr($file, $i + 5);
        }

        $tok = strtok($file, '-'); // <year>

        $fd_year = (int)$tok;

        if ('' != $tok) {
            $tok = strtok('-'); // <month>

            $fd_month = (int)$tok;

            if ('' != $tok) {
                $tok = strtok(' '); // <day>

                $fd_day = (int)$tok;

                if ('' != $tok) {
                    $tok = strtok('-'); // <hour>

                    $fd_hour = (int)$tok;

                    if ('' != $tok) {
                        $tok = strtok('-'); // <min>

                        $fd_min = (int)$tok;

                        if ('' != $tok) {
                            $tok = strtok('.'); // <sec>

                            $fd_sec = (int)$tok;

                            $fdate = sprintf(
                                '%04u-%02u-%02u %02u:%02u:%02u',
                                $fd_year,
                                $fd_month,
                                $fd_day,
                                $fd_hour,
                                $fd_min,
                                $fd_sec
                            ); // 2001-12-08 18:52:15
                        }
                    }
                }
            }
        }
    }

    return ($stattype);
}

if ('/' != mb_substr($logpath, -1)) {
    $logpath .= '/';
}

// ftp transfer
$ftptype = 0;
if ('ftp://' == mb_strtolower(mb_substr($ftpserver, 0, 6))) {
    $ftptype = 1;
} elseif ('ftps://' == mb_strtolower(mb_substr($ftpserver, 0, 7))) {
    $ftptype = 2;
}
if ($ftptype) {
    // Extract ftp server address

    if (1 == $ftptype) {
        echo "Initializing ftp file transfer.{$break}\n";

        $ftp_server = mb_substr($ftpserver, 6);
    } else {
        echo "Initializing ftps file transfer.{$break}\n";

        $ftp_server = mb_substr($ftpserver, 7);
    }

    // Extract remote directory name

    $ftp_dir = '';

    if ($i = mb_strpos($ftp_server, '/')) {
        $ftp_dir = mb_substr($ftp_server, $i);

        $ftp_server = mb_substr($ftp_server, 0, $i);
    }

    // Extract ftp server port

    $ftp_port = 21;

    if ($i = mb_strpos($ftp_server, ':')) {
        $ftp_port = (int)mb_substr($ftp_server, $i + 1);

        $ftp_server = mb_substr($ftp_server, 0, $i);
    }

    $conn_id = $login_result = 0;

    if (1 == $ftptype) {
        $conn_id = ftp_connect($ftp_server, $ftp_port, 20);
    } else {
        $conn_id = ftp_ssl_connect($ftp_server, $ftp_port, 20);
    }

    $login_result = ftp_login($conn_id, $FTPuser, $FTPpass);

    if (!$conn_id || !$login_result) {
        echo "Unable to login to ftp server '$ftp_server' on port '$ftp_port' for user '$FTPuser'.{$break}\n";

        exit;
    }

    echo "Connected to '$ftp_server' on port '$ftp_port' for user '$FTPuser'.{$break}\n";

    if ($ftppassive) {
        echo "Enabling passive mode.{$break}\n";

        ftp_pasv($conn_id, 1);
    }

    if ($ftp_dir) {
        $temp = ftp_chdir($conn_id, $ftp_dir);

        if ($test) {
            echo "[debug] chdir to '$ftp_dir' result = '$temp'{$break}\n";
        }
    }

    ftp_set_option($conn_id, FTP_TIMEOUT_SEC, 30);

    set_time_limit(90); // Reset script timeout counter

    $loglist = [];

    $loglist = ftp_nlist($conn_id, '*');

    $i = $files = 0;

    while ($loglist[$i]) {
        if ($test) {
            echo "[debug] Loglist[$i] = '{$loglist[$i]}'{$break}\n";
        }

        $file = $loglist[$i++];

        if ('.txt' == mb_substr($file, -4) || '.log' == mb_substr($file, -4)) {
            /*
                  if (strstr($file, "/") !== FALSE) {
                      $file = strrev($file);
                    $i2 = strpos($file, "/");
                    $file = substr($file, 0, $i2);
                    $file = strrev($file);
                  }
            */

            $fdate = '';

            $stattype = checkfile($file, $fdate);

            if ($stattype) {
                $logs[$files] = $file;

                $logdate[$files++] = $fdate;
            }
        }
    }

    if ($files > 1) {
        array_multisort($logdate, $logs, SORT_NUMERIC, SORT_ASC);
    } else {
        echo "No new logs to download.{$break}\n";

        exit;
    }

    for ($i = 0; $i < $files - 1; $i++) {
        set_time_limit(60); // Reset script timeout counter

        $file = $logs[$i];

        echo "Downloading log '$file'....";

        if (ftp_get($conn_id, "{$logpath}$file", (string)$file, FTP_BINARY)) {
            echo 'successful';

            if (!$save) {
                if (ftp_delete($conn_id, $file)) {
                    echo " - deleted.{$break}\n";
                } else {
                    echo " - deletion failed!{$break}\n";
                }
            } else {
                echo ".{$break}\n";
            }
        } else {
            echo "failed!{$break}\n";
        }
    }

    ftp_close($conn_id);

    echo "{$break}\n";
}

$files = 0;
$logs_saved = 0;
$handle = opendir($logpath);
while (false !== ($file = readdir($handle))) {
    $fdate = '';

    $stattype = checkfile($file, $fdate);

    if ($stattype) {
        $logs[$files] = $file;

        $logdate[$files++] = $fdate;
    }
}
closedir($handle);
if ($files > 0) {
    array_multisort($logdate, $logs, SORT_NUMERIC, SORT_ASC);
}

$numinc = 0;
$incomplete = [];
if ($files > 0) {
    $link = sqlquery_connect();
}
for ($i = 0; $i < $files; $i++) {
    set_time_limit(30); // Reset script timeout counter

    echo "Processing log '$logs[$i]'...";

    $file = $logpath . $logs[$i];

    $logfile = file($file);

    $ended = parselog($logfile);

    switch ($ended) {
        case 1:
        case 5:
            if ($test) {
                echo "Debug - not stored.{$break}\n";

                $logs_saved++;
            } else {
                if (5 == $ended && !$savesingle) {
                    echo "insufficient players.{$break}\n";
                } else {
                    if ($gamenum = storedata()) {
                        echo "game $gamenum successfully processed.{$break}\n";

                        $logs_saved++;
                    } else {
                        echo "not processed.{$break}\n";
                    }
                }
            }
            if ($backupdir) {
                copy($file, "{$backupdir}/{$logs[$i]}");
            }
            if (!$save) {
                unlink($file);
            }
            break;
        case 2:
            echo "map switch / server quit.{$break}\n";
            if (!$save) {
                unlink($file);
            }
            break;
        case 3:
            echo "invalid.{$break}\n";
            if (!$save) {
                unlink($file);
            }
            break;
        case 4:
            echo "already in database.{$break}\n";
            if (!$save) {
                unlink($file);
            }
            break;
        default:
            echo "incomplete (in session?).{$break}\n";
            $incomplete[$numinc][0] = $file;
            $incomplete[$numinc++][1] = $servername;
    }
}
if ($files > 0) {
    $GLOBALS['xoopsDB']->close($link);
}

if (!$files) {
    echo "{$break}{$bold}No log files to process.{$ebold}{$break}\n";
} elseif (!$logs_saved) {
    echo "{$break}{$bold}0 of $files logs processed - No new logs added.{$ebold}{$break}\n";
} else {
    // Remove all but most recent two incomplete log files per server
    set_time_limit(90); // Reset script timeout counter
    if (!$save) {
        $numservers = 0;

        $serverlist = [];

        for ($i = $numinc - 1; $i >= 0; $i--) {
            $file = $incomplete[$i][0];

            $servername = $incomplete[$i][1];

            for ($i2 = 0, $cserver = -1; $i2 < $numservers && $cserver < 0; $i2++) {
                if (!strcmp($servername, $serverlist[$i2][0])) {
                    $cserver = $i2;
                }
            }

            if ($cserver >= 0) {
                $serverlist[$cserver][1]++;

                if ($serverlist[$cserver][1] > 2) {
                    unlink($file);

                    $serverlist[$cserver][2]++;
                }
            } else {
                $serverlist[$numservers][0] = $servername;

                $serverlist[$numservers][1] = 1;

                $serverlist[$numservers++][2] = 0;
            }
        }

        for ($i = 0, $i2 = 0; $i < $numservers; $i++) {
            if ($serverlist[$i][2]) {
                if (!$i2) {
                    echo "{$break}\n";

                    $i2 = 1;
                }

                if ($serverlist[$i][2] > 1) {
                    $lgs = 'logs';
                } else {
                    $lgs = 'log';
                }

                echo "Removed {$serverlist[$i][2]} incomplete $lgs for {$serverlist[$i][0]}.{$break}\n";
            }
        }
    }

    echo "{$break}{$bold}$logs_saved of $files logs processed.{$ebold}{$break}\n";

    // Check career highs for each user

    if (!$test) {
        set_time_limit(90); // Reset script timeout counter

        echo "{$break}Calculating Career Highs....";

        require 'calchighs.php';

        echo "Finished.{$break}\n";
    }
}

// Check for limit on games to keep
if ($maxgames && !$test) {
    set_time_limit(90); // Reset script timeout counter

    $link = sqlquery_connect();

    $result = sqlqueryn("SELECT gm_num FROM $ut_games");

    $num = $GLOBALS['xoopsDB']->getRowsNum($result);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    if ($num > $maxgames) {
        $remove = $num - $maxgames;

        if ($remove > 1) {
            echo "{$break}{$bold}Trimming earliest $remove games from database to limit $maxgames games.{$ebold}{$break}\n";
        } else {
            echo "{$break}{$bold}Trimming earliest game from database to limit $maxgames games.{$ebold}{$break}\n";
        }

        // Select game numbers and remove individually (DELETE *** SORT BY not supported by MySQL 3.x)

        $result = sqlqueryn("SELECT gm_num FROM $ut_games ORDER BY gm_start ASC LIMIT $remove");

        if (!$result) {
            echo "Error selecting games for removal.{$break}\n";

            $GLOBALS['xoopsDB']->close($link);

            exit;
        }

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
            $gmnum = $row['gm_num'];

            // Delete Game Log (ut_games)

            $dresult = sqlqueryn("DELETE FROM $ut_games WHERE gm_num=$gmnum LIMIT 1");

            if (!$dresult) {
                echo "Error removing game.{$break}\n";

                $GLOBALS['xoopsDB']->close($link);

                exit;
            }

            // Delete Game Event Logs (ut_gevents)

            $dresult = sqlqueryn("DELETE FROM $ut_gevents WHERE ge_game=$gmnum");

            if (!$dresult) {
                echo "Error removing events.{$break}\n";

                $GLOBALS['xoopsDB']->close($link);

                exit;
            }

            // Delete Game Item Logs (ut_gitems)

            $dresult = sqlqueryn("DELETE FROM $ut_gitems WHERE gi_game=$gmnum");

            if (!$dresult) {
                echo "Error removing items.{$break}\n";

                $GLOBALS['xoopsDB']->close($link);

                exit;
            }

            // Delete Game Kill Logs (ut_gkills)

            $dresult = sqlqueryn("DELETE FROM $ut_gkills WHERE gk_game=$gmnum");

            if (!$dresult) {
                echo "Error removing kill logs.{$break}\n";

                $GLOBALS['xoopsDB']->close($link);

                exit;
            }

            // Delete Game Score Logs (ut_gscores)

            $dresult = sqlqueryn("DELETE FROM $ut_gscores WHERE gs_game=$gmnum");

            if (!$dresult) {
                echo "Error removing score logs.{$break}\n";

                $GLOBALS['xoopsDB']->close($link);

                exit;
            }

            // Delete Game Player Logs (ut_gplayers)

            $dresult = sqlqueryn("DELETE FROM $ut_gplayers WHERE gp_game=$gmnum");

            if (!$dresult) {
                echo "Error removing game player logs.{$break}\n";

                $GLOBALS['xoopsDB']->close($link);

                exit;
            }

            // Delete Game Chat Logs (ut_gchat)

            $dresult = sqlqueryn("DELETE FROM $ut_gchat WHERE gc_game=$gmnum");

            if (!$dresult) {
                echo "Error removing game chat logs.{$break}\n";

                $GLOBALS['xoopsDB']->close($link);

                exit;
            }
        }
    }

    $GLOBALS['xoopsDB']->close($link);
}
