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

$eplug_admin = true;

require_once "../../class2.php";

if (!getperms("P") || !e107::isInstalled('guestbook'))
{
    header("location:" . e_BASE . "index.php");
    exit();
}
e107::js('guestbook', 'js/guestbook.js', 'jquery'); // Load Plugin javascript and include jQuery framework
e107::css('guestbook', 'css/guestbook.css'); // load css file
e107::lan('guestbook', true, true); // e_PLUGIN.'guestbook/languages/'.e_LANGUAGE.'/guestbook.php'
e107::lan('guestbook', 'help', true); // e_PLUGIN.'guestbook/languages/'.e_LANGUAGE.'/guestbook.php'

require_once e_PLUGIN . 'guestbook/includes/guestbook_class.php';


/**
 * plugin_guestbook_admin
 * 
 * @package   Guestbook
 * @author    Barry Keal G4HDU
 * @copyright 2016
 * @version   $Id$
 * @access    public
 */
class plugin_guestbook_admin extends e_admin_dispatcher
{
    /**
     * Format: 'MODE' => array('controller' =>'CONTROLLER_CLASS'[, 'index' => 'list', 'path' => 'CONTROLLER SCRIPT PATH', 'ui' => 'UI CLASS NAME child of e_admin_ui', 'uipath' => 'UI SCRIPT PATH']);
     * Note - default mode/action is autodetected in this order:
     * - $defaultMode/$defaultAction (owned by dispatcher - see below)
     * - $adminMenu (first key if admin menu array is not empty)
     * - $modes (first key == mode, corresponding 'index' key == action)
     *
     * @var array
     */
    protected $modes = array('main' => array(
            'controller' => 'guestbook_main_admin_ui',
            'path' => null,
            'ui' => 'guestbook_main_admin_form_ui',
            'uipath' => null), );

    /* Both are optional
    * protected $defaultMode = null;
    * protected $defaultAction = null;
    */

    /**
     * Format: 'MODE/ACTION' => array('caption' => 'Menu link title'[, 'url' => '{e_PLUGIN}release/admin_config.php', 'perm' => '0']);
     * Additionally, any valid e107::getNav()->admin() key-value pair could be added to the above array
     *
     * @var array
     */
    protected $adminMenu = array(
        'main/list' => array('caption' => 'Entries', 'perm' => 'P'),
        'main/create' => array('caption' => 'Create', 'perm' => 'P'),

        'other0' => array('divider' => true),

        'main/prefs' => array('caption' => "Preferences", 'perm' => 'P'),
        //'main/maintenance' => array('caption' => "Maintenance", 'perm' => 'P'),
        // 'other1' => array('divider' => true),

        );

    /**
     * Optional, mode/action aliases, related with 'selected' menu CSS class
     * Format: 'MODE/ACTION' => 'MODE ALIAS/ACTION ALIAS';
     * This will mark active main/list menu item, when current page is main/edit
     *
     * @var array
     */
    protected $adminMenuAliases = array('main/edit' => 'main/list');

    /**
     * Navigation menu title
     *
     * Dsiplays at top of admin menu
     *
     * @var string
     */
    protected $menuTitle = "Guestbook";
}

/**
 * guestbook_main_admin_ui
 *
 * @package   guestbook
 * @author    Barry Keal G4HDU
 * @copyright Copyright (c) 2015
 * @version   $Id$
 * @access    public
 */
class guestbook_main_admin_ui extends e_admin_ui
{
    protected $pluginTitle = "Guestbook";
    protected $pluginName = 'guestbook';
    /**
     * Array containing a list of tabs to be displayed on the page
     *
     * @var   array of strings
     * @since 1.0.0
     */
    protected $preftabs = array(
        0 => "Classes",
        1 => "General",
        2 => "Moderation",
        3 => "UDF 1",
        4 => "UDF 2",
        5 => "UDF 3",
        6 => "UDF 4",
        7 => "UDF 5",
        8 => "UDF 6",
        );

