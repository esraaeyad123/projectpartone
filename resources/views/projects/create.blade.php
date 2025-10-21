<!-------------------------- Add New Project Modal -------------------------->
<div id="projectModal" class="modal">
    <div class="modal-content new-project-modal-design">
        <span class="close-btn" onclick="closeProjectModal()"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Add New Project 📝</h2>

        <div class="tab-buttons">
            <button id="project-btn" onclick="switchTab('project')" class="active"><i class="fas fa-user"></i> Project</button>
            <button id="contact-btn" onclick="switchTab('contact')"><i class="fas fa-address-book"></i> Contacts</button>
        </div>

        <form id="projectForm">
            <!-------------------------- project Tab -------------------------->
            <div id="projectTab" class="form-tab-content active">
                <fieldset class="form-section-fieldset">
                    <legend>Project Information</legend>
                    <input type="hidden" id="projectId">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="projectReference">Project Reference:</label>
                            <input type="text" id="projectReference" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder = "Automatically Generated">
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
                            <input type="date" id="registrationDate" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                      <div class="form-row">
                        <div class="form-group">
                            <label for="project_details">Project Details</label>
                            <input type="text" id="project_details" >
                        </div>
                    </div>
                </fieldset>

                <fieldset class="form-section-fieldset">
                    <legend>Project Location</legend>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="projectArabicLocation">Location:</label>
                            <input type="text" id="projectArabicLocation">
                        </div>
                    </div>
                </fieldset>

    <fieldset class="form-section-fieldset">
    <legend>Parties Section</legend>

    <!-- Customer -->
    <div class="form-row">
        <div class="form-group">
            <label for="customer">Customer:</label>
            <select id="customer" name="customer_id">
                <option value="" disabled selected>[Select Customer]</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->customer_name }} (ID: {{ $customer->id }})
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Owner & Consultant -->
    <div class="form-row">
        <div class="form-group">
            <label for="owner">Owner:</label>
            <select id="owner" name="owner_id">
                <option value="" disabled selected>[Select Owner]</option>
                @foreach($customers as $customer)
                    @if($customer->customer_type === 'Owner')
                        <option value="{{ $customer->id }}">
                            {{ $customer->customer_name }} (ID: {{ $customer->id }})
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="consultant">Consultant:</label>
            <select id="consultant" name="consultant_id">
                <option value="" disabled selected>[Select Consultant]</option>
                @foreach($customers as $customer)
                    @if($customer->customer_type === 'Consultant')
                        <option value="{{ $customer->id }}">
                            {{ $customer->customer_name }} (ID: {{ $customer->id }})
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <!-- Contractor -->
    <div class="form-row">
        <div class="form-group">
            <label for="contractor">Contractor:</label>
            <select id="contractor" name="contractor_id">
                <option value="" disabled selected>[Select Contractor]</option>
                @foreach($customers as $customer)
                    @if($customer->customer_type === 'Contractor')
                        <option value="{{ $customer->id }}">
                            {{ $customer->customer_name }} (ID: {{ $customer->id }})
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
</fieldset>


            </div>
            <!-------------------------- Contact Tab -------------------------->
            <div id="contactTab" class="form-tab-content" style="display: none;">
                <fieldset class="form-section-fieldset">
                    <legend>Contact List</legend>
                    <div class="contact-toolbar" style="border-bottom: none; padding-bottom: 5px;">
                        <button type="button" class="btn-secondary" onclick="window.populateContactFormForEdit('add')">
                            <i class="fas fa-pen"></i> Edit Selected
                        </button>
                        <button type="button" class="btn-danger" onclick="deleteSelectedContacts('#contactsTable')">
                            <i class="fas fa-trash"></i> Delete Selected
                        </button>
                        <button title="Export to Excel" onclick="exportContactsExcelBtn('contactsTable')" class="btn-icon">
                            <i class="fa-solid fa-table"></i>
                        </button>
                        <button title="Print" onclick="printContactsTable('contactsTable')" class="btn-icon">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                    <div class="table-responsive-container">
                        <table id="contactsTable" class="contacts-table display responsive nowrap" data-ignore-lang>
                            <thead>
                                <tr data-contact-id="">
                                    <th><input type="checkbox" id="selectAllContacts" onclick="window.toggleAllContacts(this, 'contactsTable')"></th>
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
                    <legend>Add Contact Person</legend>
                    <input type="hidden" id="editContactIdAdd">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contactNameAdd">Contact Name:</label>
                            <input type="text" id="contactNameAdd" placeholder="Enter contact name">
                        </div>
                        <div class="form-group">
                            <label for="contactEmailAdd">Contact Email:</label>
                            <input type="email" id="contactEmailAdd" placeholder="e.g., contact@example.com">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contactPhoneAdd">Contact Phone:</label>
                            <input type="tel" id="contactPhoneAdd" placeholder="e.g., +9665XXXXXXXX">
                        </div>
                        <div class="form-group">
                            <label for="contactMobileAdd">Contact Mobile:</label>
                            <input type="tel" id="contactMobileAdd" placeholder="e.g., +9665XXXXXXXX">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contactPositionAdd">Position:</label>
                            <input type="text" id="contactPositionAdd" placeholder="e.g., Sales Manager">
                        </div>
                        <div class="form-group-checkbox">
                            <input type="checkbox" id="isPrimaryContactAdd">
                            <label for="isPrimaryContactAdd">Primary Contact</label>
                        </div>
                    </div>

                    <div class="contact-toolbar">
                        <button type="button" class="btn btn-primary" onclick="saveContactForProject('add')">
                            💾 Save / Update
                        </button>
                        <button type="button" class="btn-secondary" onclick="clearContactForm('add')">
                            <i class="fas fa-eraser"></i> Clear Form
                        </button>
                    </div>
                </fieldset>
            </div>

            <!-------------------------- Modal Buttons -------------------------->
            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="closeProjectModal()"><i class="fas fa-times"></i> Close</button>
                <button type="button" class="btn-success" onclick="saveProject(true)"><i class="fas fa-save"></i> Save & Close</button>
                <button type="button" class="btn-primary" onclick="saveProject(false)"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
