<?php
$targetDir = "uploads/";
$originalFileName = $_FILES["fileToUpload"]["name"];
$extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
$uniqueFileName = uniqid() . '_' . mt_rand(1000, 9999) . '.' . $extension; // Generating a unique file name
$targetFile = $targetDir . $uniqueFileName;
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($targetFile)) {
    $uploadOk = 0;
    $errorMessage = "Sorry, a file with the same name already exists.";
}

// Allowed file formats
$allowedFormats = array("jpg", "png", "jpeg", "gif", "pdf", "mp4", "mp3", "avi", "mov", "doc", "docx", "xls", "xlsx", "ppt", "pptx", "odt", "ods", "odp");

if (!in_array($imageFileType, $allowedFormats)) {
    // Unsupported file type
    $uploadOk = 0;
    $errorMessage = "Sorry, only JPG, JPEG, PNG, GIF, PDF, MP4, MP3, AVI, MOV, DOC, DOCX, XLS, XLSX, PPT, ODT, ODS, and ODP files are allowed.";
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<p class='error'>$errorMessage</p>";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
        $successMessage = "The file <strong>" . htmlspecialchars($originalFileName). "</strong> has been uploaded with a unique name: <strong>" . $uniqueFileName . "</strong>";
        $fileUrl = "https://" . $_SERVER['SERVER_ADDR'] .":8088". dirname($_SERVER['PHP_SELF']) . "/$targetDir" . $uniqueFileName;
        echo "<p class='success'>$successMessage</p>";
        echo "<div class='file-url-container'>";
        echo "<p>Here is your file URL: <a href='$fileUrl'>$fileUrl</a></p>";
        echo "<button onclick='copyFileUrl()' class='copy-button'>Copy URL</button>";
        echo "</div>";
        
        
    } else {
        echo "<p class='error'>Sorry, there was an error uploading your file.</p>";
    }
}
?>
<!-- CSS Styles -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 500px;
        margin: 50px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .file-url-container {
    margin-top: 20px;
    padding: 10px;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 5px;
}
.copy-button {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 8px 16px;
    cursor: pointer;
    border-radius: 5px;
    margin-top: 5px;
}

.copy-button:hover {
    background-color: #0056b3;
}

    h2 {
        margin-top: 0;
        color: #333;
    }

    form {
        margin-top: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #666;
    }

    input[type="file"] {
        margin-bottom: 15px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        width: 100%;
    }

    input[type="submit"] {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
    }

    input[type="submit"]:hover {
        background-color: #0056b3;
    }

    .error {
        color: #d9534f;
        background-color: #f2dede;
        border: 1px solid #ebccd1;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    .success {
        color: #5cb85c;
        background-color: #dff0d8;
        border: 1px solid #d0e9c6;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
</style>
<script>
    function copyFileUrl() {
        /* Get the text field */
        var fileUrl = document.createElement('textarea');
        fileUrl.value = '<?php echo $fileUrl; ?>';
        document.body.appendChild(fileUrl);
        fileUrl.select();
        fileUrl.setSelectionRange(0, 99999); /* For mobile devices */

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        alert("File URL copied to clipboard: " + fileUrl.value);

        /* Remove the temporary textarea */
        document.body.removeChild(fileUrl);
    }
</script>
