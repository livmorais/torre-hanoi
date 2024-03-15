window.onload = function() {
    let selectedDisk = null;

    document.querySelectorAll('.disk').forEach((disk) => {
        disk.addEventListener('dragstart', (event) => {
            if (disk !== disk.parentElement.lastElementChild) {
                event.preventDefault();  
            } else {
                selectedDisk = event.target;
            }
        });
    });

    document.querySelectorAll('.tower').forEach((tower) => {
        tower.addEventListener('dragover', (event) => {
            event.preventDefault();  
        });


        tower.addEventListener('drop', (event) => {
            event.preventDefault();  
            if (isValidMove(selectedDisk, event.target)) {
                event.target.appendChild(selectedDisk);
                incrementarMovimentos();
            }
        });
         
        document.querySelectorAll('.disk').forEach((disk) => {
            disk.addEventListener('click', (event) => {
                selectedDisk = event.target;
            });
        });
        
    });
}

function incrementarMovimentos() {
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'incrementMove=true'
    })
    .then(response => response.text())
    .then(data => {
        document.querySelector('#movimentosRealizados').textContent = 'Movimentos realizados: ' + data;
    });
}

function isValidMove(disk, target) {
    if (target.childElementCount === 0) {
        return true;
    }

    const topDisk = target.lastElementChild;
    const movingDiskSize = parseInt(disk.id.split('disco')[1].split('torre')[0]);
    const topDiskSize = parseInt(topDisk.id.split('disco')[1].split('torre')[0]);

    if (movingDiskSize < topDiskSize) {
        return true;
    }

    return false;
}

let moveNumber = 0;  

function hanoi(n, origem, destino, auxiliar) {
    if (n > 0) {
        hanoi(n - 1, origem, auxiliar, destino);

        setTimeout(function() {
            moveDisk(origem, destino);
        }, moveNumber * 1000);  

        moveNumber++; 

        hanoi(n - 1, auxiliar, destino, origem);
    }
}

function moveDisk(origem, destino) {
    let towerOrigin = document.getElementById(origem);
    let towerDestination = document.getElementById(destino);

    let disk = towerOrigin.lastElementChild;

    if (isValidMove(disk, towerDestination)) {
        towerOrigin.removeChild(disk);

        towerDestination.appendChild(disk);

        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'incrementMove=true'
        })
        .then(response => response.text())
        .then(data => {
            document.querySelector('#movimentosRealizados').textContent = 'Movimentos realizados: ' + data;
        });
    }
}
document.querySelector('input[name="simulateButton"]').addEventListener('click', function(event) {
    event.preventDefault();  

    let diskNumber = document.querySelector('input[name="diskNumber"]').value;

    hanoi(diskNumber, 'torre1', 'torre3', 'torre2');
});