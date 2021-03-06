<?php

namespace Tests\Feature;

use App\Jobs\TeamRegistrationJob;
use App\Team;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeamRegistrationJobTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testTeamRegistrationJob()
    {
        $team = factory(Team::class)->create();
        TeamRegistrationJob::dispatch($team);
        $this->assertTrue(Storage::cloud()->exists('teams/'.$team->name));
        $this->assertTrue(Storage::cloud()->exists('teams/'.$team->name.'/wheat-unwatermarked_wm.jpg'));
        $this->assertFalse(Storage::disk()->exists('wheat-unwatermarked.jpg'));
        $team = $team->fresh();
        $this->assertSame(1, $team->total_tasks);
        $this->assertSame(1, $team->completed_tasks);
    }
}
