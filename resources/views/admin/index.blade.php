<x-app-layout>
    <div class="max-w-6xl mx-auto p-6 text-right">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">إدارة المستخدمين</h1>
            <a href="{{ route('admin.users.create') }}"
               class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                إضافة مستخدم جديد
            </a>
        </div>

        <table class="w-full border border-gray-300 text-right">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 border">#</th>
                    <th class="px-4 py-2 border">الاسم</th>
                    <th class="px-4 py-2 border">البريد الإلكتروني</th>
                    <th class="px-4 py-2 border">الدور</th>
                    <th class="px-4 py-2 border">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td class="px-4 py-2 border">{{ $user->id }}</td>
                    <td class="px-4 py-2 border">{{ $user->name }}</td>
                    <td class="px-4 py-2 border">{{ $user->email }}</td>
                    <td class="px-4 py-2 border">
                        {{ $user->role === 'admin' ? 'مدير' : 'مستخدم' }}
                    </td>
                    <td class="px-4 py-2 border flex justify-end gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            تعديل
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                حذف
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
