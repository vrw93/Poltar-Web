const accountDetailForm = document.getElementById('accountDetail');

async function updateAccountDetail(event) {
    event.preventDefault();

    const data = new FormData(accountDetailForm);
    const objectData = Object.fromEntries(data.entries());

    if(objectData.email !== null || objectData.username !== null){
        const csrfToken = document.querySelector('meta[name="csrf_token"]').content;

        if(objectData.username === oldUsername
            && objectData.email === oldEmail
        ){
            alert('Tidak Ada Perubahan Data');
            return;
        }

        try{
            const result = await api(
                '/api/updateAccountDetailAPI.php',
                csrfToken,
                {
                    email: objectData.email,
                    username: objectData.username,
                    id: id
                }
            );
            if(result.success){
                alert(result.message);
                oldUsername = objectData.username;
                oldEmail = objectData.email;
            }else{
                alert(result.message);
            }
        }catch(err){
            alert('Terjadi Error Internal: ' + err);
            console.log(err);
        }
    }else{
        alert('Data Tidak Lengkap');
    }
}

accountDetailForm.addEventListener('submit', updateAccountDetail);