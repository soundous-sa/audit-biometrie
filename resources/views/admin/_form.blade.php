@csrf
<div class="space-y-4">
    <div>
        <label class="block mb-1">الاسم الشخصي</label>
        <input type="text" name="firstName" value="{{ old('firstName', $fonctionnaire->firstName ?? '') }}"
               class="w-full border rounded px-3 py-2" required>
        @error('firstName') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block mb-1">الاسم العائلي</label>
        <input type="text" name="lastName" value="{{ old('lastName', $fonctionnaire->lastName ?? '') }}"
               class="w-full border rounded px-3 py-2" required>
        @error('lastName') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block mb-1">الهاتف</label>
        <input type="text" name="phone" value="{{ old('phone', $fonctionnaire->phone ?? '') }}"
               class="w-full border rounded px-3 py-2" required>
        @error('phone') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block mb-1">الرقم التأجيري (Matricule)</label>
        <input type="text" name="matricule" value="{{ old('matricule', $fonctionnaire->matricule ?? '') }}"
               class="w-full border rounded px-3 py-2" required>
        @error('matricule') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
    </div>

    <div class="flex justify-end gap-2">
        <a href="{{ route('admin.fonctionnaires.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">إلغاء</a>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">حفظ</button>
    </div>
</div>
