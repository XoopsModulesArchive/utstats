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

function getplayer($plr)
{
    global $ut_players;

    $result = sqlqueryn("SELECT pnum,plr_name FROM $ut_players WHERE pnum='$plr'");

    if (!$result) {
        echo "Player database error.<br>\n";

        exit;
    }

    $row = $GLOBALS['xoopsDB']->fetchBoth($result);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    $pnum = $row['pnum'];

    $name = $row['plr_name'];

    if ($row) {
        $gpplayer = "<a CLASS=\"darkhuman\" HREF=\"playerstats.php?player=$pnum\">$name</a>";
    } else {
        $gpplayer = '&nbsp;';
    }

    return $gpplayer;
}

function showweapons($group)
{
    global $weapons, $numweapons;

    // Sort by num, date, description, time, player, map

    switch ($group) {
        case 1: // Kills
            array_multisort(
                $weapons[1],
                SORT_DESC,
                SORT_NUMERIC,
                $weapons[5],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[0],
                SORT_ASC,
                SORT_STRING,
                $weapons[3],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[2],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[4],
                SORT_ASC,
                SORT_STRING,
                $weapons[6],
                $weapons[7],
                $weapons[8],
                $weapons[9],
                $weapons[10],
                $weapons[11],
                $weapons[12],
                $weapons[13],
                $weapons[14],
                $weapons[15],
                $weapons[16],
                $weapons[17],
                $weapons[18],
                $weapons[19],
                $weapons[20]
            );
            break;
        case 2: // Deaths
            array_multisort(
                $weapons[6],
                SORT_DESC,
                SORT_NUMERIC,
                $weapons[10],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[0],
                SORT_ASC,
                SORT_STRING,
                $weapons[8],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[7],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[9],
                SORT_ASC,
                SORT_STRING,
                $weapons[1],
                $weapons[2],
                $weapons[3],
                $weapons[4],
                $weapons[5],
                $weapons[11],
                $weapons[12],
                $weapons[13],
                $weapons[14],
                $weapons[15],
                $weapons[16],
                $weapons[17],
                $weapons[18],
                $weapons[19],
                $weapons[20]
            );
            break;
        case 3: // Deaths while Holding
            array_multisort(
                $weapons[11],
                SORT_DESC,
                SORT_NUMERIC,
                $weapons[15],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[0],
                SORT_ASC,
                SORT_STRING,
                $weapons[13],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[12],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[14],
                SORT_ASC,
                SORT_STRING,
                $weapons[1],
                $weapons[2],
                $weapons[3],
                $weapons[4],
                $weapons[5],
                $weapons[6],
                $weapons[7],
                $weapons[8],
                $weapons[9],
                $weapons[10],
                $weapons[16],
                $weapons[17],
                $weapons[18],
                $weapons[19],
                $weapons[20]
            );
            break;
        case 4: // Suicides
            array_multisort(
                $weapons[16],
                SORT_DESC,
                SORT_NUMERIC,
                $weapons[20],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[0],
                SORT_ASC,
                SORT_STRING,
                $weapons[18],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[17],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[19],
                SORT_ASC,
                SORT_STRING,
                $weapons[1],
                $weapons[2],
                $weapons[3],
                $weapons[4],
                $weapons[5],
                $weapons[6],
                $weapons[7],
                $weapons[8],
                $weapons[9],
                $weapons[10],
                $weapons[11],
                $weapons[12],
                $weapons[13],
                $weapons[14],
                $weapons[15]
            );
            break;
    }

    for ($i = 0; $i < $numweapons; $i++) {
        $num = $weapons[$group * 5 - 4][$i];

        if ($num > 0) {
            $wpdesc = $weapons[0][$i];

            if (strcmp($wpdesc, 'None')) {
                $player = getplayer($weapons[$group * 5 - 3][$i]);

                $time = sprintf('%0.1f', $weapons[$group * 5 - 2][$i] / 60);

                $map = $weapons[$group * 5 - 1][$i];

                $date = date('D, M d Y', strtotime($weapons[$group * 5][$i]));

                echo <<< EOF
    <tr>
      <td CLASS="dark" ALIGN="center">$wpdesc</td>
      <td CLASS="dark" ALIGN="center">$player</td>
      <td CLASS="grey" ALIGN="center">$num</td>
      <td CLASS="grey" ALIGN="center">$time</td>
      <td CLASS="grey" ALIGN="center">$map</td>
      <td CLASS="grey" ALIGN="center">$date</td>
    </tr>

EOF;
            }
        }
    }

    echo "</table>\n";
}

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
//========== Totals Logged ====================================================
//=============================================================================

