@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-4">إضافة موظف جديد</h1>

    <form action="{{ route('fonctionnaires.store') }}" method="POST">
        @include('fonctionnaires._form')
    </form>
@endsection
