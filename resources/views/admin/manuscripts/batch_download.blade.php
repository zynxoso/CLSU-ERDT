@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto" class="bg-gray-50 min-h-screen font-sans">
    <div class="rounded-lg shadow-lg p-6" style="background-color: rgb(255 255 255); border: 1px solid #E0E0E0;">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manuscript Batch Download</h1>
            <p class="mt-1 text-gray-600 text-sm">Download {{ $manuscriptCount }} manuscripts ({{ $fileCount }} files total)</p>
            </div>
            <a href="{{ route('admin.manuscripts.index') }}"
               class="px-4 py-2 rounded-lg transition-colors duration-200 bg-gray-600 text-white text-sm hover:bg-gray-800">
                <i class="fas fa-arrow-left mr-2"></i> Back to Manuscripts
            </a>
        </div>

        <div class="border rounded-lg p-4 mb-6 bg-green-50 border-green-500">
            <div class="flex items-start">
                <i class="fas fa-info-circle mt-1 mr-3 text-green-500"></i>
                <div>
                    <h3 class="font-semibold text-green-600 text-base">Alternative Download Method</h3>
                    <p class="mt-1 text-green-600 text-sm">
                        Since ZIP functionality is not available on this server, we've prepared your files for individual download.
                        You can download all files at once using the button below, or download them individually.
                    </p>
                </div>
            </div>
        </div>

        <!-- Download All Button -->
        <div class="rounded-lg p-4 mb-6 bg-gray-50 border border-gray-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900 text-base">Download All Files</h3>
                    <p class="text-sm text-gray-600">Automatically download all {{ $fileCount }} files</p>
                </div>
                <button onclick="downloadAll()"
                        class="px-6 py-3 rounded-lg font-semibold transition-colors duration-200 bg-green-500 text-white text-sm hover:bg-green-600">
                    <i class="fas fa-download mr-2"></i> Download All
                </button>
            </div>
            <div id="progress" class="mt-3 text-gray-600 text-sm"></div>
            <div id="progressBar" class="hidden mt-2">
                <div class="w-full rounded-full h-2 bg-gray-300">
                    <div id="progressFill" class="h-2 rounded-full transition-all duration-300 bg-green-500" style="width: 0%;"></div>
                </div>
            </div>
        </div>

        <!-- Metadata Download -->
        <div class="border rounded-lg p-4 mb-6 border-gray-300 bg-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900 text-base">
                        <i class="fas fa-file-excel mr-2 text-green-500"></i>
                        Metadata File
                    </h3>
                    <p class="text-sm text-gray-600">Complete manuscript information in Excel format</p>
                </div>
                <a href="{{ url('/admin/manuscripts/batch-download/' . $batchId . '/manuscripts_metadata.xlsx') }}"
                   class="px-4 py-2 rounded-lg batch-download-link transition-colors duration-200 bg-green-500 text-white text-sm hover:bg-green-600"
                    <i class="fas fa-download mr-2"></i> Download
                </a>
            </div>
        </div>

        <!-- Individual Files -->
        <div id="fileList">
            <h3 class="font-semibold mb-4 text-gray-900 text-lg">Individual Files</h3>
            <div class="space-y-4" id="manuscriptFiles">
                <!-- Files will be loaded here via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
let allFiles = [];

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
            <div class="border rounded-lg p-4" style="border-color: rgb(224 224 224); background-color: rgb(255 255 255);">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="font-medium" style="color: rgb(23 23 23); font-size: 16px;">${group.manuscript}</h4>
                        <p style="color: rgb(64 64 64); font-size: 14px;">Author: ${group.author}</p>
                    </div>
                    <span style="color: rgb(115 115 115); font-size: 14px;">${group.files.length} files</span>
                </div>
                <div class="flex flex-wrap gap-2">
        `;

        group.files.forEach(file => {
            const fileName = file.split('/').pop();
            const fileType = fileName.includes('manuscript_info') ? 'info' : 'document';
            const iconClass = fileType === 'info' ? 'fas fa-info-circle' : 'fas fa-file-pdf';
            const iconColor = fileType === 'info' ? '#4A90E2' : '#D32F2F';

            html += `
                <a href="${baseUrl}/${file}"
                   class="inline-flex items-center px-3 py-1 rounded transition-colors batch-download-link"
                   style="background-color: #F8F9FA; color: rgb(64 64 64); font-size: 14px; border: 1px solid #E0E0E0;"
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
        // Mark download start to prevent global loader
        if (window.markDownloadStart) {
            window.markDownloadStart();
        }
        
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        link.style.display = 'none';
        // Add class to prevent universal loader
        link.classList.add('batch-download-link');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Mark download end after a short delay
        setTimeout(() => {
            if (window.markDownloadEnd) {
                window.markDownloadEnd();
            }
            resolve();
        }, 500);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    loadFileList();
});
</script>
@endsection
