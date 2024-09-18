<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HandleCalendlyIntegration
{
    private $accessToken;
    private $uri;
    public function __construct()
    {
        $this->accessToken = config('services.calendly.token');
        $this->uri = 'https://api.calendly.com';
    }
    public function getUser()
    {
        $response = Http::withToken($this->accessToken)
            ->get($this->uri.'/users/me');
        return $response;
    }
    public function getEventTypes()
    {
        $organization = $this->getUser()->json()['resource']['current_organization'];
        $response = Http::withToken($this->accessToken)->get($this->uri.'/event_types?organization='.$organization);
        return $response;
    }
    public function getScheduledEvents()
    {
        $organization = $this->getUser()->json()['resource']['current_organization'];
        $response = Http::withToken($this->accessToken)->get($this->uri.'/scheduled_events?organization='.$organization);
        return $response;
    }
    public function scheduleEvent()
    {
        $body = [
            'max_event_count'=>1, // it can be replaced by $request Input
            "owner"=> $this->getEventTypes()->json()['collection']['0']['uri'],
            "owner_type"=> "EventType"
        ];
        $response = Http::withToken($this->accessToken)->post($this->uri.'/scheduling_links',$body);
        return $response;
    }
}
