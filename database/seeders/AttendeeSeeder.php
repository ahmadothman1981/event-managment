<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $events = \App\Models\Event::all();
        foreach($users as $user)
        {
            $eventToAttend = $events->random(rand(1,3));

            foreach($eventToAttend as $event)
            {
                \App\Models\Attendee::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id
                ]);
            }
        }
    }
}
