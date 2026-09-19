<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/sitemap.xml', function () {
    return response()->file(public_path('xml/sitemap.xml'), [
        'Content-Type' => 'application/xml'
    ]);
});

Route::group(['middleware' => 'defaultDeviceId'], function () {
    Route::group(['middleware' => 'configData'], function () {
        Route::get('/tool/generate-sitemap', [\Modules\Pages\Http\Controllers\SitemapController::class, 'generate']);
        Route::get('/', [\Modules\Pages\Http\Controllers\HomeController::class, 'index'])->name('page.home')->middleware('html.cache');

        Route::post('/load/calendar-month', [\Modules\Pages\Http\Controllers\AjaxController::class, 'calendarMonth'])->name('page.ajax.calendar.month')->middleware('html.cache');

        Route::get('{slug}.html', [\Modules\Pages\Http\Controllers\PostsController::class, 'show'])->name('page.post.show')->middleware('html.cache');
        Route::get('tim-kiem', [\Modules\Pages\Http\Controllers\PostsController::class, 'search'])->name('page.cate.search');
        Route::get('tag/{slug}', [\Modules\Pages\Http\Controllers\PostsController::class, 'tags'])->name('page.hashtag.index')->middleware('html.cache');

        Route::get('page/{slug}', [\Modules\Pages\Http\Controllers\PostsController::class, 'policy'])->name('page.policy.index')->middleware('html.cache');

        Route::get('/feed', [\Modules\Pages\Http\Controllers\FeedController::class, 'index'])->name('rss.feed');

        Route::get('lich-thang-{month}-{year}', [\Modules\Pages\Http\Controllers\CopesController::class, 'copeMonth'])->name('page.cope.show.month')->middleware('html.cache');
        Route::get('lich-ngay-{day}-{month}-{year}', [\Modules\Pages\Http\Controllers\CopesController::class, 'copeDay'])->name('page.cope.show.day')->middleware('html.cache');
        Route::get('lich-nam-{year}', [\Modules\Pages\Http\Controllers\CopesController::class, 'copeYear'])->name('page.cope.show.year')->middleware('html.cache');
        Route::get('xem-ngay-{slug}', [\Modules\Pages\Http\Controllers\CopesController::class, 'show'])->name('page.cope.show')->middleware('html.cache');

        Route::get('{slug}', [\Modules\Pages\Http\Controllers\CategoriesController::class, 'bridge'])->name('page.cate.index')->middleware('html.cache');
    });
});
