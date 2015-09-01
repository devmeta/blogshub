<?php namespace App\Models;
 
class Model extends DB { 

    static $table;
    static $model;
    protected static $onlyInstance;
    private static $query="";
	private static $select=array();
	private static $fields=array();
	private static $where=array();
    private static $joins=array();
	private static $orderby="";
	private static $groupby="";

    protected function __construct () { 
		global $mname2;
		self::$model = "\\App\\Models\\{$mname2}";
    }

    protected static function getself(){
        if (self::$onlyInstance === null){
            self::$onlyInstance = new Model;
        }
        return self::$onlyInstance;
    }

	private function getWhere(){
		return count(self::$where) ? " where " . implode(' and ',self::$where) : "";
	}

	private function getJoin(){
		$model = self::$model;
		$from = $model::$table;		
		$join = "";
		$joins = array();
		foreach(self::$joins as $row){
			if( ! in_array($row[0],$joins)){
				$join.=" left join " . $row[0] . " on " . $row[0] . "." . $row[1] . " = " . $from . ".id ";
				$joins[]=$row[0];
			}
		}
		return $join;
	}

	private function arrange($data){
		$filter = array();
		foreach($data as $i => $row){
			foreach($row as $key => $value){
				if(strpos($key,'__') === false){
					$filter[$i][$key] = $value;
				} else {
					foreach(self::$joins as $join){
						$key2 = str_replace('__'.$join[2].'__','',$key);
						$filter[$i][$join[2]][$key2] = $value;
					}
				}
			}
		}
		self::$joins = array();
		self::$where = array();
		return $filter;
	}

	private function arrange_single($data){
		$filter = array();
		foreach($data as $key => $value){
			if(strpos($key,'__') === false){
				$filter[$key] = $value;
			} else {
				foreach(self::$joins as $join){
					$key2 = str_replace('__'.$join[2].'__','',$key);
					$filter[$join[2]][$key2] = $value;
				}
			}
		}
		self::$joins = array();
		self::$where = array();
		return $filter;
	}
	
	private function getOrderby(){
		return "";
	}

	private function getSelect(){
		$model = self::$model;
		$from = $model::$table;
		$join = "";
		$select = count(self::$select) ? implode(',',self::$select) : "{$from}.*";

		foreach(self::$joins as $row){
			if( isset(self::$fields[$row[0]]) ){
				$fields = self::$fields[$row[0]];
			} else {
				$fields = self::columns($row[0]);
				self::$fields[$row[0]] = $fields;
			}
			$fields2 = array();
			foreach($fields as $field){
				$fields2[] = $row[0] . '.' . $field . ' as __' . $row[2] . '__' . $field;
			}
			$join.="," . implode(',',$fields2);
		}
		return "select {$select} {$join} from {$from}";
	}

	private function getQuery(){
		$query =  
			self::getSelect() . 
			self::getJoin() . 
			self::getWhere() . 
			self::getOrderby();
		return $query;
	}

	public function hasOne($model, $parent_id = null){
		$mpath = PATH_MDL . $model . ".php";
		$trace = debug_backtrace();
		$caller = $trace[1];

		if(file_exists( $mpath )){
			$instance = "\\App\\Models\\{$model}";

			if( ! class_exists( $instance ) ){
				include $mpath;
			}

			$table = $instance::$table;

			if(is_null($parent_id)){
				$parent_id = $table . "_id";
			}

			self::$joins[] = array($table,$parent_id,$caller['function']);
		}

		return self::getself(); 
	}

	public function hasMany($model, $parent_id = null){
		$mpath = PATH_MDL . $model . ".php";
		$trace = debug_backtrace();
		$caller = $trace[1];

		if(file_exists( $mpath )){
			$instance = "\\App\\Models\\{$model}";

			if( ! class_exists( $instance ) ){
				include $mpath;
			}

			$table = $instance::$table;

			if(is_null($parent_id)){
				$parent_id = $table . "_id";
			}

			self::$joins[] = array($table,$parent_id,$caller['function']);
		}

		return self::getself(); 
	}

	public function with($method){
		global $mname2;

		$instance = "\\App\\Models\\{$mname2}";

		if(method_exists($instance, $method)){
			call_user_func_array(array($instance, $method), array());
		}

		return self::getself(); 
	}

	public function where($field,$op,$value=null){
		if(is_null($value)){
			$value = $op;
			$op = "=";
		}

		$value = "'" . $value . "'";
		self::$where[] = $field.$op.$value;
        return self::getself();
	}

	public function get($select=array()){
		self::getself(); 
		if(count($select)){
			self::$select = $select;
		}
		return self::arrange(self::query(self::getQuery(),0));		
	}

	public function first(){
		self::getself(); 
		return self::arrange_single(self::query(self::getQuery(),1));		
	}

	public function all(){
		self::getself(); 
		return self::arrange(self::query(self::getQuery(),0));
	}

	public function find($id){
		//self::$where[] = "id=".$id;
		return self::query(self::getQuery(),1);
	}
}