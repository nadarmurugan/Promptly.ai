/**
 * main.js - Consolidated JavaScript for Promptly.ai
 *
 * Contains:
 * 1. Unified smooth scrolling for anchor links (with header offset).
 * 2. Dynamic, non-blocking contact form submission with robust state management.
 * 3. Copy-to-clipboard functionality with a dynamic, Tailwind-styled toast notification.
 * 4. Leaflet map initialization for the contact section.
 * 5. Back-to-top button visibility toggle.
 */

// --- GLOBAL STATE ---
let isSubmitting = false;

// --- UTILITY: Dynamic Toast Notification ---

/**
 * Creates and displays a dynamic, temporary notification (toast).
 * @param {string} message - The message to display.
 * @param {boolean} isSuccess - True for success (indigo), false for error (red).
 * @param {number} duration - Duration in milliseconds.
 */
function showToastNotification(message, isSuccess, duration = 3000) {
    // Look for an existing message container (creates one if it doesn't exist)
    let toastContainer = document.getElementById('global-toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'global-toast-container';
        // Tailwind classes for a fixed, bottom-right container
        toastContainer.className = 'fixed bottom-5 right-5 z-50 flex flex-col space-y-2 pointer-events-none';
        document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    toast.className = `p-4 rounded-xl shadow-2xl text-white font-semibold transition-all duration-300 transform translate-x-full opacity-0 pointer-events-auto w-72 ${
        isSuccess ? 'bg-indigo-600 dark:bg-indigo-500' : 'bg-red-600 dark:bg-red-500'
    }`;
    toast.innerHTML = `<i class="fa-solid ${isSuccess ? 'fa-check-circle' : 'fa-circle-xmark'} mr-2"></i> ${message}`;

    toastContainer.appendChild(toast);

    // Show the toast
    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);

    // Hide and remove the toast
    setTimeout(() => {
        toast.classList.remove('translate-x-0', 'opacity-100');
        toast.classList.add('translate-x-full', 'opacity-0');
        
        // Remove after the transition is complete
        toast.addEventListener('transitionend', () => toast.remove());
    }, duration);
}

// --- CORE FUNCTIONALITY ---

/**
 * Copies prompt text to clipboard and shows a toast notification.
 * @param {string} text - The prompt text (potentially HTML-escaped).
 * @param {string | null} id - Optional prompt ID for server logging.
 */
function copyPrompt(text, id = null) {
    // CRITICAL: Decode HTML entities which were necessary for safe PHP embedding (e.g., &quot; to ")
    const decodedText = new DOMParser().parseFromString(text, 'text/html').documentElement.textContent;

    navigator.clipboard.writeText(decodedText).then(() => {
        showToastNotification('Prompt copied to clipboard! ✨', true);
        
        // Optional: send prompt ID to server for logging copy event
        if (id) {
            // Note: Use the actual endpoint for logging analytics
            fetch('/api/copy_prompt.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ prompt_id: id })
            }).catch(error => {
                console.error('Copy logging failed:', error);
            });
        }
    }).catch(() => {
        showToastNotification('Failed to copy prompt. Please copy manually.', false);
    });
}

// Export copyPrompt to the global window object so it can be called from PHP-generated inline HTML
window.copyPrompt = copyPrompt;


