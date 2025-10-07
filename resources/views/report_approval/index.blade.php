@extends('layouts.app')

@section('title', 'LIMS - Approval')

{{-- ======= CONTENT ======= --}}

    <style>
        /* Reports Table Styles */
        .reports-table-container {
            overflow-x: auto;
        }

        .reports-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 0.9em;
        }

        .reports-table th,
        .reports-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .reports-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
            white-space: nowrap; /* Prevent wrapping in headers */
        }

        .reports-table .column-search-row td {
            padding: 5px 12px;
        }

        .reports-table .column-search-row input,
        .reports-table .column-search-row select {
            width: 100%;
            box-sizing: border-box;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            font-size: 0.8em;
            white-space: nowrap;
        }

        .status-submit {
            background-color: #3498db; /* Blue */
        }

        .status-checking {
            background-color: #f39c12; /* Orange */
        }

        .status-approved {
            background-color: #27ae60; /* Green */
        }

        .status-rejected {
            background-color: #e74c3c; /* Red */
        }

        /* Row Color Styles */
        .status-approved-row {
            background-color: #e8f5e9; /* Light Green */
        }

        .status-rejected-row {
            background-color: #ffebee; /* Light Red */
        }

        /* Icons and Links */
        .action-link {
            font-size: 1.2em;
            color: #3498db;
            text-decoration: none;
            transition: color 0.2s;
        }

        .action-link:hover {
            color: #2980b9;
        }

        /* Center content within the icon columns */
        .reports-table td:nth-child(1),
        .reports-table td:nth-child(2) {
            text-align: center;
            vertical-align: middle;
        }

    </style>
@section('content')

    <div class="container">
        <main class="main-content">
            <section id="approval-section" class="section-content active">
                <h2><i class="fa-solid fa-thumbs-up"></i> Approval</h2>

                <div class="reports-table-container">
                    <table class="reports-table">
                        <thead>
                            <tr>
                                <th>Edit</th>
                                <th>Report</th>
                                <th>Department</th>
                                <th>Action Required</th>
                                <th>Order #</th>
                                <th>Order Date</th>
                                <th>Sample #</th>
                                <th>R. Age</th>
                                <th>Report #</th>
                                <th>Pass/Fail</th>
                                <th>RFU NTR</th>
                                <th>Client Name</th>
                                <th>Project Name</th>
                                <th>Service Test Description</th>
                                <th>Method</th>
                                <th>Date Created</th>
                                <th>Created By</th>
                                <th>Date Checked</th>
                                <th>Checked By</th>
                                <th>Date Approved</th>
                                <th>Approved By</th>
                                <th>Declined?</th>
                                <th>Project Code</th>
                            </tr>
                            <tr class="column-search-row">
                                <td></td>
                                <td></td>
                                <td>
                                    <select data-column="department" id="department-filter">
                                        <option value="">All</option>
                                    </select>
                                </td>
                                <td>
                                    <select data-column="status" id="status-filter">
                                        <option value="all">All</option>
                                        <option value="submit_for_approval">Submit for Approval</option>
                                        <option value="checking_request">Checking Request</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </td>
                                <td><input type="text" data-column="orderNumber" placeholder="Search..."></td>
                                <td><input type="date" data-column="orderDate"></td>
                                <td><input type="text" data-column="sampleNumber" placeholder="Search..."></td>
                                <td><input type="text" data-column="rAge" placeholder="Search..."></td>
                                <td><input type="text" data-column="reportNumber" placeholder="Search..."></td>
                                <td>
                                    <select data-column="passFail" id="pass-fail-filter">
                                        <option value="">All</option>
                                        <option value="Pass">Pass</option>
                                        <option value="Fail">Fail</option>
                                        <option value="N/A">N/A</option>
                                    </select>
                                </td>
                                <td><input type="text" data-column="rfuNtr" placeholder="Search..."></td>
                                <td><input type="text" data-column="clientName" placeholder="Search..."></td>
                                <td><input type="text" data-column="projectName" placeholder="Search..."></td>
                                <td><input type="text" data-column="serviceTestDescription" placeholder="Search..."></td>
                                <td>
                                    <select data-column="method" id="method-filter">
                                        <option value="">All</option>
                                    </select>
                                </td>
                                <td><input type="date" data-column="createdDate"></td>
                                <td><input type="text" data-column="createdBy" placeholder="Search..."></td>
                                <td><input type="date" data-column="checkedDate"></td>
                                <td><input type="text" data-column="checkedBy" placeholder="Search..."></td>
                                <td><input type="date" data-column="approvedDate"></td>
                                <td><input type="text" data-column="approvedBy" placeholder="Search..."></td>
                                <td>
                                    <select data-column="declined" id="declined-filter">
                                        <option value="">All</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </td>
                                <td><input type="text" data-column="projectCode" placeholder="Search..."></td>
                            </tr>
                        </thead>
                        <tbody id="reports-table-body"></tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

