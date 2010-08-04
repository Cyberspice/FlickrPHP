<?php

require_once('Session.php');
require_once('Person.php');
require_once('Photo.php');

/**
 * Utility function that returns the N latest public photos for a user as
 * an array of FlickrPHP_Photo objects.
 * 
 * @param $api_key string The API key as returned by Flickr
 * @param $username string The Flickr username of the user
 * @param $count integer The number of images to return
 * 
 * @return array of FlickrPHP_Photo objects
 */
function getPublicPhotosForUser($api_key, $username, $count) {
	
	/* Create a flickr session */
	$session = new FlickrPHP_Session($api_key);
	
	/* Get the person */
	$person = FlickrPHP_Person::getPersonByUsername($session, $username);
	
	/* Get the number of photos */
	return $person->getPublicPhotos($count);
}
