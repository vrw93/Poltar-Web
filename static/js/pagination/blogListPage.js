import eventBus from '/static/js/pagination/eventBus.js';

const blogListData = new Map();
const blogListUI = document.getElementById('blogListPage');
let requestId = 0;


async function eventCallback(event){
    const pageN = event.detail.pageN;
    const offset = (limit * pageN) - limit;
    
    const thisRequestId = ++requestId;

    loadingUI();
    const data = await checkBlogListDataAvailability(limit, offset, pageN);
    
    if(thisRequestId !== requestId){
        return;
    }
    updateUI(data);
}

function loadingUI(){
    blogListUI.innerHTML = `
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
    let blogRow = '';

    for(const [,data] of Object.entries(datas)){
        let image = '';
        if(data.image){
            image = `<img src="${data.image}">`;
        }

        blogRow += `
            <a href="/Post?id=${data.id}" class="blogContainer">

                ${image}
                
                <h2>${data.title}</h2>
                <small>${data.createdAt}</small>
                <p>
                    ${data.description}
                    <br>
                    <small style="margin: 0px">Baca Selengkapnya</small>
                </p>
            </a>
        `;
    }

    blogListUI.innerHTML = blogRow;
}

async function checkBlogListDataAvailability(limit, offset, pageN){
    if(blogListData.has(pageN)){
        console.log('Cached');
        return blogListData.get(pageN);
    }

    const csrfToken = document.querySelector('meta[name="csrf_token"]').content;
    try{
        const result = await api(
            '/api/getBlogListAPI.php',
            csrfToken,
            {
                offset: offset,
                limit: limit
            }
        );
        if(result.success){
            blogListData.set(pageN, result.data);
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
    if(event.detail.target === 'blogListPage'){
        eventCallback(event);
    }
}); 