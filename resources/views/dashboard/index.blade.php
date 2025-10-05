@extends('layouts.app')

@section('title', 'LIMS Control Panel')

@section('content')
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

      <div class="general-card" onclick="window.location.href=''">
        <i class="fas fa-check-double"></i>
        <span data-key="Confirmations">Confirmations</span>
      </div>

      <div class="general-card" onclick="window.location.href=''">
        <i class="fas fa-exchange-alt"></i>
        <span data-key="Financial Transactions">Financial Transactions</span>
      </div>

      <div class="general-card" onclick="window.location.href=''">
        <i class="fas fa-truck"></i>
        <span data-key="Deliveries">Deliveries</span>
      </div>

      <div class="general-card" onclick="window.location.href=''">
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

      <div class="general-card" onclick="window.location.href=''">
        <i class="fas fa-building"></i>
        <span data-key="Department">Department</span>
      </div>

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




@endsection
