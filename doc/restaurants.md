# Restaurants

## Descripción
Módulo para gestionar los restaurantes del hotel, incluyendo información de capacidad, horarios de operación y características especiales.

## Endpoints

### Base URL
```
/api/pms/restaurants
```

### Autenticación
Todos los endpoints requieren autenticación Bearer Token.

```http
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Operaciones CRUD

### 1. Listar Restaurants

**GET** `/api/pms/restaurants`

#### Parámetros de Consulta
| Parámetro | Tipo | Descripción | Ejemplo |
|-----------|------|-------------|----------|
| `search` | string | Búsqueda en nombre y descripción | `?search=principal` |
| `sort_by` | string | Campo para ordenar | `?sort_by=name` |
| `sort_direction` | string | Dirección (asc/desc) | `?sort_direction=asc` |
| `per_page` | integer | Elementos por página (máx: 100) | `?per_page=20` |
| `page` | integer | Número de página | `?page=2` |
| `is_active` | boolean | Filtro por estado activo | `?is_active=true` |

#### Respuesta de Éxito (200)
```json
{
  "data": [
    {
      "id": 1,
      "name": "Restaurante Principal",
      "description": "Restaurante principal del hotel con cocina internacional",
      "capacity": 120,
      "opening_time": "18:00:00",
      "closing_time": "23:00:00",
      "location": "Planta Baja - Lobby",
      "cuisine_type": "Internacional",
      "dress_code": "Casual Elegante",
      "reservation_required": true,
      "outdoor_seating": false,
      "private_dining": true,
      "is_active": true,
      "reservations_count": 45,
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost/api/pms/restaurants?page=1",
    "last": "http://localhost/api/pms/restaurants?page=2",
    "prev": null,
    "next": "http://localhost/api/pms/restaurants?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 2,
    "per_page": 15,
    "to": 15,
    "total": 25
  }
}
```

### 2. Crear Restaurant

**POST** `/api/pms/restaurants`

#### Parámetros del Cuerpo
| Campo | Tipo | Requerido | Validación | Descripción |
|-------|------|-----------|------------|-------------|
| `name` | string | Sí | único, máx 100 chars | Nombre del restaurante |
| `description` | text | No | máx 500 chars | Descripción del restaurante |
| `capacity` | integer | Sí | mín 1, máx 500 | Capacidad máxima de personas |
| `opening_time` | time | Sí | formato HH:MM:SS | Hora de apertura |
| `closing_time` | time | Sí | formato HH:MM:SS | Hora de cierre |
| `location` | string | No | máx 200 chars | Ubicación en el hotel |
| `cuisine_type` | string | No | máx 100 chars | Tipo de cocina |
| `dress_code` | string | No | máx 100 chars | Código de vestimenta |
| `reservation_required` | boolean | No | true/false | Requiere reserva |
| `outdoor_seating` | boolean | No | true/false | Tiene asientos al aire libre |
| `private_dining` | boolean | No | true/false | Ofrece cenas privadas |
| `is_active` | boolean | No | true/false | Estado activo (default: true) |

#### Ejemplo de Petición
```json
{
  "name": "Terraza Sunset",
  "description": "Restaurante en la terraza con vista al mar y cocina mediterránea",
  "capacity": 80,
  "opening_time": "17:00:00",
  "closing_time": "24:00:00",
  "location": "Terraza - Piso 5",
  "cuisine_type": "Mediterránea",
  "dress_code": "Casual",
  "reservation_required": true,
  "outdoor_seating": true,
  "private_dining": false,
  "is_active": true
}
```

#### Respuesta de Éxito (201)
```json
{
  "data": {
    "id": 2,
    "name": "Terraza Sunset",
    "description": "Restaurante en la terraza con vista al mar y cocina mediterránea",
    "capacity": 80,
    "opening_time": "17:00:00",
    "closing_time": "24:00:00",
    "location": "Terraza - Piso 5",
    "cuisine_type": "Mediterránea",
    "dress_code": "Casual",
    "reservation_required": true,
    "outdoor_seating": true,
    "private_dining": false,
    "is_active": true,
    "reservations_count": 0,
    "created_at": "2024-01-15T11:15:00.000000Z",
    "updated_at": "2024-01-15T11:15:00.000000Z"
  },
  "message": "Restaurante creado exitosamente"
}
```

### 3. Mostrar Restaurant

**GET** `/api/pms/restaurants/{id}`

### 4. Actualizar Restaurant

**PUT** `/api/pms/restaurants/{id}`

### 5. Eliminar Restaurant

**DELETE** `/api/pms/restaurants/{id}`

## Endpoints Especiales

### Verificar Disponibilidad

**GET** `/api/pms/restaurants/{id}/availability`

#### Parámetros de Consulta
| Parámetro | Tipo | Requerido | Descripción |
|-----------|------|-----------|-------------|
| `date` | date | Sí | Fecha a consultar |
| `time` | time | Sí | Hora a consultar |
| `people` | integer | Sí | Número de personas |

#### Respuesta de Éxito (200)
```json
{
  "available": true,
  "capacity_remaining": 45,
  "suggested_times": [
    "18:00:00",
    "18:30:00",
    "19:00:00",
    "20:00:00"
  ]
}
```

### Exportar a CSV

**GET** `/api/pms/restaurants/export`

### Importar desde CSV/Excel

**POST** `/api/pms/restaurants/import`

## Características Especiales

### Horarios de Operación
- `opening_time` y `closing_time` definen el horario de servicio
- Se valida que la hora de cierre sea posterior a la de apertura
- Soporte para horarios que cruzan medianoche

### Capacidad y Reservas
- `capacity` define el número máximo de comensales
- `reservation_required` indica si es obligatorio reservar
- Sistema de verificación de disponibilidad en tiempo real

### Tipos de Servicio
- `outdoor_seating` - Servicio al aire libre
- `private_dining` - Cenas privadas o eventos especiales
- `dress_code` - Código de vestimenta requerido

## Relaciones

### Restaurant Reservations
Cada restaurante puede tener múltiples reservas asociadas.

### Restaurant Availability
Cada restaurante puede tener configuraciones de disponibilidad específicas.

## Validaciones Especiales

### Horarios
- La hora de cierre debe ser posterior a la de apertura
- Formato de tiempo en HH:MM:SS
- Validación de horarios válidos (00:00:00 - 23:59:59)

### Capacidad
- Debe ser un número positivo
- Se recomienda considerar distanciamiento social si aplica
- Verificación contra reservas existentes al reducir capacidad

## Permisos Requeridos

| Acción | Permiso |
|--------|----------|
| Listar | `restaurants.view` |
| Crear | `restaurants.create` |
| Ver | `restaurants.view` |
| Actualizar | `restaurants.edit` |
| Eliminar | `restaurants.delete` |
| Exportar | `restaurants.export` |
| Importar | `restaurants.import` |

## Códigos de Estado HTTP

- **200** - Operación exitosa
- **201** - Recurso creado exitosamente
- **400** - Error de validación
- **401** - No autenticado
- **403** - Sin permisos
- **404** - Recurso no encontrado
- **422** - Error de validación de datos
- **500** - Error interno del servidor

## Notas Importantes

1. El nombre del restaurante debe ser único en el sistema
2. Los horarios se almacenan en formato 24 horas
3. La capacidad afecta directamente la disponibilidad de reservas
4. Solo restaurantes activos aparecen en formularios de reserva
5. La eliminación está protegida si existen reservas activas
6. Se incluye contador de reservas para información estadística
7. Los cambios de capacidad pueden afectar reservas existentes