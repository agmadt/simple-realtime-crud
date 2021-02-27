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
        $createData = [
            'Name' => $request->name,
            'Email' => $request->email,
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
            
        return response()
            ->json([
                'data' => $createData,
                'message' => 'Team successfully added.'
            ]);
    }
}
