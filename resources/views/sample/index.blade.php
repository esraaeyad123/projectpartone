@extends('layouts.app')
@section('title', __('Deliveries'))
@section('content')


<div id="main-tab-headers" class="tab-header" style="display: none;">

    <span id="registration-tab" class="tab active-tab" onclick="switchMainTab('registration')">
        <i class="fas fa-clipboard-list" style="color: #007bff;"></i>
        Registration
        <span class="close-tab-btn" onclick="closeRegistrationTab(event)">&times;</span>
    </span>

    <span id="preview-tab-header" class="tab" style="display: none;" onclick="switchMainTab('preview')">
        <i class="fas fa-eye" style="color: #007bff;"></i>
        <span id="preview-tab-title">Preview: [Order No.]</span>
        <span class="close-tab-btn" onclick="closePreviewTab(event)">&times;</span>
    </span>

</div>

    <div id="registration-category-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content small-modal">
            <div class="modal-header">
                <h3>Registration: Enter Category</h3>
                <span class="close-btn" onclick="closeModal('registration-category-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="category-select">Category:</label>
                    <select id="category-select" class="form-control" onchange="checkCategorySelection(this.value)">
                        <option value="">Select a category</option>
                        <option value="crushing-testing">crushing testing CT</option>
                        <option value="other">Other Testing OT</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div id="registration-content" class="registration-form-container form-disabled" style="display: none;">
        <div class="sub-tabs">
            <span class="sub-tab active-sub-tab" onclick="showSubTab('compressive-strength')">Comprehensive Strength Test</span>
            <span class="sub-tab" >Other Tests</span>
        </div>

        <div id="compressive-strength" class="sub-tab-content active-sub-tab-content">
            <form id="concrete-crushing-form">
                <div class="form-section-title">Order Information</div>
                <div class="form-grid-4-cols">
                    <div class="form-group-with-icons">
                        <label for="order-no">Order No.:</label>
                        <input type="text" id="order-no" class="form-control" placeholder="[EditValue is null]" disabled>
                    </div>
                    <div class="form-group-with-icons">
                        <label for="sample-no">Sample No.:</label>
                        <input type="text" id="sample-no" class="form-control" placeholder="[EditValue is null]" disabled>
                    </div>
                    <div class="form-group-with-icons">
                        <label for="project-code">Project Code</label>
                        <div class="input-with-icon custom-autocomplete-container">
                            <input type="text" id="project-code" class="form-control" placeholder="[EditValue is null]" disabled>
                            <div class="project-code-icons">
                                <i class="fas fa-search icon-btn" data-onclick="openProjectCodeDropdown()" title="Search Project Code"></i>
                                <i class="fas fa-check icon-btn-check" data-onclick="confirmProjectCode()" title="Confirm Project Code"></i>
                                <i class="fas fa-clipboard-check icon-btn-confirmation" data-onclick="openConfirmationSelectionModal()" title="Select from Confirmation"></i>
                            </div>
                            <div class="custom-dropdown-project-code" id="project-code-dropdown" style="display: none;">
                                <div class="dropdown-header">
                                    <div class="header-column" style="width: 25%;">Project Code</div>
                                    <div class="header-column" style="width: 25%;">Project No.</div>
                                    <div class="header-column" style="width: 25%;">Project Name</div>
                                    <div class="header-column" style="width: 25%;">Customer Name</div>
                                </div>
                                <ul class="dropdown-options-list" id="project-code-options">
                                    <li data-onclick="selectProjectCode('AAM-KH-001', 'KH-001', 'مشروع خالد', 'خالد')">
                                        <div class="option-column" style="width: 25%;">AAM-KH-001</div>
                                        <div class="option-column" style="width: 25%;">KH-001</div>
                                        <div class="option-column" style="width: 25%;">مشروع خالد</div>
                                        <div class="option-column" style="width: 25%;">خالد</div>
                                    </li>
                                    <li data-onclick="selectProjectCode('AAM-MH-002', 'MH-002', 'مشروع محمد', 'محمد')">
                                        <div class="option-column" style="width: 25%;">AAM-MH-002</div>
                                        <div class="option-column" style="width: 25%;">MH-002</div>
                                        <div class="option-column" style="width: 25%;">مشروع محمد</div>
                                        <div class="option-column" style="width: 25%;">محمد</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-group-with-icons">
                        <label for="lab">Lab:</label>
                        <div class="input-with-icon custom-autocomplete-container">
                            <input type="text" id="lab" class="form-control" placeholder="[EditValue is null]" disabled>
                            <i class="fas fa-caret-down icon-btn" data-onclick="openLabDropdown()"></i>
                            <div class="custom-dropdown-lab" id="lab-dropdown" style="display: none;">
                                <div class="dropdown-header">
                                    <div class="header-column" style="width: 25%;">Location Name</div>
                                    <div class="header-column" style="width: 25%;">Department</div>
                                </div>
                                <ul class="dropdown-options-list" id="lab-options">
                                    <li data-onclick="selectLab('Default Location', 'Materials Testing')">
                                        <div class="option-column" style="width: 25%;">Default Location</div>
                                        <div class="option-column" style="width: 25%;">Materials Testing</div>
                                    </li>
                                    <li data-onclick="selectLab('Concrete Section', 'Materials Testing')">
                                        <div class="option-column" style="width: 25%;">Concrete Section</div>
                                        <div class="option-column" style="width: 25%;">Materials Testing</div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

              <div class="form-grid-4-cols">
    <div class="form-group-with-icons">
        <label for="order-date">Order Date.:</label>
        <div class="input-with-icon">
            <input type="text" id="order-date" class="form-control" disabled>
        </div>
    </div>

                    <div class="form-group-with-icons span-2-cols">
                        <label for="project">Project:</label>
                        <input type="text" id="project" class="form-control" disabled>
                    </div>
                    <div class="form-group-with-icons">
                        <label for="s-contractor">S. Contractor:</label>
                        <input type="text" id="s-contractor" class="form-control" placeholder="[EditValue is null]" disabled>
                    </div>
                </div>

                <div class="form-grid-4-cols">
                    <div class="form-group-with-icons">
                        <label for="project-no">Project No.:</label>
                        <input type="text" id="project-no" class="form-control" disabled>
                    </div>
                    <div class="form-group-with-icons span-2-cols">
                        <label for="customer">Customer:</label>
                        <input type="text" id="customer" class="form-control" disabled>
                    </div>
                    <div class="form-group-with-icons">
        <label for="payment-terms">Payment Terms:</label>
        <div class="input-with-icon custom-autocomplete-container">
            <input type="text" id="payment-terms" class="form-control" placeholder="[EditValue is null]" disabled>
            <i class="fas fa-caret-down icon-btn" data-onclick="openPaymentTermsDropdown()"></i>
            <div class="custom-dropdown-payment-terms" id="payment-terms-dropdown" style="display: none;">
                <div class="dropdown-header">Payment Terms</div>
                <ul class="dropdown-options-list">
                    <li onclick="selectPaymentTerms('CASH')">CASH</li>
                    <li onclick="selectPaymentTerms('ADVANCE')">ADVANCE</li>
                    <li onclick="selectPaymentTerms('30 DAYS')">30 DAYS</li>
                    <li onclick="selectPaymentTerms('10 DAYS')">10 DAYS</li>
                    <li onclick="selectPaymentTerms('7 DAYS')">7 DAYS</li>
                </ul>
            </div>
        </div>
    </div>
