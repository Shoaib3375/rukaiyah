import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { token } from './store';

window.Pusher = Pusher;

let echo = null;

export const initializeReverb = () => {
    if (echo) return echo;

    echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT,
        forceTLS: import.meta.env.VITE_REVERB_SCHEME === 'https',
        enabledTransports: ['ws'],
        authEndpoint: '/api/broadcasting/auth',
        auth: {
            headers: {
                get Authorization() {
                    return `Bearer ${token.value}`;
                }
            }
        }
    });

    return echo;
};

export const subscribeToUserNotifications = (userId, callback) => {
    if (!echo) initializeReverb();
    return echo.private(`App.Models.User.${userId}`).listen('NewNotification', callback);
};

export const subscribeToAppointmentEvents = (appointmentId, events) => {
    if (!echo) initializeReverb();
    const channel = echo.private(`appointment.${appointmentId}`);
    
    Object.entries(events).forEach(([eventName, callback]) => {
        channel.listen(eventName, callback);
    });
    
    return channel;
};

export const getReverb = () => echo;