    protected $prefs = array(
        'moderatorClass' => array(
            'title' => LAN_GB_ADMIN_CFG_2,
            'tab' => 0,
            'type' => 'userclass',
            'help' => LAN_GB_ADMIN_CFG_2),
        'postClass' => array(
            'title' => LAN_GB_ADMIN_CFG_4,
            'tab' => 0,
            'type' => 'userclass',
            'help' => LAN_GB_ADMIN_CFG_4),
        'readClass' => array(
            'title' => LAN_GB_ADMIN_CFG_3,
            'help' => LAN_GB_ADMIN_CFG_3,
            'tab' => 0,
            'type' => 'userclass'),
        'allowBBCode' => array(
            'title' => LAN_GB_ADMIN_CFG_6,
            'help' => LAN_GB_ADMIN_CFG_6,
            'tab' => 1,
            'writeParms' => array('optArray' => array(
                    '0' => LAN_NO,
                    '1' => LAN_YES,
                    )),
            'type' => 'dropdown',
            'data' => 'int'),
        'allowMultiIP' => array(
            'title' => LAN_GB_ADMIN_CFG_7,
            'help' => LAN_GB_ADMIN_CFG_7,
            'tab' => 1,
            'writeParms' => array('optArray' => array(
                    '0' => LAN_NO,
                    '1' => LAN_YES,
                    )),
            'type' => 'dropdown',
            'data' => 'int'),
        'allowLinksPost' => array(
            'title' => LAN_GB_ADMIN_CFG_8,
            'help' => LAN_GB_ADMIN_CFG_8,
            'tab' => 1,
            'writeParms' => array('optArray' => array(
                    '0' => LAN_NO,
                    '1' => LAN_YES,
                    )),
            'type' => 'dropdown',
            'data' => 'int'),
        'showURL' => array(
            'title' => LAN_GB_ADMIN_CFG_9,
            'help' => LAN_GB_ADMIN_CFG_9,
            'tab' => 1,
            'writeParms' => array('optArray' => array(
                    '0' => LAN_NO,
                    '1' => LAN_YES,
                    )),

            'type' => 'dropdown',
            'data' => 'int'),
        'entriesPerPage' => array(
            'title' => LAN_GB_ADMIN_CFG_11,
            'help' => LAN_GB_ADMIN_CFG_11,
            'tab' => 1,
            'type' => 'number',
            'data' => 'int',
            'writeParms' => 'size=mini&default=5'),
        'commentMaxLen' => array(
            'title' => LAN_GB_ADMIN_CFG_MAXLEN,
            'help' => LAN_GB_ADMIN_CFG_MAXLEN_HELP,
            'tab' => 1,
            'type' => 'number',
            'data' => 'int',
            'writeParms' => 'size=small&default=50&min=50&max=2000'),
        'entryTimeout' => array(
            'title' => LAN_GB_ADMIN_CFG_12,
            'help' => LAN_GB_ADMIN_CFG_12,
            'tab' => 1,
            'type' => 'number',
            'data' => 'int',
            'writeParms' => 'min=2&max=10&size=mini&default=5'),
        'maintDays' => array(
            'title' => LAN_GB_ADMIN_CFG_21,
            'help' => LAN_GB_ADMIN_CFG_22,
            'tab' => 1,
            'type' => 'number',
            'data' => 'int',
            'writeParms' => 'min=-1&max=90&size=mini&default=30'),
        'emailConfirmation' => array(
            'title' => LAN_GB_ADMIN_CFG_25,
            'help' => LAN_GB_ADMIN_CFG_25,
            'tab' => 2,
            'writeParms' => array('optArray' => array(
                    '0' => LAN_NO,
                    '1' => LAN_YES,
                    )),

            'type' => 'dropdown',
            'data' => 'int'),
        'modApproveGuests' => array(
            'title' => LAN_GB_ADMIN_CFG_16,
            'help' => LAN_GB_ADMIN_CFG_16,
            'tab' => 2,
            'writeParms' => array('optArray' => array(
                    '0' => LAN_NO,
                    '1' => LAN_YES,
                    )),

            'type' => 'dropdown',
            'data' => 'int'),
        'autoApproverClass' => array(
            'title' => "Auto Approve Class",
            'tab' => 2,
            'type' => 'userclass',
            'help' => "Auto Approve Class"),
        'useCaptcha' => array(
            'title' => LAN_GB_ADMIN_CFG_10,
            'help' => LAN_GB_ADMIN_CFG_10,
            'tab' => 2,
            'writeParms' => array('optArray' => array(
                    '0' => LAN_NO,
                    '1' => LAN_YES,
                    )),

            'type' => 'dropdown',
            'data' => 'int'),

        'udfName1' => array(
            'title' => "User defined field 1",
            'help' => "User defined field 1",
            'tab' => 3,
            'type' => 'text',
            'data' => 'str'),
        'udfLength1' => array(
            'title' => "User defined field 1 length",
            'help' => "User defined field 1 length",
            'tab' => 3,
            'type' => 'number',
            'data' => 'int',
            'writeParms' => 'min=2&max=10&size=mini&default=5'),
        'udfActive1' => array(
            'title' => "User defined field 1 active",
            'help' => "User defined field 1 active",
            'tab' => 3,
            'type' => 'boolean',
            'data' => 'int'),
        'udfName2' => array(
            'title' => "User defined field 2",
            'help' => "User defined field 12",
            'tab' => 4,
            'type' => 'text',
            'data' => 'str'),
        'udfLength2' => array(
            'title' => "User defined field 2 length",
            'help' => "User defined field 2 length",
            'tab' => 4,
            'type' => 'number',
            'data' => 'int',
            'writeParms' => 'min=2&max=10&size=mini&default=5'),
        'udfActive2' => array(
            'title' => "User defined field 2 active",
            'help' => "User defined field 2 active",
            'tab' => 4,
            'type' => 'boolean',
            'data' => 'int'),

        'udfName3' => array(
            'title' => "User defined field 3",
            'help' => "User defined field 3",
            'tab' => 5,
            'type' => 'text',
            'data' => 'str'),
        'udfLength3' => array(
            'title' => "User defined field 3 length",
            'help' => "User defined field 3 length",
            'tab' => 5,
            'type' => 'number',
            'data' => 'int',
            'writeParms' => 'min=2&max=10&size=mini&default=5'),
        'udfActive3' => array(
            'title' => "User defined field 3 active",
            'help' => "User defined field 3 active",
            'tab' => 5,
            'type' => 'boolean',
            'data' => 'int'),

        'udfName4' => array(
            'title' => "User defined field 4",
            'help' => "User defined field 43",
            'tab' => 6,
            'type' => 'text',
            'data' => 'str'),
        'udfLength4' => array(
            'title' => "User defined field 4 length",
            'help' => "User defined field 4 length",
            'tab' => 6,
            'type' => 'number',
            'data' => 'int',
            'writeParms' => 'min=2&max=10&size=mini&default=5'),
        'udfActive4' => array(
            'title' => "User defined field 4 active",
            'help' => "User defined field 4 active",
            'tab' => 6,
            'type' => 'boolean',
            'data' => 'int'),

        'udfName5' => array(
            'title' => "User defined field 5",
            'help' => "User defined field 5",
            'tab' => 7,
            'type' => 'text',
            'data' => 'str'),
        'udfLength5' => array(
            'title' => "User defined field 5 length",
            'help' => "User defined field 5 length",
            'tab' => 7,
            'type' => 'number',
            'data' => 'int',
            'writeParms' => 'min=2&max=10&size=mini&default=5'),
        'udfActive5' => array(
            'title' => "User defined field 5 active",
            'help' => "User defined field 5 active",
            'tab' => 7,
            'type' => 'boolean',
            'data' => 'int'),

        'udfName6' => array(
            'title' => "User defined field 6",
            'help' => "User defined field 6",
            'tab' => 8,
            'type' => 'text',
            'data' => 'str'),
        'udfLength6' => array(
            'title' => "User defined field 6 length",
            'help' => "User defined field 6 length",
            'tab' => 8,
            'type' => 'number',
            'data' => 'int',
            'writeParms' => 'min=2&max=10&size=mini&default=5'),
        'udfActive6' => array(
            'title' => "User defined field 6 active",
            'help' => "User defined field 6 active",
            'tab' => 8,
            'type' => 'boolean',
            'data' => 'int'),
        ); //xx

