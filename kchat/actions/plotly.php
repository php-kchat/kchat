<?php

/**
* KChat -
* Author Ganesh Kandu
* Contact kanduganesh@gmail.com 
*/

class plotly extends action{
	function action(){
		
		$graph_json = array();
		$return = array();
		
		$json = 'cache/Plotly.json';
		if((time() - @filemtime($json)) > 3600){
			$stmt = $this->data['pdo']->prepare("SELECT `x`, `y` from {$this->dbprefix}plotly;");
			$stmt->execute();
			while ($row = $stmt->fetch())
			{
				$graph_json['x'][] = $row['x'];
				$graph_json['y'][] = $row['y'];
			}
			$graph_json['type'] = 'scatter';
			$return = json_encode($graph_json);
			file_put_contents($json,$return);
		}else{
			$return = file_get_contents($json,1);
		}
		
		return $return;
	}
}