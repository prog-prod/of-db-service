<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

class AdminController extends BaseController
{
    public function index() {
        return $this->showView('Admins/Index',[
            'meta' => [
                'pageTitle' => 'Admins',
                'breadcrumbs' => []
            ]
        ]);
    }

    public function create() {
        return $this->showView('Admins/Create',[
            'meta' => [
                'pageTitle' => 'Create Admin',
                'breadcrumbs' => []
            ]
        ]);
    }
}
