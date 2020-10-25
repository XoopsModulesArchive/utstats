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

if (!$plr) {
    echo "Run from the main index program.<br>\n";

    exit;
}

$link = sqlquery_connect();
$result = sqlqueryn("SELECT * FROM $ut_players WHERE pnum = $plr LIMIT 1");
if (!$result) {
    echo "Database error.<br>\n";

    exit;
}
$row = $GLOBALS['xoopsDB']->fetchBoth($result);
$GLOBALS['xoopsDB']->freeRecordSet($result);
if (!$row) {
    echo "Player not found in database.<br>\n";

    exit;
}
while (list($key, $val) = each($row)) {
    ${$key} = $val;
}

$dm_hours = sprintf('%0.1f', $dm_time / 3600);
$tdm_hours = sprintf('%0.1f', $tdm_time / 3600);
$dd_hours = sprintf('%0.1f', $dd_time / 3600);
$ctf_hours = sprintf('%0.1f', $ctf_time / 3600);
$br_hours = sprintf('%0.1f', $br_time / 3600);
$mu_hours = sprintf('%0.1f', $mu_time / 3600);
$in_hours = sprintf('%0.1f', $in_time / 3600);
$lm_hours = sprintf('%0.1f', $lm_time / 3600);
$other_hours = sprintf('%0.1f', $other_time / 3600);
$total_time = $dm_time + $tdm_time + $dd_time + $ctf_time + $br_time + $other_time;
$total_hours = sprintf('%0.1f', $total_time / 3600);

$total_score = $dm_score + $tdm_score + $dd_score + $ctf_score + $br_score + $mu_score + $in_score + $lm_score + $other_score;
$total_teamkills = $tdm_teamkills + $dd_teamkills + $ctf_teamkills + $br_teamkills + $in_teamkills + $other_teamkills;
$total_teamdeaths = $tdm_teamdeaths + $dd_teamdeaths + $ctf_teamdeaths + $br_teamdeaths + $in_teamdeaths + $other_teamdeaths;
$total_wins = $dm_wins1 + $tdm_wins + $dd_wins + $ctf_wins + $br_wins + $mu_wins1 + $in_wins1 + $lm_wins + $other_wins;
$total_losses = $dm_wins2 + $dm_wins3 + $dm_losses + $tdm_losses + $dd_losses + $ctf_losses + $br_losses + $mu_wins2 + $mu_wins3 + $mu_losses + $in_wins2 + $in_wins3 + $in_losses + $lm_losses + $other_losses;
$total_games = $dm_games + $tdm_games + $dd_games + $ctf_games + $br_games + $mu_games + $in_games + $lm_games + $other_games;

//========== Efficiency =======================================================
if (0 == $dm_kills + $dm_deaths + $dm_suicides) {
    $dm_eff = '0.0';
} else {
    $dm_eff = sprintf('%0.1f', ($dm_kills / ($dm_kills + $dm_deaths + $dm_suicides)) * 100.0);
}
if (0 == $tdm_kills + $tdm_deaths + $tdm_suicides) {
    $tdm_eff = '0.0';
} else {
    $tdm_eff = sprintf('%0.1f', ($tdm_kills / ($tdm_kills + $tdm_deaths + $tdm_suicides)) * 100.0);
}
if (0 == $dd_kills + $dd_deaths + $dd_suicides) {
    $dd_eff = '0.0';
} else {
    $dd_eff = sprintf('%0.1f', ($dd_kills / ($dd_kills + $dd_deaths + $dd_suicides)) * 100.0);
}
if (0 == $ctf_kills + $ctf_deaths + $ctf_suicides) {
    $ctf_eff = '0.0';
} else {
    $ctf_eff = sprintf('%0.1f', ($ctf_kills / ($ctf_kills + $ctf_deaths + $ctf_suicides)) * 100.0);
}
if (0 == $br_kills + $br_deaths + $br_suicides) {
    $br_eff = '0.0';
} else {
    $br_eff = sprintf('%0.1f', ($br_kills / ($br_kills + $br_deaths + $br_suicides)) * 100.0);
}
if (0 == $mu_kills + $mu_deaths + $mu_suicides) {
    $mu_eff = '0.0';
} else {
    $mu_eff = sprintf('%0.1f', ($mu_kills / ($mu_kills + $mu_deaths + $mu_suicides)) * 100.0);
}
if (0 == $in_kills + $in_deaths + $in_suicides) {
    $in_eff = '0.0';
} else {
    $in_eff = sprintf('%0.1f', ($in_kills / ($in_kills + $in_deaths + $in_suicides)) * 100.0);
}
if (0 == $lm_kills + $lm_deaths + $lm_suicides) {
    $lm_eff = '0.0';
} else {
    $lm_eff = sprintf('%0.1f', ($lm_kills / ($lm_kills + $lm_deaths + $lm_suicides)) * 100.0);
}
if (0 == $other_kills + $other_deaths + $other_suicides) {
    $other_eff = '0.0';
} else {
    $other_eff = sprintf('%0.1f', ($other_kills / ($other_kills + $other_deaths + $other_suicides)) * 100.0);
}
if (0 == $plr_kills + $plr_deaths + $plr_suicides) {
    $total_eff = '0.0';
} else {
    $total_eff = sprintf('%0.1f', ($plr_kills / ($plr_kills + $plr_deaths + $plr_suicides)) * 100.0);
}

