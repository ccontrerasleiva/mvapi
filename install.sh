while getopts u: flag
do
    case "${flag}" in
        u) url=${OPTARG};;
    esac
done
if [ ! "$url" ]; then
  echo "falta parametro URL: ./install.sh -u URLSERVICIO"; exit 1
fi
echo "Actualizando repositorios APT";
sudo apt update

echo "Verificando GIT "
command -v git >/dev/null 2>&1 ||
{ echo >&2 "Git no esta instalado, instalando...";
 sudo apt install git
}

echo "Verificando MySQL"
command -v mysql >/dev/null 2>&1 ||
{ echo >&2 "MySQL no esta instalado, instalando...";
 sudo apt install mysql mysql-server
}

echo "Creando Base de datos del sistema "
sudo mysql <<-EOF
CREATE DATABASE multivende;
CREATE USER 'multivende'@'localhost' IDENTIFIED WITH mysql_native_password BY 'wrkxf7';
GRANT ALL PRIVILEGES ON multivende.* TO 'multivende'@'localhost';
EOF

echo "agrega dependencias de php7.3 "
sudo add-apt-repository ppa:ondrej/php
sudo apt install php7.3-fpm php7.3-mysql php7.3-common php7.3-mbstring php7.3-xml php7.3-zip php7.3-bcmath zip unzip php7.3-curl

echo "descargando codigo "
git clone  https://github.com/ccontrerasleiva/mvapi.git api

cd api

pwd=$(pwd)

echo "Creando archivo configuracion nginx"
sed -i "s|URL_SITE|$url|" nginx-configuration.conf
sed -i "s|FOLDER_LOC|$pwd|" nginx-configuration.conf
sed -i "s|URL_SITE|$url|" .env

echo "moviendo configuracion de nginx "
sudo cp nginx-configuration.conf /etc/nginx/sites-available/
sudo ln -s /etc/nginx/sites-available/nginx-configuration.conf /etc/nginx/sites-enabled
echo "Probando configuracion "
sudo nginx -t
echo "Reiniciando nginx "
sudo nginx -s reload
echo "Generando certificado SSL, siga las instrucciones... \n"
sudo certbot --nginx

echo "Generando migraciones de laravel "
php artisan migrate
echo "Generando Usuario "
php artisan db:seed

echo "Cambiando permisos directorio "
cd ..
sudo chmod -R 775 api
sudo chown -R  www-data:www-data api
echo "Terminado "
exit 1
