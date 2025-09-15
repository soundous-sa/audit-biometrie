<!--@extends('layouts.admin')

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

     <div x-data="fonctionnairesSelector()" class="space-y-4">
    {{-- Dropdown fonctionnaires --}}
    <div>
        <label class="block mb-1 font-semibold">الموظف القائم بالبصمة</label>
        <select x-model="selectedFonct" @change="addFonctionnaire()" class="w-full border rounded px-3 py-2">
            <option value="">-- اختر الموظف --</option>
            @foreach($fonctionnaires as $fonct)
            <option value="{{ $fonct->id }}">{{ $fonct->full_name }} ({{ $fonct->id }})</option>
            @endforeach
        </select>
    </div>

    {{-- Liste des fonctionnaires sélectionnés --}}
    <div class="border rounded p-3">
        <template x-for="(fonct, index) in fonctionnaires" :key="fonct.id">
            <div class="flex justify-between items-center mb-1 bg-gray-100 px-3 py-1 rounded">
                <span x-text="fonct.name"></span>
                <button type="button" @click="removeFonctionnaire(index)" class="text-red-500 font-bold">×</button>
                <input type="hidden" :name="'fonctionnaires[]'" :value="fonct.id">
            </div>
        </template>
        <div x-show="fonctionnaires.length == 0" class="text-gray-400">لا يوجد موظفون مختارون بعد</div>
    </div>
</div>

<script>
function fonctionnairesSelector() {
    return {
        selectedFonct: '',
        fonctionnaires: [],
        addFonctionnaire() {
            if(this.selectedFonct === '') return;

            // Vérifie si déjà ajouté
            if(!this.fonctionnaires.find(f => f.id == this.selectedFonct)) {
                let option = document.querySelector(`select option[value='${this.selectedFonct}']`);
                this.fonctionnaires.push({id: this.selectedFonct, name: option.text});
            }
            this.selectedFonct = '';
        },
        removeFonctionnaire(index) {
            this.fonctionnaires.splice(index, 1);
        }
    }
}
</script>


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
@endsection-->
@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">إضافة عملية تحيين البصمات</h2>

    <form action="{{ route('audits.store') }}" method="POST">
        @csrf
        @include('user.audits.form', [
            'audit' => new App\Models\Audit(),
            'selectedFonctionnaires' => []
        ])
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg">
            إضافة تحيين
        </button>
    </form>
</div>
@endsection

