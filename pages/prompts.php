<?php
// pages/prompts.php

// 1. Configuration and Session Management
// -----------------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Correct file paths using '../' to go up one directory level
// NOTE: These files must exist in the '../includes/' directory for the script to run.
require_once '../includes/db.php'; 
require_once '../includes/functions.php';

// --- CRITICAL UTILITY (Security) ---
if (!function_exists('esc_html')) {
    /**
     * Escapes HTML entities in a string for security (XSS prevention).
     * @param string|null $data
     * @return string
     */
    function esc_html($data): string {
        // Handle null/non-string input gracefully
        $data = is_string($data) ? $data : '';
        // ENT_QUOTES | ENT_HTML5 covers single/double quotes and ensures HTML5 compatibility
        return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}

// 2. Data Fetching
// -----------------------------------------------------------------------------
/**
 * NOTE: getPrompts() is assumed to fetch data from the database.
 * MOCK DATA: Since I cannot access your database, I will use mock data 
 * to ensure the HTML/JS logic works.
 */
if (function_exists('getPrompts')) {
    $prompts = getPrompts();
} else {
    // Mock Data for demonstration and testing the loop/card logic
    $prompts = [
        ['id' => 1, 'title' => 'Ultimate Blog Post Generator', 'description' => 'Generates a 1500-word SEO-optimized blog post outline, title, and key sections based on a single keyword.', 'category_slug' => 'copywriting', 'image' => 'placeholder_blog.jpg', 'prompt_text' => 'Act as a professional SEO copywriter. Generate a comprehensive, 1500-word blog post in Markdown format about the topic: {keyword}. Include an attention-grabbing title, an introduction, three main sections with sub-headings, and a concluding call to action.'],
        ['id' => 2, 'title' => 'Midjourney Photo-Realistic Portrait', 'description' => 'A complex prompt for Midjourney to create a hyper-detailed, cinematic portrait of a subject.', 'category_slug' => 'image', 'image' => 'placeholder_portrait.jpg', 'prompt_text' => 'photo-realistic portrait of an elderly man with a weathered face, taken on a stormy fishing boat, volumetric lighting, 85mm lens, f/1.4, cinematic, hyper-detailed, --ar 16:9 --v 6.0'],
        ['id' => 3, 'title' => 'Advanced Code Reviewer & Refactor', 'description' => 'Analyzes a block of code, provides security and performance feedback, and suggests a refactored version.', 'category_slug' => 'coding', 'image' => 'placeholder_code.jpg', 'prompt_text' => 'Review the following PHP code for security vulnerabilities (like XSS or SQL injection) and suggest performance improvements. Finally, provide the fully refactored, cleaner version.'],
        ['id' => 4, 'title' => 'Stoic Life Coach', 'description' => 'Acts as a Stoic philosopher to provide actionable advice on handling modern-day anxiety and stress.', 'category_slug' => 'lifestyle', 'image' => 'placeholder_stoic.jpg', 'prompt_text' => 'Act as Marcus Aurelius. I am feeling anxious about {situation}. Provide a piece of Stoic advice using the principles of Dichotomy of Control and Amor Fati.'],
    ];
}

// Define page metadata
$page_title = "Promptly.ai - Full AI Prompts Library";
$page_description = "Search and find the perfect command to power your next AI creation.";

// Dynamic color setup for card iteration
$dynamic_colors = [
    'indigo', 'pink', 'cyan', 'emerald', 'purple', 'amber'
];

$back_url = esc_html($_SERVER['HTTP_REFERER'] ?? '#');
// Get the current page URL for sharing, escaping for security
$current_page_url = esc_html((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($page_title); ?></title>
    <meta name="description" content="<?php echo esc_html($page_description); ?>">
    <meta name="keywords" content="AI prompts, prompt marketplace, ChatGPT prompts, Midjourney prompts, AI creativity, AI productivity, generative AI, copywriting AI tools, prompt engineering">
    <meta name="author" content="Promptly.ai">
    <meta name="robots" content="index, follow">

    <link rel="preload" href="https://cdn.tailwindcss.com" as="script">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" as="style">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" as="style">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        // 3. Tailwind Configuration (JIT Safelisting and Custom Theme)
        const DYNAMIC_COLORS_SAFELIST = [
            'indigo-600', 'pink-600', 'cyan-600', 'emerald-600', 'purple-600', 'amber-600',
            'indigo-500', 'pink-500', 'cyan-500', 'emerald-500', 'purple-500', 'amber-500',
            'shadow-indigo-500/40', 'shadow-pink-500/40', 'shadow-cyan-500/40', 
            'shadow-emerald-500/40', 'shadow-purple-500/40', 'shadow-amber-500/40',
            'hover:text-indigo-600', 'hover:text-pink-600', 'hover:text-cyan-600', 
            'hover:text-emerald-600', 'hover:text-purple-600', 'hover:text-amber-600',
            'bg-indigo-600', 'bg-pink-600', 'bg-cyan-600', 'bg-emerald-600', 'bg-purple-600', 'bg-amber-600',
            'hover:bg-indigo-500', 'hover:bg-pink-500', 'hover:bg-cyan-500', 'hover:bg-emerald-500', 'hover:bg-purple-500', 'hover:bg-amber-500',
            'ring-indigo-500', 'ring-pink-500', 'ring-cyan-500', 'ring-emerald-500', 'ring-purple-500', 'ring-amber-500',
            'shadow-indigo-800/20', 'shadow-pink-800/20', 'shadow-cyan-800/20', 
            'shadow-emerald-800/20', 'shadow-purple-800/20', 'shadow-amber-800/20',
        ];

        tailwind.config = {
            darkMode: 'class',
            content: [
                './**/*.php', 
                { raw: DYNAMIC_COLORS_SAFELIST.join(' ') }, 
            ],
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

    <style>
        /* (Styles remain the same for aesthetics and are omitted for brevity in this block) */
        body { background: #f8fafc; overflow-x: hidden; }
        .gradient-text { background: linear-gradient(135deg, #6366F1 0%, #FACC15 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .glass { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); }
        .dark .glass { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(75, 85, 99, 0.4); }
        .prompt-card { background-color: #F8FBFE; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04); transition: all 0.3s ease-in-out; border: 1px solid #e5e7eb; }
        .dark .prompt-card { background-color: #1e293b; border: 1px solid #374151; }
        .tools { display: flex; align-items: center; padding: 9px 12px; background: #e5e7eb; border-top-left-radius: 11px; border-top-right-radius: 11px; border-bottom: 1px solid #d1d5db; }
        .dark .tools { background: #374151; border-bottom: 1px solid #4b5563; }
        .circle { padding: 0 4px; } .box { display: inline-block; width: 10px; height: 10px; border-radius: 50%; }
        .red { background-color: #ff605c; } .yellow { background-color: #ffbd44; } .green { background-color: #00ca4e; }
        .hover-lift { transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); }
        .hover-lift:hover { transform: translateY(-6px); box-shadow: 0 20px 40px -8px rgba(0, 0, 0, 0.2); }
        .blob { position: absolute; border-radius: 50%; opacity: 0.5; filter: blur(60px); z-index: -1; }
        .blob-1 { width: clamp(200px, 30vw, 300px); height: clamp(200px, 30vw, 300px); background: rgba(99, 102, 241, 0.4); top: 10%; left: 5%; animation: blobFloat1 25s infinite ease-in-out; }
        .blob-2 { width: clamp(220px, 35vw, 350px); height: clamp(220px, 35vw, 350px); background: rgba(250, 204, 21, 0.4); top: 40%; right: 5%; animation: blobFloat2 30s infinite ease-in-out; }
        .blob-3 { width: clamp(180px, 25vw, 250px); height: clamp(180px, 25vw, 250px); background: rgba(99, 102, 241, 0.3); bottom: 10%; left: 15%; animation: blobFloat3 20s infinite ease-in-out; }
        @keyframes blobFloat1 { 0%, 100% { transform: translate(0, 0) scale(1); } 25% { transform: translate(40px, -60px) scale(1.1); } 50% { transform: translate(-20px, 30px) scale(0.9); } 75% { transform: translate(60px, 10px) scale(1.05); } }
        @keyframes blobFloat2 { 0%, 100% { transform: translate(0, 0) scale(1); } 33% { transform: translate(-30px, 40px) scale(1.15); } 66% { transform: translate(50px, -20px) scale(0.85); } }
        @keyframes blobFloat3 { 0%, 100% { transform: translate(0, 0) scale(1); } 50% { transform: translate(30px, -40px) scale(1.2); } }
        .mobile-menu { transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), padding 0.4s ease-in-out, opacity 0.4s ease; max-height: 0; opacity: 0; padding-top: 0; padding-bottom: 0; }
        .mobile-menu.active { max-height: 500px; opacity: 1; padding-top: 1rem; padding-bottom: 1rem; }
    </style>
</head>
<body class="dark:bg-darkbg relative pt-24">

<div class="blob blob-1"></div>
<div class="blob blob-2"></div>
<div class="blob blob-3"></div>

<header class="fixed top-4 left-1/2 -translate-x-1/2 w-[95%] md:w-[90%] max-w-6xl z-50 glass dark:bg-gray-900/80 backdrop-blur-xl border border-gray-200 dark:border-gray-700 rounded-3xl shadow-lg" role="banner">
    <div class="flex justify-between items-center px-4 py-3 md:px-6">
        <a href="../index.php" class="flex items-center gap-3 group" aria-label="Promptly.ai Home">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                <i class="fa-solid fa-brain text-white text-lg" aria-hidden="true"></i>
            </div>
            <span class="text-xl font-bold text-gray-900 dark:text-white">
                Promptly<span class="text-primary">.ai</span>
            </span>
        </a>

        <nav class="hidden md:flex absolute left-1/2 -translate-x-1/2" aria-label="Main navigation">
            <ul class="flex gap-8 text-gray-700 dark:text-gray-300 font-medium list-none p-0 m-0">
                <li><a href="../index.php#features" class="hover:text-primary transition-colors duration-300 flex items-center gap-2"><i class="fa-solid fa-star text-accent w-4" aria-hidden="true"></i> Features</a></li>
                <li><a href="#prompt-library" class="hover:text-primary transition-colors duration-300 flex items-center gap-2"><i class="fa-solid fa-lightbulb text-accent w-4" aria-hidden="true"></i> Prompts</a></li>
                <li><a href="../index.php#about" class="hover:text-primary transition-colors duration-300 flex items-center gap-2"><i class="fa-solid fa-circle-info text-accent w-4" aria-hidden="true"></i> About</a></li>
                <li><a href="../index.php#contact" class="hover:text-primary transition-colors duration-300 flex items-center gap-2"><i class="fa-solid fa-envelope text-accent w-4" aria-hidden="true"></i> Contact</a></li>
            </ul>
        </nav>

        <div class="flex items-center gap-4">
            <button id="theme-toggle" class="theme-toggle hidden md:flex w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-300" aria-label="Toggle dark/light theme">
                <i class="fa-solid fa-moon dark:hidden" aria-hidden="true"></i>
                <i class="fa-solid fa-sun hidden dark:block" aria-hidden="true"></i>
            </button>
            <a href="#prompt-library" class="hidden md:flex bg-primary px-5 py-2.5 rounded-lg text-white font-semibold items-center gap-2 hover-lift shadow-md transition-all duration-300" role="button"><i class="fa-solid fa-rocket" aria-hidden="true"></i> Explore</a>
            
            <button id="menu-btn" class="md:hidden w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-600 dark:text-gray-400 transition-colors duration-300" aria-label="Toggle menu" aria-controls="mobile-menu" aria-expanded="false">
                <i class="fa-solid fa-bars" id="menu-icon" aria-hidden="true"></i>
            </button>
        </div>
    </div>
    
    <nav id="mobile-menu" class="mobile-menu hidden md:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 rounded-b-2xl overflow-hidden px-6 transition-all duration-400" aria-label="Mobile navigation">
        <ul class="flex flex-col gap-4 list-none p-0 m-0">
            <li><a href="../index.php#features" class="flex items-center gap-3 py-2 text-gray-700 dark:text-gray-300 hover:text-primary transition-colors duration-300"><i class="fa-solid fa-star text-accent w-5" aria-hidden="true"></i> Features</a></li>
            <li><a href="#prompt-library" class="flex items-center gap-3 py-2 text-gray-700 dark:text-gray-300 hover:text-primary transition-colors duration-300"><i class="fa-solid fa-lightbulb text-accent w-5" aria-hidden="true"></i> Prompts</a></li>
            <li><a href="../index.php#about" class="flex items-center gap-3 py-2 text-gray-700 dark:text-gray-300 hover:text-primary transition-colors duration-300"><i class="fa-solid fa-circle-info text-accent w-5" aria-hidden="true"></i> About</a></li>
            <li><a href="../index.php#contact" class="flex items-center gap-3 py-2 text-gray-700 dark:text-gray-300 hover:text-primary transition-colors duration-300"><i class="fa-solid fa-envelope text-accent w-5" aria-hidden="true"></i> Contact</a></li>
            
            <li class="pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-col gap-3">
                <button id="mobile-theme-toggle" class="theme-toggle w-full py-3 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center gap-2 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-300" aria-label="Toggle dark/light theme">
                    <i class="fa-solid fa-moon dark:hidden" aria-hidden="true"></i>
                    <i class="fa-solid fa-sun hidden dark:block" aria-hidden="true"></i>
                    <span>Toggle Theme</span>
                </button>
                <a href="#prompt-library" class="bg-primary w-full py-3 rounded-lg text-white font-semibold flex items-center justify-center gap-2 hover-lift transition-all duration-300" role="button"><i class="fa-solid fa-rocket" aria-hidden="true"></i> Explore Prompts</a>
            </li>
        </ul>
    </nav>
</header>

<main role="main">
    <section id="prompt-library" class="py-20 px-4 sm:px-12 lg:px-24 pt-12" aria-labelledby="library-heading">

        <header class="text-center mb-16">
            <h1 id="library-heading" class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 dark:text-white leading-tight transition-colors duration-300">
                The Full <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-pink-600 dark:from-indigo-400 dark:to-pink-400">Prompt</span> Vault 💡
            </h1>
            <p class="mt-4 text-gray-600 dark:text-gray-400 max-w-3xl mx-auto text-xl transition-colors duration-300">
                Search and find the perfect command to power your next AI creation.
            </p>
        </header>

        <div class="max-w-4xl mx-auto mb-12" role="search">
            <div class="flex gap-4 items-center mb-6 p-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                
                <button onclick="window.history.back()" title="Go back to the previous page" 
                    class="flex items-center justify-center w-12 h-12 text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 rounded-full 
                            hover:bg-primary hover:text-white transition-all duration-300 flex-shrink-0 cursor-pointer"
                            aria-label="Go back">
                    <i class="fa-solid fa-arrow-left text-xl" aria-hidden="true"></i>
                </button>
                
                <div class="w-full relative">
                    <i class="fa-solid fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400" aria-hidden="true"></i>
                    <input type="search" id="prompt-search" placeholder="Search prompts or keywords..."
                            class="w-full py-3 pl-12 pr-4 bg-gray-100 dark:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-primary focus:border-primary text-gray-900 dark:text-white transition duration-300"
                            aria-label="Search prompt library">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto" id="prompt-list" role="list">

            <?php if (!empty($prompts)): ?>
                <?php $i = 0; ?>
                <?php foreach ($prompts as $prompt): ?>
                    <?php
                        // PHP Data preparation for the card, ensuring security
                        $prompt_id = esc_html($prompt['id'] ?? 0);
                        $prompt_title = esc_html($prompt['title'] ?? 'Untitled Prompt');
                        $prompt_description = esc_html($prompt['description'] ?? 'No description provided.');
                        
                        // Use json_encode for safe JS string passing - this is the key to fixing the copy bug
                        $prompt_text_escaped = htmlspecialchars(json_encode($prompt['prompt_text'] ?? '', JSON_HEX_APOS | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                        $prompt_title_escaped = htmlspecialchars(json_encode($prompt_title, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
                        
                        // Dynamic styling logic
                        $base_color = $dynamic_colors[$i % count($dynamic_colors)]; 
                        $accent_color_600 = "{$base_color}-600";
                        $accent_color_500 = "{$base_color}-500";
                        
                        $title_hover_class = "hover:text-{$accent_color_600}";
                        $copy_button_class = "bg-{$accent_color_600} hover:bg-{$accent_color_500} shadow-md shadow-{$accent_color_500}/40";
                        $share_button_class = "text-{$accent_color_600} hover:text-white hover:bg-{$accent_color_600} border-{$accent_color_600}";
                        
                        $i++;
                    ?>
                    
                    <article class="prompt-card flex flex-col hover-lift overflow-hidden" 
                            role="listitem" 
                            itemscope itemtype="https://schema.org/CreativeWork"
                            data-category="<?php echo esc_html($prompt['category_slug'] ?? 'general'); ?>"
                            data-title="<?php echo strtolower($prompt_title); ?>"
                            data-id="<?php echo $prompt_id; ?>"> 
                        <div class="tools flex-shrink-0">
                            <div class="circle"><span class="red box"></span></div>
                            <div class="circle"><span class="yellow box"></span></div>
                            <div class="circle"><span class="green box"></span></div>
                        </div>

                        <figure class="relative w-full h-40 md:h-48 overflow-hidden flex-shrink-0">
                            <img src="<?php echo esc_html($prompt['image'] ? '../assets/images/' . $prompt['image'] : 'https://via.placeholder.com/600x400?text=' . urlencode(ucfirst($prompt['category_slug'] ?? 'AI') . '+Prompt')); ?>"
                                alt="Image representing the '<?php echo $prompt_title; ?>' prompt"
                                loading="lazy"
                                class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900/50 to-transparent"></div>

                            <span class="absolute top-3 left-3 bg-white/80 dark:bg-darkbg/70 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-semibold text-gray-800 dark:text-gray-200">
                                <i class="fa-solid fa-code-branch mr-1 text-<?php echo $accent_color_500; ?>" aria-hidden="true"></i>
                                <?php echo esc_html(ucfirst($prompt['category_slug'] ?? 'AI')); ?>
                            </span>
                        </figure>

                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2 line-clamp-2 
                                             transition-colors duration-300 <?php echo $title_hover_class; ?>" 
                                itemprop="headline">
                                <?php echo $prompt_title; ?>
                            </h3>
                            
                            <p class="text-gray-700 dark:text-gray-300 text-sm flex-1 mb-4 line-clamp-3" itemprop="description">
                                <?php echo $prompt_description; ?>
                            </p>

                            <div class="mt-auto flex justify-center items-center pt-4 border-t border-gray-200 dark:border-gray-700 gap-2">
                                
                                <button onclick="openShareMenu(<?php echo $prompt_title_escaped; ?>, '<?php echo $current_page_url; ?>')"
                                         class="share-button flex items-center justify-center w-10 h-10 rounded-full text-base font-bold transition duration-300 ease-in-out hover-lift 
                                                 border border-2 
                                                 bg-white dark:bg-gray-800 
                                                 <?php echo $share_button_class; ?>"
                                                 aria-label="Share this prompt: <?php echo $prompt_title; ?>">
                                    <i class="fa-solid fa-share" aria-hidden="true"></i>
                                </button>
                                
                                <button onclick="copyPrompt(<?php echo $prompt_text_escaped; ?>, '<?php echo $prompt_id; ?>')"
                                         class="copy-button px-5 py-2.5 rounded-full text-white text-sm font-bold transition duration-300 ease-in-out hover-lift 
                                                 <?php echo $copy_button_class; ?>"
                                                 aria-label="Copy prompt text for <?php echo $prompt_title; ?>">
                                    <i class="fa-solid fa-copy mr-1" aria-hidden="true"></i> Copy Prompt
                                </button>
                            </div>
                        </div>
                    </article>

                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center text-gray-500 dark:text-gray-400 col-span-full py-10 transition-colors duration-300">
                    <i class="fa-solid fa-triangle-exclamation mr-2" aria-hidden="true"></i> No prompts are currently available. Please check the database connection.
                </p>
            <?php endif; ?>

        </div>
    </section>
</main>

<div id="share-modal-backdrop" class="fixed inset-0 bg-gray-900/50 dark:bg-gray-900/80 z-[1010] hidden opacity-0 items-center justify-center transition-opacity duration-300" onclick="closeShareMenu()" role="dialog" aria-modal="true" aria-labelledby="share-title">
    <div id="share-modal" class="bg-white dark:bg-gray-900 rounded-xl shadow-2xl p-6 w-11/12 max-w-sm transform scale-90 transition-all duration-300" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h2 id="share-title" class="text-xl font-bold text-gray-900 dark:text-white">Share Prompt</h2>
            <button onclick="closeShareMenu()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" aria-label="Close share dialog">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>
        <p id="share-prompt-title" class="text-sm text-gray-600 dark:text-gray-400 mb-4 truncate italic"></p>
        
        <div id="share-options" class="grid grid-cols-4 gap-4">
        </div>
    </div>
</div>
<?php 
if (file_exists('../includes/footer.php')) {
    require_once '../includes/footer.php';
} else {
    echo '<footer class="py-12 text-center text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700 mt-20" role="contentinfo">
            <div class="max-w-6xl mx-auto px-4"><p>&copy; ' . date('Y') . ' Promptly.ai. All rights reserved.</p></div>
          </footer>';
}
?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 4. GLOBAL UTILITY: TOAST NOTIFICATION ---
        function showToastNotification(message, isSuccess = true) {
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.textContent = message;
            
            toast.className = `
                fixed bottom-5 right-5 p-4 rounded-lg shadow-xl text-white font-medium z-[1020]
                transition-all duration-500 ease-out transform translate-x-full opacity-0
                ${isSuccess ? 'bg-emerald-600' : 'bg-red-600'}
            `;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-0', 'opacity-100');
                toast.classList.add('translate-x-full', 'opacity-0');
                // Allow time for exit transition before removal
                setTimeout(() => toast.remove(), 500); 
            }, 3000);
        }
        window.showToastNotification = showToastNotification; // Expose globally

        // --- 5. GLOBAL UTILITY: COPY PROMPT (FIXED UNESCAPING) ---
        window.copyPrompt = function(promptText, promptId) {
            // FIX: The promptText is passed as a JSON string literal. Use JSON.parse to safely unescape.
            let finalPromptText = '';
            try {
                // IMPORTANT: Since promptText is already a string inside the JS environment (passed from the HTML attribute),
                // we only need to parse it if it contains embedded escaped characters (which it does via json_encode).
                finalPromptText = JSON.parse(promptText);

                // Fallback for non-JSON string/simple quotes
            } catch (e) {
                console.warn("JSON.parse failed on promptText, falling back to original string. This is usually due to bad escaping.");
                finalPromptText = promptText;
            }

            navigator.clipboard.writeText(finalPromptText).then(() => {
                showToastNotification(`Prompt #${promptId} copied successfully!`, true);
            }).catch(err => {
                showToastNotification(`Failed to copy prompt.`, false);
                console.error('Could not copy text: ', err);
            });
        }
        
        // --- 6. PROMPT FILTERING LOGIC (NO CHANGES NEEDED) ---
        const searchInput = document.getElementById('prompt-search');
        const cards = Array.from(document.querySelectorAll('.prompt-card'));

        const applyFiltersAndSort = () => {
            const searchTerm = searchInput.value.toLowerCase().trim();
            
            cards.forEach(card => {
                if (!card.matches('article')) return; 

                const title = card.dataset.title ? card.dataset.title.toLowerCase() : '';
                const category = card.dataset.category ? card.dataset.category.toLowerCase() : '';
                const descriptionElement = card.querySelector('[itemprop="description"]');
                const description = descriptionElement ? descriptionElement.textContent.toLowerCase() : '';
                
                const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm) || category.includes(searchTerm);

                if (matchesSearch) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        };

        if (searchInput) {
            searchInput.addEventListener('input', applyFiltersAndSort);
            applyFiltersAndSort(); // Initial execution
        }
        
        // --- 7. SHARE MENU LOGIC (NEW/FIXED) ---
        const shareModalBackdrop = document.getElementById('share-modal-backdrop');
        const shareModal = document.getElementById('share-modal');
        const shareOptionsContainer = document.getElementById('share-options');
        const sharePromptTitle = document.getElementById('share-prompt-title');

        window.closeShareMenu = function() {
            // Start exit transition
            shareModalBackdrop.classList.remove('opacity-100');
            shareModal.classList.remove('scale-100');
            
            // Add initial classes for final state after transition
            shareModalBackdrop.classList.add('opacity-0');
            shareModal.classList.add('scale-90');

            // Hide after transition ends (300ms)
            setTimeout(() => { 
                shareModalBackdrop.classList.remove('flex');
                shareModalBackdrop.classList.add('hidden');
            }, 300);
        }

        window.openShareMenu = function(promptTitleJson, pageUrl) {
            // FIX: Unescape the JSON string literal for the title
            let promptTitle = '';
            try {
                promptTitle = JSON.parse(promptTitleJson);
            } catch (e) {
                promptTitle = promptTitleJson;
            }
            
            const shareText = encodeURIComponent(`Check out this amazing AI prompt: "${promptTitle}" from Promptly.ai!`);
            const shareUrl = encodeURIComponent(pageUrl);
            const rawUrl = pageUrl;
            
            sharePromptTitle.textContent = `Prompt: "${promptTitle}"`;
            shareOptionsContainer.innerHTML = ''; // Clear previous options
            
            const shareButtons = [
                {
                    name: 'Twitter (X)',
                    icon: 'fa-x-twitter',
                    color: 'bg-black text-white dark:bg-white dark:text-black',
                    url: `https://twitter.com/intent/tweet?text=${shareText}&url=${shareUrl}`
                },
                {
                    name: 'Facebook',
                    icon: 'fa-facebook-f',
                    color: 'bg-[#3b5998]',
                    url: `https://www.facebook.com/sharer/sharer.php?u=${shareUrl}`
                },
                {
                    name: 'WhatsApp',
                    icon: 'fa-whatsapp',
                    color: 'bg-[#25D366]',
                    url: `https://wa.me/?text=${shareText} ${shareUrl}`
                },
                {
                    name: 'Copy Link',
                    icon: 'fa-link',
                    color: 'bg-gray-500 hover:bg-gray-600',
                    action: () => {
                        navigator.clipboard.writeText(rawUrl).then(() => {
                            showToastNotification('Link copied to clipboard!', true);
                            closeShareMenu();
                        }).catch(err => {
                            showToastNotification('Failed to copy link.', false);
                            console.error('Could not copy link: ', err);
                        });
                    }
                }
            ];

            shareButtons.forEach(button => {
                const item = document.createElement('div');
                item.className = 'flex flex-col items-center group cursor-pointer';

                const btn = document.createElement('button');
                // Ensure a smooth transition on all colors/properties
                btn.className = `${button.color} text-white w-12 h-12 rounded-full flex items-center justify-center text-lg transition-all duration-200 group-hover:scale-110 shadow-md`;
                
                // Special case for copy link button: It uses 'fa-link' not 'fa-brands'
                const iconClass = button.name === 'Copy Link' ? `fa-solid ${button.icon}` : `fa-brands ${button.icon}`;
                btn.innerHTML = `<i class="${iconClass}" aria-hidden="true"></i>`;
                btn.setAttribute('aria-label', `Share on ${button.name}`);

                if (button.action) {
                    btn.onclick = button.action;
                } else {
                    btn.onclick = () => {
                        // Open in a new smaller window
                        window.open(button.url, 'ShareWindow', 'width=600,height=450');
                        closeShareMenu();
                    };
                }

                const nameText = document.createElement('p');
                nameText.className = 'text-xs mt-2 text-gray-700 dark:text-gray-300 text-center group-hover:text-primary transition-colors';
                nameText.textContent = button.name.split(' ')[0]; // Use first word for conciseness

                item.appendChild(btn);
                item.appendChild(nameText);
                shareOptionsContainer.appendChild(item);
            });

            // Show the modal
            shareModalBackdrop.classList.remove('hidden', 'opacity-0');
            shareModalBackdrop.classList.add('flex', 'opacity-100');
            setTimeout(() => {
                // Start enter transition (scale)
                shareModal.classList.remove('scale-90');
                shareModal.classList.add('scale-100');
            }, 10);
        }


        // --- UI/UX LOGIC (Theme, Menu, Scroll) ---
        const themeToggles = document.querySelectorAll('.theme-toggle');
        
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            const isDark = document.documentElement.classList.contains('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        const storedTheme = localStorage.getItem('theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
            document.documentElement.classList.add('dark');
        }

        themeToggles.forEach(toggle => {
            toggle.addEventListener('click', toggleTheme);
        });

        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        
        if (menuBtn && mobileMenu && menuIcon) {
            function toggleMobileMenu() {
                const isActive = mobileMenu.classList.toggle('active');
                
                if (isActive) {
                    mobileMenu.classList.remove('hidden');
                    menuIcon.classList.replace('fa-bars', 'fa-xmark');
                } else {
                    menuIcon.classList.replace('fa-xmark', 'fa-bars');
                    setTimeout(() => { mobileMenu.classList.add('hidden'); }, 400); 
                }
                
                menuBtn.setAttribute('aria-expanded', isActive.toString());
            }
            
            menuBtn.addEventListener('click', toggleMobileMenu);
            
            const mobileMenuLinks = mobileMenu.querySelectorAll('a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (mobileMenu.classList.contains('active')) { toggleMobileMenu(); }
                });
            });
        }

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '') { return; }
                
                e.preventDefault();
                
                const target = document.querySelector(href);
                if (target) {
                    const header = document.querySelector('header');
                    const headerHeight = header ? header.offsetHeight + 24 : 0; 
                    window.scrollTo({
                        top: target.offsetTop - headerHeight,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
</body>
</html>