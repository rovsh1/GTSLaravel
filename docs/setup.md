## Контейнеры

1. php
2. nginx
3. mariadb
4. composer - @todo для удобства запуска composer

### Add hosts
```
# MAC    sudo vim /private/etc/hosts
# LINUX  sudo vim /etc/hosts
# WIN    c:\windows\system32\drivers\etc\hosts

127.0.0.1	www.gts.local
127.0.0.1	admin.gts.local
127.0.0.1	api.gts.local
127.0.0.1	hotel.gts.local
```

### Дополнительные команды

1. `docker/bin/attach {container_name}` запускает командную строку для контейнера
2. `docker/bin/artisan` запускает artisan в php контейнере.
3. @todo разобраться - в контейнере php есть алиасы, понять что нужно, удалить ненужное
