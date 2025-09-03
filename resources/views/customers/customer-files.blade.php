


   @extends('layouts.app')

@section('title', __('LIMS - Customer Files'))

@section('content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <main class="main-content">
            <section id="customer-files-section" class="section-content active">
                <h2>
                    <i class="fas fa-folder"></i>
                    <span data-key="Customer Files">Customer Files</span>
                    <span id="customer-name-display" class="customer-name-display"></span>
                </h2>

                <div class="icon-toolbar">
                    <div>
                        <button title="Upload File" onclick="openUploadModal()" class="btn-icon" >
                            <i class="fas fa-upload"></i>
                            </button>
                        <button title="Delete Selected" onclick="deleteSelectedFiles()" class="btn-icon">
                            <i class="fas fa-trash"></i>
                            </button>
                        <button title="Download Selected as Zip" onclick="downloadSelectedFilesAsZip()" class="btn-icon">
                            <i class="fas fa-file-archive"></i>
                            </button>
                        <button title="Download Selected" onclick="downloadSelectedFiles()" class="btn-icon">
                            <i class="fas fa-download"></i>
                            </button>
                    </div>
                    <div class="icon-separator"></div>
                    <div>
                        <button title="Select All" onclick="toggleSelectAllFiles(true)" class="btn-icon">
                            <i class="fas fa-check-double"></i>
                            </button>
                        <button title="Deselect All" onclick="toggleSelectAllFiles(false)" class="btn-icon">
                            <i class="fas fa-times-circle"></i>
                            </button>
                    </div>
                    <div class="search-export-group">
                        <input type="text" id="fileSearchInput" placeholder="Search files..." data-key="Search files...">
                    </div>
                </div>

                <div id="dropZone" class="drop-zone">
                    <p><i class="fas fa-cloud-upload-alt"></i> Drag and drop files here to upload</p>
                </div>


                <div id="fileIconsContainer" class="file-icons-grid">
                    <p class="no-files-message" data-key="Loading files...">Loading files...</p>
                </div>

            </section>
        </main>
    </div>

    <div id="uploadFileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUploadModal()">&times;</span>
            <h3><span data-key="Upload File for">Upload File for</span> <span id="uploadModalCustomerName"></span></h3>
            <form id="fileUploadForm" onsubmit="uploadFile(event)">
                <div class="form-group">
                    <label for="fileInput"><span data-key="Select File">Select File:</span></label>
                    <input type="file" id="fileInput" name="fileInput" required>
                </div>
                <div class="form-buttons">
                    <button type="submit" class="form-button" data-key="Upload">Upload</button>
                    <button type="button" class="form-button cancel" onclick="closeUploadModal()" data-key="Cancel">Cancel</button>
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
    const currentCustomerId = {{ $customer->id }};
    const currentCustomerName = "{{ $customer->customer_name }}";

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('customer-name-display').textContent =
            `(${currentCustomerName} - ID: ${currentCustomerId})`;

        loadAndRenderCustomerFiles();

        setupSearchFilter();
        setupDropZone();
    });

    // =================== جلب الملفات من السيرفر ===================
    function loadAndRenderCustomerFiles() {
        const container = document.getElementById('fileIconsContainer');
        container.innerHTML = '<p class="no-files-message">Loading files...</p>';

        fetch(`/customer-files/${currentCustomerId}/files-json`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(files => {
            container.innerHTML = '';
            if (!files.length) {
                container.innerHTML = '<p class="no-files-message">لا توجد ملفات متاحة. الرجاء رفع ملفات جديدة.</p>';
            } else {
                files.forEach(file => renderFileIcon(file));
            }
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = '<p class="no-files-message">فشل تحميل الملفات.</p>';
        });
    }

    // =================== دوال مساعدة ===================
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

    // =================== رفع الملفات ===================
    function uploadFile(event) {
        event.preventDefault();
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];
        if (!file) return alert("الرجاء اختيار ملف.");

        const formData = new FormData();
        formData.append('file', file);
        formData.append('customer_id', currentCustomerId);
        formData.append('_token', '{{ csrf_token() }}');

        fetch('/customer-files', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(file => {
            renderFileIcon(file);
            alert(`تم رفع ملف "${file.name}" بنجاح`);
            document.getElementById('fileUploadForm').reset();
            closeUploadModal();
        })
        .catch(err => {
            console.error(err);
            alert("فشل رفع الملف.");
        });
    }

    // =================== حذف الملفات ===================
    function deleteFile(fileId) {
        if (!confirm("هل أنت متأكد من حذف الملف؟")) return;

        fetch(`/customer-files/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(resp => {
            document.querySelector(`.file-card[data-file-id="${fileId}"]`).remove();
            alert(resp.message);
        })
        .catch(err => {
            console.error(err);
            alert("فشل حذف الملف.");
        });
    }
window.deleteFile = deleteFile;

    // =================== عرض الملفات ===================
    function viewFile(fileId) {
        window.open(`/customer-files/${fileId}/view`, '_blank');
    }

    // =================== تنزيل الملفات ===================
    function downloadFile(fileId) {
        window.location.href = `/customer-files/${fileId}/download`;
    }

    // =================== البحث ===================
    function setupSearchFilter() {
        const input = document.getElementById('fileSearchInput');
        input.addEventListener('keyup', () => {
            const term = input.value.toLowerCase();
            document.querySelectorAll('.file-card').forEach(card => {
                const name = card.querySelector('.file-card-name').textContent.toLowerCase();
                card.style.display = name.includes(term) ? 'flex' : 'none';
            });
        });
    }

    // =================== Drag & Drop ===================
    function setupDropZone() {
        const dropZone = document.getElementById('dropZone');
        if (!dropZone) return;

        ['dragenter','dragover','dragleave','drop'].forEach(evt => {
            dropZone.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); });
        });

        dropZone.addEventListener('drop', e => {
            const files = e.dataTransfer.files;
            if (!files.length) return;
            const fileInput = document.getElementById('fileInput');
            fileInput.files = files;
            uploadFile({preventDefault: ()=>{}});
        });
    }

function openUploadModal() {
    const uploadModal = document.getElementById('uploadFileModal');
    if (uploadModal) {
        uploadModal.style.display = 'flex';
        document.getElementById('fileUploadForm').reset();
        const uploadModalCustomerNameElem = document.getElementById('uploadModalCustomerName');
        if (uploadModalCustomerNameElem) uploadModalCustomerNameElem.textContent = currentCustomerName;
    }
}

function closeUploadModal() {
    const uploadModal = document.getElementById('uploadFileModal');
    if (uploadModal) uploadModal.style.display = 'none';
}


window.openUploadModal = openUploadModal;
window.closeUploadModal = closeUploadModal;


function getFileIcon(fileName) {
    const extension = fileName.split('.').pop().toLowerCase();
    switch (extension) {
        case 'pdf':
            return '<i class="far fa-file-pdf file-icon pdf-icon"></i>';
        case 'doc':
        case 'docx':
            return '<i class="far fa-file-word file-icon doc-icon"></i>';
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
            return '<i class="far fa-file file-icon default-icon"></i>';
    }
}



// =================== تحديد / إلغاء تحديد جميع الملفات ===================
function toggleSelectAllFiles(selectAll) {
    document.querySelectorAll('.file-card-checkbox').forEach(cb => cb.checked = selectAll);
}

// =================== حذف الملفات المحددة ===================
function deleteSelectedFiles() {
    const selected = Array.from(document.querySelectorAll('.file-card-checkbox:checked'))
                          .map(cb => cb.value);

    if (!selected.length) return alert("الرجاء تحديد ملفات للحذف.");

    if (!confirm(`هل أنت متأكد من حذف ${selected.length} ملف(ملفات)؟`)) return;

    selected.forEach(fileId => {
        fetch(`/customer-files/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(resp => {
            const card = document.querySelector(`.file-card[data-file-id="${fileId}"]`);
            if (card) card.remove();
        })
        .catch(err => console.error(err));
    });
}

