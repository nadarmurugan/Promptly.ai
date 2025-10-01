<?php
// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include DB and helper functions
// ASSUMPTION: 'includes/db.php' and 'includes/functions.php' exist and are functional.
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch all prompts
// ASSUMPTION: getPrompts() returns an array of prompts or an empty array.
$prompts = getPrompts();
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promptly.ai - Explore AI Prompts</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js" defer></script>
    
    <script>
      tailwind.config = {
        darkMode: 'class', // Ensure dark mode is configured
        theme: {
          extend: {
            colors: {
              primary: '#6366F1', // Indigo
              accent: '#FACC15',  // Amber
              darkbg: '#0F172A',  // Dark Slate
              cardbg: '#111827',  // Dark Gray
            },
            fontFamily: {
              sans: ['Poppins', 'sans-serif'],
            },
          },
        },
      }
    </script>

    <style>
      /* Font reference (though Poppins is loaded via link) */
      .font-poppins { font-family: 'Poppins', sans-serif; }

      /* Base Animations */
      @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
      @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
      .animate-fadeInDown { animation: fadeInDown 0.8s ease-out 0s 1 forwards; }
      .animate-fadeInUp { animation: fadeInUp 0.7s cubic-bezier(0.17, 0.84, 0.44, 1) forwards; opacity: 0; }
      
      /* Staggered Delay for Cards (Features) */
      .animation-delay-300 { animation-delay: 0.3s !important; }
      .animation-delay-600 { animation-delay: 0.6s !important; }
      .animation-delay-900 { animation-delay: 0.9s !important; }
      .animation-delay-1200 { animation-delay: 1.2s !important; }
      .animation-delay-1500 { animation-delay: 1.5s !important; }
      
      /* Blob Animation (Background Deco) */
      @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30vw, -20vh) scale(1.1); }
        66% { transform: translate(-20vw, 10vh) scale(0.95); }
      }
      .animate-blob { animation: blob 20s infinite alternate; }
      .animation-delay-2000 { animation-delay: 2s !important; }
      .animation-delay-4000 { animation-delay: 4s !important; }
      
      /* Pulse Slow (For Headers/CTA) */
      @keyframes pulse-slow {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
      }
      .animate-pulse-slow {
        animation: pulse-slow 6s cubic-bezier(0.4,0,0.6,1) infinite;
      }

      /* Bounce Slow (For About Icon) */
      @keyframes bounce-slow {
        0%, 100% {
          transform: translateY(-5%);
          filter: drop-shadow(0 4px 6px rgba(139, 92, 246, 0.5));
        }
        50% {
          transform: translateY(5%);
          filter: drop-shadow(0 4px 6px rgba(236, 72, 153, 0.5));
        }
      }
      .animate-bounce-slow {
        animation: bounce-slow 4s ease-in-out infinite;
      }

      /* Contact Form Delay Fixes */
      .delay-200 { animation-delay: 0.2s !important; }
      .delay-400 { animation-delay: 0.4s !important; }

      /* Map container styling */
      #map { min-height: 24rem; }

      /* Leaflet customizations */
      .leaflet-container {
        border-radius: 0 0 1.5rem 0;
        transition: transform 0.6s ease;
        background: #f8fafc;
      }
      .dark .leaflet-container {
        background: #1e293b;
      }

    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white font-sans">

    <?php include 'includes/header.php'; ?>


