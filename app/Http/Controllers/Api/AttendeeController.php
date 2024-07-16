<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AttendeeResource;
use App\Models\Attendee;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Event $event )
    {
        $attendees = $event->attendees()->latest();
        return AttendeeResource::collection($attendees->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request , Event $event)
    {
        $attendee = $event->attendees()->create([
            'user_id' => 1,

        ]);
        return new AttendeeResource($attendee);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event , Attendee $attendee) 
    {
        return new AttendeeResource($attendee);
    }

    /**
     * Update the specified resource in storage.
     */
   
    public function destroy(string $event , Attendee $attendee)
    {
        $attendee->delete();
        //return no content page 204 
        return response(status:204);
    }
}
