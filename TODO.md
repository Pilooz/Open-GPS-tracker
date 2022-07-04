# TODO

## Refactoring

  - Réorganiser la partie serveur : rep config, lib
    - contrôleur index.php, 
    - sécuriser la navigation
    - refactor de librairie.php, save.php => supprimer les `global`
    - mettre le viewer.php dans les libs
  - Réorganiser le front
    - viewer
    - servir le gps.htm via le contrôleur php
    - ressources js dans un rep js
    - dégager Jquery pour plus de légèreté
  - Réorganiser la partie database

## Features

 - Passer sous OSM
 - Se passer de base de données ? => fichiers json + Redis ?  
 + Dockeriser le tout
   + Front
   + back php
   + database