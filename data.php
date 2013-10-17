<?php
define('DB_SRVR', 'localhost');
define('DB_USR', 'bratzfb_admin');
define('DB_PWD', 'Z(A=RT1sEFLO');
define('DB_NAME', 'bratzfb_app');

class User extends PDODB{
	public function __construct(){
		parent::__construct();
		$this->table = 'users';
	}
	public function create( $n, $m, $t ){
		$data = array( 'nombre'=>$n, 'mail'=>$m, 'ticket'=>$t );
		return $this->_insert( $data );
	}
}
class Bratz extends PDODB{
	public function __construct(){
		parent::__construct();
		$this->table = 'bratz';
	}
	public function create( $id, $m, $i ){
		$data = array( 'user_id'=>$id, 'src'=>"$i", 'message'=>"$m" );
		return $this->_insert( $data );
	}
}

class PDOResult{
	private $result;
	public function __construct( $result ){
		$this->result = $result;
	}
	public function get(){
		return $this->result;
	}
	public function count(){
		return count($this->result);
	}
	public function average( $field ){
		$total = 0;
		foreach($this->result as $value){
			$total += $value->$field;
		}
		if($this->count() <= 0) return 0;
		return $total/$this->count();
	}
}

class PDODB{
	private $DBH;
	protected $table;
	protected function __construct(){
		try{
			$this->DBH = new PDO("mysql:host=".DB_SRVR.";dbname=".DB_NAME, DB_USR, DB_PWD);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	protected function _relationship( $q ){
		$STH = $this->DBH->exec( $q );
	}
	protected function _query( $q ){
		$result = array();
		$STH = $this->DBH->query( $q );
		$STH->setFetchMode(PDO::FETCH_OBJ);
		while( $row = $STH->fetch() ){
			$result[] = $row;
		}
		return new PDOResult( $result );
	}
	protected function _insert( $data ){
		$fields = array();
		foreach($data as $key=>$value){
			$fields[] = $key;
		}
		$fieldsstr = implode( ', ', $fields );
		$fieldsval = ':' . implode( ', :', $fields );
		$STH = $this->DBH->prepare("INSERT INTO " . $this->table . " ( $fieldsstr, created_at ) value ( $fieldsval, NOW() )");
		$STH->execute( $data );
		return $this->DBH->lastInsertId();
	}
	protected function _all( $fields ){
		$result = array();
		$STH = $this->DBH->query("SELECT $fields FROM " . $this->table);
		$STH->setFetchMode(PDO::FETCH_OBJ);
		while( $row = $STH->fetch() ){
			$result[] = $row;
		}
		return new PDOResult( $result );
	}
	protected function _where( $fields, $condition ){
		$result = array();
		$STH = $this->DBH->query("SELECT $fields FROM " . $this->table . " WHERE $condition");
		if($STH){
			$STH->setFetchMode(PDO::FETCH_OBJ);
			while( $row = $STH->fetch() ){
				$result[] = $row;
			}
			return new PDOResult( $result );
		}else{
			return new PDOResult( NULL );
		}
	}
	protected function _delete( $condition ){
		$this->DBH->exec("DELETE FROM " . $this->table . " WHERE $condition");
		return true;  
	}
	protected function _update( $data, $condition ){
		$fields = array();
		foreach($data as $key=>$value){
			$fields[] = $key . '=:' . $key;
		}
		$fieldsstr = implode( ', ', $fields );
		$STH = $this->DBH->prepare("UPDATE " . $this->table . " SET $fieldsstr WHERE $condition");
		$STH->execute( $data );
		return $STH->rowCount();	
	}
}

class Validator{
	
	public static $errors;
	public static $valid = true;
	
	public static function validate( $fields ){
		foreach($fields as  $field ){
			$rules = explode( ",", $field['rules'] );
			$value = $field['value'];
			$name = $field['name'];
			foreach( $rules as $rule ){
				self::$rule( $name, $value );
			}
		}
	}
	
	private static function required( $name, $value ){
		if( !isset($value) || empty( $value ) ){
			self::$valid = false;
			self::$errors[] = "el campo " . $name . " es requerido";
		}
	}
	
	private function file( $name, $value ){
		if( !isset($value) || empty( $value ) ){
			self::$valid = false;
			self::$errors[] = "el campo " . $name . " es requerido";
		}
		if ($value['error'] > 0){
  			self::$errors[] = $value['error'];
  		}
	}

	private static function selection( $name, $value ){
		if( $value=="-1" ){
			self::$valid = false;
			self::$errors[] = "no has seleccionado un valor para " . $name;
		}
	}
	
	private static function email( $name, $value ){
		$mail_rule = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/";
		if( !preg_match( $mail_rule, $value ) ){
			self::$valid = false;
			self::$errors[] = "el campo " . $name . " no está bien escrito";
		}
	}
	
	private static function phone( $name, $value ){
		$phone_rule = "/^[0-9]{10}$/";
		if( !preg_match( $phone_rule, $value ) ){
			self::$valid = false;
			self::$errors[] = "el campo " . $name . " tiene caracteres inválidos";
		}
	}
	
	private static function ticket( $name, $value ){
		
	}
}