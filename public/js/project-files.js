let currentProjectId = null; // ID المشروع الحالي
let currentProjectName = "N/A"; // اسم المشروع الحالي (ليتم عرضه)
// تم إزالة 'projectFilesDataTable;' لأننا لن نستخدم DataTable للعرض الرئيسي

// =================== دوال مساعدة للأيقونات ===================

/**
 * دالة مساعدة للحصول على أيقونة بناءً على نوع الملف.
 * @param {string} fileName - اسم الملف بالكامل (مثال: document.pdf).
 * @returns {string} - كود HTML لأيقونة Font Awesome.
 */
function getFileIcon(fileName) {
    const extension = fileName.split('.').pop().toLowerCase();
    switch (extension) {
        case 'pdf':
            return '<i class="far fa-file-pdf file-icon pdf-icon"></i>';
        case 'doc':
        case 'docx':
            return '<i class="far fa fa-file-word file-icon doc-icon"></i>';
        case 'xls':
        case 'xlsx':
            return '<i class="far fa-file-excel file-icon xls-icon"></i>';
        case 'ppt':
        case 'pptx':
            return '<i class="far fa-file-powerpoint file-icon ppt-icon"></i>';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            return '<i class="far fa-file-image file-icon img-icon"></i>';
        case 'zip':
        case 'rar':
            return '<i class="far fa-file-archive file-icon zip-icon"></i>';
        case 'txt':
            return '<i class="far fa-file-alt file-icon txt-icon"></i>';
        default:
            return '<i class="far fa-file file-icon default-icon"></i>'; // أيقونة افتراضية
    }
}

// =================== دوال تحميل وتهيئة الصفحة ===================

/**
 * دالة لجلب ID واسم المشروع من localStorage عند تحميل الصفحة.
 */
function getProjectInfoFromLocalStorage() {
    const projectId = localStorage.getItem('selectedProjectId');
    const projectName = localStorage.getItem('selectedProjectName');

    if (projectId && projectName) {
        currentProjectId = projectId;
        currentProjectName = projectName;
        document.getElementById('project-name-display').textContent = `(${currentProjectName} - ID: ${currentProjectId})`;
        const uploadModalProjectNameElem = document.getElementById('uploadModalProjectName');
        if (uploadModalProjectNameElem) {
            uploadModalProjectNameElem.textContent = currentProjectName;
        }
        // تحميل وعرض الملفات فور جلب معلومات المشروع
        loadAndRenderProjectFiles();
    } else {
        showAlert("لم يتم تحديد مشروع. الرجاء العودة إلى صفحة المشاريع وتحديد مشروع.", "error"); // التعديل
        console.warn("No project ID or name found in localStorage. Please select a project.");
    }
}

/**
 * جلب الملفات المخزنة محليًا لمشروع معين.
 * (في تطبيق حقيقي، ستكون هذه مكالمة API للخادم)
 * @returns {Array} قائمة بالملفات.
 */
function getProjectFiles(projectId) {
    const allFiles = JSON.parse(localStorage.getItem('projectFiles')) || {};
    return allFiles[projectId] || [];
}

/**
 * حفظ الملفات المخزنة محليًا لمشروع معين.
 * (في تطبيق حقيقي، ستكون هذه مكالمة API للخادم)
 * @param {string} projectId - معرف المشروع.
 * @param {Array} files - قائمة الملفات للمشروع.
 */
function saveProjectFiles(projectId, files) {
    let allFiles = JSON.parse(localStorage.getItem('projectFiles')) || {};
    allFiles[projectId] = files;
    localStorage.setItem('projectFiles', JSON.stringify(allFiles));
}

/**
 * تحميل الملفات الموجودة للمشروع الحالي وعرضها كأيقونات.
 */
