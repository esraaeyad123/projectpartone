
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

async function getPriceListData() {
    try {
        const response = await fetch('/price-list');
        if (!response.ok) throw new Error('فشل جلب البيانات من السيرفر');
        const data = await response.json();
        return Array.isArray(data) ? data : [];
    } catch (err) {
        console.error('Failed to load price list:', err);
        return [];
    }
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

function updateMasterCheckboxState(api) {

    const visibleRows = api.rows({ page: 'current', search: 'applied' });
    const totalVisibleRows = visibleRows.nodes().length;
    const checkedVisibleRows = visibleRows.nodes().find('.slaveCheckbox:checked').length;
    const masterCheckbox = $('#quote-masterCheckbox');

    if (totalVisibleRows === 0) {
        masterCheckbox.prop('checked', false).prop('indeterminate', false);
    } else if (checkedVisibleRows === 0) {
        masterCheckbox.prop('checked', false).prop('indeterminate', false);
    } else if (checkedVisibleRows === totalVisibleRows) {
        masterCheckbox.prop('checked', true).prop('indeterminate', false);
    } else {

        masterCheckbox.prop('checked', false).prop('indeterminate', true);
    }
}


function getMultipleSelectedQuotationIds() {
    const selectedIds = [];
    if (!quotationDataTable) return selectedIds;

    quotationDataTable.rows(function(idx, data, node) {
        return $(node).find('input.slaveCheckbox').prop('checked');
    }).data().each(function(rowData) {
        if (rowData.quoteNo) {
            selectedIds.push(rowData.quoteNo);
        }
    });
    return selectedIds;
}


async function fetchQuotationLinesById(quoteId) {


    // لغرض التجربة، نُعيد بيانات وهمية
    if (quoteId === '123') {
        return [
            { description: "خدمة رئيسية", qty: 1, price: 5000, total: 5000 },
            { description: "رسوم إضافية", qty: 1, price: 500, total: 500 }
        ];
    }
    return []; // إرجاع مصفوفة فارغة في حال عدم وجود بيانات
}
// --- 1. Printing Function ---
function printSelectedRows() {
    // 1. Define clean, professional English column headers for the printout.
    // Total 33 headers: 5 Status columns + 28 remaining data columns.
    const englishHeaders = [
        'Status 1', 'Status 2', 'Status 3', 'Status 4', 'Status 5',
        'Category', 'Quote No.', 'Rev.', 'Quote Date', 'Project Code',
        'Legacy No', 'Legacy Date', 'Customer', 'Project Name',
        'Project Details', 'Subject', 'From', 'Inquiry', 'Contact',
        'To', 'Attn. To', 'Attn. Pos', 'Discount', 'VAT', 'Validity',
        'Currency', 'Payment Terms', 'Method', 'Remarks', 'Quote File',
        'File Status', 'Declined', 'Declined Msg'
    ];

    if (typeof quotationDataTable === 'undefined' || quotationDataTable === null) {
        console.error("Error: The quotation data table is not initialized.");
        return;
    }

    // 2. Get the nodes (HTML elements) of the selected rows only
    const selectedNodes = quotationDataTable.rows(function(idx, data, node) {
        const $node = $(node);
        if ($node.hasClass('dataTables_empty') || $node.hasClass('group')) {
             return false;
        }
        return $node.find('input.slaveCheckbox').prop('checked');
    }).nodes().toArray();

    if (selectedNodes.length === 0) {
        showCustomAlert("الرجاء اختيار صف واحد على الأقل.");
        return;
    }

    // 3. Collect HTML of the selected rows with cleanup
    const selectedRowsHtml = selectedNodes.map(node => {
        const $row = $(node).clone();

        // --- Cleanup Step: Remove unnecessary elements/classes from the row ---

        // 1. Remove ONLY the Checkbox cell (td:eq(0)).
        $row.find('td:eq(0)').remove();

        // 2. Remove the very LAST cell (td:last) which appears without a header.
        $row.find('td:last').remove();

        // 3. Remove DataTables internal classes and attributes from the row
        $row.removeClass('odd even selected');
        $row.removeAttr('role');

        // 4. Remove unnecessary classes/styles from cells (td) within the row
        $row.find('td').each(function() {
            $(this).removeClass('sorting_1 text-right dataTables_empty');
            $(this).removeAttr('tabindex');
        });

        return $row[0].outerHTML; // Return the cleaned HTML string
    }).filter(html => html.length > 0)
      .join('');

    // 4. Build the new clean HTML header row
    const cleanHeaderHtml = `
        <thead>
            <tr>
                ${englishHeaders.map(title => `<th class="px-3 py-2 text-left">${title}</th>`).join('')}
            </tr>
        </thead>
    `;

    // 5. Build the temporary printable HTML table
    const printableTableHtml = `
        <div style="direction: ltr;">
            <h1>Selected Quotations Printout</h1>
            <table id="printableQuotationTable">
                ${cleanHeaderHtml}
                <tbody>
                    ${selectedRowsHtml}
                </tbody>
            </table>
        </div>
    `;

    // 6. Use an invisible <iframe> for in-page printing
    const iframe = $('<iframe>', {
        id: 'print-iframe',
        css: { 'display': 'none', 'position': 'absolute', 'top': '-9999px' }
    }).appendTo('body')[0];

    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

    iframeDoc.write('<html><head><title>Selected Quotations Report</title>');

    // CSS for A4 compatibility and table layout
    iframeDoc.write(`
        <style>
            @media print {
                body { font-family: 'Arial', sans-serif; direction: ltr; margin: 0; padding: 20px; }
                h1 { text-align: center; margin-bottom: 20px; font-size: 16pt; color: #1a4279; text-transform: uppercase; }
                #printableQuotationTable { width: 100%; border-collapse: collapse; table-layout: fixed; page-break-inside: auto; }
                #printableQuotationTable tr { page-break-inside: avoid; page-break-after: auto; }
                #printableQuotationTable th,
                #printableQuotationTable td { border: 1px solid #c0c0c0; padding: 5px; text-align: left; word-wrap: break-word; font-size: 7pt; }
                #printableQuotationTable th { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; color-adjust: exact; font-weight: bold; color: #333; text-transform: capitalize; }
                tfoot, .dataTables_info, .dataTables_paginate, .dataTables_wrapper > div:last-child { display: none !important; }
            }
        </style>
    `);

    iframeDoc.write('</head><body>');
    iframeDoc.write(printableTableHtml);
    iframeDoc.write('</body></html>');

    iframeDoc.close();

    // M-5: Add final JavaScript cleanup inside the iframe before printing
    iframe.contentWindow.eval(`
        (function() {
            try {
                var tableContainer = document.getElementById('printableQuotationTable').parentNode;
                if (tableContainer) {
                    var lastChildren = tableContainer.querySelectorAll('.dataTables_info, .dataTables_paginate, .dataTables_length, .dataTables_filter, .dataTables_processing');
                    lastChildren.forEach(function(el) { el.remove(); });
                }
            } catch (e) {
                console.error("Print cleanup failed in iframe:", e);
            }
        })();
    `);

    iframe.contentWindow.focus();
    iframe.contentWindow.print();

    // Clean up: Remove the temporary iframe after a short delay
    setTimeout(() => {
        $(iframe).remove();
    }, 1000);
}






function addQuoteLine() {
    console.log("Add Empty Line button clicked.");
    // Example: Add a new empty row to quotationLinesDataTable
    if (quotationLinesDataTable) {
        quotationLinesDataTable.row.add({
            id: '', description: '', accounted: '', category: '', type: '', method: ''
        }).draw(false);
        showToast("New empty line added!", "info");
    }
}




function copyQuoteLine() {
    const selectedRows = quotationLinesDataTable.rows(':has(input[type="checkbox"]:checked)').data();

    if (selectedRows.length > 0) {
        // خذ أول صف محدد فقط (يمكن لاحقًا نوسع لعدة صفوف)
        const rowData = { ...selectedRows[0] }; // نسخة من بيانات الصف الأول

        // خزّنها في متغير عام
        window.copiedQuoteLineData = rowData;

        showToast("✅ تم نسخ البند! يمكنك الآن لصقه.", "info");
        console.log("📋 Copied Line Data:", window.copiedQuoteLineData);
    } else {
        showToast("⚠️ الرجاء تحديد بند واحد على الأقل للنسخ.", "warning");
    }
}


async function pasteQuoteLine() {
    const quotationId = $('#selectedQuotationId').val();
    const copiedData = window.copiedQuoteLineData;

    if (!quotationId) {
        showToast("⚠️ يجب حفظ عرض السعر أولاً قبل لصق البنود.", "warning");
        return;
    }

    if (!copiedData) {
        showToast("⚠️ لا يوجد بند منسوخ بعد. الرجاء نسخ بند أولاً.", "warning");
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // تجهيز بيانات البند في مصفوفة lines كما يتطلب الباك إند
        const newLine = {
            price_list_id: copiedData.price_list_id || null,
            description: copiedData.description || '',
            category: copiedData.category || '',
            type: copiedData.type || '',
            method: copiedData.method || '',
            quantity: copiedData.quantity || 1,
            price: copiedData.price || 0,
            total: (copiedData.price || 0) * (copiedData.quantity || 1)
        };

        const res = await fetch('/quotations/lines/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                quotation_id: quotationId,
                lines: [newLine]  // 🔹 هنا نرسل مصفوفة تحتوي على بند واحد
            }),
        });

        const data = await res.json();

        if (!res.ok || !data.success) throw new Error(data.message || 'فشل في لصق البند.');

        showToast("✅ تم لصق البند بنجاح.", "success");
        console.log("📋 Pasted new line:", data.data);

        // إعادة تحميل جدول البنود
        if (quotationLinesDataTable) quotationLinesDataTable.ajax.reload(null, false);

    } catch (error) {
        console.error("❌ خطأ أثناء لصق البند:", error);
        showToast("⚠️ حدث خطأ أثناء لصق البند: " + error.message, "error");
    }
}

