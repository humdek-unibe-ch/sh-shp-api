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
- `filter` (string, optional): It comes from the $_GET parameters. It is empty by default if it is not sent.

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

## Get All Data for selected dataTable

Get the data for all users for the selected  dataTable.

**URL**: `/api/data/table/{table_name}`

**Method**: GET

**Parameters**:
- `{table_name}` (string, required): The name of the external table.
- `filter` (string, optional): It comes from the $_GET parameters. It is empty by default if it is not sent.

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


## Get Internal Data

Get the data for the current user for the selected internal table.

**URL**: `/api/data/get_internal/{table_name}`

**Method**: GET

**Parameters**:
- `{table_name}` (string, required): The name of the internal table.
- `filter` (string, optional): It comes from the $_GET parameters. It is empty by default if it is not sent.

**Response**:
The response will contain the data for the current user and the selected internal table.

**Example**:
```json
{
  "response": {
    // Data for the current user and internal table
  }
}
```

## Get All Internal Data

Get the data for all users for the selected internal table.

**URL**: `/api/data/get_internal_all/{table_name}`

**Method**: GET

**Parameters**:
- `{table_name}` (string, required): The name of the internal table.
- `filter` (string, optional): It comes from the $_GET parameters. It is empty by default if it is not sent.

**Response**:
The response will contain the data for all users for the selected internal table.

**Example**:
```json
{
  "response": {
    // Data for all users and internal table
  }
}
```

## Import External Data

Import external data using the POST protocol.

**URL**: `/api/data/import_external/{table_name}`

**Method**: POST

**Parameters**:
- `{table_name}` (string, required): The name of the external table.

**Request Body**:
The request body should contain the data object that needs to be imported. It should be an array where each entry represents a row to be imported.

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

## Import External Row

Import a row of data into the external table using the POST protocol.

**URL**: `/api/data/import_external_row/{table_name}`

**Method**: POST

**Parameters**:
- `{table_name}` (string, required): The name of the external table.

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

## Update External Row

Update a row of data into the external table using the POST protocol.

**URL**: `/api/data/update_external_row/{table_name}/{row_id}`

**Method**: PUT

**Parameters**:
- `{table_name}` (string, required): The name of the external table.
- `{row_id}` (integer, required): It comes from the $_GET parameters. The the row id that we want to update.

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

## Create External Table

Create a new external table using the POST protocol.

**URL**: `/api/data/create_external_table/{table_name}`

**Method**: POST

**Parameters**:
- `{table_name}` (string, required): The name of the external table that needs to be created.

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


