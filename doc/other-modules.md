# Otros Módulos del Sistema

Este documento cubre la documentación de los módulos restantes del PMS Hotel Package que siguen estructuras similares de CRUD.

---

## Beverages (Bebidas)

### Base URL: `/api/pms/beverages`

### Descripción
Gestión de bebidas disponibles en el hotel, incluyendo información de precios, contenido alcohólico y categorización.

### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|--------------|
| `name` | string | Nombre de la bebida (único, máx 100 chars) |
| `description` | text | Descripción de la bebida |
| `category` | string | Categoría (Vinos, Cervezas, Cócteles, etc.) |
| `price` | decimal | Precio por unidad |
| `alcohol_content` | decimal | Porcentaje de alcohol (0-100) |
| `volume_ml` | integer | Volumen en mililitros |
| `is_alcoholic` | boolean | Contiene alcohol |
| `is_active` | boolean | Estado activo |

### Ejemplo de Respuesta
```json
{
  "id": 1,
  "name": "Vino Tinto Reserva",
  "description": "Vino tinto de la casa, cosecha 2020",
  "category": "Vinos",
  "price": 35.00,
  "alcohol_content": 13.5,
  "volume_ml": 750,
  "is_alcoholic": true,
  "is_active": true
}
```

### Permisos: `beverages.{action}`

---

## Desserts (Postres)

### Base URL: `/api/pms/desserts`

### Descripción
Gestión de postres disponibles en el hotel, incluyendo información nutricional y alérgenos.

### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|--------------|
| `name` | string | Nombre del postre (único, máx 100 chars) |
| `description` | text | Descripción del postre |
| `price` | decimal | Precio del postre |
| `calories` | integer | Calorías por porción |
| `preparation_time` | integer | Tiempo de preparación en minutos |
| `allergens` | string | Alérgenos presentes |
| `is_sugar_free` | boolean | Libre de azúcar |
| `is_gluten_free` | boolean | Libre de gluten |
| `is_active` | boolean | Estado activo |

### Ejemplo de Respuesta
```json
{
  "id": 1,
  "name": "Tiramisú",
  "description": "Postre italiano tradicional con café y mascarpone",
  "price": 15.00,
  "calories": 420,
  "preparation_time": 30,
  "allergens": "Gluten, Lácteos, Huevos",
  "is_sugar_free": false,
  "is_gluten_free": false,
  "is_active": true
}
```

### Permisos: `desserts.{action}`

---

## Events (Eventos)

### Base URL: `/api/pms/events`

### Descripción
Gestión de eventos especiales que se pueden asociar a reservas de restaurante.

### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|--------------|
| `name` | string | Nombre del evento (único, máx 100 chars) |
| `description` | text | Descripción del evento |
| `event_type` | string | Tipo (Cumpleaños, Aniversario, Corporativo, etc.) |
| `price` | decimal | Precio adicional del evento |
| `duration_hours` | integer | Duración en horas |
| `min_people` | integer | Mínimo de personas |
| `max_people` | integer | Máximo de personas |
| `includes` | text | Qué incluye el evento |
| `is_active` | boolean | Estado activo |

### Ejemplo de Respuesta
```json
{
  "id": 1,
  "name": "Cena Romántica",
  "description": "Cena especial para parejas con ambiente romántico",
  "event_type": "Romántico",
  "price": 50.00,
  "duration_hours": 3,
  "min_people": 2,
  "max_people": 2,
  "includes": "Decoración, velas, música suave, postre especial",
  "is_active": true
}
```

### Permisos: `events.{action}`

---

## Decorations (Decoraciones)

### Base URL: `/api/pms/decorations`

### Descripción
Gestión de decoraciones disponibles para eventos y ocasiones especiales.

### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|--------------|
| `name` | string | Nombre de la decoración (único, máx 100 chars) |
| `description` | text | Descripción de la decoración |
| `decoration_type` | string | Tipo (Flores, Globos, Velas, etc.) |
| `price` | decimal | Precio de la decoración |
| `setup_time` | integer | Tiempo de montaje en minutos |
| `color_scheme` | string | Esquema de colores |
| `occasion` | string | Ocasión recomendada |
| `is_active` | boolean | Estado activo |

### Ejemplo de Respuesta
```json
{
  "id": 1,
  "name": "Decoración Romántica",
  "description": "Arreglo con velas, pétalos de rosa y flores",
  "decoration_type": "Romántica",
  "price": 25.00,
  "setup_time": 15,
  "color_scheme": "Rojo y Blanco",
  "occasion": "Aniversarios, Cenas Románticas",
  "is_active": true
}
```

### Permisos: `decorations.{action}`

---

## Departments (Departamentos)

### Base URL: `/api/pms/departments`

### Descripción
Gestión de departamentos del hotel para organización interna.

### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|--------------|
| `name` | string | Nombre del departamento (único, máx 100 chars) |
| `description` | text | Descripción del departamento |
| `code` | string | Código del departamento (único, máx 10 chars) |
| `manager_name` | string | Nombre del gerente |
| `phone` | string | Teléfono del departamento |
| `email` | string | Email del departamento |
| `location` | string | Ubicación física |
| `is_active` | boolean | Estado activo |

### Ejemplo de Respuesta
```json
{
  "id": 1,
  "name": "Restaurante",
  "description": "Departamento de servicios de restaurante",
  "code": "REST",
  "manager_name": "Carlos Rodríguez",
  "phone": "+1234567890",
  "email": "restaurant@hotel.com",
  "location": "Planta Baja",
  "is_active": true
}
```

### Permisos: `departments.{action}`

---

