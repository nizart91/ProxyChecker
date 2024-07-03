## Запуск

    sudo docker compose up -d && \
        sudo docker exec -it proxychecker-app-1 composer update && \
        sudo docker exec -it proxychecker-queue-1 php artisan queue:restart
