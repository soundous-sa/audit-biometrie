@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold mb-6 text-gray-700">خاص  </h2>

    <div class="overflow-x-auto bg-white shadow-md rounded">
        <div class="p-6 text-gray-900">
            {{ __("You're logged in!") }}
        </div>
    </div>
    @if(Auth::user()->role === 'admin')
        <div class="mt-6 p-4 bg-blue-100 rounded">
            <p>👨‍💼 أنت Admin، عندك صلاحيات إضافية.</p>
        </div>
    @else
        <div class="mt-6 p-4 bg-green-100 rounded">
            <p>🙋‍♂️ أنت User عادي.</p>
        </div>
    @endif
</div>

@endsection
        </div>
