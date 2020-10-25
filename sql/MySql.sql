CREATE TABLE ut_players (
    pnum             MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    plr_name         VARCHAR(30)           NOT NULL DEFAULT '',
    plr_bot          TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    plr_frags        MEDIUMINT(9)          NOT NULL DEFAULT '0',
    plr_score        MEDIUMINT(9)          NOT NULL DEFAULT '0',
    plr_kills        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_deaths       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_suicides     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_headshots    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_firstblood   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_transgib     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_user         VARCHAR(35)           NOT NULL DEFAULT '',
    plr_id           VARCHAR(32)           NOT NULL DEFAULT '',
    plr_key          VARCHAR(32)           NOT NULL DEFAULT '',
    dm_score         MEDIUMINT(8)          NOT NULL DEFAULT '0',
    dm_frags         MEDIUMINT(9)          NOT NULL DEFAULT '0',
    dm_kills         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dm_deaths        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dm_suicides      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dm_wins1         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dm_wins2         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dm_wins3         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dm_losses        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dm_games         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dm_time          INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tdm_score        MEDIUMINT(8)          NOT NULL DEFAULT '0',
    tdm_frags        MEDIUMINT(8)          NOT NULL DEFAULT '0',
    tdm_kills        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tdm_deaths       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tdm_suicides     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tdm_teamkills    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tdm_teamdeaths   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tdm_wins         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tdm_losses       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tdm_games        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tdm_time         INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    dd_score         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dd_frags         MEDIUMINT(8)          NOT NULL DEFAULT '0',
    dd_kills         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dd_deaths        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dd_suicides      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dd_teamkills     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dd_teamdeaths    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dd_wins          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dd_losses        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dd_games         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    dd_time          INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    dd_cpcapture     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_score        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_frags        MEDIUMINT(8)          NOT NULL DEFAULT '0',
    ctf_kills        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_deaths       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_suicides     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_teamkills    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_teamdeaths   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_wins         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_losses       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_games        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_time         INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    ctf_flagcapture  MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_flagdrop     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_flagpickup   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_flagreturn   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_flagtaken    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_flagkill     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ctf_flagassist   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_score         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_frags         MEDIUMINT(8)          NOT NULL DEFAULT '0',
    br_kills         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_deaths        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_suicides      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_teamkills     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_teamdeaths    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_wins          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_losses        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_games         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_time          INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    br_bombcarried   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_bombtossed    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_bombdrop      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_bombpickup    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_bombtaken     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_bombkill      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    br_bombassist    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    mu_score         MEDIUMINT(8)          NOT NULL DEFAULT '0',
    mu_frags         MEDIUMINT(9)          NOT NULL DEFAULT '0',
    mu_kills         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    mu_deaths        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    mu_suicides      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    mu_wins1         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    mu_wins2         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    mu_wins3         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    mu_losses        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    mu_games         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    mu_time          INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    in_score         MEDIUMINT(8)          NOT NULL DEFAULT '0',
    in_frags         MEDIUMINT(9)          NOT NULL DEFAULT '0',
    in_kills         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    in_deaths        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    in_suicides      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    in_teamkills     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    in_teamdeaths    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    in_wins1         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    in_wins2         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    in_wins3         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    in_losses        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    in_games         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    in_time          INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    lm_score         MEDIUMINT(8)          NOT NULL DEFAULT '0',
    lm_frags         MEDIUMINT(9)          NOT NULL DEFAULT '0',
    lm_kills         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    lm_deaths        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    lm_suicides      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    lm_wins          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    lm_losses        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    lm_games         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    lm_time          INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    other_score      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    other_frags      MEDIUMINT(8)          NOT NULL DEFAULT '0',
    other_kills      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    other_deaths     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    other_suicides   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    other_teamkills  MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    other_teamdeaths MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    other_wins       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    other_losses     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    other_games      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    other_time       INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    plr_multi1       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_multi2       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_multi3       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_multi4       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_multi5       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_multi6       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_multi7       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spree1       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spreet1      INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    plr_spreek1      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spree2       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spreet2      INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    plr_spreek2      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spree3       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spreet3      INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    plr_spreek3      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spree4       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spreet4      INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    plr_spreek4      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spree5       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spreet5      INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    plr_spreek5      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spree6       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_spreet6      INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    plr_spreek6      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_combo1       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_combo2       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_combo3       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    plr_combo4       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    UNIQUE KEY pnum (pnum),
    KEY plr_usrid (plr_user, plr_id)
);

