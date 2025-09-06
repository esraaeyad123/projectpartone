@extends('layouts.app')

@section('title', __('Projects'))

@section('content')
<!-- مكتبات خارجية -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


<!-- ملفاتك المحلية -->
<link rel="stylesheet" href="{{ asset('css/main.css') }}">
<link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">
<link rel="stylesheet" href="{{ asset('css/projects.css') }}">

        <main class="main-content">
            <section id="projects-section" class="section-content active">
                <div class="icon-toolbar">
                    <div>
                        <button title="Add" onclick="openProjectModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                        <button title="Edit" onclick="handleEditProject()" class="btn-icon"><i class="fas fa-pen"></i></button>
                        <button title="Delete" onclick="deleteSelectedProjects()" class="btn-icon"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="icon-separator"></div>
                    <div>
<button title="File Manager"
        onclick="goToProjectFiles()"
        class="btn-icon">
    <i class="fas fa-folder-open"></i>
</button>

                       <!-- زر التصدير -->
<button onclick="exportTableToExcel('projectsTable', 'Projects')" class="btn-icon">
    <i class="fa-solid fa-file-excel"></i>
</button>

<button title="Print" id="printProjectsTableBtn" onclick="printProjectTable()" class="btn-icon">
    <i class="fas fa-print"></i>
</button>


                    </div>
                </div>

                <div class="table-responsive-container">
                    <table id="projectsTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                            <th><input type="checkbox" id="selectAllProjects"></th>

                                <th>Project Reference<br><input type="text" placeholder="Search..." class="column-filter"></th>

                             <th>Project Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                                             <th>Arbic Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
  <th>Date Registered<br>
                                    <input type="date" class="column-filter date-range-filter" placeholder="From">
                                    <input type="date" class="column-filter date-range-filter" placeholder="To">
                                </th>
                                                                <th>Region<br><input type="text" placeholder="Search..." class="column-filter"></th>

                                <th>Customer<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>owner<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>consultant<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>contractor<br><input type="text" placeholder="Search..." class="column-filter"></th>

                                                                <th>action<br><input type="text" placeholder="Search..." class="column-filter"></th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </section>

            <div id="projectModal" class="modal">
                <div class="modal-content new-project-modal-design">
                    <span class="close-btn" onclick="closeProjectModal()"><i class="fas fa-times"></i></span>
                    <h2 class="modal-title">Add new project</h2>

                    <div class="tab-buttons">
                        <button id="project-btn" onclick="switchTab('project')" class="active"><i class="fas fa-user"></i> Project</button>
                        <button id="contact-btn" onclick="switchTab('contact')"><i class="fas fa-address-book"></i> Contacts</button>
                    </div>

                    <form id="projectForm">
                        <div id="projectTab" class="form-tab-content active">
                            <fieldset class="form-section-fieldset">
                                <legend>Project Information</legend>
                                <input type="hidden" id="projectId">

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="projectReference">Project Reference:</label>
                                        <input type="text" id="projectReference" readonly value="(Generated ID)">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="projectName">Project Name:</label>
                                        <input type="text" id="projectName" name="projectName">
                                    </div>
                                    <div class="form-group">
                                        <label for="projectArabicName">Arabic Name:</label>
                                        <input type="text" id="projectArabicName">
                                    </div>
                                </div>
                                <div class="form-row">
                                     <div class="form-group">
                                        <label for="registrationDate">Date Registered:</label>
                                        <input type="date" id="registrationDate">
                                    </div>

                                </div>
                            </fieldset>

                             <fieldset class="form-section-fieldset">
                                <legend>Project Location</legend>
                                <div class="form-row">


                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="projectArabicLocation">Location:</label>
                                        <input type="text" id="projectArabicLocation">
                                    </div>

                                </div>


                            </fieldset>
<fieldset class="form-section-fieldset">
    <legend>Parties Section</legend>

    <div class="form-row">
        <div class="form-group">
            <label for="customer">Customer:</label>
            <select id="customer" name="customer_id">
                <option value="" disabled selected>[Select Customer]</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->customer_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="owner">Owner:</label>
            <select id="owner" name="owner">
                <option value="" selected disabled>[EditValue is null]</option>
            </select>
        </div>
        <div class="form-group">
            <label for="consultant">Consultant:</label>
            <select id="consultant" name="consultant">
                <option value="" selected disabled>[EditValue is null]</option>
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group">
            <label for="contractor">Contractor:</label>
            <select id="contractor" name="contractor">
                <option value="" selected disabled>[EditValue is null]</option>
            </select>
        </div>
    </div>
