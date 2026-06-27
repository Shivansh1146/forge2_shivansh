import { useState } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import api from '../lib/axios';

export default function Register() {
    const [form, setForm] = useState({ name: '', email: '', password: '', organization_slug: '' });
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const { data } = await api.post('/register', form);
            localStorage.setItem('token', data.token);
            navigate('/');
        } catch (error) {
            alert('Registration failed');
        }
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
            <div className="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <div>
                    <h2 className="text-center text-3xl font-extrabold text-gray-900">Create Account</h2>
                </div>
                <form className="mt-8 space-y-6" onSubmit={handleSubmit}>
                    <div className="space-y-4">
                        <input type="text" required className="w-full px-3 py-2 border rounded-md" placeholder="Full Name" onChange={e => setForm({...form, name: e.target.value})} />
                        <input type="email" required className="w-full px-3 py-2 border rounded-md" placeholder="Email address" onChange={e => setForm({...form, email: e.target.value})} />
                        <input type="password" required className="w-full px-3 py-2 border rounded-md" placeholder="Password" onChange={e => setForm({...form, password: e.target.value})} />
                        <input type="text" required className="w-full px-3 py-2 border rounded-md" placeholder="Organization Slug (e.g. acme)" onChange={e => setForm({...form, organization_slug: e.target.value})} />
                    </div>
                    <button type="submit" className="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Register
                    </button>
                </form>
                <div className="text-center text-sm">
                    <Link to="/login" className="text-blue-600 hover:text-blue-500">Already have an account? Sign in</Link>
                </div>
            </div>
        </div>
    );
}
