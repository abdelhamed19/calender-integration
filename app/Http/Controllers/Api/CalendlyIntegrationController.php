<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CalendlyIntegrationController extends Controller
{
    private $accessToken;
    public function __construct()
    {
        $this->accessToken = config('services.calendly.token');
    }
    public function getUser()
    {
        $response = Http::withToken($this->accessToken)
            ->get('https://api.calendly.com/users/me');
        if ($response->successful()) {
            $data = $response->json();
            $uri = $data['resource']['uri'];
            $current_organization = $data['resource']['current_organization'];
            return ['uri'=>$uri, 'current_organization'=>$current_organization];
        }
        return $response->status();
    }
    public function getEventTypes()
    {
        $organization = $this->getUser()['current_organization'];
        $response = Http::withToken($this->accessToken)->get('https://api.calendly.com/event_types?organization='.$organization);
        if ($response->successful()) {
            $data = $response->json();
            $uri = $data['collection']['0']['uri'];
            return $uri;
        }
        return $response->status();
    }
    public function getScheduledEvents()
    {
        $organization = $this->getUser()['current_organization'];
        $response = Http::withToken($this->accessToken)->get('https://api.calendly.com/scheduled_events?organization='.$organization);
        if ($response->successful()) {
            return $response->json();
        }
        return $response->status();
    }
    public function scheduleEvent()
    {
        $body =[
            'max_event_count'=>1,
            "owner"=> $this->getEventTypes(),
            "owner_type"=> "EventType"
        ];
        $response = Http::withToken($this->accessToken)->post('https://api.calendly.com/scheduling_links',$body);
        if ($response->successful()) {
            $data = $response->json();
            $url = $data['resource']['booking_url'];
            return view('calender');
        }
        return $response->status();
    }
}
