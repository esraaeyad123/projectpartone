<!-------------------------- Add New Equipment Modal -------------------------->
<div id="equipmentModal" class="modal">
    <div class="modal-content new-project-modal-design">
        <span class="close-btn" onclick="closeEquipmentModal()"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Add New Equipment ⚙️</h2>

        <div class="tab-buttons">
            <button id="equipment-details-btn" onclick="switchTab('equipment-details')" class="active">
                <i class="fas fa-cogs"></i> Equipment Details
            </button>
            <button id="calibration-btn" onclick="switchTab('calibration')">
                <i class="fas fa-ruler"></i> Calibrations
            </button>
            <button id="maintenance-btn" onclick="switchTab('maintenance')">
                <i class="fas fa-tools"></i> Maintenance
            </button>
        </div>

   <form id="equipmentForm" class="equipment-form">
    <!-------------------------- Equipment Details Tab -------------------------->
    <div id="equipment-detailsTab" class="form-tab-content active">
        <fieldset class="form-section-fieldset">
            <legend>Equipment Information</legend>
            <input type="hidden" id="equipmentId" name="id">

            <div class="form-row">
                <div class="form-group"><label>Alternative Id:</label><input type="text" name="alternative_id"></div>
                <div class="form-group"><label>Legacy Id:</label><input type="text" name="legacy_id"></div>
                <div class="form-group"><label>Description:</label><input type="text" name="description" required></div>
            </div>

            <div class="form-row">
                <div class="form-group"><label>Serial #:</label><input type="text" name="serial_number"></div>
                <div class="form-group"><label>Asset Tag:</label><input type="text" name="asset_tag"></div>
                <div class="form-group"><label>Size:</label><input type="text" name="size"></div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Type:</label>
                    <select name="type">
                        <option value="" disabled selected>Select type</option>
                        <option value="operational">Operational</option>
                        <option value="under-maintenance">Under Maintenance</option>
                        <option value="retired">Retired</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Make:</label>
                    <select name="make">
                        <option value="" disabled selected>Select make</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Model:</label>
                    <select name="model">
                        <option value="" disabled selected>Select model</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tolerance Basis:</label>
                    <select name="tolerance_basis">
                        <option value="" disabled selected>Select tolerance basis</option>
                    </select>
                </div>
                <div class="form-group"><label>Tolerance:</label><input type="text" name="tolerance"></div>
                <div class="form-group"><label>Range/Capacity:</label><input type="text" name="range_capacity"></div>
            </div>

            <div class="form-row">
                <div class="form-group"><label>Range Unit:</label><input type="text" name="range_unit"></div>
                <div class="form-group"><label>Resolution:</label><input type="text" name="resolution"></div>
                <div class="form-group">
                    <label>Resolution Unit:</label>
                    <select name="resolution_unit">
                        <option value="" disabled selected>Select Resolution Unit</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group"><label>Traceability:</label><input type="text" name="traceability"></div>
                <div class="form-group">
                    <label>Display Type:</label>
                    <select name="display_type">
                        <option value="" disabled selected>[Select display type]</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Manufacturer:</label>
                    <select name="manufacturer_id">
                        <option value="" disabled selected>[Select Manufacturer]</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group"><label>Department:</label>
                    <select name="department">
                        <option value="" disabled selected>[Select department]</option>
                    </select>
                </div>
                <div class="form-group"><label>Custodian:</label>
                    <select name="custodian">
                        <option value="" disabled selected>[Select custodian]</option>
                    </select>
                </div>
                <div class="form-group"><label>Location:</label>
                    <select name="location">
                        <option value="" disabled selected>[Select location]</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group"><label>Uncertainty:</label><input type="text" name="uncertainty"></div>
                <div class="form-group"><label>Uncertainty Unit:</label>
                    <select name="uncertainty_unit">
                        <option value="" disabled selected>[Select uncertainty unit]</option>
                    </select>
                </div>
                <div class="form-group"><label>IO:</label>
                    <select name="io">
                        <option value="" disabled selected>[Select io]</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group  custom-checkbox">
                    <label><input type="checkbox" name="master_equipment"> Master Equipment</label>
                </div>
                <div class="form-group"><label>Status:</label>
                    <select name="equipment_status">
                        <option value="" disabled selected>Select Status</option>
                        <option value="operational">Operational</option>
                        <option value="under-maintenance">Under Maintenance</option>
                        <option value="retired">Retired</option>
                    </select>
                </div>
                <div class="form-group"><label>Project:</label>
                    <select name="project_id">
                        <option value="" disabled selected>Select Project</option>
                    </select>
                </div>
            </div>
        </fieldset>

        <!-------------------------- Sub Table (Uncertainty + Date) -------------------------->
        <fieldset class="form-section-fieldset">
            <legend>Uncertainty Records</legend>
            <table class="table table-bordered" id="uncertaintyTable">
                <thead>
                    <tr>
                        <th>Uncertainty</th>
                        <th>Date</th>
                        <th style="width:80px;">Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <button type="button" class="btn btn-sm btn-primary" onclick="addUncertaintyRow()">+ Add Record</button>
        </fieldset>
    </div>
