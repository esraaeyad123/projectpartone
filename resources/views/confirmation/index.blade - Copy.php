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
                        <td>{{ $confirmation->category }}</td>
                        <td>{{ $confirmation->confirm_id }}</td>
                        <td>{{ $confirmation->confirm_date }}</td>
                        <td>{{ $confirmation->conf_source }}</td>
                        <td>{{ $confirmation->project->reference  ?? '' }}</td>
                        <td>{{ $confirmation->project->name ?? '' }}</td>
                        <td>{{ $confirmation->project->project_details ?? ''}}</td>
                        <td>{{ $confirmation->contract_no }}</td>
                        <td>{{ $confirmation->customer->customer_name }}</td>
                        <td>{{ $confirmation->validity }}</td>
                        <td>{{ $confirmation->payment_terms }}</td>
                        <td>{{ $confirmation->discount }}</td>
                        <td>{{ $confirmation->tax }}</td>
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
    // تهيئة DataTable
    window.confirmationsTable = $('#confirmationsTable').DataTable({
        responsive: true,
        scrollX: true,
        orderCellsTop: true,
        fixedHeader: true,
        columnDefs: [
            { orderable: false, targets: [0] } // عمود الشيكبوكس
        ]
    });

    // ربط البحث لكل عمود
    confirmationsTable.columns().every(function() {
        var column = this;
        $('input', this.header()).on('keyup change', function() {
            if (column.search() !== this.value) {
                column.search(this.value).draw();
            }
        });
    });

    // تحديد/إلغاء تحديد كل الصفوف
    $('#selectAllConfirmations').on('change', function() {
        var rows = confirmationsTable.rows({ 'search': 'applied' }).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });
     $('#confirmationsTable tbody').on('click', '.edit-btn', function() {
        const data = confirmationTable.row($(this).parents('tr')).data();
        openEditConfirmationModal(data.id);
    });
    $('#confirmationsTable tbody').on('change', 'input[type="checkbox"]', function() {
        var allChecked = $('tbody input[type="checkbox"]').length === $('tbody input[type="checkbox"]:checked').length;
        $('#selectAllConfirmations').prop('checked', allChecked);
    });



    // تعبئة الجدول بالبيانات
    function loadConfirmations(data) {
        confirmationsTable.clear();
        data.forEach(conf => {
            confirmationsTable.row.add([
                `<input type="checkbox" class="confirmation-checkbox">`,
                conf.conf_category,
                conf.confirmation_id,
                conf.date_confirmed,
                conf.conf_source,
                conf.project_code,
                conf.project_name,
                conf.project_details,
                conf.contract_no,
                conf.customer_name,
                conf.validity,
                conf.payment_terms,
                conf.discount,
                conf.vat,
                conf.currency
            ]).draw(false);
        });
    }

    loadConfirmations(sampleData);
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
                id: @json($project->id),          // ✅ أضف هذا
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
        window.selectedProjectId = null; // ✅ افتراضي عند عدم الاختيار
        return;
    }

       const project = projectsMap[selectedCode];
    projectNameSelect.value = project.name;
    projectDetailsInput.value = project.details;
    window.selectedProjectId = project.id; // ✅ خزن project_id للاستخدام لاحقاً

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
    const projectDetailsInput = document.getElementById('projectDetails');

    const confData = {
        customer_id: document.getElementById('customerSelect').value,
        project_id: window.selectedProjectId,
        project_code: document.getElementById('projectCodeSelect').value,
        project_name: document.getElementById('projectNameSelect').value,
        project_details: projectDetailsInput.value,
        contact_person: document.getElementById('contactPersonSelect')?.value || '',
        conf_to: document.getElementById('confToSelect')?.value || '',
        category: document.getElementById('confCategory')?.value || '',
        confirm_date: document.getElementById('confirmDate').value,
        subject: document.getElementById('subject').value,
        conf_source: document.getElementById('confSource').value,
        contract_no: document.getElementById('contractNo').value,
        currency: document.getElementById('currency').value,
        discount: document.getElementById('discount').value,
        tax: document.getElementById('tax').value,
        validity: document.getElementById('validity').value,
        payment_terms: document.getElementById('paymentTerms').value || '',
    };

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
            window.currentConfirmationId = result.id;

            // 🟢 اشعار باستخدام SweetAlert2
            Swal.fire({
                icon: 'success',
                title: 'تم الحفظ بنجاح ✅',
                text: 'تم حفظ الـ Confirmation بنجاح.',
                timer: 2000,
                showConfirmButton: false
            });

            if (closeAfter) {
                setTimeout(() => {
                    window.location.href = '/confirmations';
                }, 2000); // انتظار انتهاء الـ timer
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: '❌ خطأ',
                text: result.message || 'حدث خطأ أثناء حفظ الـ Confirmation.',
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: '⚠️ خطأ',
            text: 'حدث خطأ أثناء حفظ الـ Confirmation. تحقق من الكونسول.',
        });
    });
}



