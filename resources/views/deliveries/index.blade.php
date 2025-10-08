@extends('layouts.app')
@section('title', __('Deliveries'))
@section('content')
@include('deliveries.create') <!-- المودال -->

<main class="main-content">
    <section id="deliveries-section" class="section-content active">

        <!-------------------------------------------Start Buttons-------------------------------------------->
        <div class="icon-toolbar">
            <div>
                <button title="Add" onclick="openConfModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                <button title="Edit" onclick="openEditDeliveriesModal()" class="btn-icon"><i class="fas fa-pen"></i></button>
                <button title="Delete" onclick="deleteSelectedConfirmation()" class="btn-icon"><i class="fas fa-trash"></i></button>
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
                        <th>P. Number<br><input type="text" class="column-filter" placeholder="Search..."></th> <th>Recovered By<br><input type="text" class="column-filter" placeholder="Search..."></th>
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

        // 💡 تأكدي أن اسم الجدول هنا هو confirmationsTable
        window.deliveriesTable = $('#deliveriesTable').DataTable({
            responsive: true,
            scrollX: true,
            // ... إعدادات أخرى ...
            columns: [
                // 1. عمود الاختيار (Checkbox)
                { data: null, orderable: false, searchable: false }, 
                
                // 2. Delivery No.
                { data: 'delivery_no' }, 
                
                // 3. Delivery Date
                { data: 'delivery_date' }, 
                
                // 4. Status
                { data: 'status' }, 
                
                // 5. Department
                { data: 'department' }, 
                
                // 6. Prepared By
                { data: 'prepared_by' }, 
                
                // 7. Delivered By
                { data: 'delivered_by' }, 
                
                // 8. Project Code (V. Code)
                { data: 'project_code' }, 
                
                // 9. Project Name
                { data: 'project_name' }, 
                
                // 10. Customer Name
                { data: 'customer_name' }, 
                
                // 11. P. Number
                { data: 'p_number' }, 
                
                // 12. Recovered By
                { data: 'recovered_by' }, 
                
                // 13. Date Received (Date Rece...)
                { data: 'date_received' }, 
                
                // 14. Contract Name
                { data: 'contract_name' }, 
                
                // 15. Contact Title
                { data: 'contact_title' }, 
                
                // 16. Items
                { data: 'items' } 
            ]
            // ... إضافة وظيفة البحث الديناميكي هنا كما كانت في جدول العملاء
        });

        // ... باقي أكواد الجافاسكريبت والـ Column Filters ...

    });
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




</script>









@endsection