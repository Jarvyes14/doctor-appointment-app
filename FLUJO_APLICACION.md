# 🏥 Doctor Appointment App - Flujo de la Aplicación

## 📋 Flujo de Inicio

### 1. **Primera Visita (Sin Autenticación)**
```
Abres: http://localhost:8000/
   ↓
Redirige automáticamente a: /login
   ↓
Ves el formulario de login de Jetstream
```

### 2. **Credenciales de Prueba**

#### Admin
- Email: `admin@example.com`
- Contraseña: `admin`

#### Doctor
- Email: `doctor@example.com`
- Contraseña: `doctor`

#### Paciente
- Email: `javierbarcelosantos@example.com`
- Contraseña: `12345678`

---

## 🗺️ Rutas Después de Autenticarse

### **Para Administrador**
```
Login → /dashboard 
   ↓
[Rol: Administrador]
   ↓
Panel con opciones de admin
   ├─ /admin/dashboard ← Dashboard Admin (panel completo)
   ├─ /admin/users ← Gestionar usuarios
   ├─ /admin/roles ← Gestionar roles
   └─ /admin/patients ← Gestionar pacientes
```

### **Para Doctor**
```
Login → /dashboard
   ↓
[Rol: Doctor]
   ↓
Panel con opciones de doctor
   ├─ /appointments ← Ver sus citas
   ├─ /doctors/{id} ← Ver su perfil
   └─ /doctors/{id}/edit ← Editar su perfil
```

### **Para Paciente**
```
Login → /dashboard
   ↓
[Rol: Paciente]
   ↓
Panel con opciones de paciente
   ├─ /appointments ← Ver sus citas
   ├─ /appointments/create ← Agendar nueva cita
   └─ /doctors ← Ver doctores disponibles
```

---

## 🚀 Cómo Iniciar

```bash
# 1. Instalar dependencias
composer install

# 2. Configurar variables de entorno
cp .env.example .env
php artisan key:generate

# 3. Migrar y seedear la base de datos
php artisan migrate:fresh --seed

# 4. Iniciar servidor
php artisan serve

# 5. Abre en el navegador
http://localhost:8000
```

---

## 🔐 Sistema de Roles y Permisos

### Roles Disponibles
1. **Administrador** - Acceso total al sistema
2. **Doctor** - Acceso a su perfil y citas
3. **Paciente** - Crear y ver sus citas
4. **Recepcionista** - (Disponible para expansión futura)

### Middleware de Protección
- `admin` - Solo administradores
- `auth` - Solo usuarios autenticados
- `verified` - Usuarios con email verificado

---

## 📝 Rutas Públicas (Sin Login)

```
GET  /doctors           - Ver lista de doctores
GET  /doctors/{id}      - Ver perfil de un doctor
```

---

## ✅ Dashboard Dinámico

El `/dashboard` principal ahora es **inteligente**:

- **Administrador**: Muestra enlaces a panel de admin
- **Doctor**: Muestra opciones de gestión de citas
- **Paciente**: Muestra opciones para agendar citas

---

## 🎯 Endpoints Principales

### Admin Routes
```
GET    /admin/dashboard          - Panel de administración
GET    /admin/users              - Lista de usuarios
POST   /admin/users              - Crear usuario
PATCH  /admin/users/{id}         - Actualizar usuario
DELETE /admin/users/{id}         - Eliminar usuario

GET    /admin/roles              - Lista de roles
POST   /admin/roles              - Crear rol
PATCH  /admin/roles/{id}         - Actualizar rol
DELETE /admin/roles/{id}         - Eliminar rol

GET    /admin/patients           - Lista de pacientes
POST   /admin/patients           - Crear paciente
PATCH  /admin/patients/{id}      - Actualizar paciente
DELETE /admin/patients/{id}      - Eliminar paciente
```

### User Routes
```
GET    /appointments             - Mis citas
GET    /appointments/create      - Crear cita
POST   /appointments             - Guardar cita
GET    /appointments/{id}        - Ver cita
PATCH  /appointments/{id}/status - Cambiar estado
```

### Doctor Routes
```
GET    /doctors                  - Lista de doctores
GET    /doctors/{id}             - Ver doctor
GET    /doctors/{id}/edit        - Editar perfil
PATCH  /doctors/{id}             - Guardar cambios
```

---

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar solo tests de una clase
php artisan test --filter=UserManagement

# Ver cobertura
php artisan test --coverage
```

---

## 📊 Estado Actual

✅ Sistema de autenticación funcionando
✅ Sistema de roles y permisos implementado
✅ Dashboard dinámico según rol
✅ Gestión CRUD de usuarios, roles y pacientes
✅ Sistema de citas implementado
✅ Tests automatizados (38+ tests pasando)

---

## 🐛 Troubleshooting

### La página muestra "Welcome to Laravel" 
→ Asegúrate de estar en `/dashboard` después de login

### No puedo acceder a /admin
→ Debes tener rol "Administrador"

### Migrations fallando
→ Ejecuta: `php artisan migrate:fresh --seed`

### Tests fallando
→ Ejecuta: `php artisan migrate:fresh --seed` en ambiente de testing

---

**¡La aplicación está lista para usar!** 🚀

