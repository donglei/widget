<?php

function ee() {
    echo '<pre>';
    array_map(function($msg) {
        print_r($msg);
    }, func_get_args());
    echo '</pre>';
    die;
}

function pe() {
    echo '<pre>';
    array_map(function($msg) {
        print_r($msg);
    }, func_get_args());
    echo '</pre>';
}

function he() {
    echo "<!--\n";
    array_map(function($msg) {
        print_r($msg);
    }, func_get_args());
    echo "\n-->";
}

function getObjectMethod($obj) {
    return get_class_methods($obj);
}

function isPhoneNumber($phone_number)
{
    $pattern = '/^1[3|4|5|8][0-9]\d{8}$/';
    if(preg_match($pattern, $phone_number, $match))
    {
        return true;
    }

    return false;
}

/**
 * Rename array key from arg1 to arg2
 * 
 * array_rename($scover, 'pos_x', 'centerX');
 * arary_rename($scover, array(
 *  'pos_x'=>'centerX',
 *  'pos_y'=>'centerY'
 * ));
 */
function array_rename(&$array, $arg1, $arg2 = null){
    if($arg2 === null){
        foreach($arg1 as $k=>$v){
            array_rename($array, $k, $v);
        }
        return $array;
    }
    if(array_key_exists($arg1, $array)){
        $array[$arg2] = $array[$arg1];
        unset($array[$arg1]);
    }
    return $array;
}
function get_app_id(){
    $app_id = \UserBind::where('user_id', '=', \Auth::id())->first();
    return  $app_id->app_id;

}

if(!function_exists('getdottime'))
{
    function getdottime($date)
    {
        return date('Y.m.d', strtotime($date));
    }
}