// =================== إظهار الأقسام (معدلة لسيناريو الصفحات المتعددة) ===================
// هذه الدالة الآن مسؤولة عن التأكد من أن الأقسام 'general' و 'setup' تظهر دائماً في صفحة index (2).html
// إذا كان لديك أقسام أخرى تظهر وتختفي *داخل* نفس الصفحة (بخلاف الانتقال لصفحة أخرى)،
// فيمكنك تعديل هذه الدالة للتعامل معها.
function showSection(sectionId) {
    // هذا الجزء قد لا يكون ضرورياً جداً الآن إذا كانت معظم التنقلات هي لصفحات منفصلة،
    // ولكنه مفيد إذا كان هناك أقسام داخل نفس الصفحة لا تزال تتحكم بها هذه الدالة.

    // إخفاء كل الأقسام ذات الفئة 'section-content'
    document.querySelectorAll('.section-content').forEach(section => {
        section.style.display = 'none';
        section.classList.remove('active');
    });

    // إظهار القسم المطلوب وإضافة فئة active
    const sectionToShow = document.getElementById(sectionId);
    if (sectionToShow) {
        sectionToShow.style.display = 'block';
        sectionToShow.classList.add('active');
    }

    // بما أن 'general' و 'setup' موجودان بشكل دائم ومرئي في index (2).html،
    // فإن هذه الدالة لن تكون مسؤولة عن إخفائهما أو إظهارهما عند الانتقال للصفحات الأخرى.
    // بل هي فقط تضمن أن القسم الذي يتم النقر عليه يصبح مرئياً إذا كان في نفس الصفحة.
    // في هذا السيناريو الجديد، النقر على زر في Navbar سينقلك مباشرة إلى صفحة أخرى HTML.
}


// استدعاء الدالة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', loadSharedContent);


// =================== لتحميل الأجزاء المشتركة للصفحات ===================
async function loadSharedContent() {
    try {
        // تحميل الرأس
        const headerResponse = await fetch('header.html');
        const headerHtml = await headerResponse.text();
        const headerContainer = document.createElement('div');
        headerContainer.innerHTML = headerHtml;
        document.body.prepend(headerContainer);

        // تحميل قائمة التنقل
        const navbarResponse = await fetch('navbar.html');
        const navbarHtml = await navbarResponse.text();
        const navbarContainer = document.createElement('div');
        navbarContainer.innerHTML = navbarHtml;
        document.querySelector('.main-header').insertAdjacentElement('afterend', navbarContainer.querySelector('.main-menu'));

        // بعد تحميل المحتوى، طبق اللغة والوضع الليلي إذا كانت مفعّلة
        const savedLang = localStorage.getItem('language');
        if (savedLang) {
            setLanguage(savedLang);
        }
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }

        // هنا نقوم باستدعاء updateCurrentPageTab بعد تحميل كل المحتوى المشترك
        // للتأكد من أن كل عناصر الـ DOM اللازمة متوفرة.
        updateCurrentPageTab();

    } catch (error) {
        console.error('Failed to load shared content:', error);
    }
}


// =================== الوضع الليلي ===================
function toggleDarkMode() {
    document.body.classList.toggle("dark-mode");
    if (document.body.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
    } else {
        localStorage.setItem("darkMode", "disabled");
    }
}