$frags = $tl_kills - $tl_suicides;
$ghours = sprintf('%0.1f', $tl_gametime / 3600);
$phours = sprintf('%0.1f', $tl_playertime / 3600);

echo <<<EOF
<center>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" CLASS="box">
  <tr>
    <td CLASS="heading" ALIGN="center" COLSPAN="7">Totals Logged</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Frags</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Kills</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Deaths</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Suicides</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Games</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="85">Game Hours</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="85">Player Hours</td>
  </tr>
  <tr>
    <td CLASS="grey" ALIGN="center">$frags</td>
    <td CLASS="grey" ALIGN="center">$tl_kills</td>
    <td CLASS="grey" ALIGN="center">$tl_deaths</td>
    <td CLASS="grey" ALIGN="center">$tl_suicides</td>
    <td CLASS="grey" ALIGN="center">$tl_games</td>
    <td CLASS="grey" ALIGN="center">$ghours</td>
    <td CLASS="grey" ALIGN="center">$phours</td>
  </tr>
</td>
</table>

EOF;

//=============================================================================
//========== Total Games Played by Type =======================================
//=============================================================================

$result = sqlqueryn("SELECT * FROM $ut_type");
if (!$result) {
    echo "Database error accessing game types.<br>\n";

    exit;
}

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0">
  <tr>
    <td CLASS="heading" COLSPAN="4" ALIGN="center">Total Games Played by Type</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="165">Game (Type)</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Number</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="85">Game Hours</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="85">Player Hours</td>
  </tr>

EOF;

$tot_played = $tot_gtime = $tot_ptime = 0;

