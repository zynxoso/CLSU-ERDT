<!-- Add a password change section to the scholar profile page -->
<div class="bg-white rounded-lg p-6 border border-gray-200 shadow-sm mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold text-gray-800">Account Security</h2>
        <a href="{{ route('scholar.password.change') }}" class="text-blue-500 hover:text-blue-700 flex items-center">
            <i class="fas fa-key mr-1"></i> Change Password
        </a>
    </div>

    <div class="text-gray-600">
        <p>If you're using the default password, we strongly recommend changing it to a secure password that only you know.</p>
        <div class="mt-4 p-3 bg-yellow-50 rounded-md border border-yellow-200">
            <p class="text-yellow-700"><i class="fas fa-exclamation-triangle mr-1"></i> Default passwords should be changed immediately for security reasons.</p>
        </div>
    </div>
</div>
