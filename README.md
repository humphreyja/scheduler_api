scheduler_api
=============

A Symfony project created on November 9, 2017, 11:09 pm.


### To use

Generate data by loading Doctrine fixtures.  Then get a JWT token by passing credentials to `/api/auth/get_token`.

```
curl -X POST \
  http://localhost:8000/api/auth/get_token \
  -H 'content-type: application/json' \
  -d '{
	"_username":"johndoe",
	"_password":"password123"
}'
```

Now use that token in your requests as an `Authorization: Bearer {token}`.
