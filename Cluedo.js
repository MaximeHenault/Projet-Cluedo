// Sélection des boutons et des fenêtres modales
const buttons = document.querySelectorAll('.clickable-box');
const modals = document.querySelectorAll('.modal');
const closeButtons = document.querySelectorAll('.close-btn');
const divAccueil = document.getElementById('Accueil');
const divPersonnage = document.getElementById('Personnage');

// Ouvrir la modale associée à chaque bouton
buttons.forEach((button, index) => {
    button.addEventListener('click', () => {
        // Gestion des modales associées
        const modal = document.getElementById(`modal${index + 1}`);
        if (modal) {
            modal.style.display = 'flex';
        }
    });
});

// Ajouter un événement au bouton "Lancer la partie"
const startGameButton = document.getElementById('startGameButton');
startGameButton.addEventListener('click', () => {
    divAccueil.style.display = "none";
    divPersonnage.style.display = "block";
});

// Fermer la modale associée au bouton "Fermer"
closeButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
        const modalId = btn.getAttribute('data-close');
        document.getElementById(modalId).style.display = 'none';
    });
});

// Fermer la modale en cliquant en dehors de son contenu
window.addEventListener('click', (e) => {
    modals.forEach((modal) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
