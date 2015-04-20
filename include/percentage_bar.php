<?php
/////////////////////////////////////////////////////////////////////////////////////////
// RFSCMS http://www.sethcoder.com/
/////////////////////////////////////////////////////////////////////////////////////////
header("Content-type: image/png");
$im = @imagecreate(300, 16) or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 0, 0, 0);    // yellow
$blue = imagecolorallocate($im, 0, 0, 255);                  // blue
$black = imagecolorallocate($im,0,0,0);
$white = imagecolorallocate($im,255,255,255);
//imagestring($im, 3, 5, 5,  "My Text String", $blue);
//$red = imagecolorallocate($im, 255, 0, 0);                  // red
$green = imagecolorallocate($im, 0, 255, 0);
//imagefilledrectangle ($im,   5,  300, 195, 340, $blue);
imagefilledrectangle ($im,   0,  0, ($_GET['per'] *3),16, $green);
//imageline ($im,   205,  205, 395, 205, $red);
//       imageline ($im,   205,  205, 395, 395, $blue);
//        imagerotate($im, 90,$yellow);
imagestring($im, 3, 16, 1,  $_GET['per']."%", $black);
imagestring($im, 3, 17, 2,  $_GET['per']."%", $black);
imagestring($im, 3, 15, 0,  $_GET['per']."%", $white);
imagepng($im);
imagedestroy($im);
/*
?>
<?php
header("Content-type: image/png");
$im = @imagecreate(600, 600) or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 255, 255, 0);    // yellow
$blue = imagecolorallocate($im, 0, 0, 255);                  // blue
imagestring($im, 3, 5, 5,  "My Text String", $blue);
        $red = imagecolorallocate($im, 255, 0, 0);                  // red
        $blue = imagecolorallocate($im, 0, 0, 255);                 // blue
        imagearc($im, 20, 50, 40, 60, 0, 90, $red);
        imagearc($im, 70, 50, 40, 60, 0, 180, $red);
        imagearc($im, 120, 50, 40, 60, 0, 270, $red);
        imagearc($im, 170, 50, 40, 60, 0, 360, $red);
        imagefilledarc($im, 20, 150, 40, 60, 0, 90, $blue, IMG_ARC_PIE);
        imagefilledarc($im, 70, 150, 40, 60, 0, 180, $blue, IMG_ARC_PIE);
        imagefilledarc($im, 120, 150, 40, 60, 0, 270, $blue, IMG_ARC_PIE);
        imagefilledarc($im, 170, 150, 40, 60, 0, 360, $blue, IMG_ARC_PIE);
        imageellipse($im, 220, 50, 40, 60, $red);
        imagefilledellipse($im, 250, 150, 60, 40, $blue);
        imagerectangle ($im,   5,  210, 195, 250, $red);
        imagefilledrectangle ($im,   5,  300, 195, 340, $blue);
        imageline ($im,   205,  205, 395, 205, $red);
        imageline ($im,   205,  205, 395, 395, $blue);
        imagerotate($im, 90,$yellow);
imagepng($im);
imagedestroy($im);

/*
gd_info — Retrieve information about the currently installed GD library
getimagesize — Get the size of an image
getimagesizefromstring — Get the size of an image from a string
image_type_to_extension — Get file extension for image type
image_type_to_mime_type — Get Mime-Type for image-type returned by getimagesize, exif_read_data, exif_thumbnail, exif_imagetype
image2wbmp — Output image to browser or file
imagealphablending — Set the blending mode for an image
imageantialias — Should antialias functions be used or not
imagearc — Draws an arc
imagechar — Draw a character horizontally
imagecharup — Draw a character vertically
imagecolorallocate — Allocate a color for an image
imagecolorallocatealpha — Allocate a color for an image
imagecolorat — Get the index of the color of a pixel
imagecolorclosest — Get the index of the closest color to the specified color
imagecolorclosestalpha — Get the index of the closest color to the specified color + alpha
imagecolorclosesthwb — Get the index of the color which has the hue, white and blackness
imagecolordeallocate — De-allocate a color for an image
imagecolorexact — Get the index of the specified color
imagecolorexactalpha — Get the index of the specified color + alpha
imagecolormatch — Makes the colors of the palette version of an image more closely match the true color version
imagecolorresolve — Get the index of the specified color or its closest possible alternative
imagecolorresolvealpha — Get the index of the specified color + alpha or its closest possible alternative
imagecolorset — Set the color for the specified palette index
imagecolorsforindex — Get the colors for an index
imagecolorstotal — Find out the number of colors in an image's palette
imagecolortransparent — Define a color as transparent
imageconvolution — Apply a 3x3 convolution matrix, using coefficient and offset
imagecopy — Copy part of an image
imagecopymerge — Copy and merge part of an image
imagecopymergegray — Copy and merge part of an image with gray scale
imagecopyresampled — Copy and resize part of an image with resampling
imagecopyresized — Copy and resize part of an image
imagecreate — Create a new palette based image
imagecreatefromgd2 — Create a new image from GD2 file or URL
imagecreatefromgd2part — Create a new image from a given part of GD2 file or URL
imagecreatefromgd — Create a new image from GD file or URL
imagecreatefromgif — Create a new image from file or URL
imagecreatefromjpeg — Create a new image from file or URL
imagecreatefrompng — Create a new image from file or URL
imagecreatefromstring — Create a new image from the image stream in the string
imagecreatefromwbmp — Create a new image from file or URL
imagecreatefromxbm — Create a new image from file or URL
imagecreatefromxpm — Create a new image from file or URL
imagecreatetruecolor — Create a new true color image
imagedashedline — Draw a dashed line
imagedestroy — Destroy an image
imageellipse — Draw an ellipse
imagefill — Flood fill
imagefilledarc — Draw a partial arc and fill it
imagefilledellipse — Draw a filled ellipse
imagefilledpolygon — Draw a filled polygon
imagefilledrectangle — Draw a filled rectangle
imagefilltoborder — Flood fill to specific color
imagefilter — Applies a filter to an image
imagefontheight — Get font height
imagefontwidth — Get font width
imageftbbox — Give the bounding box of a text using fonts via freetype2
imagefttext — Write text to the image using fonts using FreeType 2
imagegammacorrect — Apply a gamma correction to a GD image
imagegd2 — Output GD2 image to browser or file
imagegd — Output GD image to browser or file
imagegif — Output image to browser or file
imagegrabscreen — Captures the whole screen
imagegrabwindow — Captures a window
imageinterlace — Enable or disable interlace
imageistruecolor — Finds whether an image is a truecolor image
imagejpeg — Output image to browser or file
imagelayereffect — Set the alpha blending flag to use the bundled libgd layering effects
imageline — Draw a line
imageloadfont — Load a new font
imagepalettecopy — Copy the palette from one image to another
imagepng — Output a PNG image to either the browser or a file
imagepolygon — Draws a polygon
imagepsbbox — Give the bounding box of a text rectangle using PostScript Type1 fonts
imagepsencodefont — Change the character encoding vector of a font
imagepsextendfont — Extend or condense a font
imagepsfreefont — Free memory used by a PostScript Type 1 font
imagepsloadfont — Load a PostScript Type 1 font from file
imagepsslantfont — Slant a font
imagepstext — Draws a text over an image using PostScript Type1 fonts
imagerectangle — Draw a rectangle
imagerotate — Rotate an image with a given angle
imagesavealpha — Set the flag to save full alpha channel information (as opposed to single-color transparency) when saving PNG images
imagesetbrush — Set the brush image for line drawing
imagesetpixel — Set a single pixel
imagesetstyle — Set the style for line drawing
imagesetthickness — Set the thickness for line drawing
imagesettile — Set the tile image for filling
imagestring — Draw a string horizontally
imagestringup — Draw a string vertically
imagesx — Get image width
imagesy — Get image height
imagetruecolortopalette — Convert a true color image to a palette image
imagettfbbox — Give the bounding box of a text using TrueType fonts
imagettftext — Write text to the image using TrueType fonts
imagetypes — Return the image types supported by this PHP build
imagewbmp — Output image to browser or file
imagexbm — Output XBM image to browser or file
iptcembed — Embeds binary IPTC data into a JPEG image
iptcparse — Parse a binary IPTC block into single tags.
jpeg2wbmp — Convert JPEG image file to WBMP image file
png2wbmp — Convert PNG image file to WBMP image file
*/