// =================== تنزيل الملفات المحددة ===================
function downloadSelectedFiles() {
    const selected = Array.from(document.querySelectorAll('.file-card-checkbox:checked'))
                          .map(cb => cb.value);

    if (!selected.length) return alert("الرجاء تحديد ملفات للتنزيل.");

    selected.forEach(fileId => {
        window.open(`/customer-files/${fileId}/download`, '_blank');
    });
}

// =================== تنزيل الملفات المحددة كـ ZIP ===================
async function downloadSelectedFilesAsZip() {
    const selected = Array.from(document.querySelectorAll('.file-card-checkbox:checked'))
                          .map(cb => cb.value);

    if (!selected.length) return alert("الرجاء تحديد ملفات لتنزيلها كملف ZIP.");

    const zip = new JSZip();

    for (const fileId of selected) {
        try {
            const response = await fetch(`/customer-files/${fileId}/download`);
            if (!response.ok) throw new Error("فشل تحميل الملف " + fileId);

            const blob = await response.blob();
            const filename = response.headers.get('Content-Disposition')
                             ?.split('filename=')[1]?.replace(/"/g,'') || `file_${fileId}`;

            zip.file(filename, blob);
        } catch (err) {
            console.error(err);
            alert("حدث خطأ أثناء تحميل بعض الملفات.");
        }
    }

    zip.generateAsync({ type: "blob" })
       .then(content => {
           saveAs(content, `customer_files_${Date.now()}.zip`);
       });
}


</script>

@endsection