    /**
     * DB Table, table alias is supported
     * Example: 'r.guestbook'
     *
     * @var string
     */
    protected $table = "guestbook"; // DB Table, table alias is supported. Example: 'r.release'

    /**
     * This is only needed if you need to JOIN tables AND don't wanna use $tableJoin
     * Write your list query without any Order or Limit.
     *
     * @var string [optional]
     */
    protected $listQry = ""; // optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
    // protected $editQry = "SELECT * FROM #guestbook WHERE guestbook_id = {ID}";
    // required - if no custom model is set in init() (primary id)
    protected $pid = "guestbook_id"; // optional
    protected $perPage = 20; // default - true - TODO - move to displaySettings
    protected $batchDelete = true; // UNDER CONSTRUCTION
    protected $displaySettings = array(); // UNDER CONSTRUCTION
    /**
     * (use this as starting point for wiki documentation)
     * $fields format  (string) $field_name => (array) $attributes
     *
     * $field_name format:
     *     'table_alias_or_name.field_name.field_alias' (if JOIN support is needed) OR just 'field_name'
     * NOTE: Keep in mind the count of exploded data can be 1 or 3!!! This means if you wanna give alias
     * on main table field you can't omit the table (first key), alternative is just '.' e.g. '.field_name.field_alias'
     *
     * $attributes format:
     *     - title (string) Human readable field title, constant name will be accpeted as well (multi-language support
     *
     *       - type (string) null (means system), number, text, dropdown, url, image, icon, datestamp, userclass, userclasses, user[_name|_loginname|_login|_customtitle|_email],
     *         boolean, method, ip
     *           full/most recent reference list - e_form::renderTableRow(), e_form::renderElement(), e_admin_form_ui::renderBatchFilter()
     *           for list of possible read/writeParms per type see below
     *
     *       - data (string) Data type, one of the following: int, integer, string, str, float, bool, boolean, model, null
     *         Default is 'str'
     *         Used only if $dataFields is not set
     *           full/most recent reference list - e_admin_model::sanitize(), db::_getFieldValue()
     *       - dataPath (string) - xpath like path to the model/posted value. Example: 'dataPath' => 'prefix/mykey' will result in $_POST['prefix']['mykey']
     *       - primary (boolean) primary field (obsolete, $pid is now used)
     *
     *       - help (string) edit/create table - inline help, constant name will be accpeted as well, optional
     *       - note (string) edit/create table - text shown below the field title (left column), constant name will be accpeted as well, optional
     *
     *       - validate (boolean|string) any of accepted validation types (see e_validator::$_required_rules), true == 'required'
     *       - rule (string) condition for chosen above validation type (see e_validator::$_required_rules), not required for all types
     *       - error (string) Human readable error message (validation failure), constant name will be accepted as well, optional
     *
     *       - batch (boolean) list table - add current field to batch actions, in use only for boolean, dropdown, datestamp, userclass, method field types
     *         NOTE: batch may accept string values in the future...
     *           full/most recent reference type list - e_admin_form_ui::renderBatchFilter()
     *
     *       - filter (boolean) list table - add current field to filter actions, rest is same as batch
     *
     *       - forced (boolean) list table - forced fields are always shown in list table
     *       - nolist (boolean) list table - don't show in column choice list
     *       - noedit (boolean) edit table - don't show in edit mode
     *
     *       - width (string) list table - width e.g '10%', 'auto'
     *       - thclass (string) list table header - th element class
     *       - class (string) list table body - td element additional class
     *
     *       - readParms (mixed) parameters used by core routine for showing values of current field. Structure on this attribute
     *         depends on the current field type (see below). readParams are used mainly by list page
     *
     *       - writeParms (mixed) parameters used by core routine for showing control element(s) of current field.
     *         Structure on this attribute depends on the current field type (see below).
     *         writeParams are used mainly by edit page, filter (list page), batch (list page)
     *
     * $attributes['type']->$attributes['read/writeParams'] pairs:
     *
     * - null -> read: n/a
     *           -> write: n/a
     *
     * - dropdown -> read: 'pre', 'post', array in format posted_html_name => value
     *               -> write: 'pre', 'post', array in format as required by e_form::selectbox()
     *
     * - user -> read: [optional] 'link' => true - create link to user profile, 'idField' => 'author_id' - tells to renderValue() where to search for user id (used when 'link' is true and current field is NOT ID field)
     *                    'nameField' => 'comment_author_name' - tells to renderValue() where to search for user name (used when 'link' is true and current field is ID field)
     *           -> write: [optional] 'nameField' => 'comment_author_name' the name of a 'user_name' field; 'currentInit' - use currrent user if no data provided; 'current' - use always current user(editor); '__options' e_form::userpickup() options
     *
     * - number -> read: (array) [optional] 'point' => '.', [optional] 'sep' => ' ', [optional] 'decimals' => 2, [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY'
     *             -> write: (array) [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY', [optional] 'maxlength' => 50, [optional] '__options' => array(...) see e_form class description for __options format
     *
     * - ip        -> read: n/a
     *             -> write: [optional] element options array (see e_form class description for __options format)
     *
     * - text -> read: (array) [optional] 'htmltruncate' => 100, [optional] 'truncate' => 100, [optional] 'pre' => '', [optional] 'post' => ' px'
     *           -> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 255), [optional] '__options' => array(...) see e_form class description for __options format
     *
     * - textarea     -> read: (array) 'noparse' => '1' default 0 (disable toHTML text parsing), [optional] 'bb' => '1' (parse bbcode) default 0,
     *                                 [optional] 'parse' => '' modifiers passed to e_parse::toHTML() e.g. 'BODY', [optional] 'htmltruncate' => 100,
     *                                 [optional] 'truncate' => 100, [optional] 'expand' => '[more]' title for expand link, empty - no expand
     *                   -> write: (array) [optional] 'rows' => '' default 15, [optional] 'cols' => '' default 40, [optional] '__options' => array(...) see e_form class description for __options format
     *                                 [optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
     *
     * - bbarea -> read: same as textarea type
     *               -> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 0),
     *                 [optional] 'size' => [optional] - medium, small, large - default is medium,
     *                 [optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
     *
     * - image -> read: [optional] 'title' => 'SOME_LAN' (default - LAN_PREVIEW), [optional] 'pre' => '{e_PLUGIN}myplug/images/',
     *                 'thumb' => 1 (true) or number width in pixels, 'thumb_urlraw' => 1|0 if true, it's a 'raw' url (no sc path constants),
     *                 'thumb_aw' => if 'thumb' is 1|true, this is used for Adaptive thumb width
     *            -> write: (array) [optional] 'label' => '', [optional] '__options' => array(...) see e_form::imagepicker() for allowed options
     *
     * - icon  -> read: [optional] 'class' => 'S16', [optional] 'pre' => '{e_PLUGIN}myplug/images/'
     *            -> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
     *
     * - datestamp  -> read: [optional] 'mask' => 'long'|'short'|strftime() string, default is 'short'
     *                    -> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
     *
     * - url    -> read: [optional] 'pre' => '{ePLUGIN}myplug/'|'http://somedomain.com/', 'truncate' => 50 default - no truncate, NOTE:
     *             -> write:
     *
     * - method -> read: optional, passed to given method (the field name)
     *             -> write: optional, passed to given method (the field name)
     *
     * - hidden -> read: 'show' => 1|0 - show hidden value, 'empty' => 'something' - what to be shown if value is empty (only id 'show' is 1)
     *             -> write: same as readParms
     *
     * - upload -> read: n/a
     *             -> write: Under construction
     *
     * Special attribute types:
     * - method (string) field name should be method from the current e_admin_form_ui class (or its extension).
     *         Example call: field_name($value, $render_action, $parms) where $value is current value,
     *         $render_action is on of the following: read|write|batch|filter, parms are currently used paramateres ( value of read/writeParms attribute).
     *         Return type expected (by render action):
     *             - read: list table - formatted value only
     *             - write: edit table - form element (control)
     *             - batch: either array('title1' => 'value1', 'title2' => 'value2', ..) or array('singleOption' => '<option value="somethig">Title</option>') or rendered option group (string '<optgroup><option>...</option></optgroup>'
     *             - filter: same as batch
     *
     * @var array
     */

