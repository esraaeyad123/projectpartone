@extends('layouts.app')

@section('title', __('LIMS - Project Files'))

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/project-files.css') }}">

<main class="main-content">
    <section id="project-files-section" class="section-content active">
        <h2>
            <i class="fas fa-folder"></i>
            <span data-key="Project Files">Project Files</span>
            <span id="project-name-display" class="project-name-display"></span>
        </h2>

        <div class="icon-toolbar">
            <div>
                <button title="Upload File" onclick="openUploadModal()" class="btn-icon">
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
    <p><i class="fas fa-cloud-upload-alt"></i> اسحب الملفات هنا لإرسالها</p>
    <input type="file" id="fileInput" multiple style="display:none">
</div>

                <div id="fileIconsContainer" class="file-icons-grid">
                    <p class="no-files-message" data-key="Loading files...">Loading files...</p>
                </div>
    </section>
</main>

<div id="uploadFileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeUploadModal()">&times;</span>
        <h3><span data-key="Upload File for">Upload File for</span> <span id="uploadModalProjectName"></span></h3>
        <form id="fileUploadForm">
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
<script src="{{ asset('js/main.js') }}"></script>

<script>
    var currentProjectId = {{ $project->id }};
    var currentProjectName = "{{ $project->name }}";

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('project-name-display').textContent = `${currentProjectName} - ID: ${currentProjectId}`;
        loadAndRenderProjectFiles();
    });

    // =================== تحميل وعرض الملفات ===================
    function loadAndRenderProjectFiles() {
        if (!currentProjectId) return;

        fetch(`/projects/${currentProjectId}/files-json`)
            .then(res => res.json())
            .then(files => {
                const container = document.getElementById('fileIconsContainer');
                container.innerHTML = '';
                if (!files.length) {
                    container.innerHTML = `<p>لا توجد ملفات متاحة. الرجاء رفع ملفات جديدة.</p>`;
                    return;
                }
                files.forEach(file => renderFileIcon(file));
            })
            .catch(err => console.error(err));
    }

    function renderFileIcon(file) {
    const container = document.getElementById('fileIconsContainer');
    const fileCard = document.createElement('div');
    fileCard.className = 'file-card';
    fileCard.innerHTML = `
        <div class="file-card-content">
            <input type="checkbox" class="selectFile" data-file-id="${file.id}">
            <span class="file-icon">${getFileIcon(file.name)}</span>
            <span>${file.name}</span>
            <button onclick="downloadFile(${file.id})">تنزيل</button>
            <button onclick="deleteFile(${file.id})">حذف</button>
        </div>
    `;
    container.appendChild(fileCard);
}


    function getFileIcon(name) {
        const ext = name.split('.').pop().toLowerCase();
        const icons = {
            pdf: '📄', doc: '📝', docx: '📝',
            xls: '📊', xlsx: '📊',
            ppt: '📈', pptx: '📈',
            jpg: '🖼️', jpeg: '🖼️', png: '🖼️', gif: '🖼️',
            zip: '🗜️', rar: '🗜️', txt: '📄'
        };
        return icons[ext] || '📁';
    }

    // =================== رفع الملف ===================
    document.getElementById('fileUploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const fileInput = document.getElementById('fileInput');
        const file = fileInput.files[0];
        if (!file) return Swal.fire('خطأ', 'اختر ملفاً أولاً', 'error');

        const formData = new FormData();
        formData.append('file', file);

        fetch(`/projects/${currentProjectId}/files`, {
            method: 'POST',
            body: formData,
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
        })
        .then(res => res.json())
        .then(data => {
            renderFileIcon(data);
            fileInput.value = '';
            Swal.fire('نجاح', 'تم رفع الملف بنجاح', 'success');
        })
        .catch(err => Swal.fire('خطأ', 'حدث خطأ أثناء رفع الملف', 'error'));
    });

    // =================== تنزيل وحذف الملفات ===================

 function downloadFile(fileId) {
        window.location.href = `/projects/files/${fileId}/download`;
    }


    function downloadSelectedFiles() {
    const selectedCheckboxes = document.querySelectorAll("#fileIconsContainer .selectFile:checked");

    if (selectedCheckboxes.length === 0) {
        Swal.fire("لا يوجد تحديد", "الرجاء تحديد ملف واحد على الأقل للتنزيل.", "warning");
        return;
    }

    let downloadInitiatedCount = 0;

    selectedCheckboxes.forEach(checkbox => {
        const fileId = checkbox.dataset.fileId;
        // إنشاء رابط تحميل مباشر لكل ملف
        const a = document.createElement('a');
        a.href = `/projects/files/${fileId}/download`; // هذا route يجب أن يكون موجود في Laravel
        a.download = ''; // الاسم سيأتي من السيرفر
        document.body.appendChild(a);
        a.click();
        a.remove();
        downloadInitiatedCount++;
    });

    if (downloadInitiatedCount > 0) {
        Swal.fire("بدء التنزيل", `تم بدء تنزيل ${downloadInitiatedCount} ملف(ملفات) محدد(ة).`, "info");
    } else {
        Swal.fire("خطأ", "لم يتم العثور على أي ملفات للتنزيل.", "error");
    }
}





    function deleteFile(fileId) {
    Swal.fire({
        title: "تأكيد الحذف",
        text: "هل أنت متأكد أنك تريد حذف هذا الملف؟ لا يمكن التراجع عن هذا الإجراء.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "نعم، احذفه",
        cancelButtonText: "إلغاء"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/projects/files/${fileId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const fileCard = document.querySelector(`.file-card[data-file-id="${fileId}"]`);
                    if (fileCard) fileCard.remove();
                    Swal.fire("تم الحذف", "تم حذف الملف بنجاح.", "success");

                    // إذا لم تعد هناك ملفات، أظهر رسالة
                    const container = document.getElementById('fileIconsContainer');
                    if (container.children.length === 0) {
                        container.innerHTML = `<p class="no-files-message" data-key="No files available">لا توجد ملفات متاحة. الرجاء رفع ملفات جديدة.</p>`;
                    }
                } else {
                    Swal.fire("خطأ", data.message || "حدث خطأ أثناء الحذف.", "error");
                }
            })
            .catch(err => Swal.fire("خطأ", "حدث خطأ أثناء الحذف.", "error"));
        }
    });
}



    // فتح نافذة رفع الملف