CREATE TABLE ut_games (
    gm_num          INT(10) UNSIGNED       NOT NULL AUTO_INCREMENT,
    gm_server       VARCHAR(35)            NOT NULL DEFAULT '',
    gm_admin        VARCHAR(35)            NOT NULL DEFAULT '',
    gm_email        VARCHAR(35)            NOT NULL DEFAULT '',
    gm_map          VARCHAR(30)            NOT NULL DEFAULT '',
    gm_type         TINYINT(3) UNSIGNED    NOT NULL DEFAULT '0',
    gm_start        DATETIME               NOT NULL DEFAULT '0000-00-00 00:00:00',
    gm_mutators     VARCHAR(120)                    DEFAULT NULL,
    gm_fraglimit    TINYINT(3) UNSIGNED    NOT NULL DEFAULT '0',
    gm_timelimit    TINYINT(3) UNSIGNED    NOT NULL DEFAULT '0',
    gm_minplayers   TINYINT(3) UNSIGNED    NOT NULL DEFAULT '0',
    gm_translocator TINYINT(3) UNSIGNED    NOT NULL DEFAULT '0',
    gm_starttime    MEDIUMINT(10) UNSIGNED NOT NULL DEFAULT '0',
    gm_length       MEDIUMINT(10) UNSIGNED NOT NULL DEFAULT '0',
    gm_numplayers   SMALLINT(5) UNSIGNED   NOT NULL DEFAULT '0',
    gm_kills        SMALLINT(5) UNSIGNED   NOT NULL DEFAULT '0',
    gm_deaths       SMALLINT(5) UNSIGNED   NOT NULL DEFAULT '0',
    gm_suicides     SMALLINT(5) UNSIGNED   NOT NULL DEFAULT '0',
    gm_t0score      SMALLINT(6)            NOT NULL DEFAULT '0',
    gm_t1score      SMALLINT(6)            NOT NULL DEFAULT '0',
    gm_firstblood   SMALLINT(6)            NOT NULL DEFAULT '-1',
    gm_headshots    SMALLINT(5) UNSIGNED   NOT NULL DEFAULT '0',
    UNIQUE KEY gm_gnum (gm_num)
);

