let employeesTable;
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
    initializeEmployeesTable();
    loadEmployees();

    // زر اختيار الكل
    $('#selectAllEmployees').on('change', function() {
        let checked = $(this).is(':checked');
        $('#employeesTable .employee-checkbox').prop('checked', checked);
    });
});

function initializeEmployeesTable() {
    if ($.fn.DataTable.isDataTable('#employeesTable')) {
        employeesTable.destroy();
    }

    employeesTable = $('#employeesTable').DataTable({
        responsive: false,
        orderCellsTop: true,
        fixedHeader: true,
        scrollX: true,
        language: { url: "/js/en-GB.json" },
        columns: [
            { orderable: false }, // 0: checkbox
            null, // 1: reference
            null, // 2: initials
            null, // 3: mid name
            null, // 4: full name
            null, // 5: first name
            null, // 6: last name
            null, // 7: email
            null, // 8: supervisor
            null, // 9: ctta
            null, // 10: business unit
            null, // 11: department
            null, // 12: title
            { render: data => data || '' }, // 13: job rules
            { visible: false, searchable: false, orderable: false } // 14: contacts data
        ],
        initComplete: function () {
            const api = this.api();

            // فلترة الأعمدة
            api.columns().every(function(index){
                if (index === 0 || index === 14) return;
                const column = this;
                $(column.header()).find('.column-filter').on('keyup change clear', function(){
                    if(column.search() !== this.value){
                        column.search(this.value).draw();
                    }
                });
            });

            // تمييز الصف عند اختيار checkbox
            $('#employeesTable tbody').on('click', 'input.employee-checkbox', function(){
                $(this).closest('tr').toggleClass('selected', this.checked);
            });

            // زر تعديل الموظف
            $('#employeesTable tbody').on('click', '.edit-employee-btn', function(){
                const row = api.row($(this).parents('tr')).data();
                const employeeId = row[1]; // Employee Reference
                openEmployeeModal(employeeId);
            });
        }
    });
}

function loadEmployees() {
    $.get('/employees/data', function(data){
        employeesTable.clear();
        data.forEach(emp => {
            employeesTable.row.add([
                `<input type="checkbox" class="employee-checkbox" value="${emp.id}">`,
                emp.employee_reference || '',
                emp.initials || '',
                emp.mid_name || '',
                emp.full_name || '',
                emp.first_name || '',
                emp.last_name || '',
                emp.email || '',
                emp.supervisor || '',
                emp.ctta || '',
                emp.business_unit || '',
                emp.department || '',
                emp.title || '',
                emp.job_rules || '',
                emp.contacts_data || ''
            ]);
        });
        employeesTable.draw();
    });
}




function openEmployeeModal() {
    $('#employeeForm')[0].reset();
    $('#editingEmployeeId').val(''); // نفرغها → معناها إضافة
    $('#employeeModal').show();
}

function closeEmployeeModal() {
    $('#employeeModal').hide();
}

function editEmployee(id) {
    $.get(`/employees/${id}`, function(emp){
        $('#employeeId').val(emp.id);
        $('#employeeRef').val(emp.reference);
        $('#firstName').val(emp.first_name);
        $('#lastName').val(emp.last_name);
        $('#email').val(emp.email);
        $('#department').val(emp.department);
        $('#title').val(emp.title);
        // Job rules checkboxes
        ['officeStaff','laboratoryStaff','siteStaff','drillersStaff','driversStaff'].forEach(rule => {
            $(`#${rule}`).prop('checked', emp.job_rules?.includes(rule) || false);
        });
        $('#employeeModal').show();
    });
}

 // حفظ أو تحديث موظف
  function saveEmployeeData(event) {
    event.preventDefault();

    const employeeId = $('#editingEmployeeId').val(); // ناخذ ID إذا كان تعديل
    let formData = {
        _token: $('meta[name="csrf-token"]').attr('content'),
        employeeReference: $('#employeeReference').val(),
        initials: $('#initials').val(),
        firstName: $('#firstName').val(),
        midName: $('#midName').val(),
        lastName: $('#lastName').val(),
        fullName: $('#fullName').val(),
        email: $('#email').val(),
        title: $('#title').val(),
        ctta: $('#ctta').val(),
        businessUnit: $('#businessUnit').val(),
        department: $('#department').val(),
        supervisor: $('#supervisor').val(),
        jobRules: getSelectedJobRules() // وظيفة مساعدة عشان تاخذ القيم من checkboxes
    };

    let url, method;
    if (employeeId) {
        url = `/employees/${employeeId}`;
        method = 'PUT';
        formData._method = 'PUT'; // ضروري مع Ajax
    } else {
        url = `/employees/store`;
        method = 'POST';
    }

    $.ajax({
        url: url,
        type: 'POST', // Laravel يستخدم _method لتحديد PUT
        data: formData,
        success: function (response) {
            closeEmployeeModal();
            loadEmployees();
            showAlert(employeeId ? 'تم تحديث الموظف بنجاح' : 'تمت إضافة الموظف بنجاح', 'success');
            $('#editingEmployeeId').val(''); // نرجعها فاضية بعد الحفظ
        },
        error: function (err) {
            console.error(err);
            if (err.status === 422) {
                let errors = err.responseJSON.errors;
                let errorMessages = Object.values(errors).map(arr => arr.join(', ')).join('\n');
                showAlert(errorMessages, 'error');
            } else if (err.status === 419) {
                showAlert('انتهت صلاحية الجلسة. يرجى إعادة تحميل الصفحة.', 'error');
            } else {
                showAlert('حدث خطأ غير متوقع أثناء حفظ الموظف', 'error');
            }
        }
    });
}

