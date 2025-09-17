@extends('layouts.app')

@section('title', __('Quotation'))

@section('content')
<link rel="stylesheet" href="{{ asset('css/quotation.css') }}">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

<section id="quotation-section" class="section-content active">
    <div class="icon-toolbar">
        <div>
            <button title="Add" onclick="openQuotationModal()" class="btn-icon">
                <i class="fas fa-file"></i>
            </button>
            <button title="Edit" onclick="editQuotationModal(getSingleSelectedQuotationId())" class="btn-icon">
                <i class="fas fa-pen"></i>
            </button>
            <button title="Delete" onclick="deleteSelectedQuotation()" class="btn-icon">
                <i class="fas fa-trash"></i>
            </button>

            <button title="Revise" onclick="reviseQuotation(getSingleSelectedQuotationId())" class="btn-icon">
                <i class="fas fa-pen-to-square"></i>
            </button>
            <button class="btn btn-success btn-add-price" onclick="openAddPriceModal()">
        <i class="fas fa-plus"></i>
    </button>
        </div>

        <div class="icon-separator"></div>

        <div>
            <button title="Save" onclick="saveQuotationChanges()" class="btn-icon">
                <i class="fas fa-save"></i>
            </button>
            <button title="Submit for Approval" onclick="submitForApproval(getSingleSelectedQuotationId())" class="btn-icon">
                <i class="fas fa-upload"></i>
            </button>
            <button title="Confirm" onclick="confirmQuotation(getSingleSelectedQuotationId())" class="btn-icon">
                <i class="fas fa-check-circle"></i>
            </button>
           <button title="Send to Customer" onclick="sendQuotationToCustomer(getSingleSelectedQuotationId())" class="btn-icon">
                <i class="fas fa-share-from-square"></i>
            </button>
        </div>

        <div class="icon-separator"></div>

        <div>
            <button title="Search" onclick="searchQuotation()" class="btn-icon">
                <i class="fas fa-search"></i>
            </button>
            <button title="Open PDF" onclick="openQuotationPDF(getSingleSelectedQuotationId())" class="btn-icon">
                <i class="fas fa-file-pdf"></i>
            </button>

 <button title="Preview" onclick="previewQuotation()" class="btn-icon">
    <i class="fas fa-file-invoice"></i>
</button>

        <button title="Export to Excel" onclick="exportSelectedToExcel()" class="btn-icon">
                <i class="fas fa-file-excel"></i>
            </button>
            <button title="Print" onclick="printSelectedRows()" class="btn-icon">
                <i class="fas fa-print"></i>
            </button>
        </div>
    </div>

</section>


    <div id="modalpre-container" class="modalpre-container">
        <div id="quotationModalpre" class="modalpre-content">
            <div class="modalpre-header">
                <div class="headerpre-left-controls">
                    <button class="approve-btn icon-btn" title="Approve Report">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="decline-btn icon-btn" title="Decline Report">
                        <i class="fas fa-ban"></i>
                    </button>
                    <button class="comment-btn icon-btn" title="Add Comment">
                        <i class="fas fa-comment-dots"></i>
                    </button>
                    <button class="print-btn icon-btn" title="Print Report">
                        <i class="fas fa-print"></i>
                    </button>
                    <span id="resize-percentage" class="resize-percentage">100%</span>
                </div>
                <div class="modalpre-controls">
                    <button class="minimize-btn" title="Minimize">-</button>
                    <button class="maximize-btn" title="Maximize">&#x2610;</button>
                    <button class="close-btn" title="Close">&times;</button>
                </div>
            </div>
            <div id="report-content" class="modalpre-body">
                <div class="report-container">
                    <header class="report-header">
                        <div class="office-info">
                            <img src="" alt="Office Logo" class="logo">
                            <div class="contact-details">
                                <p>مكتب عبدالالكريم عبدالله المنصور للاستشارات الهندسية</p>
                                <p>إستشارات هندسية - تصاميم معمارية</p>
                                <p>رفوعات مساحية - إشراف هندسي</p>
                                <p>ترخيص هندسي رقم 3448، سجل تجاري رقم 162903</p>
                            </div>
                        </div>

                        <h1 class="report-title">Proposal for Materials Testing</h1>
                    </header>


                </div>
            </div>
            <div class="modalpre-footer">
    <p>الصفحة <span id="current-page-num">1</span> من <span id="total-pages-num">1</span></p>
</div>
            <div class="resize-handle right"></div>
            <div class="resize-handle left"></div>
        </div>
    </div>

 <script>
