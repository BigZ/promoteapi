# Promote API

An api to promote your events / artists

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a8fe54a5-2b61-47b7-a8d4-c6f29b3709ab/big.png)](https://insight.sensiolabs.com/projects/a8fe54a5-2b61-47b7-a8d4-c6f29b3709ab)

## Install and run

Add a .env file at the root of the project to configure you env-dependant variables

```
DATABASE_URL="postgres://user:password@127.0.0.1/dbname"
```

## Use

### Log in
Post a form containing the username and the password to get the token.
Use the obtained token to identify with the header `X-AUTH-TOKEN`

`POST /token`

Content-type: Form-date

Fields `username` `password`

## Resources

### List

#### Pagination
A list will give you a paginated ressource, HAL formatted.

`/artists?page=2&limite&sorting[id]=desc`

#### Filtering
You can filter out results on specific fields.

`/artists?filtervalue[id]=5&filteroperator[id]=>`

Available operators are `>`, `<`, `>=`, `<=`, `=`, `!=`


#### Sorting
You can sort the result by any property

`/artists?sorting[id]=asc`

### Entity
#### Creating new entities
`POST /artists`

`{
     "artist": {
         "name": "eminem",
         "slug": "eminem",
         "bio": "rapper from detroit"
     }
 }`

 will return

`{
  "status": "created",
  "resource_id": 42
}`


#### Embedding

By default, relations are not embeded. You can change this behaviour by specifiying wich embedeed entities you need.

`/artists/1?embed[]=gigs&embed[]=labels`


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

Dump the Swagger definition
`php bin/console swagger2:dump`

Test it with dredd !
`npm install`
`dredd`

### Documentation

Use swagger-ui :)
