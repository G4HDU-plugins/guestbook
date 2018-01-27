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


require_once e_HANDLER . 'date_handler.php';
require_once e_HANDLER . "form_handler.php";
global $sec_img;
include_once (e_HANDLER . 'secure_img_handler.php');
$sec_img = new secure_image;
/**
 * guestbook
 * 
 * @package   Guestbook
 * @author    Father Barry
 * @copyright 2016
 * @version   $Id$
 * @access    public
 */
class guestbook
{
    public $prefs;
    protected $ajaxparm = null;
    protected $ajaxdata = null;
    protected $record = array();
    protected $admin = false; // is user an admin
    protected $poster = false; // allowed to post entries
    protected $reader = false; // allowed to read entries
    protected $image = false; // use image verification for guests
    protected $multiip = false; // only one entry per ip
    protected $bbcode = false; // Allow use of bbcode
    protected $perpage = 0; // Posts per page
    protected $links = false; // Allow links in comments
    protected $showsites = false; // Show sites to members only
    protected $edittime = 0; // Max time to edit page
    protected $udf = array();


    protected $from = 0;
    protected $action = 'list';
    protected $id = 0;
    protected $current = 0;
    protected $ipAddress = '127.0.0.1';
    protected $frm;
    protected $sc;
    protected $mes;
    protected $commands;
    protected $timeout;
    protected $tp;
    protected $ns;
    protected $ipBlock;
    protected $guest;
    //protected $gen;

    /**
     * guestbook::__construct()
     */
    function __construct()
    {
        // get template from theme if it exists else use default theme
        if (file_exists(THEME . 'guestbook_template.php'))
        {
            define(GUESTBOOK_THEME, THEME . 'guestbook_template.php');
        } else
        {
            define(GUESTBOOK_THEME, e_PLUGIN . 'guestbook/templates/guestbook_template.php');
        }
        require_once GUESTBOOK_THEME;
        // get prefs
        $this->prefs = e107::getPlugPref('guestbook', '', true);
        // allowed actions
        $this->adminCommands = array(
            'warn',
            'delok',
            'delete',
            'approve',
            'unapprove',
            'confirm',
            'edit',
            );
        $this->posterCommands = array(
            'cancel',
            'delcanc',
            'add',
            'save');
        $this->viewerCommands = array('view', 'list');

        $this->id = 0;
        $this->mes = e107::getMessage();
        $this->frm = e107::getForm();
        $this->ns = e107::getRender();
        $this->gen = e107::getDateConvert();
        $this->tp = e107::getParser();
        $this->sc = e107::getScBatch('guestbook', true);
        $this->template = new guestbook_template;
        // *
        // * set access rights
        // *
        $this->admin = check_class($this->prefs['moderatorClass']);
        $this->poster = check_class($this->prefs['postClass']) || $this->admin;
        $this->sc->poster = $this->poster;
        $this->guest = check_class('252');
        $this->sc->guest = $this->guest;
        $this->approve = true;
        if ($this->guest && $this->prefs['emailConfirmation'])
        {
            $this->approve = false;
            $this->emailapprove = true;
        } elseif (check_class($this->prefs['autoApproverClass']) || $this->admin)
        {
            $this->approve = true;
        } elseif ($this->guest && $this->prefs['modApproveGuests'])
        {
            $this->approve = false;
            $this->modapprove = true;
        }

        $this->viewer = check_class($this->prefs['readClass']) || $this->poster;

        $this->perpage = (int)$this->prefs['entriesPerPage'];
        $this->bbcode = ($this->prefs['allowBBCode'] == 1 ? true : false);
        $this->showsites = ($this->prefs['guestbook_showsites'] == 1 ? true : false);
        $this->multiip = ($this->prefs['allowMultiIP'] == 1 ? true : false);

        $this->links = ($this->prefs['allowLinksPost'] == 1 ? true : false);
        // $this->image = ($this->prefs['guestbook_image'] == 1 && extension_loaded("gd") ? true : false);
        $this->image = ($this->prefs['useCaptcha'] == 1 && (extension_loaded("gd") ? true : false));
        $this->sc->use_imagecode = $this->image;


        $this->edittime = time() + ($this->prefs['entryTimeout'] * 60);
        //var_dump($this->edittime);
        $this->ipAddress = e107::getIP();
        $this->frm = new e_form(true);
        $this->timeout = $_SESSION['guestbookStartTime'];
        //        var_dump($this->timeout);

        $this->ipBlock = $this->multiIPBlocked();
        if ($this->ipBlock)
        {
            $this->sc->multiBlock = true;
            $this->mes->addInfo(LAN_GB_ERROR_MULTIIP);
        }
    }
    //
    //    /**
    //     * Setters and getters for accessing the class properties
    //     */
    /**
     * guestbook::fromSession()
     * 
     * @param mixed $from
     * @return void
     */
    function fromSession($from = null)
    {
        if (is_null($from))
        {
            $this->from = $_SESSION['guestbook_from'];
        } else
        {
            $_SESSION['guestbook_from'] = $from;
            $this->from = $from;
        }
    }
    //    /**
    //     * guestbook::setAjaxParm()
    //     *
    //     * Method takes the parameter and sets property
    //     *
    //     * @param  string $parm
    //     * @return
    //     */
    //    public function setAjaxParm($parm = null)
    //    {
    //        if ($parm !== null)
    //        {
    //            // set property
    //            $this->ajaxparm = $parm;
    //            return true;
    //        } else
    //        {
    //            return false;
    //        }
    //    }
    //    /**
    //     * guestbook::getAjaxParm()
    //     *
    //     * @return
    //     */
    //    public function getAjaxParm()
    //    {
    //        return $this->ajaxparm;
    //    }
    //

