# Food Types

## Descripción
Módulo para gestionar los tipos de alimentos que categorizan los diferentes elementos del menú (entradas, platos principales, postres, etc.).

## Endpoints

### Base URL
```
/api/pms/food-types
```

### Autenticación
Todos los endpoints requieren autenticación Bearer Token.

```http
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Operaciones CRUD

### 1. Listar Food Types

**GET** `/api/pms/food-types`

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
      "name": "Platos Principales",
      "description": "Platos principales del menú del restaurante",
      "order": 2,
      "color": "#FF6B35",
      "icon": "utensils",
      "is_active": true,
      "foods_count": 15,
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    },
    {
      "id": 2,
      "name": "Entradas",
      "description": "Aperitivos y entradas para comenzar la comida",
      "order": 1,
      "color": "#4ECDC4",
      "icon": "leaf",
      "is_active": true,
      "foods_count": 8,
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost/api/pms/food-types?page=1",
    "last": "http://localhost/api/pms/food-types?page=2",
    "prev": null,
    "next": "http://localhost/api/pms/food-types?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 2,
    "per_page": 15,
    "to": 15,
    "total": 18
  }
}
```

### 2. Crear Food Type

**POST** `/api/pms/food-types`

#### Parámetros del Cuerpo
| Campo | Tipo | Requerido | Validación | Descripción |
|-------|------|-----------|------------|-------------|
| `name` | string | Sí | único, máx 100 chars | Nombre del tipo de comida |
| `description` | text | No | máx 500 chars | Descripción del tipo |
| `order` | integer | No | mín 1, máx 100 | Orden de visualización |
| `color` | string | No | formato hexadecimal | Color para la interfaz |
| `icon` | string | No | máx 50 chars | Icono para la interfaz |
| `is_active` | boolean | No | true/false | Estado activo (default: true) |

#### Ejemplo de Petición
```json
{
  "name": "Postres",
  "description": "Dulces y postres para finalizar la comida",
  "order": 3,
  "color": "#FFE66D",
  "icon": "birthday-cake",
  "is_active": true
}
```

#### Respuesta de Éxito (201)
```json
{
  "data": {
    "id": 3,
    "name": "Postres",
    "description": "Dulces y postres para finalizar la comida",
    "order": 3,
    "color": "#FFE66D",
    "icon": "birthday-cake",
    "is_active": true,
    "foods_count": 0,
    "created_at": "2024-01-15T11:15:00.000000Z",
    "updated_at": "2024-01-15T11:15:00.000000Z"
  },
  "message": "Tipo de comida creado exitosamente"
}
```

### 3. Mostrar Food Type

**GET** `/api/pms/food-types/{id}`

#### Respuesta de Éxito (200)
```json
{
  "data": {
    "id": 1,
    "name": "Platos Principales",
    "description": "Platos principales del menú del restaurante",
    "order": 2,
    "color": "#FF6B35",
    "icon": "utensils",
    "is_active": true,
    "foods_count": 15,
    "foods": [
      {
        "id": 1,
        "name": "Pasta Carbonara",
        "price": 25.50,
        "is_active": true
      },
      {
        "id": 2,
        "name": "Salmón a la Plancha",
        "price": 32.00,
        "is_active": true
      }
    ],
    "created_at": "2024-01-15T10:30:00.000000Z",
    "updated_at": "2024-01-15T10:30:00.000000Z"
  }
}
```

### 4. Actualizar Food Type

**PUT** `/api/pms/food-types/{id}`

Utiliza los mismos parámetros que la creación.

### 5. Eliminar Food Type

**DELETE** `/api/pms/food-types/{id}`

#### Respuesta de Éxito (200)
```json
{
  "message": "Tipo de comida eliminado exitosamente"
}
```

#### Respuesta de Error (422)
```json
{
  "message": "No se puede eliminar el tipo de comida porque tiene alimentos asociados",
  "errors": {
    "foods": ["Existen 15 alimentos asociados a este tipo"]
  }
}
```

## Endpoints Especiales

### Exportar a CSV

**GET** `/api/pms/food-types/export`

#### Parámetros de Consulta
| Parámetro | Tipo | Descripción |
|-----------|------|--------------|
| `search` | string | Filtro de búsqueda |
| `is_active` | boolean | Filtro por estado |

### Importar desde CSV/Excel

**POST** `/api/pms/food-types/import`

#### Parámetros
| Campo | Tipo | Requerido | Validación |
|-------|------|-----------|------------|
| `file` | file | Sí | CSV/Excel, máx 10MB |

## Ordenamiento

Los tipos de comida se pueden ordenar usando el campo `order`. Esto afecta:
- El orden de aparición en menús
- La secuencia en formularios de selección
- La organización en reportes

## Personalización Visual

### Colores
- Formato hexadecimal (#RRGGBB)
- Usado en interfaces para identificación visual
- Colores sugeridos por categoría:
  - Entradas: #4ECDC4 (Verde agua)
  - Platos Principales: #FF6B35 (Naranja)
  - Postres: #FFE66D (Amarillo)
  - Bebidas: #A8E6CF (Verde claro)

### Iconos
- Compatibles con Font Awesome
- Ejemplos comunes:
  - `leaf` - Entradas/Ensaladas
  - `utensils` - Platos principales
  - `birthday-cake` - Postres
  - `coffee` - Bebidas

## Relaciones

### Foods
Cada tipo de comida puede tener múltiples alimentos asociados. La eliminación de un tipo está restringida si tiene alimentos asociados.

## Validaciones Especiales

### Eliminación
- No se puede eliminar un tipo que tenga alimentos asociados
- Se debe reasignar o eliminar primero los alimentos

### Orden
- Los valores de orden deben ser únicos
- Se recomienda usar incrementos de 10 para facilitar reordenamientos

## Permisos Requeridos

| Acción | Permiso |
|--------|----------|
| Listar | `food_types.view` |
| Crear | `food_types.create` |
| Ver | `food_types.view` |
| Actualizar | `food_types.edit` |
| Eliminar | `food_types.delete` |
| Exportar | `food_types.export` |
| Importar | `food_types.import` |

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

1. El nombre del tipo debe ser único en el sistema
2. El campo `order` determina la secuencia de visualización
3. Los colores e iconos mejoran la experiencia de usuario
4. Solo tipos activos aparecen en formularios de selección
5. La eliminación está protegida por integridad referencial
6. Se incluye contador de alimentos asociados para información
7. Los tipos inactivos mantienen sus alimentos pero no aparecen en nuevas selecciones