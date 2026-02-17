<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

Configuración inicial del proyecto

Para iniciar el desarrollo se realizaron las configuraciones básicas en el proyecto de Laravel, garantizando que la aplicación esté adaptada al idioma español y a la zona horaria correcta. Además, se integró la base de datos MySQL y se añadió una personalización visual mínima que servirá como base de futuros avances.

En primer lugar, se verificó que el proyecto estuviera correctamente instalado y funcionando. Se comprobó la versión de PHP, Composer y Laravel, y se ejecutó php artisan serve para confirmar que la aplicación cargara sin problemas.

Posteriormente, se configuró el idioma en español. Para ello se modificó el archivo .env, estableciendo las variables APP_LOCALE=es y APP_FALLBACK_LOCALE=es. De igual manera, en config/app.php se ajustaron las claves 'locale' y 'fallback_locale' para que utilicen los valores en español. Esto asegura que todos los mensajes y validaciones por defecto se muestren en dicho idioma.
Como complemento, se creó la carpeta lang/es copiando el contenido de lang/en y traduciendo los archivos principales como auth.php, pagination.php, passwords.php y validation.php. De esta manera, los mensajes del sistema, paginación, validaciones y textos de autenticación se presentan completamente en español sin depender del fallback al inglés.

A continuación, se ajustó la zona horaria del proyecto. En el archivo config/app.php se cambió la clave 'timezone' a America/Merida, de modo que las fechas y horas que genere la aplicación se sincronicen correctamente con la ubicación geográfica de trabajo. Esta configuración se validó ejecutando php artisan tinker y revisando que config('app.timezone') devolviera el valor esperado.

En cuanto a la integración de MySQL, se configuró el archivo .env con los datos de conexión a la base de datos: DB_CONNECTION=mysql, DB_HOST=127.0.0.1, DB_PORT=3306, DB_DATABASE=appointment_db, DB_USERNAME=laravel y DB_PASSWORD=laravel123. Posteriormente se ejecutó php artisan migrate para crear las tablas predeterminadas en la base de datos, confirmando que la aplicación interactúa de manera correcta con MySQL.

Finalmente, se realizó una personalización visual básica para dar identidad al proyecto. Se agregó una imagen de perfil (foto del Tec de Software) en la carpeta public/images y se editó la vista principal resources/views/welcome.blade.php. Con esto se comprobó que Laravel sirve contenido estático y que la personalización puede evolucionar conforme avance el desarrollo.

Pasos Siguientes

Se incorporó un nuevo layout de administración que aprovecha la biblioteca Flowbite para mejorar la interfaz dividienlos en archivos para ser incluidos en la etiqueta del mismo nombre en admin.blade.php. Se instalaron los paquetes necesarios con npm, se configuró el archivo tailwind.config.js para incluir los componentes de Flowbite en app css y se importaron sus scripts en la compilación de Vite. Con esta base se creó un componente Blade principal que define la estructura del panel de administración, integrando una barra lateral (sidebar), una barra de navegación (navbar) y secciones dinámicas mediante slots e includes. Esta implementación permite que las vistas del dashboard y demás módulos se incrusten de forma flexible, asegurando una experiencia visual consistente y manteniendo el código organizado y escalable junto con un menu dropdown con nuestro perfil y linkeandolo con nuestra plantilla editada. Finalmente se le anexo un logotipo para el tipo de app a ser creado.