while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    while (list($key, $val) = each($row)) {
        ${$key} = $val;
    }

    if ($tp_played > 0) {
        $tot_played += $tp_played;

        $ghours = sprintf('%0.1f', $tp_gtime / 3600);

        $phours = sprintf('%0.1f', $tp_ptime / 3600);

        $tot_gtime += $tp_gtime;

        $tot_ptime += $tp_ptime;

        echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$tp_desc</td>
    <td CLASS="grey" ALIGN="center">$tp_played</td>
    <td CLASS="grey" ALIGN="center">$ghours</td>
    <td CLASS="grey" ALIGN="center">$phours</td>
  </tr>
EOF;
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

$ghours = sprintf('%0.1f', $tot_gtime / 3600);
$phours = sprintf('%0.1f', $tot_ptime / 3600);
echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">Totals</td>
    <td CLASS="darkgrey" ALIGN="center">$tot_played</td>
    <td CLASS="darkgrey" ALIGN="center">$ghours</td>
    <td CLASS="darkgrey" ALIGN="center">$phours</td>
  </tr>
</table>

EOF;

//=============================================================================
//========== Highs - From a Single Game =======================================
//=============================================================================

$fragsplayer = getplayer($tl_chfragssg_plr);
$fragstime = sprintf('%0.1f', $tl_chfragssg_tm / 60);
if ($tl_chfragssg > 0) {
    $vdate = strtotime($tl_chfragssg_date);

    $fragsdate = date('D, M d Y', $vdate);
} else {
    $fragsdate = '&nbsp;';
}

$killsplayer = getplayer($tl_chkillssg_plr);
$killstime = sprintf('%0.1f', $tl_chkillssg_tm / 60);
if ($tl_chkillssg > 0) {
    $vdate = strtotime($tl_chkillssg_date);

    $killsdate = date('D, M d Y', $vdate);
} else {
    $killsdate = '&nbsp;';
}

$deathsplayer = getplayer($tl_chdeathssg_plr);
$deathstime = sprintf('%0.1f', $tl_chdeathssg_tm / 60);
if ($tl_chdeathssg > 0) {
    $vdate = strtotime($tl_chdeathssg_date);

    $deathsdate = date('D, M d Y', $vdate);
} else {
    $deathsdate = '&nbsp;';
}

$suicidesplayer = getplayer($tl_chsuicidessg_plr);
$suicidestime = sprintf('%0.1f', $tl_chsuicidessg_tm / 60);
if ($tl_chsuicidessg > 0) {
    $vdate = strtotime($tl_chsuicidessg_date);

    $suicidesdate = date('D, M d Y', $vdate);
} else {
    $suicidesdate = '&nbsp;';
}

$flagcaptureplayer = getplayer($tl_chflagcapturesg_plr);
$flagcapturetime = sprintf('%0.1f', $tl_chflagcapturesg_tm / 60);
if ($tl_chflagcapturesg > 0) {
    $vdate = strtotime($tl_chflagcapturesg_date);

    $flagcapturedate = date('D, M d Y', $vdate);
} else {
    $flagcapturedate = '&nbsp;';
}

$flagreturnplayer = getplayer($tl_chflagreturnsg_plr);
$flagreturntime = sprintf('%0.1f', $tl_chflagreturnsg_tm / 60);
if ($tl_chflagreturnsg > 0) {
    $vdate = strtotime($tl_chflagreturnsg_date);

    $flagreturndate = date('D, M d Y', $vdate);
} else {
    $flagreturndate = '&nbsp;';
}

$flagkillplayer = getplayer($tl_chflagkillsg_plr);
$flagkilltime = sprintf('%0.1f', $tl_chflagkillsg_tm / 60);
if ($tl_chflagkillsg > 0) {
    $vdate = strtotime($tl_chflagkillsg_date);

    $flagkilldate = date('D, M d Y', $vdate);
} else {
    $flagkilldate = '&nbsp;';
}

$cpcaptureplayer = getplayer($tl_chcpcapturesg_plr);
$cpcapturetime = sprintf('%0.1f', $tl_chcpcapturesg_tm / 60);
if ($tl_chcpcapturesg > 0) {
    $vdate = strtotime($tl_chcpcapturesg_date);

    $cpcapturedate = date('D, M d Y', $vdate);
} else {
    $cpcapturedate = '&nbsp;';
}

$bombcarriedplayer = getplayer($tl_chbombcarriedsg_plr);
$bombcarriedtime = sprintf('%0.1f', $tl_chbombcarriedsg_tm / 60);
if ($tl_chbombcarriedsg > 0) {
    $vdate = strtotime($tl_chbombcarriedsg_date);

    $bombcarrieddate = date('D, M d Y', $vdate);
} else {
    $bombcarrieddate = '&nbsp;';
}

$bombtossedplayer = getplayer($tl_chbombtossedsg_plr);
$bombtossedtime = sprintf('%0.1f', $tl_chbombtossedsg_tm / 60);
if ($tl_chbombtossedsg > 0) {
    $vdate = strtotime($tl_chbombtossedsg_date);

    $bombtosseddate = date('D, M d Y', $vdate);
} else {
    $bombtosseddate = '&nbsp;';
}

$bombkillplayer = getplayer($tl_chbombkillsg_plr);
$bombkilltime = sprintf('%0.1f', $tl_chbombkillsg_tm / 60);
if ($tl_chbombkillsg > 0) {
    $vdate = strtotime($tl_chbombkillsg_date);

    $bombkilldate = date('D, M d Y', $vdate);
} else {
    $bombkilldate = '&nbsp;';
}

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="710">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Highs - From a Single Game</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Category</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center">Score</td>
    <td CLASS="smheading" ALIGN="center">Time (min)</td>
    <td CLASS="smheading" ALIGN="center">Map</td>
    <td CLASS="smheading" ALIGN="center">Date</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Frags</td>
    <td CLASS="dark" ALIGN="center">$fragsplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chfragssg</td>
    <td CLASS="grey" ALIGN="center">$fragstime</td>
    <td CLASS="grey" ALIGN="center">$tl_chfragssg_map</td>
    <td CLASS="grey" ALIGN="center">$fragsdate</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Kills</td>
    <td CLASS="dark" ALIGN="center">$killsplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chkillssg</td>
    <td CLASS="grey" ALIGN="center">$killstime</td>
    <td CLASS="grey" ALIGN="center">$tl_chkillssg_map</td>
    <td CLASS="grey" ALIGN="center">$killsdate</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Deaths</td>
    <td CLASS="dark" ALIGN="center">$deathsplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chdeathssg</td>
    <td CLASS="grey" ALIGN="center">$deathstime</td>
    <td CLASS="grey" ALIGN="center">$tl_chdeathssg_map</td>
    <td CLASS="grey" ALIGN="center">$deathsdate</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Suicides</td>
    <td CLASS="dark" ALIGN="center">$suicidesplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chsuicidessg</td>
    <td CLASS="grey" ALIGN="center">$suicidestime</td>
    <td CLASS="grey" ALIGN="center">$tl_chsuicidessg_map</td>
    <td CLASS="grey" ALIGN="center">$suicidesdate</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Flag Captures</td>
    <td CLASS="dark" ALIGN="center">$flagcaptureplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagcapturesg</td>
    <td CLASS="grey" ALIGN="center">$flagcapturetime</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagcapturesg_map</td>
    <td CLASS="grey" ALIGN="center">$flagcapturedate</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Flag Returns</td>
    <td CLASS="dark" ALIGN="center">$flagreturnplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagreturnsg</td>
    <td CLASS="grey" ALIGN="center">$flagreturntime</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagreturnsg_map</td>
    <td CLASS="grey" ALIGN="center">$flagreturndate</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Flag Kills</td>
    <td CLASS="dark" ALIGN="center">$flagkillplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagkillsg</td>
    <td CLASS="grey" ALIGN="center">$flagkilltime</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagkillsg_map</td>
    <td CLASS="grey" ALIGN="center">$flagkilldate</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Control Point Captures</td>
    <td CLASS="dark" ALIGN="center">$cpcaptureplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chcpcapturesg</td>
    <td CLASS="grey" ALIGN="center">$cpcapturetime</td>
    <td CLASS="grey" ALIGN="center">$tl_chcpcapturesg_map</td>
    <td CLASS="grey" ALIGN="center">$cpcapturedate</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Bombs Delivered (Carried)</td>
    <td CLASS="dark" ALIGN="center">$bombcarriedplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombcarriedsg</td>
    <td CLASS="grey" ALIGN="center">$bombcarriedtime</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombcarriedsg_map</td>
    <td CLASS="grey" ALIGN="center">$bombcarrieddate</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Bombs Delivered (Tossed)</td>
    <td CLASS="dark" ALIGN="center">$bombtossedplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombtossedsg</td>
    <td CLASS="grey" ALIGN="center">$bombtossedtime</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombtossedsg_map</td>
    <td CLASS="grey" ALIGN="center">$bombtosseddate</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Bomb Kills</td>
    <td CLASS="dark" ALIGN="center">$bombkillplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombkillsg</td>
    <td CLASS="grey" ALIGN="center">$bombkilltime</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombkillsg_map</td>
    <td CLASS="grey" ALIGN="center">$bombkilldate</td>
  </tr>
</table>

EOF;

// *****************************
// ***** Load Weapons Data *****
// *****************************

$result = sqlqueryn("SELECT * FROM $ut_weapons");
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

        $weapons[1][$numweapons] = $row['wp_chkillssg'];

        $weapons[2][$numweapons] = $row['wp_chkillssg_plr'];

        $weapons[3][$numweapons] = $row['wp_chkillssg_tm'];

        $weapons[4][$numweapons] = $row['wp_chkillssg_map'];

        $weapons[5][$numweapons] = $row['wp_chkillssg_dt'];

        $weapons[6][$numweapons] = $row['wp_chdeathssg'];

        $weapons[7][$numweapons] = $row['wp_chdeathssg_plr'];

        $weapons[8][$numweapons] = $row['wp_chdeathssg_tm'];

        $weapons[9][$numweapons] = $row['wp_chdeathssg_map'];

        $weapons[10][$numweapons] = $row['wp_chdeathssg_dt'];

        $weapons[11][$numweapons] = $row['wp_chdeathshldsg'];

        $weapons[12][$numweapons] = $row['wp_chdeathshldsg_plr'];

        $weapons[13][$numweapons] = $row['wp_chdeathshldsg_tm'];

        $weapons[14][$numweapons] = $row['wp_chdeathshldsg_map'];

        $weapons[15][$numweapons] = $row['wp_chdeathshldsg_dt'];

        $weapons[16][$numweapons] = $row['wp_chsuicidessg'];

        $weapons[17][$numweapons] = $row['wp_chsuicidessg_plr'];

        $weapons[18][$numweapons] = $row['wp_chsuicidessg_tm'];

        $weapons[19][$numweapons] = $row['wp_chsuicidessg_map'];

        $weapons[20][$numweapons++] = $row['wp_chsuicidessg_dt'];
    } else {
        // Career SG Kills

        if ($row['wp_chkillssg_plr'] == $weapons[2][$weap] && $row['wp_chkillssg_dt'] == $weapons[5][$weap]) {
            $weapons[1][$weap] += $row['wp_chkillssg'];
        } elseif ($row['wp_chkillssg'] > $weapons[1][$weap]) {
            $weapons[1][$weap] = $row['wp_chkillssg'];

            $weapons[2][$weap] = $row['wp_chkillssg_plr'];

            $weapons[3][$weap] = $row['wp_chkillssg_tm'];

            $weapons[4][$weap] = $row['wp_chkillssg_map'];

            $weapons[5][$weap] = $row['wp_chkillssg_dt'];
        }

        // Career SG Deaths

        if ($row['wp_chdeathssg_plr'] == $weapons[7][$weap] && $row['wp_chdeathssg_dt'] == $weapons[10][$weap]) {
            $weapons[6][$weap] += $row['wp_chdeathssg'];
        } elseif ($row['wp_chdeathssg'] > $weapons[6][$weap]) {
            $weapons[6][$weap] = $row['wp_chdeathssg'];

            $weapons[7][$weap] = $row['wp_chdeathssg_plr'];

            $weapons[8][$weap] = $row['wp_chdeathssg_tm'];

            $weapons[9][$weap] = $row['wp_chdeathssg_map'];

            $weapons[10][$weap] = $row['wp_chdeathssg_dt'];
        }

        // Career SG Deaths while Holding

        if ($row['wp_chdeathshldsg_plr'] == $weapons[12][$weap] && $row['wp_chdeathshldsg_dt'] == $weapons[15][$weap]) {
            $weapons[11][$weap] += $row['wp_chdeathshldsg'];
        } elseif ($row['wp_chdeathshldsg'] > $weapons[11][$weap]) {
            $weapons[11][$weap] = $row['wp_chdeathshldsg'];

            $weapons[12][$weap] = $row['wp_chdeathshldsg_plr'];

            $weapons[13][$weap] = $row['wp_chdeathshldsg_tm'];

            $weapons[14][$weap] = $row['wp_chdeathshldsg_map'];

            $weapons[15][$weap] = $row['wp_chdeathshldsg_dt'];
        }

        // Career SG Suicides

        if ($row['wp_chsuicidessg_plr'] == $weapons[17][$weap] && $row['wp_chsuicidessg_dt'] == $weapons[20][$weap]) {
            $weapons[16][$weap] += $row['wp_chsuicidessg'];
        } elseif ($row['wp_chsuicidessg'] > $weapons[16][$weap]) {
            $weapons[16][$weap] = $row['wp_chsuicidessg'];

            $weapons[17][$weap] = $row['wp_chsuicidessg_plr'];

            $weapons[18][$weap] = $row['wp_chsuicidessg_tm'];

            $weapons[19][$weap] = $row['wp_chsuicidessg_map'];

            $weapons[20][$weap] = $row['wp_chsuicidessg_dt'];
        }
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

//=============================================================================
//========== Most Kills with a Weapon - From a Single Game ====================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="710">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Most Kills with a Weapon - From a Single Game</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Weapon</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center">Kills</td>
    <td CLASS="smheading" ALIGN="center">Time (min)</td>
    <td CLASS="smheading" ALIGN="center">Map</td>
    <td CLASS="smheading" ALIGN="center">Date</td>
  </tr>

EOF;
showweapons(1);

//=============================================================================
//========== Most Deaths by a Weapon - From a Single Game =====================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="710">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Most Deaths by a Weapon - From a Single Game</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Weapon</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center">Deaths</td>
    <td CLASS="smheading" ALIGN="center">Time (min)</td>
    <td CLASS="smheading" ALIGN="center">Map</td>
    <td CLASS="smheading" ALIGN="center">Date</td>
  </tr>

EOF;
showweapons(2);

//=============================================================================
//========== Most Deaths While Holding a Weapon - From a Single Game ==========
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="710">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Most Deaths While Holding a Weapon - From a Single Game</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Weapon</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center">Deaths</td>
    <td CLASS="smheading" ALIGN="center">Time (min)</td>
    <td CLASS="smheading" ALIGN="center">Map</td>
    <td CLASS="smheading" ALIGN="center">Date</td>
  </tr>

EOF;
showweapons(3);

//=============================================================================
//========== Most Suicides - From a Single Game ===============================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="710">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Most Suicides - From a Single Game</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Cause</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center">Suicides</td>
    <td CLASS="smheading" ALIGN="center">Time (min)</td>
    <td CLASS="smheading" ALIGN="center">Map</td>
    <td CLASS="smheading" ALIGN="center">Date</td>
  </tr>

EOF;
showweapons(4);

echo <<<EOF
</center>

</td></tr></table>

EOF;

$GLOBALS['xoopsDB']->close($link);

require XOOPS_ROOT_PATH . '/footer.php';
