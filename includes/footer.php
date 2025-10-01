<?php
// Ensure session is started only if it hasn't been already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_year = date('Y');
?>

<style>
    /*
    Custom CSS for Footer Links and Socials (Keeping custom styles minimal and clean)
    - Using custom utility classes for non-Tailwind specific animations/effects
    */

    /* Footer Link Hover Effect */
    .footer-link { display: flex; gap: 0.5rem; align-items: center; transition: all 0.3s; }
    .footer-link i { transition: transform 0.3s; }
    .footer-link:hover { color: #6366F1; } /* Primary color on hover */
    .footer-link:hover i { transform: translateX(4px); }

    /* Social Icon Styles (Using Tailwind colors, but custom class for shape/shadow/hover) */
    .footer-social { 
        width: 40px; 
        height: 40px; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        border-radius: 0.75rem; 
        transition: all 0.3s; 
    }
    .footer-social:hover { 
        transform: scale(1.1); 
    }

    /* App Badge Styles */
    .footer-app { 
        flex: 1; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        padding: 0.5rem; 
        border-radius: 0.75rem; 
        transition: all 0.3s; 
        text-decoration: none; 
    }
    .footer-app:hover { 
        transform: translateY(-2px); 
    }

    /* Scroll/Load Animations */
    .animate-fadeIn { opacity:0; transform:translateY(20px); animation:fadeIn 0.8s forwards; }
    .animate-fadeIn.delay-100 { animation-delay:0.1s; }
    .animate-fadeIn.delay-200 { animation-delay:0.2s; }
    .animate-fadeIn.delay-300 { animation-delay:0.3s; }
    @keyframes fadeIn { to { opacity:1; transform:translateY(0); } }
</style>

<footer class="relative bg-gradient-to-br from-white via-gray-50 to-indigo-50 dark:from-darkbg dark:to-gray-800 text-gray-700 dark:text-gray-300 overflow-hidden" role="contentinfo">
    

    <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-y-10 gap-x-8">
        
        <div class="col-span-2 md:col-span-2 lg:col-span-2 space-y-6 animate-fadeIn">
            <a href="index.php" class="flex items-center gap-3 group mb-4" aria-label="Promptly.ai Home">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-500">
                    <i class="fa-solid fa-brain text-white text-xl" aria-hidden="true"></i>
                </div>
                <span class="text-2xl font-bold text-gray-900 dark:text-white group-hover:text-primary transition-colors duration-300">
                    Promptly<span class="text-primary">.ai</span>
                </span>
            </a>
            <p class="text-gray-600 dark:text-gray-400 leading-relaxed max-w-md">
                The leading AI prompt marketplace with over **500 curated prompts** to boost your creativity and productivity.
            </p>
            
            <div class="flex flex-wrap gap-4 pt-2">
                <div class="flex items-center gap-2 bg-white dark:bg-gray-700 px-4 py-2 rounded-xl shadow-sm hover:scale-105 transition-transform duration-300 text-sm font-medium">
                    <i class="fa-solid fa-shield-check text-green-500" aria-hidden="true"></i>
                    Secure Platform
                </div>
                <div class="flex items-center gap-2 bg-white dark:bg-gray-700 px-4 py-2 rounded-xl shadow-sm hover:scale-105 transition-transform duration-300 text-sm font-medium">
                    <i class="fa-solid fa-bolt text-yellow-500" aria-hidden="true"></i>
                    Fast Loading
                </div>
            </div>
        </div>

        <nav aria-labelledby="quick-links-heading" class="col-span-1 animate-fadeIn delay-100">
            <h3 id="quick-links-heading" class="text-lg font-bold text-gray-900 dark:text-white mb-6 relative inline-block">
                Quick Links
                <span class="absolute -bottom-1 left-0 w-1/2 h-0.5 bg-primary rounded-full"></span>
            </h3>
            <ul class="space-y-3 text-gray-600 dark:text-gray-400 font-medium" role="list">
                <li><a href="#features" class="footer-link"><i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i> Features</a></li>
                <li><a href="#prompts" class="footer-link"><i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i> Prompts</a></li>
                <li><a href="#about" class="footer-link"><i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i> About Us</a></li>
                <li><a href="#contact" class="footer-link"><i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i> Contact</a></li>
            </ul>
        </nav>

        <nav aria-labelledby="resources-heading" class="col-span-1 animate-fadeIn delay-200">
            <h3 id="resources-heading" class="text-lg font-bold text-gray-900 dark:text-white mb-6 relative inline-block">
                Resources
                <span class="absolute -bottom-1 left-0 w-1/2 h-0.5 bg-primary rounded-full"></span>
            </h3>
            <ul class="space-y-3 text-gray-600 dark:text-gray-400 font-medium" role="list">
                <li><a href="#" class="footer-link"><i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i> Blog & Tutorials</a></li>
                <li><a href="#" class="footer-link"><i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i> Documentation</a></li>
                <li><a href="#" class="footer-link"><i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i> Community Forum</a></li>
                <li><a href="#" class="footer-link"><i class="fa-solid fa-chevron-right text-xs" aria-hidden="true"></i> Support Center</a></li>
            </ul>
        </nav>

        <section aria-labelledby="contact-info-heading" class="col-span-2 md:col-span-4 lg:col-span-1 animate-fadeIn delay-300">
            <h3 id="contact-info-heading" class="text-lg font-bold text-gray-900 dark:text-white mb-6 relative inline-block">
                Connect
                <span class="absolute -bottom-1 left-0 w-1/2 h-0.5 bg-primary rounded-full"></span>
            </h3>
            
            <address class="not-italic space-y-3 mb-6 text-gray-600 dark:text-gray-400">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-envelope text-accent w-5" aria-hidden="true"></i>
                    <a href="mailto:support@promptly.ai" class="text-sm hover:text-primary transition-colors duration-300">support@promptly.ai</a>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-globe text-accent w-5" aria-hidden="true"></i>
                    <a href="https://www.promptly.ai" target="_blank" rel="noopener noreferrer" class="text-sm hover:text-primary transition-colors duration-300">www.promptly.ai</a>
                </div>
            </address>

            <div class="flex gap-3 mb-6">
                <?php
                $socials = [
                    'twitter' => ['icon' => 'fab fa-twitter', 'url' => '#'],
                    'linkedin' => ['icon' => 'fab fa-linkedin-in', 'url' => '#'],
                    'github' => ['icon' => 'fab fa-github', 'url' => '#'],
                    'discord' => ['icon' => 'fab fa-discord', 'url' => '#']
                ];
                foreach($socials as $name=>$data){
                    echo '<a href="'.$data['url'].'" class="footer-social bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 border border-gray-300 dark:border-gray-600 hover:bg-primary hover:text-white" aria-label="Follow us on '.ucfirst($name).'" target="_blank" rel="noopener noreferrer"><i class="'.$data['icon'].'"></i></a>';
                }
                ?>
            </div>

            <div class="flex gap-3 flex-wrap">
                <a href="#" class="footer-app bg-gray-900 text-white hover:bg-primary" aria-label="Download on the App Store">
                    <i class="fab fa-apple text-lg" aria-hidden="true"></i>
                    <span class="ml-2 text-xs font-semibold">App Store</span>
                </a>
                <a href="#" class="footer-app bg-gray-900 text-white hover:bg-primary" aria-label="Get it on Google Play">
                    <i class="fab fa-google-play text-lg" aria-hidden="true"></i>
                    <span class="ml-2 text-xs font-semibold">Play Store</span>
                </a>
            </div>
        </section>
    </div>

    <div class="border-t border-gray-200 dark:border-gray-800 py-6">
        <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row justify-between items-center gap-6 text-center lg:text-left">
            
            <p class="text-sm text-gray-600 dark:text-gray-400 order-3 lg:order-1">
                &copy; <?php echo $current_year; ?> Promptly.ai. All rights reserved.
            </p>

            <nav class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm text-gray-600 dark:text-gray-400 order-2" aria-label="Legal Links">
                <a href="#" class="hover:text-primary transition-colors duration-300">Privacy Policy</a>
                <a href="#" class="hover:text-primary transition-colors duration-300">Terms of Service</a>
                <a href="#" class="hover:text-primary transition-colors duration-300">Cookie Policy</a>
                <a href="#" class="hover:text-primary transition-colors duration-300">Security</a>
            </nav>

            <div class="flex items-center gap-3 order-1 lg:order-3">
                <span class="text-xs text-gray-500 dark:text-gray-500 mr-2">We accept:</span>
                <div class="flex gap-2 text-gray-400">
                    <i class="fa-brands fa-cc-visa text-2xl" title="Visa" aria-hidden="true"></i>
                    <i class="fa-brands fa-cc-mastercard text-2xl" title="Mastercard" aria-hidden="true"></i>
                    <i class="fa-brands fa-cc-paypal text-2xl" title="PayPal" aria-hidden="true"></i>
                    <i class="fa-brands fa-cc-apple-pay text-2xl" title="Apple Pay" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </div>

    <button id="back-to-top" aria-label="Back to Top" class="fixed bottom-6 right-6 w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center shadow-xl hover:-translate-y-1 hover:scale-110 transition-all duration-300 opacity-0 invisible z-40">
        <i class="fa-solid fa-chevron-up" aria-hidden="true"></i>
    </button>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('back-to-top');

        // Back to top button visibility toggle (using modern Intersection Observer is better, but this is simple and effective)
        const toggleBackToTop = () => {
            if (window.scrollY > 300) {
                backToTopButton.classList.remove('opacity-0', 'invisible');
                backToTopButton.classList.add('opacity-100', 'visible');
            } else {
                backToTopButton.classList.add('opacity-0', 'invisible');
                backToTopButton.classList.remove('opacity-100', 'visible');
            }
        };

        window.addEventListener('scroll', toggleBackToTop);
        toggleBackToTop(); 

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // Smooth scroll for anchor links (Optimized with offset for fixed header)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const href = this.getAttribute('href');
                if (href === "#") return; // Ignore empty hashes
                
                const target = document.querySelector(href);
                if (target) {
                    // Estimate header height (e.g., 90px) to prevent the header from covering the target section
                    const offset = 90; 
                    const targetPosition = target.offsetTop - offset;
                    window.scrollTo({ 
                        top: targetPosition, 
                        behavior: 'smooth' 
                    });
                }
            });
        });
        
        // Removed redundant JS for newsletter input focus as it's handled by Tailwind CSS focus utility classes in the HTML.
    });
</script>