<!-------------------------- End New Project Modal -------------------------->
<div id="modal-container"></div>
<div id="dynamicContent"></div>
<div id="customDialogModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeCustomDialog()">&times;</span>
        <h3 id="customDialogTitle"></h3>
        <p id="customDialogMessage"></p>
        <div class="form-buttons" id="customDialogButtons"></div>
    </div>
</div>

<!-------------------------- Edit Project Modal -------------------------->
<div id="editProjectModal" class="modal">
    <div class="modal-content new-project-modal-design">
        <span class="close-btn" onclick="closeEditProjectModal()"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Edit Project 📝</h2>

        <div class="tab-buttons">
            <button id="edit-project-btn" onclick="switchTab('edit-project')" class="active"><i class="fas fa-user"></i> Project</button>
            <button id="edit-contact-btn" onclick="switchTab('edit-contact')"><i class="fas fa-address-book"></i> Contacts</button>
        </div>
        <!-------------------------- Edit Project Form-------------------------->
        <form id="editProjectForm">
            <div id="editProjectTab" class="form-tab-content active">
                <fieldset class="form-section-fieldset">
                    <legend>Project Information</legend>
                    <input type="hidden" id="editProjectId">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProjectReference">Project Reference:</label>
                            <input type="text" id="editProjectReference" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder = "Automatically Generated">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProjectName">Project Name:</label>
                            <input type="text" id="editProjectName" name="projectName">
                        </div>
                        <div class="form-group">
                            <label for="editProjectArabicName">Arabic Name:</label>
                            <input type="text" id="editProjectArabicName">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editRegistrationDate">Date Registered:</label>
                            <input type="date" id="editRegistrationDate" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-group">
                            <label for="project_details">Project Details</label>
                            <input type="text" id="editprojectDetails"  name="projectDetails">
                        </div>
                </fieldset>
<fieldset class="form-section-fieldset">
    <legend>Project Location</legend>
    <div class="form-row">
        <div class="form-group">
            <label for="editProjectArabicLocation">Location:</label>
            <input type="text" id="editProjectArabicLocation">
        </div>
    </div>
</fieldset>

