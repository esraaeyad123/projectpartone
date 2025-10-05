@extends('layouts.app')
@section('title', __('Equipments'))
@section('content')
@include('equipments.create')

<main class="main-content">
    <section id="equipments-section" class="section-content active">
        <div class="icon-toolbar">
            <div>
                <button title="Add" onclick="openEquipmentModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                <button title="Edit" onclick="editSelectedEquipment()" class="btn btn-warning"><i class="fas fa-pen"></i></button>
                <button title="Delete" onclick="deleteSelectedEquipments()" class="btn-icon"><i class="fas fa-trash"></i></button>
            </div>
            <div class="icon-separator"></div>
            <div>
                <button title="File Manager" onclick="goToEquipmentFiles()" class="btn-icon"><i class="fas fa-folder-open"></i></button>
                <button title="Export to Excel" onclick="exportEquipmentsExcel()" class="btn-icon"><i class="fa-solid fa-table"></i></button>
                <button title="Print" onclick="printEquipmentsTable()" class="btn-icon"><i class="fas fa-print"></i></button>
            </div>
        </div>

        <div class="table-responsive-container">
            <table id="equipmentsTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAllEquipments">
                        </th>
                        <th>
                            Equipment ID<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Description<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Serial #<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Model<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Manufacturer<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Department<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Custodian<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Location<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Date Registered<br>
                            <span>From:</span><input type="text" class="column-filter date-range-filter flatpickr-input" data-filter-type="date-from">
                            <span>To:</span><input type="text" class="column-filter date-range-filter flatpickr-input" data-filter-type="date-to">
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
/****************************
 * Alerts
 ****************************/
function showAlert(message, type) {
    Swal.fire({
        title: type === 'success' ? 'Success!' : (type === 'error' ? 'Error!' : 'Warning!'),
        text: message,
        icon: type,
        confirmButtonText: 'OK'
    });
}

/****************************
 * Confirm
 ****************************/
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
        if (result.isConfirmed) callback();
    });
}

/****************************
 * DataTable تجهيز جدول المعدات
 ****************************/
$(document).ready(function() {
    var dateColumnIndex = 9; // العمود العاشر (تاريخ التسجيل)

    flatpickr(".flatpickr-input", { dateFormat: "Y-m-d", locale: "en", allowInput: true });

    window.equipmentsTable = $('#equipmentsTable').DataTable({
        responsive: true,
        autoWidth: false,
        processing: true,
        scrollX: true,
        columnDefs: [{ orderable: false, targets: [0] }]
    });

    loadEquipments();

    function parseDateToNumber(str) {
        if (!str) return null;
        str = String(str).trim().split(' ')[0];
        var parts = str.split('-');
        if (parts.length !== 3) return null;
        return parseInt(parts[0] + parts[1].padStart(2, '0') + parts[2].padStart(2, '0'), 10);
    }

    // فلترة التاريخ
    $.fn.dataTable.ext.search.push(function(settings, data) {
        if (settings.nTable.id !== 'equipmentsTable') return true;
        var minVal = $('input[data-filter-type="date-from"]').val();
        var maxVal = $('input[data-filter-type="date-to"]').val();
        var minNum = minVal ? parseDateToNumber(minVal) : null;
        var maxNum = maxVal ? parseDateToNumber(maxVal) : null;
        var rowNum = parseDateToNumber(data[dateColumnIndex]);
        if (rowNum === null) return (minNum === null && maxNum === null);
        if (minNum === null && rowNum <= maxNum) return true;
        if (maxNum === null && rowNum >= minNum) return true;
        if (rowNum >= minNum && rowNum <= maxNum) return true;
        return false;
    });

    $('input[data-filter-type="date-from"], input[data-filter-type="date-to"]').on('change keyup', function() {
        equipmentsTable.draw();
    });
});

/****************************
 * تحميل بيانات المعدات
 ****************************/
function loadEquipments() {
    $.get('/equipments', function(equipments) {
        equipmentsTable.clear();
        equipments.forEach(eq => {
            equipmentsTable.row.add([
                `<input type="checkbox" class="equipment-checkbox" value="${eq.id}">`,
                eq.equipment_reference || '',
                eq.description || '',
                eq.serial_number || '',
                eq.model || '',
                eq.make || '',
                eq.department || '',
                eq.custodian || '',
                eq.location || '',
                eq.registration_date || '',
            ]);
        });
        equipmentsTable.draw();
    });
}

