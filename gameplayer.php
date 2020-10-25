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

if (!$gamenum || $plr < 0) {
    echo "Run from the main index program.<br>\n";

    exit;
}

$link = sqlquery_connect();

// Load game types
$numtypes = 0;
$result = sqlqueryn("SELECT * FROM $ut_type");
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $gtype[$numtypes++] = $row;
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

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

$gametype = '';
$gametval = 0;
for ($i = 0; $i < $numtypes && !$gametype; $i++) {
    if ($gtype[$i][0] == $gm_type) {
        $gametype = $gtype[$i][1];

        $gametval = $gtype[$i][2];
    }
}
$start = strtotime($gm_start);
$matchdate = date('D, M d Y \a\t g:i:s A', $start);

// Load Player
$result = sqlqueryn("SELECT * FROM $ut_gplayers WHERE gp_game='$gamenum' && gp_num='$plr' LIMIT 1");
if (!$result) {
    echo "Game player list database error.<br>\n";

    exit;
}
$row = $GLOBALS['xoopsDB']->fetchBoth($result);
if (!$row) {
    echo "Invalid player number for game.<br>\n";

    exit;
}
$GLOBALS['xoopsDB']->freeRecordSet($result);
while (list($key, $val) = each($row)) {
    ${$key} = $val;
}

// Get current player name
$result = sqlqueryn("SELECT plr_name FROM $ut_players WHERE pnum='$gp_pnum' LIMIT 1");
if (!$result) {
    $gp_name = "Player $gp_num";
} // Player not found
else {
    $row = $GLOBALS['xoopsDB']->fetchBoth($result);

    $gp_name = $row['plr_name'];

    $GLOBALS['xoopsDB']->freeRecordSet($result);
}

if (isset($password) && $password) {
    $pw = 'Enabled';
} else {
    $pw = 'Disabled';
}
if (isset($gamestats) && $gamestats) {
    $stats = 'Enabled';
} else {
    $stats = 'Disabled';
}
if ($gm_translocator) {
    $trans = 'Enabled';
} else {
    $trans = 'Disabled';
}

if ($gametval > 1) {
    $tlabel = 'Score';
} else {
    $tlabel = 'Frag';
}

echo <<<EOF
<center>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="720">
  <tr>
    <td CLASS="heading" ALIGN="center">Individual Game Stats for $gp_name</td>
  </tr>
</table>
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="650" CLASS="box">
  <tr>
    <td CLASS="heading" COLSPAN="4" ALIGN="center">Match Information</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center" WIDTH="80">Match Date</td>
    <td CLASS="grey" ALIGN="center" WIDTH="220">$matchdate</td>
    <td CLASS="dark" ALIGN="center" WIDTH="90">Server</td>
    <td CLASS="grey" ALIGN="center">$gm_server</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Game Type</td>
    <td CLASS="grey" ALIGN="center">$gametype</td>
    <td CLASS="dark" ALIGN="center">Admin Name</td>
    <td CLASS="grey" ALIGN="center">$gm_admin</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Map Name</td>
    <td CLASS="grey" ALIGN="center">$gm_map</td>
    <td CLASS="dark" ALIGN="center">Admin Email</td>
    <td CLASS="grey" ALIGN="center">$gm_email</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Mutators</td>
    <td CLASS="grey" ALIGN="center">$gm_mutators</td>
    <td CLASS="dark" ALIGN="center">Global Stats</td>
    <td CLASS="grey" ALIGN="center">$stats</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">$tlabel Limit</td>
    <td CLASS="grey" ALIGN="center">$gm_fraglimit</td>
    <td CLASS="dark" ALIGN="center">Translocator</td>
    <td CLASS="grey" ALIGN="center">$trans</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Time Limit</td>
    <td CLASS="grey" ALIGN="center">$gm_timelimit</td>
    <td CLASS="dark" ALIGN="center">No. Players</td>
    <td CLASS="grey" ALIGN="center">$gm_numplayers</td>
  </tr>
</table>

EOF;

//=============================================================================
//========== Game Summary =====================================================
//=============================================================================

