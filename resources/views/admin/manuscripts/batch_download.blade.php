@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto" style="background-color: #FAFAFA; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;">
    <div class="rounded-lg shadow-lg p-6" style="background-color: white; border: 1px solid #E0E0E0;">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold" style="color: #212121; font-size: 24px;">Manuscript Batch Download</h1>
                <p class="mt-1" style="color: #424242; font-size: 15px;">Download {{ $manuscriptCount }} manuscripts ({{ $fileCount }} files total)</p>
            </div>
            <a href="{{ route('admin.manuscripts.index') }}"
               class="px-4 py-2 rounded-lg transition-colors duration-200"
               style="background-color: #424242; color: white; font-size: 15px;"
               onmouseover="this.style.backgroundColor='#212121'"
               onmouseout="this.style.backgroundColor='#424242'">
                <i class="fas fa-arrow-left mr-2"></i> Back to Manuscripts
            </a>
        </div>

        <div class="border rounded-lg p-4 mb-6" style="background-color: #E8F5E8; border-color: #2E7D32;">
            <div class="flex items-start">
                <i class="fas fa-info-circle mt-1 mr-3" style="color: #2E7D32;"></i>
                <div>
                    <h3 class="font-semibold" style="color: #1B5E20; font-size: 16px;">Alternative Download Method</h3>
                    <p class="mt-1" style="color: #2E7D32; font-size: 14px;">
                        Since ZIP functionality is not available on this server, we've prepared your files for individual download.
                        You can download all files at once using the button below, or download them individually.
                    </p>
                </div>
            </div>
        </div>

        <!-- Download All Button -->
        <div class="rounded-lg p-4 mb-6" style="background-color: #F8F9FA; border: 1px solid #E0E0E0;">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold" style="color: #212121; font-size: 16px;">Download All Files</h3>
                    <p class="text-sm" style="color: #424242; font-size: 14px;">Automatically download all {{ $fileCount }} files</p>
                </div>
                <button onclick="downloadAll()"
                        class="px-6 py-3 rounded-lg font-semibold transition-colors duration-200"
                        style="background-color: #2E7D32; color: white; font-size: 15px;"
                        onmouseover="this.style.backgroundColor='#1B5E20'"
                        onmouseout="this.style.backgroundColor='#2E7D32'">
                    <i class="fas fa-download mr-2"></i> Download All
                </button>
            </div>
            <div id="progress" class="mt-3" style="color: #424242; font-size: 14px;"></div>
            <div id="progressBar" class="hidden mt-2">
                <div class="w-full rounded-full h-2" style="background-color: #E0E0E0;">
                    <div id="progressFill" class="h-2 rounded-full transition-all duration-300" style="width: 0%; background-color: #2E7D32;"></div>
                </div>
            </div>
        </div>

        <!-- Metadata Download -->
        <div class="border rounded-lg p-4 mb-6" style="border-color: #E0E0E0; background-color: white;">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold" style="color: #212121; font-size: 16px;">
                        <i class="fas fa-file-excel mr-2" style="color: #2E7D32;"></i>
                        Metadata File
                    </h3>
                    <p class="text-sm" style="color: #424242; font-size: 14px;">Complete manuscript information in Excel format</p>
                </div>
                <a href="{{ url('/admin/manuscripts/batch-download/' . $batchId . '/manuscripts_metadata.xlsx') }}"
                   class="px-4 py-2 rounded-lg batch-download-link transition-colors duration-200"
                   style="background-color: #1976D2; color: white; font-size: 15px;"
                   onmouseover="this.style.backgroundColor='#1565C0'"
                   onmouseout="this.style.backgroundColor='#1976D2'">
                    <i class="fas fa-download mr-2"></i> Download
                </a>
            </div>
        </div>

        <!-- Individual Files -->
        <div id="fileList">
            <h3 class="font-semibold mb-4" style="color: #212121; font-size: 18px;">Individual Files</h3>
            <div class="space-y-4" id="manuscriptFiles">
                <!-- Files will be loaded here via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
let allFiles = [];
let downloadedCount = 0;

