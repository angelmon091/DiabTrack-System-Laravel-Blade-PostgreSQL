<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <title>{{ config('app.name', 'DiabTrack') }} - Control de Diabetes Inteligente</title>
    <meta name="description" content="DiabTrack es la plataforma inteligente para el seguimiento de la diabetes. Controla tus niveles de glucosa, nutrición y actividad física de forma sencilla.">
    <meta name="keywords" content="diabetes, control de glucosa, seguimiento de salud, diabtrack, salud digital">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="DiabTrack - Tu Compañero en el Control de la Diabetes">
    <meta property="og:description" content="Gestiona tu salud de manera efectiva con DiabTrack. Registra signos vitales, alimentación y síntomas en un solo lugar.">
    <meta property="og:image" content="{{ asset('og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ config('app.url') }}">
    <meta property="twitter:title" content="DiabTrack - Control de Diabetes">
    <meta property="twitter:description" content="La herramienta definitiva para pacientes con diabetes.">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite('resources/css/auth-global.css')
    @stack('styles')
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{ $slot }}

    <footer class="site-footer">
        <div class="footer-content">
            <div class="links">
                <a href="#">{{ __('Políticas de Privacidad') }}</a>
                <a href="#">{{ __('Términos y Condiciones') }}</a>
                <a href="#">{{ __('Desarrolladores') }}</a>
            </div>
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-reddit-alien"></i></a>
            </div>
        </div>
    </footer>
</body>
</html>
