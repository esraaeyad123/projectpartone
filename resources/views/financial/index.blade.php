@extends('layouts.app')
@section('title', __('Financial Transactions'))
@section('content')
@include('financial.create') <!-- المودال -->


<main class="main-content">
    <section id="deliveries-section" class="section-content active">

        <!-------------------------------------------Start Buttons-------------------------------------------->
        <div class="icon-toolbar">
            <div>
                <button title="Add Invoice" onclick="openConfModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                <button title="Edit Invoice" onclick="openEditInvModal()" class="btn-icon"><i class="fas fa-pen"></i></button>
                <button title="Delete" onclick="deleteSelectedConfirmation()" class="btn-icon"><i class="fas fa-trash"></i></button>

                <button title="Approve Invoice" onclick="approveInvoice()" class="btn-icon btn-approve" style="color: #28a745;">
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
            <table id="invoicesTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllInvoices"></th>

                        <th>Invoice #<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Invoice Date<br><input type="text" class="column-filter date-range-filter" data-filter-type="date-from"></th>

                        <th>Status<br><input type="text" class="column-filter" placeholder="Search..."></th>

                        <th>Net Amount<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>VAT Amount<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Total Due<br><input type="text" class="column-filter" placeholder="Search..."></th>

                        <th>Due Date<br><input type="text" class="column-filter date-range-filter" data-filter-type="date-to"></th>

                        <th>Customer Name<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>TRN<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Project Code<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Project Name<br><input type="text" class="column-filter" placeholder="Search..."></th>

                        <th>Account Manager<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Department<br><input type="text" class="column-filter" placeholder="Search..."></th>

                        <th>Items Count<br><input type="text" class="column-filter" placeholder="Search..."></th>

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
    // 1️⃣ تهيئة جدول Invoices
    // ===============================
    window.invoicesTable = $('#invoicesTable').DataTable({
        responsive: true,
        scrollX: true,
        autoWidth: false,
        columnDefs: [
            { orderable: false, targets: [0] } // عمود الشيكبوكس لا يقبل الفرز
        ],
        columns: [
            { data: 'checkbox', orderable: false, searchable: false }, // ✅ خانة التحديد
            { data: 'invoice_no' },
            { data: 'invoice_date' },
            { data: 'status' },
            { data: 'net_amount' },
            { data: 'vat_amount' },
            { data: 'total_due' },
            { data: 'due_date' },
            { data: 'customer_name' },
            { data: 'trn' },
            { data: 'project_code' },
            { data: 'project_name' },
            { data: 'account_manager' },
            { data: 'department' },
            { data: 'items_count' }
        ]
    });

    // ===============================
    // 2️⃣ تحميل بيانات الفواتير من السيرفر
    // ===============================
    loadInvoices();

    // ===============================
    // 3️⃣ تحديد الكل / إلغاء الكل
    // ===============================
    $('#selectAllInvoices').on('change', function() {
        let rows = invoicesTable.rows({ 'search': 'applied' }).nodes();
        $('input.invoice-checkbox', rows).prop('checked', this.checked);
    });

    $('#invoicesTable tbody').on('change', 'input.invoice-checkbox', function() {
        let allChecked = $('.invoice-checkbox').length === $('.invoice-checkbox:checked').length;
        $('#selectAllInvoices').prop('checked', allChecked);
    });
});


