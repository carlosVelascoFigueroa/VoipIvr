<?php

namespace App\Http\Controllers;

require '/Volumes/Expansion/code/php/alowareAssesment/vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Call;


class CdrController extends Controller
{
    public function cdr(){
        $calls = \App\Models\Call::all();
        return $calls;
    }
}
