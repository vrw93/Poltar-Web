const input = document.getElementById('thumbUpInpt');
const filename = document.getElementById('fileName');
const btn = document.getElementById('upBtn');
const preview = document.getElementById('img');
const delBtn = document.getElementById('delImg');

let previewUrl = null;

btn.addEventListener('click', () => {
    input.click();
});

input.addEventListener("change", () => {
    fileName.textContent =
        input.files.length > 0
            ? input.files[0].name
            : "Belum Ada File";

    const file = input.files[0];

    if(!file) return;

    if(previewUrl){
        URL.revokeObjectURL(previewUrl);
    }

    previewUrl = URL.createObjectURL(file);

    preview.src = previewUrl;
    preview.hidden = false;
    if(delBtn){
        delBtn.hidden = false;
    }
});

if(delBtn){
    delBtn.addEventListener('click', () => {
        input.value = "";

        preview.src = "";
        preview.hidden = true;
        delBtn.hidden = true;
        fileName.textContent = "Belum Ada File";

        if(previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }
    });
}