// ===============================
// 4️⃣ دالة تحميل بيانات الفواتير (AJAX)
// ===============================
function loadInvoices() {
    $.ajax({
        url: '/financial', // تأكد أن يرجع JSON
        type: 'GET',
        dataType: 'json',
        success: function(invoices) {
            invoicesTable.clear();

            invoices.forEach(inv => {
                invoicesTable.row.add({
                    checkbox: `<input type="checkbox" class="invoice-checkbox" value="${inv.id}">`,
                    invoice_no: inv.invoice_no ?? '',
                    invoice_date: inv.invoice_date ?? '',
                    status: inv.status ?? '',
                    net_amount: inv.net_amount ?? '0',
                    vat_amount: inv.vat_amount ?? '0',
                    total_due: inv.total_due ?? '0',
                    due_date: inv.due_date ?? '',
                    customer_name: inv.customer_name ?? (inv.customer?.customer_name ?? ''),
                    trn: inv.trn ?? (inv.customer?.trn ?? ''),
                    project_code: inv.project_code ?? (inv.project?.reference ?? ''),
                    project_name: inv.project_name ?? (inv.project?.name ?? ''),
                    account_manager: inv.account_manager ?? '',
                    department: inv.department ?? '',
                    items_count: inv.items_count ?? (inv.items?.length ?? 0)
                });
            });

            invoicesTable.draw();
        },
        error: function(xhr, status, error) {
            console.error('Error loading invoices:', error);
            Swal.fire('خطأ', 'حدث خطأ أثناء تحميل بيانات الفواتير من السيرفر', 'error');
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

function saveInvoice(closeAfter = false) {
    const invoiceData = {
        // 🧾 معلومات الفاتورة
        invoice_no: document.getElementById('deliveryNo').value,
        invoice_date: document.getElementById('deliveryDate').value,
        department: document.getElementById('departmentSelect').value,
        prof_date: document.getElementById('profDate').value,
        account_date: document.getElementById('accountDate').value,
        due_date: document.getElementById('dueDate').value,

        // 🏗️ معلومات المشروع
        project_id: document.getElementById('projectNo').value,
         project: document.getElementById('projectNo').value,
        project_code: document.getElementById('projectCodeSelect').value,
        project_name: document.getElementById('projectNameSelect').value,
        project_details: document.getElementById('projectDetails').value,
        contract_no: document.getElementById('contractNo').value,

        // 👤 معلومات العميل
customer_id: $('#editCustomerID').data('id') || $('#editCustomerID').val(),
        customer_name: document.getElementById('customerName').value,
        account_no: document.getElementById('accountNo').value,
        trn_no: document.getElementById('trnNo').value,
        location: document.getElementById('location').value,

        // 📞 معلومات الاتصال
        account_manager: document.getElementById('accountManager').value,
        contact_person: document.getElementById('contactMobile').value,
        attn_to: document.getElementById('attnTo').value,
        attn_pos: document.getElementById('attnPos').value,
        address_email: document.getElementById('addressEmail').value,

        // 💰 الشروط والإعدادات المالية
        payment_terms: document.getElementById('paymentTerms').value,
        payment_method: document.getElementById('paymentMethod').value,
        vat_profile: document.getElementById('vatProfile').value,
        discount_pct: document.getElementById('discountPct').value,
        sales_tax_pct: document.getElementById('salesTaxPct').value,
        retention_pct: document.getElementById('retentionPct').value,
        currency: document.getElementById('currency').value,
    };

    fetch('/financial', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(invoiceData)
    })
    .then(res => res.json())
    .then(result => {
        if (result.message?.includes('success')) {
            Swal.fire({
                icon: 'success',
                title: 'تم الحفظ بنجاح ✅',
                text: 'تم إنشاء الفاتورة بنجاح.',
                timer: 2000,
                showConfirmButton: false
            });

            if (closeAfter) {
                setTimeout(() => {
                    window.location.href = '/financial';
                }, 2000);
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: '❌ خطأ',
                text: result.message || 'حدث خطأ أثناء حفظ الفاتورة.',
            });
        }
    })
    .catch(err => {
        console.error('Error:', err);
        Swal.fire({
            icon: 'error',
            title: '⚠️ خطأ',
            text: 'حدث خطأ أثناء إرسال البيانات للسيرفر.',
        });
    });
}


// ===============================
// ✳️ فتح مودال تعديل الفاتورة المختارة
// ===============================
// ===============================
// ✳️ فتح مودال تعديل الفاتورة المختارة
// ===============================
function openEditInvModal() {
    const selectedIds = [];
    $('.invoice-checkbox:checked').each(function () {
        selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
        Swal.fire('تنبيه', 'الرجاء اختيار فاتورة واحدة للتعديل', 'warning');
        return;
    }

    if (selectedIds.length > 1) {
        Swal.fire('تنبيه', 'الرجاء اختيار فاتورة واحدة فقط للتعديل', 'warning');
        return;
    }

    const invoiceId = selectedIds[0];

    // جلب بيانات الفاتورة من السيرفر عبر AJAX
    $.ajax({
        url: `/financial/${invoiceId}`,
        type: 'GET',
        dataType: 'json',
        success: function (invoice) {

            // تعبئة الحقول داخل المودال
            $('#editConfId').val(invoice.id);
            $('#editDeliveryNo').val(invoice.invoice_no);
            $('#editDeliveryDate').val(invoice.invoice_date);
            $('#editDepartmentSelect').val(invoice.department);

            $('#editProfDate').val(invoice.prof_date);
            $('#editAccountDate').val(invoice.account_date);
            $('#editDueDate').val(invoice.due_date);

            $('#editProjectCodeSelect').val(invoice.project_code ?? invoice.project?.reference ?? '');
            $('#editProjectNameSelect').val(invoice.project_name ?? invoice.project?.name ?? '')
            $('#editProject').val(invoice.project_details ?? invoice.project?.project_details ?? '');

            $('#editContractNo').val(invoice.contract_no ?? '');

          $('#editCustomerID').val(invoice.customer?.id ?? ''); // رقم العميل مثل AAMC-1001
$('#editCustomerSelect').val(invoice.customer?.customer_name ?? ''); // اسم العميل مثل أحمد علي محمد

            $('#editAccountNo').val(invoice.account_no ?? '');
            $('#editTrnNo').val(invoice.trn_no ?? invoice.customer?.trn ?? '');
            $('#editLocation').val(invoice.location ?? '');

            $('#editAccountManager').val(invoice.account_manager ?? '');
            $('#editDeliveryContact').val(invoice.contact_person ?? '');
            $('#editAttnTo').val(invoice.attn_to ?? '');
            $('#editAttnPos').val(invoice.attn_pos ?? '');
            $('#editAddressEmail').val(invoice.address_email ?? '');

            $('#editPaymentTerms').val(invoice.payment_terms ?? '');
            $('#editPaymentMethod').val(invoice.payment_method ?? 'Bank Transfer');
            $('#editVatProfile').val(invoice.vat_profile ?? '');
            $('#editDiscountPct').val(invoice.discount_pct ?? 0);
            $('#editSalesTaxPct').val(invoice.sales_tax_pct ?? 0);
            $('#editRetentionPct').val(invoice.retention_pct ?? 0);
            $('#editCurrency').val(invoice.currency ?? 'SAR');

            // فتح المودال
            $('#editConfModal').show().addClass('show');

            $('#editCustomerID')
    .val(invoice.customer?.id ?? '')
    .data('id', invoice.customer?.id ?? '');

            // تحميل بنود الفاتورة (Invoice Lines)
            loadInvoiceLines(invoiceId);
        },
        error: function (xhr, status, error) {
            console.error('Error loading invoice:', error);
            Swal.fire('خطأ', 'تعذر تحميل بيانات الفاتورة من السيرفر', 'error');
        }
    });
}

// ===============================
// ✳️ تحميل تفاصيل بنود الفاتورة (Invoice Lines)
// ===============================
function loadInvoiceLines(invoiceId) {
    $.ajax({
        url: `/financial/${invoiceId}/lines`,
        type: 'GET',
        dataType: 'json',
        success: function (lines) {
            const table = $('#servicesTableEdit').DataTable();
            table.clear();

            lines.forEach(line => {
                table.row.add([
                    line.id ?? '',
                    line.name ?? '',
                    line.method ?? '',
                    line.unit ?? '',
                    line.price ?? 0,
                    line.price_only ? '✅' : '',
                    line.quantity ?? 1
                ]);
            });

            table.draw();
        },
        error: function (xhr, status, error) {
            console.error('Error loading invoice lines:', error);
            Swal.fire('خطأ', 'تعذر تحميل بنود الفاتورة من السيرفر', 'error');
        }
    });
}



function saveEditConf(closeAfterSave = true) {
    const invoiceId = $('#editConfId').val();
    if (!invoiceId) {
        Swal.fire("خطأ", "رقم الفاتورة غير صالح.", "error");
        return;
    }

    const data = {
        invoice_no: $('#editDeliveryNo').val(),
        invoice_date: $('#editDeliveryDate').val(),
        department: $('#editDepartmentSelect').val(),
        prof_date: $('#editProfDate').val(),
        account_date: $('#editAccountDate').val(),
        due_date: $('#editDueDate').val(),

        project_code: $('#editProjectCodeSelect').val(),
        project_name: $('#editProjectNameSelect').val(),
        project_details: $('#editProject').val(),
        contract_no: $('#editContractNo').val(),

        customer_id: $('#editCustomerID').val(),
        account_no: $('#editAccountNo').val(),
        trn_no: $('#editTrnNo').val(),
        location: $('#editLocation').val(),

        account_manager: $('#editAccountManager').val(),
        contact_person: $('#editDeliveryContact').val(),
        contact_mobile: $('#editContactMobile').val() || '',
        attn_to: $('#editAttnTo').val(),
        attn_pos: $('#editAttnPos').val(),
        address_email: $('#editAddressEmail').val(),

        payment_terms: $('#editPaymentTerms').val(),
        payment_method: $('#editPaymentMethod').val(),
        vat_profile: $('#editVatProfile').val(),
        discount_pct: parseFloat($('#editDiscountPct').val()) || 0,
        sales_tax_pct: parseFloat($('#editSalesTaxPct').val()) || 0,
        retention_pct: parseFloat($('#editRetentionPct').val()) || 0,
        currency: $('#editCurrency').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    // التحقق من الحقول الأساسية
    if (!data.project_code) {
        Swal.fire("تحذير", "⚠️ الرجاء اختيار المشروع.", "warning");
        return;
    }
    if (!data.customer_id) {
        Swal.fire("تحذير", "⚠️ الرجاء اختيار العميل.", "warning");
        return;
    }

    // إرسال الطلب عبر Ajax
    $.ajax({
        url: `/financial/${invoiceId}`,
        type: 'PUT',
        data: data,
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: response.message,
                timer: 1500,
                showConfirmButton: false
            });
            loadInvoices();
            if (closeAfterSave) $('#editConfModal').hide();
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ ❌',
                html: xhr.responseJSON?.message || 'حدث خطأ غير معروف'
            });
        }
    });
}


// دالة لإغلاق المودال
function closeEditConfModal() {
    $('#editConfModal').hide().removeClass('show');
}

// ===============================
// ✳️ تحميل تفاصيل بنود الفاتورة (Invoice Lines)
// ===============================
function loadInvoiceLines(invoiceId) {
    $.ajax({
        url: `/financial/${invoiceId}/lines`,
        type: 'GET',
        dataType: 'json',
        success: function (lines) {
            const table = $('#servicesTableEdit').DataTable();
            table.clear();

            lines.forEach(line => {
                table.row.add([
                    line.id ?? '',
                    line.name ?? '',
                    line.method ?? '',
                    line.unit ?? '',
                    line.price ?? 0,
                    line.price_only ? '✅' : '',
                    line.quantity ?? 1
                ]);
            });

            table.draw();
        },
        error: function (xhr, status, error) {
            console.error('Error loading invoice lines:', error);
            Swal.fire('خطأ', 'تعذر تحميل بنود الفاتورة من السيرفر', 'error');
        }
    });
}


</script>









@endsection