    /**
     * guestbook::processMain(void)
     * 
     * Process the page 
     * 
     * @return string
     */
    public function processMain()
    {
        // if we are using the secure image for guests then check it, set in __construct
        if ($this->image)
        {
            $this->verified_code = e107::getSecureImg()->verify_code($_POST['rand_num'], $_POST['code_verify']);
        }
        $this->pageLoad(); // get 'from' for lists
        $retval = "";

        if ($this->action == 'confirm')
        {
            $retval = $this->processConfirm(e_QUERY);
            $this->action = 'view';
        } else
        {
            // check the actions which must be admin perms
            if (in_array($this->action, $this->adminCommands))
            {
                if ($this->admin)
                {
                    switch ($this->action)
                    {
                        case 'unapprove':
                            $retval = $this->unapproveEntry();
                            $this->action = 'view';
                            break;
                        case 'approve':
                            $retval = $this->approveEntry();
                            $this->action = 'view';
                            break;
                        case 'delete':
                            $retval = $this->processDelete();
                            break;
                        case 'edit':
                            $retval = $this->editEntry();
                            break;
                        case 'delok':
                            $retval = $this->deleteEntry();
                            $this->action = 'list';
                            break;
                    } // end switch
                } // end if admin
                else
                {
                    $this->mes->addError(LAN_GB_ERROR_NOPERM);
                }
            } // end if admin command
            if (in_array($this->action, $this->posterCommands))
            {
                if ($this->poster)
                {
                    switch ($this->action)
                    {
                        case 'add':
                            $retval = $this->editEntry();
                            break;
                        case 'delcanc':
                            $this->action = 'view';
                            break;
                        case 'cancel':
                            $this->action = 'view';
                            break;
                        case 'save':
                            if (!$this->image || ($this->image && $this->verified_code))
                            {
                                if ($this->processSubmit())
                                {
                                    $this->action = 'view';
                                } else
                                {
                                    $this->action = 'list';
                                }
                            } elseif ($this->image && !$this->verify_code)
                            {
                                $this->mes->addWarning('Invalid Capture');
                                $this->action = 'view';
                            }
                            break;
                    } // end switch
                } // end if poster
                else
                {
                    $this->mes->addError(LAN_GB_ERROR_NOPERM);
                }
            }

            // var_dump($this->viewer);
            if (in_array($this->action, $this->viewerCommands))
            {
                if ($this->viewer)
                {
                    if ($this->id == 0)
                    {
                        $this->action = 'list';
                    }
                    //var_dump($this->action);
                    switch ($this->action)
                    {
                        case 'view':
                            $retval = $this->viewRecord();
                            break;
                        case 'list':
                        default:
                            $retval = $this->listEntries();
                    } // end switch
                } // endif viewer
                else
                {
                    $this->mes->addError(LAN_GB_ERROR_NOPERM);
                }
            }
        }
        return $retval;
    }
    /**
     * guestbook::pageLoad(void)
     *
     * Load the page, fetch the value of 'from' for list paging 
     * @return void
     */
    protected function pageLoad()
    {
        $this->action = varset($_GET['action'], 'list');
        $this->id = varset($_GET['id'], null);

        $tmp = e_QUERY;
        if (empty($tmp))
        {
            $this->fromSession(0);
        }
        if (isset($_GET['from']))
        {
            $this->fromSession($_GET['from']);
        } else
        {
            $this->fromSession();
        }
        if ($this->action == 'cancel' || varset($_POST['guestbook_cancel'], false) || varset($_POST['guestbook_delcanc'], false))
        {
            $this->action = 'view';
            $_SESSION['guestbookStartTime'] = 0;
            $this->mes->addInfo(LAN_GB_CANCELLED);
        }
    }

