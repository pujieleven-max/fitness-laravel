<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $appSetting->app_name ?? 'FitZone') - {{ $appSetting->app_name ?? 'FitZone Gym' }}</title>

    <!-- Theme Initialization Script (Prevents Flash of Unstyled Theme) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'light') {
                document.documentElement.classList.remove('dark');
            } else {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <!-- Google Fonts: Plus Jakarta Sans, Outfit (Display), JetBrains Mono (Tech/Currency) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN for instant styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Outfit"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        dark: {
                            950: '#060911',
                            900: '#0B0F19',
                            850: '#0e1422',
                            800: '#131b2e',
                            750: '#18233c',
                            700: '#1e293b',
                            600: '#334155',
                        },
                        cyber: {
                            emerald: '#10B981',
                            teal: '#14B8A6',
                            cyan: '#06B6D4',
                            amber: '#F59E0B',
                            orange: '#F97316',
                            rose: '#F43F5E',
                            violet: '#8B5CF6',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- jQuery (Required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Select2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-accent: {{ $appSetting->theme_config['primary'] ?? '#10B981' }};
            --primary-hover: {{ $appSetting->theme_config['primary_hover'] ?? '#059669' }};
            --primary-glow: {{ $appSetting->theme_config['glow'] ?? 'rgba(16, 185, 129, 0.4)' }};
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #060911;
            color: #f1f5f9;
        }

        .font-display {
            font-family: 'Outfit', sans-serif;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Ambient Glow & Mesh Background */
        .ambient-mesh {
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(6, 182, 212, 0.07) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(139, 92, 246, 0.05) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(245, 158, 11, 0.05) 0px, transparent 50%);
        }

        /* Dual Mode Glassmorphism Utilities */
        .glass-panel {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            color: #0f172a;
        }
        .dark .glass-panel {
            background: rgba(11, 15, 25, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.07);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            color: #f1f5f9;
        }

        .glass-panel-hover {
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-panel-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -10px rgba(0, 0, 0, 0.12);
        }
        .dark .glass-panel-hover:hover {
            border-color: rgba(255, 255, 255, 0.16);
            box-shadow: 0 12px 28px -10px rgba(0, 0, 0, 0.6);
        }

        /* Modern Gradient Buttons */
        .btn-theme-primary {
            background: linear-gradient(135deg, {{ $appSetting->theme_config['primary'] ?? '#10B981' }} 0%, {{ $appSetting->theme_config['primary_hover'] ?? '#059669' }} 100%);
            box-shadow: 0 4px 20px -3px var(--primary-glow);
            transition: all 0.25s ease;
        }
        .btn-theme-primary:hover {
            box-shadow: 0 8px 30px 0 var(--primary-glow);
            transform: translateY(-1px) scale(1.01);
        }

        /* ========================================================
           COMPREHENSIVE LIGHT MODE OVERRIDES
           Applies seamlessly to all pages & components when html is not .dark
           ======================================================== */
        html:not(.dark) body {
            background-color: #F8FAFC !important;
            color: #0F172A !important;
        }

        /* Ambient mesh subtle glow for light mode */
        html:not(.dark) .ambient-mesh {
            background-color: #F8FAFC !important;
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(6, 182, 212, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(139, 92, 246, 0.04) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(245, 158, 11, 0.04) 0px, transparent 50%) !important;
        }

        /* 1. All Dark Container & Background Wildcards (including fractional opacities) */
        html:not(.dark) [class*="bg-[#0B0F19]"],
        html:not(.dark) [class*="bg-[#0b0f19]"],
        html:not(.dark) [class*="bg-slate-950"] {
            background-color: #FFFFFF !important;
            border-color: #E2E8F0 !important;
        }

        html:not(.dark) [class*="bg-[#060911]"],
        html:not(.dark) [class*="bg-[#070a11]"],
        html:not(.dark) [class*="bg-[#0e1422]"],
        html:not(.dark) [class*="bg-[#131b2e]"] {
            background-color: #F8FAFC !important;
        }

        /* 2. Secondary Containers & Cards (slate-900 with all fractional opacities: /90, /80, /60, /50, /40, /30, etc.) */
        html:not(.dark) [class*="bg-slate-900"]:not(.btn-theme-primary):not([class*="bg-emerald"]):not([class*="bg-cyan"]):not([class*="bg-amber"]):not([class*="bg-rose"]):not([class*="bg-violet"]):not([class*="from-"]):not([class*="to-"]) {
            background-color: #F8FAFC !important;
            border-color: #E2E8F0 !important;
            color: #0F172A !important;
        }

        /* When inside cards/panels, items with bg-slate-900 get a clean contrasting tint */
        html:not(.dark) [class*="bg-[#0B0F19]"] [class*="bg-slate-900"]:not(.btn-theme-primary):not([class*="bg-emerald"]):not([class*="bg-cyan"]):not([class*="bg-amber"]):not([class*="bg-rose"]):not([class*="bg-violet"]):not([class*="from-"]),
        html:not(.dark) .glass-panel [class*="bg-slate-900"]:not(.btn-theme-primary):not([class*="bg-emerald"]):not([class*="bg-cyan"]):not([class*="bg-amber"]):not([class*="bg-rose"]):not([class*="bg-violet"]):not([class*="from-"]) {
            background-color: #F1F5F9 !important;
            border-color: #E2E8F0 !important;
        }

        /* 3. Controls, Secondary Buttons & Badges (slate-800 & slate-700 with all opacities) */
        html:not(.dark) [class*="bg-slate-800"]:not([class*="bg-emerald"]):not([class*="bg-cyan"]):not([class*="bg-amber"]):not([class*="bg-rose"]):not([class*="bg-violet"]):not(.btn-theme-primary):not([class*="from-"]):not([class*="to-"]),
        html:not(.dark) [class*="bg-slate-700"]:not([class*="bg-emerald"]):not([class*="bg-cyan"]):not([class*="bg-amber"]):not([class*="bg-rose"]):not([class*="bg-violet"]):not(.btn-theme-primary):not([class*="from-"]):not([class*="to-"]) {
            background-color: #F1F5F9 !important;
            border-color: #CBD5E1 !important;
            color: #1E293B !important;
        }

        html:not(.dark) button[class*="bg-slate-800"]:hover,
        html:not(.dark) button[class*="bg-slate-700"]:hover,
        html:not(.dark) a[class*="bg-slate-800"]:hover,
        html:not(.dark) a[class*="bg-slate-700"]:hover,
        html:not(.dark) [class*="hover:bg-slate-700"]:hover,
        html:not(.dark) [class*="hover:bg-slate-800"]:hover {
            background-color: #E2E8F0 !important;
            color: #0F172A !important;
        }

        /* 4. Glassmorphism Panels in Light Mode */
        html:not(.dark) .glass-panel {
            background: rgba(255, 255, 255, 0.96) !important;
            border: 1px solid #E2E8F0 !important;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05) !important;
            color: #0F172A !important;
        }
        html:not(.dark) .glass-panel-hover:hover {
            border-color: #CBD5E1 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08) !important;
        }

        /* 5. Sidebar Layout & Navigation */
        html:not(.dark) aside {
            background-color: #FFFFFF !important;
            border-color: #E2E8F0 !important;
            box-shadow: 2px 0 12px 0 rgba(0, 0, 0, 0.04) !important;
        }
        html:not(.dark) aside > div {
            background-color: #FFFFFF !important;
            border-color: #E2E8F0 !important;
        }
        html:not(.dark) aside a:not([class*="bg-gradient"]) {
            color: #475569 !important;
        }
        html:not(.dark) aside a:not([class*="bg-gradient"]):hover {
            background-color: #F1F5F9 !important;
            color: #0F172A !important;
        }
        html:not(.dark) aside a[class*="bg-gradient"] {
            background: linear-gradient(to right, rgba(16, 185, 129, 0.12), rgba(20, 184, 166, 0.06)) !important;
            border-color: rgba(16, 185, 129, 0.3) !important;
            color: #059669 !important;
        }
        html:not(.dark) aside .tracking-wider {
            color: #64748B !important;
        }
        html:not(.dark) aside [class*="bg-slate-900"] {
            background-color: #F8FAFC !important;
            border-color: #E2E8F0 !important;
        }

        /* 6. Top Navbar Header */
        html:not(.dark) header {
            background-color: rgba(255, 255, 255, 0.95) !important;
            border-color: #E2E8F0 !important;
            box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.05) !important;
        }
        html:not(.dark) header [class*="bg-slate-900"] {
            background-color: #F1F5F9 !important;
            border-color: #E2E8F0 !important;
            color: #334155 !important;
        }
        html:not(.dark) header button[class*="bg-slate-900"] {
            background-color: #FFFFFF !important;
            border-color: #CBD5E1 !important;
            color: #1E293B !important;
        }

        /* 7. Tables & Data Grids */
        html:not(.dark) table {
            color: #0F172A !important;
        }
        html:not(.dark) thead,
        html:not(.dark) thead tr,
        html:not(.dark) thead th,
        html:not(.dark) [class*="bg-slate-900/80"] th {
            background-color: #F8FAFC !important;
            color: #475569 !important;
            border-color: #E2E8F0 !important;
        }
        html:not(.dark) tbody tr {
            background-color: #FFFFFF !important;
        }
        html:not(.dark) tbody tr:hover,
        html:not(.dark) tr[class*="hover:bg-slate-900"]:hover {
            background-color: #F1F5F9 !important;
        }
        html:not(.dark) [class*="divide-slate-800"] > :not([hidden]) ~ :not([hidden]) {
            border-color: #F1F5F9 !important;
        }

        /* 8. Modals & Overlays */
        html:not(.dark) .fixed.inset-0[class*="bg-black"],
        html:not(.dark) [class*="bg-black/80"],
        html:not(.dark) [class*="bg-black/70"] {
            background-color: rgba(15, 23, 42, 0.45) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
        }
        html:not(.dark) .fixed .rounded-3xl,
        html:not(.dark) .fixed [class*="bg-slate-950"] {
            background-color: #FFFFFF !important;
            border-color: #E2E8F0 !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.2) !important;
            color: #0F172A !important;
        }
        html:not(.dark) .fixed [class*="border-slate-800"] {
            border-color: #E2E8F0 !important;
        }
        html:not(.dark) .fixed [class*="bg-slate-900"] {
            background-color: #F8FAFC !important;
            border-color: #E2E8F0 !important;
        }

        /* 9. Form Controls & Inputs */
        html:not(.dark) input:not([type="checkbox"]):not([type="radio"]),
        html:not(.dark) select,
        html:not(.dark) textarea {
            background-color: #FFFFFF !important;
            border-color: #CBD5E1 !important;
            color: #0F172A !important;
        }
        html:not(.dark) input:focus,
        html:not(.dark) select:focus,
        html:not(.dark) textarea:focus {
            border-color: var(--primary-accent) !important;
            box-shadow: 0 0 0 1px var(--primary-accent) !important;
        }
        html:not(.dark) input::placeholder,
        html:not(.dark) textarea::placeholder {
            color: #94A3B8 !important;
        }
        html:not(.dark) label {
            color: #334155 !important;
        }

        /* 10. Borders & Dividers */
        html:not(.dark) [class*="border-slate-800"],
        html:not(.dark) [class*="border-slate-700"],
        html:not(.dark) [class*="border-white/"] {
            border-color: #E2E8F0 !important;
        }

        /* 11. Headings & Typography */
        html:not(.dark) h1, 
        html:not(.dark) h2, 
        html:not(.dark) h3, 
        html:not(.dark) h4, 
        html:not(.dark) h5, 
        html:not(.dark) h6 {
            color: #0F172A !important;
        }

        html:not(.dark) .text-white:not(.btn-theme-primary):not([class*="bg-emerald-500"]):not([class*="bg-cyan-500"]):not([class*="bg-amber-500"]):not([class*="bg-rose-500"]):not([class*="bg-violet-500"]):not([class*="from-"]):not([class*="to-"]):not([class*="via-"]) {
            color: #0F172A !important;
        }

        html:not(.dark) .text-slate-400 {
            color: #64748B !important;
        }
        html:not(.dark) .text-slate-300 {
            color: #475569 !important;
        }
        html:not(.dark) .text-slate-200,
        html:not(.dark) .text-slate-100 {
            color: #1E293B !important;
        }

        /* Crisp Contrast for Neon Colors on Light Backgrounds */
        html:not(.dark) .text-emerald-400 { color: #059669 !important; }
        html:not(.dark) .text-cyan-400    { color: #0891B2 !important; }
        html:not(.dark) .text-amber-400   { color: #D97706 !important; }
        html:not(.dark) .text-rose-400    { color: #E11D48 !important; }
        html:not(.dark) .text-purple-400  { color: #7C3AED !important; }
        html:not(.dark) .text-violet-400  { color: #6D28D9 !important; }

        html:not(.dark) .text-emerald-300 { color: #047857 !important; }
        html:not(.dark) .text-cyan-300    { color: #0369A1 !important; }
        html:not(.dark) .text-amber-300   { color: #B45309 !important; }
        html:not(.dark) .text-rose-300    { color: #BE123C !important; }
        html:not(.dark) .text-purple-300  { color: #6D28D9 !important; }

        /* Crisp Status Badges & Pills in Light Mode */
        html:not(.dark) span[class*="bg-slate-700"],
        html:not(.dark) span[class*="bg-slate-800"],
        html:not(.dark) .badge-slate {
            background-color: #E2E8F0 !important;
            border: 1px solid #CBD5E1 !important;
            color: #1E293B !important;
        }

        html:not(.dark) span[class*="bg-slate-700"].text-slate-300,
        html:not(.dark) span[class*="bg-slate-800"].text-slate-300,
        html:not(.dark) span[class*="bg-slate-700"] .text-slate-300,
        html:not(.dark) span[class*="bg-slate-800"] .text-slate-300,
        html:not(.dark) span[class*="bg-slate-700"].text-slate-400,
        html:not(.dark) span[class*="bg-slate-800"].text-slate-400 {
            color: #1E293B !important;
        }

        html:not(.dark) [class*="bg-amber-500/20"],
        html:not(.dark) [class*="bg-amber-500/10"],
        html:not(.dark) [class*="bg-amber-500/15"],
        html:not(.dark) [class*="bg-amber-500/30"] {
            background-color: #FEF3C7 !important;
            border-color: #FDE68A !important;
            color: #B45309 !important;
        }
        html:not(.dark) [class*="bg-amber-500/20"] *,
        html:not(.dark) [class*="bg-amber-500/10"] * {
            color: #B45309 !important;
        }

        html:not(.dark) [class*="bg-emerald-500/20"],
        html:not(.dark) [class*="bg-emerald-500/10"],
        html:not(.dark) [class*="bg-emerald-500/15"],
        html:not(.dark) [class*="bg-emerald-500/30"] {
            background-color: #DCFCE7 !important;
            border-color: #86EFAC !important;
            color: #15803D !important;
        }
        html:not(.dark) [class*="bg-emerald-500/20"] *,
        html:not(.dark) [class*="bg-emerald-500/10"] * {
            color: #15803D !important;
        }

        html:not(.dark) [class*="bg-cyan-500/20"],
        html:not(.dark) [class*="bg-cyan-500/10"],
        html:not(.dark) [class*="bg-cyan-500/15"],
        html:not(.dark) [class*="bg-cyan-500/30"] {
            background-color: #CFFAFE !important;
            border-color: #7DD3FC !important;
            color: #0369A1 !important;
        }
        html:not(.dark) [class*="bg-cyan-500/20"] *,
        html:not(.dark) [class*="bg-cyan-500/10"] * {
            color: #0369A1 !important;
        }

        html:not(.dark) [class*="bg-rose-500/20"],
        html:not(.dark) [class*="bg-rose-500/10"],
        html:not(.dark) [class*="bg-rose-500/15"],
        html:not(.dark) [class*="bg-rose-500/30"] {
            background-color: #FFE4E6 !important;
            border-color: #FDA4AF !important;
            color: #BE123C !important;
        }
        html:not(.dark) [class*="bg-rose-500/20"] *,
        html:not(.dark) [class*="bg-rose-500/10"] * {
            color: #BE123C !important;
        }

        html:not(.dark) [class*="bg-purple-500/20"],
        html:not(.dark) [class*="bg-violet-500/20"] {
            background-color: #EDE9FE !important;
            border-color: #DDD6FE !important;
            color: #6D28D9 !important;
        }
        html:not(.dark) [class*="bg-purple-500/20"] *,
        html:not(.dark) [class*="bg-violet-500/20"] * {
            color: #6D28D9 !important;
        }

        /* 12. Bookings Calendar Matrix */
        html:not(.dark) [class*="bg-slate-900/50"],
        html:not(.dark) [class*="bg-slate-900/30"] {
            background-color: #F8FAFC !important;
            border-color: #E2E8F0 !important;
        }
        html:not(.dark) .border-emerald-500\/50[class*="bg-slate-900"] {
            background-color: #F0FDF4 !important;
            border-color: #10B981 !important;
        }
        html:not(.dark) .bg-emerald-500\/10 {
            background-color: #DCFCE7 !important;
        }
        html:not(.dark) [class*="bg-amber-500/10"] {
            background-color: #FFFBEB !important;
            border-color: #FDE68A !important;
        }
        html:not(.dark) [class*="bg-amber-500/10"] .text-amber-200 {
            color: #92400E !important;
        }
        html:not(.dark) [class*="bg-cyan-500/10"] {
            background-color: #ECFEFF !important;
            border-color: #A5F3FC !important;
        }
        html:not(.dark) [class*="bg-cyan-500/10"] .text-cyan-200 {
            color: #155E75 !important;
        }
        html:not(.dark) [class*="bg-black/40"] {
            background-color: rgba(15, 23, 42, 0.08) !important;
            color: #334155 !important;
        }

        /* 13. Laravel Pagination in Light Mode */
        html:not(.dark) nav[role="navigation"] span,
        html:not(.dark) nav[role="navigation"] a {
            background-color: #FFFFFF !important;
            border-color: #CBD5E1 !important;
            color: #334155 !important;
        }
        html:not(.dark) nav[role="navigation"] a:hover {
            background-color: #F1F5F9 !important;
            color: #0F172A !important;
        }

        /* 14. Buttons & Badges Contrast */
        html:not(.dark) .btn-theme-primary {
            color: #060911 !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #060911;
        }
        html:not(.dark) ::-webkit-scrollbar-track {
            background: #F1F5F9;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 9999px;
        }
        html:not(.dark) ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-accent);
        }

        /* ========================================================
           CYBER GLASSMORPHIC SELECT2 MODERN STYLING
           ======================================================== */
        .select2-container {
            max-width: 100%;
            vertical-align: middle;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }
        .select2-container.w-full,
        .w-full + .select2-container,
        .select2-full + .select2-container {
            width: 100% !important;
        }

        /* Single Selection Box */
        .select2-container--default .select2-selection--single {
            background-color: #0f172a !important;
            border: 1px solid #334155 !important;
            border-radius: 0.75rem !important; /* rounded-xl (12px) */
            height: 42px !important;
            padding: 0 0.875rem !important;
            display: flex !important;
            align-items: center !important;
            transition: all 0.2s ease !important;
            box-shadow: none !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #f1f5f9 !important;
            font-size: 0.8125rem !important;
            font-weight: 500 !important;
            padding-left: 0 !important;
            padding-right: 1.5rem !important;
            line-height: 40px !important;
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #64748b !important;
            font-size: 0.8125rem !important;
        }

        /* Arrow dropdown indicator */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            right: 0.75rem !important;
            top: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            width: 20px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #94a3b8 transparent transparent transparent !important;
            border-width: 5px 4.5px 0 4.5px !important;
            transition: transform 0.2s ease !important;
        }
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #94a3b8 transparent !important;
            border-width: 0 4.5px 5px 4.5px !important;
        }

        /* Focus & Open States */
        .select2-container--default.select2-container--focus .select2-selection--single,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--primary-accent) !important;
            box-shadow: 0 0 0 2px var(--primary-glow) !important;
            outline: none !important;
        }

        /* Dropdown Container */
        .select2-dropdown {
            background-color: #0b0f19 !important;
            border: 1px solid #334155 !important;
            border-radius: 0.75rem !important;
            box-shadow: 0 20px 30px -5px rgba(0, 0, 0, 0.7), 0 10px 15px -5px rgba(0, 0, 0, 0.5) !important;
            overflow: hidden !important;
            z-index: 999999 !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
        }

        /* Search Box inside Dropdown */
        .select2-search--dropdown {
            padding: 0.6rem !important;
            background-color: #0b0f19 !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
        }
        .select2-search--dropdown .select2-search__field {
            background-color: #0f172a !important;
            border: 1px solid #334155 !important;
            border-radius: 0.5rem !important;
            color: #f1f5f9 !important;
            padding: 0.45rem 0.75rem !important;
            font-size: 0.75rem !important;
            outline: none !important;
            font-family: inherit !important;
            transition: border-color 0.2s ease !important;
            width: 100% !important;
        }
        .select2-search--dropdown .select2-search__field:focus {
            border-color: var(--primary-accent) !important;
            box-shadow: 0 0 0 1px var(--primary-glow) !important;
        }

        /* Results List */
        .select2-results__options {
            max-height: 240px !important;
            padding: 0.35rem !important;
            scrollbar-width: thin;
            scrollbar-color: #334155 #0b0f19;
        }
        .select2-results__options::-webkit-scrollbar {
            width: 6px;
        }
        .select2-results__options::-webkit-scrollbar-track {
            background: #0b0f19;
        }
        .select2-results__options::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 9999px;
        }

        /* Option Items */
        .select2-results__option {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.8125rem !important;
            color: #cbd5e1 !important;
            border-radius: 0.5rem !important;
            margin-bottom: 2px !important;
            transition: all 0.15s ease !important;
        }
        .select2-results__option--highlighted[aria-selected] {
            background: linear-gradient(135deg, var(--primary-accent) 0%, var(--primary-hover) 100%) !important;
            color: #020617 !important;
            font-weight: 600 !important;
        }
        .select2-results__option[aria-selected=true] {
            background-color: rgba(16, 185, 129, 0.15) !important;
            color: var(--primary-accent) !important;
            font-weight: 600 !important;
        }

        /* Clear button */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            color: #f43f5e !important;
            font-size: 1.1rem !important;
            margin-right: 0.5rem !important;
            cursor: pointer;
        }
        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #fb7185 !important;
        }

        /* Light Mode Customizations */
        html:not(.dark) .select2-container--default .select2-selection--single {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
        }
        html:not(.dark) .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #0f172a !important;
        }
        html:not(.dark) .select2-dropdown {
            background-color: #ffffff !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
        }
        html:not(.dark) .select2-search--dropdown {
            background-color: #f8fafc !important;
            border-bottom-color: #e2e8f0 !important;
        }
        html:not(.dark) .select2-search--dropdown .select2-search__field {
            background-color: #ffffff !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
        }
        html:not(.dark) .select2-results__option {
            color: #334155 !important;
        }
        html:not(.dark) .select2-results__option--highlighted[aria-selected] {
            color: #ffffff !important;
        }
        html:not(.dark) .select2-results__options::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        html:not(.dark) .select2-results__options::-webkit-scrollbar-thumb {
            background: #cbd5e1;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 dark:bg-[#060911] dark:text-slate-100 min-h-screen flex flex-col antialiased transition-colors duration-200" 
      x-data="{ 
          sidebarOpen: false,
          darkMode: document.documentElement.classList.contains('dark'),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('theme', 'light');
              }
          }
      }">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Backdrop for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-black/70 backdrop-blur-sm lg:hidden" style="display: none;"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-72 bg-[#0B0F19] border-r border-slate-800/80 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">
            
            <!-- Logo Header -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800/80 bg-[#0B0F19]">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group min-w-0">
                    @if(!empty($appSetting->logo_url))
                        <img src="{{ $appSetting->logo_url }}" alt="Logo" class="w-11 h-11 rounded-xl object-contain bg-slate-900 border border-emerald-500/30 shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-transform shrink-0 p-1">
                    @else
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-105 transition-transform shrink-0">
                            <i class="fa-solid fa-dumbbell text-xl"></i>
                        </div>
                    @endif
                    <div class="min-w-0">
                        <div class="font-extrabold text-base tracking-tight text-white flex items-center gap-1.5 truncate">
                            {{ $appSetting->app_name ?? 'FITZONE GYM' }}
                        </div>
                        <p class="text-[11px] text-slate-400 font-medium truncate">{{ $appSetting->app_tagline ?? 'POS & Fitness Management' }}</p>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white p-2">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-5 space-y-1.5">
                
                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-2 pt-1">
                    Utama
                </div>

                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-400 border border-emerald-500/30 font-bold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center {{ request()->routeIs('dashboard') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
                    <span>Dashboard</span>
                </a>

                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-2 pt-4">
                    Kasir & Penjualan
                </div>

                <a href="{{ route('pos.index') }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pos.index') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-400 border border-emerald-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-cash-register w-5 text-center {{ request()->routeIs('pos.index') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
                        <span>Terminal Kasir POS</span>
                    </div>
                    <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wide bg-emerald-500 text-slate-950 rounded-full shadow-sm">Kasir</span>
                </a>

                <a href="{{ route('pos.orders') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pos.orders*') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-400 border border-emerald-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-receipt w-5 text-center {{ request()->routeIs('pos.orders*') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
                    <span>Riwayat Transaksi</span>
                </a>

                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-2 pt-4">
                    Suplemen & Inventori
                </div>

                <a href="{{ route('supplements.index') }}" 
                   class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('supplements.index', 'supplements.create', 'supplements.edit') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-400 border border-emerald-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-bottle-droplet w-5 text-center {{ request()->routeIs('supplements.index*') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
                        <span>Katalog Suplemen & Stok</span>
                    </div>
                </a>

                <a href="{{ route('supplements.history') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('supplements.history') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-400 border border-emerald-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-clock-rotate-left w-5 text-center {{ request()->routeIs('supplements.history') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
                    <span>Mutasi & Riwayat Stok</span>
                </a>

                <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3 pb-2 pt-4">
                    Layanan Gym & Booking
                </div>

                <a href="{{ route('bookings.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('bookings.index', 'bookings.create') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-400 border border-emerald-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-calendar-check w-5 text-center {{ request()->routeIs('bookings.index*') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
                    <span>Daftar Booking PT & Kelas</span>
                </a>

                <a href="{{ route('bookings.calendar') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('bookings.calendar') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-400 border border-emerald-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-calendar-days w-5 text-center {{ request()->routeIs('bookings.calendar') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
                    <span>Jadwal Mingguan (Kalender)</span>
                </a>

                <a href="{{ route('packages.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('packages.index*') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-400 border border-emerald-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-ticket w-5 text-center {{ request()->routeIs('packages.index*') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
                    <span>Tiket Masuk & Paket Gym</span>
                </a>

                <a href="{{ route('members.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('members.index*') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-400 border border-emerald-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-id-card-clip w-5 text-center {{ request()->routeIs('members.index*') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
                    <span>Member & Langganan</span>
                </a>

                <a href="{{ route('trainers.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('trainers.index*') ? 'bg-gradient-to-r from-emerald-500/20 to-teal-500/10 text-emerald-400 border border-emerald-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-person-running w-5 text-center {{ request()->routeIs('trainers.index*') ? 'text-emerald-400' : 'text-slate-400' }}"></i>
                    <span>Trainer & Kelas Studio</span>
                </a>

                @if(Auth::user()->isOwner())
                    <div class="text-[11px] font-bold uppercase tracking-wider text-amber-400 px-3 pb-2 pt-4 flex items-center justify-between">
                        <span>Owner & Finansial</span>
                        <i class="fa-solid fa-crown text-xs"></i>
                    </div>

                    <a href="{{ route('reports.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('reports.index*') ? 'bg-gradient-to-r from-amber-500/20 to-orange-500/10 text-amber-400 border border-amber-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="fa-solid fa-chart-line w-5 text-center {{ request()->routeIs('reports.index*') ? 'text-amber-400' : 'text-slate-400' }}"></i>
                        <span>Laporan Laba & Keuangan</span>
                    </a>

                    <a href="{{ route('users.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('users.index*') ? 'bg-gradient-to-r from-amber-500/20 to-orange-500/10 text-amber-400 border border-amber-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="fa-solid fa-users-gear w-5 text-center {{ request()->routeIs('users.index*') ? 'text-amber-400' : 'text-slate-400' }}"></i>
                        <span>Kelola Kasir & Owner</span>
                    </a>

                    <a href="{{ route('settings.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('settings.index*') ? 'bg-gradient-to-r from-amber-500/20 to-orange-500/10 text-amber-400 border border-amber-500/30 font-bold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="fa-solid fa-sliders w-5 text-center {{ request()->routeIs('settings.index*') ? 'text-amber-400' : 'text-slate-400' }}"></i>
                        <span>Pengaturan Aplikasi</span>
                    </a>
                @endif
            </div>

            <!-- User Footer Profile -->
            <div class="p-4 border-t border-slate-800/80 bg-[#0B0F19]">
                <div class="flex items-center justify-between p-2 rounded-xl bg-slate-900/80 border border-slate-800">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white shadow-md {{ Auth::user()->isOwner() ? 'bg-gradient-to-tr from-amber-600 to-yellow-400 ring-2 ring-amber-500/30' : 'bg-gradient-to-tr from-emerald-600 to-teal-400 ring-2 ring-emerald-500/30' }}">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</h4>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                @if(Auth::user()->isOwner())
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-extrabold uppercase bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                        Owner
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-extrabold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                        Kasir
                                    </span>
                                @endif
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-1.5">
                        <button @click="toggleTheme()" type="button" :title="darkMode ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-amber-400 hover:bg-amber-500/10 transition-colors">
                            <i :class="darkMode ? 'fa-solid fa-sun text-amber-400' : 'fa-solid fa-moon text-cyan-500'" class="text-xs"></i>
                        </button>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Logout" 
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors">
                                <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#060911] ambient-mesh relative">
            
            <!-- Top Navbar Glass -->
            <header class="h-20 bg-[#0B0F19]/80 backdrop-blur-xl border-b border-white/[0.06] px-6 flex items-center justify-between z-30 sticky top-0 shadow-lg shadow-black/20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-300 hover:text-white p-2.5 rounded-xl bg-slate-900 border border-slate-800 transition-colors">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-xl font-extrabold text-white tracking-tight font-display">@yield('page-title', 'Dashboard')</h1>
                        <p class="text-xs text-slate-400">@yield('page-subtitle', 'Sistem Manajemen Fitness, Suplemen & POS')</p>
                    </div>
                </div>

                <!-- Right Quick Actions -->
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <!-- Theme Color Indicator Pill -->
                    <div class="hidden lg:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900/90 border border-white/[0.07] text-xs">
                        <span class="w-2.5 h-2.5 rounded-full shadow-sm animate-pulse" style="background-color: var(--primary-accent); box-shadow: 0 0 10px var(--primary-glow);"></span>
                        <span class="text-[11px] font-bold text-slate-300 font-display">{{ $appSetting->theme_config['name'] ?? 'Cyber Emerald' }}</span>
                    </div>

                    <!-- Dark / Light Mode Toggle Button -->
                    <button @click="toggleTheme()" 
                            type="button" 
                            :title="darkMode ? 'Ganti ke Mode Terang' : 'Ganti ke Mode Gelap'"
                            class="px-2.5 py-1.5 sm:px-3 sm:py-2 rounded-xl border transition-all duration-200 flex items-center gap-2 text-xs font-bold
                                   bg-white hover:bg-slate-100 text-slate-700 border-slate-200 shadow-sm
                                   dark:bg-slate-900/90 dark:hover:bg-slate-800 dark:text-amber-300 dark:border-white/[0.08] hover:scale-105 active:scale-95 cursor-pointer">
                        <template x-if="darkMode">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-moon text-cyan-400 text-xs"></i>
                                <span class="hidden md:inline text-[11px] text-slate-300 font-display">Mode Gelap</span>
                            </div>
                        </template>
                        <template x-if="!darkMode">
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-sun text-amber-500 text-xs"></i>
                                <span class="hidden md:inline text-[11px] text-slate-700 font-display">Mode Terang</span>
                            </div>
                        </template>
                    </button>

                    <!-- Live Clock -->
                    <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-900/90 border border-white/[0.07] text-xs font-mono text-slate-300"
                         x-data="{ time: '' }" x-init="setInterval(() => { time = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }, 1000)">
                        <i class="fa-regular fa-clock" style="color: var(--primary-accent)"></i>
                        <span x-text="time || '{{ date('H:i:s') }}'">{{ date('H:i:s') }}</span>
                        <span class="text-slate-500 text-[10px]">WIB</span>
                    </div>

                    <!-- Quick POS Button -->
                    <a href="{{ route('pos.index') }}" 
                       class="btn-theme-primary flex items-center gap-2 px-4 py-2.5 rounded-xl text-slate-950 font-black text-xs uppercase tracking-wider transition-all">
                        <i class="fa-solid fa-cash-register"></i>
                        <span>Kasir POS</span>
                    </a>
                </div>
            </header>

            <!-- Main Scrollable Content Container -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                
                <!-- Flash Alert Messages -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 flex items-center justify-between shadow-lg shadow-emerald-950/40">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <span class="text-sm font-semibold">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-400 hover:text-white">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
                         class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 flex items-center justify-between shadow-lg shadow-rose-950/40">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-rose-500/20 flex items-center justify-center text-rose-400">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <span class="text-sm font-semibold">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-rose-400 hover:text-white">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if (session('info'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mb-6 p-4 rounded-xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 flex items-center justify-between shadow-lg shadow-cyan-950/40">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center text-cyan-400">
                                <i class="fa-solid fa-circle-info"></i>
                            </div>
                            <span class="text-sm font-semibold">{{ session('info') }}</span>
                        </div>
                        <button @click="show = false" class="text-cyan-400 hover:text-white">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300">
                        <div class="flex items-center gap-2 font-bold mb-2 text-sm">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span>Terjadi kesalahan pada input data:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Yield Page Content -->
                @yield('content')
            </main>
        </div>
    </div>
    <!-- Global Select2 Initialization & Alpine.js Synchronization -->
    <script>
        // 1. Intercept HTMLSelectElement.prototype.value setter to notify Select2 of programmatic changes (e.g. Alpine x-model)
        (function() {
            var originalValDesc = Object.getOwnPropertyDescriptor(HTMLSelectElement.prototype, 'value');
            if (originalValDesc && originalValDesc.set) {
                Object.defineProperty(HTMLSelectElement.prototype, 'value', {
                    set: function(val) {
                        originalValDesc.set.call(this, val);
                        if (window.jQuery && $(this).hasClass('select2-hidden-accessible')) {
                            $(this).trigger('change.select2');
                        }
                    },
                    get: function() {
                        return originalValDesc.get.call(this);
                    }
                });
            }
        })();

        // 2. Main Select2 Initializer
        window.initSelect2 = function(context) {
            if (typeof jQuery === 'undefined' || typeof jQuery.fn.select2 === 'undefined') return;

            var $targets = context ? $(context).find('select') : $('select');

            $targets.each(function() {
                var $select = $(this);

                // Skip if marked to ignore or already initialized
                if ($select.data('no-select2') !== undefined || $select.hasClass('select2-hidden-accessible')) {
                    return;
                }

                // If element is explicitly or implicitly full width
                var isFullWidth = $select.hasClass('w-full') || 
                                  $select.closest('.grid, .space-y-2, .space-y-3, .space-y-4, .space-y-5, .space-y-6, [x-show]').length > 0;

                var optionCount = $select.find('option').length;
                var placeholderText = $select.find('option[value=""]').first().text() || 
                                      $select.attr('placeholder') || 
                                      '-- Pilih --';

                $select.select2({
                    width: isFullWidth ? '100%' : 'auto',
                    placeholder: placeholderText,
                    allowClear: $select.find('option[value=""]').length > 0 && !$select.prop('required'),
                    minimumResultsForSearch: optionCount > 6 ? 0 : Infinity,
                    dropdownParent: $(document.body)
                });

                // When user selects in Select2, propagate native change & input events for Alpine.js x-model & @change
                $select.on('select2:select select2:unselect select2:clear', function(e) {
                    this.dispatchEvent(new Event('change', { bubbles: true }));
                    this.dispatchEvent(new Event('input', { bubbles: true }));
                });
            });
        };

        // 3. Document ready and dynamic observers
        $(document).ready(function() {
            window.initSelect2();

            // Observe modal triggers and DOM mutations
            var initTimeout = null;
            function scheduleInit() {
                clearTimeout(initTimeout);
                initTimeout = setTimeout(function() {
                    window.initSelect2();
                    $('select.select2-hidden-accessible').each(function() {
                        $(this).trigger('change.select2');
                    });
                }, 80);
            }

            $(document).on('click', 'button, a, [x-on\\:click], [@click]', scheduleInit);

            var observer = new MutationObserver(function(mutations) {
                var hasVisibleChange = false;
                for (var i = 0; i < mutations.length; i++) {
                    var m = mutations[i];
                    if (m.type === 'attributes' && (m.attributeName === 'style' || m.attributeName === 'class')) {
                        hasVisibleChange = true;
                        break;
                    } else if (m.addedNodes.length > 0) {
                        hasVisibleChange = true;
                        break;
                    }
                }
                if (hasVisibleChange) {
                    scheduleInit();
                }
            });

            observer.observe(document.body, {
                attributes: true,
                childList: true,
                subtree: true,
                attributeFilter: ['style', 'class']
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
