// Function to open the first modal (registration category)
function openRegistrationCategoryModal() {
    document.getElementById('registration-category-modal').style.display = 'flex';
}

// Function to close any modal by its ID
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Function to handle the category selection and show the main form and tab
function checkCategorySelection(category) {
    if (category === 'crushing-testing') {
        // إخفاء النافذة الأولى
        closeModal('registration-category-modal');
        // إظهار المسار (التبويب)
        document.getElementById('registration-tab-header').style.display = 'block';
        // إظهار النافذة الرئيسية للتسجيل
        document.getElementById('registration-form-modal').style.display = 'flex';
    } else {
        // ... منطق الفئات الأخرى ...
    }
}

// Function to close both the main modal and the tab simultaneously
function closeRegistrationTab() {
    closeModal('registration-form-modal');
    document.getElementById('registration-tab-header').style.display = 'none';
}
