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

define('LAN_GB_001', 'Guestbook');
define('LAN_GB_002', 'Sign our Guestbook');
define('LAN_GB_003', 'Entries');
define('LAN_GB_004', 'Sorry you are not permitted to view the guestbook');
define('LAN_GB_005', 'View all the guestbook');
define('LAN_GB_006', 'View Entry');
define('LAN_GB_007', 'Posted by : ');
define('LAN_GB_008', 'There has already been an entry made from your IP address');
define('LAN_GB_009', 'and posting of entries is limited to one per IP address');

define('LAN_GB_SIGN_001', 'Name');
define('LAN_GB_SIGN_002', 'Email');
define('LAN_GB_SIGN_003', 'Web Site');
define('LAN_GB_SIGN_004', 'Comment');
define('LAN_GB_SIGN_005', 'Enter the security code');
define('LAN_GB_SIGN_006', 'There are currently no entries in the guestbook. Please feel free to leave a comment.');
define('LAN_GB_SIGN_007', 'View web site');
define('LAN_GB_SIGN_008', 'Edit Entry');
define('LAN_GB_SIGN_009', 'Delete Entry');
define('LAN_GB_SIGN_010', 'Send an Email');
define('LAN_GB_SIGN_011', 'Editing saved');
define('LAN_GB_SIGN_012', 'Unable to save changes');
define('LAN_GB_SIGN_013', 'Thank you for posting your comment');
define('LAN_GB_SIGN_014', 'Sorry, but there was a problem posting your comment.');


define('LAN_GB_WARN_001', 'Name');
define('LAN_GB_WARN_002', 'Email');
define('LAN_GB_WARN_003', 'Web Site');
define('LAN_GB_WARN_004', 'Comment');


define('LAN_GB_DELETE_01', 'You are about to delete the entry from');
define('LAN_GB_DELETE_02', 'Delete');
define('LAN_GB_DELETE_03', 'Cancel');
define('LAN_GB_DELETE_04', 'Entry Deleted');
define('LAN_GB_DELETE_05', 'Entry Not Deleted');
define('LAN_GB_DELETEOK', 'Delete');
define('LAN_GB_DELETECANCEL', 'Cancel');

define('LAN_GB_LISTPOSTER', 'Poster');
define('LAN_GB_LISTCOMMENT', 'Comment');
define('LAN_GB_LISTPOSTED', 'Posted');
define('LAN_GB_LISTNOTAPPROVED', 'This guestbook entry has not yet been approved');



define('LAN_GB_ERROR', 'Error Codes~Security code needs to be entered~Invalid security code~Your name must be entered~Your email address needs to be entered~You must enter a comment~Only one entry may be made from each IP address~You took too long completing the form, sorry you need to start again.~Probable links found in your posting. Please remove before posting.~Flooding. Record not saved~Your email address appears to be invalid.~Duplicate posting, Entry not created.');
define('LAN_GB_MESSAGE_HTML', 'You may not post HTML');
define('LAN_GB_MESSAGE_LINK', 'You may not post LINKS');
define('LAN_GB_MESSAGE_TIME_1', 'You must complete the form within');
define('LAN_GB_MESSAGE_TIME_2', 'minutes ');
define('LAN_GB_MESSAGE_EMAIL', 'Your email address is required but only visible to administrators');
define('LAN_GB_MESSAGE_REQUIRED', '* required fields');

define('LAN_GB_UNABLEAPPROVE', 'System Error, unable to approve entry');
define('LAN_GB_NOWAPPROVED', 'This entry is now approved');
define('LAN_GB_READYAPPROVED', 'This entry is now approved');
define('LAN_GB_READYUNAPPROVED', 'This entry is now unapproved');
define('LAN_GB_NOFINDAPPROVED', 'Unable to find this entry or the link has expired');
define('LAN_GB_CANCELLED', 'Action cancelled');
define('LAN_GB_NOPERMDEL', 'You do not have permission to delete records');

define('LAN_GB_ERROR_ACTION', 'That command is not recognised.');
define('LAN_GB_ERROR_CAPTCHA', 'There was an error with the captcha code.');
define('LAN_GB_ERROR_NAME', 'Your name needs to be completed');
define('LAN_GB_ERROR_EMAIL', 'Your email address is required (but it is only visible to administrators)');
define('LAN_GB_ERROR_EMAILFORM', 'Your email address appears to be invalid');
define('LAN_GB_ERROR_COMMENT', 'You need to enter a comment');
define('LAN_GB_ERROR_MULTIIP', "Multiple entries from the same IP address are not allowed");
define('LAN_GB_ERROR_DUPLICATE', 'Duplicate entry');
define('LAN_GB_ERROR_NOPERM', 'Sorry but you do not have permission to do that');
define('LAN_GB_ERROR_MISSCOMMENT', 'Comment missing');
define('LAN_GB_ERROR_DELETING', "Error deleting record");

define('LAN_GB_DELETE_DELETED', "Entry Deleted");

define('LAN_GB_SAVE_UNABLE', "Unable to save the entry");
define('LAN_GB_SAVE_NOCHANGE', "No changes made");
define('LAN_GB_SAVE_SAVED', "The entry has been saved");
define('LAN_GB_SAVE_UNCREATE',"Unable to create your entry");
define('LAN_GB_SAVE_CREATED',"Your entry has been created");
define('LAN_GB_SAVE_EMAILSENT',"An email set for you to verify your email address");

?>
