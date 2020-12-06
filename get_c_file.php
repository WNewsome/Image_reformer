<?php
$name = htmlspecialchars($_GET["name"]);
$filename = htmlspecialchars($_GET["file"]);
$BPP = 8;
//Attempt to open 
$img = @imagecreatefrompng($filename);
//imagetruecolortopalette($img, false, 255);
$width = imagesx($img);
$height = imagesy($img);
$palette = array();
$found = 0;
$new_image = array_fill(0, $width + fmod($width, 2) , array_fill(0, $height, 0));
$i = -1;
?>
#include <ti/grlib/grlib.h>
static const unsigned char pixel_<?php echo $name.$BPP ?>BPP_UNCOMP[] = 
{
<?php
// Calculate palette of colors
for($y = 0; $y < $height; ++$y ){
  for($x = 0; $x < $width; ++$x ){
    $thisColor = imagecolorat( $img, $x, $y);
    $rgb = imagecolorsforindex($img, $thisColor);
    $color = sprintf('%02X%02X%02X', (round(round(($rgb['red'] / 0x19)) * 0x19)), round(round(($rgb['green'] / 0x19)) * 0x19), round(round(($rgb['blue'] / 0x19)) * 0x19));
    $dec_color = hexdec($color);
    if(array_search($dec_color, $palette) === false){
      $palette[$found] = $dec_color;
      $found++;
    };
    // Pixels
    $new_image[$x][$y] = array_search($dec_color, $palette);
    echo "0x".dechex($new_image[$x][$y]).", ";
  }
  echo "
  ";
}
?>
};
static const unsigned long palette_<?php echo $name.$BPP ?>BPP_UNCOMP[]=
{
<?php
// Print palette
foreach($palette as $color) {
  echo "0x".dechex($color) . ', ';
}
echo "
};

const Graphics_Image ".$name.$BPP."BPP_UNCOMP= // @suppress(\"Invalid arguments\")
{
  IMAGE_FMT_".$BPP."BPP_UNCOMP,
  ".$width.",
  ".$height.",
  ".count($palette).",
  palette_".$name.$BPP."BPP_UNCOMP,
  pixel_".$name.$BPP."BPP_UNCOMP,
};";
?>