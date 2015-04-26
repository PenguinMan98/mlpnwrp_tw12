<?php
class Model_Data_WeatherStateProvider extends Model_Data_WeatherStateProviderBase
{
	
	public function getOneByName( $weatherState ){
		$strSql = "
SELECT * from weather_state where name = ?
LIMIT 1
		";
		$params = array( $weatherState );
		//echo $strSql . "<br>";
		//print_r($params);
		return self::getOneFromQuery($strSql, $params);
	}
}
