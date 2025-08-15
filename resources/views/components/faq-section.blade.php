@props(['faqs' => []])

<div class="bg-white py-16 sm:py-20 lg:py-24">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-800 mb-4 sm:mb-6">Frequently Asked Questions</h2>
                <div class="w-16 sm:w-24 h-1.5 sm:h-2 mx-auto mb-6 sm:mb-8 rounded bg-green-600"></div>
                <p class="text-lg sm:text-xl text-gray-700 leading-relaxed">Find answers to common questions about the CLSU-ERDT program.</p>
            </div>

            <div class="space-y-4 sm:space-y-6" x-data="{ openFaq: null }">
                @foreach($faqs as $index => $faq)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                        <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}"
                                class="w-full px-6 py-6 sm:px-8 sm:py-8 text-left focus:outline-none focus:ring-4 focus:ring-green-200 rounded-lg transition-all duration-300 min-h-[60px] touch-manipulation"
                                :class="openFaq === {{ $index }} ? 'bg-green-50' : 'hover:bg-gray-50 active:bg-gray-100'"
                                role="button"
                                :aria-expanded="openFaq === {{ $index }}"
                                aria-controls="faq-content-{{ $index }}"
                                tabindex="0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-800 pr-4 leading-relaxed">{{ $faq['question'] }}</h3>
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 sm:w-7 sm:h-7 text-green-600 transform transition-transform duration-300"
                                         :class="openFaq === {{ $index }} ? 'rotate-180' : 'rotate-0'"
                                         fill="none" 
                                         viewBox="0 0 24 24" 
                                         stroke="currentColor"
                                         aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </button>
                        
                        <div x-show="openFaq === {{ $index }}"
                             x-collapse
                             x-cloak
                             id="faq-content-{{ $index }}"
                             class="px-6 pb-6 sm:px-8 sm:pb-8">
                            <div class="pt-4 border-t border-gray-100">
                                <p class="text-base sm:text-lg text-gray-700 leading-relaxed">{{ $faq['answer'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>
