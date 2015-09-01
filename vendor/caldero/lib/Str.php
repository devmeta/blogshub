<?php namespace App\Models;

class Str extends \DateTime {

	static function words($words){

		$wcount =  str_word_count($words);

		$ret = '';
		$words_pmin = 200;

		$estread = ceil($wcount / $words_pmin);
		$ret.= "" . $wcount . " palabras &mdash; \n";
		$ret.= "" . $estread . " minuto";
		$ret.= $estread > 1 ? "s":"";

		return $ret;
	}
	
	static function utf8_uri_encode( $utf8_string, $length = 0 ) {  $unicode = '';  $values = array(); $num_octets = 1; $unicode_length = 0; $string_length = strlen( $utf8_string ); for ($i = 0; $i < $string_length; $i++ ) {        $value = ord( $utf8_string[ $i ] );  if ( $value < 128 ) { if ( $length && ( $unicode_length >= $length ) ) break; $unicode .= chr($value); $unicode_length++; } else { if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3; $values[] = $value; if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length ) break; if ( count( $values ) == $num_octets ) { if ($num_octets == 3) { $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]); $unicode_length += 9;} else { $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);$unicode_length += 6;}$values = array();$num_octets = 1; }}} return $unicode;}
	static function seems_utf8($str) { $length = strlen($str);  for ($i=0; $i < $length; $i++) { $c = ord($str[$i]); if ($c < 0x80) $n = 0; elseif (($c & 0xE0) == 0xC0) $n=1; elseif (($c & 0xF0) == 0xE0) $n=2; elseif (($c & 0xF8) == 0xF0) $n=3; elseif (($c & 0xFC) == 0xF8) $n=4; elseif (($c & 0xFE) == 0xFC) $n=5; else return false;for ($j=0; $j<$n; $j++) { if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))return false;} } return true;}
	static function slug($title) { $title = strip_tags($title);    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);    $title = str_replace('%', '', $title);    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);    if (Str::seems_utf8($title)) {        if (function_exists('mb_strtolower')) {            $title = mb_strtolower($title, 'UTF-8');        }        $title = Str::utf8_uri_encode($title, 200);    }    $title = strtolower($title);    $title = preg_replace('/&.+?;/', '', $title); $title = str_replace('.', '-', $title);    $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);    $title = preg_replace('/\s+/', '-', $title);    $title = preg_replace('|-+|', '-', $title);    $title = trim($title, '-');    return $title;}


    static function sanitize($name,$folder = '')
    {

    	if($folder==''){
    		$folder = PATH_UPLOAD;
    	}

        $safe = $name;
        $safe = str_replace("#", "Nro", $safe);
        $safe = str_replace("$", "Dollar", $safe);
        $safe = str_replace("%", "Percent", $safe);
        $safe = str_replace("^", "", $safe);
        $safe = str_replace("&", "and", $safe);
        $safe = str_replace("*", "", $safe);
        $safe = str_replace("?", "", $safe);
        $safe = str_replace("(", "", $safe);
        $safe = str_replace(")", "", $safe);

		$files = File::dir_content($folder);
        sort($files);
        $j=1;

        while(in_array($safe,$files))
        {
            $safe= preg_replace("/\((.*?)\)/", "",$safe);
            $parts= explode(".",$safe);
            $parts2= $parts;
            unset($parts2[count($parts2)-1]);
            $safe= implode(".",$parts2) . "($j)." .  $parts[count($parts)-1];
            $j++;
        }

        $safelast = str_replace(' ','-',strtolower($safe));
        return Str::make_unique_filename($safelast,$folder); 
    }

    static function make_unique_filename($filename, $destination)
    {
        $i = 0;
        $path_parts = pathinfo($filename);
        $path_parts['filename'] = Str::slug($path_parts['filename']);
        $filename = $path_parts['filename'];

        while (file_exists($destination.$filename.'-th.'.$path_parts['extension'])) {
            $filename = $path_parts['filename'].'-'.$i;
            $i++;
        }

        return $filename.'.'.$path_parts['extension'];
    }

    
	static function nice_size($fs){if ($fs >= 1073741824) $fs = round(($fs / 1073741824 * 100) / 100).' Gb'; elseif ($fs >= 1048576) $fs = round(($fs / 1048576 * 100) / 100).' Mb'; elseif ($fs >= 1024) $fs = round(($fs / 1024 * 100) / 100).' Kb';else $fs = $fs .' b';return $fs;}
	
	static function credits2euro($credits = 0, $comma = true){
		$dinero = (float) Option::where('option_key','credit_euro_' . $credits)
			->pluck('option_value');

		if(! $dinero ){
			$dinero = (float) Option::where('option_key','credit_euro')
				->pluck('option_value');
		}

		$formateado = sprintf("%01.2f", $dinero * $credits);
		//$formateado = round(sprintf("%01.2f", $dinero * $credits),2);

		if( $comma ){
			$formateado = str_replace(".",",",$formateado);
		}

		return $formateado;
	}

	static function bold($str, $keywords = '')
	{
		$keywords = preg_replace('/\s\s+/', ' ', strip_tags(trim($keywords))); // filter

		$style = 'bold';
		$style_i = 'bold-strong';

		/* Apply Style */

		$var = '';

		foreach(explode(' ', $keywords) as $keyword)
		{
			$replacement = "<span class='".$style."'>".$keyword."</span>";
			$var .= $replacement." ";

			$str = str_ireplace($keyword, $replacement, $str);
		}

		/* Apply Important Style */

		$str = str_ireplace(rtrim($var), "<span class='".$style_i."'>".$keywords."</span>", $str);

		return $str;
	}

  static function getCColor( $credits  ){
		$color = 'grey';

		if($credits > 0 && $credits < 100) {
			$color = 'blue';
		} else if($credits > 0 && $credits < 500) {
			$color = 'green';
		} else if($credits > 0 && $credits < 1000) {
			$color = 'magenta';
		}

		return $color;

	}



  static function get_public_id( $id )
  {
    return date('y') . sprintf("%04s", $id);
  }
  static function get_id_from_public_id( $id )
  {
    return (int) substr($id, -4);
  }	



}

