// quotation.js

// =====================================================================
// Global Variable for DataTables Instance
// This will hold the reference to your main DataTable
// =====================================================================
// 1. تعريف المتغيرات العامة (فقط مرة واحدة لكل متغير)
// =====================================================================

let quotationDataTable;
let quotationLinesDataTable;
let priceListDataTable;
let currentEditingRow = null;
let currentQuotationId = null; // تعريف المتغير العام
let lastQuotationNumbers = {
    'proposal_geotechnical': 1000,
    'proposal_material_testing': 2000
};

// =====================================================================
// DOM Element Cache
// This object holds references to all necessary DOM elements.
// Make sure the IDs in your HTML match these.
// =====================================================================
const DOM = {
    quotationModal: document.getElementById("quotationModal"),
    quotationForm: document.getElementById("quotationForm"),


    // --- Header Info Section ---
    quoteCategory: document.getElementById('quoteCategory'),
    showCategoryListBtn: document.getElementById('showCategoryListBtn'),
    categoryDropdown: document.getElementById('categoryDropdown'),
    quoteNo: document.getElementById('quoteNo'),
    quoteRev: document.getElementById('quoteRev'),
    quoteDate: document.getElementById('quoteDate'),
    quoteLegacyNo: document.getElementById('quoteLegacyNo'),
    quoteLegacyDate: document.getElementById('quoteLegacyDate'),
    quoteProjectDetails: document.getElementById('quoteProjectDetails'),
    quoteSubject: document.getElementById('quoteSubject'),

    // --- Project/Customer Info Section ---
    quoteProjectCodeInput: document.getElementById('quoteProjectCodeInput'), // هذا هو حقل إدخال كود المشروع
    projectCodeDropdown: document.getElementById('projectCodeDropdown'), // القائمة المنسدلة لأكواد المشاريع
    showProjectCodeListBtn: document.getElementById('showProjectCodeListBtn'), // زر إظهار قائمة أكواد المشاريع
    quoteCustomer: document.getElementById('quoteCustomer'), // حقل العميل (customer)
    quoteProject: document.getElementById('quoteProject'), // **تعديل**: هذا هو حقل "Project Name" في HTML

    // --- Contact Info Section (تعريفات موحدة ومُعدّلة بناءً على HTML) ---
    quoteContactFrom: document.getElementById('quoteContactFrom'), // حقل "From"
    showEmployeesListBtnEmployee: document.getElementById('showEmployeesListBtnEmployee'), // زر إظهار قائمة الموظفين (متعلق بـ From)
    employeeDropdown: document.getElementById('employeeDropdown'), // القائمة المنسدلة للموظفين (متعلق بـ From)

    quoteInquiry: document.getElementById('quoteInquiry'), // حقل "Inquiry"
    quoteContactPerson: document.getElementById('quoteContactPerson'), // حقل اسم جهة الاتصال (contact)
    contactPersonDropdown: document.getElementById('contactPersonDropdown'), // القائمة المنسدلة لجهة الاتصال
    showContactPersonListBtn: document.getElementById('showContactPersonListBtn'), // زر إظهار قائمة جهات الاتصال

    quoteContactTo: document.getElementById('quoteContactTo'), // حقل "To" (Textarea)
    quoteAttnTo: document.getElementById('quoteAttnTo'), // حقل "Attn. To"
    quoteAttnPos: document.getElementById('quoteAttnPos'), // حقل "Attn. Pos"
    quoteContactEmail: document.getElementById('quoteContactEmail'), // حقل بريد جهة الاتصال
    quoteContactMobile: document.getElementById('quoteContactMobile'), // حقل جوال جهة الاتصال

    // --- Terms and Other Controls Section ---
    quoteDiscount: document.getElementById('quoteDiscount'),
    quoteVAT: document.getElementById('quoteVAT'),
    quoteValidity: document.getElementById('quoteValidity'),
    quoteCurrency: document.getElementById('quoteCurrency'),
    quotePaymentTermsInput: document.getElementById('quotePaymentTermsInput'),
    showPaymentTermsListBtn: document.getElementById('showPaymentTermsListBtn'),
    paymentTermsDropdown: document.getElementById('paymentTermsDropdown'),
    quoteMethod: document.getElementById('quoteMethod'),
    quoteUseAltForm: document.getElementById('quoteUseAltForm'),

    // --- Additional Info Section ---
    quoteRemarks: document.getElementById('quoteRemarks'),
    quoteQuoteFile: document.getElementById('quoteQuoteFile'), // **تعديل**: استخدام ID الموجود في HTML
    quoteFileStatus: document.getElementById('quoteFileStatus'),
    quoteDeclined: document.getElementById('quoteDeclined'),
    quoteDeclinedMessage: document.getElementById('quoteDeclinedMessage'),

    // --- Buttons & Main Table Elements (لا تغيير هنا) ---
    newQuotationBtn: document.getElementById('newQuotationBtn'),
    // Financials for Header Tab
    financialTotalLines: document.getElementById('financialTotalLines'),
    financialDiscountAmount: document.getElementById('financialDiscountAmount'),
    financialTaxAmount: document.getElementById('financialTaxAmount'),
    financialGrandTotal: document.getElementById('financialGrandTotal'),
    quoteOverallStatus: document.getElementById('quoteOverallStatus'),
    quoteLastConfirmation: document.getElementById('quoteLastConfirmation'),
    quoteLastConfirmed: document.getElementById('quoteLastConfirmed'),

    editQuotationBtn: document.getElementById('editQuotationBtn'),
    deleteQuotationBtn: document.getElementById('deleteQuotationBtn'),
    exportSelectedToExcel: document.getElementById('exportSelectedToExcel'),
    printSelectedRows: document.getElementById('printSelectedRows'),
    // داخل كائن DOM
masterQuotationCheckbox: document.getElementById('selectQuotations'),

    // Financials for Quote Lines Tab
    linesFinancialTotalLines: document.getElementById('linesFinancialTotalLines'),
    linesFinancialDiscountAmount: document.getElementById('linesFinancialDiscountAmount'),
    linesFinancialTaxAmount: document.getElementById('linesFinancialTaxAmount'),
    linesFinancialGrandTotal: document.getElementById('linesFinancialGrandTotal'),
    linesQuoteOverallStatus: document.getElementById('linesQuoteOverallStatus'),
    linesQuoteLastConfirmation: document.getElementById('linesQuoteLastConfirmation'),
    linesQuoteLastConfirmed: document.getElementById('linesQuoteLastConfirmed'),

    // Header Tab Specific Buttons
    saveHeaderTabBtn: document.getElementById('saveHeaderTabBtn'),
    closeHeaderTabBtn: document.getElementById('closeHeaderTabBtn'),
    saveAndCloseHeaderTabBtn: document.getElementById('saveAndCloseHeaderTabBtn'),

    // Quote Lines Tab Specific Buttons
    saveLinesTabBtn: document.getElementById('saveLinesTabBtn'),
    closeLinesTabBtn: document.getElementById('closeLinesTabBtn'),
    saveAndCloseLinesTabBtn: document.getElementById('saveAndCloseLinesTabBtn'),

// **** إضافة تعريفات DOM لأزرار التصدير والطباعة هنا ****
    exportToExcelBtn: document.getElementById('exportToExcelBtn'), // الزر الجديد
    printQuoteLinesBtn: document.getElementById('printQuoteLinesBtn'), // الزر الجديد


    // Main Quotation Table Elements
    masterCheckbox: document.getElementById('selectAllQuotations'),
    quotationTable: document.getElementById('quotationTable'),
    fixedPaginationContainer: document.getElementById('quotation-pagination-fixed-bottom'),

    // Quote Lines Table Elements
    quotationLinesTable: document.getElementById('quotationLinesTable'),
   selectAllLinesCheckbox: document.getElementById('selectAllLinesCheckbox'), // الآن سيتم العثور عليه
    // Price List Modal Elements
    priceListModal: document.getElementById("priceListModal"),
    priceListTable: document.getElementById('priceListTable'),
    priceListTableBody: document.getElementById('priceListTableBody'),
    addSelectedItemsBtn: document.getElementById('addSelectedItemsBtn'),
    selectAllPriceListItems: null, // Initialized dynamically
    priceListSearchInput: document.getElementById('priceListSearchInput'),
    priceListFilterType: document.getElementById('priceListFilterType'),
    priceListFilterMethod: document.getElementById('priceListFilterMethod'),
    clearPriceListFiltersBtn: document.getElementById('clearPriceListFiltersBtn'),
    refreshPriceListBtn: document.getElementById('refreshPriceListBtn'),
    priceListResetButtonContainer: document.getElementById('priceListResetButtonContainer'),
    newQuotationBtn: document.getElementById('newQuotationBtn'),
    savePriceBtn: document.getElementById("savePriceBtn"),
    addPriceModal : document.getElementById("addPriceModal"),
   openAddPriceBtn :  document.getElementById("btnOpenAddPrice"),
    closeAddPriceBtn : document.getElementById("closeAddPriceModalBtn"),
    cancelAddPriceBtn : document.getElementById("cancelAddPriceBtn"),


    // Dynamically added elements (like PDF button from initializeDynamicDOMElements)
    generatePdfButton: null,
};

// =====================================================================
// Helper Functions
// =====================================================================

/**
 * Marks or unmarks a form field's label as required/missing.
 * @param {HTMLElement} inputElement - The input element to check.
 * @param {boolean} isRequired - True to mark as required missing, false to remove.
 */
function markRequiredField(inputElement, isRequired) {
    const formGroup = inputElement.closest('.form-grid-2-col, .form-grid-3-col, .form-grid-2-col-sidebar');
    let label;
    if (formGroup) {
        label = formGroup.querySelector(`label[for="${inputElement.id}"]`);
    } else {
        label = document.querySelector(`label[for="${inputElement.id}"]`);
    }

    if (label) {
        if (isRequired) {
            label.classList.add('required-field-missing');
        } else {
            label.classList.remove('required-field-missing');
        }
    }
}

/**
 * Displays a toast notification message.
 * @param {string} message - The message to display.
 * @param {'success'|'error'|'info'} type - Type of toast (influences color).
 */
function showToast(message, type = 'info', duration = 3000) {
    // إنشاء أو الحصول على حاوية التوست
    const toastContainer = document.getElementById('toastContainer') || (() => {
        const div = document.createElement('div');
        div.id = 'toastContainer';
        div.style.position = 'fixed';
        div.style.top = '20px';
        div.style.right = '20px';
        div.style.zIndex = '10000';
        div.style.display = 'flex';
        div.style.flexDirection = 'column'; // لترتيب التوستات بشكل عمودي
        div.style.gap = '10px'; // مسافة بين التوستات
        document.body.appendChild(div);
        return div;
    })();

    // إنشاء عنصر التوست نفسه
    const toast = document.createElement('div');
    toast.classList.add('toast-message', type); // لإضافة فئة type للتنسيق
    toast.style.opacity = '0'; // يبدأ مخفياً
    toast.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
    toast.style.transform = 'translateY(-20px)'; // يبدأ من أعلى قليلاً لحركة دخول
    toast.style.minWidth = '250px';
    toast.style.maxWidth = '350px';
    toast.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
    toast.style.borderRadius = '8px';
    toast.style.overflow = 'hidden'; // لضمان عدم تجاوز المحتوى للحدود

    // تحديد عنوان التوست ولون الخلفية بناءً على النوع
    let title = '';
    let backgroundColor = '';
    let textColor = '#fff'; // لون النص الافتراضي

    switch (type) {
        case 'success':
            title = '!نجاح';
            backgroundColor = '#28a745'; // أخضر
            break;
        case 'error':
            title = '!خطأ';
            backgroundColor = '#dc3545'; // أحمر
            break;
        case 'warning':
            title = '!تحذير';
            backgroundColor = '#ffc107'; // أصفر (قد تحتاج لتغيير لون النص لأسود)
            textColor = '#343a40'; // نص داكن للأصفر
            break;
        case 'info':
        default:
            title = 'معلومة';
            backgroundColor = '#17a2b8'; // أزرق سماوي
            break;
    }

    toast.style.backgroundColor = backgroundColor;
    toast.style.color = textColor;

    // بناء محتوى التوست: رأس (عنوان + زر إغلاق) وجسم (الرسالة)
    toast.innerHTML = `
        <div style="
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            font-weight: bold;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            ${type === 'warning' ? 'color: #343a40;' : 'color: white;'} /* لون رأس التحذير */
        ">
            <span>${title}</span>
            <button class="toast-close-button" style="
                background: none;
                border: none;
                font-size: 1.2em;
                cursor: pointer;
                color: inherit; /* يرث اللون من الأب */
                padding: 0 5px;
            ">&times;</button>
        </div>
        <div style="padding: 15px; font-size: 0.95em;">
            ${message}
        </div>
    `;

    // إضافة التوست إلى الحاوية
    toastContainer.prepend(toast); // نضيفه في الأعلى ليظهر الجديد فوق القديم

    // ظهور التوست مع انتقال
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 50); // تأخير بسيط لتمكين الانتقال

    // إعداد مؤقت الاختفاء التلقائي
    const hideTimeout = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }, duration);

    // إضافة مستمع حدث لزر الإغلاق
    toast.querySelector('.toast-close-button').addEventListener('click', () => {
        clearTimeout(hideTimeout); // مسح مؤقت الإخفاء التلقائي
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    });
}
function showConfirmToast(message, onConfirm) {
    const toastContainer = document.getElementById('toastContainer') || (() => {
        const div = document.createElement('div');
        div.id = 'toastContainer';
        div.style.position = 'fixed';
        div.style.top = '20px';
        div.style.right = '20px';
        div.style.zIndex = '10000';
        div.style.display = 'flex';
        div.style.flexDirection = 'column';
        div.style.gap = '10px';
        document.body.appendChild(div);
        return div;
    })();

    const toast = document.createElement('div');
    toast.style.background = '#ffc107'; // لون تحذيري
    toast.style.color = '#343a40';
    toast.style.padding = '12px 20px';
    toast.style.borderRadius = '8px';
    toast.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
    toast.style.minWidth = '300px';
    toast.style.display = 'flex';
    toast.style.justifyContent = 'space-between';
    toast.style.alignItems = 'center';

    // نص الرسالة
    const msg = document.createElement('span');
    msg.textContent = message;
    toast.appendChild(msg);

    // زر التأكيد
    const btn = document.createElement('button');
    btn.textContent = 'تأكيد';
    btn.style.background = '#dc2626';
    btn.style.color = '#fff';
    btn.style.border = 'none';
    btn.style.padding = '6px 12px';
    btn.style.marginLeft = '10px';
    btn.style.borderRadius = '5px';
    btn.style.cursor = 'pointer';

    btn.onclick = () => {
        onConfirm(); // تنفيذ عملية الحذف
        toast.remove(); // إزالة التوست بعد الضغط
    };

    toast.appendChild(btn);
    toastContainer.prepend(toast);
}

// =========================================================
// استخدام دالة showToast:
// =========================================================

// showToast('تم الحفظ بنجاح!', 'success');
// showToast('حدث خطأ في جلب البيانات.', 'error');
// showToast('يرجى التحقق من المدخلات.', 'warning', 5000); // تحذير يختفي بعد 5 ثواني
// showToast('هذه رسالة معلومات عامة.', 'info');


