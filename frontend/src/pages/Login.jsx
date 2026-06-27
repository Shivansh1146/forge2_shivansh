import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import toast from 'react-hot-toast';
import api from '../lib/axios';

export default function Login() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const { data } = await api.post('/login', { email, password });
            localStorage.setItem('token', data.token);
            toast.success('Logged in successfully!');
            navigate('/');
        } catch (error) {
            const msg = error.response?.data?.message || 'Login failed. Please check your credentials.';
            toast.error(msg);
        }
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <div>
                    <h2 className="text-center text-3xl font-extrabold text-gray-900">Sign in</h2>
                </div>
                <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
                    <div className="space-y-4">
                        <input
                            type="email" required className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Email address" value={email} onChange={e => setEmail(e.target.value)}
                        />
                        <input
                            type="password" required className="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Password" value={password} onChange={e => setPassword(e.target.value)}
                        />
                    </div>
                    <button type="submit" className="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Sign In
                    </button>
                </form>
                <div className="text-center text-sm">
                    <Link to="/register" className="text-blue-600 hover:text-blue-500">Don't have an account? Register</Link>
                </div>
            </div>
        </div>
    );
}
