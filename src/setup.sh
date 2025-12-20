mkdir -p storage/app/public/qr_codes \

# 必要な権限を設定
chmod -R 775 /var/www/storage/app/public/qr_codes

chown -R www-data:www-data /var/www/storage/app/public

echo "ディレクトリが正常に作成され、必要な権限が設定されました。"