</div>

                </div>

                <div class="form-grid-4-cols">
                    <div class="form-group-with-icons">
                        <label for="pouring-ticket">Pouring Ticket:</label>
                        <div class="input-with-icon pouring-ticket-icons">
                            <input type="text" id="pouring-ticket" class="form-control" disabled>
                            <i class="fas fa-check icon-btn-check" data-onclick="validatePouringTicket()"></i>
                            <i class="fas fa-arrow-right icon-btn-arrow" data-onclick="fetchPouringTicketDetails()"></i>
                        </div>
                    </div>
                   <div class="form-group-with-icons">
    <label for="sample-type">Sample Type:</label>
    <div class="input-with-icon custom-autocomplete-container">
        <input type="text" id="sample-type" class="form-control" placeholder="[EditValue is null]" disabled>
        <i class="fas fa-caret-down icon-btn" onclick="openSampleTypeDropdown()"></i>
        <div class="custom-dropdown-sample-type" id="sample-type-dropdown" style="display: none;">
            <div class="dropdown-header">Sample Type</div>
            <ul class="dropdown-options-list">
                <li onclick="selectSampleType('CUBE 100')">CUBE 100</li>
                <li onclick="selectSampleType('CUBE 150')">CUBE 150</li>
                <li onclick="selectSampleType('CYLINDER')">CYLINDER</li>
                <li onclick="selectSampleType('CONCRETE CORES')">CONCRETE CORES</li>
                <li onclick="selectSampleType('MORTAR')">MORTAR</li>
                <li onclick="selectSampleType('GROUT')">GROUT</li>
                <li onclick="selectSampleType('ITEM 7')">ITEM 7</li>
                <li onclick="selectSampleType('ITEM 8')">ITEM 8</li>
                <li onclick="selectSampleType('ITEM 9')">ITEM 9</li>
                <li onclick="selectSampleType('ITEM 10')">ITEM 10</li>
            </ul>
        </div>
    </div>
