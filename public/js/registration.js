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



        document.getElementById('main-tab-headers').style.display = 'flex';

        document.getElementById('registration-content').style.display = 'block';
        document.getElementById('form-footer-buttons').style.display = 'flex';
        resetAndDisableForm();
        showSubTab('compressive-strength');

    } else {

    }
}



// دالة للتبديل بين محتوى التبويبات الفرعية
function showSubTab(tabId) {
    // 1. إخفاء محتوى جميع التبويبات الفرعية وإلغاء تنشيطها
    const contents = document.querySelectorAll('.sub-tab-content');
    contents.forEach(content => {
        content.style.display = 'none';
        content.classList.remove('active-sub-tab-content');
    });

    const tabs = document.querySelectorAll('.sub-tab');
    tabs.forEach(tab => {
        tab.classList.remove('active-sub-tab');
    });

    // 2. إظهار محتوى التبويب المستهدف وتنشيط التبويب نفسه
    document.getElementById(tabId).style.display = 'block';
    document.getElementById(tabId).classList.add('active-sub-tab-content');

    // إيجاد العنصر الذي تم النقر عليه (الـ Span) وتنشيطه
    const targetTab = document.querySelector(`.sub-tab[onclick*="${tabId}"]`);
    if (targetTab) {
        targetTab.classList.add('active-sub-tab');
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

     const registrationTab = document.getElementById('registration-tab');
    if (registrationTab) {
        const iconHtml = registrationTab.querySelector('i').outerHTML;
        const closeBtnHtml = registrationTab.querySelector('.close-tab-btn').outerHTML;

        const newTabText = 'Order No: ' + newCodeBase;

        // استخدام innerHTML لإعادة بناء محتوى التبويب
        registrationTab.innerHTML = iconHtml + ' ' + newTabText + ' ' + closeBtnHtml;
    }
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

  initializeTestTable();
}


function openLabDropdown() {
    const dropdown = document.getElementById('lab-dropdown');

    // إذا كانت مفتوحة (display: block)، قم بإغلاقها
    if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
    } else {

        dropdown.style.display = 'block';
    }
}

/**
 * دالة لإغلاق القائمة المنسدلة للـ Lab.
 */
function closeLabDropdown() {
    const dropdown = document.getElementById('lab-dropdown');
    // استخدم 'none' لإخفاء القائمة
    dropdown.style.display = 'none';
}

/**
 * دالة لاختيار قيمة المختبر وتعبئتها وإغلاق القائمة.
 */
function selectLab(locationName, department) {
    const labField = document.getElementById('lab');
    labField.value = `${locationName} (${department})`;

    // يتم استدعاء دالة الإغلاق مباشرة هنا
    closeLabDropdown();
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

function openSampleTypeDropdown() {


    const dropdown = document.getElementById('sample-type-dropdown');

    // التبديل: إذا كانت مفتوحة (block)، أغلقها؛ وإلا، افتحها.
    if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
    } else {
        dropdown.style.display = 'block';
    }
}

/**
 * دالة لإغلاق القائمة المنسدلة لـ Sample Type بشكل صريح.
 */
function closeSampleTypeDropdown() {
    const dropdown = document.getElementById('sample-type-dropdown');
    dropdown.style.display = 'none';
}

function selectSampleType(value) {
    // 1. تعبئة حقل Sample Type وإغلاق القائمة (المنطق الأصلي)
    document.getElementById('sample-type').value = value;
    closeSampleTypeDropdown();


    if (value === 'CYLINDER') {
        const testMethodValue = 'ASTMC39/C39N-21';
        const testLocationValue = 'CONCRETE LAB';


        for (let i = 0; i <= 3; i++) {

            const row = document.querySelector(`tr[data-row-index="${i}"]`);

            if (row) {

                const testMethodInput = row.querySelector('.test-method-input');
                if (testMethodInput) {
                    testMethodInput.value = testMethodValue;
                }


                const testLocationInput = row.querySelector('.test-location-input');
                if (testLocationInput) {
                    testLocationInput.value = testLocationValue;
                }
            }
        }
    }

}


function openPaymentTermsDropdown() {

    const dropdown = document.getElementById('payment-terms-dropdown');

    // التبديل: إذا كانت مفتوحة (block)، أغلقها؛ وإلا، افتحها.
    if (dropdown.style.display === 'block') {
        dropdown.style.display = 'none';
    } else {
        dropdown.style.display = 'block';
    }
}


function closePaymentTermsDropdown() {
    const dropdown = document.getElementById('payment-terms-dropdown');
    dropdown.style.display = 'none';
}

/**
 * دالة لاختيار قيمة Payment Terms وتعبئتها وإغلاق القائمة.
 */
function selectPaymentTerms(value) {
    document.getElementById('payment-terms').value = value;

    // استخدام دالة الإغلاق
    closePaymentTermsDropdown();
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

   initializeTestTable();
    // Add event listener for confirmation table search once on page load
    const searchInput = document.getElementById('confirmation-search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', searchConfirmationTable);
    }



function autoFillNextRow(inputElement) {
    const selectedValue = inputElement.value;

    // نبحث عن الصف الثاني (Index 1) بالتحديد لتعبئته
    const nextRow = document.querySelector('tr[data-row-index="1"]');

    if (nextRow) {
        // نجد حقل الإدخال في الخلية الأولى للصف الثاني
        const nextRowInput = nextRow.querySelector('.test-method-input');

        if (nextRowInput) {
            // تعبئة حقل الصف الثاني بنفس القيمة
            nextRowInput.value = selectedValue;
        }
    }
}

// متغير لتخزين بيانات الصف الحالي الذي ضغط على زر Items
let currentRowItemData = {};
let internalRowIndex = 0; // مؤشر الصفوف الداخلية (للتسلسل)

// دالة عامة لإغلاق أي Popover
window.closePopover = function(popoverId) {
    document.getElementById(popoverId).style.display = 'none';
};


window.showItemsDropdown = function(element, rowIndex) {
    const popover = document.getElementById('items-generator-popover');

    closePopover('items-generator-popover');

    // 1. تخزين بيانات الصف الأساسية (لتوليد كود العينة)
    currentRowItemData.rowIndex = rowIndex;
    const specimenCodeInput = document.querySelector('tr[data-row-index="0"] .test-method-input');
    currentRowItemData.specimenCode = specimenCodeInput ? specimenCodeInput.value : 'SPEC-000';

    // 2. إعادة تعيين الحقول في القمة (Controls Grid)
    document.getElementById('total-input').value = 1;
    document.getElementById('total-lab-input').value = 0;


    // 3. إعادة تعيين الجدول
    document.getElementById('generated-items-tbody').innerHTML = '';

    // 4. إظهار النافذة
    popover.style.display = 'block';
};


