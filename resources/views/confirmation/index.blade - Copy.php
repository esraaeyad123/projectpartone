
@extends('layouts.app')
@section('title', __('Confirmation'))
@section('content')
@include('confirmation.create') <!-- المودال -->

<main class="main-content">
    <section id="confirmation-section" class="section-content active">

        <!-------------------------------------------Start Buttons-------------------------------------------->
        <div class="icon-toolbar">
            <div>
                <button title="Add" onclick="openAddConfirmationModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                <button title="Edit" onclick="handleEditConfirmation()" class="btn-icon"><i class="fas fa-pen"></i></button>
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
                        <th>
                            <input type="checkbox" id="selectAllConfirmations">
                        </th>
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
                        <td>{{ $confirmation->project_details ?? ''}}</td>
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
$(document).ready(function() {
    // ======================= تهيئة DataTable =======================
    window.confirmationsTable = $('#confirmationsTable').DataTable({
        responsive: true,
        scrollX: true,
        orderCellsTop: true,
        fixedHeader: true,
        columnDefs: [
            { orderable: false, targets: [0] } // عمود الشيكبوكس
        ]
    });

    // ======================= ربط البحث لكل عمود =======================
    confirmationsTable.columns().every(function() {
        var column = this;
        $('input', this.header()).on('keyup change', function() {
            if (column.search() !== this.value) {
                column.search(this.value).draw();
            }
        });
    });

    // ======================= Select / Deselect All =======================
    $('#selectAllConfirmations').on('change', function() {
        const isChecked = $(this).is(':checked');

        // تحديث كل الصفوف المرئية فقط
        window.confirmationsTable.rows({ search: 'applied' }).nodes().each(function(row) {
            $(row).find('input.selectConfirmation').prop('checked', isChecked);
        });
    });

    // ======================= تحديث زر Select All عند تغيير أي Checkbox =======================
    $('#confirmationsTable tbody').on('change', 'input.selectConfirmation', function() {
        const totalCheckboxes = window.confirmationsTable.rows({ search: 'applied' }).nodes().length;
        const checkedCheckboxes = window.confirmationsTable.rows({ search: 'applied' }).nodes()
                                   .to$().find('input.selectConfirmation:checked').length;

        $('#selectAllConfirmations').prop('checked', totalCheckboxes === checkedCheckboxes);
    });

    // ======================= زر التعديل =======================
    $('#confirmationsTable tbody').on('click', '.edit-btn', function() {
        const data = window.confirmationsTable.row($(this).parents('tr')).data();
        openEditConfirmationModal(data.id);
    });

    // ======================= تغيير العميل =======================
    $('#editCustomer').on('change', function() {
        const customerId = $(this).val();
        if (!customerId) return;

        $('#editProjectCode').empty().append('<option value="" disabled selected>[Select Project Code]</option>');
        $('#editProjectName').empty().append('<option value="" disabled selected>[Select Project Name]</option>');
        $('#editProjectDetails').val('');

        $.get(`/customers/${customerId}/projects`, function(projects) {
            projects.forEach(project => {
                $('#editProjectCode').append(`<option value="${project.id}">${project.reference}</option>`);
                $('#editProjectName').append(`<option value="${project.id}">${project.name}</option>`);
            });
        });
    });

    // ======================= تغيير المشروع =======================
    $('#editProjectCode, #editProjectName').on('change', function() {
        const projectId = $(this).val();
        if (!projectId) return;

        $.get(`/projects/${projectId}`, function(project) {
            $('#editProjectDetails').val(project.project_details || '');
        });
    });
});


    //-------------------------------------------------------------------------------------------
   function loadConfirmations() {
    $.ajax({
        url: '/confirmations/list',
        type: 'GET',
        dataType: 'json', // تأكد من استلام JSON
        success: function(data) {
            if (!Array.isArray(data)) {
                console.error('Expected an array of confirmations');
                return;
            }

            // تفريغ الجدول
            window.confirmationsTable.clear();

            // إضافة كل التأكيدات
            data.forEach(conf => {
                window.confirmationsTable.row.add([
                    `<input type="checkbox" class="selectConfirmation" value="${conf.id}">`,
                    conf.category || '',
                    conf.confirm_id || '',
                    conf.confirm_date || '',
                    conf.conf_source || '',
                    conf.project_code || '',
                    conf.project_name || '',
                    conf.project_details || '',
                    conf.contract_no || '',
                    conf.customer_name || '',
                    conf.validity || '',
                    conf.payment_terms || '',
                    conf.discount || '',
                    conf.tax || '',
                    conf.currency || ''
                ]);
            });

            // إعادة رسم الجدول دون إعادة ضبط الصفحة
            window.confirmationsTable.draw(false);
        },
        error: function(xhr) {
            console.error('Failed to reload confirmations:', xhr.responseText);
        }
    });
}


    //-------------------------------------------------------------------------------------------
    function getSelectedConfirmationIds() {
        let ids = [];
        $('.selectConfirmation:checked').each(function() {
            ids.push($(this).val());
        });
        return ids;
    }
    //-------------------------------------------------------------------------------------------
    window.openAddConfirmationModal = function() {
        // إعادة تعيين currentConfirmationId لأننا لم نحفظ Confirmation بعد
        window.currentConfirmationId = null;

        // إعادة تعيين الحقول
        $('#confForm')[0].reset();
        $('#servicesTable tbody').empty();

        // عرض المودال
        $('#confModal').show();
    }
    //-------------------------------------------------------------------------------------------
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
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
                    columns: [{
                            name: 'id'
                        },
                        {
                            name: 'name'
                        },
                        {
                            name: 'method'
                        },
                        {
                            name: 'unit'
                        },
                        {
                            name: 'price'
                        },
                        {
                            name: 'price_only'
                        },
                        {
                            name: 'quantity'
                        }
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
                    columns: [{
                            name: 'id'
                        },
                        {
                            name: 'name'
                        },
                        {
                            name: 'method'
                        },
                        {
                            name: 'unit'
                        },
                        {
                            name: 'price'
                        },
                        {
                            name: 'price_only'
                        },
                        {
                            name: 'quantity'
                        }
                    ]
                });
                servicesTableEditInitialized = true; // لمنع إعادة التهيئة
            }
        }
    }
    //-------------------------------------------------------------------------------------------
    function duplicateConfirmation() {
        const selected = document.querySelectorAll('.selectConfirmation:checked');
        if (selected.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه ⚠️',
                text: 'يرجى اختيار Confirmation واحد للنسخ.'
            });
            return;
        }
        if (selected.length > 1) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه ⚠️',
                text: 'يمكنك نسخ Confirmation واحد فقط في كل مرة.'
            });
            return;
        }

        const confirmationId = selected[0].value;

        Swal.fire({
            title: 'هل أنت متأكد من نسخ الـ Confirmation؟',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'نعم، انسخ',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $.ajax({
                url: '/confirmations/duplicate',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: confirmationId
                },
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم النسخ ✅',
                            text: `تم إنشاء Confirmation جديد برقم: ${response.newConfirmationNumber}`,
                            timer: 2000,
                            showConfirmButton: false
                        });

                        // تحديث الجدول مباشرة (مثال باستخدام DataTable)
                        if (window.confirmationsTable) {
                            window.confirmationsTable.ajax.reload(null, false);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ ❌',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ ❌',
                        text: 'حدث خطأ أثناء النسخ.'
                    });
                }
            });
        });
    }
    //-------------------------------------------------------------------------------------------

    // تعديل الفنكشن بحيث تحذف عنصر واحد او اكثر من عنصر
    function deleteSelectedConfirmation() {
        const selected = document.querySelectorAll('.selectConfirmation:checked');

        if (selected.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: '⚠️ تنبيه',
                text: 'الرجاء اختيار Confirmation واحد على الأقل للحذف.'
            });
            return;
        }

        const confirmationIds = Array.from(selected).map(el => el.value);
        Swal.fire({
            icon: 'warning',
            title: 'تأكيد الحذف',
            text: `هل أنت متأكد من حذف ${confirmationIds.length} Confirmation${confirmationIds.length > 1 ? 's' : ''}؟`,
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (!result.isConfirmed) return;

            if (confirmationIds.length === 1) {
                // حذف عنصر واحد
                $.ajax({
                    url: `/confirmations/${confirmationIds[0]}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف ✅',
                            text: 'تم حذف الـ Confirmation بنجاح.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        selected[0].closest('tr').remove();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ ❌',
                            text: 'فشل في حذف الـ Confirmation.'
                        });
                    }
                });
            } else {
                // حذف عدة عناصر
                $.ajax({
                    url: '/confirmations/bulk-delete',
                    type: 'POST',
                    data: {
                        ids: confirmationIds
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف ✅',
                            text: 'تم حذف الـ Confirmations بنجاح.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        selected.forEach(el => el.closest('tr').remove());
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ ❌',
                            text: 'فشل في حذف Confirmations.'
                        });
                    }
                });
            }
        });
    }
    //-------------------------------------------------------------------------------------------


    //===================================================================================
    //===================================================================================
    //=============================Save Add & Edit=======================================
    //===================================================================================
    //===================================================================================
    // حفظ الconfirmaion
  function saveConf(closeAfter = true) {
    // 1. تحديد الزر وإدارة حالته
    const button = document.activeElement;
    const originalButtonText = (button && button.innerHTML) ? button.innerHTML : 'Save Confirmation';

    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    }

    // 2. قراءة قيمة المشروع بشكل آمن
    const projectIdValue = document.getElementById('projectCodeSelect')?.value;

    if (!projectIdValue || projectIdValue === '') {
        Swal.fire('خطأ في الإدخال', 'الرجاء اختيار مشروع (Project) قبل الحفظ.', 'error');
        if (button) {
            button.disabled = false;
            button.innerHTML = originalButtonText;
        }
        return;
    }

    // 3. تجميع البيانات
    const confData = {
        category: document.getElementById('confCategory')?.value,
        title: document.getElementById('subject')?.value,
        project_id: projectIdValue,
        customer_id: document.getElementById('customerIDHidden')?.value,
        project_code: document.getElementById('projectCodeSelect').value,
        project_name: document.getElementById('projectNameInput').value,
        project_details: document.getElementById('projectDetails')?.value || '',
        contact_person: document.getElementById('contactPersonInput')?.value || '',
        conf_to: document.getElementById('confToInput')?.value || '',
        confirm_date: document.getElementById('confirmDate').value,
        conf_source: document.getElementById('confSource').value || '',
        contract_no: document.getElementById('contractNo').value,
        currency: document.getElementById('currency').value,
        discount: document.getElementById('discount').value,
        tax: document.getElementById('tax').value,
        validity: document.getElementById('validity').value,
        payment_terms: document.getElementById('paymentTerms').value || '',
    };

    const url = '/confirmations';

    // 4. إرسال البيانات عبر AJAX (Fetch)
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify(confData)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => Promise.reject(data));
        }
        return response.json();
    })
    .then(data => {
        window.currentConfirmationId = data.id;

        Swal.fire({
            icon: 'success',
            title: 'تم الحفظ بنجاح ✅',
            text: 'تم حفظ التأكيد بنجاح.',
            timer: 2000,
            showConfirmButton: false
        });

loadConfirmations();


        // منطق الإغلاق أو إعادة تعيين النموذج
        if (closeAfter) {
            setTimeout(() => closeModal('confModal'), 2000);
        } else {
            document.getElementById('confForm').reset();
        }
    })
    .catch(error => {
        console.error('Save error:', error);
        let errorMessage = 'حدث خطأ غير متوقع أثناء الحفظ.';
        if (error && error.errors) {
            errorMessage = 'الرجاء تصحيح الأخطاء التالية:<br>' + Object.values(error.errors).flat().join('<br>');
        } else if (error.message) {
            errorMessage = error.message;
        }
        Swal.fire('خطأ في الحفظ ❌', errorMessage, 'error');
    })
    .finally(() => {
        if (button) {
            button.disabled = false;
            button.innerHTML = originalButtonText;
        }
    });
}

    //--------------------------------------------------------------------------------
    function handleEditConfirmation() {
        const selectedIds = getSelectedConfirmationIds();
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
    //--------------------------------------------------------------------------------
    function openEditConfirmationModal(id) {
        if (!id) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ ❌',
                text: 'لم يتم تحديد الـ Confirmation!'
            });
            return;
        }

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
            // تعبئة الحقول (نفس المنطق)
            // ==========================
            // ... (بقية حقول التعبئة هنا) ...
            $('#editConfId').val(confirmation.id);
            $('#editConfirmID').val(confirmation.confirm_id || '');
            $('#editCustomer').val(confirmation.customer_id).trigger('change');
            $('#editProject_code').val(confirmation.project_id).trigger('change');
            $('#editProject_name').val(confirmation.project_id).trigger('change');
            $('#editProjectDetails').val(confirmation.project_details || '');
            $('#editContactPerson').val(confirmation.contact_person || '');
            $('#editConfTo').val(confirmation.conf_to || '');
            $('#editConfCategory').val(confirmation.category || '');
            $('#editConfirmDate').val(confirmation.confirm_date || '');
            $('#editSubject').val(confirmation.subject || '');
            $('#editConfSource').val(confirmation.conf_source || '');
            $('#editContractNo').val(confirmation.contract_no || '');
            $('#editCurrency').val(confirmation.currency || 'SAR');
            $('#editDiscount').val(confirmation.discount || 0);
            $('#editTax').val(confirmation.tax || 15);
            $('#editValidity').val(confirmation.validity || '');
            $('#editPaymentTerms').val(confirmation.payment_terms || '');

            // ==========================
            // تعيين currentConfirmationId
            // ==========================
            window.currentConfirmationId = confirmation.id;

            // ----------------------------------------------------------------------
            // **الخطوة الحاسمة:** عرض المودال قبل جلب الخطوط والملفات
            // ----------------------------------------------------------------------
            $('#editConfModal').show(); // ⬅️ تم تقديم هذه الخطوة هنا!

            // ==========================
            // جلب خطوط الـ Confirmation
            // ==========================
            $.get(`/confirmations/${id}/lines`, function(lines) {
                const tbody = $('#servicesTableEdit tbody');
                tbody.empty();

                if (Array.isArray(lines)) {
                    lines.forEach(line => {
                        tbody.append(`
                            <tr data-line-id="${line.id}" data-service-id="${line.service_id}">
                                <td><input type="checkbox" class="service-line-selector"></td>
                                <td>${line.service_name}</td>
                                <td>${line.method}</td>
                                <td>${line.unit}</td>
                                <td>${line.total}</td>
                                <td>${line.price_only ? '✔️' : ''}</td>
                                <td>${line.quantity}</td>
                            </tr>
                        `);
                    });
                }

                // ----------------------------------------------------------------
                // **الحل النهائي:** تأخير استدعاء جلب الملفات بـ setTimeout
                // ----------------------------------------------------------------
                // بالرغم من أننا عرضنا المودال، قد لا تكون التبويبات مرئية بعد.
                // هذا التأخير يضمن أن كل شيء قد تم تحميله في الـ DOM.
                setTimeout(() => {
                    loadConfirmationFiles(id, 'editConf');
                }, 50); // تأخير 50 ميلي ثانية

            }).fail(function(err) {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ ❌',
                    text: 'فشل تحميل خطوط الـ Confirmation.'
                });
            });

        }).fail(function(err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'خطأ ❌',
                text: 'فشل تحميل بيانات الـ Confirmation.'
            });
        });
    }
    //--------------------------------------------------------------------------------
    function fillEditProjectFields(projectCode) {
        if (!projectCode || !projectsMap[projectCode]) return;
        const project = projectsMap[projectCode];

        // تحديث Project Code & Name
        $('#editProjectCode').val(project.code);
        $('#editProjectName').val(project.name);

        // تحديث Customer
        $('#editCustomer').val(project.customer_id);
    }
    //--------------------------------------------------------------------------------
    function saveEditConf(closeAfterSave = true) {
        const confirmationId = $('#editConfId').val();
        if (!confirmationId) return;

        // 1. جلب وتنظيف الحقول الأساسية
        const customerId = $('#editCustomer').val() || null;
        const projectId = $('#editProject_code').val() || null;
        const projectCode = $('#editProject_code option:selected').text() || '';
        const projectName = $('#editProject_name option:selected').text() || '';
        const projectDetails = $('#editProjectDetails').val().trim() || '';
        const contactPerson = $('#editContactPerson').val() || '';
        const confTo = $('#editConfTo').val() || '';
        const category = $('#editConfCategory').val() || '';
        const confirmDate = $('#editConfirmDate').val() || '';
        const subject = $('#editSubject').val() || '';
        const confSource = $('#editConfSource').val() || '';
        const contractNo = $('#editContractNo').val() || '';
        const currency = $('#editCurrency').val() || 'SAR';
        const discount = parseFloat($('#editDiscount').val()) || 0;
        const tax = parseFloat($('#editTax').val()) || 15;
        const validity = $('#editValidity').val() || '';
        const paymentTerms = $('#editPaymentTerms').val() || '';

        // 2. التحقق من الحقول الأساسية
        if (!customerId) {
            Swal.fire("تحذير", "⚠️ الرجاء اختيار العميل.", "warning");
            return;
        }
        if (!projectId) {
            Swal.fire("تحذير", "⚠️ الرجاء اختيار المشروع.", "warning");
            return;
        }

        // 3. بناء البيانات للإرسال
        const data = {
            customer_id: customerId,
            project_id: projectId,
            project_code: projectCode,
            project_name: projectName,
            project_details: projectDetails,
            contact_person: contactPerson,
            conf_to: confTo,
            category: category,
            confirm_date: confirmDate,
            subject: subject,
            conf_source: confSource,
            contract_no: contractNo,
            currency: currency,
            discount: discount,
            tax: tax,
            validity: validity,
            payment_terms: paymentTerms,
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        // 4. إرسال البيانات إلى السيرفر
        $.ajax({
            url: `/confirmations/${confirmationId}`,
            type: 'PUT',
            data: data,
            success: function(response) {
                Swal.fire({
                    title: 'تم التعديل بنجاح ✅',
                    text: 'تم حفظ التعديل بنجاح.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
                loadConfirmations(); // أو الدالة التي تعيد تحميل قائمة الـ confirmations
                if (closeAfterSave) $('#editConfModal').hide();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه ⚠️',
                    html: xhr.responseJSON?.message || 'حدث خطأ غير معروف'
                });
            }
        });

    }
    //===================================================================================
    //===================================================================================
    //=============================Save Add & Edit=======================================
    //===================================================================================
    //===================================================================================








    //===================================================================================
    //===================================================================================
    //=============================Service Line==========================================
    //===================================================================================
    //===================================================================================
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
    function handleServiceInsert(mode = 'add') {
        // تحقق من وجود Confirmation
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

        const serviceSelectionModal = (mode === 'edit') ?
            document.getElementById('editServiceSelectionModal') :
            document.getElementById('serviceSelectionModal');

        const serviceTable = (mode === 'edit') ?
            document.querySelector('#servicesTableEdit tbody') :
            document.querySelector('#servicesTable tbody');

        if (!serviceTable) {
            console.error('❌ لم يتم العثور على جدول الخدمات داخل المودال الحالي');
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'لم يتم العثور على جدول الخدمات داخل المودال الحالي.',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        const selectedRows = serviceSelectionModal.querySelectorAll('tbody input.service-selector:checked');
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
                        const lineId = result.line?.id || '';
                        const serviceId = result.line?.service_id || '';

                        const rowEl = document.createElement('tr');
                        rowEl.dataset.lineId = lineId;
                        rowEl.dataset.serviceId = serviceId;
                        rowEl.innerHTML = `
                        <td><input type="checkbox" class="service-line-selector"></td>
                        <td>${newLine.service_name}</td>
                        <td>${newLine.method}</td>
                        <td>${newLine.unit}</td>
                        <td>${newLine.total.toFixed(2)}</td>
                        <td>${newLine.price_only ? '✔️' : ''}</td>
                        <td>${newLine.quantity}</td>
                    `;
                        serviceTable.appendChild(rowEl);

                        // إزالة تحديد الصف بعد الإضافة
                        row.querySelector('.service-selector').checked = false;

                        Swal.fire({
                            icon: 'success',
                            title: 'تمت الإضافة ✅',
                            text: `${newLine.service_name} تمت إضافتها بنجاح.`,
                            timer: 1500,
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

        closeModal(serviceSelectionModal.id);
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
    function editSelectedServiceLine(tableSelector = '#servicesTableEdit') {
        const selectedRows = document.querySelectorAll(`${tableSelector} tbody input.service-line-selector:checked`);

        if (selectedRows.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: '⚠️',
                text: 'الرجاء اختيار صف للتعديل.'
            });
            return;
        }
        if (selectedRows.length > 1) {
            Swal.fire({
                icon: 'warning',
                title: '⚠️',
                text: 'يمكنك تعديل صف واحد فقط في كل مرة.'
            });
            return;
        }

        const row = selectedRows[0].closest('tr');
        const lineId = row.dataset.lineId || null;
        const selectedServiceId = row.dataset.serviceId || null;

        // 1️⃣ جلب كل الخدمات من مودال الإضافة
        const availableServices = $('#availableServicesTable tbody').html();
        $('#editServiceSelectionModal').fadeIn(200);
        const tbody = $('#editServiceSelectionModal tbody');
        tbody.html(availableServices);

        // 2️⃣ تعبئة القيم الحالية (سعر، كمية، Price Only)
        const currentPrice = parseFloat(row.cells[4].textContent.trim()) || 0;
        const currentQuantity = parseFloat(row.cells[6].textContent.trim()) || 1;
        const isPriceOnly = row.cells[5].textContent.trim() === '✔️';
        const currentName = row.cells[1].textContent.trim();

        const matchedRow = tbody.find(`tr:contains("${currentName}")`);
        if (matchedRow.length) {
            matchedRow.find('.service-selector').prop('checked', true);
            matchedRow.find('.editable-price').val(currentPrice);
            matchedRow.find('.editable-quantity').val(currentQuantity);
            matchedRow.find('.is-price-only').prop('checked', isPriceOnly);
        }

        // 3️⃣ عند الضغط على "Save Changes"
        $('#saveEditedServiceBtn').off('click').on('click', function() {
            const checkedRow = $('#editServiceSelectionModal tbody input.service-selector:checked').closest('tr');
            if (checkedRow.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: '⚠️',
                    text: 'الرجاء اختيار خدمة واحدة على الأقل.'
                });
                return;
            }

            const updated = {
                service_id: null, // تركها null لتجنب مشاكل FK
                service_name: checkedRow.children().eq(1).text().trim(),
                method: checkedRow.children().eq(2).text().trim(),
                unit: checkedRow.children().eq(3).text().trim(),
                price: parseFloat(checkedRow.find('.editable-price').val()) || 0,
                quantity: parseFloat(checkedRow.find('.editable-quantity').val()) || 1,
                price_only: checkedRow.find('.is-price-only').is(':checked') ? 1 : 0
            };

            // تحديث الجدول مؤقتاً
            row.cells[1].textContent = updated.service_name;
            row.cells[2].textContent = updated.method;
            row.cells[3].textContent = updated.unit;
            row.cells[4].textContent = (updated.price * updated.quantity).toFixed(2);
            row.cells[5].textContent = updated.price_only ? '✔️' : '';
            row.cells[6].textContent = updated.quantity;

            $('#editServiceSelectionModal').fadeOut(200);

            // إرسال التحديث للسيرفر
            if (lineId) {
                $.ajax({
                    url: `/confirmation-lines/${lineId}`,
                    type: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: updated,
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم التعديل بنجاح ✅',
                            text: 'تم تحديث بيانات الخدمة على قاعدة البيانات.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ ❌',
                            text: 'فشل في تحديث الخدمة على قاعدة البيانات.'
                        });
                    }
                });
            }
        });
    }
    //-------------------------------------------------------------------------------------------
    function deleteSelectedServiceLine(tableSelector) {
        const selectedRows = document.querySelectorAll(`${tableSelector} tbody input.service-line-selector:checked`);

        if (selectedRows.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: '⚠️ تنبيه',
                text: 'الرجاء اختيار صف واحد على الأقل للحذف.'
            });
            return;
        }

        Swal.fire({
            icon: 'warning',
            title: 'تأكيد الحذف',
            text: 'هل أنت متأكد من حذف الصفوف المحددة؟',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (!result.isConfirmed) return;

            selectedRows.forEach((checkbox) => {
                const row = checkbox.closest('tr');
                const lineId = row.dataset.lineId; // يجب أن يكون موجود في tr: data-line-id="..."

                if (!lineId) {
                    // إذا الصف جديد ولم يُحفظ بعد، نحذفه مباشرة دون AJAX
                    row.remove();
                    return;
                }

                // طلب AJAX لحذف الصف من السيرفر
                $.ajax({
                    url: `/confirmation-lines/${lineId}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // حذف الصف من الجدول مباشرة
                        row.remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف ✅',
                            text: 'تم حذف الصف بنجاح.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ ❌',
                            text: 'فشل الحذف.'
                        });
                    }
                });
            });
        });
    }
    //===================================================================================
    //===================================================================================
    //=============================Service Line==========================================
    //===================================================================================
    //===================================================================================






    //===================================================================================
    //===================================================================================
    //=============================Excel & Files=========================================
    //===================================================================================
    //===================================================================================
    // ---------------------- طباعة جدول الخدمات ----------------------
    // ---------------------- طباعة جدول الخدمات ----------------------
    window.printServiceLineTable = function(tableId) {
        const tableElement = document.getElementById(tableId);
        if (!tableElement) {
            Swal.fire({
                icon: 'warning',
                title: '⚠️',
                text: 'لم يتم العثور على جدول الخدمات.'
            });
            return;
        }

        const serviceTable = $(tableElement).DataTable();
        const selectedCheckboxes = serviceTable.$('input[type="checkbox"]:checked');
        let rowsToProcess = selectedCheckboxes.length > 0 ?
            selectedCheckboxes.parents('tr') :
            serviceTable.rows({
                search: 'applied'
            }).nodes();

        let printContents = `
            <html>
            <head>
                <title>طباعة جدول الخدمات</title>
                <style>
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                    body { font-family: 'Arial', sans-serif; }
                    h2 { text-align: center; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <h2>قائمة الخدمات</h2>
                <table>
                    <thead>
                        <tr>`;

        // الأعمدة
        $(tableElement).find('thead th').each(function() {
            const hasInput = $(this).find('input[type="checkbox"]').length > 0;
            if (!hasInput && $(this).text().trim() !== '') {
                printContents += '<th>' + $(this).text().trim() + '</th>';
            }
        });

        printContents += `</tr></thead><tbody>`;

        // الصفوف
        $(rowsToProcess).each(function() {
            printContents += '<tr>';
            $(this).find('td').each(function(index) {
                // تجاهل checkboxes وعمود الـ ID
                if (index === 0) return;

                // أخذ القيمة من input إذا وجد
                const input = $(this).find('input');
                const checkbox = $(this).find('input[type="checkbox"]');
                if (input.length && !checkbox.length) {
                    printContents += '<td>' + input.val() + '</td>';
                } else if (checkbox.length) {
                    printContents += '<td>' + (checkbox.is(':checked') ? '✔️' : '') + '</td>';
                } else {
                    printContents += '<td>' + $(this).text().trim() + '</td>';
                }
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

        Swal.fire({
            icon: 'success',
            title: 'تم الإرسال للطباعة',
            timer: 1500,
            showConfirmButton: false
        });
    };
    // ---------------------- تصدير جدول الخدمات إلى Excel -------------------------
    window.exportServiceLineExcel = function(tableId) {
        const tableElement = document.getElementById(tableId);
        if (!tableElement) {
            Swal.fire({
                icon: 'warning',
                title: '⚠️',
                text: 'لم يتم العثور على جدول الخدمات.'
            });
            return;
        }

        const serviceTable = $(tableElement).DataTable();
        const selectedCheckboxes = serviceTable.$('input[type="checkbox"]:checked');
        const rowsToProcess = selectedCheckboxes.length > 0 ?
            selectedCheckboxes.parents('tr') :
            serviceTable.rows({
                search: 'applied'
            }).nodes();

        const data = [];

        // Header
        const header = [];
        $(tableElement).find('thead th').each(function(index) {
            if (index === 0) return; // تجاهل ID/checkbox
            header.push($(this).text().trim());
        });
        data.push(header);

        // Rows
        $(rowsToProcess).each(function() {
            const rowData = [];
            $(this).find('td').each(function(index) {
                if (index === 0) return; // تجاهل ID/checkbox

                const input = $(this).find('input');
                const checkbox = $(this).find('input[type="checkbox"]');
                if (input.length && !checkbox.length) {
                    rowData.push(input.val());
                } else if (checkbox.length) {
                    rowData.push(checkbox.is(':checked') ? '✔️' : '');
                } else {
                    rowData.push($(this).text().trim());
                }
            });
            data.push(rowData);
        });

        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Services");

        const fileName = tableId === 'servicesTableEdit' ? 'services-data-edit.xlsx' : 'services-data.xlsx';
        XLSX.writeFile(wb, fileName);

        Swal.fire({
            icon: 'success',
            title: 'تم تصدير البيانات بنجاح ✅',
            timer: 1500,
            showConfirmButton: false
        });
    };
    // ---------------------- طباعة جدول Confirmations -------------------------------
    window.printConfirmationTable = function(tableId = 'confirmationsTable') {
        const tableElement = document.getElementById(tableId);
        if (!tableElement) {
            Swal.fire({
                icon: 'warning',
                title: '⚠️',
                text: 'لم يتم العثور على جدول Confirmations.'
            });
            return;
        }

        const dataTable = $(tableElement).DataTable();
        const selectedCheckboxes = dataTable.$('input[type="checkbox"]:checked');
        const rowsToProcess = selectedCheckboxes.length > 0 ?
            selectedCheckboxes.parents('tr') :
            dataTable.rows({
                search: 'applied'
            }).nodes();

        let printContents = `
            <html>
            <head>
                <title>طباعة Confirmations</title>
                <style>
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                    body { font-family: 'Arial', sans-serif; }
                    h2 { text-align: center; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <h2>قائمة Confirmations</h2>
                <table>
                    <thead><tr>`;

        // الأعمدة (تجاهل checkboxes وعمود فارغ)
        $(tableElement).find('thead th').each(function(index) {
            const hasCheckbox = $(this).find('input[type="checkbox"]').length > 0;
            if (!hasCheckbox && $(this).text().trim() !== '') {
                printContents += '<th>' + $(this).text().trim() + '</th>';
            }
        });

        printContents += `</tr></thead><tbody>`;

        // الصفوف
        $(rowsToProcess).each(function() {
            printContents += '<tr>';
            $(this).find('td').each(function(index) {
                if (index === 0) return; // تجاهل العمود الأول للـ checkbox
                const input = $(this).find('input');
                const checkbox = $(this).find('input[type="checkbox"]');
                if (input.length && !checkbox.length) {
                    printContents += '<td>' + input.val() + '</td>';
                } else if (checkbox.length) {
                    printContents += '<td>' + (checkbox.is(':checked') ? '✔️' : '') + '</td>';
                } else {
                    printContents += '<td>' + $(this).text().trim() + '</td>';
                }
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

        Swal.fire({
            icon: 'success',
            title: 'تم إرسال أمر الطباعة بنجاح',
            timer: 1500,
            showConfirmButton: false
        });
    };

    // ---------------------- تصدير جدول Confirmations إلى Excel ----------------------
    window.exportConfirmationsExcelBtn = function(tableId = 'confirmationsTable') {
        const tableElement = document.getElementById(tableId);
        if (!tableElement) {
            Swal.fire({
                icon: 'warning',
                title: '⚠️',
                text: 'لم يتم العثور على جدول Confirmations.'
            });
            return;
        }

        const dataTable = $(tableElement).DataTable();
        const selectedCheckboxes = dataTable.$('input[type="checkbox"]:checked');
        const rowsToProcess = selectedCheckboxes.length > 0 ?
            selectedCheckboxes.parents('tr') :
            dataTable.rows({
                search: 'applied'
            }).nodes();

        const data = [];

        // Header
        const header = [];
        $(tableElement).find('thead th').each(function(index) {
            if (index === 0) return; // تجاهل العمود الأول للـ checkbox
            header.push($(this).text().trim());
        });
        data.push(header);

        // Rows
        $(rowsToProcess).each(function() {
            const rowData = [];
            $(this).find('td').each(function(index) {
                if (index === 0) return; // تجاهل العمود الأول للـ checkbox
                const input = $(this).find('input');
                const checkbox = $(this).find('input[type="checkbox"]');
                if (input.length && !checkbox.length) {
                    rowData.push(input.val());
                } else if (checkbox.length) {
                    rowData.push(checkbox.is(':checked') ? '✔️' : '');
                } else {
                    rowData.push($(this).text().trim());
                }
            });
            data.push(rowData);
        });

        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Confirmations");

        const fileName = tableId === 'confirmationsTableEdit' ? 'confirmations-data-edit.xlsx' : 'confirmations-data.xlsx';
        XLSX.writeFile(wb, fileName);

        Swal.fire({
            icon: 'success',
            title: '✅ تم تصدير البيانات بنجاح إلى Excel',
            timer: 1500,
            showConfirmButton: false
        });
    };
    //----------------------------------------------------------------------------------
    //===================================================================================
    //===================================================================================
    //=============================Files Manager=========================================
    //===================================================================================
    //===================================================================================


    // upload function
function uploadConfirmationFile(file, confirmationId, mode = 'conf') {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch(`/confirmations/${confirmationId}/files`, {
        method: 'POST',
        body: formData
    })
    .then(async response => {
        if (!response.ok) throw new Error(await response.text());
        return response.json();
    })
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'تم رفع الملف',
            text: 'الملف تم رفعه بنجاح!',
            showConfirmButton: false,
            timer: 1500
        });
        // بعد الرفع مباشرة، تحديث الملفات في المودال
        loadConfirmationFiles(data.files, mode);
    })
    .catch(error => {
        console.error('❌ Upload error:', error);
        Swal.fire('خطأ ❌', 'فشل في رفع الملف: ' + error.message, 'error');
    });
}

// 2️⃣ تحميل وعرض الملفات في المودال
function renderConfirmationFiles(files, modalType = 'conf') {
    const container = modalType === 'conf'
        ? document.getElementById('uploadedFilesArea')
        : document.getElementById('uploadedFilesAreaEdit');

    if (!container) return;

    if (!Array.isArray(files) || files.length === 0) {
        if (container.children.length === 0)
            container.innerHTML = '<p class="text-muted">لا توجد ملفات مرفقة.</p>';
        return;
    }

    // منع التكرار
    const existingFiles = new Set(
        [...container.querySelectorAll('[data-file-id]')].map(el => el.dataset.fileId)
    );

    files.forEach(file => {
        if (!existingFiles.has(String(file.id))) {
            renderConfirmationFileIcon(file, container);
        }
    });

    // إعادة رسم المودال بدون إغلاقه (force reflow)
    container.style.display = 'none';
    container.offsetHeight;
    container.style.display = 'block';
}



// 2️⃣ تحميل الملفات وعرضها
function loadConfirmationFiles(confirmationId, modalType) {
    let container;
    if (modalType === 'conf')
        container = document.getElementById('uploadedFilesArea');
    else if (modalType === 'editConf')
        container = document.getElementById('uploadedFilesAreaEdit');
    else
        return;

    fetch(`/confirmations/${confirmationId}/files-json`)
        .then(res => res.json())
        .then(response => {
            const files = response.files || response;

            if (!Array.isArray(files) || files.length === 0) {
                if (container.children.length === 0)
                    container.innerHTML = '<p class="text-muted">لا توجد ملفات مرفقة.</p>';
                return;
            }

            // إزالة التكرار فقط، لا تمسح القديمة
            const existingFiles = new Set(
                [...container.querySelectorAll('[data-file-id]')].map(el => el.dataset.fileId)
            );

            files.forEach(file => {
                if (!existingFiles.has(String(file.id))) {
                    renderConfirmationFileIcon(file, container);
                }
            });
        })
        .catch(err => {
            console.error('❌ خطأ أثناء تحميل الملفات:', err);
            container.innerHTML = '<p style="color:red;">حدث خطأ أثناء تحميل الملفات.</p>';
        });
}
//////////////
    function openFileManager(modalType) {
        let targetArea;
        const confirmationId = window.currentConfirmationId; // تأكد أنه تم تعيينه عند فتح المودال
        if (!confirmationId) {
            Swal.fire('خطأ ❌', 'لم يتم تحديد رقم الـ Confirmation!', 'error');
            return;
        }

        if (modalType === 'conf') targetArea = document.getElementById('uploadedFilesArea');
        else if (modalType === 'editConf') targetArea = document.getElementById('editUploadedFilesArea');
        else return console.error('Invalid modal type');

        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.multiple = true;
        fileInput.onchange = function(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => uploadConfirmationFile(file, confirmationId, targetArea));
        };
        fileInput.click();
    }
    //-----------------------------------------------------------------------------------
    // هذا الكود يحل مشكلة Cannot set properties of null (setting 'innerHTML')
    // مع الحفاظ على طريقة AJAX و استخدام المسار المباشر

    //-----------------------------------------------------------------------------------
    function getFileIcon(fileName) {
        const ext = fileName.split('.').pop().toLowerCase();
        switch (ext) {
            case 'pdf':
                return '<i class="far fa-file-pdf file-icon pdf-icon"></i>';
            case 'doc':
            case 'docx':
                return '<i class="far fa-file-word file-icon doc-icon"></i>';
            case 'xls':
            case 'xlsx':
                return '<i class="far fa-file-excel file-icon xls-icon"></i>';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return '<i class="far fa-file-image file-icon img-icon"></i>';
            default:
                return '<i class="far fa-file file-icon default-icon"></i>';
        }
    }
    //-----------------------------------------------------------------------------------
    // الآن استدعي الدوال الأخرى بعد هذا


    function renderConfirmationFileIcon(file, container) {
        const fileCard = document.createElement('div');
        fileCard.className = 'file-card';
        fileCard.dataset.fileId = file.id;

        fileCard.innerHTML = `
        <div class="file-card-content">
            <input type="checkbox" class="selectFile file-card-checkbox" value="${file.id}">
            ${getFileIcon(file.name)}
            <span class="file-card-name" title="${file.name}">${file.name}</span>
        </div>
        <div class="file-card-hover-details">
            <span class="file-card-date">Uploaded: ${file.created_at}</span>
            <span class="file-card-size">Size: ${file.size}</span>
            <div class="file-card-actions-hover">
                <button type="button" class="btn-icon view-file-btn" title="View" onclick="viewConfirmationFile(${file.id})">
                    <i class="fas fa-eye"></i>
                </button>
                <button type="button" class="btn-icon download-file-btn" title="Download" onclick="downloadConfirmationFile(${file.id})">
                    <i class="fas fa-download"></i>
                </button>
                <button type="button" class="btn-icon delete-file-btn" title="Delete" onclick="deleteConfirmationFile(${file.id}, this)">
                    <i class="fas fa-trash"></i>
                </button>
                </div>
            </div>
        `;
        container.appendChild(fileCard);
    }
    //-----------------------------------------------------------------------------------



    //-----------------------------------------------------------------------------------
    function viewConfirmationFile(fileId) {
        if (!fileId) return;
        window.open(`/confirmations/files/view/${fileId}`, '_blank');
    }
    //-----------------------------------------------------------------------------------
    function downloadConfirmationFile(fileId) {
        if (!fileId) return;
        window.open(`/confirmations/files/download/${fileId}`, '_blank');
    }
    //-----------------------------------------------------------------------------------
    function deleteConfirmationFile(fileId, btnElement, event) {
        if (event) event.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'تأكيد الحذف؟',
            text: 'هل أنت متأكد من حذف هذا الملف؟',
            showCancelButton: true,
            confirmButtonText: 'حذف',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/confirmations/files/${fileId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(() => {
                        // احذف فقط البطاقة من الـ DOM
                        const fileCard = btnElement.closest('.file-card');
                        if (fileCard) fileCard.remove();

                        Swal.fire('تم الحذف', '✔️ تم حذف الملف بنجاح', 'success');
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('خطأ ❌', 'فشل حذف الملف.', 'error');
                    });
            }
        });
    }
    //-----------------------------------------------------------------------------------
    //===================================================================================
    //===================================================================================
    //=============================Files Manager=========================================
    //===================================================================================
    //===================================================================================
</script>
<style>
    /* نمط لجعل محتوى التبويبات داخل المودال قابلاً للتمرير */
    .modal-scrollable-content {
        /* تحديد الحد الأقصى للارتفاع (65% من ارتفاع النافذة المرئية)، يمكنك تعديل القيمة */
        max-height: 65vh;
        /* تفعيل شريط التمرير العمودي فقط عند تجاوز المحتوى للحد الأقصى */
        overflow-y: auto;
        /* إضافة مسافة داخلية لتبدو أفضل */
        padding: 15px;
    }
</style>
@endsection
