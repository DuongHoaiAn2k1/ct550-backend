#!/bin/bash
BACKUP_DIR="/var/www/html/ct550/backups"
MYSQL_CONTAINER="ct550_mysql_1"
DATABASE="ct550_db"
MYSQL_ROOT_PASSWORD="root"


# Kiểm tra nếu database không tồn tại
if ! docker exec $MYSQL_CONTAINER mysql -uroot -p"$MYSQL_ROOT_PASSWORD" -e "USE $DATABASE"; then
    echo "Database $DATABASE không tồn tại. Khôi phục từ bản sao lưu."
    
    # Lấy bản sao lưu gần nhất
    LATEST_BACKUP=$(ls -1t "$BACKUP_DIR" | grep "${DATABASE}_" | head -n 1)
    
    # Khôi phục nếu tìm thấy bản sao lưu
    if [ -n "$LATEST_BACKUP" ]; then
        docker exec -i $MYSQL_CONTAINER mysql -uroot -p"$MYSQL_ROOT_PASSWORD" $DATABASE < "$BACKUP_DIR/$LATEST_BACKUP"
        echo "Đã khôi phục database từ bản sao lưu $LATEST_BACKUP"
    else
        echo "Không tìm thấy bản sao lưu để khôi phục!"
    fi
else
    echo "Database $DATABASE tồn tại. Không cần khôi phục."
fi
