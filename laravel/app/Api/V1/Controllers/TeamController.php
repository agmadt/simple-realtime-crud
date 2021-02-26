<?php

namespace App\Api\V1\Controllers;

use Airtable;
use App\Api\V1\Requests\StoreTeamRequest;
use Illuminate\Support\Collection;

class TeamController
{
    public function index(): Collection
    {
        return Airtable::table('default')->get();
    }

    public function store(StoreTeamRequest $request)
    {
        // Server-side validation
    }
}
