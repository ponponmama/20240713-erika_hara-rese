mkdir -p storage/app/public/qr_codes \

# 必要な権限を設定
chmod -R 775 /var/www/storage/app/public/qr_codes

chown -R www-data:www-data /var/www/storage/app/public

# SSL証明書のディレクトリ作成と証明書生成
SSL_DIR="../docker/nginx/ssl"
if [ ! -d "$SSL_DIR" ]; then
    mkdir -p "$SSL_DIR"
fi

# 証明書が存在しない場合のみ生成
if [ ! -f "$SSL_DIR/nginx.crt" ] || [ ! -f "$SSL_DIR/nginx.key" ]; then
    echo "SSL証明書を生成しています..."
    openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
        -keyout "$SSL_DIR/nginx.key" \
        -out "$SSL_DIR/nginx.crt" \
        -subj "/C=JP/ST=Tokyo/L=Tokyo/O=Development/CN=localhost"
    echo "SSL証明書の生成が完了しました。"
else
    echo "SSL証明書は既に存在します。"
fi

echo "ディレクトリが正常に作成され、必要な権限が設定されました。"
