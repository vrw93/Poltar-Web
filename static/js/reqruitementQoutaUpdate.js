document.addEventListener('change', function(event){
    const selectElement = event.target;
    if(selectElement.tagName === 'SELECT' && selectElement.closest('.containerHImune')){
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const kouta = selectedOption.dataset.kouta || "N/A";

        const targetParent = selectElement.closest(".containerHImune");
        const targetP = targetParent.querySelector("p");
        const upperParent = selectElement.closest(".itemPanel");
        const targetBtn = upperParent.querySelector("button");

        targetP.textContent = `Kouta : ${kouta}`;
        targetBtn.className = "danger-btn";
    }
});