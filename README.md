<p align="center"><a href="https://github.com/nkoporec/formsy" target="_blank"><img src="https://i.ibb.co/Q8BbYZX/default.png" width="400"></a></p>

## About Formsy

Formsy is a web application for processing form submissions. Formsy takes the pain out of developing form back-end service and takes care of everything.

## Techonologies
 - Laravel + Livewire
 - Redis
 - Livewire
 - Alpine.js
 - Tailwind
 
## Developing

You need to have a working LAMP stack + Redis. The most straightforward way is to use [DDEV](https://github.com/drud/ddev).

Steps for setting up the project using DDEV:
 - Install DDEV
 - Go to project
 - Run `ddev start`
 
 In order to actually process incoming submissions you will need to start Horizon + Queue worker. The app is using 3 different queues, so here are the steps/commands to run after you successfully set up DDEV:
  - Start Horizon (Horizon is available at [Horizon URL](https://horizon.formsy.ddev.site))
  
    ```
    ddev exec php artisan horizon
    ```
    
  - Start submission queue (Used to proccess incoming submissions)
  
    ```
    ddev exec php artisan queue:listen --queue=submission
    ```
    
  - Start handler queue (Used to proccess handlers)
  -
    ```
    ddev exec php artisan queue:listen --queue=handler
    ```

  - Start handler-execute queue (Used to proccess handler execution stuff, such as Sendgrid / Gmail email sending)
    ```
    ddev exec php artisan queue:listen --queue=handler-execute
    ```


  - Start default queue (Used to proccess app stuff, such as sending user registration emails ....)
    ```
    ddev exec php artisan queue:listen --queue=default
    ```


When devoloping locally you can start all queues at once:
    ```
    ddev exec php artisan queue:listen --queue=submission,handler,handler-execute,default
    ```

The worker will process queues as listed in production environment all queues should be run separately for performance reasons.

## App Settings/Info

Each registered user has a unique app id, which must always be passed as part of a submission. There are also several other hidden submission input which alters the behavior.
Here is the list of all hidden inputs:
### Required
 - `_formsy_app-id`
    - User app key, found in /settings
    
 - `_formsy_form-id`
    - Id/name of a form, if the form doesn't exist, it will be automatically created
 
### Optional
 - `_formsy_redirect-url`
    - If passed, this URL will be used to redirect the user back
    
 - Honeypot field
    - This is only required if the form has enabled the honeypot protection, the field name is defined by user in /view/form/{} -> Options tab.

### Additional service

There are some additional services used.
 - [StopForumSpam](https://stopforumspam.com)
    - Used to detect spam submissions. This service only validates spam emails, so it's only usable for email fields.When the handler is invoked it loops through submission data and finds all email fields/data.
