
## Crypto simple project


This repository contains the source code for ordering crypto currencies and check price of some currencies.

## Requirements:

- **PHP >= 8.2**
- **Composer**
- **Mysql 8**
- **Redis**
## Technical Stack:
- **Laravel 11**
- **Docker**

## Installation:

#### 1. Clone this repository:

##### Clone the repo:
`git clone https://github.com/saeedmzr/crypto_assessment`

#### 2. Install dependencies:
`composer install`

#### 3. Navigate to the project directory:
`cd crypto_assessment`

#### 4. Generate the application key:
`php artisan key:generate`

#### 5. Create a .env file from .env.example and set your environment variables, including your database connection details.:

#### 6. Create a database and set it up on .env file.

#### 7. Run the database migrations:
`php artisan migrate`

#### 8. Run the database seeders to get our currencies and its rates:
`php artisan db:seed`

#### 9. Run the server:
`php artisan serve`

#### Note:
##### This project offers the flexibility of running in a Dockerized environment. To set up Docker, configure the required environment variables, such as db_port and ext, in your .env file. Then, simply execute the following command to start the development environment in the background: ######

`docker-compose up -d`
##### It launches 4 containers for you including : ######
1 . `app`
2 . `database`
3 . `web`
4 . `redis`

##### Now go to app container and simply run `php artisan migrate --seed`. Remember to be caution about env variables to set correctly.  ######

#### Note:
##### In this project i used redis for reading faster(specially in reading currencies prices). Also i love redis so much, so why not? ######


## Repository pattern

We have 3 entities for this project(`Order, Rate, Currency`). Each of them has it's own repository class that extends BaseRepository class implemented by BaseRepositoryInterface.
You can find it in :
`<root_directory>/app/repositories/Base`

## Updating Rate

On of the key features from this project is: it updates currency prices based on other currencies.
i used [FCSapi](https://fcsapi.com/) but you can add you own third party for getting prices of currency.
In order to do that you should implement `CryptoExchangerInterface` interface. Located: 
##### `<root_directory>/Serivces/Exchanger/CryptoExchangerInterface.php` #####
I used factory pattern for fetching data in this part so adding new third party won't be a hard thing to do.

#### UpdatingRatesCommand
There's a command called `UpdatingRatesCommand` located in `<root_directory>/app/Console/Commands/UpdatingRatesCommand.php`.
It updates rates by using `CryptoService` and if Crypto won't have price for given currencies it calls a method from `RateRepository` called `updateRatesBasedOnOtherRates`

#### `updateRatesBasedOnOtherRates` method from `RateRepository`
```<?php
        $rates = $this->model->query()
            ->where("updated_at", "<", now()->subMinutes($minutes))
            ->get();
        foreach ($rates as $rate) {
            $currency = $rate->currency;
            $sourceCurrency = $rate->sourceCurrency;
            $sourceRatesCurrencies = $this->availableCurrenciesThatHasRates($sourceCurrency);
            $targetRatesCurrencies = $this->availableCurrenciesThatHasRates($currency);
            $commonCurrencies = array_intersect($sourceRatesCurrencies, $targetRatesCurrencies);
            $commonCurrencyId = reset($commonCurrencies);
            $firstPrice = $this->getPriceForTwoCurrencies($currency->id, $commonCurrencyId);
            $secondPrice = $this->getPriceForTwoCurrencies($commonCurrencyId, $sourceCurrency->id);
            $finalRate = $firstPrice * $secondPrice;
            if ($finalRate != 0)
                $rate->update(['price' => $finalRate]);
        }
```
As you can see, It updates a rate if it wasn't updated for minimum 10 minutes(you can change it if you want).
For example : 
#### Imagine you have 3 currencies `BTC,IRR,USDT`. We have a rate BTC/USDT  and also we have rate for USDT/IRR.Now we want to buy some BTC based IRR. ###
#### First, this method find a currency between BTC and IRR that has rate between (In this example USDT). Then it calculate BTC/IRR rate by combine them togherer.

### Running Command:

#### Navigate to the project directory in your terminal and execute this command:
`php artisan schedule:work`
#### It runs `UpdatingRatesCommand` every minutes.

### Running Tests:

#### Navigate to the project directory in your terminal and execute this command:
`php artisan test`

#### This command will run all the tests and display the results, indicating which tests passed and highlighting any failures.

### Testing Database:

#### To run tests against a dedicated testing database without affecting your main database:

##### 1. Create a separate database for testing purposes.
##### 2. Configure the connection details for this testing database in your `.env.testing` file.
##### 3. Run the tests using the following command: `php artisan test --env=testing`

#### This ensures your tests are isolated and don't modify data in your production environment.

### Documentation:
#### Documentation for this project is generated using Swagger. To view the documentation, you can run the following command:
`php artisan l5-swagger:generate`

#### Once generated, you can access the documentation through the endpoint /api/documentation#/. This provides comprehensive details about the API endpoints and their functionalities.

### Using Makefiles for Streamlined Development (Optional)

This project leverages a Makefile to automate common Docker Compose tasks and streamline your development workflow. Here's a breakdown of the available commands:

#### Building Images:

`make build`: Rebuilds Docker images for all services defined in docker-compose.yml.

#### Starting/Stopping Services:

`make up`: Starts all services defined in docker-compose.yml.

`make down`: Stops all running services and removes containers.

`make start`: Starts all services in detached mode (background).

`make stop`: Stops all running services.

#### Managing Containers:

`make restart`: Restarts all services (equivalent to down followed by start).

`make remove`: Stops containers and removes them (use with caution).
#### Viewing Information:

`make logs`: Displays container logs in real-time.

`make stats`: Shows resource usage statistics for running containers.

`make ps`: Lists the status of all containers defined in docker-compose.yml.
#### Development Workflow:

`make update`: Updates the project code using git pull.

`make run-migrate`: Runs database migrations within the app container.

`make run-migrate-with-seed`: Runs database migrations and seeds the database with sample data (useful for initial setup).

`make run-test`: Executes application tests within the app container.
`make docs`: Generate documentation for project by `swagger`.

#### Interactive Shell Access:

`make run`: Starts the app container with a bash shell for interactive commands.
`make exec`: Executes a bash shell within the running app container (similar to docker-compose exec -it app bash).
#### Additional Notes:

Commands prefixed with @ suppress output from the underlying Docker Compose commands.
The CONTAINER variable in the Makefile is set to app by default, but you can modify it if your application's primary container has a different name.

### TODO:
#### I should add more unit tests to it. It contains 12 unit test for now that it's not great. All the functionality should be tested. Also it should have `feature` tests as well.



