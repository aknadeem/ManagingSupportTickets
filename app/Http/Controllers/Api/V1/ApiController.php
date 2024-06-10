<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponses;

    public function includeRelation(String $relationship): bool
    {
        // checking if the include parameter is set in the url e.g. /api/v1/tickets?include=author
        // then we return the true or false

        $parm = request()->get('include');

        if(!isset($parm)){
            return false;
        }

        $includes = explode(',', strtolower($parm));

        return in_array(strtolower($relationship), $includes);

    }
}
