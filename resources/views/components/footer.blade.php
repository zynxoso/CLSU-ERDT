<!-- Modern Footer Component for CLSU-ERDT Templates -->
<footer class="bg-white border-t border-gray-100 mt-20 relative z-10">
    <!-- Main Footer Content -->
    <div class="container mx-auto px-6 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <!-- Brand Section -->
            <div class="lg:col-span-1">
                <div class="flex items-center mb-6">
                    <img src="/images/erdt_logo.png" alt="ERDT Logo" class="h-12 w-auto mr-3" loading="lazy">
                    <img src="/university_logo/CLSU.png" alt="CLSU Logo" class="h-12 w-auto">
                </div>
                <h3 class="text-xl font-bold mb-3" style="color: var(--clsu-maroon);">CLSU-ERDT</h3>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    Engineering Research & Development for Technology<br>
                    <span class="font-medium">Central Luzon State University</span>
                </p>
                <p class="text-xs text-gray-500 italic">
                    Empowering future engineers and researchers for national and global impact.
                </p>
            </div>

            <!-- Quick Navigation -->
            <div>
                <h4 class="text-lg font-semibold mb-6" style="color: var(--clsu-green);">Quick Links</h4>
                <nav class="space-y-3">
                    <a href="/" class="footer-link block">Home</a>
                    <a href="/how-to-apply" class="footer-link block">How to Apply</a>
                    <a href="/about" class="footer-link block">About</a>
                    <a href="/history" class="footer-link block">History</a>
                    <a href="{{ route('scholar-login') }}" class="footer-link block">Scholar Portal</a>
                </nav>
            </div>

            <!-- Contact Information -->
            <div>
                <h4 class="text-lg font-semibold mb-6" style="color: var(--clsu-green);">Contact Info</h4>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 mt-0.5 flex-shrink-0">
                            <svg class="w-full h-full text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <span class="text-sm text-gray-700">(044) 456-0107</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 mt-0.5 flex-shrink-0">
                            <svg class="w-full h-full text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span class="text-sm text-gray-700">Science City of Muñoz, Nueva Ecija</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 mt-0.5 flex-shrink-0">
                            <svg class="w-full h-full text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <a href="mailto:erdt@clsu.edu.ph" class="text-sm text-gray-700 hover:text-green-600 transition-colors duration-200">erdt@clsu.edu.ph</a>
                    </div>
                </div>
            </div>

            <!-- Connect & Resources -->
            <div>
                <h4 class="text-lg font-semibold mb-6" style="color: var(--clsu-green);">Connect</h4>
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <a href="#" class="footer-social-icon" aria-label="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                            </svg>
                        </a>
                        <a href="#" class="footer-social-icon" aria-label="Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                            </svg>
                        </a>
                        <a href="#" class="footer-social-icon" aria-label="YouTube">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#" class="footer-social-icon" aria-label="LinkedIn">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                    <div class="mt-6">
                        <a href="https://clsu.edu.ph" target="_blank" rel="noopener" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Visit CLSU Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-gray-100 bg-gray-50">
        <div class="container mx-auto px-6 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-sm text-gray-600">
                    © {{ date('Y') }} CLSU-ERDT. All rights reserved.
                </div>
                <div class="flex items-center gap-6 text-sm">
                    <a href="#" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">Privacy Policy</a>
                    <a href="#" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">Terms of Service</a>
                    <a href="#" class="text-gray-600 hover:text-gray-800 transition-colors duration-200">Accessibility</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Back to Top Button with Progress Indicator -->
    <button id="footer-back-to-top" class="fixed bottom-8 right-8 w-14 h-14 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 z-50 group" style="display:none; background: conic-gradient(var(--clsu-green) var(--scroll-progress, 0%), #e5e7eb var(--scroll-progress, 0%));">
        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center m-0.5 group-hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-5 h-5 text-gray-700 group-hover:text-green-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        </div>
    </button>
    <style>
        /* Footer Link Styles */
        .footer-link {
            @apply text-gray-700 hover:text-green-600 transition-all duration-200 font-medium text-sm relative;
            position: relative;
        }
        
        .footer-link::before {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--clsu-green);
            transition: width 0.3s ease;
        }
        
        .footer-link:hover::before {
            width: 100%;
        }
        
        .footer-link:focus {
            outline: 2px solid var(--clsu-green);
            outline-offset: 2px;
            border-radius: 2px;
        }

        /* Social Media Icons */
        .footer-social-icon {
            @apply w-10 h-10 bg-gray-100 hover:bg-green-50 text-gray-600 hover:text-green-600 rounded-lg flex items-center justify-center transition-all duration-200;
        }
        
        .footer-social-icon:focus {
            outline: 2px solid var(--clsu-green);
            outline-offset: 2px;
        }
        
        .footer-social-icon:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            footer .grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            footer .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
        
        @media (max-width: 1024px) {
            footer .grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
    <script>
        // Enhanced Back to Top Button with Progress Indicator
        const backToTopBtn = document.getElementById('footer-back-to-top');
        
        function updateScrollProgress() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollProgress = (scrollTop / scrollHeight) * 100;
            
            // Update CSS custom property for progress
            backToTopBtn.style.setProperty('--scroll-progress', scrollProgress + '%');
            
            // Show/hide button
            if (scrollTop > 300) {
                backToTopBtn.style.display = 'block';
                // Add smooth entrance animation
                setTimeout(() => {
                    backToTopBtn.style.opacity = '1';
                    backToTopBtn.style.transform = 'scale(1)';
                }, 10);
            } else {
                backToTopBtn.style.opacity = '0';
                backToTopBtn.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    if (scrollTop <= 300) {
                        backToTopBtn.style.display = 'none';
                    }
                }, 200);
            }
        }
        
        // Initialize button styles
        backToTopBtn.style.opacity = '0';
        backToTopBtn.style.transform = 'scale(0.8)';
        backToTopBtn.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
        
        // Event listeners
        window.addEventListener('scroll', updateScrollProgress);
        
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ 
                top: 0, 
                behavior: 'smooth' 
            });
        });
        
        // Keyboard accessibility
        backToTopBtn.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                window.scrollTo({ 
                    top: 0, 
                    behavior: 'smooth' 
                });
            }
        });
        
        // Initial call
        updateScrollProgress();
    </script>
</footer>
