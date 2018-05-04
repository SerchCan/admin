<?php
    class CreditCard{
        private $number;
        private $cvc;
        private $pin;
        private $exp;
        // Generate new card 
        public function Generate($pin){
            $generator = new Plansky\CreditCard\Generator();
            $this->number= $generator->single();
            $this->cvc= rand(100,999);
            $this->pin=$pin;
            $year = intval(date("y"))+5;
            $month = date("m");
            $this->exp = $year."-".$month."-00";
            return;
        }
        // Get data from existing user
        public function fill($account){
            
            $con= new PDORepository;
            $res=$con->queryList("SELECT * FROM tarjeta INNER JOIN cuenta WHERE cuenta.numero=:account",
            array('account'=>$account))->fetch();
            
            if($res)
            {
                $this->number=$res['numero'];
                $this->pin=$res['pin'];
                $this->cvc=$res['codigo_seguridad'];
                $this->exp=$res['fecha_validez_fin'];
            }
        }
        // Add card to user
        public function addToUser($account){
            $con= new PDORepository;
            $cuenta=$con->queryList("SELECT id_cuenta from cuenta WHERE numero=:cuenta",array("cuenta"=>$account))->fetch(PDO::FETCH_ASSOC);
            $con->queryList("INSERT INTO tarjeta(numero,pin,codigo_seguridad,fecha_validez_inicio,fecha_validez_fin,estatus,id_cuenta) 
            VALUES(:number,:pin,:cvc,DATE_FORMAT(CURRENT_TIMESTAMP(), '%Y-%m-00') ,:date,'ACTIVO',:id)",
            array('number'=>$this->number,'pin'=>$this->pin,'cvc'=>$this->cvc,'date'=>$this->exp,'id'=>$cuenta['id_cuenta']));
            return;
        }
        // Get card data
        public function getCard(){
            return array('Number'=>$this->number,'Pin'=>$this->pin,'CVC'=>$this->cvc,'Expiration'=>$this->exp);
        }
    }
    
?>