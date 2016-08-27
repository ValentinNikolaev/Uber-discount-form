AfterUber form
============
This is form for Uber taxi to get estimate price for a trip with some discount. Discount can be set from configuration.

What's inside:
-------------
Slim, 
Twig (inc. slim twig-view), 
Monolog,
Bootstrap, 
Google Maps

Installation:
-------------
* composer install
* chmod 777 -R log/
* chmod 777 -R cache/ 

To do:
-----
Client side:
+ validation
+ fix css style for loader
+ separate jQuery code and native
+ change data organization. I think we should use data storage to save geolocation data, not hidden fields for lat, lng.
+ Minify custom css, js
+ replace Aray.forEach with native loop as it little faster
+ replace bootstrap with something lightweight. Custom markup, for ex. 
+ prevent form submit during ajax request.

Server side:
+ unit tests for Api methods and ajax-callbacks
+ validation and process methods to separate classes
+ shorttime cache for popular requests
+ requests statistic for analytics
+ fails statistic for analytics
+ admin exception notifications via email

UI:
+ google map layout to show the routes.
+ Current place picker for each input. 
+ Map picker for a location.
+ disable submit button while destination values are not valid
+ for results table - add currency support.
+ change table layout design


Thanks:
------
* [Steven Maguire uber-php](https://github.com/stevenmaguire/uber-php)
