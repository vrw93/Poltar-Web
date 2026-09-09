function toggleGalleryView(data){
    const view = document.getElementById("galleryView");

    if(!view.open){
        view.showModal();
        requestAnimationFrame(() => {
            view.classList.add("show");
        });

        view.querySelector('p').textContent = data.description;
        view.querySelector('small').textContent = data.createdAt;
        view.querySelector('h3').textContent = data.title;
        view.querySelector('img').src = data.image;
    }else{
        view.classList.remove("show");

        view.addEventListener("transitionend", () => {
            view.close();
        }, { once: true });
    }

    console.log("toggle", view.className);
}

document.addEventListener('click', function(event){
    const btn = event.target.closest('.galleryItem');
    if (btn){

        const data = {
            description: btn.getAttribute('data-gallery-description'),
            title: btn.querySelector('h3').textContent,
            createdAt: btn.getAttribute('data-gallery-createdAt'),
            image: btn.querySelector('img').src
        };

        toggleGalleryView(data);
    }
});