# Jetty Spiritual Healing — Appointment System
## AI Agent Work Plan (Laravel PHP + JWT)

> **Project:** Jetty Spiritual Healing Appointment System
> **Backend:** Laravel 11 · PHP 8.2+ · MySQL
> **Auth:** tymon/jwt-auth (JWT tokens)
> **Frontend:** React (separate repo)
> **Realtime:** Laravel Reverb (WebSockets)
> **Queue:** Laravel Queues with Redis
> **ML Integration:** Excluded for now (JettyMLBackBone added later)

---

## Database Schema

### Tables to implement (via Laravel migrations)

| Migration file | Table | Purpose |
|---|---|---|
| `create_users_table` | `users` | Shared user table with role enum |
| `create_patient_profiles_table` | `patient_profiles` | Extended patient data |
| `create_raqi_profiles_table` | `raqi_profiles` | Raqi bio, specialization, approval |
| `create_availabilities_table` | `availabilities` | Raqi weekly calendar slots |
| `create_appointments_table` | `appointments` | Core booking record |
| `create_session_participants_table` | `session_participants` | Multi-Raqi join table |
| `create_session_notes_table` | `session_notes` | Ruqyah logs per session |
| `create_notifications_table` | `notifications` | Email / SMS / push records |
| `create_reviews_table` | `reviews` | Post-session patient reviews |
| `create_follow_ups_table` | `follow_ups` | Follow-up scheduling |

### Key enums (use PHP-backed enums)
```php
// app/Enums/UserRole.php
enum UserRole: string {
    case Patient = 'patient';
    case Raqi    = 'raqi';
    case Admin   = 'admin';
}

// app/Enums/SessionType.php
enum SessionType: string {
    case Video    = 'video';
    case Chat     = 'chat';
    case Phone    = 'phone';
    case InPerson = 'in_person';
}

// app/Enums/AppointmentStatus.php
enum AppointmentStatus: string {
    case Pending   = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
    case Completed = 'completed';
    case NoShow    = 'no_show';
}

// app/Enums/ParticipantRole.php
enum ParticipantRole: string {
    case Lead   = 'lead';
    case CoRaqi = 'co_raqi';
}

// app/Enums/InviteStatus.php
enum InviteStatus: string {
    case Pending  = 'pending';
    case Accepted = 'accepted';
    case Declined = 'declined';
}

// app/Enums/NoteType.php
enum NoteType: string {
    case RuqyahLog      = 'ruqyah_log';
    case Observation    = 'observation';
    case Recommendation = 'recommendation';
    case Chat           = 'chat';
}

// app/Enums/NotificationChannel.php
enum NotificationChannel: string {
    case Email = 'email';
    case Sms   = 'sms';
    case Push  = 'push';
}

// app/Enums/RaqiStatus.php
enum RaqiStatus: string {
    case Pending   = 'pending';
    case Active    = 'active';
    case Suspended = 'suspended';
}
```

---

## Phase 1 — Laravel Project Setup

### Task 1.1 — Install & configure
```bash
composer create-project laravel/laravel jetty-backend
cd jetty-backend

# JWT auth
composer require tymon/jwt-auth
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret

# Other packages
composer require spatie/laravel-permission
composer require spatie/laravel-query-builder
composer require laravel/reverb
php artisan reverb:install

# Dev tools
composer require --dev barryvdh/laravel-ide-helper
composer require --dev laravel/pint
```

### Task 1.2 — Environment configuration
```env
APP_NAME=JettyHealing
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jetty_healing
DB_USERNAME=root
DB_PASSWORD=

# JWT
JWT_SECRET=
JWT_TTL=60
JWT_REFRESH_TTL=20160

# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
QUEUE_CONNECTION=redis
CACHE_STORE=redis

# Reverb
REVERB_APP_ID=jetty
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=localhost
REVERB_PORT=8080

# Mail
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=noreply@jettyhealing.com
MAIL_FROM_NAME="Jetty Healing"

# Twilio (SMS)
TWILIO_SID=
TWILIO_TOKEN=
TWILIO_FROM=

# Frontend
FRONTEND_URL=http://localhost:3000
```

