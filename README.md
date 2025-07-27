# PMS Hotel Package

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.0%2B-red.svg)](https://laravel.com)

Un paquete completo de Sistema de Gestión de Propiedades Hoteleras (PMS) para Laravel, desarrollado por Kaely Technology. Este paquete proporciona una solución integral para la gestión de hoteles, incluyendo reservas, restaurantes, eventos y más.

## 📚 Documentación

La documentación completa está organizada en la carpeta `doc/` para facilitar la navegación:

### 📖 Documentación Principal
- [**Índice de Documentación**](./doc/README.md) - Navegación completa
- [**Documentación Técnica**](./doc/technical-documentation.md) - Instalación, configuración y arquitectura

### 🏨 Módulos Principales
- [**Room Rate Rules**](./doc/room-rate-rules.md) - Reglas de tarifas por habitación
- [**Reservations**](./doc/reservations.md) - Gestión de reservas principales
- [**Restaurant Reservations**](./doc/restaurant-reservations.md) - Reservas de restaurante
- [**Restaurants**](./doc/restaurants.md) - Gestión de restaurantes
- [**Foods**](./doc/foods.md) - Gestión de alimentos
- [**Food Types**](./doc/food-types.md) - Tipos de alimentos

### 🍽️ Módulos Adicionales
- [**Otros Módulos**](./doc/other-modules.md) - Beverages, Desserts, Events, Decorations, etc.

## 📋 Tabla de Contenidos

- [Características](#características)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Uso](#uso)
- [Módulos Disponibles](#módulos-disponibles)
- [API Endpoints](#api-endpoints)
- [Comandos Artisan](#comandos-artisan)
- [Modelos](#modelos)
- [Políticas de Autorización](#políticas-de-autorización)
- [Configuración de Base de Datos](#configuración-de-base-de-datos)
- [Integración con Auth Package](#integración-con-auth-package)
- [Contribuir](#contribuir)
- [Licencia](#licencia)

## ✨ Características

- **Gestión Completa de Hotel**: Manejo integral de todas las operaciones hoteleras
- **Sistema de Reservas**: Gestión avanzada de reservas de habitaciones y restaurantes
- **Gestión de Restaurantes**: Control completo de restaurantes, menús y disponibilidad
- **Sistema de Eventos**: Organización y gestión de eventos especiales
- **Gestión de Tarifas**: Reglas flexibles para tarifas de habitaciones
- **API RESTful**: Endpoints completos para todas las funcionalidades
- **Autenticación Integrada**: Compatible con Laravel Sanctum
- **Exportación/Importación**: Funcionalidades de exportar e importar datos
- **Sistema de Permisos**: Control granular de acceso por módulos
- **Multi-tenant**: Soporte para múltiples inquilinos

## 📋 Requisitos

- PHP 8.2 o superior
- Laravel 12.0 o superior
- MySQL/PostgreSQL
- Composer
- `kaelytechnology/auth-package`

## 🚀 Instalación

### 1. Instalar via Composer

```bash
composer require kaelytechnology/pms_hotel
```

### 2. Ejecutar el comando de instalación

```bash
php artisan pms-hotel:install
```

Este comando publicará:
- Archivos de configuración
- Migraciones de base de datos
- Seeders

### 3. Ejecutar las migraciones

```bash
php artisan migrate
```

### 4. (Opcional) Ejecutar los seeders

```bash
php artisan pms-hotel:seed
```

## ⚙️ Configuración

Después de la instalación, el archivo de configuración estará disponible en `config/pms-hotel.php`.

### Configuración Principal

```php
return [
    // Configuración de módulos
    'modules' => [
        // Cada módulo con sus permisos y configuración de menú
    ],
    
    // Configuración de base de datos
    'database' => [
        'connection' => 'tenant',
        'table_prefix' => 'pms_',
    ],
    
    // Integración con Auth Package
    'auth_integration' => [
        'module_slug' => 'pms_hotel',
        'module_name' => 'PMS Hotel',
        'auto_register_permissions' => true,
        'auto_register_menus' => true,
    ],
];
```

## 📖 Uso

### Registro del Service Provider

El paquete se registra automáticamente gracias a Laravel's Package Auto-Discovery. Si necesitas registrarlo manualmente:

```php
// config/app.php
'providers' => [
    // ...
    Kaely\PmsHotel\PmsHotelServiceProvider::class,
],
```

### Middleware y Rutas

Todas las rutas están protegidas con middleware `auth:sanctum` y tienen el prefijo `api/pms`.

## 🏨 Módulos Disponibles

El paquete incluye 16 módulos principales:

| Módulo | Descripción | Icono |
|--------|-------------|-------|
| **Room Rate Rules** | Gestión de reglas de tarifas de habitaciones | 🛏️ |
| **Foods** | Gestión de alimentos | 🍽️ |
| **Food Types** | Tipos de alimentos | 📋 |
| **Dishes** | Gestión de platillos | 📖 |
| **Departments** | Departamentos del hotel | 🏢 |
| **Decorations** | Decoraciones y ambientación | 🎨 |
| **Events** | Eventos especiales | 📅 |
| **Restaurants** | Gestión de restaurantes | 🏪 |
| **Desserts** | Postres | 🍨 |
| **Beverages** | Bebidas | 🍸 |
| **Room Changes** | Cambios de habitación | 🔄 |
| **Special Requirements** | Requerimientos especiales | ⭐ |
| **Restaurant Availability** | Disponibilidad de restaurantes | 🕐 |
| **Restaurant Reservations** | Reservas de restaurante | ✅ |
| **Reservations** | Reservas generales | 📑 |
| **Courtesy Dinners** | Cenas de cortesía | 🎁 |

Cada módulo incluye permisos para: `view`, `create`, `edit`, `delete`, `export`, `import`.

## 🔌 API Endpoints

Todos los endpoints están prefijados con `/api/pms` y requieren autenticación con Laravel Sanctum.

### Headers Requeridos

```http
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

### Parámetros de Consulta Comunes

Todos los endpoints de listado soportan los siguientes parámetros:

| Parámetro | Tipo | Descripción | Ejemplo |
|-----------|------|-------------|----------|
| `search` | string | Búsqueda en campos principales | `?search=hotel` |
| `sort_by` | string | Campo para ordenar | `?sort_by=created_at` |
| `sort_direction` | string | Dirección del orden (asc/desc) | `?sort_direction=desc` |
| `per_page` | integer | Elementos por página (máx: 100) | `?per_page=25` |
| `page` | integer | Número de página | `?page=2` |

### Estructura de Respuestas

#### Respuesta de Listado (Paginada)
```json
{
  "data": [...],
  "current_page": 1,
  "per_page": 15,
  "total": 100,
  "last_page": 7,
  "from": 1,
  "to": 15,
  "links": {...}
}
```

#### Respuesta de Éxito
```json
{
  "message": "Operación exitosa",
  "data": {...}
}
```

#### Respuesta de Error
```json
{
  "message": "Error de validación",
  "errors": {
    "field": ["Error específico"]
  }
}
```

---

## 📋 Documentación Completa de Endpoints

### 1. 🛏️ Room Rate Rules

**Base URL:** `/api/pms/room-rate-rules`

#### GET `/api/pms/room-rate-rules`
Listar todas las reglas de tarifas

**Parámetros adicionales:**
- Ninguno específico

**Respuesta:**
```json
{
  "data": [
    {
      "id": 1,
      "code": "STD001",
      "class": "Standard",
      "room_type_name": "Habitación Estándar",
      "min_nights": 2,
      "max_guests": 4,
      "included_dinners": 2,
      "rule_text": "Regla especial para temporada alta",
      "is_active": true,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### POST `/api/pms/room-rate-rules`
Crear nueva regla de tarifa

**Parámetros del cuerpo:**
```json
{
  "code": "string (requerido, máx: 50, único)",
  "class": "string (requerido, máx: 100)",
  "room_type_name": "string (requerido, máx: 100)",
  "min_nights": "integer (requerido, min: 1, máx: 365)",
  "max_guests": "integer (requerido, min: 1, máx: 50)",
  "included_dinners": "integer (opcional, min: 0, máx: 10)",
  "rule_text": "string (opcional, máx: 1000)",
  "is_active": "boolean (opcional, default: true)"
}
```

#### GET `/api/pms/room-rate-rules/{id}`
Obtener regla específica

#### PUT `/api/pms/room-rate-rules/{id}`
Actualizar regla existente

**Parámetros:** Mismos que POST pero todos opcionales

#### DELETE `/api/pms/room-rate-rules/{id}`
Eliminar regla

#### GET `/api/pms/room-rate-rules/classes`
Obtener lista de clases disponibles

#### GET `/api/pms/room-rate-rules/export`
Exportar reglas a CSV

#### POST `/api/pms/room-rate-rules/import`
Importar reglas desde archivo

**Parámetros:**
```json
{
  "file": "file (requerido, tipos: csv,xlsx,xls, máx: 2MB)"
}
```

---

### 2. 📋 Reservations

**Base URL:** `/api/pms/reservations`

#### GET `/api/pms/reservations`
Listar todas las reservas

**Respuesta:**
```json
{
  "data": [
    {
      "id": 1,
      "confirmation_number": "RES001",
      "guest_name": "Juan Pérez",
      "guest_email": "juan@email.com",
      "guest_phone": "+1234567890",
      "room_number": "101",
      "room_type": "Standard",
      "check_in_date": "2024-12-25",
      "check_out_date": "2024-12-28",
      "adults": 2,
      "children": 1,
      "total_amount": "299.99",
      "status": "confirmed",
      "special_requests": "Cama extra para niño",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### POST `/api/pms/reservations`
Crear nueva reserva

**Parámetros del cuerpo:**
```json
{
  "confirmation_number": "string (requerido, máx: 50, único)",
  "guest_name": "string (requerido, máx: 255)",
  "guest_email": "email (requerido, máx: 255)",
  "guest_phone": "string (opcional, máx: 20)",
  "room_number": "string (requerido, máx: 50)",
  "room_type": "string (requerido, máx: 100)",
  "check_in_date": "date (requerido, >= hoy)",
  "check_out_date": "date (requerido, > check_in_date)",
  "adults": "integer (requerido, min: 1, máx: 10)",
  "children": "integer (opcional, min: 0, máx: 10)",
  "total_amount": "decimal (requerido, min: 0)",
  "status": "enum (requerido: pending,confirmed,checked_in,checked_out,cancelled)",
  "special_requests": "string (opcional, máx: 2000)"
}
```

---

### 3. 🍽️ Restaurant Reservations

**Base URL:** `/api/pms/restaurant-reservations`

#### GET `/api/pms/restaurant-reservations`
Listar reservas de restaurante

**Respuesta:**
```json
{
  "data": [
    {
      "id": 1,
      "reservation_id": 1,
      "restaurant_id": 1,
      "event_id": 2,
      "food_id": 3,
      "dessert_id": 1,
      "beverage_id": 2,
      "decoration_id": 1,
      "special_requirement_id": 1,
      "availability_id": 1,
      "people": 4,
      "reservation_date": "2024-12-25",
      "reservation_time": "19:30",
      "status": "confirmed",
      "comment": "Mesa junto a la ventana",
      "other": "Celebración de aniversario",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### POST `/api/pms/restaurant-reservations`
Crear reserva de restaurante

**Parámetros del cuerpo:**
```json
{
  "reservation_id": "integer (requerido, existe en pms_reservations)",
  "restaurant_id": "integer (requerido, existe en pms_restaurants)",
  "event_id": "integer (opcional, existe en pms_events)",
  "food_id": "integer (opcional, existe en pms_foods)",
  "dessert_id": "integer (opcional, existe en pms_desserts)",
  "beverage_id": "integer (opcional, existe en pms_beverages)",
  "decoration_id": "integer (opcional, existe en pms_decorations)",
  "special_requirement_id": "integer (opcional, existe en pms_special_requirements)",
  "availability_id": "integer (opcional, existe en pms_restaurant_availabilities)",
  "people": "integer (requerido, min: 1, máx: 999)",
  "reservation_date": "date (requerido, >= hoy)",
  "reservation_time": "time (requerido, formato: HH:MM)",
  "status": "enum (requerido: pending,confirmed,cancelled,completed)",
  "comment": "string (opcional, máx: 1000)",
  "other": "string (opcional, máx: 500)"
}
```

---

### 4. 🏪 Restaurants

**Base URL:** `/api/pms/restaurants`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 5. 🍽️ Foods

**Base URL:** `/api/pms/foods`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 6. 📋 Food Types

**Base URL:** `/api/pms/food-types`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 7. 📖 Dishes

**Base URL:** `/api/pms/dishes`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 8. 🏢 Departments

**Base URL:** `/api/pms/departments`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 9. 🎨 Decorations

**Base URL:** `/api/pms/decorations`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 10. 📅 Events

**Base URL:** `/api/pms/events`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 11. 🍨 Desserts

**Base URL:** `/api/pms/desserts`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 12. 🍸 Beverages

**Base URL:** `/api/pms/beverages`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 13. 🔄 Room Changes

**Base URL:** `/api/pms/room-changes`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 14. ⭐ Special Requirements

**Base URL:** `/api/pms/special-requirements`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 15. 🕐 Restaurant Availability

**Base URL:** `/api/pms/restaurant-availability`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

### 16. 🎁 Courtesy Dinners

**Base URL:** `/api/pms/courtesy-dinners`

#### Estructura estándar CRUD
Todos los endpoints siguen el patrón RESTful estándar.

---

## 🔍 Códigos de Estado HTTP

| Código | Descripción |
|--------|-------------|
| 200 | OK - Operación exitosa |
| 201 | Created - Recurso creado exitosamente |
| 400 | Bad Request - Error en los parámetros |
| 401 | Unauthorized - Token de autenticación inválido |
| 403 | Forbidden - Sin permisos para la operación |
| 404 | Not Found - Recurso no encontrado |
| 422 | Unprocessable Entity - Error de validación |
| 500 | Internal Server Error - Error del servidor |

## 🔐 Autenticación y Permisos

Cada endpoint requiere permisos específicos:

- **view**: Para endpoints GET de listado y detalle
- **create**: Para endpoints POST
- **edit**: Para endpoints PUT/PATCH
- **delete**: Para endpoints DELETE
- **export**: Para endpoints de exportación
- **import**: Para endpoints de importación

Los permisos siguen el formato: `{module}.{action}` (ej: `reservations.view`, `restaurants.create`)

## 🛠️ Comandos Artisan

### Instalación
```bash
php artisan pms-hotel:install
```
Instala el paquete completo, publica configuraciones, migraciones y seeders.

### Seeders
```bash
php artisan pms-hotel:seed
```
Ejecuta los seeders para poblar la base de datos con datos de ejemplo.

### Generación de Módulos
```bash
php artisan pms-hotel:generate-modules
```
Genera automáticamente la estructura de módulos basada en la configuración.

## 📊 Modelos

Todos los modelos extienden de `BaseModel` que proporciona:

- **Conexión Multi-tenant**: Usa la conexión `tenant`
- **Prefijo de Tablas**: Automático basado en configuración
- **User Tracking**: Seguimiento de usuarios que crean/modifican registros
- **Soft Deletes**: Eliminación suave de registros
- **Scopes**: Scope `active()` para registros activos

### Modelos Principales

- `RoomRateRule` - Reglas de tarifas de habitaciones
- `Reservation` - Reservas generales
- `Restaurant` - Restaurantes
- `RestaurantReservation` - Reservas de restaurante
- `Food`, `FoodType`, `Dish` - Gestión de alimentos
- `Event` - Eventos especiales
- `Department` - Departamentos
- `Decoration` - Decoraciones
- `Beverage`, `Dessert` - Bebidas y postres
- `RoomChange` - Cambios de habitación
- `SpecialRequirement` - Requerimientos especiales
- `RestaurantAvailability` - Disponibilidad de restaurantes
- `CourtesyDinner` - Cenas de cortesía

## 🔐 Políticas de Autorización

El paquete incluye políticas de autorización para cada modelo principal:

- `RoomRateRulePolicy`
- `FoodPolicy`
- `FoodTypePolicy`
- `DishPolicy`
- `RestaurantPolicy`
- `RestaurantReservationPolicy`

Las políticas se registran automáticamente en el `PmsHotelServiceProvider`.

## 🗄️ Configuración de Base de Datos

### Conexión Multi-tenant

El paquete está configurado para usar la conexión `tenant` por defecto:

```php
'database' => [
    'connection' => 'tenant',
    'table_prefix' => 'pms_',
],
```

### Prefijo de Tablas

Todas las tablas usan el prefijo `pms_` por defecto. Esto se puede cambiar en la configuración.

### Migraciones

Las migraciones se publican en `database/migrations` y crean todas las tablas necesarias para el funcionamiento del sistema.

## 🔗 Integración con Auth Package

El paquete se integra automáticamente con `kaelytechnology/auth-package`:

```php
'auth_integration' => [
    'module_slug' => 'pms_hotel',
    'module_name' => 'PMS Hotel',
    'auto_register_permissions' => true,
    'auto_register_menus' => true,
],
```

- **Registro Automático de Permisos**: Todos los permisos se registran automáticamente
- **Menús Automáticos**: Los menús se generan basados en la configuración de módulos
- **Control de Acceso**: Integración completa con el sistema de roles y permisos

## 🧪 Testing

```bash
# Ejecutar tests
vendor/bin/phpunit

# Ejecutar tests con coverage
vendor/bin/phpunit --coverage-html coverage
```

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📝 Changelog

Ver [CHANGELOG.md](CHANGELOG.md) para una lista de cambios.

## 🔒 Seguridad

Si descubres alguna vulnerabilidad de seguridad, por favor envía un email a info@kaelytechnology.com.

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.

## 👥 Autores

- **Kaely Technology** - *Desarrollo inicial* - [Kaely Technology](https://github.com/kaelytechnology)

## 🙏 Agradecimientos

- Laravel Framework
- Comunidad de desarrolladores PHP
- Todos los contribuidores del proyecto

---

**Desarrollado con ❤️ por [Kaely Technology](https://kaelytechnology.com)**