function getQuotationDataFromUI() {
    const headerData = {
        proposal_number: document.getElementById('quoteNo')?.value || '',
        proposal_date: document.getElementById('quoteDate')?.value || '',
        inquiry_ref: document.getElementById('quoteInquiry')?.value || '',
        validity_days: parseInt(document.getElementById('quoteValidity')?.value) || 0,
        terms: document.getElementById('quotePaymentTermsInput')?.value || '',
        subject: document.getElementById('quoteSubject')?.value || '',
        proposed_to: document.getElementById('quoteAttnTo')?.value || '',
        address: document.getElementById('quoteContactTo')?.value || '',
        contact_person: document.getElementById('quoteContactPerson')?.value || '',
        contact_title: document.getElementById('quoteAttnPos')?.value || '',
        project_name: document.getElementById('quoteProject')?.value || '',
        contact_number: document.getElementById('quoteContactMobile')?.value || '',
        contact_email: document.getElementById('quoteContactEmail')?.value || '',
        section_title: document.getElementById('quoteSubject')?.value || '',
        tax_rate: parseFloat(document.getElementById('quoteVAT')?.value) / 100 || 0,
        amount_in_words: document.getElementById('financialTotalLines')?.value || '',

    };

    const linesData = [];
    const tableRows = document.querySelectorAll('#quotationLinesTable tbody tr');

    tableRows.forEach((row, index) => {
        const descriptionElement = row.querySelector('.description-input, [name="description"]');
        const unitElement = row.querySelector('.unit-input, [name="unit"]');
        const methodElement = row.querySelector('.method-input, [name="method"]');
        const qtyElement = row.querySelector('.qty-input, [name="qty"]');
        const priceElement = row.querySelector('.price-input, [name="price"]');

        const description = descriptionElement ? descriptionElement.value : row.cells[1]?.textContent || '';
        const qty = qtyElement ? parseFloat(qtyElement.value) : parseFloat(row.cells[2]?.textContent) || 0;
        const price = priceElement ? parseFloat(priceElement.value) : parseFloat(row.cells[3]?.textContent) || 0;
        const unit = unitElement ? unitElement.value : row.cells[3]?.textContent || '';
        const method = methodElement ? methodElement.value : row.cells[2]?.textContent || '';

        linesData.push({
            id: index + 1,
            description: description,
            method: method,
            unit: unit,
            qty: qty,
            price: price,
            isPriceOnly: false
        });
    });

    return {
        header: headerData,
        lines: linesData
    };
}
function formatQuotation(data) {
    if (!data) {
        return '<p style="text-align: center; margin-top: 50px;">التقرير غير متوفر.</p>';
    }

    const { header, lines } = data;
    // تعديل لحساب الإجمالي بناءً على isPriceOnly
    const totalCalculated = lines.reduce((sum, item) => {
        // نتحقق من أن item.qty و item.price موجودة قبل الحساب
        const qty = item.qty ?? 0;
        const price = item.price ?? 0;
        return sum + (item.isPriceOnly ? 0 : qty * price);
    }, 0);
    // نتحقق من وجود tax_rate قبل الحساب
    const taxRate = header.tax_rate ?? 0;
    const taxAmount = totalCalculated * taxRate;
    const finalTotal = totalCalculated + taxAmount;

    // كود HTML الخاص برأس التقرير
    const headerHtml = `
        <header class="report-header">
            <div class="header-info-left">
                <p style="margin: 0;">Office of</p>
                <p style="margin: 0; font-weight: bold;">Abdulkarim Abdullah Almansour For Engineering Consultings</p>
                <p style="margin: 0;">Engineering consultings, Designing,</p>
                <p style="margin: 0;">Supervision, Survey Works</p>
                <p style="margin: 0;">Engineering License # 3448, J.C.C.L# 162903, C.R. # 4030279674</p>
            </div>
            <div class="header-logo-center">
                <img src="" alt="Office Logo" class="logo">
            </div>
            <div class="header-info-right">
                <p style="margin: 0";>مكتب</p>
                <p style="margin: 0; font-weight: bold;">عبدالكريم عبدالله المنصور للاستشارات الهندسية</p>
                <p style="margin: 0;">إستشارات هندسية - تصاميم معمارية</p>
                <p style="margin: 0;">رفوعات مساحية - إشراف هندسي</p>
                <p style="margin: 0;">ترخيص هندسي رقم 3448، سجل تجاري رقم 162903</p>
            </div>
        </header>
        <div class="header-underline"></div>
        <h1 class="report-title">Proposal for Materials Testing</h1>
    `;

    // استخدام المعامل ?? لوضع قيمة افتراضية "N/A"
    const detailsHtml = `
        <section class="proposal-details">
            <div class="details-column">
                <div class="detail-item">
                    <span class="label">PROPOSAL #</span>
                    <span class="value editable">${header.proposal_number ?? 'N/A'} <span class="rev-label">REV</span> <span class="rev-value">0</span></span>
                </div>
                <div class="detail-item">
                    <span class="label">PROPOSAL DATE</span>
                    <span class="value editable">${header.proposal_date ?? 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="label">INQUIRY REF</span>
                    <span class="value editable">${header.inquiry_ref ?? 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="label">VALIDITY - DAYS</span>
                    <span class="value editable">${header.validity_days ?? 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="label">TERMS</span>
                    <span class="value editable">${header.terms ?? 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="label">SUBJECT</span>
                    <span class="value editable">${header.subject ?? 'N/A'}</span>
                </div>
                <div class="contact-person-details">
                    <div class="detail-item">
                        <span class="label">CONTACT PERSON</span>
                        <span class="value editable">${header.contact_person ?? 'N/A'}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">CONTACT TITLE</span>
                        <span class="value editable">${header.contact_title ?? 'N/A'}</span>
                    </div>
                </div>
            </div>
            <div class="details-column">
                <div class="detail-item proposed-to-item">
                    <span class="label">PROPOSED TO:</span>
                    <span class="value editable">${header.proposed_to ?? 'N/A'}</span>
                </div>
                <div class="project-details">
                    <div class="detail-item">
                        <span class="label">Project</span>
                        <span class="value editable">${header.project_name ?? 'N/A'}</span>
                    </div>
                </div>
                <div class="detail-item">
                    <span class="label">CONTACT NUMBER</span>
                    <span class="value editable">${header.contact_number ?? 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="label">CONTACT EMAIL</span>
                    <span class="value editable">${header.contact_email ?? 'N/A'}</span>
                </div>
                <div class="detail-item">
                    <span class="label">ADDRESS</span>
                    <span class="value editable">${header.address ?? 'N/A'}</span>
                </div>
            </div>
        </section>
        <p class="greeting-text">Dear Sir,</p>
        <p class="intro-text">As requested, we're pleased to submit herewith our proposal for Quality Control / Material testing services for project as given above</p>
    `;

    // استخدام المعامل ?? لوضع قيمة افتراضية "N/A"
    const sectionTitleHtml = `
        <h3 class="section-heading">${header.section_title ?? 'Scope of Work'}</h3>
    `;

    // كود HTML للجدول، مع تكرار عناصر المصفوفة 'lines'
    const tableHtml = `
        <main class="report-body">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>DESCRIPTION</th>
                        <th>METHOD</th>
                        <th>UNIT</th>
                        <th>QTY</th>
                        <th>PRICE (SAR)</th>
                        <th>TOTAL (SAR)</th>
                    </tr>
                </thead>
                <tbody>
                    ${lines.map(item => `
                        <tr>
                            <td>${item.id ?? '-'}</td>
                            <td class="editable description-cell">${item.description ?? 'N/A'}</td>
                            <td class="editable">${item.method ?? 'N/A'}</td>
                            <td class="editable">${item.unit ?? 'N/A'}</td>
                            <td class="editable">${item.isPriceOnly ? '-' : (item.qty ?? 0)}</td>
                            <td class="editable">${(item.price ?? 0).toFixed(2)}</td>
                            <td>${item.isPriceOnly ? 'PriceOnly' : ((item.qty ?? 0) * (item.price ?? 0)).toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </main>
    `;

    // استخدام المعامل ?? لوضع قيمة افتراضية "0.00"
    const totalsHtml = `
        <div class="totals">
            <div class="total-item">
                <span class="label">SUBTOTAL</span>
                <span class="value">SAR ${totalCalculated.toFixed(2)}</span>
            </div>
            <div class="total-item">
                <span class="label">TAX %(${(taxRate * 100).toFixed(2)})</span>
                <span class="value">SAR ${taxAmount.toFixed(2)}</span>
            </div>
            <div class="total-item final-total">
                <span class="label">TOTAL</span>
                <span class="value">SAR ${finalTotal.toFixed(2)}</span>
            </div>
            <p class="amount-in-words">${header.amount_in_words ?? 'N/A'}</p>
        </div>
        <footer class="report-footer-line"></footer>
    `;

    return `
        <div class="report-container a4-size">
            ${headerHtml}
            ${detailsHtml}
            ${sectionTitleHtml}
            ${tableHtml}
            ${totalsHtml}
        </div>
    `;
}

// Global functions for commenting and highlighting
let highlightListener = null;


// Function to highlight the selected text
function highlightText() {
    const selection = window.getSelection();
    if (selection.toString().length > 0) {
        const span = document.createElement('span');
        span.style.backgroundColor = '#ffc107';
        span.style.fontWeight = 'bold';
        span.style.color = '#000';
        try {
            selection.getRangeAt(0).surroundContents(span);
        } catch (e) {
            console.error("Could not highlight text. Selection is not valid.", e);
        }
    }
}

// Function to undo all highlighting
function undoHighlighting() {
    const highlightedElements = document.querySelectorAll('.report-container span[style*="background-color: rgb(255, 193, 7)"]');
    highlightedElements.forEach(span => {
        const parent = span.parentNode;
        while (span.firstChild) {
            parent.insertBefore(span.firstChild, span);
        }
        parent.removeChild(span);
    });
}

// Function to apply a comment
function applyComment(comment) {
    const reportContainer = document.querySelector('.report-container');
    if (reportContainer) {
        let commentElement = document.querySelector('.comment-note');
        if (!commentElement) {
            commentElement = document.createElement('p');
            commentElement.classList.add('comment-note');
            commentElement.style.cssText = 'background-color: #f0f0f0; padding: 10px; border-left: 4px solid #007bff; margin-top: 20px;';
            reportContainer.appendChild(commentElement);
        }
        commentElement.textContent = `ملاحظة: ${comment}`;
    }
}

// Function to show a professional popup message
function showPopupMessage(message, type) {
    let popup = document.getElementById('myPopupMessage');
    if (!popup) {
        popup = document.createElement('div');
        popup.id = 'myPopupMessage';
        popup.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 15px 25px;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            font-family: Arial, sans-serif;
            font-size: 16px;
            z-index: 2000;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        `;
        document.body.appendChild(popup);
    }

    if (type === 'success') {
        popup.style.backgroundColor = '#28a745';
    } else if (type === 'info') {
        popup.style.backgroundColor = '#17a2b8';
    } else if (type === 'warning') {
        popup.style.backgroundColor = '#ffc107';
    }

    popup.textContent = message;
    popup.style.opacity = '1';

    setTimeout(() => {
        popup.style.opacity = '0';
        setTimeout(() => popup.remove(), 500);
    }, 3000);
}


