

            <div id="customerModal" class="modal">
                <div class="modal-content new-customer-modal-design">
                    <span class="close-btn" onclick="closeCustomerModal()"><i class="fas fa-times"></i></span>
                    <h2 class="modal-title">Add new customer</h2>

                    <div class="tab-buttons">
                        <button id="customer-btn" onclick="switchTab('customer')" class="active"><i class="fas fa-user"></i> Customer</button>
                        <button id="contact-btn" onclick="switchTab('contact')"><i class="fas fa-address-book"></i> Contacts</button>
                    </div>

                    <form id="customerForm" onsubmit="saveCustomer(event)">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div id="customerTab" class="form-tab-content active">
                            <fieldset class="form-section-fieldset">
                                <legend>Customer Information</legend>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="">Customer ID:</label>
<input type="text" id="customerId" name="customer_id" hidden>

                                    </div>
                                    <div class="form-group-checkbox">
                                        <input type="checkbox" id="potentialCustomer">
                                        <label for="potentialCustomer">Potential</label>
                                    </div>
                                    <div class="form-group">
                                        <label for="legacyAccNo">Legacy Acc. No.:</label>
                                        <input type="text" id="legacyAccNo">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="customerName">Customer Name:</label>
                                        <input type="text" id="customerName" name="customer_name"  required>
                                    </div>
                                    <div class="form-group">
                                        <label for="customerArabicName">Arabic Name:</label>
                                        <input type="text" name="arabic_name"  id="customerArabicName">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="customerLegalName">Legal Name:</label>
                                        <input type="text" id="customerLegalName">
                                    </div>
                                    <div class="form-group">
                                        <label for="customerType">Customer Type:</label>
                                        <select id="customerType" name="customerType" required>
                                            <option value="" selected disabled>Select Type</option> <option value="Contractor">Contractor</option>
                                            <option value="Consultant">Consultant</option>
                                            <option value="Supplier">Supplier</option>
                                            <option value="Private">Private</option>
                                            <option value="Owner">Owner</option>
                                            <option value="Other">Other</option>
                                            <option value="Governmental">Governmental</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="registrationDate">Date Registered:</label>
                                        <input type="date" id="registrationDate" value="2024-07-22">
                                    </div>
                                    <div class="form-group">
                                        <label for="customerPhone">Phone:</label>
                                        <input type="tel" id="customerPhone" placeholder="e.g., +9665XXXXXXXX">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-section-fieldset">
                                <legend>Customer Location</legend>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="customerCountry">Country:</label>
                                        <select id="customerCountry" class="form-control">
                                            <option value=""selected disabled>Select Country</option>
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="customerArabicLocation">Arabic Location:</label>
                                        <input type="text" id="customerArabicLocation">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="customerDistrict">District:</label>
                                        <input type="text" id="customerDistrict">
                                    </div>
                                    <div class="form-group">
                                        <label for="customerCity">City:</label>
                                        <select id="customerCity" name="customerCity" required>
                                            <option value=""selected disabled>Select City</option>
                                            <option value="Ehsa">Ehsa</option>
                                            <option value="Riyadh">Riyadh</option>
                                            <option value="Jeddah">Jeddah</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="customerStreet">Street:</label>
                                        <input type="text" id="customerStreet">
                                    </div>
                                    <div class="form-group">
                                        <label for="customerPostCode">Post Code:</label>
                                        <input type="text" id="customerPostCode">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="customerAddressBlock">Address Block:</label>
                                        <input type="text" id="customerAddressBlock">
                                    </div>
                                    <div class="form-group">
                                        <label for="customerPoBox">PO Box:</label>
                                        <input type="text" id="customerPoBox">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="customerBuildingNo">Building No:</label>
                                        <input type="text" id="customerBuildingNo">
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="form-section-fieldset">
                                <legend>Terms & Other Controls</legend>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="paymentTerms">Payment Terms:</label>
                                        <select id="paymentTerms" name="paymentTerms">
                                            <option value=""selected disabled>Select Payment Terms</option>
                                            <option value="IM - Immediate">IM - Immediate</option>
                                            <option value="PIA - Payment in advance">PIA - Payment in advance</option>
                                            <option value="C.O.D - Cash on delivery">C.O.D - Cash on delivery</option>
                                            <option value="E.O.M - End of month">E.O.M - End of month</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="discount">Discount:</label>
                                        <input type="number" id="discount" value="0">
                                    </div>
                                    <div class="form-group-checkbox">
                                        <input type="checkbox" id="isCash">
                                        <label for="isCash">Cash</label>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="creditLimit">Credit Limit:</label>
                                        <input type="text" id="creditLimit" value="999999999">
                                    </div>
                                    <div class="form-group">
                                        <label for="vatProfile">VAT Profile:</label>
                                        <select id="vatProfile" name="vatProfile" required>
                                            <option value="" disabled>Select VAT Profile</option>
                                            <option value="Standard VAT" selected>Standard VAT</option>
                                            <option value="Exempt Supply">Exempt Supply</option>
                                            <option value="Zero-Rated Supply">Zero-Rated Supply</option>
                                            <option value="Non-VAT Registered">Non-VAT Registered</option>
                                            <option value="Flat Rate Scheme">Flat Rate Scheme</option>
                                            <option value="Reverse Charge">Reverse Charge</option>
                                            <option value="Mixed Supply">Mixed Supply</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="trnTin">TRN/TIN #:</label>
                                        <input type="text" id="trnTin">
                                    </div>
                                    <div class="form-group">
                                        <label for="registrationNo">Registration #:</label>
                                        <input type="text" id="registrationNo">
                                    </div>
                                </div>

                                <div class="form-row-checkboxes">
                                    <div class="form-group-checkbox">
                                        <input type="checkbox" id="restrictDeliveries">
                                        <label for="restrictDeliveries">Restrict Deliveries</label>
                                    </div>
                                    <div class="form-group-checkbox">
                                        <input type="checkbox" id="restrictOrders">
                                        <label for="restrictOrders">Restrict Orders</label>
                                    </div>
                                    <div class="form-group-checkbox">
                                        <input type="checkbox" id="restrictQuotations">
                                        <label for="restrictQuotations">Restrict Quotations</label>
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
    <legend>Add Contact Person</legend>
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
        <button type="button" class="btn btn-success" onclick="saveContactForCustomer('add')">
    <i class="fas fa-save"></i> Save Contact
