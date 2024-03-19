<?php
$targetDir = "uploads/";
$originalFileName = $_FILES["fileToUpload"]["name"];
$extension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
$uniqueFileName = uniqid() . '_' . time() . '.' . $extension;
$targetFile = $targetDir . $uniqueFileName;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($targetFile)) {
    echo "Sorry, a file with a similar name already exists.";
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
        echo "The file ". htmlspecialchars(basename($originalFileName)). " has been uploaded.";

        // Get the selected deletion time from the form
        $deleteAfterSeconds = $_POST['deleteTime'];

        // Calculate expiration time
        $fileExpirationTime = time() + $deleteAfterSeconds;

        // Save expiration time in a file
        $expirationFile = $targetDir . $uniqueFileName . '.expiration';
        file_put_contents($expirationFile, $fileExpirationTime);

        $fileUrl = "http://" . $_SERVER['SERVER_ADDR'] . ":8088" . dirname($_SERVER['PHP_SELF']) . "/$targetDir" . $uniqueFileName;
        echo "<br><br>Here is your file URL: <a href='$fileUrl'>$fileUrl</a>";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

// Check for expiration and delete expired files
$files = glob($targetDir . '*.expiration');
$current_time = time();
foreach ($files as $expirationFile) {
    $expiration_time = (int)file_get_contents($expirationFile);
    $file_to_delete = str_replace('.expiration', '', $expirationFile);
    if ($current_time >= $expiration_time && file_exists($file_to_delete)) {
        unlink($file_to_delete);
        unlink($expirationFile);
        echo "<br>Expired file deleted: $file_to_delete";
    }
}
?>
