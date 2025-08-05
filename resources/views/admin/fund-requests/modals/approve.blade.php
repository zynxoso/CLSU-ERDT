<!-- Approve Request Modal -->
<div id="approve-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden modal-overlay">
    <div class="rounded-xl shadow-xl w-full max-w-md mx-4" style="background-color: #FAFAFA;">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" style="color: #212121;">Approve Fund Request</h3>
                <button onclick="document.getElementById('approve-modal').classList.add('hidden')" class="transition-colors duration-150" style="color: #757575;" onmouseover="this.style.color='#D32F2F'" onmouseout="this.style.color='#757575'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="mb-6">
                <p class="mb-4" style="color: #616161;">Are you sure you want to approve this fund request? This action cannot be undone.</p>
                <div class="p-3 rounded-lg" style="background-color: #E8F5E8; border: 1px solid #4CAF50;">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle mr-2" style="color: #4CAF50;"></i>
                        <span class="text-sm font-medium" style="color: #4CAF50;">
                            This will mark the request as approved and notify the scholar.
                        </span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.fund-requests.approve', $fundRequest->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="approve-notes" class="block text-sm font-medium mb-1" style="color: #424242;">Notes (Optional)</label>
                    <textarea id="approve-notes" name="admin_notes" rows="3" class="w-full px-3 py-2 rounded-md shadow-sm focus:outline-none transition-colors duration-200" style="border: 1px solid #E0E0E0; background-color: white; color: #424242;" placeholder="Add any notes or comments here..." onfocus="this.style.borderColor='#4CAF50'" onblur="this.style.borderColor='#E0E0E0'">{{ old('admin_notes') }}</textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('approve-modal').classList.add('hidden')" class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200" style="color: white; background-color: #757575; border: 1px solid #757575;" onmouseover="this.style.backgroundColor='#616161'" onmouseout="this.style.backgroundColor='#757575'">
                        Cancel
                    </button>
                    <button type="submit" id="approve-submit-btn" class="px-4 py-2 rounded-md shadow-sm text-sm font-medium text-white transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed" style="background-color: #4CAF50;" onmouseover="this.style.backgroundColor='#388E3C'" onmouseout="this.style.backgroundColor='#4CAF50'">
                        <i class="fas fa-check-circle mr-1"></i> Approve Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
