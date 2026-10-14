const htmlTag = document.querySelector('html');
const themeBtn = document.getElementById('themeSwitchBtn');

function clickEventHandler(){
    if(htmlTag.dataset.theme === 'light'){
        darkMode();
    }else{
        lightMode();
    }
}

function saveData(value){
    if(!value) return;

    localStorage.setItem('theme', value);
}

function loadData(){
    const theme = localStorage.getItem('theme') ?? 'dark';

    if(theme === 'dark'){
        darkMode();
    }else if(theme === 'light'){
        lightMode()
    }
}

function darkMode(){
    htmlTag.dataset.theme = 'dark';
    saveData('dark');
    themeBtn.innerHTML = '<i class="fa-solid fa-lightbulb"></i> Light';
}

function lightMode(){
    htmlTag.dataset.theme = 'light';
    saveData('light')
    themeBtn.innerHTML = '<i class="fa-solid fa-moon"></i> Dark';
}

loadData();

themeBtn.addEventListener('click', (event) => {
    clickEventHandler();
});