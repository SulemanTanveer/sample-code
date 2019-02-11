<?php

namespace App\Console\Commands;

use App\Events\NewOrder;
use App\Events\UserWasRegistered;
use App\Mail\ContactUs;
use App\Mail\PasswordResetConfirmation;
use App\Mail\SchoolList;
use App\Models\Order\Order;
use App\Notifications\OrderCancelled;
// use App\Notifications\OrderCompleted;
use App\Notifications\OrderConfirmed;
use App\User;
use Illuminate\Console\Command;
use Mail;
class testing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $order= Order::whereId(152)->first();
        NewOrder::dispatch($order);
       $order->user->notify(new OrderConfirmed($order));
        // $order->user->notify(new OrderCompleted($order));
        $order->user->notify(new OrderCancelled($order));
         $request = new \Illuminate\Http\Request();
         $request->message = 'testing';
         $request->email = 'kanwal.tanveer@barefootandco.com';


         Mail::to(\config('services.user.email'))
             ->send( new ContactUs($request));
        $req = new \Illuminate\Http\Request();

        $req->schoolName = 'Test';
        $req->schoolLevel = 1;

        Mail::to(\config('services.user.email'))
            ->send(new SchoolList( $req,env('LOGO_ADDRESS')))
        ;
        $user = User::whereId(17)->first();
        UserWasRegistered::dispatch($user);

        Mail::to($user)->send(new PasswordResetConfirmation($user));



    }
}