function handleServiceInsert(mode = 'add') {
    // ✅ تأكد أن فيه Confirmation محفوظ
    if (!window.currentConfirmationId) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه ⚠️',
            text: 'يرجى أولاً حفظ الـ Confirmation قبل إضافة الخدمات.',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

    // ✅ تأكد أن المستخدم اختار على الأقل صف واحد من الخدمات
    const selectedRows = document.querySelectorAll('.service-selector:checked');
    if (selectedRows.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه ⚠️',
            text: 'يرجى اختيار خدمة واحدة على الأقل.',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }

    // 🔁 مرّ على كل صف مختار وأضفه للسيرفر والجدول
    selectedRows.forEach(row => {
        const tr = row.closest('tr');

        const serviceName = tr.cells[1].textContent.trim();
        const method = tr.cells[2].textContent.trim();
        const unit = tr.cells[3].textContent.trim();
        const price = parseFloat(tr.querySelector('.editable-price').value || 0);
        const quantity = parseFloat(tr.querySelector('.editable-quantity').value || 1);
        const total = price * quantity;
        const isPriceOnly = tr.querySelector('.is-price-only').checked;

        const newLine = {
            confirmation_id: window.currentConfirmationId,
            service_name: serviceName,
            method: method,
            unit: unit,
            quantity: quantity,
            price: price,
            total: total,
            price_only: isPriceOnly ? 1 : 0
        };

        // 📨 أرسل البيانات للسيرفر
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
                const tbody = document.querySelector('#servicesTable tbody');
                if (!tbody) return;

                const rowEl = document.createElement('tr');
                rowEl.innerHTML = `
                    <td><input type="checkbox" class="service-line-selector"></td>
                    <td>${newLine.service_name}</td>
                    <td>${newLine.method}</td>
                    <td>${newLine.unit}</td>
                    <td>${newLine.total.toFixed(2)}</td>  <!-- هنا المجموع الكامل -->
                    <td>${newLine.price_only ? '✔️' : ''}</td>
                    <td>${newLine.quantity}</td>
                `;
                tbody.appendChild(rowEl);

                // ✅ إشعار نجاح لكل خدمة
                Swal.fire({
                    icon: 'success',
                    title: 'تم الحفظ بنجاح ✅',
                    text: `${newLine.service_name} تم إضافتها بنجاح.`,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ ❌',
                    text: result.message || 'فشل في إضافة الخدمة.',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        })
        .catch(err => {
            console.error('Error adding line:', err);
            Swal.fire({
                icon: 'error',
                title: '⚠️ خطأ',
                text: 'حدث خطأ أثناء إضافة الخدمة. تحقق من الكونسول.',
                timer: 2000,
                showConfirmButton: false
            });
        });
    });

    // ✅ إغلاق المودال بعد إضافة كل الصفوف
    closeModal('serviceSelectionModal');
}

