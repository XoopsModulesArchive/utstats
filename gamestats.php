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

$start = strtotime($gm_start);
$matchdate = date('D, M d Y \a\t g:i:s A', $start);

// Set game type
$result = sqlqueryn("SELECT tp_desc,tp_type FROM $ut_type WHERE tp_num='$gm_type' LIMIT 1");
$row = $GLOBALS['xoopsDB']->fetchBoth($result);
if (!$row) {
    echo "Error locating game type.<br>\n";

    exit;
}
$gametype = $row['tp_desc'];
$gametval = $row['tp_type'];
if ($gametval > 1 && $gametval < 6) {
    $teams = 1;
} else {
    $teams = 0;
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

// Load Players
$maxplayer = 0;
$result = sqlqueryn("SELECT * FROM $ut_gplayers WHERE gp_game = '$gamenum'");
if (!$result) {
    echo "Game player list database error.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $num = $row['gp_num'];

    if ($num > $maxplayer) {
        $maxplayer = $num;
    }

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

// Set Player Ranks
for ($r = 1; $r <= $gm_numplayers; $r++) {
    for ($i = 0, $ranks[$r] = 0; $i <= $maxplayer && !$ranks[$r]; $i++) {
        if (isset($gplayer[$i]) && $gplayer[$i]['gp_rank'] == $r) {
            $ranks[$r] = $i;
        }
    }
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

$total_frags = $gm_kills - $gm_suicides;
$total_score = 0;
for ($i = 0; $i <= $maxplayer; $i++) {
    if (isset($gplayer[$i])) {
        $total_score += $gplayer[$i]['gp_t0score'] + $gplayer[$i]['gp_t1score'];
    }
}

echo <<<EOF
<center>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="720">
  <tr>
    <td CLASS="heading" ALIGN="center">Game Index for $gm_server $gm_map</td>
  </tr>
</table>
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="235" CLASS="box">
  <tr>
    <td CLASS="heading" ALIGN="center" COLSPAN="5">Totals for This Game</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Score</td>
    <td CLASS="smheading" ALIGN="center">Frags</td>
    <td CLASS="smheading" ALIGN="center">Kills</td>
    <td CLASS="smheading" ALIGN="center">Deaths</td>
    <td CLASS="smheading" ALIGN="center">Suicides</td>
  </tr>
  <tr>
    <td CLASS="grey" ALIGN="center">$total_score</td>
    <td CLASS="grey" ALIGN="center">$total_frags</td>
    <td CLASS="grey" ALIGN="center">$gm_kills</td>
    <td CLASS="grey" ALIGN="center">$gm_deaths</td>
    <td CLASS="grey" ALIGN="center">$gm_suicides</td>
  </tr>
</table>
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="650">
  <tr>
    <td CLASS="heading" COLSPAN="4" ALIGN="center">Unreal Tournament 2003 Game Stats</td>
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
//========== Flag Event Summary ===============================================
//=============================================================================

if (2 == $gametval) {
    echo <<<EOF
<br>
<table CELLPADDING="0" CELLSPACING="2" BORDER="0" WIDTH="640">
  <tr>
    <td CLASS="heading" COLSPAN="20" ALIGN="center">Flag Event Summary</td>
  </tr>

EOF;

    for ($tm = 1; $tm >= 0; $tm--) {
        if (1 == $tm) {
            $tmcolor = 'Blue';
        } else {
            $tmcolor = 'Red';
        }

        echo <<<EOF
  <tr>
    <td CLASS="hlheading" COLSPAN="10" ALIGN="center">Team: $tmcolor</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="40">Rank</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Player</td>
    <td CLASS="smheading" ALIGN="center" COLSPAN="2" WIDTH="90">Score</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="60">Flag Captures</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="50">Flag Kills</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="60">Flag Assists</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="50">Flag Saves</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="60">Flag Pickups</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="50">Flag Drops</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Team</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
  </tr>

EOF;

        $tscore = $tcapture = $tflagkill = $tassist = $treturn = $tpickup = $tdrop = 0;

        for ($r = 1; $r <= $gm_numplayers; $r++) {
            $i = $ranks[$r];

            if ($gplayer[$i]['gp_team'] == $tm
                || ($gplayer[$i]['gp_t0score'] && 0 == $tm)
                || ($gplayer[$i]['gp_t1score'] && 1 == $tm)) {
                reset($gplayer[$i]);

                while (list($key, $val) = each($gplayer[$i])) {
                    ${$key} = $val;
                }

                $frags = $gp_kills - $gp_suicides;

                if (1 == $tm) {
                    $score = $gp_t1score;
                } else {
                    $score = $gp_t0score;
                }

                $tscore += $score;

                $tcapture += $gp_capcarry;

                $tflagkill += $gp_typekill;

                $tassist += $gp_assist;

                $treturn += $gp_return;

                $tpickup += $gp_pickup;

                $tdrop += $gp_drop;

                if ($gp_bot) {
                    $nameclass = 'darkbot';
                } else {
                    $nameclass = 'darkhuman';
                }

                $gpplayer = "<a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$gp_num\">$gp_name</a>";

                echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$gp_rank</td>
    <td CLASS="$nameclass" ALIGN="center">$gpplayer</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">$score</td>
    <td CLASS="grey" ALIGN="center">$gp_capcarry</td>
    <td CLASS="grey" ALIGN="center">$gp_typekill</td>
    <td CLASS="grey" ALIGN="center">$gp_assist</td>
    <td CLASS="grey" ALIGN="center">$gp_return</td>
    <td CLASS="grey" ALIGN="center">$gp_pickup</td>
    <td CLASS="grey" ALIGN="center">$gp_drop</td>
  </tr>

EOF;
            }
        }

        if (1 == $tm) {
            $teamscore = $gm_t1score;
        } else {
            $teamscore = $gm_t0score;
        }

        echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center" COLSPAN="2">Totals</td>
    <td CLASS="darkgrey" ALIGN="center">$teamscore</td>
    <td CLASS="darkgrey" ALIGN="center">$tscore</td>
    <td CLASS="darkgrey" ALIGN="center">$tcapture</td>
    <td CLASS="darkgrey" ALIGN="center">$tflagkill</td>
    <td CLASS="darkgrey" ALIGN="center">$tassist</td>
    <td CLASS="darkgrey" ALIGN="center">$treturn</td>
    <td CLASS="darkgrey" ALIGN="center">$tpickup</td>
    <td CLASS="darkgrey" ALIGN="center">$tdrop</td>
  </tr>

EOF;
    }

    echo <<<EOF
</table>

EOF;
}

//=============================================================================
//========== Team Scoring Graph (if imaging available) ========================
//=============================================================================

if ($teams && function_exists('ImageTypes')) {
    if (imagetypes() & (IMG_JPG | IMG_GIF | IMG_PNG)) {
        echo <<<EOF
<br>
<table>
  <tr>
    <td CLASS="medheading" COLSPAN="10" ALIGN="center">Team Scores</td>
  </tr>
  <tr>
    <td><img SRC="gamegraph.php?type=2&game=$gamenum" WIDTH="550" HEIGHT="180"></td>
  </tr>
</table>

EOF;
    }
}

//=============================================================================
//========== Game Summary =====================================================
//=============================================================================

if ($teams) {
    echo <<<EOF
<br>
<table CELLPADDING="0" CELLSPACING="2" BORDER="0" WIDTH="720">
  <tr>
    <td CLASS="heading" COLSPAN="21" ALIGN="center">Game Summary</td>
  </tr>

EOF;

    $opend = 0;

    for ($tm = 1; $tm >= 0; $tm--) {
        if (1 == $tm) {
            $tmcolor = 'Blue';
        } else {
            $tmcolor = 'Red';
        }

        echo <<<EOF
  <tr>
    <td CLASS="hlheading" COLSPAN="21" ALIGN="center">Team: $tmcolor</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="40">Rank</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Player</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="20"><img SRC="resource/pcolors.png" WIDTH="16" HEIGHT="16" BORDER="0"></td>
    <td CLASS="smheading" ALIGN="center" COLSPAN="2" WIDTH="90">Score</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="20">F</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="20">K</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="20">D</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="20">S</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="20">TK</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="20">TD</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="55">Eff.</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="50">Avg. SPH</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="50">Avg. TTL</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="40">Time</td>
    <td CLASS="smheading" ALIGN="center" COLSPAN="6" WIDTH="100">Sprees</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Team</td>
    <td CLASS="smheading" ALIGN="center">Player</td>
    <td CLASS="smheading" ALIGN="center">K</td>
    <td CLASS="smheading" ALIGN="center">R</td>
    <td CLASS="smheading" ALIGN="center">D</td>
    <td CLASS="smheading" ALIGN="center">U</td>
    <td CLASS="smheading" ALIGN="center">G</td>
    <td CLASS="smheading" ALIGN="center">W</td>
  </tr>

EOF;

        $tscore = $tkills = $tdeaths = $tsuicides = $ttkills = $ttdeaths = $ttime = 0;

        $tspree1 = $tspree2 = $tspree3 = $tspree4 = $tspree5 = $tspree6 = $teamsize = 0;

        for ($r = 1; $r <= $gm_numplayers; $r++) {
            $i = $ranks[$r];

            if ($gplayer[$i]['gp_team'] == $tm
                || ($gplayer[$i]['gp_t0score'] && 0 == $tm)
                || ($gplayer[$i]['gp_t1score'] && 1 == $tm)) {
                reset($gplayer[$i]);

                while (list($key, $val) = each($gplayer[$i])) {
                    ${$key} = $val;
                }

                $teamsize++;

                $frags = $gp_kills - $gp_suicides;

                $score = $gp_t0score + $gp_t1score;

                $tscore += $score;

                $tkills += $gp_kills;

                $tdeaths += $gp_deaths;

                $tsuicides += $gp_suicides;

                $ttkills += $gp_teamkills;

                $ttdeaths += $gp_teamdeaths;

                $ttime += $gp_time;

                $tspree1 += $gp_spree1;

                $tspree2 += $gp_spree2;

                $tspree3 += $gp_spree3;

                $tspree4 += $gp_spree4;

                $tspree5 += $gp_spree5;

                $tspree6 += $gp_spree6;

                if (0 == $gp_kills + $gp_deaths + $gp_suicides) {
                    $eff = '0.0';
                } else {
                    $eff = sprintf('%0.1f', ($gp_kills / ($gp_kills + $gp_deaths + $gp_suicides)) * 100.0);
                }

                if (0 == $gp_time) {
                    $sph = '0.0';
                } else {
                    $sph = sprintf('%0.1f', $score * (3600 / $gp_time));
                }

                $ttl = sprintf('%0.1f', $gp_time / ($gp_deaths + $gp_suicides + 1));

                $time = round($gp_time / 60.0, 1);

                if ($gp_bot) {
                    $nameclass = 'darkbot';
                } else {
                    $nameclass = 'darkhuman';
                }

                if ($gp_team != $tm) {
                    $gpplayer = "<a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$gp_num\">*$gp_name</a>";

                    $opend = 1;
                } else {
                    $gpplayer = "<a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$gp_num\">$gp_name</a>";
                }

                echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$gp_rank</td>
    <td CLASS="$nameclass" ALIGN="center">$gpplayer</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">$score</td>
    <td CLASS="grey" ALIGN="center">$frags</td>
    <td CLASS="grey" ALIGN="center">$gp_kills</td>
    <td CLASS="grey" ALIGN="center">$gp_deaths</td>
    <td CLASS="grey" ALIGN="center">$gp_suicides</td>
    <td CLASS="grey" ALIGN="center">$gp_teamkills</td>
    <td CLASS="grey" ALIGN="center">$gp_teamdeaths</td>
    <td CLASS="grey" ALIGN="center">$eff%</td>
    <td CLASS="grey" ALIGN="center">$sph</td>
    <td CLASS="grey" ALIGN="center">$ttl</td>
    <td CLASS="grey" ALIGN="center">$time</td>
    <td CLASS="grey" ALIGN="center">$gp_spree1</td>
    <td CLASS="grey" ALIGN="center">$gp_spree2</td>
    <td CLASS="grey" ALIGN="center">$gp_spree3</td>
    <td CLASS="grey" ALIGN="center">$gp_spree4</td>
    <td CLASS="grey" ALIGN="center">$gp_spree5</td>
    <td CLASS="grey" ALIGN="center">$gp_spree6</td>
  </tr>

EOF;
            }
        }

        if (1 == $tm) {
            $cimage = 'resource/p1color.png';

            $teamscore = $gm_t1score;
        } else {
            $cimage = 'resource/p2color.png';

            $teamscore = $gm_t0score;
        }

        $frags = $tkills - $tsuicides;

        if (0 == $tkills + $tdeaths + $tsuicides) {
            $eff = '0.0';
        } else {
            $eff = sprintf('%0.1f', ($tkills / ($tkills + $tdeaths + $tsuicides)) * 100.0);
        }

        if (0 == $ttime) {
            $sph = '0.0';
        } else {
            $sph = sprintf('%0.1f', $score * (3600 / $ttime));
        }

        $ttl = sprintf('%0.1f', $ttime / ($tdeaths + $tsuicides + 1));

        if ($teamsize > 0) {
            $time = sprintf('%0.1f', $ttime / 60.0 / $teamsize);
        } else {
            $time = sprintf('%0.1f', $ttime / 60.0);
        }

        echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center" COLSPAN="2">Totals</td>
    <td CLASS="darkgrey" ALIGN="center"><img SRC="$cimage" WIDTH="16" HEIGHT="16" BORDER="0"></td>
    <td CLASS="darkgrey" ALIGN="center">$teamscore</td>
    <td CLASS="darkgrey" ALIGN="center">$tscore</td>
    <td CLASS="darkgrey" ALIGN="center">$frags</td>
    <td CLASS="darkgrey" ALIGN="center">$tkills</td>
    <td CLASS="darkgrey" ALIGN="center">$tdeaths</td>
    <td CLASS="darkgrey" ALIGN="center">$tsuicides</td>
    <td CLASS="darkgrey" ALIGN="center">$ttkills</td>
    <td CLASS="darkgrey" ALIGN="center">$ttdeaths</td>
    <td CLASS="darkgrey" ALIGN="center">$eff%</td>
    <td CLASS="darkgrey" ALIGN="center">$sph</td>
    <td CLASS="darkgrey" ALIGN="center">$ttl</td>
    <td CLASS="darkgrey" ALIGN="center">$time</td>
    <td CLASS="darkgrey" ALIGN="center">$tspree1</td>
    <td CLASS="darkgrey" ALIGN="center">$tspree2</td>
    <td CLASS="darkgrey" ALIGN="center">$tspree3</td>
    <td CLASS="darkgrey" ALIGN="center">$tspree4</td>
    <td CLASS="darkgrey" ALIGN="center">$tspree5</td>
    <td CLASS="darkgrey" ALIGN="center">$tspree6</td>
  </tr>

EOF;
    }

    if ($opend) {
        echo <<<EOF
  <tr>
    <td CLASS="opnote" COLSPAN="20">* Player ended game on opposing team.</td>
  </tr>

EOF;
    }

    echo <<<EOF
</table>

EOF;
}

//=============================================================================
//========== Score Graph (if imaging available) ===============================
//=============================================================================

if (function_exists('ImageTypes') && $gametval > 1) {
    if (imagetypes() & (IMG_JPG | IMG_GIF | IMG_PNG)) {
        $graphheader = 'Individual Scores';

        $graphtype = 3;

        echo <<<EOF
<br>
<table>
  <tr>
    <td CLASS="medheading" COLSPAN="10" ALIGN="center">$graphheader</td>
  </tr>
  <tr>
    <td><img SRC="gamegraph.php?type=$graphtype&game=$gamenum" WIDTH="550" HEIGHT="180"></td>
  </tr>
</table>

EOF;
    }
}

//=============================================================================
//========== Frag Graph (if imaging available) ================================
//=============================================================================

if (function_exists('ImageTypes') && 7 != $gametval) {
    if (imagetypes() & (IMG_JPG | IMG_GIF | IMG_PNG)) {
        $graphheader = 'Individual Frags';

        $graphtype = 1;

        echo <<<EOF
<br>
<table>
  <tr>
    <td CLASS="medheading" COLSPAN="10" ALIGN="center">$graphheader</td>
  </tr>
  <tr>
    <td><img SRC="gamegraph.php?type=$graphtype&game=$gamenum" WIDTH="550" HEIGHT="180"></td>
  </tr>
</table>

EOF;
    }
}

//=============================================================================
//========== Player Summary ===================================================
//=============================================================================

if (6 != $gametval && 7 != $gametval && 8 != $gametval) {
    $fphsph = 'Avg. FPH';
} else {
    $fphsph = 'Avg. SPH';
}
echo <<<EOF
<br>
<table CELLPADDING="0" CELLSPACING="2" BORDER="0" WIDTH="720">
  <tr>
    <td CLASS="heading" COLSPAN="19" ALIGN="center">Player Summary</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Rank</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Player</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2" WIDTH="20"><img SRC="resource/pcolors.png" WIDTH="16" HEIGHT="16" BORDER="0"></td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Score</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Frags</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Kills</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Deaths</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Suicides</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Eff.</td>
    <td WIDTH="50" CLASS="smheading" ALIGN="center" ROWSPAN="2">$fphsph</td>
    <td WIDTH="50" CLASS="smheading" ALIGN="center" ROWSPAN="2">Avg. TTL</td>
    <td CLASS="smheading" ALIGN="center" ROWSPAN="2">Time</td>
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

EOF;

$total_score = $total_frags = $total_kills = $total_deaths = $total_suicides = $total_time = 0;
$total_eff = $total_fph = $total_ttl = 0;
$total_spree1 = $total_spree2 = $total_spree3 = $total_spree4 = $total_spree5 = $total_spree6 = 0;
$lowscore = $highscore = 0;

for ($r = 1; $r <= $gm_numplayers; $r++) {
    $i = $ranks[$r];

    reset($gplayer[$i]);

    while (list($key, $val) = each($gplayer[$i])) {
        ${$key} = $val;
    }

    $frags = $gp_kills - $gp_suicides;

    $score = $gp_t0score + $gp_t1score;

    if (0 == $gp_kills + $gp_deaths + $gp_suicides) {
        $eff = '0.0';
    } else {
        $eff = sprintf('%0.1f', ($gp_kills / ($gp_kills + $gp_deaths + $gp_suicides)) * 100.0);
    }

    if (0 == $gp_time) {
        $fph = '0.0';
    } else {
        if (6 != $gametval && 7 != $gametval && 8 != $gametval) {
            $fph = sprintf('%0.1f', $frags * (3600 / $gp_time));
        } else {
            $fph = sprintf('%0.1f', $score * (3600 / $gp_time));
        }
    }

    $ttl = sprintf('%0.1f', $gp_time / ($gp_deaths + $gp_suicides + 1));

    $time = sprintf('%0.1f', $gp_time / 60.0);

    $total_score += $score;

    $total_frags += $frags;

    $total_kills += $gp_kills;

    $total_deaths += $gp_deaths;

    $total_suicides += $gp_suicides;

    $total_time += $gp_time;

    $total_spree1 += $gp_spree1;

    $total_spree2 += $gp_spree2;

    $total_spree3 += $gp_spree3;

    $total_spree4 += $gp_spree4;

    $total_spree5 += $gp_spree5;

    $total_spree6 += $gp_spree6;

    if ($frags < $lowscore) {
        $lowscore = $frags;
    }

    if ($frags > $highscore) {
        $highscore = $frags;
    }

    if ($gp_bot) {
        $nameclass = 'darkbot';
    } else {
        $nameclass = 'darkhuman';
    }

    $gpplayer = "<a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$gp_num\">$gp_name</a>";

    if ($r > 8) {
        $cimage = 'resource/nocolor.png';
    } else {
        $cimage = 'resource/p' . $r . 'color.png';
    }

    echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$gp_rank</td>
    <td CLASS="$nameclass" ALIGN="center">$gpplayer</td>
    <td CLASS="grey" ALIGN="center" WIDTH="20"><img SRC="$cimage" WIDTH="16" HEIGHT="16" BORDER="0"></td>
    <td CLASS="grey" ALIGN="center">$score</td>
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

EOF;
}

if (0 == $total_kills + $total_deaths + $total_suicides) {
    $eff = '0.0';
} else {
    $eff = sprintf('%0.1f', ($total_kills / ($total_kills + $total_deaths + $total_suicides)) * 100.0);
}
if (0 == $total_time) {
    $fph = '0.0';
} else {
    if (6 != $gametval && 7 != $gametval && 8 != $gametval) {
        $fph = sprintf('%0.1f', $total_frags * (3600 / $total_time));
    } else {
        $fph = sprintf('%0.1f', $total_score * (3600 / $total_time));
    }
}
$ttl = sprintf('%0.1f', $total_time / ($total_deaths + $total_suicides + 1));
$time = sprintf('%0.1f', $total_time / $gm_numplayers / 60.0);

echo <<<EOF
  <tr>
    <td CLASS="dark" COLSPAN="2" ALIGN="center">Totals</td>
    <td CLASS="darkgrey" ALIGN="center" WIDTH="20"><img SRC="resource/p1color.png" WIDTH="16" HEIGHT="16" BORDER="0"></td>
    <td CLASS="darkgrey" ALIGN="center">$total_score</td>
    <td CLASS="darkgrey" ALIGN="center">$total_frags</td>
    <td CLASS="darkgrey" ALIGN="center">$total_kills</td>
    <td CLASS="darkgrey" ALIGN="center">$total_deaths</td>
    <td CLASS="darkgrey" ALIGN="center">$total_suicides</td>
    <td CLASS="darkgrey" ALIGN="center">$eff%</td>
    <td CLASS="darkgrey" ALIGN="center">$fph</td>
    <td CLASS="darkgrey" ALIGN="center">$ttl</td>
    <td CLASS="darkgrey" ALIGN="center">$time</td>
    <td CLASS="darkgrey" ALIGN="center">$total_spree1</td>
    <td CLASS="darkgrey" ALIGN="center">$total_spree2</td>
    <td CLASS="darkgrey" ALIGN="center">$total_spree3</td>
    <td CLASS="darkgrey" ALIGN="center">$total_spree4</td>
    <td CLASS="darkgrey" ALIGN="center">$total_spree5</td>
    <td CLASS="darkgrey" ALIGN="center">$total_spree6</td>
  </tr>
</table>

EOF;

//=============================================================================
//========== Special Events ===================================================
//=============================================================================

$transgib = 0;
$multi1 = 0;
$multi2 = 0;
$multi3 = 0;
$multi4 = 0;
$multi5 = 0;
$multi6 = 0;
$multi7 = 0;

for ($i = 0; $i <= $maxplayer; $i++) {
    if (isset($gplayer[$i])) {
        $transgib += $gplayer[$i]['gp_transgib'];

        $multi1 += $gplayer[$i]['gp_multi1'];

        $multi2 += $gplayer[$i]['gp_multi2'];

        $multi3 += $gplayer[$i]['gp_multi3'];

        $multi4 += $gplayer[$i]['gp_multi4'];

        $multi5 += $gplayer[$i]['gp_multi5'];

        $multi6 += $gplayer[$i]['gp_multi6'];

        $multi7 += $gplayer[$i]['gp_multi7'];
    }
}

if ($gm_firstblood >= 0) {
    $name = $gplayer[$gm_firstblood]['gp_name'];

    if ($gplayer[$gm_firstblood]['gp_bot']) {
        $nameclass = 'darkbot';
    } else {
        $nameclass = 'darkhuman';
    }

    $firstblood = "<a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$gm_firstblood\">$name</a>";
} else {
    $firstblood = '&nbsp;';
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
    <td CLASS="darkhuman" ALIGN="center">$firstblood</td>
    <td CLASS="dark" ALIGN="center">Head Shots</td>
    <td CLASS="grey" ALIGN="center">$gm_headshots</td>
    <td CLASS="dark" ALIGN="center">Failed Translocations</td>
    <td CLASS="grey" ALIGN="center">$transgib</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Double Kills</td>
    <td CLASS="grey" ALIGN="center">$multi1</td>
    <td CLASS="dark" ALIGN="center">Multi Kills</td>
    <td CLASS="grey" ALIGN="center">$multi2</td>
    <td CLASS="dark" ALIGN="center">Mega Kills</td>
    <td CLASS="grey" ALIGN="center">$multi3</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Ultra Kills</td>
    <td CLASS="grey" ALIGN="center">$multi4</td>
    <td CLASS="dark" ALIGN="center">Monster Kills</td>
    <td CLASS="grey" ALIGN="center">$multi5</td>
    <td CLASS="dark" ALIGN="center">Ludicrous Kills</td>
    <td CLASS="grey" ALIGN="center">$multi6</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center">Holy Shit Kills</td>
    <td CLASS="grey" ALIGN="center">$multi7</td>
    <td CLASS="dark" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
    <td CLASS="dark" ALIGN="center">&nbsp;</td>
    <td CLASS="grey" ALIGN="center">&nbsp;</td>
  </tr>
</table>

EOF;

//=============================================================================
//========== Kills Match Up ===================================================
//=============================================================================

$tcols = $gm_numplayers + 2;
$twidth = ($gm_numplayers * 22) + 225;
$blankspan = 2;
if ($teams) {
    $tcols++;

    $twidth += 20;

    $blankspan = 3;
}
$km_name = [];

for ($r = 1; $r <= $gm_numplayers; $r++) {
    $i = $ranks[$r];

    $name = $gplayer[$i]['gp_name'];

    $km_name[$i] = '';

    for ($i2 = 0, $i2Max = mb_strlen($name); $i2 < $i2Max; $i2++) {
        $km_name[$i] .= mb_substr($name, $i2, 1) . '<br>';
    }
}

// Read Individual Kill Log
$result = sqlqueryn("SELECT * FROM $ut_gkills WHERE gk_game='$gamenum'");
if (!$result) {
    echo "Error reading gkills data.<br>\n";

    exit;
}
for ($r = 1; $r <= $gm_numplayers; $r++) {
    $i = $ranks[$r];

    for ($r2 = 1; $r2 <= $gm_numplayers; $r2++) {
        $i2 = $ranks[$r2];

        $killmatch[$i][$i2] = 0;
    }
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    while (list($key, $val) = each($row)) {
        ${$key} = $val;
    }

    if ($gk_killer >= 0) {
        $killmatch[$gk_killer][$gk_victim]++;
    } else {
        $killmatch[$gk_victim][$gk_victim]++;
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="$twidth">
  <tr>
    <td CLASS="heading" ALIGN="center" COLSPAN="$tcols">Kills Match Up</td>
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center" COLSPAN="$blankspan" ROWSPAN="$blankspan">&nbsp;</td>
    <td CLASS="dark" ALIGN="center" COLSPAN="$gm_numplayers">Victim</td>
  </tr>
  <tr>

EOF;

if ($teams) { // Team games
    // Display Victim Names

    for ($t = 1; $t >= 0; $t--) {
        for ($r = 1; $r <= $gm_numplayers; $r++) {
            $i = $ranks[$r];

            if ($gplayer[$i]['gp_team'] == $t) {
                if ($gplayer[$i]['gp_bot']) {
                    $nameclass = 'darkbot';
                } else {
                    $nameclass = 'darkhuman';
                }

                $gpnum = $gplayer[$i]['gp_num'];

                echo "    <td CLASS=\"$nameclass\" ALIGN=\"center\"><a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$gpnum\">$km_name[$i]</a></td>\n";
            }
        }
    }

    // Display Victim Team Colors

    echo '  </tr>
  <tr>
';

    for ($t = 1; $t >= 0; $t--) {
        for ($r = 1; $r <= $gm_numplayers; $r++) {
            $i = $ranks[$r];

            if ($gplayer[$i]['gp_team'] == $t) {
                if (1 == $t) {
                    $tclass = 'blueteam';
                } else {
                    $tclass = 'redteam';
                }

                echo "    <td CLASS=\"$tclass\" ALIGN=\"center\" WIDTH=\"20\">&nbsp;</td>\n";
            }
        }
    }

    echo <<<EOF
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center" ROWSPAN="$gm_numplayers" WIDTH="20">K<br>i<br>l<br>l<br>e<br>r</td>

EOF;

    // Display Killer Names / Team Color / Kills Per Victim

    for ($t = 1; $t >= 0; $t--) {
        for ($r = 1; $r <= $gm_numplayers; $r++) {
            $i = $ranks[$r];

            if ($gplayer[$i]['gp_team'] == $t) {
                $name = $gplayer[$i]['gp_name'];

                if ($gplayer[$i]['gp_bot']) {
                    $nameclass = 'darkbot';
                } else {
                    $nameclass = 'darkhuman';
                }

                $gpnum = $gplayer[$i]['gp_num'];

                echo "    <td CLASS=\"$nameclass\" ALIGN=\"center\"><a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$gpnum\">$name</a></td>\n";

                if (1 == $t) {
                    $tclass = 'blueteam';
                } else {
                    $tclass = 'redteam';
                }

                echo "    <td CLASS=\"$tclass\" ALIGN=\"center\" WIDTH=\"20\">&nbsp;</td>\n";

                for ($t2 = 1; $t2 >= 0; $t2--) {
                    for ($r2 = 1; $r2 <= $gm_numplayers; $r2++) {
                        $i2 = $ranks[$r2];

                        if ($gplayer[$i2]['gp_team'] == $t2) {
                            if ($i == $i2) {
                                $cbox = 'darkgrey';
                            } else {
                                $cbox = 'grey';
                            }

                            $km = $killmatch[$i][$i2];

                            if (0 == $km) {
                                $km = '&nbsp;';
                            }

                            echo "    <td CLASS=\"$cbox\" ALIGN=\"center\" WIDTH=\"20\">$km</td>\n";
                        }
                    }
                }

                echo "  </tr>\n";
            }
        }
    }
} else { // Non Team Games
    for ($r = 1; $r <= $gm_numplayers; $r++) {
        $i = $ranks[$r];

        if ($gplayer[$i]['gp_bot']) {
            $nameclass = 'darkbot';
        } else {
            $nameclass = 'darkhuman';
        }

        $gpnum = $gplayer[$i]['gp_num'];

        echo "    <td CLASS=\"$nameclass\" ALIGN=\"center\"><a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$gpnum\">$km_name[$i]</a></td>\n";
    }

    echo <<<EOF
  </tr>
  <tr>
    <td CLASS="dark" ALIGN="center" ROWSPAN="$gm_numplayers" WIDTH="20">K<br>i<br>l<br>l<br>e<br>r</td>

EOF;

    for ($r = 1; $r <= $gm_numplayers; $r++) {
        $i = $ranks[$r];

        $name = $gplayer[$i]['gp_name'];

        if ($gplayer[$i]['gp_bot']) {
            $nameclass = 'darkbot';
        } else {
            $nameclass = 'darkhuman';
        }

        $gpnum = $gplayer[$i]['gp_num'];

        echo "
    <td CLASS=\"$nameclass\" ALIGN=\"center\"><a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$gpnum\">$name</a></td>
";

        for ($r2 = 1; $r2 <= $gm_numplayers; $r2++) {
            $i2 = $ranks[$r2];

            if ($i == $i2) {
                $cbox = 'darkgrey';
            } else {
                $cbox = 'grey';
            }

            $km = $killmatch[$i][$i2];

            if (0 == $km) {
                $km = '&nbsp;';
            }

            echo "    <td CLASS=\"$cbox\" ALIGN=\"center\" WIDTH=\"20\">$km</td>\n";
        }

        echo '
  </tr>
';
    }
}
echo "</table>\n";

//=============================================================================
//========== Weapon/Suicide Specific Information ==============================
//=============================================================================

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="550">
  <tr>
    <td CLASS="heading" COLSPAN="7" ALIGN="center">Weapon/Suicide Specific Information</td>
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
$numweapons = 0;
// Load Weapon Kills for current game
$result = sqlqueryn("SELECT gk_killer,gk_victim,gk_kweapon,gk_vweapon FROM $ut_gkills WHERE gk_game='$gamenum'");
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $killer = $row['gk_killer'];

    $victim = $row['gk_victim'];

    $weap = $row['gk_kweapon'];

    $hweap = $row['gk_vweapon'];

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
        $wskills[3][$weapon]++;
    } // Event Suicide

    elseif ($killer == $victim) {
        $wskills[3][$weapon]++;
    } // Suicide

    else {
        if ($secondary) {
            $wskills[1][$weapon]++;
        } // Secondary Kill

        else {
            $wskills[0][$weapon]++;
        } // Primary Kill
        $wskills[2][$held]++;   // In-hand
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
        $wskills[4],
        SORT_ASC,
        SORT_NUMERIC,
        $wskills[3],
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

        if (($kills || $skills || $deaths || $suicides) && strcmp($weapon, 'None')) {
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
//========== Killing Sprees By Type ===========================================
//=============================================================================

$result = sqlqueryn("SELECT * FROM $ut_gevents WHERE ge_event='1' && ge_game='$gamenum' ORDER BY ge_time");
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

        if ($ge_plr >= 0 && isset($gplayer[$ge_plr]) && '' != $gplayer[$ge_plr]['gp_name']) {
            $name = $gplayer[$ge_plr]['gp_name'];

            $bot = $gplayer[$ge_plr]['gp_bot'];

            if ($bot) {
                $nameclass = 'darkbot';
            } else {
                $nameclass = 'darkhuman';
            }
        } else {
            $name = '';

            $bot = 0;
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
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="680">
  <tr>
    <td CLASS="heading" COLSPAN="6" ALIGN="center">Killing Sprees By Type</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Player</td>
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
    <td CLASS="$nameclass" ALIGN="center">$name</td>
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

$presult = sqlqueryn("SELECT it_num,it_desc FROM $ut_items");
if (!$presult) {
    echo "Error loading item pickup descriptions.<br>\n";

    exit;
}
$numitems = 0;
while (false !== ($prow = $GLOBALS['xoopsDB']->fetchBoth($presult))) {
    $itemnum = $prow['it_num'];

    $itemdesc = $prow['it_desc'];

    $result = sqlqueryn("SELECT gi_pickups FROM $ut_gitems WHERE gi_game='$gamenum' && gi_item=$itemnum");

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
  <td CLASS="dark" ALIGN="center" COLSPAN="6">There Were No Pickups Logged</td>

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
    <td CLASS="smheading" ALIGN="center" WIDTH="200">Player</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="100">Status</td>
  </tr>

EOF;

$result = sqlqueryn("SELECT ge_plr,ge_event,ge_time,ge_quant,ge_reason FROM $ut_gevents WHERE ge_game='$gamenum' && ge_event BETWEEN '2' AND '4' ORDER BY ge_time");
if (!$result) {
    echo "Error loading connection events.<br>\n";

    exit;
}
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $plr = $row['ge_plr'];

    if ($plr >= 0 && isset($gplayer[$plr]) && '' != $gplayer[$plr]['gp_name']) {
        $name = $gplayer[$plr]['gp_name'];

        $bot = $gplayer[$plr]['gp_bot'];

        if ($bot) {
            $nameclass = 'darkbot';
        } else {
            $nameclass = 'darkhuman';
        }
    } else {
        $name = '';

        $bot = 0;
    }

    $time = sprintf('%0.1f', $row['ge_time'] / 60);

    $quant = $row['ge_quant'];

    switch ($row['ge_event']) {
        case 2:
            switch ($row['ge_reason']) {
                case 0:
                    if ($gametval < 2 || $gametval > 4) {
                        $reason = 'Connected';
                    } else {
                        $reason = '';
                    }
                    $rclass = 'grey';
                    break;
                case 1:
                    $reason = 'Disconnected';
                    $rclass = 'warn';
                    break;
                default:
                    $reson = 'Unknown';
                    $rclass = 'grey';
            }
            $player = "<a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$plr\">$name</a>";
            break;
        case 3:
            switch ($row['ge_reason']) {
                case 0:
                    $reason = 'Game Start';
                    break;
                case 1:
                    $reason = 'Game Ended';
                    break;
                default:
                    $reason = 'Unknown';
            }
            $player = '';
            $rclass = 'gselog';
            break;
        case 4:
            if ($quant) {
                $reason = 'Blue Team';
            } else {
                $reason = 'Red Team';
            }
            $player = "<a CLASS=\"$nameclass\" HREF=\"gameplayer.php?game=$gamenum&player=$plr\">$name</a>";
            $rclass = 'tclog';
            break;
    }

    if ('' != $reason) {
        echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$time</td>
    <td CLASS="$nameclass" ALIGN="center">$player</td>
    <td CLASS="$rclass" ALIGN="center">$reason</td>
  </tr>

EOF;
    }
}
echo "</table>\n";

//=============================================================================
//========== Chat Log Link ====================================================
//=============================================================================

$result = sqlqueryn("SELECT gc_time FROM $ut_gchat WHERE gc_game='$gamenum' ORDER BY gc_time");
if (!$result) {
    echo "Error accessing chat log.<br>\n";

    exit;
}
$numchat = $GLOBALS['xoopsDB']->getRowsNum($result);
if (1 == $numchat) {
    $plural = '';
} else {
    $plural = 's';
}

echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="400">
  <tr>
    <td CLASS="chatlink" COLSPAN="3" ALIGN="center">Game <a CLASS="chatlink" HREF="gamechat.php?game=$gamenum">Chat Log</a> contains $numchat message{$plural}</td>
  </tr>
</table>

EOF;

$GLOBALS['xoopsDB']->close($link);

echo <<<EOF
</center>

</td></tr></table>

EOF;

require XOOPS_ROOT_PATH . '/footer.php';
