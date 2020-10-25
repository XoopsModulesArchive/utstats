CREATE TABLE ut_gscores (
    gs_game   INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    gs_player SMALLINT(6)           NOT NULL DEFAULT '0',
    gs_time   MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    gs_score  SMALLINT(6)           NOT NULL DEFAULT '0',
    gs_team   TINYINT(4)            NOT NULL DEFAULT '0',
    KEY gs_gnum (gs_game)
);
