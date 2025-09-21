@extends('layouts.app')
@section('title', __('Projects'))
@section('content')
@include('projects.create')

<main class="main-content">
    <section id="projects-section" class="section-content active">
        <div class="icon-toolbar">
            <div>
                <button title="Add" onclick="openProjectModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                <button title="Edit" onclick="editSelectedProject()" class="btn btn-warning"><i class="fas fa-pen"></i></button>
                <button title="Delete" onclick="deleteSelectedProjects()" class="btn-icon"><i class="fas fa-trash"></i></button>
            </div>
            <div class="icon-separator"></div>
            <div>
                <button title="File Manager" onclick="goToProjectFiles()" class="btn-icon"><i class="fas fa-folder-open"></i></button>
                <button title="Export to Excel" onclick="exportProjectsExcel()" class="btn-icon"><i class="fa-solid fa-table"></i></button>
                <button title="Print" onclick="printProjectsTable()" class="btn-icon"><i class="fas fa-print"></i></button>
            </div>
        </div>

        <div class="table-responsive-container">
            <table id="projectsTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAllProjects">
                        </th>
                        <th>
                            Project Reference<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Project Name<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Arbic Name<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Date Registered<br>
                            <span>From:</span><input type="text" class="column-filter date-range-filter flatpickr-input" data-filter-type="date-from">
                            <span>To:</span><input type="text" class="column-filter date-range-filter flatpickr-input" data-filter-type="date-to">
                        </th>
                        <th>
                            Location<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            Customer<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            owner<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            consultant<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>
                        <th>
                            contractor<br>
                            <input type="text" placeholder="Search..." class="column-filter">
                        </th>

                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        </section>