window.generateItems = function() {
    const totalItems = parseInt(document.getElementById('total-input').value);

    const mixDetails = 'M25-C1';

    const baseCode = currentRowItemData.specimenCode;
    const tbody = document.getElementById('generated-items-tbody');

    if (isNaN(totalItems) || totalItems < 1) return alert("Please enter a valid number for Total.");

    tbody.innerHTML = '';
    internalRowIndex = 0;

    for (let i = 1; i <= totalItems; i++) {
        internalRowIndex++;
        const sequentialSuffix = String.fromCharCode(65 + (i - 1));
        const specimenCode = `${baseCode}-${sequentialSuffix}`;
        const specimenID = `${baseCode}-${internalRowIndex}`;

        const rowHtml = `
            <tr data-internal-index="${internalRowIndex}">
                <td>${internalRowIndex}</td>

                <td><input type="text" class="form-control form-control-sm specimen-id" value="${specimenID}" readonly></td>

                <td><input type="text" class="form-control form-control-sm specimen-code" value="${specimenCode}"></td>

                <td><input type="text" class="form-control form-control-sm mix-detail-id" value="${mixDetails}"></td>

                <td><input type="text" class="form-control form-control-sm specimen-code2" value=""></td>

                <td><input type="text" class="form-control form-control-sm specimen-description" value="Sample ${internalRowIndex}"></td>

                <td><input type="text" class="form-control form-control-sm test-description" value="Comp. Test"></td>

                <td><input type="number" class="form-control form-control-sm quantity" value="1" min="1"></td>

                <td><input type="text" class="form-control form-control-sm order-no" value="ORD-${currentRowItemData.rowIndex}"></td>

                <td>
                    <i class="fas fa-trash-alt delete-part-icon"
                       onclick="deleteInternalRow(this, ${internalRowIndex})"></i>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', rowHtml);
    }
};


window.clearTable = function() {
    document.getElementById('generated-items-tbody').innerHTML = '';
    internalRowIndex = 0;
};

window.applyTicket = function() {
    alert("Applying ticket logic...");
};

window.acceptItems = function() {
    saveGeneratedItems();
};

window.saveGeneratedItems = function() {
    alert(`Successfully saved ${document.getElementById('generated-items-tbody').children.length} items to the order!`);
    closePopover('items-generator-popover');
};

window.deleteInternalRow = function(element, index) {
    const row = element.closest(`tr[data-internal-index="${index}"]`);
    if (row) {
        row.remove();
    }
};


let currentRowIndex = 2;


function createTestRowHtml(rowIndex, shouldShowCheckbox = false) {
    const isFirstRow = rowIndex === 0;

    let testMethodCellContent;


    if (isFirstRow) {
        testMethodCellContent = `
            <div class="input-with-icon">
                 <input type="text" class="form-control test-method-input small-input" data-col="test-method">

            </div>
        `;
    }

    else if (shouldShowCheckbox) {
        testMethodCellContent = `
            <div class="input-with-icon merged-cell">
                <input type="checkbox" name="test-checkbox-${rowIndex}" data-index="${rowIndex}" class="merged-checkbox">
                <input type="text" class="form-control test-method-input small-input" data-col="test-method">

            </div>
        `;
    }

    else {
        testMethodCellContent = `
            <div class="input-with-icon">
                <input type="text" class="form-control test-method-input small-input" data-col="test-method">

            </div>
        `;
    }

    // ... (بقية HTML الـ <tr> هنا - 12 خلية)
    return `
        <tr data-row-index="${rowIndex}">

            <td>${testMethodCellContent}</td>

            <td>
                <div class="number-input-wrapper">
                    <input type="number" class="form-control r-age-input" value="0" data-col="r-age" />
                    <div class="number-controls">
                        <button type="button" class="btn-increment">+</button>
                        <button type="button" class="btn-decrement">-</button>
                    </div>
                </div>
            </td>

            <td>
                <div class="number-input-wrapper">
                    <input type="number" class="form-control a-age-input" value="0" data-col="a-age" />
                    <div class="number-controls">
                        <button type="button" class="btn-increment">+</button>
                        <button type="button" class="btn-decrement">-</button>
                    </div>
                </div>
            </td>

            <td>
                <div class="number-input-wrapper">
                    <input type="number" class="form-control price-input" value="0" data-col="price" />
                    <div class="number-controls">
                        <button type="button" class="btn-increment">+</button>
                        <button type="button" class="btn-decrement">-</button>
                    </div>
                </div>
            </td>

            <td>
                <div class="number-input-wrapper">
                    <input type="number" class="form-control qty-input" value="0" data-col="qty" />
                    <div class="number-controls">
                        <button type="button" class="btn-increment">+</button>
                        <button type="button" class="btn-decrement">-</button>
                    </div>
                </div>
            </td>

            <td>
                <div class="input-with-icon">
                    <input type="datetime-local" class="form-control scheduled-input" data-col="scheduled-at" />
                  <i class="fas fa-calendar-alt icon-btn" onclick="openDatePicker(this)"></i>
                </div>
            </td>

            <td><input type="text" class="form-control rem-input" data-col="rem"/></td>

            <td>
                <div class="input-with-icon">
                    <input type="text" class="form-control test-location-input" data-col="test-location">
                    <i class="fas fa-caret-down icon-btn" onclick="openDropdown(event, 'test-location-dropdown')"></i>
                </div>
            </td>

            <td><input type="text" class="form-control report-no-input" value="[Auto]" data-col="report-no" readonly disabled/></td>

            <td><input type="text" class="form-control rev-input" value="0" data-col="rev" readonly /></td>

            <td>
                <button type="button" class="btn btn-sm btn-info items-dropdown-btn" data-col="items" onclick="showItemsDropdown(this, ${rowIndex})">
                    <i class="fas fa-box items-icon"></i>
                </button>
            </td>

            <td>
                <i class="fas fa-trash-alt delete-row-icon" onclick="deleteTestRow(this)"></i>
            </td>

        </tr>
    `;
}

// Function initializeTestTable
function initializeTestTable() {
    const tableBody = document.getElementById('test-table-body');
    if (!tableBody) return;

    tableBody.innerHTML = '';


    let rowHtml = createTestRowHtml(0, false);
    tableBody.insertAdjacentHTML('beforeend', rowHtml);


    rowHtml = createTestRowHtml(1, true);
    tableBody.insertAdjacentHTML('beforeend', rowHtml);
}


window.addNewTestRow = function() {
    const tbody = document.getElementById('test-table-body');


    const rowHtml = createTestRowHtml(currentRowIndex, true);

    tbody.insertAdjacentHTML('beforeend', rowHtml);
    currentRowIndex++;
};


function deleteTestRow(iconElement) {
    const row = iconElement.closest('tr');
    if (row) {
        row.remove();
    }
}


window.openDropdown = function(event, dropdownId) {
    event.stopPropagation();

    const dropdown = document.getElementById(dropdownId);
    const input = event.target.closest('td').querySelector('.form-control');


    const isAlreadyOpen = dropdown.style.display === 'block' && currentTargetInput === input;


    closeAllDropdowns();

    if (isAlreadyOpen) {
        // 3. إذا كانت مفتوحة، نغلقها ونمسح مرجع الـ input
        dropdown.style.display = 'none';
        currentTargetInput = null;
    } else {
        // 4. إذا كانت مغلقة، نفتحها
        const rect = input.getBoundingClientRect();

        dropdown.style.display = 'block';
        dropdown.style.top = `${rect.bottom + window.scrollY}px`;
        dropdown.style.left = `${rect.left + window.scrollX}px`;

        // تعيين مرجع الـ input الحالي
        currentTargetInput = input;
    }
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


function disableFormFields() {
    var formElements = document.querySelectorAll('#registration-content input, #registration-content select, #registration-content textarea');
    formElements.forEach(function(element) {
        element.setAttribute('disabled', 'true');
    });

    // تعطيل أزرار الحفظ (Save buttons)
    document.getElementById('save-btn').setAttribute('disabled', 'true');
    document.getElementById('save-close-btn').setAttribute('disabled', 'true');
}
/**
 * دالة لجمع بيانات النموذج ومحاكاة عملية الحفظ.
 * تم تحديثها لتشمل جميع الحقول بناءً على IDs الموجودة في ملف HTML.
 * * @param {boolean} closeAfterSave - إذا كانت true، تغلق علامة التبويب بعد الحفظ.
 */

function disableFormFields() {
    var formElements = document.querySelectorAll('#registration-content input, #registration-content select, #registration-content textarea');
    formElements.forEach(function(element) {
        element.setAttribute('disabled', 'true');
    });

    // تعطيل الأزرار التي يجب تعطيلها بعد الحفظ (Save buttons)
    document.getElementById('save-btn').setAttribute('disabled', 'true');
    document.getElementById('save-close-btn').setAttribute('disabled', 'true');
}

// ----------------------------------------------------------------------
/**
 * تعرض رسالة نجاح احترافية (Toast) في أعلى الشاشة.
 * @param {string} mainMessage - الرسالة الرئيسية (مثل: "تم الحفظ بنجاح").
 * @param {string} detail - تفاصيل إضافية (مثل: رقم الطلب).
 */
function showSuccessMessage(mainMessage, detail = '') {
    // 1. إنشاء عنصر الرسالة
    const toast = document.createElement('div');
    toast.className = 'professional-success-toast';

    // استخدام أيقونة للنجاح (تفترض استخدام Font Awesome)
    const icon = '<i class="fas fa-check-circle" style="margin-right: 10px;"></i>';

    // بناء محتوى الرسالة
    let content = `${icon} <strong>${mainMessage}</strong>`;
    if (detail) {
        content += `<br><small>${detail}</small>`;
    }

    toast.innerHTML = content;

    // 2. إضافة الرسالة إلى الجسم
    document.body.appendChild(toast);

    // 3. عرض الرسالة مع تأثير التحريك
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translate(-50%, 0)'; // تحريكها من الأعلى
    }, 10);

    // 4. إخفاء وإزالة الرسالة بعد 4 ثواني
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translate(-50%, -100%)'; // إخفاء بتحريكها للأعلى

        // إزالة العنصر بعد انتهاء التحريك لتنظيف الـ DOM
        toast.addEventListener('transitionend', () => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        });
    }, 4000); // تظهر لمدة 4 ثواني
}


function saveRegistrationData(closeAfterSave = false) {
    // 1. جمع البيانات من حقول النموذج الرئيسية
    // ... (جميع أكواد جمع البيانات هنا) ...

    const orderNo = document.getElementById('order-no')?.value ?? 'غير مُدخل';

    const testData = collectTestTableData();

    // تجميع البيانات
    const registrationData = {
        // ... بيانات التسجيل ...
    };

    console.log('--- بيانات التسجيل المُجمّعة الجاهزة للحفظ ---');
    console.log(registrationData);




    const successMessage = 'عملية حفظ ناجحة!';
    const detailMessage = `تم تسجيل الطلب برقم: ${orderNo}`;

    showSuccessMessage(successMessage, detailMessage);



    if (closeAfterSave) {
        resetAndDisableForm();
        closeRegistrationTab();
    } else {
        disableFormFields();
        document.getElementById('actions-btn').removeAttribute('disabled');
    }
}
function saveRegistrationData(closeAfterSave = false) {
    // 1. جمع البيانات من حقول النموذج الرئيسية

    // معلومات الطلب (Order Information)
    const orderNo = document.getElementById('order-no')?.value ?? 'غير مُدخل'; //
    const sampleNo = document.getElementById('sample-no')?.value ?? 'غير مُدخل'; //
    const projectCode = document.getElementById('project-code')?.value ?? 'غير مُدخل'; //
    const lab = document.getElementById('lab')?.value ?? 'غير مُدخل'; //
    const orderDate = document.getElementById('order-date')?.value ?? 'غير مُدخل'; //
    const project = document.getElementById('project')?.value ?? 'غير مُدخل'; //
    const sContractor = document.getElementById('s-contractor')?.value ?? 'غير مُدخل'; //
    const projectNo = document.getElementById('project-no')?.value ?? 'غير مُدخل'; //
    const customer = document.getElementById('customer')?.value ?? 'غير مُدخل'; //
    const paymentTerms = document.getElementById('payment-terms')?.value ?? 'غير مُدخل'; //
    const pouringTicket = document.getElementById('pouring-ticket')?.value ?? 'غير مُدخل'; //
    const sampleType = document.getElementById('sample-type')?.value ?? 'غير مُدخل'; //
    const confirmation = document.getElementById('confirmation')?.value ?? 'غير مُدخل'; //

    // معلومات العينة (Sample Information)
    const sampleSource = document.getElementById('sample-source')?.value ?? 'غير مُدخل'; //
    const sampleDescription = document.getElementById('sample-description')?.value ?? 'غير مُدخل'; //
    const rfiWir = document.getElementById('rfi-wir')?.value ?? 'غير مُدخل'; //
    const structureRef = document.getElementById('structure-ref')?.value ?? 'غير مُدخل'; //
    const samplingMethod = document.getElementById('sampling-method')?.value ?? 'غير مُدخل'; //
    const receivedAt = document.getElementById('received-at')?.value ?? 'غير مُدخل'; //
    const totalReceived = document.getElementById('total-received')?.value ?? 'غير مُدخل'; //
    const specPropBy = document.getElementById('spec-prop-by')?.value ?? 'غير مُدخل'; //
    const sampledAt = document.getElementById('sampled-at')?.value ?? 'غير مُدخل'; //
    const receivedBy = document.getElementById('received-by')?.value ?? 'غير مُدخل'; //
    const sampledBy = document.getElementById('sampled-by')?.value ?? 'غير مُدخل'; //
    const castedAt = document.getElementById('casted-at')?.value ?? 'غير مُدخل'; //
    const sampBroughtBy = document.getElementById('samp-brought-by')?.value ?? 'غير مُدخل'; //
    const compEquipment = document.getElementById('comp-equipment')?.value ?? 'غير مُدخل'; //
    const witness = document.getElementById('witness')?.value ?? 'غير مُدخل'; //
    const mtdOfComp = document.getElementById('mtd-of-comp')?.value ?? 'غير مُدخل'; //
    const cementContent = document.getElementById('cement-content')?.value ?? 'غير مُدخل'; //
    const notes = document.getElementById('notes')?.value ?? 'غير مُدخل'; //
    const cementType = document.getElementById('cement-type')?.value ?? 'غير مُدخل'; //
    const nominalSize = document.getElementById('nominal-size')?.value ?? 'غير مُدخل'; //
    const classField = document.getElementById('class')?.value ?? 'غير مُدخل'; //


    const testData = collectTestTableData();

    // تجميع البيانات
    const registrationData = {
        orderNo, sampleNo, projectCode, lab, orderDate, project, sContractor, projectNo, customer,
        paymentTerms, pouringTicket, sampleType, confirmation, sampleSource, sampleDescription,
        rfiWir, structureRef, samplingMethod, receivedAt, totalReceived, specPropBy, sampledAt,
        receivedBy, sampledBy, castedAt, sampBroughtBy, compEquipment, witness, mtdOfComp,
        cementContent, notes, cementType, nominalSize, class: classField, testData
    };

    console.log('--- بيانات التسجيل المُجمّعة الجاهزة للحفظ ---');
    console.log(registrationData);

    const successMessage = 'تمت عملية حفظ البيانات بنجاح.';
    const detailMessage = (orderNo !== 'غير مُدخل')
        ? `تم تسجيل الطلب برقم: ${orderNo}`
        : 'يرجى مراجعة سجلات النظام للحصول على رقم الطلب.';

    showSuccessMessage(successMessage, detailMessage);

    // 3. التحكم في الحالة بعد الحفظ

    if (closeAfterSave) {
        resetAndDisableForm();
        closeRegistrationTab();
    } else {
        disableFormFields();
        document.getElementById('actions-btn').removeAttribute('disabled');
    }
}
/**
 * دالة مساعدة لجمع جميع بيانات التسجيل من النموذج الرئيسي.
 * (هذه هي الدالة التي كانت مفقودة وتسببت في الخطأ).
 */
function collectAllRegistrationData() {
    // معلومات الطلب (Order Information)
    const orderNo = document.getElementById('order-no')?.value ?? 'N/A';
    const sampleNo = document.getElementById('sample-no')?.value ?? 'N/A';
    const projectCode = document.getElementById('project-code')?.value ?? 'N/A';
    const lab = document.getElementById('lab')?.value ?? 'N/A';
    const orderDate = document.getElementById('order-date')?.value ?? 'N/A';
    const project = document.getElementById('project')?.value ?? 'N/A';
    const sContractor = document.getElementById('s-contractor')?.value ?? 'N/A';
    const projectNo = document.getElementById('project-no')?.value ?? 'N/A';
    const customer = document.getElementById('customer')?.value ?? 'N/A';
    const paymentTerms = document.getElementById('payment-terms')?.value ?? 'N/A';
    const pouringTicket = document.getElementById('pouring-ticket')?.value ?? 'N/A';
    const sampleType = document.getElementById('sample-type')?.value ?? 'N/A';
    const confirmation = document.getElementById('confirmation')?.value ?? 'N/A';

    // معلومات العينة (Sample Information)
    const sampleSource = document.getElementById('sample-source')?.value ?? 'N/A';
    const sampleDescription = document.getElementById('sample-description')?.value ?? 'N/A';
    const rfiWir = document.getElementById('rfi-wir')?.value ?? 'N/A';
    const structureRef = document.getElementById('structure-ref')?.value ?? 'N/A';
    const samplingMethod = document.getElementById('sampling-method')?.value ?? 'N/A';
    const receivedAt = document.getElementById('received-at')?.value ?? 'N/A';
    const totalReceived = document.getElementById('total-received')?.value ?? 'N/A';
    const specPropBy = document.getElementById('spec-prop-by')?.value ?? 'N/A';
    const sampledAt = document.getElementById('sampled-at')?.value ?? 'N/A';
    const receivedBy = document.getElementById('received-by')?.value ?? 'N/A';
    const sampledBy = document.getElementById('sampled-by')?.value ?? 'N/A';
    const castedAt = document.getElementById('casted-at')?.value ?? 'N/A';
    const sampBroughtBy = document.getElementById('samp-brought-by')?.value ?? 'N/A';
    const compEquipment = document.getElementById('comp-equipment')?.value ?? 'N/A';
    const witness = document.getElementById('witness')?.value ?? 'N/A';
    const mtdOfComp = document.getElementById('mtd-of-comp')?.value ?? 'N/A';
    const cementContent = document.getElementById('cement-content')?.value ?? 'N/A';
    const notes = document.getElementById('notes')?.value ?? 'N/A';
    const cementType = document.getElementById('cement-type')?.value ?? 'N/A';
    const nominalSize = document.getElementById('nominal-size')?.value ?? 'N/A';
    const classField = document.getElementById('class')?.value ?? 'N/A';

    // جمع بيانات جدول الاختبارات
    const testData = collectTestTableData();

    // تجميع البيانات في كائن واحد
    return {
        // معلومات الطلب
        orderNo, sampleNo, projectCode, lab, orderDate, project, sContractor, projectNo, customer,
        paymentTerms, pouringTicket, sampleType, confirmation,
        // معلومات العينة
        sampleSource, sampleDescription, rfiWir, structureRef, samplingMethod, receivedAt,
        totalReceived, specPropBy, sampledAt, receivedBy, sampledBy, castedAt, sampBroughtBy,
        compEquipment, witness, mtdOfComp, cementContent, notes, cementType, nominalSize,
        class: classField,
        // بيانات جدول الاختبارات
        testData
    };
}


function collectTestTableData() {
    const testTableBody = document.getElementById('test-table-body');
    if (!testTableBody) return [];

    const rows = testTableBody.querySelectorAll('tr');
    const data = [];

    rows.forEach(row => {

        const rowData = {
            testMethod: row.querySelector('.test-method-input')?.value,
            rAge: row.querySelector('.r-age-input')?.value,
            aAge: row.querySelector('.a-age-input')?.value,
            price: row.querySelector('.price-input')?.value,
            qty: row.querySelector('.qty-input')?.value,
            scheduled: row.querySelector('.scheduled-input')?.value,
            rem: row.querySelector('.rem-input')?.value,
            testLocation: row.querySelector('.test-location-input')?.value,
            reportNo: row.querySelector('.report-no-input')?.value,
            rev: row.querySelector('.rev-input')?.value,
        };
        // إضافة الصف إذا كان يحتوي على طريقة اختبار محددة
        if (rowData.testMethod) {
            data.push(rowData);
        }
    });

    return data;
}

function toggleActionDropdown(event) {
    if (event) {
        // منع النقر من إغلاق القائمة مباشرة (ضروري)
        event.stopPropagation();
    }

    const dropdown = document.getElementById('actionDropdown');

    if (dropdown) {

        if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        } else {
            dropdown.style.display = 'block';
        }
    }
}



/**
 * تعرض مودال اختيار الطابعة وتشغّل الطباعة لجميع العينات عند التأكيد.
 * @param {Array<object>} allItems - جميع كائنات العينات المراد طباعتها.
 */
function showGlobalPrinterModal() {

    document.getElementById('printer-modal')?.remove();

    const modalHTML = `
        <div id="printer-modal" style="
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); z-index: 9999;
            display: flex; justify-content: center; align-items: center;
        ">
            <div id="printer-modal-content" style="
                background-color: white; padding: 25px; border-radius: 5px;
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4); width: 400px;
                font-family: Arial, sans-serif;
            ">
                <h4 style="margin-top: 0; margin-bottom: 20px; font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                    Select Printer for ALL Items
                </h4>

                <div style="margin-bottom: 20px;">
                    <label for="printer-select" style="display: block; margin-bottom: 8px; font-size: 14px;">Select Printer:</label>
                    <select id="printer-select" style="
                        width: 100%; padding: 10px; border: 1px solid #ccc;
                        border-radius: 4px; font-size: 14px;
                    ">
                        <option value="Label">PDF (Label)</option>
                        <option value="A4">Laser (A4)</option>
                        <option value="default">ZDdesigner</option>
                    </select>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button id="cancel-print-btn" onclick="document.getElementById('printer-modal').remove()" style="
                        padding: 8px 15px; border: none; border-radius: 4px;
                        background-color: #6c757d; color: white; cursor: pointer;
                    ">
                        Cancel
                    </button>
                    <button id="confirm-print-btn" style="
                        padding: 8px 15px; border: none; border-radius: 4px;
                        background-color: #007bff; color: white; cursor: pointer;
                    ">
                        Print All
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);

    const modal = document.getElementById('printer-modal');
    const printBtn = document.getElementById('confirm-print-btn');
    const printerSelect = document.getElementById('printer-select');

    // عند الضغط على زر "Print All"
    printBtn.addEventListener('click', () => {
        const selectedPrinter = printerSelect.value;
        modal.remove(); // إغلاق نافذة اختيار الطابعة


        printAllBarcodesForSelection(selectedPrinter);
    });
}


