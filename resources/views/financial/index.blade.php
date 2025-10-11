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
                <button title="Edit Invoice" onclick="openEditDeliveriesModal()" class="btn-icon"><i class="fas fa-pen"></i></button>
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

    // 1. تحديث اسم الجدول إلى #invoicesTable
    window.invoicesTable = $('#invoicesTable').DataTable({
        responsive: true,
        scrollX: true,

        // ❌ تم حذف خاصية 'ajax'
        // ❌ تم حذف خاصية 'serverSide: true' - (هذا هو المفتاح للعمل بالـ Frontend فقط)
        processing: true, // يمكن الاحتفاظ بها لكنها لن تفعل شيئًا بدون جلب بيانات

        // ترتيب افتراضي (حسب تاريخ الفاتورة تنازليًا - العمود الثالث)
        order: [[2, 'desc']],

        // 💡 ملاحظة: عند العمل بالـ Frontend فقط، يجب إما حذف مصفوفة 'columns'
        // أو استخدام 'data: null' للأعمدة التي تريد التعامل مع محتواها يدوياً.
        // بما أن البيانات ستُقرأ مباشرة من <tbody>، سنقوم بإزالتها لتبسيط الكود،
        // لكن سأتركها مع 'data: null' للوضوح.

        columns: [
            // 1. عمود الاختيار (Checkbox)
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    // هذا الـ render لن يعمل بشكل صحيح إلا إذا كانت هناك بيانات في وضع الـ Ajax
                    // لذا يفضل وضع الـ checkbox داخل الـ HTML مباشرة في وضع الـ Frontend
                    return '<input type="checkbox" class="select-row-checkbox" value="' + (data ? data.id : '') + '">';
                }
            },

            // 2. Invoice # (سيُقرأ من محتوى الخلية في <tbody>)
            { data: null },

            // 3. Invoice Date
            { data: null },

            // 4. Status (الحالة) - يجب أن يكون كود التلوين في الـ HTML مباشرة
            { data: null },

            // 5. Net Amount
            { data: null },

            // 6. VAT Amount
            { data: null },

            // 7. Total Due
            { data: null },

            // 8. Due Date
            { data: null },

            // 9. Customer Name
            { data: null },

            // 10. TRN
            { data: null },

            // 11. Project Code
            { data: null },

            // 12. Project Name
            { data: null },

            // 13. Account Manager
            { data: null },

            // 14. Department
            { data: null },

            // 15. Items Count
            { data: null }
        ]

    });

    // 2. وظيفة البحث الديناميكي لكل عمود (Column Filters)
    // هذا الجزء سيعمل بشكل سليم لأنه يعتمد على محتويات الجدول بعد تهيئة DataTables
    $('#invoicesTable thead .column-filter').each(function(i) {
        var that = this;
        var table = window.invoicesTable;

        // لا نريد تفعيل الفلترة على أول عمود (Checkbox)
        var columnIndex = i + 1;

        $(this).on('keyup change clear', function() {
            if (table.column(columnIndex).search() !== this.value) {
                table
                    .column(columnIndex)
                    .search(this.value)
                    .draw();
            }
        });
    });

    // 3. معالج الاختيار الشامل
    $('#selectAllInvoices').on('click', function(){
        $('.select-row-checkbox').prop('checked', this.checked);
    });

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