function refreshQuoteLinesTable() {
    if (quotationLinesDataTable) {
        quotationLinesDataTable.ajax.reload(null, false); // 🔄 يعيد تحميل البيانات من السيرفر بدون إعادة ضبط الصفحة
        showToast("✅ Quote Lines reloaded from server.", "success");
    } else {
        alert("⚠️ Quotation Lines table not initialized.");
    }
}


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
            // Iterating through all columns that are *visible* or *defined* in DataTables,
            // then filtering them based on the text content for the headers.
            // This approach is more robust for dynamic columns or hidden columns.

            // Get the indices of the columns we want to export
            // We use columns().indexes() to get the actual DataTables column index,
            // then check if its header text is in our filteredHeaders.
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

function openPriceListModal() {
    if (!DOM.priceListModal) return;
    DOM.priceListModal.style.display = "flex";

    // انتظر قليلًا قبل تهيئة الجدول لضمان ظهور المودال
    setTimeout(async () => {
        await initializePriceListDataTable();
        if (priceListDataTable) {
            priceListDataTable.columns.adjust().draw();
            console.log("✅ DataTables columns adjusted after modal opened.");
        }
    }, 300);
}



/**
 * Closes the Price List modal.
 */
function closePriceListModal() {
    if (DOM.priceListModal) {
        DOM.priceListModal.style.display = "none";
        // Clear search input and reset filters when closing
        if (DOM.priceListSearchInput) {
            DOM.priceListSearchInput.value = '';
        }
        if (priceListDataTable) {
            priceListDataTable.search('').columns().search('').draw();
            // Uncheck master checkbox (it's now part of the DataTables header)
            // We need to find the checkbox within the DataTables header dynamically
            const masterCheckboxElement = $('#priceListTable thead .select-all-price-list-items')[0];
            if (masterCheckboxElement) {
                masterCheckboxElement.checked = false;
            }
            // Remove selected-row class from all rows when closing the modal
            priceListDataTable.$('tbody tr').removeClass('selected-row');
        }
        // Hide the reset button when closing the modal
        if (DOM.priceListResetButtonContainer) {
            DOM.priceListResetButtonContainer.style.display = 'none';
        }
    }
}

