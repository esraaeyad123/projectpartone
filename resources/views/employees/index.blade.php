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
                        <button title="Export to Excel" id="exportEmployeesExcelBtn" class="btn-icon" onclick="exportEmployeesExcel()">
                         <i class="fa-solid fa-table"></i>
                        </button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


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
        // مسح الجدول قبل إعادة التحميل
        employeesTable.clear();

        employees.forEach(e => {
            // إضافة الصف مع checkbox
            let rowNode = employeesTable.row.add([
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
                ''                            // 14: عمود مخفي
            ]).draw(false).node();

            // إضافة data-attribute لكل صف
            $(rowNode).attr('data-employee-id', e.id);
        });
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

           window.goToEmployeeFiles = function() {
    const employeesTable = $('#employeesTable').DataTable();
    if (!employeesTable) {
        Swal.fire("تنبيه ⚠️", "لم يتم العثور على جدول الموظفين!", "warning");
        return;
    }

    // اختيار الصفوف المحددة
    const selectedCheckboxes = employeesTable.$('input.employee-checkbox:checked');

    if (selectedCheckboxes.length === 0) {
        Swal.fire("تنبيه ⚠️", "يرجى اختيار موظف واحد!", "warning");
        return;
    }

    if (selectedCheckboxes.length > 1) {
        Swal.fire("تنبيه ⚠️", "يرجى اختيار موظف واحد فقط!", "warning");
        return;
    }

    const employeeId = $(selectedCheckboxes[0]).closest('tr').data('employee-id');
    if (!employeeId) {
        Swal.fire("خطأ ❌", "لم يتم العثور على ID الموظف!", "error");
        return;
    }

    // التوجيه لصفحة ملفات الموظف
    window.location.href = `/employees/${employeeId}/files`;
};

//----------------------------------------------------------------------------------------
//----------------------------------Employee Contacts-------------------------------------
//----------------------------------------------------------------------------------------
window.toggleAllEmployeeContacts = function(source, tableId) {
    const employeeTable = $(`#${tableId}`).DataTable();
    const rows = employeeTable.rows({ 'search': 'applied' }).nodes();
    $('input.contact-select', rows).prop('checked', source.checked);
};

// --------------------------------------------------------
// جدول جهات الاتصال للموظف
// --------------------------------------------------------
$(document).ready(function() {
    // جدول الموظف عند إضافة موظف جديد
    window.employeeContactsTable = $('#employeeContactsTable').DataTable({
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
            $(row).find('input.contact-select').val(data.id); // تأكد من تعيين القيمة

        }
    });

    // فلترة الأعمدة
    $('#employeeContactsTable thead .column-filter').on('keyup change', function() {
        let index = $(this).parent().index();
        window.employeeContactsTable.column(index).search(this.value).draw();
    });

    // جدول الموظف عند تعديل موظف
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
           $(row).find('input.contact-select').val(data.id); // تأكد من تعيين القيمة

        }
    });

    $('#editEmployeeContactsTable thead .column-filter').on('keyup change', function() {
        let index = $(this).parent().index();
        window.employeeContactsTableEdit.column(index).search(this.value).draw();
    });
});

// --------------------------------------------------------
// دوال مساعدة خارج ready
// --------------------------------------------------------

// تعبئة جدول جهات الاتصال عند تعديل الموظف
window.populateEmployeeContactsTableEdit = function(contacts) {
    if (!window.employeeContactsTableEdit) {
        console.error("employeeContactsTableEdit is not initialized!");
        return;
    }
    window.employeeContactsTableEdit.clear();
    window.employeeContactsTableEdit.rows.add(contacts).draw();
};

// مسح فورم جهة الاتصال
// مسح فورم جهة الاتصال
window.clearEmployeeContactForm = function(modalType = 'add') {
    if (modalType === 'edit') {
        $('#editContactIdEmployee, #editContactNameEmployee, #editContactEmailEmployee, #editContactPhoneEmployee, #editContactMobileEmployee, #editContactPositionEmployee').val('');
        $('#editIsPrimaryContactEmployee').prop('checked', false);
    } else {
        $('#employeeContactIdAdd, #employeeContactName, #employeeContactEmail, #employeeContactPhone, #employeeContactMobile, #employeeContactPosition').val('');
        $('#isPrimaryContactEmployee').prop('checked', false);
    }
};


