<div id="confModal" class="modal">
    <div class="modal-content new-conf-modal-design" style="width: 900px; max-width: 95%;">
        <span class="close-btn" onclick="closeModal('confModal')"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Add New Delivery 🚚</h2>

        <div class="tab-buttons">
            <button id="conf-btn" onclick="switchTab('conf')" class="active"><i class="fas fa-truck"></i> Delivery Header</button>
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

    {{-- ---------------------------------- --}}
    {{-- Project Selection --}}
    <div class="form-row">
        <div class="form-group">
            <label for="projectCodeSelect">Project Code:</label>
            <select id="projectCodeSelect" name="project_code">
                <option value="" disabled selected>[Select Project Code]</option>
                @foreach($projects as $project)
                    <option value="{{ $project->reference }}"
                            data-id="{{ $project->id }}"
                            data-name="{{ $project->name }}"
                            data-details="{{ $project->project_details ?? '' }}"
                            data-customer="{{ $project->customer_id }}">
                        {{ $project->reference }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="projectNo">Project No:</label>
            <input type="text" id="projectNo" name="project_no" placeholder="(فارغ)" readonly>
        </div>

        <div class="form-group">
            <label for="projectNameSelect">Project Name:</label>
            <input type="text" id="projectNameSelect" name="project_name" readonly>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group full-width">
            <label for="projectDetails">Details:</label>
            <input type="text" id="projectDetails" name="project_details" readonly>
        </div>
    </div>
</fieldset>

<fieldset class="form-section-fieldset">
    <legend>Customer Info</legend>
    <div class="form-row">
        <div class="form-group">
            <label for="customerID">Customer ID:</label>
            <input type="text" id="customerID" name="customer_id_ref" readonly style="background-color: #e9ecef; cursor: not-allowed;">
        </div>
        <div class="form-group">
            <label for="customerSelect">Customer:</label>
            <input type="text" id="customerSelect" name="customer_name" readonly>
        </div>
        <div class="form-group">
                            <label for="accountNo">Account No:</label>
                            <input type="text" id="accountNo" name="account_no" placeholder="(فارغ)">
                        </div>
        <div class="form-group">
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" readonly>
        </div>
    </div>
</fieldset>

<fieldset class="form-section-fieldset">
    <legend>Contact Info</legend>
    <div class="form-row">
        <div class="form-group">
            <label for="deliveryContact">Contact:</label>
 <select id="deliveryContact" name="contact_person">
        <option value="" disabled selected>[Select Contact]</option>
        {{-- سيتم تعبئته بواسطة JS عند اختيار Project Code --}}
    </select>
</div>
        <div class="form-group">
            <label for="attnTo">Attn. To:</label>
            <input type="text" id="attnTo" name="attn_to">
        </div>
        <div class="form-group">
            <label for="attnPos">Attn. Pos:</label>
            <input type="text" id="attnPos" name="attn_pos">
        </div>
    </div>
    <div class="form-row">
        <div class="form-group full-width">
            <label for="contactEmail">Address/Email:</label>
            <input type="text" id="contactEmail" name="contactEmail">
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
                    <legend>Customer Report</legend>

                    {{-- Buttons (أزرار أيقونات فقط) --}}
                    <div class="contact-toolbar" style="border-bottom: none; padding-bottom: 5px;">
                        <button type="button" class="btn btn-primary" onclick="sendToCustomer()" title="إضافة (Add)">
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
                <button type="button" class="btn-success" onclick="saveDeli(true)"><i class="fas fa-save"></i> Save & Close</button>
                <button type="button" class="btn-primary" onclick="saveDeli(false)"><i class="fas fa-save"></i> Save</button>
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
            <button id="edit-conf-btn" onclick="switchTab('edit-conf')" class="active"><i class="fas fa-truck"></i> Delivery Delivery Header</button>
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
                             <select id="editProjectNo" name="project_code">
                <option value="" disabled selected>[Select Project Code]</option>
                @foreach($projects as $project)
                  <option value="{{ $project->reference }}"
        data-id="{{ $project->id }}"
        data-name="{{ $project->name }}"
        data-details="{{ $project->project_details ?? '' }}"
        data-customer="{{ $project->customer_id }}"
        data-account="{{ $project->customer->account_no ?? '' }}"
        data-location="{{ $project->customer->city ?? '' }}">
    {{ $project->reference }}
</option>

                @endforeach
            </select>
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
                    <legend>Customer Report</legend>

                    {{-- Buttons (أزرار أيقونات فقط) --}}
                    <div class="contact-toolbar" style="border-bottom: none; padding-bottom: 5px;">
                        <button type="button" class="btn btn-primary" onclick="sendToCustomer()" title="إضافة (Add)">
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



<!-- <div id="serviceSelectionModal" class="modal" style="display: none;">
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
</style> -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const projectCodeSelect = document.getElementById('projectCodeSelect');
    const projectNameInput = document.getElementById('projectNameSelect');
    const projectDetailsInput = document.getElementById('projectDetails');
    const projectNoInput = document.getElementById('projectNo');

    const customerIdInput = document.getElementById('customerID');
    const customerNameInput = document.getElementById('customerSelect');
    const accountNoInput = document.getElementById('accountNo');
    const locationInput = document.getElementById('location');

    const deliveryContactSelect = document.getElementById('deliveryContact');
    const attnToInput = document.getElementById('attnTo');
    const attnPosInput = document.getElementById('attnPos');
    const contactEmailInput = document.getElementById('contactEmail');
    const contactPhoneInput = document.getElementById('contactPhone');
    const contactMobileInput = document.getElementById('contactMobile');

    // Projects map with customer_id
    const projectsMap = {
        @foreach($projects as $project)
            "{{ $project->reference }}": {
                id: @json($project->id),
                name: @json($project->name),
                details: @json($project->project_details ?? ''),
                customer_id: @json($project->customer_id),
            },
        @endforeach
    };

    // Customers map with contacts
    const customersMap = {
        @foreach($customers as $customer)
            "{{ $customer->id }}": {
                customer_name: @json($customer->customer_name),
                account_no: @json($customer->account_no ?? ''),
                city: @json($customer->city ?? ''),
                contacts: @json($customer->contacts ?? [])
            },
        @endforeach
    };

    // عند اختيار Project Code
    projectCodeSelect.addEventListener('change', function() {
        const selectedCode = projectCodeSelect.value;
        if (!selectedCode || !projectsMap[selectedCode]) return;

        const project = projectsMap[selectedCode];

        // تعبئة بيانات المشروع
        projectNameInput.value = project.name;
        projectDetailsInput.value = project.details;
        projectNoInput.value = project.id;

        // تعبئة بيانات العميل
        const customer = customersMap[project.customer_id];
        if (customer) {
            customerIdInput.value = project.customer_id;
            customerNameInput.value = customer.customer_name;
            accountNoInput.value = customer.account_no;
            locationInput.value = customer.city;

            // تعبئة جهات الاتصال الخاصة بالعميل
            deliveryContactSelect.innerHTML = '<option value="" disabled selected>[Select Contact]</option>';
            customer.contacts.forEach(contact => {
                const option = document.createElement('option');
                option.value = contact.id;
                option.textContent = contact.mobile + (contact.name ? ' (' + contact.name + ')' : '');
                // تخزين البيانات الكاملة كـ dataset لاستخدامها لاحقاً
                option.dataset.name = contact.name;
                option.dataset.position = contact.position || '';
                option.dataset.email = contact.email || '';
                option.dataset.mobile = contact.mobile || '';
                deliveryContactSelect.appendChild(option);
            });
        }
    });

    // عند اختيار جهة اتصال
    deliveryContactSelect.addEventListener('change', function() {
        const selectedOption = deliveryContactSelect.selectedOptions[0];
        if (!selectedOption) return;

        attnToInput.value = selectedOption.dataset.name;
        attnPosInput.value = selectedOption.dataset.position;
        contactEmailInput.value = selectedOption.dataset.email;
        contactPhoneInput.value = selectedOption.dataset.phone || '';
        contactMobileInput.value = selectedOption.dataset.mobile || '';
    });
});

// نسخة JQuery لصفحة تعديل المشروع
$('#editProjectCodeSelect').on('change', function() {
    const selectedOption = $(this).find('option:selected');

    // تحديث Project Name / Project No / Details
    $('#editProjectNameSelect').val(selectedOption.data('name'));
    $('#editProjectNo').val(selectedOption.data('id'));
    $('#editProjectDetails').val(selectedOption.data('details'));

    // تحديث Customer
    const customerId = selectedOption.data('customer');
    $('#editCustomerID').val(customerId);
    $('#editCustomerSelect').val(customerId);
    $('#editAccountNo').val(selectedOption.data('account') || '');
    $('#editLocation').val(selectedOption.data('location') || '');
});
</script>




<style>
.modal {
  display: none; /* افتراضيًا يكون مخفي */
  position: fixed;
  z-index: 9999;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow-y: auto; /* 👈 يسمح بالسكرول في الصفحة كاملة عند فتح المودال */
  background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
  margin: 40px auto;
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  max-height: 90vh; /* 👈 يحدد أقصى ارتفاع للمودال */
  overflow-y: auto; /* 👈 يسمح بسكرول داخل المودال نفسه */
}

</style>