$frags = $gp_kills - $gp_suicides;
if (0 == $gp_kills + $gp_deaths + $gp_suicides) {
    $eff = '0.0';
} else {
    $eff = sprintf('%0.1f', ($gp_kills / ($gp_kills + $gp_deaths + $gp_suicides)) * 100.0);
}
if (0 == $gp_time) {
    $fph = '0.0';
} else {
    $fph = sprintf('%0.1f', $frags * (3600 / $gp_time));
}
$ttl = sprintf('%0.1f', $gp_time / ($gp_deaths + $gp_suicides + 1));
$time = sprintf('%0.1f', $gp_time / 60.0);

if ($gp_bot) {
    $nameclass = 'darkbot';
} else {
    $nameclass = 'darkhuman';
}

echo <<<EOF
<br>
<table CELLPADDING="0" CELLSPACING="2" BORDER="0" WIDTH="600">
  <tr>
    <td CLASS="heading" COLSPAN="15" ALIGN="center">Game Summary</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="40">Rank</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="40">Frags</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="40">Kills</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="50">Deaths</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="60">Suicides</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="70">Efficiency</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="50">Avg. FPH</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="50">Avg. TTL</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="45">Time</td>
    <td CLASS="smheading" ALIGN="center" COLSPAN="6">Sprees</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">K</td>
    <td CLASS="smheading" ALIGN="center">R</td>
    <td CLASS="smheading" ALIGN="center">D</td>
    <td CLASS="smheading" ALIGN="center">U</td>
    <td CLASS="smheading" ALIGN="center">G</td>
    <td CLASS="smheading" ALIGN="center">W</td>
  </tr>
  <tr>
    <td CLASS="grey" ALIGN="center">$gp_rank</td>
    <td CLASS="grey" ALIGN="center">$frags</td>
    <td CLASS="grey" ALIGN="center">$gp_kills</td>
    <td CLASS="grey" ALIGN="center">$gp_deaths</td>
    <td CLASS="grey" ALIGN="center">$gp_suicides</td>
    <td CLASS="grey" ALIGN="center">$eff%</td>
    <td CLASS="grey" ALIGN="center">$fph</td>
    <td CLASS="grey" ALIGN="center">$ttl</td>
    <td CLASS="grey" ALIGN="center">$time</td>
    <td CLASS="grey" ALIGN="center">$gp_spree1</td>
    <td CLASS="grey" ALIGN="center">$gp_spree2</td>
    <td CLASS="grey" ALIGN="center">$gp_spree3</td>
    <td CLASS="grey" ALIGN="center">$gp_spree4</td>
    <td CLASS="grey" ALIGN="center">$gp_spree5</td>
    <td CLASS="grey" ALIGN="center">$gp_spree6</td>
  </tr>
</table>

EOF;

//=============================================================================
//========== Special Events ===================================================
//=============================================================================

if ($gm_firstblood == $gp_num) {
    $fb = 'Yes';
} else {
    $fb = 'No';
}

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="500">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Special Events</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Category</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="45">Value</td>
    <td CLASS="smheading" ALIGN="center">Category</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="45">Value</td>
    <td CLASS="smheading" ALIGN="center">Category</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="45">Value</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">First Blood</td>
    <td CLASS="grey" ALIGN="center">$fb</td>
    <td CLASS="dark" ALIGN="center">Head Shots</td>
    <td CLASS="grey" ALIGN="center">$gp_headshots</td>
    <td CLASS="dark" ALIGN="center">Failed Translocations</td>
    <td CLASS="grey" ALIGN="center">$gp_transgib</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Double Kills</td>
    <td CLASS="grey" ALIGN="center">$gp_multi1</td>
    <td CLASS="dark" ALIGN="center">Multi Kills</td>
    <td CLASS="grey" ALIGN="center">$gp_multi2</td>
    <td CLASS="dark" ALIGN="center">Mega Kills</td>
    <td CLASS="grey" ALIGN="center">$gp_multi3</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Ultra Kills</td>
    <td CLASS="grey" ALIGN="center">$gp_multi4</td>
    <td CLASS="dark" ALIGN="center">Monster Kills</td>
    <td CLASS="grey" ALIGN="center">$gp_multi5</td>
    <td CLASS="dark" ALIGN="center">Ludicrous Kills</td>
    <td CLASS="grey" ALIGN="center">$gp_multi6</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Holy Shit Kills</td>
    <td CLASS="grey" ALIGN="center">$gp_multi7</td>
    <td CLASS="dark" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
    <td CLASS="dark" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
  </tr>
