<?php  
    include_once "Include.php";
    /**
     * How to program a request.php
     * if (js request POST({user:user,password:pass});
     * you get the data from POST
     */                 
    $_POST['user'];
    $_POST['pass'];
    /**
     * if (js request GET({id:1}))
     * you get data from GET
     */
    $_GET['id'];

    // to check if the variable exist you use isset() function
    isset($_POST['user']);
    isset($_GET['id']);
    // note: you can put as many variables as you want to isset
    isset($_POST['user'],$_GET['id']);

    //POST and GET variables are differents between them
    // $_POST['id'] != $_GET['id']; they are in a different context type.

?>