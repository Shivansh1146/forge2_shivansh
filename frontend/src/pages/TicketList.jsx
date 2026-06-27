import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import api from '../lib/axios';
import { Search, Filter } from 'lucide-react';

export default function TicketList() {
    const [search, setSearch] = useState('');
    const [status, setStatus] = useState('');
    const [priority, setPriority] = useState('');
    const [page, setPage] = useState(1);
    const [breached, setBreached] = useState(false);

    const { data, isLoading } = useQuery({
        queryKey: ['tickets', search, status, priority, page, breached],
        queryFn: () => api.get('/tickets', { params: { search, status, priority, page, breached: breached ? 'true' : '' } }).then(res => res.data)
    });

    return (
        <div className="space-y-6">
            <h1 className="text-2xl font-bold text-gray-900">Tickets</h1>
            
            <div className="flex gap-4 mb-6">
                <div className="flex-1 relative">
                    <Search className="absolute left-3 top-2.5 text-gray-400" size={20} />
                    <input 
                        type="text" 
                        placeholder="Search tickets..." 
                        className="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        value={search}
                        onChange={e => setSearch(e.target.value)}
                    />
                </div>
                <select className="border rounded-lg px-4 py-2" value={status} onChange={e => setStatus(e.target.value)}>
                    <option value="">All Statuses</option>
                    <option value="open">Open</option>
                    <option value="pending">Pending</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
                <select className="border rounded-lg px-4 py-2" value={priority} onChange={e => setPriority(e.target.value)}>
                    <option value="">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
                <label className="flex items-center gap-2">
                    <input type="checkbox" checked={breached} onChange={e => setBreached(e.target.checked)} className="rounded text-red-600 focus:ring-red-500" />
                    <span className="text-sm font-medium text-gray-700">Breached Only</span>
                </label>
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table className="min-w-full divide-y divide-gray-200">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        </tr>
                    </thead>
                    <tbody className="bg-white divide-y divide-gray-200">
                        {isLoading ? (
                            <tr><td colSpan="4" className="px-6 py-4 text-center text-gray-500">Loading...</td></tr>
                        ) : data?.data?.length === 0 ? (
                            <tr><td colSpan="4" className="px-6 py-4 text-center text-gray-500">No tickets found</td></tr>
                        ) : (
                            data?.data?.map(ticket => (
                                <tr key={ticket.id} className={`hover:bg-gray-50 ${ticket.is_breached ? 'bg-red-50' : ''}`} onClick={() => window.location.href = `/tickets/${ticket.id}`} style={{cursor: 'pointer'}}>
                                    <td className="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {ticket.subject}
                                        {ticket.is_breached && <span className="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">SLA Breached</span>}
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap text-gray-500">{ticket.requester?.name}</td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800`}>
                                            {ticket.status}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 whitespace-nowrap">
                                        <span className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800`}>
                                            {ticket.priority}
                                        </span>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
                <div className="px-6 py-3 border-t flex justify-between items-center">
                    <button 
                        disabled={page === 1}
                        onClick={() => setPage(p => p - 1)}
                        className="px-4 py-2 border rounded-md disabled:opacity-50"
                    >
                        Previous
                    </button>
                    <span>Page {page} of {data?.last_page || 1}</span>
                    <button 
                        disabled={page === (data?.last_page || 1)}
                        onClick={() => setPage(p => p + 1)}
                        className="px-4 py-2 border rounded-md disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    );
}
