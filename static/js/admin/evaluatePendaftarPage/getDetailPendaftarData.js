const detailDialog = document.getElementById('detailDialog');
let itemPanel = detailDialog.querySelector('.itemPanel');

async function updateDetailUi(id){
    const oldUi = itemPanel.innerHTML;
    loadingDetailUI();

    const timeStart = performance.now();
    await checkDataAvibilty(id);
    const timeStop = performance.now();
    console.log(`Fetch Time: ${timeStop - timeStart}`);

    itemPanel.innerHTML = oldUi;
    const keahlianUi = document.getElementById('keahlianDetail');
    const dataDetailUi = detailDialog.querySelector('.dataDetail');
    const data = userData[id];

    dataDetailUi.innerHTML = `
        <span>Nama Lengkap</span><span>:</span><span>${data.name}</span>
        <span>Kelas</span><span>:</span><span>${data.kelas}</span>
        <span>No Absen</span><span>:</span><span>${data.no_absen}</span>
        <span>Mendaftar Pada</span><span>:</span><span>${data.createdAt}</span>
    `;
    keahlianUi.innerText = data.keahlian || 'Siswa Tidak Menambahkan Detail Keahlian';
}

function loadingDetailUI(){
    itemPanel.innerHTML = `
        <h2 style="margin:0px">
            <i class="fa-solid fa-address-card"></i>
            Edit Jabatan
            <hr>
        </h2>
        <div class="VContainer">
            <div style="text-align: center;font-size: 2rem;
            margin-top: 50px; margin-bottom: 50px">
                <div style="animation: spining 1.5s linear infinite">
                    <i class="fa-solid fa-rotate fa-2xl"></i>
                </div>
                <br>
                Loading...
            </div>
        </div>`;
}

async function checkDataAvibilty(id){
    try{
        if(!(id in userData)){
            const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
            const result = await api(
                '/api/admin/pendaftaran/getDetailPendaftaranDataByIdAPI.php',
                csrfToken,
                {
                    pendaftaranId: id
                }
            );

            if(result.success){
                userData[id] = result.data;
            }else{
                alert(result.message);
            }
        }
    }catch(e){
        console.error(e);
    }
}

async function toggleDetailDialog(id) {
    if (detailDialog.open) {
        detailDialog.close();
        return;
    }

    detailDialog.showModal();

    try {
        await updateDetailUi(id);
    } catch (error) {
        console.error("Gagal update detail:", error);
    }
}

document.addEventListener('click', function(event){
    const target = event.target.closest('.detail-btn');
    if(target){
        const id = target.dataset.pendaftarId;
        toggleDetailDialog(id);
    }
});