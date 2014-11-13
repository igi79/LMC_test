<?php

class UserController extends Controller
{
	public function index()
	{
		$this->_view->set('title', 'User Form');
		return $this->_view->output();
	}

	public function authorization()
	{
		$this->_view->set('title', 'Login Form');
		return $this->_view->output();
	}	
	
	public function login()
	{
		
		if (!isset($_POST['loginFormSubmit']))
		{
			header('Location: /user/authorization');
		}
		
		$login = isset($_POST['login']) ? trim($_POST['login']) : NULL;
		$password = isset($_POST['password']) ? trim($_POST['password']) : NULL;
				
		$errors = array();
		$check = true;

		if (empty($login))
		{
			$check = false;
			array_push($errors, "Login is missing!");
		}
		
		if (empty($password))
		{
			$check = false;
			array_push($errors, "Password is missing!");
		}		
		
		if (!$check)
		{
			$this->_setView('authorization');
			$this->_view->set('errors', $errors);
			$this->_view->set('formData', $_POST);
			return $this->_view->output();
		}
		
		try {				
			$user = new UserModel();
			$user->setLogin($login);
			$user->setPassword($password);
			if($user->checkCredentials()){
				$_SESSION['logged_in'] = true;
				$_SESSION['id'] = $user->getId();
				header('Location: /');
			}
			else
			{
				$this->_setView('authorization');
				$this->_view->set('formData', $_POST);
				$this->_view->set('errors', array($user->getAuthError()));				
			}
		} catch (Exception $e) {
			$this->_setView('authorization');
            $this->_view->set('formData', $_POST);
			$this->_view->set('loginError', $e->getMessage());
		}		
		
		return $this->_view->output();
	}
	
	public function logout()
	{
		session_destroy();
		header('Location: /');
		return $this->_view->output();
	}
	
    public function save()
    {
        if (!isset($_POST['userFormSubmit']))
		{
			header('Location: /user/index');
		}
		
		$errors = array();
		$check = true;

		$login = isset($_POST['login']) ? trim($_POST['login']) : NULL;
		$password = isset($_POST['password']) ? trim($_POST['password']) : NULL;
		$password_check = isset($_POST['password_check']) ? trim($_POST['password_check']) : NULL;
		$firstName = isset($_POST['first_name']) ? trim($_POST['first_name']) : NULL;
		$lastName = isset($_POST['last_name']) ? trim($_POST['last_name']) : NULL;
		$email = isset($_POST['email']) ? trim($_POST['email']) : "";

		if (empty($login))
		{
			$check = false;
			array_push($errors, "Login is required!");
		}
		else
		{	
			$user = new UserModel();
			$user->setLogin($login);
			
			if($user->userExists())
			{
				$check = false;
				array_push($errors, 'User with login "'.$login.'" exists!');				
			}			
		}	

		if (empty($password))
		{
			$check = false;
			array_push($errors, "Password is required!");
		}
		elseif ($password != $password_check)
		{
			$check = false;
			array_push($errors, "Password check must match password!");
		}		
		
		if (empty($email))
		{
			$check = false;
			array_push($errors, "E-mail is required!");
		}
		else if (!filter_var( $email, FILTER_VALIDATE_EMAIL ))
		{
			$check = false;
			array_push($errors, "Invalid E-mail!");
		}

        if (!$check)
		{
            $this->_setView('index');
            $this->_view->set('title', 'Invalid form data!');
			$this->_view->set('errors', $errors);
			$this->_view->set('formData', $_POST);
			return $this->_view->output();
		}
			
		try {				
			$user->setPassword($password);
			$user->setFirstName($firstName);
			$user->setLastName($lastName);
			$user->setEmail($email);
			$user->store();
					
			$this->_setView('success');
			$this->_view->set('title', 'Save OK!');
					
			$data = array(
				'firstName' => $firstName,
				'lastName' => $lastName,
				'email' => $email
			);
					
			$this->_view->set('userData', $data);
					
		} catch (Exception $e) {
            $this->_setView('index');
            $this->_view->set('title', 'There was an error saving the data!');
            $this->_view->set('formData', $_POST);
			$this->_view->set('saveError', $e->getMessage());
		}

        return $this->_view->output();
    }
}