@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">Tableau de bord</h1>

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="p-4 bg-green-200 rounded shadow">
            <h2 class="text-lg font-semibold">Audits</h2>
            <p class="text-2xl">{{ $auditsCount }}</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div class="p-4 bg-white rounded shadow">
            <h2 class="text-lg font-semibold mb-2">Derniers Audits</h2>
            <ul>
                @foreach($lastAudits as $audit)
                    <li>{{ $audit->date_audit }} - {{ $audit->etablissement->libelle ?? 'N/A' }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    
</div>

@endsection
