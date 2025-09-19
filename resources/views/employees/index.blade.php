@extends('layouts.app')
@section('title', __('Employees Management'))
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">


    <link rel="stylesheet" href="{{ asset('css/employees.css') }}">

  <div id="header-placeholder"></div>

    <div class="container">
        <div id="navbar-placeholder"></div>

        <main class="main-content">
            <section id="employees-section" class="section-content active">
                <div class="icon-toolbar">
                    <div>
                        <button title="Add" onclick="openEmployeeModal()" class="btn-icon"><i class="fas fa-file"></i></button>
                        <button title="Edit" onclick="handleEditEmployee()" class="btn-icon"><i class="fas fa-pen"></i></button>
                        <button title="Delete" onclick="deleteSelectedEmployees()" class="btn-icon"><i class="fas fa-trash"></i></button>
                    </div>
                    <div class="icon-separator"></div>
                    <div>
                        <button title="File Manager" onclick="goToEmployeeFiles()" class="btn-icon"><i class="fas fa-folder-open"></i> </button>
                        <button title="Employee Report" id="generateEmployeeReportBtn" onclick="generateEmployeeReport()" class="btn-icon"><i class="fa-solid fa-file-invoice"></i></button>
                        <button title="Export to Excel" id="exportEmployeesExcelBtn" class="btn-icon"><i class="fa-solid fa-table"></i></button>
                        <button title="Print" id="printEmployeesTableBtn" onclick="printEmployeesTable()" class="btn-icon"><i class="fas fa-print"></i></button>
                    </div>
                </div>

                <div class="table-responsive-container">
                    <table id="employeesTable" class="table table-bordered table-striped display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAllEmployees"></th>
                                <th>Employee Reference<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Initials<br><select class="column-filter"><option value="">All</option></select></th>
                                <th>Mid. Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Full Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>First Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Last Name<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Email<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Supervisor<br><select class="column-filter"><option value="">All</option></select></th>
                                <th>CTTA<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Business Unit<br><select class="column-filter"><option value="">All</option></select></th>
                                <th>Department<br><select class="column-filter"><option value="">All</option></select></th>
                                <th>Title<br><input type="text" placeholder="Search..." class="column-filter"></th>
                                <th>Job Rules<br>
                                    <select class="column-filter">
                                        <option value="">All</option>
                                        <option value="Office Staff">Office Staff</option>
                                        <option value="Laboratory Staff">Laboratory Staff</option>
                                        <option value="Site Staff">Site Staff</option>
                                        <option value="Drillers Staff">Drillers Staff</option>
                                        <option value="Drivers Staff">Drivers Staff</option>
                                    </select>
                                </th>
                                <th class="d-none">Contacts Data</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </section>

            <div id="employeeModal" class="modal">
                <div class="modal-content new-employee-modal-design">
                    <span class="close-btn" onclick="closeEmployeeModal()"><i class="fas fa-times"></i></span>
                    <h2 class="modal-title">Add new employee</h2>
