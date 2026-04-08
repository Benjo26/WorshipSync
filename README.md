# WorshipSync

WorshipSync is a Laravel application for worship bands. Musicians can sign in with Google, store chord charts and song structure, open songs in a rehearsal-friendly player, transpose on the fly, and practice with a built-in metronome.

## Features

- Google sign-in with Laravel Socialite
- Private song library per user
- Chord chart JSON stored in Laravel storage
- Song arrangement tracking for verse, chorus, bridge, tag, and more
- Live transpose controls in the player view
- Tap-tempo metronome with beat indicator
- Laravel Cloud deployment support

## Local setup

1. Install PHP 8.3+, Composer, and Node.js 20+.
2. Copy the environment file.

```bash
cp .env.example .env
```

3. Install dependencies.

```bash
composer install
npm install
```

4. Generate the application key.

```bash
php artisan key:generate
```

5. Configure database and Google OAuth values in `.env`.
6. Run migrations.

```bash
php artisan migrate
```

7. Start the application.

```bash
composer run dev
```

## Google OAuth

Set these values:

- `GOOGLE_CLIENT_ID`
- `GOOGLE_CLIENT_SECRET`
- `GOOGLE_REDIRECT_URI`

Example production callback:

```text
https://your-app-name.laravel.cloud/auth/google/callback
```

## Storage format

Song metadata is stored in the database, while the chord chart content is stored in Laravel storage under:

```text
storage/app/private/songs/{uuid}.json
```

Example chart JSON:

```json
{
  "sections": [
    {
      "name": "Verse 1",
      "lines": [
        "[G]Amazing [D]grace how [Em]sweet the [C]sound"
      ]
    }
  ]
}
```

## Laravel Cloud

Recommended production values:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`
- `FILESYSTEM_DISK=local`

Deployment commands:

```bash
composer install --no-interaction --prefer-dist --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
