@extends('layouts.app')

@section('content')
<!-- Include the navigation component -->
<x-navigation />

<!-- Hero Section -->
<div class="bg-gray-50 py-6 p-12">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Our Journey
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-gray-800 mb-4">
                <span class="block">{{ $heroContent['title'] ?? 'Our Rich' }}</span>
                <span class="block text-green-700">{{ $heroContent['subtitle'] ?? 'History & Legacy' }}</span>
            </h1>
            <h2 class="text-xl md:text-2xl font-medium text-gray-600 mb-8">
                {{ $heroContent['heading'] ?? 'Decades of Engineering Excellence & Innovation' }}
            </h2>
            <p class="text-lg text-gray-700 mb-12 max-w-3xl mx-auto">
                {{ $heroContent['description'] ?? 'Discover the remarkable journey of CLSU-ERDT, from its humble beginnings to becoming a premier center of excellence in engineering research and development. Explore our milestones, achievements, and the impact we\'ve made in shaping the future of engineering in the Philippines.' }}
            </p>

            <!-- Call to Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="#timeline" class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Explore Timeline
                </a>
                <a href="#achievements" class="inline-flex items-center bg-white text-gray-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-gray-300 hover:bg-gray-100">
                    <svg class="h-6 w-6 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    View Achievements
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="py-20" style="background-color: #108f01;">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-6xl mx-auto">
            <div class="text-center">
                <div class="text-4xl font-bold text-white mb-2">1998</div>
                <div class="text-white text-base">Program Established</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-white mb-2">25+</div>
                <div class="text-white text-base">Years of Excellence</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-white mb-2">500+</div>
                <div class="text-white text-base">Graduates Produced</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-white mb-2">100+</div>
                <div class="text-white text-base">Research Projects</div>
            </div>
        </div>
    </div>
</div>

<!-- Introduction Section -->
<div class="py-16 bg-gray-50" id="introduction">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-green-100 text-green-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                Our Story
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                {{ $introContent['title'] ?? 'Building Engineering Excellence' }}
            </h2>
            <div class="w-20 h-1 mx-auto mb-6 rounded bg-gradient-to-r from-green-500 to-green-700"></div>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-8 md:p-12">
                <div class="text-center max-w-4xl mx-auto">
                    @if(isset($introContent['paragraph_1']))
                        <p class="text-lg text-gray-700 leading-relaxed mb-6">{{ $introContent['paragraph_1'] }}</p>
                    @else
                        <p class="text-lg text-gray-700 leading-relaxed mb-6">
                            The Engineering Research & Development for Technology (ERDT) program at Central Luzon State University represents more than two decades of commitment to excellence in engineering education and research. Since its establishment, ERDT has been at the forefront of developing world-class engineers and researchers who contribute significantly to national development and technological advancement.
                        </p>
                    @endif

                    @if(isset($introContent['paragraph_2']))
                        <p class="text-lg text-gray-700 leading-relaxed">{{ $introContent['paragraph_2'] }}</p>
                    @else
                        <p class="text-lg text-gray-700 leading-relaxed">
                            Our journey began with a vision to bridge the gap between academic excellence and industry needs, creating a program that not only educates but also innovates. Through strategic partnerships with leading universities and industry leaders, CLSU-ERDT has evolved into a premier center of excellence that continues to shape the future of engineering in the Philippines and beyond.
                        </p>
                    @endif

                    <p class="text-lg text-gray-700 leading-relaxed mt-6">
                        Launched in April 2007, the ERDT program was established to build a critical mass of research scientists and engineers. CLSU joined the consortium to contribute expertise in agricultural engineering, with its College of Engineering playing a key role since 2009. Under the leadership of deans like Dr. Arnold R. Elepano and Dr. Rossana Marie C. Amongo, CLSU has advanced research in ABE, aligning with the Philippines' National Science and Technology Plan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vision for the Future Section -->
<div class="py-16 id="vision" style="background-color: #108f01;">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium mb-4 bg-purple-100 text-purple-700">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Future Vision
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                {{ $visionContent['title'] ?? 'Our Vision for the Future' }}
            </h2>
            <div class="w-20 h-1 mx-auto mb-6 rounded bg-white"></div>
        </div>

        <div class="max-w-6xl mx-auto">
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 rounded-2xl p-8 md:p-12">
                <div class="text-center max-w-4xl mx-auto">
                    @if(isset($visionContent['description']))
                        <p class="text-lg text-gray-700 leading-relaxed mb-8">{{ $visionContent['description'] }}</p>
                    @else
                        <p class="text-lg text-gray-700 leading-relaxed mb-8">
                            As we look towards the future, CLSU-ERDT remains committed to pushing the boundaries of engineering education and research. We envision a future where our graduates continue to lead technological innovation, drive sustainable development, and contribute to solving global challenges through engineering excellence.
                        </p>
                    @endif

                    <div class="grid md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-green-100 text-green-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-800 mb-2">Innovation Leadership</h4>
                            <p class="text-gray-600 text-sm">Leading breakthrough research in emerging technologies and sustainable engineering solutions.</p>
                        </div>
                        <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-green-100 text-green-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-800 mb-2">Global Impact</h4>
                            <p class="text-gray-600 text-sm">Expanding our reach to address global challenges and collaborate internationally.</p>
                        </div>
                        <div class="bg-white rounded-lg p-6 shadow-md hover:shadow-lg transition-all duration-300">
                            <div class="bg-green-100 text-green-700 p-3 rounded-lg w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-800 mb-2">Next Generation</h4>
                            <p class="text-gray-600 text-sm">Nurturing the next generation of engineering leaders and innovators.</p>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('how-to-apply') }}" class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Be Part of Our Future
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Continue Our Legacy</h2>
            <p class="text-xl text-gray-700 mb-12 leading-relaxed">
                Join the distinguished lineage of CLSU-ERDT graduates who have shaped the engineering landscape of the Philippines. Be part of our continuing story of excellence, innovation, and impact.
            </p>

            <div class="flex flex-col sm:flex-row gap-6 justify-center">
                <a href="{{ route('how-to-apply') }}" class="inline-flex items-center bg-green-600 text-white hover:bg-green-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="h-6 w-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Apply Now
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center bg-white text-gray-700 font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl border border-gray-300 hover:bg-gray-100">
                    <svg class="h-6 w-6 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Learn More
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced JavaScript -->
<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Reading progress bar
    window.addEventListener('scroll', () => {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;
        const progressBar = document.getElementById('reading-progress');
        if (progressBar) {
            progressBar.style.width = scrolled + '%';
        }
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in-up');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.transform').forEach(el => {
        observer.observe(el);
    });

    // Back to top functionality
    const backToTopButton = document.getElementById('back-to-top');

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('opacity-0', 'invisible');
            backToTopButton.classList.add('opacity-100', 'visible');
        } else {
            backToTopButton.classList.add('opacity-0', 'invisible');
            backToTopButton.classList.remove('opacity-100', 'visible');
        }
    });

    if (backToTopButton) {
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
</script>

<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .fade-in-up {
        animation: fadeInUp 0.6s ease forwards;
    }
</style>

<!-- Back to Top Button -->
<button id="back-to-top" class="fixed bottom-8 right-8 p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 opacity-0 invisible z-50 bg-green-600 text-white hover:bg-green-700">
    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

<x-footer />
@endsection
