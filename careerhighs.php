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

    // Sort by num, games, time, description, player

    switch ($group) {
        case 1: // Kills
            array_multisort(
                $weapons[1],
                SORT_DESC,
                SORT_NUMERIC,
                $weapons[4],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[3],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[0],
                SORT_ASC,
                SORT_STRING,
                $weapons[2],
                SORT_ASC,
                SORT_NUMERIC,
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
                $weapons[15],
                $weapons[16]
            );
            break;
        case 2: // Deaths
            array_multisort(
                $weapons[5],
                SORT_DESC,
                SORT_NUMERIC,
                $weapons[8],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[7],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[0],
                SORT_ASC,
                SORT_STRING,
                $weapons[6],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[1],
                $weapons[2],
                $weapons[3],
                $weapons[4],
                $weapons[9],
                $weapons[10],
                $weapons[11],
                $weapons[12],
                $weapons[13],
                $weapons[14],
                $weapons[15],
                $weapons[16]
            );
            break;
        case 3: // Deaths while Holding
            array_multisort(
                $weapons[9],
                SORT_DESC,
                SORT_NUMERIC,
                $weapons[12],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[11],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[0],
                SORT_ASC,
                SORT_STRING,
                $weapons[10],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[1],
                $weapons[2],
                $weapons[3],
                $weapons[4],
                $weapons[5],
                $weapons[6],
                $weapons[7],
                $weapons[8],
                $weapons[13],
                $weapons[14],
                $weapons[15],
                $weapons[16]
            );
            break;
        case 4: // Suicides
            array_multisort(
                $weapons[13],
                SORT_DESC,
                SORT_NUMERIC,
                $weapons[16],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[15],
                SORT_ASC,
                SORT_NUMERIC,
                $weapons[0],
                SORT_ASC,
                SORT_STRING,
                $weapons[14],
                SORT_ASC,
                SORT_NUMERIC,
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
                $weapons[12]
            );
            break;
    }

    for ($i = 0; $i < $numweapons; $i++) {
        $num = $weapons[$group * 4 - 3][$i];

        if ($num > 0) {
            $wpdesc = $weapons[0][$i];

            if (strcmp($wpdesc, 'None')) {
                $player = getplayer($weapons[$group * 4 - 2][$i]);

                $time = sprintf('%0.1f', $weapons[$group * 4 - 1][$i] / 3600);

                $games = $weapons[$group * 4][$i];

                echo <<< EOF
    <tr>
      <td CLASS="dark" ALIGN="center">$wpdesc</td>
      <td CLASS="darkhuman" ALIGN="center">$player</td>
      <td CLASS="grey" ALIGN="center">$num</td>
      <td CLASS="grey" ALIGN="center">$games</td>
      <td CLASS="grey" ALIGN="center">$time</td>
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
//========== Career Highs =====================================================
//=============================================================================

$fragsplayer = getplayer($tl_chfrags_plr);
$fragstime = sprintf('%0.1f', $tl_chfrags_tm / 3600);
$killsplayer = getplayer($tl_chkills_plr);
$killstime = sprintf('%0.1f', $tl_chkills_tm / 3600);
$deathsplayer = getplayer($tl_chdeaths_plr);
$deathstime = sprintf('%0.1f', $tl_chdeaths_tm / 3600);
$suicidesplayer = getplayer($tl_chsuicides_plr);
$suicidestime = sprintf('%0.1f', $tl_chsuicides_tm / 3600);
$headshotsplayer = getplayer($tl_chheadshots_plr);
$headshotstime = sprintf('%0.1f', $tl_chheadshots_tm / 3600);
$firstbloodplayer = getplayer($tl_chfirstblood_plr);
$firstbloodtime = sprintf('%0.1f', $tl_chfirstblood_tm / 3600);
$multi1player = getplayer($tl_chmulti1_plr);
$multi1time = sprintf('%0.1f', $tl_chmulti1_tm / 3600);
$multi2player = getplayer($tl_chmulti2_plr);
$multi2time = sprintf('%0.1f', $tl_chmulti2_tm / 3600);
$multi3player = getplayer($tl_chmulti3_plr);
$multi3time = sprintf('%0.1f', $tl_chmulti3_tm / 3600);
$multi4player = getplayer($tl_chmulti4_plr);
$multi4time = sprintf('%0.1f', $tl_chmulti4_tm / 3600);
$multi5player = getplayer($tl_chmulti5_plr);
$multi5time = sprintf('%0.1f', $tl_chmulti5_tm / 3600);
$multi6player = getplayer($tl_chmulti6_plr);
$multi6time = sprintf('%0.1f', $tl_chmulti6_tm / 3600);
$multi7player = getplayer($tl_chmulti7_plr);
$multi7time = sprintf('%0.1f', $tl_chmulti7_tm / 3600);
$spree1player = getplayer($tl_chspree1_plr);
$spree1time = sprintf('%0.1f', $tl_chspree1_tm / 3600);
$spree2player = getplayer($tl_chspree2_plr);
$spree2time = sprintf('%0.1f', $tl_chspree2_tm / 3600);
$spree3player = getplayer($tl_chspree3_plr);
$spree3time = sprintf('%0.1f', $tl_chspree3_tm / 3600);
$spree4player = getplayer($tl_chspree4_plr);
$spree4time = sprintf('%0.1f', $tl_chspree4_tm / 3600);
$spree5player = getplayer($tl_chspree5_plr);
$spree5time = sprintf('%0.1f', $tl_chspree5_tm / 3600);
$spree6player = getplayer($tl_chspree6_plr);
$spree6time = sprintf('%0.1f', $tl_chspree6_tm / 3600);
$fph = sprintf('%0.1f', $tl_chfph);
$fphplayer = getplayer($tl_chfph_plr);
$fphtime = sprintf('%0.1f', $tl_chfph_tm / 3600);
$flagcaptureplayer = getplayer($tl_chflagcapture_plr);
$flagcapturetime = sprintf('%0.1f', $tl_chflagcapture_tm / 3600);
$flagreturnplayer = getplayer($tl_chflagreturn_plr);
$flagreturntime = sprintf('%0.1f', $tl_chflagreturn_tm / 3600);
$flagkillplayer = getplayer($tl_chflagkill_plr);
$flagkilltime = sprintf('%0.1f', $tl_chflagkill_tm / 3600);
$cpcaptureplayer = getplayer($tl_chcpcapture_plr);
$cpcapturetime = sprintf('%0.1f', $tl_chcpcapture_tm / 3600);
$bombcarriedplayer = getplayer($tl_chbombcarried_plr);
$bombcarriedtime = sprintf('%0.1f', $tl_chbombcarried_tm / 3600);
$bombtossedplayer = getplayer($tl_chbombtossed_plr);
$bombtossedtime = sprintf('%0.1f', $tl_chbombtossed_tm / 3600);
$bombkillplayer = getplayer($tl_chbombkill_plr);
$bombkilltime = sprintf('%0.1f', $tl_chbombkill_tm / 3600);
$winsplayer = getplayer($tl_chwins_plr);
$winstime = sprintf('%0.1f', $tl_chwins_tm / 3600);
$teamwinsplayer = getplayer($tl_chteamwins_plr);
$teamwinstime = sprintf('%0.1f', $tl_chteamwins_tm / 3600);

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="580">
  <tr>
    <td CLASS="heading" COLSPAN="5" ALIGN="center">Career Highs</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="220">Category</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Score</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Games Played</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Hours Played</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Frags</td>
    <td CLASS="darkhuman" ALIGN="center">$fragsplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chfrags</td>
    <td CLASS="grey" ALIGN="center">$tl_chfrags_gms</td>
    <td CLASS="grey" ALIGN="center">$fragstime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Kills</td>
    <td CLASS="darkhuman" ALIGN="center">$killsplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chkills</td>
    <td CLASS="grey" ALIGN="center">$tl_chkills_gms</td>
    <td CLASS="grey" ALIGN="center">$killstime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Deaths</td>
    <td CLASS="darkhuman" ALIGN="center">$deathsplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chdeaths</td>
    <td CLASS="grey" ALIGN="center">$tl_chdeaths_gms</td>
    <td CLASS="grey" ALIGN="center">$deathstime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Suicides</td>
    <td CLASS="darkhuman" ALIGN="center">$suicidesplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chsuicides</td>
    <td CLASS="grey" ALIGN="center">$tl_chsuicides_gms</td>
    <td CLASS="grey" ALIGN="center">$suicidestime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Head Shots</td>
    <td CLASS="darkhuman" ALIGN="center">$headshotsplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chheadshots</td>
    <td CLASS="grey" ALIGN="center">$tl_chheadshots_gms</td>
    <td CLASS="grey" ALIGN="center">$headshotstime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most First Blood</td>
    <td CLASS="darkhuman" ALIGN="center">$firstbloodplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chfirstblood</td>
    <td CLASS="grey" ALIGN="center">$tl_chfirstblood_gms</td>
    <td CLASS="grey" ALIGN="center">$firstbloodtime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Double Kills</td>
    <td CLASS="darkhuman" ALIGN="center">$multi1player</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti1</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti1_gms</td>
    <td CLASS="grey" ALIGN="center">$multi1time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Multi Kills</td>
    <td CLASS="darkhuman" ALIGN="center">$multi2player</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti2</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti2_gms</td>
    <td CLASS="grey" ALIGN="center">$multi2time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Mega Kills</td>
    <td CLASS="darkhuman" ALIGN="center">$multi3player</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti3</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti3_gms</td>
    <td CLASS="grey" ALIGN="center">$multi3time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Ultra Kills</td>
    <td CLASS="darkhuman" ALIGN="center">$multi4player</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti4</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti4_gms</td>
    <td CLASS="grey" ALIGN="center">$multi4time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Monster Kills</td>
    <td CLASS="darkhuman" ALIGN="center">$multi5player</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti5</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti5_gms</td>
    <td CLASS="grey" ALIGN="center">$multi5time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Ludicrous Kills</td>
    <td CLASS="darkhuman" ALIGN="center">$multi6player</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti6</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti6_gms</td>
    <td CLASS="grey" ALIGN="center">$multi6time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Holy Shit Kills</td>
    <td CLASS="darkhuman" ALIGN="center">$multi7player</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti7</td>
    <td CLASS="grey" ALIGN="center">$tl_chmulti7_gms</td>
    <td CLASS="grey" ALIGN="center">$multi7time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Killing Sprees</td>
    <td CLASS="darkhuman" ALIGN="center">$spree1player</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree1</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree1_gms</td>
    <td CLASS="grey" ALIGN="center">$spree1time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Rampages</td>
    <td CLASS="darkhuman" ALIGN="center">$spree2player</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree2</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree2_gms</td>
    <td CLASS="grey" ALIGN="center">$spree2time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Dominating</td>
    <td CLASS="darkhuman" ALIGN="center">$spree3player</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree3</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree3_gms</td>
    <td CLASS="grey" ALIGN="center">$spree3time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Unstoppable</td>
    <td CLASS="darkhuman" ALIGN="center">$spree4player</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree4</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree4_gms</td>
    <td CLASS="grey" ALIGN="center">$spree4time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Godlike</td>
    <td CLASS="darkhuman" ALIGN="center">$spree5player</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree5</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree5_gms</td>
    <td CLASS="grey" ALIGN="center">$spree5time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Wicked Sick</td>
    <td CLASS="darkhuman" ALIGN="center">$spree6player</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree6</td>
    <td CLASS="grey" ALIGN="center">$tl_chspree6_gms</td>
    <td CLASS="grey" ALIGN="center">$spree6time</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Highest FPH</td>
    <td CLASS="darkhuman" ALIGN="center">$fphplayer</td>
    <td CLASS="grey" ALIGN="center">$fph</td>
    <td CLASS="grey" ALIGN="center">$tl_chfph_gms</td>
    <td CLASS="grey" ALIGN="center">$fphtime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Flag Captures</td>
    <td CLASS="darkhuman" ALIGN="center">$flagcaptureplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagcapture</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagcapture_gms</td>
    <td CLASS="grey" ALIGN="center">$flagcapturetime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Flag Returns</td>
    <td CLASS="darkhuman" ALIGN="center">$flagreturnplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagreturn</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagreturn_gms</td>
    <td CLASS="grey" ALIGN="center">$flagreturntime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Flag Kills</td>
    <td CLASS="darkhuman" ALIGN="center">$flagkillplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagkill</td>
    <td CLASS="grey" ALIGN="center">$tl_chflagkill_gms</td>
    <td CLASS="grey" ALIGN="center">$flagkilltime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Control Point Captures</td>
    <td CLASS="darkhuman" ALIGN="center">$cpcaptureplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chcpcapture</td>
    <td CLASS="grey" ALIGN="center">$tl_chcpcapture_gms</td>
    <td CLASS="grey" ALIGN="center">$cpcapturetime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Bombs Delivered (Carried)</td>
    <td CLASS="darkhuman" ALIGN="center">$bombcarriedplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombcarried</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombcarried_gms</td>
    <td CLASS="grey" ALIGN="center">$bombcarriedtime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Bombs Delivered (Tossed)</td>
    <td CLASS="darkhuman" ALIGN="center">$bombtossedplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombtossed</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombtossed_gms</td>
    <td CLASS="grey" ALIGN="center">$bombtossedtime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Bomb Kills</td>
    <td CLASS="darkhuman" ALIGN="center">$bombkillplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombkill</td>
    <td CLASS="grey" ALIGN="center">$tl_chbombkill_gms</td>
    <td CLASS="grey" ALIGN="center">$bombkilltime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Wins</td>
    <td CLASS="darkhuman" ALIGN="center">$winsplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chwins</td>
    <td CLASS="grey" ALIGN="center">$tl_chwins_gms</td>
    <td CLASS="grey" ALIGN="center">$winstime</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Most Team Wins</td>
    <td CLASS="darkhuman" ALIGN="center">$teamwinsplayer</td>
    <td CLASS="grey" ALIGN="center">$tl_chteamwins</td>
    <td CLASS="grey" ALIGN="center">$tl_chteamwins_gms</td>
    <td CLASS="grey" ALIGN="center">$teamwinstime</td>
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

        $weapons[1][$numweapons] = $row['wp_chkills'];

        $weapons[2][$numweapons] = $row['wp_chkills_plr'];

        $weapons[3][$numweapons] = $row['wp_chkills_tm'];

        $weapons[4][$numweapons] = $row['wp_chkills_gms'];

        $weapons[5][$numweapons] = $row['wp_chdeaths'];

        $weapons[6][$numweapons] = $row['wp_chdeaths_plr'];

        $weapons[7][$numweapons] = $row['wp_chdeaths_tm'];

        $weapons[8][$numweapons] = $row['wp_chdeaths_gms'];

        $weapons[9][$numweapons] = $row['wp_chdeathshld'];

        $weapons[10][$numweapons] = $row['wp_chdeathshld_plr'];

        $weapons[11][$numweapons] = $row['wp_chdeathshld_tm'];

        $weapons[12][$numweapons] = $row['wp_chdeathshld_gms'];

        $weapons[13][$numweapons] = $row['wp_chsuicides'];

        $weapons[14][$numweapons] = $row['wp_chsuicides_plr'];

        $weapons[15][$numweapons] = $row['wp_chsuicides_tm'];

        $weapons[16][$numweapons++] = $row['wp_chsuicides_gms'];
    } else {
        // Career Kills

        if ($row['wp_chkills_plr'] == $weapons[2][$weap]) {
            $weapons[1][$weap] += $row['wp_chkills'];

            if ($row['wp_chkills_gms'] > $weapons[4][$weap]) {
                $weapons[3][$weap] = $row['wp_chkills_tm'];

                $weapons[4][$weap] = $row['wp_chkills_gms'];
            }
        } elseif ($row['wp_chkills'] > $weapons[1][$weap]) {
            $weapons[1][$weap] = $row['wp_chkills'];

            $weapons[2][$weap] = $row['wp_chkills_plr'];

            if ($row['wp_chkills_gms'] > $weapons[4][$weap]) {
                $weapons[3][$weap] = $row['wp_chkills_tm'];

                $weapons[4][$weap] = $row['wp_chkills_gms'];
            }
        }

        // Career Deaths

        if ($row['wp_chdeaths_plr'] == $weapons[6][$weap]) {
            $weapons[5][$weap] += $row['wp_chdeaths'];

            if ($row['wp_chdeaths_gms'] > $weapons[8][$weap]) {
                $weapons[7][$weap] = $row['wp_chdeaths_tm'];

                $weapons[8][$weap] = $row['wp_chdeaths_gms'];
            }
        } elseif ($row['wp_chdeaths'] > $weapons[5][$weap]) {
            $weapons[5][$weap] = $row['wp_chdeaths'];

            $weapons[6][$weap] = $row['wp_chdeaths_plr'];

            if ($row['wp_chdeaths_gms'] > $weapons[8][$weap]) {
                $weapons[7][$weap] = $row['wp_chdeaths_tm'];

                $weapons[8][$weap] = $row['wp_chdeaths_gms'];
            }
        }

        // Career Deaths while Holding

        if ($row['wp_chdeathshld_plr'] == $weapons[10][$weap]) {
            $weapons[9][$weap] += $row['wp_chdeathshld'];

            if ($row['wp_chdeathshld_gms'] > $weapons[12][$weap]) {
                $weapons[11][$weap] = $row['wp_chdeathshld_tm'];

                $weapons[12][$weap] = $row['wp_chdeathshld_gms'];
            }
        } elseif ($row['wp_chdeathshld'] > $weapons[9][$weap]) {
            $weapons[9][$weap] = $row['wp_chdeathshld'];

            $weapons[10][$weap] = $row['wp_chdeathshld_plr'];

            if ($row['wp_chdeathshld_gms'] > $weapons[12][$weap]) {
                $weapons[11][$weap] = $row['wp_chdeathshld_tm'];

                $weapons[12][$weap] = $row['wp_chdeathshld_gms'];
            }
        }

        // Career Suicides

        if ($row['wp_chsuicides_plr'] == $weapons[14][$weap]) {
            $weapons[13][$weap] += $row['wp_chsuicides'];

            if ($row['wp_chsuicides_gms'] > $weapons[16][$weap]) {
                $weapons[15][$weap] = $row['wp_chsuicides_tm'];

                $weapons[16][$weap] = $row['wp_chsuicides_gms'];
            }
        } elseif ($row['wp_chsuicides'] > $weapons[13][$weap]) {
            $weapons[13][$weap] = $row['wp_chsuicides'];

            $weapons[14][$weap] = $row['wp_chsuicides_plr'];

            if ($row['wp_chsuicides_gms'] > $weapons[16][$weap]) {
                $weapons[15][$weap] = $row['wp_chsuicides_tm'];

                $weapons[16][$weap] = $row['wp_chsuicides_gms'];
            }
        }
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

//=============================================================================
//========== Most Career Kills with a Weapon ==================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="550">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Most Career Kills with a Weapon</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="180">Weapon</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Kills</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Games Played</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Hours Played</td>
  </tr>

EOF;
showweapons(1);

//=============================================================================
//========== Most Career Deaths by a Weapon ===================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="550">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Most Career Deaths by a Weapon</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="180">Weapon</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Deaths</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Games Played</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Hours Played</td>
  </tr>

EOF;
showweapons(2);

//=============================================================================
//========== Most Career Deaths While Holding a Weapon ========================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="550">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Most Career Deaths While Holding a Weapon</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="180">Weapon</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Deaths</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Games Played</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Hours Played</td>
  </tr>

EOF;
showweapons(3);

//=============================================================================
//========== Most Career Suicides =============================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="550">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Most Career Suicides</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="180">Cause</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Suicides</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Games Played</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="60">Hours Played</td>
  </tr>

EOF;
showweapons(4);

echo <<<EOF
</center>

</td></tr></table>

EOF;

$GLOBALS['xoopsDB']->close($link);

require XOOPS_ROOT_PATH . '/footer.php';