// =======================================
// Global variable
// =======================================

// =======================================
// Initialize Price List DataTable
// =======================================
async function initializePriceListDataTable() {
    if (!DOM.priceListTable) {
        console.error("DOM.priceListTable element not found. DataTables cannot be initialized.");
        return;
    }

    try {
        const data = await getPriceListData();

        if ($.fn.DataTable.isDataTable(DOM.priceListTable)) {
            priceListDataTable.clear().rows.add(data).draw();
            $('#priceListTable thead .select-all-price-list-items')[0].checked = false;
            priceListDataTable.$('tbody tr').removeClass('selected-row selected-row-price-only');
            if (DOM.priceListResetButtonContainer) DOM.priceListResetButtonContainer.style.display = 'none';
            return;
        }

        // إنشاء DataTable جديد
        priceListDataTable = $(DOM.priceListTable).DataTable({
            data: data,
            columns: [
                {
                    data: null,
                    orderable: false,
                    title: '<input type="checkbox" class="select-all-price-list-items" onclick="toggleSelectAllPriceListItems(this)" />',
                    render: rowData => `<input type="checkbox" ${rowData.priceOnly ? 'checked' : ''}>`,
                    width: "30px"
                },
                { data: 'price_list_id', title: 'ID', width: "80px" },
                { data: 'name', title: 'Name', width: "250px" },
                { data: 'method', title: 'Method', width: "100px" },
                { data: 'unit', title: 'Unit', width: "80px" },
                {
                    data: 'price',
                    title: 'Price',
                    render: data => `<input type="number" class="price-input" value="${parseFloat(data).toFixed(2)}" step="0.01" style="width:80px;">`,
                    width: "100px"
                },
                {
                    data: 'priceOnly',
                    title: 'Price Only',
                    render: data => `<input type="checkbox" class="price-only-checkbox" ${data ? 'checked' : ''}>`,
                    width: "80px"
                },
                {
                    data: 'quantity',
                    title: 'Quantity',
                    render: data => `<input type="number" class="quantity-input" value="${data}" min="0" step="1" style="width:60px;">`,
                    width: "80px"
                },
                {
                    data: 'active',
                    title: 'Active',
                    render: data => `<input type="checkbox" class="active-checkbox" ${data ? 'checked' : ''}>`,
                    width: "60px"
                }
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
                emptyTable: "No data available in table",
                zeroRecords: "No matching records found"
            },
            initComplete: function() {
                const tbody = $('#priceListTable tbody');

                // Delegated event listeners
                tbody.on('change', '.price-input', function() {
                    const row = priceListDataTable.row($(this).closest('tr'));
                    const data = row.data();
                    data.price = parseFloat($(this).val());
                    row.data(data).draw(false);
                });

                tbody.on('change', '.quantity-input', function() {
                    const row = priceListDataTable.row($(this).closest('tr'));
                    const data = row.data();
                    data.quantity = parseInt($(this).val());
                    row.data(data).draw(false);
                });

                tbody.on('change', '.price-only-checkbox', function() {
                    const rowNode = $(this).closest('tr');
                    const row = priceListDataTable.row(rowNode);
                    const data = row.data();
                    data.priceOnly = this.checked;
                    row.data(data).draw(false);

                    const mainCheckbox = rowNode.find('input[type="checkbox"]:first')[0];
                    if (mainCheckbox) {
                        mainCheckbox.checked = this.checked;
                        rowNode.toggleClass('selected-row-price-only', this.checked);
                        rowNode.toggleClass('selected-row', this.checked);
                    }
                });

                tbody.on('change', '.active-checkbox', function() {
                    const row = priceListDataTable.row($(this).closest('tr'));
                    const data = row.data();
                    data.active = this.checked;
                    row.data(data).draw(false);
                });

                tbody.on('change', 'input[type="checkbox"]:first-child', function() {
                    const rowNode = $(this).closest('tr');
                    rowNode.toggleClass('selected-row', this.checked);
                    if (this.checked) rowNode.removeClass('selected-row-price-only');
                });

                if (DOM.priceListSearchInput) {
                    DOM.priceListSearchInput.addEventListener('keyup', togglePriceListResetButton);
                }
            },
            drawCallback: function() {
                if (!priceListDataTable) return;
                priceListDataTable.columns.adjust();
                togglePriceListResetButton();

                priceListDataTable.rows().every(function() {
                    const rowData = this.data();
                    const rowNode = this.node();
                    const mainCheckbox = $(rowNode).find('input[type="checkbox"]:first')[0];

                    if (rowData.priceOnly) {
                        $(rowNode).addClass('selected-row-price-only selected-row');
                        if (mainCheckbox) mainCheckbox.checked = true;
                    } else {
                        $(rowNode).removeClass('selected-row-price-only');
                        if (mainCheckbox && !mainCheckbox.checked) $(rowNode).removeClass('selected-row');
                    }
                });
            }
        });

        console.log("✅ DataTables initialized for 'priceListTable'.");
    } catch (err) {
        console.error("❌ Failed to initialize DataTable:", err);
    }
}

