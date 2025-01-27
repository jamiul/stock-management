
# Product Catalog API with Stock Management

Product Catalog API with Stock Management, Notification System, and CI/CD Implementation

## Laravel Task:
(a) Build a REST API for a product catalog (CRUD) with minimal stock management and user authentication.

(b) Implement Caching for products to improve API performance. Integrate RabbitMQ for asynchronous processing to send email notifications when the product stock is below the minimum quantity (low stock).
## DevOps Task:
Use Docker to set up the application with MySQL and RabbitMQ. Implement a CI/CD workflow for deploying this stack to AWS or any other server. (Deployment to the server is optional, but implementing CI/CD is mandatory.)

## Nice to Have:
- Frontend visualization implemented using React.js
- Clean and maintainable code architecture
- Deployment to a production server


## Run Locally

Clone the project

```bash
git clone https://github.com/jamiul/stock-management.git
```

Go to the project directory

```bash
cd stock-management
```

## Docker Installation

Setup the project

```bash
./docker/setup.sh
```

Run the project

```bash
./docker/run.sh
```

Stop the project

```bash
./docker/stop.sh
```
## API Reference
Test using postman import 'Stock-Management.postman_collection' file then
hit the url.

#### Get all products

```http
GET /api/products
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `api_key` | `string` | **Required**. Your API key |

#### Get product

```http
GET /api/products/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of item to fetch |

#### Create a new product

```http
POST /api/products/
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | **Required**. name of item to store |
| `description`      | `string` | **Required**. description of item to store |
| `price`      | `integer` | **Required**. description of item to store |
| `quantity`      | `integer` | **Not Required**. description of item to store |

#### Update an existing product

```http
PUT /api/products/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | **Required**. name of item to store |
| `description`      | `string` | **Required**. description of item to store |
| `price`      | `integer` | **Required**. description of item to store |
| `quantity`      | `integer` | **Not Required**. description of item to store |

#### Delete an existing product

```http
DELETE /api/products/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `Id`      | `string` | **Required**. Id of item to store |
