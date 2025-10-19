
let quotationDataTable;
let currentEditingQuotationRow;

let quotationLinesDataTable;
let priceListDataTable;
let lastQuotationNumbers = {
    'proposal_geotechnical': 0,
    'proposal_material_testing': 0
};


const DOM = {
    quotationModal: document.getElementById("quotationModal"),

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


    // تشيك بوكس الرئيسي للكوتيشن تابل
    masterCheckbox: document.getElementById('quote-masterCheckbox'),


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
    saveEditsBtn : document.getElementById('saveEditedQuotationBtn'),

    // Quote Lines Tab Specific Buttons
    saveLinesTabBtn: document.getElementById('saveLinesTabBtn'),
    closeLinesTabBtn: document.getElementById('closeLinesTabBtn'),
    saveAndCloseLinesTabBtn: document.getElementById('saveAndCloseLinesTabBtn'),



    // Main Quotation Table Elements

    quotationTable: document.getElementById('quotationTable'),
    fixedPaginationContainer: document.getElementById('quotation-pagination-fixed-bottom'),

    // Quote Lines Table Elements
    quotationLinesTable: document.getElementById('quotationLinesTable'),
   selectAllLinesCheckbox: document.getElementById('selectAllLinesCheckbox'),
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

    originalQuoteId: document.getElementById('originalQuoteId'),
    quotationListView: document.getElementById('quotationListView'),
    headerQuotationView: document.getElementById('headerQuotationView'),

    // Dynamically added elements (like PDF button from initializeDynamicDOMElements)
    generatePdfButton: null,
};




function showCustomAlert(message, isError = true) {
    // Find existing alert box or create a new one
    let alertBox = $('#customPrintAlert');
    const backgroundColor = isError ? '#d9534f' : '#5cb85c'; // Red for error, Green for success

    if (alertBox.length === 0) {
        alertBox = $('<div>', {
            id: 'customPrintAlert',
            text: message,
            css: {
                position: 'fixed',
                top: '20px',
                left: '50%',
                transform: 'translateX(-50%)',
                backgroundColor: backgroundColor,
                color: 'white',
                padding: '15px 30px',
                borderRadius: '8px',
                zIndex: '10000',
                boxShadow: '0 4px 8px rgba(0,0,0,0.2)',
                fontSize: '16px',
                opacity: '0',
                transition: 'opacity 0.5s',
                direction: 'rtl' // Arabic direction
            }
        }).appendTo('body');
    } else {
        alertBox.text(message).css('backgroundColor', backgroundColor);
    }

    // Show and hide logic
    alertBox.css('opacity', '1');
    setTimeout(() => {
        alertBox.css('opacity', '0');
    }, 3000);
}


// Helper Functions


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

function openQuotationModal() {
if (DOM.quotationModal) {
 DOM.quotationModal.style.display = "block";
 openTab(null, 'headerTab'); // Open Header tab by default
 resetQuotationForm(); // Clear form fields when opening for a new entry

 const saveEditsBtn = document.getElementById('saveEditedQuotationBtn');
        const saveHeaderBtn = document.getElementById('saveHeaderTabBtn');
        const saveAndCloseBtn = document.getElementById('saveAndCloseHeaderTabBtn');

 if (DOM.saveEditedQuotationBtn) {
    // 👈🏼 إخفاء زر التعديل بشكل قسري
    DOM.saveEditedQuotationBtn.style.setProperty('display', 'none', 'important');
}

if (DOM.saveHeaderTabBtn) {
    // إظهار زر الحفظ بقوة
    DOM.saveHeaderTabBtn.style.setProperty('display', 'inline-block', 'important');
}
if (DOM.saveAndCloseHeaderTabBtn) {
    // إظهار زر الحفظ والإغلاق بقوة
    DOM.saveAndCloseHeaderTabBtn.style.setProperty('display', 'inline-block', 'important');
}
        // Clear any previous validation marks
        document.querySelectorAll('.required-field-missing').forEach(label => {
            label.classList.remove('required-field-missing');
        });
        console.log("Quotation modal opened.");
    } else {
        console.error("Quotation modal element not found.");
    }
}

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



