document.getElementById("operation").addEventListener("change", function (event) {
    const selectedVal = event.target.value;
    const selectedText = event.target.options[event.target.selectedIndex].text;
    
    document.getElementById("submit-stuff").innerText = selectedText;

    // Gestion du champ Frais Inclus (Retrait = 2)
    if (selectedVal == 2) {
        document.getElementById("frais-inclus-field").style.display = "block";
    } else {
        document.getElementById("frais-inclus-field").style.display = "none";
    }

    // Gestion des champs Téléphone et Description (Transfert = 3)
    if (selectedVal == 3) {
        showTransactionForm();
    } else {
        makeTransactionFormDisapear();
    }

    function showTransactionForm() {
        document.getElementById("phone-field").style.display = "block";
        document.getElementById("desc-field").style.display = "block";
    }

    function makeTransactionFormDisapear() {
        document.getElementById("phone-field").style.display = "none";
        document.getElementById("desc-field").style.display = "none";
    }
});