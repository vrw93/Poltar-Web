const csvLoadingUI = document.getElementById('csvloading');

async function eventHandler(action){
    csvLoadingUI.showModal();
    let file;

    if(action === 'accepted'){
        file = await getAcceptedCSV();
    }

    if(!file){
        csvLoadingUI.close();
        alert('Terjadi Kesalahan Internal');
        return;
    }

    const url = URL.createObjectURL(file);

    const a = document.createElement('a');
    a.href = url;
    a.download = "PendaftarDiterimaCSV.csv";

    document.body.appendChild(a);
    a.click();
    a.remove();

    csvLoadingUI.close();
    setTimeout(() => {
        URL.revokeObjectURL(url);
    }, 500000);
}

async function getAcceptedCSV(){
    try{
        const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
        const file = await api(
            '/api/admin/pendaftaran/exportAcptResultCSVAPI.php',
            csrfToken,
            null,
            false,
            true
        );
        return file;
    }catch(e){
        console.error(e);
    }
}

document.addEventListener('click', (event) =>{
    const btn = event.target.closest('.download-btn');
    if(btn){
        const action = btn.dataset.action;
        eventHandler(action);
    }
});