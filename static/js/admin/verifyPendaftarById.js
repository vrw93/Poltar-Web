async function nilaiPendaftarByPenId(id, statusUi, name){
    const userInput = confirm(`Verifikasi Pendaftar ${name}?`);
    
    if(!userInput){
        return;
    }
    try{
        const csrfToken = document.querySelector('meta[name="csrf_token"]').content;

        const result = await api(
            '/api/updateVerifyStatusByPendaftaranIdAPI.php',
            csrfToken,
            {
                id: id,
                status_id: 2
            }
        );

        if(result.success){
            statusUi.textContent = "Diverifikasi";
        }

        alert(result['message']);
    }catch(error){
        alert("Terjadi Error Internal " + error);
    }
}

document.addEventListener('click', function(event){
    const clickedElement = event.target;
    const targetbtn = clickedElement.closest(".edit-btn");

    if(targetbtn){
        if(targetbtn.dataset.pendaftaranId && targetbtn.dataset.namaPendaftar){
            const id = parseInt(targetbtn.dataset.pendaftaranId);
            const name = targetbtn.dataset.namaPendaftar;

            const row = targetbtn.closest('tr');
            const statusUi = row.querySelector(".status-column");

            if(isNaN(id)){
                alert("Terjadi Error Internal. Jika Masalah Terus Terjadi Cobalah Muat Ulang Halaman Ini");
                return;
            }
            nilaiPendaftarByPenId(id, statusUi, name);
        }else{
            alert("Terjadi Error Internal. Jika Masalah Terus Terjadi Cobalah Muat Ulang Halaman Ini");
        }
    }
});