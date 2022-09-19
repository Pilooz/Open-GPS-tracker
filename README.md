Open GPS-tracker
========

Open GPS-tracker is a GPS-tracking-thing written in JavaScript and some PHP. It uses a MySQL database to save tracking data.

## Features

### Tracking app

### Viewer

## Installation

```bash
mkdir -p app/public/gpx
```

### Localhost ssl installation

```bash
mkdir -p docker/conf/certs
cd docker/conf/certs
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout nginx-selfsigned.key -out nginx-selfsigned.crt
```
Launch docker containers at the project's root :

```bash
docker-compose up -d
```

## getting started

### Tracking

### Viewing a track
