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

if (preg_match('/logsave.php/i', $_SERVER['PHP_SELF'])) {
    echo "Access denied.\n";

    die();
}

function findpwk($player, $weapon) // Player, Weapon
{
    global $ut_pwkills;

    $result = sqlqueryn(
        "SELECT pwk_num FROM $ut_pwkills
                       WHERE pwk_player='$player' AND pwk_weapon='$weapon' LIMIT 1"
    );

    if (!$result) {
        echo "Error accessing pwkills table.<br>\n";

        exit;
    }

    $row = $GLOBALS['xoopsDB']->fetchBoth($result);

    $GLOBALS['xoopsDB']->freeRecordSet($result);

    if ($row) {
        $pwk = (int)$row['pwk_num'];
    } else {
        $result = sqlqueryn("INSERT INTO $ut_pwkills VALUES (NULL,'$player','$weapon','0','0','0','0','0')");

        if (!$result) {
            echo "Error adding pwkills table entry.<br>\n";

            exit;
        }

        $result = sqlqueryn('SELECT LAST_INSERT_ID()');

        $row = $GLOBALS['xoopsDB']->fetchBoth($result);

        $GLOBALS['xoopsDB']->freeRecordSet($result);

        $pwk = (int)$row[0];
    }

    return $pwk;
}

