<?php

namespace App\Contracts;

interface SearchableModel
{
    public static function search($query = '', $callback = null);

}
