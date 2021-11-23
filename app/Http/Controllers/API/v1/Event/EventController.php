<?php

namespace App\Http\Controllers\API\v1\Event;

use Exception;
use App\Models\Event;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
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

        return Event::whereBetween('event_from_date', [$start, $end])->orWhereBetween('event_to_date', [$start, $end])->with('eventType')->paginate();
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
            'app_notification' => 'nullable|boolean',
        ]);

        $event = Event::Create([
            'event_type_id' => $request->event_type_id,
            'name' => $request->name,
            'description' => $request->description,
            'event_from_date' => $request->event_from_date,
            'event_to_date' => $request->event_to_date,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        if ($request->app_notification === true) {
            Notification::create([
                'name' => $request->name,
                'description' => $request->description,
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'notification_type_id' => 1,
                'event_id' => $event->id,
            ]);
        }

        return response([
            'header' => 'Success',
            'message' => 'Event Created Successfully.',
            'data' => $event
        ], 201);
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

        $event->update([
            'event_type_id' => $request->event_type_id,
            'name' => $request->name,
            'description' => $request->description,
            'event_from_date' => $request->event_from_date,
            'event_to_date' => $request->event_to_date,
            'updated_by' => $user->id,
        ]);

        return $event;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        try {
            $event->delete();
        } catch (Exception $ex) {
            return response([
                'header' => 'Dependency error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }
        return response('', 204);
    }
}
