const detailDialog = document.getElementById('detailDialog');
const dataDetailUi = detailDialog.querySelector('.dataDetail');
const keahlianUi = document.getElementById('keahlianDetail');

async function updateDetailUi(id){
    dataDetailUi.innerHTML = '<p>Loading...</p>';
    keahlianUi.innerText = 'Loading...';

    const timeStart = performance.now();
    await checkDataAvibilty(id);
    const timeStop = performance.now();
    console.log(`Fetch Time: ${timeStop - timeStart}`);

    const data = userData[id];

    dataDetailUi.innerHTML = `
        <span>Nama Lengkap</span><span>:</span><span>${data.name}</span>
        <span>Kelas</span><span>:</span><span>${data.kelas}</span>
        <span>No Absen</span><span>:</span><span>${data.no_absen}</span>
        <span>Mendaftar Pada</span><span>:</span><span>${data.createdAt}</span>
    `;
    keahlianUi.innerText = data.keahlian || 'Siswa Tidak Menambahkan Detail Keahlian';
}

async function checkDataAvibilty(id){
    if(!(id in userData)){
        const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
        const result = await api(
            '/api/getDetailPendaftaranDataByIdAPI.php',
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