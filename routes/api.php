<?php

use Illuminate\Support\Facades\Route;
use Kaely\PmsHotel\Http\Controllers\RoomRateRuleController;
use Kaely\PmsHotel\Http\Controllers\FoodController;
use Kaely\PmsHotel\Http\Controllers\FoodTypeController;
use Kaely\PmsHotel\Http\Controllers\DishController;
use Kaely\PmsHotel\Http\Controllers\DepartmentController;
use Kaely\PmsHotel\Http\Controllers\DecorationController;
use Kaely\PmsHotel\Http\Controllers\EventController;
use Kaely\PmsHotel\Http\Controllers\RestaurantController;
use Kaely\PmsHotel\Http\Controllers\DessertController;
use Kaely\PmsHotel\Http\Controllers\BeverageController;
use Kaely\PmsHotel\Http\Controllers\RoomChangeController;
use Kaely\PmsHotel\Http\Controllers\SpecialRequirementController;
use Kaely\PmsHotel\Http\Controllers\RestaurantAvailabilityController;
use Kaely\PmsHotel\Http\Controllers\RestaurantReservationController;
use Kaely\PmsHotel\Http\Controllers\ReservationController;
use Kaely\PmsHotel\Http\Controllers\CourtesyDinnerController;

/*
|--------------------------------------------------------------------------
| PMS Hotel API Routes
|--------------------------------------------------------------------------
|
| Here are the API routes for the PMS Hotel package. All routes are
| prefixed with 'api/pms' and require authentication.
|
*/

// Room Rate Rules
Route::prefix('room-rate-rules')->name('room_rate_rules.')->group(function () {
    Route::get('/', [RoomRateRuleController::class, 'index'])->name('index');
    Route::post('/', [RoomRateRuleController::class, 'store'])->name('store');
    Route::get('/export', [RoomRateRuleController::class, 'export'])->name('export');
    Route::post('/import', [RoomRateRuleController::class, 'import'])->name('import');
    Route::get('/classes', [RoomRateRuleController::class, 'getClasses'])->name('classes');
    Route::get('/{roomRateRule}', [RoomRateRuleController::class, 'show'])->name('show');
    Route::put('/{roomRateRule}', [RoomRateRuleController::class, 'update'])->name('update');
    Route::delete('/{roomRateRule}', [RoomRateRuleController::class, 'destroy'])->name('destroy');
});

// Foods
Route::prefix('foods')->name('foods.')->group(function () {
    Route::get('/', [FoodController::class, 'index'])->name('index');
    Route::post('/', [FoodController::class, 'store'])->name('store');
    Route::get('/export', [FoodController::class, 'export'])->name('export');
    Route::post('/import', [FoodController::class, 'import'])->name('import');
    Route::get('/{food}', [FoodController::class, 'show'])->name('show');
    Route::put('/{food}', [FoodController::class, 'update'])->name('update');
    Route::delete('/{food}', [FoodController::class, 'destroy'])->name('destroy');
});

// Food Types
Route::prefix('food-types')->name('food_types.')->group(function () {
    Route::get('/', [FoodTypeController::class, 'index'])->name('index');
    Route::post('/', [FoodTypeController::class, 'store'])->name('store');
    Route::get('/export', [FoodTypeController::class, 'export'])->name('export');
    Route::post('/import', [FoodTypeController::class, 'import'])->name('import');
    Route::get('/{foodType}', [FoodTypeController::class, 'show'])->name('show');
    Route::put('/{foodType}', [FoodTypeController::class, 'update'])->name('update');
    Route::delete('/{foodType}', [FoodTypeController::class, 'destroy'])->name('destroy');
});

