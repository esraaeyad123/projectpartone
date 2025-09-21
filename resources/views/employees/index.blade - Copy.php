@extends('layouts.app')
@section('title', __('Employees Management'))
@section('content')
@include('employees.create') <!-- المودال -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">


    <link rel="stylesheet" href="{{ asset('css/employees.css') }}">

  <div id="header-placeholder"></div>

    <div class="container">
        <div id="navbar-placeholder"></div>

        <main class="main-content">
            <section id="employees-section" class="section-content active">
                <div class="icon-toolbar">
                    <div>
                        <button title="Add" onclick="openEmployeeModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                        <button title="Edit" onclick="handleEditEmployee()" class="btn-icon"><i class="fas fa-pen"></i></button>
                        <button title="Delete" onclick="deleteSelectedEmployees()" class="btn-icon"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="icon-separator"></div>
                    <div>
                        <button title="File Manager" onclick="goToEmployeeFiles()" class="btn-icon"><i class="fas fa-folder-open"></i> </button>
                        <button title="Employee Report" id="generateEmployeeReportBtn" onclick="generateEmployeeReport()" class="btn-icon"><i class="fa-solid fa-file-invoice"></i></button>
                        <button title="Export to Excel" id="exportEmployeesExcelBtn" class="btn-icon"><i class="fa-solid fa-table"></i></button>
                        <button title="Print" id="printEmployeesTableBtn" onclick="printEmployeesTable()" class="btn-icon"><i class="fas fa-print"></i></button>
                    </div>
                </div>

                <div class="table-responsive-container">
                    <table id="employeesTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllEmployees"></th>
                                <th>Employee Reference<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Initials<br><select class="column-filter"><option value="">All</option></select></th>
                                <th>Mid. Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Full Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>First Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Last Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Email<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Supervisor<br><select class="column-filter"><option value="">All</option></select></th>
                                <th>CTTA<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Business Unit<br><select class="column-filter"><option value="">All</option></select></th>
                                <th>Department<br><select class="column-filter"><option value="">All</option></select></th>
                                <th>Title<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Job Rules<br>
                                    <select class="column-filter">
                                        <option value="">All</option>
                                        <option value="Office Staff">Office Staff</option>
                                        <option value="Laboratory Staff">Laboratory Staff</option>
                                        <option value="Site Staff">Site Staff</option>
                                        <option value="Drillers Staff">Drillers Staff</option>
                                        <option value="Drivers Staff">Drivers Staff</option>
                                    </select>
                                </th>
                                <th class="d-none">Contacts Data</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </section>


 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


    <script>
        //====================== Start Script ======================
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