</button>


        <button type="button" class="btn-secondary" onclick="clearContactForm()">
            <i class="fas fa-eraser"></i> Clear Form
        </button>
    </div>
</fieldset>
                        </div>

                        <div class="form-buttons modal-bottom-buttons">
                            <button type="button" class="btn-primary" onclick="closeCustomerModal()"><i class="fas fa-times"></i> Close</button>
                            <button type="button" class="btn-secondary" id="integrationBtn"><i class="fas fa-cogs"></i> Integration..</button>

                             <button type="submit" class="btn-success"><i class="fas fa-save"></i> Save & Close</button>
                            <button type="button" class="btn-success" onclick="saveCustomer(event, false)"><i class="fas fa-save"></i> Save</button>
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



<!-- 🔹 Edit Customer Modal -->
<!-- 🔹 Edit Customer Modal -->
<div id="editCustomerModal" class="modal">
  <div class="modal-content new-customer-modal-design">
    <span class="close-btn" onclick="closeEditCustomerModal()">
      <i class="fas fa-times"></i>
    </span>
    <h2 class="modal-title">Edit Customer</h2>

    <!-- 🔹 Tab Buttons -->
    <div class="tab-buttons">
      <button id="edit-customer-btn" onclick="switchEditTab('customer')" class="active">
        <i class="fas fa-user"></i> Customer
      </button>
      <button id="edit-contact-btn" onclick="switchEditTab('contact')">
        <i class="fas fa-address-book"></i> Contacts
      </button>
    </div>

    <!-- 🔹 Main Form -->
    <form id="editCustomerForm" onsubmit="updateCustomer(event)">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" id="editCustomerId" name="id">



      <!-- ================= CUSTOMER TAB ================= -->
      <div id="editCustomerTab" class="form-tab-content active">
        <fieldset class="form-section-fieldset">
          <legend>Customer Information</legend>
          <div class="form-row">
            <div class="form-group-checkbox">
              <input type="checkbox" id="editPotentialCustomer" name="potential" value="1">
              <label for="editPotentialCustomer">Potential</label>
            </div>
            <div class="form-group">
              <label for="editLegacyAccNo">Legacy Acc. No.:</label>
              <input type="text" id="editLegacyAccNo" name="legacy_acc_no">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editCustomerName">Customer Name:</label>
              <input type="text" id="editCustomerName" name="customer_name" required>
            </div>
            <div class="form-group">
              <label for="editCustomerArabicName">Arabic Name:</label>
              <input type="text" id="editCustomerArabicName" name="arabic_name">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editCustomerLegalName">Legal Name:</label>
              <input type="text" id="editCustomerLegalName" name="customer_legal_name">
            </div>
            <div class="form-group">
              <label for="editCustomerType">Customer Type:</label>
              <select id="editCustomerType" name="customer_type" required>
                <option value="" disabled>Select Type</option>
                <option value="Contractor">Contractor</option>
                <option value="Consultant">Consultant</option>
                <option value="Supplier">Supplier</option>
                <option value="Private">Private</option>
                <option value="Owner">Owner</option>
                <option value="Other">Other</option>
                <option value="Governmental">Governmental</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editRegistrationDate">Date Registered:</label>
              <input type="date" id="editRegistrationDate" name="date_registered">
            </div>
            <div class="form-group">
              <label for="editCustomerPhone">Phone:</label>
              <input type="tel" id="editCustomerPhone" name="phone" placeholder="e.g., +9665XXXXXXXX">
            </div>
          </div>
        </fieldset>

        <fieldset class="form-section-fieldset">
          <legend>Customer Location</legend>
          <div class="form-row">
            <div class="form-group">
              <label for="editCustomerCountry">Country:</label>
              <select id="editCustomerCountry" name="country">
                <option value="" disabled>Select Country</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
              </select>
            </div>
            <div class="form-group">
              <label for="editCustomerArabicLocation">Arabic Location:</label>
              <input type="text" id="editCustomerArabicLocation" name="arabic_location">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editCustomerDistrict">District:</label>
              <input type="text" id="editCustomerDistrict" name="district">
            </div>
            <div class="form-group">
              <label for="editCustomerCity">City:</label>
              <select id="editCustomerCity" name="city">
                <option value="" disabled>Select City</option>
                <option value="Ehsa">Ehsa</option>
                <option value="Riyadh">Riyadh</option>
                <option value="Jeddah">Jeddah</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="customerStreet">Street:</label>
              <input type="text" id="editCustomerStreet" name="street">
            </div>
            <div class="form-group">
              <label for="customerPostCode">Post Code:</label>
              <input type="text" id="editCustomerPostCode" name="post_code">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="customerAddressBlock">Address Block:</label>
              <input type="text" id="editCustomerAddressBlock" name="address_block">
            </div>
            <div class="form-group">
              <label for="customerPoBox">PO Box:</label>
              <input type="text" id="editCustomerPoBox" name="po_box">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="customerBuildingNo">Building No:</label>
              <input type="text" id="editCustomerBuildingNo" name="building_no">
            </div>
          </div>
        </fieldset>

        <fieldset class="form-section-fieldset">
          <legend>Terms & Other Controls</legend>
          <div class="form-row">
            <div class="form-group">
              <label for="paymentTerms">Payment Terms:</label>
              <select id="editPaymentTerms" name="payment_terms">
                <option value="" selected disabled>Select Payment Terms</option>
                <option value="IM - Immediate">IM - Immediate</option>
                <option value="PIA - Payment in advance">PIA - Payment in advance</option>
                <option value="C.O.D - Cash on delivery">C.O.D - Cash on delivery</option>
                <option value="E.O.M - End of month">E.O.M - End of month</option>
              </select>
            </div>
            <div class="form-group">
              <label for="discount">Discount:</label>
              <input type="number" id="editDiscount" name="discount">
            </div>
            <div class="form-group-checkbox">
              <input type="checkbox" id="editIsCash" name="cash" value="1">
              <label for="isCash">Cash</label>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editCreditLimit">Credit Limit:</label>
              <input type="text" id="editCreditLimit" name="credit_limit">
            </div>
            <div class="form-group">
              <label for="vatProfile">VAT Profile:</label>
              <select id="editVatProfile" name="vat_profile" required>
                <option value="" disabled>Select VAT Profile</option>
                <option value="Standard VAT" selected>Standard VAT</option>
                <option value="Exempt Supply">Exempt Supply</option>
                <option value="Zero-Rated Supply">Zero-Rated Supply</option>
                <option value="Non-VAT Registered">Non-VAT Registered</option>
                <option value="Flat Rate Scheme">Flat Rate Scheme</option>
                <option value="Reverse Charge">Reverse Charge</option>
                <option value="Mixed Supply">Mixed Supply</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="editTrnTin">TRN/TIN #:</label>
              <input type="text" id="editTrnTin" name="trn_tin">
            </div>
            <div class="form-group">
              <label for="editRegistrationNo">Registration #:</label>
              <input type="text" id="editRegistrationNo" name="registration_no">
            </div>
          </div>

          <div class="form-row-checkboxes">
            <div class="form-group-checkbox">
              <input type="checkbox" id="editRestrictDeliveries" name="restrict_deliveries" value="1">
              <label for="editRestrictDeliveries">Restrict Deliveries</label>
            </div>
            <div class="form-group-checkbox">
              <input type="checkbox" id="editRestrictOrders" name="restrict_orders" value="1">
              <label for="editRestrictOrders">Restrict Orders</label>
            </div>
            <div class="form-group-checkbox">
              <input type="checkbox" id="editRestrictQuotations" name="restrict_quotations" value="1">
              <label for="editRestrictQuotations">Restrict Quotations</label>
            </div>
          </div>
        </fieldset>
      </div>
      <!-- END CUSTOMER TAB -->

      <!-- ================= CONTACT TAB ================= -->
      <div id="editContactTab" class="form-tab-content" style="display: none;">
        <fieldset class="form-section-fieldset">
          <legend>Contact List</legend>
          <div class="contact-toolbar" style="border-bottom: none; padding-bottom: 5px;">
            <button type="button" class="btn-secondary" onclick="populateContactFormForEdit()"><i class="fas fa-pen"></i> Edit Selected</button>
            <button type="button" class="btn-danger" onclick="deleteSelectedContacts()"><i class="fas fa-trash"></i> Delete Selected</button>
            <button type="button" class="btn-icon" id="exportContactsModalExcelBtn" title="Export to Excel"><i class="fa-solid fa-table"></i></button>
            <button type="button" class="btn-icon" id="printContactsModalTableBtn" title="Print"><i class="fas fa-print"></i></button>
          </div>
          <div class="table-responsive-container">
     <table id="contactsTableEdit" class="contacts-table display responsive nowrap">
              <thead>
                <tr>
                  <th>
                    <input type="checkbox" class="contact-select-all">
                  </th>
                  <th class="d-none">Contact ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Mobile</th>
                  <th>Position</th>
                  <th>Is Primary</th>
                </tr>
              </thead>
              <tbody>
                <!-- Rows added dynamically via JS -->
              </tbody>
            </table>
          </div>
        </fieldset>

        <fieldset class="form-section-fieldset">
          <legend>Add/Edit Contact Person</legend>
          <div class="form-row">
            <input type="text" id="editContactId"  hidden>

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
            <button type="button" class="btn btn-primary" onclick="saveContactForCustomerEdit()">
              حفظ / تحديث جهة الاتصال
            </button>
            <button type="button" class="btn-secondary" onclick="clearContactForm()">
              <i class="fas fa-eraser"></i> Clear Form
            </button>
          </div>
        </fieldset>
      </div>
      <!-- END CONTACT TAB -->

      <!-- 🔹 Modal Footer -->
      <div class="form-buttons modal-bottom-buttons">
        <button type="button" class="btn-primary" onclick="closeEditCustomerModal()"><i class="fas fa-times"></i> Close</button>
        <button type="submit" class="btn-success"><i class="fas fa-save"></i> Update & Close</button>
        <button type="button" class="btn-success" onclick="updateCustomer(event, false)"><i class="fas fa-save"></i> Update</button>
      </div>
    </form>
  </div>
</div>