<section id="features" class="relative py-20 px-6 sm:py-28 sm:px-12 lg:px-16 xl:px-24 bg-white dark:bg-gray-950 transition-colors duration-500 overflow-hidden" aria-labelledby="section-heading-promptly-ai">
    <div class="absolute inset-0 z-0" aria-hidden="true">
        <div class="absolute top-0 left-1/4 w-[400px] h-[400px] sm:w-[500px] sm:h-[500px] bg-indigo-600/10 rounded-full filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-0 right-1/4 w-[350px] h-[350px] sm:w-[450px] sm:h-[450px] bg-yellow-400/10 rounded-full filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
    </div>

    <header class="text-center mb-16 md:mb-24 relative z-10 animate-fadeInDown">
        <h3 class="text-xs font-bold uppercase tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-pink-600 to-indigo-600 dark:from-pink-400 dark:to-indigo-400 mb-3 transition-colors duration-300">
            AI-Powered Prompt Marketplace
        </h3>
    
        <h2 id="section-heading-promptly-ai" class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight pt-12 md:pt-0">
            <span class="inline-block bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-cyan-600 dark:from-indigo-400 dark:to-cyan-400">
                Why Choose
            </span>
            <span class="inline-block bg-clip-text text-transparent bg-gradient-to-r from-amber-600 to-red-600 dark:from-amber-400 dark:to-red-500">
                Promptly.ai?
            </span>
        </h2>
        <p class="mt-4 text-lg sm:text-xl text-gray-600 dark:text-gray-400 max-w-4xl mx-auto leading-normal transition-colors duration-300">
            Harness the power of AI for your creative projects. Explore, copy, and customize prompts seamlessly with our <strong>smart, production-ready tools</strong>.
        </p>
    </header>

    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10 xl:gap-12 relative z-10">

        <li class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-8 sm:p-10 shadow-xl border border-gray-300 dark:border-gray-700/50 hover:border-indigo-500/80 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-indigo-500/20 hover:shadow-gray-400/20 animate-fadeInUp">
            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-indigo-600 rounded-full p-4 text-white shadow-xl group-hover:bg-indigo-500 transition-colors duration-500" aria-hidden="true">
                <i class="fa-solid fa-book-open fa-2x"></i>
            </div>
            <h4 class="mt-10 text-2xl font-bold text-gray-900 dark:text-white text-center">Browse Prompts</h4>
            <p class="mt-4 text-indigo-700 dark:text-indigo-300 text-center leading-relaxed tracking-wide transition-colors duration-300">
                Explore AI prompts with beautiful images, clear titles, and detailed descriptions to spark your next big idea.
            </p>
            <span class="absolute bottom-4 left-1/2 w-16 h-1 rounded-full bg-indigo-500/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -translate-x-1/2" aria-hidden="true"></span>
        </li>

        <li class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-8 sm:p-10 shadow-xl border border-gray-300 dark:border-gray-700/50 hover:border-pink-500/80 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-pink-500/20 hover:shadow-gray-400/20 animate-fadeInUp animation-delay-300">
            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-pink-600 rounded-full p-4 text-white shadow-xl group-hover:bg-pink-500 transition-colors duration-500" aria-hidden="true">
                <i class="fa-solid fa-copy fa-2x"></i>
            </div>
            <h4 class="mt-10 text-2xl font-bold text-gray-900 dark:text-white text-center">Copy & Use</h4>
            <p class="mt-4 text-pink-700 dark:text-pink-300 text-center leading-relaxed tracking-wide transition-colors duration-300">
                Copy carefully crafted and tested prompts instantly, allowing you to unleash the power of AI tools without any friction.
            </p>
            <span class="absolute bottom-4 left-1/2 w-16 h-1 rounded-full bg-pink-500/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -translate-x-1/2" aria-hidden="true"></span>
        </li>

        <li class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-8 sm:p-10 shadow-xl border border-gray-300 dark:border-gray-700/50 hover:border-purple-500/80 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-purple-500/20 hover:shadow-gray-400/20 animate-fadeInUp animation-delay-600">
            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-purple-600 rounded-full p-4 text-white shadow-xl group-hover:bg-purple-500 transition-colors duration-500" aria-hidden="true">
                <i class="fa-solid fa-search fa-2x"></i>
            </div>
            <h4 class="mt-10 text-2xl font-bold text-gray-900 dark:text-white text-center">Smart Search</h4>
            <p class="mt-4 text-purple-700 dark:text-purple-300 text-center leading-relaxed tracking-wide transition-colors duration-300">
                Instantly find the perfect prompt with smart search and advanced filters categorized by AI model, use case, and complexity.
            </p>
            <span class="absolute bottom-4 left-1/2 w-16 h-1 rounded-full bg-purple-500/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -translate-x-1/2" aria-hidden="true"></span>
        </li>

        <li class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-8 sm:p-10 shadow-xl border border-gray-300 dark:border-gray-700/50 hover:border-emerald-500/80 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-emerald-500/20 hover:shadow-gray-400/20 animate-fadeInUp animation-delay-900">
            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-emerald-600 rounded-full p-4 text-white shadow-xl group-hover:bg-emerald-500 transition-colors duration-500" aria-hidden="true">
                <i class="fa-solid fa-sliders fa-2x"></i>
            </div>
            <h4 class="mt-10 text-2xl font-bold text-gray-900 dark:text-white text-center">Easy Customization</h4>
            <p class="mt-4 text-emerald-700 dark:text-emerald-300 text-center leading-relaxed tracking-wide transition-colors duration-300">
                Tailor prompts easily using our intuitive customization tools. Adjust the tone, length, style, and parameters instantly.
            </p>
            <span class="absolute bottom-4 left-1/2 w-16 h-1 rounded-full bg-emerald-500/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -translate-x-1/2" aria-hidden="true"></span>
        </li>

        <li class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-8 sm:p-10 shadow-xl border border-gray-300 dark:border-gray-700/50 hover:border-cyan-500/80 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-cyan-500/20 hover:shadow-gray-400/20 animate-fadeInUp animation-delay-1200">
            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-cyan-600 rounded-full p-4 text-white shadow-xl group-hover:bg-cyan-500 transition-colors duration-500" aria-hidden="true">
                <i class="fa-solid fa-chart-line fa-2x"></i>
            </div>
            <h4 class="mt-10 text-2xl font-bold text-gray-900 dark:text-white text-center">Performance Analytics</h4>
            <p class="mt-4 text-cyan-700 dark:text-cyan-300 text-center leading-relaxed tracking-wide transition-colors duration-300">
                Track the effectiveness of your saved prompts with detailed analytics on success rates and community user feedback.
            </p>
            <span class="absolute bottom-4 left-1/2 w-16 h-1 rounded-full bg-cyan-500/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -translate-x-1/2" aria-hidden="true"></span>
        </li>

        <li class="group relative bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-8 sm:p-10 shadow-xl border border-gray-300 dark:border-gray-700/50 hover:border-amber-500/80 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl dark:hover:shadow-amber-500/20 hover:shadow-gray-400/20 animate-fadeInUp animation-delay-1500">
            <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-amber-500 rounded-full p-4 text-white shadow-xl group-hover:bg-amber-400 transition-colors duration-500" aria-hidden="true">
                <i class="fa-solid fa-envelope fa-2x"></i>
            </div>
            <h4 class="mt-10 text-2xl font-bold text-gray-900 dark:text-white text-center">Support & Feedback</h4>
            <p class="mt-4 text-amber-700 dark:text-amber-300 text-center leading-relaxed tracking-wide transition-colors duration-300">
                Reach out via our contact form to submit suggestions, ask questions, or report bugs. Your feedback helps us grow!
            </p>
            <span class="absolute bottom-4 left-1/2 w-16 h-1 rounded-full bg-amber-500/50 opacity-0 group-hover:opacity-100 transition-opacity duration-500 -translate-x-1/2" aria-hidden="true"></span>
        </li>

    </ul>
