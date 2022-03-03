## OpenclassRooms - Formation PHP/Symfony Projet 6 - Création du site SnowTricks

Codacy

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/8805e9985eb346778e1eb4c8addbaa45)](https://www.codacy.com/gh/Gui-Dev86/P6-SnowTricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Gui-Dev86/P6-SnowTricks&amp;utm_campaign=Badge_Grade)

## Environnement de développement:
    - Symfony 5.3
    - Bootstrap 4.4
    - Composer 1.11
    - jQuery 3.5
    - WampServer 3.2.5
        - Apache 2.4.46
        - PHP 7.3.21
        - MySQL 5.7.31

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

Pour paramétrer votre base de données, modifiez cette ligne avec le nom d'utilisateur, mot de passe et nom de la base de données correespondant (ne pas oublier de retirer le # devant la ligne afin qu'elle soit prise en compte).

    # DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"

exemple : DATABASE_URL="mysql://utilisateur(root de base):mot de passe(vide de base)@127.0.0.1:3306/(nom de la base de données)

Pour paramétrer votre adresse gmail, modifiez cette ligne. Il suffit de remplacer ADRESS et PASSWORD par votre adresse gmail et le mot de passe correspondant.

    # MAILER_DSN=smtp://ADRESS:PASSWORD@smtp.gmail.com?verify_peer=0

Si vous voulez utiliser un autre smtp que celui de gmail il vous faudra l'installer avec composer, vous trouvez dans ce lien les commandes permettant d'installer ces smtp:

https://symfony.com/doc/current/mailer.html

Il restera ensuite à copier les lignes correspondantes à ce nouveau serveur smtp dans votre .env.local et les paramétrer de la même que manière que le smtp de gmail.

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

Le projet est maintenant correctemont installé. Pour le lancer déplacez vous dans le répertoire du projet et utilisez la commande :
```
$ symfony server:start
```
Auteur Guillaume Vignères - Formation Développeur d'application PHP/Symfony - Openclassroom
