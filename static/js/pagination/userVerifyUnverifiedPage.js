import eventBus from '/static/js/pagination/eventBus.js';

const verifiedData = new Map();
const table = document.getElementById('unverifiedPage');
const tbody = table.querySelector('tbody');

async function eventCallback(event){
    const pageN = event.detail.pageN;
    const offset = (limit * pageN) - limit;

    loadingUI();
    const data = await checkUnverifiedDataAvailbility(limit, offset, pageN);
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
                <td>${index++}</td>
                <td>${data.name}</td>
                <td>${data.kelas}</td>
                <td style="text-align: center">${data.no_absen}</td>
                <td>${data.createdAt}</td>
                <td class="status-column">
                    ${data.statusPendaftaran}
                    <i class="fa-solid fa-user-check"></i>
                </td>
                <td style="text-align: right">
                    <a class="edit-btn" data-pendaftaran-id="${id}"
                    data-nama-pendaftar="${data.name}">
                    <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = tableRow;
}

async function checkUnverifiedDataAvailbility(limit, offset, pageN){
    if(verifiedData.has(pageN)){
        console.log('Cached');
        return verifiedData.get(pageN);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/getUnverifiedPenDataAPI.php',
            csrfToken,
            {
                offset: offset,
                limit: limit
            }
        );
        if(result.success){
            verifiedData.set(pageN, result.data);
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
    if(event.detail.target === 'unverifiedPage'){
        eventCallback(event);
    }
});