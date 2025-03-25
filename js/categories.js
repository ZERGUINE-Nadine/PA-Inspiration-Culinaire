function onCategorieClick(categorieId, nom_categorie) {
    window.location.replace('recettes.php?categorie=' + categorieId + '&nom=' + nom_categorie);
}

function surpriseMe() {
    // Générer un nombre aléatoire entre 0 et le nombre total de catégories - 1
    var randomCategoryIndex = Math.floor(Math.random() * categories.length);
    
    if (categories[randomCategoryIndex].recettes.length > 0) {
        // Générer un nombre aléatoire entre 0 et le nombre total de recettes dans la catégorie sélectionnée - 1
        var randomRecipeIndex = Math.floor(Math.random() * categories[randomCategoryIndex].recettes.length);
        
        // Récupérer l'ID de la recette aléatoire(toli 3la hada !!)
        var randomRecipeId = categories[randomCategoryIndex].recettes[randomRecipeIndex].id;
        
        // Rediriger vers la page details_recette.php avec l'ID de la recette aléatoire
        window.location.href = 'details_recette.php?recette=' + randomRecipeId;
    } else {
        console.log('Aucune recette disponible dans cette catégorie.');
        alert('Aucune recette disponible dans cette catégorie.');
    }
}
