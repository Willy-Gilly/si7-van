<div class="box-body">
    <table class="table table-bordered datatable" style="width:100%" >
        <thead>
        <tr class="bg-gradient-cyan text-center"  style="text-align-all: center">
            @foreach(\App\Http\Controllers\HomeViewController::getColumns() as $column)
                <th>{{$column}}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @for ($i = 0; $i < \App\Http\Controllers\HomeViewController::getNbLineDatatable(); $i++)
            <tr>
                <td class="bg-gray text-center" ><strong>{{\App\Http\Controllers\HomeViewController::getLineLabel()[$i] ?? ''}}</strong> </td>
                @include("linevalue",["line" => $i])
                <td class="bg-gray text-center" ><strong>{{\App\Http\Controllers\HomeViewController::getFormulesEndOfLine()[$i] ?? ''}}</strong> </td>
            </tr>
        @endfor
        </tbody>
    </table>
</div>

<div class="input-group" style="font-size: 30px">
    <div class="input-group-prepend">
        <span class="input-group-text bg-gradient-orange" style="font-size: 30px"><strong>VAN</strong></span>
    </div>
    <strong><input type="text" style="font-size: 40px; height: 100%;" value="{{$_COOKIE["van"]}}" class="form-control" readonly></strong>
    <h1>
        @if($_COOKIE["van"] < 0)
            La VAN est négative, l'investissement n'est pas rentable.
        @elseif ($_COOKIE["van"] = 0 )
            La VAN est égale à 0, l'investissement est équitable.
        @else
            La VAN est positive, l'investissement est rentable.
        @endif
    </h1>
</div>

<script>
    function doGet(url,divToUpdate ,params) {
        params = params || {};

        $.get(url, params, function(response) { // requesting url which in form
            console.log(response);
            $(divToUpdate).html(response); // getting response and pushing to element with id #response
        });
    }
</script>



<script>
    // Utilisation du datatable
    $('.datatable').DataTable(
        {
            "language": // pas utile car on affiche pas les différentes fonctionnalités du plugin mais set la langue
                {
                    "url":"//cdn.datatables.net/plug-ins/1.10.20/i18n/French.json"
                }
        }
        );
</script>

