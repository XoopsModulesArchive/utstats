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
require '../../mainfile.php';
require '../../header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
require 'config.inc.php';
require 'logsql.php';

$magic = get_magic_quotes_gpc();

$statview = '';
$gamenum = -1;
$plr = -1;
$page = 1;
$rank = '';
if ($magic) {
    if (isset($_GET['stats'])) {
        $statview = stripslashes($_GET['stats']);
    }

    if (isset($_GET['game'])) {
        $gamenum = stripslashes($_GET['game']);
    }

    if (isset($_GET['player'])) {
        $plr = stripslashes($_GET['player']);
    }

    if (isset($_GET['page'])) {
        $page = stripslashes($_GET['page']);
    }

    if (isset($_GET['rank'])) {
        $rank = stripslashes($_GET['rank']);
    }
} else {
    if (isset($_GET['stats'])) {
        $statview = $_GET['stats'];
    }

    if (isset($_GET['game'])) {
        $gamenum = $_GET['game'];
    }

    if (isset($_GET['player'])) {
        $plr = $_GET['player'];
    }

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    }

    if (isset($_GET['rank'])) {
        $rank = $_GET['rank'];
    }
}

$twidth = 720;
$twidthm = $twidth + 160;

if ($altlayout) {
    $stylefile = "style{$altlayout}.css";

    $logofile = "statsdblogo{$altlayout}.png";

    $utlogofile = "ut2k3logo{$altlayout}.png";
} else {
    $stylefile = 'style.css';

    $logofile = 'statsdblogo.png';

    $utlogofile = 'ut2k3logo.png';
}

echo <<<EOF
<link REL="STYLESHEET" HREF="resource/{$stylefile}">
<table CELLPADDING="0" CELLSPACING="0 BORDER="0" ><tr>

<td WIDTH="$twidth" VALIGN="top" ALIGN="center">

<table CELLPADDING="0" CELLSPACING="0" BORDER="0" WIDTH="60%" ALIGN="center">
  <tr>
    <td ALIGN=center>
      <a HREF="http://ut2003stats.sourceforge.net"><img SRC="resource/{$logofile}" BORDER="0" ALT="UT2003 StatsDB Logo"></a>
    </td>
  </tr>
</table>
     <p>&nbsp;</p>
EOF;
