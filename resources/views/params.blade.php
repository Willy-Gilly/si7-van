<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-gradient-indigo">
                <h3 class="card-title">Paramètres de calcul</h3>
                <div class="card-tools">
                    <button type="button" id="paramCollapse" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body col-12">
                <input id="myNbYear" type="hidden" value="{{$_COOKIE["nbYear"]}}">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-gradient-green">Investissement total (traité de manière linéraire)</span>
                    </div>
                    <input type="text" id="invest" class="form-control dataMaskDouble" required>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-gradient-pink">Taux d'actualisation</span>
                    </div>
                    <input type="text" id="tauxActu" class="form-control dataMaskDouble" required>
                </div>
                @for ($i = 0; $i < $_COOKIE["nbYear"]; $i++)
                    <h4>Année {{$i+1}}</h4>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-gradient-yellow">Chiffre d'affaires</span>
                        </div>
                        <input type="text" id="ca{{$i}}" class="form-control dataMaskDouble" required>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-gradient-orange">Charges</span>
                        </div>
                        <input type="text" id="cs{{$i}}" class="form-control dataMaskDouble" required>
                    </div>
                @endfor
                <div class="col-12 profile-center-div">
					<button type="button" class="btn bg-gradient-blue col-12 profile-center-div" onclick="calcul()">
						Calculer
					</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function calcul(){
        for (let $i = 0; $i < $('#myNbYear').val(); $i++)
        {
            document.cookie = "year"+$i+"ca="+$("#ca"+$i).val();
            document.cookie = "year"+$i+"cs="+$("#cs"+$i).val();
        }
        document.cookie = "invest="+$("#invest").val();
        document.cookie = "tauxActu="+$("#tauxActu").val();
        doGet("/api/getData","#results");
        $('#paramCollapse').click();
    }
</script>



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
