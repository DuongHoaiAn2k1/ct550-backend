#!/bin/bash
set -e

# Khởi chạy action server
exec python -m rasa_sdk --actions actions
