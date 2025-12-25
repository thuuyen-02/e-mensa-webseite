# E-Mensa – Webanwendung (DBWT Praktikum)

Webanwendung aus dem Praktikum **Datenbanken und Webtechnologien (DBWT)**.
Im Projekt wird über mehrere Meilensteine eine „E-Mensa“-Webanwendung entwickelt:
Start mit einer Werbeseite, anschließend Funktionen zum Auswählen/Vorbestellen/Bewerten
von Speisen sowie eine mobile Webversion. :contentReference[oaicite:1]{index=1}

## Features (Beispiele – bitte anpassen)
- Werbeseite mit Informationen zur E-Mensa
- Anzeige von Gerichten / Speiseplan
- Vorbestellung / Warenkorb
- Bewertungen & Kommentare
- Mobile-optimierte Ansicht (Responsive Design)

## Tech Stack
- Frontend: HTML, CSS :contentReference[oaicite:2]{index=2}
- Backend: PHP (>= 8.2) :contentReference[oaicite:3]{index=3}
- Datenbank: MariaDB (>= 10.5), SQL :contentReference[oaicite:4]{index=4}
- Framework: Laravel (>= 11) *(falls im Projekt verwendet)* :contentReference[oaicite:5]{index=5}

## Voraussetzungen
- PHP 8.2+
- MariaDB 10.5+
- (Optional) Composer & Laravel 11, falls genutzt

## Setup & Start

### Variante A: Laravel (falls vorhanden)
```bash
composer install
cp .env.example .env
php artisan key:generate

# DB-Zugang in .env eintragen, dann:
php artisan migrate
php artisan serve
