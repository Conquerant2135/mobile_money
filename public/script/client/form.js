document.addEventListener("DOMContentLoaded", function () {
    const operationSelect = document.getElementById("operation");
    const phoneList = document.getElementById("phone-list");
    const addPhoneBtn = document.getElementById("add-phone-btn");

    // Gestion de l'affichage dynamique selon l'opération choisie
    operationSelect.addEventListener("change", function (event) {
        const selectedVal = event.target.value;
        const selectedText = event.target.options[event.target.selectedIndex].text;

        document.getElementById("submit-stuff").innerText = selectedText;

        // Frais inclus si Retrait (valeur 2)
        if (selectedVal == 2) {
            document.getElementById("frais-inclus-field").style.display = "block";
        } else {
            document.getElementById("frais-inclus-field").style.display = "none";
        }

        // Téléphone et Description si Transfert (valeur 3)
        if (selectedVal == 3) {
            showTransactionForm();
        } else {
            makeTransactionFormDisapear();
        }
    });

    function showTransactionForm() {
        document.getElementById("phone-field").style.display = "block";
        document.getElementById("desc-field").style.display = "block";
    }

    function makeTransactionFormDisapear() {
        document.getElementById("phone-field").style.display = "none";
        document.getElementById("desc-field").style.display = "none";
    }

    // Ajout d'un champ numéro supplémentaire
    addPhoneBtn.addEventListener("click", function () {
        const div = document.createElement("div");
        div.className = "input-group mb-2";
        div.innerHTML = `
            <input type="text" class="form-control" name="phone[]" placeholder="034xxxxxxx">
            <button type="button" class="btn btn-outline-danger remove-phone-btn">X</button>
        `;
        phoneList.appendChild(div);
    });

    // Suppression d'un champ numéro
    phoneList.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-phone-btn")) {
            e.target.closest(".input-group").remove();
        }
    });
});