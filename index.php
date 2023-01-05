<?php
if (isset($_FILES['file'])) {
    $tmpName = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $size = $_FILES['file']['size'];
    $error = $_FILES['file']['error'];

    //je vais prendre l'extension de mon fichier
    $tabExtension = explode('.', $name);

    //on fait en sorte d'avoir obligatoirement l'extension de mon fichier en minuscule
    $extension = strtolower(end($tabExtension));

    //on va se creer l'exension auquels nous abons le droit aux telechargement
    $extensionsName = ['jpg', 'jpeg', 'png', 'gif'];

    // On va définir la taille max auprés duquel on a le droit de telecharger notre image
    $maxSize = 400000000;

    // on va aller rechercher dans notre tableaux nos extensions + la taille + nos eventuelles erreurs
    if (in_array($extension, $extensionsName) && $size <= $maxSize && $error == 0) {

        // on va s'occuper de générer un n° unique & aléatoire avec lettres & chiffres
        $uniqueName = uniqid('', true);

        // on va concaténer notre uniqid avec l'xtension
        $file = $uniqueName . "" . "" . $extension;

        move_uploaded_file($tmpName, './upload/' . $file);

        // connexion à la db en PDO
        try {
            $db = new PDO('mysql:host=localhost;dbname=upload_file', 'root', '');
        } catch (PDOException $e) {
            die('error de connexion' . $e->getMessage());
        }

        $req = $db->prepare('INSERT INTO file(name) VALUES (?)');
        $req->execute($file);
        echo 'Image enregistrée';

    } else {
      echo 'Une erreur est apparu pendant le chargement de votre image'
    }

}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Files</title>
</head>
<body>

  <h2>Ajouter une image</h2>

  <form action="index.php" method="post" enctype="multipart/form-data">
      <label for="file"></label>
      <input type="file" name="file">
      <button type="submit">Enregistrer</button>
  </form>

  <h2>Mes Images</h2>
  <?php
    $req = $db->query("select name from  file");
    while ($data = $req->fetch()) {
      echo "<img src='./upload/".$data['name']."' width='300px' ><br>";
    }
  ?>
</body>
</html>
