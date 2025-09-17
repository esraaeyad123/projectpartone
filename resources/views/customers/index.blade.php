@extends('layouts.app')
@section('title', __('Customers Management'))
@section('content')
@include('customers.create') <!-- المودال -->

<main class="main-content">
    <section id="customers-section" class="section-content active">

        <!-------------------------------------------Start Buttons-------------------------------------------->
        <div class="icon-toolbar">
            <div>
                <button title="Add" onclick="openCustomerModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                <button title="Edit" onclick="openEditCustomerModal()"  class="btn-icon"><i class="fas fa-pen"></i></button>
                <button title="Delete" onclick="deleteSelectedCustomers()" class="btn-icon"><i class="fas fa-trash"></i></button>
            </div>

        <div class="icon-separator"></div>
            <div>
                <button title="File Manager" class="btn-icon" data-url="{{ route('customer-files.index', ['customerId' => ':id']) }}" onclick="goToCustomerFiles(this)"><i class="fas fa-folder-open"></i></button>
                <button title="Export to Excel" class="btn-icon" id="exportCustomersExcelBtn" onclick="exportCustomersExcelBtn()"><i class="fa-solid fa-table"></i></button>
                <button title="Print" class="btn-icon" onclick="printCustomersTable()"><i class="fas fa-print"></i></button>
            </div>

        </div>
        <!-------------------------------------------End Buttons----------------------------------------------->
        <!-------------------------------------------Start Table----------------------------------------------->
        <div class="table-responsive-container">
            <table id="customersTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                <thead> selectCustomer
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
                            <span>From:</span><input type="text" class="column-filter date-range-filter flatpickr-input" data-filter-type="date-from">
                            <span>To:</span><input type="text" class="column-filter date-range-filter flatpickr-input" data-filter-type="date-to">
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
        <!-------------------------------------------End Table------------------------------------------------->
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

