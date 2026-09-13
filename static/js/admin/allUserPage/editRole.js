import {updateDetailData, checkDetailDataAvaibility} from "./dataManager.js";

const editRoleDialog = document.getElementById("editRoleDialog");
const editRoleUi = editRoleDialog.querySelector(".itemPanel");

let requestId = 1;

async function editRoleClickEventHandler(userId){
    if(!toggleRoleDialog()) return;
    const oldUi = editRoleUi.innerHTML;

    loadingUI();
    const thisRequestId = ++requestId;
    const data = await checkDetailDataAvaibility(userId);

    if(requestId !== thisRequestId) return;

    editRoleDialog.dataset.userId = userId;
    updateUI(oldUi, data);
}

function updateUI(oldUi, data){
    editRoleUi.innerHTML = oldUi;
    const editForm = editRoleUi.querySelector("form");

    const role = editForm.elements.roles;

    role.value = parseInt(data.roleId, 10);
}

function loadingUI(){
    editRoleUi.innerHTML = `
        <h2 style="padding-bottom: 5px; margin-bottom:3px;
        border-bottom:2px solid var(--color-light)">
            Edit Role Pengguna
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

function toggleRoleDialog(){
    if(editRoleDialog.open){
        editRoleDialog.close();
        return false;
    }
    editRoleDialog.showModal();
    return true;
}

async function updateRole(newRole, userId){
    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/changeRolesByIdAPI.php',
            csrfToken,
            {
                userId: userId,
                roleId: newRole
            }
        );
        alert(result.message);
        return result.success;
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }
}

async function editRoleSubmitEventHandler(){
    const userId = editRoleDialog.dataset.userId;
    const form = editRoleDialog.querySelector('form');
    const oldData = await checkDetailDataAvaibility(userId);
    const oldUi = editRoleUi.innerHTML;

    const data = new FormData(form);
    const newRole = Object.fromEntries(data.entries());

    loadingUI();
    const success = await updateRole(newRole.roles, userId);
    if(success){
        const roleDropdown = form.elements.roles;
        const newRoleName = roleDropdown.options[roleDropdown.selectedIndex].text;

        oldData.roleName = newRoleName;
        oldData.roleId = newRole.roles;

        updateDetailData(oldData, userId);
    }
    updateUI(oldUi, oldData);
}

editRoleDialog.addEventListener('submit', (event) => {
    event.preventDefault();

    editRoleSubmitEventHandler();
});

document.addEventListener('click', (event) => {
    const button = event.target.closest('.role');
    if(button){
        editRoleClickEventHandler(button.dataset.userId);
    }
});