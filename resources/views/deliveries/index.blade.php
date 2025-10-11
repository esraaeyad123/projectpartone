@extends('layouts.app')
@section('title', __('Deliveries'))
@section('content')
@include('deliveries.create') <!-- المودال -->

<main class="main-content">
    <section id="deliveries-section" class="section-content active">

        <!-------------------------------------------Start Buttons-------------------------------------------->
        <div class="icon-toolbar">
            <div>
                <button title="Add Invoice" onclick="openConfModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                <button title="Edit Invoice" onclick="openEditDeliveriesModal()" class="btn-icon"><i class="fas fa-pen"></i></button>
                <button title="Delete" onclick="deleteSelectedConfirmation()" class="btn-icon"><i class="fas fa-trash"></i></button>

                <button title="Approve Delivery" onclick="approveDelivery()" class="btn-icon btn-approve" style="color: #28a745;">
                    <i class="fas fa-check-circle"></i>
                </button>


                

                <button title="Preview" onclick="previewDocument()" class="btn-icon"><i class="fas fa-eye"></i></button>
                <button title="Send to Customer" onclick="sendToCustomer()" class="btn-icon"><i class="fas fa-envelope"></i></button>
            </div>

            <div class="icon-separator"></div>

            <div>
                <button title="Convert to PDF" class="btn-icon" onclick="exportToPdfBtn()"><i class="fas fa-file-pdf"></i></button>
                <button title="Export to Excel" class="btn-icon" onclick="exportDeliveriesExcelBtn()"><i class="fa-solid fa-table"></i></button>
                <button title="Print" class="btn-icon" onclick="printDeliveriesTable()"><i class="fas fa-print"></i></button>
            </div>
        </div>
        <!-------------------------------------------End Buttons----------------------------------------------->
        <!-------------------------------------------Start confirmationTable----------------------------------------------->
        <div class="table-responsive-container">
            <table id="deliveriesTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllDeliveries"></th>

                        <th>Delivery No.<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Delivery Date<br><input type="text" class="column-filter date-range-filter" data-filter-type="date-from"></th>
                        <th>Status<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Department<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Prepared By<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Delivered By<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Project Code<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Project Name<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Customer Name<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>P. Number<br><input type="text" class="column-filter" placeholder="Search...">
                        </th>
                         <th>Recovered By<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Date Received<br><input type="text" class="column-filter date-range-filter" data-filter-type="date-from"></th>
                        <th>Contract Name<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Contact Title<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Items<br><input type="text" class="column-filter" placeholder="Search..."></th>

                    </tr>
                </thead>
                <tbody>
                    </tbody>
            </table>
        </div>
        <!-------------------------------------------End confirmationTable------------------------------------------------->
    </section>
</main>

<!-- ================================== JS Libraries ================================== -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- ================================== JS Libraries ================================== -->

<script>


//====================================== Start Script =======================================
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

    /**
     * * تستدعي هذه الدالة عند الضغط على زر "Send to Customer".
     * تعرض رسالة تحذيرية بأن الخدمة غير متوفرة حالياً.
     * */
    function sendToCustomer() {

        Swal.fire({
                title: "تنبيه",
                text: "خدمة الإرسال إلى العميل غير متوفرة حالياً",
                icon: "info",
                confirmButtonText: "حسناً"
            });
    }
//-------------------------------------------------------------------------------------------
$(document).ready(function() {

    // ===============================
    // 1️⃣ تهيئة جدول Deliveries
    // ===============================
    window.deliveriesTable = $('#deliveriesTable').DataTable({
        responsive: true,
        scrollX: true,
        autoWidth: false,
        columnDefs: [
            { orderable: false, targets: [0] } // عمود الشيكبوكس لا يقبل الفرز
        ],
        columns: [
            { data: 'checkbox', orderable: false, searchable: false }, // ✅ خانة التحديد
            { data: 'delivery_no' },
            { data: 'delivery_date' },
            { data: 'status' },
            { data: 'department' },
            { data: 'prepared_by' },
            { data: 'delivered_by' },
            { data: 'project_code' },
            { data: 'project_name' },
            { data: 'customer_name' },
            { data: 'p_number' },
            { data: 'recovered_by' },
            { data: 'date_received' },
            { data: 'contract_name' },
            { data: 'contact_title' },
            { data: 'items' }
        ]
    });

    // ===============================
    // 2️⃣ تحميل البيانات من السيرفر
    // ===============================
    loadDeliveries();

    // ===============================
    // 3️⃣ تحديد الكل / إلغاء الكل
    // ===============================
    $('#selectAllDeliveries').on('change', function() {
        let rows = deliveriesTable.rows({ 'search': 'applied' }).nodes();
        $('input.delivery-checkbox', rows).prop('checked', this.checked);
    });

    $('#deliveriesTable tbody').on('change', 'input.delivery-checkbox', function() {
        let allChecked = $('.delivery-checkbox').length === $('.delivery-checkbox:checked').length;
        $('#selectAllDeliveries').prop('checked', allChecked);
    });
});


