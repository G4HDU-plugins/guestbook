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
if (!defined('e107_INIT')) {
    exit;
}


class guestbook_shortcodes extends e_shortcode
{
    public $override = false; // when set to true, existing core/plugin shortcodes matching methods below will be overridden.

    public $current;
    public $fields;
    public $datarow;
    public $multiBlock;
    /**
     * Constructor
     */
    function __construct()
    {
    }
    /**
     * guestbook_shortcodes::sc_gb_sendpm()
     *
     * @return
     */
    function sc_gb_sendpm()
    {
        global $guestbook_obj, $guestbook_pmto, $gbpref;
        if ($gbpref['plug_installed']['pm'] && $guestbook_obj->prefs['sc_gb_notifymethod'] >
            0) {
            if ($guestbook_pmto > 0) {
                $retval = "<a href='" . e_PLUGIN . "pm/pm.php?send.{$guestbook_pmto}' ><img src='" .
                    e_PLUGIN . "pm/images/pm.png' style='border:0;' alt='" . sc_gb_123 . "' title='" .
                    sc_gb_123 . "' /></a>";
            } else {
                $retval = '';
            }
        }
        return $retval;
    }

    /**
     * guestbook_shortcodes::sc_gb_edit()
     *
     * @return
     */
    function sc_gb_edit()
    {
        global $guestbook_obj, $guestbook_itemid, $guestbook_thisrec;
        if (check_class($guestbook_obj->prefs['sc_gb_create']) || check_class($guestbook_obj->
            prefs['sc_gb_admin'])) {
            $retval = "<a href='" . e_PLUGIN .
                "guestbook/manage_adds.php?action=godo&amp;catid=$guestbook_thisrec&amp;actvar=edit' rel='external' ><img src='" .
                e_IMAGE . "admin_images/edit_16.png' style='border:0;' alt='" . sc_gb_36 .
                "' title='" . sc_gb_36 . "'/></a>";
        }

        return $retval;
    }
    function sc_view_ip()
    {
        $ip = e107::ipdecode($this->datarow['guestbook_ip'], true);
        return $ip;
    }
    function sc_view_posted()
    {
        $date = $this->datarow['guestbook_date'];
        return $date;
    }


