<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', config('app.name', 'Vinayuuki'))</title>
    <meta name="description" content="@yield('meta_description', 'Giải pháp marketing và thiết kế website chuyên nghiệp')">
    <meta name="keywords" content="@yield('meta_keywords', 'marketing, thiết kế web, vinayuuki')">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('meta_title', config('app.name', 'Vinayuuki'))">
    <meta property="og:description" content="@yield('meta_description', 'Giải pháp marketing và thiết kế website chuyên nghiệp')">
    <meta property="og:image" content="@yield('meta_image', asset('img/og-image.jpg'))">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Montserrat:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --font-main: 'Inter', sans-serif;
            --font-heading: 'Montserrat', sans-serif;
        }

        body {
            font-family: var(--font-main);
            color: #2D3748;
            line-height: 1.6;
            overflow-x: hidden;
            background-color: #F5F5F5;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: var(--font-heading);
            font-weight: 700;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.6rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
        }

        /* Block Spacer Helper */
        .block-spacer {
            width: 100%;
        }

        /* Block Divider Helper */
        .block-divider {
            width: 100%;
            border-top: 2px solid #e2e8f0;
            margin: 2rem 0;
        }

        /* Animation */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Ensure box-shadow is never clipped by section wrapper */
        section.block-section {
            overflow: visible !important;
        }
    </style>
    @yield('styles')
</head>

<body>

    <main id="app">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Scroll reveal animation
        const reveal = () => {
            const reveals = document.querySelectorAll(".reveal");
            for (let i = 0; i < reveals.length; i++) {
                const windowHeight = window.innerHeight;
                const elementTop = reveals[i].getBoundingClientRect().top;
                const elementVisible = 150;
                if (elementTop < windowHeight - elementVisible) {
                    reveals[i].classList.add("active");
                }
            }
        };

        window.addEventListener("scroll", reveal);
        // Fire once on load
        reveal();
    </script>
    @yield('scripts')
</body>

</html>