// =======================================
// Add selected items to quotation lines
// =======================================
async function addSelectedItemsToQuoteLines(withGroups = false) {
    const quotationId = document.getElementById('selectedQuotationId')?.value;
    if (!quotationId) {
        alert("⚠️ يجب حفظ عرض السعر أولاً قبل إضافة البنود!");
        return;
    }

    if (!priceListDataTable) {
        alert("⚠️ جدول الأسعار غير مُهيأ.");
        return;
    }

    const selectedItems = [];
    priceListDataTable.rows().every(function() {
        const rowNode = this.node();
        const checkbox = $(rowNode).find('input[type="checkbox"]:first')[0];
        if (checkbox && checkbox.checked) {
            const data = this.data();
            selectedItems.push({
                price_list_id: data.price_list_id,
                description: data.name || '',
                category: data.unit || '',
                type: data.priceOnly ? 'سعر فقط' : 'عادي',
                method: data.method || '',
                quantity: parseInt($(rowNode).find('.quantity-input').val() || data.quantity || 1),
                price: parseFloat($(rowNode).find('.price-input').val() || data.price || 0),
            });
        }
    });

    if (selectedItems.length === 0) {
        alert("⚠️ الرجاء تحديد عنصر واحد على الأقل من قائمة الأسعار.");
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const response = await fetch('/quotation-lines/bulk-add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ quotation_id: quotationId, lines: selectedItems }),
        });

        const data = await response.json();
        if (!response.ok || !data.success) {
            throw new Error(data.message || 'فشل في إضافة البنود.');
        }

        // إعادة تحميل البيانات من السيرفر مباشرة
        if (quotationLinesDataTable) {
            quotationLinesDataTable.ajax.reload(null, false);
        }

        closePriceListModal();
        showToast(`✅ تمت إضافة ${selectedItems.length} بند(بنود) بنجاح إلى عرض السعر.`, "success");
        console.log("✅ البنود المضافة:", data.lines);
    } catch (error) {
        console.error("❌ خطأ أثناء حفظ البنود:", error);
        showToast("⚠️ حدث خطأ أثناء حفظ البنود: " + error.message, "error");
    }
}




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
            $(item.rowNode).removeClass('selected-row');
            $(item.rowNode).addClass('selected-row-price-only');
        } else {
            // إذا كنا نقوم بإلغاء تحديد "Price Only" (newState = false)
            $(item.rowNode).removeClass('selected-row-price-only');

        }
    });



    console.log(`Rows with Price Only toggled to: ${newState}`);
}

