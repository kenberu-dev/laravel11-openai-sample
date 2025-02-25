import InputLabel from '@/Components/InputLabel';
import TextAreaInput from '@/Components/TextAreaInput';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import ReactMarkdown from 'react-markdown';

export default function Dashboard({ message }) {
    const { data, setData, post, errors } = useForm({
        prompt: '',
    })

    const  onSubmit = (e) => {
        e.preventDefault();
        post(route('send'));
    }
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    ダッシュボード
                </h2>
            }
        >
            <Head title="ダッシュボード" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={onSubmit}>
                                <InputLabel value="質問" />
                                <TextAreaInput
                                    className="mt-1 block w-full"
                                    onChange={(e) => setData('prompt', e.target.value)}
                                />
                                <button
                                    className='btn btn-primary mt-4'
                                >
                                    送信
                                </button>
                            </form>
                        </div>
                        <div className="p-6 text-gray-900">
                            <InputLabel value="回答" />
                            <ReactMarkdown>{message}</ReactMarkdown>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
