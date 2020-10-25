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

include '../../mainfile.php';
include '../../header.php';

require 'maininc.php';

$link = sqlquery_connect();
$result = sqlqueryn("SELECT * FROM $ut_totals LIMIT 1");
if (!$result) {
    echo "Database error.<br>\n";

    exit;
}
$row = $GLOBALS['xoopsDB']->fetchBoth($result);
$GLOBALS['xoopsDB']->freeRecordSet($result);
if (!$row) {
    echo "No data in stat totals database.<br>\n";

    exit;
}
while (list($key, $val) = each($row)) {
    ${$key} = $val;
}

//=============================================================================
//========== Cumulative Totals for All Players (Humans and Bots) ==============
//=============================================================================

$frags = $tl_kills - $tl_suicides;
$hours = sprintf('%0.1f', $tl_time / 3600);

echo <<<EOF
<center>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="710">
  <tr>
    <td CLASS="heading" ALIGN="center">Cumulative Totals for All Players</td>
  </tr>
</table>
<br>

EOF;

//=============================================================================
//========== Summary ==========================================================
//=============================================================================

echo <<<EOF
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" CLASS="box">
  <tr>
    <td CLASS="medheading" ALIGN="center" COLSPAN="12">Summary</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="150">Game Type</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="45">Score</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="35">F</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="35">K</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="35">D</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="35">S</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="35">TK</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="45">Eff.</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="40">Avg FPH</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="40">Avg TTL</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="50">Games</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="45">Hours</td>
  </tr>

EOF;

$tot_score = $tot_frags = $tot_kills = $tot_deaths = $tot_suicides = 0;
$tot_teamkills = $tot_played = $tot_time = 0;

