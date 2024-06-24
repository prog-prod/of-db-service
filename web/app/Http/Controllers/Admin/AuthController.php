<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\AdminLoginRequest;

class AuthController extends BaseController
{
    public function index()
    {
        return $this->showView('Auth/Login');
    }

    public function login(AdminLoginRequest $request)
    {
        $request->authenticate();

        return redirect()->route('admin.dashboard')->with('success', 'You have successfully logged in.');
    }

    public function signIn() {

    }
    public function logout()
    {
        auth('admin')->logout();
        return redirect(route('admin.login'));
    }
}
