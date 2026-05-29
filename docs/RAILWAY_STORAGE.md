# Railway Storage Notes

Uploaded profile, banner, post and gallery images are stored on Laravel's `public` disk:

```text
storage/app/public
```

The app displays these files through the `/media/{path}` route, so profile images do not rely on local Windows paths and do not require hardcoded absolute paths.

For direct `/storage/...` URLs or third-party tooling, keep the Laravel public storage link available:

```bash
php artisan storage:link --force
```

Railway deploys should also run migrations after new media/profile fields are added:

```bash
php artisan migrate --force
```

Recommended Railway environment values:

```text
FILESYSTEM_DISK=public
APP_URL=https://your-railway-domain
```

If Railway uses ephemeral storage, uploaded files may disappear after redeploy/restart unless a persistent volume or external object storage is configured later.