<input type="hidden" id="editingEmployeeId">

                    <div class="tab-buttons">
                        <button id="employee-btn" onclick="switchTab('employee')" class="active"><i class="fas fa-user"></i> Employee</button>
                        <button id="contact-btn" onclick="switchTab('contact')"><i class="fas fa-address-book"></i> Contacts</button>
                    </div>

                    <form id="employeeForm">
                        <div id="employeeTab" class="form-tab-content active">
                            <fieldset class="form-section-fieldset">
                                <legend>Employee Information</legend>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="employeeReference">Employee Reference:</label>
                                        <input type="text" id="employeeReference" readonly value="(Generated ID)">
                                    </div>
                                    <div class="form-group">
                                        <label for="initials">Initials:</label>
                                        <select id="initials"></select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="firstName">First Name:</label>
                                        <input type="text" id="firstName">
                                    </div>
                                    <div class="form-group">
                                        <label for="midName">Mid. Name:</label>
                                        <input type="text" id="midName">
                                    </div>
                                    <div class="form-group">
                                        <label for="lastName">Last Name:</label>
                                        <input type="text" id="lastName">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="fullName">Full Name:</label>
                                        <input type="text" id="fullName">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" id="email">
                                    </div>
                                    <div class="form-group">
                                        <label for="title">Title:</label>
                                        <input type="text" id="title">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="supervisor">Supervisor:</label>
                                        <select id="supervisor">
                                            <option value="" selected disabled>[EditValue is null]</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="ctta">CTTA:</label>
                                        <input type="text" id="ctta">
                                    </div>
                                    <div class="form-group">
                                        <label for="businessUnit">Business Unit:</label>
                                        <select id="businessUnit">
                                            <option value="AAM-EHSA" selected>AAM-EHSA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="department">Department:</label>
                                        <select id="department">
                                            <option value="" selected disabled>[EditValue is null]</option>
                                        </select>
                                    </div>
                                    <div class="form-group-checkbox">
                                        <label>Job Rules</label>
                                        <div class="job-rules-container">
                                            <input type="checkbox" id="officeStaff">
                                            <label for="officeStaff">Office Staff</label>
                                            <input type="checkbox" id="laboratoryStaff">
                                            <label for="laboratoryStaff">Laboratory Staff</label>
                                            <input type="checkbox" id="siteStaff">
                                            <label for="siteStaff">Site Staff</label>
                                            <input type="checkbox" id="drillersStaff">
                                            <label for="drillersStaff">Drillers Staff</label>
                                            <input type="checkbox" id="driversStaff">
                                            <label for="driversStaff">Drivers Staff</label>
                                        </div>
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
    <button type="button" class="btn-primary" onclick="closeEmployeeModal()">
        <i class="fas fa-times"></i> Close
    </button>
    <button type="button" class="btn-secondary" id="actionsBtn">
        <i class="fas fa-cogs"></i> Actions
    </button>
    <button type="button" class="btn-success" onclick="saveEmployeeData(event, $('#editingEmployeeId').val(), true)">
        <i class="fas fa-save"></i> Save & Close
    </button>
    <button type="button" id="saveEmployeeBtn" onclick="saveEmployeeData(event, $('#editingEmployeeId').val(), false)">
        Save
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

     <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
      <script src="{{ asset('js/employees.js') }}"></script>

    <script>
        $(function(){
            let headerLoaded = false;
            let navbarLoaded = false;

            $("#header-placeholder").load("/html/General/header.html", function() {
                headerLoaded = true;
                console.log("Header loaded.");
                initializeAfterContentLoad();
            });

            $("#navbar-placeholder").load("/html/General/navbar.html", function() {
                navbarLoaded = true;
                console.log("Navbar loaded.");
                initializeAfterContentLoad();
            });

            function initializeAfterContentLoad() {
                if (headerLoaded && navbarLoaded) {
                    console.log("Header and Navbar loaded. Initializing page elements.");

                    if (localStorage.getItem('darkMode') === 'enabled') {
                        document.body.classList.add('dark-mode');
                    } else {
                        document.body.classList.remove('dark-mode');
                    }

                    const savedLang = localStorage.getItem('language');
                    if (savedLang) {
                        setLanguage(savedLang);
                    } else {
                        setLanguage('en');
                    }

                    const currentLang = localStorage.getItem('language') || 'en';
                    document.querySelectorAll('.main-header button').forEach(button => {
                        if (button.onclick && button.onclick.toString().includes(`setLanguage('${currentLang}')`)) {
                            button.classList.add('active');
                        } else {
                            button.classList.remove('active');
                        }
                    });

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

                    if (typeof updatePlaceholderAndTitleAttributes === 'function') {
                        updatePlaceholderAndTitleAttributes();
                    }

                    if (typeof initializeEmployeesTable === 'function') {
                        console.log("Calling initializeEmployeesTable()");
                        initializeEmployeesTable();
                    } else {
                        console.error("initializeEmployeesTable function is not defined.");
                    }
                }
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const employeeForm = document.getElementById('employeeForm');
            if (employeeForm) {
                employeeForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    saveEmployeeData(event, true);
                });
            }

            document.getElementById('exportEmployeesExcelBtn')?.addEventListener('click', exportEmployeesTableToExcel);
            document.getElementById('printEmployeesTableBtn')?.addEventListener('click', printEmployeesTable);

            document.getElementById('exportContactsModalExcelBtn')?.addEventListener('click', () => exportTableToExcel('contactsTable', 'Employee_Contacts'));
            document.getElementById('printContactsModalTableBtn')?.addEventListener('click', () => printTable('contactsTable', 'Employee_Contacts'));

            document.getElementById('employeeModal')?.addEventListener('click', (e) => {
                if (e.target === document.getElementById('employeeModal')) {
                    closeEmployeeModal();
                }
            });

            document.getElementById('employee-btn')?.addEventListener('click', () => switchTab('employee'));
            document.getElementById('contact-btn')?.addEventListener('click', () => switchTab('contact'));

            document.getElementById('selectAllEmployees')?.addEventListener('change', (event) => toggleSelectAll(event.target));
        });

        function exportEmployeesTableToExcel() {
            exportTableToExcel('employeesTable', 'Employees_Data');
        }

        function printEmployeesTable() {
            printTable('employeesTable', 'Employees');
        }

</script>

@endsection
