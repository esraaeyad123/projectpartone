$(document).ready(function() {
    // Load header and navbar
    $("#header-placeholder").load("/html/General/header.html");
    $("#navbar-placeholder").load("/html/General/navbar.html");
});

// User-provided SweetAlert2 functions
function showAlert(message, type) {
    Swal.fire({
        title: type === 'success' ? 'Success!' : (type === 'error' ? 'Error!' : 'Warning!'),
        text: message,
        icon: type,
        confirmButtonText: 'OK'
    });
}

function showConfirm(message, callback, title = 'Confirm', confirmButtonText = 'Yes', cancelButtonText = 'No') {
    Swal.fire({
        title: title,
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: confirmButtonText,
        cancelButtonText: cancelButtonText
    }).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
}

// JavaScript Functions for the buttons using SweetAlert2
function goBack() {
    window.history.back();
}

function saveReport() {
    showAlert('Generating PDF report...', 'info');
    const reportSection = document.getElementById('report-details-section');

    if (!reportSection) {
        showAlert('Report section not found!', 'error');
        return;
    }

    const options = {
        margin: 0.2,
        filename: 'Report-' + new Date().toISOString().slice(0,10) + '.pdf',
        image: { type: 'jpeg', quality: 1 },
        html2canvas: { scale: 3, useCORS: true, logging: false },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    // تحميل كل الخطوط والصور قبل التحويل
    document.fonts.ready.then(() => {
        html2pdf().from(reportSection).set(options).save().then(() => {
            showAlert('Report saved successfully as PDF!', 'success');
        }).catch(() => {
            showAlert('Error generating PDF file.', 'error');
        });
    });
}

function printReport() {
    window.print();
}

function submitReport() {
    showConfirm('Are you sure you want to submit this report for approval?', () => {
        showAlert('Report submitted for approval successfully.', 'success');
        // Here, you would send an AJAX request to your backend to change the report's status.
    });
}

function checkReport() {
    showConfirm('Are you sure you want to mark this report as checked?', () => {
        showAlert('Report marked as checked.', 'success');
        // Send an AJAX request to the backend.
    });
}

function approveReport() {
    showConfirm('Are you sure you want to approve this report?', () => {
        showAlert('Report approved successfully.', 'success');
        // Send an AJAX request to the backend.
    });
}

function declineReport() {
    Swal.fire({
        title: 'Decline Report',
        text: 'Please enter a reason for declining this report:',
        input: 'textarea',
        inputPlaceholder: 'Enter your comment here...',
        showCancelButton: true,
        confirmButtonText: 'Decline',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            if (result.value && result.value.trim() !== "") {
                showAlert('Report declined with comment: ' + result.value, 'error');
                // Send an AJAX request to the backend with the comment.
            } else {
                showAlert('Decline cancelled. Please provide a comment if you wish to decline.', 'warning');
            }
        }
    });
}

function removeReport() {
    showConfirm('WARNING: Are you sure you want to permanently remove this report?', () => {
        showAlert('Report removed successfully.', 'success');
        // Send an AJAX request to the backend to delete the report.
        // Redirect the user to another page after deletion.
    }, 'Are you sure?', 'Yes, remove it!');
}


function passReport() {
    showConfirm('Are you sure you want to pass this report?', () => {
        showAlert('Report marked as passed.', 'success');
        // Send an AJAX request to the backend.
    });
}

function failReport() {
    showConfirm('Are you sure you want to fail this report?', () => {
        showAlert('Report marked as failed.', 'error');
        // Send an AJAX
           });
}




