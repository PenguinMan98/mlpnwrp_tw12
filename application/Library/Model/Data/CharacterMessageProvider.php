<?php
class Model_Data_CharacterMessageProvider extends Model_Data_CharacterMessageProviderBase
{
	
	public function getAllByCharacterAndText( $userId, $characterName = "", $text = ""){
				
		$characterProvider = new Model_Data_CharacterProvider();
		$sender = $characterProvider->getOneByCharacterName($characterName);
		
        $arrParams = array();
        $strSql = 'SELECT cm.*, c.name AS \'to\', c2.name AS \'from\' ';
        $strSql .= 'FROM `character_message` cm ';
        $strSql .= 'JOIN `character_user` cu ON cu.character_id = cm.recipient_character_id ';
        $strSql .= 'JOIN `character` c ON c.character_id = cm.recipient_character_id ';
        $strSql .= 'JOIN `character` c2	ON c2.character_id = cm.sender_character_id ';
        $strSql .= 'WHERE cu.`user_id` = ? ';
		$arrParams[] = $userId;
		if(is_object($sender)){
			$strSql .= 'AND `sender_character_id` = ? ';
			$arrParams[] = $sender->getCharacterId();
        }
        if($text != ""){
			$strSql .= 'AND (`message_text` LIKE ? ';
			$strSql .= 'OR `message_title` LIKE ? )';
			$arrParams[] = '%'.$text.'%';
			$arrParams[] = '%'.$text.'%';
	    }
        $strSql .= 'ORDER BY `date_created` ASC';
        $arrResults = array();
        $arrErrors = array();
        dao::getAssoc($strSql, $arrParams, $arrResults, $arrErrors);
        
        if(!empty($arrErrors)){
        	throw new Exception("Error getting messages!" . implode('|', $arrErrors));
        }
        return $arrResults;
	}
	
	public function parseLine( $line ){
		return "
		<div class=\"char_msg_outer\">
			<div class=\"char_msg_title\">".htmlspecialchars(strip_tags($line['message_title']))."</div>
			<div class=\"char_msg_by\">From: <b>".htmlspecialchars(strip_tags($line['from']))."</b> To: <b>".htmlspecialchars(strip_tags($line['to']))."</b> On <b>".date('Y-m-d H:i:s', $line['date_created'])."</b></div>
			<div class=\"char_msg\">".htmlspecialchars(strip_tags($line['message_text']))."</div>
		</div>";
	}
}
