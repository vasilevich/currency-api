currencyconverter
==============

Another simple self hosted currency converter based only on    
free government data sources but any other data source can be added as-well.   

based on this library that  I written available: [Here](https://github.com/vasilevich/currencyconverter)

Installation
------------
First, you will need to install [Composer](http://getcomposer.org/) following the instructions on their site.
Secondly install git in your respective distro/windows
Then run:
```sh
git clone git@github.com:vasilevich/currencyconverter.git
cd currencyconverter
composer install
```
And then run it like any other php website...
or just
```sh
php -S localhost:9999 
```
Usage Example
-------------
#### GET convert specific currency ,   
from -  currency from which(USD, EUR, etc...)   
to - currency to which (same...)   
amount - amount to convert from,  default is 1   
source - can be israel, europe, denmark  ,  default is israel   
```http
http://localhost:9999/api/convert?from=usd&to=ils&amount=5&source=israel
```
result:
```json
{"from":"usd","to":"ils","source":"israel","amount":"5","result":17.475}
```
#### GET all possible sources at this time:   
```http
http://localhost:9999/api/sources
```
result:
```json
["israel","europe","denmark"]
```
#### GET all possible sources at this time:   
source - can be israel, europe, denmark  ,  default is israel   
```http
http://localhost:9999/api/rates?source=israel
```
result:   
```json
{"source":"https:\/\/www.boi.org.il\/currency.xml","base":"ILS","currencies":["{\"to\":\"USD\",\"rate\":3.495}","{\"to\":\"GBP\",\"rate\":4.4802}","{\"to\":\"JPY\",\"rate\":3.1962}","{\"to\":\"EUR\",\"rate\":3.8616}","{\"to\":\"AUD\",\"rate\":2.4037}","{\"to\":\"CAD\",\"rate\":2.6485}","{\"to\":\"DKK\",\"rate\":0.5168}","{\"to\":\"NOK\",\"rate\":0.383}","{\"to\":\"ZAR\",\"rate\":0.2358}","{\"to\":\"SEK\",\"rate\":0.362}","{\"to\":\"CHF\",\"rate\":3.5101}","{\"to\":\"JOD\",\"rate\":4.9291}","{\"to\":\"LBP\",\"rate\":0.0231}","{\"to\":\"EGP\",\"rate\":0.2165}"]}
```



