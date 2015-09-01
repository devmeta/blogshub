<?php namespace App\Models;

class ArrayUtil {
	
	public function sort($data,$key,$order='asc'){
		// sort by time update, (BUBBLE)
		$array_size = count($data);
		
		if($order=='asc')
		for($x = 0; $x < $array_size; $x++) {
			for($y = 0; $y < $array_size; $y++) {
				if($data[$x][$key] < $data[$y][$key]) {
					$hold = $data[$x];
					$data[$x] = $data[$y];
					$data[$y] = $hold;
				}
			}
		}
		return $data;
	}
}