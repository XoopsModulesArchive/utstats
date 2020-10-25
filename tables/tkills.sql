CREATE TABLE ut_tkills (
    tk_game  INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    tk_team  TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    tk_score SMALLINT(6)           NOT NULL DEFAULT '0',
    tk_time  MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    KEY tk_gnumteam (tk_game, tk_team)
);
