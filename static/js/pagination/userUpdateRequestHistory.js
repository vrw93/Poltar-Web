import eventBus from '/static/js/pagination/eventBus.js';

const requestHistoryData = new Map();
const table = document.getElementById('historyPage');
const tbody = table.querySelector('tbody');

let requestId = 0;

async function eventCallback(event){
    const pageN = event.detail.pageN;
    const offset = (limit * pageN) - limit;
    
    const thisRequestId = ++requestId;
    loadingUI();
    const data = await checkRequestHisoryDataAvaibility(pageN, offset);

    if(thisRequestId != requestId) return;

    updateUI(data, offset);
}

function loadingUI(){
    tbody.innerHTML = `
        <tr>
            <td colspan="5">
                <div style="animation: spin 1.5s linear infinite;text-align: center;">
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

function getTypeNameByCode(code){
    const types = {
        'account': '<i class="fa-solid fa-user-gear"></i> Pembaruan Akun',
        'biodata': '<i class="fa-solid fa-address-card"></i> Pembaruan Biodata'
    };

    if(types[code]){
        return types[code];
    }else{
        return code;
    }
}

function getStatusIconByCode(code){
    const statuses = {
        'pending': '<i class="fa-solid fa-clock"></i>',
        'accepted': '<i class="fa-solid fa-circle-check"></i>',
        'rejected': '<i class="fa-solid fa-circle-xmark"></i>',
        'partialy': '<i class="fa-solid fa-circle-exclamation"></i>',
        'cancel' : '<i class="fa-solid fa-ban"></i>'
    };

    if(statuses[code]){
        return statuses[code];
    }else{
        return '';
    }
}

function updateUI(data, offset){
    let index = offset + 1;
    let innerHtml = '';

    for (const [,item] of Object.entries(data)){
        innerHtml += `<tr>
            <td style="text-align: center;">${index++}</td>
            <td>${item.createdAt}</td>
            <td>${getTypeNameByCode(item.type)}</td>
            <td>
                <p class="tableStatus ${item.statusCode}">
                    ${getStatusIconByCode(item.statusCode)}
                    ${item.status}
                </p>
            </td>
            <td class="VContainer" style="gap:1px">
                <button class="primary-btn detail" style="padding:3px;"
                data-request-id="${item.id}" data-request-date="${item.createdAt ?? 'N/A'}">
                    <i class="fa-solid fa-clipboard-list"></i>
                    Detail
                </button>
                <button ${item['statusCode'] !== 'pending' ? 'disabled' : ''}
                class="danger-btn cancel" style="padding:3px;"
                data-request-id="${item['id']}">
                    <i class="fa-regular fa-circle-xmark"></i>
                    Batalkan
                </button>
            </td>
        </tr>`;
    }

    tbody.innerHTML = innerHtml;
}

async function checkRequestHisoryDataAvaibility(pageN, offset){
    if(requestHistoryData.has(pageN)){
        console.log('Cached');
        return requestHistoryData.get(pageN);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/getUserUpdateRequestHistoryAPI.php',
            csrfToken,
            {
                offset: offset,
                limit: limit
            }
        );
        if(result.success){
            requestHistoryData.set(pageN, result.data);
            return result.data;
        }else{
            alert(result.message);
        }
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }
}

eventBus.addEventListener('pageChange', (event) => {
    if(event.detail.target === 'historyPage'){
        eventCallback(event);
    }
}); 