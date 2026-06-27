import { useQuery } from '@tanstack/react-query';
import api from '../lib/axios';

export default function Dashboard() {
    const { data, isLoading } = useQuery({
        queryKey: ['dashboard'],
        queryFn: () => api.get('/dashboard').then(res => res.data)
    });

    if (isLoading) return <div>Loading...</div>;

    const stats = data?.byStatus || [];
    const getCount = (status) => stats.find(s => s.status === status)?.count || 0;

    return (
        <div className="space-y-6">
            <h1 className="text-2xl font-bold text-gray-900">Dashboard</h1>
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div className="text-sm font-medium text-gray-500">Open Tickets</div>
                    <div className="mt-2 text-3xl font-bold text-gray-900">{getCount('open')}</div>
                </div>
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div className="text-sm font-medium text-gray-500">In Progress</div>
                    <div className="mt-2 text-3xl font-bold text-blue-600">{getCount('pending')}</div>
                </div>
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div className="text-sm font-medium text-gray-500">Resolved</div>
                    <div className="mt-2 text-3xl font-bold text-green-600">{getCount('resolved')}</div>
                </div>
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div className="text-sm font-medium text-gray-500">SLA Breached</div>
                    <div className="mt-2 text-3xl font-bold text-red-600">0</div>
                </div>
            </div>
        </div>
    );
}