// =====================================================================
// Initialize Dynamic DOM Elements
// =====================================================================
function initializeDynamicDOMElements() {
    // Setup file input for quote file
    if (DOM.quoteQuoteFileInput) {
        DOM.quoteQuoteFileInput.setAttribute('type', 'file');
        DOM.quoteQuoteFileInput.setAttribute('id', 'hiddenQuoteFileInput'); // Ensure it has an ID
        DOM.quoteQuoteFileInput.style.display = 'none';

        const fileInputGroup = DOM.quoteQuoteFileText ? DOM.quoteQuoteFileText.closest('.file-input-group') : null;
        if (fileInputGroup) {
            fileInputGroup.appendChild(DOM.quoteQuoteFileInput);
        } else {
            console.error("File input group for quoteQuoteFile not found. Cannot append hidden file input.");
        }
    }

    // Create and append Generate PDF button
    const generatePdfButton = document.createElement('button');
    generatePdfButton.setAttribute('type', 'button');
    generatePdfButton.classList.add('btn', 'btn-secondary', 'ms-2');
    generatePdfButton.innerHTML = '<i class="fas fa-file-pdf"></i> Create PDF';

    if (DOM.quoteFileStatus) {
        const parentDivForButton = DOM.quoteFileStatus.parentElement;
        if (parentDivForButton) {
            parentDivForButton.appendChild(generatePdfButton);
            console.log("Create PDF button added next to File Status.");
        } else {
            console.error("Parent element for quoteFileStatus not found to append Create PDF button.");
        }
    } else {
        console.error("quoteFileStatus element not found, cannot place Create PDF button.");
    }
    DOM.generatePdfButton = generatePdfButton; // Store reference

    // Disable autocomplete for all modal inputs
    if (DOM.quotationModal) {
        const modalInputs = DOM.quotationModal.querySelectorAll('input, textarea, select');
        modalInputs.forEach(input => {
            input.setAttribute('autocomplete', 'off');
        });
        console.log("Autocomplete disabled for all modal input fields.");
    }

    // Select all lines checkbox reference (DataTables might create it later)
    // DOM.selectAllLinesCheckbox is handled when DataTable is initialized for lines
}


// =====================================================================
// Modal Functions
// =====================================================================

/**
 * Opens the quotation modal and sets the active tab to 'Quote Header'.
 * Resets the form for a new entry.
 */
function openQuotationModal() {
    if (DOM.quotationModal) {
        DOM.quotationModal.style.display = "block";
        openTab(null, 'headerTab'); // Open Header tab by default
        resetQuotationForm(); // Clear form fields when opening for a new entry
        // Clear any previous validation marks
        document.querySelectorAll('.required-field-missing').forEach(label => {
            label.classList.remove('required-field-missing');
        });
        console.log("Quotation modal opened.");
    } else {
        console.error("Quotation modal element not found.");
    }
}

/**
 * Closes the quotation modal.
 */
function closeQuotationModal() {
    if (DOM.quotationModal) {
        DOM.quotationModal.style.display = "none";
        // Clear any validation marks when closing
        document.querySelectorAll('.required-field-missing').forEach(label => {
            label.classList.remove('required-field-missing');
        });
        resetQuotationForm(); // Optionally reset form fields on close
        console.log("Quotation modal closed.");
    }
}
// ===============================================
// 1. الدوال المساعدة (Helper Functions)
// ===============================================

// ===============================================
// 1. الدوال المساعدة (Helper Functions)
// ===============================================

/**
 * دالة مساعدة للحصول على الـ IDs لجميع الصفوف المحددة.
 * @returns {Array<string>} - مصفوفة تحتوي على IDs الصفوف المحددة.
 */
function getSelectedQuotationIds() {
    const selectedIds = [];
    if (typeof quotationDataTable === 'undefined' || quotationDataTable === null) {
        console.error("quotationDataTable غير معرف. لا يمكن الحصول على IDs.");
        return selectedIds;
    }

    quotationDataTable.rows().nodes().to$().find('input.select-row-checkbox:checked').each(function() {
        const rowData = quotationDataTable.row($(this).closest('tr')).data();
        if (rowData && rowData.id) {
            selectedIds.push(rowData.id);
        }
    });
    return selectedIds;
}

/**
 * دالة مساعدة للحصول على ID صف واحد فقط (لعملية التعديل).
 * @returns {string|null} - ID الصف المحدد أو null إذا لم يكن هناك صف واحد محدد.
 */
function getSingleSelectedQuotationId() {
    const selectedIds = getSelectedQuotationIds();
    if (selectedIds.length === 1) {
        return selectedIds[0];
    } else if (selectedIds.length > 1) {
        showToast("الرجاء تحديد عرض أسعار واحد فقط للتعديل.", "warning");
    } else {
        showToast("الرجاء تحديد عرض أسعار للتعديل.", "warning");
    }
    return null;
}

// ===============================================
// 2. دالة الطباعة (Print)
// ===============================================

/**
 * دالة لطباعة الصفوف المحددة من الجدول.
 * تُستخدم لفتح نافذة حوار الطباعة في نفس الصفحة.
 */
function printSelectedRows() {
     if (typeof quotationDataTable === 'undefined' || quotationDataTable === null) {
        showToast("خطأ: جدول عروض الأسعار غير مهيأ.", "error");
        return;
    }

    const selectedRowsData = quotationDataTable.rows().nodes().to$().find('input.select-row-checkbox:checked').map(function() {
        return quotationDataTable.row($(this).closest('tr')).data();
    }).get();

    if (selectedRowsData.length === 0) {
        showToast("الرجاء تحديد عرض أسعار واحد على الأقل للطباعة.", "warning");
        return;
    }

    const tempTable = $('<table>').hide().appendTo('body');
    const tempDataTable = tempTable.DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'print',
                title: 'عروض الأسعار المحددة',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        data: selectedRowsData,
        columns: quotationDataTable.settings()[0].aoColumns
    });

    tempDataTable.button('.buttons-print').trigger();

    setTimeout(() => {
        tempDataTable.destroy();
        tempTable.remove();
        showToast(`تم إرسال ${selectedRowsData.length} صف(صفوف) للطباعة.`, "info");
    }, 100);
}


// ===============================================
// 3. دالة التصدير إلى Excel
// ===============================================

/**
 * دالة لتصدير الصفوف المحددة إلى ملف Excel.
 * ينشئ الملف ويتم تنزيله مباشرة.
 */
 function exportSelectedToExcel() {
    if (typeof quotationDataTable === 'undefined' || quotationDataTable === null) {
        showToast("خطأ: جدول عروض الأسعار غير مهيأ.", "error");
        return;
    }

    const selectedRowsData = quotationDataTable.rows().nodes().to$().find('input.select-row-checkbox:checked').map(function() {
        return quotationDataTable.row($(this).closest('tr')).data();
    }).get();

    if (selectedRowsData.length === 0) {
        showToast("الرجاء تحديد عرض أسعار واحد على الأقل للتصدير.", "warning");
        return;
    }

    const tempTable = $('<table>').hide().appendTo('body');
    const tempDataTable = tempTable.DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'عروض الأسعار المحددة',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        data: selectedRowsData,
        columns: quotationDataTable.settings()[0].aoColumns
    });

    tempDataTable.button('.buttons-excel').trigger();

    setTimeout(() => {
        tempDataTable.destroy();
        tempTable.remove();
        showToast(`تم تصدير ${selectedRowsData.length} صف(صفوف) إلى Excel.`, "info");
    }, 100);
}


// ===============================================
// 4. دالة التعديل (Edit)
// ===============================================

/**
 * دالة لفتح نافذة Modal التعديل وتعبئتها ببيانات عرض الأسعار المحدد.
 * @param {string|null} quotationId - الـ ID الخاص بعرض الأسعار المراد تعديله.
 */
function editQuotationModal(quotationId) {
    if (!quotationId) return;

    console.log(`تعديل عرض الأسعار رقم: ${quotationId}`);
    showToast("جارٍ جلب بيانات عرض الأسعار...", "info");

    fetch(`/quotations/${quotationId}`)
        .then(response => {
            if (!response.ok) throw new Error('فشل جلب بيانات عرض الأسعار.');
            return response.json();
        })
        .then(data => {
            console.log("تم جلب بيانات عرض الأسعار بنجاح:", data);

            // إعادة تعيين النموذج
            resetQuotationForm();

            // --- Quote Info ---
            if (DOM.quoteSubject) DOM.quoteSubject.value = data.subject || '';
            if (DOM.quoteNo) DOM.quoteNo.value = data.quote_no || '';
            if (DOM.quoteRev) DOM.quoteRev.value = data.rev || '';
            if (DOM.quoteDate) DOM.quoteDate.value = data.quote_date || '';
            if (DOM.quoteCategory) DOM.quoteCategory.value = data.quote_category || '';

            // --- Project/Customer Info ---
            if (DOM.quoteProjectCodeInput) {
                DOM.quoteProjectCodeInput.value = data.project_code || '';
                DOM.quoteProjectCodeInput.dataset.id = data.project_id || '';
            }
            if (DOM.quoteCustomer) {
                DOM.quoteCustomer.value = data.customer_name || '';
                DOM.quoteCustomer.dataset.id = data.customer_id || '';
            }
            if (DOM.quoteProject) {
                DOM.quoteProject.value = data.projectName || '';
                DOM.quoteProject.dataset.id = data.project_id || '';
            }

            // --- Contact Info ---
            if (DOM.quoteContactFrom) DOM.quoteContactFrom.value = data.contact_from || '';
            if (DOM.quoteInquiry) DOM.quoteInquiry.value = data.inquiry || '';
            if (DOM.quoteContactPerson) {
                DOM.quoteContactPerson.value = data.contact_person || '';
                DOM.quoteContactPerson.dataset.id = data.contact_id || '';
            }
           if (DOM.quoteContactTo) {
               DOM.quoteContactTo.value = `${data.contact_email || ''} - ${data.city || ''}`;
                     }
            if (DOM.quoteAttnTo) DOM.quoteAttnTo.value = data.attn_to || '';
            if (DOM.quoteAttnPos) DOM.quoteAttnPos.value = data.attn_pos || '';
            if (DOM.quoteContactEmail) DOM.quoteContactEmail.value = data.contact_email || '';
            if (DOM.quoteContactMobile) DOM.quoteContactMobile.value = data.contact_mobile || '';

            // --- Terms and Other Controls ---
            if (DOM.quoteCurrency) DOM.quoteCurrency.value = data.currency || '';
            if (DOM.quoteDiscount) DOM.quoteDiscount.value = parseFloat(data.discount) || 0;
            if (DOM.quoteVAT) DOM.quoteVAT.value = parseFloat(data.vat) || 0;
            if (DOM.quoteValidity) DOM.quoteValidity.value = parseInt(data.validity_days) || 0;
            if (DOM.quotePaymentTermsInput) DOM.quotePaymentTermsInput.value = data.payment_terms || '';
            if (DOM.quoteMethod) DOM.quoteMethod.value = data.method || '';
            if (DOM.quoteUseAltForm) DOM.quoteUseAltForm.checked = !!data.use_alt_form;

            // --- Additional Info ---
            if (DOM.quoteRemarks) DOM.quoteRemarks.value = data.remarks || '';
            if (DOM.quoteQuoteFile) DOM.quoteQuoteFile.value = data.quote_file || '';
            if (DOM.quoteFileStatus) DOM.quoteFileStatus.value = data.file_status || '';
            if (DOM.quoteDeclined) DOM.quoteDeclined.checked = !!data.declined;
            if (DOM.quoteDeclinedMessage) DOM.quoteDeclinedMessage.value = data.declined_message || '';

            // --- Financials ---
            if (DOM.financialTotalLines) DOM.financialTotalLines.value = parseFloat(data.total_lines) || 0;
            if (DOM.financialDiscountAmount) DOM.financialDiscountAmount.value = parseFloat(data.discount_amount) || 0;
            if (DOM.financialTaxAmount) DOM.financialTaxAmount.value = parseFloat(data.tax_amount) || 0;
if (DOM.grand_total) {
   DOM.financialGrandTotal.value = parseFloat((data.grand_total || '0').trim());
}

            // --- Quote Status ---
            if (DOM.quoteOverallStatus) DOM.quoteOverallStatus.value = data.overall_status || '';
            if (DOM.quoteLastConfirmation) DOM.quoteLastConfirmation.value = data.last_confirmation || '';
            if (DOM.quoteLastConfirmed) DOM.quoteLastConfirmed.value = data.last_confirmed || '';

            // --- إعداد المودال ---
            if (DOM.quotationModal) {
                DOM.quotationModal.dataset.mode = 'edit';
                DOM.quotationModal.dataset.quotationId = quotationId;

                const modal = new bootstrap.Modal(DOM.quotationModal);
                modal.show();
            }

            showToast("تم جلب البيانات بنجاح، يمكنك الآن التعديل.", "success");
        })
        .catch(error => {
            console.error('خطأ في جلب بيانات عرض الأسعار:', error);
            showToast("فشل جلب البيانات: " + error.message, "error");
        });
}


// ===============================================
// 5. دالة الحذف (Delete)
// ===============================================

/**
 * دالة لحذف عروض الأسعار المحددة.
 * تُستخدم هذه الدالة لحذف صف أو أكثر من الجدول وقاعدة البيانات.
 */

function deleteSelectedQuotation() {
    const selectedQuotationIds = getSelectedQuotationIds();

    if (selectedQuotationIds.length === 0) {
        showToast("الرجاء تحديد عرض أسعار واحد على الأقل للحذف.", "warning");
        return;
    }

    showConfirmToast(`هل أنت متأكد من حذف ${selectedQuotationIds.length} عرض(عروض) أسعار؟`, () => {
        fetch(window.routes.quotationsDelete, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ids: selectedQuotationIds })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw new Error(err.message || 'فشل حذف البيانات من الخادم.'); });
            }
            return response.json();
        })
        .then(data => {
            showToast(data.message, "success");
            if (typeof quotationDataTable !== 'undefined') {
                quotationDataTable.ajax.reload(null, false);
            }
        })
        .catch(error => {
            console.error('خطأ في حذف عروض الأسعار:', error);
            showToast("فشل الحذف: " + error.message, "error");
        });
    });
}




/**
 * 3. دالة التبديل بين الأقسام (Show Section)
 * onclick="showSection('file-manager-section')"
 */
function showSection(sectionId) {
    console.log("عرض القسم:", sectionId);
    document.querySelectorAll('.section-content').forEach(section => {
        section.classList.remove('active');
        section.style.display = 'none';
    });
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
        targetSection.style.display = 'block';
        showToast(`تم التبديل إلى قسم ${sectionId}.`, "info");
    } else {
        console.error("القسم المطلوب لم يتم العثور عليه:", sectionId);
        showToast(`خطأ: القسم "${sectionId}" غير موجود في الصفحة.`, "error");
    }
}



/**
 * Resets all input fields within the quotation modal to their default values.
 */
