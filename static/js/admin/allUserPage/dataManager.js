const usersDetailData = new Map();

async function checkDetailDataAvaibility(userId){
    if(usersDetailData.has(userId)){
        console.log('cached');
        return usersDetailData.get(userId);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/getUserDetailDataAPI.php',
            csrfToken,
            {
                userId: userId
            }
        );
        if(result.success){
            usersDetailData.set(userId, result.data);
            return result.data;
        }else{
            alert(result.message);
        }
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }
}

export default checkDetailDataAvaibility;