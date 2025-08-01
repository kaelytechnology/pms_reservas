# Documentación Técnica - PMS Hotel Package

## Instalación y Configuración

### Requisitos del Sistema
- PHP ^8.2
- Laravel ^12.0
- MySQL/PostgreSQL
- Composer
- `kaelytechnology/auth-package`

### Instalación

#### 1. Instalación via Composer
```bash
composer require kaely/pms_hotel
```

#### 2. Instalación del Package
```bash
php artisan pms-hotel:install
```

Este comando:
- Publica la configuración en `config/pms-hotel.php`
- Publica las migraciones en `database/migrations/`
- Publica los seeders en `database/seeders/`

#### 3. Ejecutar Migraciones
```bash
php artisan migrate
```

#### 4. Ejecutar Seeders
```bash
php artisan pms-hotel:seed
```

### Configuración

#### Archivo de Configuración: `config/pms-hotel.php`

```php
return [
    // Conexión de base de datos
    'database' => [
        'connection' => env('PMS_DB_CONNECTION', 'tenant'),
        'table_prefix' => env('PMS_TABLE_PREFIX', 'pms_'),
    ],
    
    // Integración con auth-package
    'auth_package_integration' => [
        'module_slug' => 'pms-hotel',
        'module_name' => 'PMS Hotel',
        'auto_register_permissions' => true,
        'auto_register_menus' => true,
    ],
    
    // Módulos del sistema
    'modules' => [
        'room_rate_rules' => [
            'name' => 'Room Rate Rules',
            'slug' => 'room-rate-rules',
            'permissions' => ['view', 'create', 'edit', 'delete', 'export', 'import'],
            'menu_icon' => 'fas fa-tags',
            'order' => 1,
        ],
        // ... otros módulos
    ],
];
```

#### Variables de Entorno

Agregar al archivo `.env`:
```env
# Configuración PMS Hotel
PMS_DB_CONNECTION=tenant
PMS_TABLE_PREFIX=pms_
```

### Configuración Multi-Tenant

El package está diseñado para trabajar en entornos multi-tenant:

1. **Conexión de Base de Datos**: Utiliza la conexión `tenant` por defecto
2. **Prefijo de Tablas**: Todas las tablas usan el prefijo `pms_`
3. **Aislamiento de Datos**: Los datos están aislados por tenant

---

## Arquitectura del Sistema

### Service Provider

El `PmsHotelServiceProvider` registra:
- Configuración del package
- Rutas API
- Comandos Artisan
- Políticas de autorización
- Migraciones y seeders

### Estructura de Directorios

```
src/
├── Console/
│   └── Commands/
│       ├── InstallPmsHotelCommand.php
│       ├── SeedPmsHotelCommand.php
│       └── GeneratePmsModulesCommand.php
├── Http/
│   ├── Controllers/
│   │   ├── Controller.php (abstract)
│   │   ├── ReservationController.php
│   │   ├── RestaurantReservationController.php
│   │   └── ...
│   └── Requests/
│       ├── ReservationRequest.php
│       ├── RestaurantReservationRequest.php
│       └── ...
├── Models/
│   ├── BaseModel.php
│   ├── Reservation.php
│   ├── RestaurantReservation.php
│   └── ...
├── Policies/
│   ├── ReservationPolicy.php
│   ├── RestaurantReservationPolicy.php
│   └── ...
├── Traits/
│   └── HasUserTracking.php
└── PmsHotelServiceProvider.php
```

---

## Modelos y Base de Datos

### BaseModel

Todos los modelos extienden de `BaseModel` que proporciona:

```php
abstract class BaseModel extends Model
{
    use HasFactory, SoftDeletes, HasUserTracking;
    
    protected $connection = 'tenant';
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    protected $hidden = [
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at',
    ];
    
    public function getRouteKeyName(): string
    {
        return 'id';
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
```

### Trait HasUserTracking

Proporciona seguimiento automático de usuarios:

```php
trait HasUserTracking
{
    protected static function bootHasUserTracking()
    {
        static::creating(function ($model) {
            $model->created_by = auth()->id();
        });
        
        static::updating(function ($model) {
            $model->updated_by = auth()->id();
        });
        
        static::deleting(function ($model) {
            $model->deleted_by = auth()->id();
        });
    }
}
```

### Relaciones Principales

#### Reservation (Reserva Principal)
```php
class Reservation extends BaseModel
{
    public function restaurantReservations()
    {
        return $this->hasMany(RestaurantReservation::class);
    }
    
    public function roomChanges()
    {
        return $this->hasMany(RoomChange::class);
    }
    
    public function courtesyDinners()
    {
        return $this->hasMany(CourtesyDinner::class);
    }
}
```

#### RestaurantReservation
```php
class RestaurantReservation extends BaseModel
{
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    // ... otras relaciones
}
```

---

## Sistema de Autorización

### Políticas (Policies)

Cada modelo tiene su política correspondiente:

```php
class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('reservations.view');
    }
    
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->can('reservations.view');
    }
    
    public function create(User $user): bool
    {
        return $user->can('reservations.create');
    }
    
    public function update(User $user, Reservation $reservation): bool
    {
        return $user->can('reservations.edit');
    }
    
    public function delete(User $user, Reservation $reservation): bool
    {
        return $user->can('reservations.delete');
    }
}
```

### Permisos

Cada módulo tiene permisos granulares:
- `{module}.view` - Ver registros
- `{module}.create` - Crear registros
- `{module}.edit` - Editar registros
- `{module}.delete` - Eliminar registros
- `{module}.export` - Exportar datos
- `{module}.import` - Importar datos

---

## Comandos Artisan

### InstallPmsHotelCommand