// حفظ أو تعديل جهة الاتصال للموظف
window.saveEmployeeContact = function(modalType = 'add') {
    let employeeId = (modalType === 'edit') ? $('#editEmployeeId').val() : $('#employeeId').val();
    if (!employeeId) {
        Swal.fire("خطأ", "❌ الرجاء حفظ الموظف أولًا قبل إضافة جهة الاتصال", "error");
        return;
    }

    // اختيار ID الصحيح من الفورم
    let contactId = (modalType === 'edit')
        ? ($('#editContactIdEmployee').val() || '').trim()
        : ($('#employeeContactIdAdd').val() || '').trim();

    let url = contactId ? `/employees/${employeeId}/contacts/${contactId}` : `/employees/${employeeId}/contacts`;
    let method = contactId ? 'PUT' : 'POST';

    let formData = {
        name: (modalType === 'edit') ? $('#editContactNameEmployee').val() || '' : $('#employeeContactName').val() || '',
        email: (modalType === 'edit') ? $('#editContactEmailEmployee').val() || '' : $('#employeeContactEmail').val() || '',
        phone: (modalType === 'edit') ? $('#editContactPhoneEmployee').val() || '' : $('#employeeContactPhone').val() || '',
        mobile: (modalType === 'edit') ? $('#editContactMobileEmployee').val() || '' : $('#employeeContactMobile').val() || '',
        position: (modalType === 'edit') ? $('#editContactPositionEmployee').val() || '' : $('#employeeContactPosition').val() || '',
        is_primary: (modalType === 'edit')
            ? ($('#editIsPrimaryContactEmployee').is(':checked') ? 1 : 0)
            : ($('#isPrimaryContactEmployee').is(':checked') ? 1 : 0),
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


// حذف جهات الاتصال المحددة للموظفين
        // ✅ دالة لحذف الموظفين المحددين
window.deleteSelectedEmployeeContacts = function(tableSelector) {
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
            url: `/employee-contacts/delete-multiple`, // تأكد أن هذا المسار معرف في Laravel
            type: 'DELETE',
            data: {
                ids: ids,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(res) {
                if (res.success) {
                    Swal.fire("تم", "✔️ تم حذف جهات الاتصال بنجاح", "success");

                    // ✅ استخدام DataTable مباشرة من الـ selector
                    let table = $(tableSelector).DataTable();

                    // إزالة الصفوف المحذوفة من الجدول بدون إعادة تحميل الصفحة
                    table.rows(function(idx, data, node) {
                        return ids.includes($(node).attr('data-contact-id'));
                    }).remove().draw(false);
                } else {
                    Swal.fire("خطأ", res.message || "❌ لم يتم الحذف", "error");
                }
            },
            error: function(xhr) {
                Swal.fire("خطأ", xhr.responseJSON?.message || "❌ خطأ في الاتصال بالسيرفر", "error");
            }
        });
    });
};



// تعبئة الفورم عند الضغط على "Edit Selected"

window.populateEmployeeContactFormForEdit = function(modalType = 'add') {
    const table = (modalType === 'edit') ? window.employeeContactsTableEdit : window.employeeContactsTable;

    // جلب الصفوف المحددة
    const selectedRows = table.rows().nodes().to$().find('input.contact-select:checked');

    if (selectedRows.length === 0) {
        Swal.fire("تنبيه", "⚠️ الرجاء اختيار جهة اتصال واحدة للتعديل", "warning");
        return;
    }
    if (selectedRows.length > 1) {
        Swal.fire("تنبيه", "⚠️ الرجاء اختيار جهة اتصال واحدة فقط للتعديل", "warning");
        return;
    }

    // الحصول على بيانات الصف المحدد من DataTable API
    const rowIndex = table.row(selectedRows.closest('tr')).index();
    const rowData = table.row(rowIndex).data();

    if (!rowData) {
        Swal.fire("خطأ", "❌ لم يتم العثور على بيانات جهة الاتصال", "error");
        return;
    }

    // تعبئة الفورم حسب نوع المودال
    if (modalType === 'edit') {
        $('#editContactIdEmployee').val(rowData.id);
        $('#editContactNameEmployee').val(rowData.name);
        $('#editContactEmailEmployee').val(rowData.email);
        $('#editContactPhoneEmployee').val(rowData.phone);
        $('#editContactMobileEmployee').val(rowData.mobile);
        $('#editContactPositionEmployee').val(rowData.position);
        $('#editIsPrimaryContactEmployee').prop('checked', rowData.is_primary == 1 || rowData.is_primary === true);
    } else {
        $('#employeeContactIdAdd').val(rowData.id);
        $('#employeeContactNameAdd').val(rowData.name);
        $('#employeeContactEmailAdd').val(rowData.email);
        $('#employeeContactPhoneAdd').val(rowData.phone);
        $('#employeeContactMobileAdd').val(rowData.mobile);
        $('#employeeContactPositionAdd').val(rowData.position);
        $('#isPrimaryEmployeeContactAdd').prop('checked', rowData.is_primary == 1 || rowData.is_primary === true);
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
    let rowsData = [];

    const selectedCheckboxes = employeesTable.$('input[type="checkbox"]:checked');
    if (selectedCheckboxes.length > 0) {
        selectedCheckboxes.each(function() {
            const row = $(this).closest('tr');
            const data = employeesTable.row(row).data();
            rowsData.push(data);
        });
    } else {
        rowsData = employeesTable.rows({ search: 'applied' }).data().toArray();
    }

    if (rowsData.length === 0) {
        showAlert('⚠️ لا توجد بيانات لتصديرها.', 'warning');
        return;
    }

    // بناء بيانات Excel
    const header = Object.keys(rowsData[0]);
    const data = [header];

    rowsData.forEach(row => {
        const rowArray = header.map(h => row[h]);
        data.push(rowArray);
    });

    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "الموظفين");
    XLSX.writeFile(wb, "employees-data.xlsx");

    showAlert('تم تصدير بيانات الموظفين بنجاح إلى ملف Excel.', 'success');
};