function resetQuotationForm() {
    // Quote Info Section
    if (DOM.quoteCategory) DOM.quoteCategory.value = '';
    if (DOM.quoteNo) DOM.quoteNo.value = '';
    if (DOM.quoteRev) DOM.quoteRev.value = '';

    // --- بداية التعديل: تعيين تاريخ اليوم الحالي لحقلي التاريخ ---
    const today = new Date();
    const year = today.getFullYear();
    let month = (today.getMonth() + 1).toString();
    let day = today.getDate().toString();

    if (month.length < 2) {
        month = '0' + month;
    }
    if (day.length < 2) {
        day = '0' + day;
    }
    const formattedDate = `${year}-${month}-${day}`;

    // تعيين قيمة تاريخ الاقتباس
    if (DOM.quoteDate) {
        DOM.quoteDate.value = formattedDate;
    }
    // تعيين قيمة تاريخ الاستحقاق (إذا كان لديك حقل له)
    if (DOM.quoteDueDate) {
        DOM.quoteDueDate.value = formattedDate;
    }
    // --- نهاية التعديل ---

    // --- بداية التعديل الجديد: مسح حقول المشروع والعميل ---
    if (DOM.quoteProjectCodeInput) DOM.quoteProjectCodeInput.value = ''; // مسح حقل Project Code Input
    // تأكد من مسح حقول اسم المشروع والعميل إذا كانت موجودة وتتأثر باختيار Project Code
    if (DOM.quoteProject) DOM.quoteProject.value = ''; // مسح حقل اسم المشروع (إن وجد)
    if (DOM.quoteCustomer) DOM.quoteCustomer.value = ''; // مسح حقل العميل (إن وجد)
    // --- نهاية التعديل الجديد ---

    if (DOM.quoteLegacyNo) DOM.quoteLegacyNo.value = '';
    if (DOM.quoteLegacyDate) DOM.quoteLegacyDate.value = '';
    // تم التعامل مع DOM.quoteCustomer أعلاه
    // تم التعامل مع DOM.quoteProject أعلاه
    if (DOM.quoteProjectDetails) DOM.quoteProjectDetails.value = '';
    if (DOM.quoteSubject) DOM.quoteSubject.value = '';

    // Contact Info Section
    if (DOM.quoteContactFrom) DOM.quoteContactFrom.value = '';
    if (DOM.quoteInquiry) DOM.quoteInquiry.value = '';
    if (DOM.quoteContactPerson) DOM.quoteContactPerson.value = '';
    if (DOM.quoteContactTo) DOM.quoteContactTo.value = '';
    if (DOM.quoteAttnTo) DOM.quoteAttnTo.value = '';
    if (DOM.quoteAttnPos) DOM.quoteAttnPos.value = '';

    // Terms and other Controls Section
    if (DOM.quoteDiscount) DOM.quoteDiscount.value = '';
    if (DOM.quoteVAT) DOM.quoteVAT.value = '15';
    if (DOM.quoteValidity) DOM.quoteValidity.value = '60';
    if (DOM.quoteCurrency) DOM.quoteCurrency.value = 'SAR';

    if (DOM.quotePaymentTermsInput) {
        DOM.quotePaymentTermsInput.value = '';
    }

    if (DOM.quoteMethod) DOM.quoteMethod.value = '';
    if (DOM.quoteUseAltForm) DOM.quoteUseAltForm.checked = false;

    // Additional Info Section
    if (DOM.quoteRemarks) DOM.quoteRemarks.value = '';
    if (DOM.quoteQuoteFileText) DOM.quoteQuoteFileText.value = '';
    if (DOM.quoteQuoteFileInput) {
        DOM.quoteQuoteFileInput.value = '';
    }
    if (DOM.quoteFileStatus) DOM.quoteFileStatus.value = 'PDF Not Created';
    if (DOM.quoteDeclined) DOM.quoteDeclined.checked = false;
    if (DOM.quoteDeclinedMessage) DOM.quoteDeclinedMessage.value = '';

    // Financials and Quote Status for Header Tab
    if (DOM.financialTotalLines) DOM.financialTotalLines.value = '0.000';
    if (DOM.financialDiscountAmount) DOM.financialDiscountAmount.value = '0.000';
    if (DOM.financialTaxAmount) DOM.financialTaxAmount.value = '0.000';
    if (DOM.financialGrandTotal) DOM.financialGrandTotal.value = '0.000';
    if (DOM.quoteOverallStatus) DOM.quoteOverallStatus.value = '';
    if (DOM.quoteLastConfirmation) DOM.quoteLastConfirmation.value = '';
    if (DOM.quoteLastConfirmed) DOM.quoteLastConfirmed.value = '';

    // Financials and Quote Status for Quote Lines Tab
    if (DOM.linesFinancialTotalLines) DOM.linesFinancialTotalLines.value = '0.000';
    if (DOM.linesFinancialDiscountAmount) DOM.linesFinancialDiscountAmount.value = '0.000';
    if (DOM.linesFinancialTaxAmount) DOM.linesFinancialTaxAmount.value = '0.000';
    if (DOM.linesFinancialGrandTotal) DOM.linesFinancialGrandTotal.value = '0.000';
    if (DOM.linesQuoteOverallStatus) DOM.linesQuoteOverallStatus.value = '';
    if (DOM.linesQuoteLastConfirmation) DOM.linesQuoteLastConfirmation.value = '';
    if (DOM.linesQuoteLastConfirmed) DOM.linesQuoteLastConfirmed.value = '';

    // Clear any validation marks
    document.querySelectorAll('.required-field-missing').forEach(label => {
        label.classList.remove('required-field-missing');
    });
}
/**
 * Handles tab switching within the quotation modal.
 * @param {Event | null} evt - The click event object, or null if called programmatically.
 * @param {string} tabId - The ID of the tab content to display (e.g., 'headerTab', 'linesTab').
 */
function openTab(evt, tabId) {
    const tabs = document.querySelectorAll('.tab-content');
    const tabButtons = document.querySelectorAll('.tab');

    tabs.forEach(tab => tab.classList.remove('active'));
    tabButtons.forEach(btn => btn.classList.remove('active'));

    const targetTabContent = document.getElementById(tabId);
    if (targetTabContent) {
        targetTabContent.classList.add('active');
    } else {
        console.warn(`Tab content with ID '${tabId}' not found.`);
        return;
    }

    let targetTabButton;
    if (evt && evt.currentTarget) {
        targetTabButton = evt.currentTarget;
    } else {
        targetTabButton = document.querySelector(`.tab[data-tab-target="${tabId}"]`);
    }

    if (targetTabButton) {
        targetTabButton.classList.add('active');
    } else {
        console.warn(`Tab button for tab ID '${tabId}' not found.`);
    }

    // Initialize DataTables for the lines tab when it becomes active
    if (tabId === 'linesTab') {
        setTimeout(() => {
            initializeQuotationLinesDataTable(); // Ensure this function is defined elsewhere
            if (quotationLinesDataTable) {
                quotationLinesDataTable.columns.adjust().draw();
                console.log("Quotation Lines table columns adjusted after tab opened.");
            }
        }, 300); // Increased delay to 300ms
    }
}

// =====================================================================
// Tab-Specific Save & Close Functions
// =====================================================================
/**
 * دالة لإرسال بيانات الـ Quotation Header وحفظها في قاعدة البيانات.
 * تحافظ على الاسم addQuotationToTable كما هو.
 * @returns {Promise<boolean>} - تعطي true إذا تم الحفظ بنجاح، false إذا فشل.
 */




function saveAndCloseQuotationHeader() {
    saveQuotationHeader()
        .then(() => {
            closeQuotationModal();
            showToast("Quotation Header saved and modal closed!", "success");
            console.log("Quotation Header saved and modal closed.");
        })
        .catch(err => {
            console.error("Error saving quotation header:", err);
            showToast("Error saving quotation header. Please check console.", "error");
        });
}
/**
 * Saves/updates quote lines and closes the modal.
 */
function saveAndCloseQuoteLines() {
    saveQuoteLines(); // Call the save logic for lines
    closeQuotationModal();
    showToast("Quote Lines changes saved and modal closed (simulated)!", "success");
    console.log("Quote Lines changes saved and modal closed (simulated).");
}





/**
 * دالة لتحديد/إلغاء تحديد جميع مربعات الاختيار في الجدول الرئيسي.
 * يتم استدعاؤها من `onclick` الخاص بمربع الاختيار في عنوان العمود.
 * @param {HTMLInputElement} masterCheckbox - مربع الاختيار الرئيسي الذي تم النقر عليه.
 */
function toggleSelectAllQuotations(masterCheckbox) {
    // التحقق أولاً مما إذا كان كائن DataTables مهيأ
    if (quotationDataTable) {
        const isChecked = masterCheckbox.checked;

        // الحصول على جميع صفوف DataTables كعناصر HTML
        const allRowsNodes = quotationDataTable.rows().nodes();

        // 1. تحديث حالة مربعات الاختيار الفردية داخل كل صف
        $('input.select-row-checkbox', allRowsNodes).prop('checked', isChecked);

        // 2. تحديث تنسيق الصفوف (إضافة/إزالة فئة 'selected-row')
        $(allRowsNodes).toggleClass('selected-row', isChecked); // استخدام toggleClass هنا أكثر إيجازًا

        // ملاحظة: لا حاجة لاستدعاء updateSelectAllCheckbox() هنا.

    } else {
        // إذا كان DataTables غير مهيأ، يمكنك عرض رسالة تحذير بسيطة فقط
        console.warn("quotationDataTable لم يتم تهيئته بعد.");
    }
}

// =====================================================================
// Dummy Data Retrieval Functions (for testing and demonstration)
// =====================================================================

function getEmployeesData() {
    return [
        { fullName: "Eng. Osama Mohammed ", title: "Project Manager" },
        { fullName: "Eng. Bassim Mohmed Elmaghribi", title: "Site Engineer" },
        { fullName: "Eng. Mahmoud Kazem Mohamed", title: "Site Engineer" },
        { fullName: "Eng. Zeyad Fouad al-OhuMuhammad", title: "Geology" },
    ];
}

// NEW: Dummy data for Price List with 'method'
function getPriceListData() {
    return [
        { id: '102218', name: 'Monitoring of fresh concrete', method: 'ASTM C39', unit: 'NO.', price: 22.00, priceOnly: false, quantity: 1, active: true },
        { id: '102220', name: 'Sampling of fresh concrete', method: 'ASTM C172', unit: 'NO.', price: 22.00, priceOnly: false, quantity: 1, active: true },
        { id: '102455', name: 'اختبار الخبوط', method: 'ASTM C143', unit: 'NO.', price: 100.00, priceOnly: false, quantity: 1, active: true },
        { id: '102459', name: 'اختبار مقاومة البري', method: 'ASTM C944', unit: 'NO.', price: 185.00, priceOnly: false, quantity: 1, active: true },
        { id: '102460', name: 'اختبار مقاومة الصدم', method: 'ASTM C1138', unit: 'Each', price: 200.00, priceOnly: false, quantity: 1, active: true },
        { id: '102462', name: 'الإحالة باستخدام كبريتات الصوديوم والمغنيسيوم', method: 'ASTM C88', unit: 'Each', price: 200.00, priceOnly: false, quantity: 1, active: true },
        { id: '102467', name: 'الاختصاص', method: 'N/A', unit: 'NO.', price: 80.00, priceOnly: false, quantity: 1, active: true },
        { id: '102461', name: 'النموذج العيني', method: 'N/A', unit: 'NO.', price: 130.00, priceOnly: false, quantity: 1, active: true },
        { id: '102469', name: 'التسربات العضوية', method: 'N/A', unit: 'NO.', price: 180.00, priceOnly: false, quantity: 1, active: true },
        { id: '102468', name: 'المعادن الرماد', method: 'N/A', unit: 'Each', price: 200.00, priceOnly: false, quantity: 1, active: true },
        { id: '102466', name: 'الوزن النوعي', method: 'N/A', unit: 'NO.', price: 75.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-001', name: 'Concrete Compressive Strength', method: 'ASTM C39', unit: 'NO.', price: 150.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-002', name: 'Cement Fineness Test', method: 'ASTM C204', unit: 'NO.', price: 10.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-003', name: 'Soil Proctor Test', method: 'ASTM D698', unit: 'Each', price: 25.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-004', name: 'Asphalt Content Test', method: 'ASTM D2172', unit: 'NO.', price: 28.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-005', name: 'Aggregate Sieve Analysis', method: 'ASTM C136', unit: 'NO.', price: 10.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-006', name: 'Water Absorption Test', method: 'ASTM C127', unit: 'NO.', price: 200.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-007', name: 'Density of Soil', method: 'ASTM D2937', unit: 'NO.', price: 10.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-008', name: 'Concrete Mix Design', method: 'ACI 211.1', unit: 'Each', price: 120.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-009', name: 'Rebar Tensile Test', method: 'ASTM A370', unit: 'NO.', price: 10.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-010', name: 'Block Compressive Strength', method: 'ASTM C140', unit: 'NO.', price: 100.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-011', name: 'Chemical Analysis of Water', method: 'APHA 4500', unit: 'NO.', price: 300.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-012', name: 'Bitumen ***** Test', method: 'ASTM D5', unit: 'Each', price: 35.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-013', name: 'Field Density Test (Sand Cone)', method: 'ASTM D1556', unit: 'NO.', price: 40.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-014', name: 'Pile Load Test', method: 'ASTM D1143', unit: 'NO.', price: 1500.00, priceOnly: false, quantity: 1, active: true },
        { id: 'LIMS-015', name: 'Soil Classification', method: 'ASTM D2487', unit: 'Each', price: 75.00, priceOnly: false, quantity: 1, active: true }
    ];
}

function initializeProjectDropdown() {
    const projectCodeInputField = DOM.quoteProjectCodeInput;
    const projectCodeDropdown = DOM.projectCodeDropdown;
    const showProjectCodeListBtn = DOM.showProjectCodeListBtn;
    const contactInputField = DOM.quoteContactFrom;
    const contactDropdown = DOM.contactPersonDropdown;
    const showContactListBtn = DOM.showContactPersonListBtn;

    if (!projectCodeInputField || !projectCodeDropdown || !showProjectCodeListBtn ||
        !contactInputField || !contactDropdown || !showContactListBtn) {
        console.error("Required DOM elements not found.");
        return;
    }

    // -----------------------------
    // دالة تحميل المشاريع من السيرفر
    // -----------------------------
    let projectOptions = [];

     function renderProjectDropdown(data) {
        projectCodeDropdown.innerHTML = '';
        const headerRow = document.createElement('div');
        headerRow.classList.add('custom-dropdown-header-row');
        headerRow.innerHTML = `
            <span class="dropdown-column-code">Project Code</span>
            <span class="dropdown-column-name">Project Name</span>
            <span class="dropdown-column-customer">Customer</span>
        `;
        projectCodeDropdown.appendChild(headerRow);

        if (data.length === 0) {
            const noResultsItem = document.createElement('div');
            noResultsItem.classList.add('custom-dropdown-item', 'no-results');
            noResultsItem.textContent = "لا توجد مشاريع متاحة.";
            projectCodeDropdown.appendChild(noResultsItem);
            projectCodeDropdown.style.display = 'block';
            return;
        }

        data.forEach(project => {
            const item = document.createElement('div');
            item.classList.add('custom-dropdown-item');
            item.innerHTML = `
                <span>${project.code}</span>
                <span>${project.name}</span>
                <span>${project.customerName}</span>
            `;
            item.addEventListener('click', function() {
                projectCodeInputField.value = project.code;
                DOM.quoteProject.value = project.name;
                DOM.quoteProject.dataset.id = project.id; // ← تعيين data-id

                DOM.quoteCustomer.value = project.customerName;
                DOM.quoteCustomer.dataset.id = project.customerId; // ← تعيين data-id
                projectCodeDropdown.style.display = 'none';

                // تحميل جهات الاتصال للمشروع
                fetch(`/quotation/contacts?project=${project.code}`)
                    .then(res => res.json())
                    .then(data => renderContactDropdown(data))
                    .catch(err => console.error("Error fetching contacts:", err));
            });
            projectCodeDropdown.appendChild(item);
        });

        projectCodeDropdown.style.display = 'block';
    }


    function loadProjectsFromServer() {
        fetch('/quotation/projects')
            .then(res => res.json())
            .then(data => {
                projectOptions = data;
                renderProjectDropdown(projectOptions);
            })
            .catch(err => console.error("Error fetching projects:", err));
    }

    // -----------------------------
    // مستمعين الأحداث
    // -----------------------------
projectCodeInputField.addEventListener('click', loadProjectsFromServer);
    showProjectCodeListBtn.addEventListener('click', e => { e.stopPropagation(); loadProjectsFromServer(); });
    contactInputField.addEventListener('click', function() {
        const projectCode = projectCodeInputField.value;
        if (!projectCode) return;
        fetch(`/quotation/contacts?project=${projectCode}`)
            .then(res => res.json())
            .then(data => renderContactDropdown(data))
            .catch(err => console.error("Error fetching contacts:", err));
    });
    showContactListBtn.addEventListener('click', e => {
        e.stopPropagation();
        const projectCode = projectCodeInputField.value;
        if (!projectCode) return;
        fetch(`/quotation/contacts?project=${projectCode}`)
            .then(res => res.json())
            .then(data => renderContactDropdown(data))
            .catch(err => console.error("Error fetching contacts:", err));
    });

    document.addEventListener('click', e => {
        if (!projectCodeDropdown.contains(e.target) &&
            e.target !== projectCodeInputField &&
            e.target !== showProjectCodeListBtn) {
            projectCodeDropdown.style.display = 'none';
        }
        if (!contactDropdown.contains(e.target) &&
            e.target !== contactInputField &&
            e.target !== showContactListBtn) {
            contactDropdown.style.display = 'none';
        }
    });

    projectCodeDropdown.style.display = 'none';
    contactDropdown.style.display = 'none';
    console.log("Project & Contact dropdowns initialized successfully.");
}

initializeProjectDropdown();


// =====================================================================
// Custom Dropdown for 'From' field (Vanilla JavaScript)
// =====================================================================

