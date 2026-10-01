const detailData = new Map();

function updateDetailData(newData, jabatanId){
    if(!detailData.has(jabatanId)) return;

    detailData.set(jabatanId, newData);
}

async function checkDetailDataAvaibility(id){
    if(detailData.has(id)){
        console.log('cached');
        return detailData.get(id);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/admin/pendaftaran/getJabatanDetailByIdAPI.php',
            csrfToken,
            {
                id: id
            }
        );
        if(result.success){
            detailData.set(id, result.data);
            return result.data;
        }else{
            alert(result.message);
        }
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }
}

export { updateDetailData, checkDetailDataAvaibility };