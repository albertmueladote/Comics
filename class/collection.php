<?php

class collection{

	CONST TABLE = 'collection';
	private $id;
	private $title;
	private $status;
	/*
	Option 1:
		id[optional], title
	*/
	public function __construct()
	{
		$arg_list = func_get_args();
		if(func_num_args() > 0){
			switch (func_num_args()) {
				case 1:
					if(!$this->setId($arg_list[0])){
						$this->setTitle($arg_list[0]);
					}
					break;
				case 2:
					$this->setId($arg_list[0]);
					$this->setTitle($arg_list[1]);
					break;
				default:
					break;
			}
			$this->setStatus($this->getDefaultStatus());
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
			$this->setTitle($item['title']);
			$this->setStatus($item['status']);
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

	public function getTitle()
	{
		return $this->title;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getDefaultStatus()
	{
		$status = new status();
		return $status->getDefaultValue();
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

	public function setStatus($value)
	{
		if(is_int($value)){
			$status = new status($value);
			if($status->select()){
				$this->status = $value;
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
