# Setup — Desert Sky Tourism

Place `public/` contents in `public_html/` and `private/` contents in a sibling `dst-private/` directory outside the web root. Preserve hidden `.htaccess` files. The public front controller and `PUBLIC_ROOT` in `private/core.php` expect this layout.

Update `ORIGIN` in `private/core.php` for your own HTTPS domain. The first request initializes a fresh SQLite database in `dst-private/storage/` from `seed.json`.

For a new installation, create a private `dst-private/initial-admin.json` containing your own `email` and a strong new `password`. This file is consumed and deleted after the account is hashed into the local database. Never commit it. Production administrators and passwords are excluded.

Configure SMTP and Stripe from your own dashboard using your own accounts and keys. Payment and email workflows require those integrations and acceptance testing before being used with customers. No live payment or email credentials are included.

Preserve `storage/` across source deployments. The generated encryption key and database belong together and must remain private. Uploaded media and live customer/booking records are not in this repository.
