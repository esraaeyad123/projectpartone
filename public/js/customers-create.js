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
        serverSide: false,
        ajax: "{{ route('customers.data') }}",
        dom: 'Bfrtip',
        buttons: [
            { extend: 'excelHtml5', text: 'Export Excel', title: 'Customers Export', exportOptions: { columns: ':visible' } },
            { extend: 'print', text: 'Print', title: 'Customers List', exportOptions: { columns: ':visible' } }
        ],
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

    // ==========================
    // فتح مودال العميل (إضافة / تعديل)
    // ==========================
    function openCustomerModal(customer = null) {
        $('#customerForm')[0].reset();

        if (customer) {
            Object.keys(customer).forEach(key => {
                let el = $(`#${key}`);
                if(el.length){
                    if(el.is(':checkbox')) el.prop('checked', customer[key] == 1);
                    else el.val(customer[key]);
                }
            });
        } else {
            $('#customerId').val(''); // إضافة جديد
        }

        $('#customerModal').show();
    }
    window.openCustomerModal = openCustomerModal;

    function closeCustomerModal() { $('#customerModal').hide(); }
    window.closeCustomerModal = closeCustomerModal;


    function deleteSelectedCustomers() {
        const selected = Array.from($('.customerCheckbox:checked')).map(cb => cb.value);
        if(selected.length === 0) { alert('⚠️ اختر عميل واحد على الأقل'); return; }
        if(!confirm('هل أنت متأكد من حذف العملاء المحددين؟')) return;

        $.ajax({
            url: '/customers/bulk-delete',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ ids: selected, _token: $('meta[name="csrf-token"]').attr('content') }),
            success: res => {
                if(res.success){
                    alert(res.message);
                    table.ajax.reload(null, false);
                }
            }
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


// إغلاق المودال
function closeEditCustomerModal() {
  $('#editCustomerModal').hide();
}

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

    let selected = $('.customerCheckbox:checked'); if(selected.length !== 1)
     { alert('⚠️ اختر عميل واحد للتعديل'); return; }

        let customerId = selected.val();
    $.ajax({
        url: `/customers/${customerId}/edit`,
        method: 'GET',
        success: function(res) {
            if (res.status !== 'success') {
                alert('❌ لم يتم العثور على بيانات العميل');
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
            alert('❌ حدث خطأ أثناء تحميل بيانات العميل');
        }
    });
}
function populateContactsTable(contacts = []) {
    if (!window.contactsTable) {
        // إنشاء الجدول لو مش موجود
        window.contactsTable = $('#contactsTable').DataTable({
            columns: [
                { data: null, render: () => `<input type="checkbox" class="contact-select">`, orderable: false },
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
        { data: null, orderable: false, render: function(){ return '<input type="checkbox" class="contact-select">'; } },
                { data: 'id', visible: false },
        { data: 'name' },
        { data: 'email' },
        { data: 'phone' },
        { data: 'mobile' },
        { data: 'position' },
        { data: 'is_primary', render: function(data){ return data ? 'Yes' : 'No'; } }
    ]
});

function populateContactsTableEdit(contacts) {
    if (!window.contactsTableEdit) {
        console.error("contactsTableEdit is not initialized!");
        return;
    }

    window.contactsTableEdit.clear();  // يمسح الجدول
    window.contactsTableEdit.rows.add(contacts).draw(); // يضيف جهات الاتصال الجديدة
}





    window.closeEditCustomerModal = function() {
        $('#editCustomerModal').hide();
    };


    // ==========================
    // حفظ التعديل أو إضافة جديد
    // ==========================
    $('#editCustomerForm').on('submit', function(e){
        e.preventDefault();
        let formData = {};
        $('#editCustomerForm').find('input, select').each(function(){
            let name = $(this).attr('name');
            if($(this).is(':checkbox')) formData[name] = $(this).is(':checked') ? 1 : 0;
            else formData[name] = $(this).val();
        });
        formData['_token'] = $('input[name="_token"]').val();

        $.ajax({
            url: "{{ route('customers.store') }}",
            type: "POST",
            data: formData,
            success: function(response){
                alert('✅ تم حفظ العميل بنجاح');
                closeEditCustomerModal();

                // تحديث الصف مباشرة بدون إعادة تحميل كامل الجدول
                let rowIndex = table.rows().indexes().filter(idx => table.row(idx).data().id == response.customer.id);
                if(rowIndex.length){
                    table.row(rowIndex[0]).data(response.customer).draw(false);
                } else {
                    table.row.add(response.customer).draw(false);
                }
            },
            error: function(xhr){
                let errors = xhr.responseJSON?.errors || {};
                let errorMsg = '';
                for(let field in errors) errorMsg += errors[field] + '\n';
                alert(errorMsg || '❌ حدث خطأ أثناء الحفظ');
            }
        });
    });
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

window.switchEditTab = switchEditTab;










window.saveCustomer = function(event, closeModal = true) {
    event.preventDefault();

    let customerId = $('#customerId').val(); // لو فاضي معناها عميل جديد
    let url = customerId
        ? `/customers/${customerId}`   // تحديث
        : `/customers`;                // إضافة جديد

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
        _token: $('meta[name="csrf-token"]').attr('content') // تأكد إنك ضايف meta csrf
    };

    $.ajax({
        url: url,
        type: method,
        data: formData,
        success: function(response) {
            alert('✅ Customer saved successfully!');
$('#customerId').val(response.customer.id);
 // ✅ ملء جدول جهات الاتصال تلقائيًا
        populateContactsTable(response.customer.contacts || []);


            if(closeModal) closeCustomerModal();
            if (typeof table !== 'undefined') {
                table.ajax.reload(null, false);
            }
        },
        error: function(xhr) {
            let errors = xhr.responseJSON?.errors || {};
            let errorMsg = '';
            for(let field in errors){
                errorMsg += errors[field] + '\n';
            }
            alert(errorMsg || '❌ Error saving customer');
        }
    });
}













    // ------------------------- مسح نموذج جهة الاتصال -------------------------
    window.clearContactForm = function() {
        $('#contactName').val('');
        $('#contactEmail').val('');
        $('#contactPhone').val('');
        $('#contactMobile').val('');
        $('#contactPosition').val('');
        $('#isPrimaryContact').prop('checked', false);
    };

    // ------------------------- فتح / إغلاق المودال -------------------------
    window.openCustomerModal = function(){
        $('#customerForm')[0].reset();
        $('#customerId').val('');
        $('#customerModal').show();
    };
    window.closeCustomerModal = function(){
        $('#customerModal').hide();
    };




    $(document).ready(function() {

    // تعريف الجدول مرة واحدة
    window.contactsTable = $('#contactsTable').DataTable({
        columns: [
            { data: null, render: data => `<input type="checkbox" class="contact-select">`, orderable: false },
            { data: 'id', visible: false }, // رقم الاتصال مخفي
            { data: 'name' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'mobile' },
            { data: 'position' },
            { data: 'is_primary', render: d => d ? 'Yes' : 'No' }
        ]
    });

    // تعريف الدالة **مرة واحدة وواضحة**

   window.saveContactForCustomer = function() {
    let customerId = $('#customerId').val();

    if(!customerId) return alert('❌ الرجاء حفظ العميل أولًا قبل إضافة جهة اتصال');

    let formData = {
        name: $('#contactNameAdd').val(),
        email: $('#contactEmailAdd').val(),
        phone: $('#contactPhoneAdd').val(),
        mobile: $('#contactMobileAdd').val(),
        position: $('#contactPositionAdd').val(),
        is_primary: $('#isPrimaryContactAdd').is(':checked') ? 1 : 0,
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: `/customers/${customerId}/contacts`,
        type: 'POST',
        data: formData,
        success: function(res) {
            if(res.contact){
                alert('✔️ تم حفظ جهة الاتصال');

                // إضافة جهة الاتصال للجدول
                window.contactsTable.row.add(res.contact).draw(false);

                // مسح الفورم
                $('#contactNameAdd').val('');
                $('#contactEmailAdd').val('');
                $('#contactPhoneAdd').val('');
                $('#contactMobileAdd').val('');
                $('#contactPositionAdd').val('');
                $('#isPrimaryContactAdd').prop('checked', false);
            } else {
                alert('❌ الرد من السيرفر لا يحتوي على بيانات جهة الاتصال');
            }
        },
        error: function(xhr) {
            let errors = xhr.responseJSON?.errors || {};
            let msg = '';
            for(let f in errors) msg += errors[f] + '\n';
            alert(msg || '❌ حدث خطأ أثناء حفظ جهة الاتصال');
        }
    });
};




    // ------------------------- إضافة صف للـ DataTable -------------------------
    window.addContactRowToTable = function(contact) {
        if(!contact) return;
        window.contactsTable.row.add(contact).draw(false);
    };

    // ------------------------- مسح نموذج جهة الاتصال -------------------------
    window.clearContactForm = function() {
        $('#contactName').val('');
        $('#contactEmail').val('');
        $('#contactPhone').val('');
        $('#contactMobile').val('');
        $('#contactPosition').val('');
        $('#isPrimaryContact').prop('checked', false);
    };

    // ------------------------- فتح / إغلاق المودال -------------------------
    window.openCustomerModal = function(){
        $('#customerForm')[0].reset();
        $('#customerId').val('');
        $('#customerModal').show();
    };
    window.closeCustomerModal = function(){
        $('#customerModal').hide();
    };

});


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



window.populateContactFormForEdit = function() {
    let selectedCheckbox = $('#contactsTableEdit tbody input.contact-select:checked');
    if(!selectedCheckbox.length) return alert('⚠️ اختر جهة اتصال واحدة');

    let rowData = window.contactsTableEdit.row(selectedCheckbox.closest('tr')).data();

    // ملء الفورم
    $('#editContactId').val(rowData.id);
    $('#contactNameedit').val(rowData.name);
    $('#contactEmailedit').val(rowData.email);
    $('#contactPhoneedit').val(rowData.phone);
    $('#contactMobileedit').val(rowData.mobile);
    $('#contactPositionedit').val(rowData.position);
    $('#isPrimaryContact').prop('checked', rowData.is_primary);
};

window.saveContactForCustomerEdit = function() {
    // 1️⃣ جلب رقم العميل
    let customerId = $('#editCustomerId').val();
    if (!customerId) return alert('❌ الرجاء حفظ العميل أولًا قبل تعديل جهة الاتصال');

    // 2️⃣ جلب contactId من hidden input
    let contactId = $('#editContactId').val();
    if (!contactId) return alert('❌ لم يتم العثور على رقم جهة الاتصال');

    // 3️⃣ التحقق من إدخال الاسم
    let contactName = $('#contactNameedit').val().trim();
    if(!contactName) return alert('❌ الرجاء إدخال اسم جهة الاتصال');

    // 4️⃣ تحضير البيانات للإرسال
    let formData = {
        name: contactName,
        email: $('#contactEmailedit').val(),
        phone: $('#contactPhoneedit').val(),
        mobile: $('#contactMobileedit').val(),
        position: $('#contactPositionedit').val(),
        is_primary: $('#isPrimaryContact').is(':checked') ? 1 : 0,
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    // 5️⃣ إرسال الطلب (تحديث فقط)
    $.ajax({
        url: `/customers/${customerId}/contacts/${contactId}`,
        type: 'PUT',
        data: formData,
        success: function(res) {
            if(res.contact){
                alert('✔️ تم تحديث جهة الاتصال');

                // 🔹 تحديث الصف داخل الجدول مباشرة (بدون reload)
                let rowSelector = `#contactRow_${contactId}`;
                let tableRow = window.contactsTableEdit.row(rowSelector);

                if(tableRow.node()){
                    tableRow.data(res.contact).draw(false);
                }

                clearContactForm(); // مسح الفورم بعد الحفظ
            } else {
                alert('❌ الرد من السيرفر لا يحتوي على بيانات جهة الاتصال');
            }
        },
        error: function(xhr){
            let errors = xhr.responseJSON?.errors || {};
            let msg = '';
            for(let f in errors) msg += errors[f] + '\n';
            alert(msg || '❌ حدث خطأ أثناء التحديث');
        }
    });
};





function populateContactFormForEdit() {
    let selectedRow = $('#contactsTableEdit tbody input.contact-select:checked').closest('tr');
    if(!selectedRow.length) return alert('Please select a contact first!');
    let contactId = selectedRow.find('td:eq(1)').text(); // عمود ID
        console.log('Contact ID:', contactId); // للتحقق أثناء التطوير

    window.populateContactFormForEdit(contactId);

}





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


function deleteContact(id){
    if(!confirm('Are you sure?')) return;
    $.ajax({
        url: `/contacts/${id}`,
        type: 'DELETE',
        data: {_token: $('meta[name="csrf-token"]').attr('content')},
        success: function(res){
            if(res.success){
                alert(res.message);
                contactsTable.ajax.reload(null, false);
            }
        }
    });
}


function clearContactForm() {
    document.getElementById('contactPositionedit').value = '';
    document.getElementById('contactEmailedit').value = '';
    document.getElementById('contactPhoneededit').value = '';
    document.getElementById('contactMobileedit').value = '';
    document.getElementById('contactPosition').value = '';
    document.getElementById('isPrimaryContact').checked = false;
    document.getElementById('editingContactId').value = ''; // مسح ID التعديل
}






});


