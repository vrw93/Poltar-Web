function eventHandler(action, id){
    if(action !== 'active' && action !== 'inactive'){
        alert('Error Internal');
        return;
    }

    const usrConfirm = confirm(`Apakah anda yakin ingin ${action === 'active' ? 'mengaktifkan' : 'menonaktifkan'} jabatan ini?`
    );

    if(!usrConfirm) return;

    updateJabatanState(id, action);
}

async function updateJabatanState(id, action){
    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/admin/pendaftaran/updateJabatanActiveStateAPI.php',
            csrfToken,
            {
                id: id,
                code: action
            }
        );
        alert(result.message);
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }
}

document.addEventListener('click', (event) => {
    const btn = event.target.closest('.action-btn');
    if(btn){
        const action = btn.dataset.action;
        const jabatanId = btn.dataset.jabatanId;
        if(action){
            eventHandler(action, jabatanId);
        }
    }
});