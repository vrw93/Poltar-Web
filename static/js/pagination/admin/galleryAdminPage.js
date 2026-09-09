import eventBus from '/static/js/pagination/eventBus.js';

const galleryListData = new Map();
const galleryListUI = document.getElementById('galleryListPage');
let requestId = 0;


async function eventCallback(event){
    const pageN = event.detail.pageN;
    const offset = (limit * pageN) - limit;
    
    const thisRequestId = ++requestId;

    loadingUI();
    const data = await checkgalleryListDataAvailability(limit, offset, pageN);
    
    if(thisRequestId !== requestId){
        return;
    }
    updateUI(data);
}

function loadingUI(){
    galleryListUI.innerHTML = `
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

function updateUI(datas){
    let galleryRow = '';

    for(const [,data] of Object.entries(datas)){
        galleryRow += `
            <div class="galleryCardContainer">
                <button class="galleryItem"
                data-gallery-description="${data.description}" 
                data-gallery-createdAt="${data.createdAt}"
                >
                    <img src="${data.image}">
                    <div class="galleryCard">
                        <h3>${data.title}</h3>
                        <small>${data.summary}</small>
                    </div>
                </button>
                
                <div class="galleryActionButton">
                    <a href="edit?id=${data.id}" class="secondary-btn">
                        <i class="fa-solid fa-pen"></i>
                        Edit
                    </a>
                    <form
                        method="POST"
                        action="deleteGallery.php"
                        onsubmit="
                            return confirm('Delete This Post?')
                        "
                    >
                        <input type="hidden" name="id" value="${data.id}">
                        <button class="danger-btn" type="submit">
                            <i class="fa-solid fa-trash"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        `;
    }

    galleryListUI.innerHTML = galleryRow;
}

async function checkgalleryListDataAvailability(limit, offset, pageN){
    if(galleryListData.has(pageN)){
        console.log('Cached');
        return galleryListData.get(pageN);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/getGalleryListAPI.php',
            csrfToken,
            {
                offset: offset,
                limit: limit
            }
        );
        if(result.success){
            galleryListData.set(pageN, result.data);
            return result.data;
        }else{
            alert(result.message);
        }
    }catch(err){
        alert('Terjadi Error Internal.');
        console.error(err);
    }
}

eventBus.addEventListener('pageChange', (event) => {
    if(event.detail.target === 'galleryListPage'){
        eventCallback(event);
    }
}); 