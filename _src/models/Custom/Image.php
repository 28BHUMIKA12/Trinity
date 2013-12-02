<?php

/**
 * Model_Custom_Image class.
 */
class Model_Custom_Image {
	
	/**
	 * thumb function.
	 * 
	 * @access public
	 * @return void
	 */
	public function thumb($img){

	    if (!isset($img)){
	        $img = "default.jpg";
	    }
	
		#-+ Directory where original images reside
	    $imgdir = DOC_ROOT."/uploads/";
	
		#-+ Directory where the thumbnails will be saved
	    $tndir = DOC_ROOT."/uploads//thumb/90/";
		$bigtndir = DOC_ROOT."/uploads/thumb/250/";
	
		#-+ Thumbnail width
	    $tn_w = 90;
	    $bigtn_w = 250;
	    
		#-+ Check if the file exists
	    if (!file_exists($imgdir.$img))
	    {
	        die ("Error: File not found...");
	    }
	
		#-+ Check if the file extesion is .jpg
	    $ext = explode('.', $img); 
	    $ext = $ext[count($ext)-1]; 
	    
	    //$type = 'jpg';
	    $check = strtolower($ext);
    	$type = 'jpg';
	    
	    if ($check == "jpg" || $check == "jpeg"){
	    	$type = 'jpg';
	    }
	    else if ($check == "png"){
	    	$type = 'png';
	    }
	    else{
	    	return false;
	    }
	    
	    
	
		#-+ Read the source image
	    if($type == 'jpg')	
		    $src_img = ImageCreateFromJPEG($imgdir.$img);
		if($type == 'png')	
		    $src_img = ImageCreateFromPNG($imgdir.$img);
		
		#-+ Get image width and height
	    $org_h = imagesy($src_img);
	    $org_w = imagesx($src_img);
	
		#-+ Calculate thumbnail height
	    $tn_h = floor($tn_w * $org_h / $org_w);
		
		#-+ Calculate thumbnail height
	    $bigtn_h = floor($bigtn_w * $org_h / $org_w);
	
		#-+ Initialize destination image
	    $dst_img = imagecreatetruecolor($tn_w,$tn_h);
		
		#-+ Initialize destination image
	    $bigdst_img = imagecreatetruecolor($bigtn_w,$bigtn_h);
	
		#-+ Do it!
	    ImageCopyResized($dst_img, $src_img, 0, 0, 0, 0, $tn_w, $tn_h, $org_w, $org_h); 
		
		#-+ Do it!
	    ImageCopyResized($bigdst_img, $src_img, 0, 0, 0, 0, $bigtn_w, $bigtn_h, $org_w, $org_h); 
	
		#-+ Save it!
		if($type == 'jpg'){	
		    ImageJPEG($dst_img, $tndir.$img,100); 
			ImageJPEG($bigdst_img, $bigtndir.$img,100); 
		}
		if($type == 'png'){	
		    ImagePNG($dst_img, $tndir.$img,9); 
			ImagePNG($bigdst_img, $bigtndir.$img,9); 
		}
		#-+ Save it!
		
	    
	
	}


}