// =================== تغيير اللغة ===================
const translations = {
    en: {
        "LIMS Control Panel": "LIMS Control Panel",
        "Transactions": "Transactions",
        "Setup": "Setup",
        "Customers": "Customers",
        "Projects": "Projects",
        "Quotations": "Quotations",
        "Invoices": "Invoices",
        "Deliveries": "Deliveries",
        "Mail Campaigns": "Mail Campaigns",
        "Inquiries": "Inquiries",
        "Confirmations": "Confirmations",
        "Customer Types": "Customer Types",
        "Price Lists": "Price Lists",
        "Payment Terms": "Payment Terms",
        "Field Settings": "Field Settings",
        "Project Categories": "Project Categories",
        "Business Units": "Business Units",
        "General": "General",
        "Testing": "Testing",
        "Geotechnical": "Geotechnical",
        "Calibration": "Calibration",
        "Fleet": "Fleet",
        "Dashboards": "Dashboards",
        "Security": "Security",
        "Options": "Options"
    },
    ar: {
        "LIMS Control Panel": "لوحة تحكم نظام إدارة المختبرات",
        "Transactions": "المعاملات",
        "Setup": "الإعدادات",
        "Customers": "العملاء",
        "Projects": "المشاريع",
        "Quotations": "عروض الأسعار",
        "Invoices": "الفواتير",
        "Deliveries": "عمليات التسليم",
        "Mail Campaigns": "حملات البريد",
        "Inquiries": "الاستفسارات",
        "Confirmations": "التأكيدات",
        "Customer Types": "أنواع العملاء",
        "Price Lists": "قوائم الأسعار",
        "Payment Terms": "شروط الدفع",
        "Field Settings": "إعدادات الحقول",
        "Project Categories": "فئات المشاريع",
        "Business Units": "الوحدات التجارية",
        "General": "عام",
        "Testing": " الاختبار",
        "Geotechnical": "الجيوتقنية",
        "Calibration": "المعايرة",
        "Fleet": "الأسطول",
        "Dashboards": "لوحات البيانات",
        "Security": "الأمان",
        "Options": "الخيارات"
    }
};

function getTranslatedText(key) {
    const currentLang = localStorage.getItem('language') || 'en'; // افتراضي 'en'
    return translations[currentLang] && translations[currentLang][key] ? translations[currentLang][key] : key;
}


function setLanguage(lang) {
    document.body.setAttribute("dir", lang === "ar" ? "rtl" : "ltr");
    document.documentElement.lang = lang;

    // تحديث نصوص الترجمة
    document.querySelectorAll("[data-key]").forEach((element) => {
        const key = element.getAttribute("data-key");
        if (translations[lang] && translations[lang][key]) {
            element.textContent = translations[lang][key];
        }
    });

    // تحديث اتجاه الجداول
    document.querySelectorAll('table:not([data-ignore-lang])').forEach(table => {
        table.style.direction = lang === 'ar' ? 'rtl' : 'ltr';
    });

    // حفظ اللغة المختارة في التخزين المحلي
    localStorage.setItem("language", lang);

    // بعد تغيير اللغة، قم بتحديث مسار التنقل ليعكس اللغة الجديدة
    updateCurrentPageTab();
}

// =================== دوال النوافذ المنبثقة المخصصة (Alert/Confirm) ===================

let _onConfirmCallback = null; // للاحتفاظ بدالة الـ callback لزر التأكيد (Yes)
let _onCancelCallback = null; // للاحتفاظ بدالة الـ callback لزر الإلغاء (No/X)


/**
 * يفتح نافذة مودال مخصصة ويعرض رسالة مع أزرار قابلة للتخصيص.
 * @param {string} title - عنوان النافذة المنبثقة.
 * @param {string} message - الرسالة التي ستعرض داخل النافذة المنبثقة.
 * @param {string} buttonsHtml - HTML للأزرار التي ستظهر في النافذة المنبثقة (مثال: '<button>OK</button>').
 */
function openCustomDialog(title, message, buttonsHtml) {
    const modal = document.getElementById("customDialogModal");
    const modalTitle = document.getElementById("customDialogTitle");
    const modalMessage = document.getElementById("customDialogMessage");
    const modalButtons = document.getElementById("customDialogButtons");

    if (modal && modalTitle && modalMessage && modalButtons) {
        modalTitle.textContent = title;
        modalMessage.textContent = message;
        modalButtons.innerHTML = buttonsHtml;
        modal.style.display = "flex"; // <--- هذا هو التغيير الأساسي لمحاذاة المنتصف
    }
}

/**
 * يغلق نافذة المودال المخصصة.
 */
function closeCustomDialog() {
    const modal = document.getElementById("customDialogModal");
    if (modal) {
        modal.style.display = "none";
    }
    // مسح الـ callbacks عند الإغلاق لضمان عدم استدعائها بالخطأ
    _onConfirmCallback = null;
    _onCancelCallback = null;
}


/**
 * يعرض نافذة مودال تنبيه مخصصة (Alert).
 * @param {string} title - عنوان التنبيه.
 * @param {string} message - رسالة التنبيه.
 */