</section>

<section id="prompts" class="relative py-24 px-4 sm:px-12 lg:px-24 bg-gradient-to-br from-gray-100 via-white to-gray-100 dark:from-gray-900 dark:via-gray-950 dark:to-gray-900 overflow-hidden transition-colors duration-500">

    <header class="text-center mb-16">
        <h3 class="text-xs font-bold uppercase tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-pink-600 dark:from-indigo-400 dark:to-pink-400 mb-3 animate-pulse-slow transition-colors duration-300">
            Unleash Your Creativity
        </h3>
        <h2 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 dark:text-white leading-tight transition-colors duration-300">
            Curated <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-pink-600 dark:from-indigo-500 dark:to-pink-500">AI Prompts</span> Library
        </h2>
        <p class="mt-4 text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-lg transition-colors duration-300">
            Ignite your imagination with hand-picked prompts across art, code, writing, and more.
        </p>
    </header>

    <main class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" role="list">

        <?php if (!empty($prompts)): ?>
            <?php $count = 0; ?>
            <?php foreach ($prompts as $prompt): ?>
                <?php $count++; ?>
                <?php if ($count > 6) break; // Limit to 6 prompts ?>

                <?php
                    $is_featured = $count === 1;

                    // Featured prompt spans 3 columns on large screens
                    $card_classes = $is_featured
                        ? "lg:col-span-3 flex flex-col lg:flex-row overflow-hidden rounded-3xl shadow-xl transition-all duration-500 hover:scale-[1.02] bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-800 dark:to-gray-900 border border-gray-300/50 dark:border-gray-700/50"
                        // Standard prompt
                        : "flex flex-col overflow-hidden rounded-2xl shadow-md transition-all duration-500 hover:scale-[1.02] bg-white/80 dark:bg-gray-800/70 backdrop-blur-sm border border-gray-200/70 dark:border-gray-700/70 hover:shadow-lg hover:shadow-indigo-500/15 dark:hover:shadow-indigo-500/25";
                ?>

                <article class="<?php echo $card_classes; ?>" role="listitem" itemscope itemtype="https://schema.org/CreativeWork">

                    <?php if($is_featured): ?>
                        <figure class="lg:w-1/2 relative h-72 lg:h-auto overflow-hidden group">
                            <img src="assets/images/<?php echo htmlspecialchars($prompt['image']); ?>"
                                alt="<?php echo htmlspecialchars($prompt['title']); ?>"
                                class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-110 brightness-90">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/60 to-pink-900/40 dark:from-indigo-900/60 dark:to-pink-900/40"></div>
                            <figcaption class="absolute top-4 left-4 text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full bg-pink-600 text-white shadow-lg shadow-pink-500/50 transform group-hover:scale-105 transition duration-300">
                                Featured Prompt
                            </figcaption>
                        </figure>

                        <div class="lg:w-1/2 p-8 flex flex-col justify-center">
                            <h3 class="text-3xl lg:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-wide transition-colors duration-300" itemprop="headline">
                                <?php echo htmlspecialchars($prompt['title']); ?>
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-6 text-lg leading-relaxed transition-colors duration-300" itemprop="description">
                                <?php echo htmlspecialchars($prompt['description']); ?>
                            </p>
                            <button onclick="copyPrompt('<?php echo addslashes(htmlentities($prompt['prompt_text'], ENT_QUOTES, 'UTF-8')); ?>')"
                                    class="w-fit px-8 py-3 bg-gradient-to-r from-pink-600 to-indigo-600 hover:from-pink-500 hover:to-indigo-500 rounded-full text-white font-bold transition duration-300 shadow-lg shadow-pink-500/30 transform hover:-translate-y-0.5 flex items-center gap-2" itemprop="action">
                                <i class="fa-solid fa-wand-magic-sparkles"></i> Copy & Generate
                            </button>
                        </div>

                    <?php else: ?>
                        <figure class="relative w-full h-64 sm:h-auto sm:aspect-video overflow-hidden rounded-t-2xl">
                            <img src="assets/images/<?php echo htmlspecialchars($prompt['image']); ?>"
                                alt="<?php echo htmlspecialchars($prompt['title']); ?>"
                                class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-white/20 to-transparent dark:from-gray-800/80 dark:to-transparent"></div>
                        </figure>

                        <div class="p-4 flex flex-col justify-between h-50">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 transition-colors duration-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" itemprop="headline">
                                <?php echo htmlspecialchars($prompt['title']); ?>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm flex-1 mb-4 line-clamp-3 transition-colors duration-300" itemprop="description">
                                <?php echo htmlspecialchars($prompt['description']); ?>
                            </p>
                            <button onclick="copyPrompt('<?php echo addslashes(htmlentities($prompt['prompt_text'], ENT_QUOTES, 'UTF-8')); ?>')"
                                    class="w-full px-4 py-3 bg-indigo-600/90 hover:bg-indigo-500/90 dark:bg-indigo-700/70 dark:hover:bg-indigo-600/90 rounded-xl text-white font-semibold transition duration-300 shadow-sm hover:shadow-indigo-500/30" itemprop="action">
                                <i class="fa-solid fa-copy mr-2"></i> Copy Prompt
                            </button>
                        </div>
                    <?php endif; ?>

                </article>

            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-gray-500 dark:text-gray-400 col-span-full py-10 transition-colors duration-300">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> No inspiring prompts available right now. Check back soon!
            </p>
        <?php endif; ?>

    </main>

    <div class="text-center mt-12 md:mt-20">
        <a href="pages/prompts.php"
           class="inline-block px-12 py-4 text-lg font-bold text-white rounded-full transition-all duration-500 transform hover:scale-[1.03] shadow-2xl"
           style="background: linear-gradient(90deg, #6366F1 0%, #EC4899 100%);"
           rel="nofollow noopener" aria-label="Explore all AI prompts">
            Explore All Prompts <i class="fa-solid fa-arrow-right ml-3"></i>
        </a>
    </div>

