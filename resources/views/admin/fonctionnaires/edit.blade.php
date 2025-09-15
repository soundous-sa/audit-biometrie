@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4 text-center">تعديل بيانات الموظف</h2>

    <!-- Ici on passe la variable au form -->
    
    @include('admin.fonctionnaires.form', ['fonctionnaire' => $fonctionnaire])
</div>
@endsection
