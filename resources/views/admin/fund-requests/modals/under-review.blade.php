<!-- Under Review Modal -->
<div id="under-review-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal-overlay">
    <div class="rounded-xl shadow-xl w-full max-w-md mx-4" style="background-color: #FAFAFA;">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" style="color: #212121;">Mark as Under Review</h3>
                <button onclick="document.getElementById('under-review-modal').classList.add('hidden')" class="transition-colors duration-150" style="color: #757575;" onmouseover="this.style.color='#D32F2F'" onmouseout="this.style.color='#757575'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mb-6">
                <p class="mb-4" style="color: #616161;">Mark this fund request as under review? The scholar will be notified of this status change.</p>
                <div class="p-3 rounded-lg" style="background-color: #FFF8E1; border: 1px solid #FFCA28;">
                    <div class="flex items-center">
                        <i class="fas fa-search mr-2" style="color: #F57C00;"></i>
                        <span class="text-sm font-medium" style="color: #F57C00;">
                            This indicates the request is being actively reviewed by administrators.
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.fund-requests.under-review', $fundRequest->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="review-notes" class="block text-sm font-medium mb-1" style="color: #424242;">Internal Notes (Optional)</label>
                    <textarea id="review-notes" name="admin_notes" rows="3" class="w-full px-3 py-2 rounded-md shadow-sm focus:outline-none transition-colors duration-200" style="border: 1px solid #E0E0E0; background-color: white; color: #424242;" placeholder="Add any internal notes here..." onfocus="this.style.borderColor='#FFCA28'" onblur="this.style.borderColor='#E0E0E0'">{{ old('admin_notes') }}</textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('under-review-modal').classList.add('hidden')" class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" style="color: white; background-color: #757575; border: 1px solid #757575;" onmouseover="this.style.backgroundColor='#616161'" onmouseout="this.style.backgroundColor='#757575'">
                        Cancel
                    </button>
                    <button type="submit" id="review-submit-btn" class="px-4 py-2 rounded-md shadow-sm text-sm font-medium text-white transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" style="background-color: #FFCA28; color: #424242;" onmouseover="this.style.backgroundColor='#FFB300'" onmouseout="this.style.backgroundColor='#FFCA28'">
                        <i class="fas fa-search mr-1"></i> Mark as Under Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
