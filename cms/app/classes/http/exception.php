<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class HTTP_Exception extends Kohana_HTTP_Exception {
	
	/**
     * Generate a Response for all Exceptions without a more specific override
     * 
     * The user should see a nice error page, however, if we are in development
     * mode we should show the normal Kohana error page.
     * 
     * @return Response
     */
    public function get_response()
    {
		// Lets log the Exception, Just in case it's important!
		Kohana_Exception::log($this);

		if ( Setting::get('debug') == 'yes')
		{
			// Show the normal Kohana error page.
			return parent::get_response();
		}
		else
		{
			$params = array
			(
				'code'  => 500,
				'message' => rawurlencode($this->getMessage())
			);

			if ($this instanceof HTTP_Exception)
			{
				$params['code'] = $this->getCode();
			}
				
			try
			{
//				echo 'test';
//				echo Route::get('error')->uri($params);
				$request = Request::factory( Route::get('error')->uri($params), array(), FALSE)
					->execute()
					->send_headers(TRUE)
					->body();
				
				return Response::factory()
					->status($this->getCode())
					->body($request);
			}
			catch ( Exception $e )
			{
				return parent::get_response();
			}
		}
    }
}