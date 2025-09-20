@extends('layouts.app')
@section('title', __('Employees Management'))
@section('content')
@include('employees.create') <!-- المودال -->

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

        // ==================== فتح المودال ====================
function openEmployeeContactModal() {
    // تنظيف الحقول عند كل فتح
    clearContactForm('employee');
    $('#employeeContactModal').show();
}

// ==================== غلق المودال ====================
function closeEmployeeContactModal() {
    $('#employeeContactModal').hide();
}

// ==================== مساعدة لمسح الحقول ====================
function clearContactForm(type) {
    if(type === 'employee') {
        $('#editContactIdEmployee').val('');
        $('#contactNameEmployee').val('');
        $('#contactEmailEmployee').val('');
        $('#contactPhoneEmployee').val('');
        $('#contactMobileEmployee').val('');
        $('#contactPositionEmployee').val('');
        $('#isPrimaryContactEmployee').prop('checked', false);
    }
}

        // ==================== Switch Tab ====================
function switchEmployeeTab(tabName) {
    if(tabName === 'employee') {
        $('#employeeTab').show().addClass('active');
        $('#contactTab').hide().removeClass('active');

        $('#employee-btn').addClass('active');
        $('#contact-btn').removeClass('active');
    } else if(tabName === 'contact') {
        $('#contactTab').show().addClass('active');
        $('#employeeTab').hide().removeClass('active');

        $('#contact-btn').addClass('active');
        $('#employee-btn').removeClass('active');
    }
}


</script>

@endsection
