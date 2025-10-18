@extends('layouts.app')
@section('title', __('Services / Tests'))
@section('content')
@include('testServicesAndCategory.create') <!-- هنا مودال إضافة Test -->

<main class="main-content">
    <section id="tests-section" class="section-content active">
        <div class="icon-toolbar">
            <div>
                <button title="Add" onclick="openAddTestModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                <button title="Edit" onclick="editTestselecteModal()" class="btn btn-warning">
                 <i class="fas fa-pen"></i>
               </button>
                <button title="Delete" onclick="deleteSelectedTests()" class="btn-icon"><i class="fas fa-trash"></i></button>
            </div>
            <div class="icon-separator"></div>
            <div>
                <button title="File Manager" onclick="goToProjectFiles()" class="btn-icon"><i class="fas fa-folder-open"></i></button>
                <button title="Export to Excel" onclick="exportTestsExcel()" class="btn-icon"><i class="fa-solid fa-table"></i></button>
                <button title="Print" onclick="printTestsTable()" class="btn-icon"><i class="fas fa-print"></i></button>
            </div>
        </div>

        <div class="table-responsive-container">
      <table id="testsTable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th><input type="checkbox" id="selectAllTests"></th>
            <th>Test Code</th>
            <th>Short Name</th>
            <th>Service Group</th>
            <th>Date Added</th>
            <th>Activity Type</th>
            <th>Element</th>
            <th>Unit Price</th>
            <th>Actions</th>
        </tr>
        <tr>
            <th></th> <!-- search row -->
            <th><input type="text" placeholder="Search Test Code"></th>
            <th><input type="text" placeholder="Search Short Name"></th>
            <th><input type="text" placeholder="Search Service Group"></th>
            <th>
                <input type="text" data-filter-type="date-from" placeholder="From">
                <input type="text" data-filter-type="date-to" placeholder="To">
            </th>
            <th><input type="text" placeholder="Search Activity Type"></th>
            <th><input type="text" placeholder="Search Element"></th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

                <tbody></tbody>
            </table>
        </div>
    </section>
</main>


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
    const dateColumnIndex = 4; // عمود Date Added

    // -------------------- Flatpickr --------------------
    flatpickr(".flatpickr-input", {
        dateFormat: "Y-m-d",
        locale: "en",
        allowInput: true
    });

    // -------------------- DataTable --------------------
    window.testsTable = $('#testsTable').DataTable({
        responsive: true,
        autoWidth: false,
        processing: true,
        scrollX: true,
        columnDefs: [
            { orderable: false, targets: [0, 8] } // الشيكبوكس والأزرار
        ]
    });

    loadTests();

    // -------------------- Helper: Parse Date --------------------
    function parseDateToNumber(str) {
        if (!str) return null;
        str = String(str).trim().split(' ')[0];
        const parts = str.split('-');
        if (parts.length !== 3) return null;
        return parseInt(parts[0] + parts[1].padStart(2,'0') + parts[2].padStart(2,'0'), 10);
    }

    // -------------------- فلتر التواريخ --------------------
    $.fn.dataTable.ext.search.push(function(settings, data) {
        if (settings.nTable.id !== 'testsTable') return true;

        const minVal = $('input[data-filter-type="date-from"]').val();
        const maxVal = $('input[data-filter-type="date-to"]').val();

        const minNum = minVal ? parseDateToNumber(minVal) : null;
        const maxNum = maxVal ? parseDateToNumber(maxVal) : null;
        const rowNum = parseDateToNumber(data[dateColumnIndex]);

        if (rowNum === null) return (minNum === null && maxNum === null);
        if (minNum === null && maxNum === null) return true;
        if (minNum === null && rowNum <= maxNum) return true;
        if (maxNum === null && rowNum >= minNum) return true;
        if (rowNum >= minNum && rowNum <= maxNum) return true;

        return false;
    });

    $('input[data-filter-type="date-from"], input[data-filter-type="date-to"]').on('change keyup', function() {
        testsTable.draw();
    });

    // -------------------- فلترة الأعمدة --------------------
    testsTable.columns().every(function() {
        const column = this;
        if (this.index() === 0 || this.index() === dateColumnIndex || this.index() === 8) return;
        $('input, select', this.header()).on('keyup change', function() {
            const val = $.fn.dataTable.util.escapeRegex($(this).val());
            if (column.search() !== val) column.search(val).draw();
        });
    });

    // -------------------- Select All --------------------
    $('#selectAllTests').on('change', function() {
        const rows = testsTable.rows({'search': 'applied'}).nodes();
        $('input.test-checkbox', rows).prop('checked', this.checked);
    });

    $('#testsTable tbody').on('change', 'input.test-checkbox', function() {
        const allChecked = $('.test-checkbox').length === $('.test-checkbox:checked').length;
        $('#selectAllTests').prop('checked', allChecked);
    });
});

