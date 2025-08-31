let currentCustomerId = null; // ID العميل الحالي
let currentCustomerName = "N/A"; // اسم العميل الحالي (ليتم عرضه)
// تم إزالة 'customerFilesDataTable;' لأننا لن نستخدم DataTable للعرض الرئيسي

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
 * دالة لجلب ID واسم العميل من localStorage عند تحميل الصفحة.
 */
function getCustomerInfoFromLocalStorage() {
    const customerId = localStorage.getItem('selectedCustomerId');
    const customerName = localStorage.getItem('selectedCustomerName');

    if (customerId && customerName) {
        currentCustomerId = customerId;
        currentCustomerName = customerName;
        document.getElementById('customer-name-display').textContent = `(${currentCustomerName} - ID: ${currentCustomerId})`;
        const uploadModalCustomerNameElem = document.getElementById('uploadModalCustomerName');
        if (uploadModalCustomerNameElem) {
            uploadModalCustomerNameElem.textContent = currentCustomerName;
        }
        
        loadAndRenderCustomerFiles();
    } else {
   
        showAlert("لم يتم تحديد عميل. الرجاء العودة إلى صفحة العملاء وتحديد عميل.", "error"); 
        console.warn("No customer ID or name found in localStorage. Please select a customer.");
    }
}

/**
 * جلب الملفات المخزنة محليًا لعميل معين
 * (في تطبيق حقيقي هاذي مكالمة API للخادم)
 * @returns {Array} قائمة بالملفات.
 */
function getCustomerFiles(customerId) {
    const allFiles = JSON.parse(localStorage.getItem('customerFiles')) || {};
    return allFiles[customerId] || [];
}

/**
 * حفظ الملفات المخزنة محليًا لعميل معين
 * (في تطبيق حقيقي هاذي مكالمة API للخادم)
 * @param {string} customerId - معرف العميل.
 * @param {Array} files - قائمة الملفات للعميل.
 */
function saveCustomerFiles(customerId, files) {
    let allFiles = JSON.parse(localStorage.getItem('customerFiles')) || {};
    allFiles[customerId] = files;
    localStorage.setItem('customerFiles', JSON.stringify(allFiles));
}

/**
 * تحميل الملفات الموجودة للعميل الحالي وعرضها كأيقونات.
 */
