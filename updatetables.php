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

    $ital = '';

    $eital = '';
} else {
    $break = '<br>';

    $bold = '<b>';

    $ebold = '</b>';

    $ital = '<i>';

    $eital = '</i>';
}

if ('' == $password || '' == $AdminPass || $password != $AdminPass) {
    echo "Access error.{$break}\n";

    exit;
}

echo "{$bold}Updating database tables:{$ebold}{$break}\n";
$link = sqlquery_connect();
$errors = 0;

//*****************************************************************************
//********** Version 1.03 *****************************************************
//*****************************************************************************

echo "{$ital}Checking version 1.03 updates.{$eital}{$break}\n";

//*****************************************************************************
//********** ut_players (103) *************************************************
//*****************************************************************************

$result = sqlqueryn("SELECT plr_score FROM $ut_players LIMIT 1");
if ($result) {
    echo "Table $ut_players (score) already updated.{$break}\n";
} else {
    $result = sqlqueryn("ALTER TABLE $ut_players ADD plr_score MEDIUMINT(9) DEFAULT '0' NOT NULL AFTER plr_frags");

    if ($result) {
        echo "Updated $ut_players (score).{$break}\n";

        $result = sqlqueryn("UPDATE $ut_players SET plr_score=tdm_score+dd_score+ctf_score+br_score+other_score");

        if ($result) {
            echo "Updated total score for all players.{$break}\n";
        } else {
            echo "{$bold}Failed updating player score totals!{$ebold}{$break}\n";

            $errors++;
        }
    } else {
        echo "{$bold}Failed updating $ut_players (score)!{$ebold}{$break}\n";

        $errors++;
    }
}

$result = sqlqueryn("SELECT plr_multi7 FROM $ut_players LIMIT 1");
if ($result) {
    echo "Table $ut_players (multi) already updated.{$break}\n";
} else {
    $result = sqlqueryn("ALTER TABLE $ut_players ADD plr_multi7 MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER plr_multi6");

    if ($result) {
        echo "Updated $ut_players (multi).{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_players (multi)!{$ebold}{$break}\n";

        $errors++;
    }
}

$result = sqlqueryn("SELECT plr_spree6 FROM $ut_players LIMIT 1");
if ($result) {
    echo "Table $ut_players (spree) already updated.{$break}\n";
} else {
    $result = sqlqueryn(
        "ALTER TABLE $ut_players ADD plr_spree6 MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER plr_spreek5,
                       ADD plr_spreet6 INT(10) UNSIGNED DEFAULT '0' NOT NULL AFTER plr_spree6,
                       ADD plr_spreek6 MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER plr_spreet6"
    );

    if ($result) {
        echo "Updated $ut_players (spree).{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_players (spree)!{$ebold}{$break}\n";

        $errors++;
    }
}

//*****************************************************************************
//********** ut_gplayers (103) ************************************************
//*****************************************************************************

$result = sqlqueryn("SELECT gp_multi7 FROM $ut_gplayers LIMIT 1");
if ($result) {
    echo "Table $ut_gplayers (multi) already updated.{$break}\n";
} else {
    $result = sqlqueryn("ALTER TABLE $ut_gplayers ADD gp_multi7 SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL AFTER gp_multi6");

    if ($result) {
        echo "Updated $ut_gplayers (multi).{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_gplayers (multi)!{$ebold}{$break}\n";

        $errors++;
    }
}

$result = sqlqueryn("SELECT gp_spree6 FROM $ut_gplayers LIMIT 1");
if ($result) {
    echo "Table $ut_gplayers (spree) already updated.{$break}\n";
} else {
    $result = sqlqueryn("ALTER TABLE $ut_gplayers ADD gp_spree6 SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL AFTER gp_spree5");

    if ($result) {
        echo "Updated $ut_gplayers (spree).{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_gplayers (spree)!{$ebold}{$break}\n";

        $errors++;
    }
}

//*****************************************************************************
//********** ut_totals (103) **************************************************
//*****************************************************************************

$result = sqlqueryn("SELECT tl_multi7 FROM $ut_totals LIMIT 1");
if ($result) {
    echo "Table $ut_totals (multi) already updated.{$break}\n";
} else {
    $result = sqlqueryn("ALTER TABLE $ut_totals ADD tl_multi7 MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_multi6");

    if ($result) {
        echo "Updated $ut_totals (multi).{$break}\n";
    } else {
        echo "{$bold}Failed $updating ut_totals (multi)!{$ebold}{$break}\n";

        $errors++;
    }
}