/****************************
 * تحديد الكل Checkboxes
 ****************************/
$('#selectAllEquipments').on('change', function() {
    let rows = equipmentsTable.rows({ 'search': 'applied' }).nodes();
    $('input.equipment-checkbox', rows).prop('checked', this.checked);
});

$('#equipmentsTable tbody').on('change', 'input.equipment-checkbox', function() {
    let allChecked = $('.equipment-checkbox').length === $('.equipment-checkbox:checked').length;
    $('#selectAllEquipments').prop('checked', allChecked);
});

/****************************
 * حذف جهاز واحد
 ****************************/
function deleteEquipment(id) {
    showConfirm("سيتم حذف الجهاز ولا يمكنك التراجع عن هذا الإجراء!", function() {
        $.ajax({
            url: `/equipments/${id}`,
            type: 'DELETE',
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function() { showAlert('تم حذف الجهاز بنجاح ✅', 'success'); loadEquipments(); },
            error: function(xhr) { console.error(xhr.responseText); showAlert('خطأ أثناء حذف الجهاز', 'error'); }
        });
    }, "هل أنت متأكد؟");
}

/****************************
 * حذف أجهزة متعددة
 ****************************/
function getSelectedEquipmentIds() {
    let ids = [];
    $('.equipment-checkbox:checked').each(function() { ids.push($(this).val()); });
    return ids;
}

function deleteSelectedEquipments() {
    let ids = getSelectedEquipmentIds();
    if (ids.length === 0) return showAlert('الرجاء اختيار جهاز واحد على الأقل', 'warning');

    showConfirm(`سيتم حذف ${ids.length} جهاز/أجهزة!`, function() {
        $.ajax({
            url: `/equipments/bulk-delete`,
            type: 'POST',
            data: { _token: $('meta[name="csrf-token"]').attr('content'), ids: ids },
            success: function() { showAlert('تم حذف الأجهزة المحددة ✅', 'success'); loadEquipments(); },
            error: function(xhr) { console.error(xhr.responseText); showAlert('خطأ أثناء حذف الأجهزة', 'error'); }
        });
    }, "هل أنت متأكد؟");
}

/****************************
 * فتح / إغلاق Modals
 ****************************/
function openEquipmentModal() { document.getElementById('equipmentModal').style.display = 'block'; }
function closeEquipmentModal() { document.getElementById('equipmentModal').style.display = 'none'; }
function openEditEquipmentModal() { document.getElementById('editEquipmentModal').style.display = 'block'; }
function closeEditEquipmentModal() { document.getElementById('editEquipmentModal').style.display = 'none'; }

/****************************
 * تعديل المعدات
 ****************************/