/**
 * Sets the "Price Only" checkbox for all selected items in the Price List.
 */
async function setPriceOnlyForSelected() {
    if (!priceListDataTable) {
        showToast("⚠️ جدول الأسعار غير مهيأ بعد!", "error");
        console.error("priceListDataTable is not initialized.");
        return;
    }

    // نحصل على IDs البنود المحددة
    const selectedIds = [];
    priceListDataTable.rows().every(function () {
        const rowNode = this.node();
        const checkbox = $(rowNode).find('input[type="checkbox"]:first')[0];
        if (checkbox && checkbox.checked) {
            const data = this.data();
            if (data.price_list_id) selectedIds.push(data.price_list_id);
        }
    });

    if (selectedIds.length === 0) {
        showToast("⚠️ الرجاء تحديد عنصر واحد على الأقل لتعيينه كـ 'Price Only'.", "warning");
        $('#priceListTable').addClass('shake');
        setTimeout(() => $('#priceListTable').removeClass('shake'), 500);
        return;
    }

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const response = await fetch('/price-list/set-price-only', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ ids: selectedIds }),
        });

        const data = await response.json();

        if (!response.ok || !data.success) throw new Error(data.message || 'حدث خطأ أثناء التحديث.');

        // تحديث الجدول بعد النجاح
        priceListDataTable.ajax.reload(null, false);

        showToast(`✅ ${data.message}`, "success");
    } catch (error) {
        console.error("❌ خطأ أثناء تعيين Price Only:", error);
        showToast("⚠️ حدث خطأ أثناء تعيين Price Only: " + error.message, "error");
    }
}