function getRegistrationOrderNumber() {


     const registrationOrderField = document.getElementById('order-no');

    // إرجاع قيمة الحقل أو سلسلة فارغة
    return registrationOrderField ? registrationOrderField.value : '';
}

function getGeneratedItemsData() {
    const mainOrderNo = getRegistrationOrderNumber();
    const rows = document.getElementById('generated-items-tbody').querySelectorAll('tr');
    const items = [];

    rows.forEach(row => {
        const item = {};


        item.specimenID = row.querySelector('.specimen-id').value;
        item.specimenCode = row.querySelector('.specimen-code').value;
        item.mixDetailId = row.querySelector('.mix-detail-id').value;
        item.specimenCode2 = row.querySelector('.specimen-code2').value;
        item.specimenDescription = row.querySelector('.specimen-description').value;
        item.testDescription = row.querySelector('.test-description').value;
        item.quantity = parseInt(row.querySelector('.quantity').value);
        item.orderNo = row.querySelector('.orderNumber').value;

        const findValue = (selector) => row.querySelector(selector)?.value || 'N/A';

        item.receivedDate = findValue('.received-date');
        item.scheduleDate = findValue('.schedule-date');
        item.requiredStrength = findValue('.required-strength');
        item.castingDate = findValue('.casting-date');
        item.clientMixInfo = findValue('.client-mix-info');

        items.push(item);
    });

    return items;
}

