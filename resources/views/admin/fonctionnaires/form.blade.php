<form action="{{ isset($fonctionnaire) ? route('admin.fonctionnaires.update', $fonctionnaire->id) : route('admin.fonctionnaires.store') }}" method="POST" class="space-y-4">
    @csrf
    @if(isset($fonctionnaire))
        @method('PUT')
    @endif

    <div>
        <label class="block text-gray-700 font-bold mb-1">الإسم</label>
        <input type="text" name="firstName" value="{{ $fonctionnaire->firstName ?? '' }}" class="w-full border rounded px-3 py-2" required>
    </div>

    <div>
        <label class="block text-gray-700 font-bold mb-1">النسب</label>
        <input type="text" name="lastName" value="{{ $fonctionnaire->lastName ?? '' }}" class="w-full border rounded px-3 py-2" required>
    </div>

    <div>
        <label class="block text-gray-700 font-bold mb-1">الهاتف</label>
        <input type="text" name="phone" value="{{ $fonctionnaire->phone ?? '' }}" class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label class="block text-gray-700 font-bold mb-1">رقم التسجيل</label>
        <input type="matricule" name="matricule" value="{{ $fonctionnaire->matricule ?? '' }}" class="w-full border rounded px-3 py-2" required>
    </div>

    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{ isset($fonctionnaire) ? 'تحديث' : 'إضافة' }}
    </button>
</form>