    /**
     * guestbook_shortcodes::sc_gb_sign()
     *
     * @return
     */
    function sc_gb_sign()
    {
        $retval = "
    	<a href='#' id='guestbookLinkSwap' ><i class='fa fa-pencil-square-o' aria-hidden='true'></i><br>Sign our guestbook</a>";
        return $retval;
    }
    function sc_gb_back()
    {
        $link = e107::url('guestbook', 'index');
        $retval = "
        <span class='guestbookBackIcon'>
            <a href='" . $link . "' id='guestbookLink' >
                <i class='fa fa-arrow-circle-o-left' aria-hidden='true'></i>
            </a>
        </span>";
        return $retval;
    }
    function sc_gb_addnew()
    {
        $retval = ' ';
        if (!$this->multiBlock && $this->poster) {

            if ($this->datarow['guestbook_add']) {
                $retval = "<span class='guestbookNewEntryIcon'><a href='" . $this->datarow['guestbook_addnew'] .
                    "'  ><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a></span>";
            }
        }
        return $retval;
    }
    function sc_gb_record_approved()
    {
        if ($this->datarow['guestbook_approved']) {
            $retval = "   
                <a href='" . $this->datarow['guestbook_unapprove'] .
                "' id='guestbookApproveButton' >                 
                    <button type='button' class='btn btn-default gbmodGreen'>
                        <span class='glyphicon glyphicon-ban-circle ' aria-hidden='false'></span> Hide
                    </button>
                </a>";
            $retval = "<button type='button' id='guestbookApproveButton' onclick=\"location.href='" .
                $this->datarow['guestbook_unapprove'] . "'\" class='btn btn-default gbmodGreen'>
     <span class='glyphicon glyphicon-ban-circle' aria-hidden='false'></span> Hide</button>";
        } else {
            $retval = "
                <a href='" . $this->datarow['guestbook_approve'] .
                "' id='guestbookApproveButton' >                    
                    <button type='button' class='btn btn-default gbmod'>
                        <span class='glyphicon glyphicon-ok-circle gbmodRed' aria-hidden='false'></span> Show
                    </button>
                </a>";
            $retval = "<button type='button' id='guestbookApproveButton' onclick=\"location.href='" .
                $this->datarow['guestbook_approve'] . "'\" class='btn btn-default gbmod'>
     <span class='glyphicon glyphicon-ok-circle gbmodRed' aria-hidden='false'></span> Show</button>";
        }
        return $retval;

    }
    function sc_gb_record_delete()
    {
        $retval = "
                <a href='" . $this->datarow['guestbook_delete'] .
            "' id='guestbookDelButton' class='btn btn-default gbmodRed' >
                    <button type='button' class='btn btn-default gbmodRed'>
                        <span class='glyphicon glyphicon-remove-circle gbmodRed' aria-hidden='false'></span> Delete
                    </button>
                </a>";
        $retval = "<button type='button' id='guestbookDelButton' onclick=\"location.href='" .
            $this->datarow['guestbook_delete'] . "'\" class='btn btn-default gbmodRed'>
     <span class='glyphicon glyphicon-remove-circle gbmodRed' aria-hidden='false'></span> Delete</button>";
        return $retval;

    }
    function sc_gb_record_edit()
    {
        $retval = "
                <a href='" . $this->datarow['guestbook_edit'] .
            "' id='guestbookEditButton' >
                    <button type='button' class='btn btn-default gbmodBlue'>
                        <span class='glyphicon glyphicon-edit  gbmodBlue' aria-hidden='false'></span> Edit
                    </button>
                </a>";
        $retval = "<button type='button' id='guestbookEditButton' onclick=\"location.href='" .
            $this->datarow['guestbook_edit'] . "'\" class='btn btn-default gbmodBlue'>
     <span class='glyphicon glyphicon-edit  gbmodBlue' aria-hidden='false'></span> Edit</button>";
        return $retval;

    }
    function sc_gb_user_edit()
    {
        $retval = "
                <a href='" . $this->datarow['guestbook_edit'] .
            "' id='guestbookEditButton' >
                    <button type='button' class='btn btn-default gbmodBlue'>
                        <span class='glyphicon glyphicon-edit  gbmodBlue' aria-hidden='false'></span> Edit
                    </button>
                </a>";
        $retval = "<button type='button' id='guestbookEditButton' onclick=\"location.href='" .
            $this->datarow['guestbook_edit'] . "'\" class='btn btn-default gbmodBlue'>
     <span class='glyphicon glyphicon-edit  gbmodBlue' aria-hidden='false'></span> Edit</button>";
        return $retval;

    }
    /**
     * guestbook_shortcodes::sc_gb_sign_name()
     *
     * @param  mixed $parm
     * @return
     */
    function sc_sign_name()
    {
        return $this->datarow['guestbook_name'];
    }
    /**
     * guestbook_shortcodes::sc_gb_sign_name()
     *
     * @param  mixed $parm
     * @return
     */
    function sc_view_name()
    {
        return e107::getParser()->toHTML($this->datarow['guestbook_name'], false);
    }
    function sc_view_id()
    {
        return e107::getParser()->toHTML($this->datarow['guestbook_id'], false);
    }
    function sc_view_partcomment()
    {
        return e107::getParser()->html_truncate(e107::getParser()->toHTML($this->
            datarow['guestbook_comment'], false), 30, '...');
    }
    function sc_delete_ok()
    {
        return $this->datarow['guestbook_delok'];

    }
    function sc_delete_canc()
    {
        return $this->datarow['guestbook_delcanc'];

    }
    /**
     * guestbook_shortcodes::sc_gb_sign_email()
     *
     * @return
     */
    function sc_sign_email()
    {
        return $this->datarow['guestbook_email'];
    }
    function sc_view_email()
    {
        return e107::getParser()->emailObfuscate($this->datarow['guestbook_email']);
    }
    /**
     * guestbook_shortcodes::sc_gb_sign_website()
     *
     * @return
     */
    function sc_gb_sign_website()
    {
        return $this->datarow['guestbook_url'];
    }