$(document).ready(function() {

    var dateColumnIndex = null; // لو عندك عمود تاريخ للتصفية غير null

    // تهيئة Flatpickr
    flatpickr(".flatpickr-input", {
        dateFormat: "Y-m-d",
        locale: "en",
        allowInput: true
    });

    // تهيئة جدول الموظفين
    window.employeesTable = $('#employeesTable').DataTable({
        responsive: true,
        autoWidth: false,
        scrollX: true,
        columnDefs: [
            { orderable: false, targets: [0] } // عمود الشيكبوكس
        ]
    });

    // استنساخ صف الرؤوس لإنشاء صف البحث
    $('#employeesTable thead tr').clone(true).appendTo('#employeesTable thead');
    $('#employeesTable thead tr:eq(1) th:eq(0)').empty(); // منع البحث في عمود الشيكبوكس

    // تطبيق الفلترة على كل حقل
    employeesTable.columns().every(function() {
        var column = employeesTable.column(this.index());
        if (this.index() === 0) return; // تجاهل عمود الشيكبوكس
        if (dateColumnIndex !== null && this.index() === dateColumnIndex) return;

        $('input, select', this.header()).on('keyup change', function() {
            var val = $.fn.dataTable.util.escapeRegex($(this).val());
            if (column.search() !== val) {
                column.search(val).draw();
            }
        });
    });

    // تحميل البيانات للمرة الأولى
    loadEmployees();

    function parseDateToNumber(str) {
        if (!str) return null;
        str = String(str).trim().split(' ')[0];
        var parts = str.split('-');
        if (parts.length !== 3) return null;
        return parseInt(parts[0] + parts[1].padStart(2,'0') + parts[2].padStart(2,'0'), 10);
    }

    // فلتر التاريخ (إذا موجود)
    if (dateColumnIndex !== null) {
        $.fn.dataTable.ext.search.push(function(settings, data) {
            if (settings.nTable.id !== 'employeesTable') return true;
            var minVal = $('input[data-filter-type="date-from"]').val();
            var maxVal = $('input[data-filter-type="date-to"]').val();
            var minNum = minVal ? parseDateToNumber(minVal) : null;
            var maxNum = maxVal ? parseDateToNumber(maxVal) : null;
            var rowNum = parseDateToNumber(data[dateColumnIndex]);

            if (rowNum === null) return (minNum === null && maxNum === null);
            if (minNum === null && maxNum === null) return true;
            if (minNum === null && rowNum <= maxNum) return true;
            if (maxNum === null && rowNum >= minNum) return true;
            if (rowNum >= minNum && rowNum <= maxNum) return true;

            return false;
        });

        $('input[data-filter-type="date-from"], input[data-filter-type="date-to"]').on('change keyup', function() {
            employeesTable.draw();
        });
    }
});
function loadEmployees() {
        $.get('/employees', function(employees) {
            employeesTable.clear();
            employees.forEach(e => {
                employeesTable.row.add([
                    `<input type="checkbox" class="employee-checkbox" value="${e.id}">`, // 0
                    e.employee_reference || '',  // 1
                    e.initials || '',            // 2
                    e.mid_name || '',            // 3
                    e.full_name || '',           // 4
                    e.first_name || '',          // 5
                    e.last_name || '',           // 6
                    e.email || '',               // 7
                    e.supervisor_id || '',       // 8
                    e.ctta || '',                // 9
                    e.business_unit || '',       // 10
                    e.department || '',          // 11
                    e.title || '',               // 12
                    e.job_rules || '',           // 13
                    ''                            // 14: Contacts Data (عمود مخفي)
                ]);
            });
        employeesTable.draw(false); // false حتى لا يتم إعادة ترتيب الجدول
        });
    }

// تحديد/إلغاء تحديد جميع الموظفين
$('#selectAllEmployees').on('change', function() {
    let rows = employeesTable.rows({ 'search': 'applied' }).nodes();
    $('input.employee-checkbox', rows).prop('checked', this.checked);
});

window.populateEmployeeContactsTableEdit = function(contacts) {
    if (!window.employeeContactsTableEdit) {
        console.error("employeeContactsTableEdit is not initialized!");
        return;
    }
    window.employeeContactsTableEdit.clear();
    window.employeeContactsTableEdit.rows.add(contacts).draw();
};
// تحديث خانة "تحديد الكل" عند تحديد أي موظف فردي
$('#employeesTable tbody').on('change', 'input.employee-checkbox', function() {
    let allChecked = $('.employee-checkbox').length === $('.employee-checkbox:checked').length;
    $('#selectAllEmployees').prop('checked', allChecked);
});



function openEmployeeContactModal() {
    clearContactForm('employee');
    $('#employeeContactModal').show();
}

function closeEmployeeContactModal() {
    $('#employeeContactModal').hide();
}

function clearContactForm(type) {
    if (type === 'employee') {
        $('#editContactIdEmployee').val('');
        $('#employeeContactName').val('');
        $('#employeeContactEmail').val('');
        $('#employeeContactPhone').val('');
        $('#employeeContactMobile').val('');
        $('#employeeContactPosition').val('');
        $('#isPrimaryContactEmployee').prop('checked', false);
    } else if (type === 'add') {
        $('#editContactIdAdd').val('');
        $('#contactNameAdd').val('');
        $('#contactEmailAdd').val('');
        $('#contactPhoneAdd').val('');
        $('#contactMobileAdd').val('');
        $('#contactPositionAdd').val('');
        $('#isPrimaryContactAdd').prop('checked', false);
    }
}