// ===============================
// 4️⃣ دالة جلب البيانات
// ===============================
function loadDeliveries() {
    $.ajax({
        url: '/deliveries',
        type: 'GET',
        dataType: 'json',
        success: function(deliveries) {
            deliveriesTable.clear();

            deliveries.forEach(d => {
                deliveriesTable.row.add({
                    checkbox: `<input type="checkbox" class="delivery-checkbox" value="${d.id}">`,
                    delivery_no: d.delivery_no ?? '',
                    delivery_date: d.delivery_date ?? '',
                    status: d.status ?? '',
                    department: d.department ?? '',
                    prepared_by: d.prepared_by ?? '',
                    delivered_by: d.delivered_by ?? '',
                    project_code: d.project_code ?? (d.project?.code ?? ''),
                    project_name: d.project_name ?? (d.project?.name ?? ''),
                    customer_name: d.customer_name ?? (d.customer?.customer_name ?? ''),
                    p_number: d.p_number ?? '',
                    recovered_by: d.recovered_by ?? '',
                    date_received: d.date_received ?? '',
                    contract_name: d.contract_name ?? '',
                    contact_title: d.contact_title ?? '',
                    items: d.items ?? ''
                });
            });

            deliveriesTable.draw();
        },
        error: function(xhr, status, error) {
            console.error('Error loading deliveries:', error);
            Swal.fire('خطأ', 'حدث خطأ أثناء تحميل البيانات من السيرفر', 'error');
        }
    });
}

//-------------------------------------------------------------------------------------------
    function openConfModal() {
        // 💡 التعديل: تصفير النموذج الخاص بالتعميد
        $('#confForm')[0].reset();

        // 💡 التعديل: مسح قيمة مُعرِّف التعميد (إذا كان يستخدم للتعديل)
        $('#confId').val('');

        // 💡 التعديل: إظهار النافذة المنبثقة الخاصة بالتعميد
        $('#confModal').show();
    }
//-------------------------------------------------------------------------------------------
    function closeModal(modalId) {
        $('#' + modalId).hide();
    }
// ----------------------------------------------------------------------------------------
    let servicesTableInitialized = false;
    let servicesTableEditInitialized = false;

    function switchTab(tabName) {
        // إخفاء كل محتوى علامات التبويب وإزالة التفعيل من الأزرار
        $('.form-tab-content').hide();
        $('.tab-buttons button').removeClass('active');

        // 💡 التعديل: تبديل إلى علامة تبويب "Conf" للإضافة
        if (tabName === 'conf') {
            $('#confTab').show();
            $('#conf-btn').addClass('active');

        // ✅ التعديل الرئيسي: تهيئة جدول الإضافة عند التبديل إلى "Confirmation Line"
        } else if (tabName === 'contact') {
            $('#contactTab').show();
            $('#contact-btn').addClass('active');

            if (!servicesTableInitialized) {
                $('#servicesTable').DataTable({
                    paging: false,
                    searching: false,
                    info: false,
                    responsive: true,
                    // يمكنك إزالة هذا الجزء إذا كنت ستقوم بتحميل البيانات عبر Ajax
                    // هذا الجزء يضمن فقط أن تهيئة الجدول يتم بشكل صحيح
                    columns: [
                        { name: 'id' },
                        { name: 'name' },
                        { name: 'method' },
                        { name: 'unit' },
                        { name: 'price' },
                        { name: 'price_only' },
                        { name: 'quantity' }
                    ]
                });
                servicesTableInitialized = true; // لمنع إعادة التهيئة
            }

        // 💡 التعديل: تبديل إلى علامة تبويب "Edit Conf" للتعديل
        } else if (tabName === 'edit-conf') {
            $('#editConfTab').show();
            $('#edit-conf-btn').addClass('active');

        // ✅ التعديل الرئيسي: تهيئة جدول التعديل عند التبديل إلى "Confirmation Line" للتعديل
        } else if (tabName === 'edit-contact') {
            $('#editContactTab').show();
            $('#edit-contact-btn').addClass('active');

            if (!servicesTableEditInitialized) {
                $('#servicesTableEdit').DataTable({
                    paging: false,
                    searching: false,
                    info: false,
                    responsive: true,
                    // هذا الجزء يضمن فقط أن تهيئة الجدول يتم بشكل صحيح
                    columns: [
                        { name: 'id' },
                        { name: 'name' },
                        { name: 'method' },
                        { name: 'unit' },
                        { name: 'price' },
                        { name: 'price_only' },
                        { name: 'quantity' }
                    ]
                });
                servicesTableEditInitialized = true; // لمنع إعادة التهيئة
            }
        }
    }
