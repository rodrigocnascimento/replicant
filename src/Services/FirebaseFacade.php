<?php 
namespace Services;

use \Firebase\FirebaseLib as Firebase;

class FirebaseFacade extends Firebase
{

	public function __construct()
	{
		$firebaseUrl 	= getenv('FIREBASE_URL');
    	$firebaseToken 	= getenv('FIREBASE_TOKEN');

		parent::__construct($firebaseUrl, $firebaseToken);
	}

	public function getCommandByBotId($botId, $command)
	{
		$command = $this->get(sprintf('/%s/Commands/%s', $botId, $command));
		return $this->_toArray($command);
	}


	public function setState($userId, $state, $step = '0')
	{
		$pathUser = sprintf('/users/%s/state', $userId);
		$this->set($pathUser, [
			'state' => $state,
			'step' => $step
		]);
	}

	public function getState($userId)
	{
		$pathUser = sprintf('/users/%s/state', $userId);
		return $this->_toArray($this->get($pathUser));
	}

	public function targetUser($userId, $userProfileData)
	{
		// $firebase->update($path, $data); // updates data in Firebase
		// $firebase->set($path, $value);   // stores data in Firebase
		$pathUser = sprintf('/users/%s', $userId);
		$this->set($pathUser, [
			'first_name' => $userProfileData['first_name'],
			'last_name' => $userProfileData['last_name'],
			'gender' => $userProfileData['gender'],
			'locale' => $userProfileData['locale'],
		]);
	}
	
	public function _toArray($data)
	{
		return json_decode($data, true);
	}
}