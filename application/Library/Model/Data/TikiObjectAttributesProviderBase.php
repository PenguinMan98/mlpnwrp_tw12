<?php
/**
* AUTO-GENERATED
* DO NOT EDIT
*/
require_once CORE_ROOT . 'DAO.php';
class Model_Data_TikiObjectAttributesProviderBase
{
    protected function getOneFromQuery($strSql, $params)
    {
        $arrResults = array();
        $arrErrors = array();
        if (DAO::getAssoc($strSql, $params, $arrResults, $arrErrors)) {
            if (count($arrResults) > 0) {
                return new Model_Structure_TikiObjectAttributes($arrResults[0]);
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
                $arrRecordList[] = new Model_Structure_TikiObjectAttributes($arrRecord);
            }
            return $arrRecordList;
        }
        return null;
    }

    public function getOneByPk($attributeId)
    {
        $strSql = 'SELECT * FROM `tiki_object_attributes` WHERE attributeId=?';
        $params = array($attributeId);
        return Model_Data_TikiObjectAttributesProvider::getOneFromQuery($strSql, $params);
    }

    public function insertOne(&$objRecord, &$arrErrors)
    {
        $strSql = ' INSERT INTO `tiki_object_attributes` (
            attributeId,
            type,
            itemId,
            attribute,
            value
        ) VALUES  (?, ?, ?, ?, ?)';
        $params = array(
            0,
            $objRecord->getType(),
            $objRecord->getItemid(),
            $objRecord->getAttribute(),
            $objRecord->getValue()
        );
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        if ($blnResult) {
            $objRecord->setAttributeid(DAO::getInsertId());
        }
        return $blnResult;
    }

    public function replaceOne(&$objRecord, &$arrErrors)
    {
        $strSql = ' REPLACE INTO `tiki_object_attributes` (
            attributeId,
            type,
            itemId,
            attribute,
            value
        ) VALUES  (?, ?, ?, ?, ?)';
        $params = array(
            0,
            $objRecord->getType(),
            $objRecord->getItemid(),
            $objRecord->getAttribute(),
            $objRecord->getValue()
        );
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        if ($blnResult) {
            $objRecord->setAttributeid(DAO::getInsertId());
        }
        return $blnResult;
    }

    public function updateOne($objRecord, &$arrErrors)
    {
        $strSql = 'UPDATE `tiki_object_attributes` SET 
            attributeId=?,
            type=?,
            itemId=?,
            attribute=?,
            value=?
        WHERE attributeId=?';
        $arrSetParams = array(
            $objRecord->getAttributeid(),
            $objRecord->getType(),
            $objRecord->getItemid(),
            $objRecord->getAttribute(),
            $objRecord->getValue()
        );
        $arrKeyParams = array($objRecord->getOrigAttributeid());
        $params = array_merge($arrSetParams, $arrKeyParams);
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        return $blnResult;
    }

    public function deleteOne($objRecord, &$arrErrors)
    {
        $strSql = 'DELETE FROM `tiki_object_attributes` WHERE attributeId=?';
        $params = array($objRecord->getAttributeid());
        $arrErrors = array();
        $blnResult = DAO::execute($strSql, $params, $arrErrors);
        return $blnResult;
    }
}
