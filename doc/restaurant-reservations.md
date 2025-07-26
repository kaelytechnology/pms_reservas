# Restaurant Reservations

## Descripción
Módulo para gestionar las reservas de restaurante asociadas a las reservas principales del hotel, incluyendo selección de restaurante, eventos, comidas, postres, bebidas y decoraciones.

## Endpoints

### Base URL
```
/api/pms/restaurant-reservations
```

### Autenticación
Todos los endpoints requieren autenticación Bearer Token.

```http
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Operaciones CRUD

### 1. Listar Restaurant Reservations

**GET** `/api/pms/restaurant-reservations`

#### Parámetros de Consulta
| Parámetro | Tipo | Descripción | Ejemplo |
|-----------|------|-------------|----------|
| `search` | string | Búsqueda en comentarios y otros campos | `?search=cumpleaños` |
| `sort_by` | string | Campo para ordenar | `?sort_by=reservation_date` |
| `sort_direction` | string | Dirección (asc/desc) | `?sort_direction=desc` |
| `per_page` | integer | Elementos por página (máx: 100) | `?per_page=20` |
| `page` | integer | Número de página | `?page=2` |
| `status` | string | Filtro por estado | `?status=confirmed` |
| `restaurant_id` | integer | Filtro por restaurante | `?restaurant_id=1` |
| `reservation_date` | date | Filtro por fecha | `?reservation_date=2024-01-15` |

#### Respuesta de Éxito (200)
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
      "special_requirement_id": null,
      "availability_id": 1,
      "people": 4,
      "reservation_date": "2024-01-16",
      "reservation_time": "19:30:00",
      "status": "confirmed",
      "comment": "Celebración de aniversario",
      "other": "Mesa junto a la ventana",
      "reservation": {
        "id": 1,
        "confirmation_number": "RES-2024-001",
        "guest_name": "Juan Pérez"
      },
      "restaurant": {
        "id": 1,
        "name": "Restaurante Principal",
        "description": "Restaurante principal del hotel"
      },
      "event": {
        "id": 2,
        "name": "Cena Romántica",
        "description": "Cena especial para parejas"
      },
      "food": {
        "id": 3,
        "name": "Menú Gourmet",
        "description": "Selección de platos gourmet"
      },
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost/api/pms/restaurant-reservations?page=1",
    "last": "http://localhost/api/pms/restaurant-reservations?page=6",
    "prev": null,
    "next": "http://localhost/api/pms/restaurant-reservations?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 6,
    "per_page": 15,
    "to": 15,
    "total": 89
  }
}
```

### 2. Crear Restaurant Reservation

**POST** `/api/pms/restaurant-reservations`

#### Parámetros del Cuerpo
| Campo | Tipo | Requerido | Validación | Descripción |
|-------|------|-----------|------------|-------------|
| `reservation_id` | integer | Sí | existe en reservations | ID de la reserva principal |
| `restaurant_id` | integer | Sí | existe en restaurants | ID del restaurante |
| `event_id` | integer | No | existe en events | ID del evento |
| `food_id` | integer | No | existe en foods | ID de la comida |
| `dessert_id` | integer | No | existe en desserts | ID del postre |
| `beverage_id` | integer | No | existe en beverages | ID de la bebida |
| `decoration_id` | integer | No | existe en decorations | ID de la decoración |
| `special_requirement_id` | integer | No | existe en special_requirements | ID del requerimiento especial |
| `availability_id` | integer | No | existe en restaurant_availability | ID de disponibilidad |
| `people` | integer | Sí | mín 1, máx 50 | Número de personas |
| `reservation_date` | date | Sí | formato YYYY-MM-DD | Fecha de la reserva |
| `reservation_time` | time | Sí | formato HH:MM:SS | Hora de la reserva |
| `status` | string | No | enum: pending, confirmed, cancelled, completed | Estado de la reserva |
| `comment` | text | No | máx 1000 chars | Comentarios adicionales |
| `other` | text | No | máx 500 chars | Otras especificaciones |

#### Ejemplo de Petición
```json
{
  "reservation_id": 1,
  "restaurant_id": 1,
  "event_id": 2,
  "food_id": 3,
  "dessert_id": 1,
  "beverage_id": 2,
  "decoration_id": 1,
  "people": 4,
  "reservation_date": "2024-01-16",
  "reservation_time": "19:30:00",
  "status": "confirmed",
  "comment": "Celebración de aniversario",
  "other": "Mesa junto a la ventana"
}
```

#### Respuesta de Éxito (201)
```json
{
  "data": {
    "id": 2,
    "reservation_id": 1,
    "restaurant_id": 1,
    "event_id": 2,
    "food_id": 3,
    "dessert_id": 1,
    "beverage_id": 2,
    "decoration_id": 1,
    "special_requirement_id": null,
    "availability_id": null,
    "people": 4,
    "reservation_date": "2024-01-16",
    "reservation_time": "19:30:00",
    "status": "confirmed",
    "comment": "Celebración de aniversario",
    "other": "Mesa junto a la ventana",
    "created_at": "2024-01-15T11:15:00.000000Z",
    "updated_at": "2024-01-15T11:15:00.000000Z"
  },
  "message": "Reserva de restaurante creada exitosamente"
}
```

### 3. Mostrar Restaurant Reservation

**GET** `/api/pms/restaurant-reservations/{id}`

