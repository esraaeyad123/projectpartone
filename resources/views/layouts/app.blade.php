<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LIMS Control Panel')</title>

    {{-- أيقونة الموقع --}}
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

    {{-- مكتبات خارجية --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    {{-- ملفات المشروع --}}
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customers.css') }}">

    {{-- Font Awesome محلي --}}
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
</head>
<body>

   @include('layouts.header')
   @include('layouts.navbar')
    <main>

          @yield('content')
    </main>

    {{-- مكتبات JS --}}
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    {{-- Font Awesome JS محلي --}}
    <script src="{{ asset('fontawesome/js/all.min.js') }}"></script>

    {{--     <script src="{{ asset('js/customers.js') }}"></script>
--}}

        {{-- ملف JS الخاص بك --}}


    <script src="{{ asset('js/main.js') }}"></script>

</body>
</html>
