# Todos - Gestionnaire de Tâches

![Laravel](https://img.shields.io/badge/Laravel-10.x-red) ![Vite](https://img.shields.io/badge/Vite-Frontend-blue)

## Description

**Todos** est une application simple de gestion de tâches construite avec **Laravel** et **Vite**. L'application permet d'ajouter, modifier, supprimer et afficher des tâches dans une base de données.

## Fonctionnalités

- Créer des tâches  
- Modifier des tâches existantes  
- Supprimer des tâches  
- Afficher la liste des tâches  

## Prérequis

- PHP >= 8.1  
- Composer  
- Node.js & npm  
- Base de données (SQLite, MySQL, etc.)  

## Installation

1. **Cloner le projet** :

   ```bash
   git clone https://github.com/anthony20021/todos.git
   cd todos

2. **Installer les dépendances** :

   ```bash
    composer install
    npm install

3. **Configurer l'application** :

- Dupliquer .env.example et renommer en .env.
- Mettre à jour les informations de la base de données.

4. **Générer la clé** :
   
   ```bash
    php artisan key:generate

5. **Migrer la base de données** :

    ```bash
    php artisan migrate

    Ou utilisez le fichier todos.dbm avec pgmodeler

6. **Démarrer le serveur** :

    ```bash
    php artisan serve
    
    Accédez à l'application sur http://localhost:8000.

Outils Utilisés

    Laravel : Backend
    Vite : Build frontend
    Tailwind CSS (si applicable) : Design

Licence

Ce projet est sous licence MIT.
