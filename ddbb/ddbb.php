<?php
switch($ddbb_site){
	case 'local':
		$usuario = "root";
		$contrasena = "";
		$servidor = "localhost";
		$basededatos = "comic";
		break;
	case 'other':
		break;
	default:
		echo "DEFINIR A QUE BASE DE DATOS TE QUIERES CONECTAR: local, int, pro, other.";
		break;
}

if(isset($usuario) && isset($contrasena) && isset($servidor) && isset($basededatos)){
	$print = false;
	$conexion = mysqli_connect( $servidor, $usuario, $contrasena ) or die ("No se ha podido conectar al servidor de Base de datos");
	$db = mysqli_select_db( $conexion, $basededatos ) or die ( "Upps! Pues va a ser que no se ha podido conectar a la base de datos" );
}

function select($sql)
{
	global $conexion;
	global $print;
	if($print){
		_print($sql);
		return true;
	}else{
		$row = mysqli_query($conexion, $sql) or die ("Fallo en select: " . $sql); 
		$result = mysqli_fetch_object($row);
		return $result;
	}
}

function selects($sql)
{
	global $conexion;
	global $print;
	$result = array();
	if($print){
		_print($sql);
		return true;
	}else{
		$rows = mysqli_query($conexion, $sql) or die ("Fallo en selects: " . $sql); 
		while ($row = $rows->fetch_object()) {
			array_push($result, $row);
		}
		return $result;
	}
}

function insert($table, $columns, $values)
{
	global $conexion;
	global $print;
	if(strcmp(gettype($columns), 'array') == 0 && strcmp(gettype($values), 'array') == 0){
		if(sizeof($columns) == sizeof($values)){
			$sql = 'INSERT INTO ' . $table . ' (';
			$first = true;
			foreach($columns AS $c){
				if($first){
					$sql .= '\'' . $c . '\'';
					$first = false;
				}else{
						$sql .= ', \'' . $c . '\'';
				}
			}
			$sql .= ') VALUES (';
			$first = true;
			foreach($values AS $v){
				if(strcmp(gettype($v), 'string') == 0){
					$short = '%s';
				}elseif(strcmp(gettype($v), 'integer') == 0 || strcmp(gettype($v), 'double') == 0){
					$short = '%d';
				}else{
					$short = '[VALUE NOT ALLOWED]';
				}
				if($first){
					$sql .= $short;
					$first = false;
				}else{
						$sql .= ', ' . $short;
				}
			}
			$sql .= ')';
			$sql = prepare($sql, $values);
			if($print){
				_print($sql);
				return true;
			}else{
				mysqli_query($conexion, $sql) or die ("Fallo en insert: " . $sql);
				$id = mysqli_insert_id($conexion);
				return $id;
			}
		}
	}
}

function get_tables()
{
	global $basededatos;
	global $print;
	$result = array();
	$tables = selects(prepare('SHOW TABLES FROM %t', $basededatos));
	$property = 'Tables_in_' . $basededatos;
	if($print){
		return $tables;
	}else{
		foreach ($tables as $table) {
			array_push($result, $table->{$property}); 
		}
		return $result;
	}
}

function get_columns($table)
{
	$result = array();
	$columns = selects(prepare('SELECT DISTINCT(COLUMN_NAME) from information_schema.columns where table_name = %s order by ordinal_position', $table));
	foreach ($columns as $column) {
		array_push($result, $column->COLUMN_NAME); 
	}
	return $result;
}