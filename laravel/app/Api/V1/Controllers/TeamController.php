<?php

namespace App\Api\V1\Controllers;

use Airtable;
use App\Api\V1\Requests\StoreTeamRequest;
use Illuminate\Support\Collection;

class TeamController
{
    public function index()
    {
        $teams = [];
        $getAllTeam = Airtable::table('default')->get();

        foreach ($getAllTeam as $team) {

            $photo = null;
            if (isset($team['fields']['Photo']) && count($team['fields']['Photo']) > 0) {
                $photo = $team['fields']['Photo'][0]['url'];
            }

            $teams[] = [
                'id' => $team['id'],
                'name' => $team['fields']['Name'],
                'email' => $team['fields']['Email'],
                'photo' => $photo
            ];
        }
            
        return response()
            ->json([
                'teams' => $teams
            ]);
    }

    public function store(StoreTeamRequest $request)
    {
        $createData = [
            'Name' => $request->name,
            'Email' => $request->email,
            'Photo' => null
        ];

        if ($request->file('photo')) {
            $photo = $request->file('photo');

            $photoPath = $photo->storePublicly(
                '/',
                's3'
            );

            $createData['Photo'] = [
                [
                    'url' => config('filesystems.disks.s3.bucket_url') . $photoPath,
                    'filename' => $photo->getClientOriginalName(),
                ]
            ];
        }

        Airtable::table('default')->create($createData);

        $createData['id'] = uniqid() . time();
            
        return response()
            ->json([
                'data' => $createData,
                'message' => 'Team successfully added.'
            ]);
    }
}
