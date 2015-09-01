<?php 

		$fa_icons_src = dirname(__FILE__) . "/../../assets/css/ionicons.css";
		$fa_lines = file($fa_icons_src);
		$ion_icon_files = array();

		foreach($fa_lines as $line)
		{
			if(strstr($line,':before'))
			{
				$classname = str_replace(".",'',strtok($line,':'));
				$ion_icon_files[] = $classname;
			}
		}