</div>

                    <div class="form-group-with-icons">
                        <label for="confirmation">Confirmation:</label>
                        <div class="input-with-icon custom-autocomplete-container">
                            <input type="text" id="confirmation" class="form-control" placeholder="[EditValue is null]" disabled>

                        </div>
                    </div>
                </div>

                <div class="form-section-title">Sample Information</div>
                <div class="form-grid-3-cols">

                    <div class="form-group">
                        <label for="sample-source">Sample Source:</label>
                        <div class="input-with-icon">
                            <input type="text" id="sample-source" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openSampleSourceDropdown()"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sample-description">Sample Description:</label>
                        <div class="input-with-icon">
                            <input type="text" id="sample-description" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openSampleDescriptionDropdown()"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="rfi-wir">RFI/WIR:</label>
                        <input type="text" id="rfi-wir" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label for="structure-ref">Structure Ref.:</label>
                        <input type="text" id="structure-ref" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="sampling-method">Sampling Method:</label>
                        <div class="input-with-icon">
                            <input type="text" id="sampling-method" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openSamplingMethodDropdown()"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="received-at">Received :</label>
                        <input type="datetime-local" id="received-at" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label for="total-received">Total Received:</label>
                        <input type="number" id="total-received" class="form-control" value="0" disabled>
                    </div>
                    <div class="form-group">
                        <label for="spec-prop-by">Spec. Prop. By:</label>
                        <div class="input-with-icon">
                            <input type="text" id="spec-prop-by" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openSpecPropByDropdown()"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sampled-at">Sampled :</label>
                        <input type="datetime-local" id="sampled-at" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label for="received-by">Received By:</label>
                        <div class="input-with-icon">
                            <input type="text" id="received-by" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openReceivedByDropdown()"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sampled-by">Sampled By:</label>
                        <div class="input-with-icon">
                            <input type="text" id="sampled-by" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openSampledByDropdown()"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="casted-at">Casted :</label>
                        <input type="datetime-local" id="casted-at" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label for="samp-brought-by">Samp. Brght. By:</label>
                        <div class="input-with-icon">
                            <input type="text" id="samp-brought-by" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openSampBroughtByDropdown()"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comp-equipment">Comp. Equipment:</label>
                        <div class="input-with-icon">
                            <input type="text" id="comp-equipment" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openCompEquipmentDropdown()"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="witness">Witness:</label>
                        <input type="text" id="witness" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label for="mtd-of-comp">Mthd of Comp.:</label>
                        <div class="input-with-icon">
                            <input type="text" id="mtd-of-comp" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openMthdOfCompDropdown()"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cement-content">Cement Content:</label>
                        <input type="text" id="cement-content" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes:</label>
                        <input type="text" id="notes" class="form-control" disabled>
                    </div>

                    <div class="form-group">
                        <label for="cement-type">Cement Type:</label>
                        <div class="input-with-icon">
                            <input type="text" id="cement-type" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openCementTypeDropdown()"></i>
                        </div>
                    </div>
                    <div class="form-grid-3-cols">
    <div class="form-group">
        <label for="nominal-size">Nominal Size:</label>
        <div class="input-with-icon custom-autocomplete-container">
            <input type="text" id="nominal-size" class="form-control" placeholder="[EditValue is null]" disabled>
            <i class="fas fa-caret-down icon-btn" onclick="openNominalSizeDropdown()"></i>
            <div class="custom-dropdown-nominal-size" id="nominal-size-dropdown" style="display: none;">
                <div class="dropdown-header">Nominal Size</div>
                <ul class="dropdown-options-list">
                    <li onclick="selectNominalSize('4.75 mm')">4.75 mm</li>
                    <li onclick="selectNominalSize('9.5 mm')">9.5 mm</li>
                    <li onclick="selectNominalSize('12.5 mm')">12.5 mm</li>
                    <li onclick="selectNominalSize('19.0 mm')">19.0 mm</li>
                    <li onclick="selectNominalSize('25.0 mm')">25.0 mm</li>
                    <li onclick="selectNominalSize('37.5 mm')">37.5 mm</li>
                    <li onclick="selectNominalSize('50 mm')">50 mm</li>
                    <li onclick="selectNominalSize('75 mm')">75 mm</li>
                    <li onclick="selectNominalSize('100x200 mm')">100x200 mm</li>
                    <li onclick="selectNominalSize('150x300 mm')">150x300 mm</li>
                    <li onclick="selectNominalSize('100x100x100 mm')">100x100x100 mm</li>
                    <li onclick="selectNominalSize('150x150x150 mm')">150x150x150 mm</li>
                </ul>
            </div>
        </div>
    </div>
    </div>
                    <div class="form-group">
                        <label for="class">Class:</label>
                        <div class="input-with-icon">
                            <input type="text" id="class" class="form-control" disabled>
                            <i class="fas fa-caret-down icon-btn" onclick="openClassDropdown()"></i>
                        </div>
                    </div>
                </div>
              <div class="form-section-title">Test Information</div>
