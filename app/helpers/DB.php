<?php namespace App\Helpers;

class DB {
	
	function connect(){

		global $config;

		$database = $config['database'];
		$link = mysql_connect($database['host'],$database['user'],$database['pass'],$database['name']);

		if(! $link) {	
			die("Could not connect to database.");
		}

		$error = mysql_error();
		$nro = mysql_errno();

		mysql_select_db($database['name']);	
		mysql_set_charset('utf-8', $link);
		
		if($error) {
			die($nro . $error);
		}

		if( ( isset($_REQUEST['op']) && $_REQUEST['op'] == 'login') || isset($_REQUEST['encoded_pass'])){}else{
			mysql_query( "SET NAMES 'utf8' COLLATE 'utf8_general_ci'" );
		}

		return $link;
	}

	static function query($query,$type=0,$field=0,$resource=null,$debug = true){

		global $link;

		if( ! is_resource($resource))
			$resource = $link;

		$query = trim($query);
		$id = strtolower(substr($query,0,3));
		$result = array();
		
		switch($id){
			
			case 'sel':
			case 'sho':
				$result = DB::read($query,$type,$field,$resource);
			break;

			case 'ins':
			case 'del':
			case 'upd':
			case 'ren':
			case 'cre':
			case 'alt':
			case 'dro':

				$result = DB::write($query,$resource);
			break;
		
		}
		
		return $result;
	}
		
	function read($query,$type=0,$field=0){
		global $config;

		$result = mysql_query($query);
		$dataset = array();

		if(mysql_error()){
			debug(mysql_error());
			debug("query was: " . $query);
			return array("error" => mysql_error() . "\n query was \n" . $query);
		}

		if($config['debug']) {
			//debug($query);
		}
				
		switch($type){
			case 0:

				$fullresult = array();
				while($row = @mysql_fetch_assoc($result)){
					if(isset($row['created_ts'])){
						$row['created'] = timespan($row['created_ts']);
					}
					if(isset($row['updated_ts'])){
						$row['updated'] = timespan($row['updated_ts']);
					}

					array_push($fullresult,$row);
				}

				$dataset = $fullresult;
				
			break;
			
			case 1:

				$dataset = @mysql_fetch_assoc($result);

				if(isset($dataset['created_ts'])){
					$dataset['created'] = timespan($dataset['created_ts']);
				}
				if(isset($dataset['updated_ts'])){
					$dataset['updated'] = timespan($dataset['updated_ts']);
				}

			break;
			
			case 2:

				if(@mysql_num_rows($result) && @mysql_num_fields($result) > $field){
					$dataset = @mysql_result($result,0,$field);
				} else {
					$dataset = false;
				}

			break;

			case 3:
			
				$fullresult = array();
				$row = @mysql_fetch_assoc($result);
				$dataset = $row[$field];
				
			break;

			case 4:
			
				$fullresult = array();
				
				while($row = @mysql_fetch_assoc($result)){
					array_push($fullresult,$row[$field]);
				}
				
				$dataset = $fullresult;
				
			break;			
		}
    

		if($result){
			mysql_free_result($result);    
		}
		
		return $dataset;
	}

	// Operaciones INSERT / UPDATE
	function write($query,$resource = null){
		global $link;

		if($resource == null)
			$resource = $link;

		$result = mysql_query($query);

		if(mysql_error()){
			DB::debug($query);
		}		

		return mysql_insert_id();
	}

	static function columns($table){
		return DB::query('show columns from ' . $table,4,'Field');
	}

	static function update($table, $id = '', $fields = '*', $data = null, $resource = null){
		global $link;

		if($resource == null)
			$resource = $link;
		
		if($fields=='*')
			$fields = implode(' ',DB::query('show columns from ' . $table,4,'Field'));
		if($data==null)
			$data = $_POST;
		if(is_array($fields))
			$fields = implode(' ',$fields);

		$data = (array) $data;
		//var_dump($fields );
			
		$query = '';
		$fields = array_filter(array_values(explode(' ',$fields)));
		$set = array();
		$exists = false;
		$lastid = 0;

		if(is_numeric($id)){
			$exists = DB::query('select id from ' . $table . ' where id = ' . $id,2,'id');
		}

		foreach($fields as $field)
			if((isset($data[$field]) && $data[$field]) || (isset($data[$field]) && $data[$field]=='0'))
				$set[]= $field . ' = \'' . html_entity_decode(trim(mysql_real_escape_string($data[$field]))) . '\'';

		if($exists){
			$set[]= "updated_ts = " . time();
			$query = 'update ' . $table . ' set ' . implode(',',$set) . ' where id = ' . $id;
		} else {
			$set[]= "created_ts = " . time();
			$set[]= "updated_ts = " . time();
			$query = 'insert into ' . $table . ' set ' . implode(',',$set);
		}
			
		$lastid = DB::write($query);

		return $lastid ? $lastid : $id ;	
	}

	private function debug($query){
		$str = "*** SQL Error ***";
		$str.= mysql_error();
		$str.="query was: " . $query;
		
		log2file( dirname(__FILE__) . "/../../../public/debug.txt",$str);	
	}	
}