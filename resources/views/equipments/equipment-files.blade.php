@extends('layouts.app')
@section('title', __('LIMS - Equipment Files'))
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<main class="main-content">
    <section id="equipment-files-section" class="section-content active">
        <h2>
            <i class="fas fa-folder"></i>
            <span data-key="Equipment Files">Equipment Files</span>
            <span id="equipment-name-display" class="equipment-name-display"></span>
        </h2>

        <div class="icon-toolbar">
            <div>
                <button title="Upload File" onclick="openUploadModal()" class="btn-icon">
                    <i class="fas fa-upload"></i>
                </button>
                <button title="Delete Selected" onclick="deleteSelectedEquipmentFiles()" class="btn-icon">
                    <i class="fas fa-trash"></i>
                </button>
                <button title="Download Selected as Zip" onclick="downloadSelectedEquipmentFilesAsZip()" class="btn-icon">
                    <i class="fas fa-file-archive"></i>
                </button>
                <button title="Download Selected" onclick="downloadSelectedEquipmentFiles()" class="btn-icon">
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

<!-- Upload Modal -->
<div id="uploadFileModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeUploadModal()">&times;</span>
        <h3><span data-key="Upload File for">Upload File for</span> <span id="uploadModalEquipmentName"></span></h3>
        <form id="fileUploadForm" onsubmit="uploadEquipmentFile(event)">
            <div class="form-group">
                <label for="fileInput"><span data-key="Select File">Select File:</span></label>
                <input type="file" id="fileInput" name="fileInput" required multiple>
            </div>
            <div class="form-buttons">
                <button type="submit" class="form-button" data-key="Upload">Upload</button>
                <button type="button" class="form-button cancel" onclick="closeUploadModal()" data-key="Cancel">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="/js/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const currentEquipmentId = {{ $equipment->id }};
const currentEquipmentName = "{{ $equipment->equipment_reference  }}";
const csrfToken = '{{ csrf_token() }}';

// =================== عند تحميل الصفحة ===================
document.addEventListener('DOMContentLoaded', () => {
    const equipmentDisplay = document.getElementById('equipment-name-display');
    if(equipmentDisplay) equipmentDisplay.textContent = `(${currentEquipmentName} - ID: ${currentEquipmentId})`;

    loadAndRenderEquipmentFiles();
    setupSearchFilter();
    setupDropZone();
});

// =================== دوال CRUD ===================
function renderFileIcon(file) {
    const container = document.getElementById('fileIconsContainer');
    if(!container) return;

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
                <button class="btn-icon view-file-btn" title="View" onclick="viewEquipmentFile(${file.id})">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn-icon download-file-btn" title="Download" onclick="downloadEquipmentFile(${file.id})">
                    <i class="fas fa-download"></i>
                </button>
                <button class="btn-icon delete-file-btn" title="Delete" onclick="deleteEquipmentFile(${file.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.appendChild(fileCard);
}

function getFileIcon(fileName) {
    const ext = fileName.split('.').pop().toLowerCase();
    switch(ext){
        case 'pdf': return '<i class="far fa-file-pdf file-icon pdf-icon"></i>';
        case 'doc': case 'docx': return '<i class="far fa-file-word file-icon doc-icon"></i>';
        case 'xls': case 'xlsx': return '<i class="far fa-file-excel file-icon xls-icon"></i>';
        case 'ppt': case 'pptx': return '<i class="far fa-file-powerpoint file-icon ppt-icon"></i>';
        case 'jpg': case 'jpeg': case 'png': case 'gif': return '<i class="far fa-file-image file-icon img-icon"></i>';
        case 'zip': case 'rar': return '<i class="far fa-file-archive file-icon zip-icon"></i>';
        case 'txt': return '<i class="far fa-file-alt file-icon txt-icon"></i>';
        default: return '<i class="far fa-file file-icon default-icon"></i>';
    }
}