/**
 * توليد محتوى طباعة HTML مخصص لجميع العينات وطباعته مباشرة في نفس الصفحة.
 * (تم التعديل لتوليد تنسيق ملصق المختبر)
 * @param {string} printerType - نوع الطابعة ('Label' أو 'A4').
 */
function printAllBarcodesInPage(printerType = 'Label') {
    const allItems = getGeneratedItemsData();

    if (allItems.length === 0) {
        return alert('لا توجد عينات/آيتمز لتوليد الباركود.');
    }

    let printContentHTML = '';

    allItems.forEach((item, index) => {

        const canvas = document.createElement('canvas');
        JsBarcode(canvas, item.specimenID, {
            format: "CODE128",
            displayValue: false, // لا تظهر النص تحت الباركود، لأننا سنطبعه منفصلاً
            margin: 5,
            width: 1.5,
            height: 40 // زيادة الارتفاع ليتناسب مع الصورة
        });
        const barcodeDataURL = canvas.toDataURL("image/png");

        if (printerType === 'Label') {

            printContentHTML += `
                <div class="barcode-label" style="
                    /* الأبعاد الافتراضية للملصق (مثل 100mm x 50mm) */
                    width: 95mm; height: 48mm;
                    margin: 2mm; padding: 2mm; float: left; overflow: hidden;
                    font-family: Arial, sans-serif; box-sizing: border-box;
                    /* إزالة الحدود إذا كنت تطبع على طابعة ملصقات */
                    border: 0px solid #ccc;
                ">
                    <div style="text-align: center; margin-bottom: 3px;">
                        <img src="${barcodeDataURL}" style="width: 90%; height: auto; display: block; margin: 0 auto;"/>
                        <span style="font-weight: bold; font-size: 16px; margin-top: -10px; display: block;">
                            ${item.specimenID || 'N/A'}
                        </span>
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 3px;">
                        <span style="font-weight: bold;">${item.mixDetailId || 'N/A'}</span>
                        <span style="font-weight: bold;">${item.orderNo || 'N/A'}</span>
                        <span style="font-weight: bold; font-size: 16px;">${item.quantity || '1'}</span>
                    </div>

                    <div style="font-size: 10px; margin-bottom: 3px; font-weight: bold;">
                        ${item.specimenDescription || 'N/A'}
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 9px; margin-bottom: 5px;">
                        <span>RCVD@ ${item.receivedDate || 'N/A'}</span>
                        <span>SCHD@ ${item.scheduleDate || 'N/A'}</span>
                    </div>

                    <div style="font-size: 10px; font-weight: bold; text-align: center; margin-bottom: 3px;">
                        ${item.testDescription || 'COMPRESSIVE STRENGTH...'}
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 3px;">
                        <span style="font-weight: bold;">${item.specimenCode2 || 'ASTM C39/C39N-21'}</span>
                        <span style="font-weight: bold;">R.S @${item.requiredStrength || '28:25 MPa'}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; font-size: 9px;">
                        <span>${item.clientMixInfo || 'SAUDI EDY MIX'}</span>
                        <span>Casting Date@ ${item.castingDate || 'N/A'}</span>
                    </div>

                </div>
            `;
        } else {

            printContentHTML += `<div class="barcode-A4-item" style="font-family: Arial, sans-serif;">... A4 Format ...</div>`;
        }
    });

    // 3. وضع المحتوى في حاوية الطباعة وإطلاق الطباعة
    let printContainer = document.getElementById('barcode-print-container');
    if (!printContainer) {
        printContainer = document.createElement('div');
        printContainer.id = 'barcode-print-container';
        document.body.appendChild(printContainer);
    }

    printContainer.innerHTML = printContentHTML;

    initializePrintStyles(printContainer.id);
    window.print();

    // إزالة المحتوى بعد الطباعة
    setTimeout(() => {
        printContainer.innerHTML = '';
    }, 500);
}

/**
 * إضافة الأنماط اللازمة لطباعة محتوى معين فقط.
 * @param {string} containerId - مُعرّف (ID) الحاوية التي نريد طباعتها.
 */
function initializePrintStyles(containerId) {
    let printStyle = document.getElementById('print-barcode-style');
    if (!printStyle) {
        printStyle = document.createElement('style');
        printStyle.id = 'print-barcode-style';
        printStyle.media = 'print'; // تنطبق فقط عند الطباعة
        document.head.appendChild(printStyle);
    }


    printStyle.textContent = `
        @media print {
            /* 1. إخفاء جميع محتوى جسم الصفحة الافتراضي */
           body {
    visibility: visible !important;
}

            /* الإضافة الهامة: التأكد من إظهار الجسم نفسه لكي يُستضيف المحتوى الجديد */
            body {
                visibility: visible !important;
            }

            /* 2. إظهار محتوى حاوية الطباعة فقط */
            #${containerId} {
                visibility: visible !important;
                display: block !important;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            /* 3. تطبيق الإظهار على جميع العناصر داخل حاوية الطباعة */
            #${containerId}, #${containerId} * {
                visibility: visible !important;
            }

            /* لإعداد حجم الملصقات على ورقة A4 أو حجم مخصص */
            .barcode-label {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    `;
}
function generateSpecimenBarcodePDF(specimenObject, printerType) {

    const canvas = document.createElement('canvas');
    JsBarcode(canvas, specimenObject.specimenID, {
        format: "CODE128",
        displayValue: true,
        fontSize: 10,
        margin: 5,
        width: 1.5, // سُمك الخطوط
        height: 35
    });

    const barcodeDataURL = canvas.toDataURL("image/png");

    let doc;

    if (printerType === 'Label') {



        doc = new jsPDF({
            unit: 'mm',
            format: [50, 25]
        });


        doc.setFontSize(8);
        doc.text(specimenObject.specimenID, 3, 4);
        doc.text(specimenObject.mixDetailId || 'N/A', 3, 8);

        doc.addImage(barcodeDataURL, 'PNG', 3, 10, 44, 12);

    } else if (printerType === 'A4') {


        doc = new jsPDF('p', 'mm', 'a4');
        const xOffset = 10;
        let yOffset = 15;

        // العنوان
        doc.setFontSize(16);
        doc.text('Specimen Barcode Sheet', 105, yOffset, null, null, 'center');
        yOffset += 10;

        // التفاصيل
        doc.setFontSize(10);
        doc.text(`Specimen ID: ${specimenObject.specimenID}`, xOffset, yOffset);
        doc.text(`Mix Details: ${specimenObject.mixDetailId}`, xOffset, yOffset + 5);
        doc.text(`Order No: ${specimenObject.orderNo}`, xOffset, yOffset + 10);
        yOffset += 20;

        // الباركود
        doc.addImage(barcodeDataURL, 'PNG', xOffset, yOffset, 100, 30);
    }


    if (doc) {

        doc.autoPrint();


        window.open(doc.output('bloburl'), '_blank');


    }
}