$result = sqlqueryn("SELECT tl_spree6 FROM $ut_totals LIMIT 1");
if ($result) {
    echo "Table $ut_totals (spree) already updated.{$break}\n";
} else {
    $result = sqlqueryn(
        "ALTER TABLE $ut_totals ADD tl_spree6 MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_spreek5,
                       ADD tl_spreet6 INT(10) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_spree6,
                       ADD tl_spreek6 MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_spreet6"
    );

    if ($result) {
        echo "Updated $ut_totals (spree).{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_totals (spree)!{$ebold}{$break}\n";

        $errors++;
    }
}

$result = sqlqueryn("SELECT tl_chmulti7 FROM $ut_totals LIMIT 1");
if ($result) {
    echo "Table $ut_totals (chmulti) already updated.{$break}\n";
} else {
    $result = sqlqueryn(
        "ALTER TABLE $ut_totals ADD tl_chmulti7 MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_chmulti6_tm,
                       ADD tl_chmulti7_plr MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_chmulti7,
                       ADD tl_chmulti7_gms MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_chmulti7_plr,
                       ADD tl_chmulti7_tm MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_chmulti7_gms"
    );

    if ($result) {
        echo "Updated $ut_totals (chmulti).{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_totals (chmulti)!{$ebold}{$break}\n";

        $errors++;
    }
}

$result = sqlqueryn("SELECT tl_chspree6 FROM $ut_totals LIMIT 1");
if ($result) {
    echo "Table $ut_totals (chspree) already updated.{$break}\n";
} else {
    $result = sqlqueryn(
        "ALTER TABLE $ut_totals ADD tl_chspree6 MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_chspree5_tm,
                       ADD tl_chspree6_plr MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_chspree6,
                       ADD tl_chspree6_gms MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_chspree6_plr,
                       ADD tl_chspree6_tm MEDIUMINT(8) UNSIGNED DEFAULT '0' NOT NULL AFTER tl_chspree6_gms"
    );

    if ($result) {
        echo "Updated $ut_totals (chspree).{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_totals (chspree)!{$ebold}{$break}\n";

        $errors++;
    }
}

//*****************************************************************************
//********** Version 1.1 ******************************************************
//*****************************************************************************

echo "{$ital}Checking version 1.1 updates.{$eital}{$break}\n";

//*****************************************************************************
//********** ut_players (1.1) *************************************************
//*****************************************************************************

$result = sqlqueryn("SELECT dm_score FROM $ut_players LIMIT 1");
if ($result) {
    echo "Table $ut_players (dm_score) already updated.{$break}\n";
} else {
    $result = sqlqueryn("ALTER TABLE $ut_players ADD dm_score MEDIUMINT(8) NOT NULL default '0' AFTER plr_lastid");

    if ($result) {
        echo "Updated $ut_players (dm_score).{$break}\n";

        $result = sqlqueryn("UPDATE $ut_players SET dm_score=dm_frags, plr_score=dm_frags+tdm_score+dd_score+ctf_score+br_score+other_score");

        if ($result) {
            echo "Updated DM score and total score for all players.{$break}\n";
        } else {
            echo "{$bold}Failed updating player score totals!{$ebold}{$break}\n";

            $errors++;
        }
    } else {
        echo "{$bold}Failed updating $ut_players (dm_score)!{$ebold}{$break}\n";

        $errors++;
    }
}

$result = sqlqueryn("SELECT plr_user FROM $ut_players LIMIT 1");
if ($result) {
    echo "Table $ut_players (plr_user, plr_id, plr_key) already updated.{$break}\n";
} else {
    $result = sqlqueryn("ALTER TABLE $ut_players DROP plr_lastid");

    if (!$result) {
        echo "{$bold}Failed removing plr_lastid column!{$ebold}{$break}\n";

        $errors++;
    }

    $result = sqlqueryn(
        "ALTER TABLE $ut_players ADD plr_user varchar(35) NOT NULL default '' AFTER plr_transgib,
                       ADD plr_id varchar(32) NOT NULL default '' AFTER plr_user,
                       ADD plr_key varchar(32) NOT NULL default '' AFTER plr_id"
    );

    if ($result) {
        echo "Updated $ut_players (plr_user, plr_id, plr_key).{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_players (dm_score)!{$ebold}{$break}\n";

        $errors++;
    }
}

