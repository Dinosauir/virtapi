Tutorial for installation :
1. clone project
2. cd project root
3. Run command from below
```
    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```
4. cp .env.example .env
5. docker-compose up -d
6. ./vendor/bin/sail shell
7. php artisan migrate:fresh --seed

### Side notes
1. Project has tests and test coverage.
2. For CI\CD deploy I did Github Actions, in which I build project + I run tests ( I can edit to run phpcs PSR12 also ). Coverage and tests can be looked there.
3. As an alternative, in project_root there exists text/index.html, you can open that.

### Api documentation
1. open project_root
2. go to public/docs
3. there exists openapi.yaml which can be imported to openapi.

### Api documentation alternative
1. ./vendor/bin/sail shell
2. php artisan scribe:generate
3. access : localhost/docs