<div class="test-table-container">
    <table class="test-data-table">
        <thead>
            <tr>
                <th>Test Method</th>
                <th>R. Age</th>
                <th>A. Age</th>
                <th>Price</th>
                <th>QTY</th>
                <th>Scheduled @</th>
                <th>Rem.</th>
                <th>Test Location</th>
                <th>Report No.</th>
                <th>Rev.</th>
                <th>Items</th>
                <th><i class="fas fa-plus add-row-icon" onclick="addNewTestRow()"></i></th>
            </tr>
        </thead>
        <tbody id="test-table-body">
            </tbody>
    </table>
</div>

<div class="dropdown-container" id="test-method-dropdown">
    <div class="dropdown-item" data-value="ASTM C39/C39M-21">ASTM C39/C39M-21</div>
    <div class="dropdown-item" data-value="ASTM C128">ASTM C128</div>
    <div class="dropdown-item" data-value="ASTM D422">ASTM D422</div>
</div>

<div class="dropdown-container" id="test-location-dropdown">
    <div class="dropdown-item" data-value="CONCRETE LAB.">CONCRETE LAB.</div>
    <div class="dropdown-item" data-value="SOIL LAB.">SOIL LAB.</div>
    <div class="dropdown-item" data-value="FIELD.">FIELD.</div>
</div>

    <div class="form-footer-buttons" id="form-footer-buttons" style="display: none;">
    <div class="order-no-footer">
        <label for="order-number-footer">Order No.:</label>
        <div class="input-with-icon custom-autocomplete-container">
            <input type="text" id="order-number-footer" class="form-control footer-order-no-input" placeholder="[EditValue is null]">
            <i class="fas fa-caret-down icon-btn" onclick="toggleOrderNoDropdown()"></i>
            <div class="custom-dropdown-order-no" id="order-no-dropdown" style="display: none;">
                <ul class="dropdown-options-list">
                    <li onclick="selectOrderNo('ORDER-001')">ORDER-001</li>
                    <li onclick="selectOrderNo('ORDER-002')">ORDER-002</li>
                    <li onclick="selectOrderNo('ORDER-003')">ORDER-003</li>
                </ul>
            </div>
        </div>
    </div>


    <div id="confirmation-selection-modal" class="modal-overlay" style="display: none;">
        <div class="modal-content large-modal">
            <div class="modal-header">
                <h3>Select from Confirmation</h3>
                <span class="close-btn" onclick="closeModal('confirmation-selection-modal')">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-controls">
                    <input type="text" id="confirmation-search-input" class="form-control" placeholder="Search by C. Description or C. S/T ID...">
                    <button class="btn btn-primary" onclick="applySelectedConfirmation()">Select</button>
                    <button class="btn btn-secondary" onclick="closeModal('confirmation-selection-modal')">Cancel</button>
                </div>
                <div class="table-container">
                    <table class="confirmation-table">
                        <thead>
                            <tr>
                                <th style="width: 5%;"></th> <th style="width: 15%;">C. Description</th>
                                <th style="width: 5%;">Accounted</th>
                                <th style="width: 10%;">Conf. Date</th>
                                <th style="width: 10%;">C. S/T ID</th>
                                <th style="width: 10%;">Alt. Test ID</th>
                                <th style="width: 15%;">Service/Field Description</th>
                                <th style="width: 5%;">Passed QTY</th>
                                <th style="width: 5%;">Rem. QTY</th>
                                <th style="width: 10%;">C. Price</th>
                                <th style="width: 5%;">Schedule #</th>
                                <th style="width: 5%;">Expected</th>
                            </tr>
                        </thead>
                        <tbody id="confirmation-table-body">
                            <tr data-onclick="selectRow(this)">
                                <td><input type="checkbox" class="confirmation-checkbox"></td>
                                <td>Item 1 from Quote</td>
                                <td>Yes</td>
                                <td>2025-08-11</td>
                                <td>CS-001</td>
                                <td>ALT-001</td>
                                <td>Field Test 1</td>
                                <td>10</td>
                                <td>5</td>
                                <td>150.00</td>
                                <td>SCH-01</td>
                                <td>15</td>
                            </tr>
                            <tr data-onclick="selectRow(this)">
                                <td><input type="checkbox" class="confirmation-checkbox"></td>
                                <td>Item 2 from Quote</td>
                                <td>No</td>
                                <td>2025-08-12</td>
                                <td>CS-002</td>
                                <td>ALT-002</td>
                                <td>Field Test 2</td>
                                <td>8</td>
                                <td>2</td>
                                <td>200.50</td>
                                <td>SCH-02</td>
                                <td>10</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  <div class="action-dropdown-container">
    <button type="button" class="btn btn-secondary" id="actions-btn" disabled onclick="toggleActionDropdown(event)">
        <i class="fas fa-cogs"></i> Actions
    </button>
    <ul id="actionDropdown" class="action-dropdown-menu">
        <li onclick="handleAction('Preview Order')">
            <i class="fas fa-search-plus"></i> Preview Order...
        </li>
        <li onclick="handleAction('Preview Slip')">
            <i class="fas fa-file-alt"></i> Preview Slip...
        </li>
        <li onclick="handleAction('Print Barcodes')">
    <i class="fas fa-print"></i> Print Barcodes...
