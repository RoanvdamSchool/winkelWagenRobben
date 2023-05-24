<?php

// file been called by /delete.php?id={id}   $_GET['id']
echo '<h1>Update van id ' . $_GET['id'] . '<h1>';

include_once('openDB.php');

echo "<p><a href='index.php'>terug naar index</a></p>";

if ($_GET['id']) {
    $id = $_GET['id'];
    try {
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $conn->prepare("SELECT * FROM $tbl WHERE id=$id");
        $stmt->execute();

        // set the resulting array to associative
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);

        foreach ($stmt->fetchAll() as $k => $v) {
            // values fill
            foreach ($table_fields as $row) {
                if ($row <> 'id') {
                    $$row = $v[$row];
                }
            }
            // form
            echo "
                    <form action=\"edit.php\" method='post'>
                    <input type='hidden' name='id' value=$id>
                 ";
            foreach ($table_fields as $row) {
                if ($row <> 'id') {
                    if ($row == 'image') {
                        echo '
                          Select image to upload:
                              <input type="file" name="fileToUpload" id="fileToUpload">
                        ';
                    } else {
                        echo "<label for=\"$row\">$row:</label><br>
                          <input type=\"text\" id=\"$row\" name=\"$row\" value='" . $$row . "'><br>";
                    }

                }
            }
            // submit
            echo "<br><input type=\"submit\" value=\"Wegschrijven\">
                  </form> 
                  <a href='index.php'>Terug naar index</a>
                 ";
        }

    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}

if ($_POST) {

    try {
        foreach ($table_fields as $row) {
            $$row = $_POST[$row];
            if ($row == 'image') {
                $image = $_POST['fileToUpload'];
                    $target_dir = "uploads/";
                    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                    $uploadOk = 1;
                    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

                    // Check if image file is a actual image or fake image
                    if(isset($_POST["fileToUpload"])) {
                        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                        if($check !== false) {
                            echo "File is an image - " . $check["mime"] . ".";
                            $uploadOk = 1;
                        } else {
                            echo "File is not an image.";
                            $uploadOk = 0;
                        }
                    }

                    // Check if file already exists
                    if (file_exists($target_file)) {
                        echo "Sorry, file already exists.";
                        $uploadOk = 0;
                    }

                    // Check file size
                    if ($_FILES["fileToUpload"]["size"] > 500000) {
                        echo "Sorry, your file is too large.";
                        $uploadOk = 0;
                    }

                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                        && $imageFileType != "gif" ) {
                        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                        $uploadOk = 0;
                    }

                    // Check if $uploadOk is set to 0 by an error
                    if ($uploadOk == 0) {
                        echo "Sorry, your file was not uploaded.";
                        // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
                        } else {
                            echo "Sorry, there was an error uploading your file.";
                        }
                    }

            }
        }

        $sql = "UPDATE $tbl SET ";
        foreach ($table_fields as $row) {
            if ($row <> 'id') {
                $sql .= "$row = '" . $$row . "',";
            }
        }
        $sql = substr_replace($sql, "", -1);  // laatste komma weg.
        $sql .= "WHERE id = $id";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // execute the query
        $stmt->execute();

        // echo a message to say the UPDATE succeeded
        echo $stmt->rowCount() . " records UPDATED successfully";
    } catch (PDOException $e) {
        echo $sql . " < br>" . $e->getMessage();
    }

    $conn = null;
}
