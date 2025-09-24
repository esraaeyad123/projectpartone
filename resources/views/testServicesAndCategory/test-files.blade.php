@extends('layouts.app')
@section('title', __('LIMS - Project Files'))
@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
       <main class="main-content">
    <section id="test-files-section" class="section-content active">
        <h2>
            <i class="fas fa-folder"></i>
            <span data-key="Test Files">Test Files</span>
            <span id="test-name-display" class="test-name-display"></span>
        </h2>

        <div class="icon-toolbar">
            <div>
                <button title="Upload File" onclick="openTestUploadModal()" class="btn-icon">
                    <i class="fas fa-upload"></i>
                </button>
                <button title="Delete Selected" onclick="deleteSelectedTestFiles()" class="btn-icon">
                    <i class="fas fa-trash"></i>
                </button>
                <button title="Download Selected as Zip" onclick="downloadSelectedTestFilesAsZip()" class="btn-icon">
                    <i class="fas fa-file-archive"></i>
                </button>
                <button title="Download Selected" onclick="downloadSelectedTestFiles()" class="btn-icon">
                    <i class="fas fa-download"></i>
                </button>
            </div>
            <div class="icon-separator"></div>
            <div>
                <button title="Select All" onclick="toggleSelectAllTestFiles(true)" class="btn-icon">
                    <i class="fas fa-check-double"></i>
                </button>
                <button title="Deselect All" onclick="toggleSelectAllTestFiles(false)" class="btn-icon">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>
            <div class="search-export-group">
                <input type="text" id="testFileSearchInput" placeholder="Search test files..." data-key="Search test files...">
            </div>
        </div>

        <div id="dropZone" class="drop-zone">
            <p><i class="fas fa-cloud-upload-alt"></i> Drag and drop files here to upload</p>
        </div>

        <div id="testFileIconsContainer" class="file-icons-grid">
            <p class="no-files-message" data-key="Loading files...">Loading files...</p>
        </div>
    </section>
</main>

<!-- Upload Modal -->
<div id="uploadTestFileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeTestUploadModal()">&times;</span>
        <h3>
            <span data-key="Upload File for">Upload File for</span>
            <span id="uploadModalTestName"></span>
        </h3>
        <form id="testFileUploadForm" onsubmit="uploadTestFile(event)">
            <div class="form-group">
                <label for="testFileInput"><span data-key="Select File">Select File:</span></label>
                <input type="file" id="testFileInput" name="testFileInput" required multiple>
            </div>
            <div class="form-buttons">
                <button type="submit" class="form-button" data-key="Upload">Upload</button>
                <button type="button" class="form-button cancel" onclick="closeTestUploadModal()" data-key="Cancel">Cancel</button>
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


    <script src="/js/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <script>
    // =================== تعريف المتغيرات ===================
const currentTestId = {{ $test->id }};
const currentTestName = "{{ $test->short_name }}";

// تحميل الملفات وعرضها
function loadAndRenderTestFiles() {
    const container = document.getElementById('testFileIconsContainer');
    container.innerHTML = '<p class="no-files-message">Loading files...</p>';

    fetch(`/tests/${currentTestId}/files-json`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(files => {
        container.innerHTML = '';
        if (!files.length) {
            container.innerHTML = '<p class="no-files-message">لا توجد ملفات متاحة.</p>';
        } else {
            files.forEach(file => renderTestFileIcon(file));
        }
    })
    .catch(err => {
        console.error(err);
        container.innerHTML = '<p class="no-files-message">فشل تحميل الملفات.</p>';
    });
}

// =================== عند تحميل الصفحة ===================
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('test-name-display').textContent =
        `(${currentTestName} - ID: ${currentTestId})`;

    loadAndRenderTestFiles();
    setupDropZone();
    setupTestSearchFilter();
});

// =================== رفع الملفات ===================
function processAndUploadTestFiles(files) {
    if (!currentTestId) {
        Swal.fire("خطأ", "لا يوجد اختبار محدد.", "error");
        return;
    }

    if (!files || files.length === 0) {
        Swal.fire("تنبيه", "⚠️ لم يتم اختيار أي ملف.", "warning");
        return;
    }

    Array.from(files).forEach(file => {
        if (!file || !file.name) return; // تحقق من الملف

        const formData = new FormData();
        formData.append('file', file);
        formData.append('test_id', currentTestId);
        formData.append('_token', '{{ csrf_token() }}');

        fetch(`/tests/${currentTestId}/files`, {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(uploadedFile => {
            if (!uploadedFile || !uploadedFile.file_name) return;

            // 1️⃣ إضافة الملف مباشرة إلى جدول / شبكة العرض
            renderTestFileIcon(uploadedFile);

            // 2️⃣ رسالة نجاح
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: `✔️ تم رفع ملف "${uploadedFile.file_name}" بنجاح`
            });
        })
        .catch(err => {
            console.error("فشل رفع الملف:", err);
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: '❌ فشل رفع الملف.'
            });
        });
    });
}


