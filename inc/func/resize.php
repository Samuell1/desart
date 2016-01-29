<?php
function onlineresize ($subor,$NovaSirka,$NovaVyska) {

$urli = getimagesize($subor);

			if($urli["mime"] == "image/jpg"){
$image = imagecreatefromjpg($subor);
			}elseif($urli["mime"] == "image/png"){
$image = imagecreatefrompng($subor);
			}elseif($urli["mime"] == "image/gif"){
$image = imagecreatefromgif($subor);
			}elseif($urli["mime"] == "image/jpeg"){
$image = imagecreatefromjpeg($subor);
			}


$sirka = imagesx($image);
$vyska = imagesy($image);

if ($NovaVyska==0){
$pomer=$sirka/$vyska;
$NovaVyska=$NovaSirka/$pomer;
}else {
$NovaSirka=$NovaSirka;
$NovaVyska=$NovaVyska;
}

$image_zmenseny = imagecreatetruecolor($NovaSirka, $NovaVyska);
imagecopyresampled($image_zmenseny, $image, 0, 0, 0, 0, $NovaSirka, $NovaVyska, $sirka, $vyska);

// Zobrazíme zmenšeninu
			if($urli["mime"] == "image/jpg"){
header('Content-type: image/jpg');
imagejpg($image_zmenseny);
			}elseif($urli["mime"] == "image/png"){
header('Content-type: image/png');
imagepng($image_zmenseny);
			}elseif($urli["mime"] == "image/gif"){
header('Content-type: image/gif');
imagegif($image_zmenseny);
			}elseif($urli["mime"] == "image/jpeg"){
header('Content-type: image/jpeg');
imagejpeg($image_zmenseny);
			}
}
//zavolame funkciu a zobrazime obrazok
onlineresize($_GET['i'],$_GET['w'],$_GET['h']);
?>