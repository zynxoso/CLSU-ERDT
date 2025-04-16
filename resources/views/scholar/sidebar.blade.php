<ul class="space-y-2 px-4">
    <li>
        <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-4 text-gray-300 hover:bg-slate-800 rounded-lg {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'bg-slate-800' : '' }}">
            <i class="fas fa-tachometer-alt w-5 h-5 mr-2"></i>
            <span>Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ route('fund-requests.index') }}" class="flex items-center py-2 px-4 text-gray-300 hover:bg-slate-800 rounded-lg {{ request()->routeIs('fund-requests.*') ? 'bg-slate-800' : '' }}">
            <i class="fas fa-money-bill-wave w-5 h-5 mr-2"></i>
            <span>Fund Requests</span>
        </a>
    </li>
    <li>
        <a href="{{ route('scholar.manuscripts.index') }}" class="flex items-center py-2 px-4 text-gray-300 hover:bg-slate-800 rounded-lg {{ request()->routeIs('scholar.manuscripts.*') || request()->routeIs('manuscripts.*') ? 'bg-slate-800' : '' }}">
            <i class="fas fa-file-alt w-5 h-5 mr-2"></i>
            <span>Manuscripts</span>
        </a>
    </li>
    <li>
        <a href="{{ route('documents.index') }}" class="flex items-center py-2 px-4 text-gray-300 hover:bg-slate-800 rounded-lg {{ request()->routeIs('documents.*') ? 'bg-slate-800' : '' }}">
            <i class="fas fa-folder w-5 h-5 mr-2"></i>
            <span>Documents</span>
        </a>
    </li>
</ul>
