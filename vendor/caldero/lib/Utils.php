<?php 

class Utils {

	function log2file($filename, $data, $mode="w+"){
	   $fh = fopen($filename, $mode) or die("can't open file");
	   fwrite($fh,$data . "\n");
	   fclose($fh);
	   return $fh;
	}

	function nice_size($fs)
	{
		if ($fs >= 1073741824) 
			$fs = round(($fs / 1073741824 * 100) / 100).' Gb'; 
		elseif ($fs >= 1048576) 
			$fs = round(($fs / 1048576 * 100) / 100).' Mb'; 
		elseif ($fs >= 1024) 
			$fs = round(($fs / 1024 * 100) / 100).' Kb';
		else 
			$fs = $fs .' b';
		return $fs;
	}

	function file_extension($filename)
	{
		//$path_info = pathinfo($filename);
		//return $path_info['extension'];
		$dot= substr(strrchr($filename, "."), 1);
		$str= explode("?",$dot);
		return strtolower($str[0]);
	}
	 
	function fileList($directory) 
	{	
		$results = array();
		$handler = opendir($directory);

		while ($file = readdir($handler)) 
		{
			if (!in_array($file, array('.','..','index.php')))
			{
				$results[] = $file;
			}
		}

		closedir($handler);
		return $results;				
	}
		   
	function dirList ($directory, $filter = "", $exclude = "" ) {
		if(substr($directory,-1) != '/') $directory .=  "/";
		$excludeAlways = array(".", "..", "index.php","Thumbs.db");
		$arrFilter = array();
		$arrExclude = array();	
		// create an array to hold directory list
		$results = array();
		
		if (strlen($filter)){
			$arrFilter = explode(' ', $filter);
		}

		if (strlen($exclude)){
			$arrExclude = explode(' ', $exclude);
		}

		// create a handler for the directory
		$handler = opendir($directory);

		// keep going until all files in directory have been read
		while ($file = readdir($handler)) {
			
			if($arrFilter){
				if (!in_array(Utils::file_extension($file), $arrFilter)) {
					continue;
				}
			}

			if($arrExclude){
				if (in_array(Utils::file_extension($file), $arrExclude)) {
					continue;
				}
			}

			$filepath = $directory.$file;
			// if $file isn't this directory or its parent, 
			// add it to the results array
			if (!in_array($file, $excludeAlways)){
				if(is_dir($file)){
					$results[0][] = $file;
				} else {
					$file_stats = stat($filepath);
					$results[1][] = array(
						$file
						,Utils::nice_size($file_stats[7])
						,date('l, F dS 20y - H:i:s',$file_stats[8])
						,date('l, F dS 20y - H:i:s', $file_stats[9])
						,$file_stats
					);
				}
			}
		}

		// tidy up: close the handler
		closedir($handler);

		// done!
		return $results;

	}

	function createthumb($name, $filename, $new_w, $new_h) {
		
	//echo "name : " . $name;
	//echo "filename : " . $filename;
		//$system = explode('.', $name);
		$ext = Utils::file_extension( $name );
		if (preg_match('/jpg|jpeg/',$ext)){
			$src_img=imagecreatefromjpeg($name);
		}
		if (preg_match('/png/',$ext)){
			$src_img=imagecreatefrompng($name);
		}
		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);
		if ($old_x > $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$old_y*($new_h/$old_x);
		}
		if ($old_x < $old_y) {
			$thumb_w=$old_x*($new_w/$old_y);
			$thumb_h=$new_h;
		}
		if ($old_x == $old_y) {
			$thumb_w=$new_w;
			$thumb_h=$new_h;
		}
		
		$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

		if (preg_match("/png/",$ext))
		{
			imagepng($dst_img,$filename); 
		} else {
			imagejpeg($dst_img,$filename); 
		}
		imagedestroy($dst_img); 
		imagedestroy($src_img); 
	}
}