<div id="employeeModal" class="modal">
    <div class="modal-content new-employee-modal-design">
        <span class="close-btn" onclick="closeEmployeeModal()">
            <i class="fas fa-times"></i>
        </span>
        <h2 class="modal-title">Add new employee 👨‍💼</h2>

        <!-- Tabs -->
        <div class="tab-buttons">
    <button id="employee-btn" onclick="switchEmployeeTab('employee')" class="active">
        <i class="fas fa-user"></i> Employee
    </button>
    <button id="contact-btn" onclick="switchEmployeeTab('contact')">
        <i class="fas fa-address-book"></i> Contacts
    </button>
</div>


        <!-- Form Start -->
        <form id="employeeForm" onsubmit="saveEmployee(event)">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="employeeId">

            <!-- Employee Tab -->
            <div id="employeeTab" class="form-tab-content active">
                <fieldset class="form-section-fieldset">
                    <legend>Employee Information</legend>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="employeeReference">Employee Reference:</label>
                            <input type="text" id="employeeReference" readonly
                                   style="background-color: #e9ecef; cursor: not-allowed;"
                                   placeholder="Automatically Generated">
                        </div>
                        <div class="form-group">
                            <label for="initials">Initials:</label>
                            <select id="initials"></select>
                        </div>
                        <div class="form-group">
                            <label for="title">Title:</label>
                            <input type="text" id="title">
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
                            <label for="supervisor">Supervisor:</label>
                            <select id="supervisor">
                                <option value="" selected disabled>[Select Supervisor]</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="businessUnit">Business Unit:</label>
                            <select id="businessUnit">
                                <option value="AAM-EHSA" selected>AAM-EHSA</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="department">Department:</label>
                            <select id="department">
                                <option value="" selected disabled>[Select Department]</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ctta">CTTA:</label>
                            <input type="text" id="ctta">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group-checkbox">
                            <label>Job Roles</label>
                            <div class="job-rules-container">
                                <input type="checkbox" id="officeStaff"><label for="officeStaff">Office Staff</label>
                                <input type="checkbox" id="laboratoryStaff"><label for="laboratoryStaff">Laboratory Staff</label>
                                <input type="checkbox" id="siteStaff"><label for="siteStaff">Site Staff</label>
                                <input type="checkbox" id="drillersStaff"><label for="drillersStaff">Drillers Staff</label>
                                <input type="checkbox" id="driversStaff"><label for="driversStaff">Drivers Staff</label>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>

            <!-- Contact Tab -->
            <div id="contactTab" class="form-tab-content" style="display:none;">
                <fieldset class="form-section-fieldset">
                    <legend>Contact List</legend>
                    <div class="contact-toolbar">
                        <button type="button" class="btn-secondary" onclick="populateContactFormForEdit('employee')">
                            <i class="fas fa-pen"></i> Edit Selected
                        </button>
                        <button type="button" class="btn-danger" onclick="deleteSelectedContacts('#employeeContactsTable')">
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                        <button type="button" class="btn-icon" onclick="exportContactsExcelBtn('employeeContactsTable')">
                            <i class="fa-solid fa-table"></i>
                        </button>
                        <button type="button" class="btn-icon" onclick="printContactsTable('employeeContactsTable')">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                    <div class="table-responsive-container">
                        <table id="employeeContactsTable" class="contacts-table display responsive nowrap">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" onclick="toggleAllContacts(this,'employeeContactsTable')"></th>
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
                            <tbody></tbody>
                        </table>
                    </div>
                </fieldset>

                <fieldset class="form-section-fieldset">
                    <legend>Add / Edit Contact Person</legend>
                    <input type="hidden" id="editContactIdEmployee">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contactNameEmployee">Contact Name:</label>
                            <input type="text" id="contactNameEmployee">
                        </div>
                        <div class="form-group">
                            <label for="contactEmailEmployee">Contact Email:</label>
                            <input type="email" id="contactEmailEmployee">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contactPhoneEmployee">Contact Phone:</label>
                            <input type="tel" id="contactPhoneEmployee">
                        </div>
                        <div class="form-group">
                            <label for="contactMobileEmployee">Contact Mobile:</label>
                            <input type="tel" id="contactMobileEmployee">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contactPositionEmployee">Position:</label>
                            <input type="text" id="contactPositionEmployee">
                        </div>
                        <div class="form-group-checkbox">
                            <input type="checkbox" id="isPrimaryContactEmployee">
                            <label for="isPrimaryContactEmployee">Primary Contact</label>
                        </div>
                    </div>

                    <div class="contact-toolbar">
                        <button type="button" class="btn btn-primary" onclick="saveContactForEmployee()">
                            💾 Save / Update
                        </button>
                        <button type="button" class="btn-secondary" onclick="clearContactForm('employee')">
                            <i class="fas fa-eraser"></i> Clear Form
                        </button>
                    </div>
                </fieldset>
            </div>

            <!-- Modal Buttons -->
            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="closeEmployeeModal()">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="button" class="btn-success" onclick="saveEmployee(event, true)">
                    <i class="fas fa-save"></i> Save & Close
                </button>
                <button type="button" class="btn-success" onclick="saveEmployee(event, false)">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>

<!-------------------------- End Add Employee Modal -------------------------->


<!-------------------------- Edit Employee Modal -------------------------->
<div id="editEmployeeModal" class="modal">
  <div class="modal-content new-employee-modal-design">
    <span class="close-btn" onclick="closeEditEmployeeModal()"><i class="fas fa-times"></i></span>
    <h2 class="modal-title">Edit Employee 👨‍💼</h2>

    <form id="editEmployeeForm">
      <input type="hidden" id="editEmployeeId">

      <fieldset class="form-section-fieldset">
        <legend>Employee Information</legend>
        <div class="form-row">
          <div class="form-group">
            <label for="editEmployeeReference">Employee Reference:</label>
            <input type="text" id="editEmployeeReference" readonly>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="editEmployeeName">Full Name:</label>
            <input type="text" id="editEmployeeName">
          </div>
          <div class="form-group">
            <label for="editEmployeeEmail">Email:</label>
            <input type="email" id="editEmployeeEmail">
          </div>
          <div class="form-group">
            <label for="editEmployeeTitle">Title:</label>
            <input type="text" id="editEmployeeTitle">
          </div>
        </div>
      </fieldset>

      <!---------------- Modal Buttons ---------------->
      <div class="form-buttons modal-bottom-buttons">
        <button type="button" class="btn-primary" onclick="closeEditEmployeeModal()"><i class="fas fa-times"></i> Close</button>
        <button type="button" class="btn-success" onclick="updateEmployee(true)">
        <i class="fas fa-save"></i> Update & Close
        </button>
        <button type="button" class="btn-primary" onclick="updateEmployee(false)">
        <i class="fas fa-save"></i> Update
        </button>

      </div>
    </form>
  </div>
</div>
<!-------------------------- End Edit Employee Modal -------------------------->
