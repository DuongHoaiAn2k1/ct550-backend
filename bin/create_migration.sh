#!/bin/bash

if [ "$#" -ne 2 ]; then
    echo "Usage: $0 <migration_name> <directory_name>"
    exit 1
fi

MIGRATION_NAME=$1
DIRECTORY_NAME=$2
BASE_DIR="database/migrations"
MIGRATION_DIR="$BASE_DIR/$DIRECTORY_NAME"

# Tạo thư mục nếu chưa tồn tại
if [ ! -d "$MIGRATION_DIR" ]; then
    mkdir -p "$MIGRATION_DIR"
fi

# Tạo migration bằng lệnh artisan
php artisan make:migration $MIGRATION_NAME

# Kiểm tra nếu lệnh tạo migration thành công
if [ $? -ne 0 ]; then
    echo "Error creating migration"
    exit 1
fi

# Tìm file migration mới được tạo
NEW_MIGRATION_FILE=$(ls -t $BASE_DIR | grep $MIGRATION_NAME | head -n 1)

if [ -z "$NEW_MIGRATION_FILE" ]; then
    echo "Migration file not found"
    exit 1
fi

# Di chuyển file migration vào thư mục con
mv "$BASE_DIR/$NEW_MIGRATION_FILE" "$MIGRATION_DIR/"

echo "Migration created and moved to $MIGRATION_DIR/$NEW_MIGRATION_FILE"
