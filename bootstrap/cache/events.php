<?php return array (
  'App\\Providers\\EventServiceProvider' => 
  array (
    'App\\Events\\AppointmentConfirmed' => 
    array (
      0 => 'App\\Listeners\\SendAppointmentNotification',
    ),
    'App\\Events\\CoRaqiInvited' => 
    array (
      0 => 'App\\Listeners\\SendInviteNotification',
    ),
  ),
  'Illuminate\\Foundation\\Support\\Providers\\EventServiceProvider' => 
  array (
    'App\\Events\\AppointmentConfirmed' => 
    array (
      0 => 'App\\Listeners\\SendAppointmentNotification@handle',
    ),
    'App\\Events\\CoRaqiInvited' => 
    array (
      0 => 'App\\Listeners\\SendInviteNotification@handle',
    ),
  ),
);