// -------------------- Load Tests --------------------
function loadTests() {
    $.get('/tests', function(tests) {
        testsTable.clear();
        tests.forEach(t => {
            testsTable.row.add([
                `<input type="checkbox" class="test-checkbox" value="${t.id}">`,
                t.test_code || '',
                t.short_name || '',
                t.service_group || '',
                t.date_added || '',
                t.activity_type || '',
                t.element || '',
                t.unit_price || '',
                `<button class="btn btn-sm btn-primary" onclick="editTestModal(${t.id})">Edit</button>`
            ]);
        });
        testsTable.draw();
    });
}

// -------------------- Modals --------------------
function openAddTestModal() {
    $('#addTestForm')[0].reset();
    $('#testId').val('');
    $('#uncertaintyHistory tbody').empty();
    $('#addTestModal').show();
}

function closeAddTestModal() { $('#addTestModal').hide(); }

function closeEditTestModal() { $('#editTestModal').hide(); }
let tempUncertainties = [];


// -------------------- Save Test --------------------

function saveTest(closeAfterSave = true) {
    const valueInput = document.getElementById('value');
    const dateInput = document.getElementById('date_recorded');

    const uncertainties = [];
    if (valueInput && valueInput.value && dateInput && dateInput.value) {
        uncertainties.push({
            value: valueInput.value,
            date_recorded: dateInput.value
        });
    }

    const data = {
        short_name: document.getElementById('shortName').value,
        service_group: document.getElementById('serviceGroup').value,
        department: document.getElementById('department').value,
        generate_report: document.getElementById('generateReport').checked ? 1 : 0,
        description: document.getElementById('description').value,
        type: document.getElementById('type').value,
        activity_type: document.getElementById('activityType').value,
        date_added: document.getElementById('dateAdded').value,
        location: document.getElementById('location').value,
        test_method: document.getElementById('testMethod').value,
        template_name: document.getElementById('templateName').value,
        template_type: document.getElementById('templateType').value,
        file_template: document.getElementById('fileTemplate').value,
        report_designation: document.getElementById('reportDesignation').value,
        report_title: document.getElementById('reportTitle').value,
        built_in_template: document.getElementById('builtInTemplateType').value,
        element: document.getElementById('element').value,
        uncertainty: valueInput ? valueInput.value : null,
        use_uncertainty: document.getElementById('useUncertainty').checked ? 1 : 0,
        unit_price: document.getElementById('unitPrice').value,
        uncertainty_history: uncertainties,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    $.ajax({
        url: '/tests',
        type: 'POST',
        data: data,
        success: function(response) {
            Swal.fire('تم الحفظ ✅', response.message || 'تم حفظ الاختبار بنجاح', 'success');
            if (closeAfterSave) closeAddTestModal();
        },
        error: function(xhr) {
            Swal.fire('خطأ ❌', 'حدث خطأ أثناء حفظ الاختبار', 'error');
            console.error(xhr.responseText);
        }
    });
}





// -------------------- Edit Test --------------------
function editTestselecteModal() {
    let selected = $('.test-checkbox:checked'); // جميع الـ checkboxes المحددة

    if (selected.length === 0) {
        Swal.fire('⚠️', 'الرجاء اختيار اختبار واحد للتعديل', 'warning');
        return;
    }
    if (selected.length > 1) {
        Swal.fire('⚠️', 'الرجاء اختيار اختبار واحد فقط', 'warning');
        return;
    }

    let testId = selected.val();
    console.log(testId);
     // جلب id الاختبار
    editTestModal(testId);       // استدعاء دالة التعديل الموجودة
}


 function editTestModal(id) {
    $.get(`/tests/${id}`, function(test) {
        if (!test || !test.id) {
            Swal.fire('خطأ ❌','لم يتم العثور على بيانات الاختبار!','error');
            return;
        }

        // ✅ تعبئة الحقول الأساسية
        $('#editTestId').val(test.id);
        $('#editTestCode').val(test.test_code || ''); // Test Code
        $('#editShortName').val(test.short_name || '');
        $('#editServiceGroup').val(test.service_group || '');
        $('#editDepartment').val(test.department || '');
        $('#editGenerateReport').prop('checked', test.generate_report ? true : false);
        $('#editDescription').val(test.description || '');
        $('#editType').val(test.type || '');
        $('#editActivityType').val(test.activity_type || '');
        $('#editDateAdded').val(test.date_added || '');
        $('#editLocation').val(test.location || '');
        $('#editTestMethod').val(test.test_method || '');
        $('#editTemplateName').val(test.template_name || '');
        $('#editTemplateType').val(test.template_type || '');
        $('#editBuiltInTemplateType').val(test.built_in_template || ''); // ✅ built-in
        $('#editFileTemplate').val(test.file_template || '');
        $('#editReportDesignation').val(test.report_designation || '');
        $('#editReportTitle').val(test.report_title || '');
        $('#editElement').prop('checked', test.element ? true : false); // ✅ checkbox
        $('#editUseUncertainty').prop('checked', test.use_uncertainty ? true : false);
        $('#editUnitPrice').val(test.unit_price || 0);

        // ✅ جلب سجل الـ uncertainties
        loadUncertainties(test.id, 'edit');

        // ✅ فتح المودال
        $('#editTestModal').show();
    }).fail(function(err) {
        Swal.fire('خطأ ❌','حدث خطأ أثناء تحميل بيانات الاختبار','error');
    });
}

function saveEditTest(closeAfterSave) {
    const testId = $('#editTestId').val();
    if (!testId) {
        Swal.fire('خطأ ❌','لا يوجد معرف للاختبار للتعديل','error');
        return;
    }

    const formData = {
        short_name: $('#editShortName').val(),
        service_group: $('#editServiceGroup').val(),
        department: $('#editDepartment').val(),
        generate_report: $('#editGenerateReport').is(':checked') ? 1 : 0,
        description: $('#editDescription').val(),
        type: $('#editType').val(),
        activity_type: $('#editActivityType').val(),
        date_added: $('#editDateAdded').val(),
        location: $('#editLocation').val(),
        test_method: $('#editTestMethod').val(),
        template_name: $('#editTemplateName').val(),
        template_type: $('#editTemplateType').val(),
        file_template: $('#editFileTemplate').val(),
        report_designation: $('#editReportDesignation').val(),
        report_title: $('#editReportTitle').val(),
        built_in_template: $('#editBuiltInTemplateType').val(), // ✅ صحيح
        element: $('#editElement').is(':checked') ? 1 : 0, // ✅ checkbox
        uncertainty: $('#editUncertainty').val(), // ✅ آخر قيمة فقط
        use_uncertainty: $('#editUseUncertainty').is(':checked') ? 1 : 0,
        unit_price: $('#editUnitPrice').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: `/tests/${testId}`,
        type: 'PUT',
        data: formData,
        success: function() {
            Swal.fire('تم التعديل ✅','تم تعديل الاختبار بنجاح.','success');
            loadTests();
            if (closeAfterSave) closeEditTestModal();
        },
        error: function(xhr) {
            Swal.fire('خطأ ❌','حدث خطأ أثناء تعديل الاختبار','error');
        }
    });
}


