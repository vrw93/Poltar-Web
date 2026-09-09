import eventBus from '/static/js/pagination/eventBus.js';

async function nilaiPendaftarByPenId(id, sekorUi/*, statusUi*/){
    const userInput = prompt("Masukkan Sekor:");

    if(userInput === null){
        return;
    }

    if (userInput.trim() === ""){
        alert("Masukkan Sekor Terlebih Dahulu!");
        return;
    }

    const sekor = parseFloat(userInput);
    if(isNaN(sekor)){
        alert("Tipe Data Tidak Di Dukung!");
        return;
    }

    if(sekor < 0 || sekor > 100){
        alert("Sekor Harus Di Antara 0 Sampai 100");
        return;
    }

    try{
        const csrfToken = document.querySelector('meta[name="csrf_token"]').content;

        const result = await api(
            '/api/nilaiPendaftarAPI.php',
            csrfToken,
            {
                pendaftaranId: id,
                sekor: sekor
            }
        );

        if(result.success){
            sekorUi.textContent = sekor;
            invokeEvent(id, sekor);
            /*statusUi.textContent = 'Dinilai';*/
        }

        alert(result['message']);
    }catch(error){
        alert("Terjadi Error Internal " + error);
        console.log(error);
    }
}

function invokeEvent(id, newSekor){
    const changeEvent = new CustomEvent('sekorUpdate',{
        detail: {
            id: id,
            newSekor: newSekor
        }
    });

    eventBus.dispatchEvent(changeEvent);
}

document.addEventListener('click', function(event){
    const clickedElement = event.target;
    const targetbtn = clickedElement.closest(".edit-btn");

    if(targetbtn){
        if(targetbtn.dataset.pendaftaranId){
            const id = parseInt(targetbtn.dataset.pendaftaranId);

            const row = targetbtn.closest('tr');
            const sekorUi = row.querySelector(".sekor-column");
            //const statusUi = row.querySelector(".status-column");

            if(isNaN(id)){
                alert("Terjadi Error Internal. Jika Masalah Terus Terjadi Cobalah Muat Ulang Halaman Ini");
                return;
            }

            nilaiPendaftarByPenId(id, sekorUi/*, statusUi*/);
        }
    }
});