//========== FPH ==============================================================
if (0 == $dm_hours) {
    $dm_fph = '0.0';
} else {
    $dm_fph = sprintf('%0.1f', $dm_frags / ($dm_time / 3600));
}
if (0 == $tdm_hours) {
    $tdm_fph = '0.0';
} else {
    $tdm_fph = sprintf('%0.1f', $tdm_frags / ($tdm_time / 3600));
}
if (0 == $dd_hours) {
    $dd_fph = '0.0';
} else {
    $dd_fph = sprintf('%0.1f', $dd_frags / ($dd_time / 3600));
}
if (0 == $ctf_hours) {
    $ctf_fph = '0.0';
} else {
    $ctf_fph = sprintf('%0.1f', $ctf_frags / ($ctf_time / 3600));
}
if (0 == $br_hours) {
    $br_fph = '0.0';
} else {
    $br_fph = sprintf('%0.1f', $br_frags / ($br_time / 3600));
}
if (0 == $mu_hours) {
    $mu_fph = '0.0';
} else {
    $mu_fph = sprintf('%0.1f', $mu_frags / ($mu_time / 3600));
}
if (0 == $in_hours) {
    $in_fph = '0.0';
} else {
    $in_fph = sprintf('%0.1f', $in_frags / ($in_time / 3600));
}
if (0 == $lm_hours) {
    $lm_fph = '0.0';
} else {
    $lm_fph = sprintf('%0.1f', $lm_frags / ($lm_time / 3600));
}
if (0 == $other_hours) {
    $other_fph = '0.0';
} else {
    $other_fph = sprintf('%0.1f', $other_frags / ($other_time / 3600));
}
if (0 == $total_time) {
    $total_fph = '0.0';
} else {
    $total_fph = sprintf('%0.1f', $plr_frags / ($total_time / 3600));
}

//========== TTL ==============================================================
$dm_ttl = sprintf('%0.1f', $dm_time / ($dm_deaths + $dm_suicides + 1));
$tdm_ttl = sprintf('%0.1f', $tdm_time / ($tdm_deaths + $tdm_suicides + 1));
$dd_ttl = sprintf('%0.1f', $dd_time / ($dd_deaths + $dd_suicides + 1));
$ctf_ttl = sprintf('%0.1f', $ctf_time / ($ctf_deaths + $ctf_suicides + 1));
$br_ttl = sprintf('%0.1f', $br_time / ($br_deaths + $br_suicides + 1));
$mu_ttl = sprintf('%0.1f', $mu_time / ($mu_deaths + $mu_suicides + 1));
$in_ttl = sprintf('%0.1f', $in_time / ($in_deaths + $in_suicides + 1));
$lm_ttl = sprintf('%0.1f', $lm_time / ($lm_deaths + $lm_suicides + 1));
$other_ttl = sprintf('%0.1f', $other_time / ($other_deaths + $other_suicides + 1));
$total_ttl = sprintf('%0.1f', $total_time / ($plr_deaths + $plr_suicides + 1));

$dmlosses = $dm_wins2 + $dm_wins3 + $dm_losses;
$mulosses = $mu_wins2 + $mu_wins3 + $mu_losses;
$inlosses = $in_wins2 + $in_wins3 + $in_losses;
if ($plr_bot) {
    $botplayer = ' (bot)';
} else {
    $botplayer = '';
}

