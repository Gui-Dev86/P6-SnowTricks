## OpenclassRooms - Formation PHP/Symfony Projet 6 - Création ddu site SnowTricks

Codacy

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/8805e9985eb346778e1eb4c8addbaa45)](https://www.codacy.com/gh/Gui-Dev86/P6-SnowTricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Gui-Dev86/P6-SnowTricks&amp;utm_campaign=Badge_Grade)

Prérequis Php et Composer doivent être installés sur votre serveur afin de pouvoir utiliser le site. Disposer d'un server local en cours d'exécution.

## Installation

- Cloner le Repositary GitHub dans le dossier de votre choix: 
```
git clone https://github.com/Gui-Dev86/P6-SnowTricks.git
```
- Installez les dépendances du projet avec Composer et npm:
```
composer install
```
```
npm install
```
- Réalisez une copie du fichier .env nommée .env.local qui devra être crée à la racine du projet. Vous y configurez vos variables d'environnement tel que la connexion à la base de données, votre serveur SMTP ou adresse mail.

- Si elle n'existe pas déjà créez la base de données, depuis le répertoire du projet utilisez la commande:
```
php bin/console doctrine:database:create
```
Générez le fichier de migration des tables de la base de données:
```
php bin/console make:migration
```
Effectez la migration vers la base de données :
```
php bin/console doctrine:migrations:migrate
```

- Si vous souhaitez installer des données fictives afin de bénéficier d'une démo vous pouvez installer les fixtures:
```
php bin/console doctrine:fixtures:load
```
Le premier utilisateur créé est fixe et possède les droits d'administrateur afin de pouvoir tester toutes les fonctionnalités du site:
pseudo: admin
mot de passe: azerty

Le projet est maintenant correctemont installé.

Auteur Guillaume Vignères - Formation Développeur d'application PHP/Symfony - Openclassroom