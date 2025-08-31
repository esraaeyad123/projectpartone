let currentProjectId = null;
let projectsTableDataTable;
let contactsDataTable;
let editingProjectRow = null;

// =================== دوال التنبيه والتأكيد (Utility Functions) ===================
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
                {
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
                    "targets": [1],
                    "visible": false,
                    "searchable": false,
                    "orderable": false
                },
                {
                    "targets": [7],
                    "render": function (data, type, row) {
                        return data;
                    }
                }
            ],
            "initComplete": function () {
                this.api().columns().every(function (index) {
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
 * دالة لتهيئة DataTables لجدول المشاريع.
 * تم تعديل الأعمدة لتتوافق مع projects.html
 */
function initializeProjectsTable() {
    if ($.fn.DataTable.isDataTable('#projectsTable')) {
        window.projectsTableDataTable.destroy();
    }

    // تم تعديل تعريف الأعمدة ليتناسب مع الحقول الجديدة التي يوفرها المستخدم
    window['projectsTableDataTable'] = $('#projectsTable').DataTable({
        responsive: false,
        orderCellsTop: true,
        fixedHeader: true,
        scrollX: true,
        language: { url: "/js/en-GB.json" },
        columns: [
            { // 0: Checkbox
                "orderable": false,
                "render": function (data, type, row) {
                    // row[1] هو Project Reference
                    // row[3] هو Project Name
                    return `<input type="checkbox" class="selectProject" value="${row[1]}" data-project-name="${row[3]}">`;
                }
            },
            null, // 1: Project Reference
            null, // 2: Project Number
            null, // 3: Project Name
            null, // 4: Customer
            null, // 5: Opportunity
            null, // 6: Date Registered
            null, // 7: Region
            null, // 8: Department
            null, // 9: Date Started
            null, // 10: Date Completed
            { // 11: Contacts Data (Hidden Column for Array of Objects)
                "visible": false,
                "searchable": false,
                "orderable": false
            }
        ],
        "columnDefs": [
            {
                "targets": [5], // عمود Opportunity
                "render": function (data, type, row) {
                    return data ? 'Yes' : 'No';
                }
            },
            {
                "targets": [11], // عمود Contacts Data
                "visible": false,
                "searchable": false,
                "orderable": false
            },
        ],
        "initComplete": function () {
            $('#projectsTable tbody').off('click', '.edit-project-btn').on('click', '.edit-project-btn', function () {
                const row = window.projectsTableDataTable.row($(this).parents('tr')).data();
                const projectId = row[1];
                openProjectModal(projectId);
            });

            $('#projectsTable tbody').on('click', 'input.selectProject', function () {
                $(this).closest('tr').toggleClass('selected', this.checked);
            });

            this.api().columns().every(function (index) {
                // تجاوز عمود مربع الاختيار والأعمدة المخفية
                if (index === 0 || index === 11) return;

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

    $('#projectsTable thead input.column-filter, #projectsTable thead select.column-filter').on('keyup change clear', function () {
        const columnIdx = $(this).closest('th').index();
        if (columnIdx === 0 || columnIdx === 11) return;
        const searchValue = this.value;
        if (window.projectsTableDataTable.column(columnIdx).search() !== searchValue) {
            window.projectsTableDataTable.column(columnIdx).search(searchValue).draw();
        }
    });
}


// =================== دوال المودال (النافذة المنبثقة) ===================

/**
 * مسح حقول نموذج جهة الاتصال.
 */
function clearContactForm() {
    document.getElementById('contactName').value = '';
    document.getElementById('contactEmail').value = '';
    document.getElementById('contactPhone').value = '';
    document.getElementById('contactMobile').value = '';
    document.getElementById('contactPosition').value = '';
    document.getElementById('isPrimaryContact').checked = false;
    document.getElementById('editingContactId').value = '';
}

/**
 * عرض قائمة جهات الاتصال في الجدول باستخدام DataTables.
 * @param {Array} contacts - مصفوفة كائنات جهات الاتصال.
 */
function renderContactsTable(contacts) {
    if (!contactsDataTable) {
        initializeContactsTable();
    }

    contactsDataTable.clear();
    contacts.forEach(contact => {
        const rowData = [
            `<input type="checkbox" class="selectContact">`,
            contact.id || '',
            contact.name || '',
            contact.email || '',
            contact.phone || '',
            contact.mobile || '',
            contact.position || '',
            contact.isPrimary ? 'Yes' : 'No'
        ];
        contactsDataTable.row.add(rowData);
    });
    contactsDataTable.draw();
}


/**
 * إعادة تعيين (مسح) جميع حقول نموذج المشروع.
 * تم تعديل الحقول لتتناسب مع طلب المستخدم.
 */
function resetProjectForm() {
    const projectForm = document.getElementById("projectForm");
    if (projectForm) {
        // حقول تبويبة Project Information
        document.getElementById("projectReference").value = "";
        document.getElementById("projectNumber").value = "";
        document.getElementById("projectName").value = "";
        document.getElementById("projectArabicName").value = "";
        document.getElementById("opportunity").checked = false;
        document.getElementById("registrationDate").value = "";
        document.getElementById("projectDescription").value = "";

        // حقول Project Location
        document.getElementById("locationLatitude").value = "";
        document.getElementById("longitude").value = "";
        document.getElementById("projectArabicLocation").value = "";
        document.getElementById("region").value = "Others";
        document.getElementById("department").value = "Materials Testing";
        document.getElementById("driveDistance").value = "";
        document.getElementById("driveDuration").value = "";

        // حقول Parties Section
        document.getElementById("customer").value = "";
        document.getElementById("cashCustomer").value = "";
        document.getElementById("customerTRN").value = "";
        document.getElementById("customerAddress").value = "";
        document.getElementById("owner").value = "";
        document.getElementById("consultant").value = "";
        document.getElementById("contractor").value = "";
        document.getElementById("pClient").value = "";
        document.getElementById("pmCommittee").value = "";
        
        // حقول Discount & Project Timelines
        document.getElementById("discount").value = "0";
        document.getElementById("dateStarted").value = "";
        document.getElementById("dateCompleted").value = "";
        document.getElementById("intProject").value = "";
        document.getElementById("intPManager").value = "";
        document.getElementById("intAnAccount").value = "";


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

        // مسح نموذج وحقول جهات الاتصال
        clearContactForm();
        if (contactsDataTable) {
            contactsDataTable.clear().draw();
        }
    }
}


/**
 * فتح النافذة المنبثقة لإضافة أو تعديل مشروع.
 * @param {string} [projectIdToEdit=null] - معرف المشروع للتعديل.
 * تم تحديث الحقول لتتناسب مع طلب المستخدم.
 */

function openProjectModal(projectIdToEdit = null) {
    const modal = document.getElementById("projectModal");
    const projectReferenceInput = document.getElementById("projectReference");
    const registrationDateInput = document.getElementById("registrationDate");

    if (!modal) {
        console.error("Project modal not found!");
        return;
    }

    resetProjectForm();

    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayFormatted = `${yyyy}-${mm}-${dd}`;
    if (registrationDateInput) {
        registrationDateInput.max = todayFormatted;
    }

    if (projectIdToEdit) {
        window.currentProjectId = projectIdToEdit;

        const projectTable = window.projectsTableDataTable;
        if (projectTable) {
            editingProjectRow = projectTable.row(function(idx, data, node) {
                // data[1] هو Project Reference في جدول DataTables الجديد
                return data[1] === projectIdToEdit;
            });

            if (!editingProjectRow.data()) {
                showAlert("لم يتم العثور على بيانات المشروع المراد تعديلها.", "error");
                closeProjectModal();
                return;
            }
        } else {
            showAlert("خطأ في تهيئة جدول المشاريع، لا يمكن تعديل المشروع.", "error");
            return;
        }

        if (projectReferenceInput) {
            projectReferenceInput.value = projectIdToEdit;
        }

        const rowData = editingProjectRow.data();
        if (rowData) {
            // تم تحديث فهارس الأعمدة لتتناسب مع التغييرات الجديدة
            // rowData[0] هو Checkbox
            // rowData[1] هو Project Reference (الذي تم استخدامه كمعرف)
            document.getElementById("projectNumber").value = rowData[2] || '';
            document.getElementById("projectName").value = rowData[3] || '';
            document.getElementById("customer").value = rowData[4] || '';
            document.getElementById("opportunity").checked = rowData[5] === 'Yes';
            document.getElementById("registrationDate").value = rowData[6] || '';
            document.getElementById("region").value = rowData[7] || '';
            document.getElementById("department").value = rowData[8] || '';
            document.getElementById("dateStarted").value = rowData[9] || '';
            document.getElementById("dateCompleted").value = rowData[10] || '';
            
            // Assuming Contacts Data is at index 11
            const contactsData = rowData[11] || []; 

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
            showAlert("لم يتم العثور على بيانات المشروع المراد تعديلها.", "error");
            editingProjectRow = null;
            return;
        }
    } else {
        window.currentProjectId = null;
        editingProjectRow = null;

        let lastId = localStorage.getItem("lastProjectId");
        lastId = lastId ? parseInt(lastId) : 1000;
        const newId = "AAMC-" + (lastId + 1);
        if (projectReferenceInput) {
            projectReferenceInput.value = newId;
        }

        setTimeout(() => {
            initializeContactsTable();
            if (window.contactsDataTable) {
                window.contactsDataTable.clear().draw();
            }
        }, 50);
    }

    modal.style.display = "flex";
    switchTab("project");
}

/**
 * إغلاق النافذة المنبثقة للمشروع.
 */
function closeProjectModal() {
    const modal = document.getElementById("projectModal");
    if (modal) {
        modal.style.display = "none";
    }
    resetProjectForm();
    window.currentProjectId = null;
    editingProjectRow = null;
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
 * تحصل على معرف المشروع (Project ID) للمشروع المحدد في الجدول.
 * هذه الدالة لا تعرض أي رسائل تنبيه، فقط تعيد القيمة.
 * @returns {string|null} معرف المشروع المحدد أو null إذا لم يتم تحديد أي مشروع أو تم تحديد أكثر من واحد.
 */
function getSelectedProjectId() {
    const selectedCheckboxes = document.querySelectorAll(".selectProject:checked");
    if (selectedCheckboxes.length === 1) {
        return selectedCheckboxes[0].value;
    }
    return null;
}

/**
 * دالة مساعدة لزر "Edit". تتحقق من التحديد ثم تفتح المودال.
 */
function handleEditProject() {
    const selectedCheckboxes = document.querySelectorAll(".selectProject:checked");

    if (selectedCheckboxes.length === 0) {
        showAlert("الرجاء تحديد مشروع واحد للتعديل.", "warning");
        return;
    } else if (selectedCheckboxes.length > 1) {
        showAlert("الرجاء تحديد مشروع واحد فقط للتعديل.", "warning");
        return;
    }

    const projectIdToEdit = selectedCheckboxes[0].value;
    openProjectModal(projectIdToEdit);
}

/**
 * دالة جديدة للانتقال إلى صفحة "Files Manager" لمشروع محدد.
 */
function goToProjectFiles() {
    const selectedCheckboxes = document.querySelectorAll(".selectProject:checked");

    if (selectedCheckboxes.length === 0) {
        showAlert("تنبيه", "الرجاء تحديد مشروع واحد لإدارة الملفات.");
        return;
    } else if (selectedCheckboxes.length > 1) {
        showAlert("تنبيه", "الرجاء تحديد مشروع واحد فقط لإدارة الملفات.");
        return;
    }

    const projectCheckbox = selectedCheckboxes[0];
    const projectId = projectCheckbox.value;
    const projectName = projectCheckbox.dataset.projectName;

    if (projectId && projectName) {
        localStorage.setItem('selectedProjectId', projectId);
        localStorage.setItem('selectedProjectName', projectName);

        window.location.href = 'project-files.html';
    } else {
        showAlert("خطأ", "لم يتم العثور على بيانات المشروع المحددة للتحويل.");
        console.error("Missing project ID or name from selected checkbox in goToProjectFiles().");
    }
}

/**
 * حذف المشاريع المحددة من جدول DataTables.
 */
function deleteSelectedProjects() {
    const selectedCheckboxes = document.querySelectorAll(".selectProject:checked");

    if (selectedCheckboxes.length === 0) {
        showAlert("الرجاء اختيار مشروع واحد على الأقل للحذف.", "warning");
        return;
    }

    showConfirm(
        `هل أنت متأكد أنك تريد حذف ${selectedCheckboxes.length} مشروع(مشاريع) محدد(ين)؟ لا يمكن التراجع عن هذا الإجراء.`,
        () => {
            selectedCheckboxes.forEach((box) => {
                const row = box.closest("tr");
                if (row) {
                    window.projectsTableDataTable.row(row).remove().draw();
                }
            });
            showAlert("تم حذف المشاريع بنجاح.", "success");
            console.log(`${selectedCheckboxes.length} project(s) deleted.`);
            // A function to save data would be called here.
        },
        "تأكيد الحذف",
        "نعم، احذف!",
        "إلغاء"
    );
}

/**
 * تبديل حالة تحديد/إلغاء تحديد جميع مربعات الاختيار في الجدول.
 * @param {HTMLInputElement} master - مربع الاختيار الرئيسي (Select All).
 */
function toggleSelectAll(master) {
    const allCheckboxes = document.querySelectorAll(".selectProject");
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
    const editingId = document.getElementById('editingContactId').value;

    if (!name) {
        showAlert("اسم جهة الاتصال مطلوب.", "warning");
        return;
    }

    if (!contactsDataTable || !$.fn.DataTable.isDataTable('#contactsTable')) {
        initializeContactsTable();
        if (!contactsDataTable || !$.fn.DataTable.isDataTable('#contactsTable')) {
            showAlert("فشل في تهيئة جدول جهات الاتصال. لا يمكن إضافة/تعديل جهة الاتصال.", "error");
            console.error("Critical: contactsDataTable failed to initialize in addContactToTable.");
            return;
        }
    }

    const primaryText = isPrimary ? 'Yes' : 'No';
    let newContactId = editingId;

    if (!newContactId) {
        newContactId = `contact_temp_${Date.now()}_${Math.floor(Math.random() * 1000)}`;
    }

    const newRowData = [
        `<input type="checkbox" class="selectContact">`,
        newContactId,
        name,
        email,
        phone,
        mobile,
        position,
        primaryText
    ];

    if (editingId) {
        let rowIndex = -1;
        contactsDataTable.rows().every(function (index) {
            const rowData = this.data();
            if (rowData[1] === editingId) {
                rowIndex = index;
                return false;
            }
        });

        if (rowIndex !== -1) {
            contactsDataTable.row(rowIndex).data(newRowData).draw();
            showAlert("تم تحديث جهة الاتصال بنجاح!", "success");
        } else {
            showAlert("لم يتم العثور على جهة الاتصال للتعديل.", "error");
        }
    } else {
        contactsDataTable.row.add(newRowData).draw();
        showAlert("تم إضافة جهة اتصال جديدة بنجاح!", "success");
    }
    clearContactForm();
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
        document.getElementById('editingContactId').value = rowData[1] || '';
        document.getElementById('contactName').value = rowData[2] || '';
        document.getElementById('contactEmail').value = rowData[3] || '';
        document.getElementById('contactPhone').value = rowData[4] || '';
        document.getElementById('contactMobile').value = rowData[5] || '';
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
 * حفظ بيانات المشروع وجهات الاتصال.
 * @param {Event} event - كائن الحدث.
 * @param {boolean} closeAfterSave - إغلاق المودال بعد الحفظ.
 * تم تحديث الحقول لتتناسب مع طلب المستخدم.
 */
function saveProjectData(event, closeAfterSave = true) {
    event.preventDefault();

    const projectReference = document.getElementById("projectReference")?.value || "";
    const projectNumber = document.getElementById("projectNumber")?.value || "";
    const projectName = document.getElementById("projectName")?.value || "";
    const customer = document.getElementById("customer")?.value || "";
    const opportunity = document.getElementById("opportunity")?.checked || false;
    const registrationDate = document.getElementById("registrationDate")?.value || "";
    const region = document.getElementById("region")?.value || "";
    const department = document.getElementById("department")?.value || "";
    const dateStarted = document.getElementById("dateStarted")?.value || "";
    const dateCompleted = document.getElementById("dateCompleted")?.value || "";

    if (!projectReference || !projectName || !customer || !registrationDate) {
        showAlert("الرجاء ملء جميع الحقول المطلوبة (مرجع المشروع، اسم المشروع، العميل، تاريخ التسجيل).", "error");
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

    const opportunityText = opportunity ? 'Yes' : 'No';

    // تم تحديث مصفوفة البيانات لتتناسب مع الأعمدة الجديدة بالترتيب الصحيح
    const mainTableRowData = [
        `<input type="checkbox" class="selectProject" value="${projectReference}" data-project-name="${projectName}">`,
        projectReference,
        projectNumber,
        projectName,
        customer,
        opportunityText,
        registrationDate,
        region,
        department,
        dateStarted,
        dateCompleted,
        contactsData // العمود المخفي لجهات الاتصال
    ];

    if (editingProjectRow && editingProjectRow.data()) {
        editingProjectRow.data(mainTableRowData).draw();
        showAlert("تم تحديث بيانات المشروع بنجاح!", "success");
    } else {
        window.projectsTableDataTable.row.add(mainTableRowData).draw();
        localStorage.setItem("lastProjectId", parseInt(projectReference.replace("AAMC-", "")));
        showAlert("تم إضافة مشروع جديد بنجاح!", "success");
    }

    if (closeAfterSave) {
        closeProjectModal();
    }
}
// =================== دوال الطباعة والتصدير (عامة ومعدلة) ===================

/**
 * دالة عامة لتصدير أي جدول DataTables إلى Excel.
 * @param {string} tableId - معرف الجدول (مثل 'projectsTable' أو 'contactsTable').
 * @param {string} fileName - اسم الملف (مثل 'Projects_Data').
 */
function exportTableToExcel(tableId, fileName) {
    const table = document.getElementById(tableId);
    if (!table) {
        showAlert(`Table with ID "${tableId}" not found for export.`, "error");
        return;
    }

    let dataTableInstance;
    if (tableId === 'projectsTable' && window.projectsTableDataTable) {
        dataTableInstance = window.projectsTableDataTable;
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
        // تجاهل أعمدة مربع الاختيار والأعمدة المخفية
        if (index === 0 && $(this).find('input[type="checkbox"]').length > 0) {
            return;
        }
        if (tableId === 'contactsTable' && index === 1) { // Contact ID
            return;
        }
        if (tableId === 'projectsTable' && index === 11) { // Contacts Data
            return;
        }
        // استخراج النص من الرأس، مع تجاهل حقول البحث
        let headerText = $(this).contents().filter(function() {
            return this.nodeType === 3;
        }).text().trim();
        if(!headerText) {
             headerText = $(this).find('label').text().trim() || $(this).text().trim();
        }
        
        headerRow.push(headerText);
    });
    ws_data.push(headerRow);

    // Add data rows from DataTables
    dataTableInstance.rows({ search: 'applied', order: 'current' }).every(function () {
        const rowData = this.data();
        const exportRow = [];

        if (tableId === 'projectsTable') {
            for (let i = 1; i < rowData.length; i++) {
                if (i === 11) continue;
                exportRow.push(rowData[i]);
            }
        } else if (tableId === 'contactsTable') {
            exportRow.push(rowData[2] || '');
            exportRow.push(rowData[3] || '');
            exportRow.push(rowData[4] || '');
            exportRow.push(rowData[5] || '');
            exportRow.push(rowData[6] || '');
            exportRow.push(rowData[7] || '');
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

    $(table).find('thead tr:first th').each(function (index) {
        if (index === 0) return;
        if (tableId === 'contactsTable' && index === 1) return;
        if (tableId === 'projectsTable' && index === 11) return;

        let headerText = $(this).contents().filter(function() {
            return this.nodeType === 3;
        }).text().trim();
        if (headerText.includes('\n')) {
            headerText = headerText.split('\n')[0].trim();
        }
        printContents += `<th>${headerText}</th>`;
    });
    printContents += `</tr>
            </thead>
            <tbody>`;

    window[tableId === 'projectsTable' ? 'projectsTableDataTable' : 'contactsDataTable'].rows({ search: 'applied' }).every(function () {
        const rowData = this.data();
        printContents += `<tr>`;

        if (tableId === 'projectsTable') {
            for (let i = 1; i < rowData.length; i++) {
                if (i === 11) continue;
                printContents += `<td>${rowData[i]}</td>`;
            }
        } else if (tableId === 'contactsTable') {
            printContents += `<td>${rowData[2] || ''}</td>`;
            printContents += `<td>${rowData[3] || ''}</td>`;
            printContents += `<td>${rowData[4] || ''}</td>`;
            printContents += `<td>${rowData[5] || ''}</td>`;
            printContents += `<td>${rowData[6] || ''}</td>`;
            printContents += `<td>${rowData[7] || ''}</td>`;
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
