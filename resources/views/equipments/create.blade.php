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
         <form id="saveEquipment" class="equipment-form">
    @csrf

    <!-------------------------- Equipment Details Tab -------------------------->
    <div id="equipment-detailsTab" class="form-tab-content active">

        <!-- Equipment Information -->
        <fieldset class="form-section-fieldset">
            <legend>Equipment Information</legend>

            <!-- Hidden ID -->
            <input type="hidden" id="equipmentIdHidden" name="id">

            <div class="form-row">
                <div class="form-group">
                    <label>Id:</label>
                    <input type="text" id="equipment_reference" readonly placeholder="Automatically Generated">
                </div>
                <div class="form-group">
                    <label>Alternative Id:</label>
                    <input type="text" name="alternative_id">
                </div>
                <div class="form-group">
                    <label>Legacy Id:</label>
                    <input type="text" name="legacy_id">
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <input type="text" name="description" required>
                </div>
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
                    <select name="make" id="make">
                        <option value="" selected disabled>Select Make</option>
                         <option value="operational">Operational</option>

                    </select>
                </div>
                <div class="form-group">
                    <label>Model:</label>
                    <select name="model" id="model">
                        <option value="" selected disabled>Select Model</option>
                    <option value="operational">Operational</option>

                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tolerance Basis:</label>
                    <select name="tolerance_basis" id="tolerance_basis">
                        <option value="" selected disabled>Select Tolerance Basis</option>
                                                <option value="operational">Operational</option>

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
                    <select name="resolution_unit" id="resolution_unit">
                        <option value="" selected disabled>Select Resolution Unit</option>
                      <option value="operational">Operational</option>

                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group"><label>Traceability:</label><input type="text" name="traceability"></div>
                <div class="form-group">
                    <label>Display Type:</label>
                    <select name="display_type" id="display_type">
                        <option value="" selected disabled>Select Display Type</option>
                        <option value="operational">Operational</option>

                    </select>
                </div>
                <div class="form-group">
                    <label>Manufacturer:</label>
                    <select name="manufacturer_id" id="manufacturer_id">
                        <option value="" selected disabled>Select Manufacturer</option>
                        <option value="operational">Operational</option>

                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Department:</label>
                    <select name="department" id="department">
                        <option value="" selected disabled>Select Department</option>
                      <option value="operational">Operational</option>

                    </select>
                </div>
                <div class="form-group">
                    <label>Custodian:</label>
                    <select name="custodian" id="custodian">
                        <option value="" selected disabled>Select Custodian</option>
                                                <option value="operational">Operational</option>

                    </select>
                </div>
                <div class="form-group">
                    <label>Location:</label>
                    <select name="location" id="location">
                        <option value="" selected disabled>Select Location</option>
                                                <option value="operational">Operational</option>

                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group"><label>Uncertainty:</label><input type="text" name="uncertainty"></div>
                <div class="form-group">
                    <label>Uncertainty Unit:</label>
                    <select name="uncertainty_unit" id="uncertainty_unit">
                        <option value="" selected disabled>Select Uncertainty Unit</option>
                                                <option value="operational">Operational</option>

                    </select>
                </div>
                <div class="form-group">
                    <label>IO:</label>
                    <select name="io" id="io">
                        <option value="" selected disabled>Select IO</option>
                                                <option value="operational">Operational</option>

                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group custom-checkbox">
                    <label>
                        <input type="checkbox" name="master_equipment" value="1"> Master Equipment
                    </label>
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select name="equipment_status">
                        <option value="" disabled selected>Select Status</option>
                        <option value="operational">Operational</option>
                        <option value="under-maintenance">Under Maintenance</option>
                        <option value="retired">Retired</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Project:</label>
                    <select name="project_id" class="form-control">
                        <option value="" disabled selected>-- اختر مشروع --</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </fieldset>

        <!-- Uncertainty Records -->
        <fieldset class="form-section-fieldset">
            <legend>Uncertainty Records</legend>
            <div class="form-row">
                <div class="form-group">
                    <label for="uncertainty">Uncertainty:</label>
                    <input type="text" id="value" name="value" placeholder="±2%, ±0.5 MPa">
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" id="date_recorded" name="date_recorded" value="{{ date('Y-m-d') }}">
                </div>
            </div>
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


