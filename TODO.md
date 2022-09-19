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

## Sécurité 
  + Droits sur es répertoires dans app
  + Sortir le rep gpx de la www root et ecrire un traitement de transfert.
  + 
## Features

 + Passer sous OSM + Leaflet
 + Dockeriser le tout
   + Front
   + back php
   + database
   + Persistance BDD 
 + Affichage de la date en français sur le viewer
 + passer en https
 - Refresh auto sur la visualisation d'une trace
   - récupérer dernier point et faire une selection différentielle dans la bd
   - gestion du cache gpx ? comment faire ?
   - UI : Bouton on/off dés/activer "suivi temps réel"

### Navigation

  url rewriting pour 
  - http://127.0.0.1/track/ => action=track 
  - http://127.0.0.1/view/ => action=view
  - http://127.0.0.1/api/gpx/[trackname]  => action=gpx&runnerid=trackname


## Prospective 
  + viewer : charger une trace par défaut : la dernière ?
  + Dark theme pour l'écran "track"
  - Afficher plusieurs traces GPX ?
    - UI : Switcher "cumul de trace On/Off"
  - Ajouter Authentification sur viewer (lecture)
  - Ajouter authentification sur tracker (admin)
    - Ajouter une table des users. 
    - préfixer automatiquement toutes les traces par le login user.
  
  - Se passer de base de données ? => fichiers GPX
  + Comptage des points gps sur tracker
  - ~~Caching de l'envoie des points gps~~
  + supprimer les tracks
  - sécuriser la suppression côté UI et Serveur (contrôle des droits)
  - Header "no-cache" pour le php
  - Refactoring : umbrella.js sur le viewer
  - paramètre trackname vs runnerid 
