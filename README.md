# **Symfony Discount API**

## **Overview**

This project is a Symfony-based API that enables querying products with relevant discount information. The application is designed with a **hexagonal architecture** to ensure flexibility and separation of concerns. It adheres to **SOLID principles** and includes **unit tests** to ensure high code quality and maintainability.

The project leverages the **Symfony Docker** repository as its base, providing an optimized development environment using Docker. A pre-configured MySQL database is automatically populated upon setup, containing data for products, categories, and discounts.

---

## **Features**

- **Product Querying**: Retrieve products with optional filters for category and price.
- **Discount Calculation**: Automatically applies the best discount for each product, either by category or SKU.
- **Hexagonal Architecture**: Clear separation of business logic, adapters, and application layers.
- **SOLID Principles**: Ensures code is modular, maintainable, and extensible.
- **Unit Testing**: Validates critical components, ensuring the system works as expected.

---

## **Setup**

To set up and run the project, execute the `setup.sh` script located in the project root directory. This script initializes the Docker containers, sets up the Symfony environment, and populates the MySQL database.

```bash
./setup.sh
```

Once the setup is complete, the API will be available locally at:  
[https://localhost](https://localhost)

---

## **Usage**

### **Endpoint: GET /products**

Retrieve products with optional filters for category and maximum price.

#### **Query Parameters**

| Parameter       | Type     | Description                                           |
|------------------|----------|-------------------------------------------------------|
| `category`       | `string` | Filter by product category (e.g., `boots`).           |
| `priceLessThan`  | `number` | Filter products with a price lower than the specified value. |

#### **Example Request**

```bash
https://localhost/products?category=boots&priceLessThan=75000
```

#### **Example Response**

```json
[
  {
    "sku": "000003",
    "name": "Ashlington leather ankle boots",
    "category": "boots",
    "price": {
      "original": 71000,
      "final": 49700,
      "discount_percentage": "30%",
      "currency": "EUR"
    }
  }
]
```

---

## **Database Structure**

The MySQL database consists of three tables designed for optimal data storage and handling:

1. **Products**:
    - Stores product information (e.g., SKU, name, price, and category).
2. **Categories**:
    - Stores category information, including any associated discount percentages.
3. **Discounts**:
    - Stores SKU-specific discounts.

The database is populated automatically during the setup process.

---

## **Architecture**

The project follows a **hexagonal architecture**:
- **Domain Layer**: Core business logic (e.g., discount calculations).
- **Application Layer**: Handles use cases, such as retrieving products.
- **Infrastructure Layer**: Interfaces with external systems (e.g., database, HTTP).

This architecture ensures:
- Clear separation of concerns.
- Easy testability.
- Flexibility to change adapters (e.g., switch from MySQL to another database) without affecting core business logic.

---

## **Testing**

Unit tests are included to validate the critical components of the system. To run the tests, access the project container and run the following command:

```bash
./vendor/bin/phpunit
```

---

## **Troubleshooting**

### **1. MySQL Port Already in Use**

If the MySQL port (`3306`) is already in use on your machine, follow these steps to change it:

1. **Edit the Docker Compose File**:
   Open the `compose.yml` and modify the `ports` section for the MySQL service:
   ```yaml
   services:
     database:
       ports:
         - "{newPortHere}:3306"
   ```

   You will need to change ports also in
    ```yaml
   services:
     php:
       environment:
            DATABASE_URL: mysql://mcmoreno:mcmoreno@database:{newPortHere}/capitole_assessment?serverVersion=8&charset=utf8mb4

   ```
3. **Rebuild the Docker Containers**:
   Run the following commands to apply the changes:
   ```bash
   docker-compose down
   docker-compose up -d --build
   ```

4. **Update Environment Variables**:
   Modify the `DATABASE_URL` in your `.env` file to reflect the new port:
   ```dotenv
   DATABASE_URL=mysql://mcmoreno:mcmoreno@database:{newPortHere}/capitole_assessment?serverVersion=8&charset=utf8mb4
   ```

5. Restart the project:
   ```bash
   ./setup.sh
   ```

---

### **2. Security Alert When Accessing `https://localhost`**

When accessing `https://localhost`, modern browsers like Google Chrome might display a security warning because the SSL certificate is self-signed. To proceed:

1. **Google Chrome**:
   - Click on **"Advanced"** in the warning message.
   - Select **"Proceed to localhost (unsafe)"**.

2. **Firefox**:
   - Click on **"Advanced"**.
   - Click **"Accept the Risk and Continue"**.
     
## **Development Notes**

- **Base Repository**: The project is based on [Dunglas Symfony Docker](https://github.com/dunglas/symfony-docker).
- **Automated Setup**: The `setup.sh` script ensures quick and consistent setup.
- **Local HTTPS**: The API is served locally over HTTPS for secure communication.