/**
 * دالة وسيطة لطباعة جميع الباركودات بعد اختيار نوع الطابعة.
 * تقوم بتوجيه الطباعة إلى دالة الطباعة المباشرة عبر HTML/CSS.
 * @param {string} printerType - نوع الطابعة المختارة ('Label', 'A4', أو 'default').
 */
function printAllBarcodesForSelection(printerType) {

    const allItems = getGeneratedItemsData();

    if (allItems.length === 0) {

        return alert('لا توجد عينات/آيتمز لتوليد الباركود. يرجى إدخال البيانات أولاً.');
    }


    if (printerType === 'default') {

        alert('لطباعة ZDesigner مباشرة، ستحتاج إلى برنامج وسيط (مثل QZ Tray). سيتم تحويل الطباعة إلى تنسيق الملصقات (HTML Label) الآن.');

        printerType = 'Label';
    }

    printAllBarcodesInPage(printerType);
}





// =========================================================
function handleAction(actionName) {
    // 1. إغلاق القائمة المنسدلة بمجرد اختيار إجراء
    const dropdown = document.getElementById('actionDropdown');
    if (dropdown) { // تحقق إضافي لضمان وجود العنصر قبل محاولة إغلاقه
        dropdown.style.display = 'none';
    }

    console.log('Action selected:', actionName);

    switch (actionName) {
        case 'Preview Order':
            openPreviewTab();
            break;

        case 'Print Specimen Barcode':
            // جلب البيانات أولاً للتحقق مما إذا كان هناك أي شيء للطباعة
            const generatedItems = getGeneratedItemsData();

            if (generatedItems && generatedItems.length === 0) {
                return alert("يرجى إدخال وتوليد عناصر (Items) للطلب قبل محاولة طباعة الباركود.");
            }
            // فتح نافذة اختيار الطابعة مباشرة
            showGlobalPrinterModal();
            break;

        case 'Edit Order (Materials Editor)':
            openOrderModal(); // استدعاء دالة فتح نافذة التحرير
            break;

        default:
            // في حالة عدم مطابقة أي إجراء
            console.log(`Action: ${actionName} not recognized or implemented.`);
            break;
    }
}
//اغلاق
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('actionDropdown');
    const actionsBtn = document.getElementById('actions-btn');

    // إغلاق القائمة إذا لم يكن النقر على الزر أو داخل القائمة
    if (dropdown && dropdown.style.display === 'block' &&
        !actionsBtn.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = 'none';
    }
});



/**
 * دالة لفتح تبويب البريفيو وعرض التوثيق بداخله.
 * تُستدعى عند النقر على "Preview Order" من قائمة Actions.
 */
function openPreviewTab(event) {
    if (event) {
        event.preventDefault();
    }

    // 1. جمع البيانات والتحقق منها
    const registrationData = collectAllRegistrationData();
    //  تأكد من وجود العنصر 'order-no' في الصفحة
    const orderNo = document.getElementById('order-no')?.value ?? 'N/A';

    if (orderNo === 'N/A' || !registrationData) {
        alert('لا يمكن فتح التوثيق. يرجى إدخال رقم الطلب والبيانات أولاً.');
        return;
    }

    // 2. توليد محتوى البريفيو
    const previewContentHTML = generatePreviewContent(registrationData);


    const registrationContent = document.getElementById('registration-content');
    const registrationTabHeader = document.getElementById('registration-tab');
    const formFooterButtons = document.getElementById('form-footer-buttons');
    const subTabs = document.querySelectorAll('.sub-tab-content');

    if (registrationContent) {
        registrationContent.style.display = 'none';
    }
    if (registrationTabHeader) {
        registrationTabHeader.classList.remove('active-tab');
    }
    if (formFooterButtons) {
        formFooterButtons.style.display = 'none';
    }
    subTabs.forEach(tab => tab.style.display = 'none'); // إخفاء محتوى التبويبات الفرعية


    // 4. إظهار تبويب البريفيو وتفعيله
    const previewTabHeader = document.getElementById('preview-tab-header');
    const previewContainer = document.getElementById('preview-content');
    const contentArea = document.getElementById('preview-content-area');

    // إظهار رأس تبويب البريفيو وتفعيله
    if (previewTabHeader) {
        previewTabHeader.style.display = 'flex';
        previewTabHeader.classList.add('active-tab');
    }

  //حاويات البريفيو
    if (previewContainer) {
        previewContainer.style.display = 'block';
    }
    if (contentArea) {
        contentArea.style.display = 'block';
        contentArea.innerHTML = previewContentHTML; // ملء المحتوى
    }

    // 5. تحديث عنوان التبويب
    const previewTitle = document.getElementById('preview-tab-title');
    if (previewTitle) {
        previewTitle.innerText = `Preview: ${orderNo}`;
    }

    // 6. تفعيل القسم الأول (بيانات الطلب) كقسم افتراضي عند الفتح
    const initialNavItem = document.querySelector('.preview-sub-navbar .preview-nav-item');
    if (initialNavItem) {
        // افتراض أن أول عنصر تنقل هو للقسم 'preview-order-info'
        showPreviewSection('preview-order-info', initialNavItem);
    }
}
function buildDataRows(data, keys) {
    let rows = '';
    keys.forEach(key => {
        const displayKey = key.replace(/([A-Z])/g, ' $1').toUpperCase();
        const value = data[key] ?? 'N/A';
        rows += `<tr>
            <td style="padding: 8px; background-color: #f8f9fa; font-weight: bold; width: 30%;">${displayKey}</td>
            <td style="padding: 8px;">${value}</td>
        </tr>`;
    });
    return rows;
}

// دالة للتحكم في عرض قسم معين داخل صفحة البريفيو
function showPreviewSection(sectionId, element) {
    // 1. إخفاء كل الأقسام وعرض القسم المطلوب فقط
    document.querySelectorAll('.preview-section').forEach(section => {
        section.style.display = 'none';
    });
    document.getElementById(sectionId).style.display = 'block';

    // 2. تحديث التبويبات الفرعية (شريط التنقل)
    document.querySelectorAll('.preview-nav-item').forEach(item => {
        item.classList.remove('active-nav-item');
    });
    element.classList.add('active-nav-item');
}
let isBrandingApplied = false;
const ACTIVE_COLOR = '#FFA500'; // برتقالي
const INACTIVE_COLOR = '#44474B'; // رمادي/أسود