```bash
php artisan pms-hotel:install
```

Instala el package completo:
- Publica configuración
- Publica migraciones
- Publica seeders
- Muestra instrucciones de finalización

### SeedPmsHotelCommand

```bash
php artisan pms-hotel:seed
```

Ejecuta todos los seeders del package:
- Datos de ejemplo para desarrollo
- Configuraciones básicas
- Registros de prueba

### GeneratePmsModulesCommand

```bash
php artisan pms-hotel:generate-modules
```

Genera automáticamente:
- Controladores para nuevos módulos
- Modelos con relaciones
- Políticas de autorización
- Rutas API

---

## API y Rutas

### Middleware Aplicado

Todas las rutas API tienen:
- `api` - Middleware básico de API
- `auth:api` - Autenticación requerida

### Prefijo de Rutas

Todas las rutas están bajo el prefijo `/api/pms/`

### Estructura de Controladores

```php
abstract class Controller extends BaseController
{
    // Métodos comunes para todos los controladores
    
    protected function authorizeAction(string $action, $model = null)
    {
        $this->authorize($action, $model ?? $this->getModelClass());
    }
    
    abstract protected function getModelClass(): string;
}
```

### Validación de Requests

Cada endpoint tiene su Request class:

```php
class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // La autorización se maneja en el controlador
    }
    
    public function rules(): array
    {
        return [
            'confirmation_number' => 'required|string|max:50|unique:reservations,confirmation_number',
            'guest_name' => 'required|string|max:100',
            'guest_email' => 'required|email|max:100',
            // ... más reglas
        ];
    }
}
```

---

## Integración con Auth Package

### Registro Automático

El package se registra automáticamente en el sistema de autenticación:

```php
'auth_package_integration' => [
    'module_slug' => 'pms-hotel',
    'module_name' => 'PMS Hotel',
    'auto_register_permissions' => true,
    'auto_register_menus' => true,
],
```

### Permisos Automáticos

Se crean automáticamente todos los permisos definidos en la configuración.

### Menús Automáticos

Se generan menús automáticamente basados en:
- Configuración de módulos
- Permisos del usuario
- Orden especificado

---

## Exportación e Importación

### Exportación CSV

Todos los módulos soportan exportación:

```php
public function export(Request $request)
{
    $this->authorize('export', $this->getModelClass());
    
    $query = $this->getModelClass()::query();
    
    // Aplicar filtros de la request
    $this->applyFilters($query, $request);
    
    return Excel::download(
        new ModelExport($query),
        $this->getExportFilename()
    );
}
```

### Importación CSV/Excel

```php
public function import(Request $request)
{
    $this->authorize('import', $this->getModelClass());
    
    $request->validate([
        'file' => 'required|file|mimes:csv,xlsx,xls|max:10240'
    ]);
    
    $import = new ModelImport();
    Excel::import($import, $request->file('file'));
    
    return response()->json([
        'message' => 'Importación completada exitosamente',
        'imported' => $import->getRowCount(),
        'errors' => $import->getErrors()
    ]);
}
```

---

## Mejores Prácticas

### Desarrollo

1. **Seguir PSR-12** para estilo de código
2. **Usar Type Hints** en todos los métodos
3. **Documentar métodos** con PHPDoc
4. **Escribir tests** para funcionalidad crítica
5. **Validar datos** tanto en frontend como backend

### Seguridad

1. **Autorización granular** en cada endpoint
2. **Validación estricta** de datos de entrada
3. **Sanitización** de datos de salida
4. **Rate limiting** en APIs públicas
5. **Logging** de acciones sensibles

### Performance

1. **Eager loading** para relaciones
2. **Paginación** en listados
3. **Índices** en campos de búsqueda
4. **Cache** para datos estáticos
5. **Optimización** de queries

### Mantenimiento

1. **Versionado semántico** del package
2. **Migraciones reversibles**
3. **Seeders idempotentes**
4. **Documentación actualizada**
5. **Tests automatizados**

---

## Troubleshooting

### Problemas Comunes

#### Error de Conexión de Base de Datos
```
Illuminate\Database\QueryException: SQLSTATE[HY000] [1049] Unknown database
```

**Solución**: Verificar configuración de conexión `tenant` en `config/database.php`

#### Permisos No Encontrados
```
This action is unauthorized.
```

**Solución**: 
1. Ejecutar `php artisan pms-hotel:seed`
2. Verificar que el usuario tenga los permisos asignados
3. Limpiar cache: `php artisan cache:clear`

#### Tablas No Encontradas
```
Illuminate\Database\QueryException: SQLSTATE[42S02]: Base table or view not found
```

**Solución**: Ejecutar `php artisan migrate`

### Logs y Debugging

#### Habilitar Debug
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

#### Logs Específicos
```php
Log::channel('pms')->info('Reservation created', ['id' => $reservation->id]);
```

#### Debugging Queries
```php
DB::enableQueryLog();
// ... ejecutar queries
dd(DB::getQueryLog());
```

---

## Contribución

### Proceso de Desarrollo

1. Fork del repositorio
2. Crear branch feature: `git checkout -b feature/nueva-funcionalidad`
3. Commit cambios: `git commit -am 'Agregar nueva funcionalidad'`
4. Push al branch: `git push origin feature/nueva-funcionalidad`
5. Crear Pull Request

### Estándares de Código

- Seguir PSR-12
- Usar nombres descriptivos
- Comentar código complejo
- Escribir tests para nueva funcionalidad
- Actualizar documentación

### Testing

```bash
# Ejecutar tests
php artisan test

# Ejecutar tests con coverage
php artisan test --coverage

# Ejecutar tests específicos
php artisan test --filter ReservationTest
```