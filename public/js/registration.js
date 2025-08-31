// Function to open the first modal (registration category) automatically on page load
function openRegistrationCategoryModal() {
    document.getElementById('registration-category-modal').style.display = 'flex';
}

// Function to close any modal by its ID
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Function to handle the category selection and show the main form and tab
function checkCategorySelection(category) {
    if (category === 'crushing-testing') {
        closeModal('registration-category-modal');
        document.getElementById('registration-tab-header').style.display = 'flex';
        document.getElementById('registration-content').style.display = 'block';
        document.getElementById('form-footer-buttons').style.display = 'flex';
        resetAndDisableForm();
    } else {
        // ... Other category logic ...
    }
}

// Function to close only the main content and the tab header
function closeRegistrationTab() {
   if (event) {
        event.preventDefault();
    }
    document.getElementById('registration-content').style.display = 'none';
    document.getElementById('registration-tab-header').style.display = 'none';
    document.getElementById('form-footer-buttons').style.display = 'none';
    resetAndDisableForm();
}

// Function to handle sub-tab switching (Corrected)
function showSubTab(tabId, event) {
    // Remove active class from all sub-tab contents
    document.querySelectorAll('.sub-tab-content').forEach(content => {
        content.classList.remove('active-sub-tab-content');
    });
    // Remove active class from all sub-tab headers
    document.querySelectorAll('.sub-tab').forEach(tab => {
        tab.classList.remove('active-sub-tab');
    });
    // Add active class to the selected sub-tab content
    document.getElementById(tabId).classList.add('active-sub-tab-content');
    // Add active class to the clicked sub-tab header
    if (event && event.target) {
        event.target.classList.add('active-sub-tab');
    }
}

// Variable to hold the current sequential number.
let currentOrderNumber = 1;
let isNewMode = false; // A new variable to track the form state

// Function to generate a sequential code with a specific prefix
function generateSequentialCode(prefix) {
    const year = '25';
    const paddedNumber = String(currentOrderNumber).padStart(5, '0');
    currentOrderNumber++;
    return `${prefix}-${year}-${paddedNumber}`;
}

// Function to enable the form when "New" is clicked
function enableForm() {
    if (event) {
        event.preventDefault();
    }
    isNewMode = true;
    var formElements = document.querySelectorAll('#registration-content input, #registration-content select, #registration-content textarea');
    formElements.forEach(function(element) {
        element.removeAttribute('disabled');
    });
    document.getElementById('save-btn').removeAttribute('disabled');
    document.getElementById('save-close-btn').removeAttribute('disabled');
    document.getElementById('actions-btn').removeAttribute('disabled');
    const orderNoField = document.getElementById('order-no');
    orderNoField.removeAttribute('disabled');
    const newCodeBase = generateSequentialCode('AAM-CO');
    orderNoField.value = newCodeBase;
    orderNoField.setAttribute('readonly', 'true');
    orderNoField.classList.add('readonly-input');
    const sampleNoField = document.getElementById('sample-no');
    sampleNoField.removeAttribute('disabled');
    const newSampleCode = newCodeBase.replace('AAM-CO', 'AAM-CS');
    sampleNoField.value = newSampleCode;
    sampleNoField.setAttribute('readonly', 'true');
    sampleNoField.classList.add('readonly-input');
    const orderDateField = document.getElementById('order-date');
    orderDateField.removeAttribute('disabled');
    const now = new Date();
    const formattedDate = `${now.getFullYear().toString()}-${(now.getMonth() + 1).toString().padStart(2, '0')}-${now.getDate().toString().padStart(2, '0')}`;
    const formattedTime = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
    orderDateField.value = `${formattedDate}T${formattedTime}`;
    orderDateField.setAttribute('readonly', 'true');
    orderDateField.classList.add('readonly-input');
    const projectCodeField = document.getElementById('project-code');
    projectCodeField.removeAttribute('disabled');
    projectCodeField.value = '';
    const confirmationField = document.getElementById('confirmation');
    confirmationField.removeAttribute('disabled');
    confirmationField.value = '';
    const sampleTypeField = document.getElementById('sample-type');
    sampleTypeField.removeAttribute('disabled');
    sampleTypeField.value = '';
    const paymentTermsField = document.getElementById('payment-terms');
    paymentTermsField.removeAttribute('disabled');
    paymentTermsField.value = '';
    const nominalSizeField = document.getElementById('nominal-size');
    nominalSizeField.removeAttribute('disabled');
    nominalSizeField.value = '';
    const receivedAtField = document.getElementById('received-at');
    receivedAtField.removeAttribute('disabled');
    receivedAtField.value = `${formattedDate}T${formattedTime}`;
    receivedAtField.setAttribute('readonly', 'true');
    const sampledAtField = document.getElementById('sampled-at');
    sampledAtField.removeAttribute('disabled');
    sampledAtField.value = `${formattedDate}T${formattedTime}`;
    sampledAtField.setAttribute('readonly', 'true');
    const castedAtField = document.getElementById('casted-at');
    castedAtField.removeAttribute('disabled');
    castedAtField.value = `${formattedDate}T${formattedTime}`;
    castedAtField.setAttribute('readonly', 'true');
    const dropdownIcons = document.querySelectorAll('[data-onclick]');
    
    
   dropdownIcons.forEach(icon => {
 const functionCall = icon.getAttribute('data-onclick');
 if (functionCall) {
 icon.setAttribute('onclick', functionCall);
 icon.removeAttribute('data-onclick');
 icon.style.pointerEvents = 'auto';
icon.style.cursor = 'pointer';
}
});
    openProjectCodeDropdown();
    projectCodeField.focus();

// يتم تفعيلها عند النقر على "New".
    const testTableElements = document.querySelectorAll('#test-table-body input, #test-table-body .icon-btn');
    testTableElements.forEach(element => {
        element.removeAttribute('disabled');
        element.style.pointerEvents = 'auto'; 
        element.style.cursor = 'pointer'; 
    });
}

