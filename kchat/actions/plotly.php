<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class plotly extends action{
	function action(){
		
		$graph_json = array();
		$json = array();
		$file_json = array();
		
		$json = 'cache/Plotly.json';
		//create cache in every 5 second on access
		if((time() - @filemtime($json)) > 5){
			$stmt = $this->data['pdo']->prepare("SELECT `x`, `y` from {$this->dbprefix}plotly WHERE x >= DATE_SUB(NOW(), INTERVAL 7 DAY);");
			$stmt->execute(array());
			while ($row = $stmt->fetch())
			{
				$graph_json[$row['x']] = $row['y'];
			}
			$file_json = json_encode($graph_json);
			file_put_contents($json,$file_json);
		}else{
			$file_json = file_get_contents($json,1);
		}
		//generating start and end date of plot
		$plot = json_decode($file_json,1);
		$start = array_keys($plot);
		if(!isset($start[0])){
			$start[0] = date("Y-m-d H:0:0",time() - 604800);
		}
		$start = strtotime($start[0]);
		$end = strtotime(date("Y-m-d H:00:00"));
		$current = strtotime(date("Y-m-d H:00:00", $start));
		
		$output = array();
		//to feel empty with 0
		while($current <= $end){
			if(isset($plot[date("Y-m-d H:i:s", $current)])){
				$output[date("Y-m-d H:i:s", $current)] = $plot[date("Y-m-d H:i:s", $current)];
			}else{
				$output[date("Y-m-d H:i:s", $current)] = 0;
			}
			$current += 3600;
		}
		$output_json = array();
		//preparing output json
		$output_json['type'] = 'scatter';
		$output_json['x'] = array_keys($output);
		$output_json['y'] = array_values($output);
		return json_encode($output_json);
	}
}