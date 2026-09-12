import checkDetailDataAvaibility from "./dataManager.js";

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

    const fullname = editForm.elements.name;
    const noAbsen = editForm.elements.noAbsen;
    const kelas = editForm.elements.kelas;

    fullname.value = data.fullName;
    noAbsen.value = parseInt(data.no_absen, 10);
    kelas.value = data.kelasId;
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

async function biodataSubmitEventHandler(event){
    const editForm = editBiodataDialog.querySelector('form');
    let oldEditInnerHtml = editBiodataUi.innerHTML;

    const data = new FormData(editForm);
    const biodata = Object.fromEntries(data.entries());
    const userId = editBiodataDialog.dataset.userId;

    loadingUi();

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
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }

    editBiodataUi.innerHTML = oldEditInnerHtml;
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