#### Respuesta de Éxito (200)
```json
{
  "data": {
    "id": 1,
    "reservation_id": 1,
    "restaurant_id": 1,
    "event_id": 2,
    "food_id": 3,
    "dessert_id": 1,
    "beverage_id": 2,
    "decoration_id": 1,
    "special_requirement_id": null,
    "availability_id": 1,
    "people": 4,
    "reservation_date": "2024-01-16",
    "reservation_time": "19:30:00",
    "status": "confirmed",
    "comment": "Celebración de aniversario",
    "other": "Mesa junto a la ventana",
    "reservation": {
      "id": 1,
      "confirmation_number": "RES-2024-001",
      "guest_name": "Juan Pérez",
      "guest_email": "juan.perez@email.com",
      "check_in_date": "2024-01-15",
      "check_out_date": "2024-01-18"
    },
    "restaurant": {
      "id": 1,
      "name": "Restaurante Principal",
      "description": "Restaurante principal del hotel",
      "capacity": 100,
      "opening_time": "18:00:00",
      "closing_time": "23:00:00"
    },
    "event": {
      "id": 2,
      "name": "Cena Romántica",
      "description": "Cena especial para parejas",
      "price": 50.00
    },
    "food": {
      "id": 3,
      "name": "Menú Gourmet",
      "description": "Selección de platos gourmet",
      "price": 75.00
    },
    "dessert": {
      "id": 1,
      "name": "Tiramisú",
      "description": "Postre italiano tradicional",
      "price": 15.00
    },
    "beverage": {
      "id": 2,
      "name": "Vino Tinto Reserva",
      "description": "Vino tinto de la casa",
      "price": 35.00
    },
    "decoration": {
      "id": 1,
      "name": "Decoración Romántica",
      "description": "Velas y flores para ambiente romántico",
      "price": 25.00
    },
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

### 4. Actualizar Restaurant Reservation

**PUT** `/api/pms/restaurant-reservations/{id}`

Utiliza los mismos parámetros que la creación. Nota: Se permite actualizar fechas pasadas.

### 5. Eliminar Restaurant Reservation

**DELETE** `/api/pms/restaurant-reservations/{id}`

#### Respuesta de Éxito (200)
```json
{
  "message": "Reserva de restaurante eliminada exitosamente"
}
```

## Endpoints Especiales

### Exportar a CSV

**GET** `/api/pms/restaurant-reservations/export`

#### Parámetros de Consulta
| Parámetro | Tipo | Descripción |
|-----------|------|--------------|
| `search` | string | Filtro de búsqueda |
| `status` | string | Filtro por estado |
| `restaurant_id` | integer | Filtro por restaurante |
| `reservation_date` | date | Filtro por fecha |
| `event_id` | integer | Filtro por evento |

#### Respuesta
Archivo CSV descargable.

### Importar desde CSV/Excel

**POST** `/api/pms/restaurant-reservations/import`

#### Parámetros
| Campo | Tipo | Requerido | Validación |
|-------|------|-----------|------------|
| `file` | file | Sí | CSV/Excel, máx 10MB |

#### Respuesta de Éxito (200)
```json
{
  "message": "Importación completada exitosamente",
  "imported": 32,
  "errors": [
    {
      "row": 8,
      "errors": ["La reserva principal no existe"]
    },
    {
      "row": 15,
      "errors": ["El restaurante no está disponible en esa fecha"]
    }
  ]
}
```

## Estados de Restaurant Reservation

| Estado | Descripción |
|--------|-------------|
| `pending` | Reserva pendiente de confirmación |
| `confirmed` | Reserva confirmada |
| `cancelled` | Reserva cancelada |
| `completed` | Reserva completada (servicio realizado) |

## Relaciones

### Reservation (Reserva Principal)
Cada reserva de restaurante debe estar asociada a una reserva principal del hotel.

### Restaurant
Cada reserva debe especificar en qué restaurante se realizará.

### Event (Opcional)
Puede estar asociada a un evento especial (cumpleaños, aniversario, etc.).

### Food, Dessert, Beverage (Opcionales)
Pueden especificar selecciones específicas de comida, postre y bebida.

### Decoration (Opcional)
Puede incluir decoraciones especiales para la mesa.

### Special Requirement (Opcional)
Puede incluir requerimientos especiales (alergias, dietas, etc.).

### Restaurant Availability (Opcional)
Puede estar vinculada a un slot de disponibilidad específico.

## Validaciones Especiales

### Fechas
- Durante la creación: `reservation_date` debe ser fecha futura
- Durante la actualización: Se permiten fechas pasadas
- `reservation_time` debe estar en formato HH:MM:SS

### Capacidad
- El número de `people` no debe exceder la capacidad del restaurante
- Debe verificarse disponibilidad en la fecha/hora solicitada

### Relaciones
- Todas las relaciones (restaurant_id, event_id, etc.) deben existir en sus respectivas tablas
- La reserva principal debe estar activa

## Permisos Requeridos

| Acción | Permiso |
|--------|----------|
| Listar | `restaurant_reservations.view` |
| Crear | `restaurant_reservations.create` |
| Ver | `restaurant_reservations.view` |
| Actualizar | `restaurant_reservations.edit` |
| Eliminar | `restaurant_reservations.delete` |
| Exportar | `restaurant_reservations.export` |
| Importar | `restaurant_reservations.import` |

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

1. Una reserva principal puede tener múltiples reservas de restaurante
2. Las fechas pasadas solo se permiten en actualizaciones
3. El sistema debe verificar disponibilidad del restaurante
4. Los precios se calculan automáticamente basado en las selecciones
5. Las notificaciones se envían automáticamente al confirmar
6. Los cambios de estado quedan registrados con información del usuario
7. Se pueden aplicar restricciones de horario según el restaurante