### Task 1.3 — CORS configuration
```php
// config/cors.php
'paths'                => ['api/*'],
'allowed_origins'      => [env('FRONTEND_URL')],
'allowed_methods'      => ['*'],
'allowed_headers'      => ['*'],
'exposed_headers'      => ['Authorization'],
'supports_credentials' => true,
```

---

## Phase 2 — Migrations

Create in this exact order to respect foreign key constraints:

### `users` table
```php
Schema::create('users', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('full_name');
    $table->string('email')->unique();
    $table->string('phone')->nullable();
    $table->string('password');
    $table->enum('role', ['patient', 'raqi', 'admin'])->default('patient');
    $table->boolean('is_active')->default(true);
    $table->boolean('is_verified')->default(false);
    $table->timestamp('email_verified_at')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

### `patient_profiles` table
```php
Schema::create('patient_profiles', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
    $table->string('address')->nullable();
    $table->string('emergency_contact')->nullable();
    $table->text('medical_notes')->nullable();
    $table->timestamps();
});
```

### `raqi_profiles` table
```php
Schema::create('raqi_profiles', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
    $table->text('bio')->nullable();
    $table->string('specialization')->nullable();
    $table->string('languages')->nullable();
    $table->enum('status', ['pending', 'active', 'suspended'])->default('pending');
    $table->boolean('is_approved')->default(false);
    $table->timestamp('approved_at')->nullable();
    $table->decimal('rating', 3, 2)->default(0.00);
    $table->unsignedInteger('total_reviews')->default(0);
    $table->timestamps();
});
```

### `availabilities` table
```php
Schema::create('availabilities', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('raqi_id')->references('id')->on('raqi_profiles')->cascadeOnDelete();
    $table->tinyInteger('day_of_week'); // 0=Monday ... 6=Sunday
    $table->time('slot_start');
    $table->time('slot_end');
    $table->boolean('is_blocked')->default(false);
    $table->timestamps();
    $table->unique(['raqi_id', 'day_of_week', 'slot_start']);
});
```

### `appointments` table
```php
Schema::create('appointments', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('patient_id')->references('id')->on('users')->cascadeOnDelete();
    $table->foreignUuid('lead_raqi_id')->references('id')->on('raqi_profiles');
    $table->enum('session_type', ['video', 'chat', 'phone', 'in_person']);
    $table->enum('status', ['pending','confirmed','cancelled','completed','no_show'])->default('pending');
    $table->timestamp('scheduled_at');
    $table->unsignedInteger('duration_minutes')->default(60);
    $table->text('patient_notes')->nullable();
    $table->text('raqi_notes')->nullable();
    $table->string('cancel_reason')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

### `session_participants` table
```php
Schema::create('session_participants', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('appointment_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('raqi_id')->references('id')->on('raqi_profiles');
    $table->enum('role', ['lead', 'co_raqi'])->default('co_raqi');
    $table->enum('invite_status', ['pending', 'accepted', 'declined'])->default('pending');
    $table->timestamp('invited_at')->nullable();
    $table->timestamp('joined_at')->nullable();
    $table->timestamps();
    $table->unique(['appointment_id', 'raqi_id']);
});
```

### `session_notes` table
```php
Schema::create('session_notes', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('appointment_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('raqi_id')->references('id')->on('raqi_profiles');
    $table->text('content');
    $table->enum('note_type', ['ruqyah_log', 'observation', 'recommendation', 'chat']);
    $table->timestamps();
});
```

### `notifications` table
```php
Schema::create('notifications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('appointment_id')->nullable()->constrained()->nullOnDelete();
    $table->string('title');
    $table->text('body');
    $table->enum('channel', ['email', 'sms', 'push'])->default('email');
    $table->boolean('is_read')->default(false);
    $table->timestamp('sent_at')->nullable();
    $table->timestamps();
});
```

### `reviews` table
```php
Schema::create('reviews', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('appointment_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('patient_id')->references('id')->on('users');
    $table->foreignUuid('raqi_id')->references('id')->on('raqi_profiles');
    $table->tinyInteger('rating'); // 1-5
    $table->text('comment')->nullable();
    $table->timestamps();
    $table->unique(['appointment_id', 'patient_id']);
});
```

### `follow_ups` table
```php
Schema::create('follow_ups', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('appointment_id')->constrained()->cascadeOnDelete();
    $table->foreignUuid('raqi_id')->references('id')->on('raqi_profiles');
    $table->text('recommendation')->nullable();
    $table->timestamp('follow_up_date');
    $table->boolean('is_completed')->default(false);
    $table->timestamps();
});
```

---

## Phase 3 — Models

### Folder structure
```
app/
  Models/
    User.php
    PatientProfile.php
    RaqiProfile.php
    Availability.php
    Appointment.php
    SessionParticipant.php
    SessionNote.php
    Notification.php
    Review.php
    FollowUp.php
  Enums/
    UserRole.php
    SessionType.php
    AppointmentStatus.php
    ParticipantRole.php
    InviteStatus.php
    NoteType.php
    NotificationChannel.php
    RaqiStatus.php
```

### `User` model — key rules
```php
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasUuids, SoftDeletes;

    protected $fillable = ['full_name', 'email', 'phone', 'password', 'role'];
    protected $hidden   = ['password'];
    protected $casts    = ['role' => UserRole::class, 'is_active' => 'boolean'];

    public function getJWTIdentifier() { return $this->getKey(); }
    public function getJWTCustomClaims() { return ['role' => $this->role]; }

    public function patientProfile()  { return $this->hasOne(PatientProfile::class); }
    public function raqiProfile()     { return $this->hasOne(RaqiProfile::class); }
    public function appointments()    { return $this->hasMany(Appointment::class, 'patient_id'); }
    public function notifications()   { return $this->hasMany(Notification::class); }
}
```

### `RaqiProfile` model — key rules
```php
public function user()                  { return $this->belongsTo(User::class); }
public function availabilities()        { return $this->hasMany(Availability::class, 'raqi_id'); }
public function appointments()          { return $this->hasMany(Appointment::class, 'lead_raqi_id'); }
public function sessionParticipations() { return $this->hasMany(SessionParticipant::class, 'raqi_id'); }
public function reviews()               { return $this->hasMany(Review::class, 'raqi_id'); }

// Called by RaqiProfileObserver after every new Review
public function recalculateRating(): void
{
    $this->update([
        'rating'        => round($this->reviews()->avg('rating') ?? 0, 2),
        'total_reviews' => $this->reviews()->count(),
    ]);
}
```

### `Appointment` model — key rules
```php
protected $casts = [
    'session_type' => SessionType::class,
    'status'       => AppointmentStatus::class,
    'scheduled_at' => 'datetime',
];

public function patient()      { return $this->belongsTo(User::class, 'patient_id'); }
public function leadRaqi()     { return $this->belongsTo(RaqiProfile::class, 'lead_raqi_id'); }
public function participants() { return $this->hasMany(SessionParticipant::class); }
public function notes()        { return $this->hasMany(SessionNote::class); }
public function review()       { return $this->hasOne(Review::class); }
public function followUp()     { return $this->hasOne(FollowUp::class); }
public function notifications(){ return $this->hasMany(Notification::class); }

public function isCancellable(): bool
{
    return $this->scheduled_at->diffInHours(now()) > 2
        && in_array($this->status, [AppointmentStatus::Pending, AppointmentStatus::Confirmed]);
}
```

---

## Phase 4 — API Routes

```php
// routes/api.php

Route::prefix('v1')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
        Route::middleware('auth:api')->group(function () {
            Route::post('logout',  [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
            Route::get('me',       [AuthController::class, 'me']);
        });
    });

    // Patient
    Route::middleware(['auth:api', 'role:patient'])->prefix('patient')->group(function () {
        Route::get ('profile',                           [PatientProfileController::class, 'show']);
        Route::put ('profile',                           [PatientProfileController::class, 'update']);
        Route::get ('appointments',                      [AppointmentController::class, 'patientIndex']);
        Route::post('appointments',                      [AppointmentController::class, 'store']);
        Route::get ('appointments/{appointment}',        [AppointmentController::class, 'show']);
        Route::delete('appointments/{appointment}',      [AppointmentController::class, 'cancel']);
        Route::get ('raqis',                             [RaqiController::class, 'index']);
        Route::get ('raqis/{raqi}',                      [RaqiController::class, 'show']);
        Route::post('reviews',                           [ReviewController::class, 'store']);
        Route::get ('notifications',                     [NotificationController::class, 'index']);
        Route::put ('notifications/{notification}/read', [NotificationController::class, 'markRead']);
    });

    // Raqi
    Route::middleware(['auth:api', 'role:raqi', 'raqi.approved'])->prefix('raqi')->group(function () {
        Route::get('profile',  [RaqiProfileController::class, 'show']);
        Route::put('profile',  [RaqiProfileController::class, 'update']);
        Route::apiResource('availability', AvailabilityController::class);
        Route::get ('appointments',                               [AppointmentController::class, 'raqiIndex']);
        Route::put ('appointments/{appointment}/accept',          [AppointmentController::class, 'accept']);
        Route::put ('appointments/{appointment}/decline',         [AppointmentController::class, 'decline']);
        Route::put ('appointments/{appointment}/complete',        [AppointmentController::class, 'complete']);
        Route::post('appointments/{appointment}/invite',          [SessionParticipantController::class, 'invite']);
        Route::get ('appointments/{appointment}/participants',    [SessionParticipantController::class, 'index']);
        Route::get ('appointments/{appointment}/notes',           [SessionNoteController::class, 'index']);
        Route::post('appointments/{appointment}/notes',           [SessionNoteController::class, 'store']);
        Route::post('appointments/{appointment}/followup',        [FollowUpController::class, 'store']);
        Route::get ('notifications',                              [NotificationController::class, 'index']);
        Route::put ('notifications/{notification}/read',          [NotificationController::class, 'markRead']);
    });

    // Admin
    Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('users',                        [AdminUserController::class, 'index']);
        Route::get('users/{user}',                 [AdminUserController::class, 'show']);
        Route::put('users/{user}/activate',        [AdminUserController::class, 'activate']);
        Route::get('raqis/pending',                [AdminRaqiController::class, 'pending']);
        Route::put('raqis/{raqi}/approve',         [AdminRaqiController::class, 'approve']);
        Route::put('raqis/{raqi}/suspend',         [AdminRaqiController::class, 'suspend']);
        Route::get('appointments',                 [AdminAppointmentController::class, 'index']);
        Route::put('appointments/{appointment}',   [AdminAppointmentController::class, 'update']);
        Route::get('stats',                        [AdminStatsController::class, 'index']);
    });

    // Invite token flow
    Route::middleware('auth:api')->group(function () {
        Route::post('invites/{token}/accept',  [InviteController::class, 'accept']);
        Route::post('invites/{token}/decline', [InviteController::class, 'decline']);
    });
});
```

---

## Phase 5 — Controllers & Services

### Folder structure
```
app/
  Http/
    Controllers/Api/
      AuthController.php
      PatientProfileController.php
      RaqiProfileController.php
      RaqiController.php
      AvailabilityController.php
      AppointmentController.php
      SessionParticipantController.php
      SessionNoteController.php
      NotificationController.php
      ReviewController.php
      FollowUpController.php
      InviteController.php
      Admin/
        AdminUserController.php
        AdminRaqiController.php
        AdminAppointmentController.php
        AdminStatsController.php
    Middleware/
      RoleMiddleware.php
      RaqiApprovedMiddleware.php
    Requests/
      Auth/RegisterRequest.php
      Auth/LoginRequest.php
      Appointment/StoreAppointmentRequest.php
      Appointment/InviteRaqiRequest.php
      Review/StoreReviewRequest.php
      Availability/StoreAvailabilityRequest.php
  Services/
    AuthService.php
    AppointmentService.php
    SessionService.php
    NotificationService.php
    RaqiService.php
  Observers/
    RaqiProfileObserver.php
    AppointmentObserver.php
  Events/
    AppointmentConfirmed.php
    CoRaqiInvited.php
    SessionClosed.php
    NewNotification.php
    SessionNoteAdded.php
  Listeners/
    SendAppointmentNotification.php
    SendInviteNotification.php
  Jobs/
    SendEmailJob.php
    SendSmsJob.php
    SendAppointmentReminderJob.php
  Policies/
    AppointmentPolicy.php
    SessionNotePolicy.php
    ReviewPolicy.php
```

### Base `ApiController` (all controllers extend this)
```php
// app/Http/Controllers/Api/ApiController.php
abstract class ApiController extends Controller
{
    protected function success($data = null, string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data, 'message' => $message], $status);
    }

    protected function error(string $message, int $status = 400, $errors = null): JsonResponse
    {
        return response()->json(['success' => false, 'data' => $errors, 'message' => $message], $status);
    }
}
```

### `AuthController` — key methods
```php
public function register(RegisterRequest $request): JsonResponse
{
    $user  = AuthService::register($request->validated());
    $token = auth('api')->login($user);
    return $this->success($this->tokenPayload($token, $user), 'Registered successfully.', 201);
}

public function login(LoginRequest $request): JsonResponse
{
    if (!$token = auth('api')->attempt($request->only('email', 'password'))) {
        return $this->error('Invalid credentials.', 401);
    }
    return $this->success($this->tokenPayload($token, auth('api')->user()));
}

public function refresh(): JsonResponse
{
    return $this->success($this->tokenPayload(auth('api')->refresh()));
}

public function logout(): JsonResponse
{
    auth('api')->logout();
    return $this->success(null, 'Logged out successfully.');
}

private function tokenPayload(string $token, $user = null): array
{
    return [
        'access_token' => $token,
        'token_type'   => 'bearer',
        'expires_in'   => auth('api')->factory()->getTTL() * 60,
        'user'         => $user,
    ];
}
```

### `AppointmentService` — booking & cancellation
```php
public function book(User $patient, array $data): Appointment
{
    $this->checkAvailability($data['lead_raqi_id'], $data['scheduled_at'], $data['duration_minutes'] ?? 60);

    $appointment = Appointment::create([
        ...$data,
        'patient_id' => $patient->id,
        'status'     => AppointmentStatus::Pending,
    ]);

    $appointment->participants()->create([
        'raqi_id'       => $data['lead_raqi_id'],
        'role'          => ParticipantRole::Lead,
        'invite_status' => InviteStatus::Accepted,
        'joined_at'     => now(),
    ]);

    event(new AppointmentConfirmed($appointment));

    // Schedule 1-hour reminder
    SendAppointmentReminderJob::dispatch($appointment)
        ->delay(Carbon::parse($data['scheduled_at'])->subHour());

    return $appointment;
}

public function cancel(Appointment $appointment): void
{
    if (!$appointment->isCancellable()) {
        throw new \Exception('Cancellation requires at least 2 hours notice.');
    }
    $appointment->update(['status' => AppointmentStatus::Cancelled]);
    NotificationService::notify($appointment->patient, 'Appointment Cancelled', '...', $appointment);
    NotificationService::notify($appointment->leadRaqi->user, 'Appointment Cancelled', '...', $appointment);
}

private function checkAvailability(string $raqiId, string $scheduledAt, int $duration): void
{
    $start = Carbon::parse($scheduledAt);
    $end   = $start->copy()->addMinutes($duration);

    $conflict = Appointment::where('lead_raqi_id', $raqiId)
        ->whereIn('status', ['pending', 'confirmed'])
        ->where(function ($q) use ($start, $end) {
            $q->whereBetween('scheduled_at', [$start, $end])
              ->orWhereRaw('DATE_ADD(scheduled_at, INTERVAL duration_minutes MINUTE) BETWEEN ? AND ?', [$start, $end]);
        })->exists();

    if ($conflict) {
        throw new \Exception('Raqi is not available at the requested time.');
    }
}
```

### `SessionService` — multi-Raqi logic
```php
public function inviteCoRaqi(Appointment $appointment, string $raqiId, User $leadUser): SessionParticipant
{
    if ($appointment->lead_raqi_id !== $leadUser->raqiProfile->id) {
        throw new \Exception('Only the lead Raqi can invite co-Raqis.');
    }

    $raqi = RaqiProfile::where('id', $raqiId)
        ->where('is_approved', true)
        ->where('status', 'active')
        ->firstOrFail();

    $this->checkCoRaqiConflict($raqi->id, $appointment);

    $participant = $appointment->participants()->create([
        'raqi_id'       => $raqi->id,
        'role'          => ParticipantRole::CoRaqi,
        'invite_status' => InviteStatus::Pending,
        'invited_at'    => now(),
    ]);

    event(new CoRaqiInvited($participant));
    return $participant;
}

public function closeSession(Appointment $appointment, User $leadUser): void
{
    if ($appointment->lead_raqi_id !== $leadUser->raqiProfile->id) {
        throw new \Exception('Only the lead Raqi can close the session.');
    }
    $appointment->update(['status' => AppointmentStatus::Completed]);
    event(new SessionClosed($appointment));
}

private function checkCoRaqiConflict(string $raqiId, Appointment $appointment): void
{
    $conflict = SessionParticipant::where('raqi_id', $raqiId)
        ->whereHas('appointment', fn($q) => $q
            ->where('id', '!=', $appointment->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('scheduled_at', $appointment->scheduled_at)
        )->exists();

    if ($conflict) {
        throw new \Exception('Co-Raqi has a conflicting appointment at this time.');
    }
}
```

---

## Phase 6 — Middleware

### `RoleMiddleware`
```php
// app/Http/Middleware/RoleMiddleware.php
public function handle(Request $request, Closure $next, string $role): Response
{
    if (!auth('api')->check() || auth('api')->user()->role->value !== $role) {
        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }
    return $next($request);
}
```

### `RaqiApprovedMiddleware`
```php
public function handle(Request $request, Closure $next): Response
{
    $user = auth('api')->user();
    if (!$user->raqiProfile?->is_approved) {
        return response()->json(['success' => false, 'message' => 'Raqi account pending approval.'], 403);
    }
    return $next($request);
}
```

### Register in `bootstrap/app.php`
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role'          => \App\Http\Middleware\RoleMiddleware::class,
        'raqi.approved' => \App\Http\Middleware\RaqiApprovedMiddleware::class,
    ]);
})
```

---

## Phase 7 — Policies

```php
// app/Policies/AppointmentPolicy.php
public function view(User $user, Appointment $appointment): bool
{
    return $user->id === $appointment->patient_id
        || $user->raqiProfile?->id === $appointment->lead_raqi_id
        || $appointment->participants->contains('raqi_id', $user->raqiProfile?->id)
        || $user->role === UserRole::Admin;
}

