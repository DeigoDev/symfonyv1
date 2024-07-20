# Configuracion para sftp en el archivo .env
SFTP_HOST=your_sftp_host
SFTP_PORT=22
SFTP_USERNAME=your_username
SFTP_PASSWORD=your_password

# Configuracion para la conexion a la base de datos en el archivo .env
 DATABASE_URL="mysql://user:password:@ip:3306/schema?serverVersion=8.0.32&charset=utf8mb4"

# Comando para ejecturase en el archivo de los crontab
# Este comando se puede ejecutar en consola para poder simular la ejecucion de un crontab
php bin/console make:command app:save-users-data

# Comando para iniciar el proyecto de manera local con symfony
php -S localhost:8000 -t public

# Comando para ejecutar las migraciones
php bin/console doctrine:migrations:migrate

# extensiones necesarias de php para permitir la conexion con la base de datos
pdo_mysql

