@extends('layouts.app')
@section('title', __('Financial Transactions'))
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

<main class="main-content">
    <section id="deliveries-section" class="section-content active">

        <!-- Buttons -->
        <div class="icon-toolbar">
            <div>
                <button title="Add Invoice" onclick="openConfModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                <button title="Edit Invoice" onclick="openEditInvModal()" class="btn-icon"><i class="fas fa-pen"></i></button>
                <button title="Delete" onclick="deleteSelectedConfirmation()" class="btn-icon"><i class="fas fa-trash"></i></button>
                <button title="Approve Invoice" onclick="approveInvoice()" class="btn-icon btn-approve" style="color: #28a745;"><i class="fas fa-check-circle"></i></button>
                <button title="Preview" onclick="previewDocument()" class="btn-icon"><i class="fas fa-eye"></i></button>
                <button title="Send to Customer" onclick="sendToCustomer()" class="btn-icon"><i class="fas fa-envelope"></i></button>
            </div>
            <div class="icon-separator"></div>
            <div>
                <button title="Convert to PDF" class="btn-icon" onclick="exportToPdfBtn()"><i class="fas fa-file-pdf"></i></button>
                <button title="Export to Excel" class="btn-icon" onclick="exportInvoicesExcelBtn()"><i class="fa-solid fa-table"></i></button>
                <button title="Print" class="btn-icon" onclick="printInvoicesTable()"><i class="fas fa-print"></i></button>
            </div>
        </div>

        <!-- Invoices table -->
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
                <tbody></tbody>
            </table>
        </div>

    </section>
</main>

<!-- Modals -->
@include('financial.create')

<!-- JS libs -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>


<script>
// ====== Global helpers ======
function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

// ====== Invoices DataTable & load ======
let invoicesTable;

$(document).ready(function() {
    invoicesTable = $('#invoicesTable').DataTable({
        responsive: true,
        scrollX: true,
        autoWidth: false,
        columnDefs: [{ orderable: false, targets: [0] }],
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
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

    // column filters (header inputs)
    invoicesTable.columns().every(function() {
        var column = this;
        $('input', this.header()).on('keyup change', function() {
            if (column.search() !== this.value) {
                column.search(this.value).draw();
            }
        });
    });

    // select all logic (only visible rows)
    $('#selectAllInvoices').on('change', function() {
        const checked = $(this).is(':checked');
        invoicesTable.rows({ search: 'applied' }).nodes().to$().find('input.invoice-checkbox').prop('checked', checked);
    });

    $('#invoicesTable tbody').on('change', 'input.invoice-checkbox', function() {
        const rows = invoicesTable.rows({ search: 'applied' }).nodes().to$();
        const total = rows.find('input.invoice-checkbox').length;
        const checked = rows.find('input.invoice-checkbox:checked').length;
        $('#selectAllInvoices').prop('checked', total === checked);
    });

    // load data
    loadInvoices();
});

function loadInvoices() {
    $.ajax({
        url: '{{ route("financial.index") }}',
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
                    trn: inv.trn_no	 ?? (inv.customer?.trn_no ?? ''),
                    project_code: inv.project_code ?? (inv.project?.reference ?? ''),
                    project_name: inv.project_name ?? (inv.project?.name ?? ''),
                    account_manager: inv.account_manager ?? '',
                    department: inv.department ?? '',
                    items_count: inv.items_count ?? (inv.items?.length ?? 0)
                });
            });
            invoicesTable.draw();
        },
        error: function(xhr) {
            console.error('Error loading invoices:', xhr.responseText);
            // if server returns HTML, open in new window for debugging
            if (xhr && xhr.responseText && xhr.responseText.startsWith('<')) {
                const w = window.open();
                w.document.write(xhr.responseText);
                w.document.close();
            }
            Swal.fire('خطأ', 'حدث خطأ أثناء تحميل بيانات الفواتير من السيرفر', 'error');
        }
    });
}
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
// ====== Save invoice (uses /financial/store route) ======
function saveInvoice(closeAfter = false) {
    const invoiceData = {
        invoice_no: document.getElementById('deliveryNo').value,
        invoice_date: document.getElementById('deliveryDate').value,
        department: document.getElementById('departmentSelect').value,
        prof_date: document.getElementById('profDate').value,
        account_date: document.getElementById('accountDate').value,
        due_date: document.getElementById('dueDate').value,
       project_id: document.getElementById('projectNo').value,
        project_code: document.getElementById('projectCodeSelect').value,
        project_name: document.getElementById('projectNameSelect').value,
        project_details: document.getElementById('projectDetails').value,
        contract_no: document.getElementById('contractNo').value,
        customer_id: document.getElementById('customerID').value,
        customer_name: document.getElementById('customerName').value,
        account_no: document.getElementById('accountNo').value,
        trn_no: document.getElementById('trnNo').value,
        location: document.getElementById('location').value,
        account_manager: document.getElementById('accountManager').value,
        contact_person: document.getElementById('contactMobile').value,
        attn_to: document.getElementById('attnTo').value,
        attn_pos: document.getElementById('attnPos').value,
        address_email: document.getElementById('addressEmail').value,
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
            'X-CSRF-TOKEN': getCsrf()
        },
        body: JSON.stringify(invoiceData)
    })
    .then(async res => {
        const contentType = res.headers.get('content-type') || '';
        if (contentType.includes('application/json')) {
            const data = await res.json();
            if (!res.ok) throw { type: 'response', data };
            return data;
        }
        const text = await res.text();
        throw { type: 'text', text };
    })
    .then(result => {
        Swal.fire({ icon: 'success', title: 'تم الحفظ بنجاح ✅', text: result.message || 'تم إنشاء الفاتورة بنجاح.', timer: 1500, showConfirmButton: false });
        // تحديث الجدول لإظهار الفاتورة الجديدة
        loadInvoices();
        if (closeAfter) closeModal('confModal');
    })
    .catch(async err => {
        console.error('Save invoice error:', err);
        let message = 'حدث خطأ أثناء إرسال البيانات للسيرفر.';
        if (err.type === 'response' && err.data) {
            if (err.data.errors) message = Object.values(err.data.errors).flat().join('\n');
            else if (err.data.message) message = err.data.message;
        } else if (err.type === 'text' && err.text) {
            message = err.text;
        } else if (err instanceof Error) {
            message = err.message;
        }
        Swal.fire({ icon: 'error', title: '⚠️ خطأ', text: message });
    });
}

