# Learn how to build REST API's, using Slim, Eloquent, SQLite & Postman 
### PHP Team Treehouse TechDegree project #7

- [The goal of this project](#the-goal-of-this-project)
- [Installation instructions](#installation-instructions)
- [Tech used](#tech-used)
- [Folder & file structure](#folder--file-structure)

## The goal of this project
#### To build a REST API to manage a To Do list, with tasks and subtasks 

- Step 1: install the slim 3.0 skeleton & postman 
- Step 2: build the required DB structure
- Step 3: build the required Models (task & subtask), in my case using Eloquent ORM
- Step 4: build the required routes
- Step 5: add custom ApiExceptions
- Step 6: test using postman 

The following endpoints should be available: 
* [GET] /api/v1/todos
* [POST] /api/v1/todos
* [GET] /api/v1/todos/{task_id}
* [PUT] /api/v1/todos/{task_id}
* [DELETE] /api/v1/todos/{task_id}
* [GET] /api/v1/todos/{task_id}/subtasks
* [POST] /api/v1/todos/{task_id}/subtasks
* [GET] /api/v1/todos/{task_id}/subtasks/{subtask_id}
* [PUT] /api/v1/todos/{task_id}/subtasks/{subtask_id}
* [DELETE] /api/v1/todos/{task_id}/subtasks/{subtask_id}

## Installation instructions
- Git clone https://github.com/FrederikHulleman/project7-todo-api.git 
- Place the repo in a web server of your choice (XAMPP for Windows, MAMP for Mac)
- After downloading this project, make sure you run the following composer command in the project folder to install the right packages on dev:
    ```bash
    composer install
    ```
- Then make sure composer autoloads all classes automatically by running this command:
    ```bash
    composer dump-autoload -o
    ```
- Open postman and test the endpoints.
- And you're ready to go!  

## Tech used
#### In this project the following main concepts, languages, frameworks, packages and other technologies are applied:
PHP | OOP | MVC | REST API | Postman | SQLite | Slim 3.0 framework | Eloquent ORM

## Folder & file structure
#### The most important folders & files within this project:

    .             
    ├── public                      # contains htaccess and index.php files  
    └── src                         # contains the database file & the primary Slim files  
        ├── Exception               # contains the ApiException class 
        ├── Model                   # contains the Task & Subtasks class files, based on Eloquent ORM  
        └── root                    # contains the Database and main slim files: dependencies, middleware, routes & settings
 