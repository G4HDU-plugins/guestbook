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
if (!defined('USER_WIDTH')) {
    define(USER_WIDTH, 'width:100%');
}
/**
 */
class guestbook_template
{
    public $udfs = array();
    /**
     * Constructor
     */
    function __construct()
    {

    }
    function VIEW($isAdmin = false)
    {

        $retval = "
<div id='guestbookEntriesContainer'   >
	<div id='guestbookBanner'>
        <div id='guestbookOurEntries'>Guestbook Entry</div>
        <div id='guestbookPleaseSign'>{GB_BACK}</div>
    </div>
    	<div id='guestbookEntries'>
		  <table id='guestbookTable' >
			<colgroup>
				<col style='width:15%;'>
				<col style='width:35%;'>
				<col style='width:15%;'>
				<col style='width:35%;'>
			</colgroup>
            <tr>
				<td class='guestbookComment' colspan='4' >
                    <blockquote class='guestbookQuote'>
                        <span>
                            {GB_VIEW_COMMENTS}
                        </span>
                    </blockquote>

                </td>
			</tr>            
			<tr>
				<td class='guestbookViewName' >" . LAN_GB_SIGN_001 . " </td>
				<td class='guestbookViewValue' >{VIEW_NAME}</td>
				<td class='guestbookViewName'  >" . LAN_GB_SIGN_003 . "</td>
				<td  >{VIEW_WEBSITE}</td>
			</tr>";
        $udfLeft = true;// start in the left column
        for ($udf = 1; $udf < 7; $udf++) {
            // for each of the 6 UDFs
            if ($this->udfs[$udf]) {
                
                // this UDF is active
                if ($udfLeft) {
                    // if we are in the left column
                    $retval .= "
			<tr>
				<td  class='guestbookViewName' >{UDF_NAME=" . $udf . "}</td>
				<td  >{VIEW_UDF=" . $udf . "}</td>";
                } else {
                    // not left column but the right one
                    $retval .= "				
                <td class='guestbookViewName'  >{UDF_NAME=" . $udf . "}</td>
				<td  >{VIEW_UDF=" . $udf . "}</td>
			</tr>";
                }// end if else
                $udfLeft = !$udfLeft;
            } 
        } // end for
       if(!$udfLeft){
        $retval .= "
                <td class='guestbookViewName'  >&nbsp;</td>
				<td  >&nbsp;</td>
			</tr>
        ";
        }
        $retval .= "

			<tr>
				<td class='guestbookViewName'  >Posted on</td>
				<td  >{VIEW_POSTED}</td>
				<td class='guestbookViewName'  >IP Address</td>
				<td  >Logged</td>
			</tr>";
        if (!$this->timeout && $this->poster) {
            $retval .= " 
			<tr
				<td cospan='4' >{GB_USER_EDIT}</td>
			</tr>";
        }
       
        $retval .= " 
 		</table>";
        if ($isAdmin) {


            $retval .= " 
            
            <div class='btn-group btn-group-sm guestbookModeration' role='note' aria-label='moderation actions'>
            <span class='guestbookModTitle'>Moderation</span><br>
                {GB_RECORD_EDIT} {GB_RECORD_APPROVED} {GB_RECORD_DELETE}
            
      		<table id='guestbookMod' >
                <colgroup>
				    <col style='width:15%;'>
				    <col style='width:35%;'>
				    <col style='width:15%;'>
				    <col style='width:35%;'>
			     </colgroup>
			     <tr>
                    <td class='guestbookViewName' >" . LAN_GB_SIGN_002 . " </td>
				    <td class='guestbookViewValue' >{VIEW_EMAIL}</td>
				    <td class='guestbookViewName'  >IP Address</td>
				    <td class='guestbookViewValue' >{VIEW_IP}</td>
			     </tr>
            </table>
            </div>
";
        }
        $retval .= "
    </div>
</div>";
        return $retval;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function EDIT($isAdmin = false)
    {
    //    var_dump($isAdmin);
        $utcTime = time() + 120;
        $retval .= "";
        $retval .= "
<div id='signAreaDisplay'  >
	<div id='guestbookSignAreaFields'>
		<table style='width:100%'>
			<colgroup>
				<col style='width:15%;'>
				<col style='width:35%;'>
				<col style='width:15%;'>
				<col style='width:35%;'>
			</colgroup>
			<tr>
				<td class='guestbookViewName' >" . LAN_GB_SIGN_001 . " *</td>
				<td class='guestbookViewValue' >{SIGN_NAME}</td>
				<td class='guestbookViewName' >" . LAN_GB_SIGN_002 . " *</td>
				<td class='guestbookViewValue' >{SIGN_EMAIL} </td>
			</tr>
			<tr>
				<td class='guestbookViewName' >" . LAN_GB_SIGN_003 . "</td>
				<td class='guestbookViewValue' >{GB_SIGN_WEBSITE}</td>
				<td class='guestbookViewName' >&nbsp;</td>
				<td class='guestbookViewValue' >&nbsp;</td>
           	</tr>";
            $udfLeft = true;// start in the left column
        for ($udf = 1; $udf < 7; $udf++) {
            // for each of the 6 UDFs
            if ($this->udfs[$udf]) {
                
                // this UDF is active
                if ($udfLeft) {
                    // if we are in the left column
                    $retval .= "
			<tr>
				<td class='guestbookViewName' >{UDF_NAME=" . $udf . "}</td>
				<td  class='guestbookViewValue' >{SIGN_UDF=" . $udf . "}</td>";
                } else {
                    // not left column but the right one
                    $retval .= "				
                <td class='guestbookViewName'  >{UDF_NAME=" . $udf . "}</td>
				<td  class='guestbookViewValue' >{SIGN_UDF=" . $udf . "}</td>
			</tr>";
                }// end if else
                $udfLeft = !$udfLeft;
            } 
        } // end for
       if(!$udfLeft){
        $retval .= "
                <td class='guestbookViewName'  >&nbsp;</td>
				<td >&nbsp;</td>
			</tr>
        ";
        }
       $retval .= "
			<tr>
				<td class='guestbookViewName' >" . LAN_GB_SIGN_004 . " *</td>
				<td colspan='3' class='guestbookViewValue' >{GB_SIGN_COMMENTS} </td>
			</tr>
			<tr>
                <td colspan='2'  style='text-align:center;' class='guestbookViewValue' >{GB_IMAGECODE_NUMBER}{GB_IMAGECODE_BOX}</td>		
                <td colspan='2' style='text-align:center;' class='guestbookViewValue' >{SIGN_SUBMIT}&nbsp;&nbsp;{SIGN_CANCEL}</td>
			</tr>
		</table>
	</div>
</div>";
        return $retval;
    }

    function LIST_DETAIL_HEADER()
    {
        $retval = "
<div id='guestbookEntriesContainer' >
	<div id='guestbookBanner' >
		<div id='guestbookOurEntries' >Our Guestbook Entries</div>
		<div id='guestbookPleaseSign' >{GB_ADDNEW}</div>
	</div>
	<div id='guestbookEntries'>
		<table id='guestbookSignTable'>
			<colgroup >
				<col class='guestbookNameCol'/>
				<col class='guestbookCommentCol'/>
				<col class='guestbookDateCol'/>
			</colgroup>
			<thead>
				<tr>
					<th >" . LAN_GB_LISTPOSTER . "</th>
					<th >" . LAN_GB_LISTCOMMENT . "</th>
					<th >" . LAN_GB_LISTPOSTED . "</th>
				</tr>
			</thead>
			<tbody class='guestbookBody'>";
        return $retval;
    }

    /**
     * guestbook_template::LIST_DETAIL_ROW()
     * 
     * @return
     */
    function LIST_DETAIL_ROW($rowID)
    {
        // *****************************************************************************
        // *
        // *	Each posted entry in the guestbook
        // *
        // *****************************************************************************
        global $sc_style;

        $retval = "
			<tr id='guestbookRow{$rowID}' class='guestbookLink' >
				<td class='guestbookName'><a href='" . $rowID . "'>{GB_NAME}</a></td>
				<td class='guestbookComment'><a href='" . $rowID . "'>{GB_COMMENTS}</a></td>
				<td class='guestbookPosted'>{GB_POSTED}</td>
			</tr>";
        return $retval;
    }

    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function GUESTBOOK_DETAIL_FOOTER()
    {
        // *****************************************************************************
        // *
        // *	Footer of the listing page
        // *
        // *****************************************************************************
        global $sc_style;
        $retval = "
			</tbody>
		</table>
	</div>
		{GB_NP}
	<div >{CAPTCHA_SECIMG}&nbsp;</div>

</div>
";
        return $retval;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function GUESTBOOKLIST_NOSIGNAREA()
    {
        // *****************************************************************************
        // *
        // *	Not permitted to sign multiple IPS
        // *
        // *****************************************************************************
        global $sc_style;
        $retval = LAN_GB_008 . ' ({GB_SIGN_IP}) ' . LAN_GB_009 . '';
        return $retval;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function GUESTBOOKLIST_NODETAIL()
    {
        // *****************************************************************************
        // *
        // *	There are no entries in the guestbook
        // *
        // *****************************************************************************
        $retval = "
	<div class='forumheader2' style='text-align:left;' colspan='2'>" .
            LAN_GB_SIGN_006 . "</div>";
        return $retval;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function GUESTBOOK_NOTPERMITTED()
    {
        // *****************************************************************************
        // *
        // *	Not permitted to access the guestbook
        // *
        // *****************************************************************************
        global $sc_style;
        $retval = '
<div class="fborder" style="' . USER_WIDTH . ';display:inline;">
	<div class="fcaption" >' . LAN_GB_001 . '</div>
	<div class="forumheader2" style="text-align:center;" >{LAN_GB_MESSAGE}</div>
	{LAN_GB_LOGO}
	<div class="forumheader3" style="text-align:center;" >' . LAN_GB_004 . '</div>
	<div class="fcaption" style="text-align:center;" >&nbsp;</div>
</div>';
        return $retval;
    }
    function GUESTBOOK_WARN()
    {
        // *****************************************************************************
        // *
        // *	Top of page
        // *
        // *****************************************************************************
        $retval = "
<div id='guestbookWarnArea' >
	<div id='guestbookWarnAreaFields'>" . LAN_GB_LISTNOTAPPROVED . "
		<table style='width:100%'>
			<colgroup>
				<col style='width:15%;'>
				<col style='width:35%;'>
				<col style='width:15%;'>
				<col style='width:35%;'>
			</colgroup>
			<tr>
				<td  >" . LAN_GB_WARN_001 . "</td>
				<td  >{GB_WARN_NAME}</td>
				<td  >" . LAN_GB_WARN_002 . "</td>
				<td  >{GB_WARN_EMAIL} </td>
			</tr>
			<tr>
				<td  >" . LAN_GB_WARN_003 . "</td>
				<td  >{GB_WARN_WEBSITE}</td>
				<td  >&nbsp;</td>
				<td  >&nbsp;</td>
			</tr>
			<tr>                
				<td  >{GB_WARN_UDF1_NAME}</td>
				<td  >{GB_WARN_UDF1}</td>
				<td  >{GB_WARN_UDF2_NAME}</td>
				<td  >{GB_WARN_UDF2}</td>
			</tr>
			<tr>
                <td  >{GB_WARN_UDF3_NAME}</td>
				<td  >{GB_WARN_UDF3}</td>
				<td  >{GB_WARN_UDF4_NAME}</td>
				<td  >{GB_WARN_UDF4}</td>
			</tr>
			<tr>
                <td  >{GB_WARN_UDF5_NAME}</td>
                <td  >{GB_WARN_UDF5}</td>
                <td  >{GB_WARN_UDF6_NAME}</td>
				<td  >{GB_WARN_UDF6}</td>
			</tr>
			<tr>
				<td  >" . LAN_GB_WARN_004 . "</td>
				<td colspan='3' >{GB_WARN_COMMENTS} </td>
			</tr>
			<tr>
				<td colspan='4' style='text-align:center;' >{GB_WARN_CANCEL}{GB_WARN_APPROVE}{GB_WARN_DELETE}</td>
			</tr>
		</table>
	</div>
</div>";
        return $retval;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function LAN_GB_EDIT()
    {
        // *****************************************************************************
        // *
        // *	Edit an entry in the guestbook
        // *
        // *****************************************************************************
        global $sc_style;
        $retval = '
<div class="fborder" style="' . USER_WIDTH . ';display:inline;">
	<div class="fcaption" >' . LAN_GB_001 . '</div>
	<div class="forumheader2" style="text-align:center;" >{LAN_GB_MESSAGE}</div>
{LAN_GB_LOGO}
			<table style="width:100%">
			<tr>
				<td class="forumheader3" style="width:25%;" >' . LAN_GB_SIGN_001 . ' *</td>
				<td class="forumheader3" style="width:75%;" >{LAN_GB_SIGN_NAME}</td>
			</tr>
			<tr>
				<td class="forumheader3" style="width:25%;" >' . LAN_GB_SIGN_002 . ' *</td>
				<td class="forumheader3" style="width:75%;" >{LAN_GB_SIGN_EMAIL} </td>
			</tr>
			<tr>
				<td class="forumheader3" style="width:25%;" >' . LAN_GB_SIGN_003 . '</td>
				<td class="forumheader3" style="width:75%;" >{LAN_GB_SIGN_WEBSITE}</td>
			</tr>
			{LAN_GB_SIGN_UDF1_NAME}{LAN_GB_SIGN_UDF1}
			{LAN_GB_SIGN_UDF2_NAME}{LAN_GB_SIGN_UDF2}
			{LAN_GB_SIGN_UDF3_NAME}{LAN_GB_SIGN_UDF3}
			{LAN_GB_SIGN_UDF4_NAME}{LAN_GB_SIGN_UDF4}
			{LAN_GB_SIGN_UDF5_NAME}{LAN_GB_SIGN_UDF5}
			{LAN_GB_SIGN_UDF6_NAME}{LAN_GB_SIGN_UDF6}
			<tr>
				<td class="forumheader3" style="width:25%;" >' . LAN_GB_SIGN_004 . ' *</td>
				<td class="forumheader3" style="width:75%;" >{LAN_GB_SIGN_COMMENTS} </td>
			</tr>
			<tr>
				<td class="forumheader2" style="text-align:center;" colspan="2" >{LAN_GB_EDIT_SUBMIT}</td>
			</tr>
		</table>
</div>
<div class="fcaption" style="text-align:center;" >&nbsp;</div>';
        return $retval;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function DELETE()
    {
        // *****************************************************************************
        // *
        // *	Delete an entry
        // *
        // *****************************************************************************
        $retval = '
<div class="fborder" style="' . USER_WIDTH . ';display:inline;">
	<div class="fcaption" >' . LAN_GB_001 . '</div>
	<div class="forumheader2" style="text-align:center;" >{LAN_GB_MESSAGE}</div>
{LAN_GB_LOGO}
	<div class="forumheader3" style="text-align:left;" >' . LAN_GB_DELETE_01 .
            ' <b>{VIEW_NAME}</b> with ID=<b>{VIEW_ID}</b><br />The comment begins with : <b>{VIEW_PARTCOMMENT}</b></br></br>
            <div style="text-align:center;">{DELETE_OK}&nbsp;&nbsp;&nbsp;{DELETE_CANC}</div></div>
</div>
<div class="fcaption" style="text-align:center;" >&nbsp;</div>';
        return $retval;
    }
    /**
     *
     * @param $
     * @return
     * @author
     * @version
     */
    function LAN_GB_VIEW()
    {
        global $sc_style;
        $retval = '
<div class="fborder" style="' . USER_WIDTH . ';display:inline;">
	<div class="fcaption" >' . LAN_GB_001 . '</div>
<div class="forumheader2" style="text-align:center;" >{LAN_GB_MESSAGE}</div>
{LAN_GB_LOGO}

</div>
<div class="fcaption" style="text-align:center;" >' . LAN_GB_006 . '</div>
	<div class="forumheader2" style="text-align:left;" ><span style="float:left">{LAN_GB_NAME}</span>&nbsp;<span style="float:right">{LAN_GB_MOD}</span></div>
	{LAN_GB_UDF1}
	{LAN_GB_UDF2}
	{LAN_GB_UDF3}
	{LAN_GB_UDF4}
	{LAN_GB_UDF5}
	{LAN_GB_UDF6}
	<div class="forumheader3" style="text-align:left;" >{LAN_GB_COMMENTS}</div>
	<div class="forumheader3" style="" >
		<span style="float:left">{LAN_GB_POSTED}</span>&nbsp;
		<span style="float:right">{LAN_GB_HOST}  {LAN_GB_IP}</span>
	</div>
	<div class="forumheader2" style="text-align:center;" >{LAN_GB_VIEWALL}</div>
	<div class="fcaption" style="text-align:center;" >&nbsp;</div>
	';
        return $retval;
    }
}
