import eventBus from '/static/js/pagination/eventBus.js';

function invokeNumBtn(event){
    const parent = event.parentElement;
    const maxPage = parseInt(parent.dataset.maxPage, 10);

    const target = parent.dataset.target;
    const page = parseInt(event.dataset.pageN, 10);
    parent.dataset.currentPage = page;

    invokeEvent(target, page);
    updatePaginationUI(parent, event, page, maxPage);
}

function invokeInput(event, page){
    const parent = event.parentElement;
    const maxPage = parseInt(parent.dataset.maxPage, 10);

    const target = parent.dataset.target;
    parent.dataset.currentPage = page;

    invokeEvent(target, page);
    updatePaginationUI(parent, event, page, maxPage);
}

function invokeNavBtn(event){
    const parent = event.parentElement;
    const modifier = parseInt(event.dataset.mod, 10);

    const target = parent.dataset.target;
    const currentPage = parseInt(parent.dataset.currentPage, 10);
    const maxPage = parseInt(parent.dataset.maxPage, 10);
    let page = 1

    if(modifier > 0){
        if(currentPage >= maxPage){
            page = maxPage;
        }else{
            page = currentPage + modifier
        }
    }else{
        if(currentPage <= 1){
            page = 1;
        }else{
            page = currentPage + modifier;
        }
    }

    const targetBtn = parent.querySelector(`[data-page-n="${page}"]`);
    parent.dataset.currentPage = page;

    invokeEvent(target, page);
    updatePaginationUI(parent, targetBtn, page, maxPage);
}

function updatePaginationUI(parent, targetBtn, pageN, maxPage){
    const LastActiveBtn = parent.querySelector('.active');
    const indicator = parent.querySelector('.indicator');
    let limit = 0;

    if(maxPage <= 5){
        limit = maxPage;
    }else{
        limit = 3;
    }

    if(pageN <= limit || pageN == maxPage){
        LastActiveBtn.classList.remove('active');
        targetBtn.classList.add('active');
    }

    if(pageN > limit && pageN != maxPage){
        indicator.hidden = true;
    }else if(pageN == maxPage){
        indicator.hidden = false;
    }else{
        indicator.hidden = false;
    }
    /*indicator.style.left = `${targetBtn.offsetLeft-2}px`;
    indicator.style.top = `${targetBtn.offsetTop-2}px`;
    indicator.style.width = `${targetBtn.offsetWidth}px`;
    indicator.style.height = `${targetBtn.offsetHeight}px`;*/
}

function invokeEvent(target, pageNumber){
    const changeEvent = new CustomEvent('pageChange',{
        detail: {
            target: target,
            pageN: pageNumber
        }
    });

    eventBus.dispatchEvent(changeEvent);
}

document.addEventListener('click', (event) => {
    const target = event.target;
    const numButton = target.closest('.numberBtn');
    const NavBtn = target.closest('.controlBtn');

    if(numButton){
        invokeNumBtn(numButton);
    }
    if(NavBtn){
        invokeNavBtn(NavBtn);
    }
});

document.querySelectorAll(".pageInput").forEach(input => {
    input.addEventListener('keydown', (event) => {
        if(event.key === 'Enter') {
            const pageN = parseInt(input.value, 10);

            invokeInput(event.target, pageN);
        }
    });
});