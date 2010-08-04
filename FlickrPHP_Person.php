<?php

require_once('Session.php');
require_once('Photo.php');

/**
 * The definition for safe search content.
 */
define('FLICKRPHP_SEARCH_SAFE', 1);

/**
 * The definition for moderated search content.
 */
define('FLICKRPHP_SEARCH_MODERATE', 2);

/**
 * The definition for restricted search content.
 */
define('FLICKRPHP_SEARCH_RESTRICTED', 3);

/**
 * An object that represents a Flickr person i.e. user name.  It can not be
 * constructed directly but supplies factory methods that return an 
 * appropriate instance given a user name or email address.
 * 
 * @author Melanie Rhianna Lewis aka Cyberspice
 */
class FlickrPHP_Person {
	
	/**
	 * The Flickr session that returned the data
	 * 
	 * @var FlickrSession
	 */
	private $_session;
	
	/**
	 * The user id
	 * 
	 * @var integer
	 */
	private $_user_id;
	
	/**
	 * The user name
	 * 
	 * @var string
	 */
	private $_username;
	
	/**
	 * The actual/real name of the user
	 * 
	 * @var string
	 */
	private $_realname;

	/**
	 * The registered geographic location of the user
	 * 
	 * @var string
	 */
	private $_location;
	
	/**
	 * The URL prefix for the user's photos
	 * 
	 * @var string
	 */
	private $_photosurl;
	
	/**
	 * The URL prefix for the user's profile
	 * 
	 * @var string
	 */
	private $_profileurl;
	
	/**
	 * Indicates whether the user's data is already cached or needes to be
	 * read from Flickr.
	 * 
	 * @var boolean
	 */
	private $_isCached;
	
	/**
	 * Factory method that returns a FlickrPHP_Person object for a specified
	 * session for the person identified by the specified email address.
	 * 
	 * @param $session FlickrPHP_Session A flicker session
	 * @param $email string The email address of the person to find
	 * @return a FlickrPHP_Person object 
	 */
	public static function getPersonByEmail(FlickrPHP_Session $session, 
	                                        $email) {
		// Call flickr
		$response = $session->request("flickr.people.findByEmail", 
		                              array($email));
		if ($response != false) {
			$userdata = $response['user'];
			return new FlickrPHP_Person($session, 
			                            $userdata['nsid'],
			                            $userdata['username']['_content']);
		}
		
		// Error
		return false;
	}

	/**
	 * Factory method that returns a FlickrPHP_Person object for a specified 
	 * session for the person identified by the specified user name.
	 * 
	 * @param $session FlickrPHP_Session The Flickr session
	 * @param $username string The person's user name
	 * @return a FlickrPHP_Person object
	 */
	public static function getPersonByUsername(FlickrPHP_Session $session, 
	                                           $username) {
		// Call flickr
		$response = $session->request("flickr.people.findByUsername", 
		                              array('username' => $username));
		if ($response != false) {
			$user = $response['user'];
			return new FlickrPHP_Person($session, 
			                            $user['nsid'],
			                            $user['username']['_content']);
		}
		
		// Error
		return false;
	}
	
	/**
	 * Constructs a new instance of FlickrPerson for the specified session
	 * with the specified user id and user name.
	 * 
	 * @param $session FlickrSession The flickr session
	 * @param $user_id string The user ID
	 * @param $username string The user name
	 */
	private function __construct(FlickrPHP_Session $session, $user_id, $username) {
		$this->_session  = $session;
		$this->_user_id  = $user_id;
		$this->_username = $username;
		$this->_isCached = false;
	}
	
	/**
	 * Populates the object with a person's info by calling flickr if the data
	 * is not already cached.
	 */
	private function getInfo() {
		if (!$this->_isCached) {
			$response = $this->_session->request("flickr.people.getInfo", 
				array('user_id' => $this->_user_id));
			if ($response != false) {
				$person = $response['person'];
				
				$this->_realname   = $person['realname']['_content'];
				$this->_location   = $person['location']['_content'];
				$this->_photosurl  = $person['photosurl']['_content'];
				$this->_profileurl = $person['profileurl']['_content'];
				
				$this->_isCached = true;
			}
		}
	}
	
	/**
	 * Returns the id for the person.
	 * 
	 * @return string The id for the person
	 */
	public function getId() {
		return $this->_user_id;
	}
	
	/**
	 * Returns the real name for the person.
	 * 
	 * @return string The real name of the person
	 */
	public function getRealName() {
		$this->getInfo();
		return $this->_realname;
	}
	
	/**
	 * Returns the person's geographical location
	 * 
	 * @return string The person's location
	 */
	public function getLocation() {
		$this->getInfo();
		return $this->_location;
	}
	
	/**
	 * The URL of the person's photos
	 * 
	 * @return string URL
	 */
	public function getPhotosURL() {
		$this->getInfo();
		return $this->_photosurl;
	}
	
	/**
	 * The URL of the person's profile
	 * 
	 * @return string URL
	 */
	public function getProfileURL() {
		$this->getInfo();
		return $this->_profileurl;
	}
	
	/**
	 * Returns information about the person's public photos.
	 * 
	 * @param $per_page integer Number of photos per page (optional)
	 * @param $pages integer Number of pages (optional)
	 * @param $safe_search integer Search mode (optional)
	 * @param $extras string Comma separated list of extra information to 
	 *                       return (optional)
	 * 
	 * @return array of photo information
	 */
	public function getPublicPhotos($per_page = NULL, 
	                                $pages = NULL, 
	                                $safe_search = NULL,
	                                $extras = NULL) {
		$params = array();
		
		// Required params
		$params['user_id'] = $this->_user_id;
		
		// Optional params
		if (isset($per_page)) {
			$params['per_page'] = $per_page;
		}
		
		if (isset($pages)) {
			$params['page'] = $pages;
		}
		
		if (isset($safe_search)) {
			$params['safe_search'] = $safe_search;
		}
		
		if (isset($extras)) {
			$params['extras'] = $extras;
		}

		// Call flickr
	    $session  = $this->_session;
		$response = $this->_session->request("flickr.people.getPublicPhotos", $params);
		if ($response != false) {
			$photos = array();
			
			foreach($response['photos']['photo'] as $photo) {
				$photo = new FlickrPHP_Photo($photo);
				$photo->setOwner($this);
				array_push($photos, $photo);
			}
			
			return $photos;
		}
		
		// Error
		return false;
	}
}
