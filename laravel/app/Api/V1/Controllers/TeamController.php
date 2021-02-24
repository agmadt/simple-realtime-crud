<?php

namespace App\Api\V1\Controllers;

use Airtable;
use Illuminate\Support\Collection;

class TeamController
{
    public function index(): Collection
    {
        return Airtable::table('default')->get();
    }
}