</fieldset>


                                 <div class="form-row">
                                    <div class="form-group"><label for="owner">Owner:</label><select id="owner"><option value="" selected disabled>[EditValue is null]</option></select></div>
                                    <div class="form-group"><label for="consultant">Consultant:</label><select id="consultant"><option value="" selected disabled>[EditValue is null]</option></select></div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group"><label for="contractor">Contractor:</label><select id="contractor"><option value="" selected disabled>[EditValue is null]</option></select></div>
                                </div>

                            </fieldset>

                        </div>

                        <div id="contactTab" class="form-tab-content" style="display: none;">
                            <fieldset class="form-section-fieldset">
                                <legend>Contact List</legend>
                                <div class="contact-toolbar" style="border-bottom: none; padding-bottom: 5px;">
                                    <button type="button" class="btn-secondary" onclick="populateContactFormForEdit()"><i class="fas fa-pen"></i> Edit Selected</button>
                                    <button type="button" class="btn-danger" onclick="deleteSelectedContacts()"><i class="fas fa-trash"></i> Delete Selected</button>
                                    <button type="button" class="btn-icon" id="exportContactsModalExcelBtn" title="Export to Excel"><i class="fa-solid fa-table"></i></button>
                                    <button type="button" class="btn-icon" id="printContactsModalTableBtn" title="Print"><i class="fas fa-print"></i></button>
                                </div>
                                <div class="table-responsive-container">
                                    <table id="contactsTable" class="contacts-table display responsive nowrap" data-ignore-lang>
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="selectAllContacts"  onclick="toggleAllContacts(this)"></th>
                                                <th class="d-none">Contact ID</th>
                                                <th>Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                                <th>Email<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                                <th>Phone<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                                <th>Mobile<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                                <th>Position<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                                <th>Is Primary<br>
                                                    <select class="column-filter">
                                                        <option value="">All</option>
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>

                            <fieldset class="form-section-fieldset">
                                <legend>Add/Edit Contact Person</legend>
                                <div class="form-row">
                                    <div class="form-group">
                                        <input type="hidden" id="editingContactId">
                                        <label for="contactName">Contact Name:</label>
                                        <input type="text" id="contactName" placeholder="Enter contact name">
                                        <input type="hidden" id="editingContactId">
                                    </div>
                                    <div class="form-group">
                                        <label for="contactEmail">Contact Email:</label>
                                        <input type="email" id="contactEmail" placeholder="e.g., contact@example.com">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="contactPhone">Contact Phone:</label>
                                        <input type="tel" id="contactPhone" placeholder="e.g., +9665XXXXXXXX">
                                    </div>
                                    <div class="form-group">
                                        <label for="contactMobile">Contact Mobile:</label>
                                        <input type="tel" id="contactMobile" placeholder="e.g., +9665XXXXXXXX">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="contactPosition">Position:</label>
                                        <input type="text" id="contactPosition" placeholder="e.g., Sales Manager">
                                    </div>
                                    <div class="form-group-checkbox">
                                        <input type="checkbox" id="isPrimaryContact">
                                        <label for="isPrimaryContact">Primary Contact</label>
                                    </div>
                                </div>
                                <div class="contact-toolbar" style="justify-content: flex-start; border-bottom: none; padding-bottom: 0;">
<button type="button" class="btn-primary" onclick="saveContact()">
    <i class="fas fa-save"></i> Save Contact
</button>                                    <button type="button" class="btn-secondary" onclick="clearContactForm()"><i class="fas fa-eraser"></i> Clear Form</button>
                                </div>
                            </fieldset>
                        </div>

                        <div class="form-buttons modal-bottom-buttons">
                            <button type="button" class="btn-primary" onclick="closeProjectModal()"><i class="fas fa-times"></i> Close</button>
                            <button type="button" class="btn-secondary" id="actionsBtn"><i class="fas fa-cogs"></i> Actions</button>
                           <button type="button" class="btn-success" onclick="saveProject(true)">
    <i class="fas fa-save"></i> Save & Close
</button>

<button type="button" class="btn-primary" onclick="saveProject(false)">
    <i class="fas fa-save"></i> Save