function openUploadModal() {
    document.getElementById('uploadModalProjectName').textContent = currentProjectName;
    document.getElementById('uploadFileModal').style.display = 'block';
}

// غلق نافذة رفع الملف
function closeUploadModal() {
    document.getElementById('uploadFileModal').style.display = 'none';
}

// غلق أي نافذة حوارية مخصصة
function closeCustomDialog() {
    document.getElementById('customDialogModal').style.display = 'none';
}


function toggleSelectAllFiles(checkState) {
    const allCheckboxes = document.querySelectorAll("#fileIconsContainer .selectFile");
    allCheckboxes.forEach(cb => cb.checked = checkState);
}

function deleteSelectedFiles() {
    const selectedCheckboxes = document.querySelectorAll("#fileIconsContainer .selectFile:checked");

    if (selectedCheckboxes.length === 0) {
        Swal.fire("لا يوجد تحديد", "الرجاء اختيار ملف واحد على الأقل للحذف.", "warning");
        return;
    }

    const fileIds = Array.from(selectedCheckboxes).map(cb => cb.dataset.fileId);

    Swal.fire({
        title: "تأكيد الحذف",
        text: `هل أنت متأكد أنك تريد حذف ${fileIds.length} ملف(ملفات) محدد(ة)؟ لا يمكن التراجع عن هذا الإجراء.`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "نعم، احذفها",
        cancelButtonText: "إلغاء"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/projects/files/delete-multiple`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ file_ids: fileIds })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    fileIds.forEach(fileId => {
                        const fileCard = document.querySelector(`.file-card[data-file-id="${fileId}"]`);
                        if (fileCard) fileCard.remove();
                    });
                    Swal.fire("تم الحذف", `${fileIds.length} ملف(ملفات) تم حذفها بنجاح.`, "success");

                    const container = document.getElementById('fileIconsContainer');
                    if (container.children.length === 0) {
                        container.innerHTML = `<p class="no-files-message" data-key="No files available">لا توجد ملفات متاحة. الرجاء رفع ملفات جديدة.</p>`;
                    }
                } else {
                    Swal.fire("خطأ", data.message || "حدث خطأ أثناء الحذف.", "error");
                }
            })
            .catch(err => Swal.fire("خطأ", "حدث خطأ أثناء الحذف.", "error"));
        }
    });
}


function downloadSelectedFilesAsZip() {
    const selectedCheckboxes = document.querySelectorAll("#fileIconsContainer .selectFile:checked");

    if (selectedCheckboxes.length === 0) {
        Swal.fire("لا يوجد تحديد", "الرجاء تحديد ملف واحد على الأقل لتنزيله كمجلد مضغوط.", "warning");
        return;
    }

    const fileIds = Array.from(selectedCheckboxes).map(cb => cb.dataset.fileId);

    // إرسال IDs إلى السيرفر لإنشاء zip
    fetch(`/projects/files/download-multiple`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ file_ids: fileIds, project_name: currentProjectName })
    })
    .then(res => res.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${currentProjectName}_Files.zip`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
        Swal.fire("نجاح", `تم إنشاء وتنزيل مجلد مضغوط باسم "${currentProjectName}_Files.zip" بنجاح.`, "success");
    })
    .catch(err => {
        Swal.fire("خطأ", "حدث خطأ أثناء إنشاء المجلد المضغوط.", "error");
        console.error(err);
    });
}




