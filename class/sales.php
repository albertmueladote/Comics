<?php

class sales{

	CONST TABLE = 'sales';
	private $id;
	private $comic;
	private $price;
	private $date;

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
					$this->setComic($arg_list[1]);
					break;
				case 3:
					$this->setId($arg_list[0]);
					$this->setComic($arg_list[1]);
					$this->setPrice($arg_list[2]);
					$this->setDate(time());
					break;
				case 4:
					$this->setId($arg_list[0]);
					$this->setComic($arg_list[1]);
					$this->setPrice($arg_list[2]);
					$this->setDate($arg_list[3]);
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
			$this->setId($item['id']);
			$this->setComic($item['comic']);
			$this->setPrice($item['price']);
			$this->setDate($item['date']);
			return true;
		}
		return false;
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

	public function getComic()
	{
		return $this->collection;
	}

	public function getPrice()
	{
		return $this->price;
	}

	public function getDate()
	{
		return $this->date;
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

	public function setComic($value)
	{
		if(is_int($value)){
			$comic = new comic($value);
			if($comic->select()){
				$this->comic = $value;
				return true;
			}
		}
		return false;
	}

	public function setPrice($value)
	{
		if(is_int($value)){
			$value = floatval($value);
		}
		if(is_float($value)){
			if($value > 0){
				$this->price = $value;
				return true;
			}
		}
		return false;
	}

	
	public function setDate($value)
	{
		if(is_int($value)){
			if($value > 0){
				$this->date = $value;
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