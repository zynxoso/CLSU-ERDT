import {
    BarChart2,
    BookOpen,
    ClipboardList,
    FileText,
    History,
    LayoutDashboard,
    Menu,
    Settings,
    User,
    Users,
    X
} from 'lucide-react';
import { useState } from 'react';
import { Link, Outlet, useLocation } from 'react-router-dom';

export default function AdminLayout() {
    const [sidebarOpen, setSidebarOpen] = useState(true);
    const location = useLocation();

    const navigation = [
        { name: 'Dashboard', href: '/admin', icon: LayoutDashboard },
        { name: 'Analytics', href: '/admin/analytics', icon: BarChart2 },
        { name: 'Scholars', href: '/admin/scholars', icon: Users },
        { name: 'Fund Requests', href: '/admin/fund-requests', icon: FileText },
        { name: 'Documents', href: '/admin/documents', icon: ClipboardList },
        { name: 'Manuscripts', href: '/admin/manuscripts', icon: BookOpen },
        { name: 'Reports', href: '/admin/reports', icon: FileText },
        { name: 'Audit Logs', href: '/admin/audit-logs', icon: History },
        { name: 'Settings', href: '/admin/settings', icon: Settings },
    ];

    return (
        <div className="min-h-screen bg-gray-100">
            {/* Mobile sidebar */}
            <div className={`fixed inset-0 z-40 lg:hidden ${sidebarOpen ? 'block' : 'hidden'}`}>
                <div className="fixed inset-0 bg-gray-600 bg-opacity-75" onClick={() => setSidebarOpen(false)}></div>
                <div className="fixed inset-y-0 left-0 flex flex-col w-64 bg-white shadow-lg">
                    <div className="flex items-center justify-between h-16 px-4 bg-gray-800">
                        <span className="text-white font-semibold text-lg">Admin Panel</span>
                        <button onClick={() => setSidebarOpen(false)} className="text-white">
                            <X className="h-6 w-6" />
                        </button>
                    </div>
                    <div className="flex-1 overflow-y-auto">
                        <nav className="px-2 py-4">
                            {navigation.map((item) => {
                                const Icon = item.icon;
                                return (
                                    <Link
                                        key={item.name}
                                        to={item.href}
                                        className={`flex items-center px-4 py-2 text-sm font-medium rounded-md ${
                                            location.pathname === item.href
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-700 hover:bg-gray-700 hover:text-white'
                                        }`}
                                    >
                                        <Icon className="mr-3 h-5 w-5" />
                                        {item.name}
                                    </Link>
                                );
                            })}
                        </nav>
                    </div>
                </div>
            </div>

            {/* Desktop sidebar */}
            <div className="hidden lg:flex lg:flex-shrink-0">
                <div className="flex flex-col w-64">
                    <div className="flex flex-col flex-grow bg-gray-800 pt-5 pb-4 overflow-y-auto">
                        <div className="flex items-center flex-shrink-0 px-4">
                            <span className="text-white font-semibold text-lg">Admin Panel</span>
                        </div>
                        <nav className="mt-5 flex-1 px-2 space-y-1">
                            {navigation.map((item) => {
                                const Icon = item.icon;
                                return (
                                    <Link
                                        key={item.name}
                                        to={item.href}
                                        className={`flex items-center px-4 py-2 text-sm font-medium rounded-md ${
                                            location.pathname === item.href
                                                ? 'bg-gray-900 text-white'
                                                : 'text-gray-300 hover:bg-gray-700 hover:text-white'
                                        }`}
                                    >
                                        <Icon className="mr-3 h-5 w-5" />
                                        {item.name}
                                    </Link>
                                );
                            })}
                        </nav>
                    </div>
                </div>
            </div>

            {/* Main content */}
            <div className="flex flex-col flex-1 lg:pl-64">
                <div className="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white shadow">
                    <button
                        type="button"
                        className="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 lg:hidden"
                        onClick={() => setSidebarOpen(true)}
                    >
                        <Menu className="h-6 w-6" />
                    </button>
                    <div className="flex-1 px-4 flex justify-between">
                        <div className="flex-1 flex">
                            {/* Search bar can be added here */}
                        </div>
                        <div className="ml-4 flex items-center md:ml-6">
                            <Link
                                to="/admin/profile"
                                className="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                <User className="h-6 w-6" />
                            </Link>
                        </div>
                    </div>
                </div>

                <main className="flex-1 pb-8">
                    <div className="py-6">
                        <div className="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            <Outlet />
                        </div>
                    </div>
                </main>
            </div>
        </div>
    );
}