function loadAndRenderProjectFiles() {
    const fileIconsContainer = document.getElementById('fileIconsContainer');
    if (!fileIconsContainer) {
        console.error("Container #fileIconsContainer not found!");
        return;
    }
    fileIconsContainer.innerHTML = ''; // مسح المحتوى القديم

    if (currentProjectId) {
        const existingFiles = getProjectFiles(currentProjectId);
        if (existingFiles.length === 0) {
            fileIconsContainer.innerHTML = `<p class="no-files-message" data-key="No files available">لا توجد ملفات متاحة. الرجاء رفع ملفات جديدة.</p>`;
        } else {
            existingFiles.forEach(file => {
                renderFileIcon(file); // استخدام الدالة الجديدة لعرض الأيقونات
            });
        }
    } else {
        fileIconsContainer.innerHTML = `<p class="no-files-message" data-key="No project selected">لم يتم تحديد مشروع. الرجاء العودة لصفحة المشاريع وتحديد مشروع.</p>`;
    }
}


// =================== دوال المودال (رفع الملف) ===================

/**
 * فتح مودال رفع الملفات.
 */
function openUploadModal() {
    const uploadModal = document.getElementById('uploadFileModal');
    if (uploadModal) {
        uploadModal.style.display = 'flex';
        document.getElementById('fileUploadForm').reset(); // مسح النموذج
        const uploadModalProjectNameElem = document.getElementById('uploadModalProjectName');
        if (uploadModalProjectNameElem && currentProjectName) {
            uploadModalProjectNameElem.textContent = currentProjectName;
        }
    }
}

/**
 * إغلاق مودال رفع الملفات.
 */
function closeUploadModal() {
    const uploadModal = document.getElementById('uploadFileModal');
    if (uploadModal) {
        uploadModal.style.display = 'none';
    }
}

/**
 * دالة لرفع ملف جديد.
 * (هذا مثال بسيط لا يقوم برفع ملفات فعلية، بل يحاكي العملية ويضيف البيانات إلى localStorage)
 * @param {Event} event - حدث الإرسال.
 */
function uploadFile(event) {
    event.preventDefault();
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    if (!file) {
        showAlert("الرجاء تحديد ملف للرفع.", "error"); // التعديل
        return;
    }

    if (!currentProjectId) {
        showAlert("لا يوجد مشروع محدد لرفع الملفات له. الرجاء العودة لصفحة المشاريع وتحديد مشروع.", "error"); // التعديل
        return;
    }

    const fileName = file.name;
    const fileType = file.type || "Unknown";
    const uploadDate = new Date().toISOString().split('T')[0];
    const fileSizeKB = (file.size / 1024).toFixed(2);

    const newFile = {
        id: Date.now().toString(), // ID فريد للملف
        name: fileName,
        type: fileType,
        uploadDate: uploadDate,
        size: `${fileSizeKB} KB`,
        path: `uploads/${currentProjectId}/${fileName}`
    };

    let files = getProjectFiles(currentProjectId);
    files.push(newFile);
    saveProjectFiles(currentProjectId, files);

    // عرض الملف الجديد كأيقونة
    renderFileIcon(newFile);

    showAlert(`تم رفع ملف "${fileName}" بنجاح.`, "success"); // التعديل
    closeUploadModal();
}

/**
 * دالة جديدة لعرض ملف كأيقونة في الشبكة.
 * تم تعديلها لعرض الأيقونة والاسم فقط بشكل افتراضي، وتفاصيل أخرى عند التمرير.
 * @param {Object} file - كائن الملف.
 */
function renderFileIcon(file) {
    const fileIconsContainer = document.getElementById('fileIconsContainer');
    if (!fileIconsContainer) return; // تأكد من وجود الحاوية

    // إزالة رسالة "لا توجد ملفات" إذا كانت موجودة
    const noFilesMessage = fileIconsContainer.querySelector('.no-files-message');
    if (noFilesMessage) {
        noFilesMessage.remove();
    }

    const fileCard = document.createElement('div');
    fileCard.className = 'file-card';
    fileCard.setAttribute('data-file-id', file.id); // إضافة data-file-id لتسهيل الحذف والتحميل

    const fileIconHtml = getFileIcon(file.name); // استخدام الدالة المساعدة

    fileCard.innerHTML = `
        <div class="file-card-content">
            <input type="checkbox" class="selectFile file-card-checkbox" value="${file.id}">
            ${fileIconHtml}
            <span class="file-card-name" title="${file.name}">${file.name}</span>
        </div>
        <div class="file-card-hover-details">
            <span class="file-card-date">Uploaded: ${file.uploadDate}</span>
            <span class="file-card-size">Size: ${file.size}</span>
            <div class="file-card-actions-hover">
                <button class="btn-icon view-file-btn" title="View" onclick="viewFile('${file.id}')"><i class="fas fa-eye"></i></button>
                <button class="btn-icon download-file-btn" title="Download" onclick="downloadFile('${file.id}')"><i class="fas fa-download"></i></button>
                <button class="btn-icon delete-file-btn" title="Delete" onclick="deleteFile('${file.id}')"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    `;
    fileIconsContainer.appendChild(fileCard);
}


