<?php

class missing{

	CONST TABLE = 'missing';
	private $collection;
	private $missing;

	public function __construct()
	{
		$arg_list = func_get_args();
		if(func_num_args() > 0){
			switch (func_num_args()) {
				case 1:
					$this->setCollection($arg_list[0]);
					break;
				case 2:
					$this->setCollection($arg_list[0]);
					$this->setMissing($arg_list[1]);
					break;
				default:
					break;
			}
		}
	}

	public function select()
	{
			$db = new db();
			$item = $db->select($this);
			$item = $item[0];
			$this->setCollection($item['collection']);
			$this->setMissing($item['missing']);
			if(!$item){
				return false;
			}
			return true;
	}

	public function insert()
	{
		$db = new db();
		if(!$this->select()){
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

	public function getByCollection($collection)
	{
		$db = new db();
		return $db->get_rows($this, array('collection' => $collection));

	}

	public function getTable()
	{
		return self::TABLE;
	}

	public function getCollection()
	{
		return $this->collection;
	}

	public function getMissing()
	{
		return $this->missing;
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

	public function setMissing($value)
	{
		if(is_int($value)){
			if(is_null($this->getCollection())){
				$this->collection = $value;
				return true;
			}else{
			$collection = new collection($this->getCollection());
				if($collection->select()){
					if($collection->getFrom() < $value && $collection->getTo() > $value){
						$this->missing = $value;
						return true;
					}
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
