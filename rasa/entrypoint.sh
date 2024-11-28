#!/bin/sh

# Khởi động server Rasa
rasa run -m models --enable-api --cors "*" --debug &

# Khởi động server hành động tùy chỉnh
rasa run actions --actions actions
