# Contact Form + Email Sender (PHP + SMTP)

This project provides a contact form that sends messages via SMTP. It includes
an overview page, a send form, and an inbox view backed by the MailHog API for local testing.

## Run with Docker

1. Build and start the stack:

   docker compose up --build

2. Open the app in a browser:

   localhost:8080

3. MailHog inbox UI:

   localhost:8025

## Run locally (without Docker)

1. Install dependencies:

   composer install

2. Start the PHP server:

   php -S 127.0.0.1:8080 -t public

3. Make sure MailHog is running locally on SMTP port 1025 and API port 8025.

## Environment variables

- SMTP_HOST (default: mailhog)
- SMTP_PORT (default: 1025)
- SMTP_USERNAME (default: empty)
- SMTP_PASSWORD (default: empty)
- SENDER_EMAIL (default: noreply@yoursite.com)
- ADMIN_EMAIL (default: admin@yoursite.com)
- MAILHOG_API (default: http://mailhog:8025/api/v2/messages)
