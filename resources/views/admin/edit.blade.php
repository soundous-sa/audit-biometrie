@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-4">تعديل الموظف</h1>

    <form action="{{ route('admin.admin.fonctionnaires.update', $fonctionnaire) }}" method="POST">
        @method('PUT')
        @include('admin._form')  <!--@include('fonctionnaires._form')-->
    </form>
@endsection
