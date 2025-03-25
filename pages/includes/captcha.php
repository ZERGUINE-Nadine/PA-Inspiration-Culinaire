<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>inspiration Culinaire</title>
  <link rel="stylesheet" href="../../assets/styles/captcha.css">
  <link rel="stylesheet" href="../../assets/styles/sombre.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body>
  <div class="wrapper">
    <header>Just pour comfirmez que vous etes pas un robot ! </header>
    <div class="captcha-area">
      <div class="captcha-img">
        <img src="../../assets/images/captcha-bg.png" alt="Captch Background">
        <span class="captcha"></span>
      </div>
      <button class="reload-btn"><i class="fas fa-redo-alt"></i></button>
    </div>
    <form action="#" class="input-area">
      <input type="text" placeholder="Entrez le code " maxlength="6" spellcheck="false" required>
      <button class="check-btn">Check</button>

    </form>
    <div class="status-text"></div>
  </div>
  <script src="../../js/captcha.js"></script>
  <script src="../../js/sombre.js"></script>

</body>
</html>