<?php

class sales{

	CONST TABLE = 'sales';
	private $id;
	private $collection;
	private $from;
	private $to;

	public function __construct()
	{
		$arg_list = func_get_args();
		if(func_num_args() > 0){
			switch (func_num_args()) {
				case 1:
					$this->setId($arg_list[0]);
					break;
				case 2:
					$this->setCollection($arg_list[0]);
					if(strcmp($arg_list[1], 'all') == 0){
						$collection = new collection($this->getCollection());
						$collection->select();
						$this->setFrom($collection->getFrom());
						$this->setTo($collection->getTo());
					}
					break;
				case 3:
					$this->setCollection($arg_list[0]);
					$this->setFrom($arg_list[1]);
					$this->setTo($arg_list[2]);
					break;
				case 4:
					$this->setId($arg_list[0]);
					$this->setCollection($arg_list[1]);
					$this->setFrom($arg_list[2]);
					$this->setTo($arg_list[3]);
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
			$this->setCollection($item['collection']);
			$this->setFrom($item['from']);
			$this->setTo($item['to']);
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

	public function getFrom()
	{
		return $this->from;
	}

	public function getTo()
	{
		return $this->to;
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
			$collection = new collection($value);
			if($collection->select()){
				$this->collection = $value;
				return true;
			}
		}
		return false;
	}

	public function setFrom($value)
	{
		if(is_int($value)){
			if($value > 0){
				if(is_int($this->getTo())){
					if($value < $this->getTo()){
						$this->from = $value;
						return true;
					}
				}else{
					$this->from = $value;
					return true;
				}
			}
		}
		return false;
	}

	public function setTo($value)
	{
		if(is_int($value)){
			if($value > 0){
				if(is_int($this->getFrom())){
					if($value > $this->getFrom()){
						$this->to = $value;
						return true;
					}
				}else{
					$this->to = $value;
					return true;
				}
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