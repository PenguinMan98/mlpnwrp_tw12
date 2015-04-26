<?php
class Model_Data_LogProvider extends Model_Data_LogProviderBase
{
	public function getLastLog(){
		$strSql = "SELECT * FROM `log` ORDER BY `timestamp` DESC LIMIT 1";
		return $this->getOneFromQuery($strSql, array());
	}
}
