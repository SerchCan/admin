<?php
	include_once "../Conection/Conection.php";
    class User{
	    public $id;		
	    public $name;
	    public $user;
	    public $email;
	    public $status; 
	    public $address;
	    public $genre;
	    public $RFC;
	    public $CURP;
		protected $password;
	    protected $alta;
		
		public function setUser($id, $name, $email, $user, $password, $status, $alta, $address, $genre, $rfc, $curp){
			$this->id = $id;
			$this->name = $name;
			$this->email = $email;
			$this->user = $user;
			$this->status = $status; 
			$this->address = $address;
			$this->alta = $alta;
			$this->genre = $genre;
			$this->RFC = $rfc;
			$this->CURP = $curp;
			$this->password = $password;
		}
		public function getId(){
			return $this->id;
		}
		public function getName(){
            return $this->name;
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
		public function getAlta(){
			return $this->alta;
		}
	}
?>