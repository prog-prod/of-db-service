#DATE=$(date +%F)
#DUMP_DIR="/var/www/dumps"
#DUMP_FILE="$DUMP_DIR/dump_$DATE.sql"
#cd /var/www/html
#
#DB_HOST='mysql'
#DB_USER=$(grep DB_USERNAME .env | cut -d '=' -f2)
#DB_PASS=$(grep DB_PASSWORD .env | cut -d '=' -f2)
#DB_NAME=$(grep DB_DATABASE .env | cut -d '=' -f2)
#
#echo "DB_HOST: $DB_HOST"
#echo "DB_USER: root"
#echo "DB_PASS: $DB_PASS"
#echo "DB_NAME: $DB_NAME"
#
#mkdir -p $DUMP_DIR
#
#mysqldump --default-character-set=utf8mb4 -h $DB_HOST -u $DB_USER --password=$DB_PASS $DB_NAME > $DUMP_FILE
#
#find $DUMP_DIR -name "dump_*.sql" -type f -mtime +2 -exec rm {} \;
