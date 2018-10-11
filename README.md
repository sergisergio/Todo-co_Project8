# Todo-co_Project8

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/707b82b0ab7a42448293a485fb5a79e2)](https://www.codacy.com/app/sergisergio/Todo-co_Project8?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=sergisergio/Todo-co_Project8&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/707b82b0ab7a42448293a485fb5a79e2)](https://www.codacy.com/app/sergisergio/Todo-co_Project8?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=sergisergio/Todo-co_Project8&amp;utm_campaign=Badge_Coverage)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sergisergio/Bilemo_Project7/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sergisergio/Bilemo_Project7/?branch=master)

[![Build Status](https://travis-ci.com/sergisergio/Todo-co_Project8.svg?branch=master)](https://travis-ci.com/sergisergio/Todo-co_Project8)

[![Maintainability](https://api.codeclimate.com/v1/badges/f87872297f3f24e95a3a/maintainability)](https://codeclimate.com/github/sergisergio/Todo-co_Project8/maintainability)

## Setup

**Download Composer dependencies**

Make sure you have [Composer installed](https://getcomposer.org/download/)
and then run:

```
composer install
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

Now check out at `http://localhost:8000`

**Contribute**

See [Contribute.md](https://github.com/sergisergio/Todo-co_Project8/blob/master/Contribute.md)

**Documentation**

Go to the 'documentation' directory to get the doc and also quality and performances reports.

**Any Questions ?**

Feel free to contact me !
