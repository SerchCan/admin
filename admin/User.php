<?php
	class User{
		public $isLogged=false;
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
		//This function return id and type of user if Login success, false otherwise
		public function Login($user,$password){
			$con = new PDORepository;
			$res=$con->queryList("SELECT id_usuario,tipo from usuario WHERE username=:user AND BINARY password=:pass AND estatus='ACTIVO'",
			array('user'=>$user,'pass'=>$password))->fetch(PDO::FETCH_ASSOC);
			return $res ? $res : False;
		}
		public function fillUser($id){
            $con = new PDORepository;  
            $userData = $con-> queryList("SELECT * FROM Usuario WHERE id_usuario=:id", array('id'=>$id))
                            -> fetch(PDO::FETCH_ASSOC);
            if($userData){
                $id = $userData['id_usuario'];
                $name = $userData['nombre'];
                $email = $userData['correo'];
                $user = $userData['username'];
                $password = $userData['password'];
                $status = $userData['estatus'];
                $alta = $userData['fecha_alta'];
                $address = $userData['direccion'];
                $genre = $userData['sexo'];
                $rfc = $userData['rfc'];
                $curp = $userData['curp'];
                $this->setUser($id,$name,$email,$user,$password,$status,$alta,$address,$genre,$rfc,$curp);
            }
            else{
                return "No se encontro el id de usuario en el sistema.";
            }
        }
		//Set data of the user.
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
		//getters
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