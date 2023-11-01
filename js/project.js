function toggleAddClientForm() {
    const addClientFormContainer = document.getElementById('addClientFormContainer');
    const formOneForClient = document.getElementById('form_one_for_client'); // Get the form element
    const addButtonBlur = document.getElementById('addButton_blur'); // Get the "ADD NEW CLIENTS" button

    if (addClientFormContainer.style.display === 'none' || addClientFormContainer.style.display === '') {
        addClientFormContainer.style.display = 'block';
        formOneForClient.classList.remove('blur'); // Remove the blur class
        addButtonBlur.style.display = 'none'; // Hide the button
    } else {
        addClientFormContainer.style.display = 'none';
        formOneForClient.classList.add('blur'); // Add the blur class
        addButtonBlur.style.display = 'block'; // Show the button
    }
}


function toggleManualForm() {
        var form = document.getElementById("manual_form_details");
        if (form.style.display === "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }


// Function to calculate TOTAL DAYS and LOP DAYS
function calculateDays() {
    const receivedDate = new Date(document.getElementById("receiveddate").value);
    const dueDate = new Date(document.getElementById("ourtat").value);
    const vendortat = new Date(document.getElementById("duedate").value);
    const currentDate = new Date();

    const totalDays = Math.floor((vendortat - receivedDate) / (1000 * 60 * 60 * 24));
    const lopDays = currentDate > dueDate ? Math.floor((currentDate - dueDate) / (1000 * 60 * 60 * 24)) : 0;

    document.getElementById("totaldays").value = totalDays;
    document.getElementById("lopdays").value = lopDays;
}

// Add event listeners to update values when the input fields change
document.getElementById("receiveddate").addEventListener("change", calculateDays);
document.getElementById("ourtat").addEventListener("change", calculateDays);
document.getElementById("duedate").addEventListener("change", calculateDays); // Add this line

// Initial calculation when the page loads
calculateDays();


function uploadButton(){
     const clearFileIcon = document.getElementById('clear-file');
     const fileInput = document.getElementById('file-upload');
     const fileName = document.getElementById('file-name');
     const uploadButton = document.getElementById('upload-button');
     const uploadlabel = document.getElementById('pro_pm_import');
     clearFileIcon.addEventListener('click', () => {
         fileInput.value = '';
         fileName.style.display = 'none';
         uploadButton.style.display = 'none';
         clearFileIcon.style.display = 'none';
         uploadlabel.style.display="block";    
    });
function showUploadButton() {
    const selectedFile = fileInput.files[0];
    if (selectedFile) {
        fileName.innerText = selectedFile.name;
        fileName.style.display = 'inline';
        uploadButton.style.display = 'block';
        clearFileIcon.style.display = 'inline';
        uploadlabel.style.display="none";
    }}
     fileInput.addEventListener('change', showUploadButton);
}
uploadButton(); 


$(document).ready(function () {
    // Handle the click event on department options
    $(".department-option").click(function () {
        var selectedValue = $(this).text();
        $("#department1").val(selectedValue);
    });
});
      

function clientdetail(){
    document.addEventListener('DOMContentLoaded', function () {
        const clientNameInput = document.getElementById('clientname');
        const contactPersonInput = document.getElementById('contactperson');
        const departmentInput = document.getElementById('department');
        const batchNumberInput = document.getElementById('batchnumber');         
        clientNameInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent form submission
                const clientName = clientNameInput.value.trim();
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `controllers/form_controller.php?clientname=${clientName}`, true);        
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);        
                            if (response.success) {
                                contactPersonInput.value = response.contactperson;
                                departmentInput.value = response.department;
                                const batchNumber = generateBatchNumber();
                                batchNumberInput.value = batchNumber;
                            } else {
                                contactPersonInput.value = '';
                                departmentInput.value = '';
                                alert('Client not found.');
                            }
                        } catch (error) {
                            console.error('Error parsing JSON response:', error);
                        }
                    }
                };        
                xhr.send();
            }
        });
        const batchNumber = generateBatchNumber();
        batchNumberInput.value = batchNumber;
    });
}clientdetail();

$(document).ready(function () {
    // Handle the click event on department options
    $(".department-option").click(function () {
        var selectedValue = $(this).text();
        $("#department1").val(selectedValue);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const clientNameInput = document.getElementById('clientname');
    const contactPersonInput = document.getElementById('contactperson');
    const departmentInput = document.getElementById('department');
    const batchNumberInput = document.getElementById('batchnumber'); 

    clientNameInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission
            const clientName = clientNameInput.value.trim();

            // Make an AJAX request to fetch client information
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `fetch_client_info.php?clientname=${clientName}`, true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            // Update the contact person and department inputs
                            contactPersonInput.value = response.contactperson;
                            departmentInput.value = response.department;
                            // Generate and populate the batch number input
                            const batchNumber = generateBatchNumber();
                            batchNumberInput.value = batchNumber;
                        } else {
                            // Handle errors or display a message
                            contactPersonInput.value = '';
                            departmentInput.value = '';
                            alert('Client not found.');
                        }
                    } catch (error) {
                        console.error('Error parsing JSON response:', error);
                    }
                }
            };

            xhr.send();
        }
    });

    

    // Automatically generate and populate the batch number on page load
    const batchNumber = generateBatchNumber();
    batchNumberInput.value = batchNumber;
});

