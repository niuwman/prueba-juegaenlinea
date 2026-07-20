# Baile con la Muerte - Sistema de Agenda de Citas

Aplicación web para agendar citas con temática oscura, desarrollada con **Laravel 10.50.2** (Backend API) y **Nuxt 4.5.0** (Frontend).

## Requisitos Previos

- **PHP** >= 8.1
- **Composer**
- **PostgreSQL** >= 15
- **Node.js** >= 18 (recomendado para Nuxt 4)
- **npm** >= 9 

## 🚀 Instalación

### Backend (Laravel)

# Clonar repositorio
git clone https://github.com/niuwman/prueba-juegaenlinea.git
cd prueba-juegaenlinea/backend

# Instalar dependencias PHP
composer install

# Copiar archivo de entorno
cp .env.example .env

# Configurar base de datos en .env
# Editar las siguientes variables:
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=baile_con_la_muerte
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones
php artisan migrate

# Iniciar servidor backend
php artisan serve

### Frontend (Nuxt)

# Navegar al directorio frontend
cd ../frontend

# Instalar dependencias
npm install

# Configurar variables de entorno
# Crear archivo .env (opcional, ya viene con valores por defecto)
echo "NUXT_PUBLIC_API_BASE=http://localhost:8000/api" > .env

# Iniciar servidor de desarrollo
npm run dev
