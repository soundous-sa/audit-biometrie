<style>
    .table-container {
    max-height: 350px; /*  limite la hauteur visible du tableau */
    overflow-y: auto;  /*  active le scroll vertical à l’intérieur */
    overflow-x: hidden; /*  désactive le scroll horizontal */
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

/* Style optionnel pour le scroll */
.table-container::-webkit-scrollbar {
    width: 8px;
}

.table-container::-webkit-scrollbar-thumb {
    background-color: #6ec1e4;
    border-radius: 8px;
}

.table-container table {
    width: 100%;
    border-collapse: collapse;
}

.table-container th, 
.table-container td {
    text-align: center;
    padding: 10px;
}

</style>
<div class="table-container">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Établissement</th>
                <th>Détenus</th>
                <th>Empreintes Vérifiées</th>
                <th>Stabilisés</th>
                <th>Pourcentage (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tableStats as $row)
                <tr>
                    <td>{{ $row->etablissement }}</td>
                    <td>{{ $row->detenus }}</td>
                    <td>{{ $row->verified }}</td>
                    <td>{{ $row->stabilised }}</td>
                    <td>{{ $row->pourcentage }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
