<?php

namespace App\Http\Controllers;

use App\Models\School\School;
use App\Seo;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index(Seo $slug)
    {
//        return $slug;
        $school = School::whereName($slug->ecole)->first();
        $randomSchool = School::inRandomOrder()->take(1)->get();
//        return $randomSchool;
//        return $school->supplyList;
        if ($slug) {
            $data['url'] = $slug->url . "</p>";
            $data['title'] = $slug->ecole . "-" . $slug->ville . "($slug->code_postal)";

            $data['meta'] = $slug->ecol . ":fiche de l’établissement, adresse, liste scolaire, académie… Toutes les informations sur cette école.";

            $data['h1'] = "Liste scolaire - $slug->ecole ($slug->cp) ";

            $data['ecole'] = $slug->ecole;
            $data['statut'] = $slug->statut;
            $data['academie'] = $slug->academie;
            $data['code_etablissement'] = $slug->code_etablissement;
            $data['adresse'] = $slug->adresse;
            $data['code_postal'] = $slug->code_postal;
            $data['$ville'] = $slug->ville;
            if (!$school->supplyList) {
                $data['des'] = "<p>La liste scolaire de votre enfant chez vous en un clic !
Les listes scolaires de cet établissement ne sont malheureusement pas encore disponibles, mais vous pouvez nous les communiquer directement.</p>";
                $data['random_schools'] = $randomSchool;
                return response()->json([
                    'data' => $data
                ], 200);
            }
        }
            $data['des'] = "<p> La liste scolaire de votre enfant chez vous en un clic !"
           ."<br><p>Voici les listes scolaires disponibles pour cet établissement : </p>";

            $data['school_list'] = $school->supplyList;
            return response()->json([
                'data' => $data
            ], 200);

        }
}
