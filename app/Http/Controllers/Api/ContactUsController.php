<?php

namespace App\Http\Controllers\Api;

use App\Models\EmailNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ContactUs;
use App\Mail\ContactUsAdmin;
use App\Mail\SchoolList;
use Mail,Lang,Validator;
use App\Models\ContactUs as ContactUsModel;

class ContactUsController extends Controller
{
    /**
     * [parents requesting admin to add new product]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function storeSchoolList(Request $request)
    {
        $validator = Validator::make(request()->all(),[
            'schoolName' => 'required',
            'schoolLevel' => 'required',
            'attachment'   =>
                'required|mimes:doc,pdf,docx,xls,csv,jpeg,png,jpg,js,gif,svg|max:24000,'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors'=>$validator->errors()
            ], 401);
        }

    	$file = $request->file('attachment')->store('attachment', 'public');

        try {
            Mail::to(\config('services.user.email'))
                ->send(new SchoolList( $request,storage_path('app/public/' . $file)))
            ;

            return response()->json([
                'success' => true,
                'message' => Lang::get('messages.email_sent')
            ], 200);
        }
        catch (\Throwable $t)
        {
            return $t->getMessage();
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'email' => 'required',
        ]);
        
        Mail::to($request->email)
                ->send( new ContactUs($request));

        Mail::to(\config('services.contact_us'))
                ->send( new ContactUsAdmin($request));

        

        EmailNotification::create([
            'email'         =>  $request->email,
            'message'       =>  $request->message,
            'read_status'   =>  false
        ]);

        return response()->json([
            'success'   =>  true,
            'message'   =>  Lang::get('messages.email_sent')
        ]);

    }
}