</form>


            <!-------------------------- Calibration Tab -------------------------->
<div id="calibrationTab" class="form-tab-content" style="display: none;">
    <fieldset class="form-section-fieldset">
        <legend>Calibration Details</legend>

        <!-- Row 1: Basic Info -->
        <div class="form-row">
            <div class="form-group">
                <label>Calib. Method:</label>
                <input type="text" name="calib_method">
            </div>
            <div class="form-group">
                <label>Calib. Procedure No:</label>
                <input type="text" name="calib_procedure_no">
            </div>
            <div class="form-group">
                <label>Last Calib. Date:</label>
                <input type="date" name="last_calib_date">
            </div>
        </div>

        <!-- Row 2: Dates -->
        <div class="form-row">
            <div class="form-group">
                <label>Scheduled:</label>
                <input type="date" name="scheduled">
            </div>
            <div class="form-group">
                <label>Next Calib. Date:</label>
                <input type="date" name="next_calib_date">
            </div>
             <div class="form-group custom-checkbox">
                <input type="checkbox" id="reminder" name="reminder">
                <label for="reminder">Reminder</label>
            </div>

        </div>

        <!-- Row 3: Checkboxes -->
        <div class="form-row checkboxes-row">
             <div class="form-group custom-checkbox">
                <input type="checkbox" id="scheduled" name="scheduled">
                <label for="scheduled">Scheduled</label>
            </div>
            <div class="form-group custom-checkbox">
                <input type="checkbox" id="has_next_calibration" name="has_next_calibration">
                <label for="has_next_calibration">Has Next Calibration</label>
            </div>

        </div>
 <div class="form-row checkboxes-row">
            <div class="form-group custom-checkbox">
                <input type="checkbox" id="only_one" name="only_one">
                <label for="only_one">Only One</label>
            </div>
            <div class="form-group custom-checkbox">
                <input type="checkbox" id="calibrated_externally" name="calibrated_externally">
                <label for="calibrated_externally">Calibrated Externally</label>
            </div>
        </div>

        <!-- Row 4: Provider / Internally By -->
        <div class="form-row">
            <div class="form-group">
                <label>Provider:</label>
                <input type="text" name="provider">
            </div>
            <div class="form-group">
                <label>Internally By:</label>
                <input type="text" name="internally_by">
            </div>
        </div>

        <!-- Row 5: Last Certificate / Calibration Status -->
        <div class="form-row">
            <div class="form-group">
                <label>Last Certificate:</label>
                <input type="text" name="last_certificate">
            </div>
            <div class="form-group">
                <label>Calibration Status:</label>
                <select name="calibration_status">
                    <option value="completed">Completed</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="overdue">Overdue</option>
                </select>
            </div>
        </div>

    </fieldset>
