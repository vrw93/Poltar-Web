const conflictDialog = document.getElementById('conflictDialog');
const list = conflictDialog.querySelector('ul');
const label = conflictDialog.querySelector('h2');
const text = conflictDialog.querySelector('p');

async function updateUi(id, jabatanId) {
    const conflict = conflictData[jabatanId]['pemilih']; 
    const kuota = conflictData[jabatanId]['kuota'];
    const name = conflictData[jabatanId]['name'];

    list.innerHTML = '<p style="text-align: center">Loading...</p>';
    label.innerText = `Pilih ${kuota} Dari ${Object.keys(conflict).length} Siswa`
    text.innerText = `Pilihan ini akan menentukan siapa yang akan dipilih menjadi ${name}`;

    await Promise.all(
        Object.keys(conflict).map(key => checkDataAvibilty(key))
    );

    list.innerHTML = "";

    for (const key in conflict) {
        const data = userData[key];

        list.innerHTML += `
            <li>
                <div class="participantItem">
                    <p>${data.name}</p>

                    <a class="primary-btn" style="padding:2px 5px" data-pendaftar-id="${key}"
                    data-jabatan-id="${jabatanId}">
                        <i class="fa-solid fa-check"></i>
                    </a>
                </div>
            </li>
        `;
    }
}

/*async function checkDataAvibilty(id){
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
        }
    }
}*/

async function toggleDialog(id, jabatanId){
    if(conflictDialog.open){
        conflictDialog.close();
    }else{
        conflictDialog.showModal();
        await updateUi(id, jabatanId);
    }
}

async function selectPendaftar(id, jabatanId){
    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;

    const result = await api(
        '/api/conflictResolverAPI.php',
        csrfToken,
        {
            pendaftaranId: id,
            jabatanId: jabatanId,
            status: 'accepted_manual'
        }
    );
    if(result.success){
        alert(result.message);
        window.location.reload();
    }else{
        alert(result.message);
    }
}

list.addEventListener('click', function(event){
    const target = event.target.closest('.primary-btn');
    if(target){
        const id = target.dataset.pendaftarId;
        const jabatanId = target.dataset.jabatanId;
        selectPendaftar(id, jabatanId);
    }
});

document.addEventListener('click', function(event){
    const target = event.target.closest('.conflictBtn');
    if(target){
        const id = target.dataset.pendaftarId;
        const jabatanId = target.dataset.jabatanId;
        toggleDialog(id, jabatanId);
    }
});