// =================== دوال إدارة الملفات (للعرض الجديد) ===================

/**
 * تنزيل الملف المحدد.
 * (تم تعديلها لمحاكاة التنزيل باستخدام Blob و FileSaver.js)
 * @param {string} fileId - معرف الملف المراد تنزيله.
 */
function downloadFile(fileId) {
    let files = getProjectFiles(currentProjectId);
    const fileToDownload = files.find(f => f.id === fileId);

    if (fileToDownload) {
        showAlert(`جارٍ تنزيل ملف: ${fileToDownload.name}`, "info", "تنزيل"); // التعديل

        // محاكاة محتوى الملف - في تطبيق حقيقي سيكون هذا هو المحتوى الفعلي للملف
        const simulatedFileContent = `This is the simulated content for ${fileToDownload.name}.\nUploaded on: ${fileToDownload.uploadDate}\nSize: ${fileToDownload.size}`;
        const blob = new Blob([simulatedFileContent], { type: fileToDownload.type });

        // استخدام FileSaver.js لتنزيل الـ Blob
        saveAs(blob, fileToDownload.name);

        console.log(`Simulating download for file: ${fileToDownload.name}`);
    } else {
        showAlert("لم يتم العثور على الملف للتنزيل.", "error"); // التعديل
    }
}

/**
 * دالة لعرض الملف (غير متوفرة في المثال، تحتاج إلى تنفيذها).
 * @param {string} fileId - معرف الملف المراد عرضه.
 */
function viewFile(fileId) {
    let files = getProjectFiles(currentProjectId);
    const fileToView = files.find(f => f.id === fileId);
    if (fileToView) {
        showAlert(`جارٍ عرض ملف: ${fileToView.name}`, "info", "عرض"); // التعديل
        // هنا يمكنك فتح الملف في علامة تبويب جديدة أو في عارض داخل مودال
        // window.open(fileToView.path, '_blank');
        console.log(`Viewing file: ${fileToView.name} from ${fileToView.path}`);
    } else {
        showAlert("لم يتم العثور على الملف للعرض.", "error"); // التعديل
    }
}

/**
 * تنزيل الملفات المحددة.
 * (تم تعديلها لاستدعاء downloadFile لكل ملف محدد)
 */
function downloadSelectedFiles() {
    const selectedCheckboxes = document.querySelectorAll("#fileIconsContainer .selectFile:checked");

    if (selectedCheckboxes.length === 0) {
        showAlert("الرجاء تحديد ملف واحد على الأقل للتنزيل.", "warning", "لا يوجد تحديد");
        return;
    }

    let downloadInitiatedCount = 0;
    selectedCheckboxes.forEach(checkbox => {
        const fileId = checkbox.value;
        // استدعاء دالة downloadFile لكل ملف محدد
        // هذا سيؤدي إلى بدء محاكاة تنزيل منفصلة لكل ملف
        downloadFile(fileId);
        downloadInitiatedCount++;
    });

    if (downloadInitiatedCount > 0) {
        // يمكنك إظهار تنبيه عام يفيد ببدء عملية التنزيل
        showAlert(`تم بدء تنزيل ${downloadInitiatedCount} ملف(ملفات) محدد(ة).`, "info", "بدء التنزيل");
    } else {
        showAlert("لم يتم العثور على أي ملفات محددة لبدء التنزيل.", "error");
    }
}