// --- DOMContentLoaded Logic (Unified Entry Point) ---
document.addEventListener("DOMContentLoaded", function () {
    const contactForm = document.getElementById("contactForm");
    const messageContainer = document.getElementById('form-message');
    const backToTopButton = document.getElementById('back-to-top');
    const FIXED_HEADER_OFFSET = 90; // Adjust for your fixed header height

    // ------------------------------------
    // 1. Smooth Scrolling & Anchor Links
    // ------------------------------------
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);

            if (target) {
                window.scrollTo({
                    // Offset the scroll to account for the fixed header
                    top: target.offsetTop - FIXED_HEADER_OFFSET,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ------------------------------------
    // 2. Back to Top Button Logic
    // ------------------------------------
    if (backToTopButton) {
        // Visibility toggle
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
        // Initial check
        toggleBackToTop(); 

        backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ------------------------------------
    // 3. Leaflet Map Initialization
    // ------------------------------------
    let map;
    const vileParleLatLng = [19.1026, 72.8405]; // More accurate coordinates

    function setupMap() {
        if (!document.getElementById('map') || typeof L === 'undefined') {
            return;
        }
        // Remove existing map instance if it was already created (useful for hot-reloads)
        if (map) { map.remove(); }
        
        map = L.map('map', { 
            zoomControl: true, 
            scrollWheelZoom: 'center' 
        }).setView(vileParleLatLng, 15);

        // Dark/Standard Tile Layer based on preference (using CartoDB Dark for better dark mode aesthetics)
        const tileLayerUrl = document.body.classList.contains('dark') 
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';

        L.tileLayer(tileLayerUrl, {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Custom Icon for better visual
        const aiIcon = L.divIcon({
            className: 'custom-ai-icon',
            html: '<i class="fa-solid fa-robot fa-2x text-indigo-500 drop-shadow-lg" style="transform: translateY(2px);"></i>',
            iconSize: [40, 40],
            iconAnchor: [20, 40]
        });

        L.marker(vileParleLatLng, { icon: aiIcon }).addTo(map)
            .bindPopup('<b>Promptly.ai HQ</b><br>Vile Parle East, Mumbai, India.').openPopup();
    }

    // Initialize map immediately if Leaflet is loaded, otherwise wait for the script to load (using the assumption it's deferred)
    if (typeof L !== 'undefined') {
        setupMap();
    }

    // ------------------------------------
    // 4. Contact Form Submission Logic
    // ------------------------------------
    if (contactForm) {
        
        // Function to show the in-page message for the form
        const showFormMessage = (message, isSuccess) => {
            messageContainer.textContent = message;
            // Apply Tailwind classes directly to the container for styling
            messageContainer.className = `text-center font-semibold h-5 transition-all duration-300 ${
                isSuccess ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'
            }`;
        };

        contactForm.addEventListener("submit", async function(e) {
            e.preventDefault();

            if (isSubmitting) {
                showFormMessage("Please wait, your message is already being sent.", false);
                return;
            }
            
            isSubmitting = true; // Lock the form

            const form = this;
            const submitButton = form.querySelector('button[type="submit"]');
            const originalButtonHtml = submitButton.innerHTML;
            
            // A helper function to reset the button state, message, and the flag
            const resetState = (success = false) => {
                if (success) { form.reset(); }
                
                // Reset button after success/fail animation is visible
                setTimeout(() => {
                    submitButton.innerHTML = originalButtonHtml;
                    // Reset button classes to original indigo state
                    submitButton.className = "w-full relative px-10 py-4 bg-indigo-600 hover:bg-indigo-700 rounded-xl text-white text-xl font-extrabold transition-all duration-300 shadow-lg shadow-indigo-600/60 hover:shadow-xl hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-indigo-400 active:scale-95 flex justify-center items-center gap-4";
                    submitButton.disabled = false;
                    
                    // Clear the message container after a period
                    if (messageContainer.textContent) {
                        setTimeout(() => { messageContainer.textContent = ''; }, 3000); 
                    }

                    isSubmitting = false; // **CRITICAL**: Unlock the form
                }, success ? 2000 : 500); // Wait longer on success for user confirmation
            };

            // --- Step 1: Set Sending State ---
            showFormMessage('', true); // Clear previous messages
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending...';
            submitButton.classList.remove('shadow-indigo-600/60', 'hover:bg-indigo-700');
            submitButton.classList.add('bg-indigo-700', 'shadow-none');
            submitButton.disabled = true;

            const formData = new FormData(form);

            try {
                const res = await fetch(form.action, { method: "POST", body: formData });
                const data = await res.json(); 

                if (res.ok && data.status === 'success') {
                    // --- Step 2: Handle SUCCESS (HTTP 200-299 + status:success) ---
                    submitButton.innerHTML = '<i class="fa-solid fa-check-double"></i> Message Sent!';
                    submitButton.classList.remove('bg-indigo-700', 'shadow-none');
                    submitButton.classList.add('bg-green-600', 'hover:bg-green-700', 'shadow-lg', 'shadow-green-600/60');
                    
                    showFormMessage(data.message || "Your message was sent successfully!", true);
                    resetState(true);
                } else {
                    // --- Step 3: Handle SERVER-SIDE FAILURE (HTTP 4xx/5xx or status:error) ---
                    submitButton.innerHTML = '<i class="fa-solid fa-xmark"></i> Failed!';
                    submitButton.classList.remove('bg-indigo-700', 'shadow-none');
                    submitButton.classList.add('bg-red-600', 'hover:bg-red-700', 'shadow-lg', 'shadow-red-600/60');
                    
                    showFormMessage(data.message || "Submission failed. Please check your inputs.", false);
                    resetState(false);
                }

            } catch (error) {
                // --- Step 4: Handle Network/Parsing Error ---
                console.error("Fetch Error:", error);
                submitButton.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Error!';
                submitButton.classList.remove('bg-indigo-700', 'shadow-none');
                submitButton.classList.add('bg-red-600', 'hover:bg-red-700', 'shadow-lg', 'shadow-red-600/60');
                
                showFormMessage("A network error occurred. Please try again later.", false);
                resetState(false); 
            }
        });
    }
});