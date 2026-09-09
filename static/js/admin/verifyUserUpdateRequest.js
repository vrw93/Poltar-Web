const requestDetailData = new Map();
const requestDetailDialog = document.getElementById('requestDetailDig');
const closeBtn = document.getElementById('digCloseBtn');
const csrfToken = document.querySelector('meta[name="csrf_token"]').content;

async function getRequestDetail(id){
    if(requestDetailData.has(id)){
        console.log('cached');
        return requestDetailData.get(id);
    }

    try{
        const result = await api(
            '/api/getUserUpdateRequestDetailAPI.php',
            csrfToken,
            {
                id: id
            }
        );

        if(result.success){
            requestDetailData.set(id, result.data);
        }
        return result.data;
    }catch(err){
        alert('Terjadi Error Internal: ' + err);
    }
}

async function updateDetailUi(id, createdAt, userId){
    const detail = requestDetailDialog.querySelector('.dataDetail');
    const tableRow = requestDetailDialog.querySelector('tbody');
    detail.innerHTML = "Loading...";
    tableRow.innerHTML = `<tr>
        <td></td>
        <td></td>
        <td>Loading...</td>
        <td></td>
        </tr>
    `;

    const data = await getRequestDetail(id);

    detail.innerHTML = `
        <span>Tanggal Di Buat</span><span>:</span><span>${createdAt}</span>
        <span>Id Pengguna</span><span>:</span><span>${userId}</span>
    `;
    tableRow.innerHTML = "";
    
    let requestDetailRow = "";

    data.forEach((item, index) => {
        const status = setStatusIcon(item.statusCode);
        const field = setFieldName(item.field);

        requestDetailRow += `<tr>
            <td>${index + 1}</td>
            <td>${field}</td>
            <td id="status-${item.field}">
                ${item.newValue} 
                <small class="tableStatus ${status.state}">
                ${status.icon}
                </small>
            </td>
            <td>
                <a class="edit-btn"
                    data-request-id="${id}"
                    data-detail-id="${item.id}"
                    data-user-id="${userId}"
                    data-index="${index}"
                    data-action="accepted">
                    <i class="fa-solid fa-check"></i>
                </a>

                <a class="edit-btn"
                    data-request-id="${id}"
                    data-detail-id="${item.id}"
                    data-user-id="${userId}"
                    data-index="${index}"
                    data-action="rejected">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            </td>
        </tr>`;
    });

    tableRow.innerHTML = requestDetailRow;
}

function setFieldName(typeCode){
    if(typeCode === 'name'){
        return 'Nama';
    }
    if(typeCode === 'kelas_id'){
        return 'Kelas';
    }
    if(typeCode === 'no_absen'){
        return 'No Absen';
    }
    if(typeCode === 'username'){
        return 'Nama Pengguna';
    }
    return typeCode;
}

function setStatusIcon(statusCode){
    if(statusCode === 'accepted'){
        return {
            'state' : 'accepted',
            'icon' : '<i class="fa-solid fa-check"></i>'
        };
    }else if(statusCode === 'rejected'){
        return {
            'state' : 'reject',
            'icon' : '<i class="fa-solid fa-xmark"></i>'
        };
    }else{
        return {
            'state' : 'displaced',
            'icon' : '<i class="fa-solid fa-clock"></i>'
        };
    }
}

async function UpdateRequest(userId, requestId, status,
    detailId, dataIndex)
    {
    const confirms = confirm('Yakin Ingin ' + (status === 'accepted' ? 'Menerima' : 'Menolak') + ' Permintaan?');

    if(!confirms){
        return;
    }
    const data = await getRequestDetail(requestId);
    const statusUI = document.getElementById(`status-${data[dataIndex].field}`);

    try{
        const newValue = data[dataIndex].realValue ?? data[dataIndex].newValue;
        const result = await api(
            '/api/updateUserDBDataAPI.php',
            csrfToken,
            {
                userId: userId,
                requestId: requestId,
                detailId: detailId,
                field: data[dataIndex].field,
                value: newValue,
                status: status
            }
        );

        if(result.success){
            alert(result.message);
            const statusIcon = setStatusIcon(status);
            
            statusUI.innerHTML = `<td id="status-${data[dataIndex].field}">
                ${data[dataIndex].newValue} 
                <small class="tableStatus ${statusIcon.state}">
                ${statusIcon.icon}
                </small>
            </td>`;

            requestDetailData.get(requestId)[dataIndex].statusCode = status;
        }else{   
            alert(result.message);
        }
    }catch(err){
        alert('Terjadi Error Internal: ' + err);
    }
}

document.addEventListener('click', (event) => {
    //updateDetailUi(event.dataset.requestId);
    const target = event.target;
    const button = target.closest('.detail-btn');
    const editBtn = target.closest('.edit-btn');

    if(button){
        requestDetailDialog.showModal();
        updateDetailUi(
            button.dataset.requestId,
            button.dataset.createdAt,
            button.dataset.userId
        );
    }
    
    if(editBtn){
        UpdateRequest(
            editBtn.dataset.userId,
            editBtn.dataset.requestId,
            editBtn.dataset.action,
            editBtn.dataset.detailId,
            editBtn.dataset.index,
        );
    }
});

closeBtn.addEventListener('click', () => {
    requestDetailDialog.close();
});