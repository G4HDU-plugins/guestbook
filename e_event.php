<?php

/*
* e107 website system
*
* Copyright (C) 2008-2013 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
* XXX HIGHLY EXPERIMENTAL AND SUBJECT TO CHANGE WITHOUT NOTICE. 
*/

if (!defined('e107_INIT') ) {
    exit;
}


class guestbook_event // plugin-folder + '_event'
{

    /**
     * Configure functions/methods to run when specific e107 events are triggered.
     * For a list of events, please visit: http://e107.org/developer-manual/classes-and-methods#events
     * Developers can trigger their own events using: e107::getEvent()->trigger('plugin_event',$array);
     * Where 'plugin' is the folder of their plugin and 'event' is a unique name of the event.
     * $array is data which is sent to the triggered function. eg. myfunction($array) in the example below.
     *
     * @return array
     */
    function config() 
    {

        $event = array();
        /**
         *         $event[] = array(
         *             'name' => "edited", // when this is triggered... (see http://e107.org/developer-manual/classes-and-methods#events)
         *             'function' => "edited", // ..run this function (see below).
         *             );
         *         $event[] = array(
         *             'name' => "new", // when this is triggered... (see http://e107.org/developer-manual/classes-and-methods#events)
         *             'function' => "new", // ..run this function (see below).
         *             );
         *         $event[] = array(
         *             'name' => "approved", // when this is triggered... (see http://e107.org/developer-manual/classes-and-methods#events)
         *             'function' => "approved", // ..run this function (see below).
         *             );
         *         $event[] = array(
         *             'name' => "confirmed", // when this is triggered... (see http://e107.org/developer-manual/classes-and-methods#events)
         *             'function' => "confirmed", // ..run this function (see below).
         *             );
         *         $event[] = array(
         *             'name' => "unapproved", // when this is triggered... (see http://e107.org/developer-manual/classes-and-methods#events)
         *             'function' => "unapproved", // ..run this function (see below).
         *             );
         *         $event[] = array(
         *             'name' => "deleted", // when this is triggered... (see http://e107.org/developer-manual/classes-and-methods#events)
         *             'function' => "deleted", // ..run this function (see below).
         *             );
         * 
         * $event[] = array(
         * 'name' => "doconfirm", // when this is triggered... (see http://e107.org/developer-manual/classes-and-methods#events)
         * 'function' => "doconfirm", // ..run this function (see below).
         * );
         * return $event;

         * }
         * function guestbook_doconfirm( $data ) {
         * var_dump( $data );
         * //  die("XXX");
         * 
         * }
         * function doconfirm( $data ) {
         * var_dump( $data );
         * //    die("QQQ");
         * #                $this->sc->datarow = $theRecord;
         * #        $this->sc->datarow['guestbook_sitename'] = SITENAME;
         * #        $link = e_SELF . "?guestbook_action=confirm&amp;code=" . $theRecord['guestbook_emailconfirmcode'];
         * #        $this->sc->datarow['emailConfirmationlink'] = "<a href='{$link}'>click</a>";
         * #        $this->sc->datarow['guestbook_copylink'] = $link;
         * #        require_once ( "templates/email_template.php" );
         * #
         * #        $EMAIL_TEMPLATE['guestbookConfirm']['body'] = e107::getParser()->parseTemplate( $EMAIL_TEMPLATE['guestbookConfirm']['body'], true, $this->sc );
         * #        $eml = $EMAIL_TEMPLATE['guestbookConfirm']; //        - template to use. 'default'
         * #        // $eml['shortcodes']        - array of shortcode values. eg. array('MY_SHORTCODE'=>'12345');
         * #        $mailout = new e107Email;
         * #        $result = $mailout->sendEmail( $theRecord['guestbook_email'], $theReecord['guestbook_name'], $eml );
         * }
         */
    }
} //end class
