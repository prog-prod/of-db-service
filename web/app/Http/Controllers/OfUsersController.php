<?php

namespace App\Http\Controllers;

use App\Http\Requests\RedirectToExternal;

class OfUsersController extends Controller
{
    public function redirectToExternal(RedirectToExternal $request)
    {
        return redirect()->away($request->get('url'), 302);
    }
}
