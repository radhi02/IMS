<?php   
    $routeHome = ['home'];
    $routePeople = ['customer.index','customer.create','customer.edit','customer.show','customer.update','customer.destroy', 'user.index','user.create','user.edit','user.show','user.update','user.destroy', 'vendor.index','vendor.create','vendor.edit','vendor.show','vendor.update','vendor.destroy'];
    $routePlaces = ['department.index','department.create','department.edit','department.show','department.update','department.destroy'];
    $routeCategory = ['unit.index','unit.create','unit.edit','unit.show','unit.update','unit.destroy', 'subcategory.index','subcategory.create','subcategory.edit','subcategory.show','subcategory.update','subcategory.destroy', 'category.index','category.create','category.edit','category.show','category.update','category.destroy', 'materialcategory.index','materialcategory.create','materialcategory.edit','materialcategory.show','materialcategory.update','materialcategory.destroy'];
    $routePermission = ['role.index','role.create','role.edit','role.show','role.update','role.destroy','Module.permission','Module.new.creates'];
    $routeItems = ['rawmaterial.index','rawmaterial.create','rawmaterial.edit','rawmaterial.show','rawmaterial.update','rawmaterial.destroy','product.index','product.create','product.edit','product.show','product.update','product.destroy'];
    $routeSales = ['salesorder.index','salesorder.create','salesorder.edit','salesorder.show','salesorder.update','salesorder.destroy'];
    $routeManufacture = ['manufacture.index','manufacture.create','manufacture.edit','manufacture.show','manufacture.update','manufacture.destroy'];
    $routeRejectedMO = ['rejectedmo.index','rejectedmo.create','rejectedmo.edit','rejectedmo.show','rejectedmo.update','rejectedmo.destroy'];
    $routePurchase = ['purchase.index','purchase.create','purchase.edit','purchase.show','purchase.update','purchase.destroy'];
    $routeIssueMaterial = ['demandnote.index','demandnote.create','demandnote.edit','demandnote.show','demandnote.update','demandnote.destroy','issuematerial.index','issuematerial.create','issuematerial.edit','issuematerial.show','issuematerial.update','issuematerial.destroy'];
    $routeStockRawMaterial = ['stockmaterial.index','stockmaterial.create','stockmaterial.edit','stockmaterial.show','stockmaterial.update','stockmaterial.destroy'];
    $routeInventoryMaterial = ['materialinventory.index','materialinventory.create','materialinventory.edit','materialinventory.show','materialinventory.update','materialinventory.destroy'];
    $routeConsumption = ['consumption.index','consumption.create','consumption.edit','consumption.show','consumption.update','consumption.destroy'];
    $routeQuality = ['qualitychecking.index','qualitychecking.create','qualitychecking.edit','qualitychecking.show','qualitychecking.update','qualitychecking.destroy'];
    $routeInvoice = ['invoice.index','invoice.create','invoice.edit','invoice.show','invoice.update','invoice.destroy','invoice.download','invoice.storeinvoicepayment','invoice.createInvoicePayment'];
    $routeSetting = ['bank.index','bank.create','bank.edit','bank.show','bank.update','bank.destroy','company.create'];
    $className = "active subdrop";
