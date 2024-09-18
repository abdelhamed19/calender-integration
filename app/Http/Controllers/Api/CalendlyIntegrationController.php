<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\HandleCalendlyIntegration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CalendlyIntegrationController extends Controller
{
    private $handleCalendlyIntegration;
    public function __construct(HandleCalendlyIntegration $handleCalendlyIntegration)
    {
        $this->handleCalendlyIntegration = $handleCalendlyIntegration;
    }
    public function getUser()
    {
       $response = $this->handleCalendlyIntegration->getUser();
        if ($response->successful()) {
            return [
                'uri'=>$response->json()['resource']['uri'],
                'current_organization'=>$response->json()['resource']['current_organization']
            ];
        }
        return $response->status();
    }
    public function getEventTypes()
    {
        $response = $this->handleCalendlyIntegration->getEventTypes();
        if ($response->successful()) {
            return $response->json()['collection']['0']['uri'];
        }
        return $response->status();
    }
    public function getScheduledEvents()
    {
        $response = $this->handleCalendlyIntegration->getScheduledEvents();
        if ($response->successful()) {
            return $response->json();
        }
        return $response->status();
    }
    public function scheduleEvent()
    {
        $response = $this->handleCalendlyIntegration->scheduleEvent();
        if ($response->successful()) {
            $url = $response->json()['resource']['booking_url'];
            return view('calender');
        }
        return $response->status();
    }
}