// ... (باقي كود enableForm() هنا) ...


function openLabDropdown() {
    const dropdown = document.getElementById('lab-dropdown');
    dropdown.style.setProperty('display', 'block', 'important');
}

function selectLab(locationName, department) {
    const labField = document.getElementById('lab');
    labField.value = `${locationName} (${department})`;
    document.getElementById('lab-dropdown').style.display = 'none';
}

// Function to open the Project dropdown
function openProjectCodeDropdown() {
    const dropdown = document.getElementById('project-code-dropdown');
    dropdown.style.setProperty('display', 'block', 'important');
}

// Function to select a value from the Project dropdown
function selectProjectCode(code, projectNo, projectName, customerName) {
    document.getElementById('project-code').value = code;
    document.getElementById('project-no').value = projectNo;
    document.getElementById('project').value = projectName;
    document.getElementById('customer').value = customerName;
    document.getElementById('project-code-dropdown').style.display = 'none';
    document.querySelector('.project-code-icons .fa-search').style.display = 'none';
    document.querySelector('.project-code-icons .fa-check').style.display = 'none';
    document.querySelector('.project-code-icons .icon-btn-confirmation').style.display = 'block';
}

// Function to apply the selected Confirmation value
function applySelectedConfirmation() {
   if (event) {
        event.preventDefault();
    }
    if (selectedConfirmationRow) {
        const description = selectedConfirmationRow.cells[1].innerText;
        const confDate = selectedConfirmationRow.cells[3].innerText;
        const confirmationText = `${description} - ${confDate}`;
        const confirmationField = document.getElementById('confirmation');
        confirmationField.value = confirmationText;
        confirmationField.setAttribute('readonly', 'readonly');
        confirmationField.classList.add('readonly-input');
        alert("Confirmation applied successfully!");
        closeModal('confirmation-selection-modal');
    } else {
        alert("Please select an item first.");
    }
}

// Function to open the Sample Type dropdown
function openSampleTypeDropdown() {
    if (isNewMode) {
        document.querySelectorAll('.custom-dropdown-sample-type').forEach(dropdown => {
            dropdown.style.display = 'none';
        });
        const dropdown = document.getElementById('sample-type-dropdown');
        dropdown.style.display = 'block';
    }
}

// Function to select a value from the Sample Type dropdown
function selectSampleType(value) {
    document.getElementById('sample-type').value = value;
    document.getElementById('sample-type-dropdown').style.display = 'none';
}

