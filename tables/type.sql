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
