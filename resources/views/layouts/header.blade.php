<header class="main-header">

    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
        <button id="darkModeToggle" onclick="toggleDarkMode()">
            <i class="fas fa-circle-half-stroke"></i>
        </button>

        <div>
           <div style="position: relative; display: inline-block;">
    <button id="reminderBell" style="background-color: #ffe0c2; color: #f6902d; border: none; padding: 10px 15px; border-radius: 5px; cursor: pointer; font-size: 16px;">
        <i class="fas fa-bell"></i> <span id="reminderCount">0</span>
    </button>

    <ul id="reminderDropdown" style="display: none; position: absolute; top: 40px; right: 0; background: white; border: 1px solid #ccc; border-radius: 5px; width: 300px; max-height: 400px; overflow-y: auto; padding: 10px; z-index: 1000;">
        <div id="reminderList"></div>
    </ul>
</div>

            <button onclick="setLanguage('en')">
                <i class="fas fa-language"></i> English
            </button>
            <button onclick="setLanguage('ar')">
                <i class="fas fa-globe"></i> العربية
            </button>
        </div>
    </div>
    <h1>
        <i class="fas fa-microscope"></i>
        <span data-key="LIMS Control Panel">LIMS Control Panel</span>
    </h1>
</header>



<script>

    function loadReminders() {
    $.get('/dashboard/reminders', function(html) {
        $('#reminder-container').html($(html).find('.dashboard-reminders').html());
    });
}

// تحديث كل 5 دقائق
setInterval(loadReminders, 300000);
document.addEventListener('DOMContentLoaded', function() {
    let calibrationReminders = @json($calibrationReminders);
    let maintenanceReminders = @json($maintenanceReminders);

    let reminders = [];

    calibrationReminders.forEach(e => {
        if (e.calibration && e.calibration.next_calib_date) {
            reminders.push(`🔧 Calib: ${e.equipment_reference} - ${e.calibration.next_calib_date}`);
        }
    });

    maintenanceReminders.forEach(e => {
        if (e.maintenance && e.maintenance.next_maint_date) {
            reminders.push(`🛠 Maint: ${e.equipment_reference} - ${e.maintenance.next_maint_date}`);
        }
    });

    // تحديث العداد
    document.getElementById('reminderCount').textContent = reminders.length;

    // ملء القائمة المنبثقة
    let reminderList = document.getElementById('reminderList');
    reminderList.innerHTML = '';
    reminders.forEach(msg => {
        let li = document.createElement('li');
        li.textContent = msg;
        li.style.padding = '5px 0';
        reminderList.appendChild(li);
    });

    // فتح وغلق القائمة عند الضغط على الجرس
    let bell = document.getElementById('reminderBell');
    let dropdown = document.getElementById('reminderDropdown');

    bell.addEventListener('click', function() {
        dropdown.style.display = (dropdown.style.display === 'none') ? 'block' : 'none';
    });

    document.addEventListener('click', function(e) {
        if (!bell.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });

    // تنبيهات عاجلة باستخدام SweetAlert
    let urgentCalib = @json($calibrationReminders->filter(fn($eq) => $eq->calib_urgent)->pluck('equipment_reference'));
    let urgentMaint = @json($maintenanceReminders->filter(fn($eq) => $eq->maint_urgent)->pluck('equipment_reference'));

    let urgentMessages = [];
    if (urgentCalib.length > 0) urgentMessages.push(`⚠️ Calibration urgent for: ${urgentCalib.join(', ')}`);
    if (urgentMaint.length > 0) urgentMessages.push(`⚠️ Maintenance urgent for: ${urgentMaint.join(', ')}`);

    if (urgentMessages.length > 0) {
        Swal.fire({
            title: '🔔 Urgent Reminders',
            html: urgentMessages.join('<br>'),
            icon: 'warning',
            confirmButtonText: 'Got it'
        });
    }
});

</script>

<style>
.dashboard-reminders { display: flex; flex-direction: column; gap: 20px; }
.reminder-cards { display: flex; flex-wrap: wrap; gap: 10px; }
.reminder-card {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 10px 15px;
    width: 200px;
    background-color: #f0f0f0;
    transition: 0.2s;
}
.reminder-card.urgent {
    background-color: #ffdddd;
    border-color: #ff4d4d;
}
.reminder-card:hover {
    transform: scale(1.05);
}

#reminderDropdown li {
    border-bottom: 1px solid #eee;
    padding: 5px;
    font-size: 14px;
}
#reminderDropdown li:last-child {
    border-bottom: none;
}

</style>