/**
 * حذف ملف واحد من العرض و localStorage.
 * (سيتم تحديثها للعمل مع بنية الأيقونات)
 * @param {string} fileId - معرف الملف المراد حذفه.
 */
function deleteFile(fileId) {
    showConfirm("هل أنت متأكد أنك تريد حذف هذا الملف؟ لا يمكن التراجع عن هذا الإجراء.", () => { // التعديل
            let files = getProjectFiles(currentProjectId);
            const initialLength = files.length;
            files = files.filter(f => f.id !== fileId);
            saveProjectFiles(currentProjectId, files);

            if (files.length < initialLength) {
                // إزالة عنصر الملف من DOM مباشرة
                const fileCardToRemove = document.querySelector(`.file-card[data-file-id="${fileId}"]`);
                if (fileCardToRemove) {
                    fileCardToRemove.remove();
                    showAlert("تم حذف الملف بنجاح.", "success", "تم الحذف بنجاح");
                } else {
                    showAlert("لم يتم العثور على العنصر المرئي للملف للحذف.", "error");
                }
            } else {
                showAlert("لم يتم العثور على الملف للحذف.", "error");
            }
            // إذا لم تعد هناك ملفات، أظهر رسالة "لا توجد ملفات"
            const fileIconsContainer = document.getElementById('fileIconsContainer');
            if (fileIconsContainer && fileIconsContainer.children.length === 0) {
                fileIconsContainer.innerHTML = `<p class="no-files-message" data-key="No files available">لا توجد ملفات متاحة. الرجاء رفع ملفات جديدة.</p>`;
            }
        }, "تأكيد الحذف" // إضافة العنوان بعد الدالة (لأن المعامل الثاني أصبح دالة)
    );
}

/**
 * حذف الملفات المحددة من العرض و localStorage.
 * (سيتم تحديثها للعمل مع بنية الأيقونات)
 */
function deleteSelectedFiles() {
    const selectedCheckboxes = document.querySelectorAll("#fileIconsContainer .selectFile:checked");

    if (selectedCheckboxes.length === 0) {
        showAlert("الرجاء اختيار ملف واحد على الأقل للحذف.", "warning", "لا يوجد تحديد"); // التعديل
        return;
    }

    showConfirm(`هل أنت متأكد أنك تريد حذف ${selectedCheckboxes.length} ملف(ملفات) محدد(ة)؟ لا يمكن التراجع عن هذا الإجراء.`, // التعديل
        () => {
            let filesToDeleteIds = Array.from(selectedCheckboxes).map(box => box.value);
            let files = getProjectFiles(currentProjectId);
            const initialLength = files.length;

            files = files.filter(f => !filesToDeleteIds.includes(f.id));
            saveProjectFiles(currentProjectId, files);

            let deletedCount = 0;
            filesToDeleteIds.forEach(fileId => {
                const fileCardToRemove = document.querySelector(`.file-card[data-file-id="${fileId}"]`);
                if (fileCardToRemove) {
                    fileCardToRemove.remove();
                    deletedCount++;
                }
            });

            if (deletedCount > 0) {
                showAlert(`${deletedCount} ملف(ملفات) تم حذفها بنجاح.`, "success", "تم الحذف بنجاح"); // التعديل
            } else {
                showAlert("لم يتم العثور على أي ملفات محددة للحذف.", "error"); // التعديل
            }
            // بما أن زر تحديد الكل ليس مربع اختيار، لا يوجد حاجة لتغيير حالته هنا.
            // if (selectAllBtn) {
            //     selectAllBtn.checked = false;
            // }

            // إذا لم تعد هناك ملفات، أظهر رسالة "لا توجد ملفات"
            const fileIconsContainer = document.getElementById('fileIconsContainer');
            if (fileIconsContainer && fileIconsContainer.children.length === 0) {
                fileIconsContainer.innerHTML = `<p class="no-files-message" data-key="No files available">لا توجد ملفات متاحة. الرجاء رفع ملفات جديدة.</p>`;
            }
        }, "تأكيد الحذف" // إضافة العنوان بعد الدالة (لأن المعامل الثاني أصبح دالة)
    );
}

