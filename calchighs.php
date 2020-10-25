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

if (preg_match('/calchighs.php/i', $_SERVER['PHP_SELF'])) {
    echo "Access denied.\n";

    die();
}

// Load Totals
$link = sqlquery_connect();
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

// Clear fph total
$tl_chfph = $tl_chfph_plr = $tl_chfph_gms = $tl_chfph_tm = 0;

// Load Weapons Data (partial)
$result = sqlqueryn(
    "SELECT
  wp_num,
  wp_chkills,
  wp_chkills_plr,
  wp_chkills_gms,
  wp_chkills_tm,
  wp_chdeaths,
  wp_chdeaths_plr,
  wp_chdeaths_gms,
  wp_chdeaths_tm,
  wp_chdeathshld,
  wp_chdeathshld_plr,
  wp_chdeathshld_gms,
  wp_chdeathshld_tm,
  wp_chsuicides,
  wp_chsuicides_plr,
  wp_chsuicides_gms,
  wp_chsuicides_tm
  FROM $ut_weapons"
);
if (!$result) {
    echo "Error loading weapons data.<br>\n";

    exit;
}
$numweapons = 0;
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    $weapons[$row['wp_num']] = $row;

    $numweapons++;
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

// Read Players and update totals
$result = sqlqueryn("SELECT * FROM $ut_players WHERE plr_bot=0");
while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($result))) {
    while (list($key, $val) = each($row)) {
        ${$key} = $val;
    }

    $plrgames = $dm_games + $tdm_games + $dd_games + $ctf_games + $br_games + $other_games;

    $plrtime = $dm_time + $tdm_time + $dd_time + $ctf_time + $br_time + $other_time;

    // Include only players who've met the minimum game and time requirements

    // set in the config.inc.php file.

    if ($plrgames >= $minchgames && $plrtime >= $minchtime) {
        if ($plr_frags > $tl_chfrags) {
            $tl_chfrags = $plr_frags;

            $tl_chfrags_plr = $pnum;

            $tl_chfrags_gms = $plrgames;

            $tl_chfrags_tm = $plrtime;
        }

        if ($plr_kills > $tl_chkills) {
            $tl_chkills = $plr_kills;

            $tl_chkills_plr = $pnum;

            $tl_chkills_gms = $plrgames;

            $tl_chkills_tm = $plrtime;
        }

        if ($plr_deaths > $tl_chdeaths) {
            $tl_chdeaths = $plr_deaths;

            $tl_chdeaths_plr = $pnum;

            $tl_chdeaths_gms = $plrgames;

            $tl_chdeaths_tm = $plrtime;
        }

        if ($plr_suicides > $tl_chsuicides) {
            $tl_chsuicides = $plr_suicides;

            $tl_chsuicides_plr = $pnum;

            $tl_chsuicides_gms = $plrgames;

            $tl_chsuicides_tm = $plrtime;
        }

        if ($plr_headshots > $tl_chheadshots) {
            $tl_chheadshots = $plr_headshots;

            $tl_chheadshots_plr = $pnum;

            $tl_chheadshots_gms = $plrgames;

            $tl_chheadshots_tm = $plrtime;
        }

        if ($plr_firstblood > $tl_chfirstblood) {
            $tl_chfirstblood = $plr_firstblood;

            $tl_chfirstblood_plr = $pnum;

            $tl_chfirstblood_gms = $plrgames;

            $tl_chfirstblood_tm = $plrtime;
        }

        if ($plr_multi1 > $tl_chmulti1) {
            $tl_chmulti1 = $plr_multi1;

            $tl_chmulti1_plr = $pnum;

            $tl_chmulti1_gms = $plrgames;

            $tl_chmulti1_tm = $plrtime;
        }

        if ($plr_multi2 > $tl_chmulti2) {
            $tl_chmulti2 = $plr_multi2;

            $tl_chmulti2_plr = $pnum;

            $tl_chmulti2_gms = $plrgames;

            $tl_chmulti2_tm = $plrtime;
        }

        if ($plr_multi3 > $tl_chmulti3) {
            $tl_chmulti3 = $plr_multi3;

            $tl_chmulti3_plr = $pnum;

            $tl_chmulti3_gms = $plrgames;

            $tl_chmulti3_tm = $plrtime;
        }

        if ($plr_multi4 > $tl_chmulti4) {
            $tl_chmulti4 = $plr_multi4;

            $tl_chmulti4_plr = $pnum;

            $tl_chmulti4_gms = $plrgames;

            $tl_chmulti4_tm = $plrtime;
        }

        if ($plr_multi5 > $tl_chmulti5) {
            $tl_chmulti5 = $plr_multi5;

            $tl_chmulti5_plr = $pnum;

            $tl_chmulti5_gms = $plrgames;

            $tl_chmulti5_tm = $plrtime;
        }

        if ($plr_multi6 > $tl_chmulti6) {
            $tl_chmulti6 = $plr_multi6;

            $tl_chmulti6_plr = $pnum;

            $tl_chmulti6_gms = $plrgames;

            $tl_chmulti6_tm = $plrtime;
        }

        if ($plr_multi7 > $tl_chmulti7) {
            $tl_chmulti7 = $plr_multi7;

            $tl_chmulti7_plr = $pnum;

            $tl_chmulti7_gms = $plrgames;

            $tl_chmulti7_tm = $plrtime;
        }

        if ($plr_spree1 > $tl_chspree1) {
            $tl_chspree1 = $plr_spree1;

            $tl_chspree1_plr = $pnum;

            $tl_chspree1_gms = $plrgames;

            $tl_chspree1_tm = $plrtime;
        }

        if ($plr_spree2 > $tl_chspree2) {
            $tl_chspree2 = $plr_spree2;

            $tl_chspree2_plr = $pnum;

            $tl_chspree2_gms = $plrgames;

            $tl_chspree2_tm = $plrtime;
        }

        if ($plr_spree3 > $tl_chspree3) {
            $tl_chspree3 = $plr_spree3;

            $tl_chspree3_plr = $pnum;

            $tl_chspree3_gms = $plrgames;

            $tl_chspree3_tm = $plrtime;
        }

        if ($plr_spree4 > $tl_chspree4) {
            $tl_chspree4 = $plr_spree4;

            $tl_chspree4_plr = $pnum;

            $tl_chspree4_gms = $plrgames;

            $tl_chspree4_tm = $plrtime;
        }

        if ($plr_spree5 > $tl_chspree5) {
            $tl_chspree5 = $plr_spree5;

            $tl_chspree5_plr = $pnum;

            $tl_chspree5_gms = $plrgames;

            $tl_chspree5_tm = $plrtime;
        }

        if ($plr_spree6 > $tl_chspree6) {
            $tl_chspree6 = $plr_spree6;

            $tl_chspree6_plr = $pnum;

            $tl_chspree6_gms = $plrgames;

            $tl_chspree6_tm = $plrtime;
        }

        if (0 == $plrtime) {
            $plrfph = '0.0';
        } else {
            $plrfph = round($plr_frags * (3600 / $plrtime), 1);
        }

        if ($plrfph > $tl_chfph) {
            $tl_chfph = $plrfph;

            $tl_chfph_plr = $pnum;

            $tl_chfph_gms = $plrgames;

            $tl_chfph_tm = $plrtime;
        }

        if ($ctf_flagcapture > $tl_chflagcapture) {
            $tl_chflagcapture = $ctf_flagcapture;

            $tl_chflagcapture_plr = $pnum;

            $tl_chflagcapture_gms = $plrgames;

            $tl_chflagcapture_tm = $plrtime;
        }

        if ($ctf_flagreturn > $tl_chflagreturn) {
            $tl_chflagreturn = $ctf_flagreturn;

            $tl_chflagreturn_plr = $pnum;

            $tl_chflagreturn_gms = $plrgames;

            $tl_chflagreturn_tm = $plrtime;
        }

        if ($ctf_flagkill > $tl_chflagkill) {
            $tl_chflagkill = $ctf_flagkill;

            $tl_chflagkill_plr = $pnum;

            $tl_chflagkill_gms = $plrgames;

            $tl_chflagkill_tm = $plrtime;
        }

        if ($dd_cpcapture > $tl_chcpcapture) {
            $tl_chcpcapture = $dd_cpcapture;

            $tl_chcpcapture_plr = $pnum;

            $tl_chcpcapture_gms = $plrgames;

            $tl_chcpcapture_tm = $plrtime;
        }

        if ($br_bombcarried > $tl_chbombcarried) {
            $tl_chbombcarried = $br_bombcarried;

            $tl_chbombcarried_plr = $pnum;

            $tl_chbombcarried_gms = $plrgames;

            $tl_chbombcarried_tm = $plrtime;
        }

        if ($br_bombtossed > $tl_chbombtossed) {
            $tl_chbombtossed = $br_bombtossed;

            $tl_chbombtossed_plr = $pnum;

            $tl_chbombtossed_gms = $plrgames;

            $tl_chbombtossed_tm = $plrtime;
        }

        if ($br_bombkill > $tl_chbombkill) {
            $tl_chbombkill = $br_bombkill;

            $tl_chbombkill_plr = $pnum;

            $tl_chbombkill_gms = $plrgames;

            $tl_chbombkill_tm = $plrtime;
        }

        $plrwins = $dm_wins1 + $tdm_wins + $dd_wins + $ctf_wins + $br_wins;

        if ($dm_wins1 > $tl_chwins) {
            $tl_chwins = $plrwins;

            $tl_chwins_plr = $pnum;

            $tl_chwins_gms = $plrgames;

            $tl_chwins_tm = $plrtime;
        }

        $teamwins = $tdm_wins + $dd_wins + $ctf_wins + $br_wins;

        if ($teamwins > $tl_chteamwins) {
            $tl_chteamwins = $teamwins;

            $tl_chteamwins_plr = $pnum;

            $tl_chteamwins_gms = $plrgames;

            $tl_chteamwins_tm = $plrtime;
        }

        // Load Player Weapon Kills for current player

        $numpwk = 0;

        $pwkresult = sqlqueryn("SELECT * FROM $ut_pwkills WHERE pwk_player='$pnum'");

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($pwkresult))) {
            $pwk[$numpwk++] = $row;
        }

        $GLOBALS['xoopsDB']->freeRecordSet($pwkresult);

        // Weapon Specific Career Highs

        for ($i = 0; $i < $numpwk; $i++) {
            $pwkweapon = $pwk[$i]['pwk_weapon'];

            $pwkkills = $pwk[$i]['pwk_kills'];

            $pwkdeaths = $pwk[$i]['pwk_deaths'];

            $pwkheld = $pwk[$i]['pwk_held'];

            $pwksuicides = $pwk[$i]['pwk_suicides'];

            if ($pwkkills > $weapons[$pwkweapon]['wp_chkills']) {
                $weapons[$pwkweapon]['wp_chkills'] = $pwkkills;

                $wpresult = sqlqueryn(
                    "UPDATE $ut_weapons SET
          wp_chkills='$pwkkills',
          wp_chkills_plr='$pnum',
          wp_chkills_gms='$plrgames',
          wp_chkills_tm='$plrtime'
          WHERE wp_num='$pwkweapon' LIMIT 1"
                );

                if (!$wpresult) {
                    echo "Error updating weapon entry [1].<br>\n";

                    exit;
                }
            }

            if ($pwkdeaths > $weapons[$pwkweapon]['wp_chdeaths']) {
                $weapons[$pwkweapon]['wp_chdeaths'] = $pwkdeaths;

                $wpresult = sqlqueryn(
                    "UPDATE $ut_weapons SET
          wp_chdeaths='$pwkdeaths',
          wp_chdeaths_plr='$pnum',
          wp_chdeaths_gms='$plrgames',
          wp_chdeaths_tm='$plrtime'
          WHERE wp_num='$pwkweapon' LIMIT 1"
                );

                if (!$wpresult) {
                    echo "Error updating weapon entry [2].<br>\n";

                    exit;
                }
            }

            if ($pwkheld > $weapons[$pwkweapon]['wp_chdeathshld']) {
                $weapons[$pwkweapon]['wp_chdeathshld'] = $pwkheld;

                $wpresult = sqlqueryn(
                    "UPDATE $ut_weapons SET
          wp_chdeathshld='$pwkheld',
          wp_chdeathshld_plr='$pnum',
          wp_chdeathshld_gms='$plrgames',
          wp_chdeathshld_tm='$plrtime'
          WHERE wp_num='$pwkweapon' LIMIT 1"
                );

                if (!$wpresult) {
                    echo "Error updating weapon entry [3].<br>\n";

                    exit;
                }
            }

            if ($pwksuicides > $weapons[$pwkweapon]['wp_chsuicides']) {
                $weapons[$pwkweapon]['wp_chsuicides'] = $pwksuicides;

                $wpresult = sqlqueryn(
                    "UPDATE $ut_weapons SET
          wp_chsuicides='$pwksuicides',
          wp_chsuicides_plr='$pnum',
          wp_chsuicides_gms='$plrgames',
          wp_chsuicides_tm='$plrtime'
          WHERE wp_num='$pwkweapon' LIMIT 1"
                );

                if (!$wpresult) {
                    echo "Error updating weapon entry [4].<br>\n";

                    exit;
                }
            }
        }
    }
}
$GLOBALS['xoopsDB']->freeRecordSet($result);

// Save Totals
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
    echo "Error saving totals data.<br>\n";

    exit;
}

$GLOBALS['xoopsDB']->close($link);
