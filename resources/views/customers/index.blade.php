@extends('layouts.app')

@section('title', __('Customers Management'))

@section('content')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<main class="main-content">
    <section id="customers-section" class="section-content active">
        <div class="icon-toolbar">
            <div>
                <button title="Add" onclick="openCustomerModal()" class="btn-icon">
                    <i class="fas fa-file"></i>
                </button>
                <button title="Edit" onclick="openEditCustomerModal()"  class="btn-icon">
                    <i class="fas fa-pen"></i>
                </button>
                <button title="Delete" onclick="deleteSelectedCustomers()" class="btn-icon">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            <div class="icon-separator"></div>
            <div>
               <button title="File Manager"
        class="btn-icon"
        data-url="{{ route('customer-files.index', ['customerId' => ':id']) }}"
        onclick="goToCustomerFiles(this)">
    <i class="fas fa-folder-open"></i>
</button>

<!-- زر التصدير الموجود عندك -->
<button title="Export to Excel" class="btn-icon" id="exportCustomersExcelBtn" onclick="exportCustomersExcelBtn()">
  <i class="fa-solid fa-table"></i>
</button>
                <button title="Print" class="btn-icon" onclick="printCustomersTable()"><i class="fas fa-print"></i></button>

            </div>
        </div>

        <div class="table-responsive-container">
            <table id="customersTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllCustomers"></th>
                        <th>Customer ID<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Customer Name<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Arabic Name<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Customer Type<br>
                            <select class="column-filter">
                                <option value="">All</option>
                                <option value="Consultant">Consultant</option>
                                <option value="Supplier">Supplier</option>
                                <option value="Private">Private</option>
                                <option value="Owner">Owner</option>
                                <option value="Other">Other</option>
                                <option value="Governmental">Governmental</option>
                            </select>
                        </th>
                        <th>Potential<br>
                            <select class="column-filter">
                                <option value="">All</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </th>
                        <th>Date Registered<br>
                            <input type="date" class="column-filter date-range-filter" placeholder="From" data-filter-type="date-from">
                            <input type="date" class="column-filter date-range-filter" placeholder="To" data-filter-type="date-to">
                        </th>
                        <th>Phone<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>City<br>
                            <select class="column-filter">
                                <option value="">All</option>
                                <option value="Ehsa">Ehsa</option>
                                <option value="Jeddah">Jeddah</option>
                                <option value="Riyadh">Riyadh</option>
                            </select>
                        </th>
                        <th>Country<br>
                            <select class="column-filter">
                                <option value="">All</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                            </select>
                        </th>
                        <th>Payment Terms<br>
                            <select class="column-filter">
                                <option value="">All</option>
                                <option value="IM - Immediate">IM - Immediate</option>
                                <option value="PIA - Payment in advance">PIA - Payment in advance</option>
                                <option value="C.O.D - Cash on delivery">C.O.D - Cash on delivery</option>
                                <option value="E.O.M - End of month">E.O.M - End of month</option>
                            </select>
                        </th>
                        <th>Discount<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>VAT Profile<br>
                            <select class="column-filter">
                                <option value="">All</option>
                                <option value="Standard VAT" selected>Standard VAT</option>
                                <option value="Exempt Supply">Exempt Supply</option>
                                <option value="Zero-Rated Supply">Zero-Rated Supply</option>
                                <option value="Non-VAT Registered">Non-VAT Registered</option>
                                <option value="Flat Rate Scheme">Flat Rate Scheme</option>
                                <option value="Reverse Charge">Reverse Charge</option>
                                <option value="Mixed Supply">Mixed Supply</option>
                            </select>
                        </th>
                        <th>Cash<br>
                            <select class="column-filter">
                                <option value="">All</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </th>
                        <th>TRN/TIN #<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Registration #<br><input type="text" class="column-filter" placeholder="Search..."></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td><input type="checkbox" class="selectCustomer" value="{{ $customer->id }}"></td>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->customer_name }}</td>
                        <td>{{ $customer->arabic_name }}</td>
                        <td>{{ $customer->customer_type }}</td>
                        <td>{{ $customer->potential ? 'Yes' : 'No' }}</td>
                        <td>{{ $customer->date_registered }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->city }}</td>
                        <td>{{ $customer->country }}</td>
                        <td>{{ $customer->payment_terms }}</td>
                        <td>{{ $customer->discount }}</td>
                        <td>{{ $customer->vat_profile }}</td>
                        <td>{{ $customer->cash ? 'Yes' : 'No' }}</td>
                        <td>{{ $customer->trn_tin }}</td>
                        <td>{{ $customer->registration_no }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</main>

@include('customers.create') <!-- المودال -->

<!-- ================== JS ================== -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>



<script>
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

$(document).ready(function () {
    // ==========================
    // إعداد CSRF لجميع طلبات AJAX
    // ==========================
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // ==========================
    // جدول العملاء
    // ==========================
    var table = $('#customersTable').DataTable({
        responsive: true,
        processing: true,
        scrollX:true,
        serverSide: false,
        ajax: "{{ route('customers.data') }}",
        columns: [
            { data: null, render: data => `<input type="checkbox" class="customerCheckbox" value="${data.id}">`, orderable: false },
            { data: 'customer_id' },
            { data: 'customer_name' },
            { data: 'arabic_name' },
            { data: 'customer_type' },
            { data: 'potential', render: d => d ? 'Yes' : 'No' },
            { data: 'date_registered' },
            { data: 'phone' },
            { data: 'city' },
            { data: 'country' },
            { data: 'payment_terms' },
            { data: 'discount' },
            { data: 'vat_profile' },
            { data: 'cash', render: d => d ? 'Yes' : 'No' },
            { data: 'trn_tin' },
            { data: 'registration_no' }
        ]
    });

    var contactsTable;

    // function closeCustomerModal() { $('#customerModal').hide(); }
    // window.closeCustomerModal = closeCustomerModal;


    function deleteSelectedCustomers() {
    const selected = Array.from($('.customerCheckbox:checked')).map(cb => cb.value);

    if (selected.length === 0) {
        Swal.fire({
            title: "تحذير",
            text: "⚠️ اختر عميل واحد على الأقل",
            icon: "warning",
            confirmButtonText: "حسناً"
        });
        return;
    }

    Swal.fire({
        title: "تأكيد الحذف",
        text: `هل أنت متأكد من حذف ${selected.length} عميل(عملاء) محدد(ة)؟ لا يمكن التراجع عن هذا الإجراء.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "نعم، احذفهم",
        cancelButtonText: "إلغاء"
    }).then((result) => {
        if (!result.isConfirmed) return;

        $.ajax({
            url: '/customers/bulk-delete',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                ids: selected,
                _token: $('meta[name="csrf-token"]').attr('content')
            }),
            success: res => {
                if (res.success) {
                    Swal.fire({
                        title: "تم الحذف",
                        text: res.message,
                        icon: "success",
                        confirmButtonText: "حسناً"
                    }).then(() => {
                        table.ajax.reload(null, false);
                    });
                } else {
                    Swal.fire({
                        title: "خطأ",
                        text: res.message || "حدث خطأ أثناء الحذف",
                        icon: "error",
                        confirmButtonText: "حسناً"
                    });
                }
            },
            error: () => {
                Swal.fire({
                    title: "خطأ",
                    text: "❌ خطأ في الاتصال بالسيرفر",
                    icon: "error",
                    confirmButtonText: "حسناً"
                });
            }
        });
    });
}

window.deleteSelectedCustomers = deleteSelectedCustomers;




    function openContactModal() {
        $('#contactForm')[0].reset();
        $('#editingContactId').val('');
        $('#contactModal').show();
    }
    window.openContactModal = openContactModal;

    function closeContactModal() { $('#contactModal').hide(); }
    window.closeContactModal = closeContactModal;

    // ==========================
    // التابات
    // ==========================
    function switchTab(tabName) {
        if(tabName === 'customer'){
            $('#customerTab').show(); $('#contactTab').hide();
            $('#customer-btn').addClass('active'); $('#contact-btn').removeClass('active');
        } else {
            $('#customerTab').hide(); $('#contactTab').show();
            $('#customer-btn').removeClass('active'); $('#contact-btn').addClass('active');
        }
    }
    window.switchTab = switchTab;


   function switchEditTab(tab) {
    // أخفي كل التابات
    $(".form-tab-content").hide();
    $(".tab-buttons button").removeClass("active");

    if (tab === "customer") {
        $("#editCustomerTab").show();
        $("#edit-customer-btn").addClass("active");
    } else if (tab === "contact") {
        $("#editContactTab").show();
        $("#edit-contact-btn").addClass("active");
    }
}

window.switchEditTab = switchEditTab;



// 🔹 عند الضغط على Select All
$('#selectAllCustomers').on('change', function() {
    let rows = table.rows({ 'search': 'applied' }).nodes(); // الصفوف الحالية بعد الفلترة
    $('input.customerCheckbox', rows).prop('checked', this.checked);
});

// 🔹 إذا اختار المستخدم أي checkbox فردي، يحدث Select All تلقائيًا
$('#customersTable tbody').on('change', 'input.customerCheckbox', function() {
    let allChecked = $('.customerCheckbox').length === $('.customerCheckbox:checked').length;
    $('#selectAllCustomers').prop('checked', allChecked);
});



    // ==========================
    // فتح مودال التعديل
    // ==========================
// تعريف الدالة بشكل واضح على نافذة window
window.openEditCustomerModal = function() {
    let selected = $('.customerCheckbox:checked');

    if (selected.length !== 1) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: '⚠️ اختر عميل واحد للتعديل'
        });
        return;
    }

    let customerId = selected.val();
    $.ajax({
        url: `/customers/${customerId}/edit`,
        method: 'GET',
        success: function(res) {
            if (res.status !== 'success') {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: '❌ لم يتم العثور على بيانات العميل'
                });
                return;
            }

            const customer = res.customer;

            // إعادة تعيين النموذج
            $('#editCustomerForm')[0].reset();

            // تعبئة بيانات العميل
            $('#editCustomerId').val(customer.id);
            $('#editCustomerName').val(customer.customer_name);
            $('#editCustomerArabicName').val(customer.arabic_name);
            $('#editCustomerLegalName').val(customer.customer_legal_name);
            $('#editCustomerType').val(customer.customer_type);
            $('#editPotentialCustomer').prop('checked', customer.potential == 1);
            $('#editLegacyAccNo').val(customer.legacy_acc_no);
            $('#editRegistrationDate').val(customer.date_registered);
            $('#editCustomerPhone').val(customer.phone);
            $('#editCustomerCountry').val(customer.country);
            $('#editCustomerArabicLocation').val(customer.arabic_location);
            $('#editCustomerCity').val(customer.city);
            $('#editCustomerDistrict').val(customer.district);
            $('#editCustomerStreet').val(customer.street);
            $('#editCustomerPostCode').val(customer.post_code);
            $('#editCustomerAddressBlock').val(customer.address_block);
            $('#editCustomerPoBox').val(customer.po_box);
            $('#editCustomerBuildingNo').val(customer.building_no);
            $('#editPaymentTerms').val(customer.payment_terms);
            $('#editDiscount').val(customer.discount);
            $('#editIsCash').prop('checked', customer.cash == 1);
            $('#editCreditLimit').val(customer.credit_limit);
            $('#editVatProfile').val(customer.vat_profile);
            $('#editTrnTin').val(customer.trn_tin);
            $('#editRegistrationNo').val(customer.registration_no);
            $('#editRestrictDeliveries').prop('checked', customer.restrict_deliveries == 1);
            $('#editRestrictOrders').prop('checked', customer.restrict_orders == 1);
            $('#editRestrictQuotations').prop('checked', customer.restrict_quotations == 1);

            // تعبئة جدول جهات الاتصال
            populateContactsTableEdit(customer.contacts || []);

            // عرض المودال
            $('#editCustomerModal').show();
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: '❌ حدث خطأ أثناء تحميل بيانات العميل'
            });
        }
    });
}

function populateContactsTable(contacts = []) {
    if (!window.contactsTable) {
        // إنشاء الجدول لو مش موجود
        window.contactsTable = $('#contactsTable').DataTable({
            columns: [
                { data: null, render: data => `<input type="checkbox" class="contact-select" value="${data.id}">`, orderable: false },
                { data: 'id', visible: false },
                { data: 'name' },
                { data: 'email' },
                { data: 'phone' },
                { data: 'mobile' },
                { data: 'position' },
                { data: 'is_primary', render: d => d ? 'Yes' : 'No' }
            ]
        });
    }

    // مسح الجدول القديم
    window.contactsTable.clear();

    // إضافة جهات الاتصال الجديدة
    contacts.forEach(contact => window.contactsTable.row.add(contact));

    window.contactsTable.draw();
}


// بعد تحميل الصفحة
window.contactsTableEdit = $('#contactsTableEdit').DataTable({
    responsive: true,
    columns: [
        {
            data: null,
            orderable: false,
            data: null, render: data => `<input type="checkbox" class="contact-select" value="${data.id}">`, orderable: false
        },
        { data: 'id', visible: false },
        { data: 'name' },
        { data: 'email' },
        { data: 'phone' },
        { data: 'mobile' },
        { data: 'position' },
        {data: 'is_primary', render: d => (d == 1 || d === true || d === '1') ? 'Yes' : 'No'
        }
    ],
    createdRow: function(row, data, dataIndex){
        // هنا نضع data-contact-id مباشرة على <tr>
        $(row).attr('data-contact-id', data.id);
    }
});


function populateContactsTableEdit(contacts) {
    if (!window.contactsTableEdit) {
        console.error("contactsTableEdit is not initialized!");
        return;
    }

    window.contactsTableEdit.clear();  // يمسح الجدول
    window.contactsTableEdit.rows.add(contacts).draw(); // يضيف جهات الاتصال الجديدة
}



    // ==========================
    // حفظ التعديل أو إضافة جديد
    // ==========================
$('#editCustomerForm').on('submit', function(e, closeAfterUpdate = false){
    e.preventDefault();

    let formData = {};
    $('#editCustomerForm').find('input, select').each(function(){
        let name = $(this).attr('name');
        if($(this).is(':checkbox')) formData[name] = $(this).is(':checked') ? 1 : 0;
        else formData[name] = $(this).val();
    });

    formData['_token'] = $('input[name="_token"]').val();

    // تاريخ اليوم إذا فارغ
    if(!formData['date_registered']){
        formData['date_registered'] = new Date().toISOString().split('T')[0];
    }

    // قيم افتراضية
    formData['discount'] = formData['discount'] || 0;
    formData['cash'] = formData['cash'] || 0;
    formData['potential'] = formData['potential'] || 0;

    $.ajax({
        url: "{{ route('customers.store') }}",
        type: "POST",
        data: formData,
        success: function(response){
            Swal.fire({
                title: "نجاح",
                text: "✅ تم حفظ العميل بنجاح",
                icon: "success",
                confirmButtonText: "حسناً"
            }).then(() => {
                if (closeAfterUpdate) {
                    closeEditCustomerModal();
                    closeCustomerModal();
                }

                if(typeof table !== 'undefined'){
                    table.ajax.reload(null, false); // تحديث الجدول بدون ريفرش
                }

                let rowIndex = table.rows().indexes().filter(idx => table.row(idx).data().id == response.customer.id);
                if(rowIndex.length){
                    table.row(rowIndex[0]).data(response.customer).draw(false);
                } else {
                    table.row.add(response.customer).draw(false);
                }
            });
        },
        error: function(xhr){
            let errors = xhr.responseJSON?.errors || {};
            let errorMsg = '';
            for(let field in errors) errorMsg += errors[field] + '\n';
            Swal.fire({ title: "خطأ", text: errorMsg || '❌ حدث خطأ أثناء الحفظ', icon: "error", confirmButtonText: "حسناً" });
        }
    });
});


window.saveCustomer = function(e, closeAfterSave = false) {
    e.preventDefault();

    let customerId = $('#customerId').val();
    let url = customerId ? `/customers/${customerId}` : `/customers`;
    let method = customerId ? "PUT" : "POST";

    let formData = {
        customer_name: $('#customerName').val(),
        arabic_name: $('#customerArabicName').val(),
        customer_legal_name: $('#customerLegalName').val(),
        customer_type: $('#customerType').val(),
        potential: $('#potentialCustomer').is(':checked') ? 1 : 0,
        legacy_acc_no: $('#legacyAccNo').val(),
        date_registered: $('#registrationDate').val(),
        phone: $('#customerPhone').val(),
        country: $('#customerCountry').val(),
        arabic_location: $('#customerArabicLocation').val(),
        city: $('#customerCity').val(),
        district: $('#customerDistrict').val(),
        street: $('#customerStreet').val(),
        post_code: $('#customerPostCode').val(),
        address_block: $('#customerAddressBlock').val(),
        po_box: $('#customerPoBox').val(),
        building_no: $('#customerBuildingNo').val(),
        payment_terms: $('#paymentTerms').val(),
        discount: $('#discount').val(),
        cash: $('#isCash').is(':checked') ? 1 : 0,
        credit_limit: $('#creditLimit').val(),
        vat_profile: $('#vatProfile').val(),
        trn_tin: $('#trnTin').val(),
        registration_no: $('#registrationNo').val(),
        restrict_deliveries: $('#restrictDeliveries').is(':checked') ? 1 : 0,
        restrict_orders: $('#restrictOrders').is(':checked') ? 1 : 0,
        restrict_quotations: $('#restrictQuotations').is(':checked') ? 1 : 0,
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: url,
        type: method,
        data: formData,
        success: function(response) {
            console.log("🚀 Full Response:", response);
            console.log("📌 JSON Stringified:", JSON.stringify(response, null, 2));

            let cid = response.customer?.id || response.customer?.customer_id;
               $('#customer_Id').val(response.customer.id);      // الحقل المخفي
                $('#customerId').val(response.customer.id);      // حقل العرض
            console.log("🔑 Customer ID:", cid);

            Swal.fire({
                title: "نجاح",
                text: "✅ تم حفظ العميل بنجاح!",
                icon: "success",
                confirmButtonText: "حسناً"
            }).then(() => {
                // 🚨 هذا هو الجزء الذي تم تعديله ليصبح أكثر ذكاءً 🚨
                // يقوم بالإغلاق فقط إذا كان زر "Save & Close" هو الذي تم الضغط عليه
                if (closeAfterSave) {
                    closeEditCustomerModal(); // للتحقق من أنك في وضع التعديل
                    closeCustomerModal(); // للتحقق من أنك في وضع الإضافة
                }
     if (typeof table !== 'undefined') {
            table.ajax.reload(null, false); // false = يبقى في نفس الصفحة
        }
                if (typeof table !== 'undefined') {
                    let rowIndex = table.rows().indexes().filter(idx => {
                        let rowData = table.row(idx).data();
                        return rowData.id == cid || rowData.customer_id == cid;
                    });

                    if (rowIndex.length) {
                        table.row(rowIndex[0]).data(response.customer).draw(false);
                    } else {
                        table.row.add(response.customer).draw(false);
                    }
                }


            });
        },
        error: function(xhr) {
            let errors = xhr.responseJSON?.errors || {};
            let errorMsg = '';
            for (let field in errors) {
                errorMsg += errors[field] + '\n';
            }
            Swal.fire("خطأ", errorMsg || '❌ حدث خطأ أثناء الحفظ', "error");
        }
    });
};


    // ------------------------- مسح نموذج جهة الاتصال -------------------------
    window.clearContactForm = function() {
        $('#contactNameAdd').val('');
        $('#contactEmailAdd').val('');
        $('#contactPhoneAdd').val('');
        $('#contactMobileAdd').val('');
        $('#contactPositionAdd').val('');
        $('#isPrimaryContact').prop('checked', false);
    };

// لجدول اتصال خاصة لكل العمي
    $(document).ready(function() {

    // تعريف الجدول مرة واحدة
   window.contactsTable = $('#contactsTable').DataTable({
    columns: [
        { data: null,
          render: data => `<input type="checkbox" class="contact-select" value="${data.id}">`,
          orderable: false },
        { data: 'name' },
        { data: 'email' },
        { data: 'phone' },
        { data: 'mobile' },
        { data: 'position' },
        {
          data: 'is_primary',
          render: function(d) {
              // d ممكن يكون 0, "0", 1, "1"
              return (d === 1 || d === "1") ? 'Yes' : 'No';
          }
        }
    ],
    createdRow: function(row, data) {
        $(row).attr('data-contact-id', data.id);
    }
});



window.saveContactForCustomer = function(modalType = 'add') {
    let customerId = (modalType === 'edit') ? $('#editCustomerId').val() : $('#customerId').val();
    if (!customerId) {
        Swal.fire("خطأ", "❌ الرجاء حفظ العميل أولًا قبل تعديل جهة الاتصال", "error");
        return;
    }

    let contactId = (modalType === 'edit') ? $('#editContactId').val().trim() : $('#editContactIdAdd').val().trim();

    let formData = {
        name: (modalType === 'edit') ? $('#contactNameedit').val() : $('#contactNameAdd').val(),
        email: (modalType === 'edit') ? $('#contactEmailedit').val() : $('#contactEmailAdd').val(),
        phone: (modalType === 'edit') ? $('#contactPhoneedit').val() : $('#contactPhoneAdd').val(),
        mobile: (modalType === 'edit') ? $('#contactMobileedit').val() : $('#contactMobileAdd').val(),
        position: (modalType === 'edit') ? $('#contactPositionedit').val() : $('#contactPositionAdd').val(),
        is_primary: (modalType === 'edit') ? ($('#isPrimaryContact').is(':checked') ? 1 : 0) : ($('#isPrimaryContactAdd').is(':checked') ? 1 : 0),
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    if (!formData.name) {
        Swal.fire("خطأ", "❌ الرجاء إدخال اسم جهة الاتصال", "warning");
        return;
    }

    $.ajax({
        url: contactId ? `/customers/${customerId}/contacts/${contactId}` : `/customers/${customerId}/contacts`,
        type: contactId ? 'PUT' : 'POST',
        data: formData,
        success: function(res) {
            if (!res.contact) {
                Swal.fire("خطأ", "❌ الرد من السيرفر لا يحتوي على بيانات جهة الاتصال", "error");
                return;
            }

            // تأكد من القيمة الصحيحة للـ is_primary
            res.contact.is_primary = (res.contact.is_primary == 1 || res.contact.is_primary === true || res.contact.is_primary === '1') ? 1 : 0;

            Swal.fire(contactId ? "نجاح" : "نجاح", contactId ? "✔️ تم تحديث جهة الاتصال" : "✔️ تم إضافة جهة اتصال جديدة", "success");

            // تحديث الجدول الصحيح
            let table = (modalType === 'edit') ? window.contactsTableEdit : window.contactsTable;

            if (contactId) {
                let rowIndex = table.rows().eq(0).filter(idx => table.row(idx).data().id == contactId);
                if (rowIndex.length) table.row(rowIndex[0]).data(res.contact).draw(false);
            } else {
                table.row.add(res.contact).draw(false);
            }

            // مسح الحقول بعد الحفظ
            if (modalType === 'edit') {
                $('#editContactId').val('');
                $('#contactNameedit, #contactEmailedit, #contactPhoneedit, #contactMobileedit, #contactPositionedit').val('');
                $('#isPrimaryContact').prop('checked', false);
            } else {
                $('#editContactIdAdd').val('');
                $('#contactNameAdd, #contactEmailAdd, #contactPhoneAdd, #contactMobileAdd, #contactPositionAdd').val('');
                $('#isPrimaryContactAdd').prop('checked', false);
            }
        },
        error: function(xhr) {
            let errors = xhr.responseJSON?.errors || {};
            let msg = '';
            for (let f in errors) msg += errors[f] + '\n';
            Swal.fire("خطأ", msg || '❌ حدث خطأ أثناء الحفظ', "error");
        }
    });
};





    // ------------------------- إضافة صف للـ DataTable -------------------------
    window.addContactRowToTable = function(contact) {
        if(!contact) return;
        window.contactsTable.row.add(contact).draw(false);
    };


    // ------------------------- فتح / إغلاق المودال -------------------------
    window.openCustomerModal = function(){
        $('#customerForm')[0].reset();
        $('#customerId').val('');
        $('#customerModal').show();
    };
    // window.closeCustomerModal = function(){
    //     $('#customerModal').hide();
    // };

});
 window.closeEditCustomerModal = function(){
  $('#editCustomerModal').hide();
}


// 🔹 التبديل بين تبويبات المودال (Customer / Contacts)

   function switchEditTab(tab) {
    // أخفي كل التابات
    $(".form-tab-content").hide();
    $(".tab-buttons button").removeClass("active");

    if (tab === "customer") {
        $("#editCustomerTab").show();
        $("#edit-customer-btn").addClass("active");
    } else if (tab === "contact") {
        $("#editContactTab").show();
        $("#edit-contact-btn").addClass("active");
    }
}

// ملء الفورم
function populateContactFormForEdit() {
    let selectedRow = $('#contactsTableEdit tbody input.contact-select:checked').closest('tr');
    if(!selectedRow.length) return alert('Please select a contact first!');
    let contactId = selectedRow.find('td:eq(1)').text(); // عمود ID
        console.log('Contact ID:', contactId); // للتحقق أثناء التطوير

    window.populateContactFormForEdit(contactId);

}

window.populateContactFormForEdit = function(modalType = 'add') {
    // اختيار الجدول حسب نوع المودال
    let table = (modalType === 'edit') ? window.contactsTableEdit : window.contactsTable;
    let $tableSelector = (modalType === 'edit') ? '#contactsTableEdit' : '#contactsTable';

    // البحث عن الشيكبوكس المحدد
    let selectedCheckbox = $(`${$tableSelector} tbody input[type="checkbox"]:checked`);
    if (!selectedCheckbox.length) {
        Swal.fire("تنبيه", "⚠️ الرجاء اختيار جهة اتصال واحدة", "warning");
        return;
    }

    // جلب بيانات الصف
    let rowData = table.row(selectedCheckbox.closest('tr')).data();

    if (!rowData) {
        Swal.fire("خطأ", "❌ لم يتم العثور على بيانات جهة الاتصال", "error");
        return;
    }

    // ✅ ملء الحقول على حسب المودال
    if (modalType === 'edit') {
        $('#editContactId').val(rowData.id);
        $('#contactNameedit').val(rowData.name);
        $('#contactEmailedit').val(rowData.email);
        $('#contactPhoneedit').val(rowData.phone);
        $('#contactMobileedit').val(rowData.mobile);
        $('#contactPositionedit').val(rowData.position);
        $('#isPrimaryContact').prop('checked', rowData.is_primary == 1);
    } else {
        $('#editContactIdAdd').val(rowData.id); // خليه hidden input في مودال الإضافة
        $('#contactNameAdd').val(rowData.name);
        $('#contactEmailAdd').val(rowData.email);
        $('#contactPhoneAdd').val(rowData.phone);
        $('#contactMobileAdd').val(rowData.mobile);
        $('#contactPositionAdd').val(rowData.position);
        $('#isPrimaryContactAdd').prop('checked', rowData.is_primary == 1);
    }
};


//تعديل جهة اتصال



//فلتر جهات اتصال
$('#contactsTable thead .column-filter').on('keyup change', function(){
    let index = $(this).parent().index();
    contactsTable.column(index).search(this.value).draw();
});

// تنظيف فورم جهة الاتصال بعد الحفظ
window.clearContactFormEdit = function() {
    $('#contactNameedit').val('');
    $('#contactEmailedit').val('');
    $('#contactPhoneedit').val('');
    $('#contactMobileedit').val('');
    $('#contactPositionedit').val('');
    $('#isPrimaryContactedit').prop('checked', false);
    $('#editingContactId').val('');
};




// تعريف الدالة على النافذة لتكون متاحة في أي مكان
window.deleteSelectedContacts = function(tableSelector) {
    if (!tableSelector) {
        Swal.fire("خطأ", "⚠️ لم يتم تحديد الجدول", "error");
        return;
    }

    let ids = [];
    $(`${tableSelector} tbody input.contact-select:checked`).each(function() {
        let row = $(this).closest('tr');
        let id = row.attr('data-contact-id'); // ✅ هنا الحل
        if (id) ids.push(id);
    });
    console.log(ids);



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
                    // تحديث الجدول بعد الحذف
                    window[tableSelector.replace('#', '')].rows(function(idx, data, node) {
                        return ids.includes($(node).attr('data-contact-id'));
                    }).remove().draw();
                } else {
                    Swal.fire("خطأ", res.message || "❌ لم يتم الحذف", "error");
                }
            },
            error: function() {
                Swal.fire("خطأ", "❌ خطأ في الاتصال بالسيرفر", "error");
            }
        });
    });
};




function exportCustomersExcelBtn() {
            const ids = Array.from($('.customerCheckbox:checked')).map(cb => cb.value);

    const selectAll = document.getElementById('selectAllCustomers');
    const table = $('#customersTable').DataTable();

    // التحقق إذا لم يتم اختيار أي عميل ولم يتم تفعيل Select All
    if (!selectAll.checked && ids.length === 0) {
        alert('❌ الرجاء اختيار عميل واحد على الأقل أو تفعيل "Select All"');
        return;
    }

    // تجهيز البيانات للإرسال
    const payload = selectAll.checked ? { all: true, ids: [] } : { all: false, ids: ids };

    fetch("{{ route('customers.export.selected') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'customers.xlsx'; // اسم الملف
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    })
    .catch(err => {
        console.error(err);
        alert('❌ فشل التصدير');
    });
}
window.exportCustomersExcelBtn = exportCustomersExcelBtn;



// الدالة الأصلية
// عرف الجداول مرة وحدة عند التحميل
function printCustomersTable() {
    const table = $('#customersTable').DataTable();

    if (!table) {
        alert("❌ لم يتم العثور على الجدول.");
        return;
    }

    let printContents = `
        <style>
            body { font-family: Arial, sans-serif; }
            table { width: 100%; border-collapse: collapse; margin: 20px 0; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
            th { background-color: #f2f2f2; }
            h2 { text-align: center; margin-bottom: 20px; }
        </style>
        <h2>Customers Table</h2>
        <table>
            <thead>
                <tr>`;

    // عناوين الأعمدة (تجاهل أول عمود checkbox)
    $('#customersTable thead th').each(function (index) {
        if (index === 0) return;
        printContents += `<th>${$(this).text().split('\n')[0].trim()}</th>`;
    });

    printContents += `</tr></thead><tbody>`;

    // الصفوف (فقط المعروضة حالياً بالبحث/الفلترة)
    table.rows({ search: 'applied' }).every(function () {
        const rowData = this.data();
        printContents += `<tr>`;

        // rowData ممكن يكون Array أو Object -> نحوله Array بالطريقة الصحيحة
        if (Array.isArray(rowData)) {
            rowData.forEach((cell, i) => {
                if (i === 0) return; // تجاهل checkbox
                printContents += `<td>${cell || ''}</td>`;
            });
        } else {
            // إذا كان Object (في حالة Ajax sources)
            let i = 0;
            for (let key in rowData) {
                if (i === 0) { i++; continue; } // skip checkbox
                printContents += `<td>${rowData[key] || ''}</td>`;
                i++;
            }
        }

        printContents += `</tr>`;
    });

    printContents += `</tbody></table>`;

     const printWindow = window.open('', '_blank');
    printWindow.document.write(printContents);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
    showAlert("تم إعداد الجدول للطباعة!", "success");
}

// خليها global
window.printCustomersTable = function() {
    printTable('customersTable', 'قائمة العملاء');
};
window.printContactsTable = function() {
    printTable('contactsTable', 'قائمة جهات الاتصال');
};



// تأكد أنها global
window.printCustomersTable = printCustomersTable;
window.printContactsTable = printContactsTable;

function goToCustomerFiles(button) {
    let selected = $('.customerCheckbox:checked');

    if (selected.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: '⚠️ الرجاء اختيار عميل أولاً'
        });
        return;
    }

    if (selected.length > 1) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: '⚠️ الرجاء اختيار عميل واحد فقط'
        });
        return;
    }

    let customerId = selected.val();

    // استبدال الـ :id في الرابط بالـ customerId الحقيقي
    let urlTemplate = $(button).data('url');
    let url = urlTemplate.replace(':id', customerId);

    // توجه للصفحة مباشرة
    window.location.href = url;
}

window.goToCustomerFiles = goToCustomerFiles;


});



/**
 * هذه الدالة مسؤولة عن إغلاق النافذة المنبثقة لإضافة عميل جديد
 * وإعادة تهيئة النموذج.
 */
/**
 * هذه الدالة مسؤولة عن إغلاق النافذة المنبثقة لإضافة عميل جديد
 * وإعادة تهيئة النموذج.
 */
window.closeCustomerModal = function() {
    // 1. إخفاء النافذة المنبثقة.
    // 💡 نستخدم كلتا الطريقتين لضمان الإخفاء بغض النظر عن طريقة العرض في CSS
    $('#customerModal').removeClass('active').hide();

    // 2. إعادة تعيين النموذج (مسح جميع الحقول)
    $('#customerForm')[0].reset();

    // 3. إعادة تعيين الحقول المخفية أو الـ ID
    $('#customerId').val('');

    // 4. إعادة تعيين التبويبات إلى التبويب الأول (Customer Tab)
    $('#customer-btn').addClass('active');
    $('#contact-btn').removeClass('active');

    // 5. مسح جدول جهات الاتصال إذا كان يظهر
    if (window.contactsTable) {
        window.contactsTable.clear().draw();
    }
};



</script>




@endsection
