<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class conline extends action{
	
	function action(){
		
		$conline = array();
		$return = array();
		
		$json = 'cache/online.json';
		
		if((time() - @filemtime($json)) < 5){
			return json_decode(file_get_contents($json),1);
		}
		
		$ip4db = $this->data['config']['path'].'/kchat/data/GeoLiteCity.dat';
		$ip6db = $this->data['config']['path'].'/kchat/data/GeoLiteCityv6.dat';
		
		$stmt = $this->data['pdo']->prepare("SELECT `id`,(SELECT concat(`fname`,' ',`lname`) from {$this->dbprefix}users WHERE id = {$this->dbprefix}guest.id) as guest ,`ip`,`country_code`,`time_zone`,`latitude`,`longitude` from {$this->dbprefix}guest where `id` IN (SELECT `support_id` FROM `{$this->dbprefix}cache` where (`time` > (unix_timestamp() - 5)));");
		$stmt->execute(array());
		while ($row = $stmt->fetch())
		{
			$conline[] = $row;
		}
		
		$i = 0;
		foreach($conline as $key => $_online){
			if($_online['country_code'] == 'Unknown'){
				
				$update = array();
				
				if(is_ip4($_online['ip'])){
					$ip4dat = geoip_open($ip4db, GEOIP_STANDARD);
					$record = geoip_record_by_addr($ip4dat, $_online['ip']);
				}else{
					$ip6dat = geoip_open($ip6db, GEOIP_STANDARD);
					$record = geoip_record_by_addr_v6($ip6dat, $_online['ip']);
				}
				
				$conline[$key]['country_code'] = $record->country_code;
				$conline[$key]['time_zone'] = get_time_zone($record->country_code, $record->region);
				$conline[$key]['latitude'] = $record->latitude;
				$conline[$key]['longitude'] = $record->longitude;
				
				$update[$i]['id'] = $_online['id'];
				$update[$i]['country_code'] = $record->country_code;
				$update[$i]['time_zone'] = get_time_zone($record->country_code, $record->region);
				$update[$i]['latitude'] = $record->latitude;
				$update[$i]['longitude'] = $record->longitude;
				$i++;
			}
		}
		
		if(isset($update)){
			foreach($update as $value){
				$stmt = $this->data['pdo']->prepare("UPDATE `{$this->dbprefix}guest` SET `country_code` = :country_code, `time_zone` = :time_zone, `latitude` = :latitude, `longitude` = :longitude where id = :id;");
				$stmt->execute($value);
			}
		}
		
		file_put_contents($json, json_encode($conline));
		
		return $conline;
	}
}