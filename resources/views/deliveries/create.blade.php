<div id="employeeModal" class="modal">
  <div class="modal-content new-employee-modal-design">
    <span class="close-btn" onclick="closeEmployeeModal()"><i class="fas fa-times"></i></span>
    <h2 class="modal-title">Add New Employee 👨‍💼</h2>

    <div class="tab-buttons">
      <button id="employee-btn" onclick="switchEmployeeTab('employee')" class="active">
        <i class="fas fa-user"></i> Employee
      </button>
      <button id="contact-btn" onclick="switchEmployeeTab('contact')">
        <i class="fas fa-address-book"></i> Contacts
      </button>
    </div>

    <form id="employeeForm"> <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" id="employeeId">

      <div id="employeeTab" class="form-tab-content active">

        <fieldset class="form-section-fieldset">
          <legend>Employee Personal Information</legend>

          <div class="form-row">
            <div class="form-group">
              <label for="initials">Initials:</label>
              <select id="initials">
                <option value="" selected disabled>Select Initials</option>
                <option value="Mr.">Mr.</option>
                <option value="Ms.">Ms.</option>
                <option value="Mrs.">Mrs.</option>
                <option value="Dr.">Dr.</option>
                <option value="Eng.">Eng.</option>
                <option value="Prof.">Prof.</option>
              </select>
            </div>
            <div class="form-group">
              <label for="title">Title:</label>
              <input type="text" id="title">
            </div>
            <div class="form-group">
              <label for="email">Email:</label>
              <input type="email" id="email">
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
            <div class="form-group full-width-group">
              <label for="fullName">Full Name:</label>
              <input type="text" id="fullName" readonly style="background-color: #f0f0f0;">
            </div>
          </div>
        </fieldset>

        <fieldset class="form-section-fieldset">
          <legend>Employment Information</legend>

          <div class="form-row">
            <div class="form-group">
              <label for="employeeReference">Employee Reference:</label>
              <input type="text" id="employeeReference" readonly
                     style="background-color: #e9ecef; cursor: not-allowed;"
                     placeholder="Automatically Generated">
            </div>
            <div class="form-group">
              <label for="supervisor">Supervisor:</label>
              <select id="supervisor">
                <option value="" selected disabled>Select Supervisor</option>
              </select>
            </div>
            <div class="form-group">
              <label for="businessUnit">Business Unit:</label>
              <select id="businessUnit">
                  <option value="" disabled selected>Select Unit</option> <option value="AAM-JED">AAM-JED</option>
                  <option value="AAM-EHSA">AAM-EHSA</option>
              </select>
          </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="department">Department:</label>
              <select id="department">
                <option value="" selected disabled>Select Department</option>
              </select>
            </div>
            <div class="form-group">
              <label for="CTTA">CTTA:</label>
              <input type="text" id="CTTA">
            </div>
            <div class="form-group-placeholder">
              </div>
          </div>

          <div class="form-row">
            <div class="form-group-checkbox full-width-group job-roles-container-wrapper" style="padding-top: 10px;">
              <label for="" style="display: block; font-weight: bold; margin-bottom: 10px;">Job Roles:</label> 
              <div class="job-roles-creative-grid">
                
                <div class="role-box">
                    <input type="checkbox" id="officeStaff" class="hidden-checkbox">
                    <label for="officeStaff" class="checkbox-label">
                        <i class="fas fa-briefcase"></i>
                        <span>Office Staff</span>
                    </label>
                </div>

                <div class="role-box">
                    <input type="checkbox" id="laboratoryStaff" class="hidden-checkbox">
                    <label for="laboratoryStaff" class="checkbox-label">
                        <i class="fas fa-flask"></i>
                        <span>Laboratory Staff</span>
                    </label>
                </div>

                <div class="role-box">
                    <input type="checkbox" id="siteStaff" class="hidden-checkbox">
                    <label for="siteStaff" class="checkbox-label">
                        <i class="fas fa-hard-hat"></i>
                        <span>Site Staff</span>
                    </label>
                </div>

                <div class="role-box">
                    <input type="checkbox" id="drillerStaff" class="hidden-checkbox">
                    <label for="drillerStaff" class="checkbox-label">
                        <i class="fas fa-hammer"></i> <span>Driller Staff</span>
                    </label>
                </div>

                <div class="role-box">
                    <input type="checkbox" id="driversStaff" class="hidden-checkbox">
                    <label for="driversStaff" class="checkbox-label">
                        <i class="fas fa-truck"></i>
                        <span>Drivers Staff</span>
                    </label>
                </div>

              </div>
            </div>
          </div>
        </fieldset>

      </div>

      <div id="contactTab" class="form-tab-content" style="display: none;">
        <fieldset class="form-section-fieldset">
          <legend>Contact List</legend>
          <div class="contact-toolbar">
            <button type="button" class="btn-secondary" onclick="populateEmployeeContactForm('add')">
              <i class="fas fa-pen"></i> Edit Selected
            </button>
            <button type="button" class="btn-danger" onclick="deleteSelectedEmployeeContacts('#employeeContactsTable')">
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
          <input type="hidden" id="contactIdEmployee">

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
            <button type="button" class="btn btn-primary" onclick="saveEmployeeContact('add')">
              💾 Save / Update
            </button>
            <button type="button" class="btn btn-secondary" onclick="clearEmployeeContactForm('add')">
              <i class="fas fa-eraser"></i> Clear Form
            </button>
          </div>
        </fieldset>
      </div>

      <div class="form-buttons modal-bottom-buttons">
        <button type="button" class="btn-primary" onclick="closeEmployeeModal()">
          <i class="fas fa-times"></i> Close
        </button>
        <button type="button" class="btn-success" onclick="saveEmployee(true)">
          <i class="fas fa-save"></i> Save & Close
        </button>
        <button type="button" class="btn-success" onclick="saveEmployee(false)">
          <i class="fas fa-save"></i> Save
        </button>
      </div>
    </form>
  </div>