//-------------------------------------------------------------------------------------------
//------------------------------------------Service Line-------------------------------------
    function addServiceLine(mode = 'add') {
        // فتح النافذة المنبثقة الجديدة لاختيار الخدمات
        document.getElementById('serviceSelectionModal').style.display = 'block';

        // (اختياري) تهيئة DataTables للجدول الجديد إذا لم يتم تهيئته مسبقًا
        if (!$.fn.DataTable.isDataTable('#availableServicesTable')) {
            $('#availableServicesTable').DataTable({
                // خيارات DataTables هنا
                responsive: true,
                // ...
            });
        }
        // هنا يمكنك تخزين وضع الإضافة/التعديل لاستخدامه لاحقًا
        // مثلاً: $('#serviceSelectionModal').data('target-table', (mode === 'edit' ? '#servicesTableEdit' : '#servicesTable'));
    }
//-------------------------------------------------------------------------------------------
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
//-------------------------------------------------------------------------------------------
    function handleServiceInsert(insertType) {
        // 💡 يجب عليك كتابة منطق هذه الدالة لتحديد الصفوف المختارة
        // واستدعاء وظائف إدراج البيانات في الجدول الرئيسي (servicesTable أو servicesTableEdit)
        alert('Will insert selected services with type: ' + insertType);
        closeModal('serviceSelectionModal');
    }
//-------------------------------------------------------------------------------------------
// دالة JavaScript لتحديد/إلغاء تحديد الصف
    function toggleRowSelection(checkboxElement) {
        // العثور على العنصر الأب للصف (<tr>)
        const row = checkboxElement.closest('tr');

        if (checkboxElement.checked) {
            // إذا كان مربع الاختيار محددًا، أضف فئة التمييز
            row.classList.add('row-selected');
        } else {
            // إذا كان مربع الاختيار غير محدد، أزل فئة التمييز
            row.classList.remove('row-selected');
        }
    }
//-------------------------------------------------------------------------------------------


function saveDeli(closeAfter = false) {
    // جمع البيانات من الفورم
    const deliveryData = {
        delivery_no: document.getElementById('deliveryNo').value,
        delivery_date: document.getElementById('deliveryDate').value,
        department: document.getElementById('departmentSelect').value,

        project_id: document.getElementById('projectNo').value, // ID رقمي
        project_code: document.getElementById('projectCodeSelect').value,
        project_no: document.getElementById('projectNo').value,
        project_name: document.getElementById('projectNameSelect').value,
        project_details: document.getElementById('projectDetails').value,

        customer_id: document.getElementById('customerID').value, // ID رقمي
        customer_id_ref: document.getElementById('customerID').value, // نفس الرقم أو حسب النظام
        account_no: document.getElementById('accountNo').value,
        location: document.getElementById('location').value,

        contact_person: document.getElementById('deliveryContact').value, // ID رقمي
        attn_to: document.getElementById('attnTo').value,
        attn_pos: document.getElementById('attnPos').value,
        address_email: document.getElementById('contactEmail').value,
        contact_phone: document.getElementById('contactPhone') ? document.getElementById('contactPhone').value : '',
        contact_mobile: document.getElementById('contactMobile') ? document.getElementById('contactMobile').value : '',

        prepared_by: document.getElementById('preparedBy').value,
        delivered_by: document.getElementById('deliveredBy').value,
        received_by: document.getElementById('receivedBy').value,
        date_received: document.getElementById('dateReceived').value,
    };

    // إرسال البيانات عبر fetch
    fetch('/deliveries', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(deliveryData)
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'تم الحفظ بنجاح ✅',
                text: 'تم حفظ الـ Delivery بنجاح.',
                timer: 2000,
                showConfirmButton: false
            });

            if (closeAfter) {
                setTimeout(() => {
                    window.location.href = '/deliveries';
                }, 2000);
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: '❌ خطأ',
                text: result.message || 'حدث خطأ أثناء حفظ الـ Delivery.',
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: '⚠️ خطأ',
            text: 'حدث خطأ أثناء إرسال البيانات. تحقق من الكونسول.',
        });
    });
}



