<?php
/**
* AUTO-GENERATED
* DO NOT EDIT
*/
require_once CORE_ROOT . 'DAO.php';
class Model_Data_CharacterMessageProviderBase
{
    protected function getOneFromQuery($strSql, $params)
    {
        $arrResults = array();
        $arrErrors = array();
        if (DAO::getAssoc($strSql, $params, $arrResults, $arrErrors)) {
            if (count($arrResults) > 0) {
                return new Model_Structure_CharacterMessage($arrResults[0]);
            }
        }
        return null;
    }

    protected function getArrayFromQuery($strSql, $params)
    {
        $arrResults = array();
        $arrErrors = array();
        if (DAO::getAssoc($strSql, $params, $arrResults, $arrErrors)) {
            $arrRecordList = array();
            foreach ($arrResults as $arrRecord) {
                $arrRecordList[] = new Model_Structure_CharacterMessage($arrRecord);
            }
            return $arrRecordList;
        }
        return null;
    }

    public function getOneByPk($character_message_id)
    {
        $strSql = 'SELECT * FROM `character_message` WHERE character_message_id=?';
        $params = array($character_message_id);
        return Model_Data_CharacterMessageProvider::getOneFromQuery($strSql, $params);
    }

    public function insertOne(&$objRecord, &$arrErrors)
    {
        $strSql = ' INSERT INTO `character_message` (
            character_message_id,
            sender_user_id,
            sender_character_id,
            recipient_character_id,
            message_title,
            message_text,
            date_created,
            viewed,
            date_viewed
        ) VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            0,
            $objRecord->getSenderUserId(),
            $objRecord->getSenderCharacterId(),
            $objRecord->getRecipientCharacterId(),
            $objRecord->getMessageTitle(),
            $objRecord->getMessageText(),
            $objRecord->getDateCreated(),
            $objRecord->getViewed(),
            $objRecord->getDateViewed()
        );
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        if ($blnResult) {
            $objRecord->setCharacterMessageId(DAO::getInsertId());
        }
        return $blnResult;
    }

    public function replaceOne(&$objRecord, &$arrErrors)
    {
        $strSql = ' REPLACE INTO `character_message` (
            character_message_id,
            sender_user_id,
            sender_character_id,
            recipient_character_id,
            message_title,
            message_text,
            date_created,
            viewed,
            date_viewed
        ) VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            0,
            $objRecord->getSenderUserId(),
            $objRecord->getSenderCharacterId(),
            $objRecord->getRecipientCharacterId(),
            $objRecord->getMessageTitle(),
            $objRecord->getMessageText(),
            $objRecord->getDateCreated(),
            $objRecord->getViewed(),
            $objRecord->getDateViewed()
        );
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        if ($blnResult) {
            $objRecord->setCharacterMessageId(DAO::getInsertId());
        }
        return $blnResult;
    }

    public function updateOne($objRecord, &$arrErrors)
    {
        $strSql = 'UPDATE `character_message` SET 
            character_message_id=?,
            sender_user_id=?,
            sender_character_id=?,
            recipient_character_id=?,
            message_title=?,
            message_text=?,
            date_created=?,
            viewed=?,
            date_viewed=?
        WHERE character_message_id=?';
        $arrSetParams = array(
            $objRecord->getCharacterMessageId(),
            $objRecord->getSenderUserId(),
            $objRecord->getSenderCharacterId(),
            $objRecord->getRecipientCharacterId(),
            $objRecord->getMessageTitle(),
            $objRecord->getMessageText(),
            $objRecord->getDateCreated(),
            $objRecord->getViewed(),
            $objRecord->getDateViewed()
        );
        $arrKeyParams = array($objRecord->getOrigCharacterMessageId());
        $params = array_merge($arrSetParams, $arrKeyParams);
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        return $blnResult;
    }

    public function deleteOne($objRecord, &$arrErrors)
    {
        $strSql = 'DELETE FROM `character_message` WHERE character_message_id=?';
        $params = array($objRecord->getCharacterMessageId());
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        return $blnResult;
    }
}
