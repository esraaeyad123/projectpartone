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
                <button title="Export to Excel" onclick="exportTableToExcel('projectsTable', 'ProjectsExport')" class="btn-icon"><i class="fa-solid fa-table"></i></button>
                <button title="Print" onclick="printProjectTable()" class="btn-icon"><i class="fas fa-print"></i></button>
            </div>
        </div>

        <div class="table-responsive-container">
            <table id="projectsTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAllProjects"></th>
                        <th>Project Reference<br><input type="text" placeholder="Search..." class="column-filter"></th>
                        <th>Project Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                        <th>Arabic Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                        <th>Date Registered<br>
                            <span>From:</span><input type="text" class="column-filter date-range-filter flatpickr-input" data-filter-type="date-from">
                            <span>To:</span><input type="text" class="column-filter date-range-filter flatpickr-input" data-filter-type="date-to">
                        </th>
                        <th>Location<br><input type="text" placeholder="Search..." class="column-filter"></th>
                        <th>Customer<br><input type="text" placeholder="Search..." class="column-filter"></th>
                        <th>Owner<br><input type="text" placeholder="Search..." class="column-filter"></th>
                        <th>Consultant<br><input type="text" placeholder="Search..." class="column-filter"></th>
                        <th>Contractor<br><input type="text" placeholder="Search..." class="column-filter"></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </section>
</main>