// نسخة للتعديل Edit Employee Modal
function switchEmployeeTab(tabName, modalType = 'add') {
    let container = (modalType === 'edit') ? '#editEmployeeModal' : '#employeeModal';

    // أخفي جميع التابات داخل هذا المودال فقط
    $(`${container} .form-tab-content`).hide();
    $(`${container} .tab-buttons button`).removeClass('active');

    if(tabName === 'employee') {
        $(`${container} #${modalType === 'edit' ? 'editEmployeeTab' : 'employeeTab'}`).show();
        $(`${container} #${modalType === 'edit' ? 'edit-employee-btn' : 'employee-btn'}`).addClass('active');
    } else if(tabName === 'contact') {
        $(`${container} #${modalType === 'edit' ? 'editContactTab' : 'contactTab'}`).show();
        $(`${container} #${modalType === 'edit' ? 'edit-contact-btn' : 'contact-btn'}`).addClass('active');
    }
}

//========================= Employees Modals =========================

// فتح مودال إضافة موظف جديد
function openEmployeeModal() {
    $('#employeeForm')[0].reset();  // مسح الفورم
    $('#employeeId').val('');       // مسح الـ ID
    $('#employeeModal').show();     // إظهار المودال
}

// غلق مودال إضافة موظف
function closeEmployeeModal() {
    $('#employeeModal').hide();
}

// غلق مودال تعديل موظف
function closeEditEmployeeModal() {
    $('#editEmployeeModal').hide();
}

// الحصول على جميع الـ IDs المحددة من جدول الموظفين
function getSelectedEmployeeIds() {
    let ids = [];
    $('.employee-checkbox:checked').each(function() {
        ids.push($(this).val());
    });
    return ids;
}

// ==================== Save Employee ====================
   function saveEmployee(closeAfterSave) {
    const employeeId = $('#employeeId').val();
    const fullName = $('#fullName').val().trim();

    if (!fullName) {
        Swal.fire("تحذير", "⚠️ الرجاء إدخال الاسم الكامل للموظف.", "warning");
        return;
    }

    // تحديد URL والطريقة
    const url = employeeId ? `/employees/${employeeId}` : '/employees';
    const method = employeeId ? 'PUT' : 'POST';

    // بناء بيانات النموذج للإرسال
    const formData = {
        initials: $('#initials').val() || null,
        first_name: $('#firstName').val(),
        mid_name: $('#midName').val() || null,
        last_name: $('#lastName').val(),
        full_name: fullName,
        email: $('#email').val() || null,
        title: $('#title').val() || null,
        supervisor_id: $('#supervisorId').val() || null,
        ctta: $('#ctta').val() || null,
        business_unit: $('#businessUnit').val() || null,
        department: $('#department').val() || null,
        job_roles: getSelectedJobRoles(), // بدون prefix يستخدم checkboxes الإضافة
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: url,
        type: method,
        data: formData,
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'تم الحفظ بنجاح ✅',
                text: 'تم حفظ الموظف بنجاح.',
                timer: 2000,
                showConfirmButton: false
            });

            $('#employeeId').val(response.id); // لو كان إنشاء جديد
            loadEmployees(); // دالة تحميل جدول الموظفين
            if (closeAfterSave) closeEmployeeModal();
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'خطأ ❌',
                text: 'حدث خطأ أثناء حفظ الموظف',
                confirmButtonText: 'حسناً'
            });
        }
    });
}


