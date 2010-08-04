<?php


/**
 * A class that represents a flickr session.  I.e. a communication session
 * with Flickr.  An instance should be constructed with the API key as
 * returned by Flickr (for public data) or with both the API key and the
 * secret key returned by Flickr (for private data).
 * 
 * @author Melanie Rhianna Lewis aka Cyberspice
 */
class FlickrPHP_Session {
	
	/**
	 * The API key (as supplied by Yahoo!/Flickr)
	 * 
	 * @var string
	 */
	private $_api_key;
	
	/**
	 * The secret key (as supplied by Yahoo!/Flickr).
	 * 
	 * @var string
	 */
	private $_secret_key;
	
	/**
	 * The error code of the last error returned from a flickr function.
	 * 
	 * @var integer
	 */
	private $_error_code;
	
	/**
	 * The error message of the last error returned from a flickr function.
	 * 
	 * @var string
	 */
	private $_error_message;
	
	/**
	 * Constructs a new session with Flickr using the specified API key and
	 * secret key.
	 * 
	 * @param $api_key string The API key as returned by flickr
	 * @param $secret_key string The secret key as returned by flickr
	 */
	public function __construct($api_key, $secret_key = NULL) {
		$this->_api_key = $api_key;
		$this->_secret_key = $secret_key;
	}
	
	/**
	 * Call a remote API on flickr.
	 * 
	 * @param $method The method to call
	 * @param $params An associated array of any additional parameters (can be empty)
	 * @return array containing the response from flickr
	 */
	public function request($method, $params = array()) {
		// Add arguments required for every call
		$params['api_key'] = $this->_api_key;
		$params['method']  = $method;
		$params['format']  = 'php_serial';
		
		// Encode in to GET params
		$enc_params = array();
		foreach ($params as $key => $value) {
			$enc_params[] = urlencode($key) . '=' . urlencode($value);
		}
		$params_str = implode('&', $enc_params);
		
		// Call remote API
		$raw_response = file_get_contents('http://api.flickr.com/services/rest/?' . $params_str);
		$response     = unserialize($raw_response);
		
		if ($response['stat'] == 'ok') {
			return $response;
		}
		
		// Extract the error details
		$this->_error_code    = $response['code'];
		$this->_error_message = $response['message'];
		
		return false;
	}
};
