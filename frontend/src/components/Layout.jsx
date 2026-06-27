import { Outlet, Link, useNavigate } from 'react-router-dom';
import { LogOut, LayoutDashboard, Ticket, PlusCircle } from 'lucide-react';
import api from '../lib/axios';

export default function Layout() {
    const navigate = useNavigate();

    const handleLogout = async () => {
        try {
            await api.post('/logout');
            localStorage.removeItem('token');
            navigate('/login');
        } catch (error) {
            console.error(error);
        }
    };

    return (
        <div className="flex h-screen bg-gray-50">
            {/* Sidebar */}
            <div className="w-64 bg-white border-r flex flex-col">
                <div className="h-16 flex items-center px-6 border-b font-bold text-xl text-blue-600">
                    PulseDesk
                </div>
                <nav className="flex-1 p-4 space-y-2">
                    <Link to="/" className="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-md hover:bg-gray-100">
                        <LayoutDashboard size={20} /> Dashboard
                    </Link>
                    <Link to="/tickets" className="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-md hover:bg-gray-100">
                        <Ticket size={20} /> Tickets
                    </Link>
                    <Link to="/tickets/new" className="flex items-center gap-3 px-3 py-2 text-gray-700 rounded-md hover:bg-gray-100">
                        <PlusCircle size={20} /> New Ticket
                    </Link>
                </nav>
            </div>

            {/* Main Content */}
            <div className="flex-1 flex flex-col overflow-hidden">
                <header className="h-16 bg-white border-b flex items-center justify-end px-6 shadow-sm">
                    <button 
                        onClick={handleLogout}
                        className="flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium"
                    >
                        <LogOut size={18} /> Logout
                    </button>
                </header>
                <main className="flex-1 overflow-y-auto p-6">
                    <Outlet />
                </main>
            </div>
        </div>
    );
}