<!-- Edit Equipment Modal -->
<div id="editEquipmentModal" class="modal">
  <div class="modal-content new-project-modal-design">
    <span class="close-btn" onclick="closeEditEquipmentModal()"><i class="fas fa-times"></i></span>
    <h2 class="modal-title">Edit Equipment ⚙️</h2>

    <div class="tab-buttons">
        <button id="edit-equipment-details-btn" onclick="switchTabEdit('edit-equipment-details')" class="active">
            <i class="fas fa-cogs"></i> Equipment Details
        </button>
        <button id="edit-calibration-btn" onclick="switchTabEdit('edit-calibration')">
            <i class="fas fa-ruler"></i> Calibrations
        </button>
        <button id="edit-maintenance-btn" onclick="switchTabEdit('edit-maintenance')">
            <i class="fas fa-tools"></i> Maintenance
        </button>
    </div>

    <form id="editEquipmentForm">
      @csrf
      @method('PUT')
      <input type="hidden" id="editEquipmentId" name="id">

      <!-- Equipment Details -->
      <fieldset class="form-section-fieldset">
        <legend>Equipment Information</legend>

        <div class="form-row">
          <div class="form-group">
            <label>Equipment Ref:</label>
            <input type="text" id="edit_equipment_reference" name="equipment_reference" readonly placeholder="Automatically Generated">
          </div>
          <div class="form-group">
            <label>Alternative Id:</label>
            <input type="text" id="edit_alternative_id" name="alternative_id">
          </div>
          <div class="form-group">
            <label>Legacy Id:</label>
            <input type="text" id="edit_legacy_id" name="legacy_id">
          </div>
          <div class="form-group">
            <label>Description:</label>
            <input type="text" id="edit_description" name="description" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group"><label>Serial #:</label><input type="text" id="edit_serial_number" name="serial_number"></div>
          <div class="form-group"><label>Asset Tag:</label><input type="text" id="edit_asset_tag" name="asset_tag"></div>
          <div class="form-group"><label>Size:</label><input type="text" id="edit_size" name="size"></div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Type:</label>
            <select id="edit_type" name="type">
              <option value="" disabled>Select type</option>
              <option value="operational">Operational</option>
              <option value="under-maintenance">Under Maintenance</option>
              <option value="retired">Retired</option>
            </select>
          </div>
          <div class="form-group"><label>Make:</label><select id="edit_make" name="make"></select></div>
          <div class="form-group"><label>Model:</label><select id="edit_model" name="model"></select></div>
        </div>

        <div class="form-row">
          <div class="form-group"><label>Tolerance Basis:</label><select id="edit_tolerance_basis" name="tolerance_basis"></select></div>
          <div class="form-group"><label>Tolerance:</label><input type="text" id="edit_tolerance" name="tolerance"></div>
          <div class="form-group"><label>Range/Capacity:</label><input type="text" id="edit_range_capacity" name="range_capacity"></div>
        </div>

        <div class="form-row">
          <div class="form-group"><label>Range Unit:</label><input type="text" id="edit_range_unit" name="range_unit"></div>
          <div class="form-group"><label>Resolution:</label><input type="text" id="edit_resolution" name="resolution"></div>
          <div class="form-group"><label>Resolution Unit:</label><select id="edit_resolution_unit" name="resolution_unit"></select></div>
        </div>

        <div class="form-row">
          <div class="form-group"><label>Traceability:</label><input type="text" id="edit_traceability" name="traceability"></div>
          <div class="form-group"><label>Display Type:</label><select id="edit_display_type" name="display_type"></select></div>
          <div class="form-group"><label>Manufacturer:</label><select id="edit_manufacturer_id" name="manufacturer_id"></select></div>
        </div>

        <div class="form-row">
          <div class="form-group"><label>Department:</label><select id="edit_department" name="department"></select></div>
          <div class="form-group"><label>Custodian:</label><select id="edit_custodian" name="custodian"></select></div>
          <div class="form-group"><label>Location:</label><select id="edit_location" name="location"></select></div>
        </div>

        <div class="form-row">
          <div class="form-group"><label>Uncertainty:</label><input type="text" id="edit_uncertainty" name="uncertainty"></div>
          <div class="form-group"><label>Uncertainty Unit:</label><select id="edit_uncertainty_unit" name="uncertainty_unit"></select></div>
          <div class="form-group"><label>IO:</label><select id="edit_io" name="io"></select></div>
        </div>

        <div class="form-row">
          <div class="form-group custom-checkbox"><label><input type="checkbox" id="edit_master_equipment" name="master_equipment" value="1"> Master Equipment</label></div>
          <div class="form-group"><label>Status:</label><select id="edit_equipment_status" name="equipment_status"></select></div>
          <div class="form-group"><label>Project:</label><select id="edit_project_id" name="project_id">@foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->name }}</option>@endforeach</select></div>
        </div>
      </fieldset>

      <!-- Uncertainty Records -->
      <fieldset class="form-section-fieldset">
        <legend>Uncertainty Records</legend>
        <div class="form-row">
          <div class="form-group"><input type="text" id="editUncertainty" placeholder="±2%, ±0.5 MPa"></div>
          <div class="form-group"><input type="date" id="editUncertaintyDate" value="{{ date('Y-m-d') }}"></div>
          <div class="form-group"><button type="button" onclick="addUncertainty($('#editEquipmentId').val(), 'edit')">Add Uncertainty</button></div>
        </div>
        <table id="editUncertaintyHistory" class="contacts-table display responsive nowrap">
          <thead><tr><th>Date</th><th>Uncertainty</th></tr></thead>
          <tbody></tbody>
        </table>
      </fieldset>

      <!-- Calibration Tab -->
      <div id="edit-calibrationTab" class="form-tab-content" style="display:none;">
        <fieldset class="form-section-fieldset">
          <legend>Calibration Details</legend>
          <div class="form-row">
            <div class="form-group"><label>Calib. Method:</label><input type="text" id="edit_calib_method" name="calib_method"></div>
            <div class="form-group"><label>Calib. Procedure No:</label><input type="text" id="edit_calib_procedure_no" name="calib_procedure_no"></div>
            <div class="form-group"><label>Last Calib. Date:</label><input type="date" id="edit_last_calib_date" name="last_calib_date"></div>
          </div>
          <div class="form-row">
            <div class="form-group"><label>Scheduled:</label><input type="date" id="edit_scheduled" name="scheduled"></div>
            <div class="form-group"><label>Next Calib. Date:</label><input type="date" id="edit_next_calib_date" name="next_calib_date"></div>
            <div class="form-group custom-checkbox"><input type="checkbox" id="edit_reminder" name="reminder"> Reminder</div>
          </div>
          <div class="form-row checkboxes-row">
            <div class="form-group custom-checkbox"><input type="checkbox" id="edit_has_next_calibration" name="has_next_calibration"> Has Next Calibration</div>
            <div class="form-group custom-checkbox"><input type="checkbox" id="edit_only_one" name="only_one"> Only One</div>
            <div class="form-group custom-checkbox"><input type="checkbox" id="edit_calibrated_externally" name="calibrated_externally"> Calibrated Externally</div>
          </div>
        </fieldset>
      </div>

      <!-- Maintenance Tab -->
      <div id="edit-maintenanceTab" class="form-tab-content" style="display:none;">
        <fieldset class="form-section-fieldset">
          <legend>Maintenance Details</legend>
          <div class="form-row">
            <div class="form-group"><label>Last Maint. Date:</label><input type="date" id="edit_last_maint_date" name="last_maint_date"></div>
            <div class="form-group"><label>Scheduled Date:</label><input type="date" id="edit_scheduled_date" name="scheduled_date"></div>
            <div class="form-group"><label>Next Maint. Date:</label><input type="date" id="edit_next_maint_date" name="next_maint_date"></div>
          </div>
          <div class="form-row checkboxes-row">
            <div class="form-group custom-checkbox"><input type="checkbox" id="edit_scheduled_maint" name="scheduled_maint"> Scheduled</div>
            <div class="form-group custom-checkbox"><input type="checkbox" id="edit_has_next_maint" name="has_next_maint"> Has Next Maintenance</div>
            <div class="form-group custom-checkbox"><input type="checkbox" id="edit_reminder_maint" name="reminder_maint"> Reminder</div>
            <div class="form-group custom-checkbox"><input type="checkbox" id="edit_maint_externally" name="maint_externally"> Maint Externally</div>
            <div class="form-group custom-checkbox"><input type="checkbox" id="edit_only_one_maint" name="only_one_maint"> Only One</div>
          </div>
          <div class="form-row">
            <div class="form-group"><label>Occurrence:</label><input type="text" id="edit_occurrence" name="occurrence"></div>
            <div class="form-group"><label>Provider:</label><input type="text" id="edit_maint_provider" name="maint_provider"></div>
            <div class="form-group"><label>Internally By:</label><input type="text" id="edit_maint_internally_by" name="maint_internally_by"></div>
            <div class="form-group"><label>Maint. Status:</label><select id="edit_maint_status" name="maint_status"><option value="completed">Completed</option><option value="scheduled">Scheduled</option><option value="overdue">Overdue</option><option value="pending">Pending</option></select></div>
          </div>
        </fieldset>
      </div>

      <!-- Modal Buttons -->
      <div class="form-buttons modal-bottom-buttons">
        <button type="button" class="btn-primary" onclick="closeEditEquipmentModal()">Close</button>
        <button type="button" class="btn-success" onclick="saveEditEquipment(true)">Save & Close</button>
        <button type="button" class="btn-primary" onclick="saveEditEquipment(false)">Save</button>
      </div>
    </form>

  </div>
</div>




<!-------------------------- End Edit Equipment Modal -------------------------->



<style>

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


