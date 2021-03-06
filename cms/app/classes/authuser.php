<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi
 */

class AuthUser {

	/**
	 * 
	 * @return boolean
	 */
	public static function isLoggedIn()
	{
		return Auth::instance()->logged_in();
	}

	/**
	 * 
	 * @return Model_User
	 */
	public static function getRecord()
	{
		return Auth::instance()->get_user(FALSE);
	}

	/**
	 * 
	 * @return integer
	 */
	public static function getId()
	{
		return self::getRecord() ? self::getRecord()->id : FALSE;
	}

	/**
	 * 
	 * @return string
	 */
	public static function getUserName()
	{
		return self::getRecord() ? self::getRecord()->username : FALSE;
	}

	/**
	 * 
	 * @return array
	 */
	public static function getPermissions()
	{
		$roles = self::getRecord() ? self::getRecord()->roles->find_all() : FALSE;
		
		$array = array();
		if($roles)
		{
			foreach ( $roles as $role )
			{
				$array[$role->id] = $role->name;
			}
		}
		return $array;
	}

	/**
	 * Checks if user has (one of) the required permissions.
	 *
	 * @param string $permission Can contain a single permission or comma seperated list of permissions.
	 * @return boolean
	 */
	public static function hasPermission( $permissions )
	{
		if(empty($permissions))
		{
			return TRUE;
		}
		
		if(!is_array( $permissions ))
		{
			$permissions = explode(',', $permissions);
		}

		return self::getRecord() ? self::getRecord()->has_role($permissions, FALSE) : FALSE;
	}

	/**
	 * 
	 * @param string $username
	 * @param string $password
	 * @param boolean $remember
	 * @return boolean
	 */
	public static function login( $username, $password, $remember = FALSE )
	{
		$user = ORM::factory( 'user' );
		
		// Attempt to load the user
		$user
			->where( 'username', '=', $username )
			->find();
		
		if(
			$user->loaded()
			AND
			Auth::instance()->login($user, $password, $remember))
		{
			return TRUE;
		}

		return FALSE;
	}

	static public function logout()
	{
		Auth::instance()->logout();
		Session::instance()->destroy();
	}

}

// end AuthUser class