# Docker Compose LEMP Stack

This repository contains a `docker-compose` configuration to start a `LEMP (Linux, Nginx, MariaDB, PHP, Adminer, Node)` stack.

It is based on [https://github.com/stevenliebregt/docker-compose-lemp-stack](https://github.com/stevenliebregt/docker-compose-lemp-stack)

I use it only as a base environment to teach PHP in a School located in Liège, Belgium

## Détails

Les versions les plus récentes disponibles au moment de la récupération des images sont toujours utilisées.

Le moteur de bases de données utilisé est MariaDB.

PHP est installé avec Composer, mais aussi git, zip, nodejs et npm 🎉. 

Le dossier dans lequel vous devez coder votre application PHP est `app/`, situé à la racine de ce repo une fois cloné sur votre machine.

## Configuration

**UTILISATEURS DE WINDOWS**
Pour faire fonctionner Docker vous avez dû activer WSL2 et installer Ubuntu comme sous-système Linux sur votre Windows.

Les commandes qui suivent sont à taper dans le terminal d’Ubuntu. 

Pour rappel `cd /mnt/LETTRE_DE_DISQUE/Users/VOTRE_NOM/PATH_VERS_VOTRE_PROJET` vous placera dans le bon répertoire pour la suite des opérations.


La configuration de Nginx est `config/nginx/`. Vous ne devrez pas y toucher en principe.

Pour plus de facilité, un fichier d’environnement, `.env` est utilisé dans cette distribution. Il vous permet de définir certaines valeurs à réutiliser dans le `docker-compose.yml`, comme par exemple, le nom du projet et les informations de connexion à la DB. Vous pouvez en ajouter au besoin.


| Key | Description |
|-----|-------------|
|APP_NAME|Le nom à utiliser pour nommer les conteneurs.|
|MYSQL_ROOT_PASSWORD|Le mot de passe pour l’utilisateur `root` du SGBD.|

## Usage

### Démarrage

*Ne clonez pas ce repo*. Préférez le téléchargement classique des fichiers.

![Téléchargez le dossier](/readme-img/download-zip.png)

Ensuite, si vous prévoyez d’utiliser git et github sur votre projet, initialisez un repo dans le dossier récupéré (`git init`), ajoutez-y tous les fichiers (`git add .`) et définissez son *remote* (`git remote add nom-du-remote path-du-remote.git`) vers un repo github à vous. 

Vous ferez cela pour *chaque* projet. 

L’avantage est que votre code (`/app`) sera dans le repo *avec* les fichiers qui permettent à Docker de recréer son environnement d’exécution. Autrement dit, quelle que soit la machine qui devra exécuter votre code, tout fonctionnera comme sur la machine de développement. 

Attention que les données de la base de données ne sont pas archivées. Pensez régulièrement à `dumper` vos tables et leurs données dans un fichier sql que vous ajouterez aussi à l’archive.

### Démarrez les serveurs

Pour démarrer les serveurs, vous devez entrer dans le dossier créé par la commande précédente `cd path-to/dev-environnement` et instancier les conteneurs listés et configurés dans le fichier `docker-compose.yml` : `docker-compose up`.

La première fois que vous exécutez cette commande risque de prendre du temps. Les fois suivantes réutiliseront les ressources téléchargées la première fois.

Une fois la commande exécutée, vous avez accès au serveur via `http://localhost`. Vous devriez alors voir la page d’information de PHP dans votre navigateur.

J’ai ajouté l’application Web de gestion de base de données [Adminer](https://www.adminer.org) à la stack d’origine. Elle est accessible à l’adresse `http://localhost:8080`. Notez qu’elle utilise son propre moteur PHP, en version 7.4. C’est sans importance pour vos développements qui peuvent se faire pour la dernière version de PHP disponible.

L’accès à MariaDB par une application externe (TablePlus, Terminal, PHPStorm, etc.) est aussi possible via l’hôte `127.0.0.1`, port 3306, comme d’habitude.

Depuis vos scripts PHP, l’accès se fait via l’hôte `mariadb`. Un exemple de connection est disponible dans `/app`.

Le mot de passe est dans le fichier d’environnement, le login est `root`.

Pour couper les conteneurs, dans la fenêtre du terminal où les conteneurs sont actifs (c’est la fenêtre où sont affichés les `myapp-*`), pressez `CTRL+C`, comme pour interrompre la plupart des commandes exécutables dans le terminal. Vous pouvez à présent quitter Docker. 

Des méthodes plus brutales sont possibles, telles que quitter le terminal, éteindre les conteneurs depuis Docker ou encore simplement quitter Docker. 

### Un terminal ? 

Le terminal de votre machine exécute normalement des commandes dans le contexte de votre machine physique. 

Avec Docker, c’est différent : nous allons devoir exécuter des commandes dans un autre contexte, celui des conteneurs. 

Voyez les conteneurs comme des espèces de micro-machines virtuelles. Ils contiennent chacun un micro Linux et savent donc interpréter des commandes.

Sur ma machine physique, je n’ai pas installé PHP, COMPOSER, NGINX, ou MARIADB. Si je tape une commande qui commence par PHP dans mon terminal, celui-ci me dit qu’il ne connaît pas cette commande. 

Pourtant avec mon terminal je sais quand même exécuter des commandes PHP dans le conteneur PHP.  

Mon terminal me sert à envoyer des commandes à un sous-terminal qui lui, comprend les commandes. Du coup, il faut, avant de taper la commande voulue, demander à Docker de l’éxécuter pour nous dans le conteneur de notre choix.

`docker exec -ti {CONTAINER_NAME} [COMMAND]` 

Vous devez remplacer {CONTAINER_NAME} par le nom du conteneur avec lequel vous voulez interagir.

* `{APP_NAME}-php`
* `{APP_NAME}-nginx`
* `{APP_NAME}-mariadb`

Par exemple si votre application est nommée myapp dans le fichier d’environnement, tapez `docker exec -it myapp-php composer require nesbot/carbon` pour installer Carbon à l’aide de *composer* ou `docker exec -it myapp-php npm init` pour construire vos déclarations de dépendances *front*. Vous le voyez, c’est le conteneur PHP qui vous sera le plus utile. C’est dans celui-ci que j’ai ajouté composer, node, npm, git, afin que ce soit plus simple pour vous.

Vous savez donc envoyer une commande vers l’interpréteur de commandes du conteneur, mais vous restez néanmoins dans le terminal de votre machine. Pour taper plusieurs commandes d’affilée, vous devez refaire toute la commande précédente. Vous pouvez naturellement créer des alias pour vous simplifier la vie ou alors, si vous souhaitez garder le terminal du conteneur ouvert, vous pouvez utiliser `docker exec -it {CONTAINER_NAME} /bin/sh`. 

Ceci peut s‘avérer très utile pour des processus qui doivent rester ouverts, comme par exemple `npx mix watch`.

Par exemple, 
```
docker exec -it myapp-mariadb /bin/sh
mariadb --user=root --password=rootpass db_name
```
ou

```
docker exec -it myapp-php /bin/sh
npx mix watch
```

```