@endsection
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
        $(document).ready(function () {
            // Load header and navbar
            $("#header-placeholder").load("/html/General/header.html", function() {
                if (localStorage.getItem('darkMode') === 'enabled') {
                    document.body.classList.add('dark-mode');
                }
            });
            $("#navbar-placeholder").load("/html/General/navbar.html");

            // Dummy data to simulate reports from a server with all new columns
            const reports = [
                {
                    orderNumber: 'ORD-001', orderDate: '2025-08-20', sampleNumber: 'S-101', rAge: '15',
                    reportNumber: 'R-2001', passFail: 'Pass', rfuNtr: 'RFU',
                    clientName: 'Client A', projectName: 'Project Alpha', serviceTestDescription: 'DNA Analysis',
                    method: 'Method A', createdDate: '2025-08-20', createdBy: 'User A',
                    checkedDate: null, checkedBy: null, approvedDate: null, approvedBy: null,
                    declined: 'No', projectCode: 'P-ALPHA-123', department: 'Genetics',
                    status: 'submit_for_approval'
                },
                {
                    orderNumber: 'ORD-002', orderDate: '2025-08-21', sampleNumber: 'S-102', rAge: '20',
                    reportNumber: 'R-2002', passFail: 'Fail', rfuNtr: 'NTR',
                    clientName: 'Client B', projectName: 'Project Beta', serviceTestDescription: 'Material Composition',
                    method: 'Method B', createdDate: '2025-08-21', createdBy: 'User B',
                    checkedDate: '2025-08-22', checkedBy: 'John Doe', approvedDate: null, approvedBy: null,
                    declined: 'No', projectCode: 'P-BETA-456', department: 'Chemistry',
                    status: 'checking_request'
                },
                {
                    orderNumber: 'ORD-003', orderDate: '2025-08-19', sampleNumber: 'S-103', rAge: '18',
                    reportNumber: 'R-2003', passFail: 'Pass', rfuNtr: 'RFU',
                    clientName: 'Client C', projectName: 'Project Gamma', serviceTestDescription: 'DNA Sequencing',
                    method: 'Method C', createdDate: '2025-08-19', createdBy: 'User C',
                    checkedDate: '2025-08-20', checkedBy: 'Jane Smith', approvedDate: '2025-08-21', approvedBy: 'Alex Brown',
                    declined: 'No', projectCode: 'P-GAMMA-789', department: 'Biology',
                    status: 'approved'
                },
                {
                    orderNumber: 'ORD-004', orderDate: '2025-08-23', sampleNumber: 'S-104', rAge: '25',
                    reportNumber: 'R-2004', passFail: 'N/A', rfuNtr: 'N/A',
                    clientName: 'Client D', projectName: 'Project Delta', serviceTestDescription: 'Soil Analysis',
                    method: 'Method D', createdDate: '2025-08-23', createdBy: 'User D',
                    checkedDate: '2025-08-24', checkedBy: 'Alex Brown', approvedDate: null, approvedBy: null,
                    declined: 'Yes', projectCode: 'P-DELTA-012', department: 'Geology',
                    status: 'rejected'
                }
            ];

            const reportsTableBody = $('#reports-table-body');
            const columnSearchInputs = $('.column-search-row input, .column-search-row select');

            // Function to dynamically populate dropdowns based on unique values in data
            function populateDropdowns() {
                const uniqueDepartments = [...new Set(reports.map(report => report.department))];
                const uniqueMethods = [...new Set(reports.map(report => report.method))];

                const departmentFilter = $('#department-filter');
                uniqueDepartments.forEach(dept => {
                    departmentFilter.append(`<option value="${dept}">${dept}</option>`);
                });

                const methodFilter = $('#method-filter');
                uniqueMethods.forEach(method => {
                    methodFilter.append(`<option value="${method}">${method}</option>`);
                });
            }

            function filterAndSearchReports() {
                const searchTerms = {};
                columnSearchInputs.each(function() {
                    const column = $(this).data('column');
                    const value = $(this).val();
                    if (value && value !== 'all' && value !== '') { // Exclude "all" and empty values
                        searchTerms[column] = value.toLowerCase();
                    }
                });

                const filteredReports = reports.filter(report => {
                    for (const column in searchTerms) {
                        const term = searchTerms[column];
                        const reportValue = String(report[column]).toLowerCase();
                        if (!reportValue.includes(term)) {
                            return false;
                        }
                    }
                    return true;
                });

                renderReports(filteredReports);
            }

            function renderReports(filteredReports) {
                reportsTableBody.empty();

                if (filteredReports.length === 0) {
                    reportsTableBody.append(`<tr><td colspan="23" class="no-data">No reports found.</td></tr>`);
                    return;
                }

                filteredReports.forEach(report => {
                    let statusClass = '';
                    let statusText = '';
                    let rowClass = '';

                    switch (report.status) {
                        case 'submit_for_approval':
                            statusClass = 'status-submit';
                            statusText = 'Submit for Approval';
                            break;
                        case 'checking_request':
                            statusClass = 'status-checking';
                            statusText = 'Checking Request';
                            break;
                        case 'approved':
                            statusClass = 'status-approved';
                            statusText = 'Approved';
                            rowClass = 'status-approved-row';
                            break;
                        case 'rejected':
                            statusClass = 'status-rejected';
                            statusText = 'Rejected';
                            rowClass = 'status-rejected-row';
                            break;
                    }

                    const row = `
                        <tr class="${rowClass}">
                            <td><a href="edit-report.html?order=${report.orderNumber}" class="action-link" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a></td>
                            <td><a href="report-details.html?order=${report.orderNumber}" class="action-link" title="View Report"><i class="fa-solid fa-file-invoice"></i></a></td>
                            <td>${report.department}</td>
                            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                            <td>${report.orderNumber}</td>
                            <td>${report.orderDate}</td>
                            <td>${report.sampleNumber}</td>
                            <td>${report.rAge}</td>
                            <td>${report.reportNumber}</td>
                            <td>${report.passFail}</td>
                            <td>${report.rfuNtr}</td>
                            <td>${report.clientName}</td>
                            <td>${report.projectName}</td>
                            <td>${report.serviceTestDescription}</td>
                            <td>${report.method}</td>
                            <td>${report.createdDate}</td>
                            <td>${report.createdBy}</td>
                            <td>${report.checkedDate ? `${report.checkedDate}` : 'N/A'}</td>
                            <td>${report.checkedBy ? `${report.checkedBy}` : 'N/A'}</td>
                            <td>${report.approvedDate ? `${report.approvedDate}` : 'N/A'}</td>
                            <td>${report.approvedBy ? `${report.approvedBy}` : 'N/A'}</td>
                            <td>${report.declined}</td>
                            <td>${report.projectCode}</td>
                        </tr>
                    `;

                    reportsTableBody.append(row);
                });
            }

            // Populate dropdowns on initial load
            populateDropdowns();

            // Handle filter and search changes
            columnSearchInputs.on('keyup change', filterAndSearchReports);

            // Initial render of all reports
            renderReports(reports);
        });
    </script>