/**
 * تبديل حالة تحديد/إلغاء تحديد جميع مربعات الاختيار في الشبكة.
 * @param {boolean} checkState - true لتحديد الكل، false لإلغاء تحديد الكل.
 */
function toggleSelectAllFiles(checkState) {
    const allCheckboxes = document.querySelectorAll("#fileIconsContainer .selectFile");
    allCheckboxes.forEach((cb) => (cb.checked = checkState));
}


// =================== تهيئة الصفحة عند التحميل ===================

$(document).ready(function () {
    // تحميل الهيدر والناف بار
    $("#header-placeholder").load("header.html", function() {
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }
    });
    $("#navbar-placeholder").load("navbar.html");

    // جلب ID المشروع واسمه فور تحميل الصفحة من localStorage وبدء عرض الملفات
    getProjectInfoFromLocalStorage();

    // إزالة ربط زر "Select All" في رأس الأيقونات مع وظيفته (لأن #selectAllFiles أصبح زر)
    // const selectAllFilesBtn = document.getElementById('selectAllFiles');
    // if (selectAllFilesBtn) {
    //     selectAllFilesBtn.addEventListener('change', function() {
    //         toggleSelectAllFiles(this);
    //     });
    // }
    // ملاحظة: تم تعديل استدعاء toggleSelectAllFiles مباشرة في HTML لأزرار "Select All" و "Deselect All".

    // منطق البحث لفلترة بطاقات الملفات (يحتاج إلى تنفيذ مخصص)
    document.getElementById('fileSearchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const fileCards = document.querySelectorAll('.file-card');
        fileCards.forEach(card => {
            const fileName = card.querySelector('.file-card-name').textContent.toLowerCase();
            if (fileName.includes(searchTerm)) {
                card.style.display = 'flex'; // أظهر البطاقة
            } else {
                card.style.display = 'none'; // أخفِ البطاقة
            }
        });
    });
});
/**
 * تنزيل جميع الملفات المحددة في مجلد مضغوط (ZIP) باسم المشروع.
 * (يتطلب مكتبات JSZip و FileSaver.js)
 */
function downloadSelectedFilesAsZip() {
    const selectedCheckboxes = document.querySelectorAll("#fileIconsContainer .selectFile:checked");

    if (selectedCheckboxes.length === 0) {
        showAlert("الرجاء تحديد ملف واحد على الأقل لتنزيله كمجلد مضغوط.", "warning", "لا يوجد تحديد"); // التعديل
        return;
    }

    if (!currentProjectName) {
        showAlert("لا يوجد اسم مشروع لتسمية المجلد المضغوط به.", "error"); // التعديل
        return;
    }

    const zip = new JSZip(); // Create a new JSZip instance
    let filesToProcess = 0;
    let filesProcessed = 0;

    selectedCheckboxes.forEach(checkbox => {
        const fileId = checkbox.value;
        const files = getProjectFiles(currentProjectId);
        const fileToDownload = files.find(f => f.id === fileId);

        if (fileToDownload) {
            // In a real application, you'd fetch the actual file content from a server.
            // For this example, we'll simulate content or use placeholder.
            // If you were storing file content as base64 in localStorage, you could use it.
            // For demonstration, we'll just add text content.
            const simulatedFileContent = `This is the content of ${fileToDownload.name}.\nUploaded on: ${fileToDownload.uploadDate}\nSize: ${fileToDownload.size}`;
            zip.file(fileToDownload.name, simulatedFileContent); // Add file to zip
            filesToProcess++;
        }
    });

    if (filesToProcess === 0) {
        showAlert("لم يتم العثور على أي ملفات صالحة لتضمينها في المجلد المضغوط.", "error"); // التعديل
        return;
    }

    showAlert(`جارٍ تحضير ${filesToProcess} ملف(ملفات) في مجلد مضغوط. قد يستغرق هذا بعض الوقت...`, "info", "جارٍ التحضير"); // التعديل

    // Generate the zip file
    zip.generateAsync({ type: "blob" })
        .then(function(content) {
            // Use FileSaver.js to save the blob as a file
            saveAs(content, `${currentProjectName}_Files.zip`);
            showAlert(`تم إنشاء وتنزيل مجلد مضغوط باسم "${currentProjectName}_Files.zip" بنجاح.`, "success", "نجاح"); // التعديل
        })
        .catch(error => {
            showAlert("فشل في إنشاء المجلد المضغوط: " + error.message, "error"); // التعديل
            console.error("Error generating zip file:", error);
        });
}

