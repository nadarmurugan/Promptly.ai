<?php
// Ensure session is started only if it hasn't been already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promptly.ai: The Ultimate AI Prompt Marketplace & Generator</title>
    <meta name="description" content="Discover, customize, and deploy the perfect AI prompts for productivity, copywriting, and marketing. Instantly boost your creativity with our curated collection of 500+ high-quality, pre-tested AI prompts.">
    <meta name="keywords" content="AI prompts, prompt marketplace, ChatGPT prompts, Midjourney prompts, AI creativity, AI productivity, generative AI, copywriting AI tools, prompt engineering">
    <meta name="author" content="Promptly.ai">
    <meta name="robots" content="index, follow">

    <link rel="preload" href="https://cdn.tailwindcss.com" as="script">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" as="style">

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Base styles are solid, keeping them concise */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; overflow-x: hidden; }

        .gradient-text {
            background: linear-gradient(135deg, #6366F1 0%, #FACC15 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .glass { 
            background: rgba(255,255,255,0.1); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(255,255,255,0.2); 
        }
        .dark .glass {
            background: rgba(15, 23, 42, 0.5); /* darkbg / 50% opacity */
            border: 1px solid rgba(75, 85, 99, 0.4); /* gray-700 / 40% opacity */
        }
        
        .hover-lift { 
            transition: transform 0.2s ease, box-shadow 0.2s ease; 
        }
        
        .hover-lift:hover { 
            transform: translateY(-4px); 
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Simplified Theme Toggle Glows */
        .sun-glow { filter: drop-shadow(0 0 8px rgba(250, 204, 21, 0.6)); }
        .moon-glow { filter: drop-shadow(0 0 8px rgba(99, 102, 241, 0.6)); }
        
        /* Fade in animation (Kept the original fadeIn and scroll-element animations - they are good) */
        @keyframes fadeIn { from { opacity:0; transform: translateY(20px); } to { opacity:1; transform: translateY(0); } }
        .animate-fadeIn { animation: fadeIn 0.6s ease-out forwards; }
        .scroll-element { opacity: 0; transform: translateY(30px); transition: opacity 0.8s ease, transform 0.8s ease; }
        .scroll-element.visible { opacity: 1; transform: translateY(0); }

        /* Blob Animation - Optimized */
        /* Keeping original blob styles and animations as they are clean */
        .blob { position: absolute; border-radius: 50%; opacity: 0.5; filter: blur(60px); z-index: -1; }
        .blob-1 { width: clamp(200px, 30vw, 300px); height: clamp(200px, 30vw, 300px); background: rgba(99, 102, 241, 0.4); top: 10%; left: 5%; animation: blobFloat1 25s infinite ease-in-out; }
        .blob-2 { width: clamp(220px, 35vw, 350px); height: clamp(220px, 35vw, 350px); background: rgba(250, 204, 21, 0.4); top: 40%; right: 5%; animation: blobFloat2 30s infinite ease-in-out; }
        .blob-3 { width: clamp(180px, 25vw, 250px); height: clamp(180px, 25vw, 250px); background: rgba(99, 102, 241, 0.3); bottom: 10%; left: 15%; animation: blobFloat3 20s infinite ease-in-out; }
        
        @keyframes blobFloat1 { 0%, 100% { transform: translate(0, 0) scale(1); } 25% { transform: translate(40px, -60px) scale(1.1); } 50% { transform: translate(-20px, 30px) scale(0.9); } 75% { transform: translate(60px, 10px) scale(1.05); } }
        @keyframes blobFloat2 { 0%, 100% { transform: translate(0, 0) scale(1); } 33% { transform: translate(-30px, 40px) scale(1.15); } 66% { transform: translate(50px, -20px) scale(0.85); } }
        @keyframes blobFloat3 { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(30px, -40px) scale(1.2); } }

        /* Mobile menu transitions: Cleaned up to rely on a single class toggle (removed opacity/transform overrides in JS) */
        .mobile-menu {
            transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.4s ease-in-out, opacity 0.4s ease;
            max-height: 0;
            opacity: 0;
            padding-top: 0;
            padding-bottom: 0;
        }
        
        .mobile-menu.active {
            max-height: 500px; /* Sufficient height for content */
            opacity: 1;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .mobile-menu.hidden {
            display: none; /* Add hidden to remove it from layout/tab order when not active */
        }

        /* Optimize for reduced motion */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: { 
                        primary: '#6366F1', 
                        accent: '#FACC15', 
                        darkbg: '#0F172A' 
                    },
                    fontFamily: { 
                        sans: ['Poppins', 'sans-serif'] 
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            },
        }
    </script>
</head>
<body class="dark:bg-gray-900 relative">

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>

<header class="fixed top-4 left-1/2 -translate-x-1/2 w-[95%] md:w-[90%] max-w-6xl z-50 glass dark:bg-gray-900/80 backdrop-blur-xl border border-gray-200 dark:border-gray-700 rounded-3xl shadow-lg">
    <div class="flex justify-between items-center px-4 py-3 md:px-6">
        <a href="index.php" class="flex items-center gap-3 group" aria-label="Promptly.ai Home">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-brain text-white text-lg" aria-hidden="true"></i>
            </div>
            <span class="text-xl font-bold text-gray-900 dark:text-white">
                Promptly<span class="text-primary">.ai</span>
            </span>
        </a>

        <nav class="hidden md:flex absolute left-1/2 -translate-x-1/2" aria-label="Main navigation">
            <div class="flex gap-8 text-gray-700 dark:text-gray-300 font-medium">
                <a href="#features" class="hover:text-primary transition-colors duration-300 flex items-center gap-2"><i class="fa-solid fa-star text-accent w-4" aria-hidden="true"></i> Features</a>
                <a href="#prompts" class="hover:text-primary transition-colors duration-300 flex items-center gap-2"><i class="fa-solid fa-lightbulb text-accent w-4" aria-hidden="true"></i> Prompts</a>
                <a href="#about" class="hover:text-primary transition-colors duration-300 flex items-center gap-2"><i class="fa-solid fa-circle-info text-accent w-4" aria-hidden="true"></i> About</a>
                <a href="#contact" class="hover:text-primary transition-colors duration-300 flex items-center gap-2"><i class="fa-solid fa-envelope text-accent w-4" aria-hidden="true"></i> Contact</a>
            </div>
        </nav>

        <div class="flex items-center gap-4">
            <button id="theme-toggle" class="theme-toggle hidden md:flex w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-300" aria-label="Toggle dark/light theme">
                <i class="fa-solid fa-moon dark:hidden moon-glow" aria-hidden="true"></i>
                <i class="fa-solid fa-sun hidden dark:block sun-glow" aria-hidden="true"></i>
            </button>
            <a href="#prompts" class="hidden md:flex bg-primary px-5 py-2.5 rounded-lg text-white font-semibold items-center gap-2 hover-lift shadow-md transition-all duration-300"><i class="fa-solid fa-rocket" aria-hidden="true"></i> Explore</a>
            <button id="menu-btn" class="md:hidden w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 transition-colors duration-300" aria-label="Toggle menu" aria-controls="mobile-menu" aria-expanded="false">
                <i class="fa-solid fa-bars" id="menu-icon" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <nav id="mobile-menu" class="mobile-menu hidden md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 rounded-b-2xl overflow-hidden px-6 transition-all duration-400" aria-label="Mobile navigation">
        <div class="flex flex-col gap-4">
            <a href="#features" class="flex items-center gap-3 py-2 text-gray-700 dark:text-gray-300 hover:text-primary transition-colors duration-300"><i class="fa-solid fa-star text-accent w-5" aria-hidden="true"></i> Features</a>
            <a href="#prompts" class="flex items-center gap-3 py-2 text-gray-700 dark:text-gray-300 hover:text-primary transition-colors duration-300"><i class="fa-solid fa-lightbulb text-accent w-5" aria-hidden="true"></i> Prompts</a>
            <a href="#about" class="flex items-center gap-3 py-2 text-gray-700 dark:text-gray-300 hover:text-primary transition-colors duration-300"><i class="fa-solid fa-circle-info text-accent w-5" aria-hidden="true"></i> About</a>
            <a href="#contact" class="flex items-center gap-3 py-2 text-gray-700 dark:text-gray-300 hover:text-primary transition-colors duration-300"><i class="fa-solid fa-envelope text-accent w-5" aria-hidden="true"></i> Contact</a>
            
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col gap-3">
                <button id="mobile-theme-toggle" class="theme-toggle w-full py-3 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center gap-2 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-300" aria-label="Toggle dark/light theme">
                    <i class="fa-solid fa-moon dark:hidden" aria-hidden="true"></i>
                    <i class="fa-solid fa-sun hidden dark:block" aria-hidden="true"></i>
                    <span>Toggle Theme</span>
                </button>
                <a href="#prompts" class="bg-primary w-full py-3 rounded-lg text-white font-semibold flex items-center justify-center gap-2 hover-lift transition-all duration-300"><i class="fa-solid fa-rocket" aria-hidden="true"></i> Explore Prompts</a>
            </div>
        </div>
    </nav>
</header>

<main>
    <section id="hero" class="pt-32 pb-20 md:pt-40 md:pb-28 bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-darkbg relative overflow-hidden" aria-labelledby="hero-heading">
        <div class="container mx-auto px-4 md:px-6 flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

            <div class="flex-1 text-center lg:text-left scroll-element animate-fadeIn">
                <div class="inline-flex items-center gap-2 bg-white/70 dark:bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-200 dark:border-gray-700 mb-6" role="status">
                    <span class="w-2 h-2 bg-primary rounded-full animate-pulse-slow" aria-hidden="true"></span>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">AI-Powered Prompt Marketplace</span>
                </div>

                <h1 id="hero-heading" class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight mb-6">
                    Unlock Your <span class="gradient-text">AI Creativity</span> with Promptly.ai
                </h1>

                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto lg:mx-0">
                    Discover, customize, and deploy the perfect AI prompts. Boost your productivity with our curated collection of 500+ high-quality prompts.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="#prompts" class="bg-primary px-8 py-4 rounded-xl text-white font-semibold flex items-center justify-center gap-3 hover-lift shadow-lg transition-all duration-300"><i class="fa-solid fa-rocket" aria-hidden="true"></i> Explore Prompts</a>
                    <a href="#features" class="border border-primary px-8 py-4 rounded-xl font-semibold flex items-center justify-center gap-3 text-primary dark:text-white hover:bg-primary hover:text-white transition-colors duration-300"><i class="fa-solid fa-play" aria-hidden="true"></i> Watch Demo</a>
                </div>

                <div class="flex flex-wrap gap-8 mt-12 justify-center lg:justify-start">
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">500+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">AI Prompts</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">10K+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Users</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">95%</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Success Rate</div>
                    </div>
                </div>
            </div>

            <div class="flex-1 w-full max-w-lg lg:max-w-xl scroll-element animate-fadeIn" style="animation-delay: 0.2s;">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 hover-lift transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-3 h-3 rounded-full bg-red-400" aria-hidden="true"></div>
                        <div class="w-3 h-3 rounded-full bg-yellow-400" aria-hidden="true"></div>
                        <div class="w-3 h-3 rounded-full bg-green-400" aria-hidden="true"></div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 ml-2">prompt-generator.js</div>
                    </div>

                    <pre class="font-mono text-sm text-gray-800 dark:text-gray-200 overflow-x-auto">
<code>
<span class="text-purple-600">// Marketing Copy Generator</span>
const prompt = {
    role: "expert copywriter",
    tone: "persuasive",
    goal: "increase conversions"
};
</code>
                    </pre>

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center"><i class="fa-solid fa-bolt text-primary text-sm" aria-hidden="true"></i></div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">High Conversion</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">234 uses today</div>
                            </div>
                        </div>
                        <button class="bg-primary px-4 py-2 rounded-lg text-white text-sm font-semibold flex items-center gap-2 hover-lift transition-all duration-300" aria-label="Copy prompt to clipboard">
                            <i class="fa-solid fa-copy" aria-hidden="true"></i> Copy
                        </button>
                    </div>
                </div>
            </div>
        </div>
</section>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Theme Toggle Logic ---
        const themeToggle = document.getElementById('theme-toggle');
        const mobileThemeToggle = document.getElementById('mobile-theme-toggle');
        
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            // This is necessary to update the icon state on both toggles
            updateThemeIcons(isDark); 
        }

        function updateThemeIcons(isDark) {
             // Logic to update icons based on isDark state if they were complex
             // For the current simple icons, the CSS :dark selector handles it perfectly,
             // so this function is mostly a placeholder for future complexity.
        }

        // Set initial theme
        const storedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
            document.documentElement.classList.add('dark');
            updateThemeIcons(true);
        } else {
            updateThemeIcons(false);
        }

        if (themeToggle) { themeToggle.addEventListener('click', toggleTheme); }
        if (mobileThemeToggle) { mobileThemeToggle.addEventListener('click', toggleTheme); }


        // --- 2. Mobile Menu Toggle Logic (Improved) ---
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        
        function toggleMobileMenu() {
            const isActive = mobileMenu.classList.toggle('active'); // Toggles 'active' for smooth transition
            
            // Toggle 'hidden' class after transition to ensure it's not focusable when closed
            if (isActive) {
                mobileMenu.classList.remove('hidden');
            } else {
                // Wait for the transition (400ms defined in CSS) before hiding completely
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 400); 
            }
            
            // Update button icon and aria-expanded state
            menuBtn.setAttribute('aria-expanded', isActive);
            menuIcon.className = isActive ? 'fa-solid fa-xmark' : 'fa-solid fa-bars';
        }
        
        menuBtn.addEventListener('click', toggleMobileMenu);
        
        // Close mobile menu when clicking on a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('a');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (mobileMenu.classList.contains('active')) {
                    toggleMobileMenu();
                }
            });
        });


        // --- 3. Smooth Scroll ---
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                // Offset the scroll position to account for the fixed header height
                const headerHeight = document.querySelector('header').offsetHeight + 24; // Header height + some margin
                if (target) {
                    window.scrollTo({
                        top: target.offsetTop - headerHeight,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // --- 4. Scroll Reveal Animation ---
        const scrollElements = document.querySelectorAll('.scroll-element');
        
        const elementInView = (el, dividend = 1) => {
            const elementTop = el.getBoundingClientRect().top;
            return elementTop <= (window.innerHeight || document.documentElement.clientHeight) / dividend;
        };
        
        const displayScrollElement = (element) => {
            element.classList.add('visible');
        };
        
        const handleScrollAnimation = () => {
            scrollElements.forEach((el) => {
                if (elementInView(el, 1.2)) {
                    displayScrollElement(el);
                }
            });
        };
        
        // Initial check
        handleScrollAnimation();
        
        // Throttled scroll event for performance
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            if (!scrollTimeout) {
                scrollTimeout = setTimeout(() => {
                    scrollTimeout = null;
                    handleScrollAnimation();
                }, 10);
            }
        });
    });
</script>

</body>
</html>