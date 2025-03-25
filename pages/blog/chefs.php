<?php
session_start();

// Vérification de la session si l'utilisateur n'est pas connecté (redirection page login)
$is_authenticated = false;

if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
  $is_authenticated = true;
  $user_id = $_SESSION['user']['id'];
} else {
  header("Location: ../../pages/login.php");
  exit();
}

// Inclure le fichier de fonction de connexion à la base de données
require('../../php/database/connect_to_datanase.php');

try {
  // Obtenir la connexion à la base de données
  $conn = get_database_connexion();

  if (!$conn) {
    die("Erreur de connexion à la base de données.");
  }

  // Récupérer les chefs
  $chefs = [];
  $sql = "SELECT 
                chefs.id_chefs, 
                chefs.nom, 
                chefs.specialite,
                COALESCE(suivi.user_id, 0) AS is_following
            FROM 
                chefs
            LEFT JOIN 
                suivi 
            ON 
                chefs.id_chefs = suivi.chef_id AND suivi.user_id = :user_id
            ORDER BY 
                chefs.nom";

  $stmt = $conn->prepare($sql);
  $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
  $stmt->execute();

  if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $chefs[] = [
        'id' => $row['id_chefs'], // Use the correct primary key name
        'nom' => $row['nom'],
        'specialite' => $row['specialite'],
        'is_following' => $row['is_following']
      ];
    }
  } else {
    echo "Aucun chef trouvé.";
  }

  $conn = null; // Ferme la connexion à la base de données
} catch (PDOException $e) {
  echo "Erreur PDO : " . $e->getMessage();
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
  <title>Inspiration Culinaire</title>
  <link rel="stylesheet" href="../../assets/styles/chefs.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../../assets/styles/hedaer.css" />
  <link rel="stylesheet" href="../../assets/styles/mini_header.css" />
  <link rel="stylesheet" href="../../assets/styles/footer.Css" />

  <link rel="stylesheet" href="../../assets/styles/sombre.css" />
  <script src="https://kit.fontawesome.com/5732949dd8.js" crossorigin="anonymous"></script>
</head>

<body>
  <?php
  require("../../pages/includes/header.php");
  require("../../pages/includes/mini_header.php");
  ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <br><br />
        <div class="chefs-container">
          <?php if (!empty($chefs)) { ?>
            <?php foreach ($chefs as $chef) { ?>
              <div class="chef" onclick="redirectToChefProfile(<?php echo $chef['id']; ?>)">
                <div class="header">
                  <div class="nom"><?php echo htmlspecialchars($chef['nom']); ?></div>
                  <div>
                    <button onclick="onfollowClick(<?php echo $chef['id']; ?>, '<?php echo $chef['is_following'] ? 'unfollow' : 'follow'; ?>')" class="follow-btn" data-chef-id="<?php echo $chef['id']; ?>">
                      <?php echo $chef['is_following'] ? 'Suivi(e)' : 'Suivre'; ?>
                    </button>
                  </div>
                </div>
                <div class="specialite"><?php echo htmlspecialchars($chef['specialite']); ?></div>
              </div>
            <?php } ?>
          <?php } else { ?>
            <p>Aucun chef trouvé.</p>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>

  <?php require("../../pages/includes/footer.php") ?>
  <script src="../../js/profil.js"></script>
  <script src="../../js/follow.js"></script>
  <script src="../../js/sombre.js"></script>



  
</body>

</html>