// Function to open the Payment Terms dropdown
function openPaymentTermsDropdown() {
    if (isNewMode) {
        document.querySelectorAll('.custom-dropdown-payment-terms').forEach(dropdown => {
            dropdown.style.display = 'none';
        });
        const dropdown = document.getElementById('payment-terms-dropdown');
        dropdown.style.display = 'block';
    }
}

// Function to select a value from the Payment Terms dropdown
function selectPaymentTerms(value) {
    document.getElementById('payment-terms').value = value;
    document.getElementById('payment-terms-dropdown').style.display = 'none';
}

// General function to close dropdowns when clicking outside
document.addEventListener('click', (event) => {
    const sampleTypeDropdown = document.getElementById('sample-type-dropdown');
    const sampleTypeInputContainer = document.querySelector('#sample-type').closest('.input-with-icon');
    if (sampleTypeDropdown && sampleTypeInputContainer && !sampleTypeInputContainer.contains(event.target)) {
        sampleTypeDropdown.style.display = 'none';
    }
    const paymentTermsDropdown = document.getElementById('payment-terms-dropdown');
    const paymentTermsInputContainer = document.querySelector('#payment-terms').closest('.input-with-icon');
    if (paymentTermsDropdown && paymentTermsInputContainer && !paymentTermsInputContainer.contains(event.target)) {
        paymentTermsDropdown.style.display = 'none';
    }
});

// Variable to store the selected row
let selectedConfirmationRow = null;

// Function to open the "Select from Confirmation" modal
function openConfirmationSelectionModal() {
  
    const confirmationField = document.getElementById('confirmation');
    if (!confirmationField.disabled) {
        document.getElementById('confirmation-selection-modal').style.display = 'flex';
    }
}

// Function to select a single row from the table
function selectRow(row) {
    const checkboxes = document.querySelectorAll('#confirmation-table-body .confirmation-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
        checkbox.closest('tr').classList.remove('selected-row');
    });
    const checkbox = row.querySelector('.confirmation-checkbox');
    checkbox.checked = true;
    row.classList.add('selected-row');
    selectedConfirmationRow = row;
}

// Function to search the Confirmation table
function searchConfirmationTable() {
    const searchText = document.getElementById('confirmation-search-input').value.toLowerCase();
    const tableBody = document.getElementById('confirmation-table-body');
    const rows = tableBody.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;
        const cDescription = cells[1].innerText.toLowerCase();
        const cStId = cells[4].innerText.toLowerCase();
        if (cDescription.includes(searchText) || cStId.includes(searchText)) {
            found = true;
        }
        if (found) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    }
}