</div>

<div id="editEmployeeModal" class="modal">
  <div class="modal-content new-employee-modal-design">
    <span class="close-btn" onclick="closeEditEmployeeModal()">
      <i class="fas fa-times"></i>
    </span>
    <h2 class="modal-title">Edit Employee 📝</h2>

    <div class="tab-buttons">
      <button type="button" id="edit-employee-btn"
              onclick="switchEmployeeTab('employee', 'edit')"
              class="active">
        <i class="fas fa-user"></i> Employee
      </button>
      <button type="button" id="edit-contact-btn"
              onclick="switchEmployeeTab('contact', 'edit')">
        <i class="fas fa-address-book"></i> Contacts
      </button>
    </div>

    <form id="editEmployeeForm">
      <input type="hidden" id="editEmployeeId">

      <div id="editEmployeeTab" class="form-tab-content active">

        <fieldset class="form-section-fieldset">
          <legend>Employee Personal Information</legend>

          <div class="form-row">
            <div class="form-group">
              <label for="editInitials">Initials:</label>
              <select id="editInitials">
                <option value="" selected disabled>Select Initials</option>
                <option value="Mr.">Mr.</option>
                <option value="Ms.">Ms.</option>
                <option value="Mrs.">Mrs.</option>
                <option value="Dr.">Dr.</option>
                <option value="Eng.">Eng.</option>
                <option value="Prof.">Prof.</option>
              </select>
            </div>
            <div class="form-group">
              <label for="editTitle">Title:</label>
              <input type="text" id="editTitle">
            </div>
            <div class="form-group">
              <label for="editEmail">Email:</label>
              <input type="email" id="editEmail">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editFirstName">First Name:</label>
              <input type="text" id="editFirstName">
            </div>
            <div class="form-group">
              <label for="editMidName">Mid. Name:</label>
              <input type="text" id="editMidName">
            </div>
            <div class="form-group">
              <label for="editLastName">Last Name:</label>
              <input type="text" id="editLastName">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group full-width-group">
              <label for="editFullName">Full Name:</label>
              <input type="text" id="editFullName" readonly style="background-color: #f0f0f0;">
            </div>
          </div>
        </fieldset>

        <fieldset class="form-section-fieldset">
          <legend>Employment Information</legend>

          <div class="form-row">
            <div class="form-group">
              <label for="editEmployeeReference">Employee Reference:</label>
              <input type="text" id="editEmployeeReference" readonly
                     style="background-color: #e9ecef; cursor: not-allowed;"
                     placeholder="Automatically Generated">
            </div>
            <div class="form-group">
              <label for="editSupervisor">Supervisor:</label>
              <select id="editSupervisor">
                <option value="" selected disabled>Select Supervisor</option>
              </select>
            </div>
            <div class="form-group">
              <label for="editBusinessUnit">Business Unit:</label>
              <select id="editBusinessUnit">
                  <option value="" disabled selected>Select Unit
                  </option> <option value="AAM-JED">AAM-JED</option>
                  <option value="AAM-EHSA">AAM-EHSA</option>
              </select>
          </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editDepartment">Department:</label>
              <select id="editDepartment">
                <option value="" selected disabled>Select Department</option>
              </select>
            </div>
            <div class="form-group">
              <label for="editCTTA">CTTA:</label>
              <input type="text" id="editCTTA">
            </div>
            <div class="form-group-placeholder">
              </div>
          </div>

          <div class="form-row">
            <div class="form-group-checkbox full-width-group job-roles-container-wrapper" style="padding-top: 10px;">
              <label for="" style="display: block; font-weight: bold; margin-bottom: 10px;">Job Roles:</label> 
              <div class="job-roles-creative-grid">
                
                <div class="role-box">
                    <input type="checkbox" id="editOfficeStaff" class="hidden-checkbox">
                    <label for="editOfficeStaff" class="checkbox-label">
                        <i class="fas fa-briefcase"></i>
                        <span>Office Staff</span>
                    </label>
                </div>

                <div class="role-box">
                    <input type="checkbox" id="editLaboratoryStaff" class="hidden-checkbox">
                    <label for="editLaboratoryStaff" class="checkbox-label">
                        <i class="fas fa-flask"></i>
                        <span>Laboratory Staff</span>
                    </label>
                </div>

                <div class="role-box">
                    <input type="checkbox" id="editSiteStaff" class="hidden-checkbox">
                    <label for="editSiteStaff" class="checkbox-label">
                        <i class="fas fa-hard-hat"></i>
                        <span>Site Staff</span>
                    </label>
                </div>

                <div class="role-box">
                    <input type="checkbox" id="editDrillerStaff" class="hidden-checkbox">
                    <label for="editDrillerStaff" class="checkbox-label">
                        <i class="fas fa-hammer"></i> <span>Driller Staff</span>
                    </label>
                </div>

                <div class="role-box">
                    <input type="checkbox" id="editDriversStaff" class="hidden-checkbox">
                    <label for="editDriversStaff" class="checkbox-label">
                        <i class="fas fa-truck"></i>
                        <span>Drivers Staff</span>
                    </label>
                </div>

              </div>
            </div>
          </div>
        </fieldset>
      </div>

      <div id="editContactTab" class="form-tab-content" style="display: none;">
        <fieldset class="form-section-fieldset">
          <legend>Contact List</legend>
          <div class="contact-toolbar">
            <button type="button" class="btn-secondary" onclick="populateEmployeeContactFormForEdit('edit')">
              <i class="fas fa-pen"></i> Edit Selected
            </button>
            <button type="button" class="btn-danger" onclick="deleteSelectedEmployeeContacts('#editEmployeeContactsTable')">
              <i class="fas fa-trash"></i> Delete Selected
            </button>
            <button type="button" class="btn-icon" onclick="exportContactsExcelBtn('editEmployeeContactsTable')">
              <i class="fa-solid fa-table"></i>
            </button>
            <button type="button" class="btn-icon" onclick="printContactsTable('editEmployeeContactsTable')">
              <i class="fas fa-print"></i>
            </button>
          </div>
          <div class="table-responsive-container">
            <table id="editEmployeeContactsTable" class="contacts-table display responsive nowrap">
              <thead>
                <tr>
                  <th><input type="checkbox" onclick="toggleAllContacts(this,'editEmployeeContactsTable')"></th>
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
              <label for="editContactNameEmployee">Contact Name:</label>
              <input type="text" id="editContactNameEmployee">
            </div>
            <div class="form-group">
              <label for="editContactEmailEmployee">Contact Email:</label>
              <input type="email" id="editContactEmailEmployee">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editContactPhoneEmployee">Contact Phone:</label>
              <input type="tel" id="editContactPhoneEmployee">
            </div>
            <div class="form-group">
              <label for="editContactMobileEmployee">Contact Mobile:</label>
              <input type="tel" id="editContactMobileEmployee">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editContactPositionEmployee">Position:</label>
              <input type="text" id="editContactPositionEmployee">
            </div>
            <div class="form-group-checkbox">
              <input type="checkbox" id="editIsPrimaryContactEmployee">
              <label for="editIsPrimaryContactEmployee">Primary Contact</label>
            </div>
          </div>

          <div class="contact-toolbar">
            <button type="button" class="btn btn-primary" onclick="saveEmployeeContact('edit')">
              💾 Save / Update
            </button>
            <button type="button" class="btn btn-secondary" onclick="clearEmployeeContactForm('edit')">
              <i class="fas fa-eraser"></i> Clear Form
            </button>
          </div>
        </fieldset>
      </div>

      <div class="form-buttons modal-bottom-buttons">

      <button type="button" class="btn-primary" onclick="closeEditEmployeeModal()">
          <i class="fas fa-times"></i> Close
        </button>
        <button type="button" class="btn-success" onclick="updateEmployee(true)">
          <i class="fas fa-save"></i> Update & Close
        </button>
        <button type="button" class="btn-success" onclick="updateEmployee(false)">
          <i class="fas fa-save"></i> Update
        </button>
        
      </div>
    </form>
  </div>
</div>