// Ensure getProjectFiles and other related functions are accessible or defined above this.
// (They are already defined in your provided project-files.js)

// ... existing JavaScript code ...

/**
 * دالة لمعالجة رفع الملفات عبر السحب والإفلات.
 * @param {FileList} files - كائن FileList يحتوي على الملفات المسحوبة.
 */
function handleDroppedFiles(files) {
    if (!currentProjectId) {
        showAlert("لا يوجد مشروع محدد لرفع الملفات له. الرجاء العودة لصفحة المشاريع وتحديد مشروع.", "error"); // التعديل
        return;
    }

    let uploadedCount = 0;
    const totalFiles = files.length;

    if (totalFiles === 0) {
        showAlert("لم يتم إسقاط أي ملفات صالحة.", "warning", "تنبيه"); // التعديل
        return;
    }

    // Convert FileList to an array for easier iteration
    Array.from(files).forEach(file => {
        const fileName = file.name;
        const fileType = file.type || "Unknown";
        const uploadDate = new Date().toISOString().split('T')[0];
        const fileSizeKB = (file.size / 1024).toFixed(2);

        const newFile = {
            id: Date.now().toString() + Math.random().toString(36).substr(2, 9), // Unique ID for the file
            name: fileName,
            type: fileType,
            uploadDate: uploadDate,
            size: `${fileSizeKB} KB`,
            path: `uploads/${currentProjectId}/${fileName}` // Placeholder path
        };

        let existingFiles = getProjectFiles(currentProjectId); //
        existingFiles.push(newFile); //
        saveProjectFiles(currentProjectId, existingFiles); //

        renderFileIcon(newFile); //
        uploadedCount++;
    });

    if (uploadedCount > 0) {
        showAlert(`تم رفع ${uploadedCount} ملف(ملفات) بنجاح.`, "success", "نجاح"); // التعديل
    } else {
        showAlert("فشل رفع الملفات.", "error"); // التعديل
    }
}


// =================== تهيئة الصفحة عند التحميل ===================

$(document).ready(function () {
    // تحميل الهيدر والناف بار
    $("#header-placeholder").load("header.html", function() {
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }
    });
    $("#navbar-placeholder").load("navbar.html");

    // جلب ID المشروع واسمه فور تحميل الصفحة من localStorage وبدء عرض الملفات
    getProjectInfoFromLocalStorage(); //

    // منطق البحث لفلترة بطاقات الملفات (يحتاج إلى تنفيذ مخصص)
    document.getElementById('fileSearchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const fileCards = document.querySelectorAll('.file-card');
        fileCards.forEach(card => {
            const fileName = card.querySelector('.file-card-name').textContent.toLowerCase();
            if (fileName.includes(searchTerm)) {
                card.style.display = 'flex'; // أظهر البطاقة
            } else {
                card.style.display = 'none'; // أخفِ البطاقة
            }
        });
    });

    // --- Drag and Drop Event Listeners ---
    const dropZone = document.getElementById('dropZone'); //

    if (dropZone) { //
        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false); //
            document.body.addEventListener(eventName, preventDefaults, false); // Global prevent for entire body
        });

        // Highlight drop zone when dragging a file over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-over'), false); //
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-over'), false); //
        });

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false); //
    }
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function handleDrop(e) {
    const dt = e.dataTransfer; //
    const files = dt.files; //

    handleDroppedFiles(files); //
}

// Ensure the `uploadFile` function is accessible for the browse button within the drop zone.
// You might remove the `fileInput` and `fileUploadForm` if you want drop-zone to be the primary upload method,
// but keeping `openUploadModal` allows for both.
