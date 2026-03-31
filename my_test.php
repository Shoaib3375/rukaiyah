<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apptId = '019d4073-3257-73c5-b045-06f071793254';
$appt = \App\Models\Appointment::with('leadRaqi.user')->find($apptId);
$user = $appt->leadRaqi->user;
Auth::guard('api')->setUser($user);

function testApi($url) {
    $req = Illuminate\Http\Request::create($url, 'GET');
    $req->headers->set('Accept', 'application/json');
    $kernel = app()->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle($req);
    echo "GET $url -> Status: " . $response->getStatusCode() . "\n";
    echo "Body: " . $response->getContent() . "\n\n";
}

testApi('/api/v1/raqi/appointments/'.$apptId);
testApi('/api/v1/auth/me');