</li>
      <li onclick="handleAction('Print Specimen Barcode')">
    <i class="fas fa-barcode"></i> Print Specimen Barcode...
</li>
        <li onclick="handleAction('Preview Labsheets')">
            <i class="fas fa-clipboard-list"></i> Preview Labsheets...
        </li>
        <li onclick="handleAction('Edit Order (Materials Editor)')">
            <i class="fas fa-edit"></i> Edit Order (Materials Editor)
        </li>
        <li onclick="handleAction('Duplicate Order')">
            <i class="fas fa-copy"></i> Duplicate Order
        </li>
        <li onclick="handleAction('Revise Order')">
            <i class="fas fa-sync-alt"></i> Revise Order
        </li>
        <li onclick="handleAction('File Manager')">
            <i class="fas fa-folder"></i> File Manager
        </li>
        <li onclick="handleAction('Create New Customer')">
            <i class="fas fa-user-plus"></i> Create New Customer
        </li>
        <li onclick="handleAction('Apply Sample Preferences (Force)')">
            <i class="fas fa-cogs"></i> Apply Sample Preferences (Force)
        </li>
        <li onclick="handleAction('Apply Last Registered Sample (Force)')">
            <i class="fas fa-cogs"></i> Apply Last Registered Sample (Force)
        </li>
        <li onclick="handleAction('Create New Project')">
            <i class="fas fa-plus"></i> Create New Project
        </li>
        <li onclick="handleAction('Unbill')">
            <i class="fas fa-dollar-sign"></i> Unbill
        </li>
        <li onclick="handleAction('Delete Order')">
            <i class="fas fa-times"></i> Delete Order
        </li>
    </ul>
</div>
    <button class="btn btn-secondary" id="close-btn" onclick="closeRegistrationTab()">
        <i class="fas fa-times"></i> Close
    </button>
  <button class="btn btn-primary" id="new-btn" onclick="enableForm()">
    <i class="fas fa-plus"></i> New
</button>
   <button class="btn btn-success" id="save-close-btn" disabled onclick="saveRegistrationData(true)">
        <i class="fas fa-save"></i> Save & Close
    </button>
    <button class="btn btn-success" id="save-btn" disabled onclick="saveRegistrationData()">
        <i class="fas fa-save"></i> Save
    </button>

</div>

</div>

<div id="preview-content" style="display: none;">
  

</div>

<div id="preview-content-area" class="preview-container">
    </div>

<div id="items-generator-popover" class="mini-modal-items">

    <div class="modal-content-items">

        <div class="modal-header-items">
            <h5 class="modal-title">Items Details</h5> <button type="button" class="close-btn-items" onclick="closePopover('items-generator-popover')">
                &times;
            </button>
        </div>

        <div class="modal-body-items">

            <div class="input-controls-grid new-control-layout">

                <div class="control-group">
                    <label for="total-lab-input">Total Lab</label>
                    <input type="number" id="total-lab-input" class="form-control form-control-sm" value="0" min="0">
                </div>

                <div class="control-group">
                    <label for="total-input">Total</label>
                    <input type="number" id="total-input" class="form-control form-control-sm" value="1" min="1">
                </div>

                <div class="control-group action-button-group">
                    <button type="button" class="btn btn-success btn-sm btn-action-top" onclick="generateItems()">
                        <i class="fas fa-cogs"></i> Generate
                    </button>
                </div>

                <div class="control-group action-button-group">
                    <button type="button" class="btn btn-info btn-sm btn-action-top" onclick="applyTicket()">
                        <i class="fas fa-ticket-alt"></i> Apply Ticket
                    </button>
                </div>

                <div class="control-group action-button-group">
                    <button type="button" class="btn btn-danger btn-sm btn-action-top" onclick="clearTable()">
                        <i class="fas fa-trash-alt"></i> Clear
                    </button>
                </div>

                <div class="control-group action-button-group">
                    <button type="button" class="btn btn-primary btn-sm btn-action-top" onclick="acceptItems()">
                        <i class="fas fa-check"></i> Accept
                    </button>
                </div>

            </div>

            <div class="popover-table-container">
                <table class="table table-sm table-bordered generated-items-table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">#</th>
                            <th style="width: 12%;">Specimen ID</th>
                            <th style="width: 15%;">Specimen Code</th>
                            <th style="width: 15%;">Mix Detail Id</th>
                            <th style="width: 10%;">Specimen Code2</th>
                            <th style="width: 8%;">Specimen Description</th>
                            <th style="width: 15%;">Test Description</th>
                            <th style="width: 10%;">Quantity</th>
                            <th style="width: 5%;">Order No</th>
                            <th style="width: 5%;"></th>
                        </tr>
                    </thead>
                    <tbody id="generated-items-tbody">
                    </tbody>
                </table>
            </div>

        </div>
        <div class="modal-footer-items">
            <button class="btn btn-primary btn-sm" onclick="saveGeneratedItems()">Save</button>
            <button class="btn btn-sm btn-secondary" onclick="closePopover('items-generator-popover')">Cancel</button>
        </div>

    </div>
