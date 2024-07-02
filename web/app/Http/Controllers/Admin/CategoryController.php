<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;

class CategoryController extends BaseController
{
    public function index() {
        return $this->showView('Categories/Index', [
            'meta' => [
                'pageTitle' => 'Categories',
                'breadcrumbs' => []
            ]
        ]);
    }

    public function create() {
        return $this->showView('Admins/Create',[
            'meta' => [
                'pageTitle' => 'Create category',
                'breadcrumbs' => []
            ]
        ]);
    }
}
