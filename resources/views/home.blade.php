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
                               placeholder="{{$_COOKIE["nbYear"] ?? 'Nombre d\'année(s)'}}" onkeyup="updateTextYear()" onkeydown="updateTextYear()"
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
        $(document).ready(onloadInit()); // Onload setting value to years

        /**
         * Reinit the cookie value
         */
        function reInitNbYear(reload) {
            for (let $i = 0; $i < getCookie("nbYear"); $i++)
            {
                document.cookie = 'year'+$i+'ca=0; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                document.cookie = 'year'+$i+'cs=0; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            }

            document.cookie = 'nbYear=0; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            document.cookie = 'tauxActu=0; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
            @php($_COOKIE["nbYear"] = null)

            if(reload === true)
            {
                location.reload();
            }
        }

        /**
         * Set the cookie value
         * And Display parameters page
         */
        function selectNbYear() {
            reInitNbYear(false);
            let nbYear = document.getElementById("year").value;
            let nbDisplayed;
            if(nbYear.startsWith("0"))
            {
                nbDisplayed = nbYear.replace("0",'');
            }
            else
            {
                nbDisplayed=nbYear;
            }
            document.cookie = "nbYear="+nbDisplayed;
            $("#results").innerHTML="";
            doGet('/api/getParam','#params');
        }

        /**
         *  Values initialisations
         **/
        function onloadInit() {
            let myInput = document.getElementById("year");
            let myValidationButton = document.getElementById("validationYear");
            @if(isset($_COOKIE["nbYear"]))
            {
                myInput.innerText='{{$_COOKIE["nbYear"]}}';
                @if($_COOKIE["nbYear"] > 1)
                    myValidationButton.innerText = "Selectionner pour " + '{{$_COOKIE["nbYear"]}}' + " années";
                @else
                    myValidationButton.innerText = "Selectionner pour " + '{{$_COOKIE["nbYear"]}}' + " année";
                @endif
                myValidationButton.removeAttribute("disabled");
            }
            @else
            {
                myInput.value="";
                myValidationButton.innerText = "Selectionnez un nombre d'année";
                myValidationButton.setAttribute("disabled",null);
            }
            @endif
        }

        /**
         * Change Button Text and enable/disable features
         */
        function updateTextYear() {
            let myInput = document.getElementById("year");
            let myButton = document.getElementById("validationYear");
            let nbYear = myInput.value;
            myButton.setAttribute("disabled",null);
            if (myInput.value) {
                let txt2 = "Selectionnez un nombre d'année";
                let txt1 = "Selectionner pour ";
                let nbDisplayed;
                if (nbYear == 1) {
                    txt2 = " année";
                    if(nbYear.startsWith("0"))
                    {
                        nbDisplayed = nbYear.replace("0",'');
                    }
                    else
                    {
                        nbDisplayed=nbYear;
                    }
                    myButton.removeAttribute("disabled");
                } else if (nbYear > 1) {
                    txt2 = " années";
                    if(nbYear.startsWith("0"))
                    {
                        nbDisplayed = nbYear.replace("0",'');
                    }
                    else
                    {
                        nbDisplayed=nbYear;
                    }
                    myButton.removeAttribute("disabled");
                } else {
                    myButton.setAttribute("disabled", null);
                    txt1="";
                    nbDisplayed="";
                }
                myButton.innerText = txt1 + nbDisplayed + txt2;
            } else {
                myButton.setAttribute("disabled", null);
                myButton.innerText = "Selectionnez un nombre d'année";
            }
        }
    </script>


    <!-- AJAX -->
    <script>
        function doGet(url,divToUpdate ,params) {
            params = params || {};
            $.get(url, params, function(response) { // requesting url which in form

                $(divToUpdate).html(response); // getting response and pushing to element with id #response
            });
        }
    </script>

    <!-- Plugins de DataMask -->
    <script>
        $(document).ready(function(){
            $(".dataMaskNbYear").inputmask("9[9]");
        });
        $(document).ready(function(){
            $(".dataMaskNumeric").inputmask("9[9][9][9][9][9]");
        });
        $(document).ready(function(){
            $(".dataMaskDouble").inputmask("decimal", {
                placeholder: "0",
                digits: 2,
                digitsOptional: false,
                radixPoint: ",",
                groupSeparator: "",
                autoGroup: true,
                allowPlus: false,
                allowMinus: false,
                clearMaskOnLostFocus: false,
                removeMaskOnSubmit: true,
                autoUnmask: true,
                onUnMask: function(maskedValue, unmaskedValue) {
                    let x = unmaskedValue.split(',');
                    return x[0].replace(/\./g, '') + '.' + x[1];
                }
            })
        });
    </script>
    <script>
        function getCookie(cname) {
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