</div>

<div id="orderModal">
    <div class="modal-content">

        <div class="order-header-tabs">
           <span id="tab-order-header" class="tab active"
      onclick="switchOrderTab('order-header-content')">Order Header -  </span>


            <button class="close-btn" onclick="closeOrderModal()">&times;</button>
        </div>

        <div class="form-body-container">

            <div class="totals-sidebar">
                <div class="totals-section">
                    <h3>Totals</h3>

                    <h4>Entered Currency</h4>
                    <div class="total-row">
                        <label for="enteredCurrency">Currency:</label>
                        <select id="enteredCurrency" class="input-full-width">
                             <option value="SAR">SAR</option>
                             <option value="USD">USD</option>
                             <option value="EUR">EUR</option>
                        </select>
                    </div>

                    <div class="total-row">
                        <label>Total Lines:</label>
                        <span id="totalLinesEntered">0.00</span>
                    </div>
                    <div class="total-row">
                        <label>Discount Amount:</label>
                        <span id="discountAmtEntered">0.00</span>
                    </div>
                    <div class="total-row">
                        <label>Tax Amount:</label>
                        <span id="taxAmtEntered">0.00</span>
                    </div>
                    <div class="total-row grand-total">
                        <label>Grand Total:</label>
                        <span id="grandTotalEntered">0.00 SAR</span>
                    </div>

                    <h4>Local Currency</h4>
                    <div class="total-row">
                        <label for="localCurrency">Currency:</label>
                        <select id="localCurrency" class="input-full-width">
                             <option value="SAR">SAR</option>
                        </select>
                    </div>
                    <div class="total-row">
                        <label>Total Lines:</label>
                        <span id="totalLinesLocal">0.00</span>
                    </div>
                    <div class="total-row">
                        <label>Discount Amount:</label>
                        <span id="discountAmtLocal">0.00</span>
                    </div>
                    <div class="total-row">
                        <label>Tax Amount:</label>
                        <span id="taxAmtLocal">0.00</span>
                    </div>
                    <div class="total-row grand-total">
                        <label>Grand Total:</label>
                        <span id="grandTotalLocal">0.00 SAR</span>
                    </div>

                </div>
            </div>

            <div class="data-entry-panel">
                <form id="orderForm">

                    <fieldset class="form-section">
                        <legend>Order Information</legend>

                        <div class="form-row">
                            <label for="projectCode">Project Code:</label>
                            <input type="text" id="projectCode" value="AAM-P-4" readonly>
                            <label for="orderNo">Order No:</label>
                            <input type="text" id="orderNo" readonly>
                            <label for="orderDate">Order Date:</label>
                            <input type="date" id="orderDate">
                        </div>

                        <div class="form-row">
                            <label for="projectCategory">Project Category:</label>
                            <select id="projectCategory">
                                <option>General</option>
                                <option selected>Materials</option>
                            </select>
                            <label for="projectNo">Project No.:</label>
                            <input type="text" id="projectNo">
                            <label for="expReportDate">Exp. Report Date:</label>
                            <input type="date" id="expReportDate">
                        </div>

                        <div class="form-row">
                            <label for="project">Project:</label>
                            <input type="text" id="project" readonly>
                            <label for="laboratory">Laboratory:</label>
                            <select id="laboratory">
                                <option>Lab 1</option>
                                <option>Lab 2</option>
                                <option>Lab 3</option>
                            </select>
                            <label for="projectDetails">Project Details:</label>
                            <input type="text" id="projectDetails">
                        </div>

                        <div class="form-row">
                            <label for="rfiWtr">RFI/WTR:</label>
                            <select id="rfiWtr">
                                <option>RFI</option>
                                <option>WTR</option>
                            </select>
                            <label for="subcontractor">Subcontractor:</label>
                            <input type="text" id="subcontractor">
                            <label for="refOrderNo">Ref. Order No:</label>
                            <input type="text" id="refOrderNo">
                        </div>

                        <div class="form-row">
                            <label for="fieldReport">Field Report:</label>
                            <input type="text" id="fieldReport">
                            <label for="scheduleColor">Schedule Color:</label>
                            <input type="color" id="scheduleColor" value="#007bff">
                            <label for="turnAroundTime">Turn Around Time:</label>
                            <input type="text" id="turnAroundTime">
                        </div>

                        <div class="form-row">
                            <label for="legacyNo">Legacy No:</label>
                            <input type="text" id="legacyNo">
                            <label for="subject">Subject:</label>
                            <input type="text" id="subject">
                            <label for="accManager">Acc. Manager:</label>
                            <input type="text" id="accManager">
                        </div>

                        <div class="form-row-full">
                            <label for="remarks">Remarks:</label>
                            <textarea id="remarks" rows="2"></textarea>
                        </div>
                        <div class="form-row-full">
                            <label for="footerText">Footer:</label>
                            <textarea id="footerText" rows="2"></textarea>
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend>Project Information & Contacts</legend>
                        <div class="form-row-two-cols">
                             <div class="contact-group">
                                <label for="customer">Customer:</label>
                                <input type="text" id="customer">
                                <label for="customerContact">Contact:</label>
                                <input type="text" id="customerContact">
                            </div>
                            <div class="contact-group">
                                <label for="contractor">Contractor:</label>
                                <input type="text" id="contractor">
                                <label for="contractorContact">Contact:</label>
                                <input type="text" id="contractorContact">
                            </div>
                            <div class="contact-group">
                                <label for="consultant">Consultant:</label>
                                <input type="text" id="consultant">
                                <label for="consultantContact">Contact:</label>
                                <input type="text" id="consultantContact">
                            </div>
                            <div class="contact-group">
                                <label for="owner">Owner:</label>
                                <input type="text" id="owner">
                                <label for="ownerContact">Contact:</label>
                                <input type="text" id="ownerContact">
                            </div>
                            <div class="contact-group">
                                <label for="projectClient">Project Client:</label>
                                <input type="text" id="projectClient">
                                <label for="projectClientContact">Contact:</label>
                                <input type="text" id="projectClientContact">
                            </div>
                            <div class="contact-group">
                                <label for="pmic">PMIC:</label>
                                <input type="text" id="pmic">
                                <label for="pmicContact">Contact:</label>
                                <input type="text" id="pmicContact">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="form-section">
                        <legend>Terms & Conditions</legend>

                        <div class="form-row">
                            <label for="paymentTerms">Payment Terms:</label>
                            <input type="text" id="paymentTerms">
                            <label for="currency">Currency:</label>
                            <select id="currency">
                                <option value="SAR">SAR</option>
                                <option value="USD">USD</option>
                            </select>
                            <label for="confirmation">Confirmation:</label>
                            <input type="text" id="confirmation">
                        </div>

                        <div class="form-row">
                            <label for="salesTax">Sales Tax (%):</label>
                            <input type="number" id="salesTax" value="5">
                            <label for="discount">Discount (%):</label>
                            <input type="number" id="discount" value="0">
                            <label for="showPrices">Show Prices:</label>
                            <input type="checkbox" id="showPrices" checked>
                        </div>
                    </fieldset>

                </form>
            </div>
        </div>
