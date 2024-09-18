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
    private function callEndPoint($uri, $method, $data = null)
    {
        $calling = Http::withToken($this->accessToken)->$method($this->uri.$uri,$data);
        return $calling;
    }
    public function getUser()
    {
        $response = $this->callEndPoint('/users/me','get');
        return $response;
    }
    public function getEventTypes()
    {
        $organization = $this->getUser()->json()['resource']['current_organization'];
        $response = $this->callEndPoint('/event_types?organization='.$organization,'get');
        return $response;
    }
    public function getScheduledEvents()
    {
        $organization = $this->getUser()->json()['resource']['current_organization'];
        $response = $this->callEndPoint('/scheduled_events?organization='.$organization,'get');
        return $response;
    }
    public function scheduleEvent()
    {
        $body = [
            'max_event_count'=>1, // it can be replaced by $request Input
            "owner"=> $this->getEventTypes()->json()['collection']['0']['uri'],
            "owner_type"=> "EventType"
        ];
        $response = $this->callEndPoint('/scheduling_links','post',$body);
        return $response;
    }
}