async function saveQuotationHeader(isEdit = false, quotationId = null) {
    try {
        // 1️⃣ تجهيز البيانات من الحقول
        const payload = {
            customer_id: parseInt(DOM.quoteCustomer?.dataset.id) || parseInt(document.getElementById('quoteCustomerId')?.value) || null,
            project_id: parseInt(DOM.quoteProject?.dataset.id) || parseInt(document.getElementById('quoteProjectId')?.value) || null,
            contact_id: parseInt(DOM.quoteContactPerson?.dataset.id) || parseInt(document.getElementById('quoteContactId')?.value) || null,
             // باقي الحقول...
            quote_category: DOM.quoteCategory?.value.trim() || '',
            quote_no: DOM.quoteNo?.value.trim() || '',
            rev: DOM.quoteRev?.value.trim() || '',
            quote_date: DOM.quoteDate?.value || null,
            legacy_no: DOM.quoteLegacyNo?.value.trim() || '',
            legacy_date: DOM.quoteLegacyDate?.value || null,
            subject: DOM.quoteSubject?.value.trim() || '',
            currency: DOM.quoteCurrency?.value || 'SAR',
            discount: parseFloat(DOM.quoteDiscount?.value) || 0,
            vat: parseFloat(DOM.quoteVAT?.value) || 0,
            validity_days: parseInt(DOM.quoteValidity?.value) || 0,
            payment_terms: DOM.quotePaymentTermsInput?.value.trim() || '',
            method: DOM.quoteMethod?.value.trim() || '',
            remarks: DOM.quoteRemarks?.value.trim() || '',
            inquiry: DOM.quoteInquiry?.value.trim() || '',
            quote_file: DOM.quoteQuoteFile?.value.trim() || '',
            file_status: DOM.quoteFileStatus?.value.trim() || '',
            declined: DOM.quoteDeclined?.checked ? 1 : 0,
            declined_message: DOM.quoteDeclinedMessage?.value.trim() || '',
            project_details: DOM.quoteProjectDetails?.value.trim() || '',
            use_alt_form: DOM.quoteUseAltForm?.checked ? 1 : 0,
            overall_status: DOM.quoteOverallStatus?.value.trim() || '',

            contact_from: DOM.quoteContactFrom?.value.trim() || '',
             total_lines: parseFloat(DOM.financialTotalLines?.value) || 0,
    discount_amount: parseFloat(DOM.financialDiscountAmount?.value) || 0,
    tax_amount: parseFloat(DOM.financialTaxAmount?.value) || 0,
    grand_total: parseFloat(DOM.financialGrandTotal?.value) || 0,
    overall_status: DOM.quoteOverallStatus?.value.trim() || '',
    last_confirmation: DOM.quoteLastConfirmation?.value || null,
    last_confirmed: DOM.quoteLastConfirmed?.value || null,
        };

        // 2️⃣ إعداد CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) throw new Error("CSRF token not found");

        // 3️⃣ تحديد الرابط وطريقة الطلب
        let url, method;
        if (isEdit) {
            if (!quotationId) throw new Error("Quotation ID is required for update.");
            url = `/quotations/${quotationId}`;
            method = 'PUT';
        } else {
            url = '/quotation/save-header';
            method = 'POST';
        }

        // 4️⃣ إرسال الطلب للسيرفر
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        // 5️⃣ تحليل الاستجابة
        const text = await response.text();
        let data;
        try { data = JSON.parse(text); }
        catch { throw new Error("Invalid JSON response: " + text); }

        if (!response.ok || !data.success) {
            throw new Error(data.message || `HTTP ${response.status}`);
        }

        // 6️⃣ في حال النجاح
        const actionText = isEdit ? "updated" : "created";
        showToast(`✅ Quotation ${actionText} successfully!`, "success");

        // تحديث ID في المودال بعد الإضافة
        if (data.quotation_id && !isEdit) {
            document.getElementById('selectedQuotationId').value = data.quotation_id;
        }

        // تحديث الجدول إذا موجود
        if (typeof quotationDataTable !== "undefined" && quotationDataTable !== null) {
            quotationDataTable.ajax.reload(null, false);
        }

        return data;

    } catch (error) {
        console.error("❌ Error saving quotation header:", error);
        showToast("Error: " + error.message, "error");
        return { success: false, error: error.message };
    }
}


async function loadEmployees() {
    try {
        const res = await fetch('/quotation/employees');
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const employees = await res.json();
        renderEmployeeDropdown(employees); // عرض الموظفين في الـ dropdown
    } catch (error) {
        console.error("Error loading employees:", error);
    }
}

