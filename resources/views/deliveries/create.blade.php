<div id="confModal" class="modal">
    <div class="modal-content new-conf-modal-design" style="width: 900px; max-width: 95%;">
        <span class="close-btn" onclick="closeModal('confModal')"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Add New Delivery 🚚</h2>

        <div class="tab-buttons">
            <button id="conf-btn" onclick="switchTab('conf')" class="active"><i class="fas fa-truck"></i> Delivery</button>
            <button id="contact-btn" onclick="switchTab('contact')"><i class="fas fa-list-ul"></i> Delivery Lines</button>
        </div>

        <form id="confForm">
            {{-- // ---------------------------------------------------------------------------------------- --}}
            {{-- // DELIVERY TAB (ADD NEW) --}}
            {{-- // ---------------------------------------------------------------------------------------- --}}
            <div id="confTab" class="form-tab-content active">

                {{-- 1. Main Info (المعلومات الرئيسية) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Main Info</legend>
                    <input type="hidden" id="confId">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="deliveryNo">Delivery No:</label>
                            <input type="text" id="deliveryNo" name="delivery_no" value="AAM-DN-24-000081" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label for="deliveryDate">Delivery Date:</label>
                            <input type="date" id="deliveryDate" name="delivery_date" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label for="departmentSelect">Department:</label>
                            <select id="departmentSelect" name="department">
                                <option value="Materials Testing" selected>Materials Testing</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="projectCodeSelect">Project Code:</label>
                            <select id="projectCodeSelect" name="project_code">
                                <option value="AAMP-4" selected>AAMP-4</option>
                            
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="projectNo">Project No:</label>
                            <input type="text" id="projectNo" name="project_no" placeholder="(فارغ)">
                        </div>
                        <div class="form-group">
                            <label for="projectNameSelect">Project:</label>
                            <select id="projectNameSelect" name="project_name">
                                <option value="samer villa" selected>samer villa</option>
                            
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="projectDetails">Details:</label>
                            <input type="text" id="projectDetails" name="project_details" value="samer villa">
                        </div>
                    </div>
                </fieldset>

                {{-- 2. Customer Info (معلومات العميل) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Customer Info</legend>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customerID">Customer ID:</label>
                            <input type="text" id="customerID" name="customer_id_ref" value="AAMC-5" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label for="customerSelect">Customer:</label>
                            <select id="customerSelect" name="customer_id">
                                <option value="samer demo" selected>samer demo</option>
                            
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="accountNo">Account No:</label>
                            <input type="text" id="accountNo" name="account_no" placeholder="(فارغ)">
                        </div>
                        <div class="form-group">
                            <label for="location">Location:</label>
                            <input type="text" id="location" name="location" value="alhasa (الأحساء)">
                        </div>
                    </div>
                </fieldset>

                {{-- 3. Contact Info (معلومات الاتصال) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Contact Info</legend>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="deliveryContact">Contact:</label>
                            <input type="text" id="deliveryContact" name="contact_person" value="sam">
                        </div>
                        <div class="form-group">
                            <label for="attnTo">Attn. To:</label>
                            <input type="text" id="attnTo" name="attn_to" value="sam">
                        </div>
                        <div class="form-group">
                            <label for="attnPos">Attn. Pos:</label>
                            <input type="text" id="attnPos" name="attn_pos" value="site engineer (مهندس الموقع)">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="addressEmail">Address/Email:</label>
                            <input type="text" id="addressEmail" name="address_email" value="Ehsa/Saudi Arabia E-mail: a2@ebc.com">
                        </div>
                    </div>
                </fieldset>

                {{-- 4. Delivery Info (معلومات إتمام التسليم) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Delivery Info</legend>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="preparedBy">Prepared By:</label>
                            <input type="text" id="preparedBy" name="prepared_by" value="Super ZontaSoft">
                        </div>
                        <div class="form-group">
                            <label for="deliveredBy">Delivered By:</label>
                            <input type="text" id="deliveredBy" name="delivered_by" value="samer">
                        </div>
                        <div class="form-group">
                            <label for="receivedBy">Received By:</label>
                            <input type="text" id="receivedBy" name="received_by" value="sam">
                        </div>
                        <div class="form-group">
                            <label for="dateReceived">Date Received:</label>
                            <input type="date" id="dateReceived" name="date_received" value="2024-07-28">
                        </div>
                    </div>
                </fieldset>


            </div>

            {{-- // ---------------------------------------------------------------------------------------- --}}
            {{-- // CONFIRMATION LINE TAB (ADD NEW) --}}
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
            </div>

            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="closeModal('confModal')"><i class="fas fa-times"></i> Close</button>
                <button type="button" class="btn-success" onclick="saveConf(true)"><i class="fas fa-save"></i> Save & Close</button>
                <button type="button" class="btn-primary" onclick="saveConf(false)"><i class="fas fa-save"></i> Save</button>
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
        <div class="form-buttons" id="customDialogButtons"></div>
    </div>
</div>
<div id="editConfModal" class="modal">
    <div class="modal-content new-conf-modal-design" style="width: 900px; max-width: 95%;">
        <span class="close-btn" onclick="closeModal('editConfModal')"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Edit Delivery 📝</h2>

        <div class="tab-buttons">
            <button id="edit-conf-btn" onclick="switchTab('edit-conf')" class="active"><i class="fas fa-truck"></i> Delivery</button>
            <button id="edit-contact-btn" onclick="switchTab('edit-contact')"><i class="fas fa-list-ul"></i> Delivery Lines</button>
        </div>
        <form id="editConfForm">
            {{-- // ---------------------------------------------------------------------------------------- --}}
            {{-- // DELIVERY TAB (EDIT) --}}
            {{-- // ---------------------------------------------------------------------------------------- --}}
            <div id="editConfTab" class="form-tab-content active">

                {{-- 1. Main Info (المعلومات الرئيسية) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Main Info</legend>
                    <input type="hidden" id="editConfId">

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editDeliveryNo">Delivery No:</label>
                            <input type="text" id="editDeliveryNo" name="delivery_no" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label for="editDeliveryDate">Delivery Date:</label>
                            <input type="date" id="editDeliveryDate" name="delivery_date">
                        </div>
                        <div class="form-group">
                            <label for="editDepartmentSelect">Department:</label>
                            <select id="editDepartmentSelect" name="department">
                                <option value="Materials Testing">Materials Testing</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProjectCodeSelect">Project Code:</label>
                            <select id="editProjectCodeSelect" name="project_code">
                           
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editProjectNo">Project No:</label>
                            <input type="text" id="editProjectNo" name="project_no">
                        </div>
                        <div class="form-group">
                            <label for="editProjectNameSelect">Project:</label>
                            <select id="editProjectNameSelect" name="project_name">
                               
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="editProjectDetails">Details:</label>
                            <input type="text" id="editProjectDetails" name="project_details">
                        </div>
                    </div>
                </fieldset>

                {{-- 2. Customer Info (معلومات العميل) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Customer Info</legend>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editCustomerID">Customer ID:</label>
                            <input type="text" id="editCustomerID" name="customer_id_ref" readonly style="background-color: #e9ecef; cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label for="editCustomerSelect">Customer:</label>
                            <select id="editCustomerSelect" name="customer_id">
                               
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editAccountNo">Account No:</label>
                            <input type="text" id="editAccountNo" name="account_no">
                        </div>
                        <div class="form-group">
                            <label for="editLocation">Location:</label>
                            <input type="text" id="editLocation" name="location">
                        </div>
                    </div>
                </fieldset>

                {{-- 3. Contact Info (معلومات الاتصال) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Contact Info</legend>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editDeliveryContact">Contact:</label>
                            <input type="text" id="editDeliveryContact" name="contact_person">
                        </div>
                        <div class="form-group">
                            <label for="editAttnTo">Attn. To:</label>
                            <input type="text" id="editAttnTo" name="attn_to">
                        </div>
                        <div class="form-group">
                            <label for="editAttnPos">Attn. Pos:</label>
                            <input type="text" id="editAttnPos" name="attn_pos">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="editAddressEmail">Address/Email:</label>
                            <input type="text" id="editAddressEmail" name="address_email">
                        </div>
                    </div>
                </fieldset>

                {{-- 4. Delivery Info (معلومات إتمام التسليم) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Delivery Info</legend>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="editPreparedBy">Prepared By:</label>
                            <input type="text" id="editPreparedBy" name="prepared_by">
                        </div>
                        <div class="form-group">
                            <label for="editDeliveredBy">Delivered By:</label>
                            <input type="text" id="editDeliveredBy" name="delivered_by">
                        </div>
                        <div class="form-group">
                            <label for="editReceivedBy">Received By:</label>
                            <input type="text" id="editReceivedBy" name="received_by">
                        </div>
                        <div class="form-group">
                            <label for="editDateReceived">Date Received:</label>
                            <input type="date" id="editDateReceived" name="date_received">
                        </div>
                    </div>
                </fieldset>
            </div>

            {{-- // ---------------------------------------------------------------------------------------- --}}
            {{-- // CONFIRMATION LINE TAB (EDIT) --}}
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
            </div>
            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="closeModal('editConfModal')"><i class="fas fa-times"></i> Close</button>
                <button type="button" class="btn-success" onclick="saveEditConf(true)"><i class="fas fa-save"></i> Update & Close</button>
                <button type="button" class="btn-primary" onclick="saveEditConf(false)"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
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
                        {{-- ✅ حقل الكمية قابل للتعديل --}}
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
<style>
    /* تنسيق CSS لتمييز الصف المحدد */
    .row-selected {
        background-color: #ffe0b2 !important; /* لون برتقالي فاتح */
        border-left: 5px solid #ff9800; /* شريط برتقالي على اليسار */
        font-weight: 500;
    }
    /* يمكنك إضافة أي تنسيقات أخرى للجدول هنا */
</style>