function openEditDeliveriesModal() {
    const selected = $('.delivery-checkbox:checked');

    if (selected.length === 0) {
        Swal.fire('⚠️', 'الرجاء اختيار عملية تسليم واحدة للتعديل', 'warning');
        return;
    }
    if (selected.length > 1) {
        Swal.fire('⚠️', 'الرجاء اختيار عملية تسليم واحدة فقط', 'warning');
        return;
    }

    const deliveryId = selected.val();
    fetchAndOpenEditModal(deliveryId);
}

function fetchAndOpenEditModal(deliveryId) {
    $.get(`/deliveries/${deliveryId}`, function(delivery) {
        // تعبئة الحقول المحفوظة
        $('#editConfId').val(delivery.id);
        $('#editDeliveryNo').val(delivery.delivery_no);
        $('#editDeliveryDate').val(delivery.delivery_date);
        $('#editDepartmentSelect').val(delivery.department);

        // Project
        if(delivery.project){
            $('#editProjectCodeSelect').val(delivery.project.reference).trigger('change'); // trigger لتحديث الحقول تلقائيًا
        }

        // Customer
        if(delivery.customer){
            $('#editCustomerID').val(delivery.customer.id);
            $('#editCustomerSelect').val(delivery.customer.id);
            $('#editAccountNo').val(delivery.customer.account_no || '');
            $('#editLocation').val(delivery.customer.city || '');
        }

        // Contact
        $('#editDeliveryContact').html('<option value="'+delivery.contact_person+'" selected>'+delivery.attn_to+'</option>');
        $('#editAttnTo').val(delivery.attn_to || '');
        $('#editAttnPos').val(delivery.attn_pos || '');
        $('#editAddressEmail').val(delivery.address_email || '');

        // باقي الحقول
        $('#editPreparedBy').val(delivery.prepared_by || '');
        $('#editDeliveredBy').val(delivery.delivered_by || '');
        $('#editReceivedBy').val(delivery.received_by || '');
        $('#editDateReceived').val(delivery.date_received || '');

        $('#editConfModal').fadeIn();
    })
    .fail(function(xhr) {
        console.error(xhr.responseText);
        Swal.fire('خطأ ❌','فشل جلب بيانات التسليم','error');
    });
}



// عند تغيير Project Code في المودال
$('#editProjectCodeSelect').on('change', function() {
    const selectedOption = $(this).find('option:selected');

    // تحديث Project Name / Project No / Details
    $('#editProjectNameSelect').val(selectedOption.data('name'));
    $('#editProjectNo').val(selectedOption.data('id'));
    $('#editProjectDetails').val(selectedOption.data('details'));

    // تحديث Customer تلقائيًا
    const customerId = selectedOption.data('customer');
    $('#editCustomerID').val(customerId);
    $('#editCustomerSelect').val(customerId);

    // إذا أردت يمكن تحديث Account No و Location مباشرة (من map أو data-attributes)
    const accountNo = selectedOption.data('account') || '';
    const location = selectedOption.data('location') || '';
    $('#editAccountNo').val(accountNo);
    $('#editLocation').val(location);
});


function closeModal(id) {
    $(`#${id}`).fadeOut(200);
}

function getSelectedDeliveryIds() {
    let ids = [];
    $('.delivery-checkbox:checked').each(function() {
        ids.push($(this).val());
    });
    return ids;
}

function saveEditConf(closeAfterSave = true) {
    const id = $('#editConfId').val();

    const formData = {
        delivery_date: $('#editDeliveryDate').val(),
        department: $('#editDepartmentSelect').val(),
        project_code: $('#editProjectCodeSelect').val(),
        project_name: $('#editProjectNameSelect').val(),
        project_details: $('#editProjectDetails').val(),
        customer_id: $('#editCustomerSelect').val(),
        account_no: $('#editAccountNo').val(),
        location: $('#editLocation').val(),
        contact_person: $('#editDeliveryContact').val(),
        attn_to: $('#editAttnTo').val(),
        attn_pos: $('#editAttnPos').val(),
        address_email: $('#editAddressEmail').val(),
        prepared_by: $('#editPreparedBy').val(),
        delivered_by: $('#editDeliveredBy').val(),
        received_by: $('#editReceivedBy').val(),
        date_received: $('#editDateReceived').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: `/deliveries/${id}`,
        type: 'PUT',
        data: formData,
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'تم تعديل التسليم بنجاح ✅',
                timer: 2000,
                showConfirmButton: false
            });
            loadDeliveries();
            if (closeAfterSave) closeModal('editConfModal');
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire('خطأ ❌', xhr.responseJSON?.message || 'فشل التعديل', 'error');
        }
    });
}


