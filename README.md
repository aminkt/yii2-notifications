A complete notifications module for your Yii 2 powered application.

This module will install it's own table, and quickly allow you to:

* Trigger notifications to your users
* Toast these notifications on the screen using one of the supported UI libraries
* Manage a HTML list of notifications
  
![Growl notification](docs/main.png)


How to install this module:


Step1: First add flowing codes into project `composer.json`

```
"repositories": [
    {
        "type": "gitlab",
        "url": "https://gitlab.com/aminkt/yii2-notifications"
    }
],
```

Then add flowing line to require part of `composer.json` :
```
"aminkt/yii2-notifications": "*",
```

And after that run bellow command in your composer :
```
Composer update aminkt/yii2-notifications
```


More information:

A documentation is available [online](https://machour.idk.tn/yii/machour/yii2-notifications) and in the [docs](docs/Installation.md) folder of the repository.
You can also check this [live demo](https://machour.idk.tn/yii2-kitchen-sink/site/yii2-notifications).