</section>

<section id="about" class="relative py-24 sm:py-32 px-6 overflow-hidden bg-white dark:bg-gray-950 transition-colors duration-700">
    <div aria-hidden="true" class="absolute inset-0 opacity-10 dark:opacity-5">
        <svg class="h-full w-full" fill="none" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Background decorative pattern">
            <defs>
                <pattern id="pattern-about" x="0" y="0" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M-3 13L15 -5M-5 5L13 -13M2 2L-10 14" stroke="currentColor" stroke-width="0.5" class="text-gray-300 dark:text-gray-800" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#pattern-about)" />
        </svg>
    </div>

    <div class="max-w-7xl mx-auto relative z-10">
        <header class="text-center mb-16 relative" role="banner">
            <div class="absolute top-0 right-0 lg:right-4 p-2">
            </div>
            <h3 class="text-xs font-bold uppercase tracking-widest bg-gradient-to-r from-pink-500 via-indigo-600 to-pink-500 text-transparent bg-clip-text dark:from-pink-400 dark:to-indigo-500 mb-3 transition-colors duration-500">
                The Engine of Imagination
            </h3>
            <h1 class="text-5xl sm:text-6xl font-extrabold text-gray-900 dark:text-white leading-tight tracking-tight transition-colors duration-500">
                What is <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 via-pink-600 to-indigo-500 dark:from-indigo-400 dark:to-pink-500">Promptly.ai</span>?
            </h1>
        </header>

        <main class="mt-16 grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-20 items-center" role="main">
            <article class="order-2 lg:order-1 text-gray-600 dark:text-gray-300 text-lg leading-relaxed space-y-6">
                <p>
                    <strong class="text-pink-600 dark:text-pink-400">Promptly.ai</strong> is your definitive library for unlocking the potential of generative AI. We cut through the noise, offering a beautifully curated, categorized, and tested collection of prompts spanning <em class="text-indigo-600 dark:text-indigo-400 font-semibold">Image Generation, Writing, Code, Music, and more.</em>
                </p>
                <p>
                    Our mission is simple: <strong class="text-pink-600 dark:text-pink-400">to transform vague ideas into precise, powerful AI commands.</strong> Spend less time guessing and more time creating breathtaking digital art, compelling copy, or functional code.
                </p>
                
                <section class="p-6 rounded-3xl border border-indigo-500/40 bg-indigo-50 dark:bg-indigo-900/20 shadow-lg shadow-pink-600/10 dark:shadow-indigo-900/50">
                    <h2 class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mb-3 flex items-center gap-3" aria-label="Optimized for Speed and Clarity">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-500 animate-bounce-slow" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Optimized for Speed & Clarity
                    </h2>
                    <p class="text-sm text-gray-700 dark:text-indigo-100">
                        Built with <code class="bg-indigo-200/60 dark:bg-indigo-800/60 rounded px-1 py-0.5 font-mono text-gray-900 dark:text-indigo-100">Core PHP</code> for blazing fast server-side logic, <code class="bg-indigo-200/60 dark:bg-indigo-800/60 rounded px-1 py-0.5 font-mono text-gray-900 dark:text-indigo-100">Tailwind CSS</code> for a responsive, modern interface, and <code class="bg-indigo-200/60 dark:bg-indigo-800/60 rounded px-1 py-0.5 font-mono text-gray-900 dark:text-indigo-100">MySQL</code> for robust data handling. The platform is designed to be as fast as the AI it helps you command.
                    </p>
                </section>
            </article>

            <aside class="order-1 lg:order-2 relative group bg-gray-100 dark:bg-gray-900 rounded-3xl p-8 border border-gray-300 dark:border-gray-700 shadow-2xl transition duration-700 hover:shadow-[0_0_50px_rgb(236,72,153,0.5)]" aria-label="Visual highlight with AI prompt example">
                <div class="text-center">
                    <i class="fa-solid fa-wand-magic-sparkles text-6xl text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-indigo-500 mb-4 animate-bounce-slow" aria-hidden="true"></i>
                    <p class="font-mono text-sm text-indigo-600 dark:text-indigo-400 uppercase tracking-wide mb-2" aria-hidden="true">The Magic Formula</p>
                    <blockquote class="bg-gray-200/60 dark:bg-indigo-800/70 p-4 rounded-xl text-left border-l-4 border-pink-500/80 text-gray-800 dark:text-indigo-100 font-medium" aria-label="Sample AI prompt">
                        <span class="text-pink-600 dark:text-pink-400 font-bold">PROMPT:</span>
                        <span>"A lone astronaut standing on a purple, low-gravity asteroid, synthwave lighting, highly detailed, cinematic shot, 8K."</span>
                    </blockquote>
                </div>
                
                <nav class="mt-6 flex justify-center flex-wrap gap-3" aria-label="Prompt categories">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-pink-100 text-pink-700 dark:bg-pink-900/70 dark:text-pink-300">Image Prompts</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/70 dark:text-indigo-300">Code Snippets</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900/70 dark:text-green-300">Writing Helpers</span>
                </nav>
            </aside>
        </main>
    </div>
