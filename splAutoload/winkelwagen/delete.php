<?php

   echo '<h1>Verwijderen van id '.$_GET['id'].'<h1>';

   if ($_GET['id']) {
       include_once ('openDB.php');
       $id = $_GET['id'];
       try {
           // set the PDO error mode to exception
           $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

           // sql to delete a record
           $sql = "DELETE FROM $tbl WHERE id=$id";

           // use exec() because no results are returned
           $conn->exec($sql);
           echo "Record deleted successfully<br>";
           echo "<a href='index.php'>terug naar index</a>";
       } catch (PDOException $e) {
           echo $sql . "<br>" . $e->getMessage();
        }

       $conn = null;
   }