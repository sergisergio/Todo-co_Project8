# Todo-co_Project8

## How to contribute

## Setup
**Fork the repository**

Click on "fork" at top right of your screen.

![notification de déploiement en prévisualisation](https://user.oc-static.com/upload/2016/09/19/14742902701046_fork_project.png)

**Clone**

```
git clone https://github.com/sergisergio/Todo-co_Project8
```
**Go to the root's project**

```
cd Todo-co_Project8
```

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
```

Otherwise

```
composer update
```

**Setup the Database**

First check `parameters.yml` is setup for your computer. Then, create
the database & tables!

```
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
```

**Start server**

```
php bin/console server:run
```

##Do your modifications##

**Create a new branch**

```
git checkout -b my-new-feature
```

**Commit**

```
git commit -m "commit to add"
```

**Push on the new branch**

```
git push origin my-new-feature
```

**Pull Request**

Once you modifications have been sent, you must ask by doing a pull request.

Go to your Github Fork, on your new branch and click on "compare & pull request".

Then write a message to the project's owner: he'll validate or refuse your pull request (he could also ask some informations before accepting or not your pull request).

![notification de déploiement en prévisualisation](https://user.oc-static.com/upload/2016/09/19/14742929911757_PR.png)
