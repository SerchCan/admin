<?php
/*
$pass = password_hash($_POST[password],PASSWORD_DEFAULT, [15]);
password_verify($_POST[password], $password);
*/
class PassHash {
    // cost parameter
    private static $cost = array('cost' => 15);
    
    // this will be used to generate a hash
    public static function hash($password) {
        return password_hash($password,PASSWORD_DEFAULT,self::$cost);
    }
 
    // this will be used to compare a password against a hash
    public static function check_password($hash, $password) {
        return (password_verify($password,$hash));
    }

    public static function check_password_string($pass_string, $password){
        if($pass_string === $password){
            return TRUE;
        }
        else{ return FALSE; }
    }
 
}
 
?>