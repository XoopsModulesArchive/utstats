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