// ======================= فتح مودال تعديل الجهاز =======================
// --- فتح مودال تعديل الجهاز وتعبئة البيانات ---
function editEquipmentModal(id) {
    if (!id) return console.warn('No Equipment ID provided');

     if (!id) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه ⚠️',
            text: 'يرجى اختيار جهاز واحد!',
            confirmButtonText: 'حسناً'
        });
        return;
    }

    $.get(`/equipments/${id}`, function(equipment) {
        if (!equipment || !equipment.id) return showAlert('لم يتم العثور على بيانات الجهاز!', 'error');

        // --- تعبئة بيانات Equipment ---
        $('#editEquipmentId').val(equipment.id);
        $('#edit_equipment_reference').val(equipment.equipment_reference);
        $('#edit_description').val(equipment.description);
        $('#edit_serial_number').val(equipment.serial_number);
        $('#edit_legacy_id').val(equipment.legacy_id);
        $('#edit_alternative_id').val(equipment.alternative_id);
        $('#edit_asset_tag').val(equipment.asset_tag);
        $('#edit_size').val(equipment.size || '');
        $('#edit_type').val(equipment.type);
        $('#edit_make').val(equipment.make);
        $('#edit_model').val(equipment.model);
        $('#edit_tolerance_basis').val(equipment.tolerance_basis);
        $('#edit_tolerance').val(equipment.tolerance);
        $('#edit_range_capacity').val(equipment.range_capacity);
        $('#edit_range_unit').val(equipment.range_unit);
        $('#edit_resolution').val(equipment.resolution);
        $('#edit_resolution_unit').val(equipment.resolution_unit);
        $('#edit_traceability').val(equipment.traceability);
        $('#edit_display_type').val(equipment.display_type);
        $('#edit_manufacturer_id').val(equipment.manufacturer_id);
        $('#edit_department').val(equipment.department);
        $('#edit_custodian').val(equipment.custodian);
        $('#edit_location').val(equipment.location);
        $('#edit_project_id').val(equipment.project_id);
        $('#edit_master_equipment').prop('checked', equipment.master_equipment ? true : false);
        $('#edit_equipment_status').val(equipment.equipment_status || '');
        $('#edit_uncertainty_unit').val(equipment.uncertainty_unit || '');
        $('#edit_io').val(equipment.io || '');
        $('#edit_uncertainty').val(equipment.uncertainty || '');

        // --- تحميل سجلات Uncertainty ---
        loadUncertainties(equipment.id, 'edit');

        // --- Calibration ---
        if(equipment.calibration) {
            $('#edit_calib_method').val(equipment.calibration.calib_method);
            $('#edit_calib_procedure_no').val(equipment.calibration.calib_procedure_no);
            $('#edit_last_calib_date').val(equipment.calibration.last_calib_date);
            $('#edit_scheduled').val(equipment.calibration.scheduled);
            $('#edit_next_calib_date').val(equipment.calibration.next_calib_date);
            $('#edit_reminder').prop('checked', equipment.calibration.reminder);
            $('#edit_has_next_calibration').prop('checked', equipment.calibration.has_next_calibration);
            $('#edit_calibrated_externally').prop('checked', equipment.calibration.calibrated_externally);
            $('#edit_only_one').prop('checked', equipment.calibration.only_one);
        }

        // --- Maintenance ---
        if(equipment.maintenance) {
            $('#edit_last_maint_date').val(equipment.maintenance.last_maint_date);
            $('#edit_scheduled_date').val(equipment.maintenance.scheduled_date);
            $('#edit_next_maint_date').val(equipment.maintenance.next_maint_date);
            $('#edit_scheduled_maint').prop('checked', equipment.maintenance.scheduled_maint);
            $('#edit_has_next_maint').prop('checked', equipment.maintenance.has_next_maint);
            $('#edit_reminder_maint').prop('checked', equipment.maintenance.reminder_maint);
            $('#edit_maint_externally').prop('checked', equipment.maintenance.maint_externally);
            $('#edit_only_one_maint').prop('checked', equipment.maintenance.only_one);
            $('#edit_occurrence').val(equipment.maintenance.occurrence);
            $('#edit_maint_provider').val(equipment.maintenance.maint_provider);
            $('#edit_maint_internally_by').val(equipment.maintenance.maint_internally_by);
            $('#edit_maint_status').val(equipment.maintenance.maint_status);
        }

        openEditEquipmentModal();
    }).fail(function(err) {
        console.error(err);
        showAlert('خطأ أثناء تحميل بيانات الجهاز', 'error');
    });
}