public function cancel(User $user, Appointment $appointment): bool
{
    return $user->id === $appointment->patient_id && $appointment->isCancellable();
}

public function complete(User $user, Appointment $appointment): bool
{
    return $user->raqiProfile?->id === $appointment->lead_raqi_id;
}

public function invite(User $user, Appointment $appointment): bool
{
    return $user->raqiProfile?->id === $appointment->lead_raqi_id;
}
```

---

## Phase 8 — Jobs & Notifications

```php
// app/Services/NotificationService.php
public static function notify(User $user, string $title, string $body, ?Appointment $appointment = null, string $channel = 'email'): void
{
    $record = \App\Models\Notification::create([
        'user_id'        => $user->id,
        'appointment_id' => $appointment?->id,
        'title'          => $title,
        'body'           => $body,
        'channel'        => $channel,
    ]);

    match ($channel) {
        'email' => SendEmailJob::dispatch($record),
        'sms'   => SendSmsJob::dispatch($record),
        default => null,
    };

    broadcast(new NewNotification($record))->toOthers();
}
```

```php
// app/Jobs/SendEmailJob.php
public function handle(): void
{
    Mail::to($this->notification->user->email)
        ->send(new AppointmentMail($this->notification));
    $this->notification->update(['sent_at' => now()]);
}