// -------------------- Delete Selected Tests --------------------
function deleteSelectedTests() {
    const selectedIds = [];
    $('.test-checkbox:checked').each(function() { selectedIds.push($(this).val()); });
    if (!selectedIds.length) { Swal.fire('⚠️','يرجى اختيار اختبار واحد على الأقل!','warning'); return; }

    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: `سيتم حذف ${selectedIds.length} اختبار/اختبارات!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (!result.isConfirmed) return;
        $.ajax({
            url: '/tests/delete-multiple',
            type: 'POST',
            data: { ids: selectedIds, _token: $('meta[name="csrf-token"]').attr('content') },
            success: function() {
                Swal.fire('تم الحذف ✅','تم حذف الاختبارات المحددة بنجاح!','success');
                loadTests();
            },
            error: function() { Swal.fire('خطأ ❌','حدث خطأ أثناء حذف الاختبارات','error'); }
        });
    });
}

    // فتح مودال الإضافة
 function openAddTestModal(testId = null) {
    let form = document.getElementById('addTestForm');
    if (form) form.reset();
    document.getElementById('addTestModal').style.display = 'block';

    // تفريغ الجدول أولًا
    document.getElementById('uncertaintyHistory').getElementsByTagName('tbody')[0].innerHTML = '';

    // إذا كان testId موجود، حمل كل الـ Uncertainties
    if (testId) {
        loadUncertainties(testId, 'add'); // يمكن تعديل loadUncertainties لقبول مودال الإضافة أيضًا
    }
}
    // إغلاق مودال الإضافة
    function closeAddTestModal() {
        document.getElementById('addTestModal').style.display = 'none';
    }




    // إغلاق مودال التعديل
    function closeEditTestModal() {
        document.getElementById('editTestModal').style.display = 'none';
    }

     // ==========================
// جلب بيانات Uncertainties
// ==========================
function loadUncertainties(testId, mode = 'edit') {
    if (!testId) return;

    fetch(`/uncertainty-history/${testId}`)
    .then(res => res.json())
    .then(data => {
        const tableId = mode === 'edit' ? 'editUncertaintyHistory' : 'uncertaintyHistory';
        const table = document.getElementById(tableId).getElementsByTagName('tbody')[0];
        table.innerHTML = '';

        if (data.length > 0) {
            // ملء الجدول (الأحدث أولاً حسب الـ backend)
            data.forEach(u => {
                const row = table.insertRow();
                row.innerHTML = `
                    <td>${u.date_recorded}</td>
                    <td>${u.value}</td>
                `;
            });

            // أخذ أحدث قيمة (أول عنصر)
            let lastUncertainty = data[0];
            document.getElementById(mode === 'edit' ? 'editUncertainty' : 'uncertainty').value = lastUncertainty.value;
            document.getElementById(mode === 'edit' ? 'editUncertaintyDate' : 'uncertaintyDate').value = lastUncertainty.date_recorded;
        } else {
            // لو ما في بيانات، خليه يظهر التاريخ الحالي
            document.getElementById(mode === 'edit' ? 'editUncertainty' : 'uncertainty').value = '';
            document.getElementById(mode === 'edit' ? 'editUncertaintyDate' : 'uncertaintyDate').value = new Date().toISOString().split('T')[0];
        }
    })
    .catch(err => console.error('Error loading uncertainties:', err));
}




// ==========================
// إضافة Uncertainty جديدة
// ==========================


function addUncertaintyHistory(mode = 'edit') {
    const tableId = mode === 'edit' ? 'editUncertaintyHistory' : 'uncertaintyHistory';
    const testId = mode === 'edit' ? $('#editTestId').val() : $('#testId').val();
    const value = document.getElementById(mode === 'edit' ? 'editUncertainty' : 'uncertainty').value;
    const date = document.getElementById(mode === 'edit' ? 'editUncertaintyDate' : 'uncertaintyDate').value;

    if (!value || !date || !testId) return alert('يرجى إدخال القيمة، التاريخ وارتباط الاختبار');

    fetch('/uncertainty-history', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ test_id: testId, value, date_recorded: date })
    })
    .then(res => res.json())
    .then(() => loadUncertainties(testId, mode))
    .catch(err => console.error(err));
}


function toDateInput(value) {
    if (!value) return '';
    return value.split('T')[0].split(' ')[0];
}

function loadUncertainties(testId, mode = 'edit') {
    if (!testId) { console.warn('loadUncertainties: no testId'); return; }

    const url = `/uncertainty-history/${testId}`;
    console.log('Fetching', url);

    fetch(url)
    .then(res => {
        if (!res.ok) throw new Error('Network response not ok: ' + res.status);
        return res.json();
    })
    .then(data => {
        console.log('Uncertainty data:', data);
        const tableId = mode === 'edit' ? 'editUncertaintyHistory' : 'uncertaintyHistory';
        const tbody = document.querySelector(`#${tableId} tbody`);
        if (!tbody) { console.error('tbody not found for', tableId); return; }

        // إذا كانت DataTable مفعّلة
        if ($.fn.dataTable && $.fn.dataTable.isDataTable(`#${tableId}`)) {
            const dt = $(`#${tableId}`).DataTable();
            dt.clear();
            data.forEach(u => {
                const val = u.value ?? u.uncertainty ?? '';
                const date = toDateInput(u.date_recorded ?? u.recorded_at ?? u.date ?? '');
                dt.row.add([date, val]);
            });
            dt.draw();
        } else {
            tbody.innerHTML = '';
            data.forEach(u => {
                const val = u.value ?? u.uncertainty ?? '';
                const date = toDateInput(u.date_recorded ?? u.recorded_at ?? u.date ?? '');
                const row = tbody.insertRow();
                row.innerHTML = `<td>${date}</td><td>${val}</td>`;
                // عند النقر على الصف، ينسخ القيم إلى الحقول
                row.addEventListener('click', () => {
                    const valElem = document.getElementById(mode === 'edit' ? 'editUncertainty' : 'uncertainty');
                    const dateElem = document.getElementById(mode === 'edit' ? 'editUncertaintyDate' : 'uncertaintyDate');
                    if (valElem) valElem.value = val;
                    if (dateElem) dateElem.value = date;
                });
            });
        }

        // ملء الحقول بأحدث سجل (إذا موجود)
        if (data.length > 0) {
            const u0 = data[0];
            const val0 = u0.value ?? u0.uncertainty ?? '';
            const date0 = toDateInput(u0.date_recorded ?? u0.recorded_at ?? u0.date ?? '');
            const valElem = document.getElementById(mode === 'edit' ? 'editUncertainty' : 'uncertainty');
            const dateElem = document.getElementById(mode === 'edit' ? 'editUncertaintyDate' : 'uncertaintyDate');
            if (valElem) valElem.value = val0;
            if (dateElem) dateElem.value = date0;
        } else {
            const valElem = document.getElementById(mode === 'edit' ? 'editUncertainty' : 'uncertainty');
            const dateElem = document.getElementById(mode === 'edit' ? 'editUncertaintyDate' : 'uncertaintyDate');
            if (valElem) valElem.value = '';
            if (dateElem) dateElem.value = new Date().toISOString().split('T')[0];
        }
    })
    .catch(err => console.error('Error loading uncertainties:', err));
}

  window.goToProjectFiles  = function() {
    let selectedIds = getSelectedTestIds();

    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه ⚠️',
            text: 'يرجى اختيار اختبار واحد!',
            confirmButtonText: 'حسناً'
        });
        return;
    }

    if (selectedIds.length > 1) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه ⚠️',
            text: 'يرجى اختيار اختبار واحد فقط!',
            confirmButtonText: 'حسناً'
        });
        return;
    }

    // التوجيه لصفحة ملفات الاختبار
    let testId = selectedIds[0];
    window.location.href = `/tests/${testId}/files`;
};

