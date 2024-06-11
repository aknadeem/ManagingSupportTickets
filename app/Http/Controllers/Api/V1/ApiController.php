<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponses;

    protected $policyClass;

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

    public function isAble($ability, $model) {
        try{
            Gate::authorize($ability, [$model, $this->policyClass]);
            return true;
        } catch (AuthenticationException $e) {
            return false;
        }

    }
}
