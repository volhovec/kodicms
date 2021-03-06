<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package    Kodi/Helpers
 */

class Messages {

	protected static $_errors = array();
	protected static $_success = array();
	
	// Message types
	const SUCCESS = 'success';
	const ERRORS = 'errors';
	
	/**
	 * @var  string  default session key used for storing messages
	 */
	public static $session_key = 'message';

	/**
	 * 
	 * @param string $type
	 * @return array
	 */
	public static function get( $type = NULL )
	{
		if ( $type === NULL )
		{
			$array = array();

			$success = Session::instance()
				->get_once( self::$session_key.'_'.Messages::SUCCESS, array() );

			$errors = Session::instance()
				->get_once( self::$session_key.'_'.Messages::ERRORS, array() );

			$array[Messages::SUCCESS] = $success;
			$array[Messages::ERRORS] = $errors;

			return $array;
		}

		return Session::instance()->get_once( self::$session_key.'_'.$type, array() );
	}

	/**
	 * 
	 * @param string $type
	 * @param mixed $data
	 * @param array $values
	 */
	public static function set( $type = Messages::SUCCESS, $data = NULL, $values = NULL )
	{
		if ( !is_array( $data ) )
		{
			$data = array($data);
		}

		foreach ( $data as $index => $string )
		{
			$data[$index] = empty( $values ) ? $string : strtr( $string, $values );
		}

		if ( $type == Messages::SUCCESS )
		{
			self::$_success = Arr::merge( self::$_success, $data );
			Session::instance()
				->set( self::$session_key.'_'.Messages::SUCCESS, self::$_success );
		}
		else
		{
			self::$_errors = Arr::merge( self::$_errors, $data );
			Session::instance()
				->set( self::$session_key.'_'.Messages::ERRORS, self::$_errors );
		}
	}

	/**
	 * 
	 * @param string $data
	 * @param array $values
	 * @return void
	 */
	public static function errors( $data = NULL, $values = NULL )
	{
		if ( $data === NULL )
		{
			return self::get( Messages::ERRORS );
		}

		return Messages::set( Messages::ERRORS, $data, $values );
	}

	/**
	 * 
	 * @param string $data
	 * @param array $values
	 * @return void
	 */
	public static function success( $data = NULL, $values = NULL )
	{
		if ( $data === NULL )
		{
			return self::get( Messages::SUCCESS );
		}

		return Messages::set( Messages::SUCCESS, $data, $values );
	}
	
	public static function validation(Validation $validation, $file = 'validation')
	{
		$errors = $validation->errors($file);
		return Messages::errors($errors);
	}

}