// دالة مساعدة للحصول على الـ IDs المحددة من جدول الاختبارات
window.getSelectedTestIds = function() {
    const testsTable = $('#testsTable').DataTable();
    const selectedCheckboxes = testsTable.$('input[type="checkbox"]:checked');
    const ids = [];

    selectedCheckboxes.each(function() {
        ids.push($(this).val()); // تأكد أن value للـ checkbox يحتوي على test id
    });

    return ids;
};

   // ===================== طباعة جدول الاختبارات =====================
window.printTestsTable = function() {
    const testsTableElement = document.getElementById('testsTable');
    if (!testsTableElement) {
        showAlert('⚠️ لم يتم العثور على جدول الاختبارات.', 'warning');
        return;
    }

    const testsTable = $(testsTableElement).DataTable();
    const selectedCheckboxes = testsTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;

    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = testsTable.rows({ 'search': 'applied' }).nodes();
    }

    let printContents = `
        <style>
            .testsTable { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
            body { font-family: 'Arial', sans-serif; }
            h2 { text-align: center; margin-bottom: 20px; }
        </style>
        <h2>قائمة الاختبارات</h2>
        <table>
            <thead><tr>`;

    $(testsTableElement).find('thead tr:first th').each(function() {
        if ($(this).find('input[type="checkbox"]').length > 0 || $(this).text().trim() === '') return;
        const headerClone = $(this).clone();
        headerClone.find('input, select, .btn, i').remove();
        const columnTitle = headerClone.text().trim();
        printContents += '<th>' + columnTitle + '</th>';
    });

    printContents += `</tr></thead><tbody>`;

    $(rowsToProcess).each(function() {
        printContents += '<tr>';
        $(this).find('td').each(function() {
            if ($(this).find('input[type="checkbox"]').length > 0 || $(this).find('.icon-toolbar').length > 0) return;
            printContents += '<td>' + $(this).text().trim() + '</td>';
        });
        printContents += '</tr>';
    });

    printContents += `</tbody></table>`;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContents);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();

    showAlert('تم إرسال أمر الطباعة بنجاح.', 'success');
};

// ===================== تصدير جدول الاختبارات إلى Excel =====================
window.exportTestsExcel = function() {
    const testsTableElement = document.getElementById('testsTable');
    if (!testsTableElement) {
        showAlert('⚠️ لم يتم العثور على جدول الاختبارات.', 'warning');
        return;
    }

    const testsTable = $(testsTableElement).DataTable();
    const selectedCheckboxes = testsTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;

    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = testsTable.rows({ 'search': 'applied' }).nodes();
    }

    const data = [];
    const header = [];

    $(testsTableElement).find('thead th').each(function() {
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
    XLSX.utils.book_append_sheet(wb, ws, "الاختبارات");

    XLSX.writeFile(wb, "tests-data.xlsx");

    showAlert('تم تصدير البيانات بنجاح إلى ملف Excel.', 'success');
};


function viewTestFile(fileId) {
    window.open(`/tests/files/${fileId}/view`, '_blank');
}

</script>
@endsection