    protected $fields = array(
        'checkboxes' => array(
            'title' => '',
            'type' => null,
            'data' => null,
            'width' => '5%',
            'thclass' => 'center',
            'forced' => true,
            'class' => 'center',
            'toggle' => 'e-multiselect'),
        'guestbook_id' => array(
            'title' => ID,
            'type' => 'number',
            'data' => 'int',
            'width' => '5%',
            'thclass' => '',
            'forced' => true,
            'primary' => true /*, 'noedit'=>TRUE*/ ), // Primary ID is not editable
        'guestbook_name' => array(
            'title' => "Name",
            'type' => 'text',
            'data' => 'str',
            'inline' => true,
            'width' => 'auto',
            'thclass' => ''),
        'guestbook_comment' => array(
            'title' => "Comment",
            'type' => 'bbarea',
            'inline' => false,
            'data' => 'str',
            'width' => '30%',
            'thclass' => '',
            'batch' => true,
            'filter' => false),
        // 'guestbookcallsActive' => array( 'title' => "Active", 'inline' => false, 'type' => 'boolean', 'data' => 'int', 'width' => '5%', 'thclass' => 'center', 'batch' => true, 'filter' => true, 'noedit' => false ),
        'guestbook_approved' => array(
            'title' => "Approved",
            'type' => 'boolean',
            'data' => 'int',
            'width' => '5%',
            'thclass' => 'center',
            'inline' => true,
            'batch' => true,
            'filter' => true,
            'noedit' => false),
        'guestbook_confirmed' => array(
            'title' => "Confirmed",
            'type' => 'boolean',
            'data' => 'int',
            'width' => '5%',
            'thclass' => 'center',
            'inline' => true,
            'batch' => true,
            'filter' => true,
            'noedit' => false),
        'guestbook_date' => array(
            'title' => "Posted",
            'type' => 'datestamp',
            'data' => 'int',
            'width' => 'auto',
            'thclass' => '',
            'readParms' => array('mask' => 'dd mm yyyy'),
            'writeParms' => '',
            'noedit' => true),
        'options' => array(
            'title' => LAN_OPTIONS,
            'type' => null,
            'data' => null,
            'width' => '10%',
            'thclass' => 'center last',
            'class' => 'center last',
            'forced' => true)); // required - default column user prefs
    protected $fieldpref = array(
        'checkboxes',
        'guestbook_id',
        'guestbook_name',
        'guestbook_comment',
        'guestbook_approved',
        'guestbook_date',
        'options');
    protected $action = array();
    protected $subAction = array();
    protected $id = "";
    // FORMAT field_name=>type - optional if fields 'data' attribute is set or if custom model is set in init()
    /*protected $dataFields = array();*/
    // optional, could be also set directly from $fields array with attributes 'validate' => true|'rule_name', 'rule' => 'condition_name', 'error' => 'Validation Error message'
    /*protected  $validationRules = array(
    * 'release_url' => array('required', '', 'Release URL', 'Help text', 'not valid error message')
    * );*/
    // optional, if $pluginName == 'core', core prefs will be used, else e107::getPluginConfig($pluginName);
    /**
     * guestbook_main_admin_ui::observe()
     *
     * Watch for this being triggered. If it is then do something
     *
     * @return
     */
    public function observe()
    {
    }
    // optional
    public function init()
    {
    }
}

class guestbook_main_admin_form_ui extends e_admin_form_ui
{
}
new plugin_guestbook_admin();
require_once e_ADMIN . "auth.php";
e107::getAdminUI()->runPage();
require_once e_ADMIN . "footer.php";

?>
