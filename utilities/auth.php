<?php

class Auth
{
	
	public static function logged()
	{
		if ( $_SESSION['logged_in'] == true && $_SESSION['id'] > 0 ) return $_SESSION['id'];
		return false;
	}
}
