//guestbook-comment

function gbookSaveCookie(LastTab){
    document.cookie="gbookLastTab="+LastTab+"; path=/";
    gbookSaveLastTime();
}
function gbookSaveLastTime(){
    var tnow=Math.floor(Date.now() / 1000);
    document.cookie="gbookLastTabTime="+tnow+"; path=/";
}
$(document).ready(
    function(){
        
$('#gbload').on('click', function() {
    var $this = $(this);
    $this.button('loading');
    setTimeout(function() {
        $this.button('reset');
    }, 2000);
});

/**
 *     $(".guestbook_loadercontainer, .guestbook_loader").show();

 *     $(".overlay-message").click(function() {
 *         $(".guestbook_loader, .guestbook_loader").hide();
 *     });
 */
        /*
        $('.guestbookLink').click(function(event)
        {
        var temp=$(event.target).parent().attr('id');
        var id=temp.replace("guestbookRow","");
        var t3=window.location.href.split("?");
        var goloc=t3[0]+"?view="+id;
        window.location.assign(goloc);
        //alert(goloc);
        });
        */
        $('#gbookTab1').click(
            function(){
                gbookSaveCookie('1');
            }
        );

        $('#gbookTab2').click(
            function(){
                gbookSaveCookie('2');
            }
        );

        $('#guestbookLinkSwap').click(
            function(){
                $('#guestbookSignAreaButton').hide();
                $('#guestbookSignAreaFields').fadeIn(2500);

            }
        );
        $('#guestbook-xsubmit').click(
            function(event){
                event.preventDefault();
                var dataToGo='ajaxparm='+JSON.stringify(
                    {
                        "guestbook_from": $('#guestbook-from').val(),
                        "guestbook_action": "ajaxpost",
                        "guestbook_id": $('#guestbook-id').val(),
                        "guestbook_name": $('#guestbook-name').val(),
                        "guestbook_email": $('#guestbook_email').val(),
                        "guestbook_url": $('#guestbook-url').val(),
                        "guestbook_udf1": $('#guestbook-udf1').val(),
                        "guestbook_udf2": $('#guestbook-udf2').val(),
                        "guestbook_udf3": $('#guestbook-udf3').val(),
                        "guestbook_udf4": $('#guestbook-udf4').val(),
                        "guestbook_udf5": $('#guestbook-udf5').val(),
                        "guestbook_udf6": $('#guestbook-udf6').val(),
                        "guestbook_comment": $('#guestbook-comment').val()
                    } 
                );
//                console.log(dataToGo);
                $.ajax(
                    {
                        url: 'index.php',
                        dataType: 'json',
                        type: 'get',
                        contentType: 'application/json',
                        data: dataToGo,
                        processData: false,
                        success: function( data, textStatus, jQxhr ){
//                            console.log(data);
                        },
                        error: function( jqXhr, textStatus, errorThrown ){
//                            console.log(errorThrown);
                        }
                    }
                );

            }
        );

        $('#gbookPrefForm').submit(
            function(){
                gbookSaveLastTime();
            }
        );
    }
);
jQuery(document).ready(
    function(){
        var LAN_GB_ok=true;
        var LAN_GB_msg='';
        // initially hide all containers for tab content
        jQuery('#LAN_GB_signarea').hide();
        jQuery('#LAN_GB_signareabutton').show();
        jQuery('#LAN_GB_sign').click(
            function(){
                jQuery('#LAN_GB_signarea').slideToggle(1000);
            }
        );
        
        // 
        //
        var interval = setInterval(timeRemain, 1000);
        timeRemain();
        commentLength();
        
         $("#guestbook-comment").on('keyup paste', function() {
            //console.log('called');
            commentLength();
        });

        function commentLength(){
            var limitField=$('#guestbook-comment');
            //if(limitField.length ){
                var text_max =$('#guestbook-maxlen').val();
                var fourty=text_max * 0.4;
                var twenty=text_max * 0.2;
                var text_length = limitField.val().length;
                var text_remaining = text_max - text_length;

            if(text_remaining<=0){
                var textleft=limitField.val().substring(0, text_max);
                limitField.val(textleft);
                text_length = limitField.val().length;
                text_remaining = text_max - text_length;
            }
//alert(text_remaining);
            $('#count_message').html(text_remaining );
                 // alert(text_remaining);      
            $('#guestbookRemain').addClass('guestbookMaxGreen');
            if (text_remaining<fourty){
                $('#guestbookRemain').removeClass('guestbookMaxGreen');
                $('#guestbookRemain').addClass('guestbookMaxOrange');
            }
            if (text_remaining<twenty){
                $('#guestbookRemain').removeClass('guestbookMaxGreen');
                $('#guestbookRemain').removeClass('guestbookMaxOrange');
                $('#guestbookRemain').addClass('guestbookMaxRed');
            }
          //  }
        }
        function timeRemain(){
            var target_date = $('#guestbook-maxtime').val(); // set target date
            //	console.log(target_date);
            var current_date = Math.trunc(Date.now() / 1000); // get fixed current date in seconds
                            
                                        // difference of dates
            var difference = target_date - current_date;
            //	console.log('Difference '+difference)

            // calculate dates
            var minutes = parseInt(difference / 60) % 60;
            var seconds = difference % 60;
            
                        // fix dates so that it will show two digets
            //	days = (String(days).length >= 2) ? days : '0' + days;
            //	hours = (String(hours).length >= 2) ? hours : '0' + hours;
            minutes = (String(minutes).length >= 2) ? minutes : '0' + minutes;
            seconds = (String(seconds).length >= 2) ? seconds : '0' + seconds;

            var timetext_remaining=minutes+':'+seconds;



              if(difference<=0){
                clearInterval(interval);
                $('#guestbookEdit').submit(false);
                $('#guestbook-submit').addClass('disabled');
                $('.tbox').prop('disabled', true);
             }

                 
            $('#time_message').html(timetext_remaining );
                        
            $('#guestbookTimeRemain').addClass('guestbookMaxGreen');
            if (difference<90){
                $('#guestbookTimeRemain').removeClass('guestbookMaxGreen');
                $('#guestbookTimeRemain').addClass('guestbookMaxOrange');
            }
            if (difference<45){
                $('#guestbookTimeRemain').removeClass('guestbookMaxGreen');
                $('#guestbookTimeRemain').removeClass('guestbookMaxOrange');
                $('#guestbookTimeRemain').addClass('guestbookMaxRed');
            } 
          
            }
        jQuery('#dataform').submit(
            function(event){
                LAN_GB_ok=true;
                LAN_GB_msg='<ul>';
                event.preventDefault();
                jQuery('.LAN_GB').removeClass('redit');

                var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i

                var dt=new Date();
                var nowtime=parseInt(dt.valueOf()/1000,10);
                var formtime=jQuery('#LAN_GB_time').val();
                var maxtime=jQuery('#LAN_GB_maxtime').val();
                var timepassed=nowtime-formtime;

                if(maxtime>0 && timepassed>maxtime) {
                      LAN_GB_ok=false;
                      LAN_GB_msg +='<li>The form has timed out, please reload the page</li>';
                }else{
                    if(jQuery('#LAN_GB_name').val()=='') {
                        jQuery('#LAN_GB_name').addClass('redit');
                        LAN_GB_ok=false;
                        LAN_GB_msg +='<li>Your name must be completed</li>';
                    }
                     var LAN_GB_checkemail=jQuery('#LAN_GB_email');
                     var LAN_GB_emailval=LAN_GB_checkemail.val();
                    if (filter.test(LAN_GB_emailval)) {
                        // just an extra check to ensure something entered
                        if(LAN_GB_emailval=='') {
                            LAN_GB_checkemail.addClass('redit');
                            LAN_GB_ok=false;
                            LAN_GB_msg +='<li>Your email address must be completed</li>';
                        }
                    }else{
                        LAN_GB_checkemail.addClass('redit');
                        LAN_GB_ok=false;
                        LAN_GB_msg +='<li>Your email address appears to be invalid</li>';
                    }
                    if(jQuery('#LAN_GB_comment').val()=='') {
                        jQuery('#LAN_GB_comment').addClass('redit');
                        LAN_GB_ok=false;
                        LAN_GB_msg +='<li>Your comments must be completed</li>';
                    }
                    if(jQuery('#LAN_GB_dolinks').val()=='0') {
                        // now go through each field and look for links
                        var linked=false;
                        jQuery('.LAN_GBudf').each(
                            function(index){
                                var fieldid=jQuery(this);
                                var fieldcontents=fieldid.val();
                                if(fieldcontents.match(/(?:www|ftp|http)[\.\:]+[\/\w]+/)) {
                                    fieldid.addClass('redit');
                                    LAN_GB_ok=false;
                                    linked=true;
                                }
                            }
                        );
                    }
                    if(linked) {
                        LAN_GB_ok=false;
                        LAN_GB_msg +='<li>Links are not permitted except in the web site address</li>';
                    }
                }
                LAN_GB_msg = LAN_GB_msg + '</ul>';
                if(!LAN_GB_ok) {
                     fbj_message_box('validation',LAN_GB_msg);
                }else{
                    if(jQuery('#LAN_GB_edsubit').length==1) {
                        jQuery('#LAN_GB_ajaxaction').val('LAN_GB_NOTajax')
                        jQuery(this).unbind('submit').submit(); // do the original event (submit!)
                    }else{
                        // submit form with ajax
                        jQuery('#LAN_GB_ajaxaction').val('LAN_GB_ajax');
                        jQuery.ajax(
                            {
                                type:'POST',
                                url: 'index.php',
                                data:jQuery('#dataform').serialize(),
                                success: function(LAN_GB_result) {
                                    if(LAN_GB_result.LAN_GB_type=='success') {
                                        fbj_message_box('success',LAN_GB_result.LAN_GB_msg);
                                         jQuery('#LAN_GB_signarea').slideUp(1000);
                                         jQuery('#LAN_GB_entries').html(LAN_GB_result.LAN_GB_content);
                                          jQuery('#LAN_GB_comment').val('');

                                    }else{
                                         fbj_message_box(LAN_GB_result.LAN_GB_type,LAN_GB_result.LAN_GB_msg);
                                    }
                                      LAN_GB_get_secure();
                                } // end success function
                             }
                        ); // end ajax
                    } // end if
                } // end if
            }
        );
    }
);

/**
 * Function
 *
 * @param
 * @return  void
 * @access  public
 * @author  Barry Keal
 * @version
 **/
function LAN_GB_get_secure(){

    jQuery.ajax(
        {
            type:'GET',
            data:'0.LAN_GB_sec',
            url: 'index.php',
            success: function(LAN_GB_result) {
                jQuery('#LAN_GB_secimg').html(LAN_GB_result);
            } // end success function
        }
    );
}

