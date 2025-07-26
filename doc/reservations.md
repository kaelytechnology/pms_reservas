# Reservations

## Descripción
Módulo principal para gestionar las reservas del hotel, incluyendo información del huésped, fechas de estadía, habitaciones, tarifas y estado de la reserva.

## Endpoints

### Base URL
```
/api/pms/reservations
```

### Autenticación
Todos los endpoints requieren autenticación Bearer Token.

```http
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Operaciones CRUD

### 1. Listar Reservations

**GET** `/api/pms/reservations`

#### Parámetros de Consulta
| Parámetro | Tipo | Descripción | Ejemplo |
|-----------|------|-------------|----------|
| `search` | string | Búsqueda en número de confirmación, nombre del huésped, email | `?search=John` |
| `sort_by` | string | Campo para ordenar | `?sort_by=check_in_date` |
| `sort_direction` | string | Dirección (asc/desc) | `?sort_direction=desc` |
| `per_page` | integer | Elementos por página (máx: 100) | `?per_page=20` |
| `page` | integer | Número de página | `?page=2` |
| `status` | string | Filtro por estado | `?status=confirmed` |
| `check_in_date` | date | Filtro por fecha de entrada | `?check_in_date=2024-01-15` |

#### Respuesta de Éxito (200)
```json
{
  "data": [
    {
      "id": 1,
      "confirmation_number": "RES-2024-001",
      "guest_name": "Juan Pérez",
      "guest_email": "juan.perez@email.com",
      "guest_phone": "+1234567890",
      "guest_document": "12345678",
      "check_in_date": "2024-01-15",
      "check_out_date": "2024-01-18",
      "nights": 3,
      "adults": 2,
      "children": 1,
      "room_number": "101",
      "room_type": "Standard Double",
      "rate_plan": "Best Available Rate",
      "total_amount": 450.00,
      "paid_amount": 150.00,
      "pending_amount": 300.00,
      "status": "confirmed",
      "special_requests": "Cama extra para niño",
      "created_at": "2024-01-10T14:30:00.000000Z",
      "updated_at": "2024-01-12T09:15:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost/api/pms/reservations?page=1",
    "last": "http://localhost/api/pms/reservations?page=8",
    "prev": null,
    "next": "http://localhost/api/pms/reservations?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 8,
    "per_page": 15,
    "to": 15,
    "total": 112
  }
}
```

### 2. Crear Reservation

**POST** `/api/pms/reservations`

#### Parámetros del Cuerpo
| Campo | Tipo | Requerido | Validación | Descripción |
|-------|------|-----------|------------|-------------|
| `confirmation_number` | string | Sí | único, máx 50 chars | Número de confirmación único |
| `guest_name` | string | Sí | máx 100 chars | Nombre completo del huésped |
| `guest_email` | string | Sí | email válido, máx 100 chars | Email del huésped |
| `guest_phone` | string | No | máx 20 chars | Teléfono del huésped |
| `guest_document` | string | No | máx 50 chars | Documento de identidad |
| `check_in_date` | date | Sí | formato YYYY-MM-DD | Fecha de entrada |
| `check_out_date` | date | Sí | formato YYYY-MM-DD, posterior a check_in | Fecha de salida |
| `nights` | integer | Sí | mín 1, máx 365 | Número de noches |
| `adults` | integer | Sí | mín 1, máx 20 | Número de adultos |
| `children` | integer | No | mín 0, máx 10 | Número de niños |
| `room_number` | string | No | máx 10 chars | Número de habitación |
| `room_type` | string | No | máx 100 chars | Tipo de habitación |
| `rate_plan` | string | No | máx 100 chars | Plan de tarifa |
| `total_amount` | decimal | Sí | mín 0, máx 999999.99 | Monto total |
| `paid_amount` | decimal | No | mín 0, máx total_amount | Monto pagado |
| `pending_amount` | decimal | No | mín 0 | Monto pendiente |
| `status` | string | No | enum: pending, confirmed, checked_in, checked_out, cancelled, no_show | Estado de la reserva |
| `special_requests` | text | No | máx 1000 chars | Solicitudes especiales |

#### Ejemplo de Petición
```json
{
  "confirmation_number": "RES-2024-002",
  "guest_name": "María García",
  "guest_email": "maria.garcia@email.com",
  "guest_phone": "+1987654321",
  "guest_document": "87654321",
  "check_in_date": "2024-02-01",
  "check_out_date": "2024-02-05",
  "nights": 4,
  "adults": 2,
  "children": 0,
  "room_number": "205",
  "room_type": "Superior Double",
  "rate_plan": "Early Bird",
  "total_amount": 600.00,
  "paid_amount": 200.00,
  "pending_amount": 400.00,
  "status": "confirmed",
  "special_requests": "Vista al mar preferida"
}
```

#### Respuesta de Éxito (201)
```json
{
  "data": {
    "id": 2,
    "confirmation_number": "RES-2024-002",
    "guest_name": "María García",
    "guest_email": "maria.garcia@email.com",
    "guest_phone": "+1987654321",
    "guest_document": "87654321",
    "check_in_date": "2024-02-01",
    "check_out_date": "2024-02-05",
    "nights": 4,
    "adults": 2,
    "children": 0,
    "room_number": "205",
    "room_type": "Superior Double",
    "rate_plan": "Early Bird",
    "total_amount": 600.00,
    "paid_amount": 200.00,
    "pending_amount": 400.00,
    "status": "confirmed",
    "special_requests": "Vista al mar preferida",
    "created_at": "2024-01-15T16:45:00.000000Z",
    "updated_at": "2024-01-15T16:45:00.000000Z"
  },
  "message": "Reserva creada exitosamente"
}
```

### 3. Mostrar Reservation

**GET** `/api/pms/reservations/{id}`

#### Respuesta de Éxito (200)
```json
{
  "data": {
    "id": 1,
    "confirmation_number": "RES-2024-001",
    "guest_name": "Juan Pérez",
    "guest_email": "juan.perez@email.com",
    "guest_phone": "+1234567890",
    "guest_document": "12345678",
    "check_in_date": "2024-01-15",
    "check_out_date": "2024-01-18",
    "nights": 3,
    "adults": 2,
    "children": 1,
    "room_number": "101",
    "room_type": "Standard Double",
    "rate_plan": "Best Available Rate",
    "total_amount": 450.00,
    "paid_amount": 150.00,
    "pending_amount": 300.00,
    "status": "confirmed",
    "special_requests": "Cama extra para niño",
    "restaurant_reservations": [],
    "room_changes": [],
    "courtesy_dinners": [],
    "created_at": "2024-01-10T14:30:00.000000Z",
    "updated_at": "2024-01-12T09:15:00.000000Z"
  }
}
```

### 4. Actualizar Reservation

**PUT** `/api/pms/reservations/{id}`

Utiliza los mismos parámetros que la creación.

### 5. Eliminar Reservation

**DELETE** `/api/pms/reservations/{id}`

#### Respuesta de Éxito (200)
```json
{
  "message": "Reserva eliminada exitosamente"
}
```

## Endpoints Especiales

### Exportar a CSV

**GET** `/api/pms/reservations/export`

#### Parámetros de Consulta
| Parámetro | Tipo | Descripción |
|-----------|------|--------------|
| `search` | string | Filtro de búsqueda |
| `status` | string | Filtro por estado |
| `check_in_date` | date | Filtro por fecha de entrada |
| `check_out_date` | date | Filtro por fecha de salida |

#### Respuesta
Archivo CSV descargable.

### Importar desde CSV/Excel

**POST** `/api/pms/reservations/import`

#### Parámetros
| Campo | Tipo | Requerido | Validación |
|-------|------|-----------|------------|
| `file` | file | Sí | CSV/Excel, máx 10MB |

#### Respuesta de Éxito (200)
```json
{
  "message": "Importación completada exitosamente",
  "imported": 45,
  "errors": [
    {
      "row": 12,
      "errors": ["El email ya existe en el sistema"]
    }
  ]
}
```

## Estados de Reserva

| Estado | Descripción |
|--------|-------------|
| `pending` | Reserva pendiente de confirmación |
| `confirmed` | Reserva confirmada |
| `checked_in` | Huésped registrado (check-in realizado) |
| `checked_out` | Huésped ha salido (check-out realizado) |
| `cancelled` | Reserva cancelada |
| `no_show` | Huésped no se presentó |

## Relaciones

### Restaurant Reservations
Una reserva puede tener múltiples reservas de restaurante asociadas.

### Room Changes
Una reserva puede tener múltiples cambios de habitación registrados.

### Courtesy Dinners
Una reserva puede tener múltiples cenas de cortesía asociadas.

## Permisos Requeridos

| Acción | Permiso |
|--------|----------|
| Listar | `reservations.view` |
| Crear | `reservations.create` |
| Ver | `reservations.view` |
| Actualizar | `reservations.edit` |
| Eliminar | `reservations.delete` |
| Exportar | `reservations.export` |
| Importar | `reservations.import` |

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

1. El `confirmation_number` debe ser único en todo el sistema
2. La fecha de `check_out_date` debe ser posterior a `check_in_date`
3. El campo `nights` se calcula automáticamente basado en las fechas
4. El `pending_amount` se calcula como `total_amount - paid_amount`
5. Las validaciones de email incluyen formato y unicidad
6. Todos los montos se manejan con 2 decimales de precisión
7. Los cambios de estado quedan registrados con información del usuario