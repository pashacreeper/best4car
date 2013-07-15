# STO-expert

## Setup

```
git clone git@github.com:pashacreeper/sto-expert.git
bin/setup # tested on OS X
```

## Usefull

```
bin/update # will pull, install composer deps and run migrations
bin/start # will bin/update and run local server
bin/start --no-update # will run local server
bin/check_cs fix # will ensure compliance with coding standards
```

## Деплой

Деплой на сервер

```
cap deploy
```

Деплой с миграцями
```
cap deploy:migrations
```