// --- حفظ التعديلات ---
function saveEditEquipment(closeAfter = false) {
    const equipmentId = $('#editEquipmentId').val();
    if (!equipmentId) return showAlert('لا يوجد جهاز محدد للحفظ!', 'error');

    // جمع بيانات الفورم
    const formData = {
        _token: $('input[name="_token"]').val(),
        _method: 'PUT', // لأننا نستخدم RESTful update
        equipment_reference: $('#edit_equipment_reference').val(),
        alternative_id: $('#edit_alternative_id').val(),
        legacy_id: $('#edit_legacy_id').val(),
        description: $('#edit_description').val(),
        serial_number: $('#edit_serial_number').val(),
        asset_tag: $('#edit_asset_tag').val(),
        size: $('#edit_size').val(),
        type: $('#edit_type').val(),
        make: $('#edit_make').val(),
        model: $('#edit_model').val(),
        tolerance_basis: $('#edit_tolerance_basis').val(),
        tolerance: $('#edit_tolerance').val(),
        range_capacity: $('#edit_range_capacity').val(),
        range_unit: $('#edit_range_unit').val(),
        resolution: $('#edit_resolution').val(),
        resolution_unit: $('#edit_resolution_unit').val(),
        traceability: $('#edit_traceability').val(),
        display_type: $('#edit_display_type').val(),
        manufacturer_id: $('#edit_manufacturer_id').val(),
        department: $('#edit_department').val(),
        custodian: $('#edit_custodian').val(),
        location: $('#edit_location').val(),
        project_id: $('#edit_project_id').val(),
        master_equipment: $('#edit_master_equipment').prop('checked') ? 1 : 0,
        equipment_status: $('#edit_equipment_status').val(),
        uncertainty: $('#edit_uncertainty').val(),
        uncertainty_unit: $('#edit_uncertainty_unit').val(),
        io: $('#edit_io').val(),

        // Calibration

    };

    $.ajax({
        url: `/equipments/${equipmentId}`,
        method: 'POST',
        data: formData,
     success: function(response) {
    Swal.fire('تم الحفظ ✅', response.message || 'تم حفظ المعدة بنجاح', 'success');

    // تحديث جدول الـ DataTable
    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#equipmentsTable')) {
        $('#equipmentsTable').DataTable().ajax.reload(null, false); // false = لا يُعيد الصفحة للصف الأول
    }

    if (closeAfter) closeEditEquipmentModal();
},
        error: function(err) {
            console.error(err);
            showAlert('حدث خطأ أثناء حفظ البيانات', 'error');
        }
    });
}


// ==========================
// تحويل التاريخ لتنسيق input[type="date"]
// ==========================
function toDateInput(value) {
    if (!value) return '';
    return value.split('T')[0].split(' ')[0];
}

// ==========================
// تحميل سجلات Uncertainty
// ==========================
function loadUncertainties(equipmentId, mode = 'edit') {
    if (!equipmentId) return;

    const tableId = mode === 'edit' ? 'editUncertaintyHistory' : 'uncertaintyHistory';
    const valueField = mode === 'edit' ? 'editUncertainty' : 'uncertainty';
    const dateField = mode === 'edit' ? 'editUncertaintyDate' : 'uncertaintyDate';

    fetch(`/equipments/${equipmentId}`)
        .then(res => res.json())
        .then(data => {
            const records = data.equipment_uncertainties || [];
            const tbody = document.querySelector(`#${tableId} tbody`);
            tbody.innerHTML = '';

            if (records.length > 0) {
                records.forEach(u => {
                    const row = tbody.insertRow();
                    row.innerHTML = `<td>${u.date}</td><td>${u.uncertainty}</td>`;
                    row.addEventListener('click', () => {
                        document.getElementById(valueField).value = u.uncertainty;
                        document.getElementById(dateField).value = u.date;
                    });
                });

                // تعبئة الحقول بأحدث سجل (أول عنصر)
                document.getElementById(valueField).value = records[0].uncertainty;
                document.getElementById(dateField).value = records[0].date;
            } else {
                // لو لا توجد سجلات
                document.getElementById(valueField).value = '';
                document.getElementById(dateField).value = new Date().toISOString().split('T')[0];
                tbody.innerHTML = `<tr><td colspan="2">لا يوجد سجلات</td></tr>`;
            }
        })
        .catch(err => console.error('Error loading uncertainties:', err));
}

// ==========================
// إضافة سجل جديد
// ==========================
function addUncertainty(equipmentId, mode = 'edit') {
    if (!equipmentId) return;

    const valueField = mode === 'edit' ? 'editUncertainty' : 'uncertainty';
    const dateField = mode === 'edit' ? 'editUncertaintyDate' : 'uncertaintyDate';

    const value = document.getElementById(valueField).value;
    const date = document.getElementById(dateField).value;

    if (!value || !date) return alert('يرجى إدخال القيمة والتاريخ');

fetch(`/equipments/${equipmentId}/uncertainty-history`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({ uncertainty: value, date })
})
.then(res => res.json())
.then(() => loadUncertainties(equipmentId, mode))
.catch(err => console.error(err));

}

