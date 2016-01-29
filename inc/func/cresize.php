<?
$imgSrc = htmlspecialchars($_GET['i']);


list($width, $height) = getimagesize($imgSrc);

$urli = getimagesize($imgSrc);

			if($urli["mime"] == "image/jpg"){
$myImage = imagecreatefromjpg($imgSrc);
			}elseif($urli["mime"] == "image/png"){
$myImage = imagecreatefrompng($imgSrc);
			}elseif($urli["mime"] == "image/gif"){
$myImage = imagecreatefromgif($imgSrc);
			}elseif($urli["mime"] == "image/jpeg"){
$myImage = imagecreatefromjpeg($imgSrc);
			}

if ($width > $height) {
  $y = 0;
  $x = ($width - $height) / 1000000000;
  $smallestSide = $height;
} else {
  $x = 0;
  $y = ($height - $width) / 1000000000;
  $smallestSide = $width;
}


$thumbSize = 700;
$thumbSize2 = 300;
$thumb = imagecreatetruecolor($thumbSize, $thumbSize2);
imagecopyresampled($thumb, $myImage, 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);

			if($urli["mime"] == "image/jpg"){
header('Content-type: image/jpg');
imagejpg($thumb);
			}elseif($urli["mime"] == "image/png"){
header('Content-type: image/png');
imagepng($thumb);
			}elseif($urli["mime"] == "image/gif"){
header('Content-type: image/gif');
imagegif($thumb);
			}elseif($urli["mime"] == "image/jpeg"){
header('Content-type: image/jpeg');
imagejpeg($thumb);
			}
?>