// Function to create and show the confirmation prompt modal
function showConfirmationPrompt() {
    const modalHtml = `
        <div id="confirmation-prompt-modal" class="modal-overlay">
            <div class="modal-content small-modal">
                <div class="modal-header">
                    <h3>Confirm Selection</h3>
                    <span class="close-btn" onclick="closeConfirmationPrompt()">&times;</span>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to apply this confirmation?</p>
                    <div class="modal-controls">
                        <button class="btn btn-success" onclick="confirmSelection(true)">Yes</button>
                        <button class="btn btn-danger" onclick="confirmSelection(false)">No</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);
}

// Function to close the confirmation prompt modal
function closeConfirmationPrompt() {
    const modal = document.getElementById('confirmation-prompt-modal');
    if (modal) {
        modal.remove();
    }
}

// Function to confirm or cancel the selection
function confirmSelection(isConfirmed) {
    if (isConfirmed && selectedConfirmationRow) {
        const description = selectedConfirmationRow.children[1].innerText;
        const confDate = selectedConfirmationRow.children[3].innerText;
        const confirmationText = `${description} - ${confDate}`;
        const confirmationField = document.getElementById('confirmation');
        confirmationField.value = confirmationText;
        confirmationField.setAttribute('readonly', 'readonly');
        confirmationField.classList.add('readonly-input');
        alert("Confirmation applied successfully!");
    } else {
        alert("Selection was not confirmed or no item was selected.");
    }
    closeConfirmationPrompt();
}

// Function to open the Nominal Size dropdown
function openNominalSizeDropdown() {
    // Make sure the form is in "New" mode before opening the dropdown
    if (isNewMode) {
        document.querySelectorAll('.custom-dropdown-nominal-size').forEach(dropdown => {
            dropdown.style.display = 'none';
        });
        const dropdown = document.getElementById('nominal-size-dropdown');
        dropdown.style.display = 'block';
    }
}

// Function to select a value from the Nominal Size dropdown
function selectNominalSize(value) {
    document.getElementById('nominal-size').value = value;
    document.getElementById('nominal-size-dropdown').style.display = 'none';
}

// Variable to store the currently active input field for test table dropdowns
let currentTargetInput = null;



document.addEventListener('DOMContentLoaded', () => {
    // Add event listener for confirmation table search once on page load
    const searchInput = document.getElementById('confirmation-search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', searchConfirmationTable);
    }
    
    // Updated HTML for a new test row
    // دالة إنشاء صف جديد
function createTestRowHtml() {
    return `
        <tr>
            <td>
                <div class="input-with-icon">
                    <input type="text" class="form-control test-method-input" >
                    <i class="fas fa-caret-down icon-btn" onclick="openDropdown(event, 'test-method-dropdown')"></i>
                </div>
            </td>
            <td>
                <div class="number-input-wrapper">
                    <input type="number" class="form-control r-age-input" value="0" />
                    <div class="number-controls">
                        <button type="button" class="btn-increment">+</button>
                        <button type="button" class="btn-decrement">-</button>
                    </div>
                </div>
            </td>
            <td>
                <div class="number-input-wrapper">
                    <input type="number" class="form-control a-age-input" value="0" />
                    <div class="number-controls">
                        <button type="button" class="btn-increment">+</button>
                        <button type="button" class="btn-decrement">-</button>
                    </div>
                </div>
            </td>
            <td>
                <div class="number-input-wrapper">
                    <input type="number" class="form-control price-input" value="0" />
                    <div class="number-controls">
                        <button type="button" class="btn-increment">+</button>
                        <button type="button" class="btn-decrement">-</button>
                    </div>
                </div>
            </td>
            <td>
                <div class="number-input-wrapper">
                    <input type="number" class="form-control qty-input" value="0" />
                    <div class="number-controls">
                        <button type="button" class="btn-increment">+</button>
                        <button type="button" class="btn-decrement">-</button>
                    </div>
                </div>
            </td>
            <td>

                <div class="input-with-icon">
                    <input type="datetime-local" class="form-control scheduled-input" />
                    <i class="fas fa-calendar-alt icon-btn" onclick="openDatePicker(event)"></i>
                </div>
            </td>
            
            <td><input type="text" class="form-control rem-input"/></td>
            <td>
                <div class="input-with-icon">
                    <input type="text" class="form-control test-location-input" >
                    <i class="fas fa-caret-down icon-btn" onclick="openDropdown(event, 'test-location-dropdown')"></i>
                </div>
            </td>
            <td><input type="text" class="form-control report-no-input" value="[Auto]" readonly disabled/></td>
            <td><input type="text" class="form-control rev-input" value="0" readonly /></td>
            <td>
                <i class="fas fa-box items-icon" onclick="openItemsModal(this)"></i>
            </td>
            <td>
                <i class="fas fa-trash-alt delete-row-icon" onclick="deleteTestRow(this)"></i>
            </td>
        </tr>
    `;
}

function openDatePicker(event) {
    const input = event.target.closest('.input-with-icon').querySelector('.scheduled-input');
    if (input) {
        input.removeAttribute('disabled');
        // Check if the browser supports showPicker, otherwise use focus
        if (typeof input.showPicker === 'function') {
            input.showPicker();
        } else {
            input.focus();
        }
    }
}
    // Function to add a new row
    window.addNewTestRow = function() {
        const tbody = document.getElementById('test-table-body');
        const newRow = document.createElement('tr');
        newRow.innerHTML = createTestRowHtml();
        tbody.appendChild(newRow);
    };

    // Function to delete a row
    window.deleteTestRow = function(icon) {
        const row = icon.closest('tr');
        if (row) {
            row.remove();
        }
    };

    // Generic function to handle dropdowns
    window.openDropdown = function(event, dropdownId) {
        event.stopPropagation();
        closeAllDropdowns();
        const dropdown = document.getElementById(dropdownId);
        const input = event.target.closest('td').querySelector('.form-control');
        const rect = input.getBoundingClientRect();
        dropdown.style.display = 'block';
        dropdown.style.top = `${rect.bottom + window.scrollY}px`;
        dropdown.style.left = `${rect.left + window.scrollX}px`;
        currentTargetInput = input;
    };

    // Function to close all dropdowns
    function closeAllDropdowns() {
        document.querySelectorAll('.dropdown-container').forEach(d => {
            d.style.display = 'none';
        });
    }

    // Handle dropdown item selection
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function() {
            if (currentTargetInput) {
                currentTargetInput.value = this.dataset.value;
            }
            closeAllDropdowns();
            currentTargetInput = null; // Clear the reference
        });
    });

    // Handle clicks outside of dropdowns to close them
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.input-with-icon')) {
            closeAllDropdowns();
        }
    });

    // Handle number input controls
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-increment')) {
            const input = event.target.closest('.number-input-wrapper').querySelector('input[type="number"]');
            input.stepUp();
        } else if (event.target.classList.contains('btn-decrement')) {
            const input = event.target.closest('.number-input-wrapper').querySelector('input[type="number"]');
            input.stepDown();
        }
    });

    window.openItemsModal = function(icon) {
        alert('فتح نافذة تفاصيل العناصر (Items)');
    };

    addNewTestRow();
});

// Updated listener to close dropdowns when clicking outside
document.addEventListener('click', (event) => {
    const dropdowns = document.querySelectorAll('.custom-autocomplete-container');
    dropdowns.forEach(container => {
        const input = container.querySelector('input');
        const dropdown = container.querySelector('.custom-dropdown-project-code, .custom-dropdown-lab, .custom-dropdown-sample-type, .custom-dropdown-payment-terms, .custom-dropdown-nominal-size');
        const icon = container.querySelector('.icon-btn');
        if (dropdown && !container.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
});

function resetAndDisableForm() {
    isNewMode = false;
    var formElements = document.querySelectorAll('#registration-content input, #registration-content select, #registration-content textarea');
    formElements.forEach(function(element) {
        // الاستثناء: لا تقم بتعطيل حقل "scheduled-input"
        if (element.id !== 'order-no' && element.id !== 'order-number-footer' && !element.classList.contains('scheduled-input')) {
            element.setAttribute('disabled', 'true');
        } else {
            // للتأكد من أن حقل التاريخ قابل للقراءة فقط عند إعادة الضبط
            element.setAttribute('readonly', 'true');
        }
        element.classList.remove('readonly-input');
    });
    
    // تأكد من أن أيقونة التقويم غير معطلة بصورة صريحة
    const datePickerIcons = document.querySelectorAll('#test-table-body .fa-calendar-alt');
    datePickerIcons.forEach(icon => {
        icon.style.pointerEvents = 'auto';
        icon.style.cursor = 'pointer';
    });
    
    document.getElementById('save-btn').setAttribute('disabled', 'true');
    document.getElementById('save-close-btn').setAttribute('disabled', 'true');
    document.getElementById('actions-btn').setAttribute('disabled', 'true');
    document.getElementById('new-btn').removeAttribute('disabled');
    document.getElementById('confirmation').value = '';
    document.querySelector('.project-code-icons .icon-btn-confirmation').style.display = 'none';
    document.querySelector('.project-code-icons .fa-search').style.display = 'block';
    document.getElementById('lab').value = '';
}

// Listener to close the project dropdown when clicking outside
document.addEventListener('click', (event) => {
    const projectDropdown = document.getElementById('project-code-dropdown');
    const projectContainer = document.querySelector('.custom-autocomplete-container');
    if (projectDropdown && projectContainer && !projectContainer.contains(event.target)) {
        projectDropdown.style.display = 'none';
    }
});

document.addEventListener('DOMContentLoaded', (event) => {
    resetAndDisableForm();
    openRegistrationCategoryModal();
});

function toggleOrderNoDropdown() {
    const dropdown = document.getElementById('order-no-dropdown');
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

function selectOrderNo(value) {
    document.getElementById('order-number-footer').value = value;
    toggleOrderNoDropdown();
}

// Optional: Close dropdown when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.order-no-footer') && !event.target.matches('.icon-btn')) {
        const dropdown = document.getElementById('order-no-dropdown');
        if (dropdown && dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        }
    }
}
