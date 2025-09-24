<div id="addTestModal" class="modal">
    <div class="modal-content new-project-modal-design">
        <span class="close-btn" onclick="closeAddTestModal()"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Add Service/Test 🧪</h2>

        <form id="addTestForm">
            <!-- 1. Service/Test Information -->
            <fieldset class="form-section-fieldset">
                <legend>Service/Test Information</legend>

                <div class="form-row">
                    <div class="form-group">
                        <label for="testId">Service/Test ID:</label>
                        <input type="text" id="testId" readonly placeholder="Automatically Generated">
                    </div>
                    <div class="form-group">
                        <label for="testCode">Test Code:</label>
                        <input type="text" id="testCode">
                    </div>
                    <div class="form-group">
                        <label for="shortName">Short Name:</label>
                        <input type="text" id="shortName">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="serviceGroup">Service Group:</label>
                        <select id="serviceGroup">
                            <option value="">اختر مجموعة الخدمة</option>
                            <option value="soil">Soil</option>
                            <option value="concrete">Concrete</option>
                            <option value="water">Water</option>
                            <option value="environmental">Environmental</option>
                            <option value="mechanical">Mechanical</option>
                            <option value="electrical">Electrical</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="department">Department:</label>
                         <select id="department">
                            <option value="">اختر مجموعة </option>
                            <option value="soil">Soil</option>
                            <option value="concrete">Concrete</option>
                            <option value="water">Water</option>
                            <option value="environmental">Environmental</option>
                            <option value="mechanical">Mechanical</option>
                            <option value="electrical">Electrical</option>
                        </select>
                    </div>
                    <div class="form-group-checkbox">
                        <input type="checkbox" id="generateReport">
                        <label for="generateReport">Generate Report</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" rows="2"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="type">Type:</label>
                           <select id="type">
                            <option value="">اختر نوع النشاط</option>
                            <option value="soilTesting">Soil Testing</option>
                            <option value="concreteTesting">Concrete Testing</option>
                            <option value="materialTesting">Material Testing</option>
                            <option value="environmentalAnalysis">Environmental Analysis</option>
                            <option value="mechanicalTesting">Mechanical Testing</option>
                            <option value="electricalTesting">Electrical Testing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="activityType">Activity Type:</label>
                        <select id="activityType">
                            <option value="">اختر نوع النشاط</option>
                            <option value="soilTesting">Soil Testing</option>
                            <option value="concreteTesting">Concrete Testing</option>
                            <option value="materialTesting">Material Testing</option>
                            <option value="environmentalAnalysis">Environmental Analysis</option>
                            <option value="mechanicalTesting">Mechanical Testing</option>
                            <option value="electricalTesting">Electrical Testing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="dateAdded">Date Added:</label>
                        <input type="date" id="dateAdded" value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="location">Location / Sub Location:</label>
                         <select id="location">
                            <option value="">اختر نوع النشاط</option>
                            <option value="soilTesting">Soil Testing</option>
                            <option value="concreteTesting">Concrete Testing</option>
                            <option value="materialTesting">Material Testing</option>
                            <option value="environmentalAnalysis">Environmental Analysis</option>
                            <option value="mechanicalTesting">Mechanical Testing</option>
                            <option value="electricalTesting">Electrical Testing</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <!-- 2. Test Settings -->
            <fieldset class="form-section-fieldset">
                <legend>Test Settings</legend>
                <div class="form-row">
                    <div class="form-group">
                        <label for="testMethod">Test Method:</label>
                        <input type="text" id="testMethod">
                    </div>
                </div>
            </fieldset>

            <!-- 3. Template Settings -->
            <fieldset class="form-section-fieldset">
                <legend>Template Settings</legend>
                <div class="form-row">
                    <div class="form-group">
                        <label for="templateName">Template Name:</label>
                        <input type="text" id="templateName" placeholder="اسم القالب المستخدم للتقرير">
                    </div>
                    <div class="form-group">
                        <label for="templateType">Template Type:</label>
                        <select id="templateType">
                            <option value="">اختر نوع القالب</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                            <option value="builtIn">Built-in Form</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fileTemplate">File Template:</label>
                        <input type="text" id="fileTemplate" >
                    </div>
                    <div class="form-group">
                        <label for="reportDesignation">Report Designation:</label>
                        <input type="text" id="reportDesignation" >
                    </div>
                    <div class="form-group">
                        <label for="reportTitle">Report Title:</label>
                        <input type="text" id="reportTitle" >
                    </div>
                    <div class="form-group">
                        <label for="element">Element:</label>
                        <select id="element">
                            <option value="">اختر العنصر</option>
                            <option value="civil">Civil</option>
                            <option value="mechanical">Mechanical</option>
                            <option value="electrical">Electrical</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group-checkbox">
                        <input type="checkbox" id="useUncertainty">
                        <label for="useUncertainty">Use Uncertainty in Report</label>
                    </div>
                </div>
            </fieldset> <!-- ✅ إغلاق مفقود -->

            <!-- 4. Unit Price -->
            <fieldset class="form-section-fieldset">
                <legend>Unit Price</legend>
                <div class="form-row">
                    <div class="form-group">
                        <label for="unitPrice">Unit Price:</label>
                        <input type="number" id="unitPrice" step="0.01">
                    </div>
                </div>
            </fieldset>

             <div class="form-row">
    <div class="form-group">
        <label for="value">Uncertainty:</label>
        <input type="text" id="value" name="value" placeholder="±2%, ±0.5 MPa">
    </div>
    <div class="form-group">
        <label for="date_recorded">Date:</label>
        <input type="date" id="date_recorded" name="date_recorded" value="{{ date('Y-m-d') }}">
    </div>
