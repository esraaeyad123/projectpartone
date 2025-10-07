@extends('layouts.app')
@section('title', __('Confirmation'))
@section('content')
@include('confirmation.create') <!-- المودال -->

<main class="main-content">
    <section id="confirmation-section" class="section-content active">

        <!-------------------------------------------Start Buttons-------------------------------------------->
        <div class="icon-toolbar">
            <div>
                <button title="Add" onclick="openConfModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                <button title="Edit" onclick="openEditConfirmationModal()"  class="btn-icon"><i class="fas fa-pen"></i></button>
                <button title="Delete" onclick="deleteSelectedConfirmation()" class="btn-icon"><i class="fas fa-trash"></i></button>
            </div>

        <div class="icon-separator"></div>
            <div>
                <button title="Duplicate Confirmation" class="btn-icon" onclick="duplicateConfirmation()"><i class="fas fa-copy"></i> </button>
                <button title="Export to Excel" class="btn-icon" onclick="exportConfirmationsExcelBtn()"><i class="fa-solid fa-table"></i></button>
                <button title="Print" class="btn-icon" onclick="printConfirmationTable()"><i class="fas fa-print"></i></button>
            </div>

        </div>
        <!-------------------------------------------End Buttons----------------------------------------------->
        <!-------------------------------------------Start confirmationTable----------------------------------------------->
        <div class="table-responsive-container">
            <table id="confirmationsTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllConfirmations"></th>
                        <th>Conf. Category<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Conf. ID<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Date Confirmed<br><input type="text" class="column-filter date-range-filter" data-filter-type="date-from"></th>
                        <th>Conf. Source<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Project Code<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Project Name<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Project Details<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Contract No<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Customer Name<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Validity<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Payment Terms<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Discount<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>VAT<br><input type="text" class="column-filter" placeholder="Search..."></th>
                        <th>Currency<br><input type="text" class="column-filter" placeholder="Search..."></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($confirmations as $confirmation)
                    <tr>
                        <td><input type="checkbox" class="selectConfirmation" value="{{ $confirmation->id }}"></td>
                        <td>{{ $confirmation->conf_category }}</td>
                        <td>{{ $confirmation->confirmation_id }}</td>
                        <td>{{ $confirmation->date_confirmed }}</td>
                        <td>{{ $confirmation->conf_source }}</td>
                        <td>{{ $confirmation->project_code }}</td>
                        <td>{{ $confirmation->project_name }}</td>
                        <td>{{ $confirmation->project_details }}</td>
                        <td>{{ $confirmation->contract_no }}</td>
                        <td>{{ $confirmation->customer_name }}</td>
                        <td>{{ $confirmation->validity }}</td>
                        <td>{{ $confirmation->payment_terms }}</td>
                        <td>{{ $confirmation->discount }}</td>
                        <td>{{ $confirmation->vat }}</td>
                        <td>{{ $confirmation->currency }}</td>
                    </tr>
                    @endforeach
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
//-------------------------------------------------------------------------------------------

    $(document).ready(function() {

        // 💡 تأكدي أن اسم الجدول هنا هو confirmationsTable
        window.confirmationTable = $('#confirmationsTable').DataTable({
            responsive: true,
            scrollX: true,
            // ... إعدادات أخرى ...
            columns: [
                { data: null, orderable: false, searchable: false }, // Checkbox
                { data: 'conf_category' },
                { data: 'confirmation_id' },
                { data: 'date_confirmed' },
                { data: 'conf_source' },
                { data: 'project_code' },
                { data: 'project_name' },
                { data: 'project_details' },
                { data: 'contract_no' },
                { data: 'customer_name' },
                { data: 'validity' },
                { data: 'payment_terms' },
                { data: 'discount' },
                { data: 'vat' },
                { data: 'currency' }
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

document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customerSelect');
    const projectCodeSelect = document.getElementById('projectCodeSelect');
    const projectNameSelect = document.getElementById('projectNameSelect');
    const projectDetailsInput = document.getElementById('projectDetails');
    const contactPersonSelect = document.getElementById('contactPersonSelect');
    const confToSelect = document.getElementById('confToSelect');

    // خريطة المشاريع مع التفاصيل وجهات الاتصال
    const projectsMap = {
        @foreach($projects as $project)
            "{{ $project->reference }}": {
                code: @json($project->reference),
                name: @json($project->name),
                details: @json($project->project_details ?? ''),
                customer: @json($project->customer_id),
                contacts: [
                    @foreach($project->contacts as $contact)
                        { contact: @json($contact->name), to: @json($contact->phone) },
                    @endforeach
                ]
            },
        @endforeach
    };

    // تحديث خيارات المشاريع حسب العميل المحدد
    function filterProjectsByCustomer() {
        const customerId = customerSelect.value;

        // مسح المشاريع القديمة
        projectCodeSelect.innerHTML = '<option value="" disabled selected>[Select Project Code]</option>';
        projectNameSelect.innerHTML = '<option value="" disabled selected>[Select Project Name]</option>';

        for (const ref in projectsMap) {
            const project = projectsMap[ref];
            if (project.customer == customerId) {
                const codeOption = document.createElement('option');
                codeOption.value = project.code;
                codeOption.textContent = project.code;
                projectCodeSelect.appendChild(codeOption);

                const nameOption = document.createElement('option');
                nameOption.value = project.name;
                nameOption.textContent = project.name;
                projectNameSelect.appendChild(nameOption);
            }
        }

        // مسح الحقول عند تغيير العميل
        projectDetailsInput.value = '';
        contactPersonSelect.innerHTML = '<option value="" disabled selected>[Select Contact]</option>';
        confToSelect.innerHTML = '<option value="" disabled selected>[Select Destination]</option>';
    }

    // تعبئة تفاصيل المشروع وجهات الاتصال عند اختيار مشروع
    function fillProjectData() {
        const selectedCode = projectCodeSelect.value;
        if (!selectedCode || !projectsMap[selectedCode]) {
            projectDetailsInput.value = '';
            contactPersonSelect.innerHTML = '<option value="" disabled selected>[Select Contact]</option>';
            confToSelect.innerHTML = '<option value="" disabled selected>[Select Destination]</option>';
            projectNameSelect.value = '';
            return;
        }

        const project = projectsMap[selectedCode];
        projectNameSelect.value = project.name;
        projectDetailsInput.value = project.details;

        // ملء جهات الاتصال
        contactPersonSelect.innerHTML = '<option value="" disabled selected>[Select Contact]</option>';
        confToSelect.innerHTML = '<option value="" disabled selected>[Select Destination]</option>';
        project.contacts.forEach(item => {
            const contactOption = document.createElement('option');
            contactOption.value = item.contact;
            contactOption.textContent = item.contact;
            contactPersonSelect.appendChild(contactOption);

            const toOption = document.createElement('option');
            toOption.value = item.to;
            toOption.textContent = item.to;
            confToSelect.appendChild(toOption);
        });
    }

    // عند تغيير Project Name لتحديث الكود تلقائيًا
    function syncCodeFromName() {
        const selectedName = projectNameSelect.value;
        for (const ref in projectsMap) {
            if (projectsMap[ref].name === selectedName) {
                projectCodeSelect.value = ref;
                fillProjectData();
                break;
            }
        }
    }

    // Event listeners
    customerSelect.addEventListener('change', filterProjectsByCustomer);
    projectCodeSelect.addEventListener('change', fillProjectData);
    projectNameSelect.addEventListener('change', syncCodeFromName);

    // استدعاء أولي إذا كان هناك مشروع محدد مسبقًا
    filterProjectsByCustomer();
    fillProjectData();
});

function saveConf(closeAfter = false) {
    const confData = {
        customer_id: document.getElementById('customerSelect').value,
        project_code: document.getElementById('projectCodeSelect').value,
        project_name: document.getElementById('projectNameSelect').value,
        project_details: document.getElementById('projectDetails').value,
        contact_person: document.getElementById('contactPerson')?.value || '',
        conf_to: document.getElementById('confTo')?.value || ''
    };

    if (!confData.customer_id || !confData.project_code) {
        alert('⚠️ يرجى اختيار العميل والمشروع أولاً.');
        return;
    }

    fetch('/confirmations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(confData)
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === 'success') {
            alert('✅ Confirmation saved successfully.');

            // تخزين ID التعميد الجديد في متغير عام
            window.currentConfirmationId = result.id;

            if (closeAfter) {
                window.location.href = '/confirmations';
            }
        } else {
            alert('❌ Error saving confirmation');
        }
    })
    .catch(err => {
        console.error(err);
        alert('⚠️ Error saving confirmation.');
    });
}

function handleServiceInsert(mode = 'add') {
    // تحقق أولاً أنه يوجد Confirmation محفوظ
    if (!window.currentConfirmationId) {
        alert('⚠️ يرجى أولاً حفظ الـ Confirmation قبل إضافة الخدمات.');
        return;
    }

    // اجمع بيانات السطر الجديد من الجدول أو النافذة
    const newLine = {
        confirmation_id: window.currentConfirmationId,
        service_name: document.getElementById('serviceName')?.value || '',
        method: document.getElementById('serviceMethod')?.value || '',
        unit: document.getElementById('serviceUnit')?.value || '',
        quantity: parseFloat(document.getElementById('serviceQty')?.value || 1),
        price: parseFloat(document.getElementById('servicePrice')?.value || 0),
        total: 0
    };
    newLine.total = newLine.quantity * newLine.price;

    // إرسال البيانات إلى السيرفر
    fetch('/confirmation-lines', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(newLine)
    })
    .then(res => res.json())
    .then(result => {
        if (result.status === 'success') {
            alert('✅ Service added successfully.');

            // إضافة السطر الجديد للجدول بدون تحديث الصفحة
            const tbody = document.querySelector('#serviceLinesTable tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${newLine.service_name}</td>
                <td>${newLine.method}</td>
                <td>${newLine.unit}</td>
                <td>${newLine.quantity}</td>
                <td>${newLine.price.toFixed(2)}</td>
                <td>${newLine.total.toFixed(2)}</td>
            `;
            tbody.appendChild(row);

            // تفريغ الحقول بعد الإضافة
            document.getElementById('serviceName').value = '';
            document.getElementById('serviceMethod').value = '';
            document.getElementById('serviceUnit').value = '';
            document.getElementById('serviceQty').value = '';
            document.getElementById('servicePrice').value = '';
        } else {
            alert('❌ Failed to add service line.');
        }
    })
    .catch(err => {
        console.error(err);
        alert('⚠️ Error adding service line.');
    });
}



</script>









@endsection
