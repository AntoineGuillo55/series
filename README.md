# Série – Projet Démo Symfony – ENI

Bienvenue sur le dépôt du projet **Série**, réalisé dans le cadre du module de formation Symfony à l'ENI.

## Présentation

**Série** est une application de démonstration développée pour illustrer les principales fonctionnalités du framework Symfony. 

## Fonctionnalités principales

- Gestion des séries et saisons
- Authentification et gestion des utilisateurs
- Utilisation de Doctrine ORM
- Formulaires et validation
- Sécurité (gestion des rôles, authentification JWT, etc.)
- API Platform pour l'exposition d'API REST
- Envoi d'e-mails
- Gestion des fichiers et images


## Structure du projet

- `src/` : Code source principal (contrôleurs, entités, formulaires, services...)
- `templates/` : Vues Twig
- `public/` : Point d'entrée de l'application
- `config/` : Fichiers de configuration Symfony
- `assets/` : Fichiers front-end (JS, CSS, images)
- `migrations/` : Migrations de base de données
- `tests/` : Tests automatisés


## Installation

1. Cloner le dépôt :

   ```bash
   git clone <url-du-depot>
   ```

2. Installer les dépendances PHP :

   ```bash
   composer install
   ```

3. Configurer la base de données dans `.env`

4. Exécuter les migrations :

   ```bash
   php bin/console doctrine:migrations:migrate
   ```

5. Lancer le serveur de développement :

   ```bash
   symfony serve
   ```


## Auteur

Antoine Guillo


## Licence

Ce projet est fourni à des fins pédagogiques.
