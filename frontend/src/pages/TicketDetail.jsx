import { useState } from 'react';
import { useParams } from 'react-router-dom';
import { useQuery, useQueryClient } from '@tanstack/react-query';
import api from '../lib/axios';
import toast from 'react-hot-toast';

export default function TicketDetail() {
    const { id } = useParams();
    const queryClient = useQueryClient();
    const [replyBody, setReplyBody] = useState('');
    const [replyType, setReplyType] = useState('public_reply');

    const { data: ticket, isLoading } = useQuery({
        queryKey: ['ticket', id],
        queryFn: () => api.get(`/tickets/${id}`).then(res => res.data)
    });

    const submitReply = async (e) => {
        e.preventDefault();
        try {
            await api.post(`/tickets/${id}/conversations`, {
                body: replyBody,
                type: replyType
            });
            toast.success('Reply added successfully');
            setReplyBody('');
            queryClient.invalidateQueries(['ticket', id]);
        } catch (error) {
            const msg = error.response?.data?.message || 'Failed to add reply';
            toast.error(msg);
        }
    };

    if (isLoading) return <div>Loading...</div>;
    if (!ticket) return <div>Ticket not found</div>;

    const sortedActivities = [...(ticket.activity_logs || [])].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    const sortedConversations = [...(ticket.conversations || [])].sort((a, b) => new Date(a.created_at) - new Date(b.created_at));

    return (
        <div className="grid grid-cols-3 gap-6">
            <div className="col-span-2 space-y-6">
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div className="flex justify-between items-start mb-4">
                        <div>
                            <h1 className="text-2xl font-bold text-gray-900">{ticket.subject}</h1>
                            <div className="text-sm text-gray-500 mt-1">
                                Reported by {ticket.requester?.name} • {new Date(ticket.created_at).toLocaleString()}
                            </div>
                        </div>
                        {ticket.is_breached && (
                            <span className="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">SLA Breached</span>
                        )}
                    </div>
                    <div className="prose max-w-none text-gray-700">
                        {ticket.description}
                    </div>
                </div>

                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h2 className="text-lg font-bold text-gray-900 mb-4">Conversation</h2>
                    <div className="space-y-4 mb-6">
                        {sortedConversations.map(conv => (
                            <div key={conv.id} className={`p-4 rounded-lg ${conv.type === 'internal_note' ? 'bg-yellow-50 border border-yellow-200' : 'bg-gray-50 border border-gray-200'}`}>
                                <div className="flex justify-between text-sm mb-2">
                                    <span className="font-semibold text-gray-900">{conv.user?.name}</span>
                                    <span className="text-gray-500">{new Date(conv.created_at).toLocaleString()}</span>
                                </div>
                                <div className="text-gray-800 whitespace-pre-wrap">{conv.body}</div>
                                {conv.type === 'internal_note' && <div className="mt-2 text-xs font-semibold text-yellow-700 uppercase">Internal Note</div>}
                            </div>
                        ))}
                    </div>

                    <form onSubmit={submitReply} className="space-y-4 border-t pt-4">
                        <textarea 
                            required rows={4}
                            className="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Type your reply here..."
                            value={replyBody} onChange={e => setReplyBody(e.target.value)}
                        ></textarea>
                        <div className="flex justify-between items-center">
                            <select 
                                className="border rounded-lg px-3 py-2"
                                value={replyType} onChange={e => setReplyType(e.target.value)}
                            >
                                <option value="public_reply">Public Reply</option>
                                <option value="internal_note">Internal Note</option>
                            </select>
                            <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div className="space-y-6">
                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 className="font-bold text-gray-900 mb-4">Ticket Info</h3>
                    <div className="space-y-3 text-sm">
                        <div className="flex justify-between">
                            <span className="text-gray-500">Status</span>
                            <span className="font-medium capitalize">{ticket.status}</span>
                        </div>
                        <div className="flex justify-between">
                            <span className="text-gray-500">Priority</span>
                            <span className="font-medium capitalize">{ticket.priority}</span>
                        </div>
                        <div className="flex justify-between">
                            <span className="text-gray-500">Assignee</span>
                            <span className="font-medium">{ticket.assignee?.name || 'Unassigned'}</span>
                        </div>
                    </div>
                </div>

                <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 className="font-bold text-gray-900 mb-4">Activity Timeline</h3>
                    <div className="space-y-4">
                        {sortedActivities.map(log => (
                            <div key={log.id} className="flex gap-3">
                                <div className="w-2 h-2 mt-1.5 rounded-full bg-blue-500 flex-shrink-0"></div>
                                <div>
                                    <p className="text-sm text-gray-900">
                                        <span className="font-medium">{log.user?.name}</span> {log.action.replace('_', ' ')}
                                    </p>
                                    <p className="text-xs text-gray-500">{new Date(log.created_at).toLocaleString()}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}
