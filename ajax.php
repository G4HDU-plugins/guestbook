<?php
// require_once( '../../class2.php' );
error_reporting(E_ALL);
// if ( !is_object( $guestbook_obj ) ) {
// require_once( 'includes/guestbook_class.php' );
// $guestbook_obj = new guestbook;
// }
// $output=var_export($_POST);
// if ( $_REQUEST['guestbookaction'] == 'ajaxpost' ) {
// echo json_encode(
// array(
// '1' => 'asdf',
// 'guestbook-action' => 'bazzy'
// ) );
// } else {
// }
if (isset($_GET['ajaxparm']) ) {
    // we are doing ajax call
    $this->tmp = json_decode($_GET['ajaxparm'], true);
    if (json_last_error() === JSON_ERROR_NONE && $this->tmp !== null && is_array($this->tmp) ) {
        // seems like it has decoded ok
        // sanitize
    }

    $fred = $tmp->guestbookAction;
    $dump = $_SERVER['REQUEST_METHOD'] . "  " . $fred;
    echo json_encode(array( 'dump' => $dump ));
    $fp = fopen("check.txt", 'w');
    fwrite($fp, $dump);
    fclose($fp);
    // }
}
/*
   code from http://www.if-not-true-then-false.com/
   */
function objectToArray( $d ) 
{
    if (is_object($d) ) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d) ) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}
function arrayToObject( $d ) 
{
    if (is_array($d) ) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return (object) array_map(__FUNCTION__, $d);
    } else {
        // Return object
        return $d;
    }
}
/*
   usage
$init = new stdClass;

// Add some test data
$init->foo = "Test data";
$init->bar = new stdClass;
$init->bar->baaz = "Testing";
$init->bar->fooz = new stdClass;
$init->bar->fooz->baz = "Testing again";
$init->foox = "Just test";

// Convert array to object and then object back to array
$array = objectToArray($init);
$object = arrayToObject($array);

// Print objects and array
print_r($init);
echo "\n";
print_r($array);
echo "\n";
print_r($object);

   */
?>
