@extends('layouts.app')

@section('title', 'LIMS Control Panel')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- {{-- جرس التنبيهات --}}
<div style="position: fixed; top: 20px; right: 20px; z-index: 999;">
    <button id="reminderBell" class="btn btn-warning position-relative">
        <i class="fas fa-bell fa-2x"></i>
        <span id="reminderCount" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
    </button>

    <div id="reminderDropdown" class="card" style="display:none; position:absolute; top:50px; right:0; width:300px; max-height:400px; overflow-y:auto;">
        <div class="card-body">
            <h6>التذكيرات القادمة</h6>
            <ul id="reminderList" class="list-unstyled mb-0"></ul>
        </div>
    </div>
</div> -->



  {{-- Transactions --}}
  <section id="general" class="section-content active">
    <h2>
      <i class="fas fa-file-contract"></i>
      <span data-key="Transactions">Transactions</span>
    </h2>
    <div class="general-grid">

      <div class="general-card" onclick="window.location.href='{{ route('customers.index') }}'">
        <i class="fas fa-hard-hat"></i>
        <span data-key="Customers">Customers</span>
      </div>

      <div class="general-card" onclick="window.location.href='{{ route('projects.index') }}'">
        <i class="fas fa-diagram-project"></i>
        <span data-key="Projects">Projects</span>
      </div>

      <div class="general-card" onclick="window.location.href='{{ route('quotation.index') }}'">
        <i class="fas fa-file-signature"></i>
        <span data-key="Quotations">Quotations</span>
      </div>

      <div class="general-card" onclick="window.location.href='{{ route('confirmation.index') }}'">
        <i class="fas fa-check-double"></i>
        <span data-key="Confirmations">Confirmations</span>
      </div>

      <div class="general-card" onclick="window.location.href=''">
        <i class="fas fa-exchange-alt"></i>
        <span data-key="Financial Transactions">Financial Transactions</span>
      </div>

      <div class="general-card" onclick="window.location.href='{{ route('deliveries.index') }}'">
        <i class="fas fa-truck"></i>
        <span data-key="Deliveries">Deliveries</span>
      </div>

      <div class="general-card" onclick="window.location.href='{{ route('report-approval.index') }}'">
        <i class="fas fa-file-signature"></i>
        <span data-key="Report Approval Activities">Report Approval Activities</span>
      </div>

      <div class="general-card" onclick="window.location.href=''">
        <i class="fas fa-file-invoice-dollar"></i>
        <span data-key="Quotation Approval Activities">Quotation Approval Activities</span>
      </div>
    </div>
</section>

<!----------------------------------------------------Setup------------------------------------------->
<section id="setup" class="section-content active">
    <h2>
      <i class="fas fa-sliders-h"></i>
      <span data-key="Setup">Setup</span>
    </h2>
    <div class="general-grid">
      <div class="general-card" onclick="window.location.href='{{ route('employees.index') }}'">
        <i class="fas fa-users"></i>
        <span data-key="Employees">Employees</span>
      </div>

      <!-- <div class="general-card" onclick="window.location.href=''">
        <i class="fas fa-building"></i>
        <span data-key="Department">Department</span>
      </div> -->

     <div class="general-card" onclick="window.location.href='{{ route('tests.index') }}'">
  <i class="fas fa-flask"></i>
  <span data-key="Test & Services Category">Test & Services Category</span>
</div>
      <div class="general-card"  onclick="window.location.href='{{ route('equipments.index') }}'">
        <i class="fas fa-tools"></i>
        <span data-key="Equipment">Equipment</span>
      </div>
    </div>
</section>


<script>

    function loadReminders() {
    $.get('/dashboard/reminders', function(html) {
        $('#reminder-container').html($(html).find('.dashboard-reminders').html());
    });
}

// تحديث كل 5 دقائق
setInterval(loadReminders, 300000);

document.addEventListener('DOMContentLoaded', function() {
    // بيانات Blade
    let calibrationReminders = @json($calibrationReminders);
    let maintenanceReminders = @json($maintenanceReminders);

    // ⚠️ رسائل الجرس العادية
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
    let reminderCount = reminders.length;
    document.getElementById('reminderCount').textContent = reminderCount;

    // ملء القائمة المنبثقة
    let reminderList = document.getElementById('reminderList');
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

    // ⚡ تذكيرات عاجلة باستخدام SweetAlert
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
</style>


@endsection
