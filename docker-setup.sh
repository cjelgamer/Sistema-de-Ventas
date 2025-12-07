#!/bin/bash

# Script de ayuda para el proyecto Laravel en Docker
# Uso: ./docker-setup.sh [comando]

CONTAINER_ID="05e4f7bce358"

case "$1" in
    "bash")
        echo "🐳 Entrando al contenedor..."
        docker exec -it $CONTAINER_ID bash
        ;;
    
    "migrate")
        echo "🔄 Ejecutando migraciones..."
        docker exec -it $CONTAINER_ID php artisan migrate
        ;;
    
    "migrate:fresh")
        echo "⚠️  Recreando base de datos (CUIDADO: borra todos los datos)..."
        docker exec -it $CONTAINER_ID php artisan migrate:fresh
        ;;
    
    "seed")
        echo "🌱 Ejecutando seeders..."
        docker exec -it $CONTAINER_ID php artisan db:seed
        ;;
    
    "serve")
        echo "🚀 Iniciando servidor Laravel..."
        docker exec -it $CONTAINER_ID php artisan serve --host=0.0.0.0 --port=8000
        ;;
    
    "build")
        echo "📦 Compilando assets con Vite..."
        docker exec -it $CONTAINER_ID npm run build
        ;;
    
    "dev")
        echo "🔥 Iniciando Vite en modo desarrollo..."
        docker exec -it $CONTAINER_ID npm run dev -- --host
        ;;
    
    "logs")
        echo "📋 Mostrando logs de Laravel..."
        docker exec -it $CONTAINER_ID tail -f /app/storage/logs/laravel.log
        ;;
    
    "clear")
        echo "🧹 Limpiando cache..."
        docker exec -it $CONTAINER_ID php artisan config:clear
        docker exec -it $CONTAINER_ID php artisan cache:clear
        docker exec -it $CONTAINER_ID php artisan route:clear
        docker exec -it $CONTAINER_ID php artisan view:clear
        echo "✅ Cache limpiado"
        ;;
    
    "db:check")
        echo "🔍 Verificando conexión a la base de datos..."
        docker exec -it $CONTAINER_ID php artisan tinker --execute="DB::connection()->getPdo(); echo 'Conexión exitosa!';"
        ;;
    
    "status")
        echo "📊 Estado de las migraciones..."
        docker exec -it $CONTAINER_ID php artisan migrate:status
        ;;
    
    *)
        echo "🛠️  Script de ayuda para Laravel en Docker"
        echo ""
        echo "Comandos disponibles:"
        echo "  bash           - Entrar al contenedor"
        echo "  migrate        - Ejecutar migraciones"
        echo "  migrate:fresh  - Recrear base de datos (⚠️  borra datos)"
        echo "  seed           - Ejecutar seeders"
        echo "  serve          - Iniciar servidor Laravel"
        echo "  build          - Compilar assets (producción)"
        echo "  dev            - Iniciar Vite (desarrollo)"
        echo "  logs           - Ver logs de Laravel"
        echo "  clear          - Limpiar cache"
        echo "  db:check       - Verificar conexión DB"
        echo "  status         - Ver estado de migraciones"
        echo ""
        echo "Ejemplo: ./docker-setup.sh migrate"
        ;;
esac