<div class="modal-footer">
    <button type="button" class="action-btn close-btn-footer" onclick="closeOrderModal()">Close</button>
    <button type="button" class="action-btn">Actions...</button>
    <button type="button" class="action-btn save-close-btn" onclick="saveOrderData(true)">Save & Close</button>
    <button type="button" class="action-btn save-btn" onclick="saveOrderData()">Save</button>
</div>
    </div>
</div>

<style>
/* ========================================================= */
/* 1. التغطية الخارجية (Overlay) */
/* ========================================================= */
#orderModal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    overflow: auto;
}

/* ========================================================= */
/* 2. محتوى النافذة المنبثقة (Modal Content) */
/* ========================================================= */
#orderModal .modal-content {
    background-color: #fefefe;
    margin: 20px auto;
    padding: 0;
    border: 1px solid #888;
    width: 90%;
    min-width: 1000px;
    max-width: 1200px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    display: flex;
    flex-direction: column;
    height: 95vh;
    max-height: 98vh;
    overflow-x: auto;
    overflow-y: hidden;
}

/* ========================================================= */
/* 3. هيكل النافذة والتبويبات */
/* ========================================================= */
#orderModal .order-header-tabs {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    background-color: #e9ecef;
    border-bottom: 1px solid #ddd;
    flex-shrink: 0;
}
#orderModal .tab {
    padding: 5px 15px;
    margin-right: 5px;
    cursor: pointer;
    border: 1px solid transparent;
    border-bottom: none;
    font-weight: bold;
}
#orderModal .tab.active {
    background-color: #fefefe;
    border-color: #ddd;
}
#orderModal .close-btn {
    margin-left: auto;
    font-size: 28px;
    font-weight: bold;
    color: #aaa;
    border: none;
    background: transparent;
    cursor: pointer;
}
#orderModal .close-btn:hover, #orderModal .close-btn:focus {
    color: #000;
}