<!-------------------------------------------Start Script-------------------------------------------->
<script>

    // تهيئة AJAX لإرسال رمز CSRF مع كل طلب
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }});


    //======================================================================
    // هنا يتم تعريف الفلاتر المخصصة قبل تهيئة الجدول
    //======================================================================
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            // تحقق من أن هذه الفلترة تنطبق على جدول 'customersTable' فقط.
            if (settings.nTable.id !== 'customersTable') {
                return true;
            }

            // الحصول على قيم التاريخ من حقول البحث.
            const fromDateStr = $('#customersTable thead input[data-filter-type="date-from"]').val();
            const toDateStr = $('#customersTable thead input[data-filter-type="date-to"]').val();
            const rowDateStr = data[6]; // عمود 'Date Registered'

            // إذا كانت حقول الفلترة فارغة، لا تقم بالفلترة.
            if (!fromDateStr && !toDateStr) {
                return true;
            }

            // تحويل التواريخ إلى كائنات Date للقيام بالمقارنة.
            const fromDate = fromDateStr ? new Date(fromDateStr) : null;
            const toDate = toDateStr ? new Date(toDateStr) : null;
            const rowDate = rowDateStr ? new Date(rowDateStr) : null;

            // إذا كان تاريخ الصف غير صالح، لا تقم بعرضه.
            if (!rowDate || isNaN(rowDate.getTime())) {
                return false;
            }

            // تطبيق الفلترة بناءً على التاريخ المدخل.
            if (fromDate && !toDate) {
                return rowDate >= fromDate;
            }
            if (!fromDate && toDate) {
                return rowDate <= toDate;
            }
            if (fromDate && toDate) {
                return rowDate >= fromDate && rowDate <= toDate;
            }

            // في حال وجود خطأ غير متوقع، لا تقم بعرض الصف.
            return false;
        }
    );
    //======================================================================
    //======================================================================
    //======================================================================
    // هذا هو الكود المسؤول عن تهيئة DataTables عند تحميل الصفحة
    //======================================================================

    // تهيئة جدول DataTables والبحث بالاعمدة
    $(document).ready(function() {

        // تهيئة Flatpickr على حقول التاريخ
        flatpickr(".flatpickr-input", {
            dateFormat: "Y-m-d",
            locale: "en"         // يفرض استخدام اللغة الإنجليزية
        });

        var table = $('#customersTable').DataTable({
            responsive: true,
            processing: true,
            scrollX: true,
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

        // ربط حدث تغيير حقول التاريخ لإعادة رسم الجدول وتفعيل الفلتر المخصص
        $('#customersTable thead input[data-filter-type="date-from"], #customersTable thead input[data-filter-type="date-to"]').on('change', function() {
            table.draw();
        });

        // ---------------------------------------------------------------------------------------
        // الكود الجديد لتفعيل البحث في الأعمدة

        // استنساخ صف الرؤوس لإنشاء صف البحث
        $('#customersTable thead tr').clone(true).appendTo('#customersTable thead');

        // منع البحث في عمود صندوق الاختيار
        $('#customersTable thead tr:eq(1) th:eq(0)').empty();

        // تطبيق الفلترة على كل حقل بحث
        table.columns().every(function() {
            var that = this;
            var column = table.column(this.index());

            // ⛔️ تجاوز العمود الأول (الذي يحتوي على مربع الاختيار)
            if (this.index() === 0) {
                return;
            }

            // ⛔️ تجاوز عمود التاريخ
            if ($(this.header()).find('input[data-filter-type="date-from"]').length > 0) {
                return;
            }
            // معالجة البحث لجميع أنواع الحقول
            $('input, select', this.header()).on('keyup change', function() {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                // إذا كان العمود هو "Potential" أو "Cash"، نقوم بتحويل القيمة
                if (column.index() === 5) {
                if (val === "Yes") {
                        column.search('1', false, false).draw();
                    } else if (val === "No") {
                        column.search('0', false, false).draw();
                    } else {
                        column.search('').draw();
                    }
                }
                // إذا كان العمود هو "Cash" (العمود رقم 13)، قم بتحويل قيمة الفلتر
                else if (column.index() === 13) {
                    if (val === "Yes") {
                        column.search('1', false, false).draw();
                    } else if (val === "No") {
                        column.search('0', false, false).draw();
                    } else {
                        column.search('').draw();
                    }
                }

                if (column.search() !== val) {
                    column.search(val).draw();
                }
            });
        });


        // ربط الحدث بحقول التاريخ لإعادة رسم الجدول عند التغيير
        $('#customersTable thead input[data-filter-type="date-from"], #customersTable thead input[data-filter-type="date-to"]').on('change', function() {
            table.draw();
        });
    // ---------------------------------------------------------------------------------------

    // ---------------------------------------------------------------------------------------
    // --------------------Select All & Update 'select all' button----------------------------
    $('#selectAllCustomers').on('change', function() {
        let rows = table.rows({ 'search': 'applied' }).nodes(); // الصفوف الحالية بعد الفلترة
        $('input.customerCheckbox', rows).prop('checked', this.checked);
    });
    $('#customersTable tbody').on('change', 'input.customerCheckbox', function() {
        let allChecked = $('.customerCheckbox').length === $('.customerCheckbox:checked').length;
        $('#selectAllCustomers').prop('checked', allChecked);
    });
    // دوال التأكيد والتنبيه ---------------------------------------------------------------
    function showAlert(message, type) {
        Swal.fire({
            title: type === 'success' ? 'Success!' : (type === 'error' ? 'Error!' : 'Warning!'),
            text: message,
            icon: type,
            confirmButtonText: 'OK'
        });
    }
    // ---------------------------------------------------------------------------------------
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
    // ---------------------------------------------------------------------------------------
    window.deleteSelectedCustomers = function() {
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
    // ---------------------------------------------------------------------------------------
    window.openContactModal = function() {
        $('#contactForm')[0].reset();
        $('#editingContactId').val('');
        $('#contactModal').show();
    }
    // ---------------------------------------------------------------------------------------
    window.closeContactModal = function() {
        $('#contactModal').hide();
    }
    // ---------------------------------------------------------------------------------------
    window.switchTab = function(tabName) {
        if(tabName === 'customer'){
            $('#customerTab').show(); $('#contactTab').hide();
            $('#customer-btn').addClass('active'); $('#contact-btn').removeClass('active');
        } else {
            $('#customerTab').hide(); $('#contactTab').show();
            $('#customer-btn').removeClass('active'); $('#contact-btn').addClass('active');
        }
    }
    // ---------------------------------------------------------------------------------------
    window.switchEditTab = function(tab) {
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
    // ---------------------------------------------------------------------------------------
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
    // ---------------------------------------------------------------------------------------
    window.updateCustomer = function(event, closeModal = true) {
        event.preventDefault(); // منع الإرسال التلقائي

        let formData = {};
        $('#editCustomerForm').find('input, select').each(function(){
            let name = $(this).attr('name');
            if($(this).is(':checkbox')) formData[name] = $(this).is(':checked') ? 1 : 0;
            else formData[name] = $(this).val();
        });
        formData['_token'] = $('input[name="_token"]').val();

        $.ajax({
            url: "{{ route('customers.store') }}",
            type: "POST", // يفضل استخدام PUT/PATCH للتحديث، ولكن الكود الحالي يستخدم POST
            data: formData,
            success: function(response){
                Swal.fire({
                    title: "نجاح",
                    text: "✅ تم تحديث العميل بنجاح",
                    icon: "success",
                    confirmButtonText: "حسناً"
                }).then(() => {
                    if (closeModal) {
                        closeEditCustomerModal();
                    }
                    // تحديث الصف في الجدول
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

                Swal.fire({
                    title: "خطأ",
                    text: errorMsg || '❌ حدث خطأ أثناء الحفظ',
                    icon: "error",
                    confirmButtonText: "حسناً"
                });
            }
        });
    };
    // ---------------------------------------------------------------------------------------
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
    // ---------------------------------------------------------------------------------------
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
    // ---------------------------------------------------------------------------------------
    window.closeEditCustomerModal = function(){
        $('#editCustomerModal').hide();
    }
    // ---------------------------------------------------------------------------------------
    window.goToCustomerFiles = function(button) {
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
    // ---------------------------------------------------------------------------------------
    // لجدول جهات اتصال خاص بالعميل
    $(document).ready(function() {

        // --------------------------------------------------------
        // ✅ تعريف جداول البيانات (DataTables)
        // --------------------------------------------------------
        // جدول جهات الاتصال الخاصة بإنشاء عميل جديد
        window.contactsTable = $('#contactsTable').DataTable({
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
                    render: d => (d === 1 || d === "1") ? 'Yes' : 'No'
                }
            ],
            createdRow: function(row, data) {
                $(row).attr('data-contact-id', data.id);
            }
        });

        // جدول جهات الاتصال الخاصة بتعديل عميل
        window.contactsTableEdit = $('#contactsTableEdit').DataTable({
            responsive: true,
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

        // فلترة جهات الاتصال في جدول جهات الاتصال
        $('#contactsTableEdit thead .column-filter').on('keyup change', function(){
            let index = $(this).parent().index();
            window.contactsTableEdit.column(index).search(this.value).draw();
        });

        // --------------------------------------------------------
        // ✅ دوال مساعدة
        // --------------------------------------------------------
        // دالة لفتح نموذج إضافة عميل جديد
        window.openCustomerModal = function() {
            $('#customerForm')[0].reset();
            $('#customerId').val('');
            $('#customerModal').show();
        };

        // دالة لتعبئة جدول جهات الاتصال في وضع التعديل
        window.populateContactsTableEdit = function(contacts) {
            if (!window.contactsTableEdit) {
                console.error("contactsTableEdit is not initialized!");
                return;
            }
            window.contactsTableEdit.clear();
            window.contactsTableEdit.rows.add(contacts).draw();
        };

        // دالة لملء فورم جهة الاتصال لغرض التعديل
        window.populateContactFormForEdit = function(modalType = 'add') {
            let table = (modalType === 'edit') ? window.contactsTableEdit : window.contactsTable;
            let $tableSelector = (modalType === 'edit') ? '#contactsTableEdit' : '#contactsTable';
            let selectedCheckbox = $(`${$tableSelector} tbody input[type="checkbox"]:checked`);

            if (selectedCheckbox.length === 0) {
                Swal.fire("تنبيه", "⚠️ الرجاء اختيار جهة اتصال واحدة", "warning");
                return;
            }

            let rowData = table.row(selectedCheckbox.closest('tr')).data();

            if (!rowData) {
                Swal.fire("خطأ", "❌ لم يتم العثور على بيانات جهة الاتصال", "error");
                return;
            }

            // ملء الحقول حسب نوع المودال
            if (modalType === 'edit') {
                $('#editContactId').val(rowData.id);
                $('#contactNameedit').val(rowData.name);
                $('#contactEmailedit').val(rowData.email);
                $('#contactPhoneedit').val(rowData.phone);
                $('#contactMobileedit').val(rowData.mobile);
                $('#contactPositionedit').val(rowData.position);
                $('#isPrimaryContact').prop('checked', rowData.is_primary == 1);
            } else {
                $('#editContactIdAdd').val(rowData.id);
                $('#contactNameAdd').val(rowData.name);
                $('#contactEmailAdd').val(rowData.email);
                $('#contactPhoneAdd').val(rowData.phone);
                $('#contactMobileAdd').val(rowData.mobile);
                $('#contactPositionAdd').val(rowData.position);
                $('#isPrimaryContactAdd').prop('checked', rowData.is_primary == 1);
            }
        };

        // دالة لمسح حقول فورم جهة الاتصال (سواء للإضافة أو التعديل)
        window.clearContactForm = function(modalType = 'add') {
            if (modalType === 'edit') {
                $('#editContactId').val('');
                $('#contactNameedit, #contactEmailedit, #contactPhoneedit, #contactMobileedit, #contactPositionedit').val('');
                $('#isPrimaryContact').prop('checked', false);
            } else {
                $('#editContactIdAdd').val('');
                $('#contactNameAdd, #contactEmailAdd, #contactPhoneAdd, #contactMobileAdd, #contactPositionAdd').val('');
                $('#isPrimaryContactAdd').prop('checked', false);
            }
        };

        // دالة لحفظ أو تحديث جهة الاتصال
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

                    res.contact.is_primary = (res.contact.is_primary == 1 || res.contact.is_primary === true || res.contact.is_primary === '1') ? 1 : 0;
                    Swal.fire(contactId ? "نجاح" : "نجاح", contactId ? "✔️ تم تحديث جهة الاتصال" : "✔️ تم إضافة جهة اتصال جديدة", "success");

                    let table = (modalType === 'edit') ? window.contactsTableEdit : window.contactsTable;

                    if (contactId) {
                        let rowIndex = table.rows().eq(0).filter(idx => table.row(idx).data().id == contactId);
                        if (rowIndex.length) table.row(rowIndex[0]).data(res.contact).draw(false);
                    } else {
                        table.row.add(res.contact).draw(false);
                    }

                    // مسح الحقول بعد الحفظ
                    window.clearContactForm(modalType);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON?.errors || {};
                    let msg = '';
                    for (let f in errors) msg += errors[f] + '\n';
                    Swal.fire("خطأ", msg || '❌ حدث خطأ أثناء الحفظ', "error");
                }
            });
        };

        // دالة لحذف جهات الاتصال المحددة
        window.deleteSelectedContacts = function(tableSelector) {
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
                            let table = window[tableSelector.replace('#', '')];
                            if (table) {
                                table.rows(function(idx, data, node) {
                                    return ids.includes($(node).attr('data-contact-id'));
                                }).remove().draw();
                            }
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

        // --------------------------------------------------------
        // ✅ مستمعات الأحداث (Event Listeners)
        // --------------------------------------------------------
        // فلترة جهات الاتصال في جدول جهات الاتصال
        $('#contactsTable thead .column-filter').on('keyup change', function(){
            let index = $(this).parent().index();
            window.contactsTable.column(index).search(this.value).draw();
        });
    });
    // ----------------------------------------------------------------------
    window.toggleAllContacts = function(source, tableId) {
        const table = $(`#${tableId}`).DataTable();
        const rows = table.rows({ 'search': 'applied' }).nodes();
        $('input.contact-select', rows).prop('checked', source.checked);
    };
    //  دوال التصدير للاكسل
    // ----------------------------------------------------------------------
    window.exportCustomersExcelBtn = function() {
        const tableElement = document.getElementById('customersTable');
        const table = $(tableElement).DataTable();

        const selectedCheckboxes = table.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            rowsToProcess = table.rows({ 'search': 'applied' }).nodes();
        }

        // 1. بناء البيانات لـ SheetJS
        const data = [];
        const header = [];
        $(tableElement).find('thead th').each(function() {
            if ($(this).find('input[type="checkbox"]').length === 0) {
                header.push($(this).text().trim());
            }
        });
        data.push(header);

        $(rowsToProcess).each(function() {
            const rowData = [];
            $(this).find('td').each(function() {
                if ($(this).find('input[type="checkbox"]').length === 0) {
                    rowData.push($(this).text().trim());
                }
            });
            data.push(rowData);
        });

        // 2. إنشاء ورقة العمل والمصنف
        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "العملاء");

        // 3. كتابة وتنزيل الملف بصيغة XLSX
        XLSX.writeFile(wb, "customers-data.xlsx");

        showAlert('تم تصدير البيانات بنجاح إلى ملف Excel.', 'success');
    };
    // ----------------------------------------------------------------------
    window.exportContactsExcelBtn = function() {
        const tableElement = document.getElementById('contactsTable');
        if (!tableElement) {
            console.error('Table with ID "contactsTable" not found.');
            return;
        }
        const table = $(tableElement).DataTable();

        // 1. العثور على الصفوف التي سيتم تصديرها
        const selectedCheckboxes = table.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            // إذا كان هناك صفوف محددة، قم بمعالجة تلك الصفوف فقط
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            // إذا لم يتم تحديد أي شيء، قم بمعالجة جميع الصفوف المرئية
            rowsToProcess = table.rows({ 'search': 'applied' }).nodes();
        }

        // 2. بناء البيانات لـ SheetJS
        const data = [];
        const header = [];
        $(tableElement).find('thead th').each(function() {
            if ($(this).find('input[type="checkbox"]').length === 0) {
                header.push($(this).text().trim());
            }
        });
        data.push(header);

        $(rowsToProcess).each(function() {
            const rowData = [];
            $(this).find('td').each(function() {
                if ($(this).find('input[type="checkbox"]').length === 0) {
                    rowData.push($(this).text().trim());
                }
            });
            data.push(rowData);
        });

        // 3. إنشاء ورقة العمل والمصنف
        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Contacts');

        // 4. كتابة وتنزيل الملف بصيغة XLSX
        XLSX.writeFile(wb, 'contacts_data.xlsx');
    };
    // ----------------------------------------------------------------------
    window.exportContactsExcelEditBtn = function() {
        const tableElement = document.getElementById('contactsTableEdit');
        if (!tableElement) {
            console.error('Table with ID "contactsTableEdit" not found.');
            return;
        }
        const table = $(tableElement).DataTable();

        // 1. العثور على الصفوف التي سيتم تصديرها
        const selectedCheckboxes = table.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            // إذا كان هناك صفوف محددة، قم بمعالجة تلك الصفوف فقط
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            // إذا لم يتم تحديد أي شيء، قم بمعالجة جميع الصفوف المرئية
            rowsToProcess = table.rows({ 'search': 'applied' }).nodes();
        }

        // 2. بناء البيانات لـ SheetJS
        const data = [];
        const header = [];
        $(tableElement).find('thead th').each(function() {
            if ($(this).find('input[type="checkbox"]').length === 0) {
                header.push($(this).text().trim());
            }
        });
        data.push(header);

        $(rowsToProcess).each(function() {
            const rowData = [];
            $(this).find('td').each(function() {
                if ($(this).find('input[type="checkbox"]').length === 0) {
                    rowData.push($(this).text().trim());
                }
            });
            data.push(rowData);
        });

        // 3. إنشاء ورقة العمل والمصنف
        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Contacts');

        // 4. كتابة وتنزيل الملف بصيغة XLSX
        XLSX.writeFile(wb, 'contacts_data.xlsx');
    };
    // ----------------------------------------------------------------------
    //  دوال الطباعة
    // ----------------------------------------------------------------------
    window.printCustomersTable = function() {
        // تحديد الجدول المطلوب (في هذه الحالة جدول العملاء)
        const tableElement = document.getElementById('customersTable');
        const table = $(tableElement).DataTable();

        // العثور على مربعات التحديد المحددة
        const selectedCheckboxes = table.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            // إذا كان هناك صفوف محددة، قم بمعالجة تلك الصفوف فقط
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            // إذا لم يتم تحديد أي شيء، قم بمعالجة جميع الصفوف المرئية
            rowsToProcess = table.rows({ 'search': 'applied' }).nodes();
        }

        let printContents = `
            <style>
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                body { font-family: 'Arial', sans-serif; }
                h2 { text-align: center; margin-bottom: 20px; }
            </style>
            <h2>قائمة العملاء</h2>
            <table>
                <thead><tr>`;

        // نسخ رؤوس الأعمدة
        $(tableElement).find('thead tr:first th').each(function() {
            if ($(this).find('input[type="checkbox"]').length > 0) {
                return;
            }
            const headerClone = $(this).clone();
            headerClone.find('input, select, .btn, i').remove();
            const columnTitle = headerClone.text().trim();
            printContents += '<th>' + columnTitle + '</th>';
        });

        printContents += `</tr></thead><tbody>`;

        // إضافة الصفوف المحددة (أو جميعها) إلى محتوى الطباعة
        $(rowsToProcess).each(function() {
            printContents += '<tr>';
            $(this).find('td').each(function() {
                if ($(this).find('input[type="checkbox"]').length > 0) {
                    return;
                }
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
    // ----------------------------------------------------------------------
    window.printContactsTable = function() {
        const tableElement = document.getElementById('contactsTable');
        if (!tableElement) {
            console.error('❌ الجدول بالمعرّف "contactsTable" غير موجود.');
            return;
        }
        const table = $(tableElement).DataTable();

        // 1. العثور على الصفوف التي سيتم طباعتها
        const selectedCheckboxes = table.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            // إذا كان هناك صفوف محددة، قم بمعالجة تلك الصفوف فقط
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            // إذا لم يتم تحديد أي شيء، قم بمعالجة جميع الصفوف المرئية
            rowsToProcess = table.rows({ 'search': 'applied' }).nodes();
        }

        let printContents = `
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                th { background-color: #f2f2f2; }
                h2 { text-align: center; margin-bottom: 20px; }
            </style>
            <h2>قائمة جهات الاتصال</h2>
            <table>
                <thead><tr>`;

        // 2. نسخ رؤوس الأعمدة
        $(tableElement).find('thead tr:first th').each(function() {
            if ($(this).find('input[type="checkbox"]').length > 0) {
                return;
            }
            const headerClone = $(this).clone();
            headerClone.find('input, select, .btn, i').remove();
            const columnTitle = headerClone.text().trim();
            printContents += '<th>' + columnTitle + '</th>';
        });

        printContents += `</tr></thead><tbody>`;

        // 3. إضافة الصفوف التي سيتم طباعتها إلى المحتوى
        $(rowsToProcess).each(function() {
            printContents += '<tr>';
            $(this).find('td').each(function() {
                if ($(this).find('input[type="checkbox"]').length > 0) {
                    return;
                }
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
    // ----------------------------------------------------------------------
    window.printContactsTableEdit = function() {
        const tableElement = document.getElementById('contactsTableEdit');
        if (!tableElement) {
            console.error('❌ الجدول بالمعرّف "contactsTableEdit" غير موجود.');
            return;
        }
        const table = $(tableElement).DataTable();

        // 1. العثور على الصفوف التي سيتم طباعتها
        const selectedCheckboxes = table.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            // إذا كان هناك صفوف محددة، قم بمعالجة تلك الصفوف فقط
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            // إذا لم يتم تحديد أي شيء، قم بمعالجة جميع الصفوف المرئية
            rowsToProcess = table.rows({ 'search': 'applied' }).nodes();
        }

        let printContents = `
            <style>
                body { font-family: Arial, sans-serif; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
                th { background-color: #f2f2f2; }
                h2 { text-align: center; margin-bottom: 20px; }
            </style>
            <h2>قائمة جهات الاتصال</h2>
            <table>
                <thead><tr>`;

        // 2. نسخ رؤوس الأعمدة
        $(tableElement).find('thead tr:first th').each(function() {
            if ($(this).find('input[type="checkbox"]').length > 0) {
                return;
            }
            const headerClone = $(this).clone();
            headerClone.find('input, select, .btn, i').remove();
            const columnTitle = headerClone.text().trim();
            printContents += '<th>' + columnTitle + '</th>';
        });

        printContents += `</tr></thead><tbody>`;

        // 3. إضافة الصفوف التي سيتم طباعتها إلى المحتوى
        $(rowsToProcess).each(function() {
            printContents += '<tr>';
            $(this).find('td').each(function() {
                if ($(this).find('input[type="checkbox"]').length > 0) {
                    return;
                }
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
    // ---------------------------End Functions------------------------------


</script>

@endsection
