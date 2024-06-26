<p align='center'>
  <img width='128px' src='https://github.com/Shybaaaa/Forum/blob/main/public/image/logo_transparent.png' alt='logo'>  
</p>

# Forum

## Table des matières
- [Introduction](#introduction)
- [Fonctionnalités](#fonctionnalités)
- [Installation](#installation)
- [Modification](#modification)
- [Configuration](#configuration)
- [Licence](#licence)
- [Auteurs](#auteurs)

## Introduction

Bienvenue sur **Forum G1**, une plateforme de discussion moderne et conviviale conçue pour favoriser les échanges et les discussions entre utilisateurs. Forum G1 permet de créer des communautés dynamiques et engagées.

## Fonctionnalités

- **Catégories de discussion** : Organisez les sujets en différentes catégories.
- **Sujets et réponses** : Créez des sujets et répondez aux discussions existantes.
- **Modération** : Outils de modération pour maintenir un environnement respectueux.
- **Interface utilisateur réactive** : Profitez d'une interface fluide sur tous les appareils.

## Installation
Pour installer l'application et pré-requis effectuer les commandes suivantes :
```git
git clone https://github.com/Shybaaaa/workshop_forum.git
```

<br/>

Après vous pourrez executer la commande suivante pour installer l'ensemble des pré-requis :
```node
npm run installDependencies
```

<br/>

Ensuite vous lancez la commande suivante pour générer le code CSS et être à jour sur celui-ci :
```node
npm run compress
```

<br/>

Pour au final démarrer le serveur avec la commande suivante : 
```node
npm run serve
```
ou
```php
php -S localhost:8888
```

<br/>

## Modification

Si vous désirez modifier le code CSS (Tailwind), exécutez la commande suivante, qui générera le code CSS en même temps que vos changements :

```npm
npm run build
```

## Configuration

Assurez-vous de configurer les variables d'environnement nécessaires. Créez un fichier `.env` à la racine du projet et ajoutez les informations suivantes :

```plaintext
DB_HOST=your_database_host
DB_USER=your_database_user
DB_PASS=your_database_password
DB_NAME=your_database_name
```

## Licence

Ce projet est sous licence Apache2. Voir le fichier [LICENSE](LICENSE) pour plus d'informations.

## Auteurs

- [**Shybaaaa**](https://github.com/shybaaaa) - *Développeur principal*
- [**A-Meuret**](https://github.com/A-Meuret) - *Développeur principal*
- [**aless-guisset**](https://github.com/aless-guisset) - *Développeur principal*
- [**Gauv0**](https://github.com/gauv0) - *Développeur Darkmode*