// --- تبديل التابات ---
function switchEditTab(tabId) {
    $('.form-tab-content').hide();
    $('#' + tabId).show();
    $('.tab-buttons button').removeClass('active');
    $(`.tab-buttons button[onclick*="${tabId}"]`).addClass('active');
}





/****************************
 * اختيار جهاز للتعديل من القائمة
 ****************************/
function editSelectedEquipment() {
    let selected = $('.equipment-checkbox:checked');

      if (selected.length === 0) {
        Swal.fire('تنبيه ⚠️', 'يرجى اختيار اختبار واحد للتعديل', 'warning');
        return;
    }

    if (selected.length > 1) {
        Swal.fire('تنبيه ⚠️', 'يمكنك تعديل جهاز واحد فقط في كل مرة', 'warning');
        return;
    }
    // لو جهاز واحد فقط مختار
    editEquipmentModal(selected.val());
}


function saveEquipment(closeAfterSave = true) {
    // الخانات الخاصة بالـ uncertainty
    const valueInput = document.getElementById('value');
    const dateInput = document.getElementById('date_recorded');

    const uncertainties = [];
    if (valueInput && valueInput.value && dateInput && dateInput.value) {
        uncertainties.push({
            value: valueInput.value,
            date_recorded: dateInput.value
        });
    }

    // بيانات الفورم
    const data = {
        alternative_id: document.querySelector('[name="alternative_id"]').value,
        legacy_id: document.querySelector('[name="legacy_id"]').value,
        description: document.querySelector('[name="description"]').value,
        serial_number: document.querySelector('[name="serial_number"]').value,
        asset_tag: document.querySelector('[name="asset_tag"]').value,
        size: document.querySelector('[name="size"]').value,
        type: document.querySelector('[name="type"]').value,
        make: document.querySelector('[name="make"]').value,
        model: document.querySelector('[name="model"]').value,
        tolerance_basis: document.querySelector('[name="tolerance_basis"]').value,
        tolerance: document.querySelector('[name="tolerance"]').value,
        range_capacity: document.querySelector('[name="range_capacity"]').value,
        range_unit: document.querySelector('[name="range_unit"]').value,
        resolution: document.querySelector('[name="resolution"]').value,
        resolution_unit: document.querySelector('[name="resolution_unit"]').value,
        traceability: document.querySelector('[name="traceability"]').value,
        display_type: document.querySelector('[name="display_type"]').value,
        manufacturer_id: document.querySelector('[name="manufacturer_id"]').value,
        department: document.querySelector('[name="department"]').value,
        custodian: document.querySelector('[name="custodian"]').value,
        location: document.querySelector('[name="location"]').value,
        uncertainty: document.querySelector('[name="uncertainty"]').value,
        uncertainty_unit: document.querySelector('[name="uncertainty_unit"]').value,
        io: document.querySelector('[name="io"]').value,
        master_equipment: document.querySelector('[name="master_equipment"]').checked ? 1 : 0,
        equipment_status: document.querySelector('[name="equipment_status"]').value,
        project_id: document.querySelector('[name="project_id"]').value,

        // التاريخ + القيم
        uncertainty_history: uncertainties,

        // CSRF token
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };

    // AJAX
    $.ajax({
        url: '/equipments',
        type: 'POST',
        data: data,
        success: function(response) {
            Swal.fire('تم الحفظ ✅', response.message || 'تم حفظ المعدة بنجاح', 'success');
            if (closeAfterSave) closeAddEquipmentModal();
        },
        error: function(xhr) {
            Swal.fire('خطأ ❌', 'حدث خطأ أثناء حفظ بيانات المعدة', 'error');
            console.error(xhr.responseText);
        }
    });
}

