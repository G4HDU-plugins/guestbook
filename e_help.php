<?php
$name="e107:plugins:".basename(__DIR__);
$button = "
    <a href='http://manual.keal.me.uk/doku.php?id={$name}' id='HelpButton' target='_blank'>                    
        <button type='button' class='btn btn-info' style='font-size:14px;color:white;'>
            <span class='glyphicon glyphicon-info-sign' aria-hidden='false'></span> ".LAN_HELP_TITLE."
        </button>
    </a>";

$helplink_text = "<div style='width=100%;margin:0 auto;text-align: center;' >". LAN_HELP_LINK . "<br><br>
" . $button . "</div>";
$ns->tablerender(LAN_HELP_TITLE, $helplink_text, 'hduhelp');

?>
