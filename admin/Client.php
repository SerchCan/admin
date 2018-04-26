<?php
    include_once "User.php";
    // Client can only get his own data but cannot set it.
    
    class Client extends User{
	  private $accountNumber;
      private $cardNumber;
      public function __construct(){
          $con = new PDORepository;  
          //Example use: QUERY, ARGS -> fetchAll or fetch single record.  you decide
          //echo $con->queryList("SELECT * FROM ventas",null)->fetchAll());
          //$con->queryList("SELECT * FROM Usuarios where  ")

      }
	  public function getName(){
          return $this->name;
      }
      public function getAccountNumber(){
          return $this->AccountNumber;
      }
      public function getCard(){
          return $this->CardNumber;
      }
      public function getUser(){
          return $this->user;
      }
	  public function getMail(){
	      return $this->email;
      }  
      public function getStatus(){
          return $this->status;
      }
      public function getAddress(){
          return $this->address;
      }
      public function getGenre(){
          return $this->genre;
      }
      private function getPassword(){
          return $this->password;
      }
      public function getRFC(){
          return  $this->RFC;
      }
      public function getCURP(){
          return  $this->CURP;
      }
    }

    $client= new Client();
?>