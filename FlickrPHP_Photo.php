<?php

/**
 * A class that represents a photo on flickr.
 * 
 * @author Melanie Rhianna Lewis aka Cyberspice
 */
class FlickrPHP_Photo {
	
	/**
	 * The photo's id.
	 * 
	 * @var string 
	 */
	private $_id;
	
	/**
	 * The photo's title.
	 * 
	 * @var string
	 */
	private $_title;
	
	/**
	 * The server farm's id.
	 * 
	 * @var float
	 */
	private $_farm;
	
	/**
	 * The server's id.
	 * 
	 * @var string
	 */
	private $_server;
	
	/**
	 * The photo's secret
	 * 
	 * @var string
	 */
	private $_secret;
	
	/**
	 * Indicates a public photo
	 * 
	 * @var boolean
	 */
	private $_isPublic;
	
	/**
	 * Indicates a photo with friend access
	 * 
	 * @var boolean
	 */
	private $_isFriend;
	
	/**
	 * Indicates a photo with family access
	 * 
	 * @var boolean
	 */
	private $_isFamily;
	
	/**
	 * The owner of the photo
	 * 
	 * @var FlickrPHP_Person
	 */
	private $_owner;
	
	/**
	 * Constructs a new FlickrPhoto object 
	 * 
	 * @param $properties array The photo's properties
	 */
	function __construct($properties) {
		$this->_id       = $properties['id'];
		$this->_title    = $properties['title'];
		$this->_farm     = $properties['farm'];
		$this->_server   = $properties['server'];
		$this->_secret   = $properties['secret'];
		$this->_isPublic = $properties['ispublic'];
		$this->_isFriend = $properties['isfriend'];
		$this->_isFamily = $properties['isfamily'];
	}
	
	/**
	 * Returns the id for the photo.
	 * 
	 * @return string the id.
	 */
	function getId() {
		return $this->_id;
	}
	
	/**
	 * Returns the title of the photo.
	 * 
	 * @return string the title.
	 */
	function getTitle() {
		return $this->_title;
	}
	
	/**
	 * Indicates whether the image is a public one.
	 * 
	 * @return boolean
	 */
	function isPublic() {
		return ($this->_isPublic != 0);
	}
	
	/**
	 * Indicates whether the image can only be seen by friends.
	 * 
	 * @return boolean
	 */
	function isFriend() {
		return ($this->_isFriend != 0);
	}
	
	/**
	 * Indicates whether the image can only be seen by family.
	 * 
	 * @return boolean
	 */
	function isFamily() {
		return ($this->_isFamily != 0);
	}
	
	/**
	 * Sets the owner of the photo
	 * 
	 * @param $person FlickrPHP_Person The owner
	 */
	function setOwner(FlickrPHP_Person $person) {
		$this->_owner = $person;
	}
	
	/**
	 * Returns the owner of the photo.
	 * 
	 * @return FlickrPerson the owner of the photo.
	 */
	function getOwner() {
		return $this->_owner;
	}
	
	/**
	 * Return the URL for the small square version of the image.  This image 
	 * has a dimension of 75 pixels by 75 pixels.
	 * 
	 * @return string The URL.
	 */
	function getSmallSquareImageURL() {
		return 'http://farm' . $this->_farm . '.static.flickr.com/'
		     . $this->_server . '/'. $this->_id . '_'
		     . $this->_secret . '_s.jpg';
	}
	
	/**
	 * Return the URL for the thumbnail version of the image. This image has
	 * a dimension of 100 pixels on the longest side.
	 * 
	 * @return string The URL.
	 */
	function getThumbnailImageURL() {
		return 'http://farm' . $this->_farm . '.static.flickr.com/'
		     . $this->_server . '/'. $this->_id . '_'
		     . $this->_secret . '_t.jpg';
	}
	
	/**
	 * Return the URL for the small version of the image.  This image has
	 * a dimension of 240 pixels on the longest side.
	 * 
	 * @return string The URL.
	 */
	function getSmallImageURL() {
		return 'http://farm' . $this->_farm . '.static.flickr.com/'
		     . $this->_server . '/'. $this->_id . '_'
		     . $this->_secret . '_m.jpg';
	}
	
	/**
	 * Return the URL for the medium version of the image.  This image has
	 * a dimension of 500 pixels on the longest side.
	 * 
	 * @return string The URL.
	 */
	function getMediumImageURL() {
		return 'http://farm' . $this->_farm . '.static.flickr.com/'
		     . $this->_server . '/'. $this->_id . '_'
		     . $this->_secret . '.jpg';
	}
	
	/**
	 * Returns the URL for the photo's web page.  This is the Flickr page 
	 * on which the image and details are displayed.
	 * 
	 * @return string The URL
	 */
	function getURL() {
		if (isset($this->_owner)) {
			$owner = $this->_owner;
			return 'http://www.flickr.com/photos/' . $owner->getId() . '/'
			     . $this->_id;
		} else {
			return false;
		}
	}
}