</div>
<!-------------------------- End Calibration Tab Fields -------------------------->
 <!-------------------------- Maintenance Tab -------------------------->
      <div id="maintenanceTab" class="form-tab-content" style="display: none;">
    <fieldset class="form-section-fieldset">
        <legend>Maintenance Details</legend>

        <!-- Row 1: Dates -->
        <div class="form-row">
            <div class="form-group">
                <label>Last Maint. Date:</label>
                <input type="date" name="last_maint_date">
            </div>
            <div class="form-group">
                <label>Scheduled Date:</label>
                <input type="date" name="scheduled_date">
            </div>
            <div class="form-group">
                <label>Next Maint. Date:</label>
                <input type="date" name="next_maint_date">
            </div>
        </div>

        <!-- Row 2: Checkboxes -->
        <div class="form-row checkboxes-row">
            <div class="form-group custom-checkbox">
                <input type="checkbox" id="scheduled_maint" name="scheduled_maint">
                <label for="scheduled_maint">Scheduled</label>
            </div>
            <div class="form-group custom-checkbox">
                <input type="checkbox" id="has_next_maint" name="has_next_maint">
                <label for="has_next_maint">Has Next Maintenance</label>
            </div>
            <div class="form-group custom-checkbox">
                <input type="checkbox" id="reminder_maint" name="reminder_maint">
                <label for="reminder_maint">Reminder</label>
            </div>
            <div class="form-group custom-checkbox">
                <input type="checkbox" id="maint_externally" name="maint_externally">
                <label for="maint_externally">Maint Externally</label>
            </div>
              <div class="form-group custom-checkbox">
                <input type="checkbox" id="only_one" name="only_one">
                <label for="only_one">Only One</label>
            </div>
        </div>

        <!-- Row 3: Occurrence -->
        <div class="form-row">
            <div class="form-group">
                <label>Occurrence:</label>
                <input type="text" name="occurrence" placeholder="e.g. Monthly, Quarterly">
            </div>
        </div>

        <!-- Row 4: Provider / Internally By -->
        <div class="form-row">
            <div class="form-group">
                <label>Provider:</label>
                <input type="text" name="maint_provider">
            </div>
            <div class="form-group">
                <label>Internally By:</label>
                <input type="text" name="maint_internally_by">
            </div>
        </div>

        <!-- Row 5: Maintenance Status -->
        <div class="form-row">
            <div class="form-group">
                <label>Maint. Status:</label>
                <select name="maint_status">
                    <option value="completed">Completed</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="overdue">Overdue</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
        </div>
    </fieldset>
</div>



            <!-------------------------- Modal Buttons -------------------------->
            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="closeEquipmentModal()"><i class="fas fa-times"></i> Close</button>
                <button type="button" class="btn-success" onclick="saveEquipment(true)"><i class="fas fa-save"></i> Save & Close</button>
                <button type="button" class="btn-primary" onclick="saveEquipment(false)"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
<!-------------------------- End Add New Equipment Modal -------------------------->



