<?php

class collection{

	CONST TABLE = 'collection';
	private $id;
	private $title;
	private $status;
	private $from;
	private $to;
	private $complete;
	private $possession;
	private $missing;
	private $finish;
	/*
	Option 1:
		id[optional], title

	Option 2:
		id[optional], from, to

	Option 3:
		id[optional], title, from, to

	Option 4:
		id[optional], title, from, to, possession

	Option 5:
		id[optional], title, from, to, possesssion, complete
	*/
	public function __construct()
	{
		$arg_list = func_get_args();
		if(func_num_args() > 0){
			$i = 0;
			if($this->setId($arg_list[0])){
				$i++;
			}
			switch (func_num_args()) {
				case 1 + $i:
					if(!$this->setId($arg_list[0 + $i])){
						$this->setTitle($arg_list[0 + $i]);
					}
					break;
				case 2 + $i:
					$this->setFrom($arg_list[0 + $i]);
					$this->setTo($arg_list[1 + $i]);
					$this->setPossession($arg_list[1 + $i] - $arg_list[0 + $i] + 1);
					$this->setComplete($arg_list[1 + $i]);
					$this->setMissing($this->getComplete() - $this->getPossession());
					break;
				case 3 + $i:
					$this->setTitle($arg_list[0 + $i]);
					$this->setFrom($arg_list[1 + $i]);
					$this->setTo($arg_list[2 + $i]);
					$this->setPossession($arg_list[2 + $i] - $arg_list[1 + $i] + 1);
					$this->setComplete($arg_list[2 + $i]);
					$this->setMissing($this->getComplete() - $this->getPossession());
					break;
				case 4 + $i:
					$this->setTitle($arg_list[0 + $i]);
					$this->setFrom($arg_list[1 + $i]);
					$this->setTo($arg_list[2 + $i]);
					$this->setPossession($arg_list[3 + $i]);
					$this->setComplete($arg_list[2 + $i]);
					$this->setMissing($this->getComplete() - $this->getPossession());
					break;
				case 5 + $i:
					$this->setTitle($arg_list[0 + $i]);
					$this->setFrom($arg_list[1 + $i]);
					$this->setTo($arg_list[2 + $i]);
					$this->setPossession($arg_list[3 + $i]);
					$this->setComplete($arg_list[4 + $i]);
					$this->setMissing($this->getComplete() - $this->getPossession());
					break;
				default:
					break;
			}
			$this->setFinish(0);
			$this->setStatus($this->getDefaultStatus());
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
			$this->setFrom($item['from']);
			$this->setTo($item['to']);
			$this->setPossession($item['possession']);
			$this->setComplete($item['complete']);
			$this->setMissing($item['missing']);
			$this->setFinish($item['finish']);
			if(!$item){
				return false;
			}
			return true;
		}
		return false;
	}

	public function insert()
	{
		$db = new db();
		if(($this->getTo() - $this->getFrom() + 1) == ($this->getPossession() + $this->getMissing())){
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
		if(($this->getTo() - $this->getFrom() + 1) == ($this->getPossession() + $this->getMissing())){
			if($db->update($this)){		
				return true;
			}
		}
		return false;
	}

	public function getAll()
	{
		$db = new db();
		$rows = $db->get_rows($this);
		if($rows){
			$result = array();
			$status = new status();
			$status = $status->getAll();
			foreach ($rows as $row){
				$result[$row['id']] = $row;
				$result[$row['id']]['status'] = $status[$row['status']]['title'];
				if($row['finish'] == 0){
						$result[$row['id']]['finish'] = 'Si';
				}else{
					$result[$row['id']]['finish'] = 'No';
				}
			}
			return $result;
		}
		return false;
	}

	public function refresh()
	{
		$this->setMissing();
		$this->setComplete();
		$this->setPossession();
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

	public function getFrom()
	{
		return $this->from;
	}

	public function getTo()
	{
		return $this->to;
	}

	public function getComplete()
	{
		return $this->complete;
	}

	public function getPossession()
	{
		return $this->possession;
	}

	public function getMissing()
	{
		return $this->missing;
	}

	public function getFinish()
	{
		return $this->missing;
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

	public function setComplete($value = null)
	{
		if(is_null($value)){
			if(!is_null($this->getTo())){
				$this->complete = $this->getTo();
				return true;
			}
		}elseif(is_int($value)){
			if($value > 0){
				$this->complete = $value;
				return true;
			}
		}
		return false;
	}

	public function setPossession($value = null)
	{
		
		if(is_null($value)){
			if(!is_null($this->getFrom()) && !is_null($this->getTo()) && !is_null($this->getMissing())){
				$this->possession = $this->getTo() - $this->getFrom() + 1 - $this->getMissing();
				return true;
			}
		}elseif(is_int($value)){
			if($value > 0){
				$this->possession = $value;
				return true;
			}
		}
		return false;
	}

	public function setMissing($value = null)
	{
		if(is_null($value)){
			if(!is_null($this->getId())){
				$missing = new missing();
				$result = $missing->getByCollection($this->getId());
				if($missing){
					$this->missing = sizeof($result);
					return true;
				}
				$this->missing = 0;				
			}
		}elseif(is_int($value)){
			if($value >= 0){
				$this->missing = $value;
				return true;
			}
		}
		return false;
	}

	public function setFinish($value = null)
	{
		if(is_null($value)){
			if(!is_null($this->getMissing())){
				$missing = new missing();
				$result = $missing->getByCollection($this->getId());
				if($missing){
					$this->finish = 0;
				}else{
					$this->finish = 1;
				}
				return true;			
			}
		}elseif(is_int($value)){
			if($value >= 0){
				$this->missing = $value;
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
