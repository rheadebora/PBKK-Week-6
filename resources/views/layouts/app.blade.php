<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>window.routeTheme = @json(request('mode'));</script>
    <script src="{{ asset('js/theme.js') }}?v={{ filemtime(public_path('js/theme.js')) }}"></script>
    <meta name="theme-color" content="#1b4a7a">
    <meta name="description" content="Personal website Rhea Debora Sianturi. Profil mahasiswa Informatika ITS, eksperimen visual, proyek, dan catatan belajar.">
    <title>@yield('title', 'Home') — {{ $profile['short_name'] }}</title>
    <link rel="icon" href="{{ asset('images/favicon.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css'])
    <script src="{{ asset('js/portfolio.js') }}?v={{ filemtime(public_path('js/portfolio.js')) }}" defer></script>
    <script src="{{ asset('js/motion.js') }}" defer></script>
    <script src="{{ asset('js/dream-sky.js') }}?v={{ filemtime(public_path('js/dream-sky.js')) }}" defer></script>
    <script src="{{ asset('js/section-selector.js') }}?v={{ filemtime(public_path('js/section-selector.js')) }}" defer></script>
    <script src="{{ asset('js/page-navigation.js') }}?v={{ filemtime(public_path('js/page-navigation.js')) }}" defer></script>
</head>
<body @class([
    request()->routeIs('home') ? 'is-home' : 'is-inner',
    '!bg-[#211c19]' => request()->routeIs('agent.idea') && request()->query('mode') === 'dark',
])>
<a class="skip-link" href="#main-content">Lewati ke konten</a>
<div class="reading-progress" aria-hidden="true"></div>
<div class="pointer-trail" aria-hidden="true"></div>
<canvas class="dream-sky" data-dream-sky aria-hidden="true"></canvas>
<div class="site-frame">
    <header class="site-header">
        <div class="brand-identity">
            <span class="brand" aria-hidden="true"><span class="brand-monogram">{{ $profile['brand'] }}</span></span>
            <span class="brand-copy">
                <strong>{{ $profile['name'] }}</strong>
                <span>{{ $profile['nrp'] }}</span>
            </span>
        </div>
        <span class="header-rule" aria-hidden="true"></span>
        <button class="theme-toggle" type="button" aria-label="Aktifkan mode gelap" aria-pressed="false"><span data-theme-icon aria-hidden="true">◐</span><span data-theme-label>Gelap</span></button>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-nav">Menu <span aria-hidden="true">☰</span></button>
        <nav class="main-nav" id="main-nav" aria-label="Navigasi utama">
            @foreach (['home' => 'Home', 'profile' => 'Profil', 'agent.idea' => 'Ide-Riset', 'about' => 'About', 'projects' => 'Projects', 'collection' => 'Collection', 'blog' => 'Blog', 'calculator' => 'Kalkulator'] as $route => $label)
                <a href="{{ route($route) }}" @if(request()->routeIs($route) || ($route === 'profile' && request()->routeIs('student', 'dashboard.student')) || ($route === 'agent.idea' && request()->routeIs('agent', 'dashboard.agent')) || ($route === 'projects' && request()->routeIs('project')) || ($route === 'blog' && request()->routeIs('article')) || ($route === 'calculator' && request()->routeIs('calculate'))) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            <a class="contact-link" href="{{ route('contact') }}" @if(request()->routeIs('contact')) aria-current="page" @endif>Contact <span aria-hidden="true">↗</span></a>
        </nav>
    </header>
    <main id="main-content" tabindex="-1">@yield('content')</main>
    <nav class="page-section-selector" aria-label="Pilih bagian halaman" data-section-selector hidden></nav>
    <footer class="site-footer">
        <span>© {{ date('Y') }} {{ $profile['name'] }}</span>
        <a class="footer-its" href="https://www.its.ac.id/informatika/" target="_blank" rel="noopener noreferrer">Departemen Teknik Informatika · Institut Teknologi Sepuluh Nopember <span aria-hidden="true">↗</span></a>
        <a href="{{ route('project') }}">Project idea <span aria-hidden="true">↗</span></a>
    </footer>
</div>
</body>
</html>
