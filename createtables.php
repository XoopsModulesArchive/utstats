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

$password = '';
$nohtml = 0;

$magic = get_magic_quotes_gpc();
if ($magic) {
    if (isset($_GET['pass'])) {
        $password = stripslashes($_GET['pass']);
    }
} else {
    if (isset($_GET['pass'])) {
        $password = $_GET['pass'];
    }
}

$argc = $_SERVER['argc'];
$argv = $_SERVER['argv'];
for ($i = 1; $i < $argc; $i++) {
    $pos = mb_strpos($argv[$i], '=');

    if (false !== $pos && mb_strlen($argv[$i]) > $pos) {
        $param = mb_strtoupper(mb_substr($argv[$i], 0, $pos));

        $val = mb_substr($argv[$i], $pos + 1);

        switch ($param) {
            case 'PASS':
                $password = $val;
                break;
            case 'NOHTML':
                $nohtml = $val;
                break;
            default:
                echo "Invalid parameter.\n";
                die();
        }
    }
}

if ($nohtml) {
    $break = '';

    $bold = '';

    $ebold = '';
} else {
    $break = '<br>';

    $bold = '<b>';

    $ebold = '</b>';
}

if ('' == $password || '' == $AdminPass || $password != $AdminPass) {
    echo "Access error.{$break}\n";

    exit;
}

$sqlfile[0] = 'players.sql';
$sqlfile[1] = 'games.sql';
$sqlfile[2] = 'totals.sql';
$sqlfile[3] = 'gplayers.sql';
$sqlfile[4] = 'gkills.sql';
$sqlfile[5] = 'gscores.sql';
$sqlfile[6] = 'tkills.sql';
$sqlfile[7] = 'gevents.sql';
$sqlfile[8] = 'pwkills.sql';
$sqlfile[9] = 'type.sql';
$sqlfile[10] = 'weapons.sql';
$sqlfile[11] = 'items.sql';
$sqlfile[12] = 'pitems.sql';
$sqlfile[13] = 'gitems.sql';
$sqlfile[14] = 'gchat.sql';

echo "{$bold}Creating database tables:{$ebold}{$break}\n";
$link = sqlquery_connect();
$i = $errors = 0;
do {
    $fname = 'tables/' . $sqlfile[$i];

    echo "Reading '$sqlfile[$i]'....";

    if (file_exists($fname)) {
        $sqldata = file($fname);

        $done = 0;

        $qstring = '';

        while (!$done && $row = each($sqldata)) {
            $qstring .= $row[1];

            if (mb_strstr($qstring, ';')) {
                $qstring = rtrim($qstring, "\t\n\r\0;");

                $done = 1;
            }
        }

        $result = sqlqueryn($qstring);

        if ($result) {
            echo "Successful.{$break}\n";
        } else {
            echo "{$bold}Failed!{$ebold}{$break}\n";

            $errors++;
        }

        each($sqldata);

        while ($row = each($sqldata)) {
            $qstring = $row[1];

            $qstring = rtrim($qstring, "\t\n\r\0;");

            $result = sqlqueryn($qstring);

            if (!$result) {
                echo "{$bold}Error loading table data: $qstring{$ebold}{$break}\n";

                $errors++;
            }
        }
    } else {
        echo "{$bold}File not found!{$ebold}{$break}\n";
    }

    $i++;
} while (isset($sqlfile[$i]));
$GLOBALS['xoopsDB']->close($link);

if ($errors) {
    echo "{$bold}Errors!{$ebold}{$break}\n";
} else {
    echo "{$bold}Done!{$ebold}{$break}\n";
}