function initializeEmployeeDropdown() {
    const employeeInputField = DOM.quoteContactFrom;
    const showEmployeesListButton = DOM.showEmployeesListBtnEmployee;
    const employeeDropdown = DOM.employeeDropdown;

    if (!employeeInputField || !showEmployeesListButton || !employeeDropdown) {
        // Corrected console.error message
        console.error("Required DOM elements for employee dropdown not found. Check IDs for quoteContactFrom, showEmployeesListBtnEmployee, employeeDropdown.");
        return;
    }

    const allEmployees = getEmployeesData();

    function renderEmployeeDropdown(dataToRender) {
        employeeDropdown.innerHTML = '';

        if (dataToRender.length === 0) {
            employeeDropdown.style.display = 'none';
            return;
        }

        const header = document.createElement('div');
        header.classList.add('custom-dropdown-header');
        header.innerHTML = `
            <span class="header-fullname">Employee Full Name</span>
            <span class="header-title">Employee Title</span>
        `;
        employeeDropdown.appendChild(header);

        dataToRender.forEach(employee => {
            const item = document.createElement('div');
            item.classList.add('custom-dropdown-item');
            item.innerHTML = `
                <span class="employee-fullname">${employee.fullName}</span>
                <span class="employee-title">${employee.title}</span>
            `;
            item.addEventListener('click', function() {
                employeeInputField.value = employee.fullName;
                employeeDropdown.style.display = 'none';
                markRequiredField(employeeInputField, false);
            });
            employeeDropdown.appendChild(item);
        });

        employeeDropdown.style.display = 'block';
    }

    employeeInputField.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const filteredData = allEmployees.filter(employee =>
            employee.fullName.toLowerCase().includes(searchTerm) ||
            employee.title.toLowerCase().includes(searchTerm)
        );
        renderEmployeeDropdown(filteredData);
        if (this.value.trim()) {
            markRequiredField(this, false);
        }
    });

    employeeInputField.addEventListener('focus', function() {
        renderEmployeeDropdown(allEmployees);
    });

    showEmployeesListButton.addEventListener('click', function(event) {
        event.stopPropagation();
        if (employeeDropdown.style.display === 'block') {
            employeeDropdown.style.display = 'none';
        } else {
            renderEmployeeDropdown(allEmployees);
            employeeInputField.focus();
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target !== employeeInputField &&
            event.target !== showEmployeesListButton &&
            !employeeDropdown.contains(event.target)) {
            employeeDropdown.style.display = 'none';
        }
    });

    employeeDropdown.style.display = 'none';
}



// =====================================================================
// Custom Dropdown for Payment Terms (Vanilla JavaScript)
// =====================================================================

function initializePaymentTermsDropdown() {
    const paymentTermsInputField = DOM.quotePaymentTermsInput;
    const paymentTermsDropdown = DOM.paymentTermsDropdown;
    const showPaymentTermsListBtn = DOM.showPaymentTermsListBtn;

    if (!paymentTermsInputField || !paymentTermsDropdown || !showPaymentTermsListBtn) {
        console.error("Required DOM elements for payment terms dropdown not found. Check IDs: quotePaymentTermsInput, paymentTermsDropdown, showPaymentTermsListBtn.");
        return;
    }

    const paymentTermsOptions = [
        { value: "D1 - Advance Payment", text: "D1 - Advance Payment: دفع مقدّم" },
        { value: "D2 - On Delivery", text: "D2 - On Delivery: الدفع عند التسليم" },
        { value: "D3 - Net 7 Days", text: "D3 - Net 7 Days: الدفع خلال 7 أيام من تاريخ الفاتورة" },
        { value: "D4 - Immediate", text: "D4 - Immediate: الدفع الفوري" },
        { value: "D5 - Net 30 Days", text: "D5 - Net 30 Days: الدفع خلال 30 يومًا من تاريخ الفاتورة" },
        { value: "D6 - Net 60 Days", text: "D6 - Net 60 Days: الدفع خلال 60 يومًا من تاريخ الفاتورة" },
        { value: "D7 - Against Progress Billing", text: "D7 - Against Progress Billing: الدفع مقابل فواتير مرحلية/تقدم العمل" },
        { value: "D8 - LC at Sight", text: "D8 - LC at Sight: اعتماد مستندي عند الإطلاع" },
        { value: "D9 - PDC (Post-Dated Cheque)", text: "D9 - PDC (Post-Dated Cheque): شيك مؤجل الدفع" },
        { value: "D10 - Bank Transfer", text: "D10 - Bank Transfer: تحويل بنكي" },
    ];

    function renderPaymentTermsDropdown(dataToRender) {
        paymentTermsDropdown.innerHTML = '';
        if (dataToRender.length === 0) {
            paymentTermsDropdown.style.display = 'none';
            return;
        }

        dataToRender.forEach(option => {
            const item = document.createElement('div');
            item.classList.add('custom-dropdown-item');
            item.textContent = option.text;
            item.setAttribute('data-value', option.value);

            item.addEventListener('click', function() {
                paymentTermsInputField.value = option.text;
                paymentTermsDropdown.style.display = 'none';
                markRequiredField(paymentTermsInputField, false);
            });
            paymentTermsDropdown.appendChild(item);
        });

        paymentTermsDropdown.style.display = 'block';
    }

    paymentTermsInputField.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const filteredTerms = paymentTermsOptions.filter(option =>
            option.text.toLowerCase().includes(searchTerm)
        );
        renderPaymentTermsDropdown(filteredTerms);
        if (this.value.trim()) {
            markRequiredField(this, false);
        }
    });

    paymentTermsInputField.addEventListener('focus', function() {
        renderPaymentTermsDropdown(paymentTermsOptions);
    });

    showPaymentTermsListBtn.addEventListener('click', function(event) {
        event.stopPropagation();
        if (paymentTermsDropdown.style.display === 'block') {
            paymentTermsDropdown.style.display = 'none';
        } else {
            renderPaymentTermsDropdown(paymentTermsOptions);
            paymentTermsInputField.focus();
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target !== paymentTermsInputField &&
            event.target !== showPaymentTermsListBtn &&
            !paymentTermsDropdown.contains(event.target)) {
            paymentTermsDropdown.style.display = 'none';
        }
    });

    paymentTermsDropdown.style.display = 'none'; // Initially hidden
}
/**
 * Initializes the custom dropdown for the Category field.
 * Manages rendering options and handling selection for automatic quote number generation.
 */
/**
 * تقوم بتهيئة القائمة المنسدلة المخصصة لحقل "الفئة" (Category).
 * تدير عملية عرض الخيارات، ومعالجة أحداث النقر، والتحكم في الإغلاق عند النقر خارجها.
 */

// ============================
// ==== Initialize Project & Contact Dropdowns ====
// ============================
function initializeContactPersonDropdown() {
    // ==== عناصر DOM ====
    const projectCodeInputField = DOM.quoteProjectCodeInput;
    const projectCodeDropdown = DOM.projectCodeDropdown;
    const showProjectCodeListBtn = DOM.showProjectCodeListBtn;

    const contactInputField = DOM.quoteContactPerson;
    const contactDropdown = DOM.contactPersonDropdown;
    const showContactListBtn = DOM.showContactPersonListBtn;

    const quoteProjectField = DOM.quoteProject;
    const quoteCustomerField = DOM.quoteCustomer;
    const quoteContactToField = DOM.quoteContactTo;
    const quoteAttnToField = DOM.quoteAttnTo;
    const quoteAttnPosField = DOM.quoteAttnPos;
    const quoteContactEmailField = DOM.quoteContactEmail;
    const quoteContactMobileField = DOM.quoteContactMobile;

    // ==== تحقق وجود العناصر ====
    if (!projectCodeInputField || !projectCodeDropdown || !showProjectCodeListBtn ||
        !contactInputField || !contactDropdown || !showContactListBtn ||
        !quoteProjectField || !quoteCustomerField) {
        console.error("❌ عناصر DOM غير مكتملة.");
        return;
    }

    // ==== Contact Dropdown ====
 function renderContactDropdown(data) {
    contactDropdown.innerHTML = '';

    // 🔹 العناوين الرئيسية للأعمدة
    const headerRow = document.createElement('div');
    headerRow.classList.add('custom-dropdown-header-row');
    headerRow.innerHTML = `
        <span>Name</span>
        <span>Title</span>
        <span>Email</span>
        <span>Mobile</span>
        <span>Location</span>
    `;
    contactDropdown.appendChild(headerRow);

    if ((!data.project_contacts || data.project_contacts.length === 0) &&
        (!data.customer_contacts || data.customer_contacts.length === 0)) {
        const noResults = document.createElement('div');
        noResults.classList.add('custom-dropdown-item', 'no-results');
        noResults.textContent = "لا توجد جهات اتصال متاحة.";
        contactDropdown.appendChild(noResults);
        contactDropdown.style.display = 'block';
        return;
    }

    // 🔹 دالة مساعدة لرسم مجموعة (Project / Customer)
    function renderGroup(title, contacts, icon) {
        if (!contacts || contacts.length === 0) return;

        const groupHeader = document.createElement('div');
        groupHeader.classList.add('custom-dropdown-group');
        groupHeader.innerHTML = `<span class="group-icon">${icon}</span> ${title}`;
        contactDropdown.appendChild(groupHeader);

        contacts.forEach(contact => {
            const item = document.createElement('div');
            item.classList.add('custom-dropdown-item');
            item.innerHTML = `
                <span>${contact.name}</span>
                <span>${contact.title || '-'}</span>
                <span>${contact.email || '-'}</span>
                <span>${contact.mobile || contact.phone || '-'}</span>
                <span>${contact.city || '-'}</span>
            `;
            item.addEventListener('click', () => {
                contactInputField.value = contact.name;
                contactInputField.dataset.id = contact.id; // ← ID Contact
                contactDropdown.style.display = 'none';

                if (quoteAttnToField) quoteAttnToField.value = contact.name;
                if (quoteAttnPosField) quoteAttnPosField.value = contact.title || '';
                if (quoteContactEmailField) quoteContactEmailField.value = contact.email || '';
                if (quoteContactMobileField) quoteContactMobileField.value = contact.mobile || contact.phone || '';

                if (quoteContactToField) {
                    let toValue = '';
                    if (contact.city) toValue += contact.city;
                    if (contact.email) toValue += (toValue ? ' - ' : '') + contact.email;
                    quoteContactToField.value = toValue;
                }

                console.log("✅ Contact selected:", contact.name, "ID:", contact.id);
            });
            contactDropdown.appendChild(item);
        });
    }

    // 🔹 عرض المجموعتين
    renderGroup("جهات اتصال المشروع", data.project_contacts, "👷");
    renderGroup("جهات اتصال العميل", data.customer_contacts, "🏢");

    contactDropdown.style.display = 'block';
}



    function loadContacts(projectCode) {
        if (!projectCode) return;
        fetch(`/quotation/contacts?project=${projectCode}`)
            .then(res => res.json())
            .then(data => renderContactDropdown(data))
            .catch(err => console.error("Error fetching contacts:", err));
    }

    // ==== Project Dropdown ====
    function renderProjectCodeDropdown(data) {
        projectCodeDropdown.innerHTML = '';
        const headerRow = document.createElement('div');
        headerRow.classList.add('custom-dropdown-header-row');
        headerRow.innerHTML = `
            <span>Project Code</span>
            <span>Project Name</span>
            <span>Customer</span>
        `;
        projectCodeDropdown.appendChild(headerRow);

        if (data.length === 0) {
            const noResults = document.createElement('div');
            noResults.classList.add('custom-dropdown-item', 'no-results');
            noResults.textContent = "لا توجد مشاريع متاحة.";
            projectCodeDropdown.appendChild(noResults);
            projectCodeDropdown.style.display = 'block';
            return;
        }

        data.forEach(project => {
            const item = document.createElement('div');
            item.classList.add('custom-dropdown-item');
            item.innerHTML = `
                <span>${project.code}</span>
                <span>${project.name}</span>
                <span>${project.customer}</span>
            `;
            item.addEventListener('click', () => {
                projectCodeInputField.value = project.code;
                projectCodeDropdown.style.display = 'none';

                if (quoteProjectField) {
                    quoteProjectField.value = project.name;
                    quoteProjectField.dataset.id = project.id; // ← ID Project
                }
                if (quoteCustomerField) {
                    quoteCustomerField.value = project.customer;
                    quoteCustomerField.dataset.id = project.customer_id; // ← ID Customer
                }

                console.log("📌 Project selected:", project.name, "ID:", project.id);
                console.log("📌 Customer selected:", project, "ID:", project.customer_id);

                loadContacts( project.code, project.customer_id);

            });
            projectCodeDropdown.appendChild(item);
        });
        projectCodeDropdown.style.display = 'block';
    }

    function loadProjectsFromServer() {
        fetch('/quotation/projects')
            .then(res => res.json())
            .then(data => renderProjectCodeDropdown(data))
            .catch(err => console.error("Error fetching projects:", err));
    }

    // ==== Events ====
    projectCodeInputField.addEventListener('click', loadProjectsFromServer);
    showProjectCodeListBtn.addEventListener('click', e => { e.stopPropagation(); loadProjectsFromServer(); });

    contactInputField.addEventListener('click', () => loadContacts(projectCodeInputField.value));
    showContactListBtn.addEventListener('click', e => { e.stopPropagation(); loadContacts(projectCodeInputField.value); });

    document.addEventListener('click', e => {
        if (!projectCodeDropdown.contains(e.target) && e.target !== projectCodeInputField && e.target !== showProjectCodeListBtn) {
            projectCodeDropdown.style.display = 'none';
        }
        if (!contactDropdown.contains(e.target) && e.target !== contactInputField && e.target !== showContactListBtn) {
            contactDropdown.style.display = 'none';
        }
    });

    projectCodeDropdown.style.display = 'none';
    contactDropdown.style.display = 'none';
    console.log("✅ Project & Contact dropdowns initialized successfully.");
}

// ============================
// ==== Save Quotation Header ====
// ============================
function saveQuotationHeader(isEdit = false, quotationId = null) {
    return new Promise((resolve, reject) => {
        try {
            const payload = {
                customer_id: parseInt(DOM.quoteCustomer.dataset.id),
                project_id: parseInt(DOM.quoteProject.dataset.id),
                contact_id: parseInt(DOM.quoteContactPerson.dataset.id),
                quote_category: DOM.quoteCategory.value.trim(),
                quote_no: DOM.quoteNo.value.trim(),
                rev: DOM.quoteRev.value.trim(),
                quote_date: DOM.quoteDate.value,
                legacy_no: DOM.quoteLegacyNo.value.trim(),
                legacy_date: DOM.quoteLegacyDate.value,
                subject: DOM.quoteSubject.value.trim(),
                currency: DOM.quoteCurrency.value,
                discount: parseFloat(DOM.quoteDiscount.value) || 0,
                vat: parseFloat(DOM.quoteVAT.value) || 0,
                validity_days: parseInt(DOM.quoteValidity.value) || 0,
                payment_terms: DOM.quotePaymentTermsInput.value.trim(),
                method: DOM.quoteMethod.value.trim(),
                remarks: DOM.quoteRemarks.value.trim(),
                inquiry: DOM.quoteInquiry.value.trim(),
                quote_file: DOM.quoteQuoteFile.value.trim(),
                file_status: DOM.quoteFileStatus.value.trim(),
                declined: DOM.quoteDeclined.checked ? 1 : 0,
                declined_message: DOM.quoteDeclinedMessage.value.trim(),
                 project_details : DOM.quoteProjectDetails.value.trim() || '',
                total_lines: parseFloat(DOM.financialTotalLines.value) || 0,
                discount_amount: parseFloat(DOM.financialDiscountAmount.value) || 0,
                tax_amount: parseFloat(DOM.financialTaxAmount.value) || 0,
                grand_total: parseFloat(DOM.financialGrandTotal.value) || 0 ,
                 declined: DOM.quoteDeclined?.checked ? 1 : 0,
    use_alt_form: DOM.quoteUseAltForm?.checked ? 1 : 0,
    overall_status: DOM.quoteOverallStatus?.value.trim() || '',
    last_confirmation: DOM.quoteLastConfirmation?.value || null,
    last_confirmed: DOM.quoteLastConfirmed?.value || null,

    // --- بيانات إضافية ---
    inquiry: DOM.quoteInquiry?.value.trim() || '',
    contact_from: DOM.quoteContactFrom?.value.trim() || '',
            };

            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : null;
            if (!csrfToken) return reject(new Error("CSRF token not found"));

            const url = isEdit ? `/quotations/${quotationId}` : '/quotation/save-header';
            const method = isEdit ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message, "success");
                    resolve(data);
                } else {
                    showToast("Failed: " + data.message, "error");
                    reject(new Error(data.message || "Server error"));
                }
            })
            .catch(err => {
                showToast("Error: " + err.message, "error");
                reject(err);
            });
        } catch (err) {
            showToast("Unexpected error: " + err.message, "error");
            reject(err);
        }
    });
}




