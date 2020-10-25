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

if (preg_match('/logparse.php/i', $_SERVER['PHP_SELF'])) {
    echo "Access denied.\n";

    die();
}

function parseline(&$line, &$param)
{
    $ok = true;

    if (!mb_strlen($line)) {
        $ok = false;
    } else {
        $loc = mb_strpos($line, "\t");

        if (false === $loc) {
            $param = $line;

            $line = '';
        } else {
            if ($loc > 0) {
                $param = mb_substr($line, 0, $loc);
            } else {
                $param = '';
            }

            $line = mb_substr($line, $loc + 1);
        }

        if (mb_strlen($param) > 255) {
            $param = mb_substr($param, 0, 254);
        }
    }

    return $ok;
}

function parseserverdata(&$line, &$param, &$val)
{
    $ok = true;

    if (0 == mb_strlen($line)) {
        $ok = false;
    } else {
        $loc = mb_strpos($line, '\\');

        if (false === $loc) {
            $ok = false;
        } else {
            $line = mb_substr($line, $loc + 1);

            $loc = mb_strpos($line, '\\');

            if (false === $loc) {
                $ok = false;
            } else {
                $param = mb_substr($line, 0, $loc);

                $line = mb_substr($line, $loc + 1);

                $loc = mb_strpos($line, '\\');

                if (false === $loc) {
                    $val = $line;

                    $line = '';
                } else {
                    $val = mb_substr($line, 0, $loc);

                    $line = mb_substr($line, $loc);
                }
            }
        }
    }

    return $ok;
}

function endspree($plr, $time, $reason, $weapon, $opponent)
{
    global $player, $spree, $events, $numevents;

    $num = $spree[$plr][1];

    if ($num) {
        $length = $time - $spree[$plr][0];

        if ($num >= 5 && $num < 10) {
            $player[$plr]['p_spreet1'] += $length;

            $player[$plr]['p_spreek1'] += $num;
        } elseif ($num >= 10 && $num < 15) {
            $player[$plr]['p_spreet2'] += $length;

            $player[$plr]['p_spreek2'] += $num;
        } elseif ($num >= 15 && $num < 20) {
            $player[$plr]['p_spreet3'] += $length;

            $player[$plr]['p_spreek3'] += $num;
        } elseif ($num >= 20 && $num < 25) {
            $player[$plr]['p_spreet4'] += $length;

            $player[$plr]['p_spreek4'] += $num;
        } elseif ($num >= 25 && $num < 30) {
            $player[$plr]['p_spreet5'] += $length;

            $player[$plr]['p_spreek5'] += $spree[$plr][1];
        } elseif ($num >= 30) {
            $player[$plr]['p_spreet6'] += $length;

            $player[$plr]['p_spreek6'] += $spree[$plr][1];
        }

        $spree[$plr][0] = 0;

        $spree[$plr][1] = 0;

        if ($num >= 5) {
            $events[$numevents][0] = $plr;      // Player
            $events[$numevents][1] = 1;         // Event
            $events[$numevents][2] = $time;     // Time
            $events[$numevents][3] = $length;   // Length
            $events[$numevents][4] = $num;      // Quant
            $events[$numevents][5] = $reason;   // Reason
            $events[$numevents][6] = $opponent; // Opponent
            $events[$numevents++][7] = $weapon; // Item
        }
    }
}

function endmulti($plr, $time)
{
    global $player, $multi, $stattype, $multicheck;

    if ($multicheck && $multi[$plr][1] && 1 != $stattype) {
        switch ($multi[$plr][1]) {
            case 1:
                break;
            case 2:
                $player[$plr]['p_multi1']++;
                break;
            case 3:
                $player[$plr]['p_multi2']++;
                break;
            case 4:
                $player[$plr]['p_multi3']++;
                break;
            case 5:
                $player[$plr]['p_multi4']++;
                break;
            case 6:
                $player[$plr]['p_multi5']++;
                break;
            case 7:
                $player[$plr]['p_multi6']++;
                break;
            default:
                $player[$plr]['p_multi7']++;
        }
    }

    $multi[$plr][0] = 0;

    $multi[$plr][1] = 0;

    $multi[$plr][2] = 0;
}

function connections($plr, $time, $reason)
{
    global $events, $numevents;

    // Reason: 0 = Connect / 1 = Disconnect
    $events[$numevents][0] = $plr;    // Player
    $events[$numevents][1] = 2;       // Event
    $events[$numevents][2] = $time;   // Time
    $events[$numevents][3] = 0;       // Length
    $events[$numevents][4] = 0;       // Quant
    $events[$numevents][5] = $reason; // Reason
    $events[$numevents][6] = 0;       // Opponent
    $events[$numevents++][7] = 0;     // Item
}

function gameevent($time, $reason)
{
    global $events, $numevents;

    // Reason: 0 = Game Start / 1 = Game End
    $events[$numevents][0] = 0;       // Player
    $events[$numevents][1] = 3;       // Event
    $events[$numevents][2] = $time;   // Time
    $events[$numevents][3] = 0;       // Length
    $events[$numevents][4] = 0;       // Quant
    $events[$numevents][5] = $reason; // Reason
    $events[$numevents][6] = 0;       // Opponent
    $events[$numevents++][7] = 0;     // Item
}

function teamchange($time, $plr, $team)
{
    global $events, $numevents, $tchange;

    $events[$numevents][0] = $plr;    // Player
    $events[$numevents][1] = 4;       // Event
    $events[$numevents][2] = $time;   // Time
    $events[$numevents][3] = 0;       // Length
    $events[$numevents][4] = $team;   // Quant
    $events[$numevents][5] = '';      // Reason
    $events[$numevents][6] = 0;       // Opponent
    $events[$numevents++][7] = 0;     // Item

    $tchange[$plr] = $time;
}