<!-------------------------- Edit Equipment Modal -------------------------->
<div id="editEquipmentModal" class="modal">
    <div class="modal-content new-project-modal-design">
        <span class="close-btn" onclick="closeEditEquipmentModal()"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Edit Equipment ⚙️</h2>

        <div class="tab-buttons">
            <button id="edit-equipment-details-btn" onclick="switchTab('edit-equipment-details')" class="active">
                <i class="fas fa-cogs"></i> Equipment Details
            </button>
            <button id="edit-calibration-btn" onclick="switchTab('edit-calibration')">
                <i class="fas fa-ruler"></i> Calibrations
            </button>
            <button id="edit-maintenance-btn" onclick="switchTab('edit-maintenance')">
                <i class="fas fa-tools"></i> Maintenance
            </button>
        </div>

        <form id="editEquipmentForm">
            <!-------------------------- Equipment Details Tab -------------------------->
            <div id="edit-equipment-detailsTab" class="form-tab-content active">
                <fieldset class="form-section-fieldset">
                    <legend>Equipment Information</legend>
                    <input type="hidden" id="editEquipmentId">

                    <div class="form-row">
                        <div class="form-group"><label>Id:</label><input type="text" name="id"></div>
                        <div class="form-group"><label>Alternative Id:</label><input type="text" name="alternative_id"></div>
                        <div class="form-group"><label>Legacy Id:</label><input type="text" name="legacy_id"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group"><label>Description:</label><input type="text" name="description"></div>
                        <div class="form-group"><label>Serial #:</label><input type="text" name="serial_number"></div>
                        <div class="form-group"><label>Asset Tag:</label><input type="text" name="asset_tag"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group"><label>Size:</label><input type="text" name="size"></div>
                        <div class="form-group"><label>Type:</label><input type="text" name="type"></div>
                        <div class="form-group"><label>Make:</label><input type="text" name="make"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group"><label>Tolerance Basis:</label><input type="text" name="tolerance_basis"></div>
                        <div class="form-group"><label>Tolerance:</label><input type="text" name="tolerance"></div>
                        <div class="form-group"><label>Range/Capacity:</label><input type="text" name="range_capacity"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group"><label>Range Unit:</label><input type="text" name="range_unit"></div>
                        <div class="form-group"><label>Resolution:</label><input type="text" name="resolution"></div>
                        <div class="form-group"><label>Resolution Unit:</label><input type="text" name="resolution_unit"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group"><label>Traceability:</label><input type="text" name="traceability"></div>
                        <div class="form-group"><label>Display Type:</label><input type="text" name="display_type"></div>
                        <div class="form-group"><label>Model:</label><input type="text" name="model"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Manufacturer:</label>
                            <select name="manufacturer_id">
                                <option value="" disabled selected>[Select Manufacturer]</option>

                            </select>
                        </div>
                        <div class="form-group"><label>Department:</label><input type="text" name="department"></div>
                        <div class="form-group"><label>Custodian:</label><input type="text" name="custodian"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group"><label>Location:</label><input type="text" name="location"></div>
                        <div class="form-group"><label>Uncertainty:</label><input type="text" name="uncertainty"></div>
                        <div class="form-group"><label>Uncertainty Unit:</label><input type="text" name="uncertainty_unit"></div>
                    </div>

                    <div class="form-row">
                        <div class="form-group"><label>IO:</label><input type="text" name="io"></div>
                        <div class="form-group checkbox-group">
                            <label><input type="checkbox" name="master_equipment"> Master Equipment</label>
                        </div>
                    </div>
                </fieldset>

                <!-------------------------- Sub Table (Uncertainty + Date) -------------------------->
                <fieldset class="form-section-fieldset">
                    <legend>Uncertainty Records</legend>
                    <table class="table table-bordered" id="editUncertaintyTable">
                        <thead>
                            <tr>
                                <th>Uncertainty</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-primary" onclick="addEditUncertaintyRow()">+ Add Record</button>
                </fieldset>
            </div>

            <!-------------------------- Calibration Tab -------------------------->
            <div id="edit-calibrationTab" class="form-tab-content" style="display: none;">
                <fieldset class="form-section-fieldset">
                    <legend>Calibration Records</legend>
                    <p>Calibration fields will be added here.</p>
                </fieldset>
            </div>

            <!-------------------------- Maintenance Tab -------------------------->
            <div id="edit-maintenanceTab" class="form-tab-content" style="display: none;">
                <fieldset class="form-section-fieldset">
                    <legend>Maintenance Records</legend>
                    <p>Maintenance fields will be added here.</p>
                </fieldset>
            </div>

            <!-------------------------- Modal Buttons -------------------------->
            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="closeEditEquipmentModal()"><i class="fas fa-times"></i> Close</button>
                <button type="button" class="btn-success" onclick="saveEditEquipment(true)"><i class="fas fa-save"></i> Update & Close</button>
                <button type="button" class="btn-primary" onclick="saveEditEquipment(false)"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
<!-------------------------- End Edit Equipment Modal -------------------------->



<style>
/* Flex row for checkboxes */

.checkboxes-row {
    display: flex;
    gap: 30px; /* المسافة بين كل checkbox */
    align-items: center;
    margin-bottom: 15px;
}

.custom-checkbox {
    display: flex;
    align-items: center;
}

.custom-checkbox input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin-right: 8px; /* المسافة بين المربع والنص */
    accent-color: #007bff;
    cursor: pointer;
}

.custom-checkbox label {
    cursor: pointer;
    font-weight: 500;
}

.equipment-form .form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.equipment-form .form-group {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.equipment-form .form-group label {
    font-weight: 600;
    margin-bottom: 5px;
}

</style>