// Function to create the comment box and its buttons
function createCommentBox() {
    const existingBox = document.getElementById('commentBox');
    if (existingBox) {
        existingBox.remove();
        return;
    }

    const commentBox = document.createElement('div');
    commentBox.id = 'commentBox';
    commentBox.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        z-index: 1000;
        min-width: 300px;
        min-height: 250px;
        resize: both;
        overflow: auto;
        border-radius: 8px;
    `;

    // Add a draggable header to the comment box
    const header = document.createElement('div');
    header.style.cssText = `
        cursor: move;
        height: 30px;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1001;
    `;
    commentBox.appendChild(header);

    // Make the box draggable
    let isDragging = false;
    let offsetX, offsetY;

    header.addEventListener('mousedown', (e) => {
        isDragging = true;
        offsetX = e.clientX - commentBox.getBoundingClientRect().left;
        offsetY = e.clientY - commentBox.getBoundingClientRect().top;
    });

    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        commentBox.style.left = `${e.clientX - offsetX}px`;
        commentBox.style.top = `${e.clientY - offsetY}px`;
        commentBox.style.transform = 'none';
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
    });

    // زر الإغلاق تم حذفه من هنا

    const textarea = document.createElement('textarea');
    textarea.placeholder = 'اكتب تعليقك هنا...';
    textarea.style.cssText = `
        width: 100%;
        height: 120px;
        border: 1px solid #ddd;
        padding: 10px;
        box-sizing: border-box;
        margin-top: 10px;
        resize: vertical;
        border-radius: 4px;
        font-size: 14px;
    `;
    commentBox.appendChild(textarea);

    const buttonContainer = document.createElement('div');
    buttonContainer.style.cssText = `
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-top: 10px;
        gap: 10px;
        flex-wrap: wrap;
    `;
    commentBox.appendChild(buttonContainer);

    const saveBtn = document.createElement('button');
    saveBtn.textContent = 'حفظ التعليق';
    saveBtn.style.cssText = `
        padding: 8px 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        flex-shrink: 0;
    `;
    saveBtn.onclick = () => {
        const comment = textarea.value;
        if (comment) {
            applyComment(comment);
        }
        if (highlightListener) {
            document.body.removeEventListener('mouseup', highlightListener);
        }
        commentBox.remove();
        showPopupMessage('تم حفظ التعليق بنجاح.', 'success');
    };
    buttonContainer.appendChild(saveBtn);

    const highlightBtn = document.createElement('button');
    highlightBtn.textContent = 'تظليل نص محدد';
    highlightBtn.style.cssText = `
        padding: 8px 12px;
        background-color: #ffc107;
        color: black;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        flex-shrink: 0;
    `;
    highlightBtn.onclick = () => {
        if (highlightListener) {
            document.body.removeEventListener('mouseup', highlightListener);
        }
        highlightListener = highlightText;
        document.body.addEventListener('mouseup', highlightListener);
        showPopupMessage('يمكنك الآن تحديد النص المراد تظليله.', 'info');
    };
    buttonContainer.appendChild(highlightBtn);

    const saveChangesBtn = document.createElement('button');
    saveChangesBtn.textContent = 'حفظ التغييرات';
    saveChangesBtn.style.cssText = `
        padding: 8px 12px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        flex-shrink: 0;
    `;
    saveChangesBtn.onclick = () => {
        if (highlightListener) {
            document.body.removeEventListener('mouseup', highlightListener);
        }
        commentBox.remove();
        showPopupMessage('تم حفظ التغييرات بنجاح.', 'success');
    };
    buttonContainer.appendChild(saveChangesBtn);

    const undoChangesBtn = document.createElement('button');
    undoChangesBtn.textContent = 'التراجع عن التغييرات';
    undoChangesBtn.style.cssText = `
        padding: 8px 12px;
        background-color: #dc3545;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        flex-shrink: 0;
    `;
    undoChangesBtn.onclick = () => {
        undoHighlighting();
        if (highlightListener) {
            document.body.removeEventListener('mouseup', highlightListener);
        }
        commentBox.remove();
        showPopupMessage('تم التراجع عن جميع التظليلات.', 'warning');
    };
    buttonContainer.appendChild(undoChangesBtn);

    document.body.appendChild(commentBox);
}

// ربط الأزرار بوظائفها
        document.querySelector('.approve-btn').onclick = () => alert('Approve button clicked!');

        document.querySelector('.decline-btn').onclick = () => {
            const reportContainer = document.querySelector('.report-container');
            if (reportContainer) {
                let declinedStamp = document.querySelector('.declined-stamp');
                if (!declinedStamp) {
                    declinedStamp = document.createElement('div');
                    declinedStamp.classList.add('declined-stamp');
                    declinedStamp.textContent = 'Declined';
                    reportContainer.appendChild(declinedStamp);
                }
            }
        };





function previewQuotation() {
    // 1. احصل على ID عرض السعر المحدد من الجدول الرئيسي
    const quotationId = getSingleSelectedQuotationId();

    if (!quotationId) {
        showToast("يرجى اختيار عرض سعر من الجدول أولاً.", "warning");
        return;
    }

    // 2. ابحث عن الصف المحدد في جدول DataTables باستخدام ID
    // هذا يفترض أن لديك دالة `getQuotationDataById`
    const quotationData = getQuotationDataById(quotationId);

    if (!quotationData) {
        showToast("فشل العثور على بيانات عرض السعر.", "error");
        return;
    }

    // 3. استخدم البيانات المجمعة لتنسيق التقرير وعرضه
    const dataForReport = {
        header: quotationData.header,
        lines: quotationData.lines // تأكد من أن هذه البيانات موجودة في جدولك
    };

    const reportHtml = formatQuotation(dataForReport);
    document.getElementById('report-content').innerHTML = reportHtml;
    $('#quotationModalpre').modal('show'); // افتح نافذة المعاينة
}
document.addEventListener('DOMContentLoaded', () => {
    // START OF YOUR EXISTING CODE
    const modalContainer = document.getElementById('modalpre-container');
    const quotationModal = document.getElementById('quotationModalpre');
    const modalHeader = document.querySelector('.modalpre-header');
    const reportContent = document.getElementById('report-content');
    const closeBtn = document.querySelector('.close-btn');
    const maximizeBtn = document.querySelector('.maximize-btn');
    const minimizeBtn = document.querySelector('.minimize-btn');
    const sizePercentageSpan = document.getElementById('resize-percentage');

    let isDragging = false;
    let isResizing = false;
    let resizeDirection = null;
    let startX, startY, startWidth, startHeight;
    let startLeft, startTop;
    let offsetX, offsetY;
    let isMaximized = false;
    let isMinimized = false;
    let originalPosition = {};
    let originalSize = {};

    // Attach event listeners to existing buttons
    document.querySelector('.comment-btn').addEventListener('click', createCommentBox);
    document.querySelector('.approve-btn').addEventListener('click', () => showPopupMessage('تمت الموافقة على التقرير بنجاح.', 'success'));
    document.querySelector('.decline-btn').addEventListener('click', () => {
        const reportContainer = document.querySelector('.report-container');
        if (reportContainer) {
            let declinedStamp = document.querySelector('.declined-stamp');
            if (!declinedStamp) {
                declinedStamp = document.createElement('div');
                declinedStamp.classList.add('declined-stamp');
                declinedStamp.textContent = 'Declined';
                reportContainer.appendChild(declinedStamp);
            }
        }
        showPopupMessage('تم رفض التقرير.', 'warning');
    });

    document.querySelector('.print-btn').addEventListener('click', () => {
        const reportContainer = document.querySelector('.report-container');
        const printFrame = document.createElement('iframe');
        printFrame.style.cssText = 'position: absolute; top: -9999px; left: -9999px;';
        document.body.appendChild(printFrame);
        const doc = printFrame.contentWindow.document;

        let contentToPrint = '<html><head><title>Print</title>';

        // Copy all stylesheets
        document.querySelectorAll('link[rel="stylesheet"], style').forEach(sheet => {
            contentToPrint += sheet.outerHTML;
        });

        // Add print-specific styles
        contentToPrint += `
        <style>
            @media print {
                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                .report-container, .report-header, .report-footer {
                    background-color: transparent !important;
                    border: none !important;
                }
                .highlighted-text {
                    background-color: #ffc107 !important;
                    color: #000 !important;
                }
                .declined-stamp {
                    color: red !important;
                    border: 2px solid red !important;
                    opacity: 1 !important;
                }
                /* Hide buttons and unnecessary elements */
                .modalpre-header, .modalpre-footer, .button-container { display: none; }
            }
        </style>
        `;
        contentToPrint += '</head><body>';
        contentToPrint += reportContainer.outerHTML;
        contentToPrint += '</body></html>';

        doc.open();
        doc.write(contentToPrint);
        doc.close();

        setTimeout(() => {
            printFrame.contentWindow.focus();
            printFrame.contentWindow.print();
            setTimeout(() => {
                document.body.removeChild(printFrame);
            }, 500);
        }, 100);
    });

    // Dragging and resizing logic
    if (modalHeader) {
        modalHeader.addEventListener('mousedown', (e) => {
            isDragging = true;
            offsetX = e.clientX - quotationModal.offsetLeft;
            offsetY = e.clientY - quotationModal.offsetTop;
            quotationModal.style.cursor = 'grabbing';
        });
    }

    document.querySelectorAll('.resize-handle').forEach(handle => {
        handle.addEventListener('mousedown', (e) => {
            e.stopPropagation();
            isResizing = true;
            resizeDirection = handle.classList.contains('right') ? 'right' : 'left';
            startX = e.clientX;
            startWidth = quotationModal.offsetWidth;
            startLeft = quotationModal.offsetLeft;
        });
    });

    document.addEventListener('mousemove', (e) => {
        if (isDragging) {
            quotationModal.style.left = `${e.clientX - offsetX}px`;
            quotationModal.style.top = `${e.clientY - offsetY}px`;
        }
        if (isResizing) {
            if (resizeDirection === 'right') {
                const newWidth = startWidth + (e.clientX - startX);
                quotationModal.style.width = `${newWidth}px`;
            } else if (resizeDirection === 'left') {
                const newWidth = startWidth - (e.clientX - startX);
                const newLeft = startLeft + (e.clientX - startX);
                quotationModal.style.width = `${newWidth}px`;
                quotationModal.style.left = `${newLeft}px`;
            }
        }
    });

    document.addEventListener('mouseup', () => {
        isDragging = false;
        isResizing = false;
        resizeDirection = null;
        quotationModal.style.cursor = 'default';
    });

    // Maximize and Minimize logic
    if (maximizeBtn) {
        maximizeBtn.addEventListener('click', () => {
            if (!isMaximized) {
                originalPosition = { left: quotationModal.style.left, top: quotationModal.style.top };
                originalSize = { width: quotationModal.style.width, height: quotationModal.style.height };
                quotationModal.style.left = '0';
                quotationModal.style.top = '0';
                quotationModal.style.width = '100vw';
                quotationModal.style.height = '100vh';
                quotationModal.style.transform = 'none';
                isMaximized = true;
            } else {
                quotationModal.style.left = originalPosition.left;
                quotationModal.style.top = originalPosition.top;
                quotationModal.style.width = originalSize.width;
                quotationModal.style.height = originalSize.height;
                isMaximized = false;
            }
            updateSizePercentage();
        });
    }

    if (minimizeBtn) {
        minimizeBtn.addEventListener('click', () => {
            if (isMinimized) {
                quotationModal.style.width = originalSize.width;
                quotationModal.style.height = originalSize.height;
                quotationModal.style.display = 'flex';
                isMinimized = false;
            } else {
                originalSize = { width: quotationModal.style.width, height: quotationModal.style.height };
                quotationModal.style.width = '200px';
                quotationModal.style.height = '50px';
                quotationModal.style.bottom = '10px';
                quotationModal.style.right = '10px';
                quotationModal.style.top = 'auto';
                quotationModal.style.left = 'auto';
                quotationModal.style.transform = 'none';
                isMinimized = true;
            }
            updateSizePercentage();
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modalContainer.style.display = 'none';
        });
    }

    // HERE IS WHERE YOU SHOULD INSERT THE CODE FOR THE PREVIEW BUTTON
    // Do not create a new document.addEventListener('DOMContentLoaded', ...); block
    const previewBtn = document.getElementById('preview-btn');
    if (previewBtn) {
        previewBtn.addEventListener('click', () => {
            const quotationData = getQuotationDataFromUI();

            // تحقق من وجود البيانات قبل عرضها
            if (quotationData) {
                // الوصول إلى العناصر التي ستحتاجها
                const reportContent = document.getElementById('report-content');
                const modalContainer = document.getElementById('modalpre-container');
                const quotationModal = document.getElementById('quotationModalpre');

                reportContent.innerHTML = formatQuotation(quotationData);
                modalContainer.style.display = 'flex';
                quotationModal.style.display = 'flex';
                quotationModal.style.width = '800px';
                quotationModal.style.height = '600px';
                quotationModal.style.left = '50%';
                quotationModal.style.top = '50%';
                quotationModal.style.transform = 'translate(-50%, -50%)';

                isMaximized = false;
                isMinimized = false;
                updateSizePercentage();
            }
        });
    }
/**
 * تبحث عن بيانات عرض السعر الكاملة في جدول DataTables
 * بناءً على ID الخاص بالصف.
 * @param {string} quotationId - الـ ID الخاص بعرض الأسعار المراد استرجاع بياناته.
 * @returns {object|null} كائن بيانات عرض السعر أو null إذا لم يتم العثور عليه.
 */
function getQuotationDataById(quotationId) {
    if (!quotationDataTable) {
        console.error("جدول عروض الأسعار غير مُهيأ.");
        return null;
    }

    // ابحث عن الصف الذي يتطابق مع الـ ID
    let rowData = null;
    quotationDataTable.rows().every(function () {
        const data = this.data();
        // هنا يجب أن تتأكد من أن `data.id` أو أي حقل آخر هو معرف فريد
        // إذا كان لديك حقل فريد مثل quoteNo، استخدمه بدلاً من ID
        if (data.id === quotationId) {
            rowData = data;
            return false; // توقف عن البحث بعد العثور على الصف
        }
    });

    return rowData;
}

    // Resize and percentage update
    function updateSizePercentage() {
        const currentWidth = quotationModal.offsetWidth;
        const currentHeight = quotationModal.offsetHeight;
        const originalWidth = 800; // Assuming initial width
        const originalHeight = 600; // Assuming initial height
        const widthPercentage = Math.round((currentWidth / originalWidth) * 100);
        if (sizePercentageSpan) {
            sizePercentageSpan.textContent = `${widthPercentage}%`;
        }
    }

    // Global function to show the modal
    window.previewQuotation = (quotationId) => {
        // Here you would fetch data, for now we use the local function
        const quotationData = getQuotationDataFromUI(); // Using the new function
        reportContent.innerHTML = formatQuotation(quotationData);
        modalContainer.style.display = 'flex';
        quotationModal.style.display = 'flex';
        quotationModal.style.width = '800px';
        quotationModal.style.height = '600px';
        quotationModal.style.left = '50%';
        quotationModal.style.top = '50%';
        quotationModal.style.transform = 'translate(-50%, -50%)';
        isMaximized = false;
        isMinimized = false;
        updateSizePercentage();
    };

    const resizeObserver = new ResizeObserver(entries => {
        for (let entry of entries) {
            if (entry.target === quotationModal) {
                updateSizePercentage();
            }
        }
    });
    resizeObserver.observe(quotationModal);
});
</script>
    <style>

:root {
    --primary-color: #007BFF;
    --primary-hover-color: #0056b3;
    --primary-color-rgb: 0, 123, 255;
    --success-color: #28a745;
    --success-hover-color: #218838;
    --danger-color: #dc3545;
    --danger-hover-color: #c82333;
    --bg-color: #f4f7f6;
    --secondary-bg-color: #ffffff;
    --sidebar-bg-color: #ffffff;
    --card-bg-color: #fefefe;
    --input-bg-color: #ffffff;
    --header-bg-color: #e9ecef;
    --modal-header-bg-color: #f8f9fa;
    --text-color: #333;
    --text-color-light: #666;
    --text-color-dark: #222;
    --border-color: #dee2e6;
    --hover-bg-color: #f5f5f5;
    --button-bg-color: #007bff;
    --button-hover-color: #0056b3;
    --button-bg-color-light: #e0e0e0;
    --button-border-color: #cdd4da;
    --button-darker-bg: #d0d0d0;
    --button-darker-border: #bbbbbb;
    --table-alt-row-bg: #f9f9f9;
    --table-hover-bg: #e6f7ff;
    --filter-row-bg: #f2f2f2;
    --selection-color: #D6ECFF;
    --selection-border-color: #007BFF;
    --group-header-bg: #d9edf7;
    --group-header-text: #31708f;
    --icon-color: #FF8C00;
    --icon-hover-color: #FF6600;
}

body.dark-mode {
    --primary-color: #6a9eff;
    --primary-hover-color: #4b88ff;
    --primary-color-rgb: 106, 158, 255;
    --success-color: #26a69a;
    --success-hover-color: #1a7d70;
    --danger-color: #ef5350;
    --danger-hover-color: #d32f2f;
    --bg-color: #2c2c2c;
    --secondary-bg-color: #3a3a3a;
    --sidebar-bg-color: #3a3a3a;
    --card-bg-color: #444444;
    --input-bg-color: #333333;
    --header-bg-color: #4a4a4a;
    --modal-header-bg-color: #424242;
    --text-color: #e0e0e0;
    --text-color-light: #b0b0b0;
    --text-color-dark: #f0f0f0;
    --border-color: #555555;
    --hover-bg-color: #4a4a4a;
    --button-bg-color: #6a9eff;
    --button-hover-color: #4b88ff;
    --button-bg-color-light: #505050;
    --button-border-color: #6a6a6a;
    --button-darker-bg: #404040;
    --button-darker-border: #5a5a5a;
    --table-alt-row-bg: #333333;
    --table-hover-bg: #4c4c4c;
    --filter-row-bg: #3c3c3c;
    --selection-color: #3d5a80;
    --selection-border-color: #6a9eff;
    --group-header-bg: #2a4c6a;
    --group-header-text: #e0e0e0;
    --icon-color: #ffb74d;
    --icon-hover-color: #ffa726;
}

.modalpre-container {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}

.modalpre-content {
    background-color: var(--secondary-bg-color);
    border: 1px solid var(--border-color);
    width: 800px;
    height: 600px;
    position: absolute;
    overflow: hidden; /* Prevent modal content from scrolling */
    min-width: 400px;
    min-height: 300px;
    padding: 0;
    display: flex;
    flex-direction: column;
}

.modalpre-content .resize-handle {
    position: absolute;
    top: 0;
    width: 10px;
    height: 100%;
    cursor: ew-resize;
}

.modalpre-content .resize-handle.right {
    right: -5px;
}

.modalpre-content .resize-handle.left {
    left: -5px;
}

.modalpre-header {
    cursor: move;
    padding: 40px 40px;
    background-color: #1e2b4a;
    color: white;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-shrink: 0;
}

.headerpre-left-controls {
    display: flex;
    align-items: center;
    gap: 15px;
}

.modalpre-controls {
    display: flex;
    align-items: center;
    gap: 5px;
}

.icon-btn {
    background: none;
    border: none;
    padding: 0;
    font-size: 1.4rem;
    cursor: pointer;
    color: var(--icon-color);
    transition: color 0.2s ease-in-out;
}

.icon-btn:hover {
    color: var(--icon-hover-color);
}

.modalpre-controls button {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    margin-left: 5px;
    color: white;
}

.modalpre-body {
    padding: 20px;
    flex-grow: 1;
    overflow-y: auto;
    display: flex; /* Use flexbox to center the report */
    justify-content: center; /* Center horizontally */
    align-items: flex-start; /* Align to the top vertically */
    background-color: #E0E0E0; /* Gray background for the modal body */
}

.resize-percentage {
    font-size: 0.9rem;
    color: white;
    margin-left: 10px;
}

@page {
    size: A4;
    margin: 0;
}

@media print {
    body {
        margin: 0;
        padding: 0;
        background-color: #fff;
    }
    .modalpre-container {
        display: block !important;
        position: static !important;
        width: 100% !important;
        height: auto !important;
        background: none !important;
    }
    .modalpre-content {
        box-shadow: none !important;
        border: none !important;
        width: 21cm !important;
        height: 29.7cm !important;
        padding: 2cm !important;
        overflow: visible !important;
        position: static !important;
    }
    .modalpre-header, .resize-handle {
        display: none !important;
    }
    .report-container {
        width: 100%;
        height: 100%;
        box-sizing: border-box;
    }
}

.report-container {
  position: relative;
    padding-bottom: 70px;
    padding: 30px;
    font-family: Arial, sans-serif;
    color: var(--text-color-dark);
    /* New A4 size and white background */
    width: 21cm; /* A4 width */
    min-height: 29.7cm; /* A4 height */
    background-color: #FFFFFF; /* White background for the report */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Add a subtle shadow to separate it from the background */
    margin: 20px; /* Add margin for spacing */
}

/* تنسيق الهيدر الرئيسي */
.report-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end; /* لمحاذاة العناصر إلى الأسفل */
    padding: 10px 0; /* مسافة داخلية علوية وسفلية */
}


.office-info {
    display: flex;
    align-items: center;
    gap: 20px;
}

.office-info .logo {
    max-width: 150px;
}

.office-info .contact-details p {
    margin: 0;
    font-size: 0.9em;
    line-height: 1.5;
}

/* تنسيق قسم المعلومات اليسرى (الإنجليزية) */
.header-info-left {
    flex: 1; /* لتأخذ مساحة متساوية */
    text-align: left;
    font-size: 0.6em; /* تصغير حجم الخط */
    line-height: 1.2;
    padding-left: 0px; /* مسافة من حافة الصفحة */
    text-align: center;
}

/* تنسيق قسم الشعار المركزي */
.header-logo-center {
    flex-shrink: 0; /* لمنع الشعار من التقلص */
    text-align: center;
    padding: 0 10px;
}

.logo {
    max-width: 120px; /* تصغير حجم الشعار قليلاً ليناسب */
    height: auto;
    display: block; /* لضمان توسيط الشعار بشكل صحيح */
    margin: 0 auto;
}

/* تنسيق قسم المعلومات اليمنى (العربية) */
.header-info-right {
    flex: 1; /* لتأخذ مساحة متساوية */
    text-align: right;
    font-size: 0.6em; /* تصغير حجم الخط */
    line-height: 1.2;
    padding-right: 0px; /* مسافة من حافة الصفحة */
    text-align: center;
}

/* تنسيق الخط الأزرق الفاصل تحت الهيدر */
.header-underline {
    border-bottom: 2px solid #007BFF; /* لون أزرق غامق */
    margin: 5px 0 15px 0; /* مسافة بين الهيدر والعنوان */
    width: 100%;
}

/* تنسيق عنوان التقرير */
.report-title {
    font-size: 1.2em; /* حجم العنوان */
    font-weight: bold;
    text-align: center;
    margin-top: 15px; /* مسافة بعد الخط */
    margin-bottom: 20px; /* مسافة قبل محتوى التقرير */
    color: #333; /* لون نص العنوان */
}

.proposal-details {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
    padding: 0 10px; /* مسافة من حواف الصفحة */
}

.details-column {
    flex: 1;
    padding: 10px;
    /* إزالة الخلفية والحدود الافتراضية إذا كانت موجودة */
    background-color: transparent;
    border: none;
}

.detail-item {
    display: flex;
    justify-content: flex-start; /* محاذاة لليسار */
    align-items: baseline;
    margin-bottom: 5px;
    font-size: 0.7em;
}
/* التنسيق الخاص بخانة PROPOSED TO */
.detail-item:has(.label:contains("PROPOSED TO:")) .value {
    min-height: 60px; /* هذا الارتفاع يكفي لثلاثة أسطر تقريباً */

    white-space: pre-wrap; /* يسمح للنص بالنزول لسطر جديد */
}
.detail-item .label {
    font-weight: bold;
    color: #333;
    min-width: 120px; /* لتحديد عرض ثابت للـ label */
    text-align: left;
    margin-right: 10px; /* مسافة بين الـ label والـ value */
}

.detail-item .value {
    flex-grow: 1; /* للسماح للقيمة بأن تأخذ المساحة المتبقية */
    color: #555;
    background-color: transparent; /* لضمان عدم وجود خلفية افتراضية */
    padding: 2px 0;
}

/* تنسيق خاص لحقل PROPOSAL # ليتضمن REV */
.proposal-number-highlight {
    background-color: rgba(230, 230, 230, 0.7); /* ظل رمادي خفيف */
    padding: 2px 5px;
    border-radius: 3px;
    display: inline-flex; /* لجعل REV بجانبه */
    align-items: center;
    gap: 5px;
}

.rev-label {
    font-size: 0.8em;
    color: #777;
}

.rev-value {
    font-weight: normal;
}

.proposed-to-item .value {
    min-height: 100px; /* هذا الارتفاع يكفي لثلاثة أسطر تقريباً */
    white-space: pre-wrap; /* يسمح للنص بالنزول لسطر جديد */
}
/* تنسيق أقسام Contact Person و Project */
.contact-person-details,
.project-details {
    margin-top: 15px; /* مسافة بين هذا القسم والقسم الذي يسبقه */
    padding-top: 10px;

}


.details-column .detail-item:nth-child(2) .value, /* PROPOSED TO */
.details-column .detail-item:nth-child(3) .value, /* ADDRESS */
.details-column .detail-item:nth-child(4) .value, /* CONTACT NUMBER */
.details-column .detail-item:nth-child(5) .value, /* CONTACT EMAIL */
.project-details .detail-item .value  /* PROJECT */
{
    background-color: rgba(230, 230, 230, 0.7); /* ظل رمادي خفيف */
    padding: 2px 5px;
    border-radius: 3px;
}

.report-body {
    margin-top: 20px;
}

.section-heading {
    color: var(--primary-color);
    font-size: 1.2em;
    border-bottom: 2px solid var(--primary-color);
    padding-bottom: 5px;
    margin-bottom: 15px;
}

.report-table {
    width: 100%;
    border-collapse: collapse;
     font-size: 12px;
}

.report-table th,
.report-table td {
    border: 1px solid var(--border-color);
    padding: 10px;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.report-table th {
    background-color: var(--header-bg-color);
    font-weight: bold;
    font-size: 12px;
}
/* تنسيق عمود DESCRIPTION */
.description-cell {
    width: 70%; /* يمكنك زيادة هذه القيمة أو تقليلها حسب الحاجة */
    white-space: normal; /* السماح للنص بالالتفاف إلى سطر جديد */
    word-wrap: break-word; /* التأكد من أن الكلمات الطويلة لا تسبب تجاوزًا */
}



.totals {
    width: 350px;
    border: 1px solid var(--border-color);
    padding: 20px;
    background-color: var(--card-bg-color);
    border-radius: 8px;
}

.total-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.final-total {
    font-weight: bold;
    font-size: 1.2em;
    padding-top: 10px;
    border-top: 1px dashed var(--border-color);
}

.amount-in-words {
    font-style: italic;
    color: var(--text-color-light);
    margin-top: 15px;
}

.editable:hover {
    cursor: text;
    background-color: var(--hover-bg-color);
    outline: 1px solid var(--primary-color);
}
/* الأنماط الخاصة بجدول الإجماليات (totals) */
/* الأنماط الخاصة بجدول الإجماليات (totals) */
.totals {
    width: 250px; /* جعل العرض أصغر ليتناسب مع الصورة */
    text-align: right; /* محاذاة النص إلى اليمين */
    padding: 10px; /* إضافة مسافة داخلية لتوفير مساحة للظل */
    border: none; /* إزالة الحدود */
    background-color: transparent; /* جعل الخلفية شفافة */
    margin-left: auto; /* دفع العنصر إلى اليمين */
    margin-right: 0; /* إزالة المسافة من اليمين */

    /* إضافة الظل الرمادي الخفيف */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: rgba(240, 240, 240, 0.5); /* لون خلفية رمادي فاتح وشفاف */
    border-radius: 5px; /* حواف دائرية للظل */
}

.total-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px; /* تقليل المسافة بين العناصر */
    font-size: 0.8em; /* تصغير حجم الخط لجميع العناصر */
}

.total-item .label {
    font-weight: normal; /* جعل الخط عاديًا */
}

.total-item .value {
    font-weight: bold; /* جعل القيمة بخط عريض */
}

.final-total {
    font-size: 0.9em; /* زيادة حجم خط الإجمالي النهائي قليلاً */
    padding-top: 5px;
    border-top: 1px dashed var(--border-color);
}

.amount-in-words {
    font-size: 0.8em; /* تصغير حجم خط الكلام */
    font-style: italic;
    color: var(--text-color-light);
    margin-top: 15px;
}
/* تنسيق الخط الأزرق للفوتر */
.report-footer-line {
 background-color: #1e2b4a;
 height: 1.5cm; /* ارتفاع مناسب */
 width: 100%;
 position: absolute;
 bottom: 0;
 left: 0;
}
@media print {
    /* تحديد حجم الصفحة A4 وإزالة الهوامش الافتراضية */
    @page {
        size: A4;
        margin: 0;
    }

    /* ضمان أن حاوية التقرير تملأ الصفحة بالكامل */
    .report-container {
        width: 21cm;
        height: 29.7cm; /* ارتفاع صفحة A4 */
        margin: 0;
        padding: 1cm; /* يمكنك تعديل الهوامش الداخلية هنا حسب حاجتك */
        box-sizing: border-box;
    }

    /* تنسيق خط الفوتر للطباعة فقط */
    .report-footer-line {
        background-color: #1e2b4a;
        height: 1.5cm; /* ارتفاع احترافي رفيع */
        width: 100%; /* عرض يملأ عرض الحاوية بالكامل */
        position: absolute;
        bottom: 0;
        left: 0;
    }
}

/* تنسيق الفوتر الخاص بالنافذة المنبثقة (المودال) */
.modalpre-footer {
    padding: 10px 20px;
    border-top: 1px solid #ccc;
    text-align: center;
    background-color: #f8f9fa; /* لون خلفية فاتح */
    border-radius: 0 0 8px 8px; /* حواف دائرية في الأسفل */
    position: absolute; /* يوضع في أسفل المودال */
    bottom: 0;
    width: 100%;
}

.greeting-text {
    margin-top: 20px;
    margin-bottom: 10px;
    font-size: 0.7em;
    color: #333;
}

.intro-text {
    background-color: #f0f0f0; /* لون الخلفية الرمادي */
    padding: 15px; /* مسافة داخلية */
    border-radius: 5px; /* حواف دائرية خفيفة */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* الظل */
    font-size: 0.7em;
    color: #333;
    line-height: 1.5; /* مسافة بين الأسطر */
}

.declined-stamp {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-45deg);
    font-size: 80px;
    font-weight: bold;
    color: red;
    opacity: 0.3;
    pointer-events: none;
    z-index: 10;
}
.comment-note {
    margin-top: 20px;
    font-size: 14px;
    color: darkgreen;
    font-weight: bold;
    text-align: right;
}
@media print {
    body > *:not(.report-container) {
        display: none !important;
    }
    .report-container {
        display: block !important;
        width: 100% !important;
        height: auto !important;
        box-shadow: none !important;
    }
}
</style>


        <section class="section-content" id="quotationSection" style="display: block;">
            <table id="quotationTable" class="quotation-table" data-ignore-lang>
                <thead>
                    <tr>
                       <th><input type="checkbox" onclick="toggleSelectAllQuotationsMin(this)" id="selectQuotationsMin" /></th>

                            <th><i class="fas fa-circle" style="color: grey;" title="جديد / قيد الإنشاء"></i></th>
            <th><i class="fas fa-list-alt" style="color: blue;" title="مكتمل / مرسل"></i></th>
            <th><i class="fas fa-play-circle" style="color: green;" title="فعال / قيد التقدم"></i></th>
            <th><i class="fas fa-check-circle" style="color: #28a745;" title="معتمد / مقبول"></i></th>
            <th><i class="fas fa-exclamation-triangle" style="color: red;" title="مرفوض / مشكلة"></i></th>

                        <th>Category</th>
                        <th>Quote No</th>
                        <th>Rev.</th>
                        <th>Quote Date</th>
                        <th>Project Code</th>
                        <th>Legacy No</th>
                        <th>Legacy Date</th>
                        <th>Customer</th>
                        <th>Project Name</th>
                        <th>Project Details</th>
                        <th>Subject</th>
                        <th>From</th>
                        <th>Inquiry</th>
                        <th>Contact</th>
                        <th>To</th>
                        <th>Attn. To</th>
                        <th>Attn. Pos</th>
                        <th>Discount</th>
                        <th>VAT</th>
                        <th>Validity</th>
                        <th>Currency</th>
                        <th>Payment Terms</th>
                        <th>Method</th>
                        <th>Remarks</th>
                        <th>Quote File</th>
                        <th>File Status</th>
                        <th>Declined</th>
                        <th>Declined Msg</th>
                          <th>إجراءات</th>
                    </tr>
                    <tr class="filter-row">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="date" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="date" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </section>
    </section>

    <!-- Container for fixed bottom pagination -->
    <div id="quotation-pagination-fixed-bottom" class="fixed-bottom-bar">
        <!-- DataTables will inject pagination and info here via JavaScript -->
    </div>

    <div id="quotationModal" class="modal" style="display: none;">
        <div class="modal-content large-modal quotation-modal-grid">
            <span class="close" onclick="closeQuotationModal()">&times;</span>

            <ul class="tab-list">
                <li class="tab active" data-tab-target="headerTab" onclick="openTab(event, 'headerTab')">Quote Header</li>
                <li class="tab" data-tab-target="linesTab" onclick="openTab(event, 'linesTab')">Quote Lines</li>
                <li class="tab" data-tab-target="confirmationTab" onclick="openTab(event, 'confirmationTab')">Confirmation</li>
                <li class="tab" data-tab-target="additionalInfoTab" onclick="openTab(event, 'additionalInfoTab')">Additional Information</li>
                <li class="tab" data-tab-target="statusTab" onclick="openTab(event, 'statusTab')">Status & Market</li>
                <li class="tab" data-tab-target="approvalTab" onclick="openTab(event, 'approvalTab')">Approval Activities</li>
                <li class="tab" data-tab-target="contactsTab" onclick="openTab(event, 'contactsTab')">Published Contacts</li>
            </ul>

            <div class="modal-body-content">
                <div id="headerTab" class="tab-content active">
                    <div class="left-form-section">
                        <fieldset class="form-section">
                            <input type="hidden" id="currentQuotationId" value="">
                            <legend>Quote Info</legend>
                            <div class="form-grid-2-col">
                                <label for="quoteCategory">Category:<span class="required-star">*</span></label>
<div class="input-with-button" style="position: relative;">
    <input type="text" id="quoteCategory" placeholder="Select a category" required autocomplete="off" readonly>
    <button type="button" id="showCategoryListBtn" class="dropdown-toggle-btn" title="Show Categories">
        <i class="fa fa-caret-down"></i>
    </button>
    <div id="categoryDropdown" class="custom-dropdown-list">
        </div>
</div>

                                <label for="quoteNo">Quote No.:</label>
                                <input type="text" id="quoteNo" autocomplete="off">

                                <label for="quoteRev">Rev.:</label>
                                <input type="text" id="quoteRev" autocomplete="off">

                                <label for="quoteDate">Quote Date:</label>
                                <input type="date" id="quoteDate" autocomplete="off">


   <label for="quoteProjectCodeInput">Project Code:<span class="required-star">*</span></label>
<div class="input-group project-code-input-group">
    <input
        type="text"
        id="quoteProjectCodeInput"
        class="form-control"
        placeholder="Search or select Project Code"
        autocomplete="off"
        readonly >

    <button
        type="button"
        id="showProjectCodeListBtn"
        class="btn btn-outline-secondary project-code-dropdown-toggle-btn"
        title="Show Project List" >
        <i class="fas fa-caret-down"></i> </button>

    <div id="projectCodeDropdown" class="custom-dropdown-list">
        </div>
</div>


                                <label for="quoteLegacyNo">Legacy No:</label>
                                <input type="text" id="quoteLegacyNo" autocomplete="off">

                                <label for="quoteLegacyDate">Legacy Date:</label>
                                <input type="date" id="quoteLegacyDate" autocomplete="off">


    <label for="quoteCustomer">Customer Name</label>
    <input type="text" class="form-control" id="quoteCustomer" readonly>



    <label for="quoteProject">Project Name</label>
    <input type="text" class="form-control" id="quoteProject" readonly>


                                <label for="quoteProjectDetails">Project Details:</label>
                                <input type="text" id="quoteProjectDetails" autocomplete="off">

                                <label for="quoteSubject">Subject:<span class="required-star">*</span></label>
                                <input type="text" id="quoteSubject" required autocomplete="off">

                            </div>
                        </fieldset>

                        <fieldset class="form-section">
                            <legend>Contact Info</legend>
                            <div class="form-grid-2-col">
                                <label for="quoteContactFrom">From:<span class="required-star">*</span></label>
                                <div class="input-with-button" style="position: relative;">
                                    <input type="text" id="quoteContactFrom" placeholder="Select from list or type" autocomplete="off">
                                    <button type="button" id="showEmployeesListBtnEmployee" class="dropdown-toggle-btn" title="Show Employees">
                                        <i class="fa fa-caret-down"></i> </button>
                                    <div id="employeeDropdown" class="custom-dropdown-list"></div>
                                </div>

                                <label for="quoteInquiry">Inquiry:</label>
                                <input type="text" id="quoteInquiry" autocomplete="off">

<label for="quoteContactPerson">Contact:<span class="required-star">*</span></label>
<div class="input-group contact-person-input-group">
    <input
        type="text"
        id="quoteContactPerson"
        class="form-control"
        placeholder="Search or select Contact"
        autocomplete="off"
    >
    <button
        type="button"
        id="showContactPersonListBtn"
        class="btn btn-outline-secondary contact-person-dropdown-toggle-btn"
        title="Show Contacts List" >
        <i class="fas fa-caret-down"></i>
    </button>
    <div id="contactPersonDropdown" class="custom-dropdown-list">
        </div>
</div>

<label for="quoteContactTo">To:</label>
<textarea id="quoteContactTo" autocomplete="off" rows="3"></textarea>

<label for="quoteAttnTo">Attn. To:</label>
<input type="text" id="quoteAttnTo" autocomplete="off">

<label for="quoteAttnPos">Attn. Pos:</label>
<input type="text" id="quoteAttnPos" autocomplete="off">

<label for="quoteContactEmail">Contact Email:</label>
<input type="text" id="quoteContactEmail" autocomplete="off">

<label for="quoteContactMobile">Contact Mobile:</label>
<input type="text" id="quoteContactMobile" autocomplete="off">
                        </fieldset>

                        <fieldset class="form-section">
                            <legend>Terms and other Controls</legend>
                            <div class="form-grid-3-col">
                                <label for="quoteCurrency">Currency:</label>
                                <select id="quoteCurrency" autocomplete="off">
                                    <option value="SAR">SAR</option>
                                    <option value="USD">USD</option>
                                </select>

                               <label for="quoteDiscount">Discount:</label>
<div class="number-input-wrapper">
    <input type="number" id="quoteDiscount" class="form-control" value="0" step="0.01" autocomplete="off">
    <div class="number-controls">
        <button type="button" class="btn-increment">+</button>
        <button type="button" class="btn-decrement">-</button>
    </div>
</div>

<label for="quoteVAT">VAT (%):</label>
<div class="number-input-wrapper">
    <input type="number" id="quoteVAT" class="form-control" value="15" step="0.01" autocomplete="off">
    <div class="number-controls">
        <button type="button" class="btn-increment">+</button>
        <button type="button" class="btn-decrement">-</button>
    </div>
</div>

<label for="quoteValidity">Validity (Days):</label>
<div class="number-input-wrapper">
    <input type="number" id="quoteValidity" class="form-control" value="60" step="1" autocomplete="off">
    <div class="number-controls">
        <button type="button" class="btn-increment">+</button>
        <button type="button" class="btn-decrement">-</button>
    </div>
</div>

 <style>
       /* Number input with controls */
.number-input-wrapper {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 3px;
    background-color: #f9f9f9;
}
.number-input-wrapper input {
    flex-grow: 1;
    border: none;
    background-color: transparent;
    padding: 3px 5px;
    font-size: 15px;
}
.number-controls {
    display: flex;
    flex-direction: column;
    height: 100%;
    border-left: 1px solid #ccc;
}
.number-controls button {
    background-color: #f0f0f0;
    border: none;
    width: 15px;
    height: 100%;
    cursor: pointer;
    font-size: 15px;
    line-height: 1;
    padding: 0;
}
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const wrappers = document.querySelectorAll('.number-input-wrapper');

            wrappers.forEach(wrapper => {
                const input = wrapper.querySelector('input');
                const incrementBtn = wrapper.querySelector('.btn-increment');
                const decrementBtn = wrapper.querySelector('.btn-decrement');

                incrementBtn.addEventListener('click', () => {
                    const step = parseFloat(input.step) || 1;
                    const currentValue = parseFloat(input.value) || 0;
                    const newValue = currentValue + step;

                    if (step % 1 !== 0) {
                        input.value = newValue.toFixed(2);
                    } else {
                        input.value = newValue;
                    }
                });

                decrementBtn.addEventListener('click', () => {
                    const step = parseFloat(input.step) || 1;
                    const currentValue = parseFloat(input.value) || 0;
                    const newValue = currentValue - step;

                    if (step % 1 !== 0) {
                        input.value = newValue.toFixed(2);
                    } else {
                        input.value = newValue;
                    }
                });
            });
        });
    </script>
                                <label for="quotePaymentTermsInput">Payment Terms:</label>
                                <div class="input-with-button" style="position: relative;">
                                    <input type="text" id="quotePaymentTermsInput" placeholder="Select a payment method" autocomplete="off" readonly>

                                    <button type="button" id="showPaymentTermsListBtn" class="dropdown-toggle-btn" title="Show Payment Methods">
                                        <i class="fa fa-caret-down"></i>
                                    </button>

                                    <div id="paymentTermsDropdown" class="custom-dropdown-list">
                                    </div>
                                </div>

                                <label for="quoteMethod">Method:</label>
                                <input type="text" id="quoteMethod" autocomplete="off">
                            </div>
                            <div class="checkbox-container">
                                <input type="checkbox" id="quoteUseAltForm" autocomplete="off">
                                <label for="quoteUseAltForm">Use Alternative Form</label>
                            </div>
                        </fieldset>

                        <fieldset class="form-section">
                            <legend>Additional Info</legend>
                            <div class="form-grid-2-col">
                                <label for="quoteRemarks">Remarks:</label>
                                <textarea id="quoteRemarks" rows="3" autocomplete="off"></textarea>

                                <label for="quoteQuoteFile">Quote File:</label>
                                <div class="file-input-group">
                                    <input type="text" id="quoteQuoteFile" readonly autocomplete="off">
                                    <button class="btn btn-icon"><i class="fas fa-folder-open"></i></button>
                                </div>

                                <label for="quoteFileStatus">File Status:</label>
                                <input type="text" id="quoteFileStatus" value="PDF Not Created" autocomplete="off">
                            </div>
                            <div class="checkbox-container">
                                <input type="checkbox" id="quoteDeclined" autocomplete="off">
                                <label for="quoteDeclined">Declined</label>
                            </div>
                            <div class="form-grid-2-col">
                                <label for="quoteDeclinedMessage">Declined Message:</label>
                                <textarea id="quoteDeclinedMessage" rows="2" autocomplete="off"></textarea>
                            </div>
                        </fieldset>
                         <div class="modal-footer">
                <button type="button" id="closeHeaderTabBtn" class="btn btn-secondary">Close</button>
                <div class="footer-spacer"></div> <!-- Spacer to push save buttons to right -->
                    <button type="button" id="saveHeaderTabBtn" class="btn btn-primary">Save Header</button>
                    <button type="button" id="saveAndCloseHeaderTabBtn" class="btn btn-success">Save Header & Close</button>
            </div>
                    </div>

                    <!-- Financials and Quote Status sections for the Header tab -->
                    <div class="right-sidebar-section">
                        <fieldset class="form-section">
                            <legend>Financials</legend>
                            <div class="form-grid-2-col-sidebar">
                                <label for="financialTotalLines">Total Lines:</label>
                                <input type="text" id="financialTotalLines" value="0.000" autocomplete="off">

                                <label for="financialDiscountAmount">Discount Amount:</label>
                                <input type="text" id="financialDiscountAmount" value="0.000" autocomplete="off">

                                <label for="financialTaxAmount">Tax Amount:</label>
                                <input type="text" id="financialTaxAmount" value="0.000" autocomplete="off">

                                <label for="financialGrandTotal">Grand Total:</label>
                                <input type="text" id="financialGrandTotal" value="0.000" autocomplete="off">
                            </div>
                        </fieldset>

                        <fieldset class="form-section">
                            <legend>Quote Status</legend>
                            <div class="form-grid-2-col-sidebar">
                                <label for="quoteOverallStatus">Quote Status:</label>
                                <input type="text" id="quoteOverallStatus" autocomplete="off">

                                <label for="quoteLastConfirmation">Last Confirmation:</label>
                                <input type="text" id="quoteLastConfirmation" autocomplete="off">

                                <label for="quoteLastConfirmed">Last Confirmed:</label>
                                <input type="text" id="quoteLastConfirmed" autocomplete="off">
                            </div>
                        </fieldset>
                    </div>

                </div>

                <!-- START: Updated Quote Lines Tab Content -->
                <div id="linesTab" class="tab-content lines-tab-layout">
                    <div class="lines-main-content">
                        <div class="lines-toolbar">
                            <button title="Add Quote Line" class="btn btn-add-line" onclick="addQuoteLine()"><i class="fas fa-plus-square"></i></button>
                            <button title="Edit Selected Line" class="btn btn-icon" onclick="editQuoteLine()"><i class="fas fa-pen"></i></button>
                            <!-- NEW: Changed third icon to "Add Multiple Items from Price List" -->
                            <button title="Add Multiple Items from Price List" class="btn btn-icon" onclick="openPriceListModal()"><i class="fas fa-list-ul"></i></button>
                            <button title="Copy Line" class="btn btn-icon" onclick="copyQuoteLine()"><i class="fas fa-copy"></i></button>
                            <button title="Paste Line" class="btn btn-icon" onclick="pasteQuoteLine()"><i class="fas fa-paste"></i></button>
                            <button title="Clear Filters" class="btn btn-icon" onclick="clearLinesFilters()"><i class="fas fa-filter-circle-xmark"></i></button>
                            <button title="Refresh" class="btn btn-icon" onclick="refreshQuoteLinesTable()"><i class="fas fa-arrows-rotate"></i></button>
                            <span class="icon-separator"></span>
                            <button id="exportToExcelBtn" title="Export to Excel" class="btn btn-icon" onclick="exportQuoteLinesToExcel()"><i class="fas fa-file-excel"></i></button>
                            <button id="printQuoteLinesBtn" title="Print" onclick="printQuoteLinesTable()" class="btn btn-icon"><i class="fas fa-print"></i></button>
                        </div>

                        <div class="table-container lines-table-container">
                            <table id="quotationLinesTable" class="quotation-lines-table" data-ignore-lang>
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="select-all-lines" onclick="toggleSelectAllQuoteLines(this)" /></th>
                                        <th>Service/Test Id</th>
                                        <th>Line Description</th>
                                        <th>Accounted</th>
                                        <th>Category</th>
                                        <th>Type</th>
                                        <th>Method</th>
                                        <th>Actions</th>
                                    </tr>
                                    <tr class="filter-row">
                                        <td></td>
                                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                                        <td><input type="text" placeholder="Search" class="column-filter" autocomplete="off"></td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Quote lines will be populated here by DataTables -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Financials and Quote Status sections for the Lines tab - now on the right -->
                    <div class="lines-sidebar">
                        <fieldset class="form-section">
                            <legend>Financials</legend>
                            <div class="form-grid-2-col-sidebar">
                                <label for="linesFinancialTotalLines">Total Lines:</label>
                                <input type="text" id="linesFinancialTotalLines" value="0.000" autocomplete="off">

                                <label for="linesFinancialDiscountAmount">Discount Amount:</label>
                                <input type="text" id="linesFinancialDiscountAmount" value="0.000" autocomplete="off">

                                <label for="linesFinancialTaxAmount">Tax Amount:</label>
                                <input type="text" id="linesFinancialTaxAmount" value="0.000" autocomplete="off">

                                <label for="linesFinancialGrandTotal">Grand Total:</label>
                                <input type="text" id="linesFinancialGrandTotal" value="0.000" autocomplete="off">
                            </div>
                        </fieldset>

                        <fieldset class="form-section">
                            <legend>Quote Status</legend>
                            <div class="form-grid-2-col-sidebar">
                                <label for="linesQuoteOverallStatus">Quote Status:</label>
                                <input type="text" id="linesQuoteOverallStatus" autocomplete="off">

                                <label for="linesQuoteLastConfirmation">Last Confirmation:</label>
                                <input type="text" id="linesQuoteLastConfirmation" autocomplete="off">

                                <label for="linesQuoteLastConfirmed">Last Confirmed:</label>
                                <input type="text" id="linesQuoteLastConfirmed" autocomplete="off">
                            </div>
                        </fieldset>
                    </div>
                                <div class="modal-footer">
               <button type="button" id="closeLinesTabBtn" class="btn btn-secondary">Close</button>
                <div class="footer-spacer"></div> <!-- Spacer to push save buttons to right -->

                    <button type="button" id="saveLinesTabBtn" class="btn btn-primary">Save Lines</button>
                    <button type="button" id="saveAndCloseLinesTabBtn" class="btn btn-success">Save Lines & Close</button>
            </div>
                </div>
                <!-- END: Updated Quote Lines Tab Content -->

                <div id="confirmationTab" class="tab-content">
                    <p>Confirmation Content</p>
                </div>
                <div id="additionalInfoTab" class="tab-content">
                    <p>Additional Info Content</p>
                </div>
                <div id="statusTab" class="tab-content">
                    <p>Status & Market Content</p>
                </div>
                <div id="approvalTab" class="tab-content">
                    <p>Approval Activities Content</p>
                </div>
                <div id="contactsTab" class="tab-content">
                    <p>Published Contacts Content</p>
                </div>
            </div>


        </div>

    </div>

    <!-- NEW: Price List Modal Structure -->
    <div id="priceListModal" class="modal" style="display: none;">
        <div class="modal-content large-modal price-list-modal-grid">
            <span class="close" onclick="closePriceListModal()">&times;</span>
            <div class="modal-header-title">
                <h2>Add Multiple Items from Price List</h2>
            </div>
            <div class="modal-body-content price-list-body">
                <!-- Top Toolbar -->
                <div class="price-list-top-toolbar">
                    <input type="text" id="priceListSearchInput" placeholder="Enter text to search..." autocomplete="off">
                    <button class="btn btn-primary btn-search-price-list" onclick="filterPriceList()"><i class="fas fa-search"></i> Find</button>
                    <!-- NEW: Placeholder for Reset Button -->
                    <div id="priceListResetButtonContainer" style="display: none;">
                        <button class="btn btn-secondary btn-reset-search" onclick="resetPriceListFilters()"><i class="fas fa-undo"></i> Reset Search</button>
                    </div>
                </div>
                <div class="table-container price-list-table-container">
                    <table id="priceListTable" class="display" style="width:100%">
                        <thead>
                            <!-- DataTables will generate headers here based on the 'columns' configuration in JS -->
                        </thead>
                        <tbody>
                            <!-- Price list items will be populated here by DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Bottom Toolbar (Footer) - Reordered and adjusted for image match -->
            <div class="modal-footer price-list-footer">
                <div class="btn-group-left">
                    <button type="button" class="btn btn-secondary btn-reset-price-list" onclick="resetPriceListFilters()"><i class="fas fa-arrows-rotate"></i> Reset/Refresh</button>
                </div>
                <div class="btn-group-center">
                    <button type="button" class="btn btn-info btn-set-price-only" onclick="setPriceOnlyForSelected()"><i class="fas fa-money-bill-alt"></i> Set Selected as Price Only</button>
                </div>
                <div class="btn-group-right">
                    <button type="button" class="btn btn-cancel btn-close-price-list" onclick="closePriceListModal()"><i class="fas fa-times-circle"></i> Close</button>
                    <button type="button" class="btn btn-primary btn-insert-no-groups" onclick="addSelectedItemsToQuoteLines()"><i class="fas fa-plus-circle"></i> Insert without Groups</button>
                    <button type="button" class="btn btn-primary btn-insert-with-groups" onclick="addSelectedItemsToQuoteLines(true)"><i class="fas fa-object-group"></i> Insert with Groups</button>
                </div>
            </div>
        </div>
    </div>
<div id="editQuoteLineModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close-button" onclick="closeEditQuoteLineModal()">&times;</span>
        <h2>تعديل سطر عرض الأسعار</h2>
        <form id="editQuoteLineForm">
            <div class="form-group">
                <label for="editLineDescription">الوصف:</label>
                <input type="text" id="editLineDescription" class="form-control">
            </div>
            <div class="form-group">
                <label for="editLineQuantity">الكمية:</label>
                <input type="number" id="editLineQuantity" class="form-control">
            </div>
            <button type="button" id="saveEditedLineBtn" class="btn btn-primary">حفظ التغييرات</button>
            <button type="button" onclick="closeEditQuoteLineModal()" class="btn btn-secondary">إلغاء</button>
        </form>
    </div>
</div>
<div id="addPriceModal" class="modal" style="display: none;">
    <div class="modal-content small-modal">
        <span class="close" id="closeAddPriceModalBtn">&times;</span>
        <div class="modal-header-title">
            <h2>Add New Price Item</h2>
        </div>
        <div class="modal-body-content">
            <form id="addPriceForm">
                <label for="priceServiceId">Service ID:</label>
                <input type="text" id="priceServiceId" name="service_id" required placeholder="مثال: LIMS-001" autocomplete="off">

                <label for="priceName">Name:</label>
                <input type="text" id="priceName" name="name" required autocomplete="off">

                <label for="priceMethod">Method:</label>
                <select id="priceMethod" name="method" required>
                    <option value="list">List</option>
                    <option value="fixed">Fixed</option>
                    <option value="calculated">Calculated</option>
                </select>

                <label for="priceUnit">Unit:</label>
                <input type="text" id="priceUnit" name="unit" autocomplete="off">

                <label for="priceAmount">Price:</label>
                <input type="number" id="priceAmount" name="price" step="0.01" required>

                <label><input type="checkbox" id="priceOnly" name="priceOnly"> Price Only</label>
                <label><input type="checkbox" id="priceActive" name="active" checked> Active</label>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-cancel" id="cancelAddPriceBtn">Cancel</button>
            <button type="button" class="btn btn-primary" id="savePriceBtn">Save</button>
        </div>
    </div>
</div>

<div id="custom-toast-container">
    <div id="custom-toast">
        <div class="custom-toast-header">
            <span id="customToastTitle"></span>
            <button class="custom-toast-close">&times;</button>
        </div>
        <div class="custom-toast-body" id="customToastBody"></div>
    </div>
</div>
<script>
    window.routes = {
        quotationsDelete: "{{ route('quotations.delete') }}"
    };
</script>
<!-- jQuery (لازم يجي أول) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- Buttons Plugin -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- JSZip (مطلوب لـ Excel) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- pdfmake (مطلوب لـ PDF) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<!-- ملفات المشروع -->
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/quotation.js') }}"></script>
<script src="{{ asset('js/customers.js') }}"></script>




@endsection
