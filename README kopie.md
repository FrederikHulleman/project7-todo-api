###PHP Techdegree Project 7 - Todo ApiException

####This API is versioned, all routes should be prefixed with **/api/v1**

####You should have the following routes:
* [GET] /api/v1/todos
* [POST] /api/v1/todos
* [GET] /api/v1/todos/{id}
* [PUT] /api/v1/todos/{id}
* [DELETE] /api/v1/todos/{id}

####When the app first starts it will attempt to fetch all Todos in the system.  Handle the request and return all the Todos.
* Route [GET] /api/v1/todos

####When a Todo is **created** and the save link is clicked, it will make a request to the server.  Handle the request by creating a Todo and setting the proper status code.
* Route [POST] /api/v1/todos

####When a previously saved Todo is **updated** and the save link is clicked, it will make a request to the server. Handle the request by updating the existing Todo.
* Route [PUT] /api/v1/todos/{id}

####When a previously saved Todo is **deleted** and the save link is clicked, it will make a request to the server.  Handle the deletion and return a "message" that the resource has been deleted along with the proper status code.
* [DELETE] /api/v1/todos/{id}