</div>

            <!-- أزرار الحفظ والإغلاق -->
            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="closeAddTestModal()">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="button" class="btn-success" onclick="saveTest(true)">
                    <i class="fas fa-save"></i> Save & Close
                </button>
                <button type="button" class="btn-primary" onclick="saveTest(false)">
                    <i class="fas fa-save"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>


<div id="editTestModal" class="modal">
    <div class="modal-content new-project-modal-design">
        <span class="close-btn" onclick="$('#editTestModal').hide()"><i class="fas fa-times"></i></span>
        <h2 class="modal-title">Edit Service/Test 🧪</h2>

        <form id="editTestForm">
            <input type="hidden" id="editTestId">

            <!-- 1. Service/Test Information -->
            <fieldset class="form-section-fieldset">
                <legend>Service/Test Information</legend>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editTestCode">Test Code:</label>
                       <input type="text" id="editTestCode" readonly style="background-color: #e9ecef; cursor: not-allowed;" placeholder = "Automatically Generated">

                    </div>
                    <div class="form-group">
                        <label for="editShortName">Short Name:</label>
                        <input type="text" id="editShortName">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editServiceGroup">Service Group:</label>
                        <select id="editServiceGroup">
                            <option value="">اختر مجموعة الخدمة</option>
                            <option value="soil">Soil</option>
                            <option value="concrete">Concrete</option>
                            <option value="water">Water</option>
                            <option value="environmental">Environmental</option>
                            <option value="mechanical">Mechanical</option>
                            <option value="electrical">Electrical</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editDepartment">Department:</label>
                        <input type="text" id="editDepartment">
                    </div>
                    <div class="form-group-checkbox">
                        <input type="checkbox" id="editGenerateReport">
                        <label for="editGenerateReport">Generate Report</label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editDescription">Description:</label>
                        <textarea id="editDescription" rows="2"></textarea>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editType">Type:</label>
                        <input type="text" id="editType">
                    </div>
                    <div class="form-group">
                        <label for="editActivityType">Activity Type:</label>
                        <select id="editActivityType">
                            <option value="">اختر نوع النشاط</option>
                            <option value="soilTesting">Soil Testing</option>
                            <option value="concreteTesting">Concrete Testing</option>
                            <option value="materialTesting">Material Testing</option>
                            <option value="environmentalAnalysis">Environmental Analysis</option>
                            <option value="mechanicalTesting">Mechanical Testing</option>
                            <option value="electricalTesting">Electrical Testing</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editDateAdded">Date Added:</label>
                        <input type="date" id="editDateAdded">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="editLocation">Location / Sub Location:</label>
                        <input type="text" id="editLocation">
                    </div>
                </div>
            </fieldset>

            <!-- 2. Test Settings -->
            <fieldset class="form-section-fieldset">
                <legend>Test Settings</legend>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editTestMethod">Test Method:</label>
                        <input type="text" id="editTestMethod">
                    </div>
                </div>
            </fieldset>

            <!-- 3. Template Settings -->
            <fieldset class="form-section-fieldset">
                <legend>Template Settings</legend>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editTemplateName">Template Name:</label>
                        <input type="text" id="editTemplateName">
                    </div>
                    <div class="form-group">
                        <label for="editTemplateType">Template Type:</label>
                        <select id="editTemplateType">
                            <option value="">اختر نوع القالب</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                            <option value="builtIn">Built-in Form</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editFileTemplate">File Template:</label>
                        <input type="text" id="editFileTemplate">
                    </div>
                    <div class="form-group">
                        <label for="editReportDesignation">Report Designation:</label>
                        <input type="text" id="editReportDesignation">
                    </div>
                    <div class="form-group">
                        <label for="editReportTitle">Report Title:</label>
                        <input type="text" id="editReportTitle">
                    </div>
                    <div class="form-group">
                        <label for="editElement">Element:</label>
                        <select id="editElement">
                            <option value="">اختر العنصر</option>
                            <option value="civil">Civil</option>
                            <option value="mechanical">Mechanical</option>
                            <option value="electrical">Electrical</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group-checkbox">
                        <input type="checkbox" id="editUseUncertainty">
                        <label for="editUseUncertainty">Use Uncertainty in Report</label>
                    </div>
                </div>
            </fieldset>

            <!-- 4. Unit Price -->
            <fieldset class="form-section-fieldset">
                <legend>Unit Price</legend>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editUnitPrice">Unit Price:</label>
                        <input type="number" id="editUnitPrice" step="0.01">
                    </div>
                </div>
            </fieldset>

            <!-- 5. Uncertainty History -->
         <fieldset class="form-section-fieldset">
    <legend>Uncertainty History</legend>
    <div class="form-row">
        <div class="form-group">
            <label for="editUncertainty">Uncertainty:</label>
            <input type="text" id="editUncertainty" placeholder="±2%, ±0.5 MPa">
        </div>
        <div class="form-group">
            <label for="editUncertaintyDate">Date:</label>
            <input type="date" id="editUncertaintyDate" value="{{ date('Y-m-d') }}">
        </div>
    </div>
    <div class="form-group">
        <button type="button" class="btn-success" onclick="addUncertaintyHistory()">
            Add
        </button>
    </div>
    <table id="editUncertaintyHistory" class="contacts-table display responsive nowrap">
        <thead>
            <tr>
                <th>Date</th>
                <th>Uncertainty</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</fieldset>



            <!-- Buttons -->
            <div class="form-buttons modal-bottom-buttons">
                <button type="button" class="btn-primary" onclick="$('#editTestModal').hide()">
                    <i class="fas fa-times"></i> Close
                </button>
                <button type="button" class="btn-success" onclick="saveEditTest(true)">
                    <i class="fas fa-save"></i> Save & Close
                </button>
            </div>
        </form>
    </div>
</div>
