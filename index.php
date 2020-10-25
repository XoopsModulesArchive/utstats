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

if ('players' == $statview) {
    //=============================================================================

    //========== Player Stats =====================================================

    //=============================================================================

    $playerspage = 50;

    // Calculate Number of Pages

    $result = sqlquery("SELECT pnum FROM $ut_players");

    $numpages = (int)ceil($GLOBALS['xoopsDB']->getRowsNum($result) / $playerspage);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    if (!$page) {
        $page = 1;
    } elseif ($page < 1 || $page > $numpages) {
        $page = 1;
    }

    if ($numpages > 1) {
        echo "<div CLASS=\"pages\"><b>Page [$page/$numpages] Selection: ";

        $prev = $page - 1;

        $next = $page + 1;

        if ($rank) {
            $rankurl = "&rank=$rank";
        } else {
            $rankurl = '';
        }

        if (1 != $page) {
            echo "<a CLASS=\"pages\" HREF=\"index.php?stats=players{$rankurl}&page=1\">[First]</a> / <a CLASS=\"pages\" HREF=\"index.php?stats=players{$rankurl}&page=$prev\">[Previous]</a> / ";
        } else {
            echo '[First] / [Previous] / ';
        }

        if ($page < $numpages) {
            echo "<a CLASS=\"pages\" HREF=\"index.php?stats=players{$rankurl}&page=$next\">[Next]</a> / <a CLASS=\"pages\" HREF=\"index.php?stats=players{$rankurl}&page=$numpages\">[Last]</a>";
        } else {
            echo '[Next] / [Last]';
        }

        echo "</b></div>\n";
    }

    echo "<div CLASS=\"opnote\">*Select headings to change ranking by Name, Frags, Score, Kills, Deaths, or Suicides</div>\n";

    echo <<<EOF
<table CELLPADDING="1" CELLSPACING="2 BORDER="0" WIDTH="670" CLASS="box">
  <tr>
    <td CLASS="heading" COLSPAN="12" ALIGN="center">Unreal Tournament 2003 Player Stats</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Rank</td>
    <td CLASS="smheading" ALIGN="center"><a CLASS="smheading" HREF="index.php?stats=players&rank=name">Player</a></td>
    <td CLASS="smheading" ALIGN="center"><a CLASS="smheading" HREF="index.php?stats=players&rank=frags">Frags</a></td>
    <td CLASS="smheading" ALIGN="center"><a CLASS="smheading" HREF="index.php?stats=players&rank=score">Score</a></td>
    <td CLASS="smheading" ALIGN="center"><a CLASS="smheading" HREF="index.php?stats=players&rank=kills">Kills</td>
    <td CLASS="smheading" ALIGN="center"><a CLASS="smheading" HREF="index.php?stats=players&rank=deaths">Deaths</td>
    <td CLASS="smheading" ALIGN="center"><a CLASS="smheading" HREF="index.php?stats=players&rank=suicides">Suicides</td>
    <td CLASS="smheading" ALIGN="center">Eff.</td>
    <td CLASS="smheading" ALIGN="center">FPH</td>
    <td CLASS="smheading" ALIGN="center">SPH</td>
    <td CLASS="smheading" ALIGN="center">Games</td>
    <td CLASS="smheading" ALIGN="center">Hours</td>
  </tr>

EOF;

    $start = ($page * $playerspage) - $playerspage;

    switch ($rank) {
        case 'name':
            $result = sqlquery("SELECT * FROM $ut_players ORDER BY plr_bot ASC, plr_name ASC, plr_frags DESC, plr_deaths DESC, plr_suicides ASC LIMIT $start,$playerspage");
            break;
        case 'score':
            $result = sqlquery("SELECT * FROM $ut_players ORDER BY plr_bot ASC, plr_score DESC, plr_frags DESC, plr_deaths DESC, plr_suicides ASC LIMIT $start,$playerspage");
            break;
        case 'kills':
            $result = sqlquery("SELECT * FROM $ut_players ORDER BY plr_bot ASC, plr_kills DESC, plr_frags DESC, plr_deaths DESC, plr_suicides ASC LIMIT $start,$playerspage");
            break;
        case 'deaths':
            $result = sqlquery("SELECT * FROM $ut_players ORDER BY plr_bot ASC, plr_deaths DESC, plr_frags DESC, plr_suicides ASC LIMIT $start,$playerspage");
            break;
        case 'suicides':
            $result = sqlquery("SELECT * FROM $ut_players ORDER BY plr_bot ASC, plr_suicides DESC, plr_frags DESC, plr_deaths DESC LIMIT $start,$playerspage");
            break;
        default:
            $result = sqlquery("SELECT * FROM $ut_players ORDER BY plr_bot ASC, plr_frags DESC, plr_deaths DESC, plr_suicides ASC LIMIT $start,$playerspage");
    }

    if (!$result) {
        echo "Player database error.<br>\n";

        exit;
    }

    $rank = $start + 1;

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
        while (list($key, $val) = each($row)) {
            ${$key} = $val;
        }

        $time = $dm_time + $tdm_time + $dd_time + $ctf_time + $br_time + $other_time;

        if (0 == $plr_kills + $plr_deaths + $plr_suicides) {
            $eff = '0.0';
        } else {
            $eff = sprintf('%0.1f', ($plr_kills / ($plr_kills + $plr_deaths + $plr_suicides)) * 100.0);
        }

        if (0 == $time) {
            $fph = '0.0';

            $sph = '0.0';
        } else {
            $fph = sprintf('%0.1f', $plr_frags / ($time / 3600));

            $sph = sprintf('%0.1f', $plr_score / ($time / 3600));
        }

        $time = sprintf('%0.1f', $time / 3600);

        $games = $dm_games + $tdm_games + $dd_games + $ctf_games + $br_games + $other_games;

        if ($plr_bot) {
            $nameclass = 'darkbot';
        } else {
            $nameclass = 'darkhuman';
        }

        echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center">$rank</td>
    <td CLASS="dark" ALIGN="center"><a CLASS="$nameclass" HREF="playerstats.php?player=$pnum">$plr_name [$pnum]</a></td>
    <td CLASS="grey" ALIGN="center">$plr_frags</td>
    <td CLASS="grey" ALIGN="center">$plr_score</td>
    <td CLASS="grey" ALIGN="center">$plr_kills</td>
    <td CLASS="grey" ALIGN="center">$plr_deaths</td>
    <td CLASS="grey" ALIGN="center">$plr_suicides</td>
    <td CLASS="grey" ALIGN="center">$eff%</td>
    <td CLASS="grey" ALIGN="center">$fph</td>
    <td CLASS="grey" ALIGN="center">$sph</td>
    <td CLASS="grey" ALIGN="center">$games</td>
    <td CLASS="grey" ALIGN="center">$time</td>
  </tr>

EOF;

        $rank++;
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    echo "</table>\n";
} elseif ('games' == $statview) {
    //=============================================================================

    //========== Game Stats =======================================================

    //=============================================================================

    $gamespage = 50;

    // Calculate Number of Pages

    $result = sqlquery("SELECT gm_num FROM $ut_games");

    $numpages = (int)ceil($GLOBALS['xoopsDB']->getRowsNum($result) / $gamespage);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    if (!$page) {
        $page = 1;
    } elseif ($page < 1 || $page > $numpages) {
        $page = 1;
    }

    if ($numpages > 1) {
        echo "<div CLASS=\"pages\"><b>Page [$page/$numpages] Selection: ";

        $prev = $page - 1;

        $next = $page + 1;

        if (1 != $page) {
            echo "<a CLASS=\"pages\" HREF=\"index.php?stats=games&page=1\">[First]</a> / <a CLASS=\"pages\" HREF=\"index.php?stats=games&page=$prev\">[Previous]</a> / ";
        } else {
            echo '[First] / [Previous] / ';
        }

        if ($page < $numpages) {
            echo "<a CLASS=\"pages\" HREF=\"index.php?stats=games&page=$next\">[Next]</a> / <a CLASS=\"pages\" HREF=\"index.php?stats=games&page=$numpages\">[Last]</a>";
        } else {
            echo '[Next] / [Last]';
        }

        echo '</b></div>';
    }

    echo <<<EOF
<table CELLPADDING="1" CELLSPACING="2 BORDER="0" CLASS="box">
  <tr>
    <td CLASS="heading" COLSPAN="5" ALIGN="center">Unreal Tournament 2003 Game Stats</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center" WIDTH="220">Date</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="160">Match Type</td>
    <td CLASS="smheading" ALIGN="center">Map</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="50">Players</td>
    <td CLASS="smheading" ALIGN="center" WIDTH="40">Time</td>
  </tr>

EOF;

    // Load game types

    $numtypes = 0;

    $result = sqlquery("SELECT * FROM $ut_type");

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
        $gtype[$numtypes++] = $row;
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    // Load Game Stats

    $matches = 0;

    $start = ($page * $gamespage) - $gamespage;

    $result = sqlquery("SELECT * FROM $ut_games ORDER BY gm_start DESC LIMIT $start,$gamespage");

    if (!$result) {
        echo "Game database error.<br>\n";

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

        $matches++;

        echo <<<EOF
  <tr>
    <td CLASS="dark" ALIGN="center"><a CLASS="dark" HREF="gamestats.php?game=$gm_num">$matchdate</a></td>
    <td CLASS="grey" ALIGN="center">$gametype</td>
    <td CLASS="grey" ALIGN="center">$gm_map</td>
    <td CLASS="grey" ALIGN="center">$gm_numplayers</td>
    <td CLASS="grey" ALIGN="center">$length</td>
  </tr>

EOF;
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    echo "</table>\n";

    if (!$matches) {
        echo <<<EOF
<table CELLPADDING="1" CELLSPACING="2 BORDER="0" WIDTH="600">
  <tr>
    <td COLSPAN="5">&nbsp;</td>
  </tr>
  <tr>
    <td COLSPAN="5" ALIGN="center"><b>No games available.</b></td>
  </tr>
</table>

EOF;

        exit;
    }
} else {
    //=============================================================================

    //========== Main =============================================================

    //=============================================================================

    $result = sqlquery("SELECT * FROM $ut_totals LIMIT 1");

    if (!$result) {
        echo "Stats database error.<br>\n";

        exit;
    }

    $row = $GLOBALS['xoopsDB']->fetchBoth($result);

    while (list($key, $val) = each($row)) {
        ${$key} = $val;
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    $frags = $tl_kills - $tl_suicides;

    $time = sprintf('%0.1f', $tl_playertime / 3600.0);

    echo <<<EOF
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="540" CLASS="box">
  <tr>
    <td CLASS="heading" COLSPAN="9" ALIGN="center">Unreal Tournament 2003 Stats Database v1.12</td>
  </tr>
  <tr>
    <td CLASS="smheading" ALIGN="center">Total Frags</td>
    <td CLASS="smheading" ALIGN="center">Total Kills</td>
    <td CLASS="smheading" ALIGN="center">Total Deaths</td>
    <td CLASS="smheading" ALIGN="center">Total Suicides</td>
    <td CLASS="smheading" ALIGN="center">Total Team Kills</td>
    <td CLASS="smheading" ALIGN="center">Total Headshots</td>
    <td CLASS="smheading" ALIGN="center">Human Players</td>
    <td CLASS="smheading" ALIGN="center">Games Logged</td>
    <td CLASS="smheading" ALIGN="center">Player Hours</td>
  </tr>
  <tr>
    <td CLASS="grey" ALIGN="center">$frags</td>
    <td CLASS="grey" ALIGN="center">$tl_kills</td>
    <td CLASS="grey" ALIGN="center">$tl_deaths</td>
    <td CLASS="grey" ALIGN="center">$tl_suicides</td>
    <td CLASS="grey" ALIGN="center">$tl_teamkills</td>
    <td CLASS="grey" ALIGN="center">$tl_headshots</td>
    <td CLASS="grey" ALIGN="center">$tl_players</td>
    <td CLASS="grey" ALIGN="center">$tl_games</td>
    <td CLASS="grey" ALIGN="center">$time</td>
  </tr>
</table>

EOF;

    // Load Last Game

    $matches = 0;

    $result = sqlquery("SELECT * FROM $ut_games ORDER BY gm_start DESC LIMIT 1");

    if (!$result) {
        echo "Game database error (main).<br>\n";

        exit;
    }

    $row = $GLOBALS['xoopsDB']->fetchBoth($result);

    if ($row) {
        $map = $row['gm_map'];

        $start = strtotime($row['gm_start']);

        $matchdate = date('D, M d Y \a\t g:i:s A', $start);

        $link = "gamestats.php?game={$row['gm_num']}";

        echo <<<EOF
<br>
<table CELLPADDING="1" CELLSPACING="0" BORDER="0" WIDTH="350" CLASS="box">
  <tr>
    <td CLASS="lglheading" ALIGN="center"><b>Last Game Logged</b></td>
  </tr>
  <tr>
    <td CLASS="heading" ALIGN="center"><a CLASS="lglheading" HREF="$link"><b>$map</b></a></td>
  </tr>
  <tr>
    <td CLASS="heading" ALIGN="center"><a CLASS="lglheading" HREF="$link"><b>$matchdate</b></a></td>
  </tr>
</table>

EOF;
    }

    if ('' == $title_msg) {
        $title_msg = '&nbsp;';
    }

    echo <<<EOF
<table CELLPADDING="1" CELLSPACING="2" BORDER="0" WIDTH="600">
  <tr>
    <td COLSPAN="10" ALIGN="center"><b><p>$title_msg</p></b></td>
  </tr>
</table>

EOF;

    $i = 1;

    while (isset(${'query_server' . $i}) && isset(${'query_port' . $i})) {
        $query_server = ${'query_server' . $i};

        $query_port = ${'query_port' . $i};

        if ('' != $query_server) {
            require_once 'serverquery.php';

            if (GetStatus((string)$query_server, $query_port)) {
                DisplayStatus();

                DisplayPlayers();

                if ($query_spectators) {
                    DisplaySpectators();
                }
            }
        }

        $i++;
    }
}

echo <<<EOF

</td>
</tr>
</table>


EOF;

require XOOPS_ROOT_PATH . '/footer.php';