//*****************************************************************************
//********** ut_type (1.1) ****************************************************
//*****************************************************************************

$result = sqlqueryn("SELECT tp_desc FROM $ut_type WHERE tp_type='8' LIMIT 1");
if ($result && $GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
    echo "Table $ut_type already updated.{$break}\n";
} else {
    // Mutant

    $result = sqlqueryn("SELECT tp_desc FROM $ut_type WHERE tp_desc='Mutant' LIMIT 1");

    if ($result && $GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        $result = sqlqueryn("UPDATE $ut_type SET tp_type='6' WHERE tp_desc='Mutant' LIMIT 1");
    } else {
        $result = sqlqueryn("INSERT INTO $ut_type SET tp_desc='Mutant',tp_type='6'");
    }

    if ($result) {
        echo "$Updated ut_type 'Mutant'.{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_type 'Mutant'!{$ebold}{$break}\n";

        $errors++;
    }

    // Invasion

    $result = sqlqueryn("SELECT tp_desc FROM $ut_type WHERE tp_desc='Invasion' LIMIT 1");

    if ($result && $GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        $result = sqlqueryn("UPDATE $ut_type SET tp_type='7' WHERE tp_desc='Invasion' LIMIT 1");
    } else {
        $result = sqlqueryn("INSERT INTO $ut_type SET tp_desc='Invasion',tp_type='7'");
    }

    if ($result) {
        echo "Updated $ut_type 'Invasion'.{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_type 'Invasion'!{$ebold}{$break}\n";

        $errors++;
    }

    // Last Man Standing

    $result = sqlqueryn("SELECT tp_desc FROM $ut_type WHERE tp_desc='Last Man Standing' LIMIT 1");

    if ($result && $GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        $result = sqlqueryn("UPDATE $ut_type SET tp_type='8' WHERE tp_desc='Last Man Standing' LIMIT 1");
    } else {
        $result = sqlqueryn("INSERT INTO $ut_type SET tp_desc='Last Man Standing',tp_type='8'");
    }

    if ($result) {
        echo "Updated $ut_type 'Last Man Standing'.{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_type 'Last Man Standing'!{$ebold}{$break}\n";

        $errors++;
    }

    // Log Mutant

    $result = sqlqueryn("SELECT tp_desc FROM $ut_type WHERE tp_desc='Log Mutant' LIMIT 1");

    if ($result && $GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        $result = sqlqueryn("UPDATE $ut_type SET tp_type='6' WHERE tp_desc='Log Mutant' LIMIT 1");
    } else {
        $result = sqlqueryn("INSERT INTO $ut_type SET tp_desc='Log Mutant',tp_type='6'");
    }

    if ($result) {
        echo "Updated $ut_type 'Log Mutant'.{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_type 'Log Mutant'!{$ebold}{$break}\n";

        $errors++;
    }

    // Log Invasion

    $result = sqlqueryn("SELECT tp_desc FROM $ut_type WHERE tp_desc='Log Invasion' LIMIT 1");

    if ($result && $GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        $result = sqlqueryn("UPDATE $ut_type SET tp_type='7' WHERE tp_desc='Log Invasion' LIMIT 1");
    } else {
        $result = sqlqueryn("INSERT INTO $ut_type SET tp_desc='Log Invasion',tp_type='7'");
    }

    if ($result) {
        echo "Updated $ut_type 'Log Invasion'.{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_type 'Log Invasion'!{$ebold}{$break}\n";

        $errors++;
    }

    // Last Man Standing

    $result = sqlqueryn("SELECT tp_desc FROM $ut_type WHERE tp_desc='Log Last Man Standing' LIMIT 1");

    if ($result && $GLOBALS['xoopsDB']->getRowsNum($result) > 0) {
        $result = sqlqueryn("UPDATE $ut_type SET tp_type='8' WHERE tp_desc='Log Last Man Standing' LIMIT 1");
    } else {
        $result = sqlqueryn("INSERT INTO $ut_type SET tp_desc='Log Last Man Standing',tp_type='8'");
    }

    if ($result) {
        echo "Updated $ut_type 'Log Last Man Standing'.{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_type 'Log Last Man Standing'!{$ebold}{$break}\n";

        $errors++;
    }
}

