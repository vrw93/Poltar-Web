const updateDetailDialog = document.getElementById('updateDetail');
const updateDetailUi = document.getElementById('updateDetailUi');
const updateDetailDateUi = document.getElementById('updateDetailDateUi');

const detailData = new Map();

async function detailClickHandler(requestId, requestDate){
    if(updateDetailDialog.open){
        updateDetailDialog.close();
        return;
    }

    updateDetailDialog.showModal();
    try{
        updateDetailDateUi.innerHTML = `${requestDate}<br> ID Permintaan: #${requestId}`;
        loadingUi();
        const data = await checkDetailDataAvibility(requestId);
        renderDetailData(data);
    }catch(err){
        console.error(err);
    }
}

function deteleDetailDataByReqId(reqId){
    detailData.delete(reqId);
}

async function checkDetailDataAvibility(requestId){
    if(detailData.has(requestId)){
        console.log('Cached');
        return detailData.get(requestId);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/getUserUpdateRequestDetailAPI.php',
            csrfToken,
            {
                id: requestId
            }
        );
        if(result.success){
            detailData.set(requestId, result.data);
            return result.data;
        }else{
            alert(result.message);
        }
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }
}

function getTypeByField(field){
    const fields = {
        'username': 'Nama Pengguna',
        'name': 'Nama',
        'no_absen': 'Nomor Absen',
        'kelas_id': 'Kelas'
    }

    if(fields[field]){
        return fields[field];
    }
    return field;
}

function getStatusIconByStatusCode(code){
    const icons = {
        'pending': '<i class="fa-solid fa-clock"></i>',
        'accepted': '<i class="fa-solid fa-circle-check"></i>',
        'rejected': '<i class="fa-solid fa-circle-xmark"></i>',
        'partialy': '<i class="fa-solid fa-circle-exclamation"></i>',
        'cancel': '<i class="fa-solid fa-ban"></i>'
    }

    if(icons[code]){
        return icons[code];
    }
    return '';
}

function renderDetailData(data){
    let innerHtml = '';
    for (let [index, item] of Object.entries(data)){
        innerHtml += `
        <tr>
            <td style="text-align: center;">${++index}</td>
            <td>${getTypeByField(item.field)}</td>
            <td>${item.oldValue}</td>
            <td>${item.newValue}</td>
            <td>
                <p class="tableStatus ${item.statusCode}">
                    ${getStatusIconByStatusCode(item.statusCode)}
                    ${item.statusName}
                </p>
            </td>
        </tr>`;
    }
    updateDetailUi.innerHTML = innerHtml;
}

function loadingUi(){
    updateDetailUi.innerHTML = `
        <tr>
            <td colspan="5">
                <div style="animation: hour-glass-spin 1.5s linear infinite;text-align: center;">
                <i class="fa-solid fa-hourglass fa-xl"></i>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align:center; font-size:1.3rem">
                Loading...
            </td>
        </tr>
    `;
}

export default deteleDetailDataByReqId;

document.addEventListener('click', (event) => {
    const target = event.target.closest('.detail');

    if(target){
        const requestId = target.dataset.requestId;
        const requestDate = target.dataset.requestDate;

        detailClickHandler(requestId, requestDate);
    }
});