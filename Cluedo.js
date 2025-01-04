//Recuperations des différentes valeurs des boutons et modal (Fenêtre de règle du jeu)
const buttons = document.querySelectorAll('.clickable-box');
const modals = document.querySelectorAll('.modal');
const closeButtons = document.querySelectorAll('.close-btn');
const divAccueil = document.getElementById('Accueil');
const divPersonnage = document.getElementById('Personnage');


//Permet de changer la valeur du display du modal pour l'afficher
buttons.forEach((button, index) => {
    button.addEventListener('click', () => {
        const modal = document.getElementById(`modal${index + 1}`);
        if (modal) {
            modal.style.display = 'flex';
        }
    });
});

//Change la valeur du diplay pour afficher la 2ème partie de la page
const startGameButton = document.getElementById('startGameButton');
startGameButton.addEventListener('click', () => {
    divAccueil.style.display = "none";
    divPersonnage.style.display = "block";
});

//Permet de fermer le modal quand le bouton fermer est pressé
closeButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
        const modalId = btn.getAttribute('data-close');
        document.getElementById(modalId).style.display = 'none';
    });
});

//Permet de ferme le modal quand on clique autour
window.addEventListener('click', (e) => {
    modals.forEach((modal) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
