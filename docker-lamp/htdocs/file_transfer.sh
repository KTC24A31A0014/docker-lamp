#!/bin/sh

#秘密鍵を指定
SSH_KEY="/root/.ssh/test_rsa"

SOURCE_FILE="/var/log/apache2/php_error.log"

DESTINATION="storage:/root/"

/usr/bin/scp -i "$SSH_KEY" "$SOURCE_FILE" "$DESTINATION"
