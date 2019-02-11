<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        // 'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$POWhp274QPlSDtmrKJvuDu3iXo7XfA3II3C1X4VnPcIiP.0tMar8y', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\User\UserProfile::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'surname' => $faker->lastName,
        'user_id' => function(){
            return factory(App\User::class)->create()->id;
        },
        'picture' => $faker->imageUrl(),
        'sexe' => $faker->title(),
        'birthdate' => $faker->dateTime(),
        'phone' => $faker->phoneNumber,
        'mobile' => $faker->phoneNumber,
        'description' => $faker->paragraph,
    ];
});


$factory->define(App\Models\User\UserAddress::class, function (Faker $faker) {
    return [
        'name' => $faker->streetName,
        'user_id' => function(){
            return factory(App\Models\User\UserProfile::class)->create()->user_id;
        },
        'city' => $faker->city,
        'street_1' => $faker->streetAddress,
        'zip' => $faker->postcode,
    ];
});
//
//$factory->define(App\Models\City::class, function (Faker $faker) {
//    return [
//        'name' => $faker->city,
//        'true_name' => $faker->city,
//        'zip' => $faker->randomDigit,
//    ];
//});

$factory->define(App\Models\School\School::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'street' => $faker->streetName,
        'zip' => $faker->randomDigit,
        'city' => $faker->city,
        'geolloc_lt' => $faker->latitude,
        'geolloc_lg' => $faker->longitude,

    ];
});

$factory->define(App\Models\Level\Level::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});


$factory->define(App\Models\Product\ProductColor::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        'code' => $faker->hexColor
    ];
});

$factory->define(App\Models\Product\ProductType::class, function (Faker $faker) {
    return [
        'type' => $faker->name,
        'description'=> $faker->sentence
    ];
});

$factory->define(App\Models\Product\ProductStatus::class, function (Faker $faker) {
    return [
        'name' => 'true',
    ];
});

$factory->define(App\Models\Product\Product::class, function (Faker $faker) {
    $name = $faker->name;
    return [
        'ean' => $faker->uuid,
        'code' => $faker->uuid,
        'name' => $name,
        'slug' => str_slug($name),
        'description' => $faker->paragraph,
        'type_id' => function () {
            return factory(App\Models\Product\ProductType::class)->create()->id;
        },
        'price' => 100,
        'quantity' => 100,
        'order_number' => $faker->uuid,
        'purchase_date' => $faker->dateTimeBetween('last month', '-20 days'),
        'status_id' => function () {
            return factory(App\Models\Product\ProductStatus::class)->create()->id;
        },
    ];
});

$factory->define(App\Models\Category\Category::class, function (Faker $faker) {
    $name = $faker->name;
    return [
        'name' => $name,
        'description' => $faker->paragraph,
        'slug'  =>str_slug($name),
        'image' => 'iuiuhi.jpg'
    ];
});

$factory->define(App\Models\Promotion\PromotionCode::class, function (Faker $faker) {
    return [
        'code' => str_random(10),
        'discount' => $faker->randomFloat(1, 1,20),
        'expiry_date'  => $faker->dateTimeBetween('this month', '+6 days'),
    ];
});

$factory->define(App\Models\Shipment\Shipment::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->paragraph,
        'is_free' => 0,
        'cost' => $faker->randomFloat(1, 1,20),
        'delivery_status_id' => 1,
    ];
});

$factory->define(App\Models\Order\Order::class, function (Faker $faker) {
    return [
        'reference' => $faker->uuid,
        'user_id' => function(){
            return factory(App\Models\User\UserProfile::class)->create()->user_id;
        },
        // 'promotion_code_id' => function(){
        //     return factory(App\Models\Promotion\PromotionCode::class)->create()->id;
        // },
        'address_id' => function(){
            return factory(App\Models\User\UserAddress::class)->create()->id;
        },
        'shipment_id' => function(){
            return factory(App\Models\Shipment\Shipment::class)->create()->id;
        },
        'discount' => '0',
        'total' => 100,
        'payment' => 'paypal',
        'comment' => 'paypal',
        'order_status_id' => random_int(1,4),
    ];
});
$factory->define(App\Models\Order\OrderProduct::class, function (Faker $faker) {
    $product =  factory(App\Models\Product\Product::class)->create();
    
    return [
        'order_id' => function(){
            return factory(App\Models\Order\Order::class)->create()->id;
        },
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => $product->price,
    ];
});

$factory->define(App\Models\TermsAndConditions::class, function (Faker $faker) {
    return [
        'description' => $faker->paragraph,
    ];
});