CREATE TABLE ut_totals (
    tl_totals               CHAR(6)               NOT NULL DEFAULT 'Totals',
    tl_score                MEDIUMINT(9)          NOT NULL DEFAULT '0',
    tl_kills                MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_deaths               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_suicides             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_teamkills            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_teamdeaths           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_players              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_games                MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_time                 INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tl_gametime             INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tl_playertime           INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tl_cpcapture            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_flagcapture          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_flagdrop             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_flagpickup           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_flagreturn           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_flagtaken            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_flagkill             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_flagassist           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_bombcarried          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_bombtossed           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_bombdrop             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_bombpickup           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_bombtaken            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_bombkill             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_bombassist           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spkills              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spdeaths             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spsuicides           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spteamkills          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spteamdeaths         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spgames              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_sptime               INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tl_headshots            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_multi1               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_multi2               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_multi3               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_multi4               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_multi5               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_multi6               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_multi7               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spree1               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spreet1              INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tl_spreek1              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spree2               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spreet2              INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tl_spreek2              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spree3               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spreet3              INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tl_spreek3              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spree4               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spreet4              INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tl_spreek4              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spree5               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spreet5              INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tl_spreek5              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spree6               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_spreet6              INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tl_spreek6              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_combo1               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_combo2               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_combo3               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_combo4               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_transgib             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfrags              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfrags_plr          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfrags_gms          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfrags_tm           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chkills              MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chkills_plr          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chkills_gms          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chkills_tm           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chdeaths             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chdeaths_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chdeaths_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chdeaths_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chsuicides           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chsuicides_plr       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chsuicides_gms       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chsuicides_tm        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfirstblood         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfirstblood_plr     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfirstblood_gms     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfirstblood_tm      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chheadshots          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chheadshots_plr      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chheadshots_gms      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chheadshots_tm       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti1             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti1_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti1_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti1_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti2             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti2_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti2_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti2_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti3             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti3_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti3_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti3_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti4             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti4_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti4_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti4_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti5             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti5_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti5_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti5_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti6             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti6_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti6_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti6_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti7             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti7_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti7_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chmulti7_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree1             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree1_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree1_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree1_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree2             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree2_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree2_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree2_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree3             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree3_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree3_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree3_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree4             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree4_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree4_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree4_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree5             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree5_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree5_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree5_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree6             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree6_plr         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree6_gms         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chspree6_tm          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfph                FLOAT UNSIGNED        NOT NULL DEFAULT '0',
    tl_chfph_plr            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfph_gms            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfph_tm             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chcpcapture          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chcpcapture_plr      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chcpcapture_gms      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chcpcapture_tm       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagcapture        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagcapture_plr    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagcapture_gms    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagcapture_tm     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagreturn         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagreturn_plr     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagreturn_gms     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagreturn_tm      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagkill           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagkill_plr       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagkill_gms       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagkill_tm        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombcarried        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombcarried_plr    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombcarried_gms    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombcarried_tm     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombtossed         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombtossed_plr     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombtossed_gms     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombtossed_tm      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombkill           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombkill_plr       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombkill_gms       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombkill_tm        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chwins               MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chwins_plr           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chwins_gms           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chwins_tm            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chteamwins           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chteamwins_plr       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chteamwins_gms       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chteamwins_tm        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfragssg            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfragssg_plr        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfragssg_tm         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chfragssg_map        VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chfragssg_date       DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    tl_chkillssg            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chkillssg_plr        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chkillssg_tm         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chkillssg_map        VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chkillssg_date       DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    tl_chdeathssg           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chdeathssg_plr       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chdeathssg_tm        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chdeathssg_map       VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chdeathssg_date      DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    tl_chsuicidessg         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chsuicidessg_plr     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chsuicidessg_tm      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chsuicidessg_map     VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chsuicidessg_date    DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    tl_chcpcapturesg        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chcpcapturesg_plr    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chcpcapturesg_tm     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chcpcapturesg_map    VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chcpcapturesg_date   DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    tl_chflagcapturesg      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagcapturesg_plr  MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagcapturesg_tm   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagcapturesg_map  VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chflagcapturesg_date DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    tl_chflagreturnsg       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagreturnsg_plr   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagreturnsg_tm    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagreturnsg_map   VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chflagreturnsg_date  DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    tl_chflagkillsg         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagkillsg_plr     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagkillsg_tm      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chflagkillsg_map     VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chflagkillsg_date    DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    tl_chbombcarriedsg      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombcarriedsg_plr  MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombcarriedsg_tm   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombcarriedsg_map  VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chbombcarriedsg_date DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    tl_chbombtossedsg       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombtossedsg_plr   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombtossedsg_tm    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombtossedsg_map   VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chbombtossedsg_date  DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    tl_chbombkillsg         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombkillsg_plr     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombkillsg_tm      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tl_chbombkillsg_map     VARCHAR(30)           NOT NULL DEFAULT '',
    tl_chbombkillsg_date    DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    UNIQUE KEY tl_tot (tl_totals)
);

INSERT INTO ut_totals
   SET tl_totals='Totals';

CREATE TABLE ut_gplayers (
    gp_game       INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    gp_num        SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_bot        TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    gp_pnum       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    gp_t0score    SMALLINT(6)           NOT NULL DEFAULT '0',
    gp_t1score    SMALLINT(6)           NOT NULL DEFAULT '0',
    gp_kills      SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_deaths     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_suicides   SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_time       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    gp_headshots  SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_firstblood TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    gp_teamkills  SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_teamdeaths SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_capcarry   SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_tossed     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_drop       SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_pickup     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_return     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_taken      SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_typekill   SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_assist     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_multi1     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_multi2     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_multi3     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_multi4     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_multi5     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_multi6     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_multi7     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_spree1     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_spree2     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_spree3     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_spree4     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_spree5     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_spree6     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_combo1     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_combo2     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_combo3     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_combo4     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_transgib   SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    gp_rank       TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    gp_team       TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    KEY gp_gnum (gp_game)
);

