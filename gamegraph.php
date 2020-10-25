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

require 'config.inc.php';
require 'logsql.php';
require 'xoopsmtables.php';

$magic = get_magic_quotes_gpc();
if ($magic) {
    $gamenum = stripslashes($_GET['game']);

    $type = stripslashes($_GET['type']);
} else {
    $gamenum = $_GET['game'];

    $type = $_GET['type'];
}

if (!$gamenum || !$type || (1 != $type && 2 != $type && 3 != $type)) {
    echo "Run from the main index program.<br>\n";

    exit;
}

//=============================================================================
//========== Configure Main Variables =========================================
//=============================================================================

$x = 550; // Graph image width
$y = 180; // Graph image height
$minx = 36; // Left margin
$maxy = 6; // Top margin
$maxx = $x - 10; // Right margin
$miny = $y - 32; // Bottom margin
$gwa = $maxx - $minx + 1; // Graph Width Area
$gha = $miny - $maxy + 1; // Graph Height Area
$font = 1; // Label font size
$legendfont = 3; // Legend font size
$minstep = 8; // Minimum graph steps

//=============================================================================
//========== Retreive Player Data =============================================
//=============================================================================

// Retreive game info
$result = sqlquery("SELECT * FROM $ut_games WHERE gm_num = '$gamenum' LIMIT 1");
$row = $GLOBALS['xoopsDB']->fetchBoth($result);
if (!$row) {
    exit;
}
while (list($key, $val) = each($row)) {
    ${$key} = $val;
}
$GLOBALS['xoopsDB']->freeRecordSet($result);
$start = strtotime($gm_start);
$matchdate = date('D, M d Y \a\t g:i:s A', $start);