// Dishes
Route::prefix('dishes')->name('dishes.')->group(function () {
    Route::get('/', [DishController::class, 'index'])->name('index');
    Route::post('/', [DishController::class, 'store'])->name('store');
    Route::get('/export', [DishController::class, 'export'])->name('export');
    Route::post('/import', [DishController::class, 'import'])->name('import');
    Route::get('/{dish}', [DishController::class, 'show'])->name('show');
    Route::put('/{dish}', [DishController::class, 'update'])->name('update');
    Route::delete('/{dish}', [DishController::class, 'destroy'])->name('destroy');
});

// Departments
Route::prefix('departments')->name('departments.')->group(function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('index');
    Route::post('/', [DepartmentController::class, 'store'])->name('store');
    Route::get('/export', [DepartmentController::class, 'export'])->name('export');
    Route::post('/import', [DepartmentController::class, 'import'])->name('import');
    Route::get('/{department}', [DepartmentController::class, 'show'])->name('show');
    Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
    Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');
});

// Decorations
Route::prefix('decorations')->name('decorations.')->group(function () {
    Route::get('/', [DecorationController::class, 'index'])->name('index');
    Route::post('/', [DecorationController::class, 'store'])->name('store');
    Route::get('/export', [DecorationController::class, 'export'])->name('export');
    Route::post('/import', [DecorationController::class, 'import'])->name('import');
    Route::get('/{decoration}', [DecorationController::class, 'show'])->name('show');
    Route::put('/{decoration}', [DecorationController::class, 'update'])->name('update');
    Route::delete('/{decoration}', [DecorationController::class, 'destroy'])->name('destroy');
});

// Events
Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::get('/export', [EventController::class, 'export'])->name('export');
    Route::post('/import', [EventController::class, 'import'])->name('import');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
    Route::put('/{event}', [EventController::class, 'update'])->name('update');
    Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');
});

// Restaurants
Route::prefix('restaurants')->name('restaurants.')->group(function () {
    Route::get('/', [RestaurantController::class, 'index'])->name('index');
    Route::post('/', [RestaurantController::class, 'store'])->name('store');
    Route::get('/export', [RestaurantController::class, 'export'])->name('export');
    Route::post('/import', [RestaurantController::class, 'import'])->name('import');
    Route::get('/{restaurant}', [RestaurantController::class, 'show'])->name('show');
    Route::put('/{restaurant}', [RestaurantController::class, 'update'])->name('update');
    Route::delete('/{restaurant}', [RestaurantController::class, 'destroy'])->name('destroy');
});

// Desserts
Route::prefix('desserts')->name('desserts.')->group(function () {
    Route::get('/', [DessertController::class, 'index'])->name('index');
    Route::post('/', [DessertController::class, 'store'])->name('store');
    Route::get('/export', [DessertController::class, 'export'])->name('export');
    Route::post('/import', [DessertController::class, 'import'])->name('import');
    Route::get('/{dessert}', [DessertController::class, 'show'])->name('show');
    Route::put('/{dessert}', [DessertController::class, 'update'])->name('update');
    Route::delete('/{dessert}', [DessertController::class, 'destroy'])->name('destroy');
});

// Beverages
Route::prefix('beverages')->name('beverages.')->group(function () {
    Route::get('/', [BeverageController::class, 'index'])->name('index');
    Route::post('/', [BeverageController::class, 'store'])->name('store');
    Route::get('/export', [BeverageController::class, 'export'])->name('export');
    Route::post('/import', [BeverageController::class, 'import'])->name('import');
    Route::get('/{beverage}', [BeverageController::class, 'show'])->name('show');
    Route::put('/{beverage}', [BeverageController::class, 'update'])->name('update');
    Route::delete('/{beverage}', [BeverageController::class, 'destroy'])->name('destroy');
});

// Room Changes
Route::prefix('room-changes')->name('room_changes.')->group(function () {
    Route::get('/', [RoomChangeController::class, 'index'])->name('index');
    Route::post('/', [RoomChangeController::class, 'store'])->name('store');
    Route::get('/export', [RoomChangeController::class, 'export'])->name('export');
    Route::post('/import', [RoomChangeController::class, 'import'])->name('import');
    Route::get('/{roomChange}', [RoomChangeController::class, 'show'])->name('show');
    Route::put('/{roomChange}', [RoomChangeController::class, 'update'])->name('update');
    Route::delete('/{roomChange}', [RoomChangeController::class, 'destroy'])->name('destroy');
});