CREATE TABLE ut_gkills (
    gk_game    INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    gk_killer  SMALLINT(6)           NOT NULL DEFAULT '0',
    gk_victim  SMALLINT(6)           NOT NULL DEFAULT '0',
    gk_time    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    gk_kweapon TINYINT(3)            NOT NULL DEFAULT '0',
    gk_vweapon TINYINT(3)            NOT NULL DEFAULT '0',
    gk_kteam   TINYINT(4)            NOT NULL DEFAULT '0',
    gk_vteam   TINYINT(4)            NOT NULL DEFAULT '0',
    KEY gk_gnum (gk_game)
);

CREATE TABLE ut_gscores (
    gs_game   INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    gs_player SMALLINT(6)           NOT NULL DEFAULT '0',
    gs_time   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    gs_score  SMALLINT(6)           NOT NULL DEFAULT '0',
    gs_team   TINYINT(4)            NOT NULL DEFAULT '0',
    KEY gs_gnum (gs_game)
);

CREATE TABLE ut_tkills (
    tk_game  INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tk_team  TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    tk_score SMALLINT(6)           NOT NULL DEFAULT '0',
    tk_time  MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    KEY tk_gnumteam (tk_game, tk_team)
);

CREATE TABLE ut_gevents (
    ge_game     INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    ge_plr      SMALLINT(6)           NOT NULL DEFAULT '0',
    ge_event    TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    ge_time     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ge_length   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    ge_quant    MEDIUMINT(9)          NOT NULL DEFAULT '0',
    ge_reason   TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    ge_opponent SMALLINT(6)           NOT NULL DEFAULT '0',
    ge_item     SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    KEY ge_gnumev (ge_game, ge_event)
);

CREATE TABLE ut_pwkills (
    pwk_num      MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
    pwk_player   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    pwk_weapon   SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    pwk_frags    MEDIUMINT(8)          NOT NULL DEFAULT '0',
    pwk_kills    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    pwk_deaths   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    pwk_held     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    pwk_suicides MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    UNIQUE KEY pwk_num (pwk_num),
    KEY pwk_plrwp (pwk_player, pwk_weapon)
);

CREATE TABLE ut_type (
    tp_num       SMALLINT(5) UNSIGNED  NOT NULL AUTO_INCREMENT,
    tp_desc      VARCHAR(30)           NOT NULL DEFAULT '',
    tp_type      TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    tp_played    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tp_gtime     INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tp_ptime     INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tp_score     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tp_kills     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tp_deaths    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tp_suicides  MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    tp_teamkills MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    UNIQUE KEY tp_tnum (tp_num)
);

INSERT INTO ut_type
   SET tp_desc='Deathmatch', tp_type='1';
INSERT INTO ut_type
   SET tp_desc='Capture the Flag', tp_type='2';
INSERT INTO ut_type
   SET tp_desc='Bombing Run', tp_type='3';
INSERT INTO ut_type
   SET tp_desc='Team Deathmatch', tp_type='4';
INSERT INTO ut_type
   SET tp_desc='Double Domination', tp_type='5';
INSERT INTO ut_type
   SET tp_desc='Mutant', tp_type='6';
INSERT INTO ut_type
   SET tp_desc='Invasion', tp_type='7';
INSERT INTO ut_type
   SET tp_desc='Last Man Standing', tp_type='8';
INSERT INTO ut_type
   SET tp_desc='Log Deathmatch', tp_type='1';
INSERT INTO ut_type
   SET tp_desc='Log Capture the Flag', tp_type='2';
INSERT INTO ut_type
   SET tp_desc='Log Bombing Run', tp_type='3';
INSERT INTO ut_type
   SET tp_desc='Log Team Deathmatch', tp_type='4';
INSERT INTO ut_type
   SET tp_desc='Log Double Domination', tp_type='5';
INSERT INTO ut_type
   SET tp_desc='Log Mutant', tp_type='6';
INSERT INTO ut_type
   SET tp_desc='Log Invasion', tp_type='7';
INSERT INTO ut_type
   SET tp_desc='Log Last Man Standing', tp_type='8';