</table>

EOF;

//=============================================================================
//========== Weapon Specific Information ======================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="540">
  <tr>
    <td CLASS="heading" COLSPAN="7" ALIGN="center">Weapon Specific Information</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Weapon</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Frags</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Primary Kills</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="70">Secondary Kills</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Deaths</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Suicides</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Eff.</td>
  </tr>

EOF;

// Load Weapon Descriptions
$result = sqlqueryn("SELECT wp_num,wp_secondary,wp_desc FROM $ut_weapons");
if (!$result) {
    echo "Error loading weapons descriptions.<br>\n";

    exit;
}
$maxweapon = 0;
$weapons = [];
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $num = $row['wp_num'];

    $weapons[$num][0] = $row['wp_desc'];

    $weapons[$num][1] = $row['wp_secondary'];

    if ($num > $maxweapon) {
        $maxweapon = $num;
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

$wskills = [[]];
/* wskills:
 0 = Primary Kills
 1 = Secondary Kills
 2 = Deaths
 3 = Suicides
 4 = Weapon Description
 5 = Frags
*/
$numweapons = 0;
// Load Weapon Kills for current game
$result = sqlqueryn("SELECT gk_killer,gk_victim,gk_kweapon,gk_vweapon FROM $ut_gkills WHERE gk_game='$gamenum'");
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $killer = $row['gk_killer'];

    $victim = $row['gk_victim'];

    $weap = $row['gk_kweapon'];

    $hweap = $row['gk_vweapon'];

    if ($killer == $gp_num || $victim == $gp_num) {
        // Look for existing kill weapon in wskills description

        $weapon = -1;

        $secondary = 0;

        for ($i = 0; $i < $numweapons; $i++) {
            if ($weap > 0 && $weapon < 0 && !strcmp($wskills[4][$i], $weapons[$weap][0])) {
                $weapon = $i;

                $secondary = $weapons[$weap][1];
            }
        }

        // Add killer's weapon if not already used

        if ($weap > 0 && $weapon < 0) {
            $wskills[0][$numweapons] = $wskills[1][$numweapons] = 0; // Primary Kills / Secondary Kills
            $wskills[2][$numweapons] = $wskills[3][$numweapons] = 0; // Deaths / Suicides
            $wskills[4][$numweapons] = $weapons[$weap][0]; // Description
            $weapon = $numweapons++;

            $secondary = $weapons[$weap][1];
        }

        // Look for existing held weapon in wskills description

        $held = -1;

        for ($i = 0; $i < $numweapons; $i++) {
            if ($hweap > 0 && $held < 0 && !strcmp($wskills[4][$i], $weapons[$hweap][0])) {
                $held = $i;
            }
        }

        // Add victim's weapon if not already used

        if ($hweap > 0 && $held < 0) {
            $wskills[0][$numweapons] = $wskills[1][$numweapons] = 0; // Primary Kills / Secondary Kills
            $wskills[2][$numweapons] = $wskills[3][$numweapons] = 0; // Deaths / Suicides
            $wskills[4][$numweapons] = $weapons[$hweap][0]; // Description
            $held = $numweapons++;
        }

        if ($killer < 0) {
            if ($victim == $gp_num) {
                $wskills[3][$weapon]++;
            } // Event Suicide
        } elseif ($killer == $victim) {
            if ($killer == $gp_num) {
                $wskills[3][$weapon]++;
            } // Suicide
        } else {
            if ($killer == $gp_num) {
                if ($secondary) {
                    $wskills[1][$weapon]++;
                } // Secondary Kill

                else {
                    $wskills[0][$weapon]++;
                } // Primary Kill
            }

            if ($victim == $gp_num) {
                $wskills[2][$held]++;
            }   // In-hand
        }
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

if ($numweapons > 0) {
    for ($i = 0; $i < $numweapons; $i++) {
        $wskills[5][$i] = ($wskills[0][$i] + $wskills[1][$i]) - $wskills[3][$i];
    }

    array_multisort(
        $wskills[5],
        SORT_DESC,
        SORT_NUMERIC,
        $wskills[0],
        SORT_DESC,
        SORT_NUMERIC,
        $wskills[1],
        SORT_DESC,
        SORT_NUMERIC,
        $wskills[2],
        SORT_ASC,
        SORT_NUMERIC,
        $wskills[3],
        SORT_ASC,
        SORT_NUMERIC,
        $wskills[4],
        SORT_ASC,
        SORT_STRING
    );

    for ($i = 0; $i < $numweapons; $i++) {
        $weapon = $wskills[4][$i];

        $kills = $wskills[0][$i];

        $skills = $wskills[1][$i];

        $deaths = $wskills[2][$i];

        $suicides = $wskills[3][$i];

        $frags = $wskills[5][$i];

        if (($kills || $skills || $deaths) && strcmp($weapon, 'None')) {
            if (0 == $kills + $skills + $deaths + $suicides) {
                $eff = '0.0';
            } else {
                $eff = sprintf('%0.1f', (($kills + $skills) / ($kills + $skills + $deaths + $suicides)) * 100.0);
            }

            echo <<< EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$weapon</td>
    <td CLASS="grey" ALIGN="center">$frags</td>
    <td CLASS="grey" ALIGN="center">$kills</td>
    <td CLASS="grey" ALIGN="center">$skills</td>
    <td CLASS="grey" ALIGN="center">$deaths</td>
    <td CLASS="grey" ALIGN="center">$suicides</td>
    <td CLASS="grey" ALIGN="center">$eff%</td>
  </tr>

EOF;
        }
    }
} else {
    echo <<< EOF
  <tr>
    <td CLASS="grey" ALIGN="center" COLSPAN="7">No Weapon Kills or Deaths</td>
  </tr>

EOF;
}
echo "</table>\n";

//=============================================================================
//========== Suicides =========================================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="255">
  <tr>
    <td CLASS="heading" ALIGN="center" COLSPAN="2">Suicides</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Type</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Suicides</td>
  </tr>

EOF;

if ($gp_suicides > 0) {
    array_multisort(
        $wskills[3],
        SORT_DESC,
        SORT_NUMERIC,
        $wskills[5],
        SORT_DESC,
        SORT_NUMERIC,
        $wskills[0],
        SORT_DESC,
        SORT_NUMERIC,
        $wskills[1],
        SORT_DESC,
        SORT_NUMERIC,
        $wskills[2],
        SORT_ASC,
        SORT_NUMERIC,
        $wskills[4],
        SORT_ASC,
        SORT_STRING
    );

    for ($i = 0; $i < $numweapons; $i++) {
        if ($wskills[3][$i] > 0) {
            $type = $wskills[4][$i];

            $suicides = $wskills[3][$i];

            echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$type</td>
    <td CLASS="grey" ALIGN="center">$suicides</td>
  </tr>

EOF;
        }
    }
} else {
    echo <<<EOF
  <tr>
    <td CLASS="grey" ALIGN="center" COLSPAN="2">No Suicides</td>
  </tr>

EOF;
}
echo "</table>\n";

//=============================================================================
//========== Player Specific Kills and Deaths =================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="580">
  <tr>
    <td CLASS="heading" COLSPAN="8" ALIGN="center">Player Specific Kills and Deaths</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Opponent</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="40">Kills</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="45">Deaths</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="65">Efficiency</td>
    <td CLASS="smheading" ALIGN="center">Opponent</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="40">Kills</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="45">Deaths</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="65">Efficiency</td>
  </tr>

EOF;

// Load Player Names
$maxplayer = 0;
$result = sqlqueryn("SELECT gp_num,gp_pnum,gp_bot FROM $ut_gplayers WHERE gp_game = '$gamenum'");
if (!$result) {
    echo "Game player list database error.<br>\n";

    exit;
}
$numplr = 0;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $num = $row['gp_num'];

    $gplayer[$num] = $row;

    if ($num > $maxplayer) {
        $maxplayer = $num;
    }

    $opkills[0][$num] = $num;

    $opkills[1][$num] = $opkills[2][$num] = 0;

    $numplr++;

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

// Read Kill Log
$result = sqlqueryn("SELECT gk_killer,gk_victim FROM $ut_gkills WHERE gk_game='$gamenum'");
if (!$result) {
    echo "Error reading gkills player data.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $killer = $row['gk_killer'];

    $victim = $row['gk_victim'];

    if ($killer == $plr && $victim != $plr && isset($opkills[0][$victim])) {
        $opkills[1][$victim]++;
    } // Kills

    elseif ($victim == $plr && $killer >= 0 && $killer != $plr && isset($opkills[0][$killer])) {
        $opkills[2][$killer]++;
    } // Deaths
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

array_multisort(
    $opkills[1],
    SORT_DESC,
    SORT_NUMERIC,
    $opkills[2],
    SORT_DESC,
    SORT_NUMERIC,
    $opkills[0],
    SORT_ASC,
    SORT_NUMERIC
);

$col = 0;
for ($i = 0; $i < $numplr; $i++) {
    if ($opkills[0][$i] != $plr) {
        $kills = $opkills[1][$i];

        $deaths = $opkills[2][$i];

        if ($kills || $deaths) {
            if ($col > 1) {
                $col = 0;
            }

            if (0 == $col) {
                echo "  <tr>\n";
            }

            $opp = $opkills[0][$i];

            if ($kills + $deaths > 0) {
                $eff = sprintf('%0.1f', $kills / ($kills + $deaths) * 100);
            } else {
                $eff = '0.0';
            }

            $name = $gplayer[$opp]['gp_name'];

            if ($gplayer[$opp]['gp_bot']) {
                $nameclass = 'darkbot';
            } else {
                $nameclass = 'darkhuman';
            }

            echo <<<EOF
    <td CLASS="$nameclass" ALIGN="center">$name</td>
    <td CLASS="grey" ALIGN="center">$kills</td>
    <td CLASS="grey" ALIGN="center">$deaths</td>
    <td CLASS="grey" ALIGN="center">$eff%</td>

EOF;

            if (1 == $col) {
                echo "  </tr>\n";
            }

            $col++;
        }
    }
}
if ($col > 0) {
    while ($col < 2) {
        echo <<<EOF
    <td CLASS="dark" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>

EOF;

        $col++;
    }

    echo "  </tr>\n";
}
echo "</table>\n";

//=============================================================================
//========== Killing Sprees By Type ===========================================
//=============================================================================

$result = sqlqueryn("SELECT * FROM $ut_gevents WHERE ge_event='1' && ge_game='$gamenum' && ge_plr='$plr' ORDER BY ge_time");
if (!$result) {
    echo "Error loading events.<br>\n";

    exit;
}
$sprees = $header = 0;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    if ($row['ge_quant'] >= 5) {
        while (list($key, $val) = each($row)) {
            ${$key} = $val;
        }

        $time = sprintf('%0.1f', ($ge_time - $ge_length) / 60);

        $length = sprintf('%0.1f', $ge_length / 60);

        $type = '';

        if ($ge_quant >= 5 && $ge_quant < 10) {
            $type = 'Killing Spree';
        } elseif ($ge_quant >= 10 && $ge_quant < 15) {
            $type = 'Rampage';
        } elseif ($ge_quant >= 15 && $ge_quant < 20) {
            $type = 'Dominating';
        } elseif ($ge_quant >= 20 && $ge_quant < 25) {
            $type = 'Unstoppable';
        } elseif ($ge_quant >= 25 && $ge_quant < 30) {
            $type = 'Godlike';
        } elseif ($ge_quant >= 30) {
            $type = 'Wicked Sick';
        }

        switch ($ge_reason) {
            case 0: // Game Ended
                $reason = 'Game Ended';
                break;
            case 1: // Killed by {player} with a {weapon}
                $killer = $gplayer[$ge_opponent]['gp_name'];
                $weapon = $weapons[$ge_item][0];
                if (!strcmp($weapon, 'Crushed') || !strcmp($weapon, 'Telefragged') || !strcmp($weapon, 'Depressurized')) {
                    $reason = "$weapon by $killer";
                } else {
                    $wfl = mb_strtoupper($weapon[0]);

                    if ('A' == $wfl || 'E' == $wfl || 'I' == $wfl || 'O' == $wfl || 'U' == $wfl || 'Y' == $wfl) {
                        $reason = "Killed by $killer with an $weapon";
                    } else {
                        $reason = "Killed by $killer with a $weapon";
                    }
                }
                break;
            case 2: // Suicided with {weapon}
                $weapon = $weapons[$ge_item][0];
                if (!strcmp($weapon, 'Suicided') || !strcmp($weapon, 'Drowned')) {
                    $reason = (string)$weapon;
                } elseif (!strcmp($weapon, 'Corroded') || !strcmp($weapon, 'Crushed') || !strcmp($weapon, 'Gibbed') || !strcmp($weapon, 'Depressurized')) {
                    $reason = "Was $weapon";
                } elseif (!strcmp($weapon, 'Fell')) {
                    $reason = 'Fell to their death';
                } elseif (!strcmp($weapon, 'Fell Into Lava')) {
                    $reason = 'Fell into Lava';
                } elseif (!strcmp($weapon, 'Swam Too Far')) {
                    $reason = 'Tried to Swim Too Far';
                } else {
                    $wfl = mb_strtoupper($weapon[0]);

                    if ('A' == $wfl || 'E' == $wfl || 'I' == $wfl || 'O' == $wfl || 'U' == $wfl || 'Y' == $wfl) {
                        $reason = "Suicided with an $weapon";
                    } else {
                        $reason = "Suicided with a $weapon";
                    }
                }
                break;
            case 3: // Died from {weapon}
                $weapon = $weapons[$ge_item][0];
                if (!strcmp($weapon, 'Suicided') || !strcmp($weapon, 'Drowned')) {
                    $reason = (string)$weapon;
                } elseif (!strcmp($weapon, 'Corroded') || !strcmp($weapon, 'Crushed') || !strcmp($weapon, 'Gibbed') || !strcmp($weapon, 'Depressurized')) {
                    $reason = "Was $weapon";
                } elseif (!strcmp($weapon, 'Fell')) {
                    $reason = 'Fell to their death';
                } elseif (!strcmp($weapon, 'Fell Into Lava')) {
                    $reason = 'Fell into Lava';
                } elseif (!strcmp($weapon, 'Swam Too Far')) {
                    $reason = 'Tried to Swim Too Far';
                } else {
                    $wfl = mb_strtoupper($weapon[0]);

                    if ('A' == $wfl || 'E' == $wfl || 'I' == $wfl || 'O' == $wfl || 'U' == $wfl || 'Y' == $wfl) {
                        $reason = "Died from an $weapon";
                    } else {
                        $reason = "Died from a $weapon";
                    }
                }
                break;
            case 4: // Disconnected
                $reason = 'Disconnected';
                break;
            default:
                $reason = 'Unknown';
        }

        if (!$header) {
            echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="620">
  <tr>
    <td CLASS="heading" COLSPAN="5" ALIGN="center">Killing Sprees By Type</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="90">Spree Type</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="45">Start Time</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Time In Spree</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="80">Kills During Spree</td>
    <td CLASS="smheading" ALIGN="center">Reason Spree Stopped</td>
  </tr>

EOF;

            $header = 1;
        }

        echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$type</td>
    <td CLASS="grey" ALIGN="center">$time</td>
    <td CLASS="grey" ALIGN="center">$length</td>
    <td CLASS="grey" ALIGN="center">$ge_quant</td>
    <td CLASS="grey" ALIGN="center">$reason</td>
  </tr>

EOF;

        $sprees++;
    }
}
if (!$sprees) {
    echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="600">
  <tr>
    <td CLASS="heading" ALIGN="center">No Killing Sprees</td>
  </tr>

EOF;
}
echo "</table>\n";

//=============================================================================
//========== Total Items Picked Up By Type ====================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="600">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Total Items Picked Up By Type</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Item Type</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="35">No.</td>
    <td CLASS="smheading" ALIGN="center">Item Type</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="35">No.</td>
    <td CLASS="smheading" ALIGN="center">Item Type.</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="35">No.</td>
  </tr>

EOF;

$presult = sqlqueryn("SELECT it_num,it_desc FROM $ut_items");
if (!$presult) {
    echo "Error loading item pickup descriptions.<br>\n";

    exit;
}
$numitems = 0;
while (false !== ($prow = $GLOBALS['xoopsDB']->fetchBoth($presult))) {
    $itemnum = $prow['it_num'];

    $itemdesc = $prow['it_desc'];

    $result = sqlqueryn("SELECT gi_pickups FROM $ut_gitems WHERE gi_game='$gamenum' && gi_plr ='$gp_num' && gi_item=$itemnum");

    if (!$result) {
        echo "Error loading item pickups.<br>\n";

        exit;
    }

    $pickups[0][$numitems] = $itemdesc;

    $pickups[1][$numitems] = 0;

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
        $pickups[1][$numitems] += $row['gi_pickups'];
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    $numitems++;
}

if ($numitems > 0) {
    array_multisort(
        $pickups[1],
        SORT_DESC,
        SORT_NUMERIC,
        $pickups[0],
        SORT_ASC,
        SORT_STRING
    );
}

$col = $totpickups = 0;
for ($i = 0; $i < $numitems; $i++) {
    $item = $pickups[0][$i];

    $num = $pickups[1][$i];

    if ($num) {
        if ($col > 2) {
            $col = 0;
        }

        if (0 == $col) {
            echo "  <tr>\n";
        }

        echo <<<EOF
    <td CLASS="dark" ALIGN="center">$item</td>
    <td CLASS="grey" ALIGN="center">$num</td>

EOF;

        if (2 == $col) {
            echo "  </tr>\n";
        }

        $col++;

        $totpickups++;
    }
}

if (!$totpickups) {
    echo <<<EOF
  <td CLASS="dark" ALIGN="center" COLSPAN="6">There Were No Pickups Used</td>

EOF;
} else {
    while ($col < 3) {
        echo <<<EOF
  <td CLASS="dark" ALIGN="center">&nbsp;</td>
  <td CLASS="grey" ALIGN="center">&nbsp;</td>

EOF;

        $col++;
    }
}
echo "</table>\n";

//=============================================================================
//========== Connection Log ===================================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0">
  <tr>
    <td CLASS="heading" COLSPAN="3" ALIGN="center">Connection Log</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="50">Time</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="100">Status</td>
  </tr>

EOF;

$result = sqlqueryn("SELECT ge_event,ge_plr,ge_time,ge_reason FROM $ut_gevents WHERE ge_game='$gamenum' && ge_event BETWEEN '2' AND '3' ORDER BY ge_time");
if (!$result) {
    echo "Error loading connection events.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $plr = $row['ge_plr'];

    $event = $row['ge_event'];

    if ($plr == $gp_num || 3 == $event) {
        $time = sprintf('%0.1f', $row['ge_time'] / 60);

        if (3 == $event) {
            switch ($row['ge_reason']) {
                case 0:
                    $reason = 'Game Start';
                    $rclass = 'gselog';
                    break;
                case 1:
                    $reason = 'Game Ended';
                    $rclass = 'gselog';
                    break;
                default:
                    $reason = 'Unknown';
                    $rclass = 'gselog';
            }
        } else {
            switch ($row['ge_reason']) {
                case 0:
                    $reason = 'Connected';
                    $rclass = 'grey';
                    break;
                case 1:
                    $reason = 'Disconnected';
                    $rclass = 'warn';
                    break;
            }
        }

        echo <<<EOF
    <tr>
      <td CLASS="dark" ALIGN="center">$time</td>
      <td CLASS="$rclass" ALIGN="center">$reason</td>
    </tr>
  
EOF;
    }
}
echo "</table>\n";

$GLOBALS['xoopsDB']->close($link);

echo <<<EOF
</center>

</td></tr></table>

EOF;

require XOOPS_ROOT_PATH . '/footer.php';
