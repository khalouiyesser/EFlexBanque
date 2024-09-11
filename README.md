Run the command "composer install" if you have an error just run "composer update"
this command to run the Mailing server " php bin/console messenger:consume async "
To add an admin you should run this commands 
    * composer require orm-fixtures --dev 
    * symfony console make:fixture  
        ( create a code to add an admin)
    * run this command to add the admin "  php bin/console doctrine:fixtures:load --group=user --append "
