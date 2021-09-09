<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {

    Route::get('/', 'LoginController@index')
        ->name('admin.login.page');

    Route::group(['prefix' => 'login'], function () {
        Route::post('/post', 'LoginController@login')
            ->name('admin.login.post');

        Route::get('logout', 'LoginController@logout')
            ->name('admin.logout');
    });

    Route::group(['middleware' => ['auth:web', 'adminConfirm']], function () {
        Route::get('dashboard', 'IndexController@index')
            ->name('admin.dashboard');

        // Панель состояния (Устарела, заменяем на Заказы, смотри ниже)
        Route::group(['prefix' => 'panel'], function () {
            Route::get('/', 'StatePanelController@index')
                ->name('admin.statePanel.page');

            Route::get('get/products', 'StatePanelController@getProducts')
                ->name('admin.statePanel.products');

            Route::get('get/cart', 'StatePanelController@getShoppingCart')
                ->name('admin.statePanel.cart');
        });

        // Заказы
        Route::group(['prefix' => 'orders'], function () {

            Route::get('/', 'OrdersController@index')
                ->name('admin.orders.orders');

            Route::get('/viewOrders/{id}', 'OrdersController@viewOrders')
                ->name('admin.orders.viewOrders');

            Route::post('/sort_orders_table', 'OrdersController@sortOrdersTable');

            Route::post('/save/history', 'OrdersController@saveHistory');

            Route::get("/get/history", "OrdersController@getOrderHistoryByPage");
        });


        //  Категории
        Route::group(['prefix' => 'categories', 'namespace' => 'Categories'], function () {
            Route::get('/', 'CategoriesController@index')
                ->name('admin.categories.page');

            Route::post('add', 'CategoriesController@addCategory')
                ->name('admin.categories.add');

            Route::post('delete', 'CategoriesController@deleteCategory')
                ->name('admin.categories.delete');

            Route::post('change', 'CategoriesController@changeCategory')
                ->name('admin.categories.change');

            Route::get('getText', 'CategoriesController@getText')
                ->name('admin.categories.getText');
        });

        //  Потребности
        Route::group(['prefix' => 'accessories', 'namespace' => 'Accessories'], function () {
            Route::get('/', 'AccessoriesController@index')
                ->name('admin.accessories.page');

            Route::post('add', 'AccessoriesController@addAccessory')
                ->name('admin.accessories.add');

            Route::post('delete', 'AccessoriesController@deleteAccessory')
                ->name('admin.accessories.delete');

            Route::post('change', 'AccessoriesController@changeAccessory')
                ->name('admin.accessories.change');

            Route::get('get', 'AccessoriesController@getAccessoriesByCategory')
                ->name('admin.accessories.get');
        });

        //  Товары
        Route::group(['prefix' => 'products', 'namespace' => 'Products'], function () {
            Route::get('/', 'ProductsController@index')
                ->name('admin.products.page');

            Route::get('/pageNew', 'NewProductsController@indexNew')
                ->name('admin.products.pageNew');

            Route::get('/createNewProd', 'NewProductsController@createProd')
                ->name('admin.products.createProd');

            Route::post('/createNewProd', 'NewProductsController@createProdProcess')
                ->name('admin.products.createProdProcess');

            Route::get('/changeNewProd/{id}', 'NewProductsController@getProd')
                ->name('admin.products.changeNewProd');

            Route::post('/changeNewProd/{id}', 'NewProductsController@updateProduct')
                ->name('admin.products.updateProductNew');

            Route::post('/deleteProd', 'NewProductsController@deleteProd')
                ->name('admin.products.deleteNewProd');

            Route::get('/information', 'NewProductsController@information')
                ->name('admin.products.information');

            Route::get('/changeInformation/{id}', 'NewProductsController@changeInformation')
                ->name('admin.products.changeInformation');

            Route::post('/updateInformation/{id}', 'NewProductsController@updateInformation')
                ->name('admin.products.updateInformation');

            Route::get('/createInformation', 'NewProductsController@createInformation')
                ->name('admin.products.createInformation');

            Route::post('/saveInformation', 'NewProductsController@saveInformation')
                ->name('admin.products.saveInformation');

            Route::post('/deleteInformation', 'NewProductsController@deleteInformation')
                ->name('admin.products.deleteInformation');

            Route::get('/get', 'ProductsController@getProductForChange')
                ->name('admin.product.get');

            Route::get('/attributes', 'AttributesController@getAttributesForProduct')
                ->name('admin.product.get.attributes');

            Route::get('/get/attributes', 'AttributesController@getAttributesForChange')
                ->name('admin.product.attributes');

            Route::get('/sales', 'SalesController@getSales')
                ->name('admin.product.sales');

            Route::get('/get/sales', 'SalesController@getSalesForChange')
                ->name('admin.product.get.sales');

            Route::get('/get/sale', 'SalesController@getSale')
                ->name('admin.product.get.sale');

            Route::put('/set/sale', 'SalesController@setSale')
                ->name('admin.product.set.sale');

            Route::put('clear/sales', 'SalesController@clearSales')
                ->name('admin.product.clear.sales');

            Route::post('/addSale', 'NewProductsController@addSale')
                ->name('admin.product.add.sale');

            Route::post('/editSale', 'NewProductsController@editSale')
                ->name('admin.product.edit.sale');

            Route::post('/deleteSale', 'NewProductsController@deleteSale')
                ->name('admin.product.delete.sale');

            Route::get('/discountList', 'NewProductsController@discountList')
                ->name('admin.products.sales');

            Route::post('/addGlobalSale', 'NewProductsController@addGlobalSale')
                ->name('admin.product.add.global_sale');

            Route::post('/editGlobalSale', 'NewProductsController@editGlobalSale')
                ->name('admin.product.edit.global_sale');

            Route::post('/deleteGlobalSale', 'NewProductsController@deleteGlobalSale')
                ->name('admin.product.delete.global_sale');

            Route::get('/discountGlobalList', 'NewProductsController@discountGlobalList')
                ->name('admin.products.salesGlobal');

            Route::post('/addGroupSale', 'NewProductsController@addGroupSale')
                ->name('admin.product.add.group_sale');

            Route::post('/editGroupSale', 'NewProductsController@editGroupSale')
                ->name('admin.product.edit.group_sale');

            Route::post('/deleteGroupSale', 'NewProductsController@deleteGroupSale')
                ->name('admin.product.delete.group_sale');

            Route::get('/discountGroupList', 'NewProductsController@discountGroupList')
                ->name('admin.products.salesGroup');
            // Добавление
            Route::group(['prefix' => 'add'], function () {
                Route::post('/', 'ProductsController@addProduct')
                    ->name('admin.products.add');

                Route::post('attribute', 'AttributesController@addAttributes')
                    ->name('admin.products.add.attribute');

                Route::post('sale', 'SalesController@addSale')
                    ->name('admin.products.add.sale');

                Route::post('globalsale', 'SalesController@addGlobalSale')
                    ->name('admin.products.globalsale');
            });

            // Изменение
            Route::group(['prefix' => 'change'], function () {
                Route::post('/', 'ProductsController@changeProduct')
                    ->name('admin.products.change');

                Route::post('/attribute', 'AttributesController@changeAttribute')
                    ->name('admin.products.attribute.change');

                Route::post('/sale', 'SalesController@changeSale')
                    ->name('admin.products.sale.change');
            });

            // Удаление
            Route::group(['prefix' => 'delete'], function () {
                Route::delete('/', 'ProductsController@deleteProducts')
                    ->name('admin.product.delete');

                Route::delete('attributes', 'AttributesController@deleteAttributes')
                    ->name('admin.product.delete.attribute');

                Route::delete('sales', 'SalesController@deleteSales')
                    ->name('admin.product.delete.sales');
            });
        });

        //  Изображения
        Route::group(['prefix' => 'Images', 'namespace' => 'Products'], function () {
            Route::get('/', 'ImageController@index')
                ->name('admin.images.page');

            Route::get('/banner', 'ImageController@banner')
                ->name('admin.images.banner');

            Route::post('/add', 'ImageController@addImage')
                ->name('admin.addImage');

            Route::post('/addGlobal', 'ImageController@addGlobalImage')
                ->name('admin.addGlobalImage');

            Route::post('/changeImageActive', 'ImageController@changeImageActive')
                ->name('admin.changeImageActive');

            Route::post('/delete', 'ImageController@deleteImage')
                ->name('admin.deleteImage');

            Route::post('/deleteGlobal', 'ImageController@deleteGlobalImage')
                ->name('admin.deleteGlobalImage');

            Route::get('/getImages', 'ImageController@getImages')
                ->name('admin.images.get');
        });
        Route::get('/emailList', 'DistributionController@email')
            ->name('admin.distribution.emailList');

        Route::post('/addEmail', 'DistributionController@addEmail')
            ->name('admin.distribution.add.email');

        Route::post('/editEmail', 'DistributionController@editEmail')
            ->name('admin.distribution.edit.email');

        Route::post('/deleteEmail', 'DistributionController@deleteEmail')
            ->name('admin.distribution.delete.email');

        Route::post('/sendEmail', 'DistributionController@sendEmails')
            ->name('admin.distribution.send.email');

        Route::get('/phoneList', 'DistributionController@phone')
            ->name('admin.distribution.phoneList');

        Route::get('/groupList', 'DistributionController@groupList')
            ->name('admin.distribution.groupList');

        Route::post('/addEmailGroup', 'DistributionController@addEmailGroup')
            ->name('admin.distribution.add.emailGroup');

        Route::post('/editEmailGroup', 'DistributionController@editEmailGroup')
            ->name('admin.distribution.edit.emailGroup');

        Route::post('/deleteEmailGroup', 'DistributionController@deleteEmailGroup')
            ->name('admin.distribution.delete.emailGroup');

        Route::post('/addPhone', 'DistributionController@addPhone')
            ->name('admin.distribution.add.phone');

        Route::post('/editPhone', 'DistributionController@editPhone')
            ->name('admin.distribution.edit.phone');

        Route::post('/deletePhone', 'DistributionController@deletePhone')
            ->name('admin.distribution.delete.phone');

        Route::post('/sendPhone', 'DistributionController@sendPhones')
            ->name('admin.distribution.send.phone');

        Route::get('/groupPhoneList', 'DistributionController@groupPhoneList')
            ->name('admin.distribution.groupPhoneList');

        Route::post('/addPhoneGroup', 'DistributionController@addPhoneGroup')
            ->name('admin.distribution.add.phoneGroup');

        Route::post('/editPhoneGroup', 'DistributionController@editPhoneGroup')
            ->name('admin.distribution.edit.phoneGroup');

        Route::post('/deletePhoneGroup', 'DistributionController@deletePhoneGroup')
            ->name('admin.distribution.delete.phoneGroup');

        Route::get('/offer', 'DistributionController@offer')
            ->name('admin.distribution.offer');

        Route::post('/deleteOffer', 'DistributionController@deleteOffer')
            ->name('admin.distribution.offer.delete');
        Route::get('/settings', 'SettingsController@view_of_settings')->name('admin.layouts.settings');
        Route::post('/send_black_header', 'SettingsController@send_black_line_to_data_base');
        Route::get('/black_header_edit', 'SettingsController@view_of_edition_black_header')->name('admin.layouts.change_header_content');
    });


});
