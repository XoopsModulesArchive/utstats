CREATE TABLE ut_pitems (
    pi_plr     MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    pi_item    SMALLINT(5) UNSIGNED  NOT NULL DEFAULT '0',
    pi_pickups MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    KEY pi_plritm (pi_plr, pi_item)
);