function renderEmployeeDropdown(data) {
    const dropdown = DOM.employeeDropdown;
    dropdown.innerHTML = ''; // تفريغ القائمة أولًا

    if (!data.length) {
        const item = document.createElement('div');
        item.textContent = "لا توجد موظفين متاحين.";
        item.classList.add('custom-dropdown-item', 'no-results');
        dropdown.appendChild(item);
        return;
    }

    data.forEach(emp => {
        const item = document.createElement('div');
        item.classList.add('custom-dropdown-item');
        item.textContent = `${emp.full_name} (${emp.title})`;

        item.addEventListener('click', () => {
            DOM.quoteContactFrom.value = emp.full_name;
            DOM.quoteContactFrom.dataset.id = emp.id;
            dropdown.style.display = 'none';
        });

        dropdown.appendChild(item);
    });

    dropdown.style.display = 'block';
}



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


// -----------------------------
// INITIALIZE PROJECT DROPDOWN
// -----------------------------
// -----------------------------
// PROJECT DROPDOWN
// -----------------------------
// ===============================
// DOM References
// ===============================


// ===============================
// Employee Dropdown
// ===============================
async function fetchEmployeesFromServer() {
    try {
        const res = await fetch('/quotation/employees');
        if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
        return await res.json(); // [{id, full_name, title}]
    } catch (err) {
        console.error("Error fetching employees:", err);
        return [];
    }
}

async function initializeEmployeeDropdown() {
    const employeeInputField = DOM.quoteContactFrom;
    const showEmployeesListButton = DOM.showEmployeesListBtnEmployee;
    const employeeDropdown = DOM.employeeDropdown;

    if (!employeeInputField || !showEmployeesListButton || !employeeDropdown) return;

    let allEmployees = await fetchEmployeesFromServer();

    function renderEmployeeDropdown(data) {
        employeeDropdown.innerHTML = '';
        if (!data.length) {
            employeeDropdown.style.display = 'none';
            return;
        }

        data.forEach(emp => {
            const item = document.createElement('div');
            item.classList.add('custom-dropdown-item');
            item.textContent = `(${emp.initials}) ${emp.full_name} ${emp.title} `;
            item.addEventListener('click', () => {
                employeeInputField.value = emp.full_name;
                employeeInputField.dataset.id = emp.id;
                employeeDropdown.style.display = 'none';
            });
            employeeDropdown.appendChild(item);
        });

        employeeDropdown.style.display = 'block';
    }

    employeeInputField.addEventListener('focus', () => renderEmployeeDropdown(allEmployees));
    showEmployeesListButton.addEventListener('click', e => {
        e.stopPropagation();
        renderEmployeeDropdown(allEmployees);
        employeeInputField.focus();
    });

    employeeInputField.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const filtered = allEmployees.filter(emp =>
            emp.full_name.toLowerCase().includes(searchTerm) ||
            (emp.title && emp.title.toLowerCase().includes(searchTerm))
        );
        renderEmployeeDropdown(filtered);
    });

    document.addEventListener('click', e => {
        if (!employeeDropdown.contains(e.target) &&
            e.target !== employeeInputField &&
            e.target !== showEmployeesListButton) {
            employeeDropdown.style.display = 'none';
        }
    });

    employeeDropdown.style.display = 'none';
}

