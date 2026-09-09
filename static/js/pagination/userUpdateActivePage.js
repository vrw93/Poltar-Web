import eventBus from '/static/js/pagination/eventBus.js';

const inactiveData = new Map();
const table = document.getElementById('activePage');
const tbody = table.querySelector('tbody');

async function eventCallback(event){
    const pageN = event.detail.pageN;
    const offset = (limit * pageN) - limit;

    loadingUI();
    const data = await checkInactiveDataAvibility(limit, offset, pageN);
    updateUI(data, offset);
}

function loadingUI(){
    tbody.innerHTML = `
        <tr>
            <td colspan="7">
                <div style="animation: spin 1.5s linear infinite;text-align: center;">
                <i class="fa-solid fa-hourglass fa-xl"></i>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align:center; font-size:1.3rem">
                Loading
            </td>
        </tr>
    `;
}

function setTypeName(name){
    if(name === 'account'){
        return "Pembaruan Akun";
    }else if(name === 'biodata'){
        return "Pembaruan Biodata";
    }else{
        return "N/A";
    }
}

function updateUI(datas, offset){
    let tableRow = '';
    let index = offset + 1;

    const sortedDatas = Object.entries(datas).sort(
        ([, a], [, b]) =>
            new Date(b.createdAt) - new Date(a.createdAt)
    );

    sortedDatas.forEach(([id, data]) => {
        tableRow += `
            <tr>
                <td style="text-align: center">${index++}</td>
                <td>${data.username}</td>
                <td>${setTypeName(data.type)}</td>
                <td>${data.createdAt}</td>
                <td>${data.statusName}</td>
                <td style="text-align: right">
                    <a class="detail-btn" data-request-id="${data.id}"
                    data-created-at="${data.createdAt}"
                    data-user-id="${data.userId}" data-is-active='true'>
                        <i class="fa-solid fa-clipboard-list"></i>
                    </a>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = tableRow;
}

async function checkInactiveDataAvibility(limit, offset, pageN){
    if(inactiveData.has(pageN)){
        console.log('Cached');
        return inactiveData.get(pageN);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const criteria = 'active';
        const result = await api(
            '/api/getUserUpdateRequest.php',
            csrfToken,
            {
                offset: offset,
                criteria: criteria,
                limit: limit
            }
        );
        if(result.success){
            inactiveData.set(pageN, result.data);
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
    if(event.detail.target === 'activePage'){
        eventCallback(event);
    }
});