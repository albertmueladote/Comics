<?php

class compilation{

	CONST TABLE = 'compilation';
	private $id;
	private $title;
	private $book;
	private $comic;
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
					$this->setId($arg_list[0]);
					break;
				case 2:
					$this->setBook($arg_list[0]);
					$this->setComic($arg_list[1]);
					break;
				case 3:
					$this->setId($arg_list[0]);
					$this->setBook($arg_list[1]);
					$this->setComic($arg_list[2]);
					break;
				case 3:
					$this->setId($arg_list[0]);
					$this->setTitle($arg_list[1]);
					$this->setBook($arg_list[2]);
					$this->setComic($arg_list[3]);
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
			$this->setBook($item['book']);
			$this->setComic($item['comic']);
			return true;
		}
		return false;
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

	public function getBook()
	{
		return $this->book;
	}

	public function getComic()
	{
		return $this->comic;
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
			if($value > 0){
				$this->title = $value;
				return true;
			}
		}
		return false;
	}

	public function setBook($value)
	{
		if(is_int($value)){
			if($value > 0){
				$book = new book($value);
				if($book->select()){
					$this->book = $value;
					return true;
				}
			}
		}
		return false;
	}

	public function setComic($value)
	{
		if(is_int($value)){
			if($value > 0){
				$comic = new comic($value);
				if($comic->select()){
					$this->comic = $value;
					return true;
				}
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
