const biodataForm = document.getElementById('biodata');

async function updatePassword(event){
    event.preventDefault();

    const data = new FormData(biodataForm);
    const objectData = Object.fromEntries(data.entries());

    if(objectData.name !== null || 
        objectData.noAbsen !== null || 
        objectData.kelas !== null)
    {
        const csrfToken = document.querySelector('meta[name="csrf_token"]').content;

        if(objectData.name === oldName &&
            objectData.noAbsen === oldNoAbsen &&
            objectData.kelas === oldKelas){
            alert('Tidak ada perubahan data');
            return;
        }

        const noAbsen = parseInt(objectData.noAbsen, 10);
        const kelasId = parseInt(objectData.kelas, 10);

        try{
            const result = await api(
                '/api/changeBiodataAPI.php',
                csrfToken,
                {
                    name: objectData.name,
                    noAbsen: noAbsen,
                    kelasId: kelasId,
                }
            );
            if(result.success){
                alert(result.message);
            }else{
                alert(result.message);
            }
        }catch(err){
            alert('Terjadi Error Internal: ' + err);
            console.log(err);
        }
    }else{
        alert('Data Tidak Lengkap');
        return;
    }
}

biodataForm.addEventListener('submit', updatePassword);