echo <<<EOF
<center>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="710" CLASS="box">
  <tr>
    <td CLASS="heading" ALIGN="center" COLSPAN="15">Career Summary for $plr_name$botplayer [$pnum]</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Game Type</td>
    <td CLASS="smheading" ALIGN="center">Score</td>
    <td CLASS="smheading" ALIGN="center">F</td>
    <td CLASS="smheading" ALIGN="center">K</td>
    <td CLASS="smheading" ALIGN="center">D</td>
    <td CLASS="smheading" ALIGN="center">S</td>
    <td CLASS="smheading" ALIGN="center">TK</td>
    <td CLASS="smheading" ALIGN="center">TD</td>
    <td CLASS="smheading" ALIGN="center">Eff</td>
    <td CLASS="smheading" ALIGN="center">Avg FPH</td>
    <td CLASS="smheading" ALIGN="center">Avg TTL</td>
    <td CLASS="smheading" ALIGN="center">Wins</td>
    <td CLASS="smheading" ALIGN="center">Losses</td>
    <td CLASS="smheading" ALIGN="center">Games</td>
    <td CLASS="smheading" ALIGN="center">Hours</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">DeathMatch</td>
    <td CLASS="grey" ALIGN="center">$dm_score</td>
    <td CLASS="grey" ALIGN="center">$dm_frags</td>
    <td CLASS="grey" ALIGN="center">$dm_kills</td>
    <td CLASS="grey" ALIGN="center">$dm_deaths</td>
    <td CLASS="grey" ALIGN="center">$dm_suicides</td>
    <td CLASS="grey" ALIGN="center">--</td>
    <td CLASS="grey" ALIGN="center">--</td>
    <td CLASS="grey" ALIGN="center">$dm_eff</td>
    <td CLASS="grey" ALIGN="center">$dm_fph</td>
    <td CLASS="grey" ALIGN="center">$dm_ttl</td>
    <td CLASS="grey" ALIGN="center">$dm_wins1</td>
    <td CLASS="grey" ALIGN="center">$dmlosses</td>
    <td CLASS="grey" ALIGN="center">$dm_games</td>
    <td CLASS="grey" ALIGN="center">$dm_hours</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Team DeathMatch</td>
    <td CLASS="grey" ALIGN="center">$tdm_score</td>
    <td CLASS="grey" ALIGN="center">$tdm_frags</td>
    <td CLASS="grey" ALIGN="center">$tdm_kills</td>
    <td CLASS="grey" ALIGN="center">$tdm_deaths</td>
    <td CLASS="grey" ALIGN="center">$tdm_suicides</td>
    <td CLASS="grey" ALIGN="center">$tdm_teamkills</td>
    <td CLASS="grey" ALIGN="center">$tdm_teamdeaths</td>
    <td CLASS="grey" ALIGN="center">$tdm_eff</td>
    <td CLASS="grey" ALIGN="center">$tdm_fph</td>
    <td CLASS="grey" ALIGN="center">$tdm_ttl</td>
    <td CLASS="grey" ALIGN="center">$tdm_wins</td>
    <td CLASS="grey" ALIGN="center">$tdm_losses</td>
    <td CLASS="grey" ALIGN="center">$tdm_games</td>
    <td CLASS="grey" ALIGN="center">$tdm_hours</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Double Domination</td>
    <td CLASS="grey" ALIGN="center">$dd_score</td>
    <td CLASS="grey" ALIGN="center">$dd_frags</td>
    <td CLASS="grey" ALIGN="center">$dd_kills</td>
    <td CLASS="grey" ALIGN="center">$dd_deaths</td>
    <td CLASS="grey" ALIGN="center">$dd_suicides</td>
    <td CLASS="grey" ALIGN="center">$dd_teamkills</td>
    <td CLASS="grey" ALIGN="center">$dd_teamdeaths</td>
    <td CLASS="grey" ALIGN="center">$dd_eff</td>
    <td CLASS="grey" ALIGN="center">$dd_fph</td>
    <td CLASS="grey" ALIGN="center">$dd_ttl</td>
    <td CLASS="grey" ALIGN="center">$dd_wins</td>
    <td CLASS="grey" ALIGN="center">$dd_losses</td>
    <td CLASS="grey" ALIGN="center">$dd_games</td>
    <td CLASS="grey" ALIGN="center">$dd_hours</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Capture the Flag</td>
    <td CLASS="grey" ALIGN="center">$ctf_score</td>
    <td CLASS="grey" ALIGN="center">$ctf_frags</td>
    <td CLASS="grey" ALIGN="center">$ctf_kills</td>
    <td CLASS="grey" ALIGN="center">$ctf_deaths</td>
    <td CLASS="grey" ALIGN="center">$ctf_suicides</td>
    <td CLASS="grey" ALIGN="center">$ctf_teamkills</td>
    <td CLASS="grey" ALIGN="center">$ctf_teamdeaths</td>
    <td CLASS="grey" ALIGN="center">$ctf_eff</td>
    <td CLASS="grey" ALIGN="center">$ctf_fph</td>
    <td CLASS="grey" ALIGN="center">$ctf_ttl</td>
    <td CLASS="grey" ALIGN="center">$ctf_wins</td>
    <td CLASS="grey" ALIGN="center">$ctf_losses</td>
    <td CLASS="grey" ALIGN="center">$ctf_games</td>
    <td CLASS="grey" ALIGN="center">$ctf_hours</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Bombing Run</td>
    <td CLASS="grey" ALIGN="center">$br_score</td>
    <td CLASS="grey" ALIGN="center">$br_frags</td>
    <td CLASS="grey" ALIGN="center">$br_kills</td>
    <td CLASS="grey" ALIGN="center">$br_deaths</td>
    <td CLASS="grey" ALIGN="center">$br_suicides</td>
    <td CLASS="grey" ALIGN="center">$br_teamkills</td>
    <td CLASS="grey" ALIGN="center">$br_teamdeaths</td>
    <td CLASS="grey" ALIGN="center">$br_eff</td>
    <td CLASS="grey" ALIGN="center">$br_fph</td>
    <td CLASS="grey" ALIGN="center">$br_ttl</td>
    <td CLASS="grey" ALIGN="center">$br_wins</td>
    <td CLASS="grey" ALIGN="center">$br_losses</td>
    <td CLASS="grey" ALIGN="center">$br_games</td>
    <td CLASS="grey" ALIGN="center">$br_hours</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Mutant</td>
    <td CLASS="grey" ALIGN="center">$mu_score</td>
    <td CLASS="grey" ALIGN="center">$mu_frags</td>
    <td CLASS="grey" ALIGN="center">$mu_kills</td>
    <td CLASS="grey" ALIGN="center">$mu_deaths</td>
    <td CLASS="grey" ALIGN="center">$mu_suicides</td>
    <td CLASS="grey" ALIGN="center">--</td>
    <td CLASS="grey" ALIGN="center">--</td>
    <td CLASS="grey" ALIGN="center">$mu_eff</td>
    <td CLASS="grey" ALIGN="center">$mu_fph</td>
    <td CLASS="grey" ALIGN="center">$mu_ttl</td>
    <td CLASS="grey" ALIGN="center">$mu_wins1</td>
    <td CLASS="grey" ALIGN="center">$mulosses</td>
    <td CLASS="grey" ALIGN="center">$mu_games</td>
    <td CLASS="grey" ALIGN="center">$mu_hours</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Invasion</td>
    <td CLASS="grey" ALIGN="center">$in_score</td>
    <td CLASS="grey" ALIGN="center">$in_frags</td>
    <td CLASS="grey" ALIGN="center">$in_kills</td>
    <td CLASS="grey" ALIGN="center">$in_deaths</td>
    <td CLASS="grey" ALIGN="center">$in_suicides</td>
    <td CLASS="grey" ALIGN="center">$in_teamkills</td>
    <td CLASS="grey" ALIGN="center">$in_teamsuicides</td>
    <td CLASS="grey" ALIGN="center">$in_eff</td>
    <td CLASS="grey" ALIGN="center">$in_fph</td>
    <td CLASS="grey" ALIGN="center">$in_ttl</td>
    <td CLASS="grey" ALIGN="center">$in_wins1</td>
    <td CLASS="grey" ALIGN="center">$inlosses</td>
    <td CLASS="grey" ALIGN="center">$in_games</td>
    <td CLASS="grey" ALIGN="center">$in_hours</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Last Man Standing</td>
    <td CLASS="grey" ALIGN="center">$lm_score</td>
    <td CLASS="grey" ALIGN="center">$lm_frags</td>
    <td CLASS="grey" ALIGN="center">$lm_kills</td>
    <td CLASS="grey" ALIGN="center">$lm_deaths</td>
    <td CLASS="grey" ALIGN="center">$lm_suicides</td>
    <td CLASS="grey" ALIGN="center">--</td>
    <td CLASS="grey" ALIGN="center">--</td>
    <td CLASS="grey" ALIGN="center">$lm_eff</td>
    <td CLASS="grey" ALIGN="center">$lm_fph</td>
    <td CLASS="grey" ALIGN="center">$lm_ttl</td>
    <td CLASS="grey" ALIGN="center">$lm_wins</td>
    <td CLASS="grey" ALIGN="center">$lm_losses</td>
    <td CLASS="grey" ALIGN="center">$lm_games</td>
    <td CLASS="grey" ALIGN="center">$lm_hours</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Custom Other</td>
    <td CLASS="grey" ALIGN="center">$other_score</td>
    <td CLASS="grey" ALIGN="center">$other_frags</td>
    <td CLASS="grey" ALIGN="center">$other_kills</td>
    <td CLASS="grey" ALIGN="center">$other_deaths</td>
    <td CLASS="grey" ALIGN="center">$other_suicides</td>
    <td CLASS="grey" ALIGN="center">$other_teamkills</td>
    <td CLASS="grey" ALIGN="center">$other_teamdeaths</td>
    <td CLASS="grey" ALIGN="center">$other_eff</td>
    <td CLASS="grey" ALIGN="center">$other_fph</td>
    <td CLASS="grey" ALIGN="center">$other_ttl</td>
    <td CLASS="grey" ALIGN="center">$other_wins</td>
    <td CLASS="grey" ALIGN="center">$other_losses</td>
    <td CLASS="grey" ALIGN="center">$other_games</td>
    <td CLASS="grey" ALIGN="center">$other_hours</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Totals</td>
    <td CLASS="darkgrey" ALIGN="center">$total_score</td>
    <td CLASS="darkgrey" ALIGN="center">$plr_frags</td>
    <td CLASS="darkgrey" ALIGN="center">$plr_kills</td>
    <td CLASS="darkgrey" ALIGN="center">$plr_deaths</td>
    <td CLASS="darkgrey" ALIGN="center">$plr_suicides</td>
    <td CLASS="darkgrey" ALIGN="center">$total_teamkills</td>
    <td CLASS="darkgrey" ALIGN="center">$total_teamdeaths</td>
    <td CLASS="darkgrey" ALIGN="center">$total_eff</td>
    <td CLASS="darkgrey" ALIGN="center">$total_fph</td>
    <td CLASS="darkgrey" ALIGN="center">$total_ttl</td>
    <td CLASS="darkgrey" ALIGN="center">$total_wins</td>
    <td CLASS="darkgrey" ALIGN="center">$total_losses</td>
    <td CLASS="darkgrey" ALIGN="center">$total_games</td>
    <td CLASS="darkgrey" ALIGN="center">$total_hours</td>
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
    <td CLASS="grey" ALIGN="center">$dd_cpcapture</td>
    <td CLASS="grey" ALIGN="center">$ctf_flagcapture</td>
    <td CLASS="grey" ALIGN="center">$ctf_flagkill</td>
    <td CLASS="grey" ALIGN="center">$ctf_flagassist</td>
    <td CLASS="grey" ALIGN="center">$ctf_flagreturn</td>
    <td CLASS="grey" ALIGN="center">$ctf_flagpickup</td>
    <td CLASS="grey" ALIGN="center">$ctf_flagdrop</td>
    <td CLASS="grey" ALIGN="center">$br_bombcarried</td>
    <td CLASS="grey" ALIGN="center">$br_bombtossed</td>
    <td CLASS="grey" ALIGN="center">$br_bombkill</td>
    <td CLASS="grey" ALIGN="center">$br_bombdrop</td>
  </tr>
