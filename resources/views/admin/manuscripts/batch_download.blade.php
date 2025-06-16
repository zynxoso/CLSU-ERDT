@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manuscript Batch Download</h1>
                <p class="text-gray-600 mt-1">Download {{ $manuscriptCount }} manuscripts ({{ $fileCount }} files total)</p>
            </div>
            <a href="{{ route('admin.manuscripts.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                <i class="fas fa-arrow-left mr-2"></i> Back to Manuscripts
            </a>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                <div>
                    <h3 class="text-blue-800 font-semibold">Alternative Download Method</h3>
                    <p class="text-blue-700 text-sm mt-1">
                        Since ZIP functionality is not available on this server, we've prepared your files for individual download.
                        You can download all files at once using the button below, or download them individually.
                    </p>
                </div>
            </div>
        </div>

        <!-- Download All Button -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900">Download All Files</h3>
                    <p class="text-gray-600 text-sm">Automatically download all {{ $fileCount }} files</p>
                </div>
                <button onclick="downloadAll()" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                    <i class="fas fa-download mr-2"></i> Download All
                </button>
            </div>
            <div id="progress" class="mt-3 text-sm text-gray-600"></div>
            <div id="progressBar" class="hidden mt-2">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div id="progressFill" class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Metadata Download -->
        <div class="border border-gray-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900">
                        <i class="fas fa-file-excel text-green-600 mr-2"></i>
                        Metadata File
                    </h3>
                    <p class="text-gray-600 text-sm">Complete manuscript information in Excel format</p>
                </div>
                <a href="{{ url('/admin/manuscripts/batch-download/' . $batchId . '/manuscripts_metadata.xlsx') }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-download mr-2"></i> Download
                </a>
            </div>
        </div>

        <!-- Individual Files -->
        <div id="fileList">
            <h3 class="font-semibold text-gray-900 mb-4">Individual Files</h3>
            <div class="space-y-4" id="manuscriptFiles">
                <!-- Files will be loaded here via JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
let allFiles = [];
let downloadedCount = 0;

// Load file list
async function loadFileList() {
    try {
        const baseUrl = '{{ url("/admin/manuscripts/batch-download/" . $batchId) }}';
        const response = await fetch(baseUrl + '/file_list.json');
        allFiles = await response.json();
        displayFiles();
    } catch (error) {
        console.error('Error loading file list:', error);
        document.getElementById('manuscriptFiles').innerHTML = '<p class="text-red-600">Error loading file list.</p>';
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
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h4 class="font-medium text-gray-900">${group.manuscript}</h4>
                        <p class="text-sm text-gray-600">Author: ${group.author}</p>
                    </div>
                    <span class="text-sm text-gray-500">${group.files.length} files</span>
                </div>
                <div class="flex flex-wrap gap-2">
        `;

        group.files.forEach(file => {
            const fileName = file.split('/').pop();
            const fileType = fileName.includes('manuscript_info') ? 'info' : 'document';
            const iconClass = fileType === 'info' ? 'fas fa-info-circle text-blue-500' : 'fas fa-file-pdf text-red-500';

            html += `
                <a href="${baseUrl}/${file}"
                   class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded text-sm text-gray-700 transition-colors">
                    <i class="${iconClass} mr-2"></i>
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
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        resolve();
    });
}

// Load files when page loads
document.addEventListener('DOMContentLoaded', loadFileList);
</script>
@endsection
