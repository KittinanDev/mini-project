# Contact Form + Email Sender (PHP + SMTP)

โปรเจกต์นี้เป็นฟอร์มติดต่อที่ส่งข้อความผ่าน SMTP โดยมีหน้าฟอร์มส่งข้อความ
และรองรับการทดสอบกล่องจดหมายผ่าน MailHog API ในเครื่อง

## การรันด้วย Docker

0. (ตัวเลือก) สร้างไฟล์ `.env` จาก `.env.example` แล้วปรับค่าตามต้องการ:

   cp .env.example .env

   สำหรับ Windows PowerShell:

   Copy-Item .env.example .env

1. สร้างและเริ่มต้นบริการทั้งหมด:

   docker compose up --build

2. เปิดแอปในเบราว์เซอร์:

   localhost:8080

   ถ้าพอร์ต 8080 ถูกใช้งานอยู่ ให้ตั้งค่า `APP_PORT` ใน `.env` (เช่น `APP_PORT=8081`) แล้วเปิด `localhost:8081`

3. หน้า MailHog Inbox:

   localhost:8025

## การรันแบบ Local (ไม่ใช้ Docker)

1. ติดตั้ง dependencies:

   composer install

2. เริ่ม PHP server:

   php -S 127.0.0.1:8080 -t public

3. ตรวจสอบว่า MailHog ทำงานอยู่บนเครื่องที่ SMTP port 1025 และ API port 8025

## ตัวแปรสภาพแวดล้อม (Environment variables)

- SMTP_HOST (ค่าเริ่มต้น: mailhog)
- SMTP_PORT (ค่าเริ่มต้น: 1025)
- SMTP_USERNAME (ค่าเริ่มต้น: ว่าง)
- SMTP_PASSWORD (ค่าเริ่มต้น: ว่าง)
- SENDER_EMAIL (ค่าเริ่มต้น: noreply@yoursite.com)
- SENDER_NAME (ค่าเริ่มต้น: Contact Form System)
- ADMIN_EMAIL (ค่าเริ่มต้น: admin@yoursite.com)
- MAILHOG_API (ค่าเริ่มต้น: http://mailhog:8025/api/v2/messages)
- APP_PORT (ค่าเริ่มต้น: 8080)
