# Financial API

This is a simple financial API built with **PHP 8.1** and **Laravel Lumen** that handles deposits, withdrawals, and transfers.

## Architecture

  * **Domain Layer:** Contains the core business logic, including the `Account` entity and the `AccountRepositoryInterface`.
  * **Application Layer:** Includes the **Use Cases** (`DepositUseCase`, `WithdrawUseCase`, etc.) that orchestrate actions and the `AccountService` that acts as a facade.
  * **Infrastructure Layer:** Handles technical concerns such as routing, HTTP requests, and data persistence in a JSON file via the `FileAccountRepository`.

## Getting Started

Follow these steps to set up and run the project locally.

### Prerequisites

  * **Docker:** Make sure you have Docker installed and running on your system.

### Running the Application

1.  Clone the repository to your local machine.

2.  Navigate to the project's root directory.

3.  Run the following command to build and start the Docker containers (PHP and Nginx):

    ```bash
    docker-compose up -d --build
    ```

4.  Install the project dependencies inside the PHP container. This command will install the Laravel Lumen framework and all other required packages.

    ```bash
    docker-compose exec app composer install
    ```

5.  The API should now be running. You can test it by accessing `http://localhost:8080` in your browser or with an API client like Postman.

## API Endpoints

The API includes the following endpoints to handle financial operations:

  * **`POST /reset`**
    Resets the state of the application by clearing all account data.

      - **Response:** `HTTP 200 OK` with an empty body (`"OK"`).

  * **`GET /balance?account_id={ID}`**
    Retrieves the current balance for a specific account.

      - **Response:**
          - If the account exists: `HTTP 200 OK` with the account balance as a JSON number.
          - If the account does not exist: `HTTP 404 NOT FOUND` with the response body `0`.

  * **`POST /event`**
    Handles deposits, withdrawals, and transfers. The request body must be a JSON object containing the event type and details.

      - **Response:**
          - If successful: `HTTP 201 CREATED` with the updated account information in the response body.
          - If the origin account does not exist or has insufficient funds: `HTTP 404 NOT FOUND` with the response body `0`.