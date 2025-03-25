<?php
session_start();
include './php/recette_du_mois.php'; 
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspiration Culinaire</title>
    <link rel="stylesheet" href="./assets/styles/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="./assets/styles/hedaer.css">
    <link rel="stylesheet" href="./assets/styles/footer.css">
    <link rel="stylesheet" href="./assets/styles/sombre.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/css/autoComplete.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@10.2.7/dist/autoComplete.min.js"></script>
</head>

<body>
    <?php require("./pages/includes/header.php") ?>
    <div class="font">
        <div class="container-fluid">
            <div class="row top">
                <div class="col-4" style="background-color: #ffb800;">
                    <!-- Espace pour la meilleure recette -->
                    <?php if ($best_recipe): ?>
                        <div class="rectangle yellow">
    <a href="pages/recettes/details_recette.php?recette=<?php echo htmlspecialchars($best_recipe['id_recette']); ?>" class="recipe-link">
        <h2 class="dish"><?php echo htmlspecialchars($best_recipe['nom_recette']); ?></h2>
        <img src="<?php echo htmlspecialchars($best_recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($best_recipe['nom_recette']); ?>">
        <p class="rating">Note Moyenne : <?php echo number_format($best_recipe['avg_rating'], 2); ?>/5</p>
    </a>
</div>

                    <?php else: ?>
                        <p>Aucune recette avec des évaluations trouvée.</p>
                    <?php endif; ?>
                </div>
                <div class="col-8 background-image">
                    <div class="welcom">
                        <h1 style="text-decoration: underline; position:relative; top: 60px; color: whitesmoke; text-align:center">Bienvenue !</h1>
                        <h2 style="position:relative; top: 70px; color: whitesmoke; text-align:center">Découvrez votre prochaine Inspiration Culinaire</h2>
                        <input style="margin-top: 120px" type="text" value="" name="s1" id="s1" placeholder="Rechercher">
                    </div>
                </div>
            </div>
        </div>

        <div class="dishes-slider">
            <div class="slider-wrapper">
                <div class="slider-track">
                    <div class="col-md-2 dish-category">
                        <img src="https://www.leboulangerparisien.com/wp-content/uploads/2021/03/cuisine-maghrebine-1280x720.jpg" alt="Category 1">
                        <div class="dish-category-title">Magrébine</div>
                    </div>
                    <div class="col-md-2 dish-category">
                        <img src="https://assets.afcdn.com/story/acc9_641549/acc604652126_w450h311c1.jpg" alt="Category 2">
                        <div class="dish-category-title">Française</div>
                    </div>
                    <div class="col-md-2 dish-category">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT4XVOpQIv3V0b3VqCbdZQsHG3vW6Vp3aslYA&s" alt="Category 3">
                        <div class="dish-category-title">Italienne</div>
                    </div>
                    <div class="col-md-2 dish-category">
                        <img src="https://image.over-blog.com/yd7xQB6YiPDxCyoOuSoZxgIIXTI=/630x400/smart/filters:no_upscale()/image%2F0651923%2F20230220%2Fob_4eeb55_wok-poulet-nouilles.jpg" alt="Category 4">
                        <div class="dish-category-title">Asiatique</div>
                    </div>
                    <div class="col-md-2 dish-category">
                        <img src="https://assets.tmecosys.com/image/upload/t_web767x639/img/recipe/ras/Assets/EB6989F1-4B20-42E9-B93B-72DD925E12D0/Derivates/F714B7A2-3A29-4FB8-8657-80EE66F4DE73.jpg" alt="Category 5">
                        <div class="dish-category-title">Libanaise</div>
                    </div>
                    <div class="col-md-2 dish-category">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTBS_M35MnSIUVmkh_V-slbarannMMoKqWbZEkTGO7n1KTNQZXdVCKtdICV5gSvnjWtyqk&usqp=CAU" alt="Category 6">
                        <div class="dish-category-title">Turc</div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php require("./pages/includes/footer.php") ?>
        <script src="./js/index.js"></script>
        <script src="./js/sombre.js"></script>

    </div>
    <script>
    const autoCompleteJS = new autoComplete({
        selector: "#s1",
        data: {
            src: async (query) => {
                try {
                    const source = await fetch(`./php/search.php?query=${query}`);
                    const data = await source.json();
                    return data.map(item => [
                        item[0],
                        item[1]
                    ]);
                } catch (error) {
                    return error;
                }
            },
            key: [1],
            cache: false
        },
        resultsList: {
            element: (list, data) => {
                if (!data.results.length) {
                    const message = document.createElement("div");
                    message.classList.add("no_result");
                    message.textContent = "No Results";
                    list.appendChild(message);
                }
            },
            noResults: true,
            maxResults: 5,
            tabSelect: true
        },
        resultItem: {
            element: (item, data) => {
                const title = document.createElement("span");
                title.classList.add("result_item_title");
                const match =  data.match
                item.textContent = match.replace('<mark>', '').replace('</mark>', '').split(',')[1];
                item.appendChild(title);
            },
            highlight: true
        },
        events: {
            input: {
                selection: (event) => {
                    const selection = event.detail.selection.value;
                    autoCompleteJS.input.value = selection;
                    window.location.replace('./pages/recettes/details_recette.php?recette=' + selection[0] )
                }
            }
        }
    });
    </script>
</body>

</html>
