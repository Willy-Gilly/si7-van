<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;

class HomeViewController extends Controller
{
    /**
     * @return array|string
     */
    public static function getParamPartial()
    {
        try {
            return view("params")->render();
        } catch (\Throwable $e) {
            return "Div Crash";
        }
    }

    /**
     * @return array|string
     */
    public static function getData()
    {
        self::setLines();
        try {
            return view("datatable")->render();
        } catch (\Throwable $e) {
            return "Div Crash";
        }
    }


    /**
     * @return array
     */
    public static function getColumns()
    {
        $myColumns=[];
        array_push($myColumns,"Libelle\Année");
        for($i = 0; $i < $_COOKIE["nbYear"]; $i++)
        {
            array_push($myColumns,"Année n°".($i+1));
        }
        array_push($myColumns,"Formules");
        return $myColumns;
    }

    /**
     * @return array
     */
    public static function getLineLabel()
    {
        return ["Chiffre d'affaire"," - Charges"," - Dotations aux amortissements","Résultats avant IS"," - IS","Résultats après IS",
            " + Dotations aux amortissements","Flux Nets de Trésorerie","FNT Cumulés", "FNT Actualisé"];
    }

    /**
     * @return array
     */
    public static function getFormulesEndOfLine()
    {
        return [" - ", " - ","Investissement/Nb Année","CA - (Charges + Dotation)", "Résultats avant IS * 1/3",
            "Résultats avant IS - IS","Investissement/Nb Années", "Résultats après IS + Dotations aux amortissements", "FNT + FNT précédent","FNT * (1+Taux Actualisation)^(-(n°Année))"];
    }


    /**
     * @return void
     */
    public static function setLines()
    {
        $myLines = [];
        $results = self::calculs();
        for($i = 0; $i < $_COOKIE["nbYear"]; $i++)
        {
            $myLines[0][$i]=$results[$i]["ca"];
            $myLines[1][$i]=$results[$i]["cs"];
            $myLines[2][$i]=$results[$i]["invest"];
            $myLines[3][$i]=$results[$i]["rai"];
            $myLines[4][$i]=$results[$i]["is"];
            $myLines[5][$i]=$results[$i]["rsi"];
            $myLines[6][$i]=$results[$i]["invest"];
            $myLines[7][$i]=$results[$i]["fnt"];
            $myLines[8][$i]=$results[$i]["fntc"];
            $myLines[9][$i]=$results[$i]["fnta"];
        }
        $_COOKIE["results"]=$myLines;
    }

    /**
     * @return int
     * Update here if adding a row
     */
    public static function getNbLineDatatable()
    {
        return 10;
    }

    /**
     * @return array
     */
    public static function calculs()
    {
        $results =[];
        $fntcumul=0;
        $nAnnée=1;
        $fntaCumul = 0;

        for($i = 0; $i < $_COOKIE["nbYear"]; $i++)
        {
            $nbYear= floatval($_COOKIE["nbYear"]) ?? 1 ;

            $invest = floatval($_COOKIE["invest"]) ?? 0;

            $tauxActu = floatval($_COOKIE["tauxActu"] ?? 0);

            $invest1= $invest / $nbYear;

            $ca = floatval($_COOKIE["year".$i."ca"]) ?? 0;

            $cs = floatval($_COOKIE["year".$i."cs"]) ?? 0 ;

            $rai=$ca-($cs+$invest1);

            $is = $rai*1/3;

            $rsi= $rai - $is;

            $fnt = $rsi+$invest1;

            $fntcumul+= $fnt;

            $fnta=$fnt * (1+$tauxActu)**(-($nAnnée));
            $fntaCumul+=$fnta;
            $nAnnée++;

            $r = [
                "invest" => $invest1,
                "ca" => $ca,
                "cs" => $cs,
                "rai" => $rai,
                "is" => $is,
                "rsi" => $rsi,
                "fnt" => $fnt,
                "fntc" => $fntcumul,
                "fnta" => $fnta,
            ];
            array_push($results,$r);
        }

        $_COOKIE["fntaTotal"] = $fntaCumul;

        $_COOKIE["van"] = round($_COOKIE["fntaTotal"] - $_COOKIE["invest"],3);
        return $results;
    }

    /**
     * @param $lineNumber
     * @return array
     */
    public static function getLine($lineNumber)
    {
        $myLines = $_COOKIE["results"];
        return $myLines[$lineNumber];
    }

}