//=================================================================================
// فتح مودال تعديل موظف
function openEditEmployeeModal(id) {
    // جلب بيانات الموظف
    $.get(`/employees/${id}`, function(employee) {
        if (!employee || !employee.id) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ ❌',
                text: 'لم يتم العثور على بيانات الموظف!'
            });
            return;
        }

        // تعبئة الحقول في المودال
        $('#editEmployeeId').val(employee.id);
        $('#editEmployeeReference').val(employee.employee_reference || '');
        $('#editFullName').val(employee.full_name || '');
        $('#editEmail').val(employee.email || '');
        $('#editTitle').val(employee.title || '');
        $('#editFirstName').val(employee.first_name || '');
        $('#editMidName').val(employee.mid_name || '');
        $('#editLastName').val(employee.last_name || '');
        $('#editSupervisor').val(employee.supervisor_id || '');
        $('#editBusinessUnit').val(employee.business_unit || '');
        $('#editDepartment').val(employee.department || '');
        $('#editCTTA').val(employee.ctta || '');

        // مسح كل checkboxes الخاصة بالوظائف
        $('#editOfficeStaff, #editLaboratoryStaff, #editSiteStaff, #editDrillersStaff, #editDriversStaff').prop('checked', false);

        // عمل check حسب job_roles
        if (employee.job_rules) {
            const roles = employee.job_rules.split(',').map(r => r.trim());
            $('#editOfficeStaff').prop('checked', roles.includes('Office Staff'));
            $('#editLaboratoryStaff').prop('checked', roles.includes('Laboratory Staff'));
            $('#editSiteStaff').prop('checked', roles.includes('Site Staff'));
            $('#editDrillersStaff').prop('checked', roles.includes('Drillers Staff'));
            $('#editDriversStaff').prop('checked', roles.includes('Drivers Staff'));
        }

        // جلب جهات الاتصال الخاصة بالموظف
        $.get(`/employees/${id}/contacts`, function(contacts) {
            console.log('Contacts fetched:', contacts);
            if (Array.isArray(contacts) && window.employeeContactsTableEdit) {
                window.employeeContactsTableEdit.clear().rows.add(contacts).draw();

                // بعد إضافة الصفوف، إظهار المودال وضبط الأعمدة
                $('#editEmployeeModal').show();
                window.employeeContactsTableEdit.columns.adjust().draw();
            } else {
                console.error("Contacts data is not an array or table is not initialized");
                $('#editEmployeeModal').show(); // عرض المودال حتى لو لم توجد جهات اتصال
            }
        }).fail(function(err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: '❌ حدث خطأ أثناء تحميل جهات الاتصال.'
            });
            $('#editEmployeeModal').show(); // عرض المودال حتى لو فشل تحميل جهات الاتصال
        });

    }).fail(function(err) {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: '❌ حدث خطأ أثناء تحميل بيانات الموظف.'
        });
    });
}



//=================================================================================
// تحديث الموظف من مودال التعديل
   function updateEmployee(closeAfterSave = true) {
    const employeeId = $('#editEmployeeId').val();
    if (!employeeId) return;

    const formData = {
        first_name: $('#editFirstName').val().trim(),
        mid_name: $('#editMidName').val().trim() || null,
        last_name: $('#editLastName').val().trim(),
        full_name: $('#editFullName').val().trim(),
        email: $('#editEmail').val().trim() || null,
        title: $('#editTitle').val().trim() || null,
        supervisor_id: $('#editSupervisor').val() || null,
        business_unit: $('#editBusinessUnit').val() || null,
        department: $('#editDepartment').val() || null,
        ctta: $('#editCTTA').val() || null,
        job_roles: getSelectedJobRoles('edit'), // prefix "edit" => checkboxes التعديل
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: `/employees/${employeeId}`,
        type: 'PUT',
        data: formData,
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'تم التعديل بنجاح ✅',
                timer: 2000,
                showConfirmButton: false
            });

            loadEmployees();
            if (closeAfterSave) closeEditEmployeeModal();
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'خطأ ❌',
                text: 'حدث خطأ أثناء تعديل الموظف',
                confirmButtonText: 'حسناً'
            });
        }
    });
}


