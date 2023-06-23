## Ping Pong

Ping pong function to check the API's availability.

**URL**: `/api/base/ping`

**Method**: GET

**Response**:
```json
{
  "response": "pong"
}
```

## Hallo

Greet someone with a "Hallo" message.

**URL**: `/api/base/hallo/{param1}`

**Method**: GET

**Parameters**:
- `{param1}` (string, required): The name for greetings.

**Response**:
```json
{
  "response": "Hallo {param1}"
}
```

## Hallo Post

Greet someone with a "Hallo" message using a POST request.

**URL**: `/api/base/hallo_post/{name}`

**Method**: POST

**Parameters**:
- `{name}` (string, required): The name for greetings.
- `my_name` (string, required in POST body): Your name for the response.

**Response**:
```json
{
  "response": "Hallo {name}. My name is: {my_name}"
}
```