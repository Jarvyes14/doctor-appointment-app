
<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# doctor-appointment-app

## Configuración inicial en Windows con XAMPP y Laravel

### 1. Verificar instalación de Laravel
- Abrir la terminal dentro del proyecto y ejecutar:
  ```bash
  php artisan serve
  ```
- Si funciona, acceder a: http://127.0.0.1:8000

### 2. Configuración de idioma (Español)
- Instalar paquete de traducciones:
  ```bash
  composer require laravel-lang/common --dev
  ```
- Publicar los archivos de localización:
  ```bash
  php artisan lang:add es
  ```
- Modificar el archivo `config/app.php` y cambiar:
  ```php
  'locale' => 'es',
  ```

### 3. Configuración de zona horaria
En el archivo `config/app.php` cambiar:
```php
'timezone' => 'America/Merida',
```

### 4. Migraciones de base de datos
Para ejecutar migraciones:
```bash
php artisan migrate
```
Si quieres comprobar que se realizaron:
- Revisar la tabla `migrations` en tu base de datos creada en XAMPP (MySQL).
- Verificar que aparecen registros con el nombre de cada migración aplicada.

---

# Explicación de los cambios realizados

Este documento explica **cómo se construyó el layout administrativo** con Laravel, Flowbite y Tailwind, paso a paso.

---

## 1. Creación del layout principal `admin.blade.php`

Se generó un layout base donde se integran el **Navbar** y el **Sidebar**.  
Este layout incluye:

- Importación de **Tailwind CSS** y **Flowbite JS**.
- Un `slot` que permite inyectar contenido dinámico desde otras vistas.
- Fondo gris aplicado con `bg-gray-200` para mantener consistencia visual.
- Inclusión de los archivos `navbar.blade.php` y `sidebar.blade.php` desde `layouts/includes/admin/`.

```blade
@include('layouts.includes.admin.sidebar')
@include('layouts.includes.admin.navbar')

<div class="p-4 sm:ml-64">
    <div class="mt-10">
        {{$slot}}
    </div>
</div>
```

---

## 2. Creación del **Navbar** (`navbar.blade.php`)

- Navbar fijo en la parte superior.
- Se añadió un botón **hamburguesa** que permite abrir/cerrar el sidebar en dispositivos pequeños.
- Se añadió un **menú de usuario con dropdown**, mostrando foto, nombre y correo.

Ejemplo de despliegue de usuario:
```blade
<button type="button" class="flex text-sm bg-gray-800 rounded-full" data-dropdown-toggle="dropdown-user">
    <img class="w-8 h-8 rounded-full" src="http://javierbarcelosantos.dev/pfp.jpg" alt="user photo">
</button>
```

---

## 3. Creación del **Sidebar** (`sidebar.blade.php`)

- Sidebar lateral izquierdo fijo.
- Cada enlace muestra:
    - Ícono.
    - Nombre del enlace.


---

## 4. Dashboard dinámico con `slot`

Para mostrar contenido dinámico dentro del layout, se creó un componente llamado `x-admin-layout`.  
En `dashboard.blade.php` se usa de la siguiente manera:

```blade
<x-admin-layout>
    Hola desde admin
</x-admin-layout>
```

De esta forma, el texto o vistas que se coloquen dentro del componente se renderizan dentro del `slot` en `admin.blade.php`.

---

## 5. Diseño visual consistente

- Se aplicó `bg-gray-200` al `body` para dar un fondo neutro.
- Uso de clases de **Tailwind CSS** para mantener responsividad y soporte de **modo oscuro**.
- Se integraron estilos de Flowbite para el menú, sidebar y dropdowns.

---

## 6. Integración con Flowbite

Finalmente, se agregó el script de Flowbite en el layout principal para que todos los componentes interactivos (como el menú de usuario y el sidebar) funcionen correctamente:

```html
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
```

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
