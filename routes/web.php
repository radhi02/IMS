<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserControllers;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ItemdieController;
use App\Http\Controllers\Admin\AnnexureController;
use App\Http\Controllers\Admin\MaterialCategoryController;
use App\Http\Controllers\Admin\RawMaterialController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SalesOrderController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\ManufactureController;
use App\Http\Controllers\Admin\RejectedMOController;
use App\Http\Controllers\Admin\JobCardController;
use App\Http\Controllers\Admin\DemandNoteController;
use App\Http\Controllers\Admin\IssueMaterialController;
use App\Http\Controllers\Admin\StockMaterialController;
use App\Http\Controllers\Admin\InventoryMaterialController;
use App\Http\Controllers\Admin\ConsumptionController;
use App\Http\Controllers\Admin\QualityCheckingController;
use App\Http\Controllers\Admin\InvoiceController;

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

// Route::get('/', function () {
//     if(Auth::check()) {
//        redirect()->route('home');
//     } else {
//         return view('auth.login');
//     }
// });

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::group(['middleware'=>'auth', 'prevent-back-history'], function()  
{  
    Route::resource('/department', DepartmentController::class); 
    Route::post('department_destroy', [DepartmentController::class, 'destroy']);
    Route::post('/statusdepartment', 'App\Http\Controllers\Admin\DepartmentController@Status')->name('department.status');
   
    Route::resource('/materialcategory', MaterialCategoryController::class); 
    Route::post('materialcategory_destroy', [MaterialCategoryController::class, 'destroy']);
    Route::post('/statusmaterialcategory', 'App\Http\Controllers\Admin\MaterialCategoryController@Status')->name('materialcategory.status');
   
    Route::resource('/category', CategoryController::class); 
    Route::post('category_destroy', [CategoryController::class, 'destroy']);
    Route::post('/statuscategory', 'App\Http\Controllers\Admin\CategoryController@Status')->name('category.status');
   
    Route::resource('/subcategory', SubCategoryController::class); 
    Route::post('subcategory_destroy', [SubCategoryController::class, 'destroy']);
    Route::post('/statussubcategory', 'App\Http\Controllers\Admin\SubCategoryController@Status')->name('subcategory.status');
    Route::post('/fetchSubCategory', 'App\Http\Controllers\Admin\SubCategoryController@fetchSubCategory')->name('fetchSubCategory');

    Route::resource('/customer', CustomerController::class); 
    Route::post('customer_destroy', [CustomerController::class, 'destroy']);
    Route::post('/statuscustomer', 'App\Http\Controllers\Admin\CustomerController@Status')->name('customer.status');
   
    Route::resource('/vendor', VendorController::class);
    Route::post('/statusvendor', 'App\Http\Controllers\Admin\VendorController@Status')->name('vendor.status');
    Route::post('vendor_destroy', [VendorController::class, 'destroy']);
   
    Route::resource('/unit', UnitController::class);
    Route::post('unit_destroy', [UnitController::class, 'destroy']);
    Route::post('/statusunit', 'App\Http\Controllers\Admin\UnitController@Status')->name('unit.status');
   
    Route::resource('/lead', LeadController::class);
    Route::post('lead_destroy', [LeadController::class, 'destroy']);
    Route::post('/statuslead', 'App\Http\Controllers\Admin\LeadController@Status')->name('lead.status');

    Route::resource('/rawmaterial', RawMaterialController::class);
    Route::post('rawmaterial_destroy', [RawMaterialController::class, 'destroy']);
    Route::post('/statusrawmaterial', 'App\Http\Controllers\Admin\RawMaterialController@Status')->name('rawmaterial.status');
    Route::post('/fetchRawMaterial', 'App\Http\Controllers\Admin\RawMaterialController@fetchRawMaterial')->name('fetchRawMaterial');

    Route::resource('/product', ProductController::class);
    Route::post('product_destroy', [ProductController::class, 'destroy']);
    Route::post('/statusproduct', 'App\Http\Controllers\Admin\ProductController@Status')->name('product.status');
    Route::post('/addRawMaterial', 'App\Http\Controllers\Admin\ProductController@addRawMaterial')->name('addRawMaterial');

    Route::resource('/salesorder', SalesOrderController::class);
    Route::post('salesorder_destroy', [SalesOrderController::class, 'destroy']);
    Route::post('/statussalesorder', 'App\Http\Controllers\Admin\SalesOrderController@Status')->name('salesorder.status');
    Route::post('/addProduct', 'App\Http\Controllers\Admin\SalesOrderController@addProduct')->name('addProduct');
    Route::post('/fetchCustomerAddress', 'App\Http\Controllers\Admin\SalesOrderController@fetchCustomerAddress')->name('fetchCustomerAddress');
    
    Route::resource('/purchase', PurchaseController::class);
    Route::post('/fetchVendorAddress', 'App\Http\Controllers\Admin\PurchaseController@fetchVendorAddress')->name('fetchVendorAddress');
    Route::post('purchase_destroy', [PurchaseController::class, 'destroy']);
    Route::post('/statuspurchase', 'App\Http\Controllers\Admin\PurchaseController@Status')->name('purchase.status');
    Route::post('/fetchMaterialRequirement', 'App\Http\Controllers\Admin\PurchaseController@fetchMaterialRequirement')->name('fetchMaterialRequirement');
    Route::get('/downloadPurchaseInvoice/{id?}',[App\Http\Controllers\Admin\PurchaseController::class, 'downloadPurchaseInvoice'])->name('purchase.download');

    Route::get('/jobcard/{id}',[App\Http\Controllers\Admin\SalesOrderController::class, 'createJobCard'])->name('salesorder.jobcard');
    Route::post('/addToBOM', 'App\Http\Controllers\Admin\SalesOrderController@addToBOM')->name('addToBOM');
    
    Route::resource('/manufacture', ManufactureController::class);
    Route::post('/makeListing', 'App\Http\Controllers\Admin\ManufactureController@makeListing')->name('makeListing');
    
    Route::resource('/demandnote', DemandNoteController::class);
    Route::get('/downloadDemandNote/{id?}',[App\Http\Controllers\Admin\DemandNoteController::class, 'downloadDemandNote'])->name('demandnote.download');
    Route::get('/demandnote/showDemand/{id}',[App\Http\Controllers\Admin\DemandNoteController::class, 'showSingleDemandNote'])->name('demandnote.showDemand');
    Route::get('/redemandnote/{id}',[App\Http\Controllers\Admin\DemandNoteController::class, 'recreate'])->name('demandnote.recreate');
    Route::post('/redemandnote/restore/',[App\Http\Controllers\Admin\DemandNoteController::class, 'restore'])->name('demandnote.restore');

    Route::resource('/rejectedmo', RejectedMOController::class);
    Route::post('/rejectedmo/approveProduct',[App\Http\Controllers\Admin\RejectedMOController::class, 'approveProduct'])->name('rejectedmo.approveProduct');

    Route::resource('/issuematerial', IssueMaterialController::class);
    Route::get('/downloadIssueNote/{id?}',[App\Http\Controllers\Admin\IssueMaterialController::class, 'downloadIssueNote'])->name('issuematerial.download');
    
    Route::resource('/stockmaterial', StockMaterialController::class);

    Route::resource('/materialinventory', InventoryMaterialController::class);

    Route::resource('/consumption', ConsumptionController::class);
    Route::get('/downloadconsumptionNote/{id?}',[App\Http\Controllers\Admin\ConsumptionController::class, 'downloadconsumptionNote'])->name('consumption.download');

    Route::resource('/qualitychecking', QualityCheckingController::class);
    Route::post('/qualitychecking/updatestock',[App\Http\Controllers\Admin\QualityCheckingController::class, 'updateProductStock'])->name('qualitychecking.updatestock');
    
    Route::resource('/invoice', InvoiceController::class);
    Route::post('/fetchProductQun', 'App\Http\Controllers\Admin\InvoiceController@fetchProductQun')->name('fetchProductQun');
    Route::get('/downloadInvoice/{id?}',[App\Http\Controllers\Admin\InvoiceController::class, 'downloadInvoice'])->name('invoice.download');
    Route::post('/fetchSalesOrderList', 'App\Http\Controllers\Admin\InvoiceController@fetchSalesOrderList')->name('fetchSalesOrderList');
    Route::post('/fetchSalesOrderData', 'App\Http\Controllers\Admin\InvoiceController@fetchSalesOrderData')->name('fetchSalesOrderData');
    Route::post('/invoice/storeinvoicepayment/',[App\Http\Controllers\Admin\InvoiceController::class, 'storeInvoicePayment'])->name('invoice.storeinvoicepayment');
    Route::get('/invoice/createInvoicePayment/{id}',[App\Http\Controllers\Admin\InvoiceController::class, 'createInvoicePayment'])->name('invoice.createInvoicePayment');

    Route::resource('/user','App\Http\Controllers\Admin\UserControllers');
    Route::post('user_destroy', [UserControllers::class, 'destroy']);
    Route::post('/statusUser', 'App\Http\Controllers\Admin\UserControllers@Status')->name('User.status');
    Route::post('/deleteUserImage', 'App\Http\Controllers\Admin\UserControllers@deleteUserImage')->name('User.deleteImg');

    Route::resource('/role','App\Http\Controllers\Admin\RoleController');
    Route::post('role_destroy', [RoleController::class, 'destroy']);
    Route::post('/statusRole', 'App\Http\Controllers\Admin\RoleController@Status')->name('role.status');
    
    Route::resource('/bank','App\Http\Controllers\Admin\BankController');
    Route::post('bank_destroy', [BankController::class, 'destroy']);
    Route::post('/statusBank', 'App\Http\Controllers\Admin\BankController@Status')->name('bank.status');
    
    Route::post('/company/store', 'App\Http\Controllers\Admin\CompanyController@store')->name('company.store');
    Route::get('/company/create', 'App\Http\Controllers\Admin\CompanyController@create')->name('company.create');
    // Route::resource('/company','App\Http\Controllers\Admin\CompanyController');
    // Route::post('company_destroy', [BankController::class, 'destroy']);
    // Route::post('/statusBank', 'App\Http\Controllers\Admin\BankController@Status')->name('company.status');

    Route::get('/Module/create/',[App\Http\Controllers\Admin\Module\ModuleController::class, 'Create'])->name('Module.new.creates');
    Route::post('/Module/Store/',[App\Http\Controllers\Admin\Module\ModuleController::class, 'store'])->name('Module.store');
    Route::get('/Module/{id}',[App\Http\Controllers\Admin\Module\ModuleController::class, 'index'])->name('Module.permission');
    Route::post('/Module/permission/',[App\Http\Controllers\Admin\Module\ModuleController::class, 'GivePermission'])->name('Module.GivePermission');

    Route::post('/account/get_countries', 'App\Http\Controllers\HomeController@Countries')->name('all.get_countries');
    Route::post('/account/get_state', 'App\Http\Controllers\HomeController@get_state')->name('all.get_state');
    Route::post('/account/get_city', 'App\Http\Controllers\HomeController@get_city')->name('all.get_city');

    Route::post('/fetchNotifications', 'App\Http\Controllers\HomeController@fetchNotifications')->name('fetchNotifications');
    Route::post('/mark-as-read', 'App\Http\Controllers\HomeController@markNotification')->name('markNotification');
    Route::get('/notifications/show', 'App\Http\Controllers\HomeController@showNotifications')->name('notifications.show');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
