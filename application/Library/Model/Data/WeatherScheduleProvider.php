<?php
class Model_Data_WeatherScheduleProvider extends Model_Data_WeatherScheduleProviderBase
{
	/*
	Do yourself a favor and don't call this if it's not an IC room.
	*/
	public function getByRoomId( $roomId ){
		$strSql = "
SELECT ws.*, cr1.chat_room_id as `id1`, cr2.chat_room_id as `id2`
FROM weather_schedule ws
left join chat_room cr1
	on ws.chat_room_id = cr1.chat_room_id
left join chat_room cr2
	on ws.chat_room_type_id = cr2.weather_group
where ( cr1.chat_room_id = ? or cr2.chat_room_id = ? )
	AND start_ts < ?
	AND end_ts > ?
ORDER BY weather_schedule_id DESC
LIMIT 1
		";
		$params = array($roomId, $roomId, time(), time());
		//echo $strSql . "<br>";
		//print_r($params);
		return Model_Data_WeatherScheduleProvider::getOneFromQuery($strSql, $params);
	}
}
