<!DOCTYPE html>
<html>
<body>

<form action="" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*">
    <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>



<?php

include('funct.php');
include("packages/vendor/autoload.php");
\Tinify\setKey("B2JCcCK0FqVfDrPyrjX5QW1jYqF7n4vl");

if(isset($_POST['submit']))
{
$ini_target_dir = "uploads/";
$target_file = basename($_FILES["fileToUpload"]["name"]);
// $file_name = split("\.",$target_file);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$target_file = rand(19999,58888).'.'. $imageFileType;
// var_dump($target_file);
// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $ini_target_dir.$target_file)) 
    {
        // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        $src_image = $ini_target_dir.$target_file;
        $dest_image = 'uploads/resized/'.$target_file;
        $ret = resizeImage($src_image, $dest_image, 1900, 755);
        // echo '\n Image Resized';
        if($ret == true)
        {
            $source_dir = 'uploads/resized/';
            $source = \Tinify\fromFile($source_dir.$target_file);
            $source->toFile("uploads/compressed/".$target_file);  
            echo "Success";
        }
    } 
    else 
    {
        echo "Sorry, there was an error uploading your file.";
    }
}
}
?>