// ================== حذف جهاز واحد ==================
function deleteEquipment(id) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "سيتم حذف الجهاز ولا يمكنك التراجع عن هذا الإجراء!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/equipments/${id}`,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الحذف ✅',
                        text: 'تم حذف الجهاز بنجاح.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadEquipments(); // إعادة تحميل الجدول بعد الحذف
                },
                error: function(xhr) {
                    console.error("Error deleting equipment:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ ❌',
                        text: 'حدث خطأ أثناء محاولة حذف الجهاز.',
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
}

// ================== جلب الأجهزة المحددة ==================
function getSelectedEquipmentIds() {
    let ids = [];
    $('.equipment-checkbox:checked').each(function() {
        ids.push($(this).val());
    });
    return ids;
}

// ================== حذف الأجهزة المحددة ==================
function deleteSelectedEquipments() {
    let ids = getSelectedEquipmentIds();
    if (ids.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'لم يتم تحديد أي جهاز',
            text: 'الرجاء اختيار جهاز واحد على الأقل للحذف.',
            confirmButtonText: 'حسناً'
        });
        return;
    }

    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: `سيتم حذف ${ids.length} جهاز/أجهزة ولا يمكنك التراجع عن هذا الإجراء!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/equipments/bulk-delete`,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: ids
                },
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الحذف ✅',
                        text: 'تم حذف الأجهزة المحددة بنجاح.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadEquipments();
                },
                error: function(xhr) {
                    console.error("Error deleting equipments:", xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ ❌',
                        text: 'حدث خطأ أثناء محاولة حذف الأجهزة.',
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        }
    });
}

// ===================== فتح صفحة ملفات المعدات =====================
window.goToEquipmentFiles = function() {
    let selectedIds = getSelectedEquipmentIds();

    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه ⚠️',
            text: 'يرجى اختيار جهاز واحد!',
            confirmButtonText: 'حسناً'
        });
        return;
    }

    if (selectedIds.length > 1) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه ⚠️',
            text: 'يرجى اختيار جهاز واحد فقط!',
            confirmButtonText: 'حسناً'
        });
        return;
    }

    // التوجيه لصفحة ملفات الجهاز
    let equipmentId = selectedIds[0];
    window.location.href = `/equipments/${equipmentId}/files`;
};

// ===================== دالة مساعدة للحصول على IDs المحددة من جدول المعدات =====================
window.getSelectedEquipmentIds = function() {
    const equipmentsTable = $('#equipmentsTable').DataTable();
    const selectedCheckboxes = equipmentsTable.$('input[type="checkbox"]:checked');
    const ids = [];

    selectedCheckboxes.each(function() {
        ids.push($(this).val()); // تأكد أن value للـ checkbox يحتوي على equipment id
    });

    return ids;
};

// ===================== طباعة جدول المعدات =====================
window.printEquipmentsTable = function() {
    const equipmentsTableElement = document.getElementById('equipmentsTable');
    if (!equipmentsTableElement) {
        showAlert('⚠️ لم يتم العثور على جدول المعدات.', 'warning');
        return;
    }

    const equipmentsTable = $(equipmentsTableElement).DataTable();
    const selectedCheckboxes = equipmentsTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;

    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = equipmentsTable.rows({ 'search': 'applied' }).nodes();
    }

    let printContents = `
        <style>
            .equipmentsTable { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
            body { font-family: 'Arial', sans-serif; }
            h2 { text-align: center; margin-bottom: 20px; }
        </style>
        <h2>قائمة المعدات</h2>
        <table>
            <thead><tr>`;

    $(equipmentsTableElement).find('thead tr:first th').each(function() {
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

// ===================== تصدير جدول المعدات إلى Excel =====================
window.exportEquipmentsExcel = function() {
    const equipmentsTableElement = document.getElementById('equipmentsTable');
    if (!equipmentsTableElement) {
        showAlert('⚠️ لم يتم العثور على جدول المعدات.', 'warning');
        return;
    }

    const equipmentsTable = $(equipmentsTableElement).DataTable();
    const selectedCheckboxes = equipmentsTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;

    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = equipmentsTable.rows({ 'search': 'applied' }).nodes();
    }

    const data = [];
    const header = [];

    $(equipmentsTableElement).find('thead th').each(function() {
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
    XLSX.utils.book_append_sheet(wb, ws, "المعدات");

    XLSX.writeFile(wb, "equipments-data.xlsx");

    showAlert('تم تصدير البيانات بنجاح إلى ملف Excel.', 'success');
};

// ===================== عرض ملف الجهاز =====================
function viewEquipmentFile(fileId) {
    window.open(`/equipments/files/${fileId}/view`, '_blank');
}





</script>


@endsection