function deleteSelectedConfirmation() {
    const selected = $('.delivery-checkbox:checked'); // نحدد العناصر المختارة

    if (selected.length === 0) {
        Swal.fire('⚠️', 'الرجاء اختيار عنصر واحد على الأقل للحذف', 'warning');
        return;
    }

    // استخراج الـ IDs من العناصر المختارة
    const ids = selected.map(function() {
        return $(this).val();
    }).get();

    Swal.fire({
        title: 'هل أنت متأكد؟ 🗑️',
        text: `سيتم حذف ${ids.length} من عمليات التسليم.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/deliveries/delete-bulk',
                type: 'DELETE',
                data: {
                    ids: ids,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الحذف بنجاح ✅',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadDeliveries(); // إعادة تحميل الجدول
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire('❌ خطأ', 'حدث خطأ أثناء الحذف. تحقق من الكونسول.', 'error');
                }
            });
        }
    });
}


function approveDelivery() {
    const selected = $('.delivery-checkbox:checked');

    if (selected.length === 0) {
        Swal.fire('⚠️', 'الرجاء اختيار عملية تسليم لاعتمادها', 'warning');
        return;
    }
    if (selected.length > 1) {
        Swal.fire('⚠️', 'الرجاء اختيار عملية تسليم واحدة فقط', 'warning');
        return;
    }

    const deliveryId = selected.val();

    $.ajax({
        url: `/deliveries/${deliveryId}/approve`,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: response.message,
                timer: 2000,
                showConfirmButton: false
            });
            loadDeliveries(); // تحديث الجدول بعد الاعتماد
        },
        error: function(xhr) {
            Swal.fire('خطأ ❌', xhr.responseJSON?.message || 'فشل الاعتماد', 'error');
            console.error(xhr.responseJSON);
        }
    });
}


function sendToCustomer() {
    const selected = $('.delivery-checkbox:checked');

    if (selected.length === 0) {
        Swal.fire('⚠️', 'الرجاء اختيار عملية تسليم لإرسالها', 'warning');
        return;
    }
    if (selected.length > 1) {
        Swal.fire('⚠️', 'الرجاء اختيار عملية تسليم واحدة فقط', 'warning');
        return;
    }

    const deliveryId = selected.val();

    $.ajax({
        url: `/deliveries/${deliveryId}/send-to-customer`,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            Swal.fire('✅', response.message, 'success');
        },
        error: function(xhr) {
            Swal.fire('❌', xhr.responseJSON?.message || 'فشل الإرسال', 'error');
            console.error(xhr.responseJSON);
        }
    });
}

window.exportDeliveriesExcelBtn = function() {
    const tableElement = document.getElementById('deliveriesTable');
    if (!tableElement) {
        showAlert('⚠️ لم يتم العثور على جدول التسليمات.', 'warning');
        return;
    }

    const deliveriesTable = $(tableElement).DataTable();

    // الصفوف المحددة (أو كل الجدول إذا لم يوجد تحديد)
    const selectedCheckboxes = deliveriesTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;
    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = deliveriesTable.rows({ 'search': 'applied' }).nodes();
    }

    // بناء بيانات SheetJS
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

    // إنشاء المصنف والورقة
    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Deliveries");

    // تنزيل الملف
    XLSX.writeFile(wb, "deliveries-data.xlsx");

    showAlert('تم تصدير بيانات التسليمات بنجاح إلى ملف Excel.', 'success');
};


window.printDeliveriesTable = function(tableId = 'deliveriesTable') {
    const tableElement = document.getElementById(tableId);
    if (!tableElement) {
        showAlert('⚠️ لم يتم العثور على جدول التسليمات.', 'warning');
        return;
    }

    const deliveriesTable = $(tableElement).DataTable();
    const selectedCheckboxes = deliveriesTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;
    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = deliveriesTable.rows({ 'search': 'applied' }).nodes();
    }

    let printContents = `
        <html>
        <head>
            <title>طباعة التسليمات</title>
            <style>
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                body { font-family: 'Arial', sans-serif; }
                h2 { text-align: center; margin-bottom: 20px; }
            </style>
        </head>
        <body>
            <h2>جدول التسليمات</h2>
            <table>
                <thead><tr>`;

    $(tableElement).find('thead tr:first th').each(function() {
        if ($(this).find('input[type="checkbox"]').length > 0 || $(this).text().trim() === '') return;
        const columnTitle = $(this).clone().children().remove().end().text().trim();
        printContents += '<th>' + columnTitle + '</th>';
    });

    printContents += '</tr></thead><tbody>';

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


</script>









@endsection
