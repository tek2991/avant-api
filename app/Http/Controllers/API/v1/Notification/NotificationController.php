<?php

namespace App\Http\Controllers\API\v1\Notification;

use Exception;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Notification::orderBy('created_at', 'desc')->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2554',
            'notification_type_id' => 'required|exists:notification_types,id',
            'user_ids' => 'required|exists:users,id',
        ]);

        $notification = Notification::create([
            'name' => $request->name,
            'description' => $request->description,
            'notification_type_id' => $request->notification_type_id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $notification->users()->attach($request->user_ids);

        return response()->json([
            'message' => 'Notification created successfully',
            'notification' => $notification,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        try{
            $notification->delete();
            return response('Deleted', 204);
        }catch (Exception $ex){
            return response()->json([
                'header' => 'Error',
                'message' => 'Other resources depend on this record.'
            ], 418);
        }
    }
}