</section>


<section id="contact-cta" class="relative py-32 sm:py-40 px-6 bg-gradient-to-br from-indigo-50 via-white to-teal-50 dark:from-indigo-950 dark:via-gray-900 dark:to-teal-950 overflow-hidden transition-colors duration-700">
    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-200/30 to-cyan-300/30 dark:from-indigo-800/30 dark:to-cyan-900/30 opacity-80 blur-3xl animate-pulse-slow -z-10 transition-colors duration-700"></div>
    <div class="absolute -top-20 -right-20 w-[400px] h-[400px] bg-indigo-300/20 dark:bg-indigo-600/20 rounded-full filter blur-3xl animate-blob transition-colors duration-700"></div>
    <div class="absolute -bottom-20 -left-20 w-[400px] h-[400px] bg-cyan-300/20 dark:bg-cyan-600/20 rounded-full filter blur-3xl animate-blob animation-delay-2000 transition-colors duration-700"></div>

    <div class="max-w-7xl mx-auto text-center relative z-20 font-poppins">
        <header class="mb-14">
            <h3 class="text-xs font-bold uppercase tracking-widest text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-pink-600 dark:from-indigo-400 dark:to-pink-400 mb-3 animate-pulse-slow transition-colors duration-300">
                Get in Touch
            </h3>
            <h2 class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-gray-900 dark:text-white leading-tight tracking-tight animate-fadeInUp transition-colors duration-300">
                Ready to Supercharge Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-cyan-600 dark:from-indigo-400 dark:to-cyan-400">AI Creativity</span>?
            </h2>
            <p class="mt-6 text-xl sm:text-2xl text-gray-600 dark:text-gray-300 max-w-4xl mx-auto font-medium animate-fadeInUp delay-200 transition-colors duration-300">
                Join thousands of creators leveraging Promptly.ai to streamline workflow and achieve stunning results.
            </p>
        </header>

        <div class="flex flex-col sm:flex-row gap-8 justify-center max-w-md sm:max-w-3xl mx-auto animate-fadeInUp delay-400">
            <a href="#prompts" title="Explore Premium AI Prompts" class="group relative w-full sm:w-auto px-12 py-5 rounded-full font-bold text-lg bg-indigo-600 hover:bg-indigo-700 text-white shadow-2xl transition-all duration-400 transform hover:scale-105 active:scale-95 overflow-hidden focus:outline-none focus:ring-4 focus:ring-indigo-400">
                <span class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-color-indigo-400),_transparent_80%)] opacity-0 group-hover:opacity-25 transition-opacity duration-700"></span>
                <i class="fa-solid fa-rocket mr-4 transition-transform group-hover:rotate-12"></i>
                Start Exploring Now
            </a>
            <a href="#contact" title="Get in touch" class="w-full sm:w-auto px-12 py-5 rounded-full font-bold text-lg border-2 border-cyan-600 dark:border-cyan-500 text-cyan-600 dark:text-cyan-400 hover:bg-cyan-600 hover:text-white dark:hover:bg-cyan-500 dark:hover:text-gray-900 transition-all duration-400 transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-cyan-500/40 focus:outline-none focus:ring-4 focus:ring-cyan-400">
                <i class="fa-solid fa-envelope mr-4"></i>
                Get In Touch
            </a>
        </div>
    </div>
