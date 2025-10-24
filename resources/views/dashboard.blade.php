@extends('layouts.admin')
<style>
/*  Empêche la page entière de défiler */
html, body {
    height: 100%;
    overflow: hidden;
}

/*  Autorise uniquement le contenu du tableau à défiler */
.table-scroll {
    max-height: 350px; /* tu peux ajuster la hauteur visible */
    overflow-y: auto;  /* active le scroll vertical à l'intérieur */
    overflow-x: hidden;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Scroll stylisé (optionnel) */
.table-scroll::-webkit-scrollbar {
    width: 8px;
}
.table-scroll::-webkit-scrollbar-thumb {
    background-color: #6ec1e4;
    border-radius: 8px;
}
</style>


@section('content')
<div class="container mx-auto p-0">

    <!--  Filtres et boutons export (en ligne) -->
    <div class="flex items-center justify-between flex-wrap gap-4">
         <div class="bg-green-100 rounded-xl shadow-sm p-4 w-fit  text-center">
        <h2 class="text-lg font-semibold text-gray-700">عدد التحيينات</h2>
        <p class="text-3xl font-bold text-green-700 mt-2">{{ $auditsCount }}</p>
    </div>
        <!-- Sélecteur multiple -->
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 flex-wrap">
            <label for="table_etablissement" class="font-semibold text-gray-700">المؤسسات:</label>
            <select name="table_etab_id[]" id="table_etablissement" multiple size="5"
                class="border rounded px-3 py-1 text-sm focus:ring-2 focus:ring-blue-400"
                style="min-width: 200px; max-width: 240px;">
                @foreach($etablissements as $etab)
                    <option value="{{ $etab->id }}"
                        {{ in_array($etab->id, (array) request('table_etab_id')) ? 'selected' : '' }}>
                        {{ $etab->libelle }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                class="bg-blue-600 text-white text-sm px-3 py-1 rounded hover:bg-blue-700">
                تطبيق
            </button>
            <a href="{{ route('dashboard') }}"
                class="text-sm text-gray-500 underline hover:text-blue-600">إعادة تعيين</a>
        </form>

        <!-- Boutons export -->
        <div class="flex gap-2">
            <a href="{{ route('dashboard.export.excel', ['etab_table_id' => request('table_etab_id')]) }}"
                class="bg-green-500 text-white px-3 py-1 text-sm rounded hover:bg-green-600 shadow-sm">
                  Excel
            </a>
        </div>
    </div>

    <!-- Tableau compact -->
    <div class="bg-white rounded-lg shadow p-3 overflow-x-auto max-h-[300px] overflow-y-auto">
        <table class="w-full text-sm text-center border border-gray-200">
            <thead class="bg-gray-100 text-gray-700 sticky top-0">
                <tr>
                    <th class="border px-3 py-1.5">المؤسسة</th>
                    <th class="border px-3 py-1.5">عدد المعتقلين</th>
                    <th class="border px-3 py-1.5">محينة ب 10 أصابع</th>
                    <th class="border px-3 py-1.5">استقرت بصماتهم</th>
                    <th class="border px-3 py-1.5">النسبة</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tableStats as $stat)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-1">{{ $stat->etablissement }}</td>
                        <td class="border px-3 py-1">{{ $stat->detenus }}</td>
                        <td class="border px-3 py-1">{{ $stat->verified }}</td>
                        <td class="border px-3 py-1">{{ $stat->stabilised }}</td>
                        <td class="border px-3 py-1 text-blue-600 font-semibold">{{ $stat->pourcentage }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-3 text-gray-500">لا توجد بيانات متاحة</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Sélecteur graphique -->

    <form method="GET" action="{{ route('dashboard') }}" class="flex items-center justify-center gap-2">
        <label for="etablissement" class="font-semibold text-gray-700">اختر المؤسسة:</label>
        <select name="etab_id" id="etablissement"
            class="border rounded px-3 py-1 text-sm"
            onchange="this.form.submit()">
            <option value="">-- جميع المؤسسات --</option>
            @foreach($etablissements as $etab)
                <option value="{{ $etab->id }}" {{ $etab->id == $etablissementId ? 'selected' : '' }}>
                    {{ $etab->libelle }}
                </option>
            @endforeach
        </select>
    </form>

    <!--  Graphique compact -->
    <div class="bg-white rounded-lg shadow p-4 relative">
        <div class="flex justify-between items-center mb-2">
            <h3 class="text-lg font-semibold text-center flex-1">
                عدد المعتقلين المحينة بصماتهم ب{{ $etablissementName ?? '' }}
            </h3>
            <button id="downloadChart" class="text-gray-500 hover:text-blue-600" title="تحميل الرسم البياني">
                <i class="fa fa-download text-lg"></i>
            </button>
        </div>
        <canvas id="auditChart" height="70"></canvas>
    </div>

</div>

{{-- Librairies --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('auditChart').getContext('2d');

    const auditChart = new Chart(ctx, {
        data: {
            labels: @json($years),
            datasets: [
                {
                    type: 'bar',
                    label: 'Somme de عدد المعتقلين',
                    data: @json($detenus),
                    backgroundColor: 'rgba(20,136,242, 0.8)',
                    borderColor: 'rgba(20,136,242, 0.8)',
                    borderWidth: 1,
                    barPercentage: 0.9,
                    order: 1,
                    datalabels: {
                        color: 'black',
                        anchor: 'end',
                        align: 'end',
                        offset: -4, // 🔹 colle bien le label au-dessus de la barre
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value) => value
                    }
                },
                {
                    type: 'bar',
                    label: 'Somme de محينة ب 10 أصابع',
                    data: @json($verified),
                    backgroundColor: 'rgba(7,86,157, 0.8)',
                    borderColor: 'rgba(7,86,157, 0.8)',
                    borderWidth: 1,
                    barPercentage: 0.9,
                    order: 2,
                    datalabels: {
                        color: 'black',
                        anchor: 'end',
                        align: 'end',
                        offset: 12, // 🔹 descend légèrement le label pour éviter qu’il touche le 1er
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value) => value
                    }
                },
                {
                    type: 'line',
                    label: ' Somme de النسبة',
                    data: @json($avgPercent),
                    borderColor: 'rgba(250,21,4,1)',
                    backgroundColor: 'rgba(250,21,4,0.2)',
                    yAxisID: 'y1',
                    order: 0,
                    fill: false,
                    tension: 0.3,
                    pointStyle: 'circle',
                    pointRadius: 2,
                    pointHoverRadius: 2,
                    borderWidth: 2,
                    spanGaps: true,
                    showLine: true,
                    datalabels: {
                        color: 'black',
                        anchor: 'end',
                        align: 'start', // 🔹 place le pourcentage au-dessus du point rouge
                        offset: 6,
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value) => value ? value.toFixed(2) + '%' : ''
                    }
                }
            ]
        },
        options: {
            plugins: {
                legend: {
                    labels: {
                        usePointStyle: true,
                        pointStyle: "circle",
                        color: '#003f4c',
                        padding: 20
                    }
                },
                datalabels: {
                    clamp: true,
                    clip: false,
                    textAlign: 'center'
                }
            },
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false
            },
            stacked: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'عدد المعتقلين',
                        color: '#000',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Somme de النسبة  (%)',
                         color: '#000',
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    }
                    
                }
            }
        },
        plugins: [ChartDataLabels]
    });
   //  Bouton téléchargement
document.getElementById('downloadChart').addEventListener('click', function() {
    const canvas = document.getElementById('auditChart');
    const ctx = canvas.getContext('2d');

    // Création d’un canvas temporaire plus grand pour inclure le titre
    const paddingTop = 60; // espace pour le titre
    const tempCanvas = document.createElement('canvas');
    const tempCtx = tempCanvas.getContext('2d');
    tempCanvas.width = canvas.width;
    tempCanvas.height = canvas.height + paddingTop;

    //  Fond blanc
    tempCtx.fillStyle = 'white';
    tempCtx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);

    //  Titre en haut
    tempCtx.fillStyle = 'black';
    tempCtx.font = 'bold 22px "Cairo", sans-serif';
    tempCtx.textAlign = 'center';
    tempCtx.fillText('عدد المعتقلين المحينة بصماتهم ب{{ $etablissementName ?? "" }}', tempCanvas.width / 2, 40);

    //  Dessine le graphe en dessous du titre
    tempCtx.drawImage(canvas, 0, paddingTop);

    // Téléchargement du graphique avec le titre
    const a = document.createElement('a');
    a.href = tempCanvas.toDataURL('image/png');
    a.download = 'graphe_audit.png';
    a.click();
});

});
</script>

@endsection