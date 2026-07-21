/**
 * public/assets/js/operateur/frais.js
 * -------------------------------------------------------------
 * Gère la page "Barèmes de frais" (app/Views/operateur/frais/index.php) :
 *  1. Changement d'onglet (nav-tabs) par opération.
 *  2. Filtrage des lignes du tableau selon l'onglet actif.
 *  3. Synchronisation du champ caché #operation_id du formulaire
 *     d'ajout avec l'opération actuellement sélectionnée.
 * -------------------------------------------------------------
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        const tabsContainer   = document.getElementById('frais-tabs');
        const tableBody       = document.querySelector('#frais-table tbody');
        const emptyMessage    = document.getElementById('frais-empty-message');
        const hiddenOperation = document.getElementById('operation_id');
        const createForm      = document.getElementById('frais-create-form');

        if (!tabsContainer || !tableBody || !hiddenOperation) {
            // La page ne contient pas les éléments attendus, on arrête ici.
            return;
        }

        const tabButtons = Array.from(tabsContainer.querySelectorAll('.nav-link[data-operation-id]'));
        const rows        = Array.from(tableBody.querySelectorAll('tr[data-operation-id]'));

        /**
         * Active l'onglet correspondant à un bouton donné :
         * - bascule les classes .active sur les onglets
         * - filtre les lignes du tableau
         * - met à jour le champ caché operation_id du formulaire d'ajout
         */
        function activateTab(button) {
            const operationId  = button.dataset.operationId;
            const operationNom = button.dataset.operationNom || '';

            // 1. État visuel des onglets
            tabButtons.forEach(function (btn) {
                const isActive = btn === button;
                btn.classList.toggle('active', isActive);
                btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            // 2. Filtrage des lignes du tableau
            let visibleCount = 0;
            rows.forEach(function (row) {
                const match = row.dataset.operationId === operationId;
                row.classList.toggle('d-none', !match);
                if (match) {
                    visibleCount++;
                }
            });

            if (emptyMessage) {
                emptyMessage.classList.toggle('d-none', visibleCount > 0);
            }

            // 3. Formulaire d'ajout : opération = onglet actif
            hiddenOperation.value = operationId;

            if (createForm) {
                createForm.dataset.operationNom = operationNom;
            }
        }

        // Un clic sur un onglet active la vue correspondante
        tabButtons.forEach(function (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                activateTab(button);
            });
        });

        // Au chargement : activer l'onglet déjà marqué .active (le premier par défaut),
        // sinon prendre le tout premier onglet disponible.
        const initialTab = tabsContainer.querySelector('.nav-link.active') || tabButtons[0];
        if (initialTab) {
            activateTab(initialTab);
        }

        // Sécurité : empêche l'envoi du formulaire si aucune opération n'est sélectionnée
        if (createForm) {
            createForm.addEventListener('submit', function (event) {
                if (!hiddenOperation.value) {
                    event.preventDefault();
                    alert('Veuillez sélectionner une opération (onglet) avant d\'ajouter un barème.');
                }
            });
        }
    });
})();