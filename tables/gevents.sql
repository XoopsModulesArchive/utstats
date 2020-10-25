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
