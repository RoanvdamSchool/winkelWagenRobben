<?php
include_once('openDB.php');

echo 'tabel naam is '.$tbl;

echo "<br><a href='winkelwagen.php'>WinkelWagen</a>";

echo '<div>
    <a href="new.php">Nieuw</a>
</div><br>';

echo "<table style='border: solid 2px blueviolet;'>";

echo '<tr>';
foreach ($table_fields as $row) {
    echo '<th>' . $row . '</th>';
    $laatste_kolom = $row;
}
echo '<th>action</th></tr>';


try {
    $stmt = $conn->prepare("SELECT * FROM $tbl");
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach (new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k => $v) {
        echo $v;
        if ($k == 'id') {
            $id = strip_tags($v);
            $l = 'delete.php?id=' . $id;
            $e = 'edit.php?id=' . $id;
        }
        if ($k == $laatste_kolom) {
            echo "<td><a href='$l'>X</a> <a href='$e'>E</a> <a href='winkelwagen.php?plus=${id}'>A</a></td>";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
echo "</table>";
?>