<!-- CSS & JS Libraries -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {

    // ===================== Helpers =====================
    function showAlert(message, type = 'success') {
        Swal.fire({
            title: type === 'success' ? 'Success!' : (type === 'error' ? 'Error!' : 'Warning!'),
            text: message,
            icon: type,
            confirmButtonText: 'OK'
        });
    }

    function showConfirm(message, callback, title = 'Confirm', confirmBtn='Yes', cancelBtn='No') {
        Swal.fire({
            title: title,
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: confirmBtn,
            cancelButtonText: cancelBtn
        }).then(result => { if(result.isConfirmed) callback(); });
    }

    function parseDateToNumber(str) {
        if(!str) return null;
        str = String(str).split(' ')[0];
        const parts = str.split('-');
        if(parts.length!==3) return null;
        return parseInt(parts[0] + parts[1].padStart(2,'0') + parts[2].padStart(2,'0'), 10);
    }

    // ===================== Flatpickr =====================
    flatpickr(".flatpickr-input", { dateFormat:"Y-m-d", locale:"en", allowInput:true });

    // ===================== Projects DataTable =====================
    const dateColumnIndex = 4;
    window.projectsTable = $('#projectsTable').DataTable({
        responsive:true,
        autoWidth:false,
        scrollX:true,
        columnDefs:[{ orderable:false, targets:[0] }]
    });

    // Load projects
    function loadProjects(){
        $.get('/projects', function(projects){
            projectsTable.clear();
            projects.forEach(p => {
                projectsTable.row.add([
                    `<input type="checkbox" class="project-checkbox" value="${p.id}">`,
                    p.reference || '',
                    p.name || '',
                    p.arabic_name || '',
                    p.registration_date || '',
                    p.region || '',
                    p.customer?.customer_name || '',
                    p.owner || '',
                    p.consultant || '',
                    p.contractor || ''
                ]);
            });
            projectsTable.draw();
        });
    }
    loadProjects();

    // ===================== Date Filter =====================
    $.fn.dataTable.ext.search.push((settings, data) => {
        if(settings.nTable.id !== 'projectsTable') return true;
        const min = parseDateToNumber($('input[data-filter-type="date-from"]').val());
        const max = parseDateToNumber($('input[data-filter-type="date-to"]').val());
        const rowDate = parseDateToNumber(data[dateColumnIndex]);
        if(rowDate === null) return (!min && !max);
        if(!min && !max) return true;
        if(!min && rowDate <= max) return true;
        if(!max && rowDate >= min) return true;
        return rowDate >= min && rowDate <= max;
    });

    $('input[data-filter-type="date-from"], input[data-filter-type="date-to"]').on('keyup change', ()=>projectsTable.draw());

    // ===================== Column Search =====================
    $('#projectsTable thead tr').clone(true).appendTo('#projectsTable thead');
    $('#projectsTable thead tr:eq(1) th:eq(0)').empty();
    projectsTable.columns().every(function(){
        const column = this;
        if(this.index()===0 || $(this.header()).find('input[data-filter-type="date-from"]').length>0) return;
        $('input, select', this.header()).on('keyup change', function(){
            const val = $.fn.dataTable.util.escapeRegex($(this).val());
            if(column.search() !== val) column.search(val).draw();
        });
    });

    // ===================== Select All Checkbox =====================
    $('#selectAllProjects').on('change', function(){
        const rows = projectsTable.rows({search:'applied'}).nodes();
        $('input.project-checkbox', rows).prop('checked', this.checked);
    });
    $('#projectsTable tbody').on('change','input.project-checkbox',function(){
        $('#selectAllProjects').prop('checked', $('.project-checkbox').length === $('.project-checkbox:checked').length);
    });

    // ===================== Project CRUD =====================
    function getSelectedProjectIds(){ return $('.project-checkbox:checked').map((i,e)=>$(e).val()).get(); }

    window.openProjectModal = function(){
        $('#projectForm')[0].reset();
        $('#projectId').val('');
        $('#projectModal').show();
    };
    window.closeProjectModal = function(){ $('#projectModal').hide(); };
    window.closeEditProjectModal = function(){ $('#editProjectModal').hide(); };

    window.saveProject = function(closeAfterSave=false){
        const projectId = $('#projectId').val();
        const url = projectId ? `/projects/${projectId}` : '/projects';
        const method = projectId ? 'PUT' : 'POST';
        const formData = {
            name: $('#projectName').val(),
            arabic_name: $('#projectArabicName').val(),
            registration_date: $('#registrationDate').val(),
            customer_id: $('#customer').val(),
            owner: $('#owner').val(),
            consultant: $('#consultant').val(),
            contractor: $('#contractor').val(),
            projectArabicLocation: $('#projectArabicLocation').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        $.ajax({
            url, type:method, data:formData,
            success: function(res){
                Swal.fire({icon:'success', title:'تم الحفظ ✅', timer:2000, showConfirmButton:false});
                $('#projectId').val(res.id);
                loadProjects();
                if(closeAfterSave) closeProjectModal();
            },
            error: function(xhr){ Swal.fire({icon:'error', title:'خطأ ❌', text:'حدث خطأ أثناء حفظ المشروع', confirmButtonText:'حسناً'}); }
        });
    };

    window.editProjectModal = function(id){
        $.get(`/projects/${id}`, function(project){
            if(!project) return showAlert("❌ لم يتم العثور على بيانات المشروع",'error');
            $('#editProjectId').val(project.id);
            $('#editProjectName').val(project.name);
            $('#editProjectArabicName').val(project.arabic_name || '');
            $('#editRegistrationDate').val(project.registration_date || '');
            $('#editCustomer').val(project.customer_id || '');
            $('#editOwner').val(project.owner || '');
            $('#editConsultant').val(project.consultant || '');
            $('#editContractor').val(project.contractor || '');
            $('#editProjectArabicLocation').val(project.projectArabicLocation || '');
            populateContactsTableEdit(project.contacts || []);
            $('#editProjectModal').show();
        }).fail(()=>showAlert('❌ حدث خطأ أثناء تحميل بيانات المشروع','error'));
    };

    window.saveEditProject = function(closeAfterSave=false){
        const projectId = $('#editProjectId').val();
        if(!projectId) return showAlert('لا يوجد معرف مشروع للتعديل','error');
        const formData = {
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
            url:`/projects/${projectId}`, type:'PUT', data:formData,
            success: function(){ Swal.fire({icon:'success', title:'تم التعديل ✅', timer:2000, showConfirmButton:false}); loadProjects(); if(closeAfterSave) closeEditProjectModal(); },
            error: function(){ showAlert('حدث خطأ أثناء تعديل المشروع','error'); }
        });
    };

    window.deleteSelectedProjects = function(){
        const selectedIds = getSelectedProjectIds();
        if(selectedIds.length===0) return showAlert('يرجى اختيار مشروع واحد على الأقل!','warning');
        showConfirm(`سيتم حذف ${selectedIds.length} مشروع/مشاريع ولا يمكنك التراجع عن هذا الإجراء!`, ()=>{
            $.ajax({
                url:'/projects/delete-multiple', type:'POST',
                data:{ids:selectedIds,_token:$('meta[name="csrf-token"]').attr('content')},
                success:function(){ showAlert('تم حذف المشاريع المحددة بنجاح!','success'); loadProjects(); },
                error:function(){ showAlert('حدث خطأ أثناء محاولة حذف المشاريع','error'); }
            });
        },'هل أنت متأكد؟','نعم، احذف!','إلغاء');
    };

    window.editSelectedProject = function(){
        const selected = $('.project-checkbox:checked');
        if(selected.length===0) return showAlert('الرجاء اختيار مشروع واحد للتعديل','warning');
        if(selected.length>1) return showAlert('الرجاء اختيار مشروع واحد فقط','warning');
        editProjectModal(selected.val());
    };

    window.goToProjectFiles = function(){
        const selectedIds = getSelectedProjectIds();
        if(selectedIds.length===0) return showAlert('يرجى اختيار مشروع واحد!','warning');
        if(selectedIds.length>1) return showAlert('يرجى اختيار مشروع واحد فقط!','warning');
        window.location.href = `/projects/${selectedIds[0]}/files`;
    };

    // ===================== Contacts Table =====================
    window.contactsTable = $('#contactsTable').DataTable({
        columns:[
            {data:null, render:data=>`<input type="checkbox" class="contact-select" value="${data.id}">`, orderable:false},
            {data:'name'},{data:'email'},{data:'phone'},{data:'mobile'},{data:'position'},
            {data:'is_primary', render:d => (d==1 || d==='1' || d===true)?'Yes':'No'}
        ],
        createdRow:(row,data)=>$(row).attr('data-contact-id',data.id)
    });

    window.contactsTableEdit = $('#contactsTableEdit').DataTable({
        responsive:true,
        columns:[
            {data:null, render:data=>`<input type="checkbox" class="contact-select" value="${data.id}">`, orderable:false},
            {data:'name'},{data:'email'},{data:'phone'},{data:'mobile'},{data:'position'},
            {data:'is_primary', render:d => (d==1 || d==='1' || d===true)?'Yes':'No'}
        ],
        createdRow:(row,data)=>$(row).attr('data-contact-id',data.id)
    });

    window.populateContactsTableEdit = function(contacts){
        contactsTableEdit.clear();
        contactsTableEdit.rows.add(contacts).draw();
    };

    // ===================== Export & Print Functions =====================
    window.exportTableToExcel = function(tableId, filename='Export'){
        const table = document.getElementById(tableId);
        const wb = XLSX.utils.table_to_book(table, {sheet:'Sheet1'});
        XLSX.writeFile(wb, filename+'.xlsx');
    };
    window.printProjectTable = function(){ window.print(); };

});
</script>
@endsection
