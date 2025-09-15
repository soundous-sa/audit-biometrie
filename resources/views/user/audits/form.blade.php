<div class="space-y-4">
    {{-- Établissement --}}
    <div class="mb-4">
        <label for="etab_id" class="block text-gray-700 font-semibold mb-2">المؤسسة السجنية</label>
        <select name="etab_id" id="etab_id"
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
            <option value="">-- اختر المؤسسة --</option>
            @foreach($etablissements as $etablissement)
            <option value="{{ $etablissement->id }}"
                {{ old('etab_id', $audit->etab_id ?? '') == $etablissement->id ? 'selected' : '' }}>
                {{ $etablissement->libelle }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Date --}}
    <div>
        <label for="date_audit" class="block text-gray-700 font-medium mb-1">التاريخ</label>
        <input type="date" name="date_audit" id="date_audit"
            value="{{ old('date_audit', $audit->date_audit ?? '') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required>
    </div>

    {{-- Fonctionnaires --}}
    <div
        x-data="fonctionnairesSelector({!! json_encode($selectedFonctionnaires ?? []) !!})"
        x-init="init()"
        class="space-y-4">

        <div>
            <label class="block text-gray-700 font-semibold mb-2">الموظفون القائمون بالبصمة</label>
            <select x-model="selectedFonct" @change="addFonctionnaire()"
                class="w-full border rounded px-3 py-2">
                <option value="">-- اختر الموظف --</option>
                @foreach($fonctionnaires as $fonct)
                <option value="{{ $fonct->id }}">{{ $fonct->full_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mt-3 border rounded p-3">
            <template x-for="(fonct, index) in fonctionnaires" :key="fonct.id">
                <div class="flex justify-between items-center mb-1 bg-gray-100 px-3 py-2 rounded">
                    <span x-text="fonct.name"></span>
                    <button type="button" @click="removeFonctionnaire(index)" class="text-red-500 font-bold">×</button>
                    <input type="hidden" name="fonctionnaires[]" :value="fonct.id">
                </div>
            </template>
            <div x-show="fonctionnaires.length == 0" class="text-gray-400">لا يوجد موظفون مختارون بعد</div>
        </div>
    </div>

    {{-- Autres champs --}}
    <div>
        <label for="nb_detenus" class="block text-gray-700 font-semibold mb-2">عدد المعتقلين</label>
        <input type="text" name="nb_detenus" id="nb_detenus"
            value="{{ old('nb_detenus', $audit->nb_detenus ?? '') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required>
    </div>

    <div>
        <label for="nb_edited_fingerprints" class="block text-gray-700 font-semibold mb-2">عدد البصمات المحينة</label>
        <input type="text" name="nb_edited_fingerprints" id="nb_edited_fingerprints"
            value="{{ old('nb_edited_fingerprints', $audit->nb_edited_fingerprints ?? '') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required>
    </div>

    <div>
        <label for="nb_verified_fingerprints" class="block text-gray-700 font-semibold mb-2">محينة ب 10 أصابع</label>
        <input type="text" name="nb_verified_fingerprints" id="nb_verified_fingerprints"
            value="{{ old('nb_verified_fingerprints', $audit->nb_verified_fingerprints ?? '') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required>
    </div>

    <div>
        <label for="nb_without_fingerprints" class="block text-gray-700 font-semibold mb-2">معاينة عينة دون أخذ بصمتهم</label>
        <input type="text" name="nb_without_fingerprints" id="nb_without_fingerprints"
            value="{{ old('nb_without_fingerprints', $audit->nb_without_fingerprints ?? '') }}"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
            required>
    </div>
</div>

{{-- Script Alpine.js --}}
<script>
    function fonctionnairesSelector(existingFonct = []) {
        return {
            selectedFonct: '',
            fonctionnaires: [],
            
            init() {                
                // More robust initialization
                if (Array.isArray(existingFonct)) {
                    this.fonctionnaires = existingFonct.map(f => ({
                        id: f.id,
                        name: f.full_name
                    }));
                }
            },
            
            addFonctionnaire() {
                if (this.selectedFonct === '') return;
                
                // Check if already selected
                if (this.fonctionnaires.find(f => f.id == this.selectedFonct)) {
                    this.selectedFonct = '';
                    return;
                }
                
                // Find the option element and get its text
                let option = document.querySelector(`select option[value='${this.selectedFonct}']`);
                if (option) {
                    this.fonctionnaires.push({
                        id: this.selectedFonct,
                        name: option.text.trim()
                    });
                }
                
                this.selectedFonct = '';
            },
            
            removeFonctionnaire(index) {
                this.fonctionnaires.splice(index, 1);
            }
        }
    }
</script>