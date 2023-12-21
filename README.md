# Cogitech task

A simple website presenting an example solution of the given task.

## Get started
1. Clone the repository
   ```
   git clone https://github.com/Zukashi/Cogitech.git
   ```

2. Install project dependencies with [`composer`](https://getcomposer.org/)
    * `$ composer install`

4. Configure `.env` file, setup `DATABASE_URL` variable with external database URI or fill the env variables and run ```docker-compose up```
## Usage
To start the server, from command line simply run:
```shell script
 symfony server:start
```
First generate the database schema
```shell script
 php bin/console doctrine:migrations:migrate
```
Next fetch posts and load to the local database using
```shell script
 php bin/console app:load-posts
```
Finally use a browser to check whether the website is running: 
```
http://localhost:8000
```
That's it! 

## Usage examples
To log in go to:
```
http://localhost:8000/login
```
To register a new user:
```
http://localhost:8000/register
```
To get the posts list:
```
http://localhost:8000/lista
```
To get the posts data run:
```
http://localhost:8000/posts
```