// Load file list using safe fetch to avoid universal loader
async function loadFileList() {
    try {
        const baseUrl = '{{ url("/admin/manuscripts/batch-download/" . $batchId) }}';
        // Use safeFetch to prevent universal loader from showing
        const response = await window.safeFetch(baseUrl + '/file_list.json');
        allFiles = await response.json();
        displayFiles();
    } catch (error) {
        console.error('Error loading file list:', error);
        document.getElementById('manuscriptFiles').innerHTML = '<p style="color: #D32F2F;">Error loading file list.</p>';
    }
}

// Display files grouped by manuscript
function displayFiles() {
    const container = document.getElementById('manuscriptFiles');
    const groupedFiles = {};
    const baseUrl = '{{ url("/admin/manuscripts/batch-download/" . $batchId) }}';

    // Group files by manuscript
    allFiles.forEach(file => {
        const parts = file.split('/');
        if (parts.length >= 2) {
            const author = parts[0];
            const manuscript = parts[1];
            const key = `${author}/${manuscript}`;

            if (!groupedFiles[key]) {
                groupedFiles[key] = {
                    author: author.replace(/-/g, ' '),
                    manuscript: manuscript.replace(/-/g, ' '),
                    files: []
                };
            }
            groupedFiles[key].files.push(file);
        }
    });

    // Create HTML for each manuscript group
    let html = '';
    Object.keys(groupedFiles).forEach(key => {
        const group = groupedFiles[key];
        html += `
            <div class="border rounded-lg p-4" style="border-color: #E0E0E0; background-color: white;">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="font-medium" style="color: #212121; font-size: 16px;">${group.manuscript}</h4>
                        <p style="color: #424242; font-size: 14px;">Author: ${group.author}</p>
                    </div>
                    <span style="color: #757575; font-size: 14px;">${group.files.length} files</span>
                </div>
                <div class="flex flex-wrap gap-2">
        `;

        group.files.forEach(file => {
            const fileName = file.split('/').pop();
            const fileType = fileName.includes('manuscript_info') ? 'info' : 'document';
            const iconClass = fileType === 'info' ? 'fas fa-info-circle' : 'fas fa-file-pdf';
            const iconColor = fileType === 'info' ? '#1976D2' : '#D32F2F';

            html += `
                <a href="${baseUrl}/${file}"
                   class="inline-flex items-center px-3 py-1 rounded transition-colors batch-download-link"
                   style="background-color: #F8F9FA; color: #424242; font-size: 14px; border: 1px solid #E0E0E0;"
                   onmouseover="this.style.backgroundColor='#F0F0F0'"
                   onmouseout="this.style.backgroundColor='#F8F9FA'">
                    <i class="${iconClass} mr-2" style="color: ${iconColor};"></i>
                    ${fileName}
                </a>
            `;
        });

        html += `
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

// Download all files function
async function downloadAll() {
    if (allFiles.length === 0) {
        alert('No files to download');
        return;
    }

    const progress = document.getElementById('progress');
    const progressBar = document.getElementById('progressBar');
    const progressFill = document.getElementById('progressFill');
    const baseUrl = '{{ url("/admin/manuscripts/batch-download/" . $batchId) }}';

    progressBar.classList.remove('hidden');
    downloadedCount = 0;

    // First download metadata
    progress.innerHTML = 'Downloading metadata file...';
    await downloadFile(baseUrl + '/manuscripts_metadata.xlsx', 'manuscripts_metadata.xlsx');

    // Then download all other files
    for (let i = 0; i < allFiles.length; i++) {
        const file = allFiles[i];
        const fileName = file.split('/').pop();
        const fileUrl = baseUrl + '/' + file;

        progress.innerHTML = `Downloading ${i + 1} of ${allFiles.length}: ${fileName}`;
        progressFill.style.width = `${((i + 1) / allFiles.length) * 100}%`;

        await downloadFile(fileUrl, fileName);
        await new Promise(resolve => setTimeout(resolve, 300)); // Small delay between downloads
    }

    progress.innerHTML = `Download complete! ${allFiles.length + 1} files downloaded.`;
    progressFill.style.width = '100%';
}

// Helper function to download a single file
function downloadFile(url, filename) {
    return new Promise((resolve) => {
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        link.style.display = 'none';
        // Add class to prevent universal loader
        link.classList.add('batch-download-link');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        resolve();
    });
}

// Removed universal loader skip logic
document.addEventListener('DOMContentLoaded', function() {
    // Removed universal loader skip logic
    loadFileList();
});
</script>
@endsection
