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

---

## 1) `.env` – Configuración aplicada

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=appointment_db_4b
DB_USERNAME=laravel
DB_PASSWORD='aqui va el password'   # Importante: comillas si contiene # o caracteres especiales o dejarlo vacio en caso de no tener nada
```

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
'locale' => env('APP_LOCALE', 'en'),     // Lee 'es' desde .env
'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),
```

### Cómo verificar idioma/zonahoraria

```bash
php artisan tinker <<'PHP'
echo config('app.locale')."
";        // esperado: es
echo config('app.timezone')."
";      // esperado: America/Merida
PHP
```

También puedes revisar `php artisan about` y confirmar **Application locale** y **Timezone**.

---

## 3) `config/jetstream.php` – Fotos de perfil

Ajustes relevantes recibidos:

```php
'features' => [
    // Features::termsAndPrivacyPolicy(),
    Features::profilePhotos(),    // Habilitado: fotos de perfil
    // Features::api(),
    // Features::teams(['invitations' => true]),
    Features::accountDeletion(),
],

'profile_photo_disk' => 'public', // Se guardan en el disco "public"
```

### Requisitos y pasos

1. Crear el **enlace simbólico** para servir archivos públicos:

```bash
php artisan storage:link
```

2. Asegurar que el **disco `public`** exista (por defecto mapea a `storage/app/public` → `public/storage`).
3. Ir a **Perfil de usuario** (ruta Jetstream `/user/profile`) y **subir tu foto** del **Tec de Software**.

### Cómo verificar (y obtener la captura para la entrega)

* Entra a `http://localhost:8000/user/profile`, sube la foto y verifica que se muestre.
* A nivel de archivos, debería existir algo como `storage/app/public/profile-photos/<id>.jpg` y accesible vía `public/storage/profile-photos/...`.
* **Toma la captura de pantalla** mostrando la foto de perfil visible en la UI (requisito de la actividad).

> Tip: Si no carga la imagen, revisa permisos de `storage/` y que el symlink `public/storage` exista (`ls -l public/ | grep storage`).

---

## 4) Sesiones, Cache y Queue en base de datos

Tu `.env` indica `SESSION_DRIVER=database`, `CACHE_STORE=database` y `QUEUE_CONNECTION=database`. Para que funcionen:

```bash
# Tablas necesarias
php artisan session:table
php artisan queue:table
php artisan queue:failed-table
php artisan cache:table
php artisan migrate
```

### Cómo verificar

* **Tablas** presentes: `sessions`, `jobs`, `failed_jobs`, `cache`.

  ```sql
  SHOW TABLES;
  SELECT COUNT(*) FROM sessions;   -- debe incrementarse al iniciar sesión
  ```
* Probar un **job** simple (opcional) o ejecutar `php artisan queue:work` en otra terminal y observar que no hay errores de conexión.

---

## 5) MySQL – Creación de BD/usuario y colación

Si aún no existen, estos comandos (ejemplo) crean la BD y el usuario de `.env`:

```sql
CREATE DATABASE IF NOT EXISTS appointment_db_4b
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'laravel'@'localhost' IDENTIFIED BY 'Laravel123!#';
GRANT ALL PRIVILEGES ON appointment_db_4b.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
```

### Cómo verificar conexión/codificación

```bash
php artisan migrate:status
```

```sql
SELECT @@version, @@global.time_zone, @@session.time_zone;  -- Opcional, para revisar TZ del servidor
SHOW VARIABLES LIKE 'character_set%';
SHOW VARIABLES LIKE 'collation%';         -- Debe mostrar utf8mb4/utf8mb4_unicode_ci
```

> Nota: La zona horaria de MySQL no afecta la TZ de Laravel; ésta ya está fijada en `config/app.php`. Aun así, puedes alinear MySQL si lo requieres.

---

## 6) Verificación integral (pasos rápidos)

```bash
# 1) Limpiar/recachear config (si tocaste .env)
php artisan config:clear && php artisan cache:clear && php artisan config:cache

# 2) Idioma y zona horaria
php artisan tinker <<'PHP'
echo config('app.locale')."
";        // es
echo config('app.timezone')."
";      // America/Merida
PHP

# 3) DB funcionando y migraciones
php artisan migrate:status

# 4) Tablas de sesión/cola/cache creadas
mysql -u laravel -p'Laravel123!#' -h 127.0.0.1 -e "USE appointment_db_4b; SHOW TABLES;"

# 5) Storage enlazado (para fotos de perfil)
[ -L public/storage ] && echo "storage ok" || echo "storage faltante"

# 6) Subir foto en /user/profile y tomar captura
# (Hecho desde la interfaz web)
```

---

## 7) Personalización visual básica (punto de la actividad)

* **Nombre de la app**: mostrado en la UI mediante `APP_NAME` (puedes ajustar a futuro).
* **Idioma español**: valida textos y mensajes de validación en español (si agregas archivos de `lang/es/` o paquetes de localización).
* **Foto de perfil**: visible en el perfil (Jetstream) tras `storage:link`.

> Si necesitas traducir mensajes específicos, añade/edita `lang/es/*.php` o instala paquetes de localización de Laravel.