// =================== جلب الملفات ===================
function loadAndRenderTestFiles() {
    const container = document.getElementById('testFileIconsContainer');
    container.innerHTML = '<p class="no-files-message">Loading files...</p>';

    fetch(`/tests/${currentTestId}/files-json`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(files => {
        container.innerHTML = '';
        if (!files || files.length === 0) {
            container.innerHTML = '<p class="no-files-message">لا توجد ملفات متاحة.</p>';
        } else {
            files.forEach(file => renderTestFileIcon(file));
        }
    })
    .catch(err => {
        console.error(err);
        container.innerHTML = '<p class="no-files-message">فشل تحميل الملفات.</p>';
    });
}

// =================== عرض أيقونات الملفات ===================
function getTestFileIcon(fileName) {
    if (!fileName) return '<i class="far fa-file file-icon default-icon"></i>';
    const ext = fileName.split('.').pop().toLowerCase();
    switch(ext) {
        case 'pdf': return '<i class="far fa-file-pdf file-icon pdf-icon"></i>';
        case 'doc':
        case 'docx': return '<i class="far fa-file-word file-icon doc-icon"></i>';
        case 'xls':
        case 'xlsx': return '<i class="far fa-file-excel file-icon xls-icon"></i>';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif': return '<i class="far fa-file-image file-icon img-icon"></i>';
        default: return '<i class="far fa-file file-icon default-icon"></i>';
    }
}

function renderTestFileIcon(file) {
    const container = document.getElementById('testFileIconsContainer');
    const fileCard = document.createElement('div');
    fileCard.className = 'file-card';
    fileCard.dataset.fileId = file.id;

    fileCard.innerHTML = `
        <div class="file-card-content">
            <input type="checkbox" class="selectFile file-card-checkbox" value="${file.id}">
            ${getTestFileIcon(file.file_name)}
            <span class="file-card-name" title="${file.file_name}">${file.file_name}</span>
        </div>
        <div class="file-card-hover-details">
            <span class="file-card-date">Uploaded: ${file.upload_date || file.created_at}</span>
            <span class="file-card-type">Type: ${file.file_type || 'N/A'}</span>
            <div class="file-card-actions-hover">
                <button class="btn-icon view-file-btn" title="View" onclick="viewTestFile(${file.id})"><i class="fas fa-eye"></i></button>
                <button class="btn-icon download-file-btn" title="Download" onclick="downloadTestFile(${file.id})"><i class="fas fa-download"></i></button>
                <button class="btn-icon delete-file-btn" title="Delete" onclick="deleteTestFile(${file.id})"><i class="fas fa-trash"></i></button>
            </div>
        </div>
    `;
    container.appendChild(fileCard);
}

// =================== حذف الملفات ===================
function deleteTestFile(fileId) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: "سيتم حذف الملف نهائيًا!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'نعم، احذف!',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/tests/files/${fileId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(resp => {
                const fileCard = document.querySelector(`.file-card[data-file-id="${fileId}"]`);
                if (fileCard) fileCard.remove();
                Swal.fire({
                    icon: 'success',
                    title: 'تم الحذف',
                    text: resp.message || "تم حذف الملف."
                });
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: '❌ فشل حذف الملف.'
                });
            });
        }
    });
}

