<?php

class UserModel extends Model
{
	private $_id;
	private $_login;
	private $_password;
	private $_firstName;
	private $_lastName;
	private $_email;
	private $_authError;

	public function getId()
	{
		return $this->_id;
	}
		
	public function getAuthError()
	{
		return $this->_authError;	
	}	

	public function setLogin($login)
	{
		$this->_login = $login;
	}	
	
	public function setPassword($password)
	{
		$this->_password = $password;
	}

	public function setFirstName($firstName)
	{
		$this->_firstName = $firstName;
	}
	
	public function setLastName($lastName)
	{
		$this->_lastName = $lastName;
	}
	
	public function setEmail($email)
	{
		$this->_email = $email;
	}
	
	public function store()
	{
		$sql = "INSERT INTO user 
					(login,password,first_name, last_name, email)
 				VALUES 
 					(?, ?, ?, ?, ?)";
		
		$data = array(
			$this->_login,
			password_hash($this->_password,PASSWORD_DEFAULT),
			$this->_firstName,
			$this->_lastName,				
			$this->_email,
		);
		
		$sth = $this->_db->prepare($sql);
		return $sth->execute($data);
	}
	
	public function checkCredentials()
	{
		$sql = "SELECT
					id,
					login,
					password
				FROM
					user
				WHERE
					login = ?";
		
		$this->_setSql($sql);
		$credentials = $this->getRow(array($this->_login));
		
		if (empty($credentials))
		{
			$this->_authError = 'Login not found!';
			return false;
		}
		elseif (password_verify($this->_password,$credentials['password']))
		{
			$this->_id = $credentials['id'];
			return true;
		}
		else
		{
			$this->_authError = 'No matching login and password!';
			return false;				
		}
				
		return false;
	}
	
	public function userExists()
	{
		$sql = "SELECT
					id
				FROM
					user
				WHERE
					login = ?";
		
		$this->_setSql($sql);
		$user_ids = $this->getRow(array($this->_login));

		if($user_ids > 0){
			return true;
		}
		else
		{
			return false;
		}
	
	}
	
}