</table>
EOF;

//=============================================================================
//========== Career Summary - Single Player Tournament Games ==================
//=============================================================================

//=============================================================================
//========== Special Events ===================================================
//=============================================================================

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
    <td CLASS="grey" ALIGN="center">$plr_firstblood</td>
    <td CLASS="dark" ALIGN="center">Head Shots</td>
    <td CLASS="grey" ALIGN="center">$plr_headshots</td>
    <td CLASS="dark" ALIGN="center">Failed Translocations</td>
    <td CLASS="grey" ALIGN="center">$plr_transgib</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Double Kills</td>
    <td CLASS="grey" ALIGN="center">$plr_multi1</td>
    <td CLASS="dark" ALIGN="center">Multi Kills</td>
    <td CLASS="grey" ALIGN="center">$plr_multi2</td>
    <td CLASS="dark" ALIGN="center">Mega Kills</td>
    <td CLASS="grey" ALIGN="center">$plr_multi3</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Ultra Kills</td>
    <td CLASS="grey" ALIGN="center">$plr_multi4</td>
    <td CLASS="dark" ALIGN="center">Monster Kills</td>
    <td CLASS="grey" ALIGN="center">$plr_multi5</td>
    <td CLASS="dark" ALIGN="center">Ludicrous Kills</td>
    <td CLASS="grey" ALIGN="center">$plr_multi6</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Holy Shit Kills</td>
    <td CLASS="grey" ALIGN="center">$plr_multi7</td>
    <td CLASS="dark" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
    <td CLASS="dark" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
  </tr>
