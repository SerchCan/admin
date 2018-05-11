<?php
class DbHandler {
 
    private $conn;
 
    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
    function validateDate($date, $format = 'Y-m-d'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    /**
     * Getting results from query
     * @param Statement $Statement Esxceuted statement
     * @return Array $RESULT Binded results
    */
    public function get_result( $Statement ) {
        $RESULT = array();
        $Statement->store_result();
        for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
            $Metadata = $Statement->result_metadata();
            $PARAMS = array();
            while ( $Field = $Metadata->fetch_field() ) {
                $PARAMS[] = &$RESULT[ $i ][ $Field->name ];
            }
            call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
            $Statement->fetch();
        }
        return $RESULT;
    }
 
    /* ------------- `users` table method ------------------ */
 
    /**
     * Checking user's login
     * @param String $username User's username
     * @param String $password User's password
     * @return boolean User login status success/fail
     */
    public function checkLogin($username, $password) {
        // fetching user by email
        $stmt = $this->conn->prepare("SELECT password FROM usuario WHERE username = ?");
 
        $stmt->bind_param("s", $username);
 
        $stmt->execute();
 
        $stmt->bind_result($password_bd);
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // Found user with the username
            // Now verify the password
 
            $stmt->fetch();
 
            $stmt->close();
 
            if (PassHash::check_password_string($password_bd, $password)) {
                // User password is correct
                return TRUE;
            } else {
                //echo 'AQUI';
                // user password is incorrect
                return FALSE;
            }
        } else {
            $stmt->close();
 
            // user not existed with the email
            return FALSE;
        }
    }

    /**
     * Gettig user by username
     * @param String $username User's username
     * @return Array $user User's data
     */
    public function getUserByUsername($username) {
        $stmt = $this->conn->prepare("SELECT id_usuario, nombre, correo, fecha_alta, direccion, rfc, sexo FROM usuario WHERE username = ?");
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $user = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }

     /**
     * Getting user's movements
     * @param String $id User ID
     * @return Array $movements User's movements
     */
    public function getMovements($id_usuario) {
        // echo $username;
        $stmt = $this->conn->prepare("SELECT *, CASE
            WHEN tipo = \"DEPOSITO\" THEN monto * 1
                ELSE monto * -1
            END AS monto
            FROM transaccion WHERE id_tarjeta IN (SELECT id_tarjeta FROM tarjeta WHERE id_cuenta IN
                (SELECT id_cuenta FROM cuenta WHERE id_usuario = ?)) ORDER BY fecha");
        $stmt->bind_param("s", $id_usuario);
        // print_r($stmt);
        if ($stmt->execute()) {
            $movements = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $movements;
        } else {
            return NULL;
        }
    }

    /**
     * Getting user's movements
     * @param String $id User ID
     * @return Array $movements User's movements
     */
    public function getM($id_usuario) {
        // echo $id_usuario;
        $stmt = $this->conn->prepare("SELECT *, CASE
            WHEN tipo = \"DEPOSITO\" THEN monto * 1
                ELSE monto * -1
            END AS monto
            FROM transaccion WHERE id_tarjeta IN (SELECT id_tarjeta FROM tarjeta WHERE id_cuenta IN
                (SELECT id_cuenta FROM cuenta WHERE id_usuario = ?)) ORDER BY fecha");
        $stmt->bind_param("s", $id_usuario);
        // print_r($stmt);
        if ($stmt->execute()) {
            $movements = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $movements;
        } else {
            return NULL;
        }
    }

     /**
     * Getting user's movements at specific time (Month of Year)
     * @param String $id_usuario User ID
     * @param String $mes Month and Year to search details. Format: YYYY-MM
     * @param String $dia Cut day of user's account balance
     * @return Array $movements User's movements in specified time
     */
    public function getMovementsbyMonth($id_usuario, $mes, $dia) {
        // echo "string";
        // echo $id_usuario;
        $mes = $mes."-".$dia;
        $stmt = $this->conn->prepare("SELECT *, CASE
            WHEN tipo = \"DEPOSITO\" THEN monto * 1
                ELSE monto * -1
            END AS monto,
            DATE(fecha) AS fecha
            FROM transaccion WHERE id_tarjeta IN (SELECT id_tarjeta FROM tarjeta WHERE id_cuenta IN
                (SELECT id_cuenta FROM cuenta WHERE id_usuario = ?)) AND fecha > ? - INTERVAL 1 MONTH AND fecha <= ?
            ORDER BY fecha");
        $stmt->bind_param("sss", $id_usuario,$mes,$mes);
        // print_r($stmt);
        if ($stmt->execute()) {
            $movements = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $movements;
        } else {
            return NULL;
        }
    }

    /**
     * Getting user's balance
     * @param String $id_usuario User ID
     * @return Array $balance User's balance
     */
    public function getBalance($id_usuario) {
        // echo $username;
        $stmt = $this->conn->prepare("SELECT IFNULL(tipo, 'TOTAL') AS tipo, SUM((monto) * (
            CASE
                WHEN tipo = \"DEPOSITO\" THEN 1
                ELSE -1
            END
        )) AS total FROM transaccion WHERE id_tarjeta IN (SELECT id_tarjeta FROM tarjeta WHERE id_cuenta IN
                (SELECT id_cuenta FROM cuenta WHERE id_usuario = ?)) GROUP BY tipo WITH ROLLUP");
        $stmt->bind_param("s", $id_usuario);
        // print_r($stmt);
        if ($stmt->execute()) {
            $balance = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $balance;
        } else {
            return NULL;
        }
    }

    /**
     * Getting user's account balance at specific time (Month of Year)
     * @param String $id_usuario User ID
     * @param String $mes Month and Year to search details. Format: YYYY-MM
     * @param String $dia Cut day of user's account balance
     * @return Array $balance User's account balance details in specified time
     */
    public function getBalancebyMonth($id_usuario, $mes, $dia) {
        // echo $username;
        $mes = $mes."-".$dia;
        $stmt = $this->conn->prepare("SELECT IFNULL(tipo, 'TOTAL') AS tipo, SUM((monto) * (
            CASE
                WHEN tipo = \"DEPOSITO\" THEN 1
                ELSE -1
            END
        )) AS total FROM transaccion WHERE id_tarjeta IN (SELECT id_tarjeta FROM tarjeta WHERE id_cuenta IN
                (SELECT id_cuenta FROM cuenta WHERE id_usuario = ?)) AND fecha > ? - INTERVAL 1 MONTH AND fecha <= ? GROUP BY tipo WITH ROLLUP");
        $stmt->bind_param("sss", $id_usuario,$mes,$mes);
        // print_r($stmt);
        if ($stmt->execute()) {
            $balance = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $balance;
        } else {
            return NULL;
        }
    }

    /**
     * Getting user's created day (only day)
     * @param String $id_usuario User ID
     * @return Array $date User's created date
    */
    public function getDateCreated($id_usuario) {
        $stmt = $this->conn->prepare("SELECT DAY(fecha_alta) AS dia_corte FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("s", $id_usuario);
        // print_r($stmt);
        if ($stmt->execute()) {
            $date = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $date;
        } else {
            return NULL;
        }
    }

    /**
     * Getting user's account number
     * @param String $id_usuario User ID
     * @return Array $number User's account number
    */
    public function getAccountNumber($id_usuario) {
        $stmt = $this->conn->prepare("SELECT numero FROM cuenta WHERE id_usuario = ?");
        $stmt->bind_param("s", $id_usuario);
        // print_r($stmt);
        if ($stmt->execute()) {
            $number = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $number;
        } else {
            return NULL;
        }
    }

    /**
     * Getting user's data
     * @param String $id_usuario User ID
     * @return Array $data User's data
    */
    public function getDatabyID($id_usuario) {
        $stmt = $this->conn->prepare("SELECT nombre, direccion, rfc, tipo FROM usuario WHERE id_usuario = ?");
        $stmt->bind_param("s", $id_usuario);
        // print_r($stmt);
        if ($stmt->execute()) {
            $data = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $data;
        } else {
            return NULL;
        }
    }

    /**
     * Getting user's Account Status
     * @param String $id_usuario User ID
     * @return Array $res Account status datails
     */
    public function getAccountStatus($id, $mes) {
        // echo "string";
        // echo $mes;
        // echo $id;
        // print_r($stmt);
        $res = array();
        // echo($this->getDateCreated($id_usuario)[0]["dia_corte"]);
        $corte = $this->getDateCreated($id)[0]["dia_corte"];
        $corteaux = $mes.'-'.$corte;
        if (!$this->validateDate($corteaux)) {
            $corteaux = substr($corteaux,0,-3);
            $a_date = date("Y-m-t", strtotime($corteaux));
            $corte = substr($a_date,-2,2);
        }
        $res["fecha_corte"] = $mes.'-'.$corte;
        $res["numero_cuenta"] = $this->getAccountNumber($id)[0]["numero"];
        $res["datos_usuario"] = $this->getDatabyID($id);
        // $corte = $res["fecha_corte"];
        // echo $res["dia_corte"][1];
        // print_r($res["dia_corte"]);
        // echo($res["dia_corte"][0]["dia_corte"]);
        // $corte = $res["dia_corte"];
        // print_r( $corte);
        // echo $corte[0]["dia_corte"];
        // $corte = $corte[0]["dia_corte"];
        $res["movimientos"] = $this->getMovementsbyMonth($id, $mes,$corte);
        $res["saldo"] = $this->getBalancebyMonth($id,$mes,$corte);

        // $this->getMovementsbyMonth($id_usuario, $mes);
        /*if ($stmt->execute()) {
            $balance = $this->get_result($stmt);//->fetch_assoc();
            $stmt->close();
            return $balance;
        } else {
            return NULL;
        }*/
        return $res;
    }
}

?>