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

require 'maininc.php';

if (!$gamenum) {
    echo "Run from the main index program.<br>\n";

    exit;
}

$link = sqlquery_connect();

// Load game data
$result = sqlqueryn("SELECT * FROM $ut_games WHERE gm_num = '$gamenum' LIMIT 1");
if (!$result) {
    echo "Games database error.<br>\n";

    exit;
}
$row = $GLOBALS['xoopsDB']->fetchBoth($result);
if (!$row) {
    echo "Game not found in database.<br>\n";

    exit;
}
while (list($key, $val) = each($row)) {
    ${$key} = $val;
}

// Set game type
$result = sqlqueryn("SELECT tp_type FROM $ut_type WHERE tp_num='$gm_type' LIMIT 1");
$row = $GLOBALS['xoopsDB']->fetchBoth($result);
if (!$row) {
    echo "Error locating game type.<br>\n";

    exit;
}
$gametval = $row['tp_type'];
$GLOBALS['xoopsDB']->freeRecordSet($result);

// Load Players
$result = sqlqueryn("SELECT gp_num,gp_pnum,gp_bot FROM $ut_gplayers WHERE gp_game='$gamenum'");
if (!$result) {
    echo "Game player list database error.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $num = $row['gp_num'];

    $gplayer[$num] = $row;

    // Get current player name

    $pnum = $row['gp_pnum'];

    $result2 = sqlqueryn("SELECT plr_name FROM $ut_players WHERE pnum='$pnum' LIMIT 1");

    if (!$result2) {
        $gplayer[$num]['gp_name'] = "Player $num";
    } // Player not found

    else {
        $row = $GLOBALS['xoopsDB']->fetchBoth($result2);

        $gplayer[$num]['gp_name'] = $row['plr_name'];

        $GLOBALS['xoopsDB']->freeRecordSet($result2);
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

// Load Weapon Descriptions
$result = sqlqueryn("SELECT wp_num,wp_desc FROM $ut_weapons");
if (!$result) {
    echo "Error loading weapons descriptions.<br>\n";

    exit;
}
$maxweapon = 0;
$weapons = [];
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $num = $row['wp_num'];

    $weapons[$num] = $row['wp_desc'];

    if ($num > $maxweapon) {
        $maxweapon = $num;
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

//=============================================================================
//========== Chat Log =========================================================
//=============================================================================

$start = strtotime($gm_start);
$matchdate = date('D, M d Y \a\t g:i:s A', $start);

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="1" BORDER="0" CLASS="box">
  <tr>
    <td CLASS="heading" COLSPAN="3" ALIGN="center">Chat Log for $gm_server - $gm_map<br>on $matchdate</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="50">Time</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="200">Player</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="450">Text</td>
  </tr>

EOF;

//========== System Events ====================================================
$numchat = 0;
$chatlog = [];
$result = sqlqueryn("SELECT * FROM $ut_gevents WHERE ge_game='$gamenum' && ge_event BETWEEN '2' AND '5' ORDER BY ge_time");
if (!$result) {
    echo "Error loading events log.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $time = $row['ge_time'];

    $event = $row['ge_event'];

    $plr = $row['ge_plr'];

    $reas = $row['ge_reason'];

    switch ($event) {
        case 2: // Connect/Disconnect
            switch ($reas) {
                case 0:
                    if ($gametval < 2 || $gametval > 4) {
                        $reason = 'Connected';
                    } else {
                        $reason = '';
                    }
                    $prior = 2;
                    break;
                case 1:
                    $reason = 'Disconnected';
                    $prior = 4;
                    break;
            }
            $sysclass = 'chatsys';
            break;
        case 3: // Game Start/End
            switch ($reas) {
                case 0:
                    $reason = 'Game Start';
                    $prior = 1;
                    break;
                case 1:
                    $reason = 'Game Ended';
                    $prior = 5;
                    break;
            }
            $sysclass = 'chatsys';
            $plr = -1;
            break;
        case 4: // Team Change
            if ($row['ge_quant']) {
                $reason = 'Team Change to Blue Team';

                $sysclass = 'chatblue';

                $prior = 3;
            } else {
                $reason = 'Team Change to Red Team';

                $sysclass = 'chatred';

                $prior = 3;
            }
            break;
        case 5: // Team Score
            switch ($reas) {
                case 1:
                    if ($plr) {
                        $reason = 'Blue Team Scored Frag!';
                    } else {
                        $reason = 'Red Team Scored Frag!';
                    }
                    break;
                case 2:
                    if ($plr) {
                        $reason = 'Blue Team Captured Flag!';
                    } else {
                        $reason = 'Red Team Captured Flag!';
                    }
                    break;
                case 3:
                    if ($plr) {
                        $reason = 'Blue Team Carried Ball for Score!';
                    } else {
                        $reason = 'Red Team Carried Ball for Score!';
                    }
                    break;
                case 4:
                    if ($plr) {
                        $reason = 'Blue Team Scored Ball Throw!';
                    } else {
                        $reason = 'Red Team Scored Ball Throw!';
                    }
                    break;
                case 5:
                    if ($plr) {
                        $reason = 'Blue Team Dominated!';
                    } else {
                        $reason = 'Red Team Dominated!';
                    }
                    break;
                default:
                    if ($plr) {
                        $reason = 'Blue Team Scored!';
                    } else {
                        $reason = 'Red Team Scored!';
                    }
                    break;
            }
            if ($plr) {
                $sysclass = 'blueteamscore';
            } else {
                $sysclass = 'redteamscore';
            }
            $plr = -1;
            $prior = 2;
            break;
    }

    if ('' != $reason) {
        $chatlog[0][$numchat] = $time;

        $chatlog[1][$numchat] = $plr;

        $chatlog[2][$numchat] = 0;

        $chatlog[3][$numchat] = $sysclass;

        $chatlog[4][$numchat] = $reason;

        $chatlog[5][$numchat++] = $prior;
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

//========== Kills ============================================================
$result = sqlqueryn("SELECT * FROM $ut_gkills WHERE gk_game='$gamenum'");
if (!$result) {
    echo "Error reading gkills data.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $killern = $row['gk_killer'];

    $victimn = $row['gk_victim'];

    $weapon = $weapons[$row['gk_kweapon']];

    if ($killern < 0) {
        $chatlog[1][$numchat] = $victimn;

        if (!strcmp($weapon, 'Suicided') || !strcmp($weapon, 'Drowned')) {
            $chatlog[4][$numchat] = (string)$weapon;
        } elseif (!strcmp($weapon, 'Corroded') || !strcmp($weapon, 'Crushed') || !strcmp($weapon, 'Gibbed') || !strcmp($weapon, 'Depressurized')) {
            $chatlog[4][$numchat] = "Was $weapon";
        } elseif (!strcmp($weapon, 'Fell')) {
            $chatlog[4][$numchat] = 'Fell to their death';
        } elseif (!strcmp($weapon, 'Fell Into Lava')) {
            $chatlog[4][$numchat] = 'Fell into Lava';
        } elseif (!strcmp($weapon, 'Swam Too Far')) {
            $chatlog[4][$numchat] = 'Tried to Swim Too Far';
        } else {
            $wfl = mb_strtoupper($weapon[0]);

            if ('A' == $wfl || 'E' == $wfl || 'I' == $wfl || 'O' == $wfl || 'U' == $wfl || 'Y' == $wfl) {
                $chatlog[4][$numchat] = "Died from an $weapon";
            } else {
                $chatlog[4][$numchat] = "Died from a $weapon";
            }
        }
    } elseif ($killern == $victimn) {
        $chatlog[1][$numchat] = $killern;

        if (!strcmp($weapon, 'Suicided') || !strcmp($weapon, 'Drowned')) {
            $chatlog[4][$numchat] = (string)$weapon;
        } elseif (!strcmp($weapon, 'Corroded') || !strcmp($weapon, 'Crushed') || !strcmp($weapon, 'Gibbed') || !strcmp($weapon, 'Depressurized')) {
            $chatlog[4][$numchat] = "Was $weapon";
        } elseif (!strcmp($weapon, 'Fell')) {
            $chatlog[4][$numchat] = 'Fell to their death';
        } elseif (!strcmp($weapon, 'Fell Into Lava')) {
            $chatlog[4][$numchat] = 'Fell into Lava';
        } elseif (!strcmp($weapon, 'Swam Too Far')) {
            $chatlog[4][$numchat] = 'Tried to Swim Too Far';
        } else {
            $wfl = mb_strtoupper($weapon[0]);

            if ('A' == $wfl || 'E' == $wfl || 'I' == $wfl || 'O' == $wfl || 'U' == $wfl || 'Y' == $wfl) {
                $chatlog[4][$numchat] = "Suicided with an $weapon";
            } else {
                $chatlog[4][$numchat] = "Suicided with a $weapon";
            }
        }
    } else {
        $victim = $gplayer[$row['gk_victim']]['gp_name'];

        $chatlog[1][$numchat] = $killern;

        if (!strcmp($weapon, 'Crushed') || !strcmp($weapon, 'Telefragged') || !strcmp($weapon, 'Depressurized')) {
            $chatlog[4][$numchat] = "$weapon $victim";
        } else {
            $wfl = mb_strtoupper($weapon[0]);

            if ('A' == $wfl || 'E' == $wfl || 'I' == $wfl || 'O' == $wfl || 'U' == $wfl || 'Y' == $wfl) {
                $chatlog[4][$numchat] = "Killed $victim with an $weapon";
            } else {
                $chatlog[4][$numchat] = "Killed $victim with a $weapon";
            }
        }
    }

    $chatlog[0][$numchat] = $row['gk_time'];

    $chatlog[2][$numchat] = 0;

    $chatlog[3][$numchat] = 'chatkill';

    $chatlog[5][$numchat++] = 2;
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

//========== Chat =============================================================
$result = sqlqueryn("SELECT * FROM $ut_gchat WHERE gc_game='$gamenum' ORDER BY gc_time");
if (!$result) {
    echo "Error loading chat log.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $chatlog[0][$numchat] = $row['gc_time'];

    $chatlog[1][$numchat] = $row['gc_plr'];

    $chatlog[2][$numchat] = $row['gc_team'];

    $text = $row['gc_text'];

    switch ($row['gc_team']) {
        case 1:
            $chatcol = 'chatred';
            break;
        case 2:
            $chatcol = 'chatblue';
            break;
        default:
            $chatcol = 'chat';
            $sadmin = mb_substr($text, -37);
            if ($sadmin && !strcmp($sadmin, ' logged in as a server administrator.')) {
                $chatcol = 'chatsys';
            }
    }

    $chatlog[3][$numchat] = (string)$chatcol;

    $chatlog[4][$numchat] = $text;

    $chatlog[5][$numchat++] = 3;
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

if ($numchat) {
    array_multisort(
        $chatlog[0],
        SORT_ASC,
        SORT_NUMERIC,
        $chatlog[5],
        SORT_ASC,
        SORT_NUMERIC,
        $chatlog[1],
        SORT_ASC,
        SORT_STRING,
        $chatlog[2],
        SORT_ASC,
        SORT_NUMERIC,
        $chatlog[3],
        SORT_DESC,
        SORT_STRING,
        $chatlog[4],
        SORT_ASC,
        SORT_STRING
    );
}

for ($i = 0; $i < $numchat; $i++) {
    $time = sprintf('%0.1f', $chatlog[0][$i] / 60);

    $plr = $chatlog[1][$i];

    if ($plr >= 0) {
        $name = $gplayer[$plr]['gp_name'];

        $bot = $gplayer[$plr]['gp_bot'];

        if ($bot) {
            $nameclass = 'darkbot';
        } else {
            $nameclass = 'darkhuman';
        }
    } else {
        $name = '';

        $nameclass = 'dark';
    }

    $team = $chatlog[2][$i];

    $cclass = $chatlog[3][$i];

    $text = htmlspecialchars($chatlog[4][$i], ENT_QUOTES | ENT_HTML5);

    echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$time</td>
    <td CLASS="$nameclass" ALIGN="center">$name</td>
    <td CLASS="$cclass" ALIGN="left">$text</td>
  </tr>

EOF;
}
echo "</table>\n";

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="1" BORDER="0" CLASS="box">
  <tr>
    <td CLASS="smheading" ALIGN="center" COLSPAN="2" WIDTH="350">Chat Color Key</td>
  </tr>
  <tr>
    <td CLASS="chat" ALIGN="left"><font BACKGROUND="#ffffff">Player Chat Messages</td>
    <td CLASS="chatsys" ALIGN="left">Game Event Messages</td>
  </tr>
  <tr>
    <td CLASS="chatblue" ALIGN="left">Blue Team Chat Messages</td>
    <td CLASS="chatkill" ALIGN="left">Kill/Suicide Events</td>
  </tr>
  <tr>
    <td CLASS="chatred" ALIGN="left">Red Team Chat Messages</td>
    <td CLASS="blueteamscore" ALIGN="left">Blue Team Score Events</td>
  </tr>
  <tr>
    <td CLASS="chat" ALIGN="left">&nbsp;</td>
    <td CLASS="redteamscore" ALIGN="left">Red Team Score Events</td>
  </tr>
</table>

EOF;

$GLOBALS['xoopsDB']->close($link);

echo <<<EOF
</center>

</td></tr></table>

EOF;

require XOOPS_ROOT_PATH . '/footer.php';
