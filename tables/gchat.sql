CREATE TABLE ut_gchat (
    gc_game INT(10) UNSIGNED      NOT NULL DEFAULT '0',
    gc_plr  SMALLINT(6)           NOT NULL DEFAULT '0',
    gc_team TINYINT(3) UNSIGNED   NOT NULL DEFAULT '0',
    gc_time MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    gc_text VARCHAR(255)          NOT NULL DEFAULT '',
    KEY gc_gnum (gc_game)
);
