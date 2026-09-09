document.addEventListener('click', function(event){
    const clickedElement = event.target;
    const targetbtn = clickedElement.closest(".danger-btn");

    if(targetbtn){
        const parentElement = targetbtn.closest(".itemPanel");
        const selectElement = parentElement.querySelector('select');
        const txtKuota = selectElement.nextElementSibling;

        selectElement.value = "";
        if(txtKuota)
            txtKuota.textContent = "Kouta : N/A";

        targetbtn.className = "hidden";
    }
});