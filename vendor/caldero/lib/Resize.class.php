<?php
/*
Copyright (C) 2008 Alex Poole

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, see <http://www.gnu.org/licenses>.
*/

class Resize{

    //get original image & turn into a resource
    
    //get required dimensions
    
    //resize image to required dimensions within aspect ratio
    
    //check whether width or height are wrong
    
    //get number of pixels required to pad either width or height
    
    //halve them (or nearly halve them, with a one pixel offset if the total padding amount is an odd number)
    
    //add equal amount of whitespace on each half of the image, either vertically or horizontally
    
    //return the resource
    
    function Resize($imgurl){
        $this->imgurl = $imgurl;
        $this->imgsize = @getImageSize($imgurl);

        if(!$this->imgsize)
            return false;
        if (in_array($this->imgsize['mime'],array("image/jpeg","image/jpg")))
            $this->orig_im = ImageCreateFromJPEG($imgurl);
        elseif($this->imgsize['mime'] == 'image/gif')
            $this->orig_im = ImageCreateFromGif($imgurl);
        elseif($this->imgsize['mime'] == 'image/png')
            $this->orig_im = ImageCreateFromPNG($imgurl);
			
    }
    
    function crop($w, $h){

        $newim = $this->resizeImage($this->orig_im, $this->imgsize[0], $this->imgsize[1], $w, $h);
        
        //return Array($newImage, $neww, $newh);
        if ($newim[1] < $w || $newim[2] < $h){
            //add more width or height
            
            //create new white image
            $paddedimg = imagecreatetruecolor($w, $h);
            $white = imagecolorallocate($paddedimg,0,0,0);
            
			if(substr(strtolower($this->imgurl), -3) == "png"){
				imagealphablending($paddedimg, false);
				imagesavealpha($paddedimg,true);
				$transparent = imagecolorallocatealpha($paddedimg, 255, 255, 255, 127);
				imagefilledrectangle($paddedimg, 0, 0, $iw, $ih, $transparent);
			}
		
            imagefill($paddedimg, 0, 0, $white);
            //stick the thumb in the middle of the new white image
            $pastex = floor(($w - $newim[1]) / 2);
            $pastey = floor(($h - $newim[2]) / 2);
            
            imagecopy($paddedimg, $newim[0], $pastex, $pastey, 0, 0, $newim[1], $newim[2]);
            
            //imagefill($paddedimg, 0, 0, $white);
            
            return $paddedimg;
        /*
        bool imagecopymerge ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h , int $pct )
        Copy a part of src_im onto dst_im starting at the x,y coordinates src_x , src_y with a width of src_w and a height of src_h . The portion defined will be copied onto the x,y coordinates, dst_x and dst_y . 
        */

        } else {
            //pass through
            return $newim[0];
        }
        
    }
    
   
    function resizeImage($image, $iw, $ih, $maxw, $maxh){
        if ($iw > $maxw || $ih > $maxh){
            if ($iw>$maxw && $ih<=$maxh){//too wide, height is OK
                $proportion=($maxw*100)/$iw;
                $neww=$maxw;
                $newh=ceil(($ih*$proportion)/100);
            }
            
            else if ($iw<=$maxw && $ih>$maxh){//too high, width is OK
                $proportion=($maxh*100)/$ih;
                $newh=$maxh;
                $neww=ceil(($iw*$proportion)/100);
            }
            
            else {//too high and too wide

                if ($iw/$maxw > $ih/$maxh){//width is the bigger problem
                    $proportion=($maxw*100)/$iw;
                    $neww=$maxw;
                    $newh=ceil(($ih*$proportion)/100);
                }
                
                else {//height is the bigger problem
                    $proportion=($maxh*100)/$ih;
                    $newh=$maxh;
                    $neww=ceil(($iw*$proportion)/100);
                }
            }
        }
        
        else {//copy image even if not resizing
            $neww=$iw;
            $newh=$ih;
        }
        
        if (function_exists("imagecreatetruecolor")){//GD 2.0=good!
        		
					$newImage = imagecreatetruecolor($neww, $newh);

        	if(substr(strtolower($this->imgurl), -3) == "png"){
						imagealphablending($newImage, false);
						imagesavealpha($newImage,true);
						$transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
						imagefilledrectangle($newImage, 0, 0, $iw, $ih, $transparent);
					}

					imagecopyresampled($newImage, $image, 0, 0, 0, 0, $neww, $newh, $iw, $ih);
                              		
        } else {//GD 1.8=only 256 colours
         	$newImage=imagecreate($neww, $newh);
          imagecopyresized($newImage, $image, 0,0,0,0, $neww, $newh, $iw, $ih);
        }

        return Array($newImage, $neww, $newh);
    }
    
    function cropalpha($w,$h){
			$image = ImageCreateFromPNG($this->imgsize);
			//$im = imagecreatetruecolor($w, $h);
			//imagealphablending($im, true);
			//imagefilledrectangle($im, 30, 30, 70, 70, imagecolorallocate($im, 255, 0, 0));
			
			imagealphablending($image, false);
			imagesavealpha($image, true);
			$im = imagecolorallocatealpha($image, 255, 255, 255, 127);
			imagefilledrectangle($image, 0, 0, $w, $h, $im);
			// Output

			return $image;
    	
    }

}//class

/*
$im = new Resize("http://mysite.com/myimage.jpg");
imagejpeg($im -> crop(250, 156));
*/
