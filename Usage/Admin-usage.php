<?php
    //include * libraries
    include_once "../Includes/include.php";

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
    $tipoCuenta='AHORROS'; //(Ahorros,Cheques)
    $pin=1234; //4-digit number
    
    // If u want to create a Client THIS WORKS ON EXECUTIVE CLASS TOO
    /*
        $a->createClient($name,$mail,$USER,$PASS,$address,$genre,$RFC,$CURP,$idPais,$balance,$Details,$tipoCuenta,$pin);
    */
    // If u want to create a Executive ADMIN ONLY
    /*
        $a->createExecutive($name,$mail,$USER,$PASS,$address,$genre,$RFC,$CURP,$idPais,$finContrato,$Sueldo);
    */
    // If u want to create another Admin ADMIN ONLY
    /*
        $a->createAdmin($name,$mail,$USER,$PASS,$address,$genre,$RFC,$CURP,$idPais,$finContrato,$Sueldo);
    */

    //The above function Create the user, the Account,the Card and assigns the data to the user;

    //We can check the recent info retrieved
    $c=new Client($USER,$PASS);
    echo "Nombre: ".$c->getName()."</br>";
    echo "Tarjeta: ".json_encode($c->getCard())."</br>";
    echo "Cuenta: ".$c->getAccountNumber()."</br>";


?>