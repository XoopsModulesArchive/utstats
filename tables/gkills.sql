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