//=================================================================================
// جلب قيم الوظائف المحددة من checkboxes
  function getSelectedJobRoles(prefix = '') {
    const roles = [];
    // إذا prefix موجود (للتعديل)
    if (prefix === 'edit') {
        if ($('#editOfficeStaff').is(':checked')) roles.push('Office Staff');
        if ($('#editLaboratoryStaff').is(':checked')) roles.push('Laboratory Staff');
        if ($('#editSiteStaff').is(':checked')) roles.push('Site Staff');
        if ($('#editDrillersStaff').is(':checked')) roles.push('Drillers Staff');
        if ($('#editDriversStaff').is(':checked')) roles.push('Drivers Staff');
    } else {
        // للإضافة
        if ($('#officeStaff').is(':checked')) roles.push('Office Staff');
        if ($('#laboratoryStaff').is(':checked')) roles.push('Laboratory Staff');
        if ($('#siteStaff').is(':checked')) roles.push('Site Staff');
        if ($('#drillersStaff').is(':checked')) roles.push('Drillers Staff');
        if ($('#driversStaff').is(':checked')) roles.push('Drivers Staff');
    }
    return roles.join(', ');
}


function handleEditEmployee() {
    const selectedIds = getSelectedEmployeeIds(); // دالة موجودة لجلب الـ IDs
    if(selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'تحذير ⚠️',
            text: 'الرجاء اختيار موظف واحد للتعديل!'
        });
        return;
    }
    if(selectedIds.length > 1) {
        Swal.fire({
            icon: 'warning',
            title: 'تحذير ⚠️',
            text: 'الرجاء اختيار موظف واحد فقط للتعديل!'
        });
        return;
    }

    const employeeId = selectedIds[0];
    openEditEmployeeModal(employeeId);
       }

       // دالة عامة لجلب الوظائف حسب prefix


function deleteEmployee(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "سيتم حذف الموظف ولا يمكنك التراجع عن هذا الإجراء!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/employees/${id}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الحذف ✅',
                        text: 'تم حذف الموظف بنجاح.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadEmployees(); // تحديث جدول الموظفين مباشرة بدون إعادة تحميل الصفحة
                },
                error: function(xhr) {
                    console.error("Error deleting employee:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ ❌',
                        text: 'حدث خطأ أثناء محاولة حذف الموظف.',
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
}

function deleteSelectedEmployees() {
    let selectedIds = getSelectedEmployeeIds(); // دالة موجودة مسبقاً للحصول على الـ IDs

    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه ⚠️',
            text: 'يرجى اختيار موظف واحد على الأقل!',
            confirmButtonText: 'حسناً'
        });
        return;
    }

    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: `سيتم حذف ${selectedIds.length} موظف/موظفين ولا يمكنك التراجع عن هذا الإجراء!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/employees/delete-multiple', // رابط الحذف في السيرفر
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الحذف ✅',
                        text: 'تم حذف الموظفين المحددين بنجاح!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadEmployees(); // تحديث جدول الموظفين مباشرة بدون إعادة تحميل الصفحة
                },
                error: function(xhr) {
                    console.error("Error deleting employees:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ ❌',
                        text: 'حدث خطأ أثناء محاولة حذف الموظفين.',
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
}


//----------------------------------------------------------------------------------------
//----------------------------------Employee Contacts-------------------------------------
//----------------------------------------------------------------------------------------
window.toggleAllEmployeeContacts = function(source, tableId) {
    const employeeTable = $(`#${tableId}`).DataTable();
    const rows = employeeTable.rows({ 'search': 'applied' }).nodes();
    $('input.contact-select', rows).prop('checked', source.checked);
};

$(document).ready(function() {
    // جدول جهات الاتصال للموظف في إضافة موظف جديد
    window.employeeContactsTable = $('#employeeContactsTable').DataTable({
        columns: [
            {
                data: null,
                render: data => `<input type="checkbox" class="contact-select" value="${data.id}">`,
                orderable: false
            },
            { data: 'name' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'mobile' },
            { data: 'position' },
            {
                data: 'is_primary',
                render: d => (d == 1 || d === true || d === '1') ? 'Yes' : 'No'
            }
        ],
        createdRow: function(row, data) {
            $(row).attr('data-contact-id', data.id);
        }
    });

    $('#employeeContactsTable thead .column-filter').on('keyup change', function(){
        let index = $(this).parent().index();
        window.employeeContactsTable.column(index).search(this.value).draw();
    });

    // جدول جهات الاتصال للموظف في تعديل موظف
    window.employeeContactsTableEdit = $('#editEmployeeContactsTable').DataTable({
    responsive: true,
    columns: [
        { data: null, render: data => `<input type="checkbox" class="contact-select" value="${data.id}">`, orderable: false },
        { data: 'name' },
        { data: 'email' },
        { data: 'phone' },
        { data: 'mobile' },
        { data: 'position' },
        { data: 'is_primary', render: d => (d == 1 || d === true || d === '1') ? 'Yes' : 'No' }
    ],
    createdRow: function(row, data) {
        $(row).attr('data-contact-id', data.id);
    }
});

    $('#employeeContactsTableEdit thead .column-filter').on('keyup change', function(){
        let index = $(this).parent().index();
        window.employeeContactsTableEdit.column(index).search(this.value).draw();
    });
});




 // --------------------------------------------------------
