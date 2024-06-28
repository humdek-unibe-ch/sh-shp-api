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

**URL**: `/api/base/hallo/{name}`

**Method**: GET

**Parameters**:
- `{name}` (string, required): The name for greetings.

**Response**:
```json
{
  "response": "Hallo {name}"
}
```

## Hallo Post

Greet someone with a "Hallo" message using a POST request.

**URL**: `/api/base/hallo/{name}`

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

## Get my data for a selected dataTable

Get the data for the current user for the selected external table.

**URL**: `/api/my/data/table/{table_name}`

**Method**: GET

**Parameters**:
- `{table_name}` (string, required): The name of the external table.
- `filter` (string, optional): It comes from the $_GET parameters. It is empty by default if it is not sent. Ex: `AND record_id = 200`

**Response**:
The response will contain the data for the current user and the selected external table.

**Example**:
```json
{
  "response": {
    // Data for the current user and external table
  }
}
```

## Get data for a selected dataTable for all users

Get the data for all users for the selected  dataTable. 

**URL**: `/api/data/table/{table_name}`

**Method**: GET

**Parameters**:
- `{table_name}` (string, required): The name of the external table.
- `filter` (string, optional): It comes from the $_GET parameters. It is empty by default if it is not sent. Ex: `AND record_id = 200`

**Response**:
The response will contain the data for all users for the selected external table.

**Example**:
```json
{
  "response": {
    // Data for all users and external table
  }
}
```

## Import Data

Import data using the POST protocol.

**URL**: `/api/data/table/{table_name}`

**Method**: POST

**Parameters**:
- `{table_name}` (string, required): The name of the external table.

**Request Body**:
 - `application/x-www-form-urlencoded` - When using application/x-www-form-urlencoded, the body should contain key-value pairs representing the 
 data to be inserted.
 - `application/json` - When using application/json, the body should contain a JSON array of objects representing the data to be inserted.

**Response**:
The response will contain the result of the import operation.

**Example**:
```json
{
  "response": {
    // Result of the import operation
  }
}
```

## Update DataTable row

Update a row of data into the external table using the POST protocol.

**URL**: `/api/data/table/{table_name}/{record_id}`

**Method**: PUT

**Parameters**:
- `{table_name}` (string, required): The name of the external table.
- `{record_id}` (integer, required): The the record id that we want to update.

**Request Body**:
The request body should contain the data object that needs to be imported. It should be an associative array where each key represents the name of the column and the corresponding value represents the data for that column.

**Response**:
The response will contain the result of the import operation.

**Example**:
```json
{
  "response": {
    // Result of the import operation
  }
}
```

## Create DataTable

Create a new dataTable using the POST protocol.

**URL**: `/api/data/table`

**Method**: POST

**Parameters**:
- `{name}` (string, required): The name of the table to be created.
- `displayName` (string, required): The display name of the table.

**Response**:
- If the table is created successfully, the response will contain the result of the table creation operation.
- If the table already exists, the response will indicate a conflict with an appropriate error message.

**Example**:
```json
{
  "response": {
    // Result of the table creation operation
  }
}
```