</main>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


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
    // ================== تهيأة وتحميل وبحث الجدول==================
    $(document).ready(function() {

        // index العمود اللي فيه التاريخ (يبدأ من 0)
        var dateColumnIndex = 4; // عندك العمود السابع "date_registered"

        // تهيئة Flatpickr على الحقول
        flatpickr(".flatpickr-input", {
            dateFormat: "Y-m-d",
            locale: "en",
            allowInput: true
        });

        // تهيئة الجدول مرة واحدة
        window.projectsTable = $('#projectsTable').DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            scrollX: true,
            columnDefs: [
                { orderable: false, targets: [0] } // عمود الشيكبوكس وعمود الأزرار
            ]
        });

        // تحميل البيانات للمرة الأولى
        loadProjects();

        function parseDateToNumber(str) {
            if (!str) return null;
            str = String(str).trim().split(' ')[0]; // نشيل الوقت لو موجود
            var parts = str.split('-'); // flatpickr يخرج Y-m-d
            if (parts.length !== 3) return null;

            var y = parts[0], m = parts[1], d = parts[2];
            return parseInt(y + m.padStart(2, '0') + d.padStart(2, '0'), 10);
        }
        // ---------------------------------------------------------------------------------------
        // الكود الجديد لتفعيل البحث في الأعمدة
        // فلتر مخصص للتواريخ
        $.fn.dataTable.ext.search.push(function(settings, data) {
            if (settings.nTable.id !== 'projectsTable') return true;

            var minVal = $('input[data-filter-type="date-from"]').val();
            var maxVal = $('input[data-filter-type="date-to"]').val();

            var minNum = minVal ? parseDateToNumber(minVal) : null;
            var maxNum = maxVal ? parseDateToNumber(maxVal) : null;
            var rowNum = parseDateToNumber(data[dateColumnIndex]);

            if (rowNum === null) return (minNum === null && maxNum === null);

            if (minNum === null && maxNum === null) return true;
            if (minNum === null && rowNum <= maxNum) return true;
            if (maxNum === null && rowNum >= minNum) return true;
            if (rowNum >= minNum && rowNum <= maxNum) return true;

            return false;
        });

        $('input[data-filter-type="date-from"], input[data-filter-type="date-to"]').on('change keyup', function() {
            projectsTable.draw();
        });


        // استنساخ صف الرؤوس لإنشاء صف البحث
        $('#projectsTable thead tr').clone(true).appendTo('#projectsTable thead');

        // منع البحث في عمود صندوق الاختيار
        $('#projectsTable thead tr:eq(1) th:eq(0)').empty();

        // تطبيق الفلترة على كل حقل بحث
       projectsTable.columns().every(function() {
            var that = this;
            var column =projectsTable.column(this.index());

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

                if (column.search() !== val) {
                    column.search(val).draw();
                }
            });
        });

    });
    // ---------------------------------------------------------------------------------------
    function loadProjects() {
        $.get('/projects', function(projects) {
            // تنظيف الجدول
            projectsTable.clear();

            // إضافة البيانات الجديدة
            projects.forEach(p => {
                projectsTable.row.add([
                    `<input type="checkbox" class="project-checkbox" value="${p.id}">`,
                    p.reference || '',
                    p.name || '',
                    p.arabic_name || '',
                    p.registration_date || '',
                    p.region || '',
                    p.customer ? p.customer.customer_name : '',
                    p.owner || '',
                    p.consultant || '',
                    p.contractor || '',
                ]);
            });

            projectsTable.draw();
        });
    }
    //----------------------------------------------------------------------------------------
    // --------------------Select All & Update 'select all' button----------------------------
    $('#selectAllProjects').on('change', function() {
        let rows = projectsTable.rows({ 'search': 'applied' }).nodes();
        $('input.project-checkbox', rows).prop('checked', this.checked);
    });
    $('#projectsTable tbody').on('change', 'input.project-checkbox', function() {
        let allChecked = $('.project-checkbox').length === $('.project-checkbox:checked').length;
        $('#selectAllProjects').prop('checked', allChecked);
    });
    //----------------------------------------------------------------------------------------
    function switchTab(tabName) {
        $('.form-tab-content').hide();
        $('.tab-buttons button').removeClass('active');

        if (tabName === 'project') {
            $('#projectTab').show();
            $('#project-btn').addClass('active');
        } else if (tabName === 'contact') {
            $('#contactTab').show();
            $('#contact-btn').addClass('active');
        } else if (tabName === 'edit-project') {
            $('#editProjectTab').show();
            $('#edit-project-btn').addClass('active');
        } else if (tabName === 'edit-contact') {
            $('#editContactTab').show();
            $('#edit-contact-btn').addClass('active');
        }
    }
    //----------------------------------------------------------------------------------------
    function deleteProject(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "سيتم حذف المشروع ولا يمكنك التراجع عن هذا الإجراء!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/projects/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف ✅',
                            text: 'تم حذف المشروع بنجاح.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        loadProjects();
                    },
                    error: function(xhr) {
                        console.error("Error deleting project:", xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ ❌',
                            text: 'حدث خطأ أثناء محاولة حذف المشروع.',
                            confirmButtonText: 'حسناً'
                        });
                    }
                });
            }
        });
    }
    //----------------------------------------------------------------------------------------
    function openProjectModal() {
        $('#projectForm')[0].reset();
        $('#projectId').val('');
        $('#projectModal').show();
    }
    //----------------------------------------------------------------------------------------
    function closeProjectModal() {
        $('#projectModal').hide();
    }
    //----------------------------------------------------------------------------------------
    function closeEditProjectModal() {
        $('#editProjectModal').hide();
    }
    //----------------------------------------------------------------------------------------
    function getSelectedProjectIds() {
        let ids = [];
        $('.project-checkbox:checked').each(function() {
            ids.push($(this).val());
        });
        return ids;
    }
    //----------------------------------------------------------------------------------------
    function saveProject(closeAfterSave) {
    const projectId = $('#projectId').val();
    const projectName = $('#projectName').val().trim();

    if (!projectName) {
        Swal.fire("تحذير", "⚠️ الرجاء إدخال اسم للمشروع.", "warning");
        return;
    }

    // الحصول على القيم من الـ selects
    const customerId = $('#customer').val() || null;
    const ownerId = $('#owner').val() || null;
    const consultantId = $('#consultant').val() || null;
    const contractorId = $('#contractor').val() || null;

    // التأكد من اختيار جهة واحدة على الأقل
    if (!customerId && !ownerId && !consultantId && !contractorId) {
        Swal.fire("تحذير", "⚠️ الرجاء اختيار جهة واحدة على الأقل للمشروع.", "warning");
        return;
    }

    // تحديد الـ URL والطريقة
    const url = projectId ? `/projects/${projectId}` : '/projects';
    const method = projectId ? 'PUT' : 'POST';

    // بناء البيانات للإرسال
    const formData = {
        name: projectName,
        arabic_name: $('#projectArabicName').val() || null,
        registration_date: $('#registrationDate').val() || null,
        customer_id: customerId,
        owner_id: ownerId,
        consultant_id: consultantId,
        contractor_id: contractorId,
        projectArabicLocation: $('#projectArabicLocation').val() || null,
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: url,
        type: method,
        data: formData,
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: 'تم الحفظ بنجاح ✅',
                text: 'تم حفظ المشروع بنجاح.',
                timer: 2000,
                showConfirmButton: false
            });

            $('#projectId').val(response.id);
            loadProjects();
            if (closeAfterSave) closeProjectModal();
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'خطأ ❌',
                text: 'حدث خطأ أثناء حفظ المشروع',
                confirmButtonText: 'حسناً'
            });
        }
    });
}


    //----------------------------------------------------------------------------------------
    function editProjectModal(id) {
        // إرسال طلب للحصول على بيانات المشروع
        $.get(`/projects/${id}`, function(project) {
            if (!project || !project.id) {
                alert("❌ لم يتم العثور على بيانات المشروع!");
                return;
            }

            // تعبئة حقول النموذج ببيانات المشروع
            $('#editProjectId').val(project.id);
            $('#editProjectName').val(project.name);
            $('#editProjectArabicName').val(project.arabic_name || '');
            $('#editRegistrationDate').val(project.registration_date || '');
             populateEditProjectParties(project);
            $('#editProjectArabicLocation').val(project.projectArabicLocation || '');

            // استدعاء الدالة الصحيحة لتعبئة جدول جهات الاتصال
            // هنا يتم تمرير مصفوفة جهات الاتصال الخاصة بالمشروع
            // ✅ إعادة تعبئة الجدول بالبيانات الجديدة القادمة من الخادم
            if (project.contacts) {
                window.populateContactsTableEdit(project.contacts);
            } else {
                window.contactsTableEdit.clear().draw();
            }

            // عرض المودال (النافذة المنبثقة)
            $('#editProjectModal').show();

        }).fail(function(err) {
            console.error("Error fetching project:", err);
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: '❌ حدث خطأ أثناء تحميل بيانات المشروع.'
            });
        });
    }
    //----------------------------------------------------------------------------------------
    function saveEditProject(closeAfterSave) {
        let projectId = $('#editProjectId').val();
        if (!projectId) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ ❌',
                text: 'لا يوجد معرف مشروع للتعديل.',
                confirmButtonText: 'حسناً'
            });
            return;
        }

        let formData = {
            name: $('#editProjectName').val(),
            arabic_name: $('#editProjectArabicName').val(),
            registration_date: $('#editRegistrationDate').val(),
            customer_id: $('#editCustomer').val(),
            owner: $('#editOwner').val(),
            consultant: $('#editConsultant').val(),
            contractor: $('#editContractor').val(),
            projectArabicLocation: $('#editProjectArabicLocation').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };

        $.ajax({
            url: `/projects/${projectId}`,
            type: 'PUT',
            data: formData,
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'تم التعديل بنجاح ✅',
                    text: 'تم تعديل المشروع بنجاح.',
                    timer: 2000,
                    showConfirmButton: false
                });

                loadProjects();
                if (closeAfterSave) closeEditProjectModal();
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ ❌',
                    text: 'حدث خطأ أثناء تعديل المشروع',
                    confirmButtonText: 'حسناً'
                });
            }
        });
    }
    //----------------------------------------------------------------------------------------
    function deleteSelectedProjects() {
        let selectedIds = getSelectedProjectIds();

        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه ⚠️',
                text: 'يرجى اختيار مشروع واحد على الأقل!',
                confirmButtonText: 'حسناً'
            });
            return;
        }

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: `سيتم حذف ${selectedIds.length} مشروع/مشاريع ولا يمكنك التراجع عن هذا الإجراء!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/projects/delete-multiple',
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحذف ✅',
                            text: 'تم حذف المشاريع المحددة بنجاح!',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        loadProjects();
                    },
                    error: function(xhr) {
                        console.error("Error deleting projects:", xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ ❌',
                            text: 'حدث خطأ أثناء محاولة حذف المشاريع.',
                            confirmButtonText: 'حسناً'
                        });
                    }
                });
            }
        });
    }
    //----------------------------------------------------------------------------------------
    function editSelectedProject() {
        let selected = $('.project-checkbox:checked'); // افترض أن كل checkbox لديه class projectCheckbox

        if (selected.length === 0) {
            Swal.fire('⚠️', 'الرجاء اختيار مشروع واحد للتعديل', 'warning');
            return;
        }
        if (selected.length > 1) {
            Swal.fire('⚠️', 'الرجاء اختيار مشروع واحد فقط', 'warning');
            return;
        }

        let projectId = selected.val(); // جلب id المشروع
        editProjectModal(projectId);         // استدعاء دالة التعديل الموجودة
    }
    //----------------------------------------------------------------------------------------
    window.goToProjectFiles = function() {
        let selectedIds = getSelectedProjectIds();

        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه ⚠️',
                text: 'يرجى اختيار مشروع واحد!',
                confirmButtonText: 'حسناً'
            });
            return;
        }

        if (selectedIds.length > 1) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه ⚠️',
                text: 'يرجى اختيار مشروع واحد فقط!',
                confirmButtonText: 'حسناً'
            });
            return;
        }

        // التوجيه لصفحة الملفات
        let projectId = selectedIds[0];
        window.location.href = `/projects/${projectId}/files`;
    }
    //----------------------------------------------------------------------------------------
    //----------------------------------Contacts----------------------------------------------
    //----------------------------------------------------------------------------------------
    window.toggleAllContacts = function(source, tableId) {
        // ✅ تم تغيير اسم المعلمة من projectTable إلى tableId
        // ✅ تم إضافة مسافة بين const و projectTable
        const projectTable = $(`#${tableId}`).DataTable();
        const rows = projectTable.rows({ 'search': 'applied' }).nodes();
        $('input.contact-select', rows).prop('checked', source.checked);
    };
    //----------------------------------------------------------------------------------------
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
        // فلترة جهات الاتصال في جدول جهات الاتصال
        $('#contactsTable thead .column-filter').on('keyup change', function(){
            let index = $(this).parent().index();
            window.contactsTable.column(index).search(this.value).draw();
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
        // دالة لتعبئة جدول جهات الاتصال في وضع التعديل
        window.populateContactsTableEdit = function(contacts) {
            if (!window.contactsTableEdit) {
                console.error("contactsTableEdit is not initialized!");
                return;
            }
            window.contactsTableEdit.clear();
            window.contactsTableEdit.rows.add(contacts).draw();
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
        window.saveContactForProject = function(modalType = 'add') {
            let projectId = (modalType === 'edit') ? $('#editProjectId').val() : $('#projectId').val();
            if (!projectId) {
                Swal.fire("خطأ", "❌ الرجاء حفظ العميل أولًا قبل تعديل جهة الاتصال", "error");
                return;
            }

            let contactId = (modalType === 'edit') ? $('#editContactId').val().trim() : $('#editContactIdAdd').val().trim();
            let url = contactId ? `/projects/${projectId}/contacts/${contactId}` : `/projects/${projectId}/contacts`;
            let method = contactId ? 'PUT' : 'POST';

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
                url: url,
                type: method,
                data: formData,
                success: function(res) {
                    Swal.fire("نجاح", "✔️ تم الحفظ بنجاح", "success");

                    // ⭐ NEW: Fetch all contacts again and re-populate the table
                    $.get(`/projects/${projectId}/contacts`, function(contacts) {
                        if (modalType === 'edit') {
                            window.contactsTableEdit.clear().rows.add(contacts).draw();
                        } else {
                            window.contactsTable.clear().rows.add(contacts).draw();
                        }
                    });

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

                            // ✅ تصحيح الوصول إلى كائن DataTable
                            let projectTable = window[tableSelector.replace('#', '')];

                            if (projectTable) {
                                projectTable.rows(function(idx, data, node) {
                                    return ids.includes($(node).attr('data-contact-id'));
                                }).remove().draw(false);
                            }
                        } else {
                            Swal.fire("خطأ", res.message || "❌ لم يتم الحذف", "error");
                        }
                    },
                    error: function(xhr) {
                        let msg = '❌ خطأ في الاتصال بالسيرفر';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        Swal.fire("خطأ", msg, "error");
                    }
                });
            });
        };

        window.populateContactFormForEdit = function(modalType = 'add') {
            // تحديد كائن DataTable والجدول المستهدف بناءً على نوع المودال
            let projectTable;
            let $tableSelector;
            if (modalType === 'edit') {
                projectTable = window.contactsTableEdit;
                $tableSelector = '#contactsTableEdit';
            } else {
                projectTable = window.contactsTable;
                $tableSelector = '#contactsTable';
            }

            // البحث عن مربع الاختيار المحدد في جسم الجدول
            let selectedCheckbox = $(`${$tableSelector} tbody input[type="checkbox"]:checked`);

            // التحقق من أن هناك عنصر واحد فقط محدد
            if (selectedCheckbox.length === 0) {
                Swal.fire("تنبيه", "⚠️ الرجاء اختيار جهة اتصال واحدة للتعديل", "warning");
                return;
            }
            if (selectedCheckbox.length > 1) {
                Swal.fire("تنبيه", "⚠️ الرجاء اختيار جهة اتصال واحدة فقط للتعديل", "warning");
                return;
            }

            // الحصول على بيانات السطر المحدد من كائن DataTable
            let rowData = projectTable.row(selectedCheckbox.closest('tr')).data();

            // التحقق من أن البيانات موجودة
            if (!rowData) {
                Swal.fire("خطأ", "❌ لم يتم العثور على بيانات جهة الاتصال", "error");
                return;
            }

            // تعبئة الحقول بالبيانات بناءً على نوع المودال
            if (modalType === 'edit') {
                $('#editContactId').val(rowData.id);
                $('#contactNameedit').val(rowData.name);
                $('#contactEmailedit').val(rowData.email);
                $('#contactPhoneedit').val(rowData.phone);
                $('#contactMobileedit').val(rowData.mobile);
                $('#contactPositionedit').val(rowData.position);
                $('#isPrimaryContact').prop('checked', rowData.is_primary == 1 || rowData.is_primary === true);
            } else {
                $('#editContactIdAdd').val(rowData.id);
                $('#contactNameAdd').val(rowData.name);
                $('#contactEmailAdd').val(rowData.email);
                $('#contactPhoneAdd').val(rowData.phone);
                $('#contactMobileAdd').val(rowData.mobile);
                $('#contactPositionAdd').val(rowData.position);
                $('#isPrimaryContactAdd').prop('checked', rowData.is_primary == 1 || rowData.is_primary === true);
            }
        };

    });
    // ----------------------------------------------------------------------
    //----------------------------------------------------------------------------------------
    //----------------------------------Print & Excel-----------------------------------------
    //----------------------------------------------------------------------------------------
    window.printProjectsTable = function() {
        // تحديد الجدول المطلوب (في هذه الحالة جدول المشاريع)
        const projectsTableElement = document.getElementById('projectsTable');
        if (!projectsTableElement) {
            // رسالة تنبيه في حالة عدم العثور على الجدول
            showAlert('⚠️ لم يتم العثور على جدول المشاريع.', 'warning');
            return;
        }

        // تهيئة كائن DataTable
        const projectsTable = $(projectsTableElement).DataTable();

        // العثور على مربعات التحديد المحددة
        const selectedCheckboxes = projectsTable.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            // إذا كان هناك صفوف محددة، قم بمعالجة تلك الصفوف فقط
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            // إذا لم يتم تحديد أي شيء، قم بمعالجة جميع الصفوف المرئية
            rowsToProcess = projectsTable.rows({
                'search': 'applied'
            }).nodes();
        }

        let printContents = `
            <style>
                .projectsTable { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                body { font-family: 'Arial', sans-serif; }
                h2 { text-align: center; margin-bottom: 20px; }
            </style>
            <h2>قائمة المشاريع</h2>
            <table>
                <thead><tr>`;

        // نسخ رؤوس الأعمدة
        $(projectsTableElement).find('thead tr:first th').each(function() {
            if ($(this).find('input[type="checkbox"]').length > 0 || $(this).text().trim() === '') {
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
                if ($(this).find('input[type="checkbox"]').length > 0 || $(this).find('.icon-toolbar').length > 0) {
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
    //----------------------------------------------------------------------------------------
    window.exportProjectsExcel = function() {
        // تحديد الجدول المطلوب (في هذه الحالة جدول المشاريع)
        const projectsTableElement = document.getElementById('projectsTable');
        if (!projectsTableElement) {
            // رسالة تنبيه في حالة عدم العثور على الجدول
            showAlert('⚠️ لم يتم العثور على جدول المشاريع.', 'warning');
            return;
        }

        const projectsTable = $(projectsTableElement).DataTable();

        const selectedCheckboxes = projectsTable.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            rowsToProcess = projectsTable.rows({ 'search': 'applied' }).nodes();
        }

        // 1. بناء البيانات لـ SheetJS
        const data = [];
        const header = [];
        $(projectsTableElement).find('thead th').each(function() {
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

        // 2. إنشاء ورقة العمل والمصنف
        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "المشاريع");

        // 3. كتابة وتنزيل الملف بصيغة XLSX
        XLSX.writeFile(wb, "projects-data.xlsx");

        showAlert('تم تصدير البيانات بنجاح إلى ملف Excel.', 'success');
    };
    //----------------------------------------------------------------------------------------
    window.printContactsTable = function(tableId) {
        const tableElement = document.getElementById(tableId);
        if (!tableElement) {
            showAlert('⚠️ لم يتم العثور على جدول جهات الاتصال.', 'warning');
            return;
        }

        const contactsTable = $(tableElement).DataTable();
        const selectedCheckboxes = contactsTable.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            rowsToProcess = contactsTable.rows({ 'search': 'applied' }).nodes();
        }

        let printContents = `
            <html>
            <head>
                <title>طباعة قائمة جهات الاتصال</title>
                <style>
                    .contactsTable { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
                    body { font-family: 'Arial', sans-serif; }
                    h2 { text-align: center; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <h2>قائمة جهات الاتصال</h2>
                <table>
                    <thead><tr>`;

        $(tableElement).find('thead tr:first th').each(function() {
            if ($(this).find('input[type="checkbox"]').length > 0 || $(this).text().trim() === '') {
                return;
            }
            const headerClone = $(this).clone();
            headerClone.find('input, select, .btn, i').remove();
            const columnTitle = headerClone.text().trim();
            printContents += '<th>' + columnTitle + '</th>';
        });

        printContents += `</tr></thead><tbody>`;

        $(rowsToProcess).each(function() {
            printContents += '<tr>';
            $(this).find('td').each(function() {
                if ($(this).find('input[type="checkbox"]').length > 0 || $(this).find('.icon-toolbar').length > 0) {
                    return;
                }
                printContents += '<td>' + $(this).text().trim() + '</td>';
            });
            printContents += '</tr>';
        });

        printContents += `</tbody></table></body></html>`;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContents);
        printWindow.document.close();
        printWindow.focus();

        // تأكد من أن أمر الطباعة يتم استدعاؤه بعد تحميل المحتوى بالكامل.
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };

        // قم بتغيير مكان استدعاء showAlert
        // أضف رسالة النجاح في نهاية الدالة.
        showAlert('تم إرسال أمر الطباعة بنجاح.', 'success');
    };
    //----------------------------------------------------------------------------------------
    window.exportContactsExcelBtn = function(tableId) {
        // تحديد الجدول المطلوب بناءً على الـ ID
        const contactsTableElement = document.getElementById(tableId);
        if (!contactsTableElement) {
            showAlert('⚠️ لم يتم العثور على جدول جهات الاتصال.', 'warning');
            return;
        }

        const contactsTable = $(contactsTableElement).DataTable();

        const selectedCheckboxes = contactsTable.$('input[type="checkbox"]:checked');
        let rowsToProcess;

        if (selectedCheckboxes.length > 0) {
            rowsToProcess = selectedCheckboxes.parents('tr');
        } else {
            rowsToProcess = contactsTable.rows({ 'search': 'applied' }).nodes();
        }

        // 1. بناء البيانات لـ SheetJS
        const data = [];
        const header = [];
        $(contactsTableElement).find('thead th').each(function() {
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

        // 2. إنشاء ورقة العمل والمصنف
        const ws = XLSX.utils.aoa_to_sheet(data);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "جهات_الاتصال");

        // 3. كتابة وتنزيل الملف بصيغة XLSX
        XLSX.writeFile(wb, "contacts-data.xlsx");

        // إظهار رسالة النجاح
        showAlert('تم تصدير البيانات بنجاح إلى ملف Excel.', 'success');
    };


    function populateEditProjectParties(project) {
    $('#editCustomer').val(project.customer_id);
    $('#editOwner').val(project.owner_id);
    $('#editConsultant').val(project.consultant_id);
    $('#editContractor').val(project.contractor_id);
}


</script>

@endsection
