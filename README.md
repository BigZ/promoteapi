# Promote API

An example API to demonstrate the use of [https://github.com/wizardstechnologies/rest-api-bundle](wizards/rest-api-bundle).

It also tries to be an efficient example of how to test, develop, deploy and manage resources for a REST API.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a8fe54a5-2b61-47b7-a8d4-c6f29b3709ab/big.png)](https://insight.sensiolabs.com/projects/a8fe54a5-2b61-47b7-a8d4-c6f29b3709ab)
[![Build Status](https://travis-ci.org/BigZ/promoteapi.svg?branch=master)](https://travis-ci.org/BigZ/promoteapi)

## Local Development
To develop and run the project, you might want to run it locally.

### Requirements
- `docker-compose`
- `make`

### Configuration
Add `127.0.0.1 promoteapi.test` to you hosts file.

Copy the `.env.dist` file to `.env` and replace the default values with your own.

### Run

Start the docker containers with `make dev`.

Prepare the project for usage - ie prepare database schema, load fixtures, install dependencies - with `make d c=prepare`

## Usage
Acces the API on `http://promoteapi.test:4200/`

### Log in
Post a form containing the username and the password to get the token.
Use the obtained token to identify with the header `X-AUTH-TOKEN`

`POST /token`

Content-type: Form-date

Fields `username` `password`

## Resources

### List

#### Pagination
A list will give you a paginated ressource.

`/artists?page=2&limit=2`

#### Filtering
You can filter out results on specific fields.

`/artists?filter[id]=5&filteroperator[id]=>`

Available operators are `>`, `<`, `>=`, `<=`, `=`, `!=`
Default operator is =

#### Sorting
You can sort the result by any property

`/artists?sort=-id`

Will sort the results by descending id

### Entity
#### Creating new entities
`POST /artists`

`{
     "artist": {
         "name": "eminem",
         "slug": "eminem",
         "bio": "rapper from detroit",
         "labels": [1, 2]
     }
 }`

 will return

`{
   "id": 2,
   "name": "eminem",
   "slug": "eminem",
   "bio": "rapper from detroit",
   "_links": {
     "self": "/artists/2",
     "labels": [
       "/labels/1",
       "/labels/2"
     ]
   }
 }`

PUT & PATCH works the same way

#### Including

By default, relations are not embeded. You can change this behaviour by specifiying wich embedeed entities you need.

`/artists/1?include=gigs,labels`


##### Uploading

Upload a file as a binary in the content, specifying it's content-type in the header (image/* for instance)

```
 PUT /artists/2/picture HTTP/1.1
 Host: localhost:8000
 X-AUTH-TOKEN: 123
 Content-Type: image/*
 Cache-Control: no-cache
 Postman-Token: 0d0ab553-44e4-5710-f582-831123a6ed2f

 *image binary content*
```

### Testing

#### Locally
Phpspec will test that your code is doing what it should.
Behat will run end to end tests on pretty much all key features
Dredd performs a documentation test to check whether the implementation is done accordingly to the documentation.

Run the tests
`make test`

#### CI
```make build```
will create reports in the /build folder for you to follow the evolution of you app. best run on a Continuous Intergration environment.


### Documentation

Document your code with the appropriate @SWG & @JMS annotations so nelmioApiDocBundle can generate the proper doc.
ìf you want to automatically update your OpenAPI documentation according to your annotations & code, run this command:
`php bin/console dump:api-doc`

you can then have the documentation available on 
`http://127.0.0.1:8042/`
by starting a swagger-ui docker container with this command
`docker run -p 8042:8080 -e SWAGGER_JSON=/def/swagger.json -v $PWD:/def swaggerapi/swagger-ui`
