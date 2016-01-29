<?php
require("settings.php");

	$security_code = $spamkiller;
	$_SESSION['spamkiller'] = $spamkiller;

    $width = 65;
    $height = 20; 

    $image = ImageCreate($width, $height);  

    $white = ImageColorAllocate($image, 255, 255, 255);
    $black = ImageColorAllocate($image, 0, 0, 0);
    $grey = ImageColorAllocate($image, 204, 204, 204);

    $color = ImageColorAllocate($image, 0, 156, 654);

    ImageFill($image, 0, 0, $grey); 

	$line_color = imagecolorallocate($image, 64,64,64); 
	for($i=0;$i<10;$i++) {
    imageline($image,0,rand()%50,200,rand()%50,$line_color);
	}

    imagefttext($image, 15, 2, 2, 18, $color, "font.ttf", "ahdasewqe");
    
	$pixel_color = imagecolorallocate($image, 04,04,54);
	for($i=0;$i<1000;$i++) {
    imagesetpixel($image,rand()%200,rand()%50,$pixel_color);
	}  

    imagefttext($image, 15, 2, 2, 18, $black, "font.ttf", $security_code);



    header("Content-Type: image/jpeg"); 

    ImageJpeg($image);

    ImageDestroy($image);

?>