<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi
 */

class Setting
{
	/**
	 *
	 * @var string 
	 */
	public static $table_name = 'settings';

	/**
	 *
	 * @var array
	 */
	public static $settings = array();
	
	/**
	 *
	 * @var boolean 
	 */
    public static $is_loaded = FALSE;
    
	/**
	 * 
	 * @return array
	 */
    public static function init()
    {
        if (! self::$is_loaded)
        {
            self::$settings = DB::select()
				->from(self::$table_name)
				->cache_key(self::$table_name)
				->cached()
				->execute()
				->as_array('name', 'value');
            
            self::$is_loaded = true;
        }
		
		return self::$settings;
    }
    
    /**
     * Get the value of a setting
     *
     * @param name  string  The setting name
     * @return string the value of the setting name
     */
    public static function get($name, $default = NULL)
    {
        return Arr::get(self::$settings, $name, $default);
    }
    
	/**
	 * 
	 * @param array $data
	 */
    public static function saveFromData(array $data)
    {
        foreach( $data as $name => $value )
        {
			if(self::get($name) === NULL)
			{
				$query = DB::insert(self::$table_name)
					->columns(array('name', 'value'))
					->values(array($name, $value));
			}
			else 
			{
				$query = DB::update(self::$table_name)
					->set(array('value' => $value))
					->where('name', '=', $name);
			}
			
			$query->execute();
        }
		
		Kohana::cache('Database::cache('.self::$table_name.')', NULL, -1);
    }
} // end Setting class