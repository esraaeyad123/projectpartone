<!-- =================== Add Employee Modal =================== -->
<div id="employeeModal" class="modal">
  <div class="modal-content new-employee-modal-design">
    <span class="close-btn" onclick="closeEmployeeModal()"><i class="fas fa-times"></i></span>
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
            <button type="button" class="btn-secondary" onclick="populateEmployeeContactFormForEdit('employee')">
              <i class="fas fa-pen"></i> Edit Selected
            </button>
           <!-- HTML الزر -->
<button type="button" class="btn-danger" onclick="deleteSelectedEmployees('#employeeContactsTable')">
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
          <input type="hidden" id="employeeContactIdAdd">

          <div class="form-row">
            <div class="form-group">
              <label for="employeeContactName">Contact Name:</label>
              <input type="text" id="employeeContactName">
            </div>
            <div class="form-group">
              <label for="employeeContactEmail">Contact Email:</label>
              <input type="email" id="employeeContactEmail">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="employeeContactPhone">Contact Phone:</label>
              <input type="tel" id="employeeContactPhone">
            </div>
            <div class="form-group">
              <label for="employeeContactMobile">Contact Mobile:</label>
              <input type="tel" id="employeeContactMobile">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="employeeContactPosition">Position:</label>
              <input type="text" id="employeeContactPosition">
            </div>
            <div class="form-group-checkbox">
              <input type="checkbox" id="isPrimaryContactEmployee">
              <label for="isPrimaryContactEmployee">Primary Contact</label>
            </div>
          </div>

          <div class="contact-toolbar">
            <button type="button" class="btn btn-primary" onclick="saveEmployeeContact('employee')">
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
        <button type="button" class="btn-primary" onclick="closeEmployeeModal()"><i class="fas fa-times"></i> Close</button>
        <button type="button" class="btn-success" onclick="saveEmployee(true)"><i class="fas fa-save"></i> Save & Close</button>
        <button type="button" class="btn-success" onclick="saveEmployee(false)"><i class="fas fa-save"></i> Save</button>
      </div>
    </form>
  </div>
</div>



<!-- =================== Edit Employee Modal =================== -->
<div id="editEmployeeModal" class="modal">
  <div class="modal-content new-employee-modal-design">
    <span class="close-btn" onclick="closeEditEmployeeModal()">
      <i class="fas fa-times"></i>
    </span>
    <h2 class="modal-title">Edit Employee 📝</h2>

    <!-- Tabs -->
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

    <!-- Form Start -->
    <form id="editEmployeeForm">
      <input type="hidden" id="editEmployeeId">

      <!-- Employee Tab -->
      <div id="editEmployeeTab" class="form-tab-content active">
        <fieldset class="form-section-fieldset">
          <legend>Employee Information</legend>

          <div class="form-row">
            <div class="form-group">
              <label for="editEmployeeReference">Employee Reference:</label>
              <input type="text" id="editEmployeeReference" readonly
                     style="background-color: #e9ecef; cursor: not-allowed;"
                     placeholder="Automatically Generated">
            </div>
            <div class="form-group">
              <label for="editInitials">Initials:</label>
              <select id="editInitials"></select>
            </div>
            <div class="form-group">
              <label for="editTitle">Title:</label>
              <input type="text" id="editTitle">
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
            <div class="form-group">
              <label for="editFullName">Full Name:</label>
              <input type="text" id="editFullName">
            </div>
            <div class="form-group">
              <label for="editEmail">Email:</label>
              <input type="email" id="editEmail">
            </div>
            <div class="form-group">
              <label for="editSupervisor">Supervisor:</label>
              <select id="editSupervisor">
                <option value="" selected disabled>[Select Supervisor]</option>
              </select>
            </div>
          </div>
                <div class="form-row">
            <div class="form-group-checkbox">
              <label>Job Roles</label>
              <div class="job-rules-container">
                <input type="checkbox" id="editOfficeStaff"><label for="editOfficeStaff">Office Staff</label>
                <input type="checkbox" id="editLaboratoryStaff"><label for="editLaboratoryStaff">Laboratory Staff</label>
                <input type="checkbox" id="editSiteStaff"><label for="editSiteStaff">Site Staff</label>
                <input type="checkbox" id="editDrillersStaff"><label for="editDrillersStaff">Drillers Staff</label>
                <input type="checkbox" id="editDriversStaff"><label for="editDriversStaff">Drivers Staff</label>
              </div>
            </div>
          </div>
        </fieldset>
      </div>

      <!-- Contact Tab -->
      <div id="editContactTab" class="form-tab-content" style="display: none;">
        <fieldset class="form-section-fieldset">
          <legend>Contact List</legend>
          <div class="contact-toolbar">
            <button type="button" class="btn-secondary" onclick="populateEmployeeContactFormForEdit('edit')">
              <i class="fas fa-pen"></i> Edit Selected
            </button>
            <!-- HTML الزر -->
<button type="button" class="btn-danger" onclick="deleteSelectedEmployeeContacts('#editEmployeeContactsTable')">
    <i class="fas fa-trash"></i> Delete Selected
</button>

           <!-- داخل مودال التعديل -->
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

      <!-- Modal Buttons -->
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
