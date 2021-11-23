<?php

namespace App\Http\Controllers\API\v1\Notification;

use App\Http\Controllers\Controller;
use App\Models\NotificationType;
use Illuminate\Http\Request;

class NotificationTypeController extends Controller
{
    public function index()
    {
        return NotificationType::all();
    }
}
