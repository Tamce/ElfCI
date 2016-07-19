# CodeIgniter - RESTful expansion
__By ElfStack Dev Group__

---
## Summary
 This expansion for CodeIgniter is based on CodeIgniter v3.0.6.
 By using this expansion, you can create RESTful APP easily.
 Our target is fastest and easiest so we do not do so much extensions. We only provide you some basic methods which allows you to manage and treat different HTTP verbs quickly in your app.

## Expanded Router
### Common Performance
 Router now adds default http verb after methods name.
 It means if we got uri like this:
 `/controller/method/params(/params...)`
 We will call:
```
Class: Controller
Method: method\_{http\_verb}
```
 __BUT__
 if http_verb is `get` or `cli`(which means you run the app in command line),we won't add http\_verb after method name. It works as before.
### Expanded Route Rules
 We know that before we can use http verb in route rules like this:
```
$route['product/(:any)']['POST'] = 'product/index_post/$1';
```
 We keep this feature too but we expand a little.
1. If you have specific route rules with http verb, we will not add default http verb after method name.
2. HTTP verb allows common case `\*`.
3. Specific HTTP verb is prior to the common case.


 e.g.
 If you have route rules as follow
```
$route['product/(:any)']['POST'] = 'product/add/$1';
$route['product/(:any)']['*'] = 'product/index/$1';

$route['user/(:any)'] = 'user/index/$1';
$route['user/(:any)']['PUT'] = 'user/update/$1';
```
 you will got:

| http method | uri | which method to call |
|--|--|--|
| POST | /product/(:any) | Product::add($1) |
| ANY OTHER | /product/(:any) | Product::index($1) |
|--|--|--|
| PUT | /user/(:any) | User::update($1) |
| GET/CLI | /user/(:any) | User::index($1) |
| POST | /user/(:any) | User::index_post($1) |
| ... | /user/(:any) | User::index_{http-verb}($1) |

> We also check route rules in `/application/config/api.php` in order to manage your API together.

## Expanded Controller
 To enable RESTful Controller and use our method, just simply extends our class in your controller like this:
```
class Welcome extends REST_Controller {
```
 Also you can use raw controller.(Use `CI\_Controller` instead of `REST\_Controller`.
> Notice that: use raw controller will not change route rules, if you want to use the Router as before, just remove our router file`/appication/core/Router.php`.

 If you have enabled our you can use some of our methods.
### Geting Data
 To see the detail, check Reference below.
* You can use `$this->api->method` directly to get HTTP verb.
* You can use `$this->api->query();` to get query string. also you can specific index `$this->api->query('id');`
* You can use `$this->api->request();` to access data in the http body, also you can specific index `$this->api->request('name');`, this method gets data from `php://input`


### Sending Response
 We offer you method to response data. To see the detail, check Reference below.
 By using `$this->api->response` method you can send response easily, check reference below.

### Reference for $this->api
#### $this->api->method
 __var string__

 The value of this var is the lower case of HTTP verbs or `cli` in command line.
 such as `get`,`post`,`put`,`patch`,`delete`,`options`,`cli`
#### $this->api->query
 __mixed query([$key = null])__
 Returns query string.

 If you do not offer $key, return an array which contains all information of query string.
 If you offer $key, return the value of that.
#### $this->api->request
 __mixed request([$key = null])__
 Returns data in the HTTP body.

 This function get data from `php://input` and translate into array.
 If you do not offer $key, return an array which contains all information in the input stream.
 If you offer $key, return the value of that.
#### $this->api->response
__void response($data[, $code = 200[, $type = null]])
 Send response to client and exit.

 If $data is an array or an object, we use json_encode function to treat data and set `Content-Type` to `application/json`.
 Else if $type not offered, we set `Content-Type` to `text/plain`, you can edit the default type in `/application/core/REST\_Controller.php`
 $code sets header http code to return, default is 200.
