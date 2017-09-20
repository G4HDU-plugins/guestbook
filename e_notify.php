<?php
/**
 * +---------------------------------------------------------------+
 * |        Enhanced Guestbook for e107 V2.0 - by Barry Keal G4HDU
 * |
 * |        This module for the e107 V2.0 website system
 * |        Copyright Barry Keal 2004-2016
 * |
 * +---------------------------------------------------------------+
 */
if (!defined('e107_INIT') ) {
    exit;
}
e107::lan('guestbook', true, true);

/**
 * guestbook_notify
 * 
 * @package   Guestbook  
 * @author    Barry Keal
 * @copyright Barry Keal 2016
 * @version   2.0
 * @access    public
 */
class guestbook_notify extends notify
{
    /**
     * guestbook_notify::config()
     * 
     * @return array with the configuration items for notify
     */
    function config() 
    {

        $config = array();

        $config[] = array(
            'name' => LAN_GB_NOTIFY_NEWENTRY,
            'function' => "guestbook_new",
            'category' => '' );

        $config[] = array(
            'name' => LAN_GB_NOTIFY_APPROVEDENTRY,
            'function' => "guestbook_approved",
            'category' => '' );

        $config[] = array(
            'name' => LAN_GB_NOTIFY_UNAPPROVEDENTRY,
            'function' => "guestbook_unapproved",
            'category' => '' );

        $config[] = array(
            'name' => LAN_GB_NOTIFY_DELETEDENTRY,
            'function' => "guestbook_deleted",
            'category' => '' );

        $config[] = array(
            'name' => LAN_GB_NOTIFY_UPDATEDENTRY,
            'function' => "guestbook_edited",
            'category' => '' );

        $config[] = array(
            'name' => LAN_GB_NOTIFY_CONFIRMENTRY,
            'function' => "guestbook_confirmed",
            'category' => '' );

        return $config;
    }

    /**
     * guestbook_notify::guestbook_new()
     * 
     * @param  mixed $data
     * @param  mixed $prefs
     * @return
     */
    function guestbook_new( $data ) 
    {
        $message = $this->makeMessage('new', $data, $prefs);

        $this->send('guestbook_new', LAN_GB_NOTIFY_TITLE, $message);
    }

    /**
     * guestbook_notify::guestbook_approved()
     * 
     * @param  mixed $data
     * @return
     */
    function guestbook_approved( $data ) 
    {
        $message = $this->makeMessage('approve', $data, $prefs);
        $this->send('guestbook_approved', LAN_GB_NOTIFY_APP, $message);
    }
    /**
     * guestbook_notify::guestbook_unapproved()
     * 
     * @param  mixed $data
     * @return
     */
    function guestbook_unapproved( $data ) 
    {
        $message = $this->makeMessage('unapprove', $data, $prefs);
        $this->send('guestbook_unapproved', LAN_GB_NOTIFY_UAP, $message);
    }

    /**
     * guestbook_notify::guestbook_deleted()
     * 
     * @param  mixed $data
     * @return
     */
    function guestbook_deleted( $data ) 
    {
        $message = $this->makeMessage('delete', $data, $prefs);
        $this->send('guestbook_deleted', LAN_GB_NOTIFY_DELETE, $message);
    }