CREATE TABLE ut_weapons (
    wp_num               SMALLINT(5) UNSIGNED  NOT NULL AUTO_INCREMENT,
    wp_type              VARCHAR(30)           NOT NULL DEFAULT '',
    wp_desc              VARCHAR(30)           NOT NULL DEFAULT '',
    wp_secondary         TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    wp_frags             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_kills             MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_deaths            MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_suicides          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_nwsuicides        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chkills           MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chkills_plr       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chkills_gms       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chkills_tm        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeaths          MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeaths_plr      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeaths_gms      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeaths_tm       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeathshld       MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeathshld_plr   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeathshld_gms   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeathshld_tm    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chsuicides        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chsuicides_plr    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chsuicides_gms    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chsuicides_tm     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chkillssg         MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chkillssg_plr     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chkillssg_tm      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chkillssg_map     VARCHAR(30)           NOT NULL DEFAULT '',
    wp_chkillssg_dt      DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    wp_chdeathssg        MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeathssg_plr    MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeathssg_tm     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeathssg_map    VARCHAR(30)           NOT NULL DEFAULT '',
    wp_chdeathssg_dt     DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    wp_chdeathshldsg     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeathshldsg_plr MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeathshldsg_tm  MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chdeathshldsg_map VARCHAR(30)           NOT NULL DEFAULT '',
    wp_chdeathshldsg_dt  DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    wp_chsuicidessg      MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chsuicidessg_plr  MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chsuicidessg_tm   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    wp_chsuicidessg_map  VARCHAR(30)           NOT NULL DEFAULT '',
    wp_chsuicidessg_dt   DATETIME              NOT NULL DEFAULT '0000-00-00 00:00:00',
    UNIQUE KEY wp_num (wp_num),
    KEY wp_type (wp_type)
);

INSERT INTO ut_weapons
   SET wp_type='None', wp_desc='None';
INSERT INTO ut_weapons
   SET wp_type='DamTypeTeleFrag', wp_desc='Telefrag';
INSERT INTO ut_weapons
   SET wp_type='DamTypeTelefragged', wp_desc='Telefragged';
INSERT INTO ut_weapons
   SET wp_type='TransLauncher', wp_desc='Translocator Launcher';
INSERT INTO ut_weapons
   SET wp_type='DamTypeShieldImpact', wp_desc='Shield Gun';
INSERT INTO ut_weapons
   SET wp_type='ShieldGun', wp_desc='Shield Gun';
INSERT INTO ut_weapons
   SET wp_type='DamTypeAssaultBullet', wp_desc='Assault Rifle';
INSERT INTO ut_weapons
   SET wp_type='DamTypeAssaultGrenade', wp_desc='Assault Rifle', wp_secondary='1';
INSERT INTO ut_weapons
   SET wp_type='AssaultRifle', wp_desc='Assault Rifle';
INSERT INTO ut_weapons
   SET wp_type='DamTypeLinkPlasma', wp_desc='Link Gun';
INSERT INTO ut_weapons
   SET wp_type='DamTypeLinkShaft', wp_desc='Link Gun', wp_secondary='1';
INSERT INTO ut_weapons
   SET wp_type='LinkGun', wp_desc='Link Gun';
INSERT INTO ut_weapons
   SET wp_type='DamTypeShockBeam', wp_desc='Shock Rifle';
INSERT INTO ut_weapons
   SET wp_type='DamTypeShockBall', wp_desc='Shock Rifle', wp_secondary='1';
INSERT INTO ut_weapons
   SET wp_type='DamTypeShockCombo', wp_desc='Shock Rifle', wp_secondary='2';
INSERT INTO ut_weapons
   SET wp_type='ShockRifle', wp_desc='Shock Rifle';
INSERT INTO ut_weapons
   SET wp_type='DamTypeBioGlob', wp_desc='Bio Rifle';
INSERT INTO ut_weapons
   SET wp_type='BioRifle', wp_desc='Bio Rifle';
INSERT INTO ut_weapons
   SET wp_type='DamTypeMinigunBullet', wp_desc='Minigun';
INSERT INTO ut_weapons
   SET wp_type='DamTypeMinigunAlt', wp_desc='Minigun', wp_secondary='1';
INSERT INTO ut_weapons
   SET wp_type='Minigun', wp_desc='Minigun';
INSERT INTO ut_weapons
   SET wp_type='DamTypeFlakChunk', wp_desc='Flak Cannon';
