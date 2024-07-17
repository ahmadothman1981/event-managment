<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationShips;

class EventController extends Controller
{
    use CanLoadRelationShips;
     private array $relations = ['user' , 'attendees' , 'attendees.user'];

     public function __construct()
     {
         $this->middleware('auth:sanctum')->except(['index', 'show']);
     }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {  
         
       // $relations = ['user' , 'attendees' , 'attendees.user'];
        $query = $this->LoadRelationShips(Event::query(), $this->relations);
       
        return EventResource::collection( $query->latest()->paginate());
    }
   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $event = Event::create([
           ... $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
           ]),
           'user_id' => $request->user()->id,
        ]);
        return new EventResource($this->LoadRelationShips($event,$this->relations));
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        
        return new EventResource($this->LoadRelationShips($event,$this->relations));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update(
           $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time',
           ]));
         return new EventResource($this->LoadRelationShips($event));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        //return no content page 204 
        return response(status:204);
    }
}