</button>

                        </div>
                    </form>
                </div>
            </div>
            <div id="modal-container"></div>
            <div id="dynamicContent"></div>
            <div id="customDialogModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeCustomDialog()">&times;</span>
                    <h3 id="customDialogTitle"></h3>
                    <p id="customDialogMessage"></p>
                    <div class="form-buttons" id="customDialogButtons">
                    </div>
                </div>
            </div>
        </main>
    </div>







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


// ================== تحميل المشاريع ==================




function loadProjects() {
    $.get('/projects', function(projects) {

        let tbody = $('#projectsTable tbody');
        tbody.empty();

        projects.forEach(p => {
            tbody.append(`
                <tr>
                    <td><input type="checkbox" class="project-checkbox" value="${p.id}"></td>
                    <td>${p.reference || ''}</td>
                    <td>${p.name || ''}</td>
                                        <td>${p.arabic_name || ''}</td>

                                       <td>${p.registration_date || ''}</td>
                                                          <td>${p.region || ''}</td>
<td>${p.customer ? p.customer.customer_name : ''}</td>



                    <td>${p.owner || ''}</td>
                    <td>${p.consultant || ''}</td>

                    <td>${p.contractor || ''}</td>
                    <td>
                        <button class="btn-icon" onclick="editProject(${p.id})"><i class="fas fa-pen"></i></button>
                        <button class="btn-icon" onclick="deleteProject(${p.id})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `);
        });


        // 🟢 Checkbox تحديد الكل
        $('#selectAllProjects').off('change').on('change', function() {
            $('.project-checkbox').prop('checked', $(this).is(':checked'));
        });
    });
}


// إرجاع IDs المشاريع المحددة
function getSelectedProjectIds() {
    let ids = [];
    $('.project-checkbox:checked').each(function() {
        ids.push($(this).val());
    });
    return ids;
}

// ================== حذف مشروع مفرد ==================
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


// ================== حذف مشاريع متعددة ==================
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


// ================== المودال (فتح/إغلاق) ==================
function openProjectModal() {
    $('#projectForm')[0].reset();
    $('#projectId').val('');
    $('#projectModal').show();
}

function closeProjectModal() {
    $('#projectModal').hide();
}

// ================== تحميل بيانات مشروع للتعديل ==================
function editProject(id) {
    $.get(`/projects/${id}`, function(project) {
        if (!project || !project.id) {
            alert("❌ لم يتم العثور على بيانات المشروع!");
            return;
        }

        // تعبئة الفورم
        $('#projectId').val(project.id);
        $('#projectName').val(project.name);
        $('#projectArabicName').val(project.arabic_name || '');
        $('#registrationDate').val(project.registration_date || '');
        $('#customer').val(project.customer_id || '');
        $('#owner').val(project.owner || '');
        $('#consultant').val(project.consultant || '');
        $('#contractor').val(project.contractor || '');
        $('#projectArabicLocation').val(project.projectArabicLocation || '');

        // فتح المودال
        $('#projectModal').show();

        // تحميل جهات الاتصال الخاصة بالمشروع
        loadContactsTable(project.id);
    }).fail(function(err) {
        console.error("Error fetching project:", err);
        alert("❌ حدث خطأ أثناء جلب بيانات المشروع.");
    });
}