?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li>
                    <a href="{{route('home')}}" class=<?php if(in_array(Route::currentRouteName(),$routeHome)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/dashboard.svg')}}" alt="img"><span> Dashboard </span> </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routePeople)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/users1.svg')}}"
                            alt="img"><span> People</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('customer.index')}}" class="{{(Route::currentRouteName()=='customer.index')?'active':''}}">Customer List</a></li>
                        <li><a href="{{route('customer.create')}}" class="{{(Route::currentRouteName()=='customer.create' || Route::currentRouteName()=='customer.edit' || Route::currentRouteName()=='customer.show' || Route::currentRouteName()=='customer.update')?'active':''}}">New Customer </a></li>
                        <li><a href="{{route('user.index')}}" class="{{(Route::currentRouteName()=='user.index')?'active':''}}">User List</a></li>
                        <li><a href="{{route('user.create')}}" class="{{(Route::currentRouteName()=='user.create' || Route::currentRouteName()=='user.edit' || Route::currentRouteName()=='user.show' || Route::currentRouteName()=='user.update')?'active':''}}">New User </a></li>
                        <li><a href="{{route('vendor.index')}}" class="{{(Route::currentRouteName()=='vendor.index')?'active':''}}">Vendor List</a></li>
                        <li><a href="{{route('vendor.create')}}" class="{{(Route::currentRouteName()=='vendor.create' ||Route::currentRouteName()=='vendor.edit' || Route::currentRouteName()=='vendor.show' || Route::currentRouteName()=='vendor.update')?'active':''}}">New Vendor</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routePlaces)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/places.svg')}}"
                            alt="img"><span> Places</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('department.index')}}" class="{{(Route::currentRouteName()=='department.index')?'active':''}}">Department List</a></li>
                        <li><a href="{{route('department.create')}}" class="{{(Route::currentRouteName()=='department.create' || Route::currentRouteName()=='department.edit' || Route::currentRouteName()=='department.show' || Route::currentRouteName()=='department.update')?'active':''}}">New Department </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeCategory)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/items-to-be-opened-today.svg')}}"
                            alt="img"><span>Category</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('unit.index')}}" class="{{(Route::currentRouteName()=='unit.index')?'active':''}}">Unit List</a></li>
                        <li><a href="{{route('unit.create')}}" class="{{(Route::currentRouteName()=='unit.create' || Route::currentRouteName()=='unit.edit' || Route::currentRouteName()=='unit.show' || Route::currentRouteName()=='unit.update')?'active':''}}">New Unit </a></li>
                        <li><a href="{{route('category.index')}}" class="{{(Route::currentRouteName()=='category.index')?'active':''}}">Category List</a></li>
                        <li><a href="{{route('category.create')}}" class="{{(Route::currentRouteName()=='category.create' || Route::currentRouteName()=='category.edit' || Route::currentRouteName()=='category.show' || Route::currentRouteName()=='category.update')?'active':''}}">New Category </a></li>
                        <li><a href="{{route('subcategory.index')}}" class="{{(Route::currentRouteName()=='subcategory.index')?'active':''}}">Sub Category List</a></li>
                        <li><a href="{{route('subcategory.create')}}" class="{{(Route::currentRouteName()=='subcategory.create' || Route::currentRouteName()=='subcategory.edit' || Route::currentRouteName()=='subcategory.show' || Route::currentRouteName()=='subcategory.update')?'active':''}}">New Sub Category </a></li>
                        <li><a href="{{route('materialcategory.index')}}" class="{{(Route::currentRouteName()=='materialcategory.index')?'active':''}}">Material Category List</a></li>
                        <li><a href="{{route('materialcategory.create')}}" class="{{(Route::currentRouteName()=='materialcategory.create' || Route::currentRouteName()=='materialcategory.edit' || Route::currentRouteName()=='materialcategory.show' || Route::currentRouteName()=='materialcategory.update')?'active':''}}">New Material Category </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routePermission)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/authority-user.svg')}}"
                            alt="img"><span>Permission</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('role.index')}}" class="{{(Route::currentRouteName()=='role.index' || Route::currentRouteName()=='Module.permission' )?'active':''}}">Role List</a></li>
                        <li><a href="{{route('role.create')}}" class="{{(Route::currentRouteName()=='role.create' || Route::currentRouteName()=='role.edit' || Route::currentRouteName()=='role.show' || Route::currentRouteName()=='role.update')?'active':''}}">New Role </a></li>
                        <li><a href="{{route('Module.new.creates')}}" class="{{(Route::currentRouteName()=='Module.new.creates')?'active':''}}">New Module</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeItems)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/product.svg')}}"
                            alt="img"><span>Item</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('rawmaterial.index')}}" class="{{(Route::currentRouteName()=='rawmaterial.index')?'active':''}}">Raw Material List</a></li>
                        <li><a href="{{route('rawmaterial.create')}}" class="{{(Route::currentRouteName()=='rawmaterial.create' || Route::currentRouteName()=='rawmaterial.edit' || Route::currentRouteName()=='rawmaterial.show' || Route::currentRouteName()=='rawmaterial.update')?'active':''}}">New Raw Material </a></li>
                        <li><a href="{{route('product.index')}}" class="{{(Route::currentRouteName()=='product.index')}}">Product List</a></li>
                        <li><a href="{{route('product.create')}}" class="{{(Route::currentRouteName()=='product.create' || Route::currentRouteName()=='product.edit' || Route::currentRouteName()=='product.show' || Route::currentRouteName()=='product.update')?'active':''}}">New Product</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeStockRawMaterial)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/stock.svg')}}"
                            alt="img"><span>Stock</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('stockmaterial.index')}}" class="{{(Route::currentRouteName()=='stockmaterial.index' || Route::currentRouteName()=='stockmaterial.create' || Route::currentRouteName()=='stockmaterial.edit' || Route::currentRouteName()=='stockmaterial.show' || Route::currentRouteName()=='stockmaterial.update')?'active':''}}">Raw Material Stock</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeSales)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/sales1.svg')}}"
                            alt="img"><span>Sales</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('salesorder.index')}}" class="{{(Route::currentRouteName()=='salesorder.index')?'active':''}}">Sales List</a></li>
                        <li><a href="{{route('salesorder.create')}}" class="{{(Route::currentRouteName()=='salesorder.create' || Route::currentRouteName()=='salesorder.edit' || Route::currentRouteName()=='salesorder.show' || Route::currentRouteName()=='salesorder.update')?'active':''}}">New Sale </a></li>
                    </ul>
                </li>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeManufacture)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/production-1.svg')}}"
                            alt="img"><span>Manufacture</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('manufacture.index')}}" class="{{( Route::currentRouteName()=='manufacture.index' || Route::currentRouteName()=='manufacture.create' || Route::currentRouteName()=='manufacture.edit' || Route::currentRouteName()=='manufacture.show' || Route::currentRouteName()=='manufacture.update')?'active':''}}">Manufacturing List</a></li>
                        <li><a href="{{route('rejectedmo.index')}}" class="{{( Route::currentRouteName()=='rejectedmo.index' || Route::currentRouteName()=='rejectedmo.create' || Route::currentRouteName()=='rejectedmo.edit' || Route::currentRouteName()=='rejectedmo.show' || Route::currentRouteName()=='rejectedmo.update')?'active':''}}">Rejected List</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeIssueMaterial)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/quotation1.svg')}}"
                            alt="img"><span>Issue Material</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('demandnote.index')}}" class="{{(Route::currentRouteName()=='demandnote.create' || Route::currentRouteName()=='demandnote.index' || Route::currentRouteName()=='demandnote.edit' || Route::currentRouteName()=='demandnote.show' || Route::currentRouteName()=='demandnote.update')?'active':''}}">Demand Note List</a></li>
                        <li><a href="{{route('issuematerial.index')}}" class="{{( Route::currentRouteName()=='issuematerial.index' || Route::currentRouteName()=='issuematerial.create' || Route::currentRouteName()=='issuematerial.edit' || Route::currentRouteName()=='issuematerial.show' || Route::currentRouteName()=='issuematerial.update')?'active':''}}">Issue Note List</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeConsumption)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/trolley-svgrepo-com.svg')}}"
                            alt="img"><span>Consumption</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('consumption.index')}}" class="{{(Route::currentRouteName()=='consumption.index' || Route::currentRouteName()=='consumption.create' || Route::currentRouteName()=='consumption.edit' || Route::currentRouteName()=='consumption.show' || Route::currentRouteName()=='consumption.update')?'active':''}}">WIP Manufacturing Order</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeQuality)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/inspection-icon.svg')}}"
                            alt="img"><span>Quality Checking</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('qualitychecking.index')}}" class="{{(Route::currentRouteName()=='qualitychecking.index' || Route::currentRouteName()=='qualitychecking.create' || Route::currentRouteName()=='qualitychecking.edit' || Route::currentRouteName()=='qualitychecking.show' || Route::currentRouteName()=='qualitychecking.update')?'active':''}}">Check Manufactured Order</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeInvoice)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/invoice.svg')}}"
                            alt="img"><span>Sales Invoice</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('invoice.index')}}" class="{{(Route::currentRouteName()=='invoice.index' || Route::currentRouteName()=='Module.permission' || Route::currentRouteName()=='invoice.download' || Route::currentRouteName()=='invoice.storeinvoicepayment' || Route::currentRouteName()=='invoice.createInvoicePayment')?'active':''}}">Invoice List</a></li>
                        <li><a href="{{route('invoice.create')}}" class="{{(Route::currentRouteName()=='invoice.create' || Route::currentRouteName()=='invoice.edit' || Route::currentRouteName()=='invoice.show' || Route::currentRouteName()=='invoice.update')?'active':''}}">New Invoice Order </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routePurchase)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/purchase1.svg')}}"
                            alt="img"><span>Purchase Order</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('purchase.index')}}" class="{{(Route::currentRouteName()=='purchase.index' || Route::currentRouteName()=='Module.permission' || Route::currentRouteName()=='materialinventory.index' || Route::currentRouteName()=='materialinventory.create' || Route::currentRouteName()=='materialinventory.edit' || Route::currentRouteName()=='materialinventory.show' || Route::currentRouteName()=='materialinventory.update')?'active':''}}">Purchase List</a></li>
                        <li><a href="{{route('purchase.create')}}" class="{{(Route::currentRouteName()=='role.create' || Route::currentRouteName()=='purchase.edit' || Route::currentRouteName()=='purchase.show' || Route::currentRouteName()=='purchase.update')?'active':''}}">New Purchase Order </a></li>
                    </ul>
                </li>
                <!-- <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeInventoryMaterial)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/stock.svg')}}"
                            alt="img"><span>Purchase Inventory</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('materialinventory.index')}}" class="{{(Route::currentRouteName()=='materialinventory.index' || Route::currentRouteName()=='materialinventory.create' || Route::currentRouteName()=='materialinventory.edit' || Route::currentRouteName()=='materialinventory.show' || Route::currentRouteName()=='materialinventory.update')?'active':''}}">Raw Material Inventory</a></li>
                    </ul>
                </li> -->
                <li class="submenu">
                    <a href="javascript:void(0);" class=<?php if(in_array(Route::currentRouteName(),$routeSetting)) echo $className; ?> ><img src="{{URL::asset('admin_asset/img/icons/settings.svg')}}"
                            alt="img"><span>Setting</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('bank.index')}}" class="{{(Route::currentRouteName()=='bank.index' || Route::currentRouteName()=='bank.create' || Route::currentRouteName()=='bank.edit' || Route::currentRouteName()=='bank.show' || Route::currentRouteName()=='bank.update')?'active':''}}">Bank Details</a></li>
                        <li><a href="{{route('company.create')}}" class="{{(Route::currentRouteName()=='company.create')?'active':''}}">Company Details</a></li>
                    </ul>
                </li>                
                <!-- <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/product.svg')}}"
                            alt="img"><span> Product</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="productlist.html">Product List</a></li>
                        <li><a href="addproduct.html">Add Product</a></li>
                        <li><a href="categorylist.html">Category List</a></li>
                        <li><a href="addcategory.html">Add Category</a></li>
                        <li><a href="subcategorylist.html">Sub Category List</a></li>
                        <li><a href="subaddcategory.html">Add Sub Category</a></li>
                        <li><a href="brandlist.html">Brand List</a></li>
                        <li><a href="addbrand.html">Add Brand</a></li>
                        <li><a href="importproduct.html">Import Products</a></li>
                        <li><a href="barcode.html">Print Barcode</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/sales1.svg')}}"
                            alt="img"><span> Sales</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="saleslist.html">Sales List</a></li>
                        <li><a href="pos.html">POS</a></li>
                        <li><a href="pos.html">New Sales</a></li>
                        <li><a href="salesreturnlists.html">Sales Return List</a></li>
                        <li><a href="createsalesreturns.html">New Sales Return</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/purchase1.svg')}}"
                            alt="img"><span> Purchase</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="purchaselist.html">Purchase List</a></li>
                        <li><a href="addpurchase.html">Add Purchase</a></li>
                        <li><a href="importpurchase.html">Import Purchase</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/expense1.svg')}}"
                            alt="img"><span> Expense</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="expenselist.html">Expense List</a></li>
                        <li><a href="createexpense.html">Add Expense</a></li>
                        <li><a href="expensecategory.html">Expense Category</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/quotation1.svg')}}"
                            alt="img"><span> Quotation</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="quotationList.html">Quotation List</a></li>
                        <li><a href="addquotation.html">Add Quotation</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/transfer1.svg')}}"
                            alt="img"><span> Transfer</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="transferlist.html">Transfer List</a></li>
                        <li><a href="addtransfer.html">Add Transfer </a></li>
                        <li><a href="importtransfer.html">Import Transfer </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/return1.svg')}}"
                            alt="img"><span> Return</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="salesreturnlist.html">Sales Return List</a></li>
                        <li><a href="createsalesreturn.html">Add Sales Return </a></li>
                        <li><a href="purchasereturnlist.html">Purchase Return List</a></li>
                        <li><a href="createpurchasereturn.html">Add Purchase Return </a></li>
                    </ul>
                </li> 
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/places.svg')}}"
                            alt="img"><span> Places</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="newcountry.html">New Country</a></li>
                        <li><a href="countrieslist.html">Countries list</a></li>
                        <li><a href="newstate.html">New State </a></li>
                        <li><a href="statelist.html">State list</a></li>
                    </ul>
                </li>
                <li>
                    <a href="components.html"><i data-feather="layers"></i><span> Components</span> </a>
                </li>
                <li>
                    <a href="blankpage.html"><i data-feather="file"></i><span> Blank Page</span> </a>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="alert-octagon"></i> <span> Error Pages </span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="error-404.html">404 Error </a></li>
                        <li><a href="error-500.html">500 Error </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="box"></i> <span>Elements </span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="sweetalerts.html">Sweet Alerts</a></li>
                        <li><a href="tooltip.html">Tooltip</a></li>
                        <li><a href="popover.html">Popover</a></li>
                        <li><a href="ribbon.html">Ribbon</a></li>
                        <li><a href="clipboard.html">Clipboard</a></li>
                        <li><a href="drag-drop.html">Drag & Drop</a></li>
                        <li><a href="rangeslider.html">Range Slider</a></li>
                        <li><a href="rating.html">Rating</a></li>
                        <li><a href="toastr.html">Toastr</a></li>
                        <li><a href="text-editor.html">Text Editor</a></li>
                        <li><a href="counter.html">Counter</a></li>
                        <li><a href="scrollbar.html">Scrollbar</a></li>
                        <li><a href="spinner.html">Spinner</a></li>
                        <li><a href="notification.html">Notification</a></li>
                        <li><a href="lightbox.html">Lightbox</a></li>
                        <li><a href="stickynote.html">Sticky Note</a></li>
                        <li><a href="timeline.html">Timeline</a></li>
                        <li><a href="form-wizard.html">Form Wizard</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="bar-chart-2"></i> <span> Charts </span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="chart-apex.html">Apex Charts</a></li>
                        <li><a href="chart-js.html">Chart Js</a></li>
                        <li><a href="chart-morris.html">Morris Charts</a></li>
                        <li><a href="chart-flot.html">Flot Charts</a></li>
                        <li><a href="chart-peity.html">Peity Charts</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="award"></i><span> Icons </span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="icon-fontawesome.html">Fontawesome Icons</a></li>
                        <li><a href="icon-feather.html">Feather Icons</a></li>
                        <li><a href="icon-ionic.html">Ionic Icons</a></li>
                        <li><a href="icon-material.html">Material Icons</a></li>
                        <li><a href="icon-pe7.html">Pe7 Icons</a></li>
                        <li><a href="icon-simpleline.html">Simpleline Icons</a></li>
                        <li><a href="icon-themify.html">Themify Icons</a></li>
                        <li><a href="icon-weather.html">Weather Icons</a></li>
                        <li><a href="icon-typicon.html">Typicon Icons</a></li>
                        <li><a href="icon-flag.html">Flag Icons</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="columns"></i> <span> Forms </span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="form-basic-inputs.html">Basic Inputs </a></li>
                        <li><a href="form-input-groups.html">Input Groups </a></li>
                        <li><a href="form-horizontal.html">Horizontal Form </a></li>
                        <li><a href="form-vertical.html"> Vertical Form </a></li>
                        <li><a href="form-mask.html">Form Mask </a></li>
                        <li><a href="form-validation.html">Form Validation </a></li>
                        <li><a href="form-select2.html">Form Select2 </a></li>
                        <li><a href="form-fileupload.html">File Upload </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><i data-feather="layout"></i> <span> Table </span> <span
                            class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="tables-basic.html">Basic Tables </a></li>
                        <li><a href="data-tables.html">Data Table </a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/product.svg')}}"
                            alt="img"><span> Application</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="chat.html">Chat</a></li>
                        <li><a href="calendar.html">Calendar</a></li>
                        <li><a href="email.html">Email</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/time.svg')}}"
                            alt="img"><span> Report</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="purchaseorderreport.html">Purchase order report</a></li>
                        <li><a href="inventoryreport.html">Inventory Report</a></li>
                        <li><a href="salesreport.html">Sales Report</a></li>
                        <li><a href="invoicereport.html">Invoice Report</a></li>
                        <li><a href="purchasereport.html">Purchase Report</a></li>
                        <li><a href="supplierreport.html">Supplier Report</a></li>
                        <li><a href="customerreport.html">Customer Report</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="{{route('user.index')}}"><img src="{{URL::asset('admin_asset/img/icons/users1.svg')}}"
                            alt="img"><span> Users</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('user.create')}}">New User </a></li>
                        <li><a href="{{route('user.index')}}">Users List</a></li>
                    </ul>
                </li>
                <li class="submenu">
                    <a href="javascript:void(0);"><img src="{{URL::asset('admin_asset/img/icons/settings.svg')}}"
                            alt="img"><span> Settings</span> <span class="menu-arrow"></span></a>
                    <ul>
                        <li><a href="{{route('company.create')}}">General Settings</a></li>
                        <li><a href="emailsettings.html">Email Settings</a></li>
                        <li><a href="paymentsettings.html">Payment Settings</a></li>
                        <li><a href="currencysettings.html">Currency Settings</a></li>
                        <li><a href="grouppermissions.html">Group Permissions</a></li>
                        <li><a href="taxrates.html">Tax Rates</a></li>
                    </ul>
                </li> -->
            </ul>
        </div>
    </div>
</div>