// Special Requirements
Route::prefix('special-requirements')->name('special_requirements.')->group(function () {
    Route::get('/', [SpecialRequirementController::class, 'index'])->name('index');
    Route::post('/', [SpecialRequirementController::class, 'store'])->name('store');
    Route::get('/export', [SpecialRequirementController::class, 'export'])->name('export');
    Route::post('/import', [SpecialRequirementController::class, 'import'])->name('import');
    Route::get('/{specialRequirement}', [SpecialRequirementController::class, 'show'])->name('show');
    Route::put('/{specialRequirement}', [SpecialRequirementController::class, 'update'])->name('update');
    Route::delete('/{specialRequirement}', [SpecialRequirementController::class, 'destroy'])->name('destroy');
});

// Restaurant Availability
Route::prefix('restaurant-availability')->name('restaurant_availability.')->group(function () {
    Route::get('/', [RestaurantAvailabilityController::class, 'index'])->name('index');
    Route::post('/', [RestaurantAvailabilityController::class, 'store'])->name('store');
    Route::get('/export', [RestaurantAvailabilityController::class, 'export'])->name('export');
    Route::post('/import', [RestaurantAvailabilityController::class, 'import'])->name('import');
    Route::get('/{restaurantAvailability}', [RestaurantAvailabilityController::class, 'show'])->name('show');
    Route::put('/{restaurantAvailability}', [RestaurantAvailabilityController::class, 'update'])->name('update');
    Route::delete('/{restaurantAvailability}', [RestaurantAvailabilityController::class, 'destroy'])->name('destroy');
});

// Restaurant Reservations
Route::prefix('restaurant-reservations')->name('restaurant_reservations.')->group(function () {
    Route::get('/', [RestaurantReservationController::class, 'index'])->name('index');
    Route::post('/', [RestaurantReservationController::class, 'store'])->name('store');
    Route::get('/export', [RestaurantReservationController::class, 'export'])->name('export');
    Route::post('/import', [RestaurantReservationController::class, 'import'])->name('import');
    Route::get('/{restaurantReservation}', [RestaurantReservationController::class, 'show'])->name('show');
    Route::put('/{restaurantReservation}', [RestaurantReservationController::class, 'update'])->name('update');
    Route::delete('/{restaurantReservation}', [RestaurantReservationController::class, 'destroy'])->name('destroy');
});

// Reservations
Route::prefix('reservations')->name('reservations.')->group(function () {
    Route::get('/', [ReservationController::class, 'index'])->name('index');
    Route::post('/', [ReservationController::class, 'store'])->name('store');
    Route::get('/export', [ReservationController::class, 'export'])->name('export');
    Route::post('/import', [ReservationController::class, 'import'])->name('import');
    Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
    Route::put('/{reservation}', [ReservationController::class, 'update'])->name('update');
    Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy');
});

// Courtesy Dinners
Route::prefix('courtesy-dinners')->name('courtesy_dinners.')->group(function () {
    Route::get('/', [CourtesyDinnerController::class, 'index'])->name('index');
    Route::post('/', [CourtesyDinnerController::class, 'store'])->name('store');
    Route::get('/export', [CourtesyDinnerController::class, 'export'])->name('export');
    Route::post('/import', [CourtesyDinnerController::class, 'import'])->name('import');
    Route::get('/{courtesyDinner}', [CourtesyDinnerController::class, 'show'])->name('show');
    Route::put('/{courtesyDinner}', [CourtesyDinnerController::class, 'update'])->name('update');
    Route::delete('/{courtesyDinner}', [CourtesyDinnerController::class, 'destroy'])->name('destroy');
});