//*****************************************************************************
//********** ut_gscores (1.1) *************************************************
//*****************************************************************************

$result = sqlqueryn("SELECT gs_game FROM $ut_gscores LIMIT 1");
if ($result) {
    echo "Table $ut_gscores already added.{$break}\n";
} else {
    $result = sqlqueryn(
        "CREATE TABLE $ut_gscores (
                       gs_game int(10) unsigned NOT NULL default '0',
                       gs_player smallint(6) NOT NULL default '0',
                       gs_time mediumint(8) unsigned NOT NULL default '0',
                       gs_score smallint(6) NOT NULL default '0',
                       gs_team tinyint(4) NOT NULL default '0')"
    );

    if ($result) {
        echo "Added table $ut_gscores.{$break}\n";
    } else {
        echo "{$bold}Failed adding table $ut_gscores!{$ebold}{$break}\n";

        $errors++;
    }
}

//*****************************************************************************
//********** ut_gtscores (1.1) ************************************************
//*****************************************************************************
$result = sqlqueryn("SELECT gt_game FROM $ut_gtscores LIMIT 1");
if ($result) {
    $result = sqlqueryn("DROP TABLE $ut_gtscores");
}

/*
$result = sqlqueryn("SELECT gt_game FROM $ut_gtscores LIMIT 1");
if ($result)
  echo "Table $ut_gtscores already added.{$break}\n";
else {
  $result = sqlqueryn("CREATE TABLE $ut_gtscores (
                       gt_game int(10) unsigned NOT NULL default '0',
                       gt_player smallint(6) NOT NULL default '0',
                       gt_time mediumint(8) unsigned NOT NULL default '0',
                       gt_score smallint(6) NOT NULL default '0',
                       gt_team tinyint(4) NOT NULL default '0')");
  if ($result)
    echo "Added table $ut_gtscores.{$break}\n";
  else {
    echo "{$bold}Failed adding table $ut_gtscores!{$ebold}{$break}\n";
    $errors++;
  }
}
*/

//*****************************************************************************
//********** ut_player key (1.1) **********************************************
//*****************************************************************************

$result = sqlqueryn("DESCRIBE $ut_players");
if ($result) {
    // Check first field, third column - should be "PRI"

    $row = $GLOBALS['xoopsDB']->fetchBoth($result);

    $pnumkey = $row[3];

    if ('PRI' != $pnumkey) {
        $result = sqlqueryn("ALTER TABLE $ut_players DROP KEY plr_name");

        if ($result) {
            echo "Removed key plr_name from $ut_players.{$break}\n";
        } else {
            echo "Key plr_name already dropped from $ut_players.{$break}\n";

            $errors++;
        }

        $result = sqlqueryn("ALTER TABLE $ut_players DROP pnum");

        if ($result) {
            $result = sqlqueryn(
                "ALTER TABLE $ut_players
                           ADD pnum mediumint(8) unsigned NOT NULL auto_increment FIRST,
                           ADD UNIQUE KEY pnum(pnum)"
            );

            if ($result) {
                echo "Modified unique key pnum in $ut_players.{$break}\n";
            } else {
                echo "{$bold}Error modifying unique key pnum in $ut_players!{$ebold}{$break}\n";

                $errors++;
            }
        } else {
            echo "{$bold}Error dropping pnum in $ut_players!{$ebold}{$break}\n";

            $errors++;
        }
    } else {
        echo "Primary key pnum already set.{$break}\n";
    }
} else {
    echo "{$bold}Error viewing table info for $ut_players!{$ebold}{$break}\n";

    $errors++;
}

//*****************************************************************************
//********** ut_player types (1.1) ********************************************
//*****************************************************************************