function loadAndRenderCustomerFiles() {
    const fileIconsContainer = document.getElementById('fileIconsContainer');
    if (!fileIconsContainer) {
        console.error("Container #fileIconsContainer not found!");
        return;
    }
    fileIconsContainer.innerHTML = ''; // مسح المحتوى القديم

    if (currentCustomerId) {
        const existingFiles = getCustomerFiles(currentCustomerId);
        if (existingFiles.length === 0) {
            fileIconsContainer.innerHTML = `<p class="no-files-message" data-key="No files available">لا توجد ملفات متاحة. الرجاء رفع ملفات جديدة.</p>`;
        } else {
            existingFiles.forEach(file => {
                renderFileIcon(file); // استخدام الدالة الجديدة لعرض الأيقونات
            });
        }
    } else {
        fileIconsContainer.innerHTML = `<p class="no-files-message" data-key="No customer selected">لم يتم تحديد عميل. الرجاء العودة لصفحة العملاء وتحديد عميل.</p>`;
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
        const uploadModalCustomerNameElem = document.getElementById('uploadModalCustomerName');
        if (uploadModalCustomerNameElem && currentCustomerName) {
            uploadModalCustomerNameElem.textContent = currentCustomerName;
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
 * (يضيف البيانات إلى localStorage)
 * @param {Event} event - حدث الإرسال.
 */
function uploadFile(event) {
    event.preventDefault();
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];

    if (!file) {
      
        showAlert("الرجاء تحديد ملف للرفع.", "error"); 
        return;
    }

    if (!currentCustomerId) {
       
        showAlert("لا يوجد عميل محدد لرفع الملفات له. الرجاء العودة لصفحة العملاء وتحديد عميل.", "error"); 
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
        path: `uploads/${currentCustomerId}/${fileName}`
    };

    let files = getCustomerFiles(currentCustomerId);
    files.push(newFile);
    saveCustomerFiles(currentCustomerId, files);

    // عرض الملف الجديد كأيقونة
    renderFileIcon(newFile);

  
    showAlert(`تم رفع ملف "${fileName}" بنجاح.`, "success"); 
    closeUploadModal();
}

/**
 * دالة جديدة لعرض ملف كأيقونة في الشبكة.
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
    let files = getCustomerFiles(currentCustomerId);
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
    let files = getCustomerFiles(currentCustomerId);
    const fileToView = files.find(f => f.id === fileId);
    if (fileToView) {
    
        showAlert(`جارٍ عرض ملف: ${fileToView.name}`, "info", "عرض"); 
       
        console.log(`Viewing file: ${fileToView.name} from ${fileToView.path}`);
    } else {
       
        showAlert("لم يتم العثور على الملف للعرض.", "error"); 
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
    
        downloadFile(fileId);
        downloadInitiatedCount++;
    });

    if (downloadInitiatedCount > 0) {
       
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
 
    showConfirm("هل أنت متأكد أنك تريد حذف هذا الملف؟ لا يمكن التراجع عن هذا الإجراء.", () => {
            let files = getCustomerFiles(currentCustomerId);
            const initialLength = files.length;
            files = files.filter(f => f.id !== fileId);
            saveCustomerFiles(currentCustomerId, files);

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
      
        showAlert("الرجاء اختيار ملف واحد على الأقل للحذف.", "warning", "لا يوجد تحديد"); 
        return;
    }

  
    showConfirm(`هل أنت متأكد أنك تريد حذف ${selectedCheckboxes.length} ملف(ملفات) محدد(ة)؟ لا يمكن التراجع عن هذا الإجراء.`, 
        () => {
            let filesToDeleteIds = Array.from(selectedCheckboxes).map(box => box.value);
            let files = getCustomerFiles(currentCustomerId);
            const initialLength = files.length;

            files = files.filter(f => !filesToDeleteIds.includes(f.id));
            saveCustomerFiles(currentCustomerId, files);

            let deletedCount = 0;
            filesToDeleteIds.forEach(fileId => {
                const fileCardToRemove = document.querySelector(`.file-card[data-file-id="${fileId}"]`);
                if (fileCardToRemove) {
                    fileCardToRemove.remove();
                    deletedCount++;
                }
            });

            if (deletedCount > 0) {
            
                showAlert(`${deletedCount} ملف(ملفات) تم حذفها بنجاح.`, "success", "تم الحذف بنجاح"); 
            } else {
             
                showAlert("لم يتم العثور على أي ملفات محددة للحذف.", "error"); 
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

    // جلب ID العميل واسمه فور تحميل الصفحة من localStorage وبدء عرض الملفات
    getCustomerInfoFromLocalStorage();


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
 * تنزيل جميع الملفات المحددة في مجلد مضغوط (ZIP) باسم العميل.
 * (يتطلب مكتبات JSZip و FileSaver.js)
 */
function downloadSelectedFilesAsZip() {
    const selectedCheckboxes = document.querySelectorAll("#fileIconsContainer .selectFile:checked");

    if (selectedCheckboxes.length === 0) {
       
        showAlert("الرجاء تحديد ملف واحد على الأقل لتنزيله كمجلد مضغوط.", "warning", "لا يوجد تحديد"); 
        return;
    }

    if (!currentCustomerName) {
      
        showAlert("لا يوجد اسم عميل لتسمية المجلد المضغوط به.", "error"); 
        return;
    }

    const zip = new JSZip(); // Create a new JSZip instance
    let filesToProcess = 0;
    let filesProcessed = 0;

    selectedCheckboxes.forEach(checkbox => {
        const fileId = checkbox.value;
        const files = getCustomerFiles(currentCustomerId);
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
     
        showAlert("لم يتم العثور على أي ملفات صالحة لتضمينها في المجلد المضغوط.", "error"); 
        return;
    }

  
    showAlert(`جارٍ تحضير ${filesToProcess} ملف(ملفات) في مجلد مضغوط. قد يستغرق هذا بعض الوقت...`, "info", "جارٍ التحضير"); 

    // Generate the zip file
    zip.generateAsync({ type: "blob" })
        .then(function(content) {
            // Use FileSaver.js to save the blob as a file
            saveAs(content, `${currentCustomerName}_Files.zip`);
           
            showAlert(`تم إنشاء وتنزيل مجلد مضغوط باسم "${currentCustomerName}_Files.zip" بنجاح.`, "success", "نجاح"); 
        })
        .catch(error => {
           
            showAlert("فشل في إنشاء المجلد المضغوط: " + error.message, "error"); 
            console.error("Error generating zip file:", error);
        });
}

// Ensure getCustomerFiles and other related functions are accessible or defined above this.
// (They are already defined in your provided customer-files.js)

// ... existing JavaScript code ...

/**
 * دالة لمعالجة رفع الملفات عبر السحب والإفلات.
 * @param {FileList} files - كائن FileList يحتوي على الملفات المسحوبة.
 */
function handleDroppedFiles(files) {
    if (!currentCustomerId) {
      
        showAlert("لا يوجد عميل محدد لرفع الملفات له. الرجاء العودة لصفحة العملاء وتحديد عميل.", "error"); 
        return;
    }

    let uploadedCount = 0;
    const totalFiles = files.length;

    if (totalFiles === 0) {
      
        showAlert("لم يتم إسقاط أي ملفات صالحة.", "warning", "تنبيه"); 
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
            path: `uploads/${currentCustomerId}/${fileName}` // Placeholder path
        };

        let existingFiles = getCustomerFiles(currentCustomerId); //
        existingFiles.push(newFile); //
        saveCustomerFiles(currentCustomerId, existingFiles); //

        renderFileIcon(newFile); //
        uploadedCount++;
    });

    if (uploadedCount > 0) {
       
        showAlert(`تم رفع ${uploadedCount} ملف(ملفات) بنجاح.`, "success", "نجاح"); 
    } else {
       
        showAlert("فشل رفع الملفات.", "error"); 
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

    // جلب ID العميل واسمه فور تحميل الصفحة من localStorage وبدء عرض الملفات
    getCustomerInfoFromLocalStorage(); //

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