// ===============================
// Project Dropdown
// ===============================
function initializeProjectDropdown() {
    const projectCodeInputField = DOM.quoteProjectCodeInput;
    const projectCodeDropdown = DOM.projectCodeDropdown;
    const showProjectCodeListBtn = DOM.showProjectCodeListBtn;

    const quoteProjectField = DOM.quoteProject;
    const quoteCustomerField = DOM.quoteCustomer;
    const quoteProjectDetailsField = DOM.quoteProjectDetails;

    const contactInputField = DOM.quoteContactPerson;
    const contactDropdown = DOM.contactPersonDropdown;
    const showContactListBtn = DOM.showContactPersonListBtn;

    const quoteAttnToField = DOM.quoteAttnTo;
    const quoteAttnPosField = DOM.quoteAttnPos;
    const quoteContactEmailField = DOM.quoteContactEmail;
    const quoteContactMobileField = DOM.quoteContactMobile;
    const quoteContactToField = DOM.quoteContactTo;

    if (!projectCodeInputField || !projectCodeDropdown || !showProjectCodeListBtn ||
        !quoteProjectField || !quoteCustomerField || !contactInputField || !contactDropdown || !showContactListBtn) {
        console.error("❌ Required DOM elements not found.");
        return;
    }

    // ===============================
    // Render Contact Dropdown
    // ===============================
    function renderContactDropdown(data) {
        contactDropdown.innerHTML = '';

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

        const groups = [
            { title: "جهات اتصال المشروع", contacts: data.project_contacts || [], icon: "👷" },
            { title: "جهات اتصال العميل", contacts: data.customer_contacts || [], icon: "🏢" }
        ];

        let hasContacts = false;

        groups.forEach(group => {
            if (!group.contacts.length) return;
            hasContacts = true;

            const groupHeader = document.createElement('div');
            groupHeader.classList.add('custom-dropdown-group');
            groupHeader.innerHTML = `<span class="group-icon">${group.icon}</span> ${group.title}`;
            contactDropdown.appendChild(groupHeader);

            group.contacts.forEach(contact => {
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
                    contactInputField.dataset.id = contact.id;
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
                });
                contactDropdown.appendChild(item);
            });
        });

        if (!hasContacts) {
            const noResults = document.createElement('div');
            noResults.classList.add('custom-dropdown-item', 'no-results');
            noResults.textContent = "لا توجد جهات اتصال متاحة.";
            contactDropdown.appendChild(noResults);
        }

        contactDropdown.style.display = 'block';
    }


    // ===============================
    // Load Contacts
    // ===============================
    function loadContacts(projectCode) {
        if (!projectCode) return;
        fetch(`/quotation/contacts?project=${projectCode}`)
            .then(res => res.json())
            .then(data => renderContactDropdown(data))
            .catch(err => console.error("Error fetching contacts:", err));
    }

    // ===============================
    // Render Project Dropdown
    // ===============================
    function renderProjectDropdown(data) {
        projectCodeDropdown.innerHTML = '';

        const headerRow = document.createElement('div');
        headerRow.classList.add('custom-dropdown-header-row');
        headerRow.innerHTML = `
            <span>Project Code</span>
            <span>Project Name</span>
            <span>Customer</span>
        `;
        projectCodeDropdown.appendChild(headerRow);

        if (!data.length) {
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
                <span>${project.customer_name || ''}</span>
            `;
            item.addEventListener('click', () => {
                projectCodeInputField.value = project.code;
                projectCodeDropdown.style.display = 'none';

                quoteProjectField.value = project.name;
                quoteProjectField.dataset.id = project.id;

                quoteCustomerField.value = project.customer_name || '';
                quoteCustomerField.dataset.id = project.customer_id || '';

                if (quoteProjectDetailsField) {
                    quoteProjectDetailsField.value = project.project_details || '';
                }

                loadContacts(project.code);
            });
            projectCodeDropdown.appendChild(item);
        });

        projectCodeDropdown.style.display = 'block';
    }

    // ===============================
    // Load Projects
    // ===============================
    function loadProjectsFromServer() {
        fetch('/quotation/projects')
            .then(res => res.json())
            .then(data => renderProjectDropdown(data))
            .catch(err => console.error("Error fetching projects:", err));
    }

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
}

// ===============================
// INITIALIZATION
// ===============================
document.addEventListener('DOMContentLoaded', () => {
     initializeProjectDropdown();
     initializeEmployeeDropdown();
     initializePaymentTermsDropdown();
     initializeCategoryDropdown();
     initializeQuotationDataTable();


});




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



// =====================================================================
// Functions for File Handling and PDF Generation
// =====================================================================



function generateQuotationPdf() {
    if (DOM.quoteFileStatus) {
        DOM.quoteFileStatus.value = 'PDF Generated';
        console.log("Simulating PDF generation. Status updated to 'PDF Generated'.");
        showToast("PDF Generation Initiated (simulated).", "info");
    } else {
        console.error("File status element not found.");
    }
}

async function openQuotationPDF() {
    // 1. الحصول على البيانات من الباك إند
    const quotationData = await getSingleSelectedQuotationData();
    if (!quotationData) return;

    // 2. التحقق من وجود بيانات الرأس
    if (!quotationData.header) {
        showCustomAlert("فشل في تحميل بيانات رأس عرض السعر المختار.", true);
        return;
    }

    // 3. إذا أردنا عرض الرأس فقط، نتجاهل البنود
    const headerOnlyData = {
        header: quotationData.header,
        lines: [] // مصفوفة البنود فارغة
    };

    // 4. تنسيق محتوى الكوتيشن في هيئة HTML
    const reportHtml = formatQuotation(headerOnlyData);

    // 5. تعبئة محتوى التقرير داخل المودال
    const reportContentElement = document.getElementById('report-content');
    if (!reportContentElement) {
        console.error("لم يتم العثور على العنصر #report-content");
        showCustomAlert("خطأ في الواجهة: لم يتم العثور على حاوية التقرير.", true);
        return;
    }
    reportContentElement.innerHTML = reportHtml;

    // 6. عرض المودال
    const modalContainer = document.getElementById('modalpre-container');
    if (modalContainer) modalContainer.style.display = 'flex';

    showCustomAlert(
        `تم فتح بيانات الرأس للتقرير رقم: ${headerOnlyData.header.proposal_number || 'غير محدد'} في وضع المعاينة.`,
        false
    );
}


/**
 * تبديل حالة تحديد جميع صفوف Quotation Table
 * @param {HTMLInputElement} masterCheckbox - عنصر التشيك بوكس الرئيسي
 */
function toggleSelectAllQuotationTable(masterCheckbox) {
    // 1. تحقق من أن مرجع الجدول الرئيسي موجود
    if (!quotationDataTable) return;

    const isChecked = masterCheckbox.checked;

    // 2. تحديد/إلغاء تحديد جميع التشيك بوكسات الفرعية للصفوف
    // نستخدم .rows().nodes() للوصول إلى عناصر DOM لجميع الصفوف
    $(quotationDataTable.rows().nodes())
        .find('input.slaveCheckbox') // ابحث عن التشيك بوكس الفرعي
        .prop('checked', isChecked) // طبق حالة التحديد
        .closest('tr').toggleClass('selected-row', isChecked); // طبق تظليل الصف

    // 3. النقطة الحاسمة: تحديث شريط الأدوات لتفعيل/تعطيل الأيقونات
    updateToolbarState();
}

// =====================================================================
// Placeholder/Assumed External Functions (Implement these as needed)
// =====================================================================
function initializeQuotationDataTable() {
    if (!DOM.quotationTable || $.fn.DataTable.isDataTable(DOM.quotationTable)) return;

    quotationDataTable = $(DOM.quotationTable).DataTable({
     ajax: {
    url:  '/quotation/list',
    type: 'GET',
    dataSrc: '',
    error: function(xhr, status, error) {
        console.error("❌ Ajax Error:", status, error);
        console.log("Response:", xhr.responseText);
        alert("Ajax error: " + xhr.status + " " + xhr.statusText);
    }
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
                render: data => `<input type="checkbox" class="slaveCheckbox" data-id="${data}"/>`
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
$('#quotationTable').on('change', 'input.slaveCheckbox', function() {
    const checkedBoxes = $('input.slaveCheckbox:checked');

    if (checkedBoxes.length === 1) {
        // خذ ID من data-id
        const selectedId = checkedBoxes.first().data('id');
        $('#selectedQuotationId').val(selectedId);
        console.log("✅ تم اختيار عرض السعر ID:", selectedId);
    } else {
        // لو ما فيه صف أو أكثر من صف محدد، نفرغ القيمة
        $('#selectedQuotationId').val('');
        console.log("❌ لم يتم تحديد صف واحد فقط.");
    }
});

$('#quotationTable tbody').on('click', '.btn-delete', function() {
    const rowData = quotationDataTable.row($(this).closest('tr')).data();
    deleteSelectedQuotation(rowData.id);
});


function toggleSelectAllQuotations(masterCheckbox) {
    const checkboxes = document.querySelectorAll('.slaveCheckbox');
    checkboxes.forEach(cb => {
        cb.checked = masterCheckbox.checked;
        $(cb).closest('tr').toggleClass('selected-row', masterCheckbox.checked);
    });
}

function updateSelectAllCheckbox() {
    const checkboxes = document.querySelectorAll('.slaveCheckbox');
    const master = document.querySelector('#quotationTable thead input[type="checkbox"]');
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    master.checked = allChecked;
}




// إضافة جديدة
function handleCreateHeader(closeAfterSave = false) {
    saveQuotationHeader(false, null).then(data => {
        if (data.success && closeAfterSave) closeQuotationModal();
    });
}

// تعديل موجود
function handleUpdateHeader() {
    const selectedQuotationId = document.getElementById('selectedQuotationId')?.value;
    saveQuotationHeader(true, selectedQuotationId);
}


// JS: منع الفورم من إرسال GET
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("quotationForm");
    form.addEventListener("submit", function(event) {
        event.preventDefault(); // يمنع إرسال GET
    });
});

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

function generateQuotationPdf() {
    if (DOM.quoteFileStatus) {
        DOM.quoteFileStatus.value = 'PDF Generated';
        console.log("Simulating PDF generation. Status updated to 'PDF Generated'.");
        showToast("PDF Generation Initiated (simulated).", "info");
    } else {
        console.error("File status element not found.");
    }
}




function deleteSelectedQuotation() {
    if (!quotationDataTable) return showCustomAlert("خطأ: لم يتم تهيئة جدول عروض الأسعار.");

    const selectedRows = quotationDataTable.rows(function(idx, data, node) {
        return $(node).find('input.slaveCheckbox').prop('checked');
    });
    const selectedCount = selectedRows.count();
    if (selectedCount === 0) return showCustomAlert("الرجاء اختيار صف واحد على الأقل.");

    const ids = selectedRows.data().pluck('id').toArray();

    showConfirmToast(`هل أنت متأكد من حذف ${selectedCount} عرض سعر؟`, () => {
        fetch('/quotations/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids })
        })
        .then(async res => {
            const text = await res.text();
            let data;
            try { data = JSON.parse(text); } catch { data = { message: text }; }
            if (!res.ok) throw new Error(data.message || 'حدث خطأ أثناء الحذف');

            selectedRows.remove().draw(false);
            showCustomAlert(data.message || `تم حذف ${selectedCount} عرض سعر بنجاح.`, false);
        })
        .catch(err => {
            console.error("❌ Delete error:", err);
            showCustomAlert("فشل حذف البيانات: " + err.message);
        });
    });
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

async function getSingleSelectedQuotationData() {
    const id = document.getElementById('selectedQuotationId')?.value;
    if (!id) return null;

    try {
        const res = await fetch(`/quotations/${id}`);
        if (!res.ok) throw new Error("Failed to fetch quotation data");
        return await res.json();
    } catch (err) {
        console.error(err);
        return null;
    }
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



function saveEditedQuotation() {
    // 1. الحصول على الـ quotationId من hidden أو checkbox
    let quotationId = $('#selectedQuotationId').val();
    if (!quotationId) {
        const checked = $('input.slaveCheckbox:checked');
        if (checked.length === 1) {
            quotationId = checked.data('id') || checked.attr('data-id');
        }
    }

    if (!quotationId) {
        showCustomAlert("خطأ: لم يتم تحديد صف عرض السعر.", true);
        return;
    }

    // 2. تجميع البيانات
    const updatedData = {
        customer_id: $('#quoteCustomerId').val(),
        project_id: $('#quoteProjectId').val(),
        contact_id: $('#quoteContactId').val(),   // ID فقط
        quote_category: $('#quoteCategory').val(),
        quote_no: $('#quoteNo').val(),
        rev: $('#quoteRev').val(),
        quote_date: $('#quoteDate').val(),
        legacy_no: $('#quoteLegacyNo').val(),
        legacy_date: $('#quoteLegacyDate').val(),
        subject: $('#quoteSubject').val(),
        currency: $('#quoteCurrency').val(),
        discount: parseFloat($('#quoteDiscount').val()) || 0,
        vat: parseFloat($('#quoteVAT').val()) || 0,
        validity_days: $('#quoteValidity').val(),
        payment_terms: $('#quotePaymentTermsInput').val(),
        method: $('#quoteMethod').val(),
        remarks: $('#quoteRemarks').val(),
        inquiry: $('#quoteInquiry').val(),
        contact_from: $('#quoteContactFrom').val(),
        project_details: $('#quoteProjectDetails').val(),
        use_alt_form: $('#quoteUseAltForm').prop('checked') ? 1 : 0,
        declined: $('#quoteDeclined').prop('checked') ? 1 : 0,
        declined_message: $('#quoteDeclinedMessage').val(),
        quote_file: $('#quoteQuoteFile').val(),
        file_status: $('#quoteFileStatus').val()
    };

    // 3. إرسال AJAX
    $.ajax({
        url: `/quotations/${quotationId}`,
        type: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // ✅ بديل عن data._token
        },
        data: updatedData,
        success: function (response) {
            if (response.success) {
                // 4. تحديث صف الـ DataTable محلياً
                const rowNode = quotationDataTable.rows().nodes().to$().find(`input.slaveCheckbox[data-id='${quotationId}']`).closest('tr');
                const row = quotationDataTable.row(rowNode);
                const updatedRowData = { ...row.data(), ...updatedData };
                row.data(updatedRowData).draw(false);

                // 5. إخفاء المودال وعرض رسالة نجاح
                $('#quotationModal').hide();
                showCustomAlert("✅ تم حفظ وتحديث عرض السعر بنجاح.", false);
            } else {
                showCustomAlert("❌ حدث خطأ أثناء حفظ التعديلات.", true);
            }
        },
        error: function (xhr) {
            console.error("Error updating quotation:", xhr.responseText);
            showCustomAlert("❌ لم يتم حفظ التعديلات، تحقق من الحقول المطلوبة.", true);
        }
    });
}



async function editQuotationModal() {
    // ✅ 1. تأكد من وجود الجدول
    if (typeof quotationDataTable === 'undefined' || quotationDataTable === null) {
        showCustomAlert("⚠️ لم يتم تهيئة جدول عروض الأسعار.", true);
        return;
    }

    // ✅ 2. تحديد الصف المختار بالتشيك بوكس
    const selectedRows = quotationDataTable.rows(function(idx, data, node) {
        return $(node).find('input.slaveCheckbox').prop('checked');
    });

    const selectedCount = selectedRows.count();

    if (selectedCount !== 1) {
        showCustomAlert("الرجاء اختيار صف واحد فقط للتعديل.", true);
        return;
    }

 let quotationId = $('#selectedQuotationId').val();

    // 2) إذا ما فيه hidden، جرّب من التشيك بوكس المحدد
    if (!quotationId) {
        const checked = $('input.slaveCheckbox:checked');
        if (checked.length === 1) {
            quotationId = checked.data('id') || checked.attr('data-id');
        }
    }
    if (!quotationId) {
        showCustomAlert("⚠️ لم يتم العثور على معرف عرض السعر المحدد.", true);
        return;
    }

    console.log("🔍 جاري جلب بيانات عرض السعر من السيرفر:", quotationId);

    try {
        // ✅ 4. جلب البيانات من السيرفر
        const response = await fetch(`/quotations/${quotationId}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) throw new Error("حدث خطأ أثناء جلب البيانات من السيرفر");

        const data = await response.json();
        console.log("✅ تم جلب بيانات عرض السعر:", data);

        // ✅ 5. تعبئة النموذج بالقيم الصحيحة حسب أسماء الحقول في migration
        $('#quotationForm')[0].reset();
        $('#modalTitle').text(`تعديل عرض السعر #${data.quote_no || 'N/A'}`);

        $('#selectedQuotationId').val(data.id || '');
        $('#quoteCategory').val(data.quote_category || '');
        $('#quoteNo').val(data.quote_no || '');
        $('#quoteRev').val(data.rev || '');
        $('#quoteDate').val(data.quote_date || '');
        $('#quoteLegacyNo').val(data.legacy_no || '');
        $('#quoteLegacyDate').val(data.legacy_date || '');
        $('#quoteSubject').val(data.subject || '');
        $('#quoteCustomer').val(data.customer_name || ''); // ← يفضل أن تُرجع من الباك إند via join
        $('#quoteProjectCodeInput').val(data.project_code || ''); // ← يفضل أن تُرجع من الباك إند via join
        $('#quoteProject').val(data.project_name || '');
        $('#quoteProjectDetails').val(data.project_details || '');
        $('#quoteContactFrom').val(data.contact_from || '');
        $('#quoteInquiry').val(data.inquiry || '');
        $('#quoteContactPerson').val(data.contact_name || ''); // ← من join مع contacts
        $('#quoteContactTo').val(data.contact_email || '');
        $('#quoteAttnTo').val(data.attn_to || '');
        $('#quoteAttnPos').val(data.attn_pos || '');
        $('#quoteContactEmail').val(data.contact_email || '');
        $('#quoteContactMobile').val(data.contact_mobile || '');
        $('#quoteDiscount').val(data.discount || 0);
        $('#quoteVAT').val(data.vat || 0);
        $('#quoteValidity').val(data.validity_days || '');
        $('#quoteCurrency').val(data.currency || '');
        $('#quotePaymentTermsInput').val(data.payment_terms || '');
        $('#quoteMethod').val(data.method || '');
        $('#quoteRemarks').val(data.remarks || '');
        $('#quoteQuoteFile').val(data.quote_file || '');
        $('#quoteFileStatus').val(data.file_status || 'PDF Not Created');
        $('#quoteDeclined').prop('checked', !!data.declined);
        $('#quoteDeclinedMessage').val(data.declined_message || '');
        $('#quoteUseAltForm').prop('checked', !!data.use_alt_form);
           $('#quoteCustomerId').val(data.customer_id || '');
           $('#quoteProjectId').val(data.project_id || '');
        $('#quoteContactId').val(data.contact_id || '');
        $('#quoteAttnTo').val(data.attn_to || '');
$('#quoteAttnPos').val(data.attn_pos || '');

$('#financialTotalLines').val(data.total_lines || 0);
$('#financialDiscountAmount').val(data.discount_amount || 0);
$('#financialTaxAmount').val(data.tax_amount || 0);
$('#financialGrandTotal').val(data.grand_total || 0);
$('#quoteOverallStatus').val(data.overall_status || '');
$('#quoteLastConfirmation').val(data.last_confirmation || '');
$('#quoteLastConfirmed').val(data.last_confirmed || '');

        // ✅ 6. تبديل الأزرار
        if (DOM.saveEditedQuotationBtn)
            DOM.saveEditedQuotationBtn.style.setProperty('display', 'inline-block', 'important');
        if (DOM.saveHeaderTabBtn)
            DOM.saveHeaderTabBtn.style.setProperty('display', 'none', 'important');
        if (DOM.saveAndCloseHeaderTabBtn)
            DOM.saveAndCloseHeaderTabBtn.style.setProperty('display', 'none', 'important');

        // ✅ 7. عرض المودال
        $('#quotationModal').css('display', 'flex');
        openTab(null, 'headerTab');

    } catch (error) {
        console.error("❌ خطأ أثناء تحميل البيانات:", error);
        showCustomAlert("حدث خطأ أثناء تحميل بيانات عرض السعر من السيرفر.", true);
    }
}