let currentEditingRow = null; // اجعل هذا المتغير عاماً في أعلى ملفك

/**
 * Adds selected items from the Price List modal to the main Quotation Lines table.
 * @param {boolean} withGroups - True if items should be inserted with groups (dummy functionality for now).
 */
// دالة لإضافة العناصر المحددة من جدول قائمة الأسعار إلى جدول سطور عرض الأسعار

// تعريف عالمي للدالة



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
    initializeProjectCodeDropdown();
    initializeCategoryDropdown();

    initializeDynamicDOMElements();
    initializeContactPersonDropdown();







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

// تعريف متغير عام للـ DataTable (خارج أي دالة)

window.initializeQuotationLinesDataTable = function(quotationId) {
    // قراءة الـ ID من الحقل (لو حابب تتجاهل المعطى وتمتلكه من DOM)
    quotationId = $('#selectedQuotationId').val() || quotationId;

    if (!quotationId) {
        console.error("❌ Quotation ID is undefined! احفظ Quotation أولاً.");
        return;
    }

    const tableSelector = '#quotationLinesTable';

    // إذا الجدول مهيأ مسبقًا → فقط reload بالـ ID الجديد
    if ($.fn.DataTable.isDataTable(tableSelector)) {
        quotationLinesDataTable = $(tableSelector).DataTable();
        quotationLinesDataTable.ajax.url(`/quotation-lines?quotation_id=${quotationId}`).load();
        console.log(`🔄 DataTable reloaded for Quotation ID: ${quotationId}`);
        return;
    }

    // إنشاء DataTable جديد مع وضع data-id في زر الحذف
    quotationLinesDataTable = $(tableSelector).DataTable({
        ajax: { url: `/quotation-lines?quotation_id=${quotationId}`, dataSrc: '' },
        columns: [
            { data: null, orderable: false, render: () => `<input type="checkbox">` },
            { data: 'price_list_id', title: 'Service/Test Id' },
            { data: 'description', title: 'Line Description' },
            { data: 'accounted', render: d => d ? "Yes" : "No" },
            { data: 'category' },
            { data: 'type' },
            { data: 'method' },
            {
                data: null,
                orderable: false,
                render: (data, type, row) => {
                    // row.id يجب أن يكون موجوداً في JSON القادم من السيرفر
                    return `<button class="btn btn-sm btn-danger delete-line-btn" data-id="${row.id}">
                                <i class="fas fa-trash-alt"></i>
                            </button>`;
                }
            }
        ],
        scrollX: true,
        scrollY: "400px",
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        responsive: false
    });

    console.log("✅ QuotationLines DataTable initialized successfully.");
};

