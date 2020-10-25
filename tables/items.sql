CREATE TABLE ut_items (
    it_num     SMALLINT(5) UNSIGNED  NOT NULL AUTO_INCREMENT,
    it_type    VARCHAR(30)           NOT NULL DEFAULT '',
    it_desc    VARCHAR(30)           NOT NULL DEFAULT '',
    it_pickups MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
    UNIQUE KEY it_num (it_num),
    KEY it_typ (it_type)
);

INSERT INTO ut_items
   SET it_type='ShieldGunPickup', it_desc='Shield Gun';
INSERT INTO ut_items
   SET it_type='AssaultRiflePickup', it_desc='Assault Rifle';
INSERT INTO ut_items
   SET it_type='BioRiflePickup', it_desc='Bio Rifle';
INSERT INTO ut_items
   SET it_type='ShockRiflePickup', it_desc='Shock Rifle';
INSERT INTO ut_items
   SET it_type='LinkGunPickup', it_desc='Link Gun';
INSERT INTO ut_items
   SET it_type='MinigunPickup', it_desc='Minigun';
INSERT INTO ut_items
   SET it_type='FlakCannonPickup', it_desc='Flak Cannon';
INSERT INTO ut_items
   SET it_type='RocketLauncherPickup', it_desc='Rocket Launcher';
INSERT INTO ut_items
   SET it_type='SniperRiflePickup', it_desc='Lightning Gun';
INSERT INTO ut_items
   SET it_type='RedeemerPickup', it_desc='Redeemer';
INSERT INTO ut_items
   SET it_type='IonPainterPickup', it_desc='Ion Painter';
INSERT INTO ut_items
   SET it_type='SuperShockRiflePickup', it_desc='Super Shock Rifle';
INSERT INTO ut_items
   SET it_type='AssaultAmmoPickup', it_desc='Assault Rifle Ammo';
INSERT INTO ut_items
   SET it_type='AssaultGrenadesPickup', it_desc='Assault Rifle Grenades';
INSERT INTO ut_items
   SET it_type='BioAmmoPickup', it_desc='Bio Rifle Ammo';
INSERT INTO ut_items
   SET it_type='ShockAmmoPickup', it_desc='Shock Rifle Ammo';
INSERT INTO ut_items
   SET it_type='LinkAmmoPickup', it_desc='Link Gun Ammo';
INSERT INTO ut_items
   SET it_type='MinigunAmmoPickup', it_desc='Minigun Ammo';
INSERT INTO ut_items
   SET it_type='FlakAmmoPickup', it_desc='Flak Cannon Ammo';
INSERT INTO ut_items
   SET it_type='RocketAmmoPickup', it_desc='Rocket Launcher Ammo';
INSERT INTO ut_items
   SET it_type='SniperAmmoPickup', it_desc='Lightning Gun Ammo';
INSERT INTO ut_items
   SET it_type='AdrenelinPickup', it_desc='Adrenaline';
INSERT INTO ut_items
   SET it_type='ShieldPack', it_desc='Shield Pack';
INSERT INTO ut_items
   SET it_type='LargeShieldPickup', it_desc='Large Shield Pack';
INSERT INTO ut_items
   SET it_type='HealthVialPickup', it_desc='Health Vial';
INSERT INTO ut_items
   SET it_type='HealthPack', it_desc='Health Pack';
INSERT INTO ut_items
   SET it_type='LargeHealthPack', it_desc='Large Health Pack';
INSERT INTO ut_items
   SET it_type='UDamagePickup', it_desc='Damage Amplifier';
INSERT INTO ut_items
   SET it_type='LargeHealthPickup', it_desc='Large Health Pack';