// ====== Project/Customer client-side maps (Blade -> JS safe) ======
const projectsMap = {
    @foreach($projects as $project)
        "{{ $project->reference }}": {
            id: {{ json_encode($project->id) }},
            name: {!! json_encode($project->name) !!},
            details: {!! json_encode($project->project_details ?? '') !!},
            customer_id: {{ json_encode($project->customer_id) }}
        }@if(!$loop->last),@endif
    @endforeach
};

const customersMap = {
    @foreach($customers as $customer)
        "{{ $customer->id }}": {
            name: {!! json_encode($customer->customer_name) !!},
            account_no: {!! json_encode($customer->account_no ?? '') !!},
            city: {!! json_encode($customer->city ?? '') !!},
            contacts: [
                @foreach($customer->contacts as $contact)
                    {
                        id: {{ json_encode($contact->id) }},
                        name: {!! json_encode($contact->name) !!},
                        position: {!! json_encode($contact->position ?? '') !!},
                        email: {!! json_encode($contact->email ?? '') !!},
                        mobile: {!! json_encode($contact->mobile ?? '') !!},
                        phone: {!! json_encode($contact->phone ?? '') !!}
                    }@if(!$loop->last),@endif
                @endforeach
            ]
        }@if(!$loop->last),@endif
    @endforeach
};

// fill project/customer selects when modal opens

