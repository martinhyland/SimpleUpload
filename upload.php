<?php
require('config.php');

require('lib/SimpleUpload.php');

if (!isset($_POST['submit'])){
    die("No direct access");
}

if ($use_uniqid) {
  $target_file = $target_dir .uniqid() . "." .strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
}
else {
  $target_file = $target_dir .strtolower($_FILES["fileToUpload"]["name"]);
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
}

if (Authenticate($_POST["upload-secret"], $upload_password)) {
    if (ActualImage($_FILES["fileToUpload"]["tmp_name"])) {
        if (AlreadyExsists($target_file)) {
            if (Acceptablefiletype($imageFileType)) {
                if (upload($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    header("Location: index.php?upload=true&protocol=". $protocol . "&uploadurl=" . $target_file);
                    echo($target_file);
                }
                else{
                    die("Unknown Error");
                }
            }
            else{
                die("File already exists");
            }
        }
        else{
            die("That is not a real image");
        }
    }
    else{
        die("Authentication failure!");
    }
}

?>