// ✅ دوال مساعدة للموظفين
// --------------------------------------------------------

// دالة لتعبئة جدول جهات الاتصال للموظفين في وضع التعديل
window.populateEmployeeContactsTableEdit = function(contacts) {
    if (!window.employeeContactsTableEdit) {
        console.error("employeeContactsTableEdit is not initialized!");
        return;
    }
    window.employeeContactsTableEdit.clear();
    window.employeeContactsTableEdit.rows.add(contacts).draw();
};

// دالة لمسح حقول فورم جهة الاتصال للموظفين (إضافة أو تعديل)
window.clearEmployeeContactForm = function(modalType = 'add') {
    if (modalType === 'edit') {
        $('#editContactIdEmployee').val('');
        $('#editContactNameEmployee').val('');
        $('#editContactEmailEmployee').val('');
        $('#editContactPhoneEmployee').val('');
        $('#editContactMobileEmployee').val('');
        $('#editContactPositionEmployee').val('');
        $('#editIsPrimaryContactEmployee').prop('checked', false);
    } else {
        $('#editEmployeeContactIdAdd').val('');
        $('#employeeContactNameAdd, #employeeContactEmailAdd, #employeeContactPhoneAdd, #employeeContactMobileAdd, #employeeContactPositionAdd').val('');
        $('#isPrimaryEmployeeContactAdd').prop('checked', false);
    }
};

