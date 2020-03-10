@foreach($_COOKIE["results"][$line] ?? ["erreur" => 0] as $oneYear)
<td>{{round($oneYear,3)}}</td>
@endforeach
