async function togglePendaftaranState(event){
    const btn = event.currentTarget;
    
    const userInput = confirm(pendaftaranState === 'reg_closed' ? 'Buka Pendaftaran?' : 'Tutup Pendaftaran?');
    if(!userInput){
        return;
    }

    statusCode = pendaftaranState === 'reg_closed' ? 'reg_opened' : 'reg_closed';

    try{
        const csrfToken = document.querySelector('meta[name="csrf_token"]').content;

        const result = await api(
            '/api/updateServerStatusByNameAPI.php',
            csrfToken,
            {
                name: 'reqruitementPage',
                statusCode: statusCode
            }
        );
        if(result.success){
            pendaftaranState = statusCode;
            btn.innerHTML = statusCode === 'reg_opened' ? 
                '<i class="fa-solid fa-door-closed"></i> Tutup Pendaftaran' :
                '<i class="fa-solid fa-door-open"></i> Buka Pendaftaran';
        }
        alert(result['message']);
    }catch(error){
        alert("Terjadi Error Internal " + error);
        console.log(error);
    }
}

const btn = document.getElementById('pendaftaranStateToggleBtn');
btn.addEventListener('click', togglePendaftaranState);