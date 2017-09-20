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
if (!defined('e107_INIT')) { exit; 
}


class guestbook_dashboard // include plugin-folder in the name.
{
    
    function chart()
    {
        return false;
    }
    
    
    function status()
    {
        $sql = e107::getDb();
        $guestbook_posts = $sql->count('guestbook');
        
        $var[0]['icon']     = "<img class='guestbook_16' src='".e_PLUGIN."guestbook/images/guestbook_16.png' />";
        $var[0]['title']     = "Guestbook Posts";
        $var[0]['url']        = e_PLUGIN."guestbook/admin_config.php";
        $var[0]['total']     = $guestbook_posts;

        return $var;
    }    
    
    
    function latest()
    {
        $sql = e107::getDb();
        $pending_posts = $sql->count('guestbook', '(*)', "WHERE guestbook_approved=0");
        
        $var[0]['icon']     = "<img src='".e_PLUGIN."guestbook/images/guestbook_16.png' />";
        $var[0]['title']     = "Guestbook Pending";
        $var[0]['url']        = e_PLUGIN."guestbook/admin_config.php?filter_options=bool__guestbook_approved__0";
        $var[0]['total']     = $pending_posts;

        return $var;
    }    
    
    
}
?>