function saveQuotationHeader() {
    return new Promise((resolve, reject) => {
        try {
            const quotationId = DOM.quotationModal?.dataset.quotationId || null;
            const isEdit = !!quotationId;

            const payload = {
                customer_id: parseInt(DOM.quoteCustomer.dataset.id) || null,
                project_id: parseInt(DOM.quoteProject.dataset.id) || null,
                contact_id: parseInt(DOM.quoteContactPerson.dataset.id) || null,
                quote_category: DOM.quoteCategory.value.trim(),
                contact_from: DOM.quoteContactFrom.value.trim(),
                quote_no: DOM.quoteNo.value.trim(),
                rev: DOM.quoteRev.value.trim(),
                quote_date: DOM.quoteDate.value || null,
                legacy_no: DOM.quoteLegacyNo.value.trim(),
                legacy_date: DOM.quoteLegacyDate.value || null,
                subject: DOM.quoteSubject.value.trim(),
                currency: DOM.quoteCurrency.value || '',
                discount: parseFloat(DOM.quoteDiscount.value) || 0,
                vat: parseFloat(DOM.quoteVAT.value) || 0,
                validity_days: parseInt(DOM.quoteValidity.value) || 0,
                payment_terms: DOM.quotePaymentTermsInput.value.trim(),
                method: DOM.quoteMethod.value.trim(),
                remarks: DOM.quoteRemarks.value.trim(),
                quote_file: DOM.quoteQuoteFile.value.trim(),
                file_status: DOM.quoteFileStatus.value.trim(),
                declined: DOM.quoteDeclined.checked ? 1 : 0,
                declined_message: DOM.quoteDeclinedMessage.value.trim() || '',
                project_details:DOM.quoteProjectDetails.value.trim() || '',
                total_lines: parseFloat(DOM.financialTotalLines.value) || 0,
                discount_amount: parseFloat(DOM.financialDiscountAmount.value) || 0,
                tax_amount: parseFloat(DOM.financialTaxAmount.value) || 0,
                grand_total: parseFloat(DOM.financialGrandTotal.value) || 0,
                use_alt_form: DOM.quoteUseAltForm?.checked ? 1 : 0,
                overall_status: DOM.quoteOverallStatus?.value.trim() || '',
                last_confirmation: DOM.quoteLastConfirmation?.value || null,
                last_confirmed: DOM.quoteLastConfirmed?.value || null,
                inquiry: DOM.quoteInquiry.value.trim()
            };

            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : null;
            if (!csrfToken) return reject(new Error("CSRF token not found"));

            const url = isEdit ? `/quotations/${quotationId}` : '/quotation/save-header';
            const method = isEdit ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(async res => {
                const text = await res.text();
                let data;
                try { data = JSON.parse(text); }
                catch (err) { throw new Error("Invalid JSON response from server:\n" + text); }

                if (!res.ok || !data.success) {
                    const msg = data.message || `HTTP error! Status: ${res.status}`;
                    showToast("❌ Failed to save quotation: " + msg, "error");
                    return reject(new Error(msg));
                }

                showToast(isEdit ? "✅ Quotation updated successfully!" : "✅ Quotation created successfully!", "success");

                // تحديث الـ dataset في حالة إنشاء جديد
                if (!isEdit && DOM.quotationModal) {
                    DOM.quotationModal.dataset.quotationId = data.quotation_id;
                    DOM.quotationModal.dataset.mode = 'edit';
                }

                // **هنا نضيف تحديث جدول DataTable بعد الإضافة أو التعديل**
                if (typeof quotationDataTable !== 'undefined' && quotationDataTable !== null) {
                    quotationDataTable.ajax.reload(null, false); // false => لا تعود إلى الصفحة الأولى
                }

                resolve(data);
            })
            .catch(err => {
                console.error("❌ Error saving quotation:", err);
                showToast("❌ Error saving quotation: " + err.message, "error");
                reject(err);
            });

        } catch (err) {
            console.error("❌ Unexpected error in saveQuotationHeader:", err);
            showToast("❌ Unexpected error: " + err.message, "error");
            reject(err);
        }
    });
}





// ============================
// ==== Initialize Everything ====
function initializeQuotationDataTable() {
    if (!DOM.quotationTable || $.fn.DataTable.isDataTable(DOM.quotationTable)) return;

    quotationDataTable = $(DOM.quotationTable).DataTable({
        ajax: {
            url: '/quotations/list', // المصدر من Laravel
            type: 'GET',
            dataSrc: '' // JSON مصفوفة مباشرة
        },
        responsive: false, // تم تعطيل Responsive للسماح بـ scrollX
        orderCellsTop: true,
        scrollX: true,
        scrollY: '400px',
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: true,
        scrollCollapse: true,
        dom: '<"top"lf>rt<"bottom"ip>',
        columns: [
            // 1. اختيار الصف
            {
                data: 'id',
                title: '<input type="checkbox" onclick="toggleSelectAllQuotations(this)" />',
                orderable: false,
                searchable: false,
                render: data => `<input type="checkbox" class="select-row-checkbox" data-id="${data}"/>`
            },
            // 2. حالات أيقونات
            { data: 'isNew', orderable: false, searchable: false, width: '40px', render: d => d ? '<i class="fas fa-circle" style="color: grey;" title="جديد"></i>' : '' },
            { data: 'isSent', orderable: false, searchable: false, width: '40px', render: d => d ? '<i class="fas fa-list-alt" style="color: blue;" title="مكتمل"></i>' : '' },
            { data: 'isActive', orderable: false, searchable: false, width: '40px', render: d => d ? '<i class="fas fa-play-circle" style="color: green;" title="فعال"></i>' : '' },
            { data: 'isApproved', orderable: false, searchable: false, width: '40px', render: d => d ? '<i class="fas fa-check-circle" style="color: #28a745;" title="معتمد"></i>' : '' },
            { data: 'isRejected', orderable: false, searchable: false, width: '40px', render: d => d ? '<i class="fas fa-exclamation-triangle" style="color: red;" title="مرفوض"></i>' : '' },

            // 3. باقي الأعمدة كما في نسخة Local
            { data: 'quote_category', defaultContent: '' },
            { data: 'quote_no', defaultContent: '' },
            { data: 'rev', defaultContent: '' },
            { data: 'quote_date', defaultContent: '' },
            { data: 'project_code', defaultContent: '' },
            { data: 'legacy_no', defaultContent: '' },
            { data: 'legacy_date', defaultContent: '' },
            { data: 'customer_name', defaultContent: '' },
            { data: 'project_name', defaultContent: '' },
            { data: 'project_details', defaultContent: '' },
            { data: 'subject', defaultContent: '' },
            { data: 'contact_from', defaultContent: '' },
            { data: 'inquiry', defaultContent: '' },
            { data: 'contact', defaultContent: '' },
            { data: 'to', defaultContent: '' },
            { data: 'attn_to', defaultContent: '' },
            { data: 'attn_pos', defaultContent: '' },
            { data: 'discount', className: 'dt-body-right', defaultContent: '' },
            { data: 'vat', className: 'dt-body-right', defaultContent: '' },
            { data: 'validity', className: 'dt-body-right', defaultContent: '' },
            { data: 'currency', defaultContent: '' },
            { data: 'payment_terms', defaultContent: '' },
            { data: 'method', defaultContent: '' },
            { data: 'remarks', defaultContent: '' },
            { data: 'quote_file', defaultContent: '' },
            { data: 'file_status', defaultContent: '' },
            { data: 'declined', render: d => d ? 'Yes' : 'No', defaultContent: '' },
            { data: 'declined_message', defaultContent: '' },

            // 4. عمود الإجراءات
            {
                render: function(data, type, row) {
                    return `<i class="fas fa-edit btn-edit" style="cursor:pointer;color:#17a2b8;" title="تعديل"></i>
                <i class="fas fa-trash-alt btn-delete" style="cursor:pointer;color:#dc3545;margin-left:10px;" title="حذف"></i>`;
                },
                orderable: false
            }
        ],
        initComplete: function() {
            const api = this.api();

            // التعامل مع checkbox لكل صف
            $('#quotationTable tbody').on('change', 'input.select-row-checkbox', function() {
                $(this).closest('tr').toggleClass('selected-row', this.checked);
                updateSelectAllCheckbox();
            });

            // Fixed Pagination أسفل الجدول
            if (DOM.fixedPaginationContainer) {
                const wrapper = $(api.table().container());
                const infoElement = wrapper.find('.dataTables_info');
                const paginateElement = wrapper.find('.dataTables_paginate');
                DOM.fixedPaginationContainer.innerHTML = '';
                infoElement.appendTo(DOM.fixedPaginationContainer);
                paginateElement.appendTo(DOM.fixedPaginationContainer);
            }

            // Filter row للأعمدة القابلة للبحث
            $('#quotationTable thead tr.filter-row input').each(function(i) {
                const column = api.column(i + 6); // الأعمدة غير القابلة للبحث (الحالة) = 6
                $(this).on('keyup change clear', function() {
                    if (column.search() !== this.value) {
                        column.search(this.value).draw();
                    }
                });
            });
        }
    });

    console.log("DataTables initialized with full features (Local layout + AJAX data).");
}

$('#quotationTable tbody').on('click', '.btn-edit', function() {
    const rowData = quotationDataTable.row($(this).closest('tr')).data();
    editQuotationModal(rowData.id);
});

$('#quotationTable tbody').on('click', '.btn-delete', function() {
    const rowData = quotationDataTable.row($(this).closest('tr')).data();
    deleteSelectedQuotation(rowData.id);
});
function editQuotationModal(quotationId) {
    if (!quotationId) return;

    console.log(`تعديل عرض الأسعار رقم: ${quotationId}`);
    showToast("جارٍ جلب بيانات عرض الأسعار...", "info");

    fetch(`/quotations/${quotationId}`)
        .then(response => {
            if (!response.ok) throw new Error('فشل جلب بيانات عرض الأسعار.');
            return response.json();
        })
        .then(data => {
            console.log("تم جلب بيانات عرض الأسعار بنجاح:", data);

            // إعادة تعيين النموذج
            resetQuotationForm();

            // --- تعبئة الحقول ---
            // --- Project/Customer Info ---
           // Customer & Project IDs
if (DOM.quoteCustomer) {
    DOM.quoteCustomer.value = data.customer_name || '';
    DOM.quoteCustomer.dataset.id = data.customer_id || '';
}

if (DOM.quoteProject) {
    DOM.quoteProject.value = data.projectName || '';
    DOM.quoteProject.dataset.id = data.project_id || '';
}

if (DOM.quoteContactPerson) {
    DOM.quoteContactPerson.value = data.contact_person || '';
    DOM.quoteContactPerson.dataset.id = data.contact_id || '';
}


            DOM.quoteCategory.value = data.quote_category || '';
            DOM.quoteNo.value = data.quote_no || '';
            DOM.quoteRev.value = data.rev || '';
            DOM.quoteDate.value = data.quote_date || '';
            DOM.quoteProjectCodeInput.value = data.project_code || '';
            DOM.quoteLegacyNo.value = data.legacy_no || '';
            DOM.quoteLegacyDate.value = data.legacy_date || '';
            DOM.quoteCustomer.value = data.customer_name || '';
            DOM.quoteProject.value = data.projectName || '';
            DOM.quoteProjectDetails.value = data.project_details || '';
            DOM.quoteSubject.value = data.subject || '';

            // Contact Info
            DOM.quoteContactFrom.value = data.contact_from || '';
            DOM.quoteInquiry.value = data.inquiry || '';
            DOM.quoteContactPerson.value = data.contact_person || '';
            DOM.quoteContactTo.value = data.contact_to || '';
            DOM.quoteAttnTo.value = data.attn_to || '';
            DOM.quoteAttnPos.value = data.attn_pos || '';
            DOM.quoteContactEmail.value = data.contact_email || '';
            DOM.quoteContactMobile.value = data.contact_mobile || '';

            // Terms
            DOM.quoteCurrency.value = data.currency || 'SAR';
            DOM.quoteDiscount.value = parseFloat(data.discount) || 0;
            DOM.quoteVAT.value = parseFloat(data.vat) || 0;
            DOM.quoteValidity.value = data.validity_days || 0;
            DOM.quotePaymentTermsInput.value = data.payment_terms || '';
            DOM.quoteMethod.value = data.method || '';
            DOM.quoteUseAltForm.checked = !!data.use_alt_form;

            // Additional Info
            DOM.quoteRemarks.value = data.remarks || '';
            DOM.quoteQuoteFile.value = data.quote_file || '';
            DOM.quoteFileStatus.value = data.file_status || '';
            DOM.quoteDeclined.checked = !!data.declined;
            DOM.quoteDeclinedMessage.value = data.declined_message || '';
            DOM.quoteProjectDetails.value = data.project_details || '';


            // Financials
            DOM.financialTotalLines.value = parseFloat(data.total_lines) || 0;
            DOM.financialDiscountAmount.value = parseFloat(data.discount_amount) || 0;
            DOM.financialTaxAmount.value = parseFloat(data.tax_amount) || 0;
            DOM.financialGrandTotal.value = parseFloat(data.grand_total) || 0;

            // Quote Status
            DOM.quoteOverallStatus.value = data.overall_status || '';
            DOM.quoteLastConfirmation.value = data.last_confirmation || '';
            DOM.quoteLastConfirmed.value = data.last_confirmed || '';

            // --- إعداد المودال ---
            if (DOM.quotationModal) {
                DOM.quotationModal.dataset.mode = 'edit';
                DOM.quotationModal.dataset.quotationId = quotationId;

                const modal = new bootstrap.Modal(DOM.quotationModal);
                modal.show();
            }

            // --- ربط أزرار الحفظ ---
            DOM.saveHeaderTabBtn.onclick = () => saveQuotationHeader(true, quotationId);
            DOM.saveAndCloseHeaderTabBtn.onclick = () => saveQuotationHeader(true, quotationId)
                .then(() => closeQuotationModal());

            showToast("تم جلب البيانات بنجاح، يمكنك الآن التعديل.", "success");
        })
        .catch(error => {
            console.error('خطأ في جلب بيانات عرض الأسعار:', error);
            showToast("فشل جلب البيانات: " + error.message, "error");
        });
}

// عند فتح مودال إنشاء جديد
function openNewQuotationModal() {
    resetQuotationForm();

    DOM.quotationModal.dataset.mode = 'create';
    DOM.quotationModal.dataset.quotationId = '';

    const modal = new bootstrap.Modal(DOM.quotationModal);
    modal.show();

    DOM.saveHeaderTabBtn.onclick = () => saveQuotationHeader(false);
    DOM.saveAndCloseHeaderTabBtn.onclick = () => saveQuotationHeader(false)
        .then(() => closeQuotationModal());
}