// ================== حفظ مشروع (جديد أو تعديل) ==================
function saveProject(closeAfterSave) {
    let projectId = $('#projectId').val();
    let url = projectId ? `/projects/${projectId}` : '/projects';
    let method = projectId ? 'PUT' : 'POST';

    let formData = {
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


// ================== تحميل جهات الاتصال للمشروع ==================
function loadContactsTable(projectId) {
$.get(`/projects/${projectId}/contacts`, function(contacts) {
        let tbody = $('#contactsTable tbody');
        tbody.empty();

        if (contacts.length === 0) {
            tbody.append('<tr><td colspan="8">No contacts found</td></tr>');
            return;
        }

        contacts.forEach(c => {
            tbody.append(`
                <tr >
                    <td><input type="checkbox" class="contact-checkbox" value="${c.id}"></td>
                    <td>${c.name}</td>
                    <td>${c.email}</td>
                    <td>${c.phone}</td>
                    <td>${c.mobile}</td>
                    <td>${c.position}</td>
                    <td>${c.is_primary ? 'Yes' : 'No'}</td>
                </tr>
            `);
        });
    });
}

// ================== حفظ جهة اتصال ==================
function saveContact() {
    let projectId = $('#projectId').val();
    if (!projectId) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه ⚠️',
            text: 'يرجى حفظ المشروع أولاً قبل حفظ جهة الاتصال!',
            confirmButtonText: 'حسناً'
        });
        return;
    }

    // التحقق إذا كانت العملية تعديل أو إضافة جديدة
    let contactId = $('#editingContactId').val();
    let url = contactId ? `/project-contacts/${contactId}` : '/project-contacts';
    let method = contactId ? 'PUT' : 'POST';

    let contactData = {
        project_id: projectId,
        name: $('#contactName').val(),
        email: $('#contactEmail').val(),
        phone: $('#contactPhone').val(),
        mobile: $('#contactMobile').val(),
        position: $('#contactPosition').val(),
        is_primary: $('#isPrimaryContact').is(':checked') ? 1 : 0,
        _token: $('meta[name="csrf-token"]').attr('content')
    };

    $.ajax({
        url: url,
        method: method,
        data: contactData,
        success: function(response) {
            Swal.fire({
                icon: 'success',
                title: contactId ? 'تم التعديل ✅' : 'تم الحفظ ✅',
                text: contactId ? 'تم تعديل جهة الاتصال بنجاح!' : 'تم حفظ جهة الاتصال بنجاح!',
                timer: 2000,
                showConfirmButton: false
            });

            // تحديث جدول جهات الاتصال
            loadContactsTable(projectId);

            // تفريغ الفورم بعد الحفظ أو التعديل
            clearContactForm();
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'خطأ ❌',
                text: contactId ? 'فشل في تعديل جهة الاتصال!' : 'فشل في حفظ جهة الاتصال!',
                confirmButtonText: 'حسناً'
            });
        }
    });
}



// ================== تعديل جهة اتصال ==================
function editContact(id, name, email, phone, mobile, position, isPrimary) {
    $('#editingContactId').val(id);
    $('#contactName').val(name);
    $('#contactEmail').val(email);
    $('#contactPhone').val(phone);
    $('#contactMobile').val(mobile);
    $('#contactPosition').val(position);
    $('#isPrimaryContact').prop('checked', isPrimary);
}



// ================== Helpers ==================
function clearContactForm() {
    $('#contactName').val('');
    $('#contactEmail').val('');
    $('#contactPhone').val('');
    $('#contactMobile').val('');
    $('#contactPosition').val('');
    $('#isPrimaryContact').prop('checked', false);
    $('#editingContactId').val('');
}

// ================== تبويبات المودال ==================
function switchTab(tabName) {
    $('.form-tab-content').hide();
    $('.tab-buttons button').removeClass('active');

    if (tabName === 'project') {
        $('#projectTab').show();
        $('#project-btn').addClass('active');
    } else if (tabName === 'contact') {
        $('#contactTab').show();
        $('#contact-btn').addClass('active');
    }
}

// ================== عند تحميل الصفحة ==================
$(document).ready(function() {
    loadProjects();

    // تهيئة الجدول الرئيسي
    $('#projectsTable').DataTable({
        responsive: true,
        autoWidth: false
    });

    // تهيئة جدول جهات الاتصال
    $('#contactsTable').DataTable({
        responsive: true,
        autoWidth: false
    });
});

function populateContactFormForEdit() {
const selectedCheckboxes = document.querySelectorAll('#contactsTable .contact-checkbox:checked');

    if (selectedCheckboxes.length !== 1) {
        showAlert("الرجاء تحديد جهة اتصال واحدة فقط للتعديل.", "warning");
        return;
    }

    // 🔹 خذ الـ contactId من قيمة الـ checkbox
    const contactId = selectedCheckboxes[0].value;
    console.log('Editing contact with ID:', contactId);

    // 🔹 جلب بيانات جهة الاتصال من Laravel API
    $.ajax({
        url: `/project-contacts/${contactId}`, // Laravel route
        type: "GET",
        success: function(contact) {
            if (!contact || !contact.id) {
                showAlert("❌ لم يتم العثور على بيانات جهة الاتصال.", "error");
                return;
            }

            // ✅ تعبئة الحقول بالفورم
            $('#editingContactId').val(contact.id);
            $('#contactName').val(contact.name || '');
            $('#contactEmail').val(contact.email || '');
            $('#contactPhone').val(contact.phone || '');
            $('#contactMobile').val(contact.mobile || '');
            $('#contactPosition').val(contact.position || '');
            $('#isPrimaryContact').prop('checked', contact.is_primary == 1);
        },
        error: function(xhr) {
            console.error("Error fetching contact:", xhr.responseText);
            showAlert("❌ حدث خطأ أثناء جلب بيانات جهة الاتصال.", "error");
        }
    });
}