// دالة لحفظ أو تحديث جهة الاتصال للموظف
window.saveEmployeeContact = function(modalType = 'add') {
    let employeeId = (modalType === 'edit') ? $('#editEmployeeId').val() : $('#employeeId').val();
    if (!employeeId) {
        Swal.fire("خطأ", "❌ الرجاء حفظ الموظف أولًا قبل إضافة جهة الاتصال", "error");
        return;
    }

    let contactId = (modalType === 'edit') ? ($('#editEmployeeContactId').val() || '').trim() : ($('#editContactIdEmployee').val() || '').trim();
    let url = contactId ? `/employees/${employeeId}/contacts/${contactId}` : `/employees/${employeeId}/contacts`;
    let method = contactId ? 'PUT' : 'POST';

    let formData = {
        name: (modalType === 'edit') ? $('#editContactNameEmployee').val() || '' : $('#employeeContactName').val() || '',
        email: (modalType === 'edit') ? $('#editContactEmailEmployee').val() || '' : $('#employeeContactEmail').val() || '',
        phone: (modalType === 'edit') ? $('#editContactPhoneEmployee').val() || '' : $('#employeeContactPhone').val() || '',
        mobile: (modalType === 'edit') ? $('#editContactMobileEmployee').val() || '' : $('#employeeContactMobile').val() || '',
        position: (modalType === 'edit') ? $('#editContactPositionEmployee').val() || '' : $('#employeeContactPosition').val() || '',
        is_primary: (modalType === 'edit') ? ($('#editIsPrimaryContactEmployee').is(':checked') ? 1 : 0) : ($('#editIsPrimaryContactEmployee').is(':checked') ? 1 : 0),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    if (!formData.name) {
        Swal.fire("خطأ", "❌ الرجاء إدخال اسم جهة الاتصال", "warning");
        return;
    }

    $.ajax({
        url: url,
        type: method,
        data: formData,
        success: function(res) {
            Swal.fire("نجاح", "✔️ تم الحفظ بنجاح", "success");

            // إعادة تحميل كل جهات الاتصال للموظف بعد الحفظ
            $.get(`/employees/${employeeId}/contacts`, function(contacts) {
                if (modalType === 'edit') {
                    window.employeeContactsTableEdit.clear().rows.add(contacts).draw();
                } else {
                    window.employeeContactsTable.clear().rows.add(contacts).draw();
                }
            });

            window.clearEmployeeContactForm(modalType);
        },
        error: function(xhr) {
            let errors = xhr.responseJSON?.errors || {};
            let msg = '';
            for (let f in errors) msg += errors[f] + '\n';
            Swal.fire("خطأ", msg || '❌ حدث خطأ أثناء الحفظ', "error");
        }
    });
};


// دالة لحذف جهات الاتصال المحددة للموظفين
// خارج أي ready
function deleteSelectedContacts(tableSelector) {
    if (!tableSelector) {
        Swal.fire("خطأ", "⚠️ لم يتم تحديد الجدول", "error");
        return;
    }

    let ids = [];
    $(`${tableSelector} tbody input.contact-select:checked`).each(function() {
        let row = $(this).closest('tr');
        let id = row.attr('data-contact-id');
        if (id) ids.push(id);
    });

    if (ids.length === 0) {
        Swal.fire("تحذير", "⚠️ الرجاء اختيار جهة اتصال واحدة على الأقل للحذف", "warning");
        return;
    }

    Swal.fire({
        title: "تأكيد الحذف",
        text: `هل أنت متأكد من حذف ${ids.length} جهة اتصال؟`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "نعم، احذف",
        cancelButtonText: "إلغاء"
    }).then((result) => {
        if (!result.isConfirmed) return;

        $.ajax({
            url: `/contacts/delete-multiple`,
            type: 'DELETE',
            data: {
                ids: ids,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.success) {
                    Swal.fire("تم", res.message, "success");
                    let projectTable = window[tableSelector.replace('#', '')];
                    if (projectTable) {
                        projectTable.rows(function(idx, data, node) {
                            return ids.includes($(node).attr('data-contact-id'));
                        }).remove().draw(false);
                    }
                } else {
                    Swal.fire("خطأ", res.message || "❌ لم يتم الحذف", "error");
                }
            },
            error: function(xhr) {
                let msg = '❌ خطأ في الاتصال بالسيرفر';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                Swal.fire("خطأ", msg, "error");
            }
        });
    });
}


// دالة لتعبئة الفورم عند تعديل جهة اتصال موظف

// دالة تعبئة فورم جهة الاتصال عند الضغط على زر "Edit Selected"
window.populateEmployeeContactFormForEdit = function(modalType = 'add') {
    // تحديد الجدول وكائن DataTable بناءً على نوع المودال
    let employeeTable;
    let $tableSelector;
    if (modalType === 'edit') {
        employeeTable = window.employeeContactsTableEdit;
        $tableSelector = '#editEmployeeContactsTable';
    } else {
        employeeTable = window.employeeContactsTable;
        $tableSelector = '#employeeContactsTable';
    }

    // جلب الصفوف المحددة
    const selectedCheckboxes = $(`${$tableSelector} tbody input[type="checkbox"]:checked`);

    // تحقق من عدد الصفوف المحددة
    if (selectedCheckboxes.length === 0) {
        Swal.fire("تنبيه", "⚠️ الرجاء اختيار جهة اتصال واحدة للتعديل", "warning");
        return;
    }
    if (selectedCheckboxes.length > 1) {
        Swal.fire("تنبيه", "⚠️ الرجاء اختيار جهة اتصال واحدة فقط للتعديل", "warning");
        return;
    }

    // تعبئة الفورم بالبيانات
    const rowData = employeeTable.row(selectedCheckboxes.closest('tr')).data();
     console.log(rowData)
    if (!rowData) {
        Swal.fire("خطأ", "❌ لم يتم العثور على بيانات جهة الاتصال", "error");
        return;
    }

    if (modalType === 'edit') {
        $('#editContactIdEmployee').val(rowData.id);
        $('#editContactNameEmployee').val(rowData.name);
        $('#editContactEmailEmployee').val(rowData.email);
        $('#editContactPhoneEmployee').val(rowData.phone);
        $('#editContactMobileEmployee').val(rowData.mobile);
        $('#editContactPositionEmployee').val(rowData.position);
        $('#editIsPrimaryContactEmployee').prop('checked', rowData.is_primary == 1 || rowData.is_primary === true);
    } else {
        $('#editContactIdAddEmployee').val(rowData.id);
        $('#employeeContactName').val(rowData.name);
        $('#employeeContactEmail').val(rowData.email);
        $('#employeeContactPhone').val(rowData.phone);
        $('#employeeContactMobile').val(rowData.mobile);
        $('#employeeContactPosition').val(rowData.position);
        $('#isPrimaryContactEmployee').prop('checked', rowData.is_primary == 1 || rowData.is_primary === true);
    }
};

