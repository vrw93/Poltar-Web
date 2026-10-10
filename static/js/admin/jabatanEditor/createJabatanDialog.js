const dialog = document.getElementById('createDialog');
let form = dialog.querySelector('form');
const dataMap = new Map();

async function clickEventHandler(){
    if(!toggleDialog()) return;

    iconPreviewUpdate(form);
    loadData(form);
}

async function createJabatanSubmit(event){
    const formdata = new FormData(event.target);
    let data = Object.fromEntries(formdata.entries());

    const kuota = parseInt(data.kuota, 10);
    const name = data.name;
    const icons = data.icons;
    const priority = data.priority;
    
    if(isNaN(kuota) || kuota < 1){
        alert('Kuota harus berupa angka dan lebih besar dari 0.');
        return;
    }

    const UI = dialog.querySelector('.itemPanel');
    const oldHtml = UI.innerHTML;
    loadingUI();

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/admin/pendaftaran/createJabatanAPI.php',
            csrfToken,
            {
                name: name,
                priority: priority,
                kuota: kuota,
                icons: icons
            }
        );
        alert(result.message);
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }

    UI.innerHTML = oldHtml;
    form = dialog.querySelector('form');
    iconPreviewUpdate(form);
    eventListenerAdder(form);
    loadData(form);
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

function loadData(form){
    dataMap.forEach((value, key) => {
        const input = form.querySelector(`input[name="${key}"]`);
        input.value = value;
    });
}

function saveData(event){
    const name = event.target.name;
    const value = event.target.value;

    dataMap.set(name, value);
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

function toggleDialog(){
    if(dialog.open){
        dialog.close();
        return false;
    }else{
        dialog.showModal();
        return true;
    }
}

function eventListenerAdder(form){
    form.addEventListener('input', (event) => {
        saveData(event);
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        createJabatanSubmit(event);
    });
}

eventListenerAdder(form);

document.addEventListener('click', (event) => {
    const btn = event.target.closest('.add-btn');

    if(btn){
        clickEventHandler();
    }
});