async function reviseQuotation() {
    const selectedData = await getSingleSelectedQuotationData();
    if (!selectedData) return;

    const quotationId = selectedData.id;

    try {
        const response = await fetch(`/quotations/${quotationId}/revise`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        });

        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        const data = await response.json();

        // تحديث الجدول
        const rowNode = quotationDataTable.rows().nodes().to$().find(`input.slaveCheckbox[data-id='${quotationId}']`).closest('tr');
        const row = quotationDataTable.row(rowNode);
        row.data({ ...row.data(), rev: data.new_rev }).draw(false);

        showCustomAlert(`✅ تم تحديث مراجعة عرض السعر إلى ${data.new_rev}`, false);
    } catch (err) {
        console.error(err);
        showCustomAlert("❌ فشل في تحديث مراجعة عرض السعر: " + err.message, true);
    }
}



// ⚙️ دالة توليد رقم عرض السعر بناءً على الفئة المختارة
function generateQuotationNumber(selectedCategoryValue) {
    const quoteNoInput = document.getElementById('quoteNo');

    // تحقق أن الحقل موجود فعلاً
    if (!quoteNoInput) {
        console.error("❌ لم يتم العثور على حقل Quote No. في الصفحة (id='quoteNo').");
        return;
    }

    // منطق التوليد البسيط — يمكنك تعديله حسب النظام الفعلي
    let prefix = '';

    switch (selectedCategoryValue) {
        case 'proposal_geotechnical':
            prefix = 'AAM-GT';
            break;
        case 'proposal_material_testing':
            prefix = 'AAM-MT';
            break;
        default:
            prefix = 'AAM-XX'; // قيمة افتراضية
    }

    // مثال بسيط لتوليد رقم متسلسل مؤقت (يمكن لاحقًا جلب الرقم الحقيقي من السيرفر)
    const randomSuffix = Math.floor(1000 + Math.random() * 9000); // 4 أرقام عشوائية
    const generatedQuoteNo = `${prefix}-${randomSuffix}`;

    // تعبئة الحقل بالقيمة الجديدة
    quoteNoInput.value = generatedQuoteNo;

    console.log(`📄 تم توليد رقم عرض السعر: ${generatedQuoteNo}`);
}
