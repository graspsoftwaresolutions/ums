<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IrcController extends Controller
{
    public function ircIndex()
    {
        return view('IRC.irc');
    }
}
