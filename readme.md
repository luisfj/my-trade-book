<h1 align="center">
  My Trade Book
</h1>

<p align="center">
<a href="https://www.linkedin.com/in/luis-fernando-johann/" target="_blank">
 <img src="https://img.shields.io/badge/-LinkedIn-02569B?logo=linkedin&logoColor=white&style=fot-the-badge" alt="luis-fernando-johann" />
</a>
</p>

Projeto para gestão de trades em forex, permite importar os arquivos de trades realizados exportados por MT4 e MT5.

## Tecnologias
 
- [PHP 7.2](https://www.php.net/releases/7_2_0.php)
- [Laravel](https://laravel.com/)
- [VueJS](https://br.vuejs.org/)
- [vue-google-charts](https://vuejsprojects.com/vue-google-charts)
- [MariaDB](https://mariadb.org/)
- [Adminer](https://hub.docker.com/_/adminer/)

## Como Executar

### Usando Docker
- Clonar repositório git
- Executar o docker-compose da raiz do projeto:
```
docker-compose up -d
```
- A Primeira vez que subir o projeto executar os migrations e seeds:
```
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

A aplicação poderá ser acessada em [localhost:8080](http://localhost:8080).
O adminer poderá ser acessado em [localhost:8081](http://localhost:8081)

## Credenciais de acesso

Para acessar a aplicação como Administrador, utilizar as seguintes credenciais:
```
email: admin@admin.com
senha: admin
```