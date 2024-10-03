#!/bin/bash

# Kiểm tra xem có truyền tham số tên model hay không
if [ -z "$1" ]; then
  echo "Vui lòng cung cấp tên của model."
  echo "Cú pháp: ./make_model.sh ModelName"
  exit 1
fi

# Tạo model với tên được cung cấp
php artisan make:model $1

# echo "Model '$1' đã được tạo thành công."
