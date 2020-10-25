<?php

require XOOPS_ROOT_PATH . '/modules/utstats/config.inc.php';
require XOOPS_ROOT_PATH . '/modules/utstats/logsql.php';

$result = sqlquery("SELECT tl_kills, tl_games from $ut_totals");

while (false !== ($row = $GLOBALS['xoopsDB']->fetchRow($result))) {
    $games = printf('Games  : %s<br>', $row[1]);

    $frags = printf('Frags  : %s<br>', $row[0]);

    echo $games;

    echo $frags;
}
?>

1
2
3
