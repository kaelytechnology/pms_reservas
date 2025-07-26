# Foods

## Descripción
Módulo para gestionar los alimentos disponibles en el sistema del hotel, incluyendo información nutricional, precios y categorización.

## Endpoints

### Base URL
```
/api/pms/foods
```

### Autenticación
Todos los endpoints requieren autenticación Bearer Token.

```http
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

## Operaciones CRUD

### 1. Listar Foods

**GET** `/api/pms/foods`

#### Parámetros de Consulta
| Parámetro | Tipo | Descripción | Ejemplo |
|-----------|------|-------------|----------|
| `search` | string | Búsqueda en nombre y descripción | `?search=pasta` |
| `sort_by` | string | Campo para ordenar | `?sort_by=name` |
| `sort_direction` | string | Dirección (asc/desc) | `?sort_direction=asc` |
| `per_page` | integer | Elementos por página (máx: 100) | `?per_page=20` |
| `page` | integer | Número de página | `?page=2` |
| `food_type_id` | integer | Filtro por tipo de comida | `?food_type_id=1` |
| `is_active` | boolean | Filtro por estado activo | `?is_active=true` |

#### Respuesta de Éxito (200)
```json
{
  "data": [
    {
      "id": 1,
      "name": "Pasta Carbonara",
      "description": "Pasta italiana con salsa carbonara tradicional",
      "food_type_id": 1,
      "price": 25.50,
      "calories": 650,
      "preparation_time": 20,
      "ingredients": "Pasta, huevos, panceta, queso parmesano, pimienta negra",
      "allergens": "Gluten, Huevos, Lácteos",
      "is_vegetarian": false,
      "is_vegan": false,
      "is_gluten_free": false,
      "is_active": true,
      "food_type": {
        "id": 1,
        "name": "Platos Principales",
        "description": "Platos principales del menú"
      },
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  ],
  "links": {
    "first": "http://localhost/api/pms/foods?page=1",
    "last": "http://localhost/api/pms/foods?page=4",
    "prev": null,
    "next": "http://localhost/api/pms/foods?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 4,
    "per_page": 15,
    "to": 15,
    "total": 58
  }
}
```

### 2. Crear Food

**POST** `/api/pms/foods`

#### Parámetros del Cuerpo
| Campo | Tipo | Requerido | Validación | Descripción |
|-------|------|-----------|------------|-------------|
| `name` | string | Sí | único, máx 100 chars | Nombre del alimento |
| `description` | text | No | máx 500 chars | Descripción del alimento |
| `food_type_id` | integer | Sí | existe en food_types | ID del tipo de comida |
| `price` | decimal | Sí | mín 0, máx 9999.99 | Precio del alimento |
| `calories` | integer | No | mín 0, máx 5000 | Calorías por porción |
| `preparation_time` | integer | No | mín 1, máx 300 | Tiempo de preparación en minutos |
| `ingredients` | text | No | máx 1000 chars | Lista de ingredientes |
| `allergens` | string | No | máx 200 chars | Alérgenos presentes |
| `is_vegetarian` | boolean | No | true/false | Es vegetariano |
| `is_vegan` | boolean | No | true/false | Es vegano |
| `is_gluten_free` | boolean | No | true/false | Libre de gluten |
| `is_active` | boolean | No | true/false | Estado activo (default: true) |

#### Ejemplo de Petición
```json
{
  "name": "Ensalada César",
  "description": "Ensalada fresca con lechuga romana, crutones y aderezo césar",
  "food_type_id": 2,
  "price": 18.00,
  "calories": 320,
  "preparation_time": 10,
  "ingredients": "Lechuga romana, crutones, queso parmesano, aderezo césar",
  "allergens": "Gluten, Lácteos, Huevos",
  "is_vegetarian": true,
  "is_vegan": false,
  "is_gluten_free": false,
  "is_active": true
}
```

#### Respuesta de Éxito (201)
```json
{
  "data": {
    "id": 2,
    "name": "Ensalada César",
    "description": "Ensalada fresca con lechuga romana, crutones y aderezo césar",
    "food_type_id": 2,
    "price": 18.00,
    "calories": 320,
    "preparation_time": 10,
    "ingredients": "Lechuga romana, crutones, queso parmesano, aderezo césar",
    "allergens": "Gluten, Lácteos, Huevos",
    "is_vegetarian": true,
    "is_vegan": false,
    "is_gluten_free": false,
    "is_active": true,
    "created_at": "2024-01-15T11:15:00.000000Z",
    "updated_at": "2024-01-15T11:15:00.000000Z"
  },
  "message": "Alimento creado exitosamente"
}
```

### 3. Mostrar Food

**GET** `/api/pms/foods/{id}`

### 4. Actualizar Food

**PUT** `/api/pms/foods/{id}`

### 5. Eliminar Food

**DELETE** `/api/pms/foods/{id}`

## Endpoints Especiales

### Exportar a CSV

**GET** `/api/pms/foods/export`

### Importar desde CSV/Excel

**POST** `/api/pms/foods/import`

## Filtros Dietéticos

El sistema permite filtrar alimentos por:
- Vegetarianos (`is_vegetarian=true`)
- Veganos (`is_vegan=true`)
- Libres de gluten (`is_gluten_free=true`)
- Por tipo de comida (`food_type_id`)
- Por rango de calorías
- Por tiempo de preparación

## Relaciones

### Food Type
Cada alimento debe estar asociado a un tipo de comida (entrada, plato principal, postre, etc.).

## Permisos Requeridos

| Acción | Permiso |
|--------|----------|
| Listar | `foods.view` |
| Crear | `foods.create` |
| Ver | `foods.view` |
| Actualizar | `foods.edit` |
| Eliminar | `foods.delete` |
| Exportar | `foods.export` |
| Importar | `foods.import` |

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

1. El nombre del alimento debe ser único en el sistema
2. Los precios se manejan con 2 decimales de precisión
3. Las calorías son opcionales pero recomendadas para información nutricional
4. Los alérgenos deben listarse claramente para seguridad del cliente
5. Los filtros dietéticos ayudan a personalizar menús
6. El tiempo de preparación ayuda en la planificación de cocina
7. Solo alimentos activos aparecen en menús públicos