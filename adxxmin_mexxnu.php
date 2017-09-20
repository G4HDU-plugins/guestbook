<?php
/*
   +---------------------------------------------------------------+
   |        Enhanced Guestbook for e107 v7xx - by Father Barry
   |
   |        This module for the e107 .7+ website system
   |        Copyright Barry Keal 2004-2011
   |
   |		Licenced for the use of the purchaser only. This is not free
   |		software.
   |
   +---------------------------------------------------------------+
*/
if (!defined('e107_INIT') ) {
    exit;
}
include_lan(e_PLUGIN . 'guestbook/languages/' . e_LANGUAGE . '_guestbook.php');

$action = basename($_SERVER['PHP_SELF'], '.php');

$var['admin_config']['text'] = LAN_GB_ADMIN_MENU_1;
$var['admin_config']['link'] = 'admin_config.php';

$var['admin_readme']['text'] = LAN_GB_ADMIN_MENU_2;
$var['admin_readme']['link'] = 'admin_readme.php';

$var['admin_vupdate']['text'] = LAN_GB_ADMIN_MENU_3;
$var['admin_vupdate']['link'] = 'admin_vupdate.php';
show_admin_menu(LAN_GB_ADMIN_MENU_4, $action, $var);

?>