function initializeCategoryDropdown() {
    // الحصول على عناصر DOM. تأكد من تهيئة كائن DOM بشكل صحيح في مكان آخر.
    const categoryInputField = DOM.quoteCategory; // حقل إدخال الفئة
    const showCategoryListButton = DOM.showCategoryListBtn; // زر إظهار القائمة المنسدلة
    const categoryDropdownList = DOM.categoryDropdown; // حاوية القائمة المنسدلة نفسها

    // تحقق أساسي: تأكد من العثور على جميع عناصر DOM المطلوبة قبل المتابعة.
    if (!categoryInputField || !showCategoryListButton || !categoryDropdownList) {
        console.error("لم يتم العثور على عناصر DOM المطلوبة للقائمة المنسدلة للفئة. يرجى التحقق من معرفات (IDs) 'quoteCategory', 'showCategoryListBtn', 'categoryDropdown'.");
        return; // الخروج من الدالة إذا كانت العناصر مفقودة.
    }

    // --- بداية التعديل الجديد: تعريف categoryOptions ---
    // مصفوفة خيارات الفئات. (يمكن تعريفها كـ const عامة في أعلى ملف JavaScript لسهولة الوصول إليها عالمياً)
    const categoryOptions = [
        { type: "AAM-GT", value: "proposal_geotechnical", text: "Proposal for Geotechnical" },
        { type: "AAM-MT", value: "proposal_material_testing", text: "Proposal for Material Testing" }
    ];
    // --- نهاية التعديل الجديد ---

    /**
     * تقوم بعرض خيارات الفئات داخل القائمة المنسدلة،
     * وتُظهرها في عمودين: 'النوع' (Type) و 'اسم الفئة' (Category Name).
     * @param {Array} optionsToRender - مصفوفة من كائنات الفئة {type, value, text}.
     */
    function renderCategoryDropdown(optionsToRender) {
        // 1. مسح أي محتوى موجود حالياً في القائمة المنسدلة لتجنب التكرار عند إعادة العرض.
        categoryDropdownList.innerHTML = '';

        // 2. إذا لم تكن هناك خيارات لعرضها، قم بإخفاء القائمة والخروج.
        if (optionsToRender.length === 0) {
            categoryDropdownList.style.display = 'none';
            return;
        }

        // --- بداية التعديل الجديد: إضافة صف الرأس للعمودين ---
        // 3. إنشاء وإلحاق صف الرأس لجدول القائمة المنسدلة (اختياري لكن موصى به للوضوح).
        const headerRow = document.createElement('div');
        headerRow.classList.add('custom-dropdown-header-row'); // إضافة فئة CSS لتنسيق الرأس.
        headerRow.innerHTML = `<div class="dropdown-column-type-header">theType </div><div class="dropdown-column-name-header">Category</div>`;
        categoryDropdownList.appendChild(headerRow);
        // --- نهاية التعديل الجديد ---

        // 4. المرور على كل خيار لإنشاء صفه الخاص في القائمة المنسدلة.
        optionsToRender.forEach(option => {
            const item = document.createElement('div');
            item.classList.add('custom-dropdown-item'); // إضافة فئة CSS لتنسيق صف العنصر.
            item.setAttribute('data-value', option.value); // تخزين قيمة البيانات الفعلية (مثلاً "proposal_geotechnical").

            // --- بداية التعديل الجديد: إنشاء وتعبئة عمودين لكل عنصر ---
            // إنشاء العمود الأول لـ 'النوع' (Type).
            const typeCol = document.createElement('div');
            typeCol.classList.add('dropdown-column-type'); // إضافة فئة CSS لتنسيق عمود 'النوع'.
            typeCol.textContent = option.type; // تعيين المحتوى إلى "AAM-GT" أو "AAM-MT".

            // إنشاء العمود الثاني لـ 'اسم الفئة' (Category Name).
            const nameCol = document.createElement('div');
            nameCol.classList.add('dropdown-column-name'); // إضافة فئة CSS لتنسيق عمود 'اسم الفئة'.
            nameCol.textContent = option.text; // تعيين المحتوى إلى "Proposal for Geotechnical" وما إلى ذلك.

            // إلحاق العمودين بصف العنصر.
            item.appendChild(typeCol);
            item.appendChild(nameCol);
            // --- نهاية التعديل الجديد ---

            // 5. إضافة مستمع حدث النقر لكل عنصر في القائمة المنسدلة.
            item.addEventListener('click', function() {
                // تحديث حقل الإدخال بالنص المقروء.
                categoryInputField.value = option.text;
                // تخزين قيمة الفئة الفعلية في خاصية بيانات مخصصة للمنطق الداخلي.
                categoryInputField.setAttribute('data-selected-value', option.value);
                // إخفاء القائمة المنسدلة بعد الاختيار.
                categoryDropdownList.style.display = 'none';
                // استدعاء الدالة لتوليد رقم الاقتباس بناءً على القيمة المختارة.
                generateQuotationNumber(option.value);

                // إذا كان لديك دالة لتحديد الحقول المطلوبة، قم بإلغاء التعليق عليها واستخدامها:
                // markRequiredField(categoryInputField, false);
            });

            // 6. إلحاق صف العنصر (الصف الذي يحتوي على الأعمدة) بحاوية القائمة المنسدلة.
            categoryDropdownList.appendChild(item);
        });

        // 7. أخيراً، جعل القائمة المنسدلة مرئية.
        categoryDropdownList.style.display = 'block';
    }

    // --- مستمعات الأحداث والحالة الأولية لهذه القائمة المنسدلة ---

    // 1. مستمع حدث النقر لزر تبديل القائمة المنسدلة (زر إظهار القائمة).
    showCategoryListButton.addEventListener('click', function(event) {
        event.stopPropagation(); // منع الحدث من الانتشار إلى مستمع النقر على المستند أدناه.

        if (categoryDropdownList.style.display === 'block') {
            categoryDropdownList.style.display = 'none'; // إذا كانت مرئية حالياً، قم بإخفائها.
        } else {
            renderCategoryDropdown(categoryOptions); // إذا كانت مخفية، قم بعرضها وإظهارها.
            // اختياري: تركيز على حقل الإدخال عند فتح القائمة المنسدلة:
            // categoryInputField.focus();
        }
    });

    // --- بداية التعديل الجديد: مستمع النقر على المستند والإخفاء الأولي الموحد ---
    // 2. مستمع حدث لإغلاق القائمة المنسدلة عند النقر في أي مكان خارجها.
    // يجب إضافة هذا المستمع مرة واحدة فقط أثناء التهيئة.
    document.addEventListener('click', function(event) {
        // التحقق مما إذا كان هدف النقر ليس حقل الإدخال، وليس زر التبديل،
        // وليس داخل القائمة المنسدلة نفسها.
        if (event.target !== categoryInputField &&
            event.target !== showCategoryListButton &&
            !categoryDropdownList.contains(event.target)) {
            categoryDropdownList.style.display = 'none'; // إخفاء القائمة المنسدلة.
        }
    });

    // 3. الحالة الأولية: التأكد من أن القائمة المنسدلة مخفية عند تحميل الصفحة لأول مرة.
    // يجب تنفيذ هذا السطر مرة واحدة أيضاً أثناء التهيئة.
    categoryDropdownList.style.display = 'none';
    // --- نهاية التعديل الجديد ---
}


/**
 * Generates a unique quotation number based on the selected category.
 * Updates the 'Quote No.' input field (DOM.quoteNo).
 * @param {string} categoryValue - The value of the selected category (e.g., 'proposal_geotechnical').
 */
function generateQuotationNumber(categoryValue) {
    if (!categoryValue) {
        DOM.quoteNo.value = '';
        return;
    }

    let prefix = '';
    let lastNum = 0;

    switch (categoryValue) {
        case 'proposal_geotechnical':
            prefix = 'GT';
            lastNum = lastQuotationNumbers.proposal_geotechnical;
            break;
        case 'proposal_material_testing':
            prefix = 'MT';
            lastNum = lastQuotationNumbers.proposal_material_testing;
            break;
        default:
            DOM.quoteNo.value = '';
            console.warn(`Unknown category selected: ${categoryValue}`);
            return;
    }

    const nextNum = lastNum + 1;
    const formattedNum = String(nextNum).padStart(4, '0');

    if (categoryValue === 'proposal_geotechnical') {
        lastQuotationNumbers.proposal_geotechnical = nextNum;
    } else if (categoryValue === 'proposal_material_testing') {
        lastQuotationNumbers.proposal_material_testing = nextNum;
    }

    DOM.quoteNo.value = `${prefix}-${formattedNum}`;
    console.log(`Generated Quotation Number: ${DOM.quoteNo.value} for category: ${categoryValue}`);
}


// =====================================================================
// Functions for File Handling and PDF Generation
// =====================================================================

function handleQuoteFileSelection() {
    if (DOM.quoteQuoteFileInput && DOM.quoteQuoteFileInput.files.length > 0) {
        const fileName = DOM.quoteQuoteFileInput.files[0].name;
        if (DOM.quoteQuoteFileText) DOM.quoteQuoteFileText.value = fileName;
        if (DOM.quoteFileStatus) DOM.quoteFileStatus.value = 'File Attached';
        console.log("File selected:", fileName);
    } else {
        if (DOM.quoteQuoteFileText) DOM.quoteQuoteFileText.value = '';
        if (DOM.quoteFileStatus) DOM.quoteFileStatus.value = 'No File Selected';
        console.log("No file selected.");
    }
}

function generateQuotationPdf(quotationId) {
    fetch(`/quotations/${quotationId}/generate-pdf`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('فشل تحديث قاعدة البيانات');
        return response.json();
    })
    .then(data => {
        showToast(data.message, 'success');

        // تحديث العنصر المحلي في الصفحة
        if (DOM.quoteFileStatus) DOM.quoteFileStatus.value = data.file_status;

        // إعادة تحميل DataTable إذا كنت تستخدمها
        if (typeof quotationDataTable !== 'undefined') quotationDataTable.ajax.reload(null, false);
    })
    .catch(error => {
        console.error(error);
        showToast(error.message, 'error');
    });
}


// =====================================================================
// Event Listeners Setup
// Combines dynamic DOM event listeners and new tab button listeners
// =====================================================================
function setupEventListeners() {
    // General Modal Open Button

    if (DOM.newQuotationBtn) {
        DOM.newQuotationBtn.addEventListener('click', openQuotationModal);
    }

    // File Input and PDF Button
    if (DOM.quoteQuoteFileBtn) {
        DOM.quoteQuoteFileBtn.addEventListener('click', function() {
            if (DOM.quoteQuoteFileInput) {
                DOM.quoteQuoteFileInput.click();
            }
        });
    }

    if (DOM.quoteQuoteFileInput) {
        DOM.quoteQuoteFileInput.addEventListener('change', handleQuoteFileSelection);
    }

    if (DOM.generatePdfButton) {
        DOM.generatePdfButton.addEventListener('click', generateQuotationPdf);
    }

    // Main Quotation Table Select All Checkbox
    if (DOM.masterCheckbox) {
        DOM.masterCheckbox.addEventListener('change', function() {
            toggleSelectAllQuotations(this);
        });
    }


    // Header Tab Specific Buttons
    if (DOM.saveHeaderTabBtn) {
        DOM.saveHeaderTabBtn.addEventListener('click', saveQuotationHeader);
    }
    if (DOM.closeHeaderTabBtn) {
        DOM.closeHeaderTabBtn.addEventListener('click', closeQuotationModal);
    }
    if (DOM.saveAndCloseHeaderTabBtn) {
        DOM.saveAndCloseHeaderTabBtn.addEventListener('click', saveAndCloseQuotationHeader);
    }

    // Quote Lines Tab Specific Buttons
    if (DOM.saveLinesTabBtn) {
        DOM.saveLinesTabBtn.addEventListener('click', saveQuoteLines);
    }
    if (DOM.closeLinesTabBtn) {
        DOM.closeLinesTabBtn.addEventListener('click', closeQuotationModal);
    }
    if (DOM.saveAndCloseLinesTabBtn) {
        DOM.saveAndCloseLinesTabBtn.addEventListener('click', saveAndCloseQuoteLines);
    }

    // **** إضافة حدث لزر تصدير Excel ****
    if (DOM.exportToExcelBtn) {
        DOM.exportToExcelBtn.addEventListener('click', exportQuoteLinesToExcel);
    } else {
        // رسالة تحذير مفيدة في الكونسول إذا لم يتم العثور على الزر
        console.warn("Export to Excel button (exportToExcelBtn) not found in DOM.");
    }

    // **** إضافة حدث لزر الطباعة ****
    if (DOM.printQuoteLinesBtn) {
        DOM.printQuoteLinesBtn.addEventListener('click', printQuoteLinesTable);
    } else {
        // رسالة تحذير مفيدة في الكونسول إذا لم يتم العثور على الزر
        console.warn("Print button (printQuoteLinesBtn) not found in DOM.");
    }

    // Price List Modal Button (assuming openPriceListModal is defined elsewhere)
    // You might have a button to open the price list modal, e.g.:
    // if (DOM.openPriceListBtn) {
    //    DOM.openPriceListBtn.addEventListener('click', openPriceListModal);
    // }
    if (DOM.addSelectedItemsBtn) {
        DOM.addSelectedItemsBtn.addEventListener('click', function() {
            // This function would typically be in priceList.js or a related file
            // addSelectedPriceListItemsToQuoteLines(priceListDataTable);
            console.log("Add Selected Items button clicked.");
            showToast("Items added (simulated)!", "success");
            // Example:
            // addSelectedItemsToQuoteLines(quotationLinesDataTable, priceListDataTable);
        });
    }
// Main Quotation Table Select All Checkbox
    if (DOM.masterQuotationCheckbox) {
        DOM.masterQuotationCheckbox.addEventListener('change', function() {
            toggleSelectAllQuotations(this);
        });
    }

    // --- Buttons for the Main Quotation Table ---
    if (DOM.editQuotationBtn) {
        DOM.editQuotationBtn.addEventListener('click', function() {
            const id = getSingleSelectedQuotationId();
            editQuotationModal(id);
        });
    }

    if (DOM.deleteQuotationBtn) {
        DOM.deleteQuotationBtn.addEventListener('click', deleteSelectedQuotation);
    }

    if (DOM.exportQuotationToExcelBtn) {
        DOM.exportQuotationToExcelBtn.addEventListener('click', exportSelectedToExcel);
    }

    if (DOM.printQuotationBtn) {
        DOM.printQuotationBtn.addEventListener('click', printSelectedRows);
    }

    console.log("All event listeners setup.");
}


// =====================================================================
// Document Ready and Initialization
// =====================================================================

function initializeQuotationLinesDataTable() {
    if (DOM.quotationLinesTable && !$.fn.DataTable.isDataTable(DOM.quotationLinesTable)) {
        console.log("Initializing DataTables for 'quotationLinesTable'...");

        quotationLinesDataTable = $(DOM.quotationLinesTable).DataTable({
            ajax: {
                url: '/quotations/lines/data', // هذا الراوت يرجع JSON من Laravel
                type: 'GET',
                dataSrc: '' // البيانات عبارة عن مصفوفة JSON
            },
            scrollX: true,
            scrollY: "400px",
            autoWidth: false,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            dom: '<"top"Bfl>rt<"bottom"ip>',
            buttons: [
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> طباعة جدول سطور عروض الأسعار',
                    title: 'تقرير سطور عروض الأسعار',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: function(idx, data, node) {
                            const headerNode = quotationLinesDataTable.column(idx).header();
                            if (!headerNode) return false;
                            const headerText = headerNode.textContent.trim().toLowerCase();
                            return headerText !== '' && headerText !== 'actions' && headerText !== 'select' && headerText !== 'checkbox';
                        },
                        stripHtml: true
                    },
                    messageTop: function() {
                        return 'بيانات سطور عروض الأسعار - تاريخ الطباعة: ' + new Date().toLocaleDateString();
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fas fa-file-excel"></i> تصدير إلى Excel',
                    title: 'بيانات سطور عروض الأسعار',
                    exportOptions: {
                        columns: function(idx, data, node) {
                            const headerNode = quotationLinesDataTable.column(idx).header();
                            if (!headerNode) return false;
                            const headerText = headerNode.textContent.trim().toLowerCase();
                            return headerText !== '' && headerText !== 'actions' && headerText !== 'select' && headerText !== 'checkbox';
                        },
                        stripHtml: true
                    }
                }
            ],
            responsive: false,
            pagingType: "full_numbers",
            scrollCollapse: true,
            language: {
                processing: "جاري المعالجة...",
                search: "بحث:",
                lengthMenu: "عرض _MENU_ سجلات",
                info: "عرض _START_ إلى _END_ من _TOTAL_ سجل",
                infoEmpty: "عرض 0 إلى 0 من 0 سجل",
                infoFiltered: "(تمت التصفية من _MAX_ إجمالي السجلات)",
                loadingRecords: "جاري تحميل السجلات...",
                zeroRecords: "لا توجد سجلات مطابقة",
                emptyTable: "لا توجد بيانات متاحة في الجدول",
                paginate: {
                    first: "الأول",
                    previous: "السابق",
                    next: "التالي",
                    last: "الأخير"
                },
                aria: {
                    sortAscending: ": تفعيل لترتيب العمود تصاعدياً",
                    sortDescending: ": تفعيل لترتيب العمود تنازلياً"
                }
            },
            columns: [
                { data: "id", orderable: false, width: "30px", render: d => `<input type="checkbox" class="select-row-checkbox" value="${d}">` },
                { data: "service_id", width: "120px" },
                { data: "description", width: "200px" },
                { data: "accounted", width: "80px" },
                { data: "category", width: "100px" },
                { data: "type", width: "80px" },
                { data: "method", width: "80px" },
                { data: null, orderable: false, width: "100px", render: (data, type, row) =>
                    `<button class="btn btn-sm btn-primary" onclick="editQuoteLine(this)">✏️</button>
                     <button class="btn btn-sm btn-danger" onclick="deleteQuoteLine(this)">🗑️</button>`
                }
            ],
            fixedColumns: { leftColumns: 1 },
            initComplete: function() {
                $('#quotationLinesTable tbody').on('change', 'input[type="checkbox"]', function() {
                    $(this).closest('tr').toggleClass('selected-row', this.checked);
                });
                if (quotationLinesDataTable) quotationLinesDataTable.columns.adjust();
            },
            drawCallback: function() {
                if (quotationLinesDataTable) quotationLinesDataTable.columns.adjust();
            }
        });

        // Filters الأعمدة
        $('#quotationLinesTable tfoot tr.filter-row input').each(function(i) {
            var that = quotationLinesDataTable.column(i);
            $(this).on('keyup change clear', function() {
                if (that.search() !== this.value) that.search(this.value).draw();
            });
        });

        // مربع الاختيار الرئيسي
        if (DOM.selectAllLinesCheckbox) {
            DOM.selectAllLinesCheckbox.addEventListener('change', function() {
                toggleSelectAllQuoteLines(this);
            });
        }

        console.log("DataTables initialized for 'quotationLinesTable' successfully.");
    }
}


