<?
// interim database changes

sc_query("ALTER TABLE `site_vars` ADD `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST");
sc_query("ALTER TABLE `site_vars` ADD `desc` TEXT");
sc_query("ALTER TABLE `site_vars` ADD `type` TEXT");

sc_query("ALTER TABLE `menu_top` ADD `access_method` TEXT");
sc_query("ALTER TABLE `menu_top` ADD `other_requirements` TEXT");
sc_query("ALTER TABLE `menu_top` DROP `access`");

sc_query("update `menu_top` set `access_method`='admin,access' where `name`='Admin'");
sc_query("update `menu_top` set `other_requirements`='loggedin=true' where `name`='Profile'");



?>