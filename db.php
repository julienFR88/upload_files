<?php
          // connexion à la db en PDO
          try {
            $db = new PDO('mysql:host=localhost;dbname=upload_file', 'root', '');
        } catch (PDOException $e) {
            die('error de connexion' . $e->getMessage());
        }
?>