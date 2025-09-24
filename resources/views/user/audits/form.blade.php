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
        x-data='fonctionnairesSelector(@json($selectedFonctionnaires ?? []))'
        x-init="init()"
        class="space-y-4">

        <div>
            <label for="fonctionnaire_select" class="block text-gray-700 font-semibold mb-2">الموظفون القائمون بالبصمة</label>
            <select id="fonctionnaire_select" x-ref="fonctSelect" x-model="selectedFonct" @change="addFonctionnaire()"
                class="w-full border rounded px-3 py-2">
                <option value="">-- اختر الموظف --</option>
                @foreach($fonctionnaires as $fonct)
                <option value="{{ $fonct->id }}" data-name="{{ $fonct->full_name }}">{{ $fonct->full_name }}</option>
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
    {{-- نوع الاستجابة --}}
<div class="mb-4">
    <label for="response_type_id" class="block text-gray-700 font-semibold mb-2">نوع الاستجابة</label>
    <select name="response_type_id" id="response_type_id"
        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300  mb-4">
        <option value="">-- اختر --</option>
        @foreach($responseTypes as $type)
    <option value="{{ $type->id }}"
        {{ old('response_type_id', $audit->response_type_id ?? '') == $type->id ? 'selected' : '' }}>
        {{ $type->name }}
    </option>
@endforeach
    </select>
</div>
</div>

{{-- Script Alpine.js --}}
<script>
    function fonctionnairesSelector(existingFonct = []) {
        return {
            selectedFonct: '',
            fonctionnaires: [],

            init() {
                // Normalize any incoming structure (array, keyed object, null)
                let data = existingFonct;
                if (!Array.isArray(data)) {
                    if (data && typeof data === 'object') {
                        data = Object.keys(data)
                            .filter(k => /^\d+$/.test(k))
                            .map(k => data[k]);
                    } else {
                        data = [];
                    }
                }
                this.fonctionnaires = data
                    .filter(f => f && typeof f === 'object')
                    .map(f => {
                        const name = f.full_name || f.name || (f.firstName && f.lastName ? `${f.firstName} ${f.lastName}` : null) || f.firstName || f.lastName || f.matricule || '—';
                        return { id: f.id, name };
                    });
                // Debug: show incoming + processed data (remove after confirmation)
                console.log('Existing fonctionnaires raw:', data);
                console.log('Initial fonctionnaires mapped:', JSON.parse(JSON.stringify(this.fonctionnaires)));
            },

            addFonctionnaire() {
                if (this.selectedFonct === '') return;

                // Prevent duplicates
                if (this.fonctionnaires.find(f => f.id == this.selectedFonct)) {
                    this.selectedFonct = '';
                    return;
                }

                // Only search within the fonctionnaires select
                let option = this.$refs.fonctSelect?.querySelector(`option[value='${this.selectedFonct}']`);
                if (option) {
                    const name = option.dataset.name ? option.dataset.name.trim() : option.text.trim();
                    this.fonctionnaires.push({ id: this.selectedFonct, name });
                }
                this.selectedFonct = '';
                // Debug (uncomment if needed):
                // console.log('After add:', JSON.parse(JSON.stringify(this.fonctionnaires)));
            },

            removeFonctionnaire(index) {
                this.fonctionnaires.splice(index, 1);
                // Debug (uncomment if needed):
                // console.log('After remove:', JSON.parse(JSON.stringify(this.fonctionnaires)));
            }
        };
    }
</script>