</table>

EOF;

//=============================================================================
//========== Weapon Specific Totals ===========================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="540">
  <tr>
    <td CLASS="heading" COLSPAN="7" ALIGN="center">Weapon Specific Totals</td>
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
// Load Player Weapon Kills for current player
$result = sqlqueryn("SELECT * FROM $ut_pwkills WHERE pwk_player='$pnum'");
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $weapon = $row['pwk_weapon'];

    $frags = $row['pwk_frags'];

    $kills = $row['pwk_kills'];

    $deaths = $row['pwk_deaths'];

    $suicides = $row['pwk_suicides'];

    if ($frags || $kills || $deaths || $suicides) {
        // Look for existing matching wskills description

        $weap = -1;

        $secondary = 0;

        for ($i = 0; $i < $numweapons && $weap < 0; $i++) {
            if (!strcmp($wskills[4][$i], $weapons[$weapon][0])) {
                $weap = $i;

                $secondary = $weapons[$weapon][1];
            }
        }

        // Add weapon if not already used

        if ($weap < 0) {
            $wskills[0][$numweapons] = $wskills[1][$numweapons] = 0; // Primary Kills / Secondary Kills
            $wskills[2][$numweapons] = $wskills[3][$numweapons] = 0; // Deaths / Suicides
            $wskills[4][$numweapons] = $weapons[$weapon][0]; // Description
            $wskills[5][$numweapons] = 0; // Frags
            $weap = $numweapons++;

            $secondary = $weapons[$weapon][1];
        }

        $wskills[5][$weap] += $frags;

        if ($secondary) {
            $wskills[1][$weap] += $kills;
        } else {
            $wskills[0][$weap] += $kills;
        }

        $wskills[2][$weap] += $deaths;

        $wskills[3][$weap] += $suicides;
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

if ($numweapons > 0) {
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

        if (0 == $kills + $skills + $deaths + $suicides) {
            $eff = '0.0';
        } else {
            $eff = sprintf('%0.1f', (($kills + $skills) / ($kills + $skills + $deaths + $suicides)) * 100.0);
        }

        if ($kills > 0 || $skills > 0 || $deaths > 0) {
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
}
echo "</table>\n";

//=============================================================================
//========== Suicides Totals ==================================================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="255">
  <tr>
    <td CLASS="medheading" ALIGN="center" COLSPAN="2">Suicides Totals</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Type</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="55">Suicides</td>
  </tr>

EOF;

if ($numweapons > 0) {
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
//========== Killing Sprees by Type ===========================================
//=============================================================================

$time1 = sprintf('%0.1f', $plr_spreet1 / 60);
$time2 = sprintf('%0.1f', $plr_spreet2 / 60);
$time3 = sprintf('%0.1f', $plr_spreet3 / 60);
$time4 = sprintf('%0.1f', $plr_spreet4 / 60);
$time5 = sprintf('%0.1f', $plr_spreet5 / 60);
$time6 = sprintf('%0.1f', $plr_spreet6 / 60);

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
    <td CLASS="grey" ALIGN="center">$plr_spree1</td>
    <td CLASS="grey" ALIGN="center">$time1</td>
    <td CLASS="grey" ALIGN="center">$plr_spreek1</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Rampage</td>
    <td CLASS="grey" ALIGN="center">$plr_spree2</td>
    <td CLASS="grey" ALIGN="center">$time2</td>
    <td CLASS="grey" ALIGN="center">$plr_spreek2</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Dominating</td>
    <td CLASS="grey" ALIGN="center">$plr_spree3</td>
    <td CLASS="grey" ALIGN="center">$time3</td>
    <td CLASS="grey" ALIGN="center">$plr_spreek3</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Unstoppable</td>
    <td CLASS="grey" ALIGN="center">$plr_spree4</td>
    <td CLASS="grey" ALIGN="center">$time4</td>
    <td CLASS="grey" ALIGN="center">$plr_spreek4</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Godlike</td>
    <td CLASS="grey" ALIGN="center">$plr_spree5</td>
    <td CLASS="grey" ALIGN="center">$time5</td>
    <td CLASS="grey" ALIGN="center">$plr_spreek5</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Wicked Sick</td>
    <td CLASS="grey" ALIGN="center">$plr_spree6</td>
    <td CLASS="grey" ALIGN="center">$time6</td>
    <td CLASS="grey" ALIGN="center">$plr_spreek6</td>
  </tr>
</table>

EOF;

//=============================================================================
//========== Total Items Collected ============================================
//=============================================================================

// Load Item Descriptions
$result = sqlqueryn("SELECT it_num,it_desc FROM $ut_items");
if (!$result) {
    echo "Error loading item descriptions.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $items[$row['it_num']] = $row['it_desc'];
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

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

$result = sqlqueryn("SELECT pi_item,pi_pickups FROM $ut_pitems WHERE pi_plr='$pnum'");
if (!$result) {
    echo "Error loading player item pickups.<br>\n";

    exit;
}

$totpickups = 0;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $pickups[0][$totpickups] = $items[$row['pi_item']];

    $pickups[1][$totpickups++] = $row['pi_pickups'];
}

if ($totpickups > 0) {
    array_multisort(
        $pickups[1],
        SORT_DESC,
        SORT_NUMERIC,
        $pickups[0],
        SORT_ASC,
        SORT_STRING
    );
}

$col = 0;
for ($i = 0; $i < $totpickups; $i++) {
    $item = $pickups[0][$i];

    $num = $pickups[1][$i];

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

//=============================================================================
//========== Most Recent Games Played =========================================
//=============================================================================

// Load game types
$numtypes = 0;
$result = sqlqueryn("SELECT * FROM $ut_type");
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $gtype[$numtypes++] = $row;
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2 BORDER="0">
  <tr>
    <td CLASS="heading" COLSPAN="5" ALIGN="center">Most Recent Games Played</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="220">Date</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="160">Match Type</td>
    <td CLASS="smheading" ALIGN="center">Map</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="50">Players</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="40">Time</td>
  </tr>

EOF;

$games = 0;
$result = sqlqueryn(
    "SELECT gm_num, gm_map, gm_type, gm_start, gm_length, gm_numplayers
                       FROM $ut_gplayers, $ut_games
                       WHERE $ut_gplayers.gp_pnum='$pnum' && $ut_games.gm_num=$ut_gplayers.gp_game
                       ORDER BY $ut_games.gm_start DESC LIMIT 10"
);
if (!$result) {
    echo "Error accessing game and game player tables.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    while (list($key, $val) = each($row)) {
        ${$key} = $val;
    }

    $gametype = '';

    for ($i = 0; $i < $numtypes && !$gametype; $i++) {
        if ($gtype[$i][0] == $gm_type) {
            $gametype = $gtype[$i][1];
        }
    }

    $start = strtotime($gm_start);

    $matchdate = date('D, M d Y \a\t g:i:s A', $start);

    $length = sprintf('%0.1f', $gm_length / 60.0);

    echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center"><a CLASS="dark" HREF="gamestats.php?game=$gm_num">$matchdate</a></td>
    <td CLASS="grey" ALIGN="center">$gametype</td>
    <td CLASS="grey" ALIGN="center">$gm_map</td>
    <td CLASS="grey" ALIGN="center">$gm_numplayers</td>
    <td CLASS="grey" ALIGN="center">$length</td>
  </tr>

EOF;

    $games++;
}
echo "</table>\n";
$GLOBALS['xoopsDB']->freeRecordSet($result);

$GLOBALS['xoopsDB']->close($link);

echo <<<EOF
</center>

</td></tr></table>

EOF;

require XOOPS_ROOT_PATH . '/footer.php';