INSERT INTO ut_weapons
   SET wp_type='DamTypeFlakShell', wp_desc='Flak Cannon', wp_secondary='1';
INSERT INTO ut_weapons
   SET wp_type='FlakCannon', wp_desc='Flak Cannon';
INSERT INTO ut_weapons
   SET wp_type='DamTypeRocket', wp_desc='Rocket Launcher';
INSERT INTO ut_weapons
   SET wp_type='DamTypeRocketHoming', wp_desc='Rocket Launcher', wp_secondary='1';
INSERT INTO ut_weapons
   SET wp_type='RocketLauncher', wp_desc='Rocket Launcher';
INSERT INTO ut_weapons
   SET wp_type='DamTypeSniperShot', wp_desc='Lightning Gun';
INSERT INTO ut_weapons
   SET wp_type='DamTypeSniperHeadShot', wp_desc='Lightning Gun';
INSERT INTO ut_weapons
   SET wp_type='LightningGun', wp_desc='Lightning Gun';
INSERT INTO ut_weapons
   SET wp_type='SniperRifle', wp_desc='Lightning Gun';
INSERT INTO ut_weapons
   SET wp_type='DamTypeRedeemer', wp_desc='Redeemer';
INSERT INTO ut_weapons
   SET wp_type='Redeemer', wp_desc='Redeemer';
INSERT INTO ut_weapons
   SET wp_type='DamTypeIonBlast', wp_desc='Ion Cannon';
INSERT INTO ut_weapons
   SET wp_type='DamSuperShockRifle', wp_desc='Super Shock Rifle';
INSERT INTO ut_weapons
   SET wp_type='DamZoomSuperShockRifle', wp_desc='Super Shock Rifle';
INSERT INTO ut_weapons
   SET wp_type='SuperShockRifle', wp_desc='Super Shock Rifle';
INSERT INTO ut_weapons
   SET wp_type='ZoomSuperShockRifle', wp_desc='Super Shock Rifle', wp_secondary='1';
INSERT INTO ut_weapons
   SET wp_type='DamBallLauncher', wp_desc='Ball Launcher';
INSERT INTO ut_weapons
   SET wp_type='BallLauncher', wp_desc='Ball Launcher';
INSERT INTO ut_weapons
   SET wp_type='DamTypeSuperShockBeam', wp_desc='Super Shock Rifle';
INSERT INTO ut_weapons
   SET wp_type='fell', wp_desc='Fell';
INSERT INTO ut_weapons
   SET wp_type='Crushed', wp_desc='Crushed';
INSERT INTO ut_weapons
   SET wp_type='FellLava', wp_desc='Fell Into Lava';
INSERT INTO ut_weapons
   SET wp_type='Suicided', wp_desc='Suicided';
INSERT INTO ut_weapons
   SET wp_type='Gibbed', wp_desc='Gibbed';
INSERT INTO ut_weapons
   SET wp_type='Drowned', wp_desc='Drowned';
INSERT INTO ut_weapons
   SET wp_type='Corroded', wp_desc='Corroded';
INSERT INTO ut_weapons
   SET wp_type='SwamTooFar', wp_desc='Swam Too Far';
INSERT INTO ut_weapons
   SET wp_type='Depressurized', wp_desc='Depressurized';
INSERT INTO ut_weapons
   SET wp_type='ClassicDamTypeEnforcer', wp_desc='Classic Enforcer';
INSERT INTO ut_weapons
   SET wp_type='ClassicDamTypeSniperShot', wp_desc='Classic Sniper Rifle';
INSERT INTO ut_weapons
   SET wp_type='ClassicDamTypeSniperHeadShot', wp_desc='Classic Sniper Rifle';
INSERT INTO ut_weapons
   SET wp_type='DamageType', wp_desc='Unknown Weapon';
INSERT INTO ut_weapons
   SET wp_type='TeamChange', wp_desc='Team Change';
INSERT INTO ut_weapons
   SET wp_type='DamTypeInstaVape', wp_desc='Super Shock Rifle', wp_secondary='1';
INSERT INTO ut_weapons
   SET wp_type='Painter', wp_desc='Ion Cannon';

