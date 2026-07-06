<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MembershipRegistrationController extends Controller
{
    /**
     * Display the registration form.
     */
    public function create()
    {
        return view('membership.register');
    }
}

