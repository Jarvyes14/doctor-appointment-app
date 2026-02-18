# 🔍 COMANDOS PARA VERIFICAR LA BASE DE DATOS

## 1. USAR TINKER (Consola interactiva de Laravel)

### Contar usuarios totales
```bash
php artisan tinker
```

Luego en la consola interactiva:
```php
>>> App\Models\User::count()
// Muestra: 5 (o el número de usuarios)

>>> App\Models\User::all()
// Muestra lista de todos los usuarios

>>> App\Models\User::where('email', 'usuario@ejemplo.com')->first()
// Busca un usuario específico
```

---

## 2. ACCEDER A LA BASE DE DATOS DIRECTAMENTE

### SQLite (que es lo que usas)
```bash
# Acceder a la BD
sqlite3 database/database.sqlite

# Dentro de sqlite3:
SELECT COUNT(*) FROM users;
SELECT * FROM users;
SELECT * FROM users WHERE id = 5;
.quit  # Para salir
```

### Ver todas las tablas
```bash
sqlite3 database/database.sqlite ".tables"
```

### Ver estructura de tabla users
```bash
sqlite3 database/database.sqlite ".schema users"
```

---

## 3. COMANDOS COMPLETOS LISTOS PARA COPIAR

### Contar usuarios
```bash
cd "C:\Users\javie\Desktop\Laravel\doctor-appointment-app" && sqlite3 database/database.sqlite "SELECT COUNT(*) as total_usuarios FROM users;"
```

### Ver todos los usuarios
```bash
cd "C:\Users\javie\Desktop\Laravel\doctor-appointment-app" && sqlite3 database/database.sqlite "SELECT id, name, email FROM users;"
```

### Ver usuarios con sus roles
```bash
cd "C:\Users\javie\Desktop\Laravel\doctor-appointment-app" && sqlite3 database/database.sqlite "SELECT u.id, u.name, u.email, r.name as rol FROM users u LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id LEFT JOIN roles r ON mhr.role_id = r.id;"
```

### Ver citas asociadas a un usuario
```bash
cd "C:\Users\javie\Desktop\Laravel\doctor-appointment-app" && sqlite3 database/database.sqlite "SELECT * FROM appointments WHERE patient_id = 5;"
```

### Ver pacientes
```bash
cd "C:\Users\javie\Desktop\Laravel\doctor-appointment-app" && sqlite3 database/database.sqlite "SELECT id, user_id, blood_type_id FROM patients;"
```

### Ver doctores
```bash
cd "C:\Users\javie\Desktop\Laravel\doctor-appointment-app" && sqlite3 database/database.sqlite "SELECT id, user_id FROM doctors;"
```

---

## 4. USAR LARAVEL TINKER (MÁS FÁCIL)

### Abrir Tinker
```bash
php artisan tinker
```

### Una vez dentro, ejecuta:

**Contar usuarios:**
```php
>>> User::count()
```

**Ver todos:**
```php
>>> User::all()
```

**Ver usuarios con roles:**
```php
>>> User::with('roles')->get()
```

**Ver un usuario específico:**
```php
>>> User::find(5)  // Busca usuario con ID 5
```

**Ver citas de un usuario:**
```php
>>> User::find(5)->appointments
```

**Ver si existe un usuario:**
```php
>>> User::where('email', 'admin@example.com')->exists()
// true o false
```

**Eliminar un usuario manualmente (para probar):**
```php
>>> $user = User::find(5)
>>> $user->appointments()->delete()
>>> $user->roles()->detach()
>>> $user->delete()
>>> User::count()  // Debe ser menor
```

---

## 5. PARA VERIFICAR SI TU ELIMINACIÓN FUNCIONA

### Paso 1: Contar usuarios ANTES
```bash
php artisan tinker
>>> User::count()
// Anota el número (ej: 5)
```

### Paso 2: Ir a /admin/users y eliminar un usuario
- Click en botón eliminar
- Confirmar
- Ver mensaje "¡Eliminado!"

### Paso 3: Contar usuarios DESPUÉS
```bash
>>> User::count()
// Debe ser 4 (uno menos)
```

### Paso 4: Verificar que desapareció
```bash
>>> User::find(5)  // null (no existe)
>>> User::all()    // No aparece en la lista
```

---

## 6. COMANDO TODO EN UNO PARA VERIFICAR

```bash
php artisan tinker --execute="echo 'Total usuarios: ' . App\Models\User::count();"
```

---

## RESUMEN RÁPIDO

**Lo más importante:**
```bash
# Abrir consola
php artisan tinker

# Contar usuarios
>>> User::count()

# Ver todos
>>> User::all()

# Salir
>>> exit()
```

---

**Elige la opción que prefieras según tu comodidad** 🔍

