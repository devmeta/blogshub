<?php 

include 'vendor/caldero/lib/utils.php';
include 'vendor/caldero/lib/ArrayUtil.class.php';

class CamController {
	
	public function index($name){
		$fsdata = dirname(__FILE__) . '/../../data/' . $name;
		$filelist = Utils::fileList($fsdata,5);
		$files = array();

		foreach($filelist as $file){

			$ts2 = strtok($file,".");
			$mins = (time() - filename2date($ts2, 'U')) / 60;
			$unixtime= filename2date($ts2, ($mins > 1440 ? 'Y M j D H:i' : 'D H:i'));

			$files[] = array(
				'filename'	=> $file,
				'ts'	=> date2es($unixtime) . ' (hace '. timespan($mins) .')'
			);
		}

		return array(
			'name' => $name,
			'files' => $files
		);
	}
}