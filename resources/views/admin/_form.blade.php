@csrf
<div class="space-y-4">
    <div>
        <label class="block mb-1">الاسم الكامل</label>
        <input type="text" name="full_name" value="{{ old('full_name', $fonctionnaire->full_name ?? '') }}"
               class="w-full border rounded px-3 py-2" required>
        @error('full_name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
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
