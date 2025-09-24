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
                    <th class="py-2 px-4 border">نوع الاستجابة</th>
                    <th class="py-2 px-4 border"></th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse($audits as $audit)
                <tr class="hover:bg-gray-100 border-b">
                    <td class="py-2 px-4 border">{{ $audit->etablissement->libelle ?? '—' }}</td>

                    {{-- Affichage Many-to-Many --}}
                    <td class="py-2 px-4 border">
                        @if($audit->fonctionnaires->isNotEmpty())
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
                    <td class="py-2 px-4 border">{{ $audit->responseType?->name ?? '—' }}</td>
                    <td class="py-2 px-4 flex justify-center gap-2">
                        {{-- Edit icon --}}
                        <a href="{{ route('audits.edit', $audit->id) }}"
                            class="flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-semibold rounded-md p-2 w-10 h-10"
                            title="تعديل">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M17.414 2.586a2 2 0 010 2.828l-10 10a1 1 0 01-.464.263l-5 1a1 1 0 01-1.213-1.213l1-5a1 1 0 01.263-.464l10-10a2 2 0 012.828 0z" />
                            </svg>
                        </a>

                        {{-- Print icon --}}
                        <a href="{{ route('audits.pdf', $audit->id) }}"
                            class="flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-semibold rounded-md p-2 w-10 h-10"
                            title="طباعة">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M6 9V2h12v7h2a2 2 0 0 1 2 2v7h-4v4H6v-4H2v-7a2 2 0 0 1 2-2h2zm2-5v5h8V4H8zm0 13v3h8v-3H8z" />
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