<fieldset class="form-section-fieldset">
    <legend>Parties Section</legend>

    {{-- Customer --}}
    <div class="form-row">
        <div class="form-group">
            <label for="editCustomer">Customer:</label>
            <select id="editCustomer" name="customer_id">
                <option value="" disabled selected>[Select Customer]</option>
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->customer_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Owner & Consultant --}}
    <div class="form-row">
        <div class="form-group">
            <label for="editOwner">Owner:</label>
            <select id="editOwner" name="owner_id">
                <option value="" disabled selected>[Select Owner]</option>
                @foreach($customers->where('customer_type', 'Owner') as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->customer_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="editConsultant">Consultant:</label>
            <select id="editConsultant" name="consultant_id">
                <option value="" disabled selected>[Select Consultant]</option>
                @foreach($customers->where('customer_type', 'Consultant') as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->customer_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Contractor --}}
    <div class="form-row">
        <div class="form-group">
            <label for="editContractor">Contractor:</label>
            <select id="editContractor" name="contractor_id">
                <option value="" disabled selected>[Select Contractor]</option>
                @foreach($customers->where('customer_type', 'Contractor') as $customer)
                    <option value="{{ $customer->id }}">
                        {{ $customer->customer_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</fieldset>

            </div>
            <!-------------------------- Edit Contac tTab-------------------------->
            <div id="editContactTab" class="form-tab-content" style="display: none;">
                    <fieldset class="form-section-fieldset">
                    <legend>Contact List</legend>
                    <div class="contact-toolbar" style="border-bottom: none; padding-bottom: 5px;">
                    <button type="button" class="btn-secondary" onclick="window.populateContactFormForEdit('edit')">
                    <i class="fas fa-pen"></i> Edit Selected
                    </button>

                    <button type="button" class="btn-danger" onclick="deleteSelectedContacts('#contactsTableEdit')">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>

                    <button title="Export to Excel" onclick="exportContactsExcelBtn('contactsTableEdit')" class="btn-icon">
                        <i class="fa-solid fa-table"></i>
                    </button>


                    <button title="Print" onclick="printContactsTable('contactsTableEdit')" class="btn-icon">
                        <i class="fas fa-print"></i>
                    </button>
                    </div>
                    <div class="table-responsive-container">
                            <table id="contactsTableEdit" class="contacts-table display responsive nowrap" data-ignore-lang>
                                        <thead>
                                            <tr data-contact-id="">
                                                <th><input type="checkbox" id="selectAllContacts" onclick="window.toggleAllContacts(this, 'contactsTableEdit')"></th>
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
                    <legend>Add/Edit Contact Person</legend>
                    <div class="form-row">
                        <input type="hidden" id="editContactId">


                        <div class="form-group">
                        <label for="contactNameedit">Contact Name:</label>
                        <input type="text" id="contactNameedit" placeholder="Enter contact name">
                        </div>
                        <div class="form-group">
                        <label for="contactEmailedit">Contact Email:</label>
                        <input type="email" id="contactEmailedit" placeholder="contact@example.com">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                        <label for="contactPhoneedit">Contact Phone:</label>
                        <input type="tel" id="contactPhoneedit" placeholder="+9665XXXXXXXX">
                        </div>
                        <div class="form-group">
                        <label for="contactMobileedit">Contact Mobile:</label>
                        <input type="tel" id="contactMobileedit" placeholder="+9665XXXXXXXX">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                        <label for="contactPositionedit">Position:</label>
                        <input type="text" id="contactPositionedit" placeholder="e.g., Sales Manager">
                        </div>
                        <div class="form-group-checkbox">
                        <input type="checkbox" id="isPrimaryContact">
                        <label for="isPrimaryContact">Primary Contact</label>
                        </div>
                    </div>

                    <div class="contact-toolbar">
                        <button type="button" class="btn btn-primary" onclick="saveContactForProject('edit')">
                        💾 Save / Update
                    </button>
                    <button type="button" class="btn-secondary" onclick="clearContactForm('edit')">
                        <i class="fas fa-eraser"></i> Clear Form
                    </button>
                    </div>
                    </fieldset>
            </div>
            <!-------------------------- Modal Buttons -------------------------->
            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="closeEditProjectModal()"><i class="fas fa-times"></i> Close</button>
                <button type="button" class="btn-success" onclick="saveEditProject(true)"><i class="fas fa-save"></i> Update & Close</button>
                <button type="button" class="btn-primary" onclick="saveEditProject(false)"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
<!-------------------------- End Edit Project Modal -------------------------->
