#!/bin/bash
TIMESTAMP=$(date +"%Y%m%d%H%M")
BACKUP_DIR="/var/www/html/ct550/backups"
MYSQL_CONTAINER="ct550_mysql_1"
DATABASE="ct550_db"
MYSQL_ROOT_PASSWORD="root"


# Tạo thư mục lưu bản sao lưu nếu chưa có
mkdir -p "$BACKUP_DIR"

# Thực hiện sao lưu
docker exec $MYSQL_CONTAINER mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" $DATABASE > "$BACKUP_DIR/${DATABASE}_$TIMESTAMP.sql"

# Giữ lại tối đa 5 bản sao lưu gần nhất, xóa các bản cũ hơn
ls -1t "$BACKUP_DIR" | grep "${DATABASE}_" | sed -e '1,5d' | xargs -I {} rm -f "$BACKUP_DIR/{}"