// حذف عنصر واحد (عبر زر الحذف في صف)
$(document).on('click', '.delete-line-btn', async function (e) {
    e.preventDefault();

    const lineId = $(this).data('id');
    if (!lineId) {
        alert("⚠️ لم يتم العثور على معرف البند.");
        return;
    }

    if (!confirm("هل أنت متأكد أنك تريد حذف هذا البند؟")) return;

    try {
        const csrfToken = document.querySelector('meta[name=\"csrf-token\"]').content;

        const res = await fetch('/quotation-lines/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: [lineId] })
        });

        const data = await res.json();

        if (!res.ok || !data.success) throw new Error(data.message || 'فشل في حذف البند');

        showToast("✅ تم حذف البند بنجاح!", "success");

        // أعِد تحميل الجدول (من السيرفر) بدون إعادة الصفحة
        if (quotationLinesDataTable) quotationLinesDataTable.ajax.reload(null, false);

    } catch (err) {
        console.error("Error deleting line:", err);
        showToast("⚠️ حدث خطأ أثناء حذف البند: " + err.message, "error");
    }
});


async function saveQuoteLines() {
    const quotationId = document.getElementById('selectedQuotationId')?.value;
    if (!quotationId) {
        alert("⚠️ لا يمكن حفظ البنود قبل حفظ عرض السعر نفسه!");
        return;
    }

    if (!quotationLinesDataTable) {
        alert("⚠️ جدول البنود غير مُهيأ!");
        return;
    }

    // قراءة البيانات من DataTable
    const updatedLines = [];
    quotationLinesDataTable.rows().every(function () {
        const rowNode = this.node();
        const data = this.data();

        // مثال على قراءة الأعمدة من الجدول
        const line = {
            id: data.id || null, // إذا كان موجود
            quotation_id: quotationId,
            price_list_id: data.price_list_id,
            description: $(rowNode).find('.desc-input').val() || data.description,
            category: data.category,
            type: data.type,
            method: data.method,
            accounted: data.accounted ? 1 : 0,
            quantity: parseInt($(rowNode).find('.qty-input').val() || 1),
            price: parseFloat($(rowNode).find('.price-input').val() || 0)
        };
        updatedLines.push(line);
    });

    if (updatedLines.length === 0) {
        alert("⚠️ لا توجد بنود لحفظها!");
        return;
    }

    console.log("💾 Saving Quotation Lines...", updatedLines);

    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const response = await fetch('/quotation-lines/bulk-update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                quotation_id: quotationId,
                lines: updatedLines
            }),
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'فشل في حفظ البنود');
        }

        showToast("✅ تم حفظ البنود بنجاح!", "success");
        console.log("✅ Quote Lines saved successfully:", result);

        // تحديث الجدول من السيرفر بعد الحفظ
        quotationLinesDataTable.ajax.reload(null, false);

    } catch (error) {
        console.error("❌ خطأ أثناء حفظ البنود:", error);
        showToast("⚠️ حدث خطأ أثناء الحفظ: " + error.message, "error");
    }
}



async function saveAndCloseQuoteLines() {
    await saveQuoteLines(); // نحفظ البنود أولاً
    closeQuotationModal();  // نغلق نافذة عرض السعر
    showToast("✅ تم حفظ البنود وإغلاق النافذة بنجاح!", "success");
    console.log("✅ Quote Lines saved and modal closed.");
}



