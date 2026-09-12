import checkDetailDataAvaibility from "./dataManager.js";

const userDetailDialog = document.getElementById("userDetailDialog");
const detailUi = userDetailDialog.querySelector('.itemPanel');

let requestId = 1;

async function detailClickEventHandler(userId){
    if(!toggleUserDetailDialog()) return;
    loadingUI();

    const thisRequestId = ++requestId;
    const data = await checkDetailDataAvaibility(userId);

    if(thisRequestId !== requestId) return;

    updateUI(data);
}

function updateUI(data){
    const accountDetail = `
        <span>Nama Pengguna</span><span>:</span><span>${data.username}</span>
        <span>Email</span><span>:</span><span>${data.email}</span>
        <span>Role</span><span>:</span><span>${data.roleName}</span>
        <span>Dibuat Pada</span><span>:</span><span>${data.accountCreationTime}</span>
    `;

    const biodataDetail = `
        <span>Nama Lengkap</span><span>:</span><span>${data.fullName}</span>
        <span>No Absen</span><span>:</span><span>${data.no_absen}</span>
        <span>Kelas</span><span>:</span><span>${data.kelas}</span>
    `;

    let pendaftaranDetail = '';

    if(!data.pendaftaranCreationTime){
        pendaftaranDetail = `
        <div style="text-align: center; padding:10px">
            <i class="fa-solid fa-circle-xmark fa-xl"></i>
            <br>
            Pengguna Belum/Tidak Mendaftar
        </div>
        `;
    }else{
        pendaftaranDetail = `
        <div class="dataDetail">
            <span>Mendaftar Pada</span><span>:</span><span>${data.pendaftaranCreationTime}</span>
            <span>Sekor</span><span>:</span><span>${data.sekor}</span>
            <span>Verifikasi</span><span>:</span><span>${data.statusName}</span>
        </div>
        `;
    }

    detailUi.innerHTML = `
    <h2 style="padding-bottom: 5px; margin-bottom:3px;
    border-bottom:2px solid var(--color-light)">
        Detail Pengguna
    </h2>
    <p style="color: var(--text-secondary);">
        <i class="fa-solid fa-user"></i>
        <b>Detail Akun</b>
    </p>
    <div class="dataDetail">
        ${accountDetail}
    </div>

    <hr style="width: 100%">
    
    <p style="color: var(--text-secondary);">
        <i class="fa-solid fa-address-card"></i>
        <b>Detail Biodata</b>
    </p>
    <div class="dataDetail">
        ${biodataDetail}
    </div>
    
    <hr style="width: 100%">
    
    <p style="color: var(--text-secondary);">
        <i class="fa-solid fa-user-plus"></i>
        <b>Detail Pendaftaran</b>
    </p>
    ${pendaftaranDetail}
    `;
}

function loadingUI(){
    detailUi.innerHTML = `
        <h2 style="padding-bottom: 5px; margin-bottom:3px;
        border-bottom:2px solid var(--color-light)">
            Detail Pengguna
        </h2>
        <div style="text-align: center;font-size: 2rem;
        margin-top: 50px; margin-bottom: 50px">
            <div style="animation: spining 1.5s linear infinite">
                <i class="fa-solid fa-rotate fa-2xl"></i>
            </div>
            <br>
            Loading...
        </div>
    `
}

function toggleUserDetailDialog(){
    if(userDetailDialog.open){
        userDetailDialog.close();
        return false;
    }else{
        userDetailDialog.showModal();
        return true;
    }
}

document.addEventListener('click', (event) => {
    const button = event.target.closest('.detail');
    if(button){
        detailClickEventHandler(button.dataset.userId);
    }
});