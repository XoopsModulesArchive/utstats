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
