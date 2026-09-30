const dialog = document.getElementById("editorDialog");

async function eventCallback(jabatanId){
    toggleDialog();
}

function toggleDialog(){
    if(dialog.open){
        dialog.close();
        return;
    }else{
        dialog.showModal();
    }
}

document.addEventListener('click', (event) => {
    const btn = event.target.closest('.edit-btn');

    if(btn){
        const jbtnID = btn.dataset.jabatanId;
        eventCallback(jbtnID);
    }
});