function addQuoteLine() {
    console.log("Add Empty Line button clicked.");

    // بيانات السطر الجديد (يمكنك تركها فارغة أو ملء بعض القيم الافتراضية)
    const newLineData = {
        description: '',
        accounted: '',
        category: '',
        type: '',
        method: '',
        quotation_id: currentQuotationId // يجب أن يكون لديك ID عرض الأسعار الحالي
    };

    // AJAX لإضافة السطر في قاعدة البيانات
    $.ajax({
        url: '/quotations/lines/store',
        type: 'POST',
        data: newLineData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // مهم للـ Laravel
        },
        success: function(response) {
            // أضف السطر الجديد للـ DataTable مباشرة بعد أن تم حفظه في DB
            if (quotationLinesDataTable) {
                quotationLinesDataTable.row.add(response).draw(false);
                showToast("تم إضافة سطر جديد بنجاح!", "info");
            }
        },
        error: function(xhr) {
            console.error("Error adding quote line:", xhr.responseText);
            showToast("حدث خطأ أثناء إضافة السطر.", "error");
        }
    });
}




// =====================================================================
// Placeholder/Assumed External Functions (Implement these as needed)
// =====================================================================



function copyQuoteLine() {
    const selectedRows = quotationLinesDataTable.rows(':has(input[type="checkbox"]:checked)').data();
    if (selectedRows.length > 0) {
        // Store a deep copy of the data, excluding the checkbox and action buttons
        window.copiedQuoteLineData = selectedRows[0].toArray().slice(1, -1); // Exclude first (checkbox) and last (actions)
        alert("Line copied! Now click Paste Line to add it.");
        console.log("Copied Line Data:", window.copiedQuoteLineData);
    } else {
        alert("Please select a line to copy.");
    }
}

function pasteQuoteLine() {
    if (window.copiedQuoteLineData) {
        const newRowData = ['<input type="checkbox">', ...window.copiedQuoteLineData, '<button class="btn btn-sm btn-info edit-btn" onclick="editQuoteLine(this)"><i class="fas fa-edit"></i></button> <button class="btn btn-sm btn-danger delete-btn" onclick="deleteQuoteLine(this)"><i class="fas fa-trash-alt"></i></button>'];
        quotationLinesDataTable.row.add(newRowData).draw(false);
        alert("Line pasted!");
        console.log("Pasted new line:", newRowData);
    } else {
        alert("No line copied yet. Please copy a line first.");
    }
}

// عند تهيئة جدول quotationLinesDataTable:
$(document).ready(function() { // تأكد من أن الكود يعمل بعد تحميل DOM
    if (DOM.quotationLinesTable && !$.fn.DataTable.isDataTable(DOM.quotationLinesTable)) {
        quotationLinesDataTable = $(DOM.quotationLinesTable).DataTable({
            // ... إعدادات DataTables الأخرى ...
        });

        // ربط حقول الإدخال المخصصة بالبحث في الأعمدة
        $('#quotationLinesTable thead tr.filter-row input').on('keyup change', function() {
            quotationLinesDataTable
                .column($(this).parent().index()) // الحصول على فهرس العمود
                .search(this.value)
                .draw();
        });
    }
});

// دالة مسح الفلاتر المعدلة
function clearLinesFilters() {
    if (quotationLinesDataTable) {
        // مسح قيمة حقول الإدخال بصرياً
        $('#quotationLinesTable thead tr.filter-row input').val('');

        // مسح فلاتر DataTables برمجياً وإعادة الرسم
        quotationLinesDataTable.columns().search('').draw();

        alert("Filters cleared for Quote Lines table.");
    } else {
        alert("Quotation Lines table not initialized.");
        console.error("quotationLinesDataTable is null or undefined.");
    }
}
function refreshQuoteLinesTable() {
    if (quotationLinesDataTable) {
        // In a real app, this would re-fetch data from a backend
        quotationLinesDataTable.clear().draw();
        // For now, just a dummy refresh
        alert("Quote Lines table refreshed (data cleared).");
    } else {
        alert("Quote Lines table not initialized.");
    }
}

function exportQuoteLinesToExcel() {
    if (quotationLinesDataTable) {
        console.log("Starting export for quotationLinesTable...");

        // 1. استخراج البيانات المرئية/المفلترة فقط
        // استخدام rows({ search: 'applied' }) يضمن أنك تحصل على الصفوف التي تظهر حاليًا بعد أي تصفية أو بحث.
        const dataRows = quotationLinesDataTable.rows({ search: 'applied' }).data();
        const numRows = dataRows.length;

        if (numRows === 0) {
            alert("No data to export in Quote Lines table.");
            console.warn("No rows found in quotationLinesTable with applied filters for export.");
            return;
        }

        // 2. استخراج رؤوس الأعمدة المرئية وتصفيتها
        // DataTables.columns().header() يعطي عناصر الـ <th>
        const headers = quotationLinesDataTable.columns().header().toArray().map(th => th.textContent.trim());

        // قم بتصفية الرؤوس لاستبعاد العمود الفارغ، عمود الإجراءات، وأي رأس لـ checkbox
        // يمكنك إضافة المزيد من الشروط هنا إذا كانت هناك رؤوس أعمدة أخرى لا تريد تصديرها
        const filteredHeaders = headers.filter(header =>
            header !== '' &&          // استبعاد الرؤوس الفارغة (عادة لـ checkboxes أو أعمدة مخصصة)
            header.toLowerCase() !== 'actions' && // استبعاد عمود الإجراءات (غير حساس لحالة الأحرف)
            header.toLowerCase() !== 'select'     // استبعاد رأس عمود التحديد/checkbox إذا كان موجودًا
        );

        console.log("Original Headers:", headers);
        console.log("Filtered Headers for Excel:", filteredHeaders);

        // 3. معالجة وتنظيف بيانات الصفوف
        const cleanedData = [];
        dataRows.each(function(rowData, dataIndex) {
            const tempRow = [];

            quotationLinesDataTable.columns().every(function(colIdx) {
                const headerText = this.header().textContent.trim();

                if (filteredHeaders.includes(headerText)) {
                    let cellContent = rowData[colIdx];

                    // Check if cellContent is an HTML string and extract text
                    if (typeof cellContent === 'string' && $(cellContent).length > 0) {
                        // Create a temporary div to parse HTML and get text content
                        const tempDiv = $('<div>').html(cellContent);
                        cellContent = tempDiv.text().trim();
                    }
                    // Handle cases where DataTables might store objects or other types
                    else if (typeof cellContent === 'object' && cellContent !== null) {
                        cellContent = String(cellContent); // Convert object to string representation
                    }
                    // If the cell content is empty or only whitespace, convert it to an empty string
                    if (cellContent === undefined || cellContent === null || (typeof cellContent === 'string' && cellContent.trim() === '')) {
                         cellContent = '';
                    }

                    tempRow.push(cellContent);
                }
            });
            cleanedData.push(tempRow);
        });

        console.log("Cleaned Data for Excel:", cleanedData);

        // 4. بناء ورقة العمل والملف Excel
        if (typeof XLSX === 'undefined') {
            console.error("XLSX library (SheetJS) is not loaded. Make sure the script is included.");
            alert("Export failed: XLSX library not found. Please contact support.");
            return;
        }

        const worksheet = XLSX.utils.aoa_to_sheet([filteredHeaders, ...cleanedData]);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Quotation Lines Data"); // اسم الورقة في Excel
        XLSX.writeFile(workbook, "QuotationLines_Export.xlsx"); // اسم ملف Excel

        alert("Quotation Lines exported to Excel successfully!");
    } else {
        alert("Quotation Lines table not initialized for export.");
        console.error("quotationLinesDataTable is null or undefined. Ensure it's initialized.");
    }
}

function printQuoteLinesTable() {
   if (quotationLinesDataTable) {
        console.log("Preparing quotationLinesTable for printing - ALL COLUMNS AND ROWS...");

        // فهارس الأعمدة التي نريد طباعتها (باستثناء Checkbox و Actions)
        // هذا يتطابق مع ترتيب الأعمدة في HTML
        const columnsToPrintIndexes = [1, 2, 3, 4, 5, 6]; // Service/Test Id to Method

        // 1. استخراج رؤوس الأعمدة المراد طباعتها فقط
        const filteredHeaders = columnsToPrintIndexes.map(idx => {
            const headerElement = quotationLinesDataTable.column(idx).header();
            // تحقق مما إذا كان العنصر موجودًا قبل الوصول إلى textContent
            return headerElement ? headerElement.textContent.trim() : '';
        }).filter(header => header !== ''); // فلتر لأي رؤوس فارغة قد تنتج عن خطأ

        console.log("Original Headers (all - for debugging):", quotationLinesDataTable.columns().header().toArray().map(th => th.textContent.trim()));
        console.log("Filtered Headers for print (by index):", filteredHeaders);

        if (filteredHeaders.length === 0) {
            alert("لا توجد أعمدة صالحة للطباعة (ربما خطأ في فهارس الأعمدة أو رؤوسها فارغة).");
            console.warn("Print aborted: No valid headers found after filtering by index.");
            return;
        }

        // 2. استخراج جميع الصفوف المرئية/المفلترة من DataTables
        const dataRows = quotationLinesDataTable.rows({ search: 'applied' }).data();
        const numRows = dataRows.length;

        console.log("Number of data rows (after filters):", numRows);
        // console.log("Raw data from DataTables (first 5 rows for inspection):", dataRows.toArray().slice(0, 5)); // إلغاء التعليق إذا احتجت لمزيد من التفاصيل

        if (numRows === 0) {
            alert("لا توجد بيانات للطباعة في جدول سطور عروض الأسعار (بعد الفلاتر).");
            console.warn("Print aborted: No rows found in quotationLinesTable with applied filters.");
            return;
        }

        // 3. معالجة وتنظيف بيانات الصفوف لضمان استخراج النص من HTML
        const cleanedData = [];
        dataRows.each(function(rowData, dataIndex) {
            const tempRow = [];
            // المرور فقط على الأعمدة المحددة للطباعة
            columnsToPrintIndexes.forEach(colIdx => {
                let cellContent = rowData[colIdx]; // هذا هو محتوى الخلية الخام من DataTables

                // إذا كان المحتوى سلسلة HTML، استخرج النص منها
                if (typeof cellContent === 'string' && $(cellContent).length > 0) {
                    const tempDiv = $('<div>').html(cellContent);
                    cellContent = tempDiv.text().trim();
                }
                // إذا كان المحتوى كائنًا (مثل كائن زر أو أي شيء آخر)، قم بتحويله إلى سلسلة
                else if (typeof cellContent === 'object' && cellContent !== null) {
                    cellContent = String(cellContent);
                }
                // إذا كان المحتوى فارغًا أو مسافات بيضاء فقط، اجعله سلسلة فارغة
                if (cellContent === undefined || cellContent === null || (typeof cellContent === 'string' && cellContent.trim() === '')) {
                     cellContent = '';
                }

                tempRow.push(cellContent);
            });
            cleanedData.push(tempRow);
        });

        console.log("Cleaned Data for print (first 5 processed rows):", cleanedData.slice(0, 5));

        // 4. بناء جدول HTML للطباعة
        let tableHtml = '<h2>تقرير عروض الأسعار</h2>'; // عنوان للتقرير
        tableHtml += '<table border="1" style="width:100%; border-collapse: collapse;">'; // جدول بحدود بسيطة

        // رؤوس الجدول
        tableHtml += '<thead><tr>';
        filteredHeaders.forEach(header => {
            tableHtml += `<th style="padding: 8px; text-align: left; background-color: #f2f2f2; border: 1px solid #ddd;">${header}</th>`;
        });
        tableHtml += '</tr></thead>';

        // جسم الجدول
        tableHtml += '<tbody>';
        cleanedData.forEach(row => {
            tableHtml += '<tr>';
            row.forEach(cell => {
                tableHtml += `<td style="padding: 8px; border: 1px solid #ddd;">${cell}</td>`;
            });
            tableHtml += '</tr>';
        });
        tableHtml += '</tbody>';
        tableHtml += '</table>';

        console.log("Generated table HTML (check this in console, copy-paste to .html file to verify):", tableHtml);

        // 5. فتح نافذة جديدة للطباعة وعرض الجدول
        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>طباعة عروض الأسعار</title>');
        // تضمين CSS لتنسيق الطباعة
        printWindow.document.write('<style>');
        printWindow.document.write(`
            body { font-family: Arial, sans-serif; margin: 20px; }
            h2 { text-align: center; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; font-weight: bold; }
            /* قواعد CSS للطباعة فقط */
            @media print {
                /* إخفاء عناصر غير مرغوب فيها عند الطباعة */
                body * { visibility: hidden; }
                .printable-area, .printable-area * { visibility: visible; }
                .printable-area { position: absolute; left: 0; top: 0; }
                /* ضبط عرض الأعمدة إذا لزم الأمر */
                table { table-layout: fixed; } /* قد يساعد في التحكم في عرض الأعمدة */
            }
        `);
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div class="printable-area">'); // منطقة قابلة للطباعة
        printWindow.document.write(tableHtml);
        printWindow.document.write('</div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close(); // مهم لإغلاق مستند النافذة الجديدة
        printWindow.focus(); // نقل التركيز إلى نافذة الطباعة

        // تأخير الطباعة قليلاً للسماح للمحتوى بالتحميل الكامل
        setTimeout(() => {
            printWindow.print(); // فتح مربع حوار الطباعة
            // printWindow.close(); // يمكنك إزالة التعليق على هذا السطر إذا أردت إغلاق النافذة تلقائيًا بعد الطباعة
        }, 500); // تأخير 500 مللي ثانية (نصف ثانية)

        console.log("Print process initiated for quotationLinesTable.");

    } else {
        alert("Quotation Lines table not initialized for printing.");
        console.error("quotationLinesDataTable is null or undefined for print. Ensure it's initialized.");
    }
}


function initializePriceListDataTable() {
    if (!DOM.priceListTable) return console.error("Price list table not found");

    if (!$.fn.DataTable.isDataTable(DOM.priceListTable)) {
        priceListDataTable = $(DOM.priceListTable).DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/price-lists',
                type: 'GET',
                dataSrc: 'data'
            },
            columns: [
                { // Checkbox column
                    data: null,
                    orderable: false,
                    title: '<input type="checkbox" class="select-all-price-list-items" />',
                    render: function (data, type, row) {
                        return `<input type="checkbox" class="row-master-checkbox" ${row.price_only ? 'checked' : ''}>`;
                    },
                    width: "30px"
                },
                { data: 'id', title: 'ID', width: "80px" },
                { data: 'name', title: 'Name', width: "250px" },
                { data: 'method', title: 'Method', width: "100px" },
                { data: 'unit', title: 'Unit', width: "80px" },
                { data: 'price', title: 'Price', render: function(data){ return `<input type="number" class="price-input" value="${parseFloat(data).toFixed(2)}" step="0.01" style="width:80px">`; }, width: "100px" },
                { data: 'price_only', title: 'Price Only', render: function(data){ return `<input type="checkbox" class="price-only-checkbox" ${data ? 'checked' : ''}>`; }, width: "80px" },
                { data: 'quantity', title: 'Quantity', render: function(data){ return `<input type="number" class="quantity-input" value="${data}" min="0" step="1" style="width:60px">`; }, width: "80px" },
                { data: 'active', title: 'Active', render: function(data){ return `<input type="checkbox" class="active-checkbox" ${data ? 'checked' : ''}>`; }, width: "60px" }
            ],
            scrollX: true,
            scrollY: "400px",
            autoWidth: false,
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            dom: '<"top"lf>rt<"bottom"ip>',
            responsive: false,
            pagingType: "full_numbers",
            scrollCollapse: true,
            fixedColumns: { leftColumns: 1 },
            language: {
                processing: "Processing...",
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)",
                loadingRecords: "Loading records...",
                zeroRecords: "No matching records found",
                emptyTable: "No data available in table",
                paginate: { first:"First", previous:"Previous", next:"Next", last:"Last" }
            },
            initComplete: function() {
                DOM.selectAllPriceListItems = document.querySelector('#priceListTable thead .select-all-price-list-items');

                // Event: master checkbox
                $(DOM.selectAllPriceListItems).on('change', function(){
                    const checked = this.checked;
                    $('#priceListTable tbody input.row-master-checkbox').prop('checked', checked).trigger('change');
                });

                attachPriceListEditableEvents();
            },
            rowCallback: function(row, data) {
        // أضف data-id لكل <tr>
        $(row).attr('data-id', data.id);
    },
            drawCallback: function(){
                priceListDataTable.columns.adjust();
            }

        });
    } else {
        priceListDataTable.ajax.reload(null, false);
        if(DOM.selectAllPriceListItems) DOM.selectAllPriceListItems.checked = false;
        priceListDataTable.$('tbody tr').removeClass('selected-row selected-row-price-only');
    }
}

