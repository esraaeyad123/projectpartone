

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('customer-name-display').textContent =
        `(${currentCustomerName} - ID: ${currentCustomerId})`;

    loadAndRenderCustomerFiles();

    document.getElementById('fileUploadForm').addEventListener('submit', uploadFile);
})

// =================== تحميل الملفات من السيرفر ===================
function loadAndRenderCustomerFiles() {
    const container = document.getElementById('fileIconsContainer');
    container.innerHTML = 'Loading...';

    fetch(`/customer-files/${currentCustomerId}/json`)
        .then(res => res.json())
        .then(files => {
            container.innerHTML = '';
            if (!files.length) {
                container.innerHTML = '<p class="no-files-message">لا توجد ملفات متاحة.</p>';
            } else {
                files.forEach(file => renderFileIcon({
                    id: file.id,
                    name: file.name,
                    type: file.type,
                    size: Math.round(file.size / 1024) + ' KB',
                    path: file.path,
                    uploadDate: new Date(file.created_at).toISOString().split('T')[0]
                }));
            }
        })
        .catch(err => {
            console.error(err);
            container.innerHTML = '<p class="no-files-message">فشل تحميل الملفات.</p>';
        });
}
// =================== عرض أيقونة الملف ===================
function renderFileIcon(file) {
    const fileIconsContainer = document.getElementById('fileIconsContainer');
    const fileCard = document.createElement('div');
    fileCard.className = 'file-card';
    fileCard.setAttribute('data-file-id', file.id);

    fileCard.innerHTML = `
        <div class="file-card-content">
            <input type="checkbox" class="selectFile file-card-checkbox" value="${file.id}">
            ${getFileIcon(file.name)}
            <span class="file-card-name">${file.name}</span>
        </div>
    `;
    fileIconsContainer.appendChild(fileCard);
}



// =================== رفع الملفات ===================
function uploadFile(event) {
    event.preventDefault();
    const fileInput = document.getElementById('fileInput');
    if (!fileInput.files[0]) return alert("اختر ملف أولاً");

    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    formData.append('customer_id', currentCustomerId);

    fetch('/customer-files', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(async res => {
        if (!res.ok) {
            const text = await res.text();
            throw new Error(`Server Error: ${text}`);
        }
        return res.json();
    })
    .then(file => {
        renderFileIcon({
            id: file.id,
            name: file.name,
            type: file.type,
            uploadDate: new Date().toISOString().split('T')[0],
            size: Math.round(file.size/1024) + ' KB',
            path: file.path
        });
        closeUploadModal();
        alert('تم رفع الملف وعرضه بنجاح');
    })
    .catch(err => {
        console.error(err);
        alert("فشل رفع الملف. تحقق من Network tab لمعرفة الخطأ.");
    });
}


window.uploadFile = uploadFile;


// =================== حذف الملفات ===================
function deleteFile(fileId) {
    if(!confirm('هل تريد حذف هذا الملف؟')) return;

    fetch(`/customer-files/${fileId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(() => {
        document.querySelector(`.file-card[data-file-id='${fileId}']`).remove();
    });
}

function deleteSelectedFiles() {
    const selected = [...document.querySelectorAll('.selectFile:checked')];
    if (!selected.length) return alert('اختر ملف واحد على الأقل');
    if(!confirm(`هل تريد حذف ${selected.length} ملف(ملفات)؟`)) return;

    selected.forEach(cb => deleteFile(cb.value));
}

// =================== تنزيل الملفات ===================
function downloadFile(fileId) {
    window.location.href = `/customer-files/download/${fileId}`;
}

function downloadSelectedFiles() {
    const selected = [...document.querySelectorAll('.selectFile:checked')];
    if (!selected.length) return alert('اختر ملف واحد على الأقل');

    selected.forEach(cb => downloadFile(cb.value));
}

// =================== تنزيل ملفات ZIP ===================
function downloadSelectedFilesAsZip() {
    const selected = [...document.querySelectorAll('.selectFile:checked')];
    if (!selected.length) return alert('اختر ملف واحد على الأقل');

    const zip = new JSZip();
    const promises = selected.map(cb => {
        const fileId = cb.value;
        return fetch(`/customer-files/${fileId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.blob())
            .then(blob => {
                const fileName = cb.closest('.file-card').querySelector('.file-card-name').textContent;
                zip.file(fileName, blob);
            });
    });

    Promise.all(promises).then(() => {
        zip.generateAsync({type:"blob"}).then(content => {
            saveAs(content, `${currentCustomerName}_Files.zip`);
        });
    });
}

// =================== البحث ===================
function setupSearchFilter() {
    const searchInput = document.getElementById('fileSearchInput');
    searchInput.addEventListener('keyup', () => {
        const term = searchInput.value.toLowerCase();
        document.querySelectorAll('.file-card').forEach(card => {
            const name = card.querySelector('.file-card-name').textContent.toLowerCase();
            card.style.display = name.includes(term) ? 'flex' : 'none';
        });
    });
}

// =================== تحديد / إلغاء تحديد الكل ===================
function toggleSelectAllFiles(checkState) {
    document.querySelectorAll('.selectFile').forEach(cb => cb.checked = checkState);
}

// =================== Drag & Drop ===================
function setupDropZone() {
    const dropZone = document.getElementById('dropZone');
    if (!dropZone) return;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e =>
        dropZone.addEventListener(e, preventDefaults, false)
    );
    ['dragenter', 'dragover'].forEach(e =>
        dropZone.addEventListener(e, () => dropZone.classList.add('drag-over'), false)
    );
    ['dragleave', 'drop'].forEach(e =>
        dropZone.addEventListener(e, () => dropZone.classList.remove('drag-over'), false)
    );
    dropZone.addEventListener('drop', handleDrop, false);
}

function preventDefaults(e) { e.preventDefault(); e.stopPropagation(); }

function handleDrop(e) {
    const files = e.dataTransfer.files;
    Array.from(files).forEach(file => {
        const fakeEvent = { preventDefault: ()=>{} };
        document.getElementById('fileInput').files = e.dataTransfer.files;
        uploadFile(fakeEvent);
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
