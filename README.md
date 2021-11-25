# Docker Compose LEMP Stack

This repository contains a little `docker-compose` configuration to start a `LEMP (Linux, Nginx, MariaDB, PHP, Adminer)` stack.

It is based on [https://github.com/stevenliebregt/docker-compose-lemp-stack](https://github.com/stevenliebregt/docker-compose-lemp-stack)

I use it only as a base environment to teach PHP in a School located in Liège, Belgium

## Details

Les versions les plus récentes disponibles au moment de la construction des conteneurs sont toujours utilisées.

Le moteur de base de données utilisé est MariaDB. Ceci est non seulement une évolution souhaitable par rapport à MySQL mais aussi une exigence de compatibilité pour les Mac M1+ à l’heure où j’écris. 

PHP est installé avec Composer.

Le dossier dans lequel vous devez coder votre application PHP est app/, situé à la racine de ce repo une fois cloné sur votre machine.

## Configuration

La configuration de Nginx est `config/nginx/`.

Pour plus de facilité, un fichier d’environnement, `.env` est utilisé. Il permet de définir certaines valeurs à réutiliser dans le docker-compose.yml, comme par exemple, le nom du projet et les informations de connexion à la DB.


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

J’ai ajouté l’application de gestion de base de données [Adminer](https://www.adminer.org) à la stack d’origine. Elle est disponible à l’adresse `http://localhost:8080`

L’accès à MariaDB par une application est possible sur l’adresse locale, port 3306, comme d’habitude.

## Un terminal ? 

Attention, le terminal de votre machine tape des commandes dans le contexte de votre machine. Sur ma machine par exemple, je n’ai pas installé PHP, COMPOSER, NGINX, ou MARIADB, et j’interagis pourtant via le terminal avec tous ces logiciels. Alors, comment ? 

Si vous avez besoin de taper des commandes dans le terminal d’un des conteneurs, dans votre terminal, tapez :

`docker exec -ti {CONTAINER_NAME} [COMMAND]` 

Vous devez remplacer {CONTAINER_NAME} par le nom du conteneur avec lequel vous voulez interagir.

* `{APP_NAME}-php`
* `{APP_NAME}-nginx`
* `{APP_NAME}-mariadb`

Par exemple si votre application est nommée myapp dans le fichier d’environnement, tapez `docker exec -it myapp-php composer require nesbot/carbon` pour installer Carbon à l’aide de composer.

La commande précédente permet d’envoyer une commande vers l’interpréteur de commandes du conteneur, mais vous restez dans votre terminal. Pour taper plusieurs commandes d’affilée, vous devez refaire toute la commande précédente ou alors, si vous souhaitez garder le terminal du conteneur ouvert, vous pouvez utiliser `docker exec -it {CONTAINER_NAME} /bin/sh`

Par exemple, 
```
docker exec -it myapp-mariadb /bin/sh
mariadb --user=root --password=rootpass db_name
```
