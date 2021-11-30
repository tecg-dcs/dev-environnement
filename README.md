# Docker Compose LEMP Stack

This repository contains a little `docker-compose` configuration to start a `LEMP (Linux, Nginx, MariaDB, PHP, Adminer, Node)` stack.

It is based on [https://github.com/stevenliebregt/docker-compose-lemp-stack](https://github.com/stevenliebregt/docker-compose-lemp-stack)

I use it only as a base environment to teach PHP in a School located in Liège, Belgium

## Détails

Les versions les plus récentes disponibles au moment de la récupération des images sont toujours utilisées.

Le moteur de base de données utilisé est MariaDB.

PHP est installé avec Composer, mais aussi avec git, zip, nodejs et npm 🎉. Ces deux derniers outils sont surtout là pour le cas où vous auriez besoin d’utiliser une dépendance *front*, mais dans le cadre du cours, la probabilité est assez faible.

Le dossier dans lequel vous devez coder votre application PHP est `app/`, situé à la racine de ce repo une fois cloné sur votre machine.

## Configuration

**UTILISATEURS DE WINDOWS**
Si vous avez fait le choix regrettable de travailler avec une machine qui n’est pas équipée d’un système Unix/Linux, tout n’est pas perdu. Pour faire fonctionner Docker vous avez dû activer WSL2 et installer Ubuntu comme sous-système Linux sur votre Windows. Ouf. Les commandes qui suivent sont à taper dans le terminal d’Ubuntu. Pour rappel `cd /mnt/LETTRE_DE_DISQUE/Users/VOTRE_NOM/PATH_VERS_VOTRE_PROJET` vous placera dans le bon répertoire pour la suite des opérations.

Le fichier docker-compose utilise une variable Unix (PWD) qui n’est pas reconnue dans un interpréteur de commande WINDOWS. Pensez-y !


La configuration de Nginx est `config/nginx/`.

Pour plus de facilité, un fichier d’environnement, `.env` est utilisé. Il permet de définir certaines valeurs à réutiliser dans le `docker-compose.yml, comme par exemple, le nom du projet et les informations de connexion à la DB.


| Key | Description |
|-----|-------------|
|APP_NAME|Le nom à utiliser pour nommer les conteneurs.|
|MYSQL_ROOT_PASSWORD|Le mot de passe pour l’utilisateur `root` du SGBD.|

## Usage

Pour l’utiliser : 

##### Clonez ce dépôt.

`git clone https://github.com/hepl-pwcs/dev-environnement.git`.

##### Démarrez les serveurs.

Pour démarrer les serveurs, vous devez instancier les conteneurs listés et configurés dans le fichier `docker-compose.yml` : `docker-compose up`.

À ce moment, vous avez accès au serveur via `http://localhost`

J’ai ajouté l’application de gestion de base de données [Adminer](https://www.adminer.org) à la stack d’origine. Elle est disponible à l’adresse `http://localhost:8080`. Notez qu’elle utilise son propre moteur PHP, en version 7.4. C’est sans importance pour vos développements qui peuvent se faire pour la dernière version de PHP disponible.

L’accès à MariaDB par une application externe (TablePlus, Terminal, PHPStorm, etc.) est possible sur l’adresse locale, port 3306, comme d’habitude.

## Un terminal ? 

Attention, le terminal de votre machine tape des commandes dans le contexte de votre machine. Sur ma machine par exemple, je n’ai pas installé PHP, COMPOSER, NGINX, ou MARIADB, et j’interagis pourtant via le terminal avec tous ces logiciels. Alors, comment ? 

Si vous avez besoin de taper des commandes dans le terminal d’un des conteneurs, dans votre terminal, tapez :

`docker exec -ti {CONTAINER_NAME} [COMMAND]` 

Vous devez remplacer {CONTAINER_NAME} par le nom du conteneur avec lequel vous voulez interagir.

* `{APP_NAME}-php`
* `{APP_NAME}-nginx`
* `{APP_NAME}-mariadb`

Par exemple si votre application est nommée myapp dans le fichier d’environnement, tapez `docker exec -it myapp-php composer require nesbot/carbon` pour installer Carbon à l’aide de *composer*.

La commande précédente permet d’envoyer une commande vers l’interpréteur de commandes du conteneur, mais vous restez dans votre terminal. Pour taper plusieurs commandes d’affilée, vous devez refaire toute la commande précédente ou alors, si vous souhaitez garder le terminal du conteneur ouvert, vous pouvez utiliser `docker exec -it {CONTAINER_NAME} /bin/sh`

Par exemple, 
```
docker exec -it myapp-mariadb /bin/sh
mariadb --user=root --password=rootpass db_name
```
