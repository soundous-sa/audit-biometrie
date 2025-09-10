@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">لائحة عمليات التحيين</h2>

    {{-- Message succès --}}
    @if(session('success'))
    <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

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
                    <th class="py-2 px-4 border"></th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($audits as $audit)
                <tr class="hover:bg-gray-100 border-b">
                    <td class="py-2 px-4 border">{{ $audit->etablissement->libelle ?? '—' }}</td>
                    <td class="py-2 px-4 border">{{ $audit->fonctionnaire->full_name ?? '—' }}</td>
                    <td class="py-2 px-4 border">{{ $audit->date_audit }}</td>
                    <td class="py-2 px-4 border">{{ $audit->nb_detenus }}</td>
                    <td class="py-2 px-4 border">{{ $audit->nb_edited_fingerprints }}</td>
                    <td class="py-2 px-4 border">{{ $audit->nb_verified_fingerprints }}</td>
                    <td class="py-2 px-4 border">{{ $audit->nb_without_fingerprints }}</td>
                    <td class="py-2 px-4  flex justify-center">
                        {{-- Edit icon --}}
                        <a href="{{ route('audits.edit', $audit->id) }}"
                            class="flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-semibold rounded-md p-1 md:p-2 w-8 h-8 md:w-10 md:h-10"
                            title="تعديل">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.414 2.586a2 2 0 010 2.828l-10 10a1 1 0 01-.464.263l-5 1a1 1 0 01-1.213-1.213l1-5a1 1 0 01.263-.464l10-10a2 2 0 012.828 0z" />
                            </svg>
                        </a>
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