function handleDroppedFiles(files) {
    if (!currentProjectId) {
        Swal.fire("خطأ", "لا يوجد مشروع محدد لرفع الملفات له.", "error");
        return;
    }

    const formData = new FormData();
    Array.from(files).forEach(file => formData.append('file', file));

    fetch(`/projects/${currentProjectId}/files`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
        body: formData
    })
    .then(res => res.json())
    .then(file => {
        renderFileIcon(file); // عرض الملف المرفوع
        Swal.fire("نجاح", "تم رفع الملف بنجاح.", "success");
    })
    .catch(err => {
        console.error(err);
        Swal.fire("خطأ", "حدث خطأ أثناء رفع الملف.", "error");
    });
}

const dropZone = document.getElementById('dropZone');

if (dropZone) {
    // منع السلوك الافتراضي
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    // إضافة تأثير عند السحب فوق المنطقة
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('drag-over'));
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.remove('drag-over'));
    });

    // عند إسقاط الملفات
    dropZone.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        handleDroppedFiles(files);
    });

    // فتح File Dialog عند النقر على Drop Zone
    dropZone.addEventListener('click', () => document.getElementById('fileInput').click());

    // التعامل مع اختيار الملفات من File Dialog
    document.getElementById('fileInput').addEventListener('change', function() {
        handleDroppedFiles(this.files);
        this.value = ''; // إعادة ضبط الاختيار
    });
}

function handleDroppedFiles(files) {
    if (!currentProjectId) {
        Swal.fire("خطأ", "لا يوجد مشروع محدد.", "error");
        return;
    }

    Array.from(files).forEach(file => {
        const formData = new FormData();
        formData.append('file', file);

        fetch(`/projects/${currentProjectId}/files`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(file => renderFileIcon(file))
        .catch(err => Swal.fire("خطأ", "فشل رفع الملف.", "error"));
    });
}



// عند النقر خارج الـ modal لإغلاقه
window.onclick = function(event) {
    const uploadModal = document.getElementById('uploadFileModal');
    const customModal = document.getElementById('customDialogModal');
    if (event.target == uploadModal) uploadModal.style.display = 'none';
    if (event.target == customModal) customModal.style.display = 'none';
}


</script>
@endsection
