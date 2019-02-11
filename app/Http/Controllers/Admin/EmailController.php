<?php

namespace App\Http\Controllers\Admin;

use App\Models\EmailNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
    public function unreadCount()
    {
        return EmailNotification::whereReadStatus(0)->get();//->count();
    }

    public function changeStatus($id)
    {
        $email = EmailNotification::where('id',$id)->first();
        $email->read_status = true;
        $email->save();
    }

    public function index()
    {
        return EmailNotification::all();
    }


}
