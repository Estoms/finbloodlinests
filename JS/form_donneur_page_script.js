// Sélectionnez l'élément de date de naissance
var naissDonneurInput = document.getElementById("naissDonneur");

// Ajoutez un écouteur d'événements pour surveiller les changements de date
naissDonneurInput.addEventListener("change", function() {
    // Obtenez la date sélectionnée
    var selectedDate = new Date(naissDonneurInput.value);

    // Obtenez la date courante
    var currentDate = new Date();

    // Calculez la différence d'âge en millisecondes (18 ans = 18 * 365 * 24 * 60 * 60 * 1000)
    var minAgeDate = new Date(currentDate.getFullYear() - 18, currentDate.getMonth(), currentDate.getDate());

    // Vérifiez si la date sélectionnée est supérieure à la date courante
    if (selectedDate > currentDate) {
        alert("La date de naissance ne peut pas être dans le futur.");
        naissDonneurInput.value = ""; // Réinitialiser la date
    }

    // Vérifiez si la date sélectionnée est inférieure à 18 ans par rapport à la date courante
    if (selectedDate > minAgeDate) {
        alert("Vous devez avoir au moins 18 ans pour vous inscrire.");
        naissDonneurInput.value = ""; // Réinitialiser la date
    }
});
