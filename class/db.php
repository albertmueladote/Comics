<?php

class db{

	CONST USER = 'root';
	CONST PASS = '';
	CONST SERVER = 'localhost';
	CONST DDBB = 'comic';
	private $conn;
 
	public function __construct()
	{
	}


	private function open()
	{
		$conn = mysqli_connect( $this->getServer(), $this->getUser(), $this->getPass()) or die ("No se ha podido conectar al servidor de Base de datos");
		$db = mysqli_select_db( $conn, $this->getDDBB()) or die ("Upps! Pues va a ser que no se ha podido conectar a la base de datos");
		$this->conn  = $conn;
		return $conn;
	}

	private function close(){
		$this->conn->close();
	}

	public function getUser()
	{
		return self::USER;
	}

	public function getPass()
	{
		return self::PASS;
	}

	public function getServer()
	{
		return self::SERVER;
	}

	public function getDDBB()
	{
		return self::DDBB;
	}

	public function get_rows($item, $params = null){
		$table = $item->getTable();
		$sql = "SELECT * FROM $table";
		$first = true;
		$values = array();
		if(sizeof($params) > 0){
			$sql .= " WHERE";
			foreach ($params as $field => $value) {
				if($first){
					$sql .= " $field = ?";
					$first = false;
				}else{
					$sql .= " AND $field = ?";
				}
				array_push($values, $value);
			}
		}
		$result = $this->query($sql, $values);
		return $result;
	}

	public function select($item)
	{
		$params = array();
		$table = $item->getTable();
		if(method_exists($item, 'getId')){
			$sql = "SELECT * FROM  $table WHERE id = ?";
			$params = array($item->getId());
		}else{
			$sql = "SELECT * FROM $table WHERE ";
			$first = true;
			foreach($item->getAttr() AS $field => $param)
			{
				array_push($params, "$param");
				if($first){
					$sql .= $field . " = ?" . 
					$first = false;
				}else{
					$sql .= ' AND ' . $field . " = ?";
				}
			}
		}
		$result = $this->query($sql, $params);
		return $result;
	}

	public function insert($item)
	{
		$fields = array();
		$params = array();
		$variables = array();
		$table = $item->getTable();
		foreach($item->getAttr() AS $field => $param)
		{
			if(strcmp($field, 'id') == 0){
				$param = null;
			}
			array_push($fields, "`" . $field . "`");
			array_push($params, "$param");
			array_push($variables, '?');
		}
		$fields = implode(", ", $fields);
		$variables = implode(", ", $variables);

		$sql = "INSERT INTO $table ($fields) VALUES ($variables);";
		return $this->query($sql, $params);
	}

	public function delete($item)
	{
		$table = $item->getTable();
		$sql = "DELETE FROM $table WHERE id = ?;";
		return $this->query($sql, array($item->getId()));
	}

	public function update($item)
	{
		$table = $item->getTable();
		$values = array();
		$params = array();
		$id =  $item->getId();
		foreach($item->getAttr() AS $field => $param)
		{
			if(strcmp($field, 'id') != 0){
				if(!is_null($param)){
					array_push($values, '`' . $field . "` = ?");
					array_push($params, $param);
				}
			}
		}
		$values = implode(", ", $values);
		$sql = "UPDATE $table SET $values WHERE `id` = ?;";
		array_push($params, $id);
		return $this->query($sql, $params);
	}

	private function query($sql, $params)
	{
		$result = false;
		$conn = $this->open();
		$types = '';
		foreach ($params as $p) {
			if(is_numeric($p)){
				$floatVal = floatval($p);
				if($floatVal && intval($floatVal) != $floatVal)
				{
				    $types .= 'd';
				}else{
					$types .= 'i';
				}
			}elseif(is_string($p)){
				$types .= 's';
				
			}	
		}
		$stmt = $conn->prepare($sql);
		if($types&&$params){
            $bind_names[] = $types;
            for ($i=0; $i<count($params);$i++) 
            {
                $bind_name = 'bind' . $i;
                $$bind_name = $params[$i];
                $bind_names[] = &$$bind_name;
            }
            $return = call_user_func_array(array($stmt,'bind_param'),$bind_names);
        }

       	$stmt->execute();
       	if($stmt->affected_rows > 0){
       		$result = true;
       	}else if($stmt->affected_rows < 0){
       		$rows = $stmt->get_result();
       		if($rows){
	       		if($rows->num_rows > 0){
		       		$result = array();
			       	foreach($rows AS $row){
			       		array_push($result, $row);
			       	}
		       	}
	       	}
       	}
       	$this->close();
       	return $result;
	}
}