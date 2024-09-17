<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Spatie\GoogleCalendar\Event;
use App\Http\Controllers\Api\CalendlyIntegrationController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/google/redirect', function () {
    return Socialite::driver('google')->redirect();
});

Route::get('/auth/callback', function () {
    $provider_user = Socialite::driver('google')->stateless()->user();

    $user = User::where('provider_id',$provider_user->id)->first();
    if (!$user)
    {
        User::create([
            'provider_id'=>$provider_user->id,
            'name'=>$provider_user->name,
            'email'=>$provider_user->email,
            'password'=>\Illuminate\Support\Facades\Hash::make('123456789')
        ]);
        return 1;
    }
    return 'authed';
});

Route::post('save-calendar-events', function (\Illuminate\Http\Request $request){
    $title = $request->input('title');

    $event = new Event();
    $event->name = $title;
    $event->startDateTime = Carbon\Carbon::now();
    $event->endDateTime = Carbon\Carbon::now()->addHour();
    //$event->addAttendee(['email' => 'abdelhamed.muhammed19@gmail.com']);
    //$event->addMeetLink(); // optionally add a google meet link to the event

    $event->save(null,['sendNotifications' => true]);
    return back();
});
Route::get('schedule-events',[CalendlyIntegrationController::class,'scheduleEvent']);

//Route::get('get/cals',function (){
//    dd(Event::get());
//});