$result = sqlqueryn("SELECT lm_time FROM $ut_players LIMIT 1");
if ($result) {
    echo "Table $ut_players (mutant, invasion, lms) already updated.{$break}\n";
} else {
    $result = sqlqueryn(
        "ALTER TABLE $ut_players
                       ADD mu_score mediumint(8) NOT NULL default '0' AFTER br_bombassist,
                       ADD mu_frags mediumint(9) NOT NULL default '0' AFTER mu_score,
                       ADD mu_kills mediumint(8) unsigned NOT NULL default '0' AFTER mu_frags,
                       ADD mu_deaths mediumint(8) unsigned NOT NULL default '0' AFTER mu_kills,
                       ADD mu_suicides mediumint(8) unsigned NOT NULL default '0' AFTER mu_deaths,
                       ADD mu_wins1 mediumint(8) unsigned NOT NULL default '0' AFTER mu_suicides,
                       ADD mu_wins2 mediumint(8) unsigned NOT NULL default '0' AFTER mu_wins1,
                       ADD mu_wins3 mediumint(8) unsigned NOT NULL default '0' AFTER mu_wins2,
                       ADD mu_losses mediumint(8) unsigned NOT NULL default '0' AFTER mu_wins3,
                       ADD mu_games mediumint(8) unsigned NOT NULL default '0' AFTER mu_losses,
                       ADD mu_time int(10) unsigned NOT NULL default '0' AFTER mu_games,
                       ADD in_score mediumint(8) NOT NULL default '0' AFTER mu_time,
                       ADD in_frags mediumint(9) NOT NULL default '0' AFTER in_score,
                       ADD in_kills mediumint(8) unsigned NOT NULL default '0' AFTER in_frags,
                       ADD in_deaths mediumint(8) unsigned NOT NULL default '0' AFTER in_kills,
                       ADD in_suicides mediumint(8) unsigned NOT NULL default '0' AFTER in_deaths,
                       ADD in_teamkills mediumint(8) unsigned NOT NULL default '0' AFTER in_suicides,
                       ADD in_teamdeaths mediumint(8) unsigned NOT NULL default '0' AFTER in_teamkills,
                       ADD in_wins1 mediumint(8) unsigned NOT NULL default '0' AFTER in_teamdeaths,
                       ADD in_wins2 mediumint(8) unsigned NOT NULL default '0' AFTER in_wins1,
                       ADD in_wins3 mediumint(8) unsigned NOT NULL default '0' AFTER in_wins2,
                       ADD in_losses mediumint(8) unsigned NOT NULL default '0' AFTER in_wins3,
                       ADD in_games mediumint(8) unsigned NOT NULL default '0' AFTER in_losses,
                       ADD in_time int(10) unsigned NOT NULL default '0' AFTER in_games,
                       ADD lm_score mediumint(8) NOT NULL default '0' AFTER in_time,
                       ADD lm_frags mediumint(9) NOT NULL default '0' AFTER lm_score,
                       ADD lm_kills mediumint(8) unsigned NOT NULL default '0' AFTER lm_frags,
                       ADD lm_deaths mediumint(8) unsigned NOT NULL default '0' AFTER lm_kills,
                       ADD lm_suicides mediumint(8) unsigned NOT NULL default '0' AFTER lm_deaths,
                       ADD lm_wins mediumint(8) unsigned NOT NULL default '0' AFTER lm_suicides,
                       ADD lm_losses mediumint(8) unsigned NOT NULL default '0' AFTER lm_wins,
                       ADD lm_games mediumint(8) unsigned NOT NULL default '0' AFTER lm_losses,
                       ADD lm_time int(10) unsigned NOT NULL default '0' AFTER lm_games"
    );

    if ($result) {
        echo "Updated $ut_players (mutant, invasion, lms).{$break}\n";
    } else {
        echo "{$bold}Failed updating $ut_players (mutant, invasion, lms)!{$ebold}{$break}\n";

        $errors++;
    }
}

//*****************************************************************************
//********** ut_gplayers (1.1) ************************************************
//*****************************************************************************

$result = sqlqueryn("SELECT gp_name FROM $ut_gplayers LIMIT 1");
if (!$result) {
    echo "Table $ut_gplayers already updated.{$break}\n";
} else {
    $result = sqlqueryn("ALTER TABLE $ut_gplayers DROP gp_name");

    if ($result) {
        echo "Updated table $ut_gplayers.{$break}\n";
    } else {
        echo "{$bold}Failed updating table $ut_gplayers!{$ebold}{$break}\n";

        $errors++;
    }
}