function deleteSelectedTestFiles() {
    const selected = Array.from(document.querySelectorAll('.file-card-checkbox:checked'))
                        .map(cb => cb.value);

    if (!selected.length) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'الرجاء تحديد ملفات للحذف.'
        });
        return;
    }

    Swal.fire({
        title: `هل أنت متأكد من حذف ${selected.length} ملف(ملفات)؟`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذفها',
        cancelButtonText: 'إلغاء',
    }).then((result) => {
        if (result.isConfirmed) {
            // إرسال جميع الملفات المختارة للحذف
            selected.forEach(fileId => {
                fetch(`/tests/files/${fileId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(resp => {
                    // إزالة بطاقة الملف من الصفحة
                    const card = document.querySelector(`.file-card[data-file-id="${fileId}"]`);
                    if (card) card.remove();
                })
                .catch(err => console.error(err));
            });

            Swal.fire({
                icon: 'success',
                title: 'تم الحذف',
                text: `${selected.length} ملف(ملفات) تم حذفها بنجاح.`
            });
        }
    });
}

// =================== عرض / تنزيل الملفات ===================
function viewTestFile(fileId) {
    window.open(`/tests/files/${fileId}/view`, '_blank');
}

function downloadTestFile(fileId) {
    window.location.href = `/tests/files/${fileId}/download`;
}

 function toggleSelectAllTestFiles(selectAll) {
        document.querySelectorAll('.file-card-checkbox').forEach(cb => cb.checked = selectAll);
    }

// =================== DropZone ===================
function setupTestDropZone() {
    const dropZone = document.getElementById('testDropZone');
    if (!dropZone) return;

    ['dragenter','dragover','dragleave','drop'].forEach(evt => {
        dropZone.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); });
    });

    dropZone.addEventListener('drop', e => {
        const files = e.dataTransfer.files;
        if (!files.length) return;
        processAndUploadTestFiles(files);
    });
}

// =================== Upload Modal ===================
function openTestUploadModal() {
    const uploadModal = document.getElementById('uploadTestFileModal');
    if (uploadModal) {
        uploadModal.style.display = 'flex';
        document.getElementById('testFileUploadForm').reset();

        const uploadModalTestNameElem = document.getElementById('uploadModalTestName');
        if (uploadModalTestNameElem) {
            uploadModalTestNameElem.textContent = currentTestName;
        }
    }
}

function closeTestUploadModal() {
    const uploadModal = document.getElementById('uploadTestFileModal');
    if (uploadModal) {
        uploadModal.style.display = 'none';
    }
}

window.openTestUploadModal = openTestUploadModal;
window.closeTestUploadModal = closeTestUploadModal;


function getFileIcon(fileName) {
        const ext = fileName.split('.').pop().toLowerCase();
        switch(ext) {
            case 'pdf': return '<i class="far fa-file-pdf file-icon pdf-icon"></i>';
            case 'doc':
            case 'docx': return '<i class="far fa-file-word file-icon doc-icon"></i>';
            case 'xls':
            case 'xlsx': return '<i class="far fa-file-excel file-icon xls-icon"></i>';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif': return '<i class="far fa-file-image file-icon img-icon"></i>';
            default: return '<i class="far fa-file file-icon default-icon"></i>';
        }
    }

    function renderFileIcon(file) {
        const container = document.getElementById('fileIconsContainer');
        const fileCard = document.createElement('div');
        fileCard.className = 'file-card';
        fileCard.dataset.fileId = file.id;

        fileCard.innerHTML = `
            <div class="file-card-content">
                <input type="checkbox" class="selectFile file-card-checkbox" value="${file.id}">
                ${getFileIcon(file.name)}
                <span class="file-card-name" title="${file.name}">${file.name}</span>
            </div>
            <div class="file-card-hover-details">
                <span class="file-card-date">Uploaded: ${file.created_at}</span>
                <span class="file-card-size">Size: ${file.size}</span>
                <div class="file-card-actions-hover">
                    <button class="btn-icon view-file-btn" title="View" onclick="viewFile(${file.id})"><i class="fas fa-eye"></i></button>
                    <button class="btn-icon download-file-btn" title="Download" onclick="downloadFile(${file.id})"><i class="fas fa-download"></i></button>
                    <button class="btn-icon delete-file-btn" title="Delete" onclick="deleteFile(${file.id})"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        `;
        container.appendChild(fileCard);
    }

function setupDropZone() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('testFileInput'); // تأكد أن هذا صحيح

    if (!dropZone) return;

    ['dragenter','dragover','dragleave','drop'].forEach(evt => {
        dropZone.addEventListener(evt, e => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    ['dragenter','dragover'].forEach(evt => {
        dropZone.addEventListener(evt, () => dropZone.classList.add('drag-over'));
    });

    ['dragleave','drop'].forEach(evt => {
        dropZone.addEventListener(evt, () => dropZone.classList.remove('drag-over'));
    });

    dropZone.addEventListener('drop', e => {
        const files = e.dataTransfer.files;
        processAndUploadTestFiles(files); // <--- غيرت اسم الدالة للاختبارات
    });

    dropZone.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function() {
        processAndUploadTestFiles(this.files); // <--- نفس الدالة
        this.value = ''; // إعادة تعيين بعد رفع الملفات
    });
}


function downloadTestFile(fileId) {
    window.location.href = `/tests/files/${fileId}/download`;
}

// تنزيل الملفات المحددة كـ ZIP
async function downloadSelectedTestFilesAsZip() {
    const selected = Array.from(document.querySelectorAll('.file-card-checkbox:checked')).map(cb => cb.value);
    if (!selected.length) return Swal.fire({ icon: 'warning', title: 'تنبيه', text: 'الرجاء تحديد ملفات لتنزيلها كملف ZIP.' });

    const zip = new JSZip();
    for (const id of selected) {
        const res = await fetch(`/tests/files/${id}/download`);
        const blob = await res.blob();
        zip.file(`file_${id}`, blob);
    }
    const content = await zip.generateAsync({ type: "blob" });
    saveAs(content, `${currentTestName}_files.zip`);
    Swal.fire({ icon: 'success', title: 'تم التحميل', text: 'تم تنزيل الملفات المحددة كملف ZIP.' });
}

// عند التحميل
document.addEventListener('DOMContentLoaded', () => loadAndRenderTestFiles());


function downloadSelectedTestFiles() {
    const selected = Array.from(document.querySelectorAll('.file-card-checkbox:checked'))
                        .map(cb => cb.value);

    if (!selected.length) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'الرجاء تحديد ملفات للتنزيل.'
        });
        return;
    }

    selected.forEach(fileId => {
        const link = document.createElement('a');
        link.href = `/tests/files/${fileId}/download`;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    Swal.fire({
        icon: 'success',
        title: 'تم التنزيل',
        text: 'تم بدء تنزيل جميع الملفات المحددة.'
    });
}

// ربط الدالة بـ window ليتم استدعاؤها من HTML
window.downloadSelectedTestFiles = downloadSelectedTestFiles;

</script>

@endsection
