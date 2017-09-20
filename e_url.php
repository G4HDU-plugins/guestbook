<?php

/*
* e107 Bootstrap CMS
*
* Copyright (C) 2008-2015 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
* 
* IMPORTANT: Make sure the redirect script uses the following code to load class2.php: 
* 
* 	if (!defined('e107_INIT'))
* 	{
* 		require_once("../../class2.php");
* 	}
* 
*/

if ( !defined( 'e107_INIT' ) ) {
    exit;
}

// v2.x Standard  - Simple mod-rewrite module.

class guestbook_url // plugin-folder + '_url'
    {
    function config() {
        $config = array();

        /**
         *         $config['other'] = array(
         *             'alias'         => 'guestbook',                         // default alias '_blank'. {alias} is substituted with this value below. Allows for customization within the admin area.
         *             'regex'            => '^{alias}/?$',                         // matched against url, and if true, redirected to 'redirect' below.
         *             'sef'            => '{alias}',                             // used by e107::url(); to create a url from the db table.
         *             'redirect'        => '{e_PLUGIN}guestbook/index.php',     // file-path of what to load when the regex returns true.

         *         );
         */

        $config['index'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}/?$', // matched against url, and if true, redirected to 'redirect' below.
            'sef' => '{alias}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?$1', // file-path of what to load when the regex returns true.

            );
        $config['view'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(view)(-)(.*)(-)(.*)(-)([0-9]+)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?view-{guestbook_name}-{guestbook_comment}-{guestbook_id}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&id=$7', // file-path of what to load when the regex returns true.

            );
        $config['add'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(add)(.*)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?add', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&id=0', // file-path of what to load when the regex returns true.

            );
        $config['save'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(save)(-)([0-9]+)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?save-{guestbook_id}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&id=$3', // file-path of what to load when the regex returns true.
            );
        $config['edit'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(edit)(-)([0-9]+)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?edit-{guestbook_id}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&id=$3', // file-path of what to load when the regex returns true.

            );
        $config['delete'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(delete)(-)([0-9]+)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?delete-{guestbook_id}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&id=$3', // file-path of what to load when the regex returns true.

            );
        $config['delok'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(delok)(-)([0-9]+)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?delok-{guestbook_id}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&id=$3', // file-path of what to load when the regex returns true.

            );
        $config['delcanc'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(delcanc)(-)([0-9]+)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?delcanc-{guestbook_id}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&id=$3', // file-path of what to load when the regex returns true.

            );
        $config['cancel'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(cancel)(-)([0-9]+)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?cancel-{guestbook_id}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&id=$3', // file-path of what to load when the regex returns true.

            );
        $config['approve'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(approve)(-)([0-9]+)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?approve-{guestbook_id}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&id=$3', // file-path of what to load when the regex returns true.

            );
        $config['unapprove'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(unapprove)(-)([0-9]+)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?unapprove-{guestbook_id}', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&id=$3', // file-path of what to load when the regex returns true.

            );
        $config['list'] = array(
            'alias' => 'guestbook',
            'regex' => '^{alias}\?(list)(-)([0-9]+)$', // matched against url, and if true, redirected to 'redirect' below. /ig
            'sef' => '{alias}?list-', // used by e107::url(); to create a url from the db table.
            'redirect' => '{e_PLUGIN}guestbook/index.php?action=$1&from=$3', // file-path of what to load when the regex returns true.

            );
        return $config;
    }


}
