const passwordForm = document.getElementById('password');

async function updatePassword(event){
    event.preventDefault();

    const data = new FormData(passwordForm);
    const objectData = Object.fromEntries(data.entries());

    if(objectData.oldPassword !== null || 
        objectData.newPassword !== null || 
        objectData.confirmPassword !== null)
    {
        const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
        
        if(objectData.newPassword !== objectData.confirmPassword){
            alert('Password baru tidak sama dengan password konfirmasi');
            return;
        }

        try{
            const result = await api(
                '/api/changePasswordAPI.php',
                csrfToken,
                {
                    oldPassword: objectData.oldPassword,
                    newPassword: objectData.newPassword,
                    confirmPassword: objectData.confirmPassword,
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

passwordForm.addEventListener('submit', updatePassword);