function showAlert(title, message) {
    const buttons = `
        <button type="button" class="form-button" onclick="closeCustomDialog()">OK</button>
    `;
    openCustomDialog(title, message, buttons);
}

/**
 * يعرض نافذة مودال تأكيد مخصصة (Confirm).
 * @param {string} title - عنوان التأكيد.
 * @param {string} message - رسالة التأكيد.
 * @param {function} onConfirmCallback - الدالة التي يتم استدعاؤها عند تأكيد المستخدم (النقر على Yes).
 * @param {function} [onCancelCallback=null] - الدالة التي يتم استدعاؤها عند إلغاء المستخدم (النقر على No/X).
 */
function showConfirm(title, message, onConfirmCallback, onCancelCallback = null) {
    _onConfirmCallback = onConfirmCallback; // تخزين الدالة للاستدعاء لاحقاً
    _onCancelCallback = onCancelCallback; // تخزين دالة الإلغاء هنا

    const buttons = `
        <button type="button" class="form-button" onclick="handleCustomConfirm(true)">Yes</button>
        <button type="button" class="form-button cancel" onclick="handleCustomConfirm(false)">No</button>
    `;
    openCustomDialog(title, message, buttons);
}

/**
 * دالة مساعدة لمعالجة رد المستخدم على نافذة التأكيد.
 * @param {boolean} confirmed - صحيح إذا أكد المستخدم، خطأ إذا ألغى.
 */
function handleCustomConfirm(confirmed) {
    closeCustomDialog(); // هذه الدالة الآن تمسح _onConfirmCallback و _onCancelCallback
    if (confirmed && typeof _onConfirmCallback === 'function') {
        _onConfirmCallback();
    } else if (!confirmed && typeof _onCancelCallback === 'function') {
        _onCancelCallback();
    }
    // لا داعي لمسح الـ callbacks هنا مرة أخرى، لأن closeCustomDialog تفعل ذلك.
}

// =================== دوال إدارة تبويبات الصفحات ===================

/**
 * يقوم بتحديث نص علامة التبويب للصفحة الحالية.
 * يجب استدعاء هذه الدالة عند تحميل الصفحة وأيضاً عند تغيير اللغة.
 */
function updateCurrentPageTab() {
    const currentPageTab = document.getElementById('currentPageTab');
    if (!currentPageTab) {
        console.warn("Current page tab element not found. Skipping tab update.");
        return;
    }

    const tabTitleSpan = currentPageTab.querySelector('.tab-title');
    if (!tabTitleSpan) {
        console.warn("Tab title span not found inside current page tab.");
        return;
    }

    // بناءً على عنوان الصفحة (من <title> أو من URL) نحدد المفتاح للترجمة
    const pageTitle = document.title;
    let translationKey = '';

    if (pageTitle.includes('Customers Management')) {
        translationKey = 'Customers';
    } else if (pageTitle.includes('LIMS Control Panel')) {
        translationKey = 'General'; // لصفحة index.html
    }
    // أضف المزيد من الشروط هنا لصفحاتك الأخرى
    // else if (pageTitle.includes('Projects Management')) {
    //     translationKey = 'Projects';
    // }
    // ...

    tabTitleSpan.textContent = getTranslatedText(translationKey);
    // يمكن أيضاً تحديث data-key إذا كنت تريد استخدامها لغرض آخر
    tabTitleSpan.setAttribute('data-key', translationKey);
}

/**
 * تغلق الصفحة الحالية بالعودة إلى الصفحة السابقة في سجل المتصفح.
 * إذا لم يكن هناك سجل سابق، تعود لصفحة index.html.
 */
function closeCurrentPageTab() {
    if (window.history.length > 1) {
        window.history.back();
    } else {
        // إذا لم يكن هناك سجل سابق (مثال: تم فتح الصفحة في علامة تبويب جديدة)،
        // يمكننا التوجيه لصفحة معينة، مثلاً الرئيسية
        window.location.href = 'index.html';
    }
}

// =======================================================================
// استدعاء دالة updateCurrentPageTab عند تحميل الصفحة
// هذا الجزء يتم تشغيله بواسطة loadSharedContent() الآن
// لا داعي لـ $(document).ready() إضافي هنا، loadSharedContent() كافية.