// ----------------- Attach Editable Events -----------------
function attachPriceListEditableEvents() {
    $('#priceListTable tbody').on('change', '.price-input', function(){
        const row = priceListDataTable.row($(this).closest('tr'));
        const data = row.data();
        data.price = parseFloat($(this).val());
        row.data(data).draw(false);
    });
    $('#priceListTable tbody').on('change', '.quantity-input', function(){
        const row = priceListDataTable.row($(this).closest('tr'));
        const data = row.data();
        data.quantity = parseInt($(this).val());
        row.data(data).draw(false);
    });
    $('#priceListTable tbody').on('change', '.price-only-checkbox', function(){
        const row = priceListDataTable.row($(this).closest('tr'));
        const data = row.data();
        data.price_only = this.checked ? 1 : 0;
        row.data(data).draw(false);
    });
    $('#priceListTable tbody').on('change', '.active-checkbox', function(){
        const row = priceListDataTable.row($(this).closest('tr'));
        const data = row.data();
        data.active = this.checked ? 1 : 0;
        row.data(data).draw(false);
    });
    $('#priceListTable tbody').on('change', '.row-master-checkbox', function(){
        const tr = $(this).closest('tr');
        tr.toggleClass('selected-row', this.checked);
    });
}

// ----------------- Open / Close Modals -----------------
function openPriceListModal() {
    if (DOM.priceListModal) {
        DOM.priceListModal.style.display = "flex"; // Use flex for centering
        // Initialize DataTables for the price list table when modal opens
        setTimeout(() => {
            initializePriceListDataTable();
            // Force DataTables to adjust columns after modal is visible
            if (priceListDataTable) {
                priceListDataTable.columns.adjust().draw();
                console.log("DataTables columns adjusted after modal opened.");
            }
        }, 300); // Increased delay to 300ms
    }
}
function closePriceListModal(){
    if(DOM.priceListModal){
        DOM.priceListModal.style.display = "none";
        if(priceListDataTable){
            priceListDataTable.search('').columns().search('').draw();
            priceListDataTable.$('tbody tr').removeClass('selected-row');
        }
        if(DOM.priceListResetButtonContainer) DOM.priceListResetButtonContainer.style.display = 'none';
        if(DOM.priceListSearchInput) DOM.priceListSearchInput.value = '';
    }
}


// ----------------- Save Price Item via AJAX -----------------
function savePriceItem(){
    const payload = {
        service_id: $('#priceServiceId').val().trim(),
        name: $('#priceName').val().trim(),
        method: $('#priceMethod').val(),
        unit: $('#priceUnit').val().trim(),
        price: parseFloat($('#priceAmount').val()) || 0,
        price_only: $('#priceOnly').is(':checked') ? 1 : 0,
        quantity: parseInt($('#priceQuantity').val()) || 1,
        active: $('#priceActive').is(':checked') ? 1 : 0
    };
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/price-lists',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken },
        data: JSON.stringify(payload),
        contentType: 'application/json',
        dataType: 'json',
        success: function(res){
            if(res.success){
                showToast("✅ Price item saved successfully!", "success");
                closeAddPriceModal();
                if(priceListDataTable) priceListDataTable.ajax.reload(null,false);
            } else {
                showToast("❌ Failed: "+(res.message||''),"error");
            }
        },
        error: function(xhr){
            showToast("❌ Error: "+(xhr.responseJSON?.message || xhr.statusText),"error");
        }
    });
}

function openAddPriceModal() {
    document.getElementById("addPriceModal").style.display = "block";
}

function closeAddPriceModal() {
    document.getElementById("addPriceModal").style.display = "none";
}
$(document).ready(function() {
    $('#savePriceBtn').click(savePriceItem);
});

/**
 * Resets filters and reloads data for the price list table.
 */
function resetPriceListFilters() {
    if (priceListDataTable) {
        DOM.priceListSearchInput.value = ''; // Clear search input
        priceListDataTable.search('').columns().search('').draw(); // Clear all filters and redraw
        priceListDataTable.clear().rows.add(getPriceListData()).draw(); // Reload original data
        // Uncheck master checkbox on reset
        const masterCheckboxElement = $('#priceListTable thead .select-all-price-list-items')[0];
        if (masterCheckboxElement) {
            masterCheckboxElement.checked = false;
        }
        // Remove selected-row class from all rows on reset
        priceListDataTable.$('tbody tr').removeClass('selected-row');
        // Hide the reset button when reloading data
        if (DOM.priceListResetButtonContainer) {
            DOM.priceListResetButtonContainer.style.display = 'none';
        }
        alert("Price List filters reset and data refreshed.");
    } else {
        alert("Price List table not initialized.");
    }
}

/**
 * Toggles the visibility of the "Reset Search" button in the Price List modal.
 * Shows the button if search input is not empty and no rows are found.
 */
function togglePriceListResetButton() {
    if (priceListDataTable && DOM.priceListSearchInput && DOM.priceListResetButtonContainer) {
        const searchTerm = DOM.priceListSearchInput.value.trim();
        const rowCount = priceListDataTable.rows({ search: 'applied' }).count();

        if (searchTerm !== '' && rowCount === 0) {
            DOM.priceListResetButtonContainer.style.display = 'block';
        } else {
            DOM.priceListResetButtonContainer.style.display = 'none';
        }
    }
}

/**
 * Toggles the selection of all checkboxes in the Price List table based on 'priceOnly' property.
 * It also applies a visual highlight (grey) to these rows, overriding any blue selection.
 */
/**
 * Toggles the selection of all checkboxes in the Price List table based on 'priceOnly' property.
 * It also applies a visual highlight (grey) to these rows, overriding any blue selection.
 */
function toggleSelectPriceListOnly() {
    // تأكد من تهيئة priceListDataTable
    if (!priceListDataTable) {
        console.warn("Price List DataTable is not initialized.");
        return;
    }

    let allPriceOnlySelected = true; // نفترض أن الكل محدد في البداية
    let rowsToToggle = [];

    // نمر على جميع الصفوف (بما في ذلك الصفوف المخفية بالتصفية أو الترقيم)
    priceListDataTable.rows({ search: 'none', order: 'none', page: 'all' }).every(function() {
        const rowData = this.data();
        const rowNode = this.node();
        const rowCheckbox = $(rowNode).find('input[type="checkbox"]:first');

        if (rowData.priceOnly) {
            // قم بتخزين الصفوف التي تحتوي على priceOnly
            rowsToToggle.push({ rowNode: rowNode, rowCheckbox: rowCheckbox[0] });
            // تحقق مما إذا كانت جميع الصفوف priceOnly محددة حاليًا
            if (!rowCheckbox[0].checked) {
                allPriceOnlySelected = false;
            }
        }
    });

    // إذا كانت جميع الصفوف التي تحتوي على priceOnly محددة بالفعل، فقم بإلغاء تحديدها كلها.
    // وإلا، قم بتحديد كل الصفوف التي تحتوي على priceOnly.
    const newState = !allPriceOnlySelected;

    rowsToToggle.forEach(item => {
        item.rowCheckbox.checked = newState;

        // **** هنا هو الجزء الحاسم لضمان اختفاء الأزرق وظهور الرمادي ****
        if (newState) {
            // إذا كنا نقوم بتحديد "Price Only" (newState = true)
            $(item.rowNode).removeClass('selected-row');      // أولاً: أزل فئة اللون الأزرق تمامًا
            $(item.rowNode).addClass('selected-row-price-only'); // ثم: طبق فئة اللون الرمادي
        } else {
            // إذا كنا نقوم بإلغاء تحديد "Price Only" (newState = false)
            $(item.rowNode).removeClass('selected-row-price-only'); // أزل فئة اللون الرمادي
            // لا نُعيد الفئة الزرقاء هنا، لأنها تحديد عام ويتم التحكم بها بواسطة الـ checkbox الرئيسي
            // أو الـ drawCallback عند إعادة رسم الجدول.
        }
    });

    // هذا السطر يضمن أن الـ checkbox الرئيسي (الخاص بتحديد الكل) لا يتأثر.
    // DOM.selectAllPriceListItemsCheckbox.checked = false; // لا تفعل هذا! (يبقى معلقاً أو محذوفاً)

    console.log(`Rows with Price Only toggled to: ${newState}`);
}

/**
 * Sets the "Price Only" checkbox for all selected items in the Price List.
 */
function setPriceOnlyForSelected() {
    if (priceListDataTable) {
        priceListDataTable.rows().every(function() {
            const rowNode = this.node();
            const checkbox = $(rowNode).find('input[type="checkbox"]:first')[0]; // Main row checkbox
            if (checkbox && checkbox.checked) {
                const priceOnlyCheckbox = $(rowNode).find('.price-only-checkbox')[0];
                if (priceOnlyCheckbox) {
                    priceOnlyCheckbox.checked = true;
                    // Update the DataTables data model
                    const data = this.data();
                    data.priceOnly = true;
                    this.data(data).draw(false); // Update row data without redrawing the whole table
                }
            }
        });
        alert("Set 'Price Only' for selected items.");
    } else {
        alert("Price List table not initialized.");
    }
}


/**
 * Adds selected items from the Price List modal to the main Quotation Lines table.
 * @param {boolean} withGroups - True if items should be inserted with groups (dummy functionality for now).
 */
// دالة لإضافة العناصر المحددة من جدول قائمة الأسعار إلى جدول سطور عرض الأسعار
function addSelectedItemsToQuoteLines(withGroups = false) {
    if (!currentQuotationId) {
        return showToast("الرجاء اختيار عرض سعر أولاً!", "warning");
    }
    if (!priceListDataTable) {
        return showToast("جدول قائمة الأسعار غير جاهز.", "error");
    }

    const selectedItems = [];
    priceListDataTable.rows().every(function() {
        const rowNode = this.node();
        const checkbox = $(rowNode).find('input[type="checkbox"]:first')[0];
        if (checkbox && checkbox.checked) {
            const data = this.data();
            data.price = parseFloat($(rowNode).find('.price-input').val() || data.price || 0);
            data.quantity = parseInt($(rowNode).find('.quantity-input').val() || data.quantity || 1);
            data.priceOnly = $(rowNode).find('.price-only-checkbox').prop('checked');
            data.active = $(rowNode).find('.active-checkbox').prop('checked');
            selectedItems.push(data);
        }
    });

    if (selectedItems.length === 0) {
        return showToast("الرجاء تحديد عنصر واحد على الأقل من قائمة الأسعار.", "info");
    }

    const payload = selectedItems.map(item => ({
        quotation_id: currentQuotationId,
        price_list_id: item.id,
        description: item.name,
        category: item.unit,
        type: item.priceOnly ? "سعر فقط" : "عادي",
        method: item.method,
        quantity: item.quantity,
        price: item.price,
        total: item.price * item.quantity
    }));

    $.ajax({
        url: '/quotation-lines',
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        data: JSON.stringify({ lines: payload }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                showToast(response.message, "success");
                closePriceListModal();

                if (quotationLinesDataTable) {
                    // إعادة تحميل بيانات الجدول من السيرفر بعد الحفظ
                    quotationLinesDataTable.ajax.reload(null, false);
                }
            } else {
                showToast("❌ فشل الحفظ: " + (response.message || ''), "error");
            }
        },
        error: function(xhr) {
            console.error("خطأ عند حفظ العناصر:", xhr.responseText || xhr.statusText);
            showToast("❌ حدث خطأ عند حفظ العناصر.", "error");
        }
    });
}



// =====================================================================
// Document Ready and Initialization
// =====================================================================
$(document).ready(function() {

  // تهيئة مستمعات الأحداث لأزرار Header Tab
    if (DOM.closeHeaderTabBtn) {
        DOM.closeHeaderTabBtn.addEventListener('click', closeQuotationModal);
        console.log("Event listener added to closeHeaderTabBtn"); // لأغراض التشخيص
    } else {
        console.warn("DOM.closeHeaderTabBtn not found!");
    }

    if (DOM.saveHeaderTabBtn) {
        DOM.saveHeaderTabBtn.addEventListener('click', saveQuotationHeader);
        console.log("Event listener added to saveHeaderTabBtn"); // لأغراض التشخيص
    } else {
        console.warn("DOM.saveHeaderTabBtn not found!");
    }

    if (DOM.saveAndCloseHeaderTabBtn) {
        DOM.saveAndCloseHeaderTabBtn.addEventListener('click', saveAndCloseQuotationHeader);
        console.log("Event listener added to saveAndCloseHeaderTabBtn"); // لأغراض التشخيص
    } else {
        console.warn("DOM.saveAndCloseHeaderTabBtn not found!");
    }

  // ربط زر الإغلاق "Close"
    $('#closeLinesTabBtn').on('click', function() {
        closeQuotationModal();
        console.log("Close Lines tab button clicked.");
    });

    // ربط زر "Save Lines"
    $('#saveLinesTabBtn').on('click', function() {
        saveQuoteLines();
        console.log("Save Lines tab button clicked.");
    });

    // ربط زر "Save Lines & Close"
    $('#saveAndCloseLinesTabBtn').on('click', function() {
        saveAndCloseQuoteLines();
        console.log("Save Lines & Close tab button clicked.");
    });


    initializeQuotationDataTable();

    initializeEmployeeDropdown();
    initializePaymentTermsDropdown();
    initializeContactPersonDropdown();
    initializeCategoryDropdown();

    initializeDynamicDOMElements();








    // تعيين التاريخ الافتراضي
    if (DOM.quoteDate && !DOM.quoteDate.value) {
        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        DOM.quoteDate.value = `${yyyy}-${mm}-${dd}`;
    }

    // Price List DataTable will be initialized when its modal is opened

    // NEW: Add a global resize listener to adjust DataTables columns
    window.addEventListener('resize', function() {
        // Only adjust if the priceListModal is currently displayed
        if (DOM.priceListModal && DOM.priceListModal.style.display === 'flex' && priceListDataTable) {
            priceListDataTable.columns.adjust().draw();
            console.log("Price List table columns adjusted on window resize.");
        }
        // Also adjust the quotationLinesTable if its tab is active
        const linesTab = document.getElementById('linesTab');
        if (linesTab && linesTab.classList.contains('active') && quotationLinesDataTable) {
            quotationLinesDataTable.columns.adjust().draw();
            console.log("Quotation Lines table columns adjusted on window resize.");
        }
    });
});
