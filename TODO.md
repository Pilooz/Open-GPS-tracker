# TODO

## Refactoring

  - Réorganiser la partie serveur : rep config, lib
    + contrôleur index.php, 
    + sécuriser la navigation
    - refactor de librairie.php, save.php => supprimer les `global`
    - mettre le viewer.php dans les libs
  - Réorganiser le front
    - viewer
    + servir le gps.htm via le contrôleur php
    - ressources js dans un rep js
    + dégager Jquery pour plus de légèreté
    - Dark theme
  - Réorganiser la partie database

## Features

 - Passer sous OSM
 - Se passer de base de données ? => fichiers GPX
 + Dockeriser le tout
   + Front
   + back php
   + database

### Navigation

  url rewriting pour 
  - http://127.0.0.1/track/ => action=track 
  - http://127.0.0.1/view/ => action=view