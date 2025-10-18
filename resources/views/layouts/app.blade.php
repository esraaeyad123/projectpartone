<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LIMS Control Panel')</title>

    {{-- أيقونة الموقع --}}
    <link rel="icon" href="{{ asset('FullLogo.png') }}" >

    {{-- مكتبات خارجية عبر HTTPS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    {{-- ملفات المشروع --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customers.css') }}">
    <link rel="stylesheet" href="{{ asset('css/projects.css') }}">
    <link rel="stylesheet" href="{{ asset('css/project-files.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-files.css') }}">
    <link rel="stylesheet" href="{{ asset('css/report-details.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/testing.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/registration.css') }}" />







</head>
<body>

   @include('layouts.header')
   @include('layouts.navbar')

   <main>
       @yield('content')
   </main>

   {{-- مكتبات JS --}}
   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   {{-- DataTables --}}
   <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
   <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

   {{-- Font Awesome --}}
   <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

   {{-- ملفات JS للمشروع --}}
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="{{ asset('js/report-details.js') }}" type="text/javascript"></script>
     <script src="{{ asset('js/customer_types.js') }}"></script>
     <script src="{{ asset('js/main.js') }}"></script>
      <script src="{{ asset('js/testing.js') }}"></script>
    <script src="{{ asset('js/registration.js') }}"></script>





</body>
</html>

</html>
