# MyEvents

Plateforme de gestion d'événements avec Laravel.

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## Démarrer

```bash
php artisan serve
```

Ensuite => http://localhost:8000

## Login

Admin : admin@eventpro.fr / password123
Chef de projet : sophie@eventpro.fr / password123

## Fichiers importants

- Authentification : `app/Http/Controllers/Auth/`
- Modèles : `app/Models/`
- Routes : `routes/web.php`
- Vues : `resources/views/`
- Admin Filament : `http://localhost:8000/admin`

## Fonctionnalités

- Créer des événements
- S'inscrire sans compte
- Inviter des gens
- Contraintes alimentaires
- Export CSV des participants
- Dashboard avec stats

