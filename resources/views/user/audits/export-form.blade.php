@extends('layouts.admin')

@section('content')
<div class="bg-white shadow-md rounded-lg p-6 mt-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">تصفية وتصدير عمليات التحيين</h2>

    <form action="{{ route('audits.filter') }}" method="POST" class="max-w-3xl mx-auto mb-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label>المؤسسة</label>
                <select name="etab_id" class="w-full border rounded px-3 py-2">
                    <option value="">الكل</option>
                    @foreach($etablissements as $etab)
                    <option value="{{ $etab->id }}" @if(request('etab_id')==$etab->id) selected @endif>{{ $etab->libelle }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>من تاريخ</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label>إلى تاريخ</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="mt-4 flex gap-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">عرض النتائج</button>


    </form>

    @if(isset($audits))
    <form action="{{ route('audits.export') }}" method="GET">
        @csrf
        <input type="hidden" name="etab_id" value="{{ request('etab_id') }}">
        <input type="hidden" name="from_date" value="{{ request('from_date') }}">
        <input type="hidden" name="to_date" value="{{ request('to_date') }}">
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">تصدير Excel</button>
    </form>
    @endif
</div>

<div class="overflow-x-auto bg-white shadow-md rounded-lg">
    <table class="min-w-full border-collapse">
        <thead class="bg-blue-500 text-white text-center">
            <tr>
                <th class="py-2 px-4 border">المؤسسة السجنية</th>
                <th class="py-2 px-4 border">الموظف</th>
                <th class="py-2 px-4 border">التاريخ</th>
                <th class="py-2 px-4 border">عدد المعتقلين</th>
                <th class="py-2 px-4 border">عدد البصمات المحينة</th>
                <th class="py-2 px-4 border">محينة ب 10 أصابع</th>
                <th class="py-2 px-4 border">عينة دون بصمات</th>

            </tr>
        </thead>
        <tbody class="text-center">
            @forelse($audits ?? [] as $audit)
            <tr class="hover:bg-gray-100 border-b">
                <td class="py-2 px-4 border">{{ $audit->etablissement->libelle ?? '—' }}</td>
                <td>
                    @if($audit->fonctionnaires->count())
                    {{ $audit->fonctionnaires->pluck('full_name')->join(', ') }}
                    @else
                    —
                    @endif
                </td>
                <td class="py-2 px-4 border">{{ $audit->date_audit }}</td>
                <td class="py-2 px-4 border">{{ $audit->nb_detenus }}</td>
                <td class="py-2 px-4 border">{{ $audit->nb_edited_fingerprints }}</td>
                <td class="py-2 px-4 border">{{ $audit->nb_verified_fingerprints }}</td>
                <td class="py-2 px-4 border">{{ $audit->nb_without_fingerprints }}</td>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-3 text-gray-500">لا توجد عمليات تحيين</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

</div>
@endsection