//*****************************************************************************
//********** Version 1.12 *****************************************************
//*****************************************************************************

echo "{$ital}Checking version 1.12 updates.{$eital}{$break}\n";

//*****************************************************************************
//********** Table Indexes (1.12) *********************************************
//*****************************************************************************

$result = sqlqueryn("SHOW INDEX FROM $ut_players");
$i = $GLOBALS['xoopsDB']->getRowsNum($result);
if (3 == $i) {
    echo "Table indexes already updated.{$break}\n";
} else {
    $ierrors = 0;

    $result = sqlqueryn("ALTER TABLE $ut_pwkills DROP INDEX pwk_player");

    if (!$result) {
        echo "{$bold}Error modifying index for table pwk_player!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn("ALTER TABLE $ut_pwkills DROP INDEX pwk_weapon");

    if (!$result) {
        echo "{$bold}Error modifying index for table $ut_pwkills!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn("ALTER TABLE $ut_pwkills ADD INDEX pwk_plrwp(pwk_player,pwk_weapon)");

    if (!$result) {
        echo "{$bold}Error modifying index for table $ut_pwkills(2)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn("ALTER TABLE $ut_items DROP INDEX it_pickups");

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_items!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_items DROP INDEX it_type');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_items(2)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_items ADD INDEX it_typ(it_type)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_items(3)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_pitems DROP INDEX pi_plr');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_pitems!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_pitems DROP INDEX pi_item');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_pitems(2)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_pitems DROP INDEX pi_pickups');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_pitems(3)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_pitems ADD INDEX pi_plritm(pi_plr,pi_item)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_pitems(4)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_players ADD INDEX plr_usrid(plr_user,plr_id)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_players!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_totals DROP INDEX tl_totals');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_totals!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_weapons DROP INDEX wp_frags');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_weapons!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gitems DROP INDEX gi_game');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gitems!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gitems DROP INDEX gi_item');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gitems(2)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gitems DROP INDEX gi_plr');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gitems(3)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gitems DROP INDEX gi_pickups');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gitems(4)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_tkills ADD INDEX tk_gnumteam(tk_game,tk_team)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_tkills!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gplayers DROP INDEX gp_game');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gplayers!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gplayers DROP INDEX gp_num');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gplayers(2)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gplayers ADD INDEX gp_gnum(gp_game)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gplayers(3)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gscores ADD INDEX gs_gnum(gs_game)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gscores!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gkills ADD INDEX gk_gnum(gk_game)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gkills!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gevents ADD INDEX ge_gnumev(ge_game,ge_event)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gevents!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gitems ADD INDEX gi_gnumit(gi_game,gi_item)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gitems!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_gchat ADD INDEX gc_gnum(gc_game)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_gchat!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_type ADD UNIQUE INDEX tp_tnum (tp_num)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_type(3)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_type DROP INDEX tp_num');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_type!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_type DROP INDEX tp_desc');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_type(2)!{$ebold}{$break}\n";

        $ierrors++;
    }

    $result = sqlqueryn('ALTER TABLE ut_type ADD INDEX tp_tnum (tp_num)');

    if (!$result) {
        echo "{$bold}Error modifying index for table ut_type(3)!{$ebold}{$break}\n";

        $ierrors++;
    }

    if (!$ierrors) {
        echo "Updated table indexes.{$break}\n";
    } else {
        echo "{$bold}Failed updating one or more table indexes!{$ebold}{$break}\n";

        $errors += $ierrors;
    }
}

//*****************************************************************************
//********** Totals Index (1.12) **********************************************
//*****************************************************************************

$result = sqlqueryn('SHOW INDEX FROM ut_totals');
$i = $GLOBALS['xoopsDB']->getRowsNum($result);
if (1 != $i) {
    $result = sqlqueryn('ALTER TABLE ut_totals ADD INDEX tl_tot(tl_totals)');

    if (!$result) {
        echo "{$bold}Error adding index for table ut_totals!{$ebold}{$break}\n";

        $errors++;
    }
}

//*****************************************************************************
//*****************************************************************************

$GLOBALS['xoopsDB']->close($link);

if ($errors) {
    echo "{$bold}Errors!{$ebold}{$break}\n";
} else {
    echo "{$bold}Done!{$ebold}{$break}\n";
}
