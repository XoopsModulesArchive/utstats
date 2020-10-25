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

function sqlquery($query)
{
    global $SQLhost, $SQLdb, $SQLus, $SQLpw;

    $link = @mysql_connect((string)$SQLhost, (string)$SQLus, (string)$SQLpw);

    if (!$link) {
        echo "Database access error.\n";

        exit;
    }

    $result = mysql_db_query((string)$SQLdb, (string)$query);

    $GLOBALS['xoopsDB']->close($link);

    return $result;
}

function sqlquery_connect()
{
    global $SQLhost, $SQLdb, $SQLus, $SQLpw;

    $link = @mysql_connect((string)$SQLhost, (string)$SQLus, (string)$SQLpw);

    if (!$link) {
        echo "Database access error.\n";

        exit;
    }

    $result = mysqli_select_db($GLOBALS['xoopsDB']->conn, (string)$SQLdb);

    if (!$result) {
        echo "Error selecting database '$SQLdb'.\n";

        exit;
    }

    return $link;
}

function sqlqueryn($query)
{
    global $SQLhost, $SQLdb, $SQLus, $SQLpw, $uselimit;

    if (!$uselimit) { // Remove LIMIT 1 from UPDATE queries
        if (!strcmp(mb_substr($query, 0, 6), 'UPDATE') && !strcmp(mb_substr($query, -7), 'LIMIT 1')) {
            $query = mb_substr($query, 0, -7);
        }
    }

    $result = $GLOBALS['xoopsDB']->queryF((string)$query);

    return $result;
}
