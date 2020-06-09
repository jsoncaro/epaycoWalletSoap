# epaycoWalletSoap
Epayco Wallet Soap Server

Repostorio prueba ePayco

### Prerrequisitos

```
MySql
PHP > 7.2.x
Apache
Composer
```

### Preparar entorno de desarrollo

Clonar Repo

```
git clone https://github.com/jsoncaro/epaycoWalletSoap.git
```

```
cd /epaycoWalletSoap/
```

Intalar dependencias

```
composer install
```

En el archivo .env

1. Cofigurar base de datos MySql

```
DATABASE_URL=mysql://usuario:contraseña@127.0.0.1:3306/nombredelabasededatos
```
2. Crear base de datos
```
php bin/console doctrine:database:create
```

3. Cofigurar envio de eMails
```
###> symfony/swiftmailer-bundle ###
# For Gmail as a transport, use: "gmail://username:password@localhost"
# For a generic SMTP server, use: "smtp://localhost:25?encryption=&auth_mode="
# Delivery is disabled by default via "null://localhost"
#MAILER_URL=null://localhost
MAILER_URL=gmail://usuario@gmail.com:contraeña@localhost
###< symfony/swiftmailer-bundle ###
```
5. Correr migraciones en la base de datos

php bin/console doctrine:migrations:migrate

### Testing con PostMan Collection

```
https://www.getpostman.com/collections/42cac8783bf7e79635e4
```