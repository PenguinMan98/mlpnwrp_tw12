<?php
class Model_Data_GuestUsersProvider extends Model_Data_GuestUsersProviderBase
{
	public function getAll(){
		$strSql = '
SELECT * FROM `guest_users`';
		$params = array();
		return Model_Data_GuestUsersProvider::getArrayFromQuery($strSql, $params);
	}
	
	public function logoutGuestUsers($interval){
		$strSql = "DELETE FROM `guest_users` WHERE UNIX_TIMESTAMP() - `last_activity` > ?";
		$arrParams = array($interval);
		$arrErrors = array();
		dao::execute($strSql, $arrParams, $arrErrors);
		if(!empty($arrErrors)) throw new Exception("Error logging out registered users: " . implode('|',$arrErrors));
		return true;
	}
	
	public function getMyChar( $userId, $handle ){
		$character = $this->getOneByPk($handle);
		if(is_object($character) && $character->getUserId() == $userId ){
			return array( $character->getAsArray() ); // my guest
		}elseif(is_object($character)){
			// not my guest
			return false;
		}
		// no guest
		return array();
	}

	public function getDetailsByCharacterName( $charName ) {
		$strSql = '
SELECT gu.*, uu.login as `username`
FROM `guest_users` gu 
LEFT JOIN `character_login_log` cll 
	ON cll.`user_id` = gu.`user_id`
LEFT JOIN `users_users` uu 
	ON uu.`userId` = cll.`user_id`
WHERE gu.handle=?
ORDER BY cll.login_time DESC
LIMIT 1';
		$arrParams = array($charName);
		$arrErrors = array();
		$arrResults = array();
		dao::getAssoc($strSql, $arrParams, $arrResults, $arrErrors);
		if(!empty($arrResults)){
			return $arrResults[0]; // if only one, return that.
		}
		return array();
	}
}
