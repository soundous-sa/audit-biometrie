@php
    // Removed legacy commented form above to avoid duplicated scripts/selects affecting JS queries.
@endphp
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

