### Interview Foundation Build

Using latest laravel version to send multiple emails asynchronously over API

### Setup

---
Clone the repo and follow below steps.
1. Run `composer install`
2. Copy `.env.example` to `.env` Example for linux users : `cp .env.example .env`
3. Set valid database credentials of env variables `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`
4. Run `php artisan key:generate` to generate application key
5. Run `php artisan api-token:generate` to generate API token and copy the display token or if you want to fetch API token you can run `php artisan api-token:generate --fetch` (Note: We will use these token in API as api_token={{your_api_token}} where we replace  `{{your_api_token}}` with generated token )
6. Run `php artisan migrate`
7. Run `php artisan horizon` (For local environment) and for production environment we need to install supervisor. For more information please refer to [Horizon](https://laravel.com/docs/8.x/horizon)

Thats it... Run the command `php artisan serve` and cheers

### API Documentation

---
  * Sample API JSON for (POST -  api/send?api_token={{your_api_token}})

```
{
  "emails": [
    {
      "email": "abc@example.com",
      "subject": "Test Subject",
      "body": "Test Body",
      "attachment": {
        "file_name": "xxxxx.jpg",
        "base64_file": "< Base64 Value >"
      }
    },
    {
      "email": "xyz@example.com",
      "subject": "Test Subject",
      "body": "Test Body",
      "attachment": [
        {
          "file_name": "xxxxx.jpg",
          "base64_file": "< Base64 Value >"
        },
        {
          "file_name": "xxxxx.png",
          "base64_file": "< Base64 Value >"
        }
      ]
    },
    {
      "email": "pqr@example.com",
      "subject": "Test Subject",
      "body": "Test Body"
    }
  ]
}
```

  * Sample API JSON for (GET -  api/list?api_token={{your_api_token}})


### Task overview

---

  * Build a simple API that supports the sending route
  * Build a Mail object which accepts email, subject, body, attachments
  * Make sure that emails are sent asynchronously, i.e. not blocking the send request
  * Test the route

### Used API routes:

---
  
  | Method  | Route |
  | ------------- | ------------- |
  | POST  | api/send  |
  | GET  | api/list  |

  * The token is used as a URI parameter in the request api_token={{your_api_token}}

### Task Goal

The primary goal is for the functionality to work as expected. The idea is to spend about 4 working hours on it, maximum 8 working hours.

### Task Minimum requirements

  * Have an endpoint as described above that accepts an array of emails, each of them having subject, body, base64 attachments (could be many or none) and the email address where is the email going to
  * Attachments, if provided, need to have base64 value and the name of the file
  * Build a mail using a standard Laravel functions for it and default email provider (the one that is easiest for you to setup)
  * Build a job to dispatch email, use the default Redis/Horizon setup
  * Write a unit test that makes sure that the job is dispatched correctly and also is not dispatched if there’s a validation error

### Task Bonus requirements
  
  * Have an endpoint api/list that lists all sent emails with email, subject, body and downloadable attachments
  * Unit test the above mentioned route (test for expected subject/body/attachment name)
