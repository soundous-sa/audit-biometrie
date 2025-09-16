{{-- Removed obsolete commented standalone form & duplicate script to avoid conflicts --}}
@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6 mt-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800 text-center">تعديل عملية التحيين</h2>

    <form action="{{ route('audits.update', $audit->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('user.audits.form', [
            'audit' => $audit,
            'selectedFonctionnaires' => $selectedFonctionnaires
        ])
        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
            حفظ التغييرات
        </button>
    </form>
</div>
@endsection