function applyCompanyBranding() {
    const reportPage = document.getElementById('report-page-container');
    const brandingBtn = document.getElementById('apply-branding-btn');
    const brandingIcon = brandingBtn ? brandingBtn.querySelector('i') : null;

    if (!reportPage || !brandingIcon) return;

    // --------------------------------------------------------------------------
    // محتوى الرأس الثابت للشركة (A4-Compatible Header)
    // --------------------------------------------------------------------------
    const companyHeaderHTML = `
        <div id="company-header-static" style="
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            /* الهوامش (10mm هو الهامش الافتراضي لصفحة A4) */
            padding: 10mm 10mm 0 10mm;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        ">
            <header style="
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                font-size: 9px;
                margin-bottom: 5px;
            ">
                <div style="flex: 1; text-align: center; line-height: 1.2;">
                    <p style="margin: 0; font-style: italic; font-size: 1em;">Office of</p>
                    <p style="margin: 0; font-weight: bold; font-size: 1em;">Abdulkarim Abdullah Almansour For Engineering Consultings</p>
                    <p style="margin: 0; font-weight: bold; font-style: italic; font-size: 1em;">Engineering License # 3448, C.R. # 4030279674</p>
                </div>

                <div style="flex-shrink: 0; text-align: center; padding: 0 10px;">
                    <div style="width: 100%; height: 40px; text-align: center;">
                        [AAM Logo Image]
                    </div>
                </div>

                <div dir="rtl" style="flex: 1; text-align: center; line-height: 1.2;">
                    <p style="margin: 0; font-size: 1em;">مكتب</p>
                    <p style="margin: 0; font-weight: bold; font-size: 1em;">عبدالكريم عبدالله المنصور للاستشارات الهندسية</p>
                    <p style="margin: 0; font-weight: bold; font-style: italic; font-size: 1em;">ترخيص هندسي رقم 3448، سجل تجاري رقم 4030279674</p>
                </div>
            </header>

            <div style="
                border-bottom: 2px solid #007BFF; /* أزرق داكن */
                /* عرض 100% يغطي كامل العرض بعد تطبيق الهوامش */
                width: 100%;
                margin: 5px 0 0 0;
                display: block;
            "></div>
        </div>
    `;

    // --------------------------------------------------------------------------
    // محتوى التذييل الثابت للشركة (A4-Compatible Footer)
    // --------------------------------------------------------------------------
    const companyFooterHTML = `
        <div id="company-footer-static" style="
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            /* الهوامش */
            padding: 0 10mm 10mm 10mm;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        ">
            <div style="
                border-top: 30px solid #000080; /* أزرق داكن */
                width: 100%;
                margin: 15px 0 5px 0;
                display: block;
            "></div>

            <footer style="
                display: flex;
                justify-content: space-between;
                align-items: flex-end;
                font-size: 10px;
                color: #333;
            ">
                <div style="flex: 1; text-align: left; line-height: 1.2;">
                    <p style="margin: 0;">Report No.: [Report Number Placeholder]</p>
                    <p style="margin: 0;">Page 1 of 1</p>
                </div>

                <div style="flex-shrink: 0; text-align: right;">
                    <div style="width: 120px; height: 30px; border: 1px solid #000; text-align: center; line-height: 30px; font-size: 10px; font-weight: bold;">
                        [Barcode Placeholder]
                    </div>
                </div>
            </footer>
        </div>
    `;
    // --------------------------------------------------------------------------

    isBrandingApplied = !isBrandingApplied;

    // 1. التنظيف أولاً للتأكد من عدم التكرار
    document.getElementById('company-header-static')?.remove();
    document.getElementById('company-footer-static')?.remove();

    if (isBrandingApplied) {
        // 2. تطبيق (إضافة) الرأس والتذييل
        reportPage.insertAdjacentHTML('afterbegin', companyHeaderHTML); // إضافة الرأس في البداية
        reportPage.insertAdjacentHTML('beforeend', companyFooterHTML);  // إضافة التذييل في النهاية


        brandingIcon.style.color = ACTIVE_COLOR;
        brandingBtn.title = "Company Branding Applied (Click to Remove)";

    } else {
        // إلغاء تفعيل العلامة التجارية

        brandingIcon.style.color = INACTIVE_COLOR;
        brandingBtn.title = "Apply Company Header/Footer";
    }
}
/**
 * دالة توليد محتوى البريفيو ليطابق هيكل تقرير "Crushing Testing Order" (A4)
 * مع شريط أدوات تحكم متقدم وخلفية رمادية.
 *
 * @param {Object} data - بيانات التسجيل المُجمّعة.
 * @returns {string} - كود HTML الكامل لصفحة المعاينة.
 */
