@extends('layouts.app')

@section('title', __('Customers Management'))

@section('content')
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
                        <button title="File Manager" onclick="goToProjectFiles()" class="btn-icon"><i class="fas fa-folder-open"></i> </button>
                        <button title="Export to Excel" id="exportProjectsExcelBtn" class="btn-icon"><i class="fa-solid fa-table"></i></button>
                        <button title="Print" id="printProjectsTableBtn" onclick="printProjectsTable()" class="btn-icon"><i class="fas fa-print"></i></button>
                    </div>
                </div>

                <div class="table-responsive-container">
                    <table id="projectsTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllProjects"></th>
                                <th>Project Reference<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Project Number<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Project Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Customer<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Opportunity<br>
                                    <select class="column-filter">
                                        <option value="">All</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </th>
                                <th>Date Registered<br>
                                    <input type="date" class="column-filter date-range-filter" placeholder="From">
                                    <input type="date" class="column-filter date-range-filter" placeholder="To">
                                </th>
                                <th>Region<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Department<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Date Started<br>
                                    <input type="date" class="column-filter date-range-filter" placeholder="From">
                                    <input type="date" class="column-filter date-range-filter" placeholder="To">
                                </th>
                                <th>Date Completed<br>
                                    <input type="date" class="column-filter date-range-filter" placeholder="From">
                                    <input type="date" class="column-filter date-range-filter" placeholder="To">
                                </th>
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
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="projectReference">Project Reference:</label>
                                        <input type="text" id="projectReference" readonly value="(Generated ID)">
                                    </div>
                                    <div class="form-group">
                                        <label for="projectNumber">Project Number:</label>
                                        <input type="text" id="projectNumber">
                                    </div>
                                     <div class="form-group-checkbox">
                                        <input type="checkbox" id="opportunity">
                                        <label for="opportunity">Opportunity</label>
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
                                    <div class="form-group">
                                        <label for="projectDescription">Project Description:</label>
                                        <input type="text" id="projectDescription">
                                    </div>
                                </div>
                            </fieldset>

                             <fieldset class="form-section-fieldset">
                                <legend>Project Location</legend>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="locationLatitude">Location Latitude:</label>
                                        <input type="text" id="locationLatitude">
                                    </div>
                                    <div class="form-group">
                                        <label for="longitude">Longitude:</label>
                                        <input type="text" id="longitude">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="projectArabicLocation">Arabic Location:</label>
                                        <input type="text" id="projectArabicLocation">
                                    </div>
                                     <div class="form-group">
                                        <label for="region">Region:</label>
                                        <select id="region">
                                            <option value="Others" selected>Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                     <div class="form-group">
                                        <label for="department">Department:</label>
                                        <select id="department">
                                            <option value="Materials Testing" selected>Materials Testing</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="driveDistance">Drive Distance (m):</label>
                                        <input type="text" id="driveDistance">
                                    </div>
                                </div>
                                <div class="form-row">
                                     <div class="form-group">
                                        <label for="driveDuration">Drive Duration:</label>
                                        <input type="text" id="driveDuration">
                                    </div>
                                    <div class="form-group">
                                       <button type="button" class="btn-secondary"><i class="fas fa-calculator"></i> Calculate Distance & Time</button>
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-section-fieldset">
                                <legend>Parties Section</legend>
                                 <div class="form-row">
                                    <div class="form-group">
                                        <label for="customer">Customer:</label>
                                        <select id="customer">
                                            <option value="" selected disabled>[EditValue is null]</option>
                                            <option value="AAMC-1048">AAMC-1048</option>
                                            <option value="AAMC-1047">AAMC-1047</option>
                                            <option value="AAMC-1046">AAMC-1046</option>
                                            <option value="AAMC-1045">AAMC-1045</option>
                                        </select>
                                        <span style="margin-left: 8px;">Cash</span>
                                    </div>
                                </div>
                                <div id="cashCustomerDetails">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="cashCustomer">Cash Customer:</label>
                                            <input type="text" id="cashCustomer">
                                        </div>
                                        <div class="form-group">
                                            <label for="customerTRN">Customer TRN:</label>
                                            <input type="text" id="customerTRN">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                         <div class="form-group" style="flex-basis: 100%;">
                                            <label for="customerAddress">Address:</label>
                                            <textarea id="customerAddress" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                                 <div class="form-row">
                                    <div class="form-group"><label for="owner">Owner:</label><select id="owner"><option value="" selected disabled>[EditValue is null]</option></select></div>
                                    <div class="form-group"><label for="consultant">Consultant:</label><select id="consultant"><option value="" selected disabled>[EditValue is null]</option></select></div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group"><label for="contractor">Contractor:</label><select id="contractor"><option value="" selected disabled>[EditValue is null]</option></select></div>
                                    <div class="form-group"><label for="pClient">P. Client:</label><select id="pClient"><option value="" selected disabled>[EditValue is null]</option></select></div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group"><label for="pmCommittee">PM Committee:</label><select id="pmCommittee"><option value="" selected disabled>[EditValue is null]</option></select></div>
                                </div>
                            </fieldset>

                            <fieldset class="form-section-fieldset">
                                <legend>Discount & Project Timelines</legend>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="discount">Discount:</label>
                                        <input type="number" id="discount" value="0">
                                    </div>
                                    <div class="form-group">
                                        <label for="dateStarted">Date Started:</label>
                                        <input type="date" id="dateStarted">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="dateCompleted">Date Completed:</label>
                                        <input type="date" id="dateCompleted">
                                    </div>
                                    <div class="form-group">
                                        <label for="intProject">Int. Project:</label>
                                        <input type="text" id="intProject" placeholder="[EditValue is null]">
                                    </div>
                                </div>
                                <div class="form-row">
                                     <div class="form-group">
                                        <label for="intPManager">Int. P. Manager:</label>
                                        <input type="text" id="intPManager" placeholder="[EditValue is null]">
                                    </div>
                                    <div class="form-group">
                                        <label for="intAnAccount">Int. An. Account:</label>
                                        <input type="text" id="intAnAccount" placeholder="[EditValue is null]">
                                    </div>
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
                                                <th><input type="checkbox" id="selectAllContacts" onclick="toggleAllContacts(this)"></th>
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
                                    <button type="button" class="btn-primary" onclick="addContactToTable()"><i class="fas fa-save"></i> Save Contact</button>
                                    <button type="button" class="btn-secondary" onclick="clearContactForm()"><i class="fas fa-eraser"></i> Clear Form</button>
                                </div>
                            </fieldset>
                        </div>

                        <div class="form-buttons modal-bottom-buttons">
                            <button type="button" class="btn-primary" onclick="closeProjectModal()"><i class="fas fa-times"></i> Close</button>
                            <button type="button" class="btn-secondary" id="actionsBtn"><i class="fas fa-cogs"></i> Actions</button>
                            <button type="submit" class="btn-success"><i class="fas fa-save"></i> Save & Close</button>
                            <button type="button" id="saveProjectBtn" onclick="saveProjectData(event)">Save</button>
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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>


   <script src="main.js" type="text/javascript"></script>


    <script src="projects.js" type="text/javascript"></script>

    <script>
     /**
        // هذا السكريبت سيضمن تهيئة DataTables ووظائف الصفحة بعد تحميل كل المحتوى الديناميكي
        $(function(){

            let headerLoaded = false;

            let navbarLoaded = false;

            // تحميل header.html
            $("#header-placeholder").load("header.html", function() {
                headerLoaded = true;
                console.log("Header loaded.");
                initializeAfterContentLoad();
            });

            // تحميل navbar.html
            $("#navbar-placeholder").load("navbar.html", function() {
                navbarLoaded = true;
                console.log("Navbar loaded.");
                initializeAfterContentLoad();
            });

            // هذه الدالة تُستدعى بعد تحميل الـ header والـ navbar
            function initializeAfterContentLoad() {
                if (headerLoaded && navbarLoaded) {
                    console.log("Header and Navbar loaded. Initializing page elements.");

                    // تطبيق وضع Dark Mode
                    if (localStorage.getItem('darkMode') === 'enabled') {
                        document.body.classList.add('dark-mode');
                    } else {
                        document.body.classList.remove('dark-mode');
                    }

                    // تطبيق اللغة المحفوظة
                    const savedLang = localStorage.getItem('language');
                    if (savedLang) {
                        setLanguage(savedLang);
                    } else {
                        setLanguage('en'); // اللغة الافتراضية
                    }

                    // تحديث حالة الأزرار بناءً على اللغة الحالية
                    const currentLang = localStorage.getItem('language') || 'en';
                    document.querySelectorAll('.main-header button').forEach(button => {
                        if (button.onclick && button.onclick.toString().includes(`setLanguage('${currentLang}')`)) {
                            button.classList.add('active');
                        } else {
                            button.classList.remove('active');
                        }
                    });

                    // تحديث حالة أزرار القائمة الجانبية
                    const currentPageFileName = window.location.pathname.split('/').pop();
                    document.querySelectorAll('.main-menu button').forEach(button => {
                        if (button.onclick) {
                            const targetPageMatch = button.onclick.toString().match(/window\.location\.href = '(.+?)'/);
                            if (targetPageMatch) {
                                const targetFileName = targetPageMatch[1].split('/').pop();
                                if (currentPageFileName === targetFileName) {
                                    button.classList.add('active');
                                } else {
                                    button.classList.remove('active');
                                }
                            }
                        }
                    });

                    // تحديث Placeholder/Title attributes
                    if (typeof updatePlaceholderAndTitleAttributes === 'function') {
                        updatePlaceholderAndTitleAttributes();
                    }

                    // الأهم: تهيئة DataTables هنا بعد التأكد من تحميل كل شيء
                    if (typeof initializeProjectsTable === 'function') {
                        console.log("Calling initializeProjectsTable()");
                        initializeProjectsTable();
                    } else {
                        console.error("initializeProjectsTable function is not defined.");
                    }

                    // NEW: تهيئة جدول جهات الاتصال داخل المودال
                    // يجب استدعاء هذه الدالة فقط عند فتح تبويب جهات الاتصال في المودال
                    // أو عند فتح المودال لأول مرة
                }
            }
        });

        // ربط الأحداث بالأزرار والنماذج باستخدام DOMContentLoaded لضمان وجود العناصر
        document.addEventListener('DOMContentLoaded', () => {


            const projectForm = document.getElementById('projectForm');
                if (projectForm) {
                    projectForm.addEventListener('submit', function(event) {
                        event.preventDefault(); // هذا السطر يمنع إعادة تحميل الصفحة
                        saveProjectData(event, true);    // يستدعي دالة حفظ بيانات المشروع
                    });
                }


            // أزرار جدول المشاريع
            document.getElementById('exportProjectsExcelBtn')?.addEventListener('click', exportProjectsTableToExcel);
            document.getElementById('printProjectsTableBtn')?.addEventListener('click', printProjectsTable);

            // أزرار جدول جهات الاتصال داخل المودال
            document.getElementById('exportContactsModalExcelBtn')?.addEventListener('click', () => exportTableToExcel('contactsTable', 'Project_Contacts'));
            document.getElementById('printContactsModalTableBtn')?.addEventListener('click', () => printTable('contactsTable', 'Project_Contacts'));


            document.getElementById('projectModal')?.addEventListener('click', (e) => {
                if (e.target === document.getElementById('projectModal')) {
                    closeProjectModal();
                }
            });

            document.getElementById('project-btn')?.addEventListener('click', () => switchTab('project'));
            document.getElementById('contact-btn')?.addEventListener('click', () => switchTab('contact')); // لا يزال الاسم 'contact' مناسباً

            document.getElementById('selectAllProjects')?.addEventListener('change', (event) => toggleSelectAll(event.target));
        });


        // هذه الدوال تحتاج إلى تعريفها في ملف projects.js أو في هذا السكريبت إذا لم تكن موجودة
        function exportProjectsTableToExcel() {
            exportTableToExcel('projectsTable', 'Projects_Data');
        }

        function printProjectsTable() {
            printTable('projectsTable', 'Projects');
        }
        **/
        // ربط الأحداث بالأزرار والنماذج
    document.addEventListener('DOMContentLoaded', () => {
        // تهيئة DataTables بمجرد أن يكون DOM جاهزًا
        if (typeof initializeProjectsTable === 'function') {
            console.log("DOM content loaded. Initializing projects table.");
            initializeProjectsTable();
        } else {
            console.error("initializeProjectsTable function is not defined. Please check projects.js file.");
        }

        // ... (بقية الكود الخاص بك الذي يربط الأزرار بالأحداث) ...
        const projectForm = document.getElementById('projectForm');
        document.getElementById('exportProjectsExcelBtn')?.addEventListener('click', exportProjectsTableToExcel);
        document.getElementById('printProjectsTableBtn')?.addEventListener('click', printProjectsTable);
        document.getElementById('exportContactsModalExcelBtn')?.addEventListener('click', () => exportTableToExcel('contactsTable', 'project_Contacts'));
        document.getElementById('printContactsModalTableBtn')?.addEventListener('click', () => printTable('contactsTable', 'project_Contacts'));
        document.getElementById('projectModal')?.addEventListener('click', (e) => {
            if (e.target === document.getElementById('projectModal')) {
                closeProjectModal();
            }
        });
        document.getElementById('project-btn')?.addEventListener('click', () => switchTab('project'));
        document.getElementById('contact-btn')?.addEventListener('click', () => switchTab('contact'));
        document.getElementById('selectAllProjects')?.addEventListener('change', (event) => toggleSelectAll(event.target));
    });

    // الدوال المساعدة (يمكنك نقلها إلى customers.js لتنظيم أفضل)
    function exportProjectsTableToExcel() {
        exportTableToExcel('projectsTable', 'Projects_Data');
    }

    function printProjectsTable() {
        printTable('projectsTable', 'Projects');
    }
    </script>



@endsection