    /**
     * guestbook_notify::guestbook_edited()
     * 
     * @param  mixed $data
     * @return
     */
    function guestbook_edited( $data ) 
    {
        $message = $this->makeMessage('edited', $data);
        $this->send('guestbook_edited', LAN_GB_NOTIFY_UPD, $message);
    }
    /**
     * guestbook_notify::guestbook_confirmed()
     * 
     * @param  mixed $data
     * @return void
     */
    function guestbook_confirmed( $data ) 
    {
        $message = $this->makeMessage('confirmed', $data);
        $this->send('guestbook_confirmed', LAN_GB_NOTIFY_UPD, $message);
    }
    /**
     * guestbook_notify::makeMessage()
     * 
     * @param  string $action
     * @param  mixed  $data
     * @return
     */
    private function makeMessage( $action = '', $data = array() ) 
    {
        switch ( $action ) {
        case 'new':
            $messageAction = LAN_GB_NOTIFY_NEW;
            break;
        case 'edited':
            $messageAction = LAN_GB_NOTIFY_UPD;
            break;
        case 'delete':
            $messageAction = LAN_GB_NOTIFY_DELETE;
            break;
        case 'approve':
            $messageAction = LAN_GB_NOTIFY_APP;
            break;
        case 'unapprove':
            $messageAction = LAN_GB_NOTIFY_UAP;
            break;
        case 'updated':
            $messageAction = LAN_GB_NOTIFY_UPD;
            break;
        case 'confirmed':
            $messageAction = LAN_GB_NOTIFY_CONF;
            break;
        default:
            return false;
        }
        $message = "
        <div id='guestbookMail' style='display:block;margin:0;padding:0;width:100%;'>" . LAN_GB_NOTIFY_OCCURED . " : " . $messageAction . "<br>";

        $message .= "<table id='guestbooktable' style='margin:0;padding:0;width:100%;'>";
        $message .= "<tr><td style='width:20%;'>" . LAN_GB_NOTIFY_BY . "</td><td style='width:80%;'><b>{$data['data']['guestbook_name']}</b></td></tr>";
        $message .= "<tr><td style='width:20%;'>" . LAN_GB_NOTIFY_EMAIL . "</td><td><b>{$data['data']['guestbook_email']}</b></td></tr>";
        $message .= "<tr><td style='width:20%;'>" . LAN_GB_NOTIFY_WEB . "</td><td><b>{$data['data']['guestbook_url']}</b></td></tr>";
        $message .= "<tr><td style='width:20%;vertical-align:top;'>" . LAN_GB_NOTIFY_COMMENT . "</td><td><b>" . nl2br($data['data']['guestbook_comment']) .
            "</b></td></tr>";

        if ($data['prefs']['udfActive1'] ) {
            $message .= "<tr><td style='width:20%;'>" . $data['prefs']['udfName1'] . "</td><td><b>{$data['data']['guestbook_udf1']}</b></td></tr>";
        }
        if ($data['prefs']['udfActive2'] ) {
            $message .= "<tr><td style='width:20%;'>" . $data['prefs']['udfName2'] . "</td><td><b>{$data['data']['guestbook_udf2']}</b></td></tr>";
        }
        if ($data['prefs']['udfActive3'] ) {
            $message .= "<tr><td style='width:20%;'>" . $data['prefs']['udfName3'] . "</td><td><b>{$data['data']['guestbook_udf3']}</b></td></tr>";
        }
        if ($data['prefs']['udfActive4'] ) {
            $message .= "<tr><td style='width:20%;'>" . $data['prefs']['udfName4'] . "</td><td><b>{$data['data']['guestbook_udf4']}</b></td></tr>";
        }
        if ($data['prefs']['udfActive5'] ) {
            $message .= "<tr><td style='width:20%;'>" . $data['prefs']['udfName5'] . "</td><td><b>{$data['data']['guestbook_udf5']}</b></td></tr>";
        }

        $tmp = explode('.', $data['data']['guestbook_user'], 2);
        if ($tmp[0] > 0 ) {
            $username = $tmp[1];
        } else {
            $username = LAN_GB_NOTIFY_GUEST;
        }
        $message .= "<tr><td style='width:20%;'>" . LAN_GB_NLAN_GB_NOTIFY_INASOTIFY_10 . "</td><td><b>" . $username . "</b></td></tr>";

        $message .= "</table><br><br>";

        $message .= LAN_GB_NOTIFY_MODLINK . " : <a href='" . SITEURLBASE . e_PLUGIN_ABS . "guestbook/index.php?0.edit.{$data['iguestbook_d']}'>Edit</a><br>";
        $message .= LAN_GB_NOTIFY_DELLINK . " : <a href='" . SITEURLBASE . e_PLUGIN_ABS . "guestbook/index.php?0.delete.{$data['guestbook_id']}'>Delete</a><br>";
        $message .= "</div>";

        return $message;
    }

}

?>
