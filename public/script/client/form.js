document.getElementById("operation").addEventListener("change", function (event) {
    console.log('valeur : ' + event.target.value);

    const selectedText = event.target.options[event.target.selectedIndex].text;
    document.getElementById("submit-stuff").innerText = selectedText;

    if (event.target.value == 3) {
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