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
$allowedFormats = array("jpg", "png", "jpeg", "gif", "pdf", "mp4", "mp3", "avi", "mov", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "odt", "ods", "odp");

if (!in_array($imageFileType, $allowedFormats)) {
    // Unsupported file type
    echo "Sorry, only JPG, JPEG, PNG, GIF, PDF, MP4, MP3, AVI, MOV, DOC, DOCX, XLS, XLSX, PPT, ODT, ODS, and ODP files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        echo "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.";

        // Get the selected deletion time from the form
        $deleteAfterSeconds = $_POST['deleteTime'];

        // Calculate expiration time
        $fileExpirationTime = time() + $deleteAfterSeconds;

        // Save expiration time in a file
        $expirationFile = $targetDir . basename($targetFile) . '.expiration';
        file_put_contents($expirationFile, $fileExpirationTime);

        $fileUrl = "http://" . $_SERVER['SERVER_ADDR'] . ":8088" . dirname($_SERVER['PHP_SELF']) . "/$targetDir" . basename($_FILES["fileToUpload"]["name"]);
        echo "<br><br>Here is your file URL: <a href='$fileUrl'>$fileUrl</a>";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Check for expiration and delete expired files
$files = glob($targetDir . '*.expiration');
$current_time = time();
foreach ($files as $file) {
    $expiration_time = (int)file_get_contents($file);
    $file_to_delete = str_replace('.expiration', '', $file);
    if ($current_time >= $expiration_time && file_exists($file_to_delete)) {
        unlink($file_to_delete);
        unlink($file);
        echo "<br>Expired file deleted: $file_to_delete";
    }
}
?>
