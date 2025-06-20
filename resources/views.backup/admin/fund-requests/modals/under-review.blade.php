<!-- Under Review Modal -->
<div id="under-review-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal-overlay">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Mark as Under Review</h3>
                <button onclick="document.getElementById('under-review-modal').classList.add('hidden')" class="text-gray-400 hover:text-red-600 transition-colors duration-150">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <p class="text-gray-600 mb-6">Mark this fund request as under review? The scholar will be notified of this status change.</p>

            <form action="{{ route('admin.fund-requests.under-review', $fundRequest->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="review-notes" class="block text-sm font-medium text-gray-700 mb-1">Internal Notes (Optional)</label>
                    <textarea id="review-notes" name="admin_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-600 focus:border-blue-600" placeholder="Add any internal notes here...">{{ old('admin_notes') }}</textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('under-review-modal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 transition-colors duration-150">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                        <i class="fas fa-search mr-1"></i> Mark as Under Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
