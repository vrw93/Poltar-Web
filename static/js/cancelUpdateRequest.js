import deteleDetailDataByReqId from '/static/js/renderUpdateRequestDetail.js'

async function cancelEventHandler(reqId, target){
    const confirms = confirm(
        'Yakin Ingin Menghapus Permintaan Pembaruan Ini?\nRequest Id: #'
        + reqId
    );

    if(confirms !== true) return;
    
    const result = await cancelRequest(reqId);
    if(!result.success) return;

    updateUi(target, result.data);
    deteleDetailDataByReqId(reqId);
}

function updateUi(target, statusName){
    const parent = target.parentElement.parentElement;
    const statusTab = parent.querySelector('.tableStatus');
    
    target.disabled = true;
    statusTab.className = '';
    statusTab.classList.add('tableStatus', 'cancel');
    statusTab.innerHTML = '<i class="fa-solid fa-ban"></i> ' + statusName.name;
}

async function cancelRequest(reqId){
    try{
        const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
        const result = await api(
            '/api/cancelUpdateRequestAPI.php',
            csrfToken,
            {
                requestId: reqId
            }
        );
        alert(result.message);
        return result;
    }catch(err){
        alert('Terjadi Error Internal: ' + err);
        console.log(err);
    }
}

document.addEventListener('click', (event) => {
    const target = event.target.closest('.cancel');
    if(target){
        const reqId = target.dataset.requestId;
        cancelEventHandler(reqId, target);
    }
});