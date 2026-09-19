import eventBus from '/static/js/pagination/eventBus.js';

const allUsersDataList = new Map();
const table = document.getElementById('allUserPage');
const tbody = table.querySelector('tbody');

let requestId = 1;

async function eventCallback(event){
    const pageN = event.detail.pageN;
    const offset = (limit * pageN) - limit;

    const thisReqId = ++requestId;
    loadingUI();
    const data = await checkAllUsersDataListAvailability(limit, offset, pageN);

    if(thisReqId !== requestId) return;

    updateUI(data, offset);
}

function getIconByCode(code){
    if(icons[code]){
        return icons[code];
    }else{
        return '';
    }
}

function updateUI(datas, offset){
    let innerHtml = '';
    let index = offset + 1;

    for(const [,data] of Object.entries(datas)){
        innerHtml += `
        <tr>
            <td style="text-align: center;">
                ${index++}
            </td>
            <td>${data.username}</td>
            <td>${data.email}</td>
            <td>
                ${getIconByCode(
                    'usr-role-' + data.roleCode
                )}
                ${data.roleName ?? 'N/a'}
            </td>
            <td>
                ${getIconByCode(
                    'usr-' + (data.statusCode ?? 'unverified')
                )}
                ${data.statusName ?? 'Tidak Daftar'}
            </td>
            <td style="text-align: right;gap: 15px">
                <a class="no-bg-btn detail" title="Lihat Detail Pengguna"
                data-user-id="${data.id}">
                    <i class="fa-solid fa-clipboard-list"></i>
                </a>
                <a class="no-bg-btn edit" title="Edit Biodata Pengguna"
                data-user-id="${data.id}">
                    <i class="fa-solid fa-user-gear"></i>
                </a>
                <a class="no-bg-btn role" title="Edit Role Pengguna"
                data-user-id="${data.id}">
                    <i class="fa-solid fa-user-tie"></i>
                </a>
            </td>
        </tr>
        `; 
    }

    tbody.innerHTML = innerHtml;
}

function loadingUI(){
    tbody.innerHTML = `
        <tr>
            <td colspan="7">
                <div style="animation: spining 1.5s linear infinite;text-align: center;">
                <i class="fa-solid fa-rotate fa-2xl"></i>
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

async function checkAllUsersDataListAvailability(limit, offset, pageN){
    if(allUsersDataList.has(pageN)){
        console.log('Cached');
        return allUsersDataList.get(pageN);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/getUsersDataAdminAPI.php',
            csrfToken,
            {
                offset: offset,
                limit: limit
            }
        );
        if(result.success){
            allUsersDataList.set(pageN, result.data);
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
    if(event.detail.target === 'allUserPage'){
        eventCallback(event);
    }
});