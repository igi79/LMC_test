<?php

class ArticleModel extends Model
{
	
	private $_id;
	private $_title;
	private $_intro;
	private $_date;
	private $_content;
	private $_userId;
	
	public function setId($id)
	{
		$this->_id = $id;
	}
	
	public function getId()
	{
		return $this->_id;
	}	

	public function setTitle($title)
	{
		$this->_title = $title;
	}
	
	public function setIntro($intro)
	{
		$this->_intro = $intro;
	}

	public function setDate($date)
	{
		$this->_date = $date;
	}
		
	public function setContent($content)
	{
		$this->_content = $content;
	}	

	public function setUserId($userId)
	{
		$this->_userId = $userId;
	}	

	public function getUserId()
	{
		$sql = "SELECT
					u.id AS user_id
				FROM 
					article AS a
				LEFT JOIN 
					user AS u ON u.id = a.user_id 				 
				WHERE 
					a.id = ?";
		
		$this->_setSql($sql);
		$articleDetails = $this->getRow(array($this->_id));
		
		if (empty($articleDetails))
		{
			return false;
		}
		
		return $articleDetails['user_id'];
	}
	
	
	public function getArticles($enabled = true)
	{
		$sql = "SELECT
					a.id AS id,
					a.title AS title,
					a.intro AS intro,
					DATE_FORMAT(a.date, '%d.%m.%Y.') AS date,
					u.id AS user_id,
					CONCAT(u.first_name,' ',u.last_name) AS full_name
				FROM 
					article AS a
				LEFT JOIN 
					user AS u ON u.id = a.user_id 
				WHERE
					a.enabled = ?
				ORDER BY a.date DESC";
		
		$this->_setSql($sql);
		$data = array( $enabled ? 'Y' : 'N' );
		$articles = $this->getAll($data);
		
		if (empty($articles))
		{
			return false;
		}
		
		return $articles;
	}
	
	public function getArticleById($id)
	{
		$sql = "SELECT
					a.id AS id,
					a.title AS title,
					a.intro AS intro,
					a.content AS content,
					DATE_FORMAT(a.date, '%d.%m.%Y.') AS date,
					u.id AS user_id,
					CONCAT(u.first_name,' ',u.last_name) AS full_name				 
				FROM 
					article AS a
				LEFT JOIN 
					user AS u ON u.id = a.user_id 				 
				WHERE 
					a.id = ?";
		
		$this->_setSql($sql);
		$articleDetails = $this->getRow(array($id));
		
		if (empty($articleDetails))
		{
			return false;
		}
		
		return $articleDetails;
	}
	
	public function store()
	{
		if( empty($this->_id) )
		{
			$sql = "INSERT INTO article
						(title,intro,content,date,user_id)
	 				VALUES
	 					(?, ?, ?, NOW(), ?)";
		
			$data = array(
					$this->_title,
					$this->_intro,
					$this->_content,
					$this->_userId
			);
			$sth = $this->_db->prepare($sql);
			$ret = $sth->execute($data);
			$this->_id = $this->_db->lastInsertId();
			return $ret;				
		}
		else
		{
			$sql = "UPDATE article
					SET
						title = ?,
						intro = ?,
						content = ?,
						date = NOW(),
						user_id = ?
	 				WHERE
	 					id = ?";
			
			$data = array(
					$this->_title,
					$this->_intro,
					$this->_content,
					$this->_userId,
					$this->_id
			);
			$sth = $this->_db->prepare($sql);
			return $sth->execute($data);				
		}
	}
		
}