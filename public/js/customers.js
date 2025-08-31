let currentCustomerId = null; // متغير لتعقب ID العميل الذي يتم عرضه أو تعديله
let contactsDataTable; // متغير عام لتمثيل DataTables الخاص بجهات الاتصال
let editingCustomerRow = null; // NEW: Holds the DataTables row object being edited

// =================== دوال التنبيه والتأكيد (Utility Functions) ===================
// هذه الدوال لا تعتمد على تهيئة أي جداول أو مكونات أخرى.
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

// =================== دوال تهيئة DataTables ===================

/**
 * دالة لتهيئة DataTables لجدول جهات الاتصال داخل المودال.
 * سيتم استدعاؤها عند فتح المودال لأول مرة أو عند الحاجة.
 */
function initializeContactsTable() {
    if ($.fn.DataTable.isDataTable('#contactsTable')) {
        contactsDataTable.destroy();
        contactsDataTable = null;
    }

    try {
        contactsDataTable = $('#contactsTable').DataTable({
            responsive: true,
            orderCellsTop: true,
            fixedHeader: true,
            searching: true,
            paging: true,
            info: true,
            language: {url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/en-GB.json" },
            columns: [
                { // 0: Checkbox
                    "orderable": false,
                    "searchable": false,
                    "render": function (data, type, row) {
                        return '<input type="checkbox" class="selectContact">';
                    }
                },
                null, // 1: Contact ID (سيتم إخفاؤه لاحقًا في columnDefs)
                null, // 2: Name
                null, // 3: Email
                null, // 4: Phone
                null, // 5: Mobile
                null, // 6: Position
                null  // 7: Is Primary
            ],
            "columnDefs": [
                {
                    "targets": [1], // عمود Contact ID
                    "visible": false,
                    "searchable": false,
                    "orderable": false
                },
                {
                    "targets": [7], // عمود Is Primary (لضمان العرض الصحيح لـ Yes/No إذا كنت لا تستخدم Render)
                    "render": function (data, type, row) {
                        // البيانات هنا هي العنصر رقم 7 في المصفوفة (Yes/No)
                        return data;
                    }
                }
            ],
            "initComplete": function () {
                this.api().columns().every(function (index) {
                    // تخطي عمود مربع الاختيار (index 0) وعمود Contact ID (index 1) المخفي
                    if (index === 0 || index === 1) return;
                    const column = this;
                    const filterElement = $(column.header()).find('.column-filter');
                    if (filterElement.length) {
                        filterElement.off('keyup change clear').on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                    }
                });

                $('#contactsTable tbody').off('click', 'input.selectContact').on('click', 'input.selectContact', function () {
                    $(this).closest('tr').toggleClass('selected', this.checked);
                });
            }
        });

        $('#contactsTable thead #selectAllContacts').off('change').on('change', function () {
            toggleAllContacts(this);
        });

        console.log("contactsDataTable initialized successfully (Contact ID column is hidden).");

    } catch (e) {
        console.error("Error initializing contactsDataTable:", e);
        showAlert("فشل في تهيئة جدول جهات الاتصال. الرجاء إبلاغ الدعم الفني.", "error");
        contactsDataTable = null;
    }
}
/**
 * دالة لتهيئة DataTables لجدول العملاء.
 */
function initializeCustomersTable() {
    if ($.fn.DataTable.isDataTable('#customersTable')) {
        window.customersTableDataTable.destroy();
    }

    window['customersTableDataTable'] = $('#customersTable').DataTable({
        responsive: false, // تم تعطيل Responsive للسماح بـ scrollX
        orderCellsTop: true,
        fixedHeader: true,
        scrollX: true, // تم تفعيل السكرول الأفقي
        url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/en-GB.json",
        columns: [
            { // 0: Checkbox
                "orderable": false,
                "render": function (data, type, row) {
                    const customerId = row[1];
                    const customerName = row[2];
                    return `<input type="checkbox" class="selectCustomer" value="${customerId}" data-customer-name="${customerName}">`;
                }
            },
            null, // 1: Customer ID
            null, // 2: Customer Name
            null, // 3: Arabic Name
            null, // 4: Customer Type
            { // 5: Potential (العمود الجديد)
                "render": function (data, type, row) {
                    return data; // سيعرض "Yes" أو "No" بناءً على البيانات
                }
            },
            null, // 6: Date Registered (تم تحريكه)
            null, // 7: Phone (تم تحريكه)
            null, // 8: City (تم تحريكه)
            null, // 9: Country (تم تحريكه)
            null, // 10: Payment Terms (تم تحريكه)
            { // 11: Discount (تم تحريكه)
                type: 'num'
            },
            null, // 12: VAT Profile (تم تحريكه)
            null, // 13: Cash (تم تحريكه)
            null, // 14: TRN/TIN # (تم تحريكه)
            null, // 15: Registration # (تم تحريكه)
            { // 16: Contacts Data (Hidden Column for Array of Objects) (تم تحريكه)
                "visible": false,
                "searchable": false,
                "orderable": false
            }
        ],
        "columnDefs": [
            {
                "targets": [13], // عمود Cash (الفهرس تغير الآن إلى 13)
                "render": function (data, type, row) {
                    return data; // سيعرض "Yes" أو "No" كما هو مخزن
                }
            },

        ],
        "initComplete": function () {
            // إضافة معالج حدث لأزرار التعديل
            $('#customersTable tbody').off('click', '.edit-customer-btn').on('click', '.edit-customer-btn', function () {
                const row = window.customersTableDataTable.row($(this).parents('tr')).data();
                const customerId = row[1]; // افترض أن ID العميل في الفهرس 1
                openCustomerModal(customerId);
            });

            $('#customersTable tbody').on('click', 'input.selectCustomer', function () {
                $(this).closest('tr').toggleClass('selected', this.checked);
            });

            // تحديث معالج الفلاتر ليتناسب مع الأعمدة الجديدة
            this.api().columns().every(function (index) {
                // تخطي عمود مربع الاختيار (index 0) وعمود Contacts Data (index 16) المخفي
                if (index === 0 || index === 16) return;

                const column = this;
                const filterElement = $(column.header()).find('.column-filter');
                if (filterElement.length) {
                    filterElement.off('keyup change clear').on('keyup change clear', function () {
                        if (column.search() !== this.value) {
                            column.search(this.value).draw();
                        }
                    });
                }
            });
        }
    });

    $('#customersTable thead input.column-filter, #customersTable thead select.column-filter').on('keyup change clear', function () {
        const columnIdx = $(this).closest('th').index();
        if (columnIdx === 0 || columnIdx === 16) return; // تخطي Checkbox و Contacts Data (تحديث الفهرس هنا أيضاً)
        const searchValue = this.value;
        if (window.customersTableDataTable.column(columnIdx).search() !== searchValue) {
            window.customersTableDataTable.column(columnIdx).search(searchValue).draw();
        }
    });
}


// =================== دوال المودال (النافذة المنبثقة) ===================

/**
 * مسح حقول نموذج جهة الاتصال.
 * (تم نقلها هنا لتكون قبل openCustomerModal و resetCustomerForm)
 */
function clearContactForm() {
    document.getElementById('contactName').value = '';
    document.getElementById('contactEmail').value = '';
    document.getElementById('contactPhone').value = '';
    document.getElementById('contactMobile').value = '';
    document.getElementById('contactPosition').value = '';
    document.getElementById('isPrimaryContact').checked = false;
    document.getElementById('editingContactId').value = ''; // مسح ID التعديل
}

/**
 * عرض قائمة جهات الاتصال في الجدول باستخدام DataTables.
 * @param {Array} contacts - مصفوفة كائنات جهات الاتصال.
 * (تم نقلها هنا لتكون قبل openCustomerModal التي قد تستدعيها)
 */
function renderContactsTable(contacts) {
    if (!contactsDataTable) {
        initializeContactsTable();
    }

    contactsDataTable.clear(); // مسح البيانات الموجودة
    contacts.forEach(contact => {
        // بناء صف البيانات لـ DataTables من كائن جهة الاتصال:
        // 0: checkbox (HTML)
        // 1: Contact ID
        // 2: Name
        // 3: Email
        // 4: Phone
        // 5: Mobile
        // 6: Position
        // 7: Is Primary (نص "Yes" أو "No")
        const rowData = [
            `<input type="checkbox" class="selectContact">`, // 0
            contact.id || '',      // 1: Contact ID
            contact.name || '',    // 2: Name
            contact.email || '',   // 3: Email
            contact.phone || '',   // 4: Phone
            contact.mobile || '',  // 5: Mobile
            contact.position || '',// 6: Position
            contact.isPrimary ? 'Yes' : 'No' // 7: Is Primary
        ];
        contactsDataTable.row.add(rowData);
    });
    contactsDataTable.draw(); // إعادة رسم الجدول بعد إضافة جميع الصفوف
}


/**
 * إعادة تعيين (مسح) جميع حقول نموذج العميل.
 */
function resetCustomerForm() {
    const customerForm = document.getElementById("customerForm");
    if (customerForm) {
        // حقول تبويبة Customer Information
        document.getElementById("customerName").value = "";
        document.getElementById("customerArabicName").value = "";
        document.getElementById("customerLegalName").value = "";
        document.getElementById("customerType").value = "";
        document.getElementById("potentialCustomer").checked = false; // <--- جديد: إعادة تعيين حقل "Potential"
        document.getElementById("legacyAccNo").value = "";
        document.getElementById("customerPhone").value = "";

        const today = new Date();
        const yyyy = today.getFullYear();
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const dd = String(today.getDate()).padStart(2, '0');
        const todayFormatted = `${yyyy}-${mm}-${dd}`;
        const registrationDateInput = document.getElementById("registrationDate");
        if (registrationDateInput) {
            registrationDateInput.value = todayFormatted;
            registrationDateInput.max = todayFormatted;
        }

        // حقول Customer Location
        document.getElementById("customerCountry").value = "";
        document.getElementById("customerArabicLocation").value = "";
        document.getElementById("customerDistrict").value = "";
        document.getElementById("customerCity").value = "";
        document.getElementById("customerStreet").value = "";
        document.getElementById("customerPostCode").value = "";
        document.getElementById("customerAddressBlock").value = "";
        document.getElementById("customerPoBox").value = "";
        document.getElementById("customerBuildingNo").value = "";

        // حقول Terms & Other Controls
        document.getElementById("paymentTerms").value = "PIA - Payment in advance"; // أو القيمة الافتراضية
        document.getElementById("discount").value = "0";
        document.getElementById("isCash").checked = false;
        document.getElementById("creditLimit").value = "999999999";
        document.getElementById("vatProfile").value = ""; // أو القيمة الافتراضية
        document.getElementById("trnTin").value = "";
        document.getElementById("registrationNo").value = "";
        document.getElementById("restrictDeliveries").checked = false;
        document.getElementById("restrictOrders").checked = false;
        document.getElementById("restrictQuotations").checked = false;

        // مسح نموذج وحقول جهات الاتصال
        clearContactForm();
        // مسح جدول جهات الاتصال باستخدام DataTables API
        if (contactsDataTable) {
            contactsDataTable.clear().draw();
        }
    }
}


/**
 * فتح النافذة المنبثقة لإضافة أو تعديل عميل.
 * @param {string} [customerIdToEdit=null] - معرف العميل للتعديل.
 */

function openCustomerModal(customerIdToEdit = null) {
    const modal = document.getElementById("customerModal");
    const customerIdInput = document.getElementById("customerId");
    const registrationDateInput = document.getElementById("registrationDate");

    if (!modal) {
        console.error("Customer modal not found!");
        return;
    }

    resetCustomerForm(); // تفترض وجود هذه الدالة

    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayFormatted = `${yyyy}-${mm}-${dd}`;
    if (registrationDateInput) {
        registrationDateInput.max = todayFormatted;
    }

    if (customerIdToEdit) {
        // تحميل بيانات العميل الموجود
        window.currentCustomerId = customerIdToEdit; // استخدام window.currentCustomerId كمتغير عام

        // Store the actual DataTables row object for direct access later
        // We need to find the row data first to get the actual row object
        const customerTable = window.customersTableDataTable;
        if (customerTable) {
            editingCustomerRow = customerTable.rows().every(function() {
                if (this.data()[1] === customerIdToEdit) {
                    return this; // Return the row API object
                }
            }).data(); // This will return a DataTables API instance of the found row

            // If the above doesn't directly give the row API object, use `row(selector)`
            // A more direct way:
            editingCustomerRow = customerTable.row(function(idx, data, node) {
                return data[1] === customerIdToEdit;
            });

            if (!editingCustomerRow.data()) { // Check if a row was actually found
                showAlert("لم يتم العثور على بيانات العميل المراد تعديلها.", "error");
                closeCustomerModal(); // Close modal if no data found
                return;
            }
        } else {
             showAlert("خطأ في تهيئة جدول العملاء، لا يمكن تعديل العميل.", "error");
             return;
        }

        if (customerIdInput) {
            customerIdInput.value = customerIdToEdit;
        }

        // Here, populate fields using editingCustomerRow.data()
        const rowData = editingCustomerRow.data();
        if (rowData) {
            document.getElementById("customerName").value = rowData[2];
            document.getElementById("customerArabicName").value = rowData[3];
            document.getElementById("customerType").value = rowData[4];
            document.getElementById("potentialCustomer").checked = rowData[5] === 'Yes';
            if (registrationDateInput) registrationDateInput.value = rowData[6];
            document.getElementById("customerPhone").value = rowData[7];
            document.getElementById("customerCity").value = rowData[8];
            document.getElementById("customerCountry").value = rowData[9];
            document.getElementById("paymentTerms").value = rowData[10] || "PIA - Payment in advance";
            document.getElementById("discount").value = rowData[11] || "0";
            document.getElementById("vatProfile").value = rowData[12] || "";
            document.getElementById("isCash").checked = (rowData[13] === 'Yes');
            document.getElementById("trnTin").value = rowData[14] || "";
            document.getElementById("registrationNo").value = rowData[15] || "";

            const contactsData = rowData[16];

            setTimeout(() => {
                initializeContactsTable();
                if (contactsData && Array.isArray(contactsData)) {
                    renderContactsTable(contactsData);
                } else {
                    if (window.contactsDataTable) {
                        window.contactsDataTable.clear().draw();
                    }
                }
            }, 50);

        } else {
            showAlert("لم يتم العثور على بيانات العميل المراد تعديلها.", "error");
            editingCustomerRow = null; // Clear if not found
            return;
        }
    } else {
        // وضع الإضافة: توليد ID جديد
        window.currentCustomerId = null;
        editingCustomerRow = null; // NEW: Ensure this is null for new additions

        let lastId = localStorage.getItem("lastCustomerId");
        lastId = lastId ? parseInt(lastId) : 1000;
        const newId = "AAMC-" + (lastId + 1);
        if (customerIdInput) {
            customerIdInput.value = newId;
        }

        setTimeout(() => {
            initializeContactsTable();
            if (window.contactsDataTable) {
                window.contactsDataTable.clear().draw();
            }
        }, 50);
    }

    modal.style.display = "flex";
    switchTab("customer");
}

/**
 * إغلاق النافذة المنبثقة للعميل.
 */
function closeCustomerModal() {
    const modal = document.getElementById("customerModal");
    if (modal) {
        modal.style.display = "none";
    }
    resetCustomerForm();
    window.currentCustomerId = null; // Clear global ID on close
    editingCustomerRow = null; // NEW: Clear the row reference
}

/**
 * إغلاق النافذة المنبثقة للعميل.
 */
function closeCustomerModal() {
    const modal = document.getElementById("customerModal");
    if (modal) {
        modal.style.display = "none";
    }
    resetCustomerForm();
}

/**
 * التبديل بين التبويبات.
 * @param {string} tabId - اسم التبويبة.
 */
function switchTab(tabId) {
    document.querySelectorAll('.form-tab-content').forEach(tab => tab.style.display = 'none');
    document.querySelectorAll('.tab-buttons button').forEach(btn => btn.classList.remove('active'));
    document.getElementById(tabId + 'Tab').style.display = 'block';
    document.getElementById(tabId + '-btn').classList.add('active');

    if (tabId === 'contact' && contactsDataTable) {
        setTimeout(() => {
            contactsDataTable.columns.adjust().draw();
        }, 50);
    }
}




/**
 * تحصل على معرف العميل (Customer ID) للعميل المحدد في الجدول.
 * هذه الدالة لا تعرض أي رسائل تنبيه، فقط تعيد القيمة.
 * @returns {string|null} معرف العميل المحدد أو null إذا لم يتم تحديد أي عميل أو تم تحديد أكثر من واحد.
 */
function getSelectedCustomerId() {
    const selectedCheckboxes = document.querySelectorAll(".selectCustomer:checked");
    if (selectedCheckboxes.length === 1) {
        return selectedCheckboxes[0].value;
    }
    return null; // لا يوجد تحديد أو تحديد متعدد
}

/**
 * دالة مساعدة لزر "Edit". تتحقق من التحديد ثم تفتح المودال.
 */
function handleEditCustomer() {
    const selectedCheckboxes = document.querySelectorAll(".selectCustomer:checked");

    if (selectedCheckboxes.length === 0) {
        showAlert("الرجاء تحديد عميل واحد للتعديل.", "warning");
        return; // توقف هنا ولا تفتح المودال
    } else if (selectedCheckboxes.length > 1) {
        showAlert("الرجاء تحديد عميل واحد فقط للتعديل.", "warning");
        return; // توقف هنا ولا تفتح المودال
    }

    const customerIdToEdit = selectedCheckboxes[0].value;
    openCustomerModal(customerIdToEdit); // الآن فقط افتح المودال إذا كان التحديد صحيحاً
}

/**
 * دالة جديدة للانتقال إلى صفحة "Files Manager" لعميل محدد.
 */
function goToCustomerFiles() {
    const selectedCheckboxes = document.querySelectorAll(".selectCustomer:checked");

    if (selectedCheckboxes.length === 0) {
        showAlert("تنبيه", "الرجاء تحديد عميل واحد لإدارة الملفات.");
        return;
    } else if (selectedCheckboxes.length > 1) {
        showAlert("تنبيه", "الرجاء تحديد عميل واحد فقط لإدارة الملفات.");
        return;
    }

    const customerCheckbox = selectedCheckboxes[0];
    const customerId = customerCheckbox.value; // هذا يجلب Customer ID
    // تأكد أن checkbox لديه خاصية data-customer-name مع اسم العميل
    const customerName = customerCheckbox.dataset.customerName;

    if (customerId && customerName) {
        // حفظ ID واسم العميل في localStorage
        localStorage.setItem('selectedCustomerId', customerId);
        localStorage.setItem('selectedCustomerName', customerName);

        // الآن قم بإعادة التوجيه إلى صفحة customer-files.html بدون معاملات في الـ URL
        window.location.href = 'customer-files.html';
    } else {
        showAlert("خطأ", "لم يتم العثور على بيانات العميل المحددة للتحويل.");
        console.error("Missing customer ID or name from selected checkbox in goToCustomerFiles().");
    }
}

/**
 * حذف العملاء المحددين من جدول DataTables.
 */
function deleteSelectedCustomers() {
    // جمع جميع مربعات الاختيار المحددة
    const selectedCheckboxes = document.querySelectorAll(".selectCustomer:checked");

    // إذا لم يتم تحديد أي عميل، يمكن إظهار رسالة
    if (selectedCheckboxes.length === 0) {
        showAlert("الرجاء اختيار عميل واحد على الأقل للحذف.", "warning");
        return;
    }


    showConfirm(
        `هل أنت متأكد أنك تريد حذف ${selectedCheckboxes.length} عميل(عملاء) محدد(ين)؟ لا يمكن التراجع عن هذا الإجراء.`,
        // دالة الـ callback للتأكيد (عند النقر على Yes)
        () => {
            selectedCheckboxes.forEach((box) => {
                const row = box.closest("tr"); // الحصول على الصف الأب لمربع الاختيار
                if (row) {
                    // تم التعديل هنا: استخدام window.customersTableDataTable
                    window.customersTableDataTable.row(row).remove().draw(); // إزالة الصف وإعادة رسم الجدول
                }
            });
            showAlert("تم حذف العملاء بنجاح.", "success");
            console.log(`${selectedCheckboxes.length} customer(s) deleted.`);
            saveCustomersData(); // تفترض وجود هذه الدالة لحفظ التغييرات بعد الحذف
        },
        "تأكيد الحذف", // العنوان
        "نعم، احذف!", // نص زر التأكيد
        "إلغاء" // نص زر الإلغاء
    );
}

/**
 * تبديل حالة تحديد/إلغاء تحديد جميع مربعات الاختيار في الجدول.
 * @param {HTMLInputElement} master - مربع الاختيار الرئيسي (Select All).
 */
function toggleSelectAll(master) {
    const allCheckboxes = document.querySelectorAll(".selectCustomer");
    allCheckboxes.forEach((cb) => (cb.checked = master.checked));
}

// =================== دوال إدارة جهات الاتصال (معدل) ===================

/**
 * إضافة أو تحديث جهة اتصال في جدول DataTables.
 */
function addContactToTable() {
    const name = document.getElementById('contactName').value.trim();
    const email = document.getElementById('contactEmail').value.trim();
    const phone = document.getElementById('contactPhone').value.trim();
    const mobile = document.getElementById('contactMobile').value.trim();
    const position = document.getElementById('contactPosition').value.trim();
    const isPrimary = document.getElementById('isPrimaryContact').checked;
    const editingId = document.getElementById('editingContactId').value; // هذا الحقل سيحمل ID جهة الاتصال التي يتم تعديلها

    if (!name) {
        showAlert("اسم جهة الاتصال مطلوب.", "warning");
        return;
    }

    // التأكد من تهيئة جدول جهات الاتصال قبل محاولة التفاعل معه
    if (!contactsDataTable || !$.fn.DataTable.isDataTable('#contactsTable')) {
        initializeContactsTable();
        // تحقق مرة أخرى بعد التهيئة
        if (!contactsDataTable || !$.fn.DataTable.isDataTable('#contactsTable')) {
            showAlert("فشل في تهيئة جدول جهات الاتصال. لا يمكن إضافة/تعديل جهة الاتصال.", "error");
            console.error("Critical: contactsDataTable failed to initialize in addContactToTable.");
            return;
        }
    }

    const primaryText = isPrimary ? 'Yes' : 'No';
    let newContactId = editingId;

    if (!newContactId) {
        // توليد ID مؤقت لجهة الاتصال الجديدة إذا لم يكن موجوداً
        newContactId = `contact_temp_${Date.now()}_${Math.floor(Math.random() * 1000)}`;
    }

    // بناء صف البيانات الجديدة/المحدثة بناءً على الفهارس الصحيحة
    // 0: checkbox (HTML)
    // 1: Contact ID (المخفي)
    // 2: Name
    // 3: Email
    // 4: Phone
    // 5: Mobile
    // 6: Position
    // 7: Is Primary (نص "Yes" أو "No")
    const newRowData = [
        `<input type="checkbox" class="selectContact">`, // 0: Checkbox HTML
        newContactId,   // 1: Contact ID
        name,           // 2: Name
        email,          // 3: Email
        phone,          // 4: Phone
        mobile,         // 5: Mobile
        position,       // 6: Position
        primaryText     // 7: Is Primary
    ];

    if (editingId) {
        // وضع التحديث: البحث عن الصف باستخدام ID جهة الاتصال (الفهرس 1)
        let rowIndex = -1;
        contactsDataTable.rows().every(function (index) {
            const rowData = this.data();
            if (rowData[1] === editingId) { // البحث عن طريق Contact ID
                rowIndex = index;
                return false; // Break loop
            }
        });

        if (rowIndex !== -1) {
            contactsDataTable.row(rowIndex).data(newRowData).draw();
            showAlert("تم تحديث جهة الاتصال بنجاح!", "success");
        } else {
            showAlert("لم يتم العثور على جهة الاتصال للتعديل.", "error");
        }
    } else {
        // وضع الإضافة: إضافة صف جديد
        contactsDataTable.row.add(newRowData).draw();
        showAlert("تم إضافة جهة اتصال جديدة بنجاح!", "success");
    }
    clearContactForm(); // مسح النموذج بعد الإضافة/التحديث
}

/**
 * ملء نموذج جهة الاتصال ببيانات الصف المحدد للتعديل.
 */
function populateContactFormForEdit() {
    const selectedCheckboxes = document.querySelectorAll('#contactsTable .selectContact:checked');

    if (selectedCheckboxes.length !== 1) {
        showAlert("الرجاء تحديد جهة اتصال واحدة فقط للتعديل.", "warning");
        return;
    }

    const selectedRowElement = selectedCheckboxes[0].closest('tr');
    const rowData = contactsDataTable.row(selectedRowElement).data();

    if (rowData) {
        // الفهارس بناءً على تعريف أعمدة DataTables:
        // 0: checkbox
        // 1: Contact ID (المخفي)
        // 2: Name
        // 3: Email
        // 4: Phone
        // 5: Mobile
        // 6: Position
        // 7: Is Primary

        document.getElementById('editingContactId').value = rowData[1] || ''; // Contact ID
        document.getElementById('contactName').value = rowData[2] || '';
        document.getElementById('contactEmail').value = rowData[3] || '';
        document.getElementById('contactPhone').value = rowData[4] || '';
        document.getElementById('contactMobile').value = rowData[5] || ''; // Mobile
        document.getElementById('contactPosition').value = rowData[6] || '';
        document.getElementById('isPrimaryContact').checked = (rowData[7] === 'Yes');
    } else {
        showAlert("حدث خطأ أثناء جلب بيانات جهة الاتصال.", "error");
    }
}

/**
 * حذف جهات الاتصال المحددة من جدول DataTables.
 */
function deleteSelectedContacts() {
    const selectedRows = contactsDataTable.rows('.selected').nodes();

    if (selectedRows.length === 0) {
        showAlert("الرجاء تحديد جهة اتصال واحدة على الأقل للحذف.", "warning");
        return;
    }

    showConfirm(
        `هل أنت متأكد أنك تريد حذف ${selectedRows.length} جهة اتصال محددة؟ لا يمكن التراجع عن هذا الإجراء.`,
        () => {
            contactsDataTable.rows('.selected').remove().draw();
            showAlert("تم حذف جهات الاتصال بنجاح.", "success");
        },
        "تأكيد الحذف",
        "نعم، احذف!",
        "إلغاء"
    );
}

/**
 * تحديد/إلغاء تحديد كل جهات الاتصال في جدول DataTables.
 * @param {HTMLInputElement} masterCheckbox
 */
function toggleAllContacts(masterCheckbox) {
    contactsDataTable.rows().nodes().each(function (row) {
        const checkbox = $(row).find('.selectContact');
        if (checkbox.length) {
            checkbox.prop('checked', masterCheckbox.checked);
            $(row).toggleClass('selected', masterCheckbox.checked);
        }
    });
}


// =================== دالة الحفظ الرئيسية (Main Save Function) ===================

/**
 * حفظ بيانات العميل وجهات الاتصال.
 * @param {Event} event - كائن الحدث.
 * @param {boolean} closeAfterSave - إغلاق المودال بعد الحفظ.
 */
function saveCustomer(event, closeAfterSave = true) {
    event.preventDefault();

    const customerId = document.getElementById("customerId")?.value || "";
    const customerName = document.getElementById("customerName")?.value || "";
    const customerArabicName = document.getElementById("customerArabicName")?.value || "";
    const customerLegalName = document.getElementById("customerLegalName")?.value || "";
    const customerType = document.getElementById("customerType")?.value || "";
    const potentialCustomer = document.getElementById("potentialCustomer")?.checked || false;
    const registrationDate = document.getElementById("registrationDate")?.value || "";
    const customerPhone = document.getElementById("customerPhone")?.value || "";
    const customerCountry = document.getElementById("customerCountry")?.value || "";
    const customerCity = document.getElementById("customerCity")?.value || "";
    const legacyAccNo = document.getElementById("legacyAccNo")?.value || "";
    const customerArabicLocation = document.getElementById("customerArabicLocation")?.value || "";
    const customerDistrict = document.getElementById("customerDistrict")?.value || "";
    const customerStreet = document.getElementById("customerStreet")?.value || "";
    const customerPostCode = document.getElementById("customerPostCode")?.value || "";
    const customerAddressBlock = document.getElementById("customerAddressBlock")?.value || "";
    const customerPoBox = document.getElementById("customerPoBox")?.value || "";
    const customerBuildingNo = document.getElementById("customerBuildingNo")?.value || "";
    const paymentTerms = document.getElementById("paymentTerms")?.value || "";
    const discount = document.getElementById("discount")?.value || "0";
    const isCash = document.getElementById("isCash")?.checked || false;
    const creditLimit = document.getElementById("creditLimit")?.value || "";
    const vatProfile = document.getElementById("vatProfile")?.value || "";
    const trnTin = document.getElementById("trnTin")?.value || "";
    const registrationNo = document.getElementById("registrationNo")?.value || "";
    const restrictDeliveries = document.getElementById("restrictDeliveries")?.checked || false;
    const restrictOrders = document.getElementById("restrictOrders")?.checked || false;
    const restrictQuotations = document.getElementById("restrictQuotations")?.checked || false;

    if (!customerName || !customerType || !customerCity) { // Added customerCountry as required
        showAlert("الرجاء ملء جميع الحقول المطلوبة (اسم العميل، نوع العميل، المدينة، الدولة).", "error");
        return;
    }

    const contactsData = contactsDataTable ? contactsDataTable.rows().data().toArray().map(row => ({
        id: row[1],
        name: row[2],
        email: row[3],
        phone: row[4],
        mobile: row[5],
        position: row[6],
        isPrimary: row[7] === 'Yes'
    })) : [];

    const potentialText = potentialCustomer ? 'Yes' : 'No';
    const isCashText = isCash ? 'Yes' : 'No';

    const mainTableRowData = [
        `<input type="checkbox" class="selectCustomer" value="${customerId}" data-customer-name="${customerName}">`,
        customerId,
        customerName,
        customerArabicName,
        customerType,
        potentialText,
        registrationDate,
        customerPhone,
        customerCity,
        customerCountry,
        paymentTerms,
        parseFloat(discount),
        vatProfile,
        isCashText,
        trnTin,
        registrationNo,
        contactsData
    ];

    // Use editingCustomerRow directly for update
    if (editingCustomerRow && editingCustomerRow.data()) { // Check if it's an existing, valid row
        editingCustomerRow.data(mainTableRowData).draw();
        showAlert("تم تحديث بيانات العميل بنجاح!", "success");
    } else {
        // New addition
        window.customersTableDataTable.row.add(mainTableRowData).draw();
        localStorage.setItem("lastCustomerId", parseInt(customerId.replace("AAMC-", "")));
        showAlert("تم إضافة عميل جديد بنجاح!", "success");
    }

    if (closeAfterSave) {
        closeCustomerModal();
    }
}
// =================== دوال الطباعة والتصدير (عامة ومعدلة) ===================

/**
 * دالة عامة لتصدير أي جدول DataTables إلى Excel.
 * @param {string} tableId - معرف الجدول (مثل 'customersTable' أو 'contactsTable').
 * @param {string} fileName - اسم الملف (مثل 'Customers_Data').
 */
function exportTableToExcel(tableId, fileName) {
    const table = document.getElementById(tableId);
    if (!table) {
        showAlert(`Table with ID "${tableId}" not found for export.`, "error");
        return;
    }

    let dataTableInstance;
    if (tableId === 'customersTable' && window.customersTableDataTable) {
        dataTableInstance = window.customersTableDataTable;
    } else if (tableId === 'contactsTable' && contactsDataTable) {
        dataTableInstance = contactsDataTable;
    } else {
        showAlert(`DataTables instance for table ID "${tableId}" not found.`, "error");
        return;
    }

    const ws_data = [];

    // Add header row
    const headerRow = [];
    $(table).find('thead tr:first th').each(function (index) {
        if (index === 0 && $(this).find('input[type="checkbox"]').length > 0) {
            return; // Skip checkbox column
        }
        // تخطي عمود Contact ID في التصدير لجدول جهات الاتصال
        if (tableId === 'contactsTable' && index === 1) { // Contact ID is at index 1
            return;
        }
        // تخطي عمود Contacts Data المخفي في جدول العملاء
        if (tableId === 'customersTable' && index === 16) { // Contacts Data is at index 16 (تم تحديث الفهرس)
             return;
        }
        // استخدام childNodes[0].nodeValue لأنه قد يكون هناك <br> أو <input>
        headerRow.push($(this).contents().filter(function() {
            return this.nodeType === 3; // Filter for text nodes
        }).text().trim());
    });
    ws_data.push(headerRow);

    // Add data rows from DataTables
    dataTableInstance.rows({ search: 'applied', order: 'current' }).every(function () {
        const rowData = this.data();
        const exportRow = [];

        if (tableId === 'customersTable') {
            for (let i = 0; i < rowData.length; i++) {
                if (i === 0) continue; // Skip checkbox HTML
                if (i === 16) continue; // Skip contacts data array (hidden column), تم تحديث الفهرس
                exportRow.push(rowData[i]);
            }
        } else if (tableId === 'contactsTable') {
            // 0: checkbox (skip)
            // 1: Contact ID (skip for export)
            // 2: Name
            // 3: Email
            // 4: Phone
            // 5: Mobile
            // 6: Position
            // 7: Is Primary
            exportRow.push(rowData[2] || ''); // Name
            exportRow.push(rowData[3] || ''); // Email
            exportRow.push(rowData[4] || ''); // Phone
            exportRow.push(rowData[5] || ''); // Mobile
            exportRow.push(rowData[6] || ''); // Position
            exportRow.push(rowData[7] || ''); // Is Primary
        }
        ws_data.push(exportRow);
    });

    const ws = XLSX.utils.aoa_to_sheet(ws_data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
    XLSX.writeFile(wb, `${fileName}.xlsx`);
}

/**
 * دالة لطباعة جدول DataTables.
 * @param {string} tableId - معرف الجدول المراد طباعته.
 * @param {string} title - عنوان مستند الطباعة.
 */
function printTable(tableId, title = '') {
    const table = document.getElementById(tableId);
    if (!table) {
        showAlert("الجدول غير موجود للطباعة.", "error");
        return;
    }

    let printContents = `
        <style>
            body { font-family: Arial, sans-serif; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            h1 { text-align: center; margin-bottom: 20px; }
        </style>
        <h1>${title || 'Table Print'}</h1>
        <table>
            <thead>
                <tr>`;

    // رأس الجدول (TH)
    $(table).find('thead tr:first th').each(function (index) {
        // تخطي عمود Checkbox (index 0)
        // تخطي عمود Contacts Data (index 16) لـ customersTable
        if (index === 0) return; // For both tables, skip checkbox
        if (tableId === 'customersTable' && index === 16) return; // <--- تحديث الفهرس: تخطي عمود Contacts Data لـ customersTable

        let headerText = $(this).text().trim();
        // إذا كان هناك نص بعد <br>، خذ الجزء الأول فقط
        if (headerText.includes('\n')) {
            headerText = headerText.split('\n')[0].trim();
        }
        printContents += `<th>${headerText}</th>`;
    });
    printContents += `</tr>
            </thead>
            <tbody>`;

    // جسم الجدول (Tbody) - عرض البيانات المرئية فقط
    window[tableId === 'customersTable' ? 'customersTableDataTable' : 'contactsDataTable'].rows({ search: 'applied' }).every(function () {
        const rowData = this.data();
        printContents += `<tr>`;

        if (tableId === 'customersTable') {
            for (let i = 0; i < rowData.length; i++) {
                if (i === 0) continue; // Skip checkbox HTML
                if (i === 16) continue; // <--- تحديث الفهرس: تخطي عمود Contacts Data array (hidden column)
                printContents += `<td>${rowData[i]}</td>`;
            }
        } else if (tableId === 'contactsTable') {
            // 0: checkbox (skip)
            // 1: Contact ID (skip for print)
            // 2: Name
            // 3: Email
            // 4: Phone
            // 5: Mobile
            // 6: Position
            // 7: Is Primary
            printContents += `<td>${rowData[2] || ''}</td>`; // Name
            printContents += `<td>${rowData[3] || ''}</td>`; // Email
            printContents += `<td>${rowData[4] || ''}</td>`; // Phone
            printContents += `<td>${rowData[5] || ''}</td>`; // Mobile
            printContents += `<td>${rowData[6] || ''}</td>`; // Position
            printContents += `<td>${rowData[7] || ''}</td>`; // Is Primary
        }
        printContents += `</tr>`;
    });

    printContents += `
            </tbody>
        </table>`;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContents);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
    showAlert("تم إعداد الجدول للطباعة!", "success");
}
