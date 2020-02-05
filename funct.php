<?php

function resizeImage($sourceImage, $targetImage, $maxWidth, $maxHeight, $quality = 80)
{
    $isValid = @getimagesize($sourceImage);

    if (!$isValid)
    {
        return false;
    }

    // Get dimensions and type of source image.
    list($origWidth, $origHeight, $type) = getimagesize($sourceImage);

    if ($maxWidth == 0)
    {
        $maxWidth  = $origWidth;
    }

    if ($maxHeight == 0)
    {
        $maxHeight = $origHeight;
    }

    // Calculate ratio of desired maximum sizes and original sizes.
    $widthRatio = $maxWidth / $origWidth;
    $heightRatio = $maxHeight / $origHeight;

    // Ratio used for calculating new image dimensions.
    $ratio = min($widthRatio, $heightRatio);

    // Calculate new image dimensions.
    $newWidth  = (int)$origWidth  * $ratio;
    $newHeight = (int)$origHeight * $ratio;

    // Create final image with new dimensions.
    $newImage = imagecreatetruecolor($newWidth, $newHeight);

    // Obtain image from given source file.
    switch(strtolower(image_type_to_mime_type($type)))
    {
        case 'image/jpeg':                      
            $image = @imagecreatefromjpeg($sourceImage);            
            if (!$image)
            {
                return false;
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight); 

            if(imagejpeg($newImage,$targetImage,$quality))
            {
                // Free up the memory.
                imagedestroy($image);
                imagedestroy($newImage);
                return true;
            }            
        break;
        
        case 'image/png':
            $image = @imagecreatefrompng($sourceImage);

            if (!$image)
            {
                return false;
            }

            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

            if(imagepng($newImage,$targetImage, floor($quality / 10)))
            {
                // Free up the memory.
                imagedestroy($image);
                imagedestroy($newImage);
                return true;
            }
        break;
                
		default:
			return false;
       }
}
?>