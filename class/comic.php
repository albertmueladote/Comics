<?php

class comic{

	CONST TABLE = 'comic';
	private $id;
	private $collection;
	private $number;
	private $month;
	private $year;

	public function __construct()
	{
		$arg_list = func_get_args();
		if(func_num_args() > 0){
			switch (func_num_args()) {
				case 1:
					$this->setId($arg_list[0]);
					break;
				case 2:
					$this->setId($arg_list[0]);
					$this->setCollection($arg_list[1]);
					break;
				case 4:
					$this->setCollection($arg_list[0]);
					$this->setNumber($arg_list[1]);
					$this->setMonth($arg_list[2]);
					$this->setYear($arg_list[3]);
					break;
				case 5:
					$this->setId($arg_list[0]);
					$this->setCollection($arg_list[1]);
					$this->setNumber($arg_list[2]);
					$this->setMonth($arg_list[3]);
					$this->setYear($arg_list[4]);
					break;
				default:
					break;
			}
		}
	}

	public function select()
	{
		if(is_int($this->getId())){
			$db = new db();
			$item = $db->select($this);
			$item = $item[0];
			if(!$item){
				return false;
			}
			$this->setId($item['id']);
			$this->setCollection($item['collection']);
			$this->setNumber($item['number']);
			$this->setMonth($item['month']);
			$this->setYear($item['year']);
			return true;
		}
		return false;
	}

	public function getByCollection()
	{
		if(is_int($this->getCollection())){
			$collection = $this->getCollection();
		}else if(is_int($this->getId())){
			$collection = $this->getId();
		}
		$db = new db();
		$items = $db->get_rows($this, array('collection' => $collection));
		if(!$items){
			return false;
		}
		$result = array();
		foreach ($items as $item) {
			$comic = new comic($item['id'], $item['collection'], $item['number'], $item['year'], $item['month']);
			array_push($result, $comic);
		}
		return $result;
	}

	public function insertRange($from, $to, $collection, $number = 1)
	{
		$months = 12;
		if(is_array($from) && is_array($to)){
			if(sizeof($from) == 2 && sizeof($to) == 2){
				do{
					$continue = true;
					$comic = new comic($collection, $number, $from[0], $from[1]);
					$comic->insert();
					$from[0]++;
					if($from[0] > 12){
						$from[0] = 1;
						$from[1]++;
					}
					if($from[0] > $to[0] && $from[1] == $to[1]){
						$continue = false;
					}
					$number++;
				}while($continue);
			}
		}
	}

	public function insert()
	{
		$db = new db();
		if(!$db->select($this)){
			if($db->insert($this)){
				return true;
			}
		}
		return false;
	}

	public function delete()
	{
		$db = new db();
		if($db->delete($this)){
			return true;
		}
		return false;
	}

	public function update()
	{
		$db = new db();
		if($db->update($this)){		
			return true;
		}
		return false;
	}

	public function getTable()
	{
		return self::TABLE;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getCollection()
	{
		return $this->collection;
	}

	public function getNumber()
	{
		return $this->number;
	}

	public function getMonth()
	{
		return $this->month;
	}

	public function getYear()
	{
		return $this->year;
	}

	public function setId($value)
	{
		if(is_int($value)){
			if($value > 0){
				$this->id = $value;
				return true;
			}
		}
		return false;
	}

	public function setCollection($value)
	{
		if(is_int($value)){
			if($value > 0){
				$collection = new collection($value);
				if($collection->select()){
					$this->collection = $value;
					return true;
				}
			}
		}
		return false;
	}

	public function setNumber($value)
	{
		if(is_int($value)){
			if($value > 0){
				$this->number = $value;
				return true;
			}
		}
		return false;
	}

	public function setMonth($value)
	{
		if(is_int($value)){
			if($value > 0 && $value < 13){
				$this->month = $value;
				return true;
			}
		}
		return false;
	}
	
	public function setYear($value)
	{
		if(is_int($value)){
			if($value > 0){
				$this->year = $value;
				return true;
			}
		}
		return false;
	}

	public function getAll()
	{
		$db = new db();
		$rows = $db->get_rows($this);
		return $rows;
	}

	public function getAttr() {
		$result = array();
        foreach($this as $var => $value) {
            $result[$var] = $value;
        }
        return $result;
    }


}
