<?php

/**
 * +---------------------------------------------------------------+
 * |        Enhanced Guestbook for e107 VV2.0 - by Barry Keal G4HDU
 * |
 * |        This module for the e107 V2.0 website system
 * |        Copyright Barry Keal 2004-2016
 * |
 * +---------------------------------------------------------------+
 */
// get round the problem of sanitizing get/post with { } in url
#if (isset($_GET['ajaxparm']) ) {
#    $TEMPGET = $_GET['ajaxparm'];
#    unset($_GET['ajaxparm']);
#}

if (!defined('e107_INIT')) {
    include_once "../../class2.php";
}

/**
 * Check that it is calling correctly and the plugin is installed
 */

if (!defined('e107_INIT') || !e107::isInstalled('guestbook') ) {
    e107::redirect();
    exit;
}
/**
 * get the template for the plugin from theme directory if it exists
 * else get it from plugin directory
 */


e107::lan('guestbook',false,true);
/**
 * Create the guestbook object if it is not already created
 */
if (!is_object($guestbook_obj) ) {
    include_once 'includes/guestbook_class.php';
    $guestbook_obj = new guestbook;
}
#if (isset($TEMPGET) ) {
#    $guestbook_obj->setAjaxParm($TEMPGET);
#}
e107::js('footer', e_PLUGIN . 'guestbook/js/guestbook.js', null, 'jquery'); // Load Plugin javascript and include jQuery framework
//e107::js( 'footer', e_PLUGIN . 'guestbook/js/guestbook.min.js', null, 'jquery' ); // Load Plugin javascript and include jQuery framework
e107::css('guestbook', 'css/guestbook.css'); // load css file
e107::meta('keywords', 'guestbook'); // add meta data to <HEAD>
e107::lan('guestbook', false, true);

$textOut = $guestbook_obj->processMain();
//print_a($text);
if ($textOut !== null ) {
    include_once HEADERF;
    $ns->tablerender(LAN_GB_001, $textOut,'guestbook');
    include_once FOOTERF;
}

