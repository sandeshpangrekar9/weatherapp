Weather Forcast Application:-
-----------------------------

In order to build our application, we have used PHP 8.2.6 version. Please enter below command in git bash to clone the git repo. To run the project on local, place the project root folder under xampp/htdocs (for Xampp) OR under wamp64/www (for WampServer) folder.

$ git clone https://github.com/sandeshpangrekar9/weatherapp-repo.git

After cloning git repo, we can access the application using 'http://localhost/weatherapp/' url from browser.
We can also test it out using PHP's built-in web server as below:
1. Open git bash, go to project root folder path.
2. Enter below command to run the project
   $ php -S localhost:8080 -t public
3. This will start the cli-server on port 8080 and we can access using 'http://localhost:8080' url from browser.

We can enter any location in provided textbox, select the location and get weather forcast for that location. We have option to select Current Weather, Next 24 Hours or Next 7 Days data to display.

Note:- As there is no database requirement in this task, so we haven't configured database.