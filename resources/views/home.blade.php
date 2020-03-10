@extends('adminlte::page')

@section('title', 'Calcul')

@section('content_header')
    <h1 class="m-0 text-dark">Calcul de la V.A.N.</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" id="year" name="year"
                               class="form-control profile-input-content dataMaskNbYear"
                               placeholder="Nombre d'année(s)" onkeyup="updateTextYear()" onkeydown="updateTextYear()"
                               onchange="updateTextYear()" onkeypress="updateTextYear()" onload="onloadInit()">
                        <button id="validationYear" type="button" disabled onclick="selectNbYear()" onload="onloadInit()" style="padding-left: 2px;"
                                class="btn bg-gradient-blue col-5">Selectionnez un nombre d'année
                        </button>
                        <!-- Sert à  séparer les boutons, les classes CSS ne le permettent pas --><p style="opacity: 0; padding-right: 5px; padding-left: 5px;"></p>
                        <button id="validationYear" type="button" onclick="reInitNbYear(true)"
                                class="btn bg-gradient-danger col-3">Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="params" class="col-12">
        <!-- Div actualisé -->
    </div>
    <div id="results" class="col-12">
        <!-- Div actualisé -->
    </div>
@stop

@section('js')

    <script>
        $(document).ready(onloadInit()); // On set par défaut au cas où il y aurait eu un problème au load de la page (avec un refresh par exemple).

        /**
         * Réinitialise toutes les valeurs des cookies
         */
        function reInitNbYear(reload) {
            for (let $i = 0; $i < getCookie("nbYear"); $i++)
            {
                document.cookie = 'year'+$i+'ca=0; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                document.cookie = 'year'+$i+'cs=0; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            }

            document.cookie = 'nbYear=0; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            document.cookie = 'tauxActu=0; expires=Thu, 01 Jan 1970 00:00:01 GMT;';

            //le reload n'est activé que lorsqu'on clique sur le bouton réinitialiser
            if(reload === true)
            {
                location.reload();
            }
        }

        /**
         * Met des valeurs dans les cookies
         * Et affiche la page des paramètres
         */
        function selectNbYear() {
            reInitNbYear(false); // On supprime tout ce qui existait à chaque clique sur le bouton où l'on change le nombre d'années

            let nbYear = document.getElementById("year").value; //On récupère la valeur de l'input
            let nbDisplayed; // Initialisation de la variable qui va servir à stoquer le nombre affiché
            if(nbYear.startsWith("0")) // On vérifie que le nombre ne commence pas par 0, si il commence par 0, on supprime le 0
            {
                nbDisplayed = nbYear.replace("0",'');
            }
            else //Sinon on affiche la valeur par défaut
            {
                nbDisplayed=nbYear;
            }
            document.cookie = "nbYear="+nbDisplayed; // On met dans un cookie le nombre d'années selectionné pour s'en servir en backend
            $("#results").innerHTML=""; // Je remplace ce qu'il y a dans le div de results dans le cas où on actualise les paramètres et qu'il y avait un précédent calcul
            doGet('/api/getParam','#params'); // J'actualise le div
        }

        /**
         *  Values initialisations
         **/
        function onloadInit() {

            let myInput = document.getElementById("year"); //Récupère l'input du nombre d'années
            let myValidationButton = document.getElementById("validationYear"); // Récupère le bouton qui selectionne le nombre d'années

            @if(isset($_COOKIE["nbYear"])) // Si il y a un cookie présent, on change le texte du bouton
            {
                myInput.innerText='{{$_COOKIE["nbYear"]}}'; // Je remet le cookie dans l'input du nombre d'année
                @if($_COOKIE["nbYear"] > 1) // On adapte le texte pour ne pas faire de faute de grammaire selon le nombre d'années
                    myValidationButton.innerText = "Selectionner pour " + '{{$_COOKIE["nbYear"]}}' + " années";
                @else
                    myValidationButton.innerText = "Selectionner pour " + '{{$_COOKIE["nbYear"]}}' + " année";
                @endif
                myValidationButton.removeAttribute("disabled");
            }
            @else // sinon, on l'initialise par dessus.
            {
                myInput.value="";
                myValidationButton.innerText = "Selectionnez un nombre d'année";
                myValidationButton.setAttribute("disabled",null); // On le met en disable pour pas que l'utilisateur rentre de valeur.
            }
            @endif
        }

        /**
         * Change le texte du bouton selon ce que l'utilisateur écrit.
         */
        function updateTextYear() {
            let myInput = document.getElementById("year"); //Récupère l'input du nombre d'années

            let myButton = document.getElementById("validationYear"); // Récupère le bouton de validation du nombre d'années

            let nbYear = myInput.value; // Je met la valeur du nombre d'années dans une variable

            myButton.setAttribute("disabled",null); // Je supprime l'attribut disable du bouton

            if (myInput.value) { // Si il y a une valeur dans l'input alors
                let txt2 = "Selectionnez un nombre d'année";
                let txt1 = "Selectionner pour ";
                let nbDisplayed;

                if(nbYear.startsWith("0"))//On retire le 0 au début
                {
                    nbDisplayed = nbYear.replace("0",'');
                }
                else
                {
                    nbDisplayed=nbYear;
                }

                myButton.removeAttribute("disabled"); // On retire le disable du bouton

                if (nbYear == 1) { // Si c'est égal à 1 (peut être "01" "1", pas de triple égal car on reçoit du texte)
                    txt2 = " année";

                } else if (nbYear > 1) {
                    txt2 = " années";

                } else { // L'input est soit nul, soit égal à 0 donc on remet le disable
                    myButton.setAttribute("disabled", null);
                    txt1="";
                    nbDisplayed="";
                }

                myButton.innerText = txt1 + nbDisplayed + txt2; // On change le texte d'affiché avec les chaines de caractères

            } else { // sinon je remet le disable et réinitialise le texte
                myButton.setAttribute("disabled", null);
                myButton.innerText = "Selectionnez un nombre d'année";
            }
        }
    </script>


    <!-- AJAX -->
    <script>
        function doGet(url,divToUpdate ,params) { // sert à actualiser une partie du code html
            params = params || {};
            $.get(url, params, function(response) { // Essaye d'obtenir un résultat dans les routes pour retourner une réponse

                $(divToUpdate).html(response); // Si il y a une réponse, la retourne
            });
        }
    </script>

    <!-- Plugins de DataMask -->
    <script>
        $(document).ready(function(){ // Masque de remplissage pour le nombre d'années
            $(".dataMaskNbYear").inputmask("9[9]");
        });
    </script>
    <script>
        function getCookie(cname) { // Sert à obtenir un cookie par son nom
            const name = cname + "=";
            const decodedCookie = decodeURIComponent(document.cookie);
            const ca = decodedCookie.split(';');
            for(let i = 0; i <ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
    </script>
@stop