CREATE TABLE ut_items (
    it_num     SMALLINT(5) UNSIGNED  NOT NULL AUTO_INCREMENT,
    it_type    VARCHAR(30)           NOT NULL DEFAULT '',
    it_desc    VARCHAR(30)           NOT NULL DEFAULT '',
    it_pickups MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    UNIQUE KEY it_num (it_num),
    KEY it_typ (it_type)
);

INSERT INTO ut_items
   SET it_type='ShieldGunPickup', it_desc='Shield Gun';
INSERT INTO ut_items
   SET it_type='AssaultRiflePickup', it_desc='Assault Rifle';
INSERT INTO ut_items
   SET it_type='BioRiflePickup', it_desc='Bio Rifle';
INSERT INTO ut_items
   SET it_type='ShockRiflePickup', it_desc='Shock Rifle';
INSERT INTO ut_items
   SET it_type='LinkGunPickup', it_desc='Link Gun';
INSERT INTO ut_items
   SET it_type='MinigunPickup', it_desc='Minigun';
INSERT INTO ut_items
   SET it_type='FlakCannonPickup', it_desc='Flak Cannon';
INSERT INTO ut_items
   SET it_type='RocketLauncherPickup', it_desc='Rocket Launcher';
INSERT INTO ut_items
   SET it_type='SniperRiflePickup', it_desc='Lightning Gun';
INSERT INTO ut_items
   SET it_type='RedeemerPickup', it_desc='Redeemer';
INSERT INTO ut_items
   SET it_type='IonPainterPickup', it_desc='Ion Painter';
INSERT INTO ut_items
   SET it_type='SuperShockRiflePickup', it_desc='Super Shock Rifle';
INSERT INTO ut_items
   SET it_type='AssaultAmmoPickup', it_desc='Assault Rifle Ammo';
INSERT INTO ut_items
   SET it_type='AssaultGrenadesPickup', it_desc='Assault Rifle Grenades';
INSERT INTO ut_items
   SET it_type='BioAmmoPickup', it_desc='Bio Rifle Ammo';
INSERT INTO ut_items
   SET it_type='ShockAmmoPickup', it_desc='Shock Rifle Ammo';
INSERT INTO ut_items
   SET it_type='LinkAmmoPickup', it_desc='Link Gun Ammo';
INSERT INTO ut_items
   SET it_type='MinigunAmmoPickup', it_desc='Minigun Ammo';
INSERT INTO ut_items
   SET it_type='FlakAmmoPickup', it_desc='Flak Cannon Ammo';
INSERT INTO ut_items
   SET it_type='RocketAmmoPickup', it_desc='Rocket Launcher Ammo';
INSERT INTO ut_items
   SET it_type='SniperAmmoPickup', it_desc='Lightning Gun Ammo';
INSERT INTO ut_items
   SET it_type='AdrenelinPickup', it_desc='Adrenaline';
INSERT INTO ut_items
   SET it_type='ShieldPack', it_desc='Shield Pack';
INSERT INTO ut_items
   SET it_type='LargeShieldPickup', it_desc='Large Shield Pack';
INSERT INTO ut_items
   SET it_type='HealthVialPickup', it_desc='Health Vial';
INSERT INTO ut_items
   SET it_type='HealthPack', it_desc='Health Pack';
INSERT INTO ut_items
   SET it_type='LargeHealthPack', it_desc='Large Health Pack';
INSERT INTO ut_items
   SET it_type='UDamagePickup', it_desc='Damage Amplifier';
INSERT INTO ut_items
   SET it_type='LargeHealthPickup', it_desc='Large Health Pack';

CREATE TABLE ut_pitems (
    pi_plr     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    pi_item    SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    pi_pickups MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    KEY pi_plritm (pi_plr, pi_item)
);

CREATE TABLE ut_gitems (
    gi_game    INT(8) UNSIGNED      NOT NULL DEFAULT '0',
    gi_item    SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
    gi_plr     SMALLINT(6)          NOT NULL DEFAULT '0',
    gi_pickups SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
    KEY gi_gnumit (gi_game, gi_item)
);

CREATE TABLE ut_gchat (
    gc_game INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    gc_plr  SMALLINT(6)           NOT NULL DEFAULT '0',
    gc_team TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    gc_time MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    gc_text VARCHAR(255)          NOT NULL DEFAULT '',
    KEY gc_gnum (gc_game)
);