function generatePreviewContent(data) {
    // ----------------------------------------------------
    // 1. هيكل جدول معلومات الطلب الرئيسي (الجدول العلوي)
    // ----------------------------------------------------
    const dataFields = [
        { label: 'PROJECT', valueKey: 'project' }, { label: 'CUSTOMER', valueKey: 'customer' },
        { label: 'ORDER DATE', valueKey: 'orderDate' }, { label: 'ORDER NO.', valueKey: 'orderNo' },
        { label: 'CONTACT', valueKey: 'contact' }, { label: 'QUANTITY', valueKey: 'quantity' },
        { label: 'BROUGHT BY', valueKey: 'broughtBy' }, { label: 'RECEIVED BY', valueKey: 'receivedBy' },
        { label: 'CONCRETE SUPPLIE', valueKey: 'concreteSupplie' }, { label: 'DATE RECVD.', valueKey: 'dateRecvd' },
        { label: 'LOCATION', valueKey: 'location' }, { label: 'CLIENT REF.', valueKey: 'clientRef' },
        { label: 'SAMPLE TYPE', valueKey: 'sampleType' }, { label: 'SUPPLIER NAME', valueKey: 'supplierName' },
        { label: 'RFI', valueKey: 'rfi' }, { label: 'CASTING DATE', valueKey: 'castedAt' },
        // تم إضافة مسافة لتجنب دمج النص مع قيمة الحقل في السطر الأخير
       // الشيفرة المُعدَّلة (أو اتركها كما هي واستخدم التعديل في الخطوة 2)
{ label: 'Tests on Sample:', valueKey: 'orderNo', isFullRow: true }
    ];

    let headerTableContent = '';
    let i = 0;
    while (i < dataFields.length) {
        const item1 = dataFields[i];
       if (item1.isFullRow) {
        // يتم استخراج رقم العينة من بيانات التسجيل (data)
        const sampleRefValue = data[item1.valueKey] || 'N/A';

        // يتم بناء النص الكامل المطلوب
        const fullRowText = `Tests on Sample: ${sampleRefValue}`;

        // إضافة الصف إلى الجدول
        headerTableContent += `
            <tr>
                <td class="header-label full-row" colspan="4" style="border: 1px solid #000; padding: 4px; font-size: 11px; text-align: left; background-color: white; font-weight: bold;">${fullRowText}</td>
            </tr>
        `;
        i++;
        continue;
    }

        const item2 = dataFields[i + 1] || {};

        // تطبيق الحدود والمحاذاة كما في الصورة
        headerTableContent += `
            <tr>
                <td class="header-label" style="width: 20%; border: 1px solid #000; padding: 4px; font-weight: bold; font-size: 11px; background-color: #f8f8f8;">${item1.label}</td>
                <td class="header-value" style="width: 30%; border: 1px solid #000; padding: 4px; font-size: 11px;">${data[item1.valueKey] || 'N/A'}</td>
                <td class="header-label" style="width: 20%; border: 1px solid #000; padding: 4px; font-weight: bold; font-size: 11px; background-color: #f8f8f8;">${item2.label || ''}</td>
                <td class="header-value" style="width: 30%; border: 1px solid #000; padding: 4px; font-size: 11px;">${data[item2.valueKey] || ''}</td>
            </tr>
        `;
        i += 2;
    }

    const headerTable = `<table class="report-main-table" dir="ltr" style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">${headerTableContent}</table>`;

    // ----------------------------------------------------
    // 2. جدول بيانات الاختبارات (الجدول الأوسط)
    // ----------------------------------------------------
    let testTable = `
        <div class="test-section" style="border: 1px solid #000; margin-bottom: 5px;">
            <table class="test-data-table" dir="ltr" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr class="test-header-row" style="background-color: #f0f0f0; font-size: 11px;">
                        <th style="width: 5%; border: 1px solid #000; padding: 4px;">#</th>
                        <th style="width: 35%; text-align: left; border: 1px solid #000; padding: 4px;">TEST DESCRIPTION</th>
                        <th style="width: 10%; border: 1px solid #000; padding: 4px;">AGE</th>
                        <th style="width: 15%; border: 1px solid #000; padding: 4px;">METHOD</th>
                        <th style="width: 15%; border: 1px solid #000; padding: 4px;">Expected Testing Date</th>
                        <th style="width: 10%; border: 1px solid #000; padding: 4px;">UNIT</th>
                        <th style="width: 10%; border: 1px solid #000; padding: 4px;">QTY</th>
                    </tr>
                </thead>
                <tbody>
    `;

    // استخدام data.testData لملء صفوف الاختبار
    if (data.testData && Array.isArray(data.testData) && data.testData.length > 0) {
        data.testData.forEach((item, index) => {
            testTable += `
                <tr class="test-data-row" style="font-size: 11px;">
                    <td style="padding: 4px; border: 1px solid #000;">${index + 1}.</td>
                    <td style="padding: 4px; text-align: left; border: 1px solid #000;">${item.testDescription || 'N/A'}</td>
                    <td style="padding: 4px; border: 1px solid #000;">${item.rAge || 'N/A'}</td>
                    <td style="padding: 4px; border: 1px solid #000;">${item.testMethod || 'N/A'}</td>
                    <td style="padding: 4px; border: 1px solid #000;">${item.expectedTestingDate || 'N/A'}</td>
                    <td style="padding: 4px; border: 1px solid #000;">${item.unit || 'NO.'}</td>
                    <td style="padding: 4px; border: 1px solid #000;">${item.qty || 'N/A'}</td>
                </tr>
            `;
        });
    } else {
        testTable += `<tr><td colspan="7" style="text-align: center; padding: 10px; border: 1px solid #000;">لا توجد بيانات اختبارات مضافة.</td></tr>`;
    }
    testTable += `</tbody></table></div>`;

   // ----------------------------------------------------
// 3. قسم الملاحظات والتوقيعات (الجدول السفلي) - مُعدّل ليُطابق الصورة
// ----------------------------------------------------
const footerSection = `
    <div class="footer-sections">
        <table class="remarks-notes-table" dir="ltr" style="width: 100%; border-collapse: collapse; margin-bottom: 5px; font-size: 11px;">
            <tbody>
                <tr>
                    <td style="width: 10%; border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f0f0f0;">REMARKS</td>
                    <td style="width: 90%; border: 1px solid #000; padding: 4px;">${data.remarks || 'Nil'}</td>
                </tr>
                <tr>
                    <td style="width: 10%; border: 1px solid #000; padding: 4px; font-weight: bold; background-color: #f0f0f0;">NOTES</td>
                    <td style="width: 90%; border: 1px solid #000; padding: 4px;">${data.notes || 'N.P.'}</td>
                </tr>
            </tbody>
        </table>

        <table class="signature-table" dir="ltr" style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-size: 11px; text-align: center;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th style="width: 15%; border: 1px solid #000; padding: 4px;"></th>
                    <th style="width: 25%; border: 1px solid #000; padding: 4px;">Representative Name</th>
                    <th style="width: 20%; border: 1px solid #000; padding: 4px;">Tel/Mobile</th>
                    <th style="width: 25%; border: 1px solid #000; padding: 4px;">Email</th>
                    <th style="width: 15%; border: 1px solid #000; padding: 4px;">Signature</th>
                </tr>
            </thead>
            <tbody>
                <tr style="height: 30px;">
                    <td style="text-align: left; font-weight: bold; padding-left: 5px; border: 1px solid #000;">CUSTOMER</td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                </tr>
                <tr style="height: 30px;">
                    <td style="text-align: left; font-weight: bold; padding-left: 5px; border: 1px solid #000;">CONSULTANT</td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                </tr>
                <tr style="height: 30px;">
                    <td style="text-align: left; font-weight: bold; padding-left: 5px; border: 1px solid #000;">CONTRACTOR</td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                </tr>
                <tr style="height: 30px;">
                    <td style="text-align: left; font-weight: bold; padding-left: 5px; border: 1px solid #000;">OWNER</td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                    <td class="signature-cell" style="border: 1px solid #000;"></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="sample-receipt-box" style="border: 1px solid #000; padding: 5px; margin-top: 5px; display: flex; justify-content: space-between; align-items: flex-end; font-size: 11px;">
        <div>
            <h4 style="margin: 0 0 5px 0; font-size: 12px;">SAMPLE RECEIPT/CONFIRMATION:</h4>
          <p style="margin: 0;"><strong>NAME OF LAB REPRESENTATIVE:</strong> ${data.receivedBy || 'N/A'}</p>
            <p style="margin: 5px 0 0 0;"><strong>SIGNATURE:</strong> ____________________________</p>
            <p style="margin: 5px 0 0 0;"><strong>DATE:</strong> ____________________________</p>
        </div>
        <div class="barcode-placeholder" style="width: 200px; height: 80px; border: 1px solid #000; text-align: center; line-height: 80px; font-size: 12px; font-weight: bold;">
            [Barcode Image]
        </div>
    </div>
`;

const reportHeader = `
    <header class="report-header" style="
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        padding: 0 0;
        font-size: 9px;
    ">

        <div class="header-info-left" style="
            flex: 1;
            text-align: center; /* محاذاة في المنتصف */
            line-height: 1.2;
            padding-bottom: 5px;
        ">
            <p style="margin: 0; font-style: italic; font-size: 1em;">Office of</p>
            <p style="margin: 0; font-weight: bold; font-size: 1em;">Abdulkarim Abdullah Almansour For Engineering Consultings</p>
            <p style="margin: 0; font-style: italic; font-size: 1em;">Engineering consultings, Designing,</p>
            <p style="margin: 0; font-style: italic; font-size: 1em;">Supervision, Survey Works</p>
            <p style="margin: 0; font-weight: bold; font-style: italic; font-size: 1em;">Engineering License # 3448, J.C.C.L# 162903, C.R. # 4030279674</p>
        </div>

        <div class="header-logo-center" style="
            flex-shrink: 0;
            text-align: center;
            padding: 0 10px;
        ">
            <div style="width: 100%; height: 40px; text-align: center;">
                [AAM Logo Image]
            </div>
        </div>

        <div class="header-info-right" dir="rtl" style="
            flex: 1;
            text-align: center; /* محاذاة في المنتصف */
            line-height: 1.2;
            padding-bottom: 5px;
        ">
            <p style="margin: 0; font-size: 1em;">مكتب</p>
            <p style="margin: 0; font-weight: bold; font-size: 1em;">عبدالكريم عبدالله المنصور للاستشارات الهندسية</p>
            <p style="margin: 0; font-style: italic; font-size: 1em;">إستشارات هندسية - تصاميم معمارية</p>
            <p style="margin: 0; font-style: italic; font-size: 1em;">رفوعات مساحية - إشراف هندسي</p>
            <p style="margin: 0; font-weight: bold; font-style: italic; font-size: 1em;">ترخيص هندسي رقم 3448، سجل تجاري رقم 162903</p>
        </div>
    </header>

    <div style="
        border-bottom: 2px solid #007BFF; /* الخط الأزرق */
        margin: 5px 0 15px 0;
        width: 100%;
    "></div>

    <h1 style="
        font-size: 1.6em; /* تم تكبير الخط هنا إلى 1.6em */
        font-weight: bold;
        text-align: center;
        margin-top: 15px;
        margin-bottom: 20px;
        color: #333;
    ">Crushing Testing Order</h1>
`;
    // ----------------------------------------------------
    // 5. توليد شريط الأدوات المتقدم (Toolbar)
    // ----------------------------------------------------
    const advancedToolbar = `

        <div id="pdf-toolbar" class="pdf-toolbar" style="padding: 5px 15px; background-color:#E8E8E8; color: ${INACTIVE_COLOR}; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 1000;">
        <div class="toolbar-left" style="display: flex; gap: 10px;">

            <button id="apply-branding-btn" class="btn toolbar-btn" onclick="applyCompanyBranding()" style="background: none; border: none; cursor: pointer;" title="Apply Company Header/Footer">
                <i class="fas fa-certificate" style="color: ${INACTIVE_COLOR};"></i>
            </button>

            <button id="hand-tool-btn" class="btn toolbar-btn" onclick="toggleHandTool()" style="color: ${INACTIVE_COLOR}; background: none; border: none; cursor: pointer;">
                <i class="fas fa-hand-paper"></i>
            </button>

            <button id="print-btn" class="btn btn-print-pdf" onclick="printReportOnly()" style="color: ${INACTIVE_COLOR}; background: none; border: none; cursor: pointer;">
                <i class="fas fa-print"></i>
            </button>

            <button class="btn btn-close-preview" onclick="closePreviewTab()" style="color: ${INACTIVE_COLOR}; background: none; border: none; cursor: pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="toolbar-center" style="display: flex; align-items: center; gap: 5px;">


                <button class="btn toolbar-btn" onclick="zoomOut()" style="color: #44474B; background: none; border: none; cursor: pointer;">
                    <i class="fas fa-search-minus"></i>
                </button>
                <span id="zoom-level">100%</span>
                <button class="btn toolbar-btn" onclick="zoomIn()" style="color: #44474B; background: none; border: none; cursor: pointer;">
                    <i class="fas fa-search-plus"></i>
                </button>
            </div>

            <div class="toolbar-right" style="display: flex; align-items: center; gap: 5px;">
                <button class="btn toolbar-btn" onclick="goToPreviousPage()" style="color: #44474B; background: none; border: none; cursor: pointer;"><i class="fas fa-chevron-left"></i></button>
                <span id="page-number">1 / 1</span>
                <button class="btn toolbar-btn" onclick="goToNextPage()" style="color: #44474B; background: none; border: none; cursor: pointer;"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    `;

    // ----------------------------------------------------
    // 6. تجميع المحتوى وإعادته (الهيكل الكامل)
    // ----------------------------------------------------
    return `
        ${advancedToolbar}

        <div id="preview-bg-container" class="preview-background-container" style="background-color: #f0f0f0; padding: 30px 0; display: flex; justify-content: center; min-height: 100vh;">
            <div id="report-page-container" class="report-page-container" style="
                background-color: white;
                width: 210mm; /* عرض A4 */
                min-height: 297mm; /* ارتفاع A4 */
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                padding: 10mm; /* مساحة الهامش الداخلية */
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 0;
                transform: scale(1.0);
                transform-origin: top center;
            ">

                ${reportHeader}
                ${headerTable}
                ${testTable}
                ${footerSection}
            </div>
        </div>
    `;
}
let isHandToolActive = false;

function toggleHandTool() {
    // استهداف العنصر الذي يحتوي على التقرير (أو الجسم بأكمله)
    const targetElement = document.getElementById('preview-bg-container') || document.body;
    const handToolBtn = document.getElementById('hand-tool-btn');

    if (targetElement) {
        isHandToolActive = !isHandToolActive;

        if (isHandToolActive) {


            targetElement.style.cursor = '-webkit-grab';


            targetElement.style.cursor = 'grab';

            // تمييز الزر
            if (handToolBtn) {
                handToolBtn.style.backgroundColor = '#6c757d';
            }
        } else {
            // العودة للوضع الافتراضي
            targetElement.style.cursor = 'default';

            if (handToolBtn) {
                handToolBtn.style.backgroundColor = 'transparent';
            }
        }
    }
}


let currentZoom = 1.0; // 100%
const zoomStep = 0.1;  // 10%

function updateZoomDisplay() {
    // استخدم ID الحاوية الجديدة
    const reportPage = document.getElementById('report-page-container');
    const zoomLevelSpan = document.getElementById('zoom-level');

    if (reportPage) {
        reportPage.style.transform = `scale(${currentZoom})`;
        reportPage.style.transformOrigin = 'top center';
    }

    if (zoomLevelSpan) {
        zoomLevelSpan.innerText = `${Math.round(currentZoom * 100)}%`;
    }
}

function zoomIn() {
    if (currentZoom < 2.0) {
        currentZoom += zoomStep;
        updateZoomDisplay();
    }
}

function zoomOut() {
    if (currentZoom > 0.5) {
        currentZoom -= zoomStep;
        updateZoomDisplay();
    }
}

