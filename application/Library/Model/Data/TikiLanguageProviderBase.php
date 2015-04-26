<?php
/**
* AUTO-GENERATED
* DO NOT EDIT
*/
require_once CORE_ROOT . 'DAO.php';
class Model_Data_TikiLanguageProviderBase
{
    protected function getOneFromQuery($strSql, $params)
    {
        $arrResults = array();
        $arrErrors = array();
        if (DAO::getAssoc($strSql, $params, $arrResults, $arrErrors)) {
            if (count($arrResults) > 0) {
                return new Model_Structure_TikiLanguage($arrResults[0]);
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
                $arrRecordList[] = new Model_Structure_TikiLanguage($arrRecord);
            }
            return $arrRecordList;
        }
        return null;
    }

    public function getOneByPk($id)
    {
        $strSql = 'SELECT * FROM `tiki_language` WHERE id=?';
        $params = array($id);
        return Model_Data_TikiLanguageProvider::getOneFromQuery($strSql, $params);
    }

    public function insertOne(&$objRecord, &$arrErrors)
    {
        $strSql = ' INSERT INTO `tiki_language` (
            id,
            source,
            lang,
            tran,
            changed,
            userId,
            lastModif
        ) VALUES  (?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            0,
            $objRecord->getSource(),
            $objRecord->getLang(),
            $objRecord->getTran(),
            $objRecord->getChanged(),
            $objRecord->getUserid(),
            $objRecord->getLastmodif()
        );
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        if ($blnResult) {
            $objRecord->setId(DAO::getInsertId());
        }
        return $blnResult;
    }

    public function replaceOne(&$objRecord, &$arrErrors)
    {
        $strSql = ' REPLACE INTO `tiki_language` (
            id,
            source,
            lang,
            tran,
            changed,
            userId,
            lastModif
        ) VALUES  (?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            0,
            $objRecord->getSource(),
            $objRecord->getLang(),
            $objRecord->getTran(),
            $objRecord->getChanged(),
            $objRecord->getUserid(),
            $objRecord->getLastmodif()
        );
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        if ($blnResult) {
            $objRecord->setId(DAO::getInsertId());
        }
        return $blnResult;
    }

    public function updateOne($objRecord, &$arrErrors)
    {
        $strSql = 'UPDATE `tiki_language` SET 
            id=?,
            source=?,
            lang=?,
            tran=?,
            changed=?,
            userId=?,
            lastModif=?
        WHERE id=?';
        $arrSetParams = array(
            $objRecord->getId(),
            $objRecord->getSource(),
            $objRecord->getLang(),
            $objRecord->getTran(),
            $objRecord->getChanged(),
            $objRecord->getUserid(),
            $objRecord->getLastmodif()
        );
        $arrKeyParams = array($objRecord->getOrigId());
        $params = array_merge($arrSetParams, $arrKeyParams);
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        return $blnResult;
    }

    public function deleteOne($objRecord, &$arrErrors)
    {
        $strSql = 'DELETE FROM `tiki_language` WHERE id=?';
        $params = array($objRecord->getId());
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        return $blnResult;
    }
}
