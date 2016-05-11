# Promote API

An api to promote your events / artists

## Install and run

Add a .env file at the root of the project to configure you env-dependant variables

```
DATABASE_URL="postgres://ùser:password@127.0.0.1/dbname"
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

### Entity
#### Embedding

By default, relations are not embeded. You can change this behaviour by specifiying wich embedeed entities you need.

`/artists/1?embed[]=gigs&embed[]=labels`