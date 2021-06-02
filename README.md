# Data to Slide

Script crea copias de un template en DRIVE y reemplaza los placeholder por datos, retorna la URL del google SLIDE.

## Instalación

### Aplicación

- PHP 5.4.x

#### Instalación

El software uso como base el [manual](https://developers.google.com/slides/quickstart/php) de google.

#### Requerimientos

Ejecucion y permisos de:
- Google Slides API
- Google Drive API

#### Dependencias

Las dependencias son manejadas por composer, para ello se ejecuta:

```bash
composer require google/apiclient:^2.0
```

## Uso

- Se necesita unas credenciales que se obtienen desde la consola de Google > APIs & Services > Credentials > OAuth > Download, este archivo se guarda/renombra en la ruta: credentials/client_secret.json

- Para una primera ejecucion se necesita dar permisos de forma manual a la aplicacion local, para obtener el credentials/token.json y poder usar los APIs, esto se realiza mediante la consola (dentro de la carpeta del script):

```bash
php auth.php
```

- Se sigue la URL generada y se da los permisos necesarios para la aplicacion, luego se copia el texto de respuesta de google y esto genera el archivo json.

- Crear o copiar las plantillas base (carpeta documents) y publicarlas en Google Drive, luego usar la opcion de 'Save as Google Slides' desde el menu de Google Slides

- Las imagenes que se van a utilizar deben tener acceso publico

- Enviar el parametro POST de template, para la seleccion del template base