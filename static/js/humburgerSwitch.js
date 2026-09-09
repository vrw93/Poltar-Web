const header = document.getElementById('headerNav');
const humburger = document.getElementById('humburger');
const userMenu = document.getElementById('userMenu');
const userBtn = document.getElementById('userBtn');

humburger.addEventListener('click', () =>{
    header.classList.toggle("active");
    if(userMenu.classList.contains('active')){
        userMenu.classList.remove('active');
    }
});

if(userBtn !== null){
    userBtn.addEventListener('click', () => {
        userMenu.classList.toggle('active');
        if(header.classList.contains('active')){
            header.classList.remove('active');
        }
    });
}
