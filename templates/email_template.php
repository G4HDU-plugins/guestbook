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

$EMAIL_TEMPLATE['guestbookConfirm']['subject']        = 'Guestbook entry at {SITENAME}';
$EMAIL_TEMPLATE['guestbookConfirm']['header']        = "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">
<html xmlns='http://www.w3.org/1999/xhtml' >
	<head>
		<meta http-equiv='content-type' content='text/html; charset=utf-8' />
		<style type='text/css'>
			body { padding:10px; background-color: green }
			div#body { padding:10px; width: 800px; background-color: #FFFFFF; border-radius: 5px; font-family: helvetica,arial }
			.video-thumbnail { max-width: 400px }
			.media img { max-width:600px }
		</style>
	</head>
	<body>
		<div id='body'>";

$EMAIL_TEMPLATE['guestbookConfirm']['body']             = "
			<div style='background-color:##dff0d9;text-align:left;width:500px;'>
				<div style='display:inline;float:left;'>
					Hi {GB_EMAILNAME},<br /><br />
					Thank you for signing our guestbook at {GB_SITENAME}. The administrator of the site requires all guests to confirm their email address
					before your guestbook submission is made visible on the site. To confirm your email address please
					click on the link below while you are connected to the internet.<br /><br />

					Please {GB_CONFIRMLINK} to confirm.
				</div>
				<div style='display:inline;float:right;width:200px;'>
					<img src='images/altsignlogo.png' />
				</div><br /><br />

				Best regards<br />
				Administrator
				<div style='clear:both;font-size:.80em;'>
					If you have problems with this link please copy and paste the following into your browser {GB_COPYLINK}
				</div>";
$EMAIL_TEMPLATE['guestbookConfirm']['footer']        = "
				<br /><br />
				{SITENAME=link}
			</div>
		</div>
	</body>
</html>";
$EMAIL_TEMPLATE['guestbookConfirm']['priority']=3;
$EMAIL_TEMPLATE['guestbookConfirm']['wordwrap']=60;
$EMAIL_TEMPLATE['guestbookConfirm']['cc']            = "";
$EMAIL_TEMPLATE['guestbookConfirm']['bcc']            = "";
$EMAIL_TEMPLATE['guestbookConfirm']['attachments']    = "";







?>
