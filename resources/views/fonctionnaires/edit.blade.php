@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-center">تعديل بيانات الموظف</h2>

    <!-- Ici on passe la variable au form -->
     <!-- hello this is just a test from edit.blade.php author : abdelmouniem -->
    @include('fonctionnaires.form', ['fonctionnaire' => $fonctionnaire])
</div>
@endsection
