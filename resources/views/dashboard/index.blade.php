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
      <div class="general-card" >
        <i class="fas fa-file-signature"></i>
        <span data-key="Quotations">Quotations</span>
      </div>
      <div class="general-card"><i class="fas fa-file-invoice-dollar"></i><span>Invoices</span></div>
      <div class="general-card"><i class="fas fa-truck"></i><span>Deliveries</span></div>
      <div class="general-card"><i class="fas fa-envelope-open-text"></i><span>Mail Campaigns</span></div>
      <div class="general-card"><i class="fas fa-circle-question"></i><span>Inquiries</span></div>
      <div class="general-card"><i class="fas fa-check-double"></i><span>Confirmations</span></div>
    </div>
  </section>

  {{-- Setup --}}
  <section id="setup" class="section-content active">
    <h2>
      <i class="fas fa-sliders-h"></i>
      <span>Setup</span>
    </h2>
    <div class="general-grid">
      <div class="general-card"><i class="fas fa-tags"></i><span>Customer Types</span></div>
      <div class="general-card"><i class="fas fa-coins"></i><span>Price Lists</span></div>
      <div class="general-card"><i class="fas fa-hand-holding-usd"></i><span>Payment Terms</span></div>
      <div class="general-card"><i class="fas fa-file-alt"></i><span>Field Settings</span></div>
      <div class="general-card"><i class="fas fa-industry"></i><span>Project Categories</span></div>
      <div class="general-card"><i class="fas fa-sitemap"></i><span>Business Units</span></div>
    </div>
  </section>
@endsection
