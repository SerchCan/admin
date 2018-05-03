<?php

    //include Client library
    include_once "../admin/Client.php";
    //Client can only get his own data.

    //you can use a get/post method for user and pass variables.
    $user="melendez"; 
    $pass="Pass"; 
    $c= new Client($user,$pass);
    // if user and pass are incorrect the function print an error message;
    //then you can access information

    //Encode as json if you retrieve information to front-end
    echo json_encode($c->getAccountNumber());

    //if u want to return more than 1 field data put all on array()
    $data=array('account'=>$c->getAccountNumber(),'card'=>$c->getCard());
    //and encode
    echo json_encode($data);

?>