// الحصول على جميع الـ IDs المحددة من جدول Confirmations
function getSelectedConfirmationIds() {
    let ids = [];
    $('.selectConfirmation:checked').each(function() {
        ids.push($(this).val());
    });
    return ids;
}


// =====================================
// جلب الـ Confirmation المحددة للتعديل
// =====================================
function handleEditConfirmation() {
    const selectedIds = getSelectedConfirmationIds(); // دالة لجلب الـ IDs
    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'تحذير ⚠️',
            text: 'الرجاء اختيار Confirmation واحد للتعديل!'
        });
        return;
    }
    if (selectedIds.length > 1) {
        Swal.fire({
            icon: 'warning',
            title: 'تحذير ⚠️',
            text: 'الرجاء اختيار Confirmation واحد فقط للتعديل!'
        });
        return;
    }

    const confirmationId = selectedIds[0];
    openEditConfirmationModal(confirmationId);
}

// =====================================
// فتح المودال وتعبئة بيانات Confirmation + Lines
// =====================================
function openEditConfirmationModal(id) {
    if (!id) {
        Swal.fire({
            icon: 'error',
            title: 'خطأ ❌',
            text: 'لم يتم تحديد الـ Confirmation!'
        });
        return;
    }

    // جلب بيانات الـ Confirmation
    $.get(`/confirmations/${id}`, function(confirmation) {
        if (!confirmation || !confirmation.id) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ ❌',
                text: 'لم يتم العثور على بيانات الـ Confirmation!'
            });
            return;
        }

        // ==========================
        // تعبئة الحقول في المودال
        // ==========================
        $('#editConfId').val(confirmation.id);
        $('#editConfCategory').val(confirmation.category || '');
        $('#editConfirmID').val(confirmation.confirmation_id || '');

        // التاريخ
        const confirmDate = confirmation.confirm_date
            ? new Date(confirmation.confirm_date).toISOString().split('T')[0]
            : new Date().toISOString().split('T')[0];
        $('#editConfirmDate').val(confirmDate);

        // المشروع
        $('#editProjectCode').val(confirmation.project?.reference || '');
        $('#editProjectName').val(confirmation.project?.name || '');
        $('#editProjectDetails').val(confirmation.project?.project_details || '');

        // العميل
        $('#editCustomer').val(confirmation.customer?.id || '');

        // باقي الحقول
        $('#editContactPerson').val(confirmation.contact_person || '');
        $('#editConfTo').val(confirmation.conf_to || '');
        $('#editSubject').val(confirmation.subject || '');
        $('#editConfSource').val(confirmation.conf_source || '');
        $('#editContractNo').val(confirmation.contract_no || '');
        $('#editCurrency').val(confirmation.currency || 'SAR');
        $('#editDiscount').val(confirmation.discount || 0);
        $('#editTax').val(confirmation.tax || 15);
        $('#editValidity').val(confirmation.validity || '60 Days');
        $('#editPaymentTerms').val(confirmation.payment_terms || '');

        // ==========================
        // جلب خطوط الـ Confirmation (Lines)
        // ==========================
        $.get(`/confirmations/${id}/lines`, function(lines) {
            if (Array.isArray(lines) && window.confirmationLinesTableEdit) {
                window.confirmationLinesTableEdit.clear().rows.add(lines).draw();
            } else {
                console.warn("Lines data is not an array or table is not initialized");
            }

            // بعد تعبئة كل البيانات، عرض المودال
            $('#editConfModal').show();
            if (window.confirmationLinesTableEdit) window.confirmationLinesTableEdit.columns.adjust().draw();
        }).fail(function(err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: '❌ حدث خطأ أثناء تحميل خطوط الـ Confirmation.'
            });
            $('#editConfModal').show(); // عرض المودال حتى لو فشل تحميل الخطوط
        });

    }).fail(function(err) {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'خطأ',
            text: '❌ حدث خطأ أثناء تحميل بيانات الـ Confirmation.'
        });
    });
}


function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}


</script>









@endsection
