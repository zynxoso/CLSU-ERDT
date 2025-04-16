<div class="sidebar-menu">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('scholars.index') }}" class="{{ request()->routeIs('scholars.*') ? 'active' : '' }}">
        <i class="fas fa-user-graduate"></i>
        <span>Scholars</span>
    </a>

    <a href="{{ route('fund-requests.index') }}" class="{{ request()->routeIs('fund-requests.*') ? 'active' : '' }}">
        <i class="fas fa-money-bill-wave"></i>
        <span>Fund Requests</span>
    </a>

    <a href="{{ route('disbursements.index') }}" class="{{ request()->routeIs('disbursements.*') ? 'active' : '' }}">
        <i class="fas fa-hand-holding-usd"></i>
        <span>Disbursements</span>
    </a>

    <a href="{{ route('admin.manuscripts.index') }}" class="{{ request()->routeIs('admin.manuscripts.*') ? 'active' : '' }}">
        <i class="fas fa-file-alt"></i>
        <span>Manuscripts</span>
    </a>

    <a href="{{ route('documents.index') }}" class="{{ request()->routeIs('documents.*') ? 'active' : '' }}">
        <i class="fas fa-file-pdf"></i>
        <span>Documents</span>
    </a>
</div>
