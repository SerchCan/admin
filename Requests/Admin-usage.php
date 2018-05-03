<?php
    //include Admin library
    include_once "../admin/Admin.php";

    // Get User and Password of an admin
    $user="sergiopumas"; 
    $pass="Password"; 
    $a = new Admin($user,$pass);
    // GET: name,mail,user,pass,address,genre,RFC,CURP,IdPais,Balance,Details,Type(cliente,ejecutivo,administrador),PIN
    $name="SomeName";
    $mail="asd@gmail.com";
    $USER="aUser";
    $PASS="Password";
    $address="Av. Puerto Juarez"; 
    $genre="masculino";// (masculino, femenino)
    $RFC="RFCDATA";
    $CURP="SOMECURPNUMBERS";
    $idPais = 1; // 1 is for México (number should be on your db)
    $balance=100.5; // float value
    $Details="Description";
    $tipo='cliente'; //(cliente, ejecutivo, administrador)
    $pin=1234; //4-digit number
    
    // If u want to create a Client
    //$a->createUser($name,$mail,$USER,$PASS,$address,$genre,$RFC,$CURP,$idPais,$balance,$Details,$tipo,$pin);
    //The above function Create the user, the Account,the Card and assigns the data to the user;
    //add library;
    include_once "../admin/Client.php";
    //We can check the recent info retrieved
    $c=new Client($USER,$PASS);
    echo "Nombre: ".$c->getName()."</br>";
    echo "Tarjeta: ".json_encode($c->getCard())."</br>";
    echo "Cuenta: ".$c->getAccountNumber()."</br>";


?>