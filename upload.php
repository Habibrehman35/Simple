<?php
$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($targetFile)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}


// Allowed file formats
$allowedFormats = array("jpg", "png", "jpeg", "gif", "pdf", "mp4","mp3", "avi", "mov", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "odt", "ods", "odp");

if (!in_array($imageFileType, $allowedFormats)) {
    // Unsupported file type
    echo "Sorry, only JPG, JPEG, PNG, GIF, PDF, MP4, AVI, MOV, DOC, DOCX, XLS, XLSX, PPT, ODT, ODS, and ODP files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        $fileUrl = "http://" . $_SERVER['SERVER_ADDR'] .":8088". dirname($_SERVER['PHP_SELF']) . "/$targetDir" . basename($_FILES["fileToUpload"]["name"]);
        echo "<br><br>Here is your file URL: <a href='$fileUrl'>$fileUrl</a>";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
