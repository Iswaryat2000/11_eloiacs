

function updateTime() {
    const currentTimeElement = document.getElementById('currentTime');
    const currentTime = new Date();
    const hours = currentTime.getHours().toString().padStart(2, '0');
    const minutes = currentTime.getMinutes().toString().padStart(2, '0');
    const seconds = currentTime.getSeconds().toString().padStart(2, '0');

    const formattedTime = `, ${hours}:${minutes}:${seconds}`;
    currentTimeElement.textContent = formattedTime;
    if (
        parseInt(hours) > 21 || (parseInt(hours) === 21 && parseInt(minutes) >= 55)
    ) {
        const clockInButton = document.getElementById('clock-in');
        const clockOutButton = document.getElementById('clock-out');
        const autoClockOutFlag = localStorage.getItem('autoClockOut');

        if (!autoClockOutFlag) {
            if (clockInButton.style.display !== 'none') {
                clockInButton.click();
            }
            if (clockOutButton.style.display !== 'none') {
                clockOutButton.click();
            }
            localStorage.setItem('autoClockOut', 'true');
        }
    } else {
        localStorage.removeItem('autoClockOut');
    }
}
setInterval(updateTime, 1000);
updateTime();

function calculatependingprodqc(){

    // Add event listeners for "PRODUCTION" projects
    document.addEventListener("DOMContentLoaded", function () {
        const prodRows = document.querySelectorAll(".prod-file-row");
    
        prodRows.forEach(function (row) {
            const completedInput = row.querySelector(".completed-input");
            const targetInput = row.querySelector(".target-input");
            const pendingInput = row.querySelector(".pending-input");
    
            function calculatePending() {
                const completed = parseInt(completedInput.value) || 0;
                const target = parseInt(targetInput.value) || 0;
                const pending = target - completed;
                pendingInput.value = pending;
            }
    
            completedInput.addEventListener("input", calculatePending);
            targetInput.addEventListener("input", calculatePending);
    
            // Calculate pending initially
            calculatePending();
        });
    
        // Add event listeners for "QC" projects
        const qcRows = document.querySelectorAll(".qc-file-row");
    
        qcRows.forEach(function (row) {
            const completedInputQC = row.querySelector(".completed-input_qc");
            const targetInputQC = row.querySelector(".target-input_qc");
            const pendingInputQC = row.querySelector(".pending-input_qc");
    
            function calculatePendingQC() {
                const completedQC = parseInt(completedInputQC.value) || 0;
                const targetQC = parseInt(targetInputQC.value) || 0;
                const pendingQC = targetQC - completedQC;
                pendingInputQC.value = pendingQC;
            }
    
            completedInputQC.addEventListener("input", calculatePendingQC);
            targetInputQC.addEventListener("input", calculatePendingQC);
    
            // Calculate pending initially for QC projects
            calculatePendingQC();
        });
    });
        }    calculatependingprodqc();
    
    