@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">إضافة عملية تحيين البصمات</h2>
    
    <form action="{{ route('audits.store') }}" method="POST" class="space-y-4">
        @csrf

        <div class="mb-4">
    <label for="etab_id" class="block text-gray-700 font-semibold mb-2">المؤسسة السجنية</label>
    <select name="etab_id" id="etab_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
        <option value="">-- اختر المؤسسة --</option>
        @foreach($etablissements as $etablissement)
            <option value="{{ $etablissement->id }}">{{ $etablissement->libelle }}</option>
        @endforeach
    </select>
</div>

        <div>
            <label for="date_audit" class="block text-gray-700 font-medium mb-1">التاريخ</label>
            <input type="date" name="date_audit" id="date_audit" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

      <div class="mb-4">
    <label for="fonct_id" class="block text-gray-700 font-semibold mb-2">الموظف القائم بالبصمة</label>
    <select name="fonct_id" id="fonct_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
        <option value="">-- اختر الموظف --</option>
        @foreach($fonctionnaires as $fonctionnaire)
            <option value="{{ $fonctionnaire->id }}">
                 {{ $fonctionnaire->full_name }} ({{ $fonctionnaire->matricule }})
            </option>
        @endforeach
    </select>
</div>

        <div>
            <label for="nb_detenus" class="block text-gray-700 font-semibold mb-2">عدد المعتقلين</label>
            <input type="text" name="nb_detenus" id="nb_detenus" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div>
            <label for="nb_edited_fingerprints" class="block text-gray-700 font-semibold mb-2">عدد البصمات المحينة</label>
            <input type="text" name="nb_edited_fingerprints" id="nb_edited_fingerprints" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div>
            <label for="nb_verified_fingerprints" class="block text-gray-700 font-semibold mb-2">محينة ب 10 أصابع</label>
            <input type="text" name="nb_verified_fingerprints" id="nb_verified_fingerprints" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div>
            <label for="nb_without_fingerprints" class="block text-gray-700 font-semibold mb-2">معاينة عينة دون أخذ بصمتهم</label>
            <input type="text" name="nb_without_fingerprints" id="nb_without_fingerprints" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">إضافة تحيين</button>
    </form>
</div>
@endsection