function storedata()
{
    global $server, $player, $gkills, $gkcount, $gscores, $gscount, $team, $tkills, $tkcount, $pickups;

    global $events, $nohtml, $chatlog, $uselimit;

    global $ut_totals, $ut_games, $ut_players, $ut_gplayers, $ut_weapons, $ut_gkills, $ut_pwkills, $ut_gscores, $ut_gitems, $ut_pitems, $ut_items, $ut_gevents, $ut_type, $ut_tkills, $ut_gchat;

    if ($nohtml) {
        $break = '';
    } else {
        $break = '<br>';
    }

    if ((1 == $server[14] || 5 == $server[14]) && $server[9] > 0) { // ended / length
        [
            $map,
            $gametype,
            $gametnum,
            $matchdate,
            $mutators,
            $fraglimit,
            $timelimit,
            $minplayers,
            $translocator,
            $length,
            $password,
            $gamestats,
            $firstblood,
            $starttime,
            $ended,
            $maxplayer,
            $servername,
            $admin,
            $email,
            $tot_score,
            $team,
            $tot_kills,
            $tot_deaths,
            $tot_suicides,
            $teamkills,
            $numplayers,
            $maxpickups,
            $numevents,
            $headshots,
            $numchat,
        ] = $server;

        $matchdate = date('Y-m-d H:i:s', $matchdate); // YYYY-MM-DD HH:MM:SS

        $tot_ptime = 0;

        // Save Game Data

        $servername = $servername;

        $admin = $admin;

        $email = $email;

        $map = $map;

        $mutators = $mutators;

        $result = sqlqueryn(
            "INSERT INTO $ut_games VALUES (NULL,
      '$servername',
      '$admin',
      '$email',
      '$map',
      '$gametnum',
      '$matchdate',
      '$mutators',
      '$fraglimit',
      '$timelimit',
      '$minplayers',
      '$translocator',
      '$starttime',
      '$length',
      '$numplayers',
      '$tot_kills',
      '$tot_deaths',
      '$tot_suicides',
      '$team[0]',
      '$team[1]',
      '$firstblood',
      '$headshots')"
        );

        if (!$result) {
            echo "Error saving game data in database $ut_games.{$break}\n";

            exit;
        }

        $result = sqlqueryn('SELECT LAST_INSERT_ID()');

        $row = $GLOBALS['xoopsDB']->fetchBoth($result);

        $gamenum = (int)$row[0];

        $GLOBALS['xoopsDB']->freeRecordSet($result);

        // Read Totals Data

        $result = sqlqueryn("SELECT * FROM $ut_totals LIMIT 1");

        $row = $GLOBALS['xoopsDB']->fetchBoth($result);

        while (list($key, $val) = each($row)) {
            ${$key} = $val;
        }

        $GLOBALS['xoopsDB']->freeRecordSet($result);

        $tl_chfragssg_map = addslashes($tl_chfragssg_map);

        $tl_chkillssg_map = addslashes($tl_chkillssg_map);

        $tl_chdeathssg_map = addslashes($tl_chdeathssg_map);

        $tl_chsuicidessg_map = addslashes($tl_chsuicidessg_map);

        $tl_chcpcapturesg_map = addslashes($tl_chcpcapturesg_map);

        $tl_chflagcapturesg_map = addslashes($tl_chflagcapturesg_map);

        $tl_chflagreturnsg_map = addslashes($tl_chflagreturnsg_map);

        $tl_chflagkillsg_map = addslashes($tl_chflagkillsg_map);

        $tl_chbombcarriedsg_map = addslashes($tl_chbombcarriedsg_map);

        $tl_chbombtossedsg_map = addslashes($tl_chbombtossedsg_map);

        $tl_chbombkillsg_map = addslashes($tl_chbombkillsg_map);

        // Player Data

        for ($i = 0; $i <= $maxplayer; $i++) {
            if (isset($player[$i]) && '' != $player[$i]['p_name']) {
                while (list($key, $val) = each($player[$i])) {
                    ${$key} = $val;
                }

                $frags = $p_kills - $p_suicides;

                // Check for existing player

                $spname = addslashes($p_name);

                $spuser = addslashes($p_user);

                if ($p_user && $p_id) {
                    $result = sqlqueryn("SELECT * FROM $ut_players WHERE plr_user='$spuser' AND plr_id='$p_id' LIMIT 1");
                } else {
                    $result = sqlqueryn("SELECT * FROM $ut_players WHERE plr_name='$spname' AND plr_user='' AND plr_id='' LIMIT 1");
                }

                if (!$result) {
                    echo "Error accessing players database.{$break}\n";

                    exit;
                }

                $row = $GLOBALS['xoopsDB']->fetchBoth($result);

                if (!$row) { // Create new player
                    $result = sqlqueryn("INSERT INTO $ut_players SET plr_name='$spname', plr_user='$spuser', plr_id='$p_id', plr_key='$p_key'");

                    if (!$result) {
                        echo "Error creating new player in database.{$break}\n";

                        exit;
                    }

                    $result = sqlqueryn('SELECT LAST_INSERT_ID()');

                    $row = $GLOBALS['xoopsDB']->fetchBoth($result);

                    $plrnum = (int)$row[0];

                    $result = sqlqueryn("SELECT * FROM $ut_players WHERE pnum='$plrnum' LIMIT 1");

                    $row = $GLOBALS['xoopsDB']->fetchBoth($result);

                    if (!$p_bot) {
                        $tl_players++;
                    }
                }

                $GLOBALS['xoopsDB']->freeRecordSet($result);

                while (list($key, $val) = each($row)) {
                    ${$key} = $val;
                }

                $player[$i]['p_num'] = $pnum;

                $plr_name = addslashes($plr_name);

                $p_name = addslashes($p_name);

                $plr_user = addslashes($plr_user);

                $plr_bot = $p_bot;

                $plr_score += $p_t0score + $p_t1score;

                $plr_frags += $frags;

                $plr_kills += $p_kills;

                $plr_deaths += $p_deaths;

                $plr_suicides += $p_suicides;

                $plr_headshots += $p_headshots;

                $plr_firstblood += $p_firstblood;

                $plr_transgib += $p_transgib;

                $plr_multi1 += $p_multi1;

                $plr_multi2 += $p_multi2;

                $plr_multi3 += $p_multi3;

                $plr_multi4 += $p_multi4;

                $plr_multi5 += $p_multi5;

                $plr_multi6 += $p_multi6;

                $plr_multi7 += $p_multi7;

                $plr_spree1 += $p_spree1;

                $plr_spreet1 += $p_spreet1;

                $plr_spreek1 += $p_spreek1;

                $plr_spree2 += $p_spree2;

                $plr_spreet2 += $p_spreet2;

                $plr_spreek2 += $p_spreek2;

                $plr_spree3 += $p_spree3;

                $plr_spreet3 += $p_spreet3;

                $plr_spreek3 += $p_spreek3;

                $plr_spree4 += $p_spree4;

                $plr_spreet4 += $p_spreet4;

                $plr_spreek4 += $p_spreek4;

                $plr_spree5 += $p_spree5;

                $plr_spreet5 += $p_spreet5;

                $plr_spreek5 += $p_spreek5;

                $plr_spree6 += $p_spree6;

                $plr_spreet6 += $p_spreet6;

                $plr_spreek6 += $p_spreek6;

                $plr_combo1 += $p_combo1;

                $plr_combo2 += $p_combo2;

                $plr_combo3 += $p_combo3;

                $plr_combo4 += $p_combo4;

                switch ($gametype) {
                    case 1: // DeathMatch
                        $dm_score += $p_t0score + $p_t1score;
                        $dm_frags += $frags;
                        $dm_kills += $p_kills;
                        $dm_deaths += $p_deaths;
                        $dm_suicides += $p_suicides;
                        if (1 == $p_rank) {
                            $dm_wins1++;
                        } elseif (2 == $p_rank && $numplayers > 2) {
                            $dm_wins2++;
                        } elseif (3 == $p_rank && $numplayers > 3) {
                            $dm_wins3++;
                        } else {
                            $dm_losses++;
                        }
                        $dm_games++;
                        $dm_time += $p_totaltime;
                        break;
                    case 2: // Capture the Flag
                        $ctf_score += $p_t0score + $p_t1score;
                        $ctf_frags += $frags;
                        $ctf_kills += $p_kills;
                        $ctf_deaths += $p_deaths;
                        $ctf_suicides += $p_suicides;
                        $ctf_teamkills += $p_teamkills;
                        $ctf_teamdeaths += $p_teamdeaths;
                        $ctf_flagcapture += $p_capcarry;
                        $ctf_flagdrop += $p_dropped;
                        $ctf_flagpickup += $p_pickup;
                        $ctf_flagreturn += $p_return;
                        $ctf_flagtaken += $p_taken;
                        $ctf_flagkill += $p_typekill;
                        $ctf_flagassist += $p_assist;
                        if ($team[$p_team] > $team[1 - $p_team]) {
                            $ctf_wins++;
                        } else {
                            $ctf_losses++;
                        }
                        $ctf_games++;
                        $ctf_time += $p_totaltime;
                        break;
                    case 3: // Bombing Run
                        $br_score += $p_t0score + $p_t1score;
                        $br_frags += $frags;
                        $br_kills += $p_kills;
                        $br_deaths += $p_deaths;
                        $br_suicides += $p_suicides;
                        $br_teamkills += $p_teamkills;
                        $br_teamdeaths += $p_teamdeaths;
                        $br_bombcarried += $p_capcarry;
                        $br_bombtossed += $p_tossed;
                        $br_bombdrop += $p_dropped;
                        $br_bombpickup += $p_pickup;
                        $br_bombtaken += $p_taken;
                        $br_bombkill += $p_typekill;
                        $br_bombassist += $p_assist;
                        if ($team[$p_team] > $team[1 - $p_team]) {
                            $br_wins++;
                        } else {
                            $br_losses++;
                        }
                        $br_games++;
                        $br_time += $p_totaltime;
                        break;
                    case 4: // Team DeathMatch
                        $tdm_score += $p_t0score + $p_t1score;
                        $tdm_frags += $frags;
                        $tdm_kills += $p_kills;
                        $tdm_deaths += $p_deaths;
                        $tdm_suicides += $p_suicides;
                        $tdm_teamkills += $p_teamkills;
                        $tdm_teamdeaths += $p_teamdeaths;
                        if ($team[$p_team] > $team[1 - $p_team]) {
                            $tdm_wins++;
                        } else {
                            $tdm_losses++;
                        }
                        $tdm_games++;
                        $tdm_time += $p_totaltime;
                        break;
                    case 5: // Double Domination
                        $dd_score += $p_t0score + $p_t1score;
                        $dd_frags += $frags;
                        $dd_kills += $p_kills;
                        $dd_deaths += $p_deaths;
                        $dd_suicides += $p_suicides;
                        $dd_teamkills += $p_teamkills;
                        $dd_teamdeaths += $p_teamdeaths;
                        $dd_cpcapture += $p_capcarry;
                        if ($team[$p_team] > $team[1 - $p_team]) {
                            $dd_wins++;
                        } else {
                            $dd_losses++;
                        }
                        $dd_games++;
                        $dd_time += $p_totaltime;
                        break;
                    case 6: // Mutant
                        $mu_score += $p_t0score + $p_t1score;
                        $mu_frags += $frags;
                        $mu_kills += $p_kills;
                        $mu_deaths += $p_deaths;
                        $mu_suicides += $p_suicides;
                        if (1 == $p_rank) {
                            $mu_wins1++;
                        } elseif (2 == $p_rank && $numplayers > 2) {
                            $mu_wins2++;
                        } elseif (3 == $p_rank && $numplayers > 3) {
                            $mu_wins3++;
                        } else {
                            $mu_losses++;
                        }
                        $mu_games++;
                        $mu_time += $p_totaltime;
                        break;
                    case 7: // Invasion
                        $in_score += $p_t0score + $p_t1score;
                        $in_frags += $frags;
                        $in_kills += $p_kills;
                        $in_deaths += $p_deaths;
                        $in_suicides += $p_suicides;
                        if (1 == $p_rank) {
                            $in_wins1++;
                        } elseif (2 == $p_rank && $numplayers > 2) {
                            $in_wins2++;
                        } elseif (3 == $p_rank && $numplayers > 3) {
                            $in_wins3++;
                        } else {
                            $in_losses++;
                        }
                        $in_games++;
                        $in_time += $p_totaltime;
                        break;
                    case 8: // Last Man Standing
                        $lm_score += $p_t0score + $p_t1score;
                        $lm_frags += $frags;
                        $lm_kills += $p_kills;
                        $lm_deaths += $p_deaths;
                        $lm_suicides += $p_suicides;
                        if (1 == $p_rank) {
                            $lm_wins1++;
                        } elseif (2 == $p_rank && $numplayers > 2) {
                            $lm_wins2++;
                        } elseif (3 == $p_rank && $numplayers > 3) {
                            $lm_wins3++;
                        } else {
                            $lm_losses++;
                        }
                        $lm_games++;
                        $lm_time += $p_totaltime;
                        break;
                    default:
                        $other_score += $p_t0score + $p_t1score;
                        $other_frags += $frags;
                        $other_kills += $p_kills;
                        $other_deaths += $p_deaths;
                        $other_suicides += $p_suicides;
                        $other_teamkills += $p_teamkills;
                        $other_teamdeaths += $p_teamdeaths;
                        if ($team[$p_team] > $team[1 - $p_team]) {
                            $other_wins++;
                        } else {
                            $other_losses++;
                        }
                        $other_games++;
                        $other_time += $p_totaltime;
                }

                // Check for name change

                if ($plr_name != $p_name) {
                    $plr_name = $p_name;
                }

                // Save player stats

                $result = sqlqueryn(
                    "REPLACE INTO $ut_players VALUES (
          '$pnum',
          '$plr_name',
          '$plr_bot',
          '$plr_frags',
          '$plr_score',
          '$plr_kills',
          '$plr_deaths',
          '$plr_suicides',
          '$plr_headshots',
          '$plr_firstblood',
          '$plr_transgib',
          '$plr_user',
          '$plr_id',
          '$plr_key',
          '$dm_score',
          '$dm_frags',
          '$dm_kills',
          '$dm_deaths',
          '$dm_suicides',
          '$dm_wins1',
          '$dm_wins2',
          '$dm_wins3',
          '$dm_losses',
          '$dm_games',
          '$dm_time',
          '$tdm_score',
          '$tdm_frags',
          '$tdm_kills',
          '$tdm_deaths',
          '$tdm_suicides',
          '$tdm_teamkills',
          '$tdm_teamdeaths',
          '$tdm_wins',
          '$tdm_losses',
          '$tdm_games',
          '$tdm_time',
          '$dd_score',
          '$dd_frags',
          '$dd_kills',
          '$dd_deaths',
          '$dd_suicides',
          '$dd_teamkills',
          '$dd_teamdeaths',
          '$dd_wins',
          '$dd_losses',
          '$dd_games',
          '$dd_time',
          '$dd_cpcapture',
          '$ctf_score',
          '$ctf_frags',
          '$ctf_kills',
          '$ctf_deaths',
          '$ctf_suicides',
          '$ctf_teamkills',
          '$ctf_teamdeaths',
          '$ctf_wins',
          '$ctf_losses',
          '$ctf_games',
          '$ctf_time',
          '$ctf_flagcapture',
          '$ctf_flagdrop',
          '$ctf_flagpickup',
          '$ctf_flagreturn',
          '$ctf_flagtaken',
          '$ctf_flagkill',
          '$ctf_flagassist',
          '$br_score',
          '$br_frags',
          '$br_kills',
          '$br_deaths',
          '$br_suicides',
          '$br_teamkills',
          '$br_teamdeaths',
          '$br_wins',
          '$br_losses',
          '$br_games',
          '$br_time',
          '$br_bombcarried',
          '$br_bombtossed',
          '$br_bombdrop',
          '$br_bombpickup',
          '$br_bombtaken',
          '$br_bombkill',
          '$br_bombassist',
          '$mu_score',
          '$mu_frags',
          '$mu_kills',
          '$mu_deaths',
          '$mu_suicides',
          '$mu_wins1',
          '$mu_wins2',
          '$mu_wins3',
          '$mu_losses',
          '$mu_games',
          '$mu_time',
          '$in_score',
          '$in_frags',
          '$in_kills',
          '$in_deaths',
          '$in_suicides',
          '$in_teamkills',
          '$in_teamdeaths',
          '$in_wins1',
          '$in_wins2',
          '$in_wins3',
          '$in_losses',
          '$in_games',
          '$in_time',
          '$lm_score',
          '$lm_frags',
          '$lm_kills',
          '$lm_deaths',
          '$lm_suicides',
          '$lm_wins',
          '$lm_losses',
          '$lm_games',
          '$lm_time',
          '$other_score',
          '$other_frags',
          '$other_kills',
          '$other_deaths',
          '$other_suicides',
          '$other_teamkills',
          '$other_teamdeaths',
          '$other_wins',
          '$other_losses',
          '$other_games',
          '$other_time',
          '$plr_multi1',
          '$plr_multi2',
          '$plr_multi3',
          '$plr_multi4',
          '$plr_multi5',
          '$plr_multi6',
          '$plr_multi7',
          '$plr_spree1',
          '$plr_spreet1',
          '$plr_spreek1',
          '$plr_spree2',
          '$plr_spreet2',
          '$plr_spreek2',
          '$plr_spree3',
          '$plr_spreet3',
          '$plr_spreek3',
          '$plr_spree4',
          '$plr_spreet4',
          '$plr_spreek4',
          '$plr_spree5',
          '$plr_spreet5',
          '$plr_spreek5',
          '$plr_spree6',
          '$plr_spreet6',
          '$plr_spreek6',
          '$plr_combo1',
          '$plr_combo2',
          '$plr_combo3',
          '$plr_combo4')"
                );

                if (!$result) {
                    echo "Error saving player data in database.{$break}\n";

                    exit;
                }

                // Save Game Player Data

                $result = sqlqueryn(
                    "INSERT INTO $ut_gplayers VALUES (
          '$gamenum',
          '$i',
          '$p_bot',
          '$pnum',
          '$p_t0score',
          '$p_t1score',
          '$p_kills',
          '$p_deaths',
          '$p_suicides',
          '$p_totaltime',
          '$p_headshots',
          '$p_firstblood',
          '$p_teamkills',
          '$p_teamdeaths',
          '$p_capcarry',
          '$p_tossed',
          '$p_dropped',
          '$p_pickup',
          '$p_return',
          '$p_taken',
          '$p_typekill',
          '$p_assist',
          '$p_multi1',
          '$p_multi2',
          '$p_multi3',
          '$p_multi4',
          '$p_multi5',
          '$p_multi6',
          '$p_multi7',
          '$p_spree1',
          '$p_spree2',
          '$p_spree3',
          '$p_spree4',
          '$p_spree5',
          '$p_spree6',
          '$p_combo1',
          '$p_combo2',
          '$p_combo3',
          '$p_combo4',
          '$p_transgib',
          '$p_rank',
          '$p_team')"
                );

                if (!$result) {
                    echo "Error saving game player data in database.{$break}\n";

                    exit;
                }

                // Totals

                $tl_score += $p_t0score + $p_t1score;

                $tl_kills += $p_kills;

                $tl_deaths += $p_deaths;

                $tl_suicides += $p_suicides;

                $tl_teamkills += $p_teamkills;

                $tl_teamdeaths += $p_teamdeaths;

                $tl_headshots += $p_headshots;

                $tl_time += $p_totaltime;

                if (!$p_bot) {
                    $tl_playertime += $p_totaltime;

                    $tot_ptime += $p_totaltime;
                }

                $tl_multi1 += $p_multi1;

                $tl_multi2 += $p_multi2;

                $tl_multi3 += $p_multi3;

                $tl_multi4 += $p_multi4;

                $tl_multi5 += $p_multi5;

                $tl_multi6 += $p_multi6;

                $tl_multi7 += $p_multi7;

                $tl_spree1 += $p_spree1;

                $tl_spreet1 += $p_spreet1;

                $tl_spreek1 += $p_spreek1;

                $tl_spree2 += $p_spree2;

                $tl_spreet2 += $p_spreet2;

                $tl_spreek2 += $p_spreek2;

                $tl_spree3 += $p_spree3;

                $tl_spreet3 += $p_spreet3;

                $tl_spreek3 += $p_spreek3;

                $tl_spree4 += $p_spree4;

                $tl_spreet4 += $p_spreet4;

                $tl_spreek4 += $p_spreek4;

                $tl_spree5 += $p_spree5;

                $tl_spreet5 += $p_spreet5;

                $tl_spreek5 += $p_spreek5;

                $tl_spree6 += $p_spree6;

                $tl_spreet6 += $p_spreet6;

                $tl_spreek6 += $p_spreek6;

                $tl_combo1 += $p_combo1;

                $tl_combo2 += $p_combo2;

                $tl_combo3 += $p_combo3;

                $tl_combo4 += $p_combo4;

                $tl_transgib += $p_transgib;

                // Game highs

                if (!$p_bot) {
                    if ($frags > $tl_chfragssg) {
                        $tl_chfragssg = $frags;

                        $tl_chfragssg_plr = $pnum;

                        $tl_chfragssg_tm = $p_totaltime;

                        $tl_chfragssg_map = $map;

                        $tl_chfragssg_date = $matchdate;
                    }

                    if ($p_kills > $tl_chkillssg) {
                        $tl_chkillssg = $p_kills;

                        $tl_chkillssg_plr = $pnum;

                        $tl_chkillssg_tm = $p_totaltime;

                        $tl_chkillssg_map = $map;

                        $tl_chkillssg_date = $matchdate;
                    }

                    if ($p_deaths > $tl_chdeathssg) {
                        $tl_chdeathssg = $p_deaths;

                        $tl_chdeathssg_plr = $pnum;

                        $tl_chdeathssg_tm = $p_totaltime;

                        $tl_chdeathssg_map = $map;

                        $tl_chdeathssg_date = $matchdate;
                    }

                    if ($p_suicides > $tl_chsuicidessg) {
                        $tl_chsuicidessg = $p_suicides;

                        $tl_chsuicidessg_plr = $pnum;

                        $tl_chsuicidessg_tm = $p_totaltime;

                        $tl_chsuicidessg_map = $map;

                        $tl_chsuicidessg_date = $matchdate;
                    }
                }

                switch ($gametype) {
                    case 1: // DeathMatch
                        $tl_spkills += $p_kills;
                        $tl_spdeaths += $p_deaths;
                        $tl_spsuicides += $p_suicides;
                        $tl_spteamkills += $p_kills;
                        $tl_spteamdeaths += $p_deaths;
                        $tl_spgames += 1;
                        $tl_sptime += $p_totaltime;
                        break;
                    case 2: // Capture the Flag
                        $tl_flagcapture += $p_capcarry;
                        $tl_flagdrop += $p_dropped;
                        $tl_flagpickup += $p_pickup;
                        $tl_flagreturn += $p_return;
                        $tl_flagtaken += $p_taken;
                        $tl_flagkill += $p_typekill;
                        $tl_flagassist += $p_assist;
                        if ($p_capcarry > $tl_chflagcapturesg && !$p_bot) {
                            $tl_chflagcapturesg = $p_capcarry;

                            $tl_chflagcapturesg_plr = $pnum;

                            $tl_chflagcapturesg_tm = $p_totaltime;

                            $tl_chflagcapturesg_map = $map;

                            $tl_chflagcapturesg_date = $matchdate;
                        }
                        if ($p_return > $tl_chflagreturnsg && !$p_bot) {
                            $tl_chflagreturnsg = $p_return;

                            $tl_chflagreturnsg_plr = $pnum;

                            $tl_chflagreturnsg_tm = $p_totaltime;

                            $tl_chflagreturnsg_map = $map;

                            $tl_chflagreturnsg_date = $matchdate;
                        }
                        if ($p_typekill > $tl_chflagkillsg && !$p_bot) {
                            $tl_chflagkillsg = $p_typekill;

                            $tl_chflagkillsg_plr = $pnum;

                            $tl_chflagkillsg_tm = $p_totaltime;

                            $tl_chflagkillsg_map = $map;

                            $tl_chflagkillsg_date = $matchdate;
                        }
                        break;
                    case 3: // Bombing Run
                        $tl_bombcarried += $p_capcarry;
                        $tl_bombtossed += $p_tossed;
                        $tl_bombdrop += $p_dropped;
                        $tl_bombpickup += $p_pickup;
                        $tl_bombtaken += $p_taken;
                        $tl_bombkill += $p_typekill;
                        $tl_bombassist += $p_assist;
                        if ($p_capcarry > $tl_chbombcarriedsg && !$p_bot) {
                            $tl_chbombcarriedsg = $p_capcarry;

                            $tl_chbombcarriedsg_plr = $pnum;

                            $tl_chbombcarriedsg_tm = $p_totaltime;

                            $tl_chbombcarriedsg_map = $map;

                            $tl_chbombcarriedsg_date = $matchdate;
                        }
                        if ($p_tossed > $tl_chbombtossedsg && !$p_bot) {
                            $tl_chbombtossedsg = $p_tossed;

                            $tl_chbombtossedsg_plr = $pnum;

                            $tl_chbombtossedsg_tm = $p_totaltime;

                            $tl_chbombtossedsg_map = $map;

                            $tl_chbombtossedsg_date = $matchdate;
                        }
                        break;
                    case 4: // Team DeathMatch
                        break;
                    case 5: // Double Domination
                        $tl_cpcapture += $p_capcarry;
                        if ($p_capcarry > $tl_chcpcapturesg && !$p_bot) {
                            $tl_chcpcapturesg = $p_capcarry;

                            $tl_chcpcapturesg_plr = $pnum;

                            $tl_chcpcapturesg_tm = $p_totaltime;

                            $tl_chcpcapturesg_map = $map;

                            $tl_chcpcapturesg_date = $matchdate;
                        }
                        break;
                    default: // Other
                }
            }
        }

        // Save Totals

        $tl_games++;

        $tl_gametime += $length;

        $result = sqlqueryn(
            "REPLACE INTO $ut_totals VALUES (
      'Totals',
      '$tl_score',
      '$tl_kills',
      '$tl_deaths',
      '$tl_suicides',
      '$tl_teamkills',
      '$tl_teamdeaths',
      '$tl_players',
      '$tl_games',
      '$tl_time',
      '$tl_gametime',
      '$tl_playertime',
      '$tl_cpcapture',
      '$tl_flagcapture',
      '$tl_flagdrop',
      '$tl_flagpickup',
      '$tl_flagreturn',
      '$tl_flagtaken',
      '$tl_flagkill',
      '$tl_flagassist',
      '$tl_bombcarried',
      '$tl_bombtossed',
      '$tl_bombdrop',
      '$tl_bombpickup',
      '$tl_bombtaken',
      '$tl_bombkill',
      '$tl_bombassist',
      '$tl_spkills',
      '$tl_spdeaths',
      '$tl_spsuicides',
      '$tl_spteamkills',
      '$tl_spteamdeaths',
      '$tl_spgames',
      '$tl_sptime',
      '$tl_headshots',
      '$tl_multi1',
      '$tl_multi2',
      '$tl_multi3',
      '$tl_multi4',
      '$tl_multi5',
      '$tl_multi6',
      '$tl_multi7',
      '$tl_spree1',
      '$tl_spreet1',
      '$tl_spreek1',
      '$tl_spree2',
      '$tl_spreet2',
      '$tl_spreek2',
      '$tl_spree3',
      '$tl_spreet3',
      '$tl_spreek3',
      '$tl_spree4',
      '$tl_spreet4',
      '$tl_spreek4',
      '$tl_spree5',
      '$tl_spreet5',
      '$tl_spreek5',
      '$tl_spree6',
      '$tl_spreet6',
      '$tl_spreek6',
      '$tl_combo1',
      '$tl_combo2',
      '$tl_combo3',
      '$tl_combo4',
      '$tl_transgib',
      '$tl_chfrags',
      '$tl_chfrags_plr',
      '$tl_chfrags_gms',
      '$tl_chfrags_tm',
      '$tl_chkills',
      '$tl_chkills_plr',
      '$tl_chkills_gms',
      '$tl_chkills_tm',
      '$tl_chdeaths',
      '$tl_chdeaths_plr',
      '$tl_chdeaths_gms',
      '$tl_chdeaths_tm',
      '$tl_chsuicides',
      '$tl_chsuicides_plr',
      '$tl_chsuicides_gms',
      '$tl_chsuicides_tm',
      '$tl_chfirstblood',
      '$tl_chfirstblood_plr',
      '$tl_chfirstblood_gms',
      '$tl_chfirstblood_tm',
      '$tl_chheadshots',
      '$tl_chheadshots_plr',
      '$tl_chheadshots_gms',
      '$tl_chheadshots_tm',
      '$tl_chmulti1',
      '$tl_chmulti1_plr',
      '$tl_chmulti1_gms',
      '$tl_chmulti1_tm',
      '$tl_chmulti2',
      '$tl_chmulti2_plr',
      '$tl_chmulti2_gms',
      '$tl_chmulti2_tm',
      '$tl_chmulti3',
      '$tl_chmulti3_plr',
      '$tl_chmulti3_gms',
      '$tl_chmulti3_tm',
      '$tl_chmulti4',
      '$tl_chmulti4_plr',
      '$tl_chmulti4_gms',
      '$tl_chmulti4_tm',
      '$tl_chmulti5',
      '$tl_chmulti5_plr',
      '$tl_chmulti5_gms',
      '$tl_chmulti5_tm',
      '$tl_chmulti6',
      '$tl_chmulti6_plr',
      '$tl_chmulti6_gms',
      '$tl_chmulti6_tm',
      '$tl_chmulti7',
      '$tl_chmulti7_plr',
      '$tl_chmulti7_gms',
      '$tl_chmulti7_tm',
      '$tl_chspree1',
      '$tl_chspree1_plr',
      '$tl_chspree1_gms',
      '$tl_chspree1_tm',
      '$tl_chspree2',
      '$tl_chspree2_plr',
      '$tl_chspree2_gms',
      '$tl_chspree2_tm',
      '$tl_chspree3',
      '$tl_chspree3_plr',
      '$tl_chspree3_gms',
      '$tl_chspree3_tm',
      '$tl_chspree4',
      '$tl_chspree4_plr',
      '$tl_chspree4_gms',
      '$tl_chspree4_tm',
      '$tl_chspree5',
      '$tl_chspree5_plr',
      '$tl_chspree5_gms',
      '$tl_chspree5_tm',
      '$tl_chspree6',
      '$tl_chspree6_plr',
      '$tl_chspree6_gms',
      '$tl_chspree6_tm',
      '$tl_chfph',
      '$tl_chfph_plr',
      '$tl_chfph_gms',
      '$tl_chfph_tm',
      '$tl_chcpcapture',
      '$tl_chcpcapture_plr',
      '$tl_chcpcapture_gms',
      '$tl_chcpcapture_tm',
      '$tl_chflagcapture',
      '$tl_chflagcapture_plr',
      '$tl_chflagcapture_gms',
      '$tl_chflagcapture_tm',
      '$tl_chflagreturn',
      '$tl_chflagreturn_plr',
      '$tl_chflagreturn_gms',
      '$tl_chflagreturn_tm',
      '$tl_chflagkill',
      '$tl_chflagkill_plr',
      '$tl_chflagkill_gms',
      '$tl_chflagkill_tm',
      '$tl_chbombcarried',
      '$tl_chbombcarried_plr',
      '$tl_chbombcarried_gms',
      '$tl_chbombcarried_tm',
      '$tl_chbombtossed',
      '$tl_chbombtossed_plr',
      '$tl_chbombtossed_gms',
      '$tl_chbombtossed_tm',
      '$tl_chbombkill',
      '$tl_chbombkill_plr',
      '$tl_chbombkill_gms',
      '$tl_chbombkill_tm',
      '$tl_chwins',
      '$tl_chwins_plr',
      '$tl_chwins_gms',
      '$tl_chwins_tm',
      '$tl_chteamwins',
      '$tl_chteamwins_plr',
      '$tl_chteamwins_gms',
      '$tl_chteamwins_tm',
      '$tl_chfragssg',
      '$tl_chfragssg_plr',
      '$tl_chfragssg_tm',
      '$tl_chfragssg_map',
      '$tl_chfragssg_date',
      '$tl_chkillssg',
      '$tl_chkillssg_plr',
      '$tl_chkillssg_tm',
      '$tl_chkillssg_map',
      '$tl_chkillssg_date',
      '$tl_chdeathssg',
      '$tl_chdeathssg_plr',
      '$tl_chdeathssg_tm',
      '$tl_chdeathssg_map',
      '$tl_chdeathssg_date',
      '$tl_chsuicidessg',
      '$tl_chsuicidessg_plr',
      '$tl_chsuicidessg_tm',
      '$tl_chsuicidessg_map',
      '$tl_chsuicidessg_date',
      '$tl_chcpcapturesg',
      '$tl_chcpcapturesg_plr',
      '$tl_chcpcapturesg_tm',
      '$tl_chcpcapturesg_map',
      '$tl_chcpcapturesg_date',
      '$tl_chflagcapturesg',
      '$tl_chflagcapturesg_plr',
      '$tl_chflagcapturesg_tm',
      '$tl_chflagcapturesg_map',
      '$tl_chflagcapturesg_date',
      '$tl_chflagreturnsg',
      '$tl_chflagreturnsg_plr',
      '$tl_chflagreturnsg_tm',
      '$tl_chflagreturnsg_map',
      '$tl_chflagreturnsg_date',
      '$tl_chflagkillsg',
      '$tl_chflagkillsg_plr',
      '$tl_chflagkillsg_tm',
      '$tl_chflagkillsg_map',
      '$tl_chflagkillsg_date',
      '$tl_chbombcarriedsg',
      '$tl_chbombcarriedsg_plr',
      '$tl_chbombcarriedsg_tm',
      '$tl_chbombcarriedsg_map',
      '$tl_chbombcarriedsg_date',
      '$tl_chbombtossedsg',
      '$tl_chbombtossedsg_plr',
      '$tl_chbombtossedsg_tm',
      '$tl_chbombtossedsg_map',
      '$tl_chbombtossedsg_date',
      '$tl_chbombkillsg',
      '$tl_chbombkillsg_plr',
      '$tl_chbombkillsg_tm',
      '$tl_chbombkillsg_map',
      '$tl_chbombkillsg_date')"
        );

        if (!$result) {
            echo "Error saving totals data.{$break}\n";

            exit;
        }

        // Load Weapons for Single Game Totals

        $result = sqlqueryn(
            "SELECT wp_num,wp_chkillssg,wp_chkillssg_plr,wp_chkillssg_tm,
      wp_chkillssg_map,wp_chkillssg_dt,wp_chdeathssg,wp_chdeathssg_plr,wp_chdeathssg_tm,
      wp_chdeathssg_map,wp_chdeathssg_dt,wp_chdeathshldsg,wp_chdeathshldsg_plr,
      wp_chdeathshldsg_tm,wp_chdeathshldsg_map,wp_chdeathshldsg_dt,wp_chsuicidessg,
      wp_chsuicidessg_plr,wp_chsuicidessg_tm,wp_chsuicidessg_map,wp_chsuicidessg_dt
      FROM $ut_weapons"
        );

        if (!$result) {
            echo "Error loading weapons data.{$break}\n";

            exit;
        }

        $numweapons = 0;

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
            $weapsg[$numweapons++] = $row;
        }

        $GLOBALS['xoopsDB']->freeRecordSet($result);

        // Clear weapon specific per player totals

        for ($wpn = 0; $wpn <= $numweapons; $wpn++) {
            for ($i = 0; $i <= $maxplayer; $i++) {
                if (isset($player[$i])) {
                    $wtkills[$wpn][$i] = $wtdeaths[$wpn][$i] = $wtheld[$wpn][$i] = $wtsuicides[$wpn][$i] = 0;
                }
            }
        }

        // Save Individual Kill Log

        for ($i = 0; $i < $gkcount; $i++) {
            [$gkkiller, $gkvictim, $gktime, $gkkweapon, $gkvweapon, $gkkteam, $gkvteam] = $gkills[$i];

            $result = sqlqueryn(
                "INSERT INTO $ut_gkills VALUES (
        '$gamenum',
        '$gkkiller',
        '$gkvictim',
        '$gktime',
        '$gkkweapon',
        '$gkvweapon',
        '$gkkteam',
        '$gkvteam')"
            );

            if (!$result) {
                echo "Error saving gkills data.{$break}\n";

                exit;
            }

            // Use actual player numbers for gkkiller and gkvictim

            if ($gkkiller >= 0) {
                $killer = $player[$gkkiller]['p_num'];
            } else {
                $killer = -1;
            }

            if ($gkvictim >= 0) {
                $victim = $player[$gkvictim]['p_num'];
            } else {
                $victim = -1;
            }

            if ($killer == $victim) { // Self-inflicted Suicide
                // Killer Weapon: frags-1 / suicides+1

                $pwk = findpwk($killer, $gkkweapon);

                $result = sqlqueryn("UPDATE $ut_pwkills SET pwk_frags=pwk_frags-1,pwk_suicides=pwk_suicides+1 WHERE pwk_num='$pwk' LIMIT 1");

                if (!$result) {
                    echo "Error updating pwkills table entry [1].{$break}\n";

                    exit;
                }

                // Killer Weapon Totals: frags-1 / suicides+1

                $result = sqlqueryn("UPDATE $ut_weapons SET wp_frags=wp_frags-1,wp_suicides=wp_suicides+1 WHERE wp_num='$gkkweapon' LIMIT 1");

                if (!$result) {
                    echo "Error updating weapons table entry [1].{$break}\n";

                    exit;
                }

                $wtsuicides[$gkkweapon][$gkkiller]++; // Weapon Specific for Game
            } elseif (-1 == $killer) { // Environment Suicide
                // Victim Weapon: suicides+1
                $pwk = findpwk($victim, $gkkweapon);

                $result = sqlqueryn("UPDATE $ut_pwkills SET pwk_suicides=pwk_suicides+1 WHERE pwk_num='$pwk' LIMIT 1");

                if (!$result) {
                    echo "Error updating pwkills table entry [2].{$break}\n";

                    exit;
                }

                // Killer Weapon Totals: nwsuicides+1

                $result = sqlqueryn("UPDATE $ut_weapons SET wp_nwsuicides=wp_nwsuicides+1 WHERE wp_num='$gkkweapon' LIMIT 1");

                if (!$result) {
                    echo "Error updating weapons table entry [2].{$break}\n";

                    exit;
                }

                $wtsuicides[$gkkweapon][$gkvictim]++; // Weapon Specific for Game
            } else {
                // Killer Weapon: frags+1 / kills+1

                $pwk = findpwk($killer, $gkkweapon);

                $result = sqlqueryn("UPDATE $ut_pwkills SET pwk_frags=pwk_frags+1,pwk_kills=pwk_kills+1 WHERE pwk_num='$pwk' LIMIT 1");

                if (!$result) {
                    echo "Error updating pwkills table entry [3].{$break}\n";

                    exit;
                }

                // Victim Weapon: deaths+1

                $pwk = findpwk($victim, $gkkweapon);

                $result = sqlqueryn("UPDATE $ut_pwkills SET pwk_deaths=pwk_deaths+1 WHERE pwk_num='$pwk' LIMIT 1");

                if (!$result) {
                    echo "Error updating pwkills table entry [4].{$break}\n";

                    exit;
                }

                // Victim Held Weapon: held+1

                $pwk = findpwk($victim, $gkvweapon);

                $result = sqlqueryn("UPDATE $ut_pwkills SET pwk_held=pwk_held+1 WHERE pwk_num='$pwk' LIMIT 1");

                if (!$result) {
                    echo "Error updating pwkills table entry [5].{$break}\n";

                    exit;
                }

                // Killer Weapon Totals: frags+1 / kills+1

                $result = sqlqueryn("UPDATE $ut_weapons SET wp_frags=wp_frags+1,wp_kills=wp_kills+1 WHERE wp_num='$gkkweapon' LIMIT 1");

                if (!$result) {
                    echo "Error updating weapons table entry [3].{$break}\n";

                    exit;
                }

                // Victim Weapon Totals: deaths+1

                $result = sqlqueryn("UPDATE $ut_weapons SET wp_deaths=wp_deaths+1 WHERE wp_num='$gkvweapon' LIMIT 1");

                if (!$result) {
                    echo "Error updating weapons table entry [4].{$break}\n";

                    exit;
                }

                // Weapon Specific for Game

                $wtkills[$gkkweapon][$gkkiller]++;

                $wtdeaths[$gkkweapon][$gkvictim]++;

                $wtheld[$gkvweapon][$gkvictim]++;
            }
        }

        // Save Individual Score Log

        for ($i = 0; $i < $gscount; $i++) {
            [$gsplayer, $gstime, $gsscore, $gsteam] = $gscores[$i];

            $result = sqlqueryn(
                "INSERT INTO $ut_gscores VALUES (
        '$gamenum',
        '$gsplayer',
        '$gstime',
        '$gsscore',
        '$gsteam')"
            );

            if (!$result) {
                echo "Error saving gscores data.{$break}\n";

                exit;
            }
        }

        // Check Weapon Totals for Single Game (kills,deaths,suicides)

        for ($wpn = 0; $wpn < $numweapons; $wpn++) {
            $num = $weapsg[$wpn]['wp_num'];

            for ($i = 0; $i <= $maxplayer; $i++) {
                if (isset($player[$i]) && '' != $player[$i]['p_name'] && 0 == $player[$i]['p_bot']) {
                    $pnum = $player[$i]['p_num'];

                    // Weapon Single Game Kill Highs

                    if (isset($wtkills[$num][$i]) && $wtkills[$num][$i] > $weapsg[$wpn]['wp_chkillssg']) {
                        $weapsg[$wpn]['wp_chkillssg'] = $wtkills[$num][$i];

                        $result = sqlqueryn(
                            "UPDATE $ut_weapons SET
              wp_chkillssg='{$wtkills[$num][$i]}',
              wp_chkillssg_plr='$pnum',
              wp_chkillssg_tm='$length',
              wp_chkillssg_map='$map',
              wp_chkillssg_dt='$matchdate'
              WHERE wp_num='$num' LIMIT 1"
                        );

                        if (!$result) {
                            echo "Error saving weapon single game kill highs.{$break}\n";

                            exit;
                        }
                    }

                    // Weapon Single Game Death Highs

                    if (isset($wtdeaths[$num][$i]) && $wtdeaths[$num][$i] > $weapsg[$wpn]['wp_chdeathssg']) {
                        $weapsg[$wpn]['wp_chdeathssg'] = $wtdeaths[$num][$i];

                        $result = sqlqueryn(
                            "UPDATE $ut_weapons SET
              wp_chdeathssg='{$wtdeaths[$num][$i]}',
              wp_chdeathssg_plr='$pnum',
              wp_chdeathssg_tm='$length',
              wp_chdeathssg_map='$map',
              wp_chdeathssg_dt='$matchdate'
              WHERE wp_num='$num' LIMIT 1"
                        );

                        if (!$result) {
                            echo "Error saving weapon single game death highs.{$break}\n";

                            exit;
                        }
                    }

                    // Weapon Single Game Suicide Highs

                    if (isset($wtsuicides[$num][$i]) && $wtsuicides[$num][$i] > $weapsg[$wpn]['wp_chsuicidessg']) {
                        $weapsg[$wpn]['wp_chsuicidessg'] = $wtsuicides[$num][$i];

                        $result = sqlqueryn(
                            "UPDATE $ut_weapons SET
              wp_chsuicidessg='{$wtsuicides[$num][$i]}',
              wp_chsuicidessg_plr='$pnum',
              wp_chsuicidessg_tm='$length',
              wp_chsuicidessg_map='$map',
              wp_chsuicidessg_dt='$matchdate'
              WHERE wp_num='$num' LIMIT 1"
                        );

                        if (!$result) {
                            echo "Error saving weapon single game suicide highs.{$break}\n";

                            exit;
                        }
                    }

                    // Weapon Single Game Held Death Highs

                    if (isset($wtheld[$num][$i]) && $wtheld[$num][$i] > $weapsg[$wpn]['wp_chdeathshldsg']) {
                        $weapsg[$wpn]['wp_chdeathshldsg'] = $wtheld[$num][$i];

                        $result = sqlqueryn(
                            "UPDATE $ut_weapons SET
              wp_chdeathshldsg='{$wtheld[$num][$i]}',
              wp_chdeathshldsg_plr='$pnum',
              wp_chdeathshldsg_tm='$length',
              wp_chdeathshldsg_map='$map',
              wp_chdeathshldsg_dt='$matchdate'
              WHERE wp_num='$num' LIMIT 1"
                        );

                        if (!$result) {
                            echo "Error saving weapon single game held death highs.{$break}\n";

                            exit;
                        }
                    }
                }
            }
        }

        // Save Pickups Data

        for ($itm = 1; $itm <= $maxpickups; $itm++) {
            // Save for each player into game and player by type

            for ($i = 0; $i <= $maxplayer; $i++) {
                if (isset($player[$i]) && '' != $player[$i]['p_name']) {
                    $num = $pickups[$i][$itm] ?? 0;

                    if ($num) {
                        // Save Game Pickups by Player

                        $result = sqlqueryn(
                            "INSERT INTO $ut_gitems VALUES (
              '$gamenum',
              '$itm',
              '$i',
              '$num')"
                        );

                        if (!$result) {
                            echo "Error saving gitems data.{$break}\n";

                            exit;
                        }

                        // Save Player Pickup Totals

                        $pnum = $player[$i]['p_num'];

                        $result = sqlqueryn("SELECT pi_pickups FROM $ut_pitems WHERE pi_plr='$pnum' && pi_item='$itm' LIMIT 1");

                        if (!$result) {
                            echo "Error reading pitems data.{$break}\n";

                            exit;
                        }

                        if ($row = $GLOBALS['xoopsDB']->fetchBoth($result)) {
                            $result = sqlqueryn("UPDATE $ut_pitems SET pi_pickups=pi_pickups+'$num' WHERE pi_plr='$pnum' && pi_item='$itm' LIMIT 1");

                            if (!$result) {
                                echo "Error updating pitems data.{$break}\n";

                                exit;
                            }
                        } else {
                            $result = sqlqueryn("INSERT INTO $ut_pitems VALUES ('$pnum','$itm','$num')");

                            if (!$result) {
                                echo "Error inserting pitems data.{$break}\n";

                                exit;
                            }
                        }

                        // Save Item Totals

                        $result = sqlqueryn("UPDATE $ut_items SET it_pickups=it_pickups+'$num' WHERE it_num='$itm' LIMIT 1");

                        if (!$result) {
                            echo "Error updating items data.{$break}\n";

                            exit;
                        }
                    }
                }
            }
        }

        // Save Events Data

        for ($i = 0; $i < $numevents; $i++) {
            [$geplr, $gevent, $getime, $gelength, $gequant, $gereason, $geopponent, $geitem] = $events[$i];

            $result = sqlqueryn(
                "INSERT INTO $ut_gevents VALUES (
        '$gamenum',
        '$geplr',
        '$gevent',
        '$getime',
        '$gelength',
        '$gequant',
        '$gereason',
        '$geopponent',
        '$geitem')"
            );

            if (!$result) {
                echo "Error saving events data.{$break}\n";

                exit;
            }
        }

        // Save Team Data

        if ($tkcount > 0) {
            for ($i = 0; $i < $tkcount; $i++) {
                [$tnum, $tscore, $ttime] = $tkills[$i];

                $result = sqlqueryn(
                    "INSERT INTO $ut_tkills VALUES (
          '$gamenum',
          '$tnum',
          '$tscore',
          '$ttime')"
                );

                if (!$result) {
                    echo "Error saving tkills data.{$break}\n";

                    exit;
                }
            }
        }

        // Update Game Type Data

        $result = sqlqueryn(
            "UPDATE $ut_type SET
      tp_played=tp_played+1,
      tp_gtime=tp_gtime+'$length',
      tp_ptime=tp_ptime+'$tot_ptime',
      tp_score=tp_score+'$tot_score',
      tp_kills=tp_kills+'$tot_kills',
      tp_deaths=tp_deaths+'$tot_deaths',
      tp_suicides=tp_suicides+'$tot_suicides',
      tp_teamkills=tp_teamkills+'$teamkills'
      WHERE tp_num='$gametnum' LIMIT 1"
        );

        if (!$result) {
            echo "Error saving tkills data.{$break}\n";

            exit;
        }

        // Save Chatlog

        for ($i = 0; $i < $numchat; $i++) {
            [$gcplr, $gcteam, $gctime, $gctext] = $chatlog[$i];

            $gctext = addslashes($gctext);

            $result = sqlqueryn(
                "INSERT INTO $ut_gchat VALUES (
        '$gamenum',
        '$gcplr',
        '$gcteam',
        '$gctime',
        '$gctext')"
            );

            if (!$result) {
                echo "Error saving chat data.{$break}\n";

                exit;
            }
        }

        $status = $gamenum;
    } else {
        $status = 0;
    }

    return $status;
}