// الدوال التالية تحتاج منطقاً كاملاً لتطبيقها، نتركها وهمية للآن:
function toggleHandTool() { console.log('Hand Tool activated (requires advanced CSS/JS)'); }
function goToPreviousPage() { console.log('Go to previous page (requires multi-page logic)'); }
function goToNextPage() { console.log('Go to next page (requires multi-page logic)'); }


function printReportOnly() {
    // كود CSS @media print الخارجي يتولى إخفاء كل شيء وإظهار التقرير فقط.
    window.print();
}

/**
 * دالة للتبديل بين محتوى التبويبات الفرعية (ضمن صفحة التسجيل).
 */
function showSubTab(tabId) {

    // ----------------------------------------------------------------------

    // 1. إخفاء محتوى جميع التبويبات الفرعية وإلغاء تنشيطها
    const contents = document.querySelectorAll('.sub-tab-content');
    contents.forEach(content => {
        content.style.display = 'none';
        content.classList.remove('active-sub-tab');
    });

    const headers = document.querySelectorAll('.sub-tab-header');
    headers.forEach(header => {
        header.classList.remove('active-sub-tab');
    });

    // 2. إظهار التبويب المطلوب وتنشيطه
    const selectedContent = document.getElementById(tabId);
    if (selectedContent) {
        selectedContent.style.display = 'block';
        selectedContent.classList.add('active-sub-tab');
    }

    const selectedHeader = document.querySelector(`.sub-tab-header[onclick*="${tabId}"]`);
    if (selectedHeader) {
        selectedHeader.classList.add('active-sub-tab');
    }

        document.getElementById('form-footer-buttons').style.display = 'flex';
}
/**
 * دالة لتبديل المحتوى الرئيسي بين تبويب التسجيل والبريفيو عند النقر على الرأس.
 */
function switchMainTab(targetTabName) {
    const registrationTab = document.getElementById('registration-tab');
    const previewTab = document.getElementById('preview-tab-header');
    const registrationContent = document.getElementById('registration-content');
    const previewContent = document.getElementById('preview-content');
    const previewContentArea = document.getElementById('preview-content-area'); //  إضافة هذا المتغير

    // إزالة حالة التفعيل من جميع التبويبات وإخفاء محتواها
    registrationTab?.classList.remove('active-tab');
    previewTab?.classList.remove('active-tab');
    registrationContent.style.display = 'none';
    previewContent.style.display = 'none';
    //  إخفاء محتوى البريفيو الداخلي بشكل افتراضي
    previewContentArea.style.display = 'none';

    if (targetTabName === 'registration') {
        registrationTab?.classList.add('active-tab');
        registrationContent.style.display = 'block';



        // إظهار أول تبويب فرعي
        showSubTab('compressive-strength');

    } else if (targetTabName === 'preview') {
        previewTab?.classList.add('active-tab');
        previewContent.style.display = 'block';

        //  الخطوة المفقودة: إظهار المحتوى الداخلي للبريفيو
        previewContentArea.style.display = 'block';
    }
}

/**
 * دالة لإغلاق تبويب البريفيو، العودة تلقائياً لتبويب التسجيل (لتجربة مستخدم أسهل).
 */
function closePreviewTab(event) {
    if (event) {
        // منع النقر على 'X' من تفعيل وظيفة 'switchMainTab' على العنصر الأب
        event.stopPropagation();
        event.preventDefault();
    }

    // 1. إخفاء البريفيو
    document.getElementById('preview-content').style.display = 'none';
    document.getElementById('preview-tab-header').style.display = 'none';
    document.getElementById('preview-tab-header').classList.remove('active-tab');
document.getElementById('preview-content-area').style.display = 'none';
    // 2. العودة التلقائية لصفحة التسجيل وتفعيلها
    document.getElementById('registration-content').style.display = 'block';
    document.getElementById('registration-tab').classList.add('active-tab');

    //  الحل الهيكلي: إظهار التبويب الفرعي الأول ('compressive-strength')
    showSubTab('compressive-strength');

    // 3. التأكد من ظهور الشريط السفلي
    document.getElementById('form-footer-buttons').style.display = 'flex';
}


/**
 * دالة لإغلاق نموذج التسجيل بالكامل (تعود لشاشة اختيار الفئة/المودال).
 */
function closeRegistrationTab(event) {
    if (event) {
        // منع النقر على 'X' من تفعيل وظيفة 'switchMainTab' على العنصر الأب
        event.stopPropagation();
        event.preventDefault();
    }

    document.getElementById('registration-content').style.display = 'none';

    // إخفاء رؤوس التبويبات بالكامل عند إغلاق نموذج العمل
    document.getElementById('main-tab-headers').style.display = 'none';

    document.getElementById('form-footer-buttons').style.display = 'none';
    resetAndDisableForm();

    // يجب استدعاء دالة فتح المودال للعودة إلى شاشة اختيار الفئة
    // openRegistrationCategoryModal();
}

document.addEventListener('DOMContentLoaded', initializeTestTable);



function openOrderModal() {
    const modal = document.getElementById('orderModal');
    if (!modal) return;

    modal.style.display = 'flex';
    modal.style.justifyContent = 'center';
    modal.style.alignItems = 'center';


    const orderNumber = generateSequentialCode('AAM-CO');

    // جلب التاريخ
    const orderDateInputFromRegistration = document.getElementById('order-date');
    const orderDate = orderDateInputFromRegistration && orderDateInputFromRegistration.value
                      ? orderDateInputFromRegistration.value
                      : new Date().toISOString().split('T')[0];



    const modalOrderNoInput = document.getElementById('modal-order-no');
    if (modalOrderNoInput) {
        modalOrderNoInput.value = orderNumber;
    }


    const modalOrderDateInput = document.getElementById('order-date');
    if (modalOrderDateInput) {
        modalOrderDateInput.value = orderDate;
    }


    const tabTitleElement = document.getElementById('tab-order-header');
    if (tabTitleElement) {
        tabTitleElement.textContent = `Order Header - ${orderNumber}`;
    }

    // 3. تعيين تاريخ التقرير المتوقع
    const expReportDateInput = document.getElementById('expReportDate');
    if (expReportDateInput && !expReportDateInput.value) {
         expReportDateInput.value = orderDate;
    }
}
// **دالة إغلاق النافذة**
function closeOrderModal() {
   if (event) {
        event.preventDefault();
    }
    document.getElementById('orderModal').style.display = 'none';
}
/**
 * دالة لحفظ بيانات الطلب من النافذة المنبثقة.
 * هذه الدالة تقوم بجمع كافة البيانات المدخلة والقابلة للتعديل للحفظ.
 * * @param {boolean} close - صحيح إذا كان يجب إغلاق النافذة بعد الحفظ. القيمة الافتراضية هي false (حفظ فقط).
 */
function saveOrderData(close = false) {

    const getMockValue = (id) => {
        const element = document.getElementById(id);
        return element ? element.value : (id === 'modal-order-no' ? 'ORD-001' : 'N/A');
    };

    const getMockChecked = (id) => {
        const element = document.getElementById(id);
        return element ? element.checked : true;
    };

    const orderData = {
        orderNo: getMockValue('modal-order-no'),
        orderDate: getMockValue('order-date'),
        projectCode: getMockValue('projectCode'),

    };


    const termsData = {
        salesTax: parseFloat(getMockValue('salesTax') || 0),
        discount: parseFloat(getMockValue('discount') || 0),
        paymentTerms: getMockValue('paymentTerms'),
        currency: getMockValue('currency'),
        showPrices: getMockChecked('showPrices')
    };

    //  جمع بيانات العملات
    const currencyData = {
        enteredCurrency: getMockValue('enteredCurrency'),
        localCurrency: getMockValue('localCurrency'),
    };

    //  دمج جميع البيانات
    const fullOrderPayload = {
        ...orderData,
        ...termsData,
        ...currencyData,

    };


    console.log("Order Data Saved Successfully:", fullOrderPayload);

    alert(`تم حفظ الطلب رقم: ${fullOrderPayload.orderNo} بنجاح.`);


    if (close) {
        closeOrderModal();
    }
}





function showActionsMenu() {
    console.log("Showing actions menu for the order.");

}

/**
 * تتحكم في التبديل بين تبويبات Order Header
 * @param {string} contentId - معرّف (ID) المحتوى المراد إظهاره.
 */
function switchOrderTab(contentId) {

    const allContents = document.querySelectorAll('.order-tab-content');
    allContents.forEach(content => {
        content.style.display = 'none';
    });


    const allTabs = document.querySelectorAll('.order-header-tabs .tab');
    allTabs.forEach(tab => {
        tab.classList.remove('active');
    });


    const selectedTab = document.querySelector(`[onclick*="${contentId}"]`);
    if (selectedTab) {
        selectedTab.classList.add('active');
    }


    const targetContent = document.getElementById(contentId);
    if (targetContent) {
        targetContent.style.display = 'block';
    }
}
// إضافة حدث النقر على الخلفية لإغلاق النافذة
window.onload = function() {
    const modal = document.getElementById('orderModal');
    if (modal) {
        modal.addEventListener('click', (event) => {
            // الإغلاق فقط إذا تم النقر على الخلفية (وليس داخل محتوى النافذة)
            if (event.target === modal) {
                closeOrderModal();
            }
        });
    }
};
