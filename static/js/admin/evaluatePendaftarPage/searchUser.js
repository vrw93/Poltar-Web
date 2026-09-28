const searchForm = document.getElementById("userSearch");
const serachInput = searchForm.querySelector('input');
const table = document.getElementById('verifiedPage');
const tbody = table.querySelector('tbody');
const searchData = new Map();

let requestId = 1;

async function eventCallback(searchValue) {
    loadingUI();
    const thisRequestId = ++requestId;
    const datas = await checkSearchDataAvaibility(searchValue);

    if(thisRequestId !== requestId) return;

    updateUI(datas);
}

function updateUI(datas){
    let tableRow = '';
    let index = 1;

    const sortedDatas = Object.entries(datas.pendaftarData).sort(
        ([, a], [, b]) =>
            new Date(b.createdAt) - new Date(a.createdAt)
    );

    if(Object.keys(sortedDatas).length === 0){
        tableRow = `
            <tr>
                <td colspan="7">
                    <div style="text-align: center;">
                    <i class="fa-solid fa-inbox fa-2xl"></i>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="7" style="text-align:center; font-size:1.3rem">
                    Data Tidak Ditemukan
                </td>
            </tr>
        `;
    }

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

async function checkSearchDataAvaibility(searchValue){
    if(searchData.has(searchValue)){
        console.log('Cached');
        return searchData.get(searchValue);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/admin/pendaftaran/getVerifiedPenSearchDataAPI.php',
            csrfToken,
            {
                search: searchValue
            }
        );
        if(result.success){
            searchData.set(searchValue, result.data);
            return result.data;
        }else{
            alert(result.message);
        }
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }
}

serachInput.addEventListener('input', (event) => {
    const inputValue = event.target.value;

    eventCallback(inputValue);
});

searchForm.addEventListener('submit', (event) => {
    event.preventDefault();
});