// when user selects project code
document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'projectCodeSelect') {
        const code = e.target.value;
        if (!code || !projectsMap[code]) return;
        const project = projectsMap[code];
        document.getElementById('projectNameSelect').value = project.name || '';
        document.getElementById('projectDetails').value = project.details || '';
        document.getElementById('projectNo').value = project.id || '';
        const customer = customersMap[project.customer_id];
        const deliveryContact = document.getElementById('deliveryContact');
        deliveryContact.innerHTML = '<option value="" disabled selected>[Select Contact]</option>';
        if (customer && Array.isArray(customer.contacts)) {
            customer.contacts.forEach(contact => {
                const option = document.createElement('option');
                option.value = contact.id;
                option.textContent = `${contact.name} (${contact.mobile || ''})`;
                option.dataset.name = contact.name || '';
                option.dataset.position = contact.position || '';
                option.dataset.email = contact.email || '';
                option.dataset.mobile = contact.mobile || '';
                deliveryContact.appendChild(option);
            });
            document.getElementById('customerID').value = project.customer_id;
            document.getElementById('customerName').value = customer.name || '';
            document.getElementById('accountNo').value = customer.account_no || '';
            document.getElementById('location').value = customer.city || '';
        }
    }
});

// when user selects a contact
document.addEventListener('change', function(e) {
    if (e.target && e.target.id === 'deliveryContact') {
        const selected = e.target.selectedOptions[0];
        if (!selected) return;
        document.getElementById('attnTo').value = selected.dataset.name || '';
        document.getElementById('attnPos').value = selected.dataset.position || '';
        document.getElementById('addressEmail').value = selected.dataset.email || '';
        document.getElementById('contactMobile').value = selected.dataset.mobile || '';
    }
});



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


function deleteSelectedConfirmation() {
    if (typeof invoicesTable === 'undefined' || invoicesTable === null) {
        Swal.fire("خطأ", "لم يتم تهيئة جدول الفواتير (invoicesTable).", "error");
        return;
    }

    // 1️⃣ جمع معرّفات الفواتير المحددة
    const selectedIds = [];
    const rowsToDelete = [];

    invoicesTable.$('.invoice-checkbox:checked').each(function() {
        const row = $(this).closest('tr');
        const id = $(this).val(); // القيمة المفترضة داخل الـ checkbox
        selectedIds.push(id);
        rowsToDelete.push(row);
    });

    if (selectedIds.length === 0) {
        Swal.fire("تحذير", "⚠️ الرجاء تحديد فاتورة واحدة على الأقل للحذف.", "warning");
        return;
    }

    // 2️⃣ تأكيد الحذف عبر SweetAlert
    Swal.fire({
        title: "هل أنت متأكد؟",
        text: `أنت على وشك حذف ${selectedIds.length} فاتورة/فواتير. لا يمكن التراجع عن هذا الإجراء!`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "نعم، احذف!",
        cancelButtonText: "إلغاء",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // 3️⃣ إرسال طلب AJAX للحذف
            $.ajax({
                url: `/financial/delete-multiple`,
                type: 'POST',
                data: {
                    ids: selectedIds,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'DELETE'
                },
                success: function(response) {
                    // إزالة الصفوف من DataTables
                    invoicesTable.rows(rowsToDelete).remove().draw(false);
                    $('#selectAllInvoices').prop('checked', false);

                    Swal.fire("تم الحذف! ✅", response.message || "تم حذف الفواتير بنجاح.", "success");
                },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    Swal.fire("خطأ ❌", xhr.responseJSON?.message || "حدث خطأ أثناء حذف الفواتير.", "error");
                }
            });
        }
    });
}


function approveInvoice() {
    const selected = $('.invoice-checkbox:checked');

    if (selected.length === 0) {
        Swal.fire('⚠️', 'الرجاء اختيار فاتورة لاعتمادها', 'warning');
        return;
    }

    if (selected.length > 1) {
        Swal.fire('⚠️', 'الرجاء اختيار فاتورة واحدة فقط', 'warning');
        return;
    }

    const invoiceId = selected.val();

    $.ajax({
        url: `/financial/${invoiceId}/approve`,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: response.message,
                timer: 2000,
                showConfirmButton: false
            });

            // 🔄 تحديث جدول الفواتير بعد الاعتماد
            loadInvoices();
        },
        error: function (xhr) {
            Swal.fire('❌ خطأ', xhr.responseJSON?.message || 'فشل اعتماد الفاتورة', 'error');
            console.error(xhr.responseJSON);
        }
    });
}

