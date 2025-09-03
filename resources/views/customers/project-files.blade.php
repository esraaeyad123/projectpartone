
   @extends('layouts.app')

@section('title', __('LIMS - Project Files'))

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

        <main class="main-content">
            <section id="project-files-section" class="section-content active">
                <h2>
                    <i class="fas fa-folder"></i>
                    <span data-key="Project Files">Project Files</span>
                    <span id="project-name-display" class="project-name-display"></span>
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
            <h3><span data-key="Upload File for">Upload File for</span> <span id="uploadModalProjectName"></span></h3>
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
            <script src="{{ asset('js/main.js') }}"></script>

        <script src="{{ asset('js/project-files.js') }}"></script>



@endsection
