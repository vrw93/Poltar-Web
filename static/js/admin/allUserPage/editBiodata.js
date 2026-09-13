import {updateDetailData, checkDetailDataAvaibility} from "./dataManager.js";

const editBiodataDialog = document.getElementById("editBiodataDialog");

let requestId = 1;

async function editClickEventHandler(userId){
    if(!toggleEditDialog()) return;

    let oldInnerHtml = editBiodataDialog.innerHTML;
    loadingUi();

    const thisRequestId = ++requestId;
    const data = await checkDetailDataAvaibility(userId);

    if(thisRequestId !== requestId) return;

    updateUi(oldInnerHtml, data);
    editBiodataDialog.dataset.userId = userId;
}

function updateUi(oldUi, data){
    editBiodataDialog.innerHTML = oldUi;
    const editForm = editBiodataDialog.querySelector('form');
    const title = editBiodataDialog.querySelector('.username');

    const fullname = editForm.elements.name;
    const noAbsen = editForm.elements.noAbsen;
    const kelas = editForm.elements.kelas;

    fullname.value = data.fullName;
    noAbsen.value = parseInt(data.no_absen, 10);
    kelas.value = data.kelasId;
    title.innerText = "Edit Biodata " + data.username;
}

function toggleEditDialog(){
    if(editBiodataDialog.open){
        editBiodataDialog.close();
        return false;
    }
    editBiodataDialog.showModal();
    return true;
}

function loadingUi(){
    const editBiodataUi = editBiodataDialog.querySelector(".itemPanel");

    editBiodataUi.innerHTML = `
        <h2 style="padding-bottom: 5px; margin-bottom:3px;
        border-bottom:2px solid var(--color-light)">
            Edit Biodata Pengguna
        </h2>
        <div style="text-align: center;font-size: 2rem;
        margin-top: 50px; margin-bottom: 50px">
            <div style="animation: spining 1.5s linear infinite">
                <i class="fa-solid fa-rotate fa-2xl"></i>
            </div>
            <br>
            Loading...
        </div>
    `;
}

async function updateBiodata(biodata, userId, oldData){
    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/updateBiodataAdminAPI.php',
            csrfToken,
            {
                name: biodata.name,
                noAbsen: biodata.noAbsen,
                kelasId: biodata.kelas,
                userId: userId
            }
        );
        alert(result.message);
        if(result.success){
            oldData.fullName = biodata.name;
            oldData.no_absen = parseInt(biodata.noAbsen, 10);
            oldData.kelasId = biodata.kelas;

            updateDetailData(oldData, userId);
        }
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }
}

async function biodataSubmitEventHandler(){
    const editForm = editBiodataDialog.querySelector('form');
    let oldInnerHtml = editBiodataDialog.innerHTML;

    const userId = editBiodataDialog.dataset.userId;
    const data = new FormData(editForm);
    const biodata = Object.fromEntries(data.entries());
    const oldData = await checkDetailDataAvaibility(userId);

    loadingUi();
    await updateBiodata(biodata, userId, oldData);

    updateUi(oldInnerHtml, oldData);
}

document.addEventListener('click', (event) => {
    const button = event.target.closest(".edit");
    if(button){
        editClickEventHandler(button.dataset.userId);
    }
});

editBiodataDialog.addEventListener('submit', (event) => {
    event.preventDefault();
    
    biodataSubmitEventHandler();
});