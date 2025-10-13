<div id="confModal" class="modal">
    <div class="modal-content new-conf-modal-design" style="width: 900px; max-width: 95%;">
        <span class="close-btn" onclick="closeModal('confModal')"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Add New Confirmation 📝</h2>

        <div class="tab-buttons">
            <button id="conf-btn" onclick="switchTab('conf')" class="active"><i class="fas fa-file-signature"></i> Confirmation</button>
            <button id="contact-btn" onclick="switchTab('contact')"><i class="fas fa-list-ul"></i> Confirmation Line</button>
        </div>

        <form id="confForm">
            {{-- // ---------------------------------------------------------------------------------------- --}}
            {{-- // CONFIRMATION TAB (ADD NEW) --}}
            {{-- // ---------------------------------------------------------------------------------------- --}}
            <div id="confTab" class="form-tab-content active">

                {{-- 1. Main Information (المعلومات الرئيسية) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Main Information</legend>
                    <input type="hidden" id="confId">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="confCategory">Category:</label>
                            <select id="confCategory" name="category">
                                <option value="" disabled selected>[Select Category]</option>
                                <option value="AAM-MT-C">AAM-MT-C</option>
                                <option value="AAM-MT-T">AAM-MT-T</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="confirmID">Confirm ID:</label>
                            <input type="text" id="confirmID" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder = "Automatically Generated">
                        </div>
                        <div class="form-group">
                            <label for="confirmDate">Confirm Date:</label>
                            <input type="date" id="confirmDate" name="confirm_date" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

       <div class="form-row" style="display: flex; gap: 15px; flex-wrap: wrap;">
    <div class="form-group" style="flex: 1;">
        <label for="customerSelect">Customer Name:</label>
        <select id="customerSelect" name="customer_id">
            <option value="" disabled selected>[Select Customer]</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}"
                    {{ isset($confirmation) && $confirmation->customer_id == $customer->id ? 'selected' : '' }}>
                    {{ $customer->customer_name }}
                </option>
            @endforeach
        </select>
    </div>

       <div class="form-group" style="flex: 1;">
        <label for="projectCodeSelect">Project Code:</label>
       <select id="projectCodeSelect" name="project_code">
    <option value="" disabled selected>[Select Project Code]</option>
    @foreach($projects as $project)
        <option value="{{ $project->reference }}"
            data-id="{{ $project->id }}"
            data-customer="{{ $project->customer_id }}">
            {{ $project->reference }}
        </option>
    @endforeach
</select>
    </div>


    <div class="form-group" style="flex: 1;">
        <label for="projectNameSelect">Project Name:</label>
        <select id="projectNameSelect" name="project_name">
            <option value="" disabled selected>[Select Project Name]</option>
            @foreach($projects as $project)
                <option value="{{ $project->name }}"
                    data-customer="{{ $project->customer_id }}"
                    {{ isset($confirmation) && $confirmation->project_name == $project->name ? 'selected' : '' }}>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>



                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="projectDetails">Project Details:</label>
                            <input type="text" id="projectDetails" name="project_details">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="subject">Subject:</label>
                            <input type="text" id="subject" name="subject">
                        </div>
                        <div class="form-group">
                            <label for="confSource">Conf. Source:</label>
                            <select id="confSource" name="conf_source">
                                <option value="" disabled selected>[Select Source]</option>
                                <option value="Authorization">Authorization</option>
                                <option value="Letter Document">Letter Document</option>
                                <option value="Purchase Order">Purchase Order</option>
                                <option value="Tender Document">Tender Document</option>
                                <option value="Email">Email</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="contractNo">Contract No.:</label>
                            <input type="text" id="contractNo" name="contract_no">
                        </div>
                    </div>

                </fieldset>

                {{-- 2. Contact Information (معلومات الاتصال) --}}
               <fieldset class="form-section-fieldset">
    <legend>Contact Information</legend>
    <div class="form-row">
        <div class="form-group">
            <label for="contactPersonSelect">Contact:</label>
            <select id="contactPersonSelect" name="contact_person">
                <option value="" disabled selected>[Select Contact]</option>
                {{-- سيتم ملؤها ديناميكياً حسب المشروع --}}
            </select>
        </div>
        <div class="form-group">
            <label for="confToSelect">To:</label>
            <select id="confToSelect" name="conf_to">
                <option value="" disabled selected>[Select Destination]</option>
                {{-- سيتم ملؤها ديناميكياً حسب المشروع --}}
            </select>
        </div>
    </div>
</fieldset>


                {{-- 3. Terms and other Controls (الشروط والضوابط الأخرى) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Terms and other Controls</legend>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="currency">Currency:</label>
                            <input type="text" id="currency" value="SAR" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label for="discount">Discount (%):</label>
                            <input type="number" id="discount" name="discount" placeholder="Only numbers">
                        </div>
                        <div class="form-group">
                            <label for="tax">Tax (%):</label>
                            <input type="text" id="tax" value="15" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label for="validity">Validity (Days):</label>
                            <input type="text" id="validity" value="60 Days">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="paymentTerms">Payment Terms:</label>
                            <select id="paymentTerms" name="payment_terms">
                                <option value="" disabled selected>[Select Term]</option>
                                <option value="IM">IM - Immediate</option>
                                <option value="PIA">PIA - Payment in advance</option>
                                <option value="C.O.D">C.O.D - Cash on delivery</option>
                                <option value="E.O.M">E.O.M - End of month</option>
                            </select>
                        </div>
                    </div>
                </fieldset>


            </div>

            {{-- // ---------------------------------------------------------------------------------------- --}}
            {{-- // CONFIRMATION LINE TAB (ADD NEW) - FINAL MODIFIED --}}
            {{-- // ---------------------------------------------------------------------------------------- --}}
            <div id="contactTab" class="form-tab-content" style="display: none;">

                {{-- 1. Services/Test Section (الخدمات/الاختبارات) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Services/Test</legend>

                    {{-- Buttons (أزرار أيقونات فقط) --}}
                    <div class="contact-toolbar" style="border-bottom: none; padding-bottom: 5px;">
                        <button type="button" class="btn btn-primary" onclick="addServiceLine()" title="إضافة (Add)">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn-secondary" onclick="editSelectedServiceLine('#servicesTable')" title="تعديل (Edit)">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button type="button" class="btn-danger" onclick="deleteSelectedServiceLine('#servicesTable')" title="حذف (Delete)">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button title="تصدير لاكسل (Export to Excel)" onclick="exportServiceLineExcel('servicesTable')" class="btn-icon">
                            <i class="fa-solid fa-table"></i>
                        </button>
                        <button title="طباعة (Print)" onclick="printServiceLineTable('servicesTable')" class="btn-icon">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>

                    {{-- Table (هيكل الجدول الصحيح) --}}
                    <div class="table-responsive-container">
                        <table id="servicesTable" class="services-table display responsive nowrap" data-ignore-lang>
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Method</th>
                                    <th>Unit</th>
                                    <th>Price</th>
                                    <th>Price only</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Table rows will be populated here via JS --}}
                            </tbody>
                        </table>
                    </div>
                </fieldset>

                {{-- 2. File Manager Section (مدير الملفات) --}}
                <fieldset class="form-section-fieldset">
                    <legend>File Manager</legend>
                    <p style="color: gray;">Use the File Manager button at the bottom to upload files from your device.</p>
                    <div id="uploadedFilesArea">
                        {{-- Files will be listed here --}}
                        <div id="fileIconsContainer"></div>

                    </div>
                </fieldset>
            </div>

            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="closeModal('confModal')"><i class="fas fa-times"></i> Close</button>
<button type="button" class="btn-success" onclick="openFileManager('conf')">
    <i class="fas fa-folder-open"></i> File Manager
</button>                <button type="button" class="btn-success" onclick="saveConf(true)"><i class="fas fa-save"></i> Save & Close</button>
                <button type="button" class="btn-primary" onclick="saveConf(false)"><i class="fas fa-save"></i> Save</button>
            </div>
        </form>
    </div>
</div>
<!-- ------------------------------------------------------------------------------------------------------ -->
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
<!-- ------------------------------------------------------------------------------------------------------ -->
<div id="editConfModal" class="modal">
    <div class="modal-content new-conf-modal-design" style="width: 900px; max-width: 95%;">
        <span class="close-btn" onclick="closeModal('editConfModal')"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Edit Confirmation 📝</h2>

        <div class="tab-buttons">
            <button id="edit-conf-btn" onclick="switchTab('edit-conf')" class="active"><i class="fas fa-file-signature"></i> Confirmation</button>
            <button id="edit-contact-btn" onclick="switchTab('edit-contact')"><i class="fas fa-list-ul"></i> Confirmation Line</button>
        </div>
        <form id="editConfForm">
            {{-- // ---------------------------------------------------------------------------------------- --}}
            {{-- // CONFIRMATION TAB (EDIT) --}}
            {{-- // ---------------------------------------------------------------------------------------- --}}
            <div id="editConfTab" class="form-tab-content active">

                {{-- 1. Main Information (المعلومات الرئيسية) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Main Information</legend>
                    <input type="hidden" id="editConfId">


                    <div class="form-row">
                        <div class="form-group">
                            <label for="editConfCategory">Category:</label>
                            <select id="editConfCategory" name="category">
                                <option value="" disabled selected>[Select Category]</option>
                                <option value="AAM-MT-C">AAM-MT-C</option>
                                <option value="AAM-MT-T">AAM-MT-T</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editConfirmID">Confirm ID:</label>
                            <input type="text" id="editConfirmID" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder = "Automatically Generated">
                        </div>
                        <div class="form-group">
                            <label for="editConfirmDate">Confirm Date:</label>
                            <input type="date" id="editConfirmDate" name="confirm_date" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                   <div class="form-row">
<div class="form-group">
    <label for="editProjectCode">Project Code:</label>
    <select id="editProjectCode" name="project_code">
        <option value="" disabled selected>[Select Project Code]</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}">{{ $project->reference }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="editProjectName">Project Name:</label>
    <select id="editProjectName" name="project_name">
        <option value="" disabled selected>[Select Project Name]</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}">{{ $project->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="editCustomerName">Customer Name:</label>
    <select id="editCustomer" name="customer_id">
        <option value="" disabled selected>[Select Customer]</option>
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="editProjectDetails">Project Details:</label>
    <input type="text" id="editProjectDetails" name="project_details">
</div>

</div>



                    <div class="form-row">
                        <div class="form-group">
                            <label for="editSubject">Subject:</label>
                            <input type="text" id="editSubject" name="subject">
                        </div>
                        <div class="form-group">
                            <label for="editConfSource">Conf. Source:</label>
                            <select id="editConfSource" name="conf_source">
                                <option value="" disabled selected>[Select Source]</option>
                                <option value="Authorization">Authorization</option>
                                <option value="Letter Document">Letter Document</option>
                                <option value="Purchase Order">Purchase Order</option>
                                <option value="Tender Document">Tender Document</option>
                                <option value="Email">Email</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editContractNo">Contract No.:</label>
                            <input type="text" id="editContractNo" name="contract_no">
                        </div>
                    </div>

                </fieldset>

                {{-- 2. Contact Information (معلومات الاتصال) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Contact Information</legend>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editContactPerson">Contact:</label>
                            <input type="text" id="editContactPerson" name="contact_person" placeholder="e.g., Contact Person Name">
                        </div>
                        <div class="form-group">
                            <label for="editConfTo">To:</label>
                            <input type="text" id="editConfTo" name="conf_to" placeholder="e.g., Destination/Recipient">
                        </div>
                    </div>
                </fieldset>

                {{-- 3. Terms and other Controls (الشروط والضوابط الأخرى) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Terms and other Controls</legend>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editCurrency">Currency:</label>
                            <input type="text" id="editCurrency" value="SAR" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label for="editDiscount">Discount (%):</label>
                            <input type="number" id="editDiscount" name="discount" placeholder="Only numbers">
                        </div>
                        <div class="form-group">
                            <label for="editTax">Tax (%):</label>
                            <input type="text" id="editTax" value="15" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label for="editValidity">Validity (Days):</label>
                            <input type="text" id="editValidity" value="60 Days">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editPaymentTerms">Payment Terms:</label>
                            <select id="editPaymentTerms" name="payment_terms">
                                <option value="" disabled selected>[Select Term]</option>
                                <option value="IM">IM - Immediate</option>
                                <option value="PIA">PIA - Payment in advance</option>
                                <option value="C.O.D">C.O.D - Cash on delivery</option>
                                <option value="E.O.M">E.O.M - End of month</option>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </div>

            {{-- // ---------------------------------------------------------------------------------------- --}}
            {{-- // CONFIRMATION LINE TAB (EDIT) - FINAL MODIFIED --}}
            {{-- // ---------------------------------------------------------------------------------------- --}}
            <div id="editContactTab" class="form-tab-content" style="display: none;">
                <fieldset class="form-section-fieldset">
                    <legend>Services/Test</legend>

                    {{-- Buttons (أزرار أيقونات فقط) --}}
                    <div class="contact-toolbar" style="border-bottom: none; padding-bottom: 5px;">
                        <button type="button" class="btn btn-primary" onclick="addServiceLine('edit')" title="إضافة (Add)">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button type="button" class="btn-secondary" onclick="editSelectedServiceLine('#servicesTableEdit')" title="تعديل (Edit)">
                            <i class="fas fa-pen"></i>
                        </button>
                         <button type="button" class="btn-danger" onclick="deleteSelectedServiceLine('#servicesTableEdit')" title="حذف (Delete)">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button title="تصدير لاكسل (Export to Excel)" onclick="exportServiceLineExcel('servicesTableEdit')" class="btn-icon">
                            <i class="fa-solid fa-table"></i>
                        </button>
                        <button title="طباعة (Print)" onclick="printServiceLineTable('servicesTableEdit')" class="btn-icon">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>

                    {{-- Table (هيكل الجدول الصحيح) --}}
                    <div class="table-responsive-container">
                        <table id="servicesTableEdit" class="services-table display responsive nowrap" data-ignore-lang>
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Method</th>
                                    <th>Unit</th>
                                    <th>Price</th>
                                    <th>Price only</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Table rows will be populated here via JS --}}
                            </tbody>
                        </table>
                    </div>
                </fieldset>

                {{-- 2. File Manager Section (مدير الملفات) --}}
                <fieldset class="form-section-fieldset">
                    <legend>File Manager</legend>
                    <p style="color: gray;">Use the File Manager button at the bottom to upload files from your device.</p>
                    <div id="editUploadedFilesArea">
                        {{-- Files will be listed here --}}
                    </div>

                </fieldset>

            </div>
            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="closeModal('editConfModal')"><i class="fas fa-times"></i> Close</button>
<button type="button" class="btn-success" onclick="openFileManager('editConf')">
    <i class="fas fa-folder-open"></i> File Manager
</button>
                <button type="button" class="btn-success" onclick="saveEditConf(true)"><i class="fas fa-save"></i> Update & Close</button>
                <button type="button" class="btn-primary" onclick="saveEditConf(false)"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
<!-- --------------------------------------------Select Services/Items---------------------------------------------------------- -->
<div id="serviceSelectionModal" class="modal" style="display: none;">
    <div class="modal-content new-conf-modal-design" style="width: 800px; max-width: 90%;">
        <span class="close-btn" onclick="closeModal('serviceSelectionModal')"><i class="fas fa-times"></i></span>
        <h3 class="modal-title">Select Services/Items</h3>

        <div class="table-responsive-container" style="margin-top: 20px;">
            <table id="availableServicesTable" class="services-table display responsive nowrap" data-ignore-lang>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Method</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Price only</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- مثال على صف يحتوي على حقول إدخال قابلة للتعديل --}}
                    <tr data-service-id="101">
                        {{-- ✅ تعديل: إضافة دالة toggleRowSelection عند تغيير حالة الـ checkbox --}}
                        <td><input type="checkbox" name="selectService[]" value="101" class="service-selector" onchange="toggleRowSelection(this)"> 101</td>
                        <td>Calibration Service A</td>
                        <td>ASTM E4</td>
                        <td>Service</td>
                        <td><input type="number" class="form-control input-sm editable-price" value="500.00" min="0" step="0.01" style="width: 80px;"></td>
                        <td><input type="checkbox" class="is-price-only"></td>
                        <td><input type="number" class="form-control input-sm editable-quantity" value="1" min="1" style="width: 50px;"></td>
                    </tr>

                    <tr data-service-id="102">
                        <td><input type="checkbox" name="selectService[]" value="102" class="service-selector" onchange="toggleRowSelection(this)"> 102</td>
                        <td>Pressure Test B</td>
                        <td>ISO 9001</td>
                        <td>Item</td>
                        {{-- ✅ حقل السعر قابل للتعديل --}}
                        <td><input type="number" class="form-control input-sm editable-price" value="1200.00" min="0" step="0.01" style="width: 80px;"></td>
                        {{-- ✅ مربع اختيار Price only قابل للتعديل --}}
                        <td><input type="checkbox" class="is-price-only"></td>
                        {{-- ✅ حقل الكمية قابل للتعديل --}}
                        <td><input type="number" class="form-control input-sm editable-quantity" value="1" min="1" style="width: 50px;"></td>
                    </tr>
                    {{-- ... (بقية الخدمات) ... --}}
                </tbody>
            </table>
        </div>

        <div class="form-buttons modal-bottom-buttons" style="margin-top: 20px; justify-content: flex-end;">
            <button type="button" class="btn-primary" onclick="closeModal('serviceSelectionModal')"><i class="fas fa-times"></i> Close </button>
            <button type="button" class="btn-warning" onclick="handleServiceInsert('price_only')"><i class="fas fa-money-bill-alt"></i> Set Selected as Price Only</button>
            <button type="button" class="btn-success" onclick="handleServiceInsert('test')"><i class="fas fa-plus"></i> Insert</button>
        </div>
    </div>
</div>
<div id="editServiceSelectionModal" class="modal" style="display: none;">
    <div class="modal-content new-conf-modal-design" style="width: 800px; max-width: 90%;">
        <span class="close-btn" onclick="closeModal('editServiceSelectionModal')"><i class="fas fa-times"></i></span>
        <h3 class="modal-title">✏️ Edit Service Line</h3>

         <div class="table-responsive-container" style="margin-top: 20px;">
            <table id="editavailableServicesTable" class="services-table display responsive nowrap" data-ignore-lang>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Method</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Price only</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>

                    {{-- مثال على صف يحتوي على حقول إدخال قابلة للتعديل --}}
                    <tr data-service-id="101">
                        {{-- ✅ تعديل: إضافة دالة toggleRowSelection عند تغيير حالة الـ checkbox --}}
                        <td><input type="checkbox" name="selectService[]" value="101" class="service-selector" onchange="toggleRowSelection(this)"> 101</td>
                        <td>Calibration Service A</td>
                        <td>ASTM E4</td>
                        <td>Service</td>
                        <td><input type="number" class="form-control input-sm editable-price" value="500.00" min="0" step="0.01" style="width: 80px;"></td>
                        <td><input type="checkbox" class="is-price-only"></td>
                        <td><input type="number" class="form-control input-sm editable-quantity" value="1" min="1" style="width: 50px;"></td>
                    </tr>

                    <tr data-service-id="102">
                        <td><input type="checkbox" name="selectService[]" value="102" class="service-selector" onchange="toggleRowSelection(this)"> 102</td>
                        <td>Pressure Test B</td>
                        <td>ISO 9001</td>
                        <td>Item</td>
                        {{-- ✅ حقل السعر قابل للتعديل --}}
                        <td><input type="number" class="form-control input-sm editable-price" value="1200.00" min="0" step="0.01" style="width: 80px;"></td>
                        {{-- ✅ مربع اختيار Price only قابل للتعديل --}}
                        <td><input type="checkbox" class="is-price-only"></td>
                        {{-- ✅ حقل الكمية قابل للتعديل --}}
                        <td><input type="number" class="form-control input-sm editable-quantity" value="1" min="1" style="width: 50px;"></td>
                    </tr>
                    {{-- ... (بقية الخدمات) ... --}}
                </tbody>
            </table>
        </div>

        <div class="form-buttons modal-bottom-buttons" style="margin-top: 20px; justify-content: flex-end;">
            <button type="button" class="btn-primary" onclick="closeModal('editServiceSelectionModal')">
                <i class="fas fa-times"></i> Close
            </button>
            <button type="button" class="btn-success" id="saveEditedServiceBtn">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </div>
</div>

<!-- ------------------------------------------------------------------------------------------------------ -->
<style>
    /* تنسيق CSS لتمييز الصف المحدد */
    .row-selected {
        background-color: #ffe0b2 !important; /* لون برتقالي فاتح */
        border-left: 5px solid #ff9800; /* شريط برتقالي على اليسار */
        font-weight: 500;
    }
    /* يمكنك إضافة أي تنسيقات أخرى للجدول هنا */
</style>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customerSelect');
    const projectCodeSelect = document.getElementById('projectCodeSelect');
    const projectNameSelect = document.getElementById('projectNameSelect');
    const projectDetailsInput = document.getElementById('projectDetails');
    const contactPersonSelect = document.getElementById('contactPersonSelect');
    const confToSelect = document.getElementById('confToSelect');

    // -----------------------------
    // جهات الاتصال لكل عميل
    // -----------------------------
    const customerContactsMap = {
        @foreach($customers as $customer)
        "{{ $customer->id }}": [
            @foreach($customer->contacts as $contact)
            { name: @json($contact->name), to: @json($contact->phone) },
            @endforeach
        ],
        @endforeach
    };

    // -----------------------------
    // المشاريع
    // -----------------------------
    const projectsMap = {
        @foreach($projects as $project)
        "{{ $project->id }}": {
            code: "{{ $project->reference }}",
            name: "{{ $project->name }}",
            details: "{{ $project->project_details ?? '' }}",
            customer_id: "{{ $project->customer_id }}"
        },
        @endforeach
    };

    // -----------------------------
    // عند اختيار العميل
    // -----------------------------
    function fillDataForCustomer() {
        const customerId = customerSelect.value;

        // مسح المشاريع القديمة
        projectCodeSelect.innerHTML = '<option value="" disabled selected>[Select Project Code]</option>';
        projectNameSelect.innerHTML = '<option value="" disabled selected>[Select Project Name]</option>';

        // تعبئة المشاريع الخاصة بالعميل
        for (const id in projectsMap) {
            const project = projectsMap[id];
            if (project.customer_id == customerId) {
                projectCodeSelect.append(new Option(project.code, id));
                projectNameSelect.append(new Option(project.name, id));
            }
        }

        // مسح الحقول القديمة
        projectDetailsInput.value = '';
        contactPersonSelect.innerHTML = '<option value="" disabled selected>[Select Contact]</option>';
        confToSelect.innerHTML = '<option value="" disabled selected>[Select Destination]</option>';

        // تعبئة جهات الاتصال
        if(customerContactsMap[customerId]) {
            customerContactsMap[customerId].forEach(item => {
                contactPersonSelect.append(new Option(item.name, item.name));
                confToSelect.append(new Option(item.to, item.to));
            });
        }
    }

    // -----------------------------
    // عند اختيار مشروع
    // -----------------------------
    function fillProjectData(projectId) {
        if (!projectsMap[projectId]) return;
        const project = projectsMap[projectId];

        // مزامنة الكود والاسم
        projectCodeSelect.value = projectId;
        projectNameSelect.value = projectId;

        // تعبئة تفاصيل المشروع
        projectDetailsInput.value = project.details;

        // اختيار العميل تلقائيًا
        customerSelect.value = project.customer_id;
        fillDataForCustomer();
    }

    // -----------------------------
    // Event Listeners
    // -----------------------------
    customerSelect.addEventListener('change', fillDataForCustomer);
    projectCodeSelect.addEventListener('change', function() {
        fillProjectData(this.value);
    });
    projectNameSelect.addEventListener('change', function() {
        fillProjectData(this.value);
    });

    // -----------------------------
    // إذا كان هناك بيانات محددة مسبقًا (Edit)
    // -----------------------------
    if(customerSelect.value) {
        fillDataForCustomer();
    }
    if(projectCodeSelect.value) {
        fillProjectData(projectCodeSelect.value);
    }
});
</script>