// =================== Load & Upload ===================
function loadAndRenderEquipmentFiles() {
    const container = document.getElementById('fileIconsContainer');
    if(!container) return;

    container.innerHTML = '<p class="no-files-message">Loading files...</p>';

    fetch(`/equipments/${currentEquipmentId}/files-json`)
        .then(res => res.json())
        .then(files => {
            container.innerHTML = '';
            if(!files.length){
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

function processAndUploadEquipmentFiles(files) {
    if(!files || !files.length){
        Swal.fire("تنبيه", "⚠️ لم يتم اختيار أي ملف.", "warning");
        return;
    }

    Array.from(files).forEach(file => {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('_token', csrfToken);

        fetch(`/equipments/${currentEquipmentId}/files`, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(uploadedFile => {
            renderFileIcon(uploadedFile);
            Swal.fire("نجاح", `✔️ تم رفع ملف "${uploadedFile.name}" بنجاح`, "success");
        })
        .catch(err => { console.error(err); Swal.fire("خطأ", "❌ فشل رفع الملف.", "error"); });
    });
}

function uploadEquipmentFile(event){
    event.preventDefault();
    const files = document.getElementById('fileInput').files;
    processAndUploadEquipmentFiles(files);
    document.getElementById('fileUploadForm').reset();
    closeUploadModal();
}

// =================== File Actions ===================
function deleteEquipmentFile(fileId){
    Swal.fire({
        title:'هل أنت متأكد؟',
        text:"سيتم حذف الملف نهائيًا!",
        icon:'warning',
        showCancelButton:true,
        confirmButtonColor:'#d33',
        cancelButtonColor:'#3085d6',
        confirmButtonText:'نعم، احذف!',
        cancelButtonText:'إلغاء'
    }).then(result=>{
        if(result.isConfirmed){
            fetch(`/equipments/files/${fileId}`, {
                method:'DELETE',
                headers:{'X-CSRF-TOKEN': csrfToken,'X-Requested-With':'XMLHttpRequest'}
            })
            .then(res=>res.json())
            .then(resp=>{
                const card = document.querySelector(`.file-card[data-file-id="${fileId}"]`);
                if(card) card.remove();
                Swal.fire("تم الحذف", resp.message || "✔️ تم حذف الملف بنجاح", "success");
            })
            .catch(err=>{
                console.error(err);
                Swal.fire("خطأ","❌ فشل حذف الملف.","error");
            });
        }
    });
}

function viewEquipmentFile(fileId){ window.open(`/equipments/files/${fileId}/view`, '_blank'); }
function downloadEquipmentFile(fileId){ window.location.href=`/equipments/files/${fileId}/download`; }

// =================== Select / Delete / Download ===================
function toggleSelectAllFiles(selectAll){
    document.querySelectorAll('.file-card-checkbox').forEach(cb => cb.checked = selectAll);
}

function deleteSelectedEquipmentFiles(){
    const selected = Array.from(document.querySelectorAll('.file-card-checkbox:checked')).map(cb=>cb.value);
    if(!selected.length){
        Swal.fire("تنبيه","الرجاء تحديد ملفات للحذف.","warning");
        return;
    }

    Swal.fire({
        title:`هل أنت متأكد من حذف ${selected.length} ملف(ملفات)؟`,
        icon:'warning',
        showCancelButton:true,
        confirmButtonText:'نعم، احذفها',
        cancelButtonText:'إلغاء'
    }).then(result=>{
        if(result.isConfirmed){
            selected.forEach(fileId=>{
                fetch(`/equipments/files/${fileId}`,{
                    method:'DELETE',
                    headers:{'X-CSRF-TOKEN':csrfToken,'X-Requested-With':'XMLHttpRequest'}
                })
                .then(res=>res.json())
                .then(()=>{ const card=document.querySelector(`.file-card[data-file-id="${fileId}"]`); if(card) card.remove(); })
                .catch(err=>{ console.error(err); Swal.fire("خطأ","❌ فشل حذف أحد الملفات.","error"); });
            });
            Swal.fire("تم الحذف",`${selected.length} ملف(ملفات) تم حذفها بنجاح.`,"success");
        }
    });
}

function downloadSelectedEquipmentFiles(){
    const selected = Array.from(document.querySelectorAll('.file-card-checkbox:checked')).map(cb=>cb.value);
    if(!selected.length){ Swal.fire("تنبيه","الرجاء تحديد ملفات للتنزيل.","warning"); return; }

    selected.forEach(fileId=>{
        const link = document.createElement('a');
        link.href = `/equipments/files/${fileId}/download`;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    Swal.fire("تم التنزيل","تم بدء تنزيل الملفات المحددة.","success");
}

async function downloadSelectedEquipmentFilesAsZip(){
    const selected = Array.from(document.querySelectorAll('.file-card-checkbox:checked')).map(cb=>cb.value);
    if(!selected.length){ Swal.fire("تنبيه","الرجاء تحديد ملفات لتنزيلها كملف ZIP.","warning"); return; }

    const zip = new JSZip();
    const failedFiles = [];

    for(const fileId of selected){
        try{
            const response = await fetch(`/equipments/files/${fileId}/download`);
            if(!response.ok) throw new Error("فشل تحميل الملف "+fileId);
            const blob = await response.blob();
            const filename = response.headers.get('Content-Disposition')?.split('filename=')[1]?.replace(/"/g,'') || `file_${fileId}`;
            zip.file(filename, blob);
        }catch(err){ console.error(err); failedFiles.push(fileId); }
    }

    if(failedFiles.length){
        Swal.fire("خطأ",`فشل تحميل بعض الملفات: <br>${failedFiles.join(', ')}`,"error");
    }

    zip.generateAsync({type:"blob"}).then(content=>{
        saveAs(content,`${currentEquipmentName.replace(/[^a-z0-9]/gi,'_').toLowerCase()}_files.zip`);
        Swal.fire("تم التحميل","تم تنزيل الملفات المحددة كملف ZIP بنجاح.","success");
    });
}

// =================== Drag & Drop ===================
function setupDropZone() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');

    if(!dropZone || !fileInput) return;

    ['dragenter','dragover','dragleave','drop'].forEach(evt => {
        dropZone.addEventListener(evt, e => { e.preventDefault(); e.stopPropagation(); });
    });

    ['dragenter','dragover'].forEach(evt => dropZone.addEventListener(evt, () => dropZone.classList.add('drag-over')));
    ['dragleave','drop'].forEach(evt => dropZone.addEventListener(evt, () => dropZone.classList.remove('drag-over')));

    dropZone.addEventListener('drop', e => {
        const files = e.dataTransfer.files;
        if(files.length) processAndUploadEquipmentFiles(files);
    });

    dropZone.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', e => {
        if(e.target.files.length) processAndUploadEquipmentFiles(e.target.files);
    });
}

// =================== Upload Modal ===================
function openUploadModal(){
    const modal = document.getElementById('uploadFileModal');
    if(modal){
        modal.style.display = 'flex';
        const form = document.getElementById('fileUploadForm');
        if(form) form.reset();
        const nameElem = document.getElementById('uploadModalEquipmentName');
        if(nameElem) nameElem.textContent = currentEquipmentName;
    }
}

function closeUploadModal(){
    const modal = document.getElementById('uploadFileModal');
    if(modal) modal.style.display = 'none';
}

window.openUploadModal = openUploadModal;
window.closeUploadModal = closeUploadModal;

// =================== Search Filter ===================
function setupSearchFilter(){
    const input = document.getElementById('fileSearchInput');
    if(!input) return;

    input.addEventListener('keyup', ()=>{
        const term = input.value.toLowerCase();
        document.querySelectorAll('.file-card').forEach(card=>{
            const name = card.querySelector('.file-card-name')?.textContent.toLowerCase() || '';
            card.style.display = name.includes(term) ? 'flex' : 'none';
        });
    });
}

</script>

@endsection
