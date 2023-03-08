    1.  После того как создали новый сервер, на котором ничего еще нет кроме debian запускаем make site

Для устранения ошибки после установки докера:
```
docker: Error response from daemon: failed to create shim task: OCI runtime create failed: runc create failed: unable to start container process: error during container init: unable to apply apparmor profile: apparmor failed to apply profile: write /proc/self/attr/apparmor/exec: no such file or directory: unknown.
```
Добавлена установка apparmor. https://github.com/docker/for-linux/issues/1199

provisioning/roles/docker/tasks/main.yml
секция  name: Install dependencies


    2. Запускаем make update для обновления dependencies в хост системе 

    3. Запускаем make authorize дял создания пользователя deploy
