# GameRating

GameRating est une application web développée avec **Symfony** permettant de **gérer, noter et suivre ses jeux vidéo**.

L'utilisateur peut créer un compte, ajouter des jeux à sa collection, leur attribuer une note sur 5 / 10 / 100, une periode, un temps de jeu, un commentaire et consulter ses statistiques.

---

## Fonctionnalités

* Authentification utilisateur (inscription / connexion)
* Ajout de jeux à sa collection
* Attribution de notes personnalisées
* Gestion des jeux par utilisateur
* Statistiques (notes moyennes, temps de jeu, jeu de l'année, etc.)
* Timeline graphique des jeux
* Gestion de profil utilisateur
* Intégration API externe (IGDB)

---

## Stack technique

* **Backend** : PHP 8+ (testé avec PHP 8.4)
* **Framework** : Symfony 6.4
* **ORM** : Doctrine
* **Base de données** : MySQL
* **Frontend** : Twig + bulma + vis.js
* **API externe** : IGDB

---

## Structure du projet

```
src/
├── Controller/     # Logique des routes (Game, User, API, etc.)
├── Entity/         # Entités Doctrine (Game, User, UserGame, etc.)
├── Repository/     # Accès base de données
├── Form/           # Formulaires Symfony
├── Service/        # Logique métier (stats, API IGDB, etc.)
└── Security/       # Sécurité (auth, voters, etc.)
```

---

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/dremms/gameRating.git
cd gameRating
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer l'environnement

Créer ou modifier le fichier `.env` :

```env
DATABASE_URL="mysql://user:password@127.0.0.1:3306/gamerating"
```

---

### 4. Créer la base de données

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

---

### 5. Lancer le serveur

```bash
symfony server:start
```

ou

```bash
php -S localhost:8000 -t public
```

---

## API externe (IGDB)

Le projet utilise un service (`IgdbClientService`) pour récupérer des données de jeux.

il faut configurer les clés API IGDB dans le `.env` :

```env
IGDB_CLIENT_ID=your_client_id
IGDB_CLIENT_SECRET=your_secret
```

---

## Auteur

* dremms

---

## Améliorations prévues

* Ajout d’un flux RSS
* Ajout de la consultation des autres profils
* Modification de l'import de jeux avec recherche par nom au lieu d'ID IGDB
* Refonte du front (meilleur responsive / menu burger...)

---

## Impressions d'écrans

### Écran de login
![Écran de login](public/imageMd/1login.png)

### Page d'accueil
![Page d'accueil](public/imageMd/2home.png)

### Jeux de l'utilisateur
![Jeux de l'utilisateur](public/imageMd/3userGame.png)

### Affichage d'un jeu
![Affichage d'un jeu](public/imageMd/4userGameDisplay.png)

### Édition d'un jeu
![Édition d'un jeu](public/imageMd/5userGameEdit.png)

### Ajout d'un jeu
![Ajout d'un jeu](public/imageMd/6addGame.png)