function teamscore($time, $tm, $score, $reason)
{
    global $events, $numevents, $team, $tkills, $tkcount;

    $events[$numevents][0] = $tm;   // Player
    $events[$numevents][1] = 5;       // Event
    $events[$numevents][2] = $time;   // Time
    $events[$numevents][3] = 0;       // Length
    $events[$numevents][4] = (int)$score;  // Quant
    $events[$numevents][5] = $reason; // Reason
    $events[$numevents][6] = 0;       // Opponent
    $events[$numevents++][7] = 0;     // Item

    $team[$tm] += (int)$score;

    $tkills[$tkcount][0] = $tm;        // Team number
    $tkills[$tkcount][1] = (int)$score; // Score
    $tkills[$tkcount++][2] = $time;      // Time
}

function parselog(&$logfile)
{
    global $ignorelogtype;

    global $server, $player, $gkills, $gkcount, $gscores, $gscount, $team, $tkills, $tkcount, $stattype, $nohtml;

    global $spree, $multi, $pickups, $events, $numevents, $tchange, $chatlog, $servername, $uselimit;

    global $ut_totals, $ut_games, $ut_players, $ut_gplayers, $ut_weapons, $ut_gkills, $ut_pwkills, $ut_gscores, $ut_gitems, $ut_pitems, $ut_items, $ut_gevents, $ut_type, $ut_tkills, $ut_gchat;

    if ($nohtml) {
        $break = '';
    } else {
        $break = '<br>';
    }

    $ended = $started = $ngfound = $numplayers = $maxplayer = $gkcount = $gscount = $tkcount = $tot_score = 0;

    $tot_kills = $tot_deaths = $tot_suicides = $teamkills = $maxpickups = $numevents = 0;

    $headshots = $numchat = 0;

    $password = $gamestats = $fraglimit = $timelimit = $translocator = 0;

    $starttime = $length = 0;

    $firstblood = -1;

    $map = $gametype = $gametnum = $matchdate = $mutators = $minplayers = $admin = $email = '';

    // Clear existing arrays

    if (isset($player)) {
        while (null != array_pop($player)) {
        }
    }

    if (isset($pickups)) {
        while (null != array_pop($pickups)) {
        }
    }

    $player[0] = [
        'p_name' => '',
        'p_bot' => 0,
        'p_kills' => 0,
        'p_deaths' => 0,
        'p_suicides' => 0,
        'p_starttime' => 0,
        'p_headshots' => 0,
        'p_firstblood' => 0,
        'p_multi1' => 0,
        'p_multi2' => 0,
        'p_multi3' => 0,
        'p_multi4' => 0,
        'p_multi5' => 0,
        'p_multi6' => 0,
        'p_multi7' => 0,
        'p_spree1' => 0,
        'p_spreet1' => 0,
        'p_spreek1' => 0,
        'p_spree2' => 0,
        'p_spreet2' => 0,
        'p_spreek2' => 0,
        'p_spree3' => 0,
        'p_spreet3' => 0,
        'p_spreek3' => 0,
        'p_spree4' => 0,
        'p_spreet4' => 0,
        'p_spreek4' => 0,
        'p_spree5' => 0,
        'p_spreet5' => 0,
        'p_spreek5' => 0,
        'p_spree6' => 0,
        'p_spreet6' => 0,
        'p_spreek6' => 0,
        'p_combo1' => 0,
        'p_combo2' => 0,
        'p_combo3' => 0,
        'p_combo4' => 0,
        'p_totaltime' => 0,
        'p_connected' => 0,
        'p_team' => -1,
        'p_user' => '',
        'p_id' => '',
        'p_key' => 0,
        'p_t0score' => 0,
        'p_t1score' => 0,
        'p_teamkills' => 0,
        'p_teamdeaths' => 0,
        'p_pickup' => 0,
        'p_taken' => 0,
        'p_dropped' => 0,
        'p_assist' => 0,
        'p_typekill' => 0,
        'p_return' => 0,
        'p_capcarry' => 0,
        'p_tossed' => 0,
        'p_transgib' => 0,
        'p_rank' => 0,
        'p_num' => 0,
    ];

    $chatlog = [];

    $assist = [];

    // Check database version

    $mysqlver = $GLOBALS['xoopsDB']->getServerVersion();

    $dot = mb_strpos($mysqlver, '.');

    if (false === $dot) {
        echo "Unable to determine MySQL version.<br>\n";

        exit;
    }

    $mysqlverh = (int)mb_substr($mysqlver, 0, $dot);

    $dot2 = mb_strpos($mysqlver, '.', $dot + 1);

    if (false === $dot2) {
        echo "Unable to determine MySQL version.<br>\n";

        exit;
    }

    $mysqlverl = (int)mb_substr($mysqlver, $dot + 1, $dot2 - $dot - 1);

    if ($mysqlverh > 3 || (3 == $mysqlverh && $mysqlverl >= 23)) {
        $uselimit = 1;
    } else {
        $uselimit = 0;
    }

    while ((list($line_num, $line) = each($logfile)) && $ended < 4) {
        $i = 0;

        if ('Local Log' != mb_substr($line, 0, 9)) {
            while (parseline($line, $param)) {
                $data[$i++] = trim($param);
            }
        }

        if ($i > 1) {
            $tt = mb_strtoupper($data[1]);

            switch ($tt) {
                case 'NG': // New Game
                    if (10 != $i || $ended) {
                        break;
                    }
                    $ngfound = 1;
                    $matchdate = strtotime($data[2]); // Date/Time (2002-10-26 21:55:20)
                    $timezone = $data[3]; // Time zone
                    $mapfile = addslashes(mb_substr($data[4], 0, 30)); // Map filename
                    $map = addslashes(mb_substr($data[5], 0, 30)); // Map title
                    $author = addslashes($data[6]); // Map author
                    $gtype = $data[7]; // Game type
                    $gname = addslashes(mb_substr($data[8], 0, 30)); // Game name
                    $mut = addslashes($data[9]); // Mutators

                    if ('Untitled' == $map) {
                        $map = $mapfile;
                    }

                    // Drop "Log " from beginning of game type description
                    if ($ignorelogtype && 'Log ' == mb_substr($gname, 0, 4)) {
                        $gname = mb_substr($gname, 4);
                    }

                    // Look up game type
                    $result = sqlqueryn("SELECT tp_num,tp_type FROM $ut_type WHERE tp_desc='$gname' LIMIT 1");
                    if (!$result) {
                        echo "1 Error reading game type table $ut_type.{$break}\n";

                        exit;
                    }
                    if ($row = $GLOBALS['xoopsDB']->fetchBoth($result)) {
                        $gametnum = $row['tp_num'];

                        $gametype = $row['tp_type'];

                        $GLOBALS['xoopsDB']->freeRecordSet($result);
                    } else { // Add new game type
                        $result = sqlqueryn("INSERT INTO $ut_type SET tp_desc='$gname'");

                        if (!$result) {
                            echo "Error saving new game type.{$break}\n";

                            exit;
                        }

                        $result = sqlqueryn('SELECT LAST_INSERT_ID()');

                        $row = $GLOBALS['xoopsDB']->fetchBoth($result);

                        $gametnum = (int)$row[0];

                        $gametype = 0;
                    }

                    $mutators = '';
                    $tok = strtok($mut, '.');
                    if ('' != $tok) {
                        $tok = strtok("|\n");

                        while ($tok) {
                            $mut = $tok;

                            if ('Mut' == mb_substr($mut, 0, 3)) {
                                $mut = mb_substr($mut, 3);
                            }

                            $mutators .= $mut . ', ';

                            $tok = strtok('.');

                            $tok = strtok("|\n");
                        }

                        $mutators = rtrim($mutators, ', ');
                    }
                    break;
                case 'SI': // Server Info
                    if (8 != $i || $ended) {
                        break;
                    }
                    $servername = addslashes(mb_substr($data[2], 0, 35)); // Server name
                    $timezone = $data[3]; // Time zone
                    $admin = addslashes(mb_substr($data[4], 0, 35)); // Admin name
                    $email = addslashes(mb_substr($data[5], 0, 35)); // Admin email
                    $siline = $data[7];

                    // Check for existing game in database
                    $md = date('Y-m-d H:i:s', $matchdate);
                    $result = sqlqueryn(
                        "SELECT gm_num FROM $ut_games WHERE gm_server='$servername' &&
                    gm_map='$map' && gm_type='$gametnum' && gm_start='$md' LIMIT 1"
                    );
                    if (!$result) {
                        echo "Error accessing game database.{$break}\n";

                        exit;
                    }
                    if ($GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
                        $ended = 4;
                    }

                    while (parseserverdata($siline, $param, $val)) {
                        $info = mb_strtolower(trim($param));

                        $status = mb_strtolower(trim($val));

                        switch ($info) {
                            case 'servermode': // dedicated
                                break;
                            case 'adminname':
                                break;
                            case 'adminemail':
                                break;
                            case 'password':
                                if ('true' == $status) {
                                    $password = 1;
                                } else {
                                    $password = 0;
                                }
                                break;
                            case 'gamestats':
                                if ('true' == $status) {
                                    $gamestats = 1;
                                } else {
                                    $gamestats = 0;
                                }
                                break;
                            case 'goalscore':
                                $fraglimit = $status;
                                break;
                            case 'timelimit':
                                $timelimit = $status;
                                break;
                            case 'minplayers':
                                $minplayers = $status;
                                break;
                            case 'translocator':
                                if ('true' == $status) {
                                    $translocator = 1;
                                } else {
                                    $translocator = 0;
                                }
                                break;
                            default:
                                break;
                        }

                        $tok = strtok("\\\n");
                    }
                    break;
                case 'SG': // Start Game - (none)
                    if ($ended) {
                        break;
                    }
                    $started = 1;
                    $starttime = $data[0];
                    for ($n = 0; $n <= $maxplayer; $n++) {
                        if (isset($player[$n])) {
                            $player[$n]['p_starttime'] = $data[0];

                            $player[$n]['p_totaltime'] = 0;
                        }
                    }
                    $team[0] = $team[1] = 0;
                    gameevent($starttime, 0);
                    break;
                case 'C': // Player connect - playernumber / playername
                    //                  playernumber / cd-key hash / id name, id pass hash
                    if ($i < 4) {
                        break;
                    }
                    $plr = $data[2];
                    if (isset($player[$plr]) && '' != $player[$plr]['p_name'] && $player[$plr]['p_connected']) {
                        if ($i < 6) {
                            break;
                        }

                        $key = $data[3];

                        $user = $data[4];

                        $id = $data[5];

                        $player[$plr]['p_key'] = $key;

                        $player[$plr]['p_user'] = $user;

                        $player[$plr]['p_id'] = $id;

                        // Check for existing user (only if stats user/id is set)

                        for ($i2 = 0; $i2 <= $maxplayer && $relog[$plr] < 0; $i2++) {
                            if ($plr != $i2 && isset($player[$i2]) && !strcmp($key, $player[$i2]['p_key']) && !strcmp($user, $player[$i2]['p_user']) && !strcmp($id, $player[$i2]['p_id'])) {
                                $relog[$plr] = $i2;

                                $player[$plr]['p_name'] = '';

                                $player[$i2]['p_connected'] = 1;

                                $player[$i2]['p_starttime'] = $data[0];

                                connections($i2, $data[0], 0);
                            }
                        }

                        break;
                    }

                    if ($data[3]) {
                        $cname = $data[3];

                        $named = 1;
                    } else {
                        $cname = "Player $plr";

                        $named = 0;
                    }
                    $bot = 0;
                    if ('[BOT]' == mb_substr($cname, 0, 5)) {
                        $cname = mb_substr($cname, 5);

                        $bot = 1;
                    }

                    if (isset($player[$plr]) && '' != $player[$plr]['p_name']) {
                        $player[$plr]['p_name'] = $cname;

                        $player[$plr]['p_bot'] = $bot;

                        $player[$plr]['p_starttime'] = $data[0];

                        $player[$plr]['p_connected'] = 1;

                        $player[$plr]['p_named'] = $named;

                        connections($plr, $data[0], 0);
                    } else {
                        $relog[$plr] = -1;

                        // Check for existing player name (set relog if stats user/id not set)

                        if ($named) {
                            for ($i2 = 0; $i2 <= $maxplayer && $relog[$plr] < 0; $i2++) {
                                if (isset($player[$i2]) && !strcmp($cname, $player[$i2]['p_name']) && !$player[$i2]['p_connected'] && !$player[$i2]['p_user'] && !$player[$i2]['p_id']) {
                                    $relog[$plr] = $i2;

                                    $player[$plr]['p_name'] = '';

                                    $player[$i2]['p_connected'] = 1;

                                    $player[$i2]['p_starttime'] = $data[0];

                                    connections($i2, $data[0], 0);
                                }
                            }
                        }

                        if ($relog[$plr] < 0) {
                            // Add new player

                            $player[$plr] = [
                                'p_name' => $cname,
                                'p_bot' => $bot,
                                'p_kills' => 0,
                                'p_deaths' => 0,
                                'p_suicides' => 0,
                                'p_starttime' => $data[0],
                                'p_headshots' => 0,
                                'p_firstblood' => 0,
                                'p_multi1' => 0,
                                'p_multi2' => 0,
                                'p_multi3' => 0,
                                'p_multi4' => 0,
                                'p_multi5' => 0,
                                'p_multi6' => 0,
                                'p_multi7' => 0,
                                'p_spree1' => 0,
                                'p_spreet1' => 0,
                                'p_spreek1' => 0,
                                'p_spree2' => 0,
                                'p_spreet2' => 0,
                                'p_spreek2' => 0,
                                'p_spree3' => 0,
                                'p_spreet3' => 0,
                                'p_spreek3' => 0,
                                'p_spree4' => 0,
                                'p_spreet4' => 0,
                                'p_spreek4' => 0,
                                'p_spree5' => 0,
                                'p_spreet5' => 0,
                                'p_spreek5' => 0,
                                'p_spree6' => 0,
                                'p_spreet6' => 0,
                                'p_spreek6' => 0,
                                'p_combo1' => 0,
                                'p_combo2' => 0,
                                'p_combo3' => 0,
                                'p_combo4' => 0,
                                'p_totaltime' => 0,
                                'p_connected' => 1,
                                'p_team' => -1,
                                'p_user' => '',
                                'p_id' => '',
                                'p_key' => 0,
                                'p_t0score' => 0,
                                'p_t1score' => 0,
                                'p_teamkills' => 0,
                                'p_teamdeaths' => 0,
                                'p_pickup' => 0,
                                'p_taken' => 0,
                                'p_dropped' => 0,
                                'p_assist' => 0,
                                'p_typekill' => 0,
                                'p_return' => 0,
                                'p_capcarry' => 0,
                                'p_tossed' => 0,
                                'p_transgib' => 0,
                                'p_rank' => 0,
                                'p_num' => 0,
                                'p_named' => $named,
                            ];

                            $spree[$plr][0] = 0; // Start Time
                            $spree[$plr][1] = 0; // Kills
                            $multi[$plr][0] = 0; // Start Time
                            $multi[$plr][1] = 0; // Kills
                            $multi[$plr][2] = 0; // Last Kill Time
                            $tchange[$plr] = 0; // Team Change Tracking
                            $assist[$plr] = 0; // Assists
                            if ($named) {
                                connections($plr, $data[0], 0);
                            }
                        }

                        if ($plr > $maxplayer) {
                            $maxplayer = $plr;
                        }
                    }
                    break;
                case 'D': // Player disconnect
                    if (3 != $i) {
                        break;
                    }
                    $plr = $data[2];
                    if ($plr >= 0 && $relog[$plr] >= 0) {
                        $plr = $relog[$plr];
                    }
                    $player[$plr]['p_connected'] = 0;
                    if (!$ended) {
                        $time = $data[0] - $player[$plr]['p_starttime'];

                        $player[$plr]['p_totaltime'] += $time;

                        endspree($plr, $data[0], 4, 0, 0); // End Killing Sprees
                        endmulti($plr, $data[0]); // End Multi-Kills
                    }
                    connections($plr, $data[0], 1);
                    break;
                case 'G': // Game event
                    if ($i < 3 || $ended) {
                        break;
                    }
                    $plr = $data[3];
                    if ($plr >= 0 && $relog[$plr] >= 0) {
                        $plr = $relog[$plr];
                    }
                    $event = mb_strtolower($data[2]);
                    switch ($event) {
                        case 'namechange':
                            if (5 == $i && $data[4]) {
                                $cname = $data[4];

                                $bot = 0;

                                if ('[BOT]' == mb_substr($cname, 0, 5)) {
                                    $cname = mb_substr($cname, 5);

                                    $bot = 1;
                                }

                                $named = 1;

                                $relogged = 0;

                                // Check for existing player name

                                if (!$bot && $relog[$data[3]] < 0) {
                                    for ($i2 = 0; $i2 <= $maxplayer && $relog[$data[3]] < 0; $i2++) {
                                        if (isset($player[$i2]) && !strcmp($cname, $player[$i2]['p_name']) && !$player[$i2]['p_connected']) {
                                            $relog[$data[3]] = $i2;

                                            $plr = $i2;

                                            $player[$data[3]]['p_name'] = '';

                                            $player[$plr]['p_connected'] = 1;

                                            $player[$plr]['p_starttime'] = $data[0];

                                            connections($plr, $data[0], 0);

                                            $relogged = 1;
                                        }
                                    }
                                }

                                if (!$relogged) {
                                    if (!$player[$plr]['p_named']) {
                                        connections($plr, $data[0], 0);
                                    }

                                    $player[$plr]['p_name'] = $cname;

                                    $player[$plr]['p_bot'] = $bot;

                                    $player[$plr]['p_named'] = $named;
                                }
                            }
                            break;
                        case 'teamchange': // Team = 0-1
                            if (5 == $i) {
                                $player[$plr]['p_team'] = $data[4];

                                teamchange($data[0], $plr, $data[4]);
                            }
                            break;
                        case 'flag_dropped':
                        case 'bomb_dropped':
                            if ($i > 3) {
                                $player[$plr]['p_dropped']++;
                            }
                            break;
                        case 'flag_taken':
                        case 'bomb_taken':
                            if ($i > 3) {
                                $player[$plr]['p_taken']++;
                            }
                            break;
                        case 'flag_returned':
                            if ($i > 3) {
                                $player[$plr]['p_return']++;
                            }
                            break;
                        case 'flag_pickup':
                        case 'bomb_pickup':
                            if ($i > 3) {
                                $player[$plr]['p_pickup']++;
                            }
                            break;
                        case 'flag_captured':
                            if ($i > 3) {
                                $player[$plr]['p_capcarry']++;

                                for ($i = 0; $i < $maxplayer; $i++) {
                                    if (isset($player[$i]) && isset($assist[$i]) && $assist[$i] && $i != $plr) {
                                        $player[$i]['p_assist']++;
                                    }

                                    $assist[$i] = 0;
                                }
                            }
                            break;
                        case 'flag_returned_timeout':
                        case 'bomb_returned_timeout':
                        default:
                            break;
                    }
                    break;
                case 'P': // Special event - player / event
                    if ($i < 4 || $ended) {
                        break;
                    }
                    $plr = $data[2];
                    if ($plr >= 0 && $relog[$plr] >= 0) {
                        $plr = $relog[$plr];
                    }
                    $event = mb_strtolower($data[3]);
                    switch ($event) {
                        case 'first_blood':
                            $firstblood = $plr;
                            $player[$plr]['p_firstblood'] = '1';
                            break;
                        case 'spree_1': // Killing Spree!
                            $player[$plr]['p_spree1']++;
                            break;
                        case 'spree_2': // Rampage!
                            $player[$plr]['p_spree2']++;
                            if ($player[$plr]['p_spree1']) {
                                $player[$plr]['p_spree1']--;
                            }
                            break;
                        case 'spree_3': // Dominating!
                            $player[$plr]['p_spree3']++;
                            if ($player[$plr]['p_spree2']) {
                                $player[$plr]['p_spree2']--;
                            }
                            break;
                        case 'spree_4': // Unstoppable!
                            $player[$plr]['p_spree4']++;
                            if ($player[$plr]['p_spree3']) {
                                $player[$plr]['p_spree3']--;
                            }
                            break;
                        case 'spree_5': // Godlike!
                            $player[$plr]['p_spree5']++;
                            if ($player[$plr]['p_spree4']) {
                                $player[$plr]['p_spree4']--;
                            }
                            break;
                        case 'spree_6': // Wicked Sick!
                            $player[$plr]['p_spree6']++;
                            if ($player[$plr]['p_spree5']) {
                                $player[$plr]['p_spree5']--;
                            }
                            break;
                        case 'multikill_1': // Double Kill
                            $player[$plr]['p_multi1']++;
                            break;
                        case 'multikill_2': // Multi Kill
                            $player[$plr]['p_multi2']++;
                            if ($player[$plr]['p_multi1']) {
                                $player[$plr]['p_multi1']--;
                            }
                            break;
                        case 'multikill_3': // Mega Kill
                            $player[$plr]['p_multi3']++;
                            if ($player[$plr]['p_multi2']) {
                                $player[$plr]['p_multi2']--;
                            }
                            break;
                        case 'multikill_4': // Ultra Kill
                            $player[$plr]['p_multi4']++;
                            if ($player[$plr]['p_multi3']) {
                                $player[$plr]['p_multi3']--;
                            }
                            break;
                        case 'multikill_5': // Monster Kill
                            $player[$plr]['p_multi5']++;
                            if ($player[$plr]['p_multi4']) {
                                $player[$plr]['p_multi4']--;
                            }
                            break;
                        case 'multikill_6': // Ludicrous Kill
                            $player[$plr]['p_multi6']++;
                            if ($player[$plr]['p_multi5']) {
                                $player[$plr]['p_multi5']--;
                            }
                            break;
                        case 'multikill_7': // Holy Shit (ignore after 7)
                            $player[$plr]['p_multi7']++;
                            if ($player[$plr]['p_multi6']) {
                                $player[$plr]['p_multi6']--;
                            }
                            break;
                        case 'combospeed':
                        case 'xgame.combospeed': // Speed!
                            $player[$plr]['p_combo1']++;
                            break;
                        case 'combodefensive':
                        case 'xgame.combodefensive': // Booster!
                            $player[$plr]['p_combo2']++;
                            break;
                        case 'comboinvis':
                        case 'xgame.comboinvis': // Invisible!
                            $player[$plr]['p_combo3']++;
                            break;
                        case 'comboberserk':
                        case 'xgame.comboberserk': // Berserk!
                            $player[$plr]['p_combo4']++;
                            break;
                        case 'translocate_gib':
                            $player[$plr]['p_transgib']++;
                            break;
                    }
                    break;
                case 'K': // Kill - killer / damagetype / victim / victimweapon
                    if (6 != $i || $ended) {
                        break;
                    }
                    $killtime = $data[0];
                    $killer = $data[2];
                    if ($killer >= 0 && $relog[$killer] >= 0) {
                        $killer = $relog[$killer];
                    }
                    $victim = $data[4];
                    if ($victim >= 0 && $relog[$victim] >= 0) {
                        $victim = $relog[$victim];
                    }
                    $killweapon = $data[3];
                    $victweapon = $data[5];
                    if ($killer >= 0 && ($killer == $victim)) { // Shot self
                        $player[$victim]['p_suicides']++; // Suicides
                        $reason = 2;

                        $tot_suicides++;
                    } elseif ($killer < 0) { // Fell, etc.
                        $player[$victim]['p_suicides']++; // Event Suicides
                        $reason = 3;

                        $tot_suicides++;
                    } else {
                        $player[$killer]['p_kills']++; // Kills

                        $tot_kills++;

                        $player[$victim]['p_deaths']++; // Deaths

                        $reason = 1;

                        $tot_deaths++;

                        if (mb_strstr($killweapon, 'HeadShot')) {
                            $player[$killer]['p_headshots']++; // Head Shots

                            $headshots++;
                        }

                        // Track Killing Sprees for Killer

                        if (!$spree[$killer][1]) {
                            $spree[$killer][0] = $killtime; // First Kill

                            $spree[$killer][1] = 1;
                        } else {
                            $spree[$killer][1]++;
                        } // Kills

                        // Track Multi-Kills for Killer
                        if ($killtime - $multi[$killer][2] < 6) { // Within multi range
                            if (!$multi[$killer][1]) {
                                $multi[$killer][0] = $killtime; // Start Time
                                $multi[$killer][1] = 1; // Kills
                            } else {
                                $multi[$killer][1]++;
                            } // Kills
                            $multi[$killer][2] = $killtime; // Last Kill Time
                        } else {
                            endmulti($killer, $killtime); // End Multi-Kill for Killer

                            $multi[$killer][0] = $killtime;

                            $multi[$killer][1] = 1;

                            $multi[$killer][2] = $killtime;
                        }
                    }

                    // Check for Team Change Suicide
                    if ($killer < 0 && $tchange[$victim] >= $killtime - 1 && !strcasecmp($killweapon, 'DamageType')) {
                        $killweapon = 'TeamChange';

                        $tchange[$victim] = 0;
                    }

                    // Get Kill Weapon
                    $result = sqlqueryn("SELECT wp_num FROM $ut_weapons WHERE wp_type='$killweapon' LIMIT 1");
                    if (!$result) {
                        echo "Error reading weapons table.{$break}\n";

                        exit;
                    }
                    if ($row = $GLOBALS['xoopsDB']->fetchBoth($result)) {
                        $killweaponnum = $row['wp_num'];

                        $GLOBALS['xoopsDB']->freeRecordSet($result);
                    } else { // Add new weapon
                        $result = sqlqueryn("INSERT INTO $ut_weapons SET wp_type='$killweapon',wp_desc='$killweapon'");

                        if (!$result) {
                            echo "Error saving new weapon.{$break}\n";

                            exit;
                        }

                        $result = sqlqueryn('SELECT LAST_INSERT_ID()');

                        $row = $GLOBALS['xoopsDB']->fetchBoth($result);

                        $killweaponnum = (int)$row[0];
                    }

                    // Get Victim Weapon
                    $result = sqlqueryn("SELECT wp_num FROM $ut_weapons WHERE wp_type='$victweapon' LIMIT 1");
                    if (!$result) {
                        echo "Error reading weapons table (victim).{$break}\n";

                        exit;
                    }
                    if ($row = $GLOBALS['xoopsDB']->fetchBoth($result)) {
                        $victweaponnum = $row['wp_num'];

                        $GLOBALS['xoopsDB']->freeRecordSet($result);
                    } else { // Add new weapon
                        $result = sqlqueryn("INSERT INTO $ut_weapons SET wp_type='$victweapon',wp_desc='$victweapon'");

                        if (!$result) {
                            echo "Error saving new weapon (victim).{$break}\n";

                            exit;
                        }

                        $result = sqlqueryn('SELECT LAST_INSERT_ID()');

                        $row = $GLOBALS['xoopsDB']->fetchBoth($result);

                        $victweaponnum = (int)$row[0];
                    }

                    endspree($victim, $killtime, $reason, $killweaponnum, $killer); // End Killing Spree for Victim
                    endmulti($victim, $killtime); // End Multi-Kill for Victim
                    $gkills[$gkcount][0] = $killer;        // Killer
                    $gkills[$gkcount][1] = $victim;        // Victim
                    $gkills[$gkcount][2] = $killtime;      // Time
                    $gkills[$gkcount][3] = $killweaponnum; // Killer's Weapon Number
                    $gkills[$gkcount][4] = $victweaponnum; // Victim's Weapon Number
                    if ($killer >= 0) {
                        $gkills[$gkcount][5] = $player[$killer]['p_team'];
                    } // Killer Team
                    else {
                        $gkills[$gkcount][5] = -1;
                    }
                    if ($victim >= 0) {
                        $gkills[$gkcount++][6] = $player[$victim]['p_team'];
                    } // Victim Team
                    else {
                        $gkills[$gkcount++][6] = -1;
                    }
                    break;
                case 'TK': // Team Kill (teammate kill)
                    if (6 != $i || $ended) {
                        break;
                    }
                    $teamkills++;
                    $killtime = $data[0];
                    $killer = $data[2];
                    if ($killer >= 0 && $relog[$killer] >= 0) {
                        $killer = $relog[$killer];
                    }
                    $victim = $data[4];
                    if ($victim >= 0 && $relog[$victim] >= 0) {
                        $victim = $relog[$victim];
                    }
                    $killweapon = $data[3];
                    $victweapon = $data[5];
                    $player[$killer]['p_teamkills']++; // TeamKills
                    $player[$victim]['p_teamdeaths']++; // TeamDeaths

                    // Get Kill Weapon
                    $result = sqlqueryn("SELECT wp_num FROM $ut_weapons WHERE wp_type='$killweapon' LIMIT 1");
                    if (!$result) {
                        echo "Error reading weapons table.{$break}\n";

                        exit;
                    }
                    if ($row = $GLOBALS['xoopsDB']->fetchBoth($result)) {
                        $killweaponnum = $row['wp_num'];

                        $GLOBALS['xoopsDB']->freeRecordSet($result);
                    } else { // Add new weapon
                        $result = sqlqueryn("INSERT INTO $ut_weapons SET wp_type='$killweapon',wp_desc='$killweapon'");

                        if (!$result) {
                            echo "Error saving new weapon.{$break}\n";

                            exit;
                        }

                        $result = sqlqueryn('SELECT LAST_INSERT_ID()');

                        $row = $GLOBALS['xoopsDB']->fetchBoth($result);

                        $killweaponnum = (int)$row[0];
                    }

                    // Get Victim Weapon
                    $result = sqlqueryn("SELECT wp_num FROM $ut_weapons WHERE wp_type='$victweapon' LIMIT 1");
                    if (!$result) {
                        echo "Error reading weapons table (victim).{$break}\n";

                        exit;
                    }
                    if ($row = $GLOBALS['xoopsDB']->fetchBoth($result)) {
                        $victweaponnum = $row['wp_num'];

                        $GLOBALS['xoopsDB']->freeRecordSet($result);
                    } else { // Add new weapon
                        $result = sqlqueryn("INSERT INTO $ut_weapons SET wp_type='$victweapon',wp_desc='$victweapon'");

                        if (!$result) {
                            echo "Error saving new weapon (victim).{$break}\n";

                            exit;
                        }

                        $result = sqlqueryn('SELECT LAST_INSERT_ID()');

                        $row = $GLOBALS['xoopsDB']->fetchBoth($result);

                        $victweaponnum = (int)$row[0];
                    }

                    endspree($victim, $killtime, $reason, $killweaponnum, $killer); // End Killing Spree for Victim
                    endmulti($victim, $killtime); // End Multi-Kill for Victim
                    $gkills[$gkcount][0] = $killer;     // Killer
                    $gkills[$gkcount][1] = $victim;     // Victim
                    $gkills[$gkcount][2] = $killtime;   // Time
                    $gkills[$gkcount][3] = $killweaponnum; // Killer's Weapon Number
                    $gkills[$gkcount][4] = $victweaponnum; // Victim's Weapon Number
                    if ($killer >= 0) {
                        $gkills[$gkcount][5] = $player[$killer]['p_team'];
                    } // Killer Team
                    else {
                        $gkills[$gkcount][5] = -1;
                    }
                    if ($victim >= 0) {
                        $gkills[$gkcount++][6] = $player[$victim]['p_team'];
                    } // Victim Team
                    else {
                        $gkills[$gkcount++][6] = -1;
                    }
                    break;
                case 'S': // Score
                    if (5 != $i || $ended) {
                        break;
                    }
                    $time = $data[0];
                    $plr = $data[2];
                    $score = (int)$data[3];
                    if ($plr >= 0 && $relog[$plr] >= 0) {
                        $plr = $relog[$plr];
                    }
                    switch ($data[4]) {
                        case 'critical_frag':
                            if (1 == $player[$plr]['p_team']) {
                                $player[$plr]['p_t1score'] += $score;
                            } else {
                                $player[$plr]['p_t0score'] += $score;
                            }
                            $player[$plr]['p_typekill']++;
                            break;
                        case 'ball_thrown_final':
                            if (1 == $player[$plr]['p_team']) {
                                $player[$plr]['p_t1score'] += $score;
                            } else {
                                $player[$plr]['p_t0score'] += $score;
                            }
                            $player[$plr]['p_tossed']++;
                            break;
                        case 'ball_cap_final':
                            if (1 == $player[$plr]['p_team']) {
                                $player[$plr]['p_t1score'] += $score;
                            } else {
                                $player[$plr]['p_t0score'] += $score;
                            }
                            $player[$plr]['p_capcarry']++;
                            break;
                        case 'dom_score':
                            if (1 == $player[$plr]['p_team']) {
                                $player[$plr]['p_t1score'] += $score;
                            } else {
                                $player[$plr]['p_t0score'] += $score;
                            }
                            $player[$plr]['p_capcarry']++;
                            break;
                        case 'flag_cap_assist':
                            $assist[$plr] = 1;
                            if (1 == $player[$plr]['p_team']) {
                                $player[$plr]['p_t1score'] += $score;
                            } else {
                                $player[$plr]['p_t0score'] += $score;
                            }
                            break;
                        case 'frag':
                        case 'self_frag':
                        case 'team_frag':
                        case 'flag_cap_1st_touch':
                        case 'ball_score_assist':
                        case 'ball_score_1st_touch':
                        case 'flag_ret_friendly':
                        case 'flag_ret_enemy':
                        case 'flag_cap_final':
                        default:
                            if (1 == $player[$plr]['p_team']) {
                                $player[$plr]['p_t1score'] += $score;
                            } else {
                                $player[$plr]['p_t0score'] += $score;
                            }
                            break;
                    }
                    $tot_score += (int)$data[3];
                    $gscores[$gscount][0] = $plr;        // Player
                    $gscores[$gscount][1] = $time;       // Time
                    $gscores[$gscount][2] = $score;      // Score
                    $gscores[$gscount++][3] = $player[$plr]['p_team']; // Team
                    break;
                case 'T': // Team score
                    if (5 != $i || $ended) {
                        break;
                    }
                    $event = mb_strtolower($data[4]);
                    switch ($event) {
                        case 'tdm_frag':
                            teamscore($data[0], $data[2], $data[3], 1);
                            break;
                        case 'flag_cap':
                            teamscore($data[0], $data[2], $data[3], 2);
                            break;
                        case 'ball_carried':
                            teamscore($data[0], $data[2], $data[3], 3);
                            break;
                        case 'ball_tossed':
                            teamscore($data[0], $data[2], $data[3], 4);
                            break;
                        case 'dom_teamscore':
                            teamscore($data[0], $data[2], $data[3], 5);
                            break;
                        default:
                            teamscore($data[0], $data[2], $data[3], 0);
                            break;
                            break;
                    }
                    break;
                case 'EG': // End Game
                    if ($ended) {
                        break;
                    }
                    $event = mb_strtolower($data[2]);
                    $time = $data[0];
                    switch ($event) {
                        case 'fraglimit':
                        case 'timelimit':
                        case 'teamscorelimit':
                        case 'lastman':
                            $ended = 1;
                            break;
                        case 'mapchange':
                        case 'serverquit':
                        default:
                            $ended = 2;
                    }
                    $length = $time - $starttime;
                    for ($n = 0; $n <= $maxplayer; $n++) {
                        if (isset($relog[$n]) && $relog[$n] < 0) {
                            if (isset($player[$n]) && '' != $player[$n]['p_name'] && 1 == $player[$n]['p_connected']) {
                                $ptime = $time - $player[$n]['p_starttime'];

                                $player[$n]['p_totaltime'] += $ptime;
                            }

                            if (isset($player[$n])) {
                                endspree($n, $time, 0, 0, 0); // End Killing Sprees
                                endmulti($n, $time); // End Multi-Kills
                            }
                        }
                    }
                    gameevent($time, 1);
                    break;
                case 'I': // Item Pickup (2 = Player / 3 = Item)
                    if ($ended) {
                        break;
                    }
                    $item = $data[3];
                    $plr = $data[2];
                    if ($plr >= 0 && isset($relog[$plr]) && $relog[$plr] >= 0) {
                        $plr = $relog[$plr];
                    }
                    // Get Item Number
                    $result = sqlqueryn("SELECT it_num FROM $ut_items WHERE it_type='$item' LIMIT 1");
                    if (!$result) {
                        echo "Error reading items table.{$break}\n";

                        exit;
                    }
                    if ($row = $GLOBALS['xoopsDB']->fetchBoth($result)) {
                        $num = $row['it_num'];

                        if (isset($pickups[$plr][$num])) {
                            $pickups[$plr][$num]++;
                        } else {
                            $pickups[$plr][$num] = 1;
                        }

                        $GLOBALS['xoopsDB']->freeRecordSet($result);
                    } else { // Add new item
                        $result = sqlqueryn("INSERT INTO $ut_items SET it_type='$item',it_desc='$item'");

                        if (!$result) {
                            echo "Error saving new item.{$break}\n";

                            exit;
                        }

                        $result = sqlqueryn('SELECT LAST_INSERT_ID()');

                        $row = $GLOBALS['xoopsDB']->fetchBoth($result);

                        $num = (int)$row[0];

                        if (isset($pickups[$plr][$num])) {
                            $pickups[$plr][$num]++;
                        } else {
                            $pickups[$plr][$num] = 1;
                        }
                    }
                    if ($num > $maxpickups) {
                        $maxpickups = $num;
                    }
                    break;
                case 'V': // Chat
                    $plr = $data[2];
                    if ($plr >= 0 && $relog[$plr] >= 0) {
                        $plr = $relog[$plr];
                    }
                    if (!isset($player[$plr]) || '' == $player[$plr]['p_name']) {
                        $plr = -1;
                    }
                    $chatlog[$numchat][0] = $plr;
                    $chatlog[$numchat][1] = 0;
                    $chatlog[$numchat][2] = $data[0];
                    $chatlog[$numchat++][3] = $data[3];
                    break;
                case 'TV': // Team Chat
                case 'VT':
                    $plr = $data[2];
                    if ($plr >= 0 && $relog[$plr] >= 0) {
                        $plr = $relog[$plr];
                    }
                    if (!isset($player[$plr]) || '' == $player[$plr]['p_name']) {
                        $plr = -1;
                    }
                    $chatlog[$numchat][0] = $plr;
                    $chatlog[$numchat][1] = $player[$plr]['p_team'] + 1;
                    $chatlog[$numchat][2] = $data[0];
                    $chatlog[$numchat++][3] = $data[3];
                    break;
            }
        }
    }

    $numplayers = 0;

    for ($i = 0; $i <= 255; $i++) {
        if (isset($player[$i]) && isset($player[$i]['p_name']) && '' != $player[$i]['p_name']) {
            $numplayers++;
        }
    }

    // Calculate actual player rankings (frags, deaths, suicides or team score)

    if ($numplayers > 0) {
        $i = 0;

        for ($n = 0; $n <= $maxplayer; $n++) {
            if (isset($player[$n]) && isset($player[$n]['p_name']) && '' != $player[$n]['p_name']) {
                $ranks[0][$i] = $player[$n]['p_kills'] - $player[$n]['p_suicides'];

                $ranks[1][$i] = $player[$n]['p_deaths'];

                $ranks[2][$i] = $player[$n]['p_suicides'];

                $ranks[3][$i] = $n;

                $ranks[4][$i++] = $player[$n]['p_t0score'] + $player[$n]['p_t1score'];
            }
        }

        if ($gametnum > 1 && $gametnum < 6) { // Sort by Team Score for team games
            array_multisort(
                $ranks[4],
                SORT_DESC,
                SORT_NUMERIC,
                $ranks[0],
                SORT_DESC,
                SORT_NUMERIC,
                $ranks[1],
                SORT_ASC,
                SORT_NUMERIC,
                $ranks[2],
                SORT_ASC,
                SORT_NUMERIC,
                $ranks[3],
                SORT_ASC,
                SORT_NUMERIC
            );
        } else {
            array_multisort(
                $ranks[0],
                SORT_DESC,
                SORT_NUMERIC,
                $ranks[1],
                SORT_ASC,
                SORT_NUMERIC,
                $ranks[2],
                SORT_ASC,
                SORT_NUMERIC,
                $ranks[3],
                SORT_ASC,
                SORT_NUMERIC,
                $ranks[4],
                SORT_DESC,
                SORT_NUMERIC
            );
        }

        for ($i = 0; $i < $numplayers; $i++) {
            $player[$ranks[3][$i]]['p_rank'] = $i + 1;
        }
    }

    if ($ended && $ended < 4) {
        if (!$started || !$ngfound) {
            $ended = 3;
        }
    }

    if (1 == $ended && $numplayers < 2) {
        $ended = 5;
    }

    $server = [
        $map,
        $gametype,
        $gametnum,
        $matchdate,
        $mutators,
        $fraglimit,
        $timelimit,
        $minplayers,
        $translocator,
        $length,
        $password,
        $gamestats,
        $firstblood,
        $starttime,
        $ended,
        $maxplayer,
        $servername,
        $admin,
        $email,
        $tot_score,
        $team,
        $tot_kills,
        $tot_deaths,
        $tot_suicides,
        $teamkills,
        $numplayers,
        $maxpickups,
        $numevents,
        $headshots,
        $numchat,
    ];

    // 1 = Ended Normally / 2 = Mapswitch, etc. / 3 = No 'NG' or 'SG' found / 4 = Existing Game

    return $ended;
}
