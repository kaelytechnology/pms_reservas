# Room Rate Rules

## Descripción
Módulo para gestionar las reglas de tarifas por habitación, incluyendo códigos, tipos de habitación, número mínimo de noches, huéspedes máximos y cenas incluidas.

## Endpoints

### Base URL
```
/api/pms/room-rate-rules
```

### Autenticación
Todos los endpoints requieren autenticación Bearer Token.

```http
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Operaciones CRUD

### 1. Listar Room Rate Rules

**GET** `/api/pms/room-rate-rules`

#### Parámetros de Consulta
| Parámetro | Tipo | Descripción | Ejemplo |
|-----------|------|-------------|----------|
| `search` | string | Búsqueda en código, clase, tipo de habitación | `?search=VIP` |
| `sort_by` | string | Campo para ordenar | `?sort_by=code` |
| `sort_direction` | string | Dirección (asc/desc) | `?sort_direction=desc` |
| `per_page` | integer | Elementos por página (máx: 100) | `?per_page=20` |
| `page` | integer | Número de página | `?page=2` |

#### Respuesta de Éxito (200)
```json
{
  "data": [
    {
      "id": 1,
      "code": "VIP001",
      "class": "VIP",
      "room_type_name": "Suite Presidencial",
      "min_nights": 2,
      "max_guests": 4,
      "included_dinners": 2,
      "rule_text": "Tarifa especial para suite presidencial con servicios VIP",
      "is_active": true,
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost/api/pms/room-rate-rules?page=1",
    "last": "http://localhost/api/pms/room-rate-rules?page=5",
    "prev": null,
    "next": "http://localhost/api/pms/room-rate-rules?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 15,
    "to": 15,
    "total": 67
  }
}
```

### 2. Crear Room Rate Rule

**POST** `/api/pms/room-rate-rules`

#### Parámetros del Cuerpo
| Campo | Tipo | Requerido | Validación | Descripción |
|-------|------|-----------|------------|-------------|
| `code` | string | Sí | único, máx 50 chars | Código único de la regla |
| `class` | string | No | máx 100 chars | Clase de la habitación |
| `room_type_name` | string | Sí | máx 100 chars | Nombre del tipo de habitación |
| `min_nights` | integer | No | 1-365 | Número mínimo de noches |
| `max_guests` | integer | No | 1-50 | Número máximo de huéspedes |
| `included_dinners` | integer | No | 0-10 | Cenas incluidas |
| `rule_text` | string | No | máx 1000 chars | Texto descriptivo de la regla |
| `is_active` | boolean | No | true/false | Estado activo (default: true) |

#### Ejemplo de Petición
```json
{
  "code": "STD001",
  "class": "Standard",
  "room_type_name": "Habitación Estándar",
  "min_nights": 1,
  "max_guests": 2,
  "included_dinners": 0,
  "rule_text": "Tarifa estándar para habitación doble",
  "is_active": true
}
```

#### Respuesta de Éxito (201)
```json
{
  "data": {
    "id": 2,
    "code": "STD001",
    "class": "Standard",
    "room_type_name": "Habitación Estándar",
    "min_nights": 1,
    "max_guests": 2,
    "included_dinners": 0,
    "rule_text": "Tarifa estándar para habitación doble",
    "is_active": true,
    "created_at": "2024-01-15T11:00:00.000000Z",
    "updated_at": "2024-01-15T11:00:00.000000Z"
  },
  "message": "Room Rate Rule creado exitosamente"
}
```

### 3. Mostrar Room Rate Rule

**GET** `/api/pms/room-rate-rules/{id}`

#### Respuesta de Éxito (200)
```json
{
  "data": {
    "id": 1,
    "code": "VIP001",
    "class": "VIP",
    "room_type_name": "Suite Presidencial",
    "min_nights": 2,
    "max_guests": 4,
    "included_dinners": 2,
    "rule_text": "Tarifa especial para suite presidencial con servicios VIP",
    "is_active": true,
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

### 4. Actualizar Room Rate Rule

**PUT** `/api/pms/room-rate-rules/{id}`

Utiliza los mismos parámetros que la creación.

### 5. Eliminar Room Rate Rule

**DELETE** `/api/pms/room-rate-rules/{id}`

#### Respuesta de Éxito (200)
```json
{
  "message": "Room Rate Rule eliminado exitosamente"
}
```

## Endpoints Especiales

### Obtener Clases Disponibles

**GET** `/api/pms/room-rate-rules/classes`

#### Respuesta de Éxito (200)
```json
{
  "data": [
    "Standard",
    "Superior",
    "Deluxe",
    "VIP",
    "Presidential"
  ]
}
```

### Exportar a CSV

**GET** `/api/pms/room-rate-rules/export`

#### Parámetros de Consulta
| Parámetro | Tipo | Descripción |
|-----------|------|--------------|
| `search` | string | Filtro de búsqueda |
| `class` | string | Filtro por clase |
| `is_active` | boolean | Filtro por estado |

#### Respuesta
Archivo CSV descargable.

### Importar desde CSV/Excel

**POST** `/api/pms/room-rate-rules/import`

#### Parámetros
| Campo | Tipo | Requerido | Validación |
|-------|------|-----------|------------|
| `file` | file | Sí | CSV/Excel, máx 10MB |

#### Respuesta de Éxito (200)
```json
{
  "message": "Importación completada exitosamente",
  "imported": 25,
  "errors": []
}
```

## Permisos Requeridos

| Acción | Permiso |
|--------|----------|
| Listar | `room_rate_rules.view` |
| Crear | `room_rate_rules.create` |
| Ver | `room_rate_rules.view` |
| Actualizar | `room_rate_rules.edit` |
| Eliminar | `room_rate_rules.delete` |
| Exportar | `room_rate_rules.export` |
| Importar | `room_rate_rules.import` |

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

1. El campo `code` se convierte automáticamente a mayúsculas
2. El campo `code` debe ser único en todo el sistema
3. Los valores por defecto se aplican automáticamente si no se proporcionan
4. Las validaciones se ejecutan tanto en frontend como backend
5. Todos los cambios quedan registrados con información del usuario