function submitForApproval() {
    const selectedRows = quotationDataTable.rows(function(idx, data, node) {
        return $(node).find('input.slaveCheckbox').prop('checked');
    });

    const selectedCount = selectedRows.count();
    if (selectedCount === 0) {
        return showCustomAlert("⚠️ الرجاء اختيار صف واحد على الأقل.", true);
    }

    const ids = selectedRows.data().pluck('id').toArray();

    // استخدام toast للتأكيد بدل confirm()
    showConfirmToast(
        `هل أنت متأكد من إرسال ${selectedCount} اقتباس/اقتباسات للموافقة؟`,
        async () => {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch('/quotations/send-for-approval', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ quotation_ids: ids }),
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.message || "فشل في إرسال الاقتباسات للموافقة.");
                }

                showCustomAlert(`✅ تم إرسال ${selectedCount} اقتباس/اقتباسات للموافقة بنجاح.`, false);

                // إعادة تحميل الجدول بعد الإرسال
                quotationDataTable.ajax.reload(null, false);

            } catch (error) {
                console.error("❌ خطأ أثناء الإرسال:", error);
                showCustomAlert(`⚠️ خطأ أثناء الإرسال: ${error.message}`, true);
            }
        }
    );
}



function sendQuotationToCustomer() {
    if (!quotationDataTable) {
        showCustomAlert("⚠️ جدول الاقتباسات غير مهيأ.", true);
        return;
    }

    // نجيب الصفوف المحددة
    const selectedRows = quotationDataTable.rows(function(idx, data, node) {
        return $(node).find('input.slaveCheckbox').prop('checked');
    });

    const selectedCount = selectedRows.count();
    if (selectedCount === 0) {
        return showCustomAlert("الرجاء اختيار صف واحد على الأقل.", true);
    }

    const ids = selectedRows.data().pluck('id').toArray();

    // استخدام showConfirmToast بدل confirm()
    showConfirmToast(
        `هل أنت متأكد من إرسال ${selectedCount} اقتباس/اقتباسات للعميل؟`,
        () => {
            // عند الضغط على "تأكيد"
            fetch('/send-quotations-to-customer', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ quotation_ids: ids })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showCustomAlert(`✅ تم إرسال ${selectedCount} اقتباس/اقتباسات بنجاح.`, false);
                } else {
                    showCustomAlert(`⚠️ فشل الإرسال: ${data.message || 'حدث خطأ.'}`, true);
                }
            })
            .catch(err => {
                console.error(err);
                showCustomAlert("⚠️ حدث خطأ أثناء إرسال الاقتباسات.", true);
            });
        }
    );
}


function confirmQuotation() {
    const selectedRows = quotationDataTable.rows(function(idx, data, node) {
        return $(node).find('input.slaveCheckbox').prop('checked');
    });

    const selectedCount = selectedRows.count();
    if (selectedCount === 0) {
        return showCustomAlert("⚠️ الرجاء اختيار صف واحد على الأقل.", true);
    }

    const ids = selectedRows.data().pluck('id').toArray();

    // عرض toast للتأكيد
    showConfirmToast(
        `هل أنت متأكد من تأكيد ${selectedCount} اقتباس/اقتباسات؟`,
        async () => {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch('/quotations/confirm', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ quotation_ids: ids }),
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    throw new Error(data.message || "فشل في تأكيد الاقتباسات.");
                }

                showCustomAlert(`✅ تم تأكيد ${selectedCount} اقتباس/اقتباسات بنجاح.`, false);

                // إعادة تحميل الجدول بعد التأكيد
                quotationDataTable.ajax.reload(null, false);

            } catch (error) {
                console.error("❌ خطأ أثناء التأكيد:", error);
                showCustomAlert(`⚠️ خطأ أثناء التأكيد: ${error.message}`, true);
            }
        }
    );
}
