````markdown
# Configuraciones realizadas y cómo verificarlas (Actividad 3)

> Proyecto: **Laravel – Doctor Appointment (4B)**
> Commit solicitado: `chore: configure MySQL connection, timezone, language and profile photo`

Este README documenta exactamente **qué cambié** y **cómo verificarlo**, con base en los archivos que me compartiste: `.env`, `config/app.php` y `config/jetstream.php`.

---

## 0) Resumen de cambios

* **Idioma por defecto**: Español (`APP_LOCALE=es`).
* **Zona horaria**: `America/Merida` en `config/app.php`.
* **Conexión MySQL**: configurada en `.env` (`appointment_db_4b`, usuario `laravel`).
* **Sesiones / Cache / Queue**: mediante **base de datos** (tablas necesarias).
* **Fotos de perfil (Jetstream)**: activadas y guardadas en **disco `public`** (con `storage:link`).
* **Nuevo layout `AdminLayout`** creado en `resources/views/layouts/`.
* **Integración de Flowbite** para sidebar y navbar.
* **Separación de código en includes** usando `@include`.
* **Uso de contenido dinámico con `{{$slot}}`** en dashboard.blade.php.
* **Documentación actualizada** en este README.

---

## 1) `.env` – Configuración aplicada

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=appointment_db_4b
DB_USERNAME=laravel
DB_PASSWORD='aqui va el password'   # Importante: comillas si contiene # o caracteres especiales
````

### Cómo verificar `.env`

* **Locale / Timezone reflejados por la app** (ver abajo en sección 2).
* **Conexión a MySQL**:

  ```bash
  php artisan migrate:status   # Si conecta, lista migraciones
  ```
* **Drivers database (sesión/cola/cache)**: deben existir las **tablas** respectivas tras ejecutar los comandos de la sección 4.

> Nota: si cambias `.env`, recuerda limpiar/recachear:
> `php artisan config:clear && php artisan cache:clear && php artisan config:cache`

---

## 2) `config/app.php` – Idioma y zona horaria

Ajustes aplicados:

```php
'timezone' => 'America/Merida',
'locale' => env('APP_LOCALE', 'en'),
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
```

### Cómo verificar idioma/zonahoraria

```bash
php artisan tinker <<'PHP'
echo config('app.locale')."\n";        // esperado: es
echo config('app.timezone')."\n";      // esperado: America/Merida
PHP
```

También puedes revisar `php artisan about` y confirmar **Application locale** y **Timezone**.

---

## 3) `config/jetstream.php` – Fotos de perfil

Ajustes relevantes recibidos:

```php
'features' => [
    Features::profilePhotos(),
    Features::accountDeletion(),
],

'profile_photo_disk' => 'public',
```

### Pasos

```bash
php artisan storage:link
```

Luego subir foto de perfil en `/user/profile` y verificar que se guarde en `storage/app/public/profile-photos/`.

---

## 4) Sesiones, Cache y Queue en base de datos

```bash
php artisan session:table
php artisan queue:table
php artisan queue:failed-table
php artisan cache:table
php artisan migrate
```

Confirmar que existan las tablas `sessions`, `jobs`, `failed_jobs`, `cache`.

---

## 5) Creación de `AdminLayout`

1. Comando Artisan:

   ```bash
   php artisan make:component AdminLayout
   ```
2. El layout fue movido a `resources/views/layouts/admin.blade.php` y se incluyó el uso de:

   ```blade
   @include('layouts.navbar')
   @include('layouts.sidebar')

   <main class="p-4 sm:ml-64">
       {{ $slot }}
   </main>
   ```
3. Cualquier vista que use `<x-admin-layout>` mostrará su contenido en el slot.

---

## 6) Integración de Flowbite

1. Instalación:

   ```bash
   npm install flowbite --save
   ```

2. Configuración en `tailwind.config.js`:

   ```js
   module.exports = {
     content: [
       "./resources/**/*.blade.php",
       "./resources/**/*.js",
       "./node_modules/flowbite/**/*.js"
     ],
     plugins: [
       require('flowbite/plugin')
     ],
   }
   ```

3. Agregado script en layout:

   ```blade
   <script src="{{ asset('node_modules/flowbite/dist/flowbite.min.js') }}"></script>
   ```

4. Sidebar y navbar fueron implementados con componentes de Flowbite.
   Botones funcionales: desplegar menú de usuario y redirigir a la información de usuario.

---

## 7) Separación de código en includes

* `resources/views/layouts/navbar.blade.php`
* `resources/views/layouts/sidebar.blade.php`

Ambos se incluyen con `@include`.

---

## 8) Uso de `{{$slot}}` en dashboard

`resources/views/layouts/admin.blade.php` contiene:

```blade
<main>
    {{ $slot }}
</main>
```

Así el contenido de `dashboard.blade.php` es dinámico.

---

## 9) Verificación final

```bash
php artisan config:clear && php artisan cache:clear && php artisan view:clear
php artisan serve
```

* Revisar que el dashboard muestre contenido.
* Sidebar/ Navbar visibles y operativos.
* Acceso correcto a perfil de usuario.

---

## 10) Verificación integral rápida

```bash
php artisan about
php artisan migrate:status
npm run build
```

Comprobar que:

* BD conecta.
* Storage enlazado.
* Navbar y sidebar cargan correctamente.
* Slots muestran contenido dinámico.