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
if (!defined('e107_INIT') ) {
    exit;
}
e107::lan('guestbook', 'guestbook'); // e_PLUGIN.'guestbook/languages/'.e_LANGUAGE.'/guestbook.php'

// v2 e_search addon.
// Removes the need for search_parser.php, search_advanced.php and in most cases search language files.

class guestbook_search extends e_search // include plugin-folder in the name.
{

    function config()
    {

        $search = array(
            'name' => "Guestbook",
            'table' => 'guestbook as t ',
            'return_fields' => array(
                't.guestbook_id',
                't.guestbook_name',
                't.guestbook_comment',
                't.guestbook_date',
                "t.guestbook_udf1",
                "t.guestbook_udf2",
                "t.guestbook_udf3",
                "t.guestbook_udf4",
                "t.guestbook_udf5" ),

            'search_fields' => array(
                't.guestbook_name' => 1.5,
                't.guestbook_comment' => 3.0,
                "t.guestbook_udf1" => 2.0,
                "t.guestbook_udf2" => 2.0,
                "t.guestbook_udf3" => 2.0,
                "t.guestbook_udf4" => 2.0,
                "t.guestbook_udf5" => 2.0,
                ), // fields and weights.
            'advanced' => array( 'approved' => array(
                    'type' => 'dropdown',
                    'text' => "Approval",
                    'list' => array(
                        array( 'id' => 1, 'title' => 'Pending' ),
                        array( 'id' => 2, 'title' => 'Approved' ),
                        array( 'id' => 3, 'title' => 'All' ),
                        ) ), //						'author'=> array('type'	=> 'author',	'text' => LAN_SEARCH_61)
                    ),

            'order' => array( 't.guestbook_date' => DESC ),
            'refpage' => 'guestbook.php' );
        return $search;
    }


    /* Compile Database data for output */
    function compile( $row )
    {


        $res = array();
        // $res['link'] = e107::url( 'guestbook?0.view', 'guestbook', $row ); // e_PLUGIN . "faq/faq.php?cat." . $cat_id . "." . $link_id . "";
        $res['link'] =  e_PLUGIN . "guestbook/index.php?0.view.".$row['guestbook_id'];
        $res['pre_title'] = $row['guestbook_name'] ? $row['guestbook_name'] . ' | ' : "";
        $res['title'] = e107::getParser()->html_truncate($row['guestbook_comment'],  20, '', false);
        $res['summary'] =  e107::getParser()->html_truncate($row['guestbook_comment'],  200, '...', false);
        $res['detail'] = e107::getParser()->toDate($row['guestbook_date'], 'long');
        return $res;
    }


    /**
     * Optional - Advanced Where
     *
     * @param $parm - data returned from $_GET (ie. advanced fields included. in this case 'date' and 'author' )
     */
    function where( $parm = '' )
    {

        if (varset($parm['approved']) ) {
    //                var_dump($parm);
            switch ( (int)$parm['approved'] )
            {
            case 1:
                $qry = 'guestbook_approved = 0 AND';
                break;
            case 2:
                $qry = 'guestbook_approved = 1 AND';
                break;
            default:
            case 3:
                $qry = '';
                break;
            }
           
        }
        return $qry;
    }
    /*
    array (size=9)
    'q' => string 'plugin' (length=6)
    's' => string '1' (length=1)
    'r' => string '0' (length=1)
    'in' => string 'freddy' (length=6)
    'ex' => string 'fantasy' (length=7)
    'ep' => string 'berty' (length=5)
    'be' => string 'hell' (length=4)
    't' => string 'all' (length=3)
    'adv' => int 0
    */
}

?>
