
**회원가입**
**/api/register [POST]**
```

headers
Content-Type: multipart/form-data

request
{
	"name": string | required,
	"nickname": string | required,
	"password": string | required,
	"email": string | required,
	"phone": string | required,
	"gender": string
}
```


**로그인**
**/api/login [POST]**

```
headers
Content-Type: multipart/form-data

request
{
	"password": string | required,
	"email": string | required
}

response
{
	"token": string
}
```


**로그인한 유저 정보 조회**
**/api/user [GET]**

```
headers
Content-Type: multipart/form-data
Authorization: #{login 응답으로 받은 token}

response
{
	"name": string,
	"nickname": string,
	"email": string,
	"phone": string,
	"gender": string
}

```


**로그인한 유저 주문 정보 조회**
**/api/orders [GET]**

```
headers
Content-Type: multipart/form-data
Authorization: #{login 응답으로 받은 token}

response
[{
	"user_id": number,
	"order_no": number,
	"product_name": string,
	"created_at": datetime,
	"updated_at": datetime
}]
```

**전체 유저 정보 혹은 특정 유저 정보 조회 **
**/api/users [POST]**

```
headers
Content-Type: multipart/form-data

request
{
	"email": string,
	"name": string,
	"offset": number,
	"limit": number
}

response

email, name 으로 특정 유저를 조회했을 경우
{
	"name": string,
	"nickname": string,
	"email": string,
	"phone": string,
	"gender": string,
	"last_order": number // 가장 마지막 주문 번호
}

전체 유저를 조회했을 경우
[{
	"name": string,
	"nickname": string,
	"email": string,
	"phone": string,
	"gender": string,
	"last_order": number // 가장 마지막 주문 번호
}]

```