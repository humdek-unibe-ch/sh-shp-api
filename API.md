# Base 
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

# Data

## Get External Data

Get the data for the current user for the selected external table.

**URL**: `/api/data/get_external/{table_name}`

**Method**: GET

**Parameters**:
- `{table_name}` (string, required): The name of the external table.

**Response**:
The response will contain the data for the current user and the selected external table.

**Example**:
```json
{
  "response": {
    // Data for the current user and external table
  }
}

## Get All External Data

Get the data for all users for the selected external table.

**URL**: `/api/data/get_external_all/{table_name}`

**Method**: GET

**Parameters**:
- `{table_name}` (string, required): The name of the external table.

**Response**:
The response will contain the data for all users for the selected external table.

**Example**:
```json
{
  "response": {
    // Data for all users and external table
  }
}


## Get Internal Data

Get the data for the current user for the selected internal table.

**URL**: `/api/data/get_internal/{table_name}`

**Method**: GET

**Parameters**:
- `{table_name}` (string, required): The name of the internal table.

**Response**:
The response will contain the data for the current user and the selected internal table.

**Example**:
```json
{
  "response": {
    // Data for the current user and internal table
  }
}

## Get All Internal Data

Get the data for all users for the selected internal table.

**URL**: `/api/data/get_internal_all/{table_name}`

**Method**: GET

**Parameters**:
- `{table_name}` (string, required): The name of the internal table.

**Response**:
The response will contain the data for all users for the selected internal table.

**Example**:
```json
{
  "response": {
    // Data for all users and internal table
  }
}