if (2 == $type) {
    // Read tkills for each team for the game - set score and time

    $lowscore = $highscore = 0;

    $team[0] = $team[1] = 0;

    $link = sqlquery_connect();

    $lines = 2;

    for ($num = 1; $num <= 2; $num++) { // 1 = Blue (Team 1) / 2 = Red (0)
        $fg = 0;

        $tm = 2 - $num;

        $result = sqlqueryn("SELECT * FROM $ut_tkills WHERE tk_game = '$gamenum' && tk_team = '$tm' ORDER BY tk_time");

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
            $fg += (int)$row['tk_score'];

            $pscore[$num][] = $fg;

            $ptime[$num][] = $row['tk_time'] - $gm_starttime;

            if ($fg < $lowscore) {
                $lowscore = $fg;
            }

            if ($fg > $highscore) {
                $highscore = $fg;
            }
        }

        $team[$tm] = $fg;
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    $GLOBALS['xoopsDB']->close($link);

    // Set rank by team color

    $ranks[1] = 1;

    $ranks[2] = 2;
} elseif (3 == $type) {
    // Read score for each player for the game - set score and time

    $lowscore = $highscore = 0;

    $lines = $gm_numplayers;

    if ($lines > 8) {
        $lines = 8;
    }

    $link = sqlquery_connect();

    // Set rankings

    $maxplayer = 0;

    $i = 1;

    $result = sqlqueryn("SELECT * FROM $ut_gplayers WHERE gp_game = '$gamenum' ORDER BY gp_rank");

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
        $ranks[$i++] = $row['gp_num'];

        if ($row['gp_num'] > $maxplayer) {
            $maxplayer = $row['gp_num'];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    for ($i = 0; $i <= $maxplayer; $i++) {
        $pscorefg[$i] = 0;
    }

    $result = sqlqueryn("SELECT * FROM $ut_gscores WHERE gs_game = '$gamenum' ORDER BY gs_time");

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
        $num = $row['gs_player'];

        $time = $row['gs_time'];

        $score = $row['gs_score'];

        $pscorefg[$num] += $score;

        $pscore[$num][] = $pscorefg[$num];

        $ptime[$num][] = $time - $gm_starttime;

        if ($pscorefg[$num] < $lowscore) {
            $lowscore = $pscorefg[$num];
        }

        if ($pscorefg[$num] > $highscore) {
            $highscore = $pscorefg[$num];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    $GLOBALS['xoopsDB']->close($link);
} else {
    // Read gkills for each player for the game - set frag number and time

    $lowscore = $highscore = 0;

    $lines = $gm_numplayers;

    if ($lines > 8) {
        $lines = 8;
    }

    $link = sqlquery_connect();

    // Set rankings

    $maxplayer = 0;

    $i = 1;

    $result = sqlqueryn("SELECT * FROM $ut_gplayers WHERE gp_game = '$gamenum' ORDER BY gp_rank");

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
        $ranks[$i++] = $row['gp_num'];

        if ($row['gp_num'] > $maxplayer) {
            $maxplayer = $row['gp_num'];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    for ($i = 0; $i <= $maxplayer; $i++) {
        $pscorefg[$i] = 0;
    }

    $result = sqlqueryn("SELECT * FROM $ut_gkills WHERE gk_game = '$gamenum' ORDER BY gk_time");

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
        if ($row['gk_killer'] == $row['gk_victim'] || $row['gk_killer'] < 0) { // Suicide
            $num = $row['gk_victim'];

            $pscorefg[$num]--;

            $pscore[$num][] = $pscorefg[$num];

            $ptime[$num][] = $row['gk_time'] - $gm_starttime;
        } else {
            $num = $row['gk_killer'];

            $pscorefg[$num]++;

            $pscore[$num][] = $pscorefg[$num];

            $ptime[$num][] = $row['gk_time'] - $gm_starttime;
        }

        if ($pscorefg[$num] < $lowscore) {
            $lowscore = $pscorefg[$num];
        }

        if ($pscorefg[$num] > $highscore) {
            $highscore = $pscorefg[$num];
        }
    }

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    $GLOBALS['xoopsDB']->close($link);
}

$fsr = $highscore - $lowscore;
if ($fsr <= 0) {
    $fsr = 1;
}
$ftr = $gm_length;

//=============================================================================
//========== Setup Grid Increments ============================================
//=============================================================================

// Time Steps = 1, 2.5, 5, 10, 20, 50, 100, 500, 1000, 5000, 10000, 50000, 100000
// Max indices = 9
if ($gm_length <= 8 * 60) {
    $step = 1;
} elseif ($gm_length <= 20 * 60) {
    $step = 2.5;
} elseif ($gm_length <= 40 * 60) {
    $step = 5;
} elseif ($gm_length <= 80 * 60) {
    $step = 10;
} elseif ($gm_length <= 160 * 60) {
    $step = 20;
} elseif ($gm_length <= 400 * 60) {
    $step = 50;
} elseif ($gm_length <= 800 * 60) {
    $step = 100;
} elseif ($gm_length <= 4000 * 60) {
    $step = 500;
} elseif ($gm_length <= 8000 * 60) {
    $step = 1000;
} elseif ($gm_length <= 40000 * 60) {
    $step = 5000;
} elseif ($gm_length <= 80000 * 60) {
    $step = 10000;
} elseif ($gm_length <= 400000 * 60) {
    $step = 50000;
} elseif ($gm_length <= 800000 * 60) {
    $step = 100000;
}
for ($i = 0, $n = 1; $i <= (int)($gm_length / 60); $i += $step, $n++) {
    $xindex[$n] = $i;
}
$gridx = (int)(($gm_length / 60) / $step) + 1;

// Score Steps = 1, 2, 5, 10, 20, 50, 100, 500, 1000, 5000, 10000
// Max indices = 5
$range = $fsr;
if ($range <= 4) {
    $step = 1;
} elseif ($range <= 8) {
    $step = 2;
} elseif ($range <= 20) {
    $step = 5;
} elseif ($range <= 40) {
    $step = 10;
} elseif ($range <= 80) {
    $step = 20;
} elseif ($range <= 200) {
    $step = 50;
} elseif ($range <= 400) {
    $step = 100;
} elseif ($range <= 2000) {
    $step = 500;
} elseif ($range <= 4000) {
    $step = 1000;
} elseif ($range <= 20000) {
    $step = 5000;
} elseif ($range <= 40000) {
    $step = 10000;
}
if ($lowscore < 0) {
    $base = ceil($lowscore / $step) * $step;

    if (0 == $base) {
        $base = 0;
    }
} else {
    $base = 0;
}
for ($i = $base, $n = 1; $i <= $range; $i += $step) {
    $yindex[$n] = $i;

    $n++;
}
$gridy = floor($range / $step) + 1;

//=============================================================================
//========== Initialize Image =================================================
//=============================================================================

if (imagetypes() & IMG_PNG) {
    header('Content-type: image/png');
} elseif (imagetypes() & IMG_GIF) {
    header('Content-type: image/gif');
} else {
    header('Content-type: image/jpeg');
}

$im = imagecreatefrompng('resource/graphimg.png');

//=============================================================================
//========== Set Colors =======================================================
//=============================================================================

$white = imagecolorallocate($im, 255, 255, 255);  // #FFFFFF
$black = imagecolorallocate($im, 0, 0, 0);        // #000000
$back = imagecolorallocate($im, 181, 181, 181);   // #B7B7B7
$violet = imagecolorallocate($im, 128, 128, 192); // #8080C0
$red = imagecolorallocate($im, 255, 0, 0);        // #FF0000
$green = imagecolorallocate($im, 0, 220, 0);      // #00DC00
$blue = imagecolorallocate($im, 0, 0, 255);       // #0000FF
$yellow = imagecolorallocate($im, 245, 245, 0);   // #F5F500
$orange = imagecolorallocate($im, 236, 142, 9);   // #EC8E09
$cyan = imagecolorallocate($im, 46, 214, 193);    // #2ED6C1
$plum = imagecolorallocate($im, 187, 81, 92);     // #BB515C

$dashed = [$black, $black, $black, $black, $white, $white, $white, $white];
$dashedred = [$red, $red, $red, $red, $red, $red, $back, $back, $back, $back]; // 3/2
$dashedyellow = [$yellow, $yellow, $yellow, $yellow, $yellow, $yellow, $back, $back, $back, $back]; // 3/2
$dashedwhite = [$white, $white, $white, $white, $white, $white, $back, $back, $back, $back]; // 3/2

$color[1] = $blue;
$color[2] = $red; // dashedred
$color[3] = $green;
$color[4] = $yellow; // dashedyellow
$color[5] = $orange;
$color[6] = $white; // dashedwhite
$color[7] = $cyan;
$color[8] = $plum;

//=============================================================================
//========== Draw Graph Area Border ===========================================
//=============================================================================

imageline($im, $minx, $miny, $maxx, $miny, $violet);
imageline($im, $maxx, $miny, $maxx, $maxy, $violet);
imageline($im, $maxx, $maxy, $minx, $maxy, $violet);
imageline($im, $minx, $maxy, $minx, $miny, $violet);

//=============================================================================
//========== Create y-Axis Grid & Labels ======================================
//=============================================================================

for ($i = 1; $i <= $gridy; $i++) {
    $vy = $miny - round(($yindex[$i] - $lowscore) * ($gha / $fsr));

    $strposx = ($minx - mb_strlen($yindex[$i])) - (mb_strlen($yindex[$i]) * 5) - 3;

    $strposy = $vy - ($font * 4);

    if ($vy != $miny) {
        imageline($im, $minx, $vy, $maxx, $vy, $violet);
    }

    imagefilledrectangle($im, $minx - 1, $vy - 1, $minx + 1, $vy + 1, $black);

    imagestring($im, $font, $strposx, $strposy, $yindex[$i], $black);
}

//=============================================================================
//========== Create x-Axis Grid & Labels ======================================
//=============================================================================

for ($i = 1; $i <= $gridx; $i++) {
    $vx = round(($xindex[$i] * 60) * ($gwa / $ftr)) + $minx;

    $strposx = $vx - mb_strlen($xindex[$i]);

    if ($vx != $minx) {
        imageline($im, $vx, $miny, $vx, $maxy, $violet);
    }

    imagefilledrectangle($im, $vx - 1, $miny - 1, $vx + 1, $miny + 1, $black);

    imagestring($im, $font, $strposx, $miny + 5, $xindex[$i], $black);
}

//=============================================================================
//========== Legend ===========================================================
//=============================================================================

// y-Axis Legend
if (1 == $type) {
    $ystring = 'FRAGS';
} else {
    $ystring = 'SCORE';
}
$strposx = 2;
$strposy = (int)(($miny - $maxy) / 2) + (mb_strlen($ystring) * 5);
$offset = mb_strlen($ystring);
$offset2 = mb_strlen($ystring) * 7 + 3;
imageline($im, $strposx + 6, $miny, $strposx + 6, $strposy + $offset, $color[6]);
imagestringup($im, $legendfont, $strposx, $strposy, $ystring, $black);
imageline($im, $strposx + 6, $strposy - $offset2, $strposx + 6, $maxy, $color[6]);

// x-Axis Legend
$xstring = 'TIME (min)';
$strposx = (int)(($maxx - $minx) / 2) - mb_strlen($xstring);
$strposy = $y - 16;
$offset = (int)(mb_strlen($xstring) / 2);
$offset2 = mb_strlen($xstring) * 7 + 2;
imageline($im, $minx, $strposy + 3, $strposx - $offset, $strposy + 3, $color[6]);
imagestring($im, $legendfont, $strposx, $strposy - 3, $xstring, $black);
imageline($im, $strposx + $offset2, $strposy + 3, $maxx, $strposy + 3, $color[6]);

//=============================================================================
//========== Plot Lines =======================================================
//=============================================================================

// x point = (round) (time * (gwa / ftr))
// y point = (round) (score * (gha / fsr))
for ($r = 1; $r <= $lines; $r++) {
    $i = 0;

    $fromx = $minx;

    $fromy = $miny - round((0 - $lowscore) * ($gha / $fsr));

    $pointx = $pointy = 0;

    $num = $ranks[$r];

    while (isset($ptime[$num][$i])) {
        $pointx = round($ptime[$num][$i] * ($gwa / $ftr)) + $minx;

        $pointy = $miny - round(($pscore[$num][$i] - $lowscore) * ($gha / $fsr));

        if ($pointx > ($fromx + $minstep)) {
            imageline($im, $fromx, $fromy, $pointx - $minstep, $fromy, $color[$r]);

            imageline($im, $fromx, $fromy + 1, $pointx - $minstep, $fromy + 1, $color[$r]); // Thick line

            $fromx = $pointx - $minstep;
        }

        imageline($im, $fromx, $fromy, $pointx, $pointy, $color[$r]);

        imageline($im, $fromx, $fromy + 1, $pointx, $pointy + 1, $color[$r]); // Thick line

        $fromx = $pointx;

        $fromy = $pointy;

        $i++;
    }

    if ($pointx < $maxx) {
        imageline($im, $fromx, $fromy, $maxx, $fromy, $color[$r]);

        imageline($im, $fromx, $fromy + 1, $maxx, $fromy + 1, $color[$r]); // Thick line
    }
}

//=============================================================================
//========== Generate Image ===================================================
//=============================================================================

if (imagetypes() & IMG_PNG) {
    imagepng($im);
} elseif (imagetypes() & IMG_GIF) {
    imagegif($im);
} else {
    imagejpeg($im);
}
imagedestroy($im);
