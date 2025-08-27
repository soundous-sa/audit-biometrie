@extends('layouts.admin')

@section('content')
<h2 class="text-3xl font-bold mb-6 text-gray-700">إضافة موظف جديد</h2>

<div class="max-w-3xl bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    @include('fonctionnaires.form')
</div>

@endsection
