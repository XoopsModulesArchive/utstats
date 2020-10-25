CREATE TABLE ut_gitems (
    gi_game    INT(8) UNSIGNED      NOT NULL DEFAULT '0',
    gi_item    SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
    gi_plr     SMALLINT(6)          NOT NULL DEFAULT '0',
    gi_pickups SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
    KEY gi_gnumit (gi_game, gi_item)
);