// app/Jobs/SendSmsJob.php
public function handle(): void
{
    $twilio = new \Twilio\Rest\Client(config('services.twilio.sid'), config('services.twilio.token'));
    $twilio->messages->create($this->notification->user->phone, [
        'from' => config('services.twilio.from'),
        'body' => $this->notification->body,
    ]);
    $this->notification->update(['sent_at' => now()]);
}

// Dispatched at booking time, fires 1 hour before session
// SendAppointmentReminderJob::dispatch($appointment)->delay($appointment->scheduled_at->subHour());
```

---

## Phase 9 — Realtime (Laravel Reverb)

```php
// routes/channels.php

// Per-user notification channel
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Per-appointment session room
Broadcast::channel('appointment.{appointmentId}', function ($user, $appointmentId) {
    $appt = Appointment::find($appointmentId);
    if (!$appt) return false;
    return $user->id === $appt->patient_id
        || $appt->participants->contains('raqi_id', $user->raqiProfile?->id);
});
```

Events to broadcast:

| Event | Channel | Trigger |
|---|---|---|
| `NewNotification` | `user.{id}` | Any notification created |
| `AppointmentConfirmed` | `appointment.{id}` | Raqi accepts |
| `CoRaqiInvited` | `appointment.{id}` | Lead invites co-Raqi |
| `SessionClosed` | `appointment.{id}` | Lead closes session |
| `SessionNoteAdded` | `appointment.{id}` | Any Raqi adds note |

---

## Phase 10 — Session Types

| Type | Backend | Frontend |
|---|---|---|
| `video` | Store `jitsi_room = "jetty-{appointment_id}"` | Embed Jitsi iframe |
| `chat` | Messages stored as `SessionNote` (`note_type=chat`), broadcast via Reverb | WebSocket chat |
| `phone` | Store phone number, show dial instructions | Dial-in screen |
| `in_person` | Store Raqi address, no realtime | Location card |

---

## Phase 11 — Testing (Pest)

```bash
composer require pestphp/pest --dev
php artisan pest:install
```

```
tests/Feature/
  Auth/RegisterTest.php
  Auth/LoginTest.php
  Patient/BookAppointmentTest.php
  Patient/BrowseRaqisTest.php
  Patient/CancelAppointmentTest.php
  Raqi/AcceptDeclineTest.php
  Raqi/InviteCoRaqiTest.php
  Raqi/SessionNotesTest.php
  Admin/ApproveRaqiTest.php
  Admin/StatsTest.php
  Notifications/NotificationDispatchTest.php