$result = sqlqueryn("SELECT * FROM $ut_type");
if (!$result) {
    echo "Database error accessing game types.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    while (list($key, $val) = each($row)) {
        ${$key} = $val;
    }

    if ($tp_played > 0) {
        $frags = $tp_kills - $tp_suicides;

        if (0 == $tp_kills + $tp_deaths + $tp_suicides) {
            $eff = '0.0';
        } else {
            $eff = sprintf('%0.1f', ($tp_kills / ($tp_kills + $tp_deaths + $tp_suicides)) * 100.0);
        }

        if (0 == $tp_gtime) {
            $fph = '0.0';
        } else {
            $fph = sprintf('%0.1f', $frags * (3600 / $tp_gtime));
        }

        $ttl = sprintf('%0.1f', $tp_gtime / ($tp_deaths + $tp_suicides + 1));

        $hours = sprintf('%0.1f', $tp_gtime / 3600);

        $tot_score += $tp_score;

        $tot_frags += $frags;

        $tot_kills += $tp_kills;

        $tot_deaths += $tp_deaths;

        $tot_suicides += $tp_suicides;

        $tot_teamkills += $tp_teamkills;

        $tot_played += $tp_played;

        $tot_time += $tp_gtime;

        echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$tp_desc</td>
    <td CLASS="grey" ALIGN="center">$tp_score</td>
    <td CLASS="grey" ALIGN="center">$frags</td>
    <td CLASS="grey" ALIGN="center">$tp_kills</td>
    <td CLASS="grey" ALIGN="center">$tp_deaths</td>
    <td CLASS="grey" ALIGN="center">$tp_suicides</td>
    <td CLASS="grey" ALIGN="center">$tp_teamkills</td>
    <td CLASS="grey" ALIGN="center">$eff%</td>
    <td CLASS="grey" ALIGN="center">$fph</td>
    <td CLASS="grey" ALIGN="center">$ttl</td>
    <td CLASS="grey" ALIGN="center">$tp_played</td>
    <td CLASS="grey" ALIGN="center">$hours</td>
  </tr>
EOF;
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

if (0 == $tot_kills + $tot_deaths + $tot_suicides) {
    $tot_eff = '0.0';
} else {
    $tot_eff = sprintf('%0.1f', ($tot_kills / ($tot_kills + $tot_deaths + $tot_suicides)) * 100.0);
}
if (0 == $tot_time) {
    $tot_fph = '0.0';
} else {
    $tot_fph = sprintf('%0.1f', $tot_frags * (3600 / $tot_time));
}
$tot_ttl = sprintf('%0.1f', $tot_time / ($tot_deaths + $tot_suicides + 1));
$tot_hours = sprintf('%0.1f', $tot_time / 3600);

echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">Totals</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_score</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_frags</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_kills</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_deaths</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_suicides</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_teamkills</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_eff%</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_fph</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_ttl</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_played</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_hours</td>
  </tr>
</table>

EOF;

//=============================================================================
//========== CTF, Bombing Run, and Domination Events Summary ==================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="600">
  <tr>
    <td CLASS="medheading" ALIGN="center" COLSPAN="11">CTF, Bombing Run, and Domination Events Summary</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Control Point Captures</td>
    <td CLASS="dark" ALIGN="center">Flag Captures</td>
    <td CLASS="dark" ALIGN="center">Flag Kills</td>
    <td CLASS="dark" ALIGN="center">Flag Assists</td>
    <td CLASS="dark" ALIGN="center">Flag Saves</td>
    <td CLASS="dark" ALIGN="center">Flag Pickups</td>
    <td CLASS="dark" ALIGN="center">Flag Drops</td>
    <td CLASS="dark" ALIGN="center">Bomb Carried</td>
    <td CLASS="dark" ALIGN="center">Bomb Tossed</td>
    <td CLASS="dark" ALIGN="center">Bomb Kills</td>
    <td CLASS="dark" ALIGN="center">Bomb Drops</td>
  </tr>
  <tr>
    <td CLASS="grey" ALIGN="center">$tl_cpcapture</td>
    <td CLASS="grey" ALIGN="center">$tl_flagcapture</td>
    <td CLASS="grey" ALIGN="center">$tl_flagkill</td>
    <td CLASS="grey" ALIGN="center">$tl_flagassist</td>
    <td CLASS="grey" ALIGN="center">$tl_flagreturn</td>
    <td CLASS="grey" ALIGN="center">$tl_flagpickup</td>
    <td CLASS="grey" ALIGN="center">$tl_flagdrop</td>
    <td CLASS="grey" ALIGN="center">$tl_bombcarried</td>
    <td CLASS="grey" ALIGN="center">$tl_bombtossed</td>
    <td CLASS="grey" ALIGN="center">$tl_bombkill</td>
    <td CLASS="grey" ALIGN="center">$tl_bombdrop</td>
  </tr>
</table>
EOF;

//=============================================================================
//========== Special Events ===================================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="500">
  <tr>
    <td CLASS="medheading" ALIGN="center" COLSPAN="6">Special Events</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Headshots</td>
    <td CLASS="grey" ALIGN="center" WIDTH="45">$tl_headshots</td>
    <td CLASS="dark" ALIGN="center">Failed Translocations</td>
    <td CLASS="grey" ALIGN="center" WIDTH="45">$tl_transgib</td>
    <td CLASS="dark" ALIGN="center">Double Kills</td>
    <td CLASS="grey" ALIGN="center" WIDTH="45">$tl_multi1</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Multi Kills</td>
    <td CLASS="grey" ALIGN="center">$tl_multi2</td>
    <td CLASS="dark" ALIGN="center">Mega Kills</td>
    <td CLASS="grey" ALIGN="center">$tl_multi3</td>
    <td CLASS="dark" ALIGN="center">Ultra Kills</td>
    <td CLASS="grey" ALIGN="center">$tl_multi4</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Monster Kills</td>
    <td CLASS="grey" ALIGN="center">$tl_multi5</td>
    <td CLASS="dark" ALIGN="center">Ludicrous Kills</td>
    <td CLASS="grey" ALIGN="center">$tl_multi6</td>
    <td CLASS="dark" ALIGN="center">Holy Shit Kills</td>
    <td CLASS="grey" ALIGN="center">$tl_multi7</td>
  </tr>
</table>
EOF;

//=============================================================================
//========== Weapon Specific Totals ===========================================
//=============================================================================

$result = sqlqueryn("SELECT wp_desc,wp_secondary,wp_frags,wp_kills,wp_deaths,wp_suicides,wp_nwsuicides FROM $ut_weapons");
if (!$result) {
    echo "Database error accessing weapons table.<br>\n";

    exit;
}

$numweapons = 0;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    for ($i = 0, $weap = -1; $i < $numweapons && $weap < 0; $i++) {
        if (0 == strcmp($weapons[0][$i], $row['wp_desc'])) {
            $weap = $i;
        }
    }

    if ($weap < 0) {
        $weapons[0][$numweapons] = $row['wp_desc'];

        $weapons[1][$numweapons] = $row['wp_frags'];

        if ($row['wp_secondary']) {
            $weapons[2][$numweapons] = 0;

            $weapons[3][$numweapons] = $row['wp_kills'];
        } else {
            $weapons[2][$numweapons] = $row['wp_kills'];

            $weapons[3][$numweapons] = 0;
        }

        $weapons[4][$numweapons] = $row['wp_deaths'];

        $weapons[5][$numweapons] = $row['wp_suicides'];

        $weapons[6][$numweapons++] = $row['wp_suicides'] + $row['wp_nwsuicides'];
    } else {
        $weapons[1][$weap] += $row['wp_frags'];

        if ($row['wp_secondary']) {
            $weapons[3][$weap] += $row['wp_kills'];
        } else {
            $weapons[2][$weap] += $row['wp_kills'];
        }

        $weapons[4][$weap] += $row['wp_deaths'];

        $weapons[5][$weap] += $row['wp_suicides'];

        $weapons[6][$weap] += $row['wp_suicides'] + $row['wp_nwsuicides'];
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

// Sort by frags,deaths,suicides,kills,secondary kills,description
array_multisort(
    $weapons[1],
    SORT_DESC,
    SORT_NUMERIC,
    $weapons[4],
    SORT_ASC,
    SORT_NUMERIC,
    $weapons[5],
    SORT_ASC,
    SORT_NUMERIC,
    $weapons[2],
    SORT_ASC,
    SORT_NUMERIC,
    $weapons[3],
    SORT_ASC,
    SORT_NUMERIC,
    $weapons[0],
    SORT_ASC,
    SORT_STRING,
    $weapons[6],
    SORT_ASC,
    SORT_NUMERIC
);

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="590">
  <tr>
    <td CLASS="heading" COLSPAN="7" ALIGN="center">Weapon Specific Totals</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Weapon</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Frags</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="70">Primary Kills</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="70">Secondary Kills</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Deaths</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Suicides</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Eff.</td>
  </tr>

EOF;

for ($i = 0; $i < $numweapons; $i++) {
    $weapon = $weapons[0][$i];

    $frags = $weapons[1][$i];

    $kills = $weapons[2][$i];

    $skills = $weapons[3][$i];

    $deaths = $weapons[4][$i];

    $suicides = $weapons[5][$i];

    if (0 == $kills + $skills + $deaths + $suicides) {
        $eff = '0.0';
    } else {
        $eff = sprintf('%0.1f', (($kills + $skills) / ($kills + $skills + $deaths + $suicides)) * 100.0);
    }

    if (($frags || $kills || $skills || $deaths) && strcmp($weapon, 'None')) {
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
echo "</table>\n";

//=============================================================================
//========== Suicides Totals ==================================================
//=============================================================================

// Sort by suicides,description,frags,deaths,kills,secondary kills
array_multisort(
    $weapons[6],
    SORT_DESC,
    SORT_NUMERIC,
    $weapons[0],
    SORT_ASC,
    SORT_STRING,
    $weapons[1],
    SORT_DESC,
    SORT_NUMERIC,
    $weapons[4],
    SORT_ASC,
    SORT_NUMERIC,
    $weapons[2],
    SORT_ASC,
    SORT_NUMERIC,
    $weapons[3],
    SORT_ASC,
    SORT_NUMERIC,
    $weapons[5],
    SORT_DESC,
    SORT_NUMERIC
);

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="260">
  <tr>
    <td CLASS="heading" COLSPAN="2" ALIGN="center">Suicides Totals</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="200">Type</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Suicides</td>
  </tr>

EOF;

for ($i = 0; $i < $numweapons; $i++) {
    $weapon = $weapons[0][$i];

    $suicides = $weapons[6][$i];

    if ($suicides) {
        echo <<< EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$weapon</td>
    <td CLASS="grey" ALIGN="center">$suicides</td>
  </tr>

EOF;
    }
}
echo "</table>\n";

//=============================================================================
//========== Killing Sprees by Type ===========================================
//=============================================================================

$time1 = sprintf('%0.1f', $tl_spreet1 / 60);
$time2 = sprintf('%0.1f', $tl_spreet2 / 60);
$time3 = sprintf('%0.1f', $tl_spreet3 / 60);
$time4 = sprintf('%0.1f', $tl_spreet4 / 60);
$time5 = sprintf('%0.1f', $tl_spreet5 / 60);
$time6 = sprintf('%0.1f', $tl_spreet6 / 60);

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="390">
  <tr>
    <td CLASS="medheading" ALIGN="center" COLSPAN="4">Killing Sprees by Type</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="110">Spree Type</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="85"># of Sprees</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="115">Total Time (min)</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="80">Total Kills</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Killing Spree</td>
    <td CLASS="grey" ALIGN="center">$tl_spree1</td>
    <td CLASS="grey" ALIGN="center">$time1</td>
    <td CLASS="grey" ALIGN="center">$tl_spreek1</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Rampage</td>
    <td CLASS="grey" ALIGN="center">$tl_spree2</td>
    <td CLASS="grey" ALIGN="center">$time2</td>
    <td CLASS="grey" ALIGN="center">$tl_spreek2</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Dominating</td>
    <td CLASS="grey" ALIGN="center">$tl_spree3</td>
    <td CLASS="grey" ALIGN="center">$time3</td>
    <td CLASS="grey" ALIGN="center">$tl_spreek3</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Unstoppable</td>
    <td CLASS="grey" ALIGN="center">$tl_spree4</td>
    <td CLASS="grey" ALIGN="center">$time4</td>
    <td CLASS="grey" ALIGN="center">$tl_spreek4</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Godlike</td>
    <td CLASS="grey" ALIGN="center">$tl_spree5</td>
    <td CLASS="grey" ALIGN="center">$time5</td>
    <td CLASS="grey" ALIGN="center">$tl_spreek5</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Wicked Sick</td>
    <td CLASS="grey" ALIGN="center">$tl_spree6</td>
    <td CLASS="grey" ALIGN="center">$time6</td>
    <td CLASS="grey" ALIGN="center">$tl_spreek6</td>
  </tr>
</table>
EOF;

//=============================================================================
//========== Total Items Collected ============================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="600">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Total Items Collected</td>
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

$result = sqlqueryn("SELECT it_desc,it_pickups FROM $ut_items ORDER BY it_pickups DESC, it_desc ASC");
if (!$result) {
    echo "Error loading item pickup descriptions.<br>\n";

    exit;
}
$col = $totpickups = 0;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $item = $row['it_desc'];

    $num = $row['it_pickups'];

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
$GLOBALS['xoopsDB']->freeRecordSet($result);

if (!$totpickups) {
    echo <<<EOF
  <td CLASS="dark" ALIGN="center" COLSPAN="6">NO ITEM PICKUPS</td>

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

$GLOBALS['xoopsDB']->close($link);

echo <<<EOF
</center>

</td></tr></table>


EOF;

include '../../footer.php';
