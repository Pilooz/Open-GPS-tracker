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

## Features

 + Passer sous OSM + Leaflet
 + Dockeriser le tout
   + Front
   + back php
   + database

### Navigation

  url rewriting pour 
  - http://127.0.0.1/track/ => action=track 
  - http://127.0.0.1/view/ => action=view
  - http://127.0.0.1/api/gpx/[trackname]  => action=gpx&runnerid=trackname


## Prospective 
  - viewer : charger une trace par défaut : la dernière ?
  - Dark theme pour l'écran "track"
  - Ajouter Authentification sur viewer (lecture)
  - Ajouter authentification sur tracker (admin)
  - Se passer de base de données ? => fichiers GPX