## Dishes (Platos)

### Base URL: `/api/pms/dishes`

### Descripción
Gestión de platos específicos del menú, diferentes a los alimentos generales.

### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|--------------|
| `name` | string | Nombre del plato (único, máx 100 chars) |
| `description` | text | Descripción del plato |
| `category` | string | Categoría del plato |
| `price` | decimal | Precio del plato |
| `ingredients` | text | Lista de ingredientes |
| `cooking_method` | string | Método de cocción |
| `spice_level` | string | Nivel de picante (Suave, Medio, Picante) |
| `chef_special` | boolean | Especialidad del chef |
| `is_active` | boolean | Estado activo |

### Permisos: `dishes.{action}`

---

## Special Requirements (Requerimientos Especiales)

### Base URL: `/api/pms/special-requirements`

### Descripción
Gestión de requerimientos especiales para huéspedes (alergias, dietas, etc.).

### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|--------------|
| `name` | string | Nombre del requerimiento (único, máx 100 chars) |
| `description` | text | Descripción detallada |
| `requirement_type` | string | Tipo (Alergia, Dieta, Médico, etc.) |
| `severity` | string | Severidad (Baja, Media, Alta, Crítica) |
| `instructions` | text | Instrucciones para el personal |
| `is_active` | boolean | Estado activo |

### Ejemplo de Respuesta
```json
{
  "id": 1,
  "name": "Alergia al Gluten",
  "description": "Huésped con intolerancia al gluten severa",
  "requirement_type": "Alergia",
  "severity": "Alta",
  "instructions": "Evitar todos los productos con gluten. Usar utensilios separados.",
  "is_active": true
}
```

### Permisos: `special_requirements.{action}`

---

## Room Changes (Cambios de Habitación)

### Base URL: `/api/pms/room-changes`

### Descripción
Gestión de cambios de habitación durante la estadía del huésped.

### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|--------------|
| `reservation_id` | integer | ID de la reserva (requerido) |
| `from_room` | string | Habitación original |
| `to_room` | string | Nueva habitación |
| `change_date` | date | Fecha del cambio |
| `change_time` | time | Hora del cambio |
| `reason` | string | Motivo del cambio |
| `additional_cost` | decimal | Costo adicional |
| `status` | string | Estado (pending, completed, cancelled) |
| `notes` | text | Notas adicionales |

### Permisos: `room_changes.{action}`

---

## Restaurant Availability (Disponibilidad de Restaurante)

### Base URL: `/api/pms/restaurant-availability`

### Descripción
Gestión de horarios y disponibilidad específica de restaurantes.

### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|--------------|
| `restaurant_id` | integer | ID del restaurante (requerido) |
| `date` | date | Fecha específica |
| `time_slot` | time | Horario disponible |
| `available_tables` | integer | Mesas disponibles |
| `max_capacity` | integer | Capacidad máxima |
| `is_available` | boolean | Está disponible |
| `special_event` | string | Evento especial si aplica |

### Permisos: `restaurant_availability.{action}`

---

## Courtesy Dinners (Cenas de Cortesía)

### Base URL: `/api/pms/courtesy-dinners`

### Descripción
Gestión de cenas de cortesía ofrecidas a huéspedes especiales.

### Campos Principales
| Campo | Tipo | Descripción |
|-------|------|--------------|
| `reservation_id` | integer | ID de la reserva (requerido) |
| `dinner_date` | date | Fecha de la cena |
| `dinner_time` | time | Hora de la cena |
| `restaurant_id` | integer | Restaurante asignado |
| `people_count` | integer | Número de personas |
| `menu_type` | string | Tipo de menú |
| `reason` | string | Motivo de la cortesía |
| `approved_by` | string | Aprobado por |
| `status` | string | Estado (pending, approved, completed) |
| `cost_value` | decimal | Valor de la cortesía |

### Permisos: `courtesy_dinners.{action}`

---

## Operaciones Comunes para Todos los Módulos

### Endpoints Estándar
- **GET** `/{module}` - Listar con paginación
- **POST** `/{module}` - Crear nuevo registro
- **GET** `/{module}/{id}` - Mostrar registro específico
- **PUT** `/{module}/{id}` - Actualizar registro
- **DELETE** `/{module}/{id}` - Eliminar registro
- **GET** `/{module}/export` - Exportar a CSV
- **POST** `/{module}/import` - Importar desde CSV/Excel

### Parámetros de Consulta Comunes
- `search` - Búsqueda en campos principales
- `sort_by` - Campo para ordenar
- `sort_direction` - Dirección del orden (asc/desc)
- `per_page` - Elementos por página (máx: 100)
- `page` - Número de página
- `is_active` - Filtro por estado activo

### Códigos de Estado HTTP
- **200** - Operación exitosa
- **201** - Recurso creado exitosamente
- **400** - Error de validación
- **401** - No autenticado
- **403** - Sin permisos
- **404** - Recurso no encontrado
- **422** - Error de validación de datos
- **500** - Error interno del servidor

### Estructura de Respuesta Estándar
```json
{
  "data": { /* objeto o array de datos */ },
  "message": "Mensaje de éxito",
  "links": { /* enlaces de paginación */ },
  "meta": { /* metadatos de paginación */ }
}
```

### Notas Importantes
1. Todos los módulos requieren autenticación Bearer Token
2. Los permisos siguen el patrón `{module}.{action}`
3. Los campos marcados como "único" no pueden duplicarse
4. Los campos booleanos tienen valores por defecto
5. Las validaciones se aplican tanto en frontend como backend
6. Todos los cambios quedan registrados con información del usuario