    /**
     * guestbook::formDecode()
     * 
     * @return void
     * @todo   refactor and check
     */
    protected function formDecode()
    {
        $this->record['guestbook_id'] = (int)$_POST['guestbook_id'];
        $this->record['guestbook_name'] = $this->tp->toDB($_POST['guestbook_name']);
        $this->record['guestbook_email'] = $this->tp->toDB($_POST['guestbook_email']);
        $this->record['guestbook_url'] = $this->tp->toDB($_POST['guestbook_url']);
        $this->record['guestbook_comment'] = $this->tp->toDB($_POST['guestbook_comment']);
        $this->record['guestbook_udf1'] = $this->tp->toDB($_POST['guestbook_udf1']);
        $this->record['guestbook_udf2'] = $this->tp->toDB($_POST['guestbook_udf2']);
        $this->record['guestbook_udf3'] = $this->tp->toDB($_POST['guestbook_udf3']);
        $this->record['guestbook_udf4'] = $this->tp->toDB($_POST['guestbook_udf4']);
        $this->record['guestbook_udf5'] = $this->tp->toDB($_POST['guestbook_udf5']);
        $this->record['guestbook_udf6'] = $this->tp->toDB($_POST['guestbook_udf6']);
        // work out if approved or not
        // Approval types - email confirm, mod approve, class, none required,

        if ($this->id == 0 && ($this->prefs['emailConfirmation'] == 1 || $this->prefs['modApproveGuests'] == 1))
        {
            $this->record['guestbook_approved'] = 0;
        }
        // if new record then add the extra bits for a new entry
        if ($this->id == 0)
        {
            if (USER)
            {
                $this->record['guestbook_user'] = USERID . "." . USERNAME;
                $this->record['guestbook_userid'] = USERID;
            } else
            {
                $this->record['guestbook_user'] = '0.Guest';
                $this->record['guestbook_userid'] = 0;
            }
            $this->record['guestbook_date'] = time();
            $this->record['guestbook_ip'] = e107::getip();
            $temp = gethostbyaddr($this->record['guestbook_ip']);
            $this->record['guestbook_host'] = ($temp !== false ? $temp : $this->record['guestbook_ip']);
        }
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    protected function validate()
    {

        $errorArray = array();
        if ($this->image && !USER)
        {
            $codeVerify = trim($_POST['code_verify']);
            if (!$this->sec_img->verify_code($_POST['rand_num'], $codeVerify))
            {
                $errorArray[] = LAN_GB_ERROR_CAPTCHA;
            }
        }
        if (empty($this->record['guestbook_name']))
        {
            // if name empty (required field)
            $errorArray[] = LAN_GB_ERROR_NAME;
        }
        if (empty($this->record['guestbook_email']))
        {
            // if email empty (required field)
            $errorArray[] = LAN_GB_ERROR_EMAIL;
        }
        if (!filter_var($this->record['guestbook_email'], FILTER_VALIDATE_EMAIL))
        {
            // if email address seems to be invalid
            $errorArray[] = LAN_GB_ERROR_EMAILFORM;
        }
        if (empty($this->record['guestbook_comment']))
        {
            // if comments empty  (required field)
            $errorArray[] = LAN_GB_ERROR_MISSCOMMENT;
        }
        if ($this->prefs['allowMultiIP'] == 0 && $this->id == 0)
        {
            // if not permitted to create multiple posts from same IP address and it is a newentry
            $ip = e107::getip(); // check multi ip and not this record we are saving
            if (e107::getDb()->select('guestbook', 'guestbook_id', "where guestbook_ip = '{$ip}'", true, false))
            {
                // multiple from the same IP not allowed
                $errorArray[] = LAN_GB_ERROR_MULTIIP;
            }
        }
        $now = time();
        $gbtime = (int)$_POST['guestbook_time'];
        $diff = $now - $gbtime;
        //  if ( $this->LAN_GB_edittime > 0 && $diff > $this->LAN_GB_edittime )
        //  {
        // if max
        //      $errorArray[] = 7;
        //  }

        if ($this->prefs['allowLinksPost'] == 0 && ($this->checkLinks($this->record['guestbook_name']) || $this->checkLinks($this->record['guestbook_comment']) || $this->checkLinks($this->record['guestbook_udf1']) ||
            $this->checkLinks($this->recordT['guestbook_udf2']) || $this->checkLinks($this->record['guestbook_udf3']) || $this->checkLinks($this->record['Lguestbook_udf4']) || $this->checkLinks($this->record['guestbook_udf5'])))
        {
            $errorArray[] = "Links are not permitted in entries";
        }
        $fp = new floodprotect;
        if (!$fp->flood("guestbook", "guestbook_date"))
        {
            $errorArray[] = 'Flood';
        }
        if ($this->id == 0 && e107::getDb()->select('guestbook', 'guestbook_id', "where
    guestbook_name = '{$this->record['guestbook_name']}' and
    guestbook_email = '{$this->record['guestbook_email']}' and
    guestbook_url = '{$this->record['guestbook_url']}' and
    guestbook_comment = '{$this->record['guestbook_comment']}'", true, false))
        {
            // check not a duplicate post
            $errorArray[] = LAN_GB_ERROR_DUPLICATE;
        }
        $error = true;
        if (count($errorArray) > 0)
        {
            $error = false;
            $errorArray[] = count($errorArray);
            foreach ($errorArray as $errormsg)
            {
                $this->mes->addError($errormsg);
            }
        }
        return $error;
    }

    /**
     * guestbook::processSubmit()
     *
     * @return
     */
    protected function processSubmit()
    {

        $this->formDecode();
        if ($this->validate())
        {
            $this->saveRecord();
            return true;
        } else
        {
            return false;
        }

    }
    // GUESTBOOK_SIGN_AREA
    protected function approveEntry()
    {
        $args = array('data' => array('guestbook_approved' => 1), 'WHERE' => "guestbook_id='{$this->id}' AND guestbook_approved=0");
        $result = e107::getDb()->update('guestbook', $args, false);
        if ($result)
        {
            $this->mes->addSuccess(LAN_GB_NOWAPPROVED);
            $param['data'] = $this->getRecord();
            $param['id'] = $this->id;
            $param['prefs'] = $this->prefs;
            e107::getEvent()->trigger('guestbook_approved', $param);
        } else
        {
            $this->mes->addError(LAN_GB_UNABLEAPPROVE);
        }
    }
    /**
     * guestbook::unapproveEntry()
     * 
     * @return void
     */
    protected function unapproveEntry()
    {
        $args = array('data' => array('guestbook_approved' => 0), 'WHERE' => "guestbook_id='{$this->id}' AND guestbook_approved=1");
        $result = e107::getDb()->update('guestbook', $args, false);
        if ($result)
        {

            $this->mes->addSuccess(LAN_GB_READYUNAPPROVED);
            $subject = "Guestbook entry unapproved";
            $report = "Guestbook entry unapproved";
            $param['data'] = $this->getRecord();
            $param['id'] = $this->id;
            $param['prefs'] = $this->prefs;
            e107::getEvent()->trigger('guestbook_unapproved', $param);
        } else
        {
            $this->mes->addError(LAN_GB_UNABLEAPPROVE);
        }
    }
    /**
     * guestbook::deleteEntry()
     * 
     * @return void
     */
    protected function deleteEntry()
    {
        $param['data'] = $this->getRecord();
        $param['id'] = $this->id;
        $param['prefs'] = $this->prefs;
        $qry = "DELETE FROM #guestbook WHERE guestbook_id={$this->id}";
        if (e107::getDb()->gen($qry, false))
        {
            $this->mes->addSuccess(LAN_GB_DELETE_DELETED);
            e107::getEvent()->trigger('guestbook_deleted', $param);
        } else
        {
            $this->mes->addError(LAN_GB_ERROR_DELETING);
        }

    }
    /**
     * guestbook::saveRecord()
     * 
     * @return
     */
    protected function saveRecord()
    {
        // generate an array of data to be saved and sanitised
        // saving the record - either new or edited
        if ($this->id == 0)
        {
            // new entry
            $this->record['guestbook_date'] = time();
            // to do
            $this->record['guestbook_approved'] = 0; // set the approval according to rules
            if ($this->prefs['emailConfirmation'] == 1)
            {
                $key = $this->record['guestbook_name'] . $this->record['guestbook_email'] . date('dmY');
            }
            $this->record['guestbook_emailconfirmcode'] = md5($key);
            $tp = $this->tp;
            $args = array('data' => array(
                    'guestbook_id' => 0,
                    'guestbook_name' => $this->tp->toDB($this->record['guestbook_name']),
                    'guestbook_email' => $this->tp->toDB($this->record['guestbook_email']),
                    'guestbook_url' => $this->tp->toDB($this->record['guestbook_url']),
                    'guestbook_comment' => $this->tp->toDB($this->record['guestbook_comment']),
                    'guestbook_udf1' => $this->tp->toDB($this->record['guestbook_udf1']),
                    'guestbook_udf2' => $this->tp->toDB($this->record['guestbook_udf2']),
                    'guestbook_udf3' => $this->tp->toDB($this->record['guestbook_udf3']),
                    'guestbook_udf4' => $this->tp->toDB($this->record['guestbook_udf4']),
                    'guestbook_udf5' => $this->tp->toDB($this->record['guestbook_udf5']),
                    'guestbook_udf6' => $this->tp->toDB($this->record['guestbook_udf6']),
                    'guestbook_date' => $this->tp->toDB($this->record['guestbook_date']),
                    'guestbook_user' => $this->tp->toDB($this->record['guestbook_user']),
                    'guestbook_approved' => $this->tp->toDB($this->record['guestbook_approved']),
                    'guestbook_ip' => $this->tp->toDB($this->record['guestbook_ip']),
                    'guestbook_host' => $this->tp->toDB($this->record['guestbook_host']),
                    'guestbook_emailconfirmcode' => $this->tp->toDB($this->record['guestbook_emailconfirmcode']),
                    ));
            $result = e107::getDb()->insert("guestbook", $args, false);
            if ($result && $this->prefs['emailConfirmation'] == 1)
            {
                $this->id = $result;
                $this->mes->addSuccess(LAN_GB_SAVE_CREATED);
                $this->mes->addInfo(LAN_GB_SAVE_EMAILSENT);
                $param['data'] = $this->getRecord();
                $param['id'] = $this->id;
                $param['prefs'] = $this->prefs;
                $this->sendEmail($param); //   e107::getEvent()->trigger( 'guestbook_new', $param );
            } elseif ($result && $this->prefs['emailConfirmation'] == 0)
            {
                $this->mes->addSuccess(LAN_GB_SAVE_CREATED);
                $this->id = $result;
                $param['data'] = $this->getRecord();
                $param['id'] = $this->id;
                $param['prefs'] = $this->prefs;
                e107::getEvent()->trigger('guestbook_new', $param);
            } else
            {
                $this->mes->addError(LAN_GB_SAVE_UNCREATE);
            }

        } else
        {
            // update existing
            $args = array('data' => array(
                    'guestbook_name' => $this->tp->toDB($this->record['guestbook_name']),
                    'guestbook_email' => $this->tp->toDB($this->record['guestbook_email']),
                    'guestbook_url' => $this->tp->toDB($this->record['guestbook_url']),
                    'guestbook_comment' => $this->tp->toDB($this->stripHTMLTags($this->record['guestbook_comment'])),
                    'guestbook_udf1' => $this->tp->toDB($this->record['guestbook_udf1']),
                    'guestbook_udf2' => $this->tp->toDB($this->record['guestbook_udf2']),
                    'guestbook_udf3' => $this->tp->toDB($this->record['guestbook_udf3']),
                    'guestbook_udf4' => $this->tp->toDB($this->record['guestbook_udf4']),
                    'guestbook_udf5' => $this->tp->toDB($this->record['guestbook_udf5']),
                    'guestbook_udf6' => $this->tp->toDB($this->record['guestbook_udf6']),
                    ), 'WHERE' => " guestbook_id={$this->id} ");
            $result = e107::getDb()->update("guestbook", $args, false);
            if ($result > 0)
            {
                $this->mes->addSuccess(LAN_GB_SAVE_SAVED);
                $param['data'] = $this->getRecord();
                $param['id'] = $this->id;
                $param['prefs'] = $this->prefs;
                e107::getEvent()->trigger('guestbook_edited', $param);
            } elseif ($result === 0)
            {
                $this->mes->addInfo(LAN_GB_SAVE_NOCHANGE);
            } else
            {
                $this->mes->addError(LAN_GB_SAVE_UNABLE);
            }
        }
        return $result;
    }
    function stripHTMLTags($text)
    {
        // $text=$this->tp->toText($text);
        $text = strip_tags($text);
        $search = array(
            '[html]',
            '[/html]' , 
            "&amp;#039;",
            "&amp;#036;",
            "&#039;",
            "&#036;",
            "&#092;",
            "&amp;#092;");
        $replace = array(
            '',
            '',
            "'",
            '$',
            "'",
            '$',
            "\\",
            "\\");
        $text = str_replace($search, $replace, $text);
        return $text;
    }
    /**
     * guestbook::getRecord()
     * 
     * @param integer $id
     * @return
     */
    function getRecord($id = 0)
    {
        $bbcode = ($this->prefs['allowBBCode'] == 1 ? true : false);
        e107::getDb()->select("guestbook", "*", "guestbook_id={$id}", false, false);
        $row = e107::getDb()->fetch();
        if ($row)
        {

            // parse all toHTML from db
            $retval['guestbook_name'] = $this->tp->toHTML($row['guestbook_name'], false);
            $retval['guestbook_email'] = $this->tp->toHTML($row['guestbook_email'], false);
            $retval['guestbook_url'] = $this->tp->toHTML($row['guestbook_url'], false);
            $retval['guestbook_comment'] = $this->tp->toHTML($row['guestbook_comment'], $bbcode);
            $retval['guestbook_userid'] = (int)$row['guestbook_userid'];
            $retval['guestbook_udf1'] = $this->tp->toHTML($row['guestbook_udf1'], false);
            $retval['guestbook_udf2'] = $this->tp->toHTML($row['guestbook_udf2'], false);
            $retval['guestbook_udf3'] = $this->tp->toHTML($row['guestbook_udf3'], false);
            $retval['guestbook_udf4'] = $this->tp->toHTML($row['guestbook_udf4'], false);
            $retval['guestbook_udf5'] = $this->tp->toHTML($row['guestbook_udf5'], false);
            $retval['guestbook_date'] = (int)$row['guestbook_date'];
            $retval['guestbook_user'] = $this->tp->toHTML($row['guestbook_user'], false);
            $retval['guestbook_approved'] = (int)$row['guestbook_approved'];
            $retval['guestbook_ip'] = $this->tp->toHTML($row['guestbook_ip'], false);
            $retval['guestbook_host'] = $this->tp->toHTML($row['guestbook_host'], false);
            $retval['guestbook_emailconfirmcode'] = $this->tp->toHTML($row['guestbook_emailconfirmcode'], false);
        } else
        {
            $retval = false;
        }
        return $retval;
    }
    /**
     * guestbook::sendEmail()
     * 
     * @param  mixed $theRecord
     * @return
     */
    protected function sendEmail($theRecord)
    {
        //        var_dump( $theRecord );
        $this->sc->datarow = $theRecord;
        $this->sc->datarow['guestbook_sitename'] = SITENAME;
        $link = e_SELF . "?" . $theRecord['data']['guestbook_emailconfirmcode'] . ".confirm." . $this->id;
        $this->sc->datarow['emailConfirmationlink'] = "<a href='{$link}'>Click to Confirm</a>";
        $this->sc->datarow['guestbook_copylink'] = $link;
        include_once "templates/email_template.php";
        $EMAIL_TEMPLATE['guestbookConfirm']['body'] = $this->tp->parseTemplate($EMAIL_TEMPLATE['guestbookConfirm']['body'], true, $this->sc);
        $eml = $EMAIL_TEMPLATE['guestbookConfirm'];
        //		- template to use. 'default'
        // $eml['shortcodes']		- array of shortcode values. eg. array('MY_SHORTCODE'=>'12345');
        $mailout = new e107Email;
        $result = $mailout->sendEmail($theRecord['data']['guestbook_email'], $theReecord['data']['guestbook_name'], $eml, false);
        return $result;
    }

    /**
     * guestbook::clear_cache()
     * 
     * @return void
     */
    function clear_cache()
    {
        global $e107cache;
        $e107cache->clear('nq_guestbook');
    }

    /**
     * guestbook::secure_image()
     * 
     * @return
     */
    function secure_imagex()
    {
        global $sec_img; // if ( !USER && $this->LAN_GB_image )
        //  {
        if ($this->image)
        {
            $retval = " <input type = 'hidden' name = 'rand_num' value = '" . $securecodeimg = $this->sec_img->random_number . "' >
    " . $sec_img->r_image() . " <br /> <input class = 'tbox' type = 'text' name = 'code_verify' id = 'code_verify' size = '15' maxlength = '20' > ";
            //  }
            $this->sc->datarow['secure_image'] = $retval;
        }
        return;
    }
    /**
     * guestbook::checkMultiIp()
     *
     * @return
     */
    protected function allowMultiIp()
    {
        $retval = false;
        if ($this->prefs['allowMultiIP'] == 0)
        {
            // disallow multiple from one IP
            // check if this IP used before
            $ip = e107::getip();
            $qry = "SELECT guestbook_id from #guestbook where guestbook_ip='{$ip}' ";
            if (e107::getDb() - gen($qry, false))
            {
                $retval = false;
            } else
            {
                $retval = true;
            }
        } else
        {
            // multi OK
            $retval = true;
        }
        return $retval;
    }
    /**
     * guestbook::processConfirm()
     * 
     * @param  mixed $code
     * @return void
     */
    protected function processConfirm($code)
    {
        $tmp = explode('.', $code, 3);
        $cCode = $tmp[0];
        $id = (int)$tmp[2];
        $qry = "SELECT guestbook_id,guestbook_approved FROM #guestbook where guestbook_id={$id} AND guestbook_emailconfirmcode='{$cCode}'";
        if (e107::getDb()->gen($qry, false))
        {
            $row = e107::getDb()->fetch();
            if ($row['guestbook_approved'] == 1)
            {
                // already approved
                $this->mes->addInfo(LAN_GB_READYAPPROVED);
            } else
            {
                // not approved so confirm it
                $qry = "UPDATE #guestbook SET guestbook_approved=1 where guestbook_id={$id} AND guestbook_emailconfirmcode='{$cCode}'";
                if (e107::getDb()->gen($qry, false))
                {
                    $this->mes->addSuccess(LAN_GB_NOWAPPROVED);
                } else
                {
                    $this->mes->addError(LAN_GB_UNABLEAPPROVE);
                }
            }
        } else
        {
            $this->mes->addError(LAN_GB_NOFINDAPPROVED);
        }
    }
    /**
     * guestbook::processDelete()
     * 
     * @return
     */
    protected function processDelete()
    {
        if ($this->id > 0)
        {
            // get the requisite record.
            $qry = "SELECT * FROM #guestbook WHERE guestbook_id='{$this->id}'";
            if (e107::getDb()->gen($qry, false))
            {
                $row = e107::getDb()->fetch();
                $this->sc->datarow = $row;
            }
            $tag['guestbook_id'] = $this->id;
            $link = e107::url('guestbook', 'delok', $tag);
            $linkCancel = e107::url('guestbook', 'delcanc', $tag);
            $this->sc->datarow['guestbook_delcanc'] = '<a href="' . $linkCancel . '"><button type="button" class="btn btn-secondary">' . LAN_GB_DELETECANCEL . '</button></a>';
            $this->sc->datarow['guestbook_delok'] = '<a href="' . $link . '"><button type="button" class="btn btn-danger">' . LAN_GB_DELETEOK . '</button></a>';
            $retval .= $this->tp->parsetemplate($this->template->DELETE(), true, $this->sc);
        }
        return $retval;
    }


    /**
     * guestbook::viewRecord()
     * 
     * @return
     */
    protected function viewRecord()
    {
        // var_dump($this->id);
        if ($this->id > 0)
        {

            // get the requisite record.
            $row = $this->getRecord($this->id);
            if ($row)
            {
                $this->sc->datarow = $row;
                $this->sc->datarow['guestbook_date'] = $this->gen->convert_date($this->sc->datarow['guestbook_date'], 'short');
                $this->getUDF('view', $row);
                $tag['guestbook_id'] = $this->id;
                $link = $this->sc->datarow['guestbook_edit'] = e107::url('guestbook', 'edit', $tag);
                $this->sc->datarow['guestbook_approve'] = e107::url('guestbook', 'approve', $tag);
                $this->sc->datarow['guestbook_unapprove'] = e107::url('guestbook', 'unapprove', $tag);
                $this->sc->datarow['guestbook_delete'] = e107::url('guestbook', 'delete', $tag);
                $this->template->udfs[1] = $this->prefs['udfActive1'];
                $this->template->udfs[2] = $this->prefs['udfActive2'];
                $this->template->udfs[3] = $this->prefs['udfActive3'];
                $this->template->udfs[4] = $this->prefs['udfActive4'];
                $this->template->udfs[5] = $this->prefs['udfActive5'];
                $this->template->udfs[6] = $this->prefs['udfActive6']; // var_dump($this->prefs);
                $retval = $this->tp->parsetemplate($this->template->VIEW($this->admin), false, $this->sc);
            }
        }
        return $retval;
    }
    /**
     * guestbook::getUDF()
     * 
     * @param  string $mode
     * @param  mixed  $row
     * @return void
     */
    protected function getUDF($mode = 'view', $row)
    {
        $this->sc->datarow['udf_name'][1] = $this->prefs['udfName1'];
        $this->sc->datarow['udf_name'][2] = $this->prefs['udfName2'];
        $this->sc->datarow['udf_name'][3] = $this->prefs['udfName3'];
        $this->sc->datarow['udf_name'][4] = $this->prefs['udfName4'];
        $this->sc->datarow['udf_name'][5] = $this->prefs['udfName5'];
        $this->sc->datarow['udf_name'][6] = $this->prefs['udfName6'];
        if ($mode == 'edit')
        {
            $this->sc->datarow['udf'][1] = $this->frm->text('udf1', $row['guestbook_udf1']);
            $this->sc->datarow['udf'][2] = $this->frm->text('udf2', $row['guestbook_udf2']);
            $this->sc->datarow['udf'][3] = $this->frm->text('udf3', $row['guestbook_udf3']);
            $this->sc->datarow['udf'][4] = $this->frm->text('udf4', $row['guestbook_udf4']);
            $this->sc->datarow['udf'][5] = $this->frm->text('udf5', $row['guestbook_udf5']);
            $this->sc->datarow['udf'][6] = $this->frm->text('udf6', $row['guestbook_udf6']);
        } else
        {
            $this->sc->datarow['udf'][1] = $row['guestbook_udf1'];
            $this->sc->datarow['udf'][2] = $row['guestbook_udf2'];
            $this->sc->datarow['udf'][3] = $row['guestbook_udf3'];
            $this->sc->datarow['udf'][4] = $row['guestbook_udf4'];
            $this->sc->datarow['udf'][5] = $row['guestbook_udf5'];
            $this->sc->datarow['udf'][6] = $row['guestbook_udf6'];
        }
    }
    /**
     * guestbook::editEntry()
     * 
     * @return
     */
    protected function editEntry()
    {
        if ($this->id == 0)
        {
            $_SESSION['guestbookStartTime'] = time();
        }

        // var_dump($this->prefs);
        $tag['guestbook_id'] = $this->id;
        $link = e107::url('guestbook', 'save', $tag);
        $retval = "
<div id='guestbookHead' >
	<form method = 'post' action = '" . $link . "' id='guestbookEdit'>
		<input type = 'hidden' name = 'guestbook-maxtime' id = 'guestbook-maxtime' value = '" . $this->edittime . "' />
		<input type = 'hidden' name = 'guestbook-maxlen' id = 'guestbook-maxlen' value = '" . $this->prefs['commentMaxLen'] . "' />";
        // everything apart from list or if ID == 0
        // get the requisite record if not adding
        if ($this->id > 0)
        {
            $row = $this->getRecord($this->id);
        }
        $nameoptions = array('required' => 'required');
        $emailoptions = array('required' => 'required');
        $commentoptions = array('required' => 'required');
        $this->sc->datarow['guestbook_name'] = $this->frm->text('guestbook_name', $row['guestbook_name'], 50, $nameoptions);
        $this->sc->datarow['guestbook_email'] = $this->frm->email('guestbook_email', $row['guestbook_email'], 100, $emailoptions);
        $this->sc->datarow['guestbook_url'] = $this->frm->url('guestbook_url', $row['guestbook_url'], 100);
        if ($this->prefs['allowBBCode'] != 1)
        {
            $this->sc->datarow['guestbook_comment'] .= $this->frm->textarea('guestbook_comment', $row['guestbook_comment'], 8, 50, $commentoptions, 10);
        } else
        {
            $template = "
	<div class='btn-toolbar'>
	   {BB=b}{BB=i}{BB=u}{BB=format}{BB=left}{BB=center}{BB=right}{BB=justify}
	   {BB=list}{BB=fontcol}{BB=fontsize}{BB=emotes}
	</div>";
            $this->sc->datarow['guestbook_comment'] .= $this->frm->bbarea('guestbook_comment', $row['guestbook_comment'], $template, '_common', 'small', $commentoptions);
        } // end if
        $this->sc->datarow['guestbook_comment'] .= '
        
        <div id="guestbookRemain" class="pull-left" id="count_place">Characters Remaining <span id="count_message"></span></div>
        <div id="guestbookTimeRemain" class="pull-right" id="time_place">Edit time Remaining <span id="time_message"></span></div>';
        if ($this->prefs['udfActive1'])
        {
            $this->template->udfs[1] = 1;
            $this->sc->datarow['udf_name'][1] = $this->prefs['udfName1'];
            $this->sc->datarow['guestbook_udf'][1] = $this->frm->text('guestbook_udf1', $row['guestbook_udf1']);
        }
        if ($this->prefs['udfActive2'])
        {
            $this->template->udfs[2] = 1;
            $this->sc->datarow['udf_name'][2] = $this->prefs['udfName2'];
            $this->sc->datarow['guestbook_udf'][2] = $this->frm->text('guestbook_udf2', $row['guestbook_udf2']);
        }
        if ($this->prefs['udfActive3'] > 0)
        {
            $this->template->udfs[3] = 1;
            $this->sc->datarow['udf_name'][3] = $this->prefs['udfName3'];
            $this->sc->datarow['guestbook_udf'][3] = $this->frm->text('guestbook_udf3', $row['guestbook_udf3']);
        }
        if ($this->prefs['udfActive4'] > 0)
        {
            $this->template->udfs[4] = 1;
            $this->sc->datarow['udf_name'][4] = $this->prefs['udfName4'];
            $this->sc->datarow['guestbook_udf'][4] = $this->frm->text('guestbook_udf4', $row['guestbook_udf4']);
        }
        if ($this->prefs['udfActive5'] > 0)
        {
            $this->template->udfs[5] = 1;
            $this->sc->datarow['udf_name'][5] = $this->prefs['udfName5'];
            $this->sc->datarow['guestbook_udf'][5] = $this->frm->text('guestbook_udf5', $row['guestbook_udf5']);
        }
        if ($this->prefs['udfActive6'])
        {
            $this->template->udfs[6] = 1;
            $this->sc->datarow['udf_name'][6] = $this->prefs['udfName6'];
            $this->sc->datarow['guestbook_udf'][6] = $this->frm->text('guestbook_udf6', $row['guestbook_udf6']);
        }
        //$this->secure_image();
        $tag['guestbook_id'] = $this->id;
        $linkCancel = e107::url('guestbook', 'cancel', $tag);
        $this->sc->datarow['guestbook_cancel'] = '<a href="' . $linkCancel . '"><button type="button" class="btn btn-secondary">' . LAN_GB_DELETECANCEL . '</button></a>';
        $options = array('loading' => true, 'class' => 'btn_submit btn-success');
        $this->sc->datarow['guestbook_submit'] = $this->frm->button('guestbook_submit', 'submit', 'submit', 'Save', $options);
        $retval .= $this->tp->parsetemplate($this->template->EDIT(), true, $this->sc);
        $retval .= "
    </form>
</div>";
        return $retval;
    }

    /**
     * guestbook::checkIPBlock()
     * 
     * @return false if OK true if blocked
     */
    function multiIPBlocked()
    {
        if (!$this->multiip)
        {
            // multiple signups from same ip not allowed
            // check if this IP already guestbooked
            $qry = "SELECT guestbook_id from #guestbook where guestbook_ip='{$this->ipAddress}'";
            $result = e107::getDB()->gen($qry, false);
            if ($result)
            {
                return true;
            }
        } else
        {
            return false;
        }
    }
    /**
     * guestbook::listEntries()
     * 
     * @return
     */
    function listEntries()
    {
        if ($this->poster)
        {
            $this->sc->datarow['guestbook_add'] = true;
            $this->sc->datarow['guestbook_addnew'] = e107::url('guestbook', 'add', ' ');
        } else
        {
            $this->sc->datarow['guestbook_add'] = false;
            $this->sc->datarow['guestbook_addnew'] = '';
        }
        $text .= $this->tp->parsetemplate($this->template->LIST_DETAIL_HEADER(), true, $this->sc);
        if (!$this->admin)
        {
            $where .= " WHERE guestbook_approved = 1 ";
        }
        $total = e107::getDb()->count('guestbook', '(guestbook_id)', $where, false);
        $url = e107::url('guestbook', 'list', '');
        $parms = $total . ",,{$this->from} ,{$url}[FROM]";
        $parms = "total={$total}&amount={$this->prefs['entriesPerPage']}&current={$this->from}&type=record&url={$url}--FROM--";
        // var_dump($parms);
        $this->nextprev = $this->tp->parseTemplate(" {NEXTPREV={$parms}}");
        //var_dump($this->nextprev);
        if(!isset($this->from)){
        $this->from=0;
        }
        $qry = "SELECT * FROM #guestbook {$where} order by guestbook_date desc limit {$this->from},{$this->prefs['entriesPerPage']}  ";
        if (e107::getDb()->gen($qry, false))
        {
            while ($this->sc->datarow = e107::getDb()->fetch())
            {
                $tag['guestbook_id'] = $this->sc->datarow['guestbook_id'];
                $tag['guestbook_name'] = $this->tp->html_truncate($this->sc->datarow['guestbook_name'], 25, '', false);
                $tag['guestbook_name'] = str_replace(' ', '', ucwords(strtolower($tag['guestbook_name'])));
                $this->sc->datarow['guestbook_comment'] = $this->tp->html_truncate($this->sc->datarow['guestbook_comment'], 60, ' ...', false);
                $this->sc->datarow['guestbook_comment'] = $this->stripHTMLTags($this->sc->datarow['guestbook_comment']);
                $tag['guestbook_comment'] = str_replace(' ', '', ucwords(strtolower($tag['guestbook_comment'])));
                $url = e107::url('guestbook', 'view', $tag);
                // die($url)  ;
                $this->sc->datarow['guestbook_listdate'] = $this->gen->convert_date($this->sc->datarow['guestbook_date'], 'short');
                $link = "?guestbook_id={$this->sc->datarow['guestbook_id']}&amp;guestbook_from={$this->from}&amp;guestbook_action=";
                $link = $url;
                $text .= $this->tp->parsetemplate($this->template->LIST_DETAIL_ROW($link), true, $this->sc);
            }
        } else
        {
            $text .= $this->tp->parsetemplate($this->template->GUESTBOOKLIST_NODETAIL(), true, $this->sc);
        }
        $this->sc->datarow['guestbook_nextprev'] = $this->nextprev;
        $text .= $this->tp->parsetemplate($this->template->GUESTBOOK_DETAIL_FOOTER(), true, $this->sc);
        return $text;
    }
    /**
     * guestbook::checkLinks()
     * 
     * @param  mixed $field
     * @return
     */
    protected function checkLinks($field)
    {
        if (strlen($field) == 0)
        {
            return false;
        }
        $needle = array(
            'http:',
            'https:',
            'ssl:',
            'ftp:',
            'file:',
            'ftps:',
            'www.');
        foreach ($needle as $what)
        {
            if (($pos = stripos($field, $what)) !== false)
            {
                return $pos;
            }
        }
        return false;
    }
    /**
     * guestbook::makeToolTip()
     * 
     * @param  string $placement
     * @param  string $toolTip
     * @return
     */
    protected function makeToolTip($placement = 'bottom', $toolTip = 'Default')
    {
        return " data-toggle='guestbookToolTip' data-placement='{$placement}' data-original-title='{$toolTip}'";
    }
    /**
     * guestbook::jsonDecode()
     *
     * @param  mixed $parm
     * @return
     */
    protected function jsonDecode($parm = null)
    {
        // decode the parameter into an array
        if ($parm !== null)
        {
            $this->record = json_decode($parm, true);
        } else
        {
            $this->record = json_decode($this->ajaxparm, true);
        }
        // check that it is properly decoded json
        $jsonLastError = json_last_error();
        if ($jsonLastError === JSON_ERROR_NONE && is_array($this->ajaxdata))
        {
            return true;
        } else
        {
            return $jsonLastError;
        }
    }

    protected function processAjax()
    {
        $this->jsonDecode();
        $this->sanitize();
        $this->saveRecord();
        // log details for debuggering
        $fp = fopen("check.txt", 'w');
        $dump = "<pre>" . print_r($jsonData) . "</pre>";
        fwrite($fp, $dump);
        fclose($fp);
    }
    protected function processWarn()
    {
        return $this->signArea('approve');
    }

}
