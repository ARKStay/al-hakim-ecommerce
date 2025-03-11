<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.5/dist/flowbite.min.js"></script>
    {{-- SweetAlert2 untuk notifikasi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Trix Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <link rel="icon" href="{{ asset('storage/banks/favicon123.ico') }}" type="image/x-icon">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }
    </style>
    <title>Al Hakim Store</title>
</head>

<body>
    <div class="antialiased bg-gray-50">
        <x-dashboard.navbar></x-dashboard.navbar>

        <!-- Sidebar -->
        <x-dashboard.sidebar></x-dashboard.sidebar>

        <x-dashboard.header>{{ $title }}</x-dashboard.header>

        <main class="p-4 md:ml-64 h-auto bg-gray-100">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