// حذف جهات الاتصال المحددة من قاعدة البيانات



function deleteSelectedContacts() {
    let ids = [];

    // اجمع كل IDs من الشيكبوكسات المحددة
    $('#contactsTable tbody input.contact-checkbox:checked').each(function() {
        ids.push($(this).val());
    });

    if (ids.length === 0) {
        Swal.fire({
            icon: "warning",
            title: "تنبيه",
            text: "⚠️ الرجاء تحديد جهة اتصال واحدة على الأقل للحذف."
        });
        return;
    }

    // نافذة تأكيد باستخدام SweetAlert2
    Swal.fire({
        title: "هل أنت متأكد؟",
        text: `سيتم حذف ${ids.length} جهة اتصال ولا يمكن التراجع عن هذا الإجراء.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "نعم، احذف!",
        cancelButtonText: "إلغاء"
    }).then((result) => {
        if (result.isConfirmed) {
            // طلب AJAX للحذف
            $.ajax({
                url: '/project-contacts/delete-multiple',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: ids
                },
                success: function(response) {
                    // إزالة الصفوف من الجدول بعد نجاح الحذف
                    $('#contactsTable tbody input.contact-checkbox:checked').each(function() {
                        $(this).closest('tr').remove();
                    });

                    Swal.fire({
                        icon: "success",
                        title: "تم الحذف!",
                        text: "✅ تم حذف جهات الاتصال بنجاح."
                    });
                },
                error: function(xhr) {
                    console.error("Error deleting contacts:", xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "خطأ",
                        text: "❌ حدث خطأ أثناء الحذف."
                    });
                }
            });
        }
    });
}


// ✅ تصدير إلى إكسل
function exportTableToExcel(tableId, fileName) {


    const table = document.getElementById(tableId);
    if (!table) {
        Swal.fire("خطأ", `الجدول ${tableId} غير موجود`, "error");
        return;
    }

    let dataTableInstance;
    if (tableId === 'projectsTable' && window.projectsTableDataTable) {
        dataTableInstance = window.projectsTableDataTable;
    } else if (tableId === 'contactsTable' && window.contactsDataTable) {
        dataTableInstance = window.contactsDataTable;
    } else {
        Swal.fire("خطأ", `DataTables للجدول ${tableId} غير موجود`, "error");
        return;
    }

    const ws_data = [];

    // العناوين
    const headerRow = [];
    $(table).find('thead tr:first th').each(function (index) {
        if (index === 0) return; // تجاهل أول عمود (checkbox مثلاً)
        headerRow.push($(this).text().trim());
    });
    ws_data.push(headerRow);

    // البيانات
    dataTableInstance.rows({ search: 'applied' }).every(function () {
        const rowData = this.data();
        const exportRow = [];
        for (let i = 1; i < rowData.length; i++) {
            exportRow.push(rowData[i]);
        }
        ws_data.push(exportRow);
    });

    const ws = XLSX.utils.aoa_to_sheet(ws_data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
    XLSX.writeFile(wb, `${fileName}.xlsx`);
}




// ✅ طباعة الجدول
function printProjectTable(tableId = 'projectsTable', title = '') {
    let tableInstance = tableId === 'projectsTable'
        ? window.projectsTableDataTable
        : window.contactsDataTable;



    let printContents = `
        <style>
            body { font-family: Arial, sans-serif; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            h1 { text-align: center; margin-bottom: 20px; }
        </style>
        <h1>${title || 'Table Print'}</h1>
        <table>
            <thead><tr>`;

    $(`#${tableId} thead tr:first th`).each(function(index) {
        if (index === 0) return; // تجاهل عمود الشيكبوكس
        printContents += `<th>${$(this).text().trim()}</th>`;
    });

    printContents += `</tr></thead><tbody>`;

    tableInstance.rows({ search: 'applied' }).every(function() {
        const rowData = this.data();
        printContents += `<tr>`;
        for (let i = 1; i < rowData.length; i++) {
            printContents += `<td>${rowData[i]}</td>`;
        }
        printContents += `</tr>`;
    });

    printContents += `</tbody></table>`;

    const printWindow = window.open('', '_blank');
    printWindow.document.write(printContents);
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
}



function goToProjectFiles() {
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

window.goToProjectFiles = goToProjectFiles;



</script>




@endsection
