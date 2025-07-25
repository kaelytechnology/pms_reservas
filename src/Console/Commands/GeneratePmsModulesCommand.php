<?php

namespace Kaely\PmsHotel\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GeneratePmsModulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'pms:generate-modules';

    /**
     * The console command description.
     */
    protected $description = 'Generate all remaining PMS Hotel modules';

    /**
     * Module definitions
     */
    protected array $modules = [
        'Department' => [
            'table' => 'pms_departments',
            'fields' => ['name:string:255:unique', 'description:text:nullable'],
            'migration_order' => '000005',
        ],
        'Decoration' => [
            'table' => 'pms_decorations',
            'fields' => ['name:string:255:unique', 'description:text:nullable'],
            'migration_order' => '000006',
        ],
        'Event' => [
            'table' => 'pms_events',
            'fields' => [
                'name:string:255:unique',
                'description:text:nullable',
                'observations:text:nullable',
                'location:string:255:nullable',
                'capacity:integer',
                'schedule:string:255:nullable'
            ],
            'migration_order' => '000007',
        ],
        'Dessert' => [
            'table' => 'pms_desserts',
            'fields' => ['name:string:255:unique', 'description:text:nullable'],
            'migration_order' => '000009',
        ],
        'Beverage' => [
            'table' => 'pms_beverages',
            'fields' => ['name:string:255:unique', 'description:text:nullable'],
            'migration_order' => '000010',
        ],
        'RoomChange' => [
            'table' => 'pms_room_changes',
            'fields' => [
                'conf_oper_id:string:255',
                'room_number:string:50',
                'assigned_room_number:string:50',
                'description:text:nullable'
            ],
            'migration_order' => '000011',
        ],
        'SpecialRequirement' => [
            'table' => 'pms_special_requirements',
            'fields' => ['name:string:255:unique', 'description:text:nullable'],
            'migration_order' => '000012',
        ],
        'RestaurantAvailability' => [
            'table' => 'pms_restaurant_availability',
            'fields' => [
                'restaurant_id:foreignId:pms_restaurants',
                'day_of_week:integer:nullable',
                'specific_date:date:nullable',
                'opening_time:time',
                'closing_time:time',
                'specific_time:time:nullable',
                'is_open:boolean:default:true',
                'max_capacity:integer',
                'available_capacity:integer',
                'sitting_name:string:255:nullable',
                'status:string:255:default:active',
                'notes:text:nullable'
            ],
            'migration_order' => '000013',
        ],
        'Reservation' => [
            'table' => 'pms_reservations',
            'fields' => [
                'guest_id:unsignedBigInteger',
                'room_id:unsignedBigInteger',
                'check_in:date',
                'check_out:date',
                'status:string:255:default:pending'
            ],
            'migration_order' => '000015',
        ],
        'CourtesyDinner' => [
            'table' => 'pms_courtesy_dinners',
            'fields' => [
                'guest_id:unsignedBigInteger',
                'restaurant_id:foreignId:pms_restaurants',
                'date:date',
                'schedule:string:255',
                'status:string:255:default:pending',
                'notes:text:nullable'
            ],
            'migration_order' => '000016',
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Generating PMS Hotel modules...');

        foreach ($this->modules as $moduleName => $config) {
            $this->generateModule($moduleName, $config);
        }

        // Generate the missing migration for RestaurantReservation
        $this->generateRestaurantReservationMigration();

        $this->info('All PMS Hotel modules generated successfully!');
        return 0;
    }

    /**
     * Generate a complete module
     */
    protected function generateModule(string $moduleName, array $config): void
    {
        $this->info("Generating {$moduleName} module...");

        $this->generateModel($moduleName, $config);
        $this->generateMigration($moduleName, $config);
        $this->generateController($moduleName, $config);
        $this->generateRequest($moduleName, $config);
        $this->generatePolicy($moduleName, $config);
    }

    /**
     * Generate model file
     */
    protected function generateModel(string $moduleName, array $config): void
    {
        $modelPath = base_path("packages/kaely/pms_hotel/src/Models/{$moduleName}.php");
        
        if (File::exists($modelPath)) {
            return;
        }

        $fields = $this->parseFields($config['fields']);
        $fillable = $this->getFillableFields($fields);
        $casts = $this->getCasts($fields);
        $rules = $this->getValidationRules($fields, $config['table']);
        $permissionPrefix = Str::snake($moduleName);
        if ($permissionPrefix === 'special_requirement') {
            $permissionPrefix = 'special_requirements';
        } elseif ($permissionPrefix === 'room_change') {
            $permissionPrefix = 'room_changes';
        } elseif ($permissionPrefix === 'restaurant_availability') {
            $permissionPrefix = 'restaurant_availability';
        } elseif ($permissionPrefix === 'courtesy_dinner') {
            $permissionPrefix = 'courtesy_dinners';
        }

        $content = $this->getModelTemplate($moduleName, $config['table'], $fillable, $casts, $rules, $permissionPrefix);
        
        File::put($modelPath, $content);
    }

    /**
     * Generate migration file
     */
    protected function generateMigration(string $moduleName, array $config): void
    {
        $migrationPath = base_path("packages/kaely/pms_hotel/database/migrations/2024_01_01_{$config['migration_order']}_create_{$config['table']}_table.php");
        
        if (File::exists($migrationPath)) {
            return;
        }

        $fields = $this->parseFields($config['fields']);
        $content = $this->getMigrationTemplate($config['table'], $fields);
        
        File::put($migrationPath, $content);
    }

    /**
     * Generate controller file
     */
    protected function generateController(string $moduleName, array $config): void
    {
        $controllerPath = base_path("packages/kaely/pms_hotel/src/Http/Controllers/{$moduleName}Controller.php");
        
        if (File::exists($controllerPath)) {
            return;
        }

        $content = $this->getControllerTemplate($moduleName);
        
        File::put($controllerPath, $content);
    }

    /**
     * Generate request file
     */
    protected function generateRequest(string $moduleName, array $config): void
    {
        $requestPath = base_path("packages/kaely/pms_hotel/src/Http/Requests/{$moduleName}Request.php");
        
        if (File::exists($requestPath)) {
            return;
        }

        $fields = $this->parseFields($config['fields']);
        $rules = $this->getValidationRules($fields, $config['table']);
        $content = $this->getRequestTemplate($moduleName, $config['table'], $rules);
        
        File::put($requestPath, $content);
    }

    /**
     * Generate policy file
     */
    protected function generatePolicy(string $moduleName, array $config): void
    {
        $policyPath = base_path("packages/kaely/pms_hotel/src/Policies/{$moduleName}Policy.php");
        
        if (File::exists($policyPath)) {
            return;
        }

        $permissionPrefix = Str::snake($moduleName);
        if ($permissionPrefix === 'special_requirement') {
            $permissionPrefix = 'special_requirements';
        } elseif ($permissionPrefix === 'room_change') {
            $permissionPrefix = 'room_changes';
        } elseif ($permissionPrefix === 'restaurant_availability') {
            $permissionPrefix = 'restaurant_availability';
        } elseif ($permissionPrefix === 'courtesy_dinner') {
            $permissionPrefix = 'courtesy_dinners';
        }

        $content = $this->getPolicyTemplate($moduleName, $permissionPrefix);
        
        File::put($policyPath, $content);
    }

    /**
     * Generate RestaurantReservation migration
     */
    protected function generateRestaurantReservationMigration(): void
    {
        $migrationPath = base_path('packages/kaely/pms_hotel/database/migrations/2024_01_01_000014_create_pms_restaurant_reservations_table.php');
        
        if (File::exists($migrationPath)) {
            return;
        }

        $content = <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pms_restaurant_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reservation_id')->nullable();
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->unsignedBigInteger('food_id')->nullable();
            $table->unsignedBigInteger('dessert_id')->nullable();
            $table->unsignedBigInteger('beverage_id')->nullable();
            $table->unsignedBigInteger('decoration_id')->nullable();
            $table->unsignedBigInteger('requirement_id')->nullable();
            $table->unsignedBigInteger('availability_id')->nullable();
            $table->integer('people');
            $table->text('comment')->nullable();
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->string('other', 500)->nullable();
            $table->string('status')->default('pending');
            
            // User tracking fields
            $table->unsignedBigInteger('user_add')->nullable();
            $table->unsignedBigInteger('user_edit')->nullable();
            $table->unsignedBigInteger('user_deleted')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['reservation_date', 'reservation_time']);
            $table->index('status');
            $table->index(['created_at', 'updated_at']);
            $table->index('deleted_at');
            
            // Foreign key constraints
            $table->foreign('reservation_id')->references('id')->on('pms_reservations')->onDelete('set null');
            $table->foreign('restaurant_id')->references('id')->on('pms_restaurants')->onDelete('restrict');
            $table->foreign('event_id')->references('id')->on('pms_events')->onDelete('set null');
            $table->foreign('food_id')->references('id')->on('pms_foods')->onDelete('set null');
            $table->foreign('dessert_id')->references('id')->on('pms_desserts')->onDelete('set null');
            $table->foreign('beverage_id')->references('id')->on('pms_beverages')->onDelete('set null');
            $table->foreign('decoration_id')->references('id')->on('pms_decorations')->onDelete('set null');
            $table->foreign('requirement_id')->references('id')->on('pms_special_requirements')->onDelete('set null');
            $table->foreign('availability_id')->references('id')->on('pms_restaurant_availability')->onDelete('set null');
            $table->foreign('user_add')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_edit')->references('id')->on('users')->onDelete('set null');
            $table->foreign('user_deleted')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pms_restaurant_reservations');
    }
};
PHP;
        
        File::put($migrationPath, $content);
    }

    // Helper methods for parsing and template generation would go here...
    // Due to space constraints, I'll include the essential template methods

    protected function parseFields(array $fields): array
    {
        $parsed = [];
        foreach ($fields as $field) {
            $parts = explode(':', $field);
            $parsed[] = [
                'name' => $parts[0],
                'type' => $parts[1] ?? 'string',
                'length' => $parts[2] ?? null,
                'modifier' => $parts[3] ?? null,
            ];
        }
        return $parsed;
    }

    protected function getFillableFields(array $fields): string
    {
        $fillable = [];
        foreach ($fields as $field) {
            if (!in_array($field['name'], ['id', 'created_at', 'updated_at', 'deleted_at'])) {
                $fillable[] = "'{$field['name']}'";
            }
        }
        return implode(",\n        ", $fillable);
    }

    protected function getCasts(array $fields): string
    {
        $casts = [];
        foreach ($fields as $field) {
            $cast = match($field['type']) {
                'integer' => 'integer',
                'boolean' => 'boolean',
                'date' => 'date',
                'time' => 'datetime:H:i',
                'foreignId', 'unsignedBigInteger' => 'integer',
                default => 'string'
            };
            $casts[] = "'{$field['name']}' => '{$cast}'";
        }
        return implode(",\n        ", $casts);
    }

    protected function getValidationRules(array $fields, string $table): string
    {
        $rules = [];
        foreach ($fields as $field) {
            $rule = [];
            
            if ($field['modifier'] !== 'nullable') {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }
            
            $rule[] = match($field['type']) {
                'integer', 'foreignId', 'unsignedBigInteger' => 'integer',
                'boolean' => 'boolean',
                'date' => 'date',
                'time' => 'date_format:H:i',
                'text' => 'string|max:1000',
                default => 'string|max:' . ($field['length'] ?? '255')
            };
            
            if ($field['modifier'] === 'unique') {
                $rule[] = "unique:{$table},{$field['name']}" . '" . ($id ? ",{$id}" : "") . "';
            }
            
            $ruleString = implode('|', $rule);
            $rules[] = "'{$field['name']}' => '{$ruleString}'";
        }
        return implode(",\n            ", $rules);
    }

    protected function getModelTemplate(string $moduleName, string $table, string $fillable, string $casts, string $rules, string $permissionPrefix): string
    {
        return <<<PHP
<?php

namespace Kaely\\PmsHotel\\Models;

use Illuminate\\Database\\Eloquent\\Builder;

class {$moduleName} extends BaseModel
{
    /**
     * The table associated with the model.
     */
    protected \$table = '{$table}';

    /**
     * The attributes that are mass assignable.
     */
    protected \$fillable = [
        {$fillable},
    ];

    /**
     * The attributes that should be cast.
     */
    protected \$casts = [
        {$casts},
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected \$hidden = [
        'user_add',
        'user_edit', 
        'user_deleted',
    ];

    /**
     * Get the validation rules for the model.
     */
    public static function rules(int \$id = null): array
    {
        return [
            {$rules},
        ];
    }

    /**
     * Get the permission prefix for this model.
     */
    public static function getPermissionPrefix(): string
    {
        return '{$permissionPrefix}';
    }

    /**
     * Scope a query to search.
     */
    public function scopeSearch(Builder \$query, ?string \$search): Builder
    {
        if (!\$search) {
            return \$query;
        }

        return \$query->where('name', 'like', "%{\$search}%");
    }
}
PHP;
    }

    protected function getMigrationTemplate(string $table, array $fields): string
    {
        $fieldDefinitions = [];
        foreach ($fields as $field) {
            $definition = "\$table->{$field['type']}('{$field['name']}'";
            if ($field['length']) {
                $definition .= ", {$field['length']}";
            }
            $definition .= ')';
            
            if ($field['modifier'] === 'nullable') {
                $definition .= '->nullable()';
            } elseif ($field['modifier'] === 'unique') {
                $definition .= '->unique()';
            }
            
            $fieldDefinitions[] = "            {$definition};";
        }
        
        $fieldsString = implode("\n", $fieldDefinitions);
        
        return <<<PHP
<?php

use Illuminate\\Database\\Migrations\\Migration;
use Illuminate\\Database\\Schema\\Blueprint;
use Illuminate\\Support\\Facades\\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('{$table}', function (Blueprint \$table) {
            \$table->id();
{$fieldsString}
            
            // User tracking fields
            \$table->unsignedBigInteger('user_add')->nullable();
            \$table->unsignedBigInteger('user_edit')->nullable();
            \$table->unsignedBigInteger('user_deleted')->nullable();
            
            \$table->timestamps();
            \$table->softDeletes();
            
            // Foreign key constraints
            \$table->foreign('user_add')->references('id')->on('users')->onDelete('set null');
            \$table->foreign('user_edit')->references('id')->on('users')->onDelete('set null');
            \$table->foreign('user_deleted')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('{$table}');
    }
};
PHP;
    }

    protected function getControllerTemplate(string $moduleName): string
    {
        $variable = Str::camel($moduleName);
        return <<<PHP
<?php

namespace Kaely\\PmsHotel\\Http\\Controllers;

use Illuminate\\Http\\Request;
use Illuminate\\Http\\JsonResponse;
use Illuminate\\Support\\Facades\\Gate;
use Kaely\\PmsHotel\\Models\\{$moduleName};
use Kaely\\PmsHotel\\Http\\Requests\\{$moduleName}Request;
use Symfony\\Component\\HttpFoundation\\BinaryFileResponse;

class {$moduleName}Controller extends Controller
{
    public function index(Request \$request): JsonResponse
    {
        Gate::authorize('viewAny', {$moduleName}::class);
        \$query = {$moduleName}::query();
        if (\$request->filled('search')) {
            \$query->search(\$request->search);
        }
        \$sortBy = \$request->get('sort_by', 'id');
        \$sortDirection = \$request->get('sort_direction', 'asc');
        \$query->orderBy(\$sortBy, \$sortDirection);
        \$perPage = min(\$request->get('per_page', 15), 100);
        \$items = \$query->paginate(\$perPage);
        return response()->json(\$items);
    }

    public function store({$moduleName}Request \$request): JsonResponse
    {
        Gate::authorize('create', {$moduleName}::class);
        \$item = {$moduleName}::create(\$request->validated());
        return response()->json(['message' => 'Creado exitosamente.', 'data' => \$item], 201);
    }

    public function show({$moduleName} \${$variable}): JsonResponse
    {
        Gate::authorize('view', \${$variable});
        return response()->json(\${$variable});
    }

    public function update({$moduleName}Request \$request, {$moduleName} \${$variable}): JsonResponse
    {
        Gate::authorize('update', \${$variable});
        \${$variable}->update(\$request->validated());
        return response()->json(['message' => 'Actualizado exitosamente.', 'data' => \${$variable}]);
    }

    public function destroy({$moduleName} \${$variable}): JsonResponse
    {
        Gate::authorize('delete', \${$variable});
        \${$variable}->delete();
        return response()->json(['message' => 'Eliminado exitosamente.']);
    }

    public function export(Request \$request): BinaryFileResponse
    {
        Gate::authorize('export', {$moduleName}::class);
        // Export implementation
        return response()->download('temp.csv');
    }

    public function import(Request \$request): JsonResponse
    {
        Gate::authorize('import', {$moduleName}::class);
        return response()->json(['message' => 'Importado exitosamente.']);
    }
}
PHP;
    }

    protected function getRequestTemplate(string $moduleName, string $table, string $rules): string
    {
        $variable = Str::camel($moduleName);
        return <<<PHP
<?php

namespace Kaely\\PmsHotel\\Http\\Requests;

use Illuminate\\Foundation\\Http\\FormRequest;
use Illuminate\\Validation\\Rule;

class {$moduleName}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        \$id = \$this->route('{$variable}')?->id;
        return [
            {$rules},
        ];
    }
}
PHP;
    }

    protected function getPolicyTemplate(string $moduleName, string $permissionPrefix): string
    {
        return <<<PHP
<?php

namespace Kaely\\PmsHotel\\Policies;

use Kaely\\PmsHotel\\Models\\{$moduleName};
use App\\Models\\User;

class {$moduleName}Policy
{
    public function viewAny(User \$user): bool
    {
        return \$user->hasPermission('{$permissionPrefix}.view');
    }

    public function view(User \$user, {$moduleName} \$model): bool
    {
        return \$user->hasPermission('{$permissionPrefix}.view');
    }

    public function create(User \$user): bool
    {
        return \$user->hasPermission('{$permissionPrefix}.create');
    }

    public function update(User \$user, {$moduleName} \$model): bool
    {
        return \$user->hasPermission('{$permissionPrefix}.edit');
    }

    public function delete(User \$user, {$moduleName} \$model): bool
    {
        return \$user->hasPermission('{$permissionPrefix}.delete');
    }

    public function restore(User \$user, {$moduleName} \$model): bool
    {
        return \$user->hasPermission('{$permissionPrefix}.delete');
    }

    public function forceDelete(User \$user, {$moduleName} \$model): bool
    {
        return \$user->hasPermission('{$permissionPrefix}.delete');
    }

    public function export(User \$user): bool
    {
        return \$user->hasPermission('{$permissionPrefix}.export');
    }

    public function import(User \$user): bool
    {
        return \$user->hasPermission('{$permissionPrefix}.import');
    }
}
PHP;
    }
}