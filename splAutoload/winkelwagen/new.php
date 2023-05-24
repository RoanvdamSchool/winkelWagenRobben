<?php
echo '<h1>Nieuwe regel toevoegen</h1>';

include_once('openDB.php');

if ($_POST) {
    echo "<h2>regel toevoegen.</h2>";

    foreach ($table_fields as $row) {
        $$row = $_POST[$row];   // $email = $_POST[$row];
    }

    try {
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO $tbl (";
        foreach ($table_fields as $row) {
            if ($row <> 'id') {
                $sql .= $row . ',';
            }
        }
        $sql = substr_replace($sql, "", -1);  // laatste komma weg.
        $sql .= ") VALUES (";
        foreach ($table_fields as $row) {
            if ($row <> 'id') {
                $sql .= "'" . $$row . "',";
            }
        }
        $sql = substr_replace($sql, "", -1);  // laatste komma weg.
        $sql .= ")";
        // use exec() because no results are returned
        $conn->exec($sql);
        echo "New record created successfully";
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}

// formulier aanmaken
echo "
 <form action=\"new.php\" method='post'>";
foreach ($table_fields as $row) {
    if ($row <> 'id') {
        echo "<label for=\"$row\">$row:</label><br>
            <input type=\"text\" id=\"$row\" name=\"$row\"><br><br>";
    }
}
echo "<input type=\"submit\" value=\"Wegschrijven\"> 
        </form> 
        <a href='index.php'>Terug naar index</a>
        ";