function sendToCustomer() {
    const selected = $('.invoice-checkbox:checked');

    if (selected.length === 0) {
        Swal.fire('⚠️', 'الرجاء اختيار فاتورة لإرسالها للعميل', 'warning');
        return;
    }
    if (selected.length > 1) {
        Swal.fire('⚠️', 'الرجاء اختيار فاتورة واحدة فقط', 'warning');
        return;
    }

    const invoiceId = selected.val();

    $.ajax({
        url: `/financial/${invoiceId}/send-to-customer`,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            Swal.fire({
                icon: 'success',
                title: 'تم الإرسال بنجاح ✅',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            });
        },
        error: function (xhr) {
            Swal.fire({
                icon: 'error',
                title: '❌ خطأ',
                text: xhr.responseJSON?.message || 'فشل إرسال الفاتورة للعميل'
            });
            console.error(xhr.responseJSON);
        }
    });
}


window.exportToPdfBtn = function() {
    const tableElement = document.getElementById('invoicesTable');
    if (!tableElement) {
        showAlert('⚠️ لم يتم العثور على جدول الفواتير.', 'warning');
        return;
    }

    const invoicesTable = $(tableElement).DataTable();
    const selectedCheckboxes = invoicesTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;
    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = invoicesTable.rows({ 'search': 'applied' }).nodes();
    }

    let docDefinition = {
        content: [
            { text: '📄 قائمة الفواتير', style: 'header', alignment: 'center', margin: [0, 0, 0, 20] },
            {
                table: {
                    headerRows: 1,
                    widths: 'auto',
                    body: []
                }
            }
        ],
        styles: {
            header: { fontSize: 16, bold: true },
            tableHeader: { bold: true, fillColor: '#eeeeee' }
        },
        defaultStyle: { fontSize: 9 }
    };

    // رؤوس الأعمدة
    const header = [];
    $(tableElement).find('thead th').each(function() {
        if ($(this).find('input[type="checkbox"]').length === 0 && $(this).text().trim() !== '') {
            header.push({ text: $(this).text().trim(), style: 'tableHeader' });
        }
    });
    docDefinition.content[1].table.body.push(header);

    // البيانات
    $(rowsToProcess).each(function() {
        const rowData = [];
        $(this).find('td').each(function() {
            if ($(this).find('input[type="checkbox"]').length === 0 && $(this).find('.icon-toolbar').length === 0) {
                rowData.push($(this).text().trim());
            }
        });
        docDefinition.content[1].table.body.push(rowData);
    });

    pdfMake.createPdf(docDefinition).download('invoices-data.pdf');
    showAlert('تم تصدير الفواتير إلى PDF بنجاح ✅', 'success');
};



window.exportInvoicesExcelBtn = function() {
    const tableElement = document.getElementById('invoicesTable');
    if (!tableElement) {
        showAlert('⚠️ لم يتم العثور على جدول الفواتير.', 'warning');
        return;
    }

    const invoicesTable = $(tableElement).DataTable();
    const selectedCheckboxes = invoicesTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;
    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = invoicesTable.rows({ 'search': 'applied' }).nodes();
    }

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
    XLSX.utils.book_append_sheet(wb, ws, "Invoices");
    XLSX.writeFile(wb, "invoices-data.xlsx");

    showAlert('تم تصدير الفواتير إلى Excel بنجاح ✅', 'success');
};

window.printInvoicesTable = function(tableId = 'invoicesTable') {
    const tableElement = document.getElementById(tableId);
    if (!tableElement) {
        showAlert('⚠️ لم يتم العثور على جدول الفواتير.', 'warning');
        return;
    }

    const invoicesTable = $(tableElement).DataTable();
    const selectedCheckboxes = invoicesTable.$('input[type="checkbox"]:checked');
    let rowsToProcess;
    if (selectedCheckboxes.length > 0) {
        rowsToProcess = selectedCheckboxes.parents('tr');
    } else {
        rowsToProcess = invoicesTable.rows({ 'search': 'applied' }).nodes();
    }

    let printContents = `
        <html>
        <head>
            <title>طباعة الفواتير</title>
            <style>
                table { width: 100%; border-collapse: collapse; font-size: 12px; }
                th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
                body { font-family: 'Arial', sans-serif; direction: rtl; }
                h2 { text-align: center; margin-bottom: 15px; }
            </style>
        </head>
        <body>
            <h2>قائمة الفواتير 📋</h2>
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

    showAlert('تم إرسال أمر الطباعة بنجاح 🖨️', 'success');
};


</script>

@endsection