//----------------------------------------------------------------------------------------
//----------------------------------Print & Excel Employees--------------------------------
//----------------------------------------------------------------------------------------
window.printEmployeesTable = function() {
    const tableElement = document.getElementById('employeesTable');
    if (!tableElement) {
        showAlert('⚠️ لم يتم العثور على جدول الموظفين.', 'warning');
        return;
    }

    const employeesTable = $(tableElement).DataTable();
    const selectedCheckboxes = employeesTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;

    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = employeesTable.rows({ 'search': 'applied' }).nodes();
    }

    let printContents = `
        <html>
        <head>
            <title>قائمة الموظفين</title>
            <style>
                .employeesTable { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                body { font-family: 'Arial', sans-serif; }
                h2 { text-align: center; margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <h2>قائمة الموظفين</h2>
            <table>
                <thead><tr>`;

    // نسخ رؤوس الأعمدة بدون الشيكبوكس والأزرار
    $(tableElement).find('thead tr:first th').each(function() {
        if ($(this).find('input[type="checkbox"]').length > 0 || $(this).text().trim() === '') return;
        const headerClone = $(this).clone();
        headerClone.find('input, select, .btn, i').remove();
        printContents += '<th>' + headerClone.text().trim() + '</th>';
    });

    printContents += `</tr></thead><tbody>`;

    // إضافة الصفوف
    $(rowsToProcess).each(function() {
        printContents += '<tr>';
        $(this).find('td').each(function() {
            if ($(this).find('input[type="checkbox"]').length > 0 || $(this).find('.icon-toolbar').length > 0) return;
            printContents += '<td>' + $(this).text().trim() + '</td>';
        });
        printContents += '</tr>';
    });

    printContents += `</tbody></table></body></html>`;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContents);
    printWindow.document.close();
    printWindow.focus();

    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };

    showAlert('تم إرسال أمر الطباعة بنجاح.', 'success');
};

//----------------------------------------------------------------------------------------
window.exportEmployeesExcel = function() {
    const tableElement = document.getElementById('employeesTable');
    if (!tableElement) {
        showAlert('⚠️ لم يتم العثور على جدول الموظفين.', 'warning');
        return;
    }

    const employeesTable = $(tableElement).DataTable();
    const selectedCheckboxes = employeesTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;

    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = employeesTable.rows({ 'search': 'applied' }).nodes();
    }

    // بناء بيانات Excel
    const data = [];
    const header = [];
    $(tableElement).find('thead th').each(function() {
        if ($(this).find('input[type="checkbox"]').length === 0 && $(this).text().trim() !== '') {
            header.push($(this).text().trim());
        }
    });
    data.push(header);

    $(rowsToProcess).each(function() {
        const rowData = [];
        $(this).find('td').each(function() {
            if ($(this).find('input[type="checkbox"]').length === 0 && $(this).find('.icon-toolbar').length === 0) {
                rowData.push($(this).text().trim());
            }
        });
        data.push(rowData);
    });

    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "الموظفين");
    XLSX.writeFile(wb, "employees-data.xlsx");

    showAlert('تم تصدير بيانات الموظفين بنجاح إلى ملف Excel.', 'success');
};


   </script>

@endsection