</section>

<section id="contact"
    class="min-h-screen flex flex-col lg:flex-row bg-gradient-to-br from-indigo-100 via-white to-teal-100 dark:from-indigo-900 dark:via-gray-900 dark:to-teal-900 text-gray-900 dark:text-white font-poppins overflow-hidden transition-colors duration-700">

    <div class="lg:w-1/2 relative">
        <div id="map"
            class="w-full h-96 lg:h-full brightness-[0.7] saturate-150 transition-all duration-1000 ease-in-out scale-95 lg:scale-100 lg:hover:scale-[1.02] shadow-lg rounded-b-3xl lg:rounded-tr-3xl lg:rounded-bl-none"></div>
        <div
            class="absolute inset-0 bg-gradient-to-r from-indigo-200/70 to-transparent dark:from-indigo-900/70 dark:to-transparent pointer-events-none rounded-b-3xl lg:rounded-tr-3xl transition-colors duration-700">
        </div>
        <div
            class="absolute bottom-8 left-8 z-20 max-w-xs bg-white/80 dark:bg-indigo-800/70 backdrop-blur-md rounded-xl p-5 border border-indigo-300 dark:border-indigo-600 shadow-lg space-y-3 transition-colors duration-700">
            <h3
                class="text-2xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-cyan-600 dark:from-indigo-400 dark:to-cyan-400 transition-colors duration-300">
                Our Global Hub</h3>
            <address
                class="not-italic text-gray-700 dark:text-gray-300 leading-relaxed transition-colors duration-300">
                Promptly.ai HQ<br>
                Vile Parle , Mumbai , India
            </address>
            <a href="tel:+1234567890"
                class="inline-flex items-center gap-2 text-cyan-600 dark:text-cyan-400 hover:text-cyan-500 dark:hover:text-cyan-300 font-semibold transition-colors duration-300">
                <i class="fa-solid fa-phone"></i> +1 (91) 1234567890
            </a>
        </div>
    </div>

    <div
        class="lg:w-1/2 flex items-center justify-center p-10 sm:p-16 backdrop-blur-sm bg-white/80 dark:bg-gray-900/80 rounded-t-3xl lg:rounded-t-none lg:rounded-l-3xl shadow-2xl border border-indigo-300/60 dark:border-indigo-700/60 overflow-hidden transition-colors duration-700">
        
        <form id="contactForm" action="contact_submit.php" method="POST"
            class="w-full max-w-lg flex flex-col gap-8 relative animate-fadeInUp">
            
            <h2 class="text-4xl sm:text-5xl font-extrabold mb-6 text-center transition-colors duration-300">
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-400 dark:from-indigo-400 dark:to-indigo-200">Connect</span>
                with Our Team
            </h2>

            <div class="relative group">
                <input type="text" id="name" name="name" placeholder=" " required aria-label="Your Full Name"
                    class="peer w-full rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-2 border-gray-300 dark:border-gray-700 appearance-none px-5 pt-6 pb-3 focus:outline-none focus:ring-4 focus:ring-indigo-500 transition duration-300" />
                <label for="name"
                    class="absolute left-5 top-3 text-gray-600 dark:text-gray-400 text-sm pointer-events-none transition-all duration-300 peer-placeholder-shown:top-6 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-xs peer-focus:text-indigo-600 dark:peer-focus:text-indigo-400 font-semibold">
                    Your Full Name
                </label>
            </div>

            <div class="relative group">
                <input type="email" id="email" name="email" placeholder=" " required aria-label="Your Professional Email"
                    class="peer w-full rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-2 border-gray-300 dark:border-gray-700 appearance-none px-5 pt-6 pb-3 focus:outline-none focus:ring-4 focus:ring-indigo-500 transition duration-300" />
                <label for="email"
                    class="absolute left-5 top-3 text-gray-600 dark:text-gray-400 text-sm pointer-events-none transition-all duration-300 peer-placeholder-shown:top-6 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-xs peer-focus:text-indigo-600 dark:peer-focus:text-indigo-400 font-semibold">
                    Your Professional Email
                </label>
            </div>

            <div class="relative group">
                <textarea id="message" name="message" rows="5" placeholder=" " required aria-label="Your Message"
                    class="peer w-full rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-2 border-gray-300 dark:border-gray-700 appearance-none px-5 pt-6 pb-3 resize-none focus:outline-none focus:ring-4 focus:ring-indigo-500 transition duration-300"></textarea>
                <label for="message"
                    class="absolute left-5 top-3 text-gray-600 dark:text-gray-400 text-sm pointer-events-none transition-all duration-300 peer-placeholder-shown:top-6 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-xs peer-focus:text-indigo-600 dark:peer-focus:text-indigo-400 font-semibold">
                    Please Describe Your Request
                </label>
            </div>

            <p
                class="text-center text-gray-600 dark:text-gray-400 text-sm italic select-text transition-colors duration-300">
                Prefer email? Reach us directly at: <a href="mailto:support@promptly.ai"
                    class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-500 dark:hover:text-cyan-300 underline underline-offset-4 font-semibold transition-colors duration-300">support@promptly.ai</a>
            </p>

            <div id="form-message" class="text-center h-5 transition-all duration-300"></div>

            <button type="submit"
                class="w-full relative px-10 py-4 bg-indigo-600 hover:bg-indigo-700 rounded-xl text-white text-xl font-extrabold transition-all duration-300 shadow-lg shadow-indigo-600/60 hover:shadow-xl hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-indigo-400 active:scale-95 flex justify-center items-center gap-4">
                <i class="fa-solid fa-paper-plane animate-pulse"></i> Send Your Message
            </button>
        </form>
    </div>
</section>

<script src="assets/js/main.js"></script>

 <?php include 'includes/footer.php'; ?>

</body>
</html>