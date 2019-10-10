<?php

class status{

	CONST TABLE = 'status';
	CONST DEFAULT_VALUE = 1;
	private $id;
	private $title;

	public function __construct($title = null)
	{
		$arg_list = func_get_args();
		if(func_num_args() > 0){
			switch (func_num_args()) {
				case 1:
					$this->setId($arg_list[0]);
					break;
				case 2:
					$this->setId($arg_list[0]);
					$this->setTitle($arg_list[1]);
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
			$this->setTitle($item['title']);
			return true;
		}
		return false;
	}

	public function insert()
	{
		$db = new db();
		if($db->insert($this)){
			return true;
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

	public function getAll()
	{
		$db = new db();
		$rows = $db->get_rows($this);
		if($rows){
			$result = array();
			foreach ($rows as $row) {
				$result[$row['id']] = $row;				
			}
			return $result;
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

	public function getDefaultValue()
	{
		return self::DEFAULT_VALUE;
	}

	public function getTitle()
	{
		return $this->title;
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

	public function setTitle($value)
	{
		if(is_string($value)){
			if(strlen($value) > 0){
				$this->title = $value;
				return true;
			}
		}
		return false;
	}

	public function getAttr() {
		$result = array();
        foreach($this as $var => $value) {
            $result[$var] = $value;
        }
        return $result;
    }
}
