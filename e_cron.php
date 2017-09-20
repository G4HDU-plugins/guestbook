<?php

/**
 * e107 website system
 *
 * Copyright (C) 2008-2016 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 */
e107::lan('guestbook', true, true); // e_PLUGIN.'guestbook/languages/'.e_LANGUAGE.'/guestbook.php'
class guestbook_cron // plugin-folder name + '_cron'.
{
    function config() // Setup
    {
        $cron = array();

        $cron[] = array(
            'name' => LAN_GB_CRON_MAINT, // Displayed in admin area. .
            'function' => "maintenance", // Name of the function which is defined below.
            'category' => 'content', // Choose between: mail, user, content, notify, or backup
            'description' => LAN_GB_CRON_DESC // Displayed in admin area.
                );

        return $cron;
    }

    public function maintenance() 
    {
        // Do Something.
        $maintDays = e107::pref('guestbook', 'maintDays');
        if ($maintDays == 0 ) {
            //     return;
        }
        $now = time();
        $since = time() - ( $maintDays * 60 * 60 * 24 );
        $args = "guestbook_approved=0 AND guestbook_date < {$since}";
        $log = e107::getLog();
        $result = e107::getDb()->delete("guestbook", $args, false);
        if ($result > 0 ) {
            $log->add('Guestbook Maintenance', "Maintenance {$result} deleted", E_LOG_INFORMATIVE, 'GBCRON');
        } else {
            $log->add('Guestbook Maintenance', "No records deleted", E_LOG_INFORMATIVE, 'GBCRON');
        }
    }

}
