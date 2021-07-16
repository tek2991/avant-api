<?php

namespace App\Http\Controllers\API\v1\Event;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'start_of_month' => 'required|date',
        ]);

        $start = $request->start_of_month;
        $end = Carbon::create($start)->lastOfMonth()->format('Y-m-d');

        return Event::whereBetween('event_from_date', [$start, $end])->orWhereBetween('event_to_date', [$start, $end])->with(['creator.userDetail', 'eventType'])->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('director') !== true) {
            return response([
                'header' => 'Forbidden',
                'message' => 'Please Logout and Login again.'
            ], 401);
        }

        $this->validate($request, [
            'event_type_id' => 'required|min:1|exists:event_types,id',
            'name' => 'required|max:255|string',
            'description' => 'required|max:255|string',
            'event_from_date' => 'required|date',
            'event_to_date' => 'required|date|after_or_equal:event_date_from',
        ]);


        return Event::Create([
            'event_type_id' => $request->event_type_id,
            'name' => $request->name,
            'description' => $request->description,
            'event_from_date' => $request->event_from_date,
            'event_to_date' => $request->event_to_date,
            'created_by' => $user->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //
    }
}
