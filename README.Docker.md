## Docker Setup ##

### Building and running your application

When you're ready, start your application by running:\
`docker compose up` inside the project directory.

Your application will be available at http://localhost:8000.

PhpMyAdmin will be available at http://localhost:8080. \
You can login with the credentials found in the `.env` or `compose.yaml` (`MYSQL_USER`, `MYSQL_PASSWORD` entries).

This will do the following:
- Build the Docker image for your application.
- Start the Docker containers for your application and services.<br /><br />

- <b>install all dependencies (composer & npm) & copy them to your computer (if you haven't already).</b>
- <b>create a new .env file (if there isn't one already).</b><br /><br />

- migrate the database.

<mark style="padding: 3px">-Please wait until all dependencies are copied to your computer  & all processes are run before ending the container or opening the webpage!-</mark>

When all processes are finished the logs will show:
- `[node] VITE vX.Y.Z ready in X ms.`
- `[app] laravel-migrate (exit status 0; expected)`

### Stopping your application ###

To stop your application, press:\
`Ctrl+C` inside the terminal (if you're running the application in the terminal), or run:\
`docker compose down` inside the project directory.

### Running Laravel commands ###

To run Laravel commands, you need to connect to the containers shell.\
To do this, first get the container ID by running:\
`docker ps` inside the terminal.

Take note of the container ID of the `app` container (e.g. `docker-test-app`).\
Then run:\
`docker exec -it [CONTAINERID] bash` inside the terminal.

From here you can run Laravel commands like:

### Setting up new Models ###

To create a new model, run:\
`php artisan make:model -mrc [MODELNAME]`.\
`mrc` creates a model, migration and (resource) controller.

Storage directories:
- Models - `app/Models/[MODELNAME].php`
- Migrations - `database/migrations/[TIMESTAMP]_create_[MODELNAME]_table.php`
- Controllers - `app/Http/Controllers/[MODELNAME]Controller.php`
- Views - `resources/views/[MODELNAME]/[VIEWNAME].blade.php`

### Routes ###

To show all routes, run:\
`php artisan route:list`.

### Link Storage to /public ###

To show all routes, run:\
`php artisan storage:link`.

## Update Dependencies ##

### Composer ###

To update composer dependencies, open the terminal in your `app` container and run:\
`composer update`.

### NPM ###
To update npm dependencies, open the terminal in your `node` container and run:\
`npm update`.

### Important ###
<mark style="padding: 3px">- Afterwards you should delete the docker images of `app` and / or `node`-</mark><br>
<mark style="padding: 3px">- When rebuilding the app image make sure to delete any existing symlinks first -</mark>
