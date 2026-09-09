const tbody = document.querySelector(".tbody-class");

function renderLeaderboard(jabatanId){
    const data = leaderboard[jabatanId];

    tbody.innerHTML = "";

    data.sort((a, b) => {
        if (a.statusCode === 'accepted_manual'){
            a.statusCode = 'accepted';
        }
        if (b.statusCode === 'accepted_manual'){
            b.statusCode = 'accepted';
        }
        if (a.statusCode === 'accepted' && b.statusCode !== 'accepted') {
            return -1;
        }
        if (b.statusCode === 'accepted' && a.statusCode !== 'accepted'){
            return 1;
        }
        if (a.statusCode !== 'accepted' && b.statusCode === 'evaluated'){
            return 1;
        }
        if (b.statusCode !== 'accepted' && a.statusCode === 'evaluated'){
            return -1;
        }
        if (a.statusCode !== 'accepted' && b.statusCode === 'allocated'){
            return 1;
        }
        if (b.statusCode !== 'accepted' && a.statusCode === 'allocated'){
            return -1;
        }
        if (a.statusCode === 'rejected' && b.statusCode === 'displaced'){
            return 1;
        }
        if (b.statusCode === 'rejected' && a.statusCode === 'displaced'){
            return -1;
        }
        
        return b.sekor - a.sekor;
    });
    
    //console.log(data);

    let displayRank = 1;
    data.forEach(item => {
        const rank =
        item.statusCode === "accepted" || item.statusCode === 'evaluated' || item.statusCode === 'allocated'
            ? displayRank++
            : "-";

        item.rank = rank;

        if(window.innerWidth < 768){
            mobileTable(item);
        }else{
            desktopTable(item);
        }
    });
}

function mobileTable(item){
    let selfNameClass = "";
    let statusClass = 'class="tableStatus accepted"';

    if(item.user_id === userId){
        selfNameClass = 'class="selfNameClass"';
    }

    if(item.statusCode === 'rejected'){
        statusClass = 'class="tableStatus reject"';
    }
    if(item.statusCode === 'displaced'){
        statusClass = 'class="tableStatus displaced"';
    }
    tbody.innerHTML += `
        <tr ${selfNameClass}>
            <td>${item.rank}</td>
            <td>
            <p>${item.name}</p>
            <div class="tableDetail">
                <small class="tableKelas">${item.kelas}</small>
                <small class="tableSekor">${item.sekor ?? 'Belum Dinilai'}</small>
                <div>
                <small ${statusClass}>${item.status}</small>
                </div>
            </div>
            </td>
        </tr>
    `;
    selfNameClass = "";
}

function desktopTable(item){
    let selfNameClass = "";
    
    if(item.user_id == userId){
        selfNameClass = 'class="selfNameClass"';
    }
    tbody.innerHTML += `
        <tr ${selfNameClass}>
            <td>${item.rank}</td>
            <td>${item.name}</td>
            <td>${item.kelas}</td>
            <td>${item.sekor ?? 'Belum Dinilai'}</td>
            <td>${item.status}</td>
        </tr>
    `;
    selfNameClass = "";
}

function updateStatusAndRank(jabatanId, userId){
    const data = leaderboard[jabatanId]?.find(item => item.user_id == userId);

    const statusUi = document.getElementById("status");
    const rankUi = document.getElementById("rank");

    statusUi.innerHTML = `<b>Status:</b> ${data.status}`;
    rankUi.innerHTML = `<b>Peringkat:</b> ${data.rank}`;
}

renderLeaderboard(firstId);
updateStatusAndRank(firstId, userId);

document.querySelectorAll(".jbtn-btn")
.forEach(button => {

    button.addEventListener("click", () => {

        renderLeaderboard(button.dataset.jabatanId);
        updateStatusAndRank(button.dataset.jabatanId, userId);
    });

});