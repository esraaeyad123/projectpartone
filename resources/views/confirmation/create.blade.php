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
            <div id="confTab" class="form-tab-content active modal-scrollable-content">

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
                            <input type="text" id="confirmID" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder="Automatically Generated">
                        </div>
                        <div class="form-group">
                            <label for="confirmDate">Confirm Date:</label>
                            <input type="date" id="confirmDate" name="confirm_date" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="form-row" style="display: flex; gap: 15px; flex-wrap: wrap;">


                        <div class="form-group" style="flex: 1;">
                            <label for="projectCodeSelect">Project Code:</label>
                            <select id="projectCodeSelect" name="project_code">
                                <option value="" disabled selected>[Select Project Code]</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}"
                                    data-customer="{{ $project->customer_id }}">
                                    {{ $project->reference }}
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group" style="flex: 1;">
                            <label for="projectNameInput">Project Name:</label>
                            <input type="text" id="projectNameInput" name="project_name" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder="Auto-filled">
                            {{-- You might need a hidden input if the backend expects a specific ID --}}
                            <input type="hidden" id="projectNameHidden" name="project_name_hiddin">
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label for="customerNameInput">Customer Name:</label>
                            <input type="text" id="customerNameInput" name="customer_name_display" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder="Auto-filled">
                            {{-- You might need a hidden input for the Customer ID --}}
                            <input type="hidden" id="customerIDHidden" name="customer_id">
                        </div>
                    </div>




                    <div class="form-row">
                        <div class="form-group full-width">
                            <label for="projectDetails">Project Details:</label>
                            <input type="text" id="projectDetails" name="project_details" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder="Auto-filled">
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
                            <label for="contactPersonInput">Contact Name:</label>
                            <input type="text" id="contactPersonInput" name="contact_person" placeholder="e.g., Contact Person Name">
                        </div>
                        <div class="form-group">
                            <label for="confToInput">Email:</label>
                            <input type="text" id="confToInput" name="conf_to" placeholder="e.g., Destination/Recipient">
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
            <div id="contactTab" class="form-tab-content modal-scrollable-content" style="display: none;">

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
                <button type="button" class="btn-success" onclick="openFileManager('conf')"><i class="fas fa-folder-open"></i> File Manager</button>
                <button type="button" class="btn-success" onclick="saveConf(true)"><i class="fas fa-save"></i> Save & Close</button>
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
<!-- التعديل -->
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
            {{-- تم إضافة class "modal-scrollable-content" هنا --}}
            <div id="editConfTab" class="form-tab-content active modal-scrollable-content">

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
                            <input type="text" id="editConfirmID" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder="Automatically Generated">
                        </div>
                        <div class="form-group">
                            <label for="editConfirmDate">Confirm Date:</label>
                            <input type="date" id="editConfirmDate" name="confirm_date" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="editProjectCode">Project Code:</label>
                            <select id="editProject_code" name="project_code">
                                <option value="" disabled selected>[Select Project Code]</option>
                                @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->reference }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editProjectName">Project Name:</label>
                            <select id="editProject_name" name="project_name">
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
                            <label for="editContactPerson">Contact Name:</label>
                            <input type="text" id="editContactPerson" name="contact_person">
                        </div>
                        <div class="form-group">
                            <label for="editConfTo">Email:</label>
                            <input type="text" id="editConfTo" name="conf_to">
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
            {{-- // CONFIRMATION LINE TAB (EDIT) --}}
            {{-- // ---------------------------------------------------------------------------------------- --}}
            {{-- تم نسخ هذا الجزء بالكامل من مودال الإضافة وتعديل المعرفات الداخلية --}}
            <div id="editContactTab" class="form-tab-content modal-scrollable-content" style="display: none;">

                {{-- 1. Services/Test Section (الخدمات/الاختبارات) --}}
                <fieldset class="form-section-fieldset">
                    <legend>Services/Test</legend>

                    {{-- Buttons (أزرار أيقونات فقط) --}}
                    <div class="contact-toolbar" style="border-bottom: none; padding-bottom: 5px;">
                        {{-- تم تعديل onclick لاستخدام 'addServiceLine' بدون متغيرات في الإضافة --}}
                        <button type="button" class="btn btn-primary" onclick="addServiceLine()" title="إضافة (Add)">
                            <i class="fas fa-plus"></i>
                        </button>
                        {{-- تم تعديل معرف الجدول ليتوافق مع التعديل --}}
                        <button type="button" class="btn-secondary" onclick="editSelectedServiceLine('#servicesTableEdit')" title="تعديل (Edit)">
                            <i class="fas fa-pen"></i>
                        </button>
                        {{-- تم تعديل معرف الجدول ليتوافق مع التعديل --}}
                        <button type="button" class="btn-danger" onclick="deleteSelectedServiceLine('#servicesTableEdit')" title="حذف (Delete)">
                            <i class="fas fa-trash"></i>
                        </button>
                        {{-- تم تعديل معرف الجدول ليتوافق مع التعديل --}}
                        <button title="تصدير لاكسل (Export to Excel)" onclick="exportServiceLineExcel('servicesTableEdit')" class="btn-icon">
                            <i class="fa-solid fa-table"></i>
                        </button>
                        {{-- تم تعديل معرف الجدول ليتوافق مع التعديل --}}
                        <button title="طباعة (Print)" onclick="printServiceLineTable('servicesTableEdit')" class="btn-icon">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>

                    {{-- Table (هيكل الجدول الصحيح) --}}
                    <div class="table-responsive-container">
                        {{-- تم تعديل معرف الجدول ليصبح servicesTableEdit --}}
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
                    <div id="uploadedFilesAreaEdit">
                        {{-- Files will be listed here --}}
                        <div id="fileIconsContainerEdit"></div>

                    </div>
                </fieldset>

            </div>


            <div class="form-buttons modal-bottom-buttons">
                {{-- تم تعديل دالة الإغلاق لـ editConfModal --}}
                <button type="button" class="btn-primary" onclick="closeModal('editConfModal')"><i class="fas fa-times"></i> Close</button>
                {{-- تم تعديل الـ mode لـ openFileManager --}}
                <button type="button" class="btn-success" onclick="openFileManager('editConf')">
                    <i class="fas fa-folder-open"></i> File Manager
                </button>
                {{-- تم تعديل دالة الحفظ لـ saveEditConf --}}
                <button type="button" class="btn-success" onclick="saveEditConf(true)"><i class="fas fa-save"></i> Update & Close</button>
                {{-- تم تعديل دالة الحفظ لـ saveEditConf --}}
                <button type="button" class="btn-primary" onclick="saveEditConf(false)"><i class="fas fa-save"></i> Update</button>
            </div>
        </form>
    </div>
</div>
<!-- ------------------------------------------------------------------------------------------------------ -->
<style>
    /* تنسيق CSS لتمييز الصف المحدد */
    .row-selected {
        background-color: #ffe0b2 !important;
        /* لون برتقالي فاتح */
        border-left: 5px solid #ff9800;
        /* شريط برتقالي على اليسار */
        font-weight: 500;
    }

    /* حاوية الملفات */
#uploadedFilesArea, #uploadedFilesAreaEdit {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 12px;
    padding: 10px;
}

/* بطاقة الملف */
.file-card {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 8px;
    position: relative;
    transition: transform 0.2s, box-shadow 0.2s;
}

.file-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* محتوى البطاقة */
.file-card-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.file-icon {
    font-size: 32px;
    margin-bottom: 6px;
    color: #555;
}

.file-card-name {
    font-size: 13px;
    word-break: break-word;
}

/* تفاصيل عند hover */
.file-card-hover-details {
    display: none;
    margin-top: 6px;
    font-size: 11px;
    color: #666;
}

.file-card:hover .file-card-hover-details {
    display: block;
}

/* أزرار العمليات */
.file-card-actions-hover {
    display: flex;
    justify-content: center;
    gap: 6px;
    margin-top: 4px;
}

.btn-icon {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    font-size: 14px;
    color: #555;
    transition: color 0.2s;
}

.btn-icon:hover {
    color: #007bff;
}


    /* يمكنك إضافة أي تنسيقات أخرى للجدول هنا */
</style>
<!-- ------------------------------------------------------------------------------------------------------ -->
<script>
    // -----------------------------------------------------------
    // 1. إعداد خرائط البيانات (Data Maps) من PHP/Laravel
    // -----------------------------------------------------------
    const projectsMap = {};
    const customersMap = {};

    // تعبئة خريطة المشاريع
    @foreach($projects as $project)
    projectsMap['{{ $project->id }}'] = {
        name: '{{ $project->name }}',
        details: '{{ $project->project_details }}',
        customer_id: '{{ $project->customer_id }}',
        // لا نحتاج لـ reference هنا حيث أن القيمة في القائمة المنسدلة هي الـ ID
    };
    @endforeach

    // تعبئة خريطة العملاء (نفترض أن لديك قائمة جهات الاتصال لكل عميل)
    @foreach($customers as $customer)
    customersMap['{{ $customer->id }}'] = {
        name: '{{ $customer->customer_name }}',
        // هذا هو المفتاح الجديد لجهات الاتصال، يجب أن يكون $customer->contacts مصفوفة في Laravel
        contacts: @json($customer->contacts ?? []),
    };
    @endforeach


    // -----------------------------------------------------------
    // 2. دالة التعبئة التلقائية والتفعيل عند تحميل الصفحة
    // -----------------------------------------------------------
    document.addEventListener('DOMContentLoaded', function() {
        // IDs المستخدمة لمودال "Add New Confirmation"
        const projectCodeSelect = document.getElementById('projectCodeSelect');
        const projectNameInput = document.getElementById('projectNameInput');
        const customerNameInput = document.getElementById('customerNameInput');
        const projectDetailsInput = document.getElementById('projectDetails');
        const customerIdHidden = document.getElementById('customerIDHidden');

        // IDs المستخدمة لجهات الاتصال
        const contactPersonInput = document.getElementById('contactPersonInput');
        const confToInput = document.getElementById('confToInput'); // الإيميل

        if (projectCodeSelect) {
            projectCodeSelect.addEventListener('change', function() {
                const selectedProjectId = projectCodeSelect.value;
                const project = projectsMap[selectedProjectId];

                // مسح جميع الحقول إذا لم يتم العثور على المشروع
                if (!project) {
                    projectNameInput.value = '';
                    customerNameInput.value = '';
                    projectDetailsInput.value = '';
                    customerIdHidden.value = '';
                    contactPersonInput.value = '';
                    confToInput.value = '';
                    return;
                }

                // أ. تعبئة بيانات المشروع والعميل
                if (projectNameInput) projectNameInput.value = project.name || '';
                if (projectDetailsInput) projectDetailsInput.value = project.details  || '';

                const customer = customersMap[project.customer_id];
                if (customer) {
                    if (customerNameInput) customerNameInput.value = customer.name || '';
                    if (customerIdHidden) customerIdHidden.value = project.customer_id;

                    // ب. تعبئة معلومات الاتصال (كما هو مطلوب: الاسم والإيميل من جهة الاتصال الرئيسية)

                    // البحث عن جهة الاتصال الرئيسية (نفترض أن أول جهة اتصال هي الرئيسية)
                    const primaryContact = customer.contacts.length > 0 ? customer.contacts[0] : null;

                    if (primaryContact) {
                        // يجب أن تحتوي بيانات جهة الاتصال على خاصيتين (name و email)
                        if (contactPersonInput) contactPersonInput.value = primaryContact.name || '';
                        if (confToInput) confToInput.value = primaryContact.email || '';
                    } else {
                        // مسح حقول الاتصال إذا لم يتم العثور على جهة اتصال
                        if (contactPersonInput) contactPersonInput.value = '';
                        if (confToInput) confToInput.value = '';
                    }
                } else {
                    // مسح حقول العميل إذا لم يتم العثور عليه
                    if (customerNameInput) customerNameInput.value = 'Customer Not Found';
                    if (customerIdHidden) customerIdHidden.value = '';
                    if (contactPersonInput) contactPersonInput.value = '';
                    if (confToInput) confToInput.value = '';
                }
            });
        }

        // هنا يمكنك إضافة منطق التعبئة التلقائية لمودال التعديل (Edit Modal) بنفس الطريقة
    });
</script>
