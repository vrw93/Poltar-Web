import { updateDetailData, checkDetailDataAvaibility } from "/static/js/admin/jabatanEditor/dataManager.js";

const dialog = document.getElementById("editorDialog");
let requestId = 1;
let currentJabatanId;

async function eventCallback(jabatanId){
    if(!toggleDialog()) return;

    const thisRequestId = ++requestId;
    loadingUI();
    const data = await checkDetailDataAvaibility(jabatanId);

    if(thisRequestId !== requestId) return;

    currentJabatanId = jabatanId;
    updateUI(data);
    const form = dialog.querySelector('form');
    iconPreviewUpdate(form);
}

async function editJabatanSubmit(event, id){
    const formdata = new FormData(event.target);
    let data = Object.fromEntries(formdata.entries());
    const oldData = await checkDetailDataAvaibility(id);

    const kuota = parseInt(data.kuota, 10);
    const name = data.name;
    const icons = data.icons;
    
    if(isNaN(kuota) || kuota < 1){
        alert('Kuota harus berupa angka dan lebih besar dari 0.');
        return;
    }

    loadingUI();

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/admin/pendaftaran/updateJabatanDetailAPI.php',
            csrfToken,
            {
                id: id,
                name: name,
                kuota: kuota,
                icons: icons
            }
        );
        alert(result.message);

        if(!result.success){
            data = oldData;
        }else{
            updateDetailData(data, id);
        }
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }

    updateUI(data);
    const form = dialog.querySelector('form');
    iconPreviewUpdate(form);
}

function iconPreviewUpdate(form){
    const iconInput = form.querySelector('input[name="icons"]');
    const iconPreview = form.querySelector('#iconPreview');

    iconInput.addEventListener('input', (event) => {
        const value = event.target.value;

        iconPreview.innerHTML = `
            <i class="fa-solid fa-${value || 'circle-question'} fa-xl"></i>
        `;
    });
}

function loadingUI(){
    const itemPanel = dialog.querySelector('.itemPanel');

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
        </div>
    `
}

function updateUI(data){
    const itemPanel = dialog.querySelector('.itemPanel');

    itemPanel.innerHTML = `
        <h2 style="margin:0px">
            <i class="fa-solid fa-address-card"></i>
            Edit Jabatan ${data.name}
            <hr>
        </h2>
        <form id="editForm" class="VContainer" style="gap:3px">
            <p style="color:var(--color-text-secondary)"><b>Nama</b>:</p>
            <input placeholder="Masukkan Nama Jabatan" name="name"
            type="text" required value="${data.name}">
            <p style="color:var(--color-text-secondary)"><b>Kuota</b>:</p>
            <input placeholder="Masukkan Kuota Jabatan" name="kuota"
            type="number" required value="${data.kouta ?? data.kuota}" min="1">
            <p style="color:var(--color-text-secondary)"><b>Ikon</b>:</p>
            <small style="max-width: 500px;">
                Ikon dapat diambil atau dicari di 
                <a href="https://fontawesome.com/search?ic=free-collection" target="_blank"
                style="color:var(--color-text-secondary); text-decoration: underline">
                    Font Awesome
                </a>.
                <br>
                Silahkan memasukkan <b>nama ikon</b> yang ingin digunakan tanpa awalan "fa-"
                atau "&lt;i class='fa-solid fa-house'&gt;&lt;/i&gt;" contoh: "house" atau "users".
            </small>
            <div class="containerHImune" style="justify-content: flex-start;
            align-items: center; gap:10px">
                <div style="flex-grow: 1;">
                    <p><b>Nama Ikon</b>:</p>
                    <input placeholder="Masukkan Ikon Jabatan" name="icons"
                    type="text" value="${data.icons}"
                    style="width: 90%">
                </div>
                <div>
                    <p><b>Preview Ikon</b>:</p>
                    <div class="itemPanel" id="iconPreview"
                    style="padding: 5px; align-items: center;">
                        <i class="fa-solid fa-${data.icons || 'circle-question'} fa-xl"></i>
                    </div>
                </div>
            </div>
            <button type="submit" class="teritary-btn">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Perubahan
            </button>
        </form>
    `;
}

function toggleDialog(){
    if(dialog.open){
        dialog.close();
        return false;
    }else{
        dialog.showModal();
        return true;
    }
}

document.addEventListener('click', (event) => {
    const btn = event.target.closest('.edit-btn');

    if(btn){
        const jbtnID = btn.dataset.jabatanId;
        eventCallback(jbtnID);
    }
});

dialog.addEventListener('submit', (event) => {
    event.preventDefault();

    editJabatanSubmit(event, currentJabatanId);
});