## Server test

1. After downloading the code, first run the command to install all the components:

```bash
$ composer install
```

2. Then setup the database credentials into the .env file for the DATABASE_URL variable

3. Once everything is correct, you can use the commands listed by "bin/console"

4. The commands related to this task are:
4.1. To setup the database:

```bash
$ bin/console doctrine:database:create
```

4.2. To create the table:

```bash
bin/console doctrine:schema:create
```

4.3. To create the default items:

```bash
bin/console doctrine:migrations:execute --up 'DoctrineMigrations\Version20210513225310'
```

4.4. To clear the database:

```bash
bin/console doctrine:migrations:execute --down 'DoctrineMigrations\Version20210513225310'
```

4.5. For better usage, is recommended to use 8000 for the server api and 8001 for the client
```bash
bin/console server:start 8000|8001
```