<?php

namespace App\Api\V1\Controllers;

use Airtable;
use App\Jobs\TeamStored;
use Illuminate\Support\Facades\Redis;
use App\Api\V1\Requests\StoreTeamRequest;

class TeamController
{
    public function index()
    {
        $teams = [];
        
        $getAllTeam = Redis::get('all_team');
        if (!$getAllTeam) {

            Redis::set('airtable_fetch_process', 1);

            $getAllTeam = Airtable::table('default')->get();
            
            Redis::set('airtable_fetch_process', 0);

            $getAllTeam = json_encode($getAllTeam);

            Redis::set('all_team', $getAllTeam);
            Redis::expire('all_team', 1 * 60); // will expire in 1 minutes
        }

        $getAllTeam = json_decode($getAllTeam, true);

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
        
        $totalQueued = Redis::get('queued_job');

		if (!$totalQueued) {
			$totalQueued = 0;
		}

		Redis::set('queued_job', $totalQueued += 1);

		TeamStored::dispatch($createData)->delay($totalQueued * 1);

        $createData['id'] = uniqid() . time();
            
        return response()
            ->json([
                'data' => $createData,
                'message' => 'Team successfully added.'
            ]);
    }
}
