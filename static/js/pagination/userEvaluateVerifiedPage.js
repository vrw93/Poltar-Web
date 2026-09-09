import eventBus from '/static/js/pagination/eventBus.js';

const verifiedData = new Map();
const table = document.getElementById('verifiedPage');
const tbody = table.querySelector('tbody');
let requestId = 0;
let oldConflictData = {};

async function eventCallback(event){
    const pageN = event.detail.pageN;
    const offset = (limit * pageN) - limit;
    
    const thisRequestId = ++requestId;

    loadingUI();
    const data = await checkVerifiedDataAvailability(limit, offset, pageN);
    
    if(thisRequestId !== requestId){
        return;
    }
    updateUI(data, offset);
    tbody.dataset.currentPage = pageN;
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

function checkConflictJabatan(currentJabatan, data, currentJabatanId, id){
    if (data.conflictData?.[currentJabatanId]?.pemilih?.[id] != null) {
        return `${currentJabatan} <a class="danger-btn conflictBtn" style="padding: 2px 5px" 
            title="Siswa Ini Memiliki Nilai Yang Sama Dengan Siswa Lain" data-pendaftar-id="${id}"
            data-jabatan-id="${currentJabatanId}">
            <i class="fa-solid fa-triangle-exclamation fa-sm fa-shake"></i>
            </a>`;
    }
    return currentJabatan;
}

function updateUI(datas, offset){
    let tableRow = '';
    let index = offset + 1;

    const sortedDatas = Object.entries(datas.pendaftarData).sort(
        ([, a], [, b]) =>
            new Date(b.createdAt) - new Date(a.createdAt)
    );

    sortedDatas.forEach(([id, data]) => {
        tableRow += `
            <tr>
                <td>${index++}</td>
                <td>${data.name}</td>
                <td>${data.kelas}</td>
                <td>${checkConflictJabatan(
                    data.currentJabatan, datas,
                    data.currentJabatanId, id
                    )}
                </td>
                <td class="sekor-column">${data.sekor ?? 'Belum Di Nilai'}</td>
                <td class="status-column">${data.statusPendaftaran}</td>
                <td style="text-align: right">
                    <a class="detail-btn" data-pendaftar-id="${id}"
                    title="Detail Siswa">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </a>
                    <a class="edit-btn" data-pendaftaran-id="${id}"
                    title="Edit Nilai Siswa">
                    <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = tableRow;
}

async function checkVerifiedDataAvailability(limit, offset, pageN){
    if(verifiedData.has(pageN)){
        console.log('Cached');
        return verifiedData.get(pageN);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/getVerifiedPenDataAPI.php',
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

async function getConflictedData(){
    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/getConflictedPenDataAPI.php',
            csrfToken,
            {}
        );
        if(result.success){
            return result.data;
        }else{
            alert(result.message);
        }
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }
}

async function updatePendaftarSekor(id, newSekor){
    const currentPage = parseInt(tbody.dataset.currentPage, 10);
    const offset = (limit * currentPage) - limit;

    let data;
    const oldata = verifiedData.get(currentPage);

    if(oldata){
        verifiedData.delete(currentPage);
        data = await checkVerifiedDataAvailability(limit, offset, currentPage);
        verifiedData.set(currentPage, data);
    }

    if(oldata.pendaftarData?.[id].sekor !== newSekor){
        const newConflictData = await getConflictedData();
        conflictData = {...newConflictData, ...oldConflictData};

        for (const [key, data] of verifiedData){
            if(!data?.conflictData) continue;

            for(const item of Object.values(conflictData)){
                for(const [penId,] of Object.entries(item.pemilih)){
                    if(data.pendaftarData?.[penId]){
                        verifiedData.delete(key);
                    }
                }
            }
        }
        oldConflictData = newConflictData;
    }

    const thisRequestId = ++requestId;

    if(data){
        if(thisRequestId !== requestId){
            return;
        }
        loadingUI();
        updateUI(data);
    }
}

eventBus.addEventListener('pageChange', (event) => {
    if(event.detail.target === 'verifiedPage'){
        eventCallback(event);
    }
}); 

eventBus.addEventListener('sekorUpdate', (event) => {
    updatePendaftarSekor(
        event.detail.id,
        event.detail.newSekor
    );
});