```

Run all tests:
```bash
php artisan test
php artisan test --filter BookAppointmentTest
```

---

## Build order for agent

Follow this exact sequence — no skipping:

```
1.  laravel new jetty-backend
2.  composer require tymon/jwt-auth + jwt:secret
3.  composer require spatie/laravel-permission laravel/reverb
4.  Configure .env (DB, JWT, Redis, Mail, Twilio)
5.  Create all Enum classes (app/Enums/)
6.  Run migrations in dependency order
7.  Create Models with HasUuids, relationships, casts
8.  Register Observers in AppServiceProvider
9.  Create Form Request classes (validation rules)
10. Create Policy classes + register in AuthServiceProvider
11. Create RoleMiddleware + RaqiApprovedMiddleware
12. Register middleware aliases in bootstrap/app.php
13. Create base ApiController
14. Create Service classes (Auth, Appointment, Session, Notification)
15. Create Jobs (Email, SMS, Reminder)
16. Create Events + Listeners, wire in EventServiceProvider
17. Create all Controllers
18. Define routes/api.php
19. Configure Reverb channels in routes/channels.php
20. php artisan test — all green
21. React frontend (separate repo)
22. Axios JWT interceptor + silent token refresh
23. Patient panel
24. Raqi panel
25. Admin panel
26. Laravel Echo + Reverb WebSocket integration
```

---

## Key business rules (must enforce)

| Rule | Enforced in |
|---|---|
| Cancellation only if `scheduled_at` > 2 hours away | `Appointment::isCancellable()` + `AppointmentPolicy::cancel()` |
| Only `is_approved=true` + `status=active` Raqis in browse | `RaqiController::index()` query scope |
| `rating` always computed from reviews, never stored manually | `RaqiProfileObserver` on Review created |
| Co-Raqi must not have conflicting appointment at same time | `SessionService::checkCoRaqiConflict()` |
| Only Lead Raqi can invite co-Raqis or close session | `AppointmentPolicy::invite()` + `SessionService` guard |
| Co-Raqi must be `is_approved` + `status=active` | `SessionService::inviteCoRaqi()` |
| All timestamps in UTC | `config/app.php` → `timezone = 'UTC'` |
| API envelope always `{ success, data, message }` | Base `ApiController` helper methods |
| Reminder job fires 1 hour before session | `AppointmentObserver::created()` dispatches delayed job |
| Duplicate review per appointment blocked | DB unique constraint on `(appointment_id, patient_id)` |
