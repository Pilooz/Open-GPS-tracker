# TODO

## Refactoring

  + Réorganiser la partie serveur : rep config, lib
    + contrôleur index.php, 
    + sécuriser la navigation
    + refactor de librairie.php, save.php => supprimer les `global`
  + Réorganiser le front
    + viewer
    + servir le gps.htm via le contrôleur php
    + ressources js dans un rep js
    + dégager Jquery pour plus de légèreté
  + Réorganiser la partie database
  - Le Viewver doit être par défaut
  - tracker protégé par login/mdp

## Features

 + Passer sous OSM + Leaflet
 + Dockeriser le tout
   + Front
   + back php
   + database
   + Persistance BDD 
 + Affichage de la date en français sur le viewer
 ~ Changer d'image docker pour embarquer un certificat SSL 
  => La géoloc du navigateur ne fonctionne pas en http !!

### Navigation

  url rewriting pour 
  - http://127.0.0.1/track/ => action=track 
  - http://127.0.0.1/view/ => action=view
  - http://127.0.0.1/api/gpx/[trackname]  => action=gpx&runnerid=trackname



### Prospective 
  + Dark theme pour l'écran "track"
  - viewer : charger une trace par défaut : la dernière ?
  - Afficher plusieurs traces GPX ?
  - Ajouter Authentification sur viewer (lecture)
  - Ajouter authentification sur tracker (admin)
    - Ajouter une table des users. 
    - préfixer automatiquement toutes les traces par le login user.
  
  - Se passer de base de données ? => fichiers GPX
  + Comptage des points gps
  - ~~Caching de l'envoie des points gps~~
  + supprimer les tracks
  - sécuriser la suppression côté UI et Serveur (contrôle des droits)
  - Header "no-cache" pour le php
  - Refactoring : umbrella.js sur le viewer
  - paramètre trackname vs runnerid 
