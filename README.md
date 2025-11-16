# Digitup Training Platform API  
Plateforme de gestion des formations – Développement Laravel

##  1. Introduction
Cette API permet la gestion complète d'une plateforme de formations comprenant :
- Authentification par rôles (admin, formateur, apprenant)
- Gestion des utilisateurs, catégories et formations
- Système d’inscription aux formations
- Suivi des formations par formateur/apprenant

---

#  2. Installation du projet

##  Prérequis
Avant d’installer le projet, assurez-vous d’avoir :
- PHP ≥ 8.1
- Composer ≥ 2.x
- MySQL ≥ 8
- Laravel 10
- Postman (pour les tests API)

---

##  Étapes d'installation

### 1 Cloner le dépôt
```bash
git clone https://github.com/AbdeljalilPr/digitup_test.git
cd digitup_test
```

### 2 Installer les dépendances
```bash
composer install
```

### 3 Copier et configurer le fichier .env
```bash
cp .env.example .env
```

Modifier dans `.env` :
```
DB_DATABASE=test_db
DB_USERNAME=root
DB_PASSWORD=
```

Puis générer la clé :
```bash
php artisan key:generate
```

### 4 Lancer les migrations
```bash
php artisan migrate
```

### 5 (Optionnel) Seeders
Si vous ajoutez des seeders plus tard :
```bash
php artisan db:seed
```

### 6 Lancer le serveur
```bash
php artisan serve
```

---

# 3. Schéma de la base de données (ERD)
Voici un résumé textuel du modèle relationnel actuel :

```
Users
- id
- name
- email
- password
- role (admin, formateur, apprenant)
- timestamps

Categories
- id
- nom
- description
- icone
- timestamps

Trainings
- id
- titre
- description
- duree
- niveau (debutant, intermediaire, expert)
- categorie_id → categories.id
- formateur_id → users.id
- prix
- date_debut
- nombre_max_participants
- statut (en_cours, terminee, annulee)
- timestamps

Enrollments
- id
- user_id → users.id
- training_id → trainings.id
- statut (en_attente, acceptee, terminee)
- note_finale
- timestamps
EntrepriseTrainingSeats
- id, entreprise_id → users.id, training_id → trainings.id, seats_purchased, seats_used, timestamps
```

###  Relations
- User 1—> Trainings (formateur → ses formations)
- Category 1—> Trainings
- Training 1—> Enrollments
- User <—> Training (via enrollments)
- Entreprise 1—> Employees (users)

- Entreprise 1—> Seats (EntrepriseTrainingSeats)

---

#  4. Authentification & Middleware

### Système utilisé : **Laravel Sanctum**

### Roles supportés :
- **admin**
- **formateur**
- **apprenant**
- **entreprise**
### Middlewares :
- `auth:sanctum`
- `role:admin`
- `role:formateur`
- `role:apprenant`
- `role:entreprise`

Le middleware Role vérifie que l’utilisateur a bien le rôle associé à la route.

---

#  5. Routes API disponibles

##  Auth
| Méthode | Route | Rôle | Description |
|--------|--------|-------|-------------|
| POST | /register | public | inscription |
| POST | /login | public | connexion |
| POST | /logout | auth | déconnexion |
| GET | /user | auth | infos utilisateur |

---

##  Routes ADMIN  
( middleware : `auth:sanctum`, `role:admin` )

### Users
| Méthode | Route |
|--------|--------|
| GET | /users |
| POST | /users |
| PUT | /users/{id} |
| DELETE | /users/{id} |

### Categories
| Méthode | Route |
|--------|--------|
| GET | /categories |
| POST | /categories |
| PUT | /categories/{id} |
| DELETE | /categories/{id} |

### Trainings
| Méthode | Route |
|--------|--------|
| GET | /trainings |
| POST | /trainings |
| PUT | /trainings/{id} |
| DELETE | /trainings/{id} |

---

##  Routes FORMATEUR  
(middleware : `auth:sanctum`, `role:formateur`)

| Méthode | Route | Description |
|--------|--------|-------------|
| GET | /my-trainings | Voir mes formations |
| POST | /trainings/{training}/grade | Noter un apprenant |

---

##  Routes APPRENANT  
(middleware : `auth:sanctum`, `role:apprenant`)

| Méthode | Route |
|--------|--------|
| POST | /trainings/{training}/enroll |
| GET | /my-enrollments |

## Routes ENTREPRISE
---

#  6. Exemples Postman

### Register
POST `/register`
```json
{
  "name": "Abdou",
  "email": "Abdou@any.com",
  "password": "123456",
  "role": "apprenant"
}
```

### Login
POST `/login`
```json
{
  "email": "Abdou@any.com",
  "password": "123456"
}
```

Exemple d’en-têtes après connexion :
```
Authorization: Bearer {token}
```

### Créer une formation (admin)
```json
{
  "titre": "Laravel Avancé",
  "description": "Formation complète",
  "duree": 12,
  "niveau": "expert",
  "categorie_id": 1,
  "prix": 120,
  "date_debut": "2025-01-20",
  "nombre_max_participants": 25
}
```

### S’inscrire à une formation (apprenant)
POST `/trainings/1/enroll`

---

#  7. Architecture du projet

Actuellement, l’architecture mise en place est :

```
Controller → Service → Repository → Models → Database
```

Controllers : reçoivent la requête HTTP, valident via FormRequest/DTO, appellent les Services
Services : contiennent la logique métier, valident les règles complexes, appellent les Repositories
Repositories : gèrent uniquement l’accès à la base de données
DTOs : typés et validés, transportent les données entre Controllers et Services

### Pourquoi pas encore Service/Repository ?

Parce que cette partie est prévue dans les étapes ultérieures du test.
Pour l’instant : **structure simple, claire, fonctionnelle**.

---

