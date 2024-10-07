# BGL Corp : Subscription Billing App

This is a simple billing application for managing subscription-based products sold worldwide. The application supports both flat rate and variable rate subscriptions, with monthly and annual billing options.

## Requirements

- Products can have either flat rate or variable rate subscriptions.
- Subscriptions can be either monthly or annual.
- Each product has a subscription start date.
- Each product has a billing period start date and a billing period end date.
- Bills are generated based on the combination of billing period start/end date and subscription type (monthly or annual).
- Billing rules:
  - Billing period start date is set to the bill current date +1.
  - Billing period end date is adjusted to the same date of the next month or year based on the subscription type.

## Installation

To run this application, install the required packages using Composer. Follow [these instructions](https://getcomposer.org/) to install Composer if you haven’t already.

```bash
composer install
```

## Running the Application

Run all unit tests with mock data using the following command:

```bash
vendor/bin/phpunit tests/BillingServiceTest.php
```

## Assumptions
- There are two types of subscriptions: flat rate (monthly and annual) and variable rate (monthly and annual).
- There is no base rate for variable rate subscriptions.
- Monthly flat rate bill calculation: Total = Monthly Rate
- Annual flat rate bill calculation: Total = Monthly Rate * 12 
- Monthly variable rate calculation: Total = Rate * Usage
- Annual variable rate calculation: Total = Rate * Usage

## Design Decisions

- No Customer entity is introduced.
- Enums are used to manage subscription types.
- SOLID principles are implemented to ensure easier maintainability and extensibility.


## Libraries Used
- `php-faker` for generating mock data.
- `Carbon` for handling date and time operations.
- `phpunit` for unit testing the code.