// وظيفة مساعدة لجمع job rules
function getSelectedJobRules() {
    let rules = [];
    if ($('#officeStaff').is(':checked')) rules.push('Office Staff');
    if ($('#laboratoryStaff').is(':checked')) rules.push('Laboratory Staff');
    if ($('#siteStaff').is(':checked')) rules.push('Site Staff');
    if ($('#drillersStaff').is(':checked')) rules.push('Drillers Staff');
    if ($('#driversStaff').is(':checked')) rules.push('Drivers Staff');
    return rules.join(', ');
}



// فتح مودال التعديل بعد اختيار موظف
function handleEditEmployee() {
    // الحصول على الموظف المحدد (checkbox محدد)
    const selected = $('.employee-checkbox:checked');
    if (selected.length === 0) {
        showAlert('الرجاء تحديد موظف للتعديل.', 'warning');
        return;
    }
    if (selected.length > 1) {
        showAlert('الرجاء تحديد موظف واحد فقط.', 'warning');
        return;
    }

    const employeeId = selected.val();
    openEditEmployeeModal(employeeId);
}

function openEditEmployeeModal(employeeId) {
    $.ajax({
        url: `/employees/${employeeId}`,
        type: 'GET',
        success: function(emp) {
            // فتح المودال
            $('#employeeModal').show();

            // تعبئة الحقول
            $('#employeeReference').val(emp.employee_reference);
            $('#initials').val(emp.initials);
            $('#firstName').val(emp.first_name);
            $('#midName').val(emp.mid_name);
            $('#lastName').val(emp.last_name);
            $('#fullName').val(emp.full_name);
            $('#email').val(emp.email);
            $('#title').val(emp.title);
            $('#ctta').val(emp.ctta);
            $('#businessUnit').val(emp.business_unit);
            $('#department').val(emp.department);

            // ✅ هنا نضع ID في الحقل المخفي
            $('#editingEmployeeId').val(emp.id);

            // تعبئة checkboxes الخاصة بـ job rules
            ['officeStaff','laboratoryStaff','siteStaff','drillersStaff','driversStaff'].forEach(rule => {
                $(`#${rule}`).prop('checked', emp.job_rules?.includes(rule) || false);
            });
        },
        error: function(err) {
            console.error(err);
            showAlert('تعذر تحميل بيانات الموظف.', 'error');
        }
    });
}



function deleteEmployee(id){
    Swal.fire({
        title: 'Confirm delete?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete!'
    }).then(result => {
        if(result.isConfirmed){
            $.ajax({
                url:`/employees/${id}`,
                type:'DELETE',
                data:{_token:$('meta[name="csrf-token"]').attr('content')},
                success:function(){ loadEmployees(); }
            });
        }
    });
}

function deleteSelectedEmployees(){
    let ids = [];
    $('.employee-checkbox:checked').each(function(){ ids.push($(this).val()); });
    if(ids.length===0){ Swal.fire('Select at least one'); return; }
    Swal.fire({title:`Delete ${ids.length} employees?`,icon:'warning',showCancelButton:true,confirmButtonText:'Yes'})
    .then(res=>{
        if(res.isConfirmed){
            $.post('/employees/delete-multiple',{_token:$('meta[name="csrf-token"]').attr('content'),ids},function(){ loadEmployees(); });
        }
    });
}

function editSelectedEmployee(){
    let selected = $('.employee-checkbox:checked');
    if(selected.length !== 1){ Swal.fire('Select exactly one employee'); return; }
    editEmployee(selected.val());
}

window.exportTableToExcel = function(tableId, fileName){
    // مماثلة للـ projects.js
};
window.printEmployeesTable = function(){ /* مماثلة للـ projects.js */ };