    /**
     * guestbook_shortcodes::sc_gb_sign_comments()
     *
     * @return
     */
    function sc_gb_sign_comments()
    {
        return $this->datarow['guestbook_comment'];
    }
    function sc_gb_view_comments()
    {
        return $this->datarow['guestbook_comment'];
    }
    function sc_gb_sign_approved()
    {
        return $this->datarow['guestbook_approved'];
    }
    function sc_gb_id()
    {
        return $this->datarow['guestbook_id'];
    }
    /**
     * guestbook_shortcodes::sc_gb_sign_image()
     *
     * @return
     */
    function sc_gb_imagecode_number()
    {
        //
        if ($this->use_imagecode && $this->guest) {            {
                return e107::getSecureImg()->renderImage();
            }

            return '';
        }
    }
    function sc_gb_imagecode_box($parm = '')
    {
      //  var_dump($this->use_imagecode );
        if ($this->use_imagecode && $this->guest) {
            return e107::getSecureImg()->renderInput();
        }

        return '';
    }
    /**
     * guestbook_shortcodes::sc_gb_sign_udf1_name()
     *
     * @return
     */
    function sc_udf_name($param)
    {
        return $this->datarow['udf_name'][$param];
    }
    function sc_view_udf($param)
    {
        return e107::getParser()->toHTML($this->datarow['udf'][$param], false);
    }
    /**
     * guestbook_shortcodes::sc_gb_sign_udf1()
     *
     * @return
     */
    function sc_sign_udf($param)
    {
        return $this->datarow['guestbook_udf'][$param];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_udf2_name()
    {
        return $this->datarow['udf2_name'];
    }
    function sc_view_udf2()
    {
        return e107::getParser()->toHTML($this->datarow['udf2'], false);
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_sign_udf2()
    {
        return $this->datarow['guestbook_udf2'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_udf3_name()
    {
        return $this->datarow['udf3_name'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_sign_udf3()
    {
        return $this->datarow['guestbook_udf3'];
    }
    function sc_view_udf3()
    {
        return e107::getParser()->toHTML($this->datarow['udf3'], false);
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_udf4_name()
    {
        return $this->datarow['udf4_name'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_sign_udf4()
    {
        return $this->datarow['guestbook_udf4'];
    }
    function sc_view_udf4()
    {
        return e107::getParser()->toHTML($this->datarow['udf4'], false);
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_udf5_name()
    {
        return $this->datarow['udf5_name'];
    }
    function sc_view_udf5()
    {
        return e107::getParser()->toHTML($this->datarow['udf5'], false);
    }
    function sc_sign_udf5()
    {
        return $this->datarow['guestbook_udf5'];
    }
    function sc_udf6_name()
    {
        return $this->datarow['udf6_name'];
    }
    function sc_view_udf6()
    {
        return e107::getParser()->toHTML($this->datarow['udf6'], false);
    }
    function sc_sign_udf6()
    {
        return $this->datarow['guestbook_udf6'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_sign_submit()
    {
        return $this->datarow['guestbook_submit'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_edit_submit()
    {
        return "<input class='button' type='submit' name='sc_gb_edsubit' id='sc_gb_edsubit' />";
    }
    /**
     * guestbook_shortcodes::sc_gb_name()
     *
     * @return
     */
    function sc_gb_name()
    {
        return $this->datarow['guestbook_name'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_comments()
    {
        return $this->datarow['guestbook_comment'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_posted()
    {
        return $this->datarow['guestbook_listdate'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_ip()
    {
        global $guestbook_obj, $tp, $guestbook_row;
        if ($guestbook_obj->sc_gb_admin) {
            // return $tp->toHTML( $guestbook_row['sc_gb_ip'], false );
        }
        return $this->datarow['guestbook_ip'];
    }

    /**
     * guestbook_shortcodes::sc_gb_host()
     *
     * @return
     */
    function sc_gb_host()
    {
        global $guestbook_obj, $tp, $guestbook_row;
        if ($guestbook_obj->sc_gb_admin) {
            // return $tp->toHTML( $guestbook_row['sc_gb_host'], false );
        }
        return $this->datarow['guestbook_host'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_mod()
    {
        global $guestbook_obj, $tp, $guestbook_row;
        // sort out url
        $url = '';
        if ($guestbook_row['sc_gb_url'] == '' || $guestbook_row['sc_gb_url'] ==
            'http://') {
            $url = '';
            $website = '';
        } else {
            if (strpos($guestbook_row['sc_gb_url'], 'http://') === false) {
                $url = 'http://' . $guestbook_row['sc_gb_url'];
            } else {
                $url = $guestbook_row['sc_gb_url'];
            }
            $website = "<a href='" . $url . "' rel='external' ><img src='" . e_PLUGIN_ABS .
                "forum/images/lite/website.png' style='height:16px;width:16px;border:0px;'  alt='" .
                sc_gb_SIGN_007 . "' title='" . sc_gb_SIGN_007 . "' /></a>";
        }

        if ($guestbook_obj->sc_gb_admin) {
            // we are admin so show allsorts
            $mail = 'mailto:' . $guestbook_row['sc_gb_email'];
            $retval .= "
		{$website}
   		<a href='" . $mail . "' ><img src='" . e_PLUGIN_ABS .
                "forum/images/lite/email.png' style='height:16px;width:16px;border:0px;'  alt='" .
                sc_gb_SIGN_010 . "' title='" . sc_gb_SIGN_010 . "' /></a>
   		<a href='" . e_SELF . "?{$guestbook_obj->from}.edit.{$guestbook_row['sc_gb_id']}' ><img src='" .
                e_PLUGIN_ABS . "forum/images/lite/admin_edit.png' style='height:16px;width:16px;border:0px;'  alt='" .
                sc_gb_SIGN_008 . "' title='" . sc_gb_SIGN_008 . "' /></a>
   		<a href='" . e_SELF . "?{$guestbook_obj->from}.delete.{$guestbook_row['sc_gb_id']}' ><img src='" .
                e_PLUGIN_ABS . "forum/images/lite/admin_delete.png' style='height:16px;width:16px;border:0px;'  alt='" .
                sc_gb_SIGN_009 . "' title='" . sc_gb_SIGN_009 . "' /></a>";
        } elseif (USER) {
            $retval .= $website;
        } elseif (!USER && !$guestbook_obj->sc_gb_showsites) {
            $retval .= $website;
        }
        return $retval;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_np()
    {
        return $this->datarow['guestbook_nextprev'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_MESSAGE_HTML()
    {
        return sc_gb_MESSAGE_HTML;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_MESSAGE_LINK()
    {
        global $guestbook_obj;
        if (!$guestbook_obj->sc_gb_links) {
            return sc_gb_MESSAGE_LINK;
        }
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_MESSAGE_TIME()
    {
        global $guestbook_obj;
        if ($guestbook_obj->sc_gb_edittime > 0) {
            return sc_gb_MESSAGE_TIME_1 . ' ' . (int)($guestbook_obj->sc_gb_edittime / 60) .
                ' ' . sc_gb_MESSAGE_TIME_2;
        }
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_MESSAGE_EMAIL()
    {
        return sc_gb_MESSAGE_EMAIL;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_delete_name()
    {
        global $guestbook_row, $tp;
        $name = $tp->toFORM($guestbook_row['sc_gb_name']);
        return $name;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_delete_id()
    {
        global $guestbook_row, $tp;
        $id = $tp->toFORM($guestbook_row['sc_gb_id']);
        return $id;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_deleteok()
    {
        return "<input type='submit' class='button' name='sc_gb_delok' id='sc_gb_delok' value='" .
            sc_gb_DELETE_02 . "' />";
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_deletecanc()
    {
        return "<input type='submit' class='button' name='sc_gb_delcanc' id='sc_gb_delcanc' value='" .
            sc_gb_DELETE_03 . "' />";
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_viewall()
    {
        return "<a href='" . e_SELF . "'>" . LAN_GB_005 . "</a>";
    }

    function sc_sign_cancel()
    {
        return $this->datarow['guestbook_cancel'];
    }
    function sc_gb_sign_approve()
    {
        return $this->datarow['guestbook_approve'];
    }
    function sc_gb_sign_delete()
    {
        return $this->datarow['guestbook_delete'];
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function sc_gb_sign_save()
    {
        return $this->datarow['guestbook_submit'];
    }
    function sc_gb_emailname()
    {
        return $this->datarow['guestbook_name'];
    }
    function sc_gb_sitename()
    {
        return $this->datarow['guestbook_sitename'];
    }
    function sc_gb_confirmlink()
    {
        return $this->datarow['emailConfirmationlink'];
    }
    function sc_gb_copylink()
    {
        return $this->datarow['guestbook_copylink'];
    }
    function sc_timeout_display($param)
    {
        $retval = "
        <div id='countdownContainer'>
    </div>";
        return $retval;
    }

}