// ---------------------- طباعة جدول جهات الاتصال ----------------------
window.printContactsTable = function(tableId) {
        const tableElement = document.getElementById(tableId);
        if (!tableElement) {
            showAlert('⚠️ لم يتم العثور على جدول جهات الاتصال.', 'warning');
            return;
        }

        const contactsTable = $(tableElement).DataTable();
        const selectedCheckboxes = contactsTable.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            rowsToProcess = contactsTable.rows({ 'search': 'applied' }).nodes();
        }

        let printContents = `
            <html>
            <head>
                <title>طباعة قائمة جهات الاتصال</title>
                <style>
                    .contactsTable { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                    body { font-family: 'Arial', sans-serif; }
                    h2 { text-align: center; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <h2>قائمة جهات الاتصال</h2>
                <table>
                    <thead><tr>`;

        $(tableElement).find('thead tr:first th').each(function() {
            if ($(this).find('input[type="checkbox"]').length > 0 || $(this).text().trim() === '') {
                return;
            }
            const headerClone = $(this).clone();
            headerClone.find('input, select, .btn, i').remove();
            const columnTitle = headerClone.text().trim();
            printContents += '<th>' + columnTitle + '</th>';
        });

        printContents += `</tr></thead><tbody>`;

        $(rowsToProcess).each(function() {
            printContents += '<tr>';
            $(this).find('td').each(function() {
                if ($(this).find('input[type="checkbox"]').length > 0 || $(this).find('.icon-toolbar').length > 0) {
                    return;
                }
                printContents += '<td>' + $(this).text().trim() + '</td>';
            });
            printContents += '</tr>';
        });

        printContents += `</tbody></table></body></html>`;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContents);
        printWindow.document.close();
        printWindow.focus();

        // تأكد من أن أمر الطباعة يتم استدعاؤه بعد تحميل المحتوى بالكامل.
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };

        // قم بتغيير مكان استدعاء showAlert
        // أضف رسالة النجاح في نهاية الدالة.
        showAlert('تم إرسال أمر الطباعة بنجاح.', 'success');
    };

// ---------------------- تصدير جدول جهات الاتصال إلى Excel ----------------------
window.exportContactsExcelBtn = function(tableId) {
    const contactsTableElement = document.getElementById(tableId);
    if (!contactsTableElement) {
        showAlert('⚠️ لم يتم العثور على جدول جهات الاتصال.', 'warning');
        return;
    }

    const contactsTable = $(contactsTableElement).DataTable();

    const selectedCheckboxes = contactsTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;

    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = contactsTable.rows({ 'search': 'applied' }).nodes();
    }

    // 1. بناء البيانات
    const data = [];
    const header = [];
    $(contactsTableElement).find('thead th').each(function() {
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

    // 2. إنشاء ملف Excel
    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "جهات_الاتصال");

    // 3. تغيير اسم الملف حسب نوع الجدول
    const fileName = (tableId === 'editEmployeeContactsTable')
        ? 'contacts-data-edit.xlsx'
        : 'contacts-data-add.xlsx';

    XLSX.writeFile(wb, fileName);

    // ✅ رسالة نجاح
    showAlert('✅ تم تصدير البيانات بنجاح إلى ملف Excel.', 'success');
};


   </script>

@endsection
