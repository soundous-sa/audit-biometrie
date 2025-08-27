@extends('layouts.admin')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.3/dist/tailwind.min.css" rel="stylesheet">

</head>
<body>
    


<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold mb-6 text-gray-700">قائمة الموظفين</h2>

    <a href="{{ route('fonctionnaires.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">إضافة موظف جديد</a>

    <div class="overflow-x-auto bg-white shadow-md rounded">
        <table class="min-w-full border-collapse">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="py-2 px-4 border">الإسم</th>
                    <th class="py-2 px-4 border">النسب</th>
                    <th class="py-2 px-4 border">الهاتف</th>
                    <th class="py-2 px-4 border">رقم التسجيل</th>
                    <th class="py-2 px-4 border">أفعال</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fonctionnaires as $f)
                <tr class="hover:bg-gray-100 border-b">
                    <td class="py-2 px-4 border">{{ $f->firstName }}</td>
                    <td class="py-2 px-4 border">{{ $f->lastName }}</td>
                    <td class="py-2 px-4 border">{{ $f->phone }}</td>
                    <td class="py-2 px-4 border">{{ $f->matricule }}</td>
                    <td class="py-2 px-4 border flex space-x-2 justify-end">
                        <a href="{{ route('fonctionnaires.edit', $f->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded">تعديل</a>
                        <form action="{{ route('fonctionnaires.destroy', $f->id) }}" method="POST" onsubmit="return confirm('هل متأكد من الحذف؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">حذف</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

@endsection