/* ========================================================= */
/* 4. جسم النافذة (Body Container) - يضيف التمرير الرأسي */
/* ========================================================= */
#orderModal .form-body-container {
    display: flex;
    flex-grow: 1;
    padding: 15px;
    overflow-y: auto;
    overflow-x: hidden;
}

/* ========================================================= */
/* 5. الشريط الجانبي للإجماليات (Totals Sidebar) - مثبت */
/* ========================================================= */
#orderModal .totals-sidebar {
    width: 250px;
    min-width: 250px;
    padding: 15px;
    background-color: #f7f7f7;
    border-left: 1px solid #eee;
    margin-left: 15px;
    position: sticky;
    top: 0;
    align-self: flex-start;
    height: fit-content;
}
/* تنسيق قسم الإجماليات الداخلي */
#orderModal .totals-sidebar .total-row {
    display: flex;
    justify-content: space-between;
    padding: 3px 0;
    font-size: 0.9em;
}
/* تنسيق القوائم المنسدلة للعملة داخل الإجماليات */
#orderModal .totals-sidebar .total-row select.input-full-width {
    width: 100px;
    padding: 3px;
}
#orderModal .totals-sidebar h4 {
    border-bottom: 1px solid #ccc;
    padding-bottom: 5px;
    margin-top: 15px;
    margin-bottom: 5px;
    font-size: 1em;
    color: #333;
}
#orderModal .totals-sidebar .grand-total {
    font-weight: bold;
    border-top: 1px dashed #999;
    padding-top: 5px;
    margin-top: 5px;
}

/* ========================================================= */
/* 6. لوحة إدخال البيانات وتنسيقات النموذج الداخلية */
/* ========================================================= */
#orderModal .data-entry-panel {
    flex-grow: 1;
    padding-right: 15px;
}
#orderModal .form-section {
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 20px;
}
#orderModal .form-section legend {
    font-size: 1.1em;
    font-weight: bold;
    padding: 0 10px;
    width: auto;
    color: #007bff;
}
#orderModal .form-row {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr 2fr 1fr 2fr;
    gap: 10px;
    margin-bottom: 8px;
    align-items: center;
}
#orderModal .form-row label {
    text-align: right;
    font-weight: 500;
}
#orderModal .form-row input[type="text"],
#orderModal .form-row input[type="date"],
#orderModal .form-row input[type="number"], /* إضافة تنسيق حقول الأرقام */
#orderModal .form-row select {
    width: 100%;
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
#orderModal .form-row-full {
    display: grid;
    grid-template-columns: 1fr 5fr;
    gap: 10px;
    margin-bottom: 8px;
    align-items: flex-start;
}
#orderModal .form-row-full textarea {
    width: 100%;
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
    resize: vertical;
}
#orderModal .form-row-two-cols {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
#orderModal .contact-group {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 5px 10px;
    margin-bottom: 15px;
    align-items: center;
}
#orderModal .contact-group label {
    text-align: right;
    font-weight: 500;
}

/* ========================================================= */
/* 7. تذييل النافذة (Footer/Actions) */
/* ========================================================= */
#orderModal .modal-footer {
    display: flex;
    justify-content: flex-end;
    padding: 10px 15px;
    border-top: 1px solid #ddd;
    background-color: #e9ecef;
    flex-shrink: 0;
}
#orderModal .action-btn {
    padding: 8px 15px;
    margin-left: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}
#orderModal .close-btn-footer { background-color: #6c757d; color: white; }
#orderModal .save-close-btn { background-color: #007bff; color: white; }
#orderModal .save-btn { background-color: #28a745; color: white; }
</style>
     <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
     <script>

    const { jsPDF } = window.jspdf;
</script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
   


@endsection
