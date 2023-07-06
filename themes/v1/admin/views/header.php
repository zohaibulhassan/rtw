<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no,width=device-width"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="<?php echo base_url('themes/v1/assets/img/favicon-16x16.png'); ?>" sizes="16x16">
    <title><?php echo $page_title; ?> - <?php echo $Settings->site_name; ?></title>
    <!-- weather icons -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>bower_components/weather-icons/css/weather-icons.min.css" media="all">
    <!-- metrics graphics (charts) -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>bower_components/metrics-graphics/dist/metricsgraphics.css">
    <!-- chartist -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>bower_components/chartist/dist/chartist.min.css">
    <!-- uikit -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>bower_components/uikit/css/uikit.almost-flat.min.css" media="all">
    <!-- flag icons -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>icons/flags/flags.min.css" media="all">
    <!-- style switcher -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>css/style_switcher.min.css" media="all">
    <!--  notifications functions -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/bower_components/toastr/toastr.min.css'); ?>">
    <!-- select2 -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>bower_components/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.css">
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>bower_components/select2/dist/css/select2.min.css">
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>bower_components/sweetalert2/dist/css/sweetalert2.min.css">
    <!-- altair admin -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>css/main.min.css" media="all">
    <!-- altair admin -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>css/custom.css" media="all">
    <!-- themes -->
    <link rel="stylesheet" href="<?php echo base_url('themes/v1/assets/'); ?>css/themes/themes_combined.min.css" media="all">
    <!-- common functions -->
    <script src="<?php echo base_url('themes/v1/assets/'); ?>js/common.min.js"></script>
    <script src="<?php echo base_url('themes/v1/assets/'); ?>bower_components/font-awesome/all.min.js"></script>
</head>
<?php
if ($leftmenu_open) {
    ?>

    <body class="disable_transitions sidebar_main_open sidebar_main_swipe">
    <?php
} else {
    ?>

        <body class="disable_transitions sidebar_main_swipe">
        <?php
}
    ?>
        <!-- main header -->
        <header id="header_main">
            <div class="header_main_content">
                <nav class="uk-navbar">
                    <!-- main sidebar switch -->
                    <a href="#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
                        <span class="sSwitchIcon"></span>
                    </a>
                    <div class="uk-navbar-flip">
                        <ul class="uk-navbar-nav user_actions">
                            <li>
                                <a href="#" style="font-size: 16px;line-height: 50px;" id="CurentDateTimeShow">
                                    <?php echo date('Y-m-d H:i:s'); ?>
                                </a>
                            </li>
                            <!-- <li>
                            <a href="#" class="sidebar_posmenu_toggle"  style="font-size: 16px;line-height: 50px;" data-type="brands">
                                Brands
                            </a>
                        </li> -->
                            <?php
                        if (!$leftmenu_open) {
                            ?>

                                <body class="disable_transitions sidebar_main_open sidebar_main_swipe">
                                    <li>
                                        <a href="#" style="font-size: 16px;line-height: 50px;" id="holdbill">
                                            Hold Bills
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" style="font-size: 16px;line-height: 50px;" id="registerdetail">
                                            Register Detail
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" style="font-size: 16px;line-height: 50px;" id="registerdetail">
                                            Register Close
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="sidebar_posmenu_toggle" style="font-size: 16px;line-height: 50px;" data-type="categories">
                                            Categories
                                        </a>
                                    </li>
                                <?php
                        }
    ?>
                                <li data-uk-dropdown="{mode:'click',pos:'bottom-right'}">
                                    <a href="#" class="user_action_image"><img class="md-user-image" src="<?php echo base_url('assets/'); ?>images/male1.png" alt=""></a>
                                    <div class="uk-dropdown uk-dropdown-small">
                                        <ul class="uk-nav js-uk-prevent">
                                            <li><a href="<?php echo admin_url('users/profile/'.$this->session->userdata('user_id')); ?>">My profile</a></li>
                                            <?php if ($Owner) { ?>
                                                <li><a href="<?php echo admin_url('system_settings'); ?>"><?php echo lang('settings'); ?></a></li>
                                            <?php } ?>
                                            <li><a href="<?php echo admin_url('logout'); ?>">Logout</a></li>
                                        </ul>
                                    </div>
                                </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header><!-- main header end -->
        <!-- main sidebar -->
        <aside id="sidebar_main">
            <div class="sidebar_main_header">
                <div class="sidebar_logo">
                    <a href="#" class="sSidebar_hide sidebar_logo_large">
                        <img class="logo_regular" src="<?php echo base_url('assets/images/300_X_80_Rho_Com_360_.png'); ?>" alt="" height="15" width="71">
                        <img class="logo_light" src="<?php echo base_url('assets/images/300_X_80_Rho_Com_360_.png'); ?>" alt="" height="15" width="71">
                    </a>
                    <a href="#" class="sSidebar_show sidebar_logo_small">
                        <img class="logo_regular" src="<?php echo base_url('themes/v1/assets/img/favicon-16x16.png'); ?>" alt="" height="32" width="32">
                        <img class="logo_light" src="<?php echo base_url('themes/v1/assets/img/favicon-16x16.png'); ?>" alt="" height="32" width="32">
                    </a>
                </div>
            </div>

            <div class="menu_section">
                <ul>
                    <li class="current_section" title="Dashboard">
                        <a href="<?php echo base_url(''); ?>">
                            <span class="menu_icon"><i class="fa-solid fa-gauge"></i></span>
                            <span class="menu_title">Dashboard</span>
                        </a>
                    </li>
                    <?php if ($Owner || $Admin || $GP['products-index'] || $GP['products-add'] || $GP['products-barcode'] || $GP['products-adjustments'] || $GP['products-stock_count']) { ?>
                        <li title="Products">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-boxes-stacked"></i></span>
                                <span class="menu_title">Products</span>
                            </a>
                            <ul>
                                <li><a href="<?php echo base_url('admin/products'); ?>">Product List</a></li>
                                <?php if ($Owner || $Admin || $GP['products-add']) { ?>
                                    <li><a href="<?php echo base_url('admin/products/add'); ?>">Add Product</a></li>
                                <?php } ?>
                                <li><a href="<?php echo base_url('admin/system_settings/categories'); ?>">Product Categories</a></li>
                                <li><a href="<?php echo base_url('admin/products/groups'); ?>">Product Groups</a></li>
                                <!-- <li><a href="<?php echo base_url('admin/products/product_forms'); ?>">Product Forms</a></li>
                            <li><a href="<?php echo base_url('admin/products/formulas'); ?>">Product Formulas</a></li>
                            <li><a href="<?php echo base_url('admin/products/formula_forms'); ?>">Formula Forms</a></li>
                            <li><a href="<?php echo base_url('admin/products/formula_strengths'); ?>">Formula Strength</a></li> -->
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($Owner || $Admin || $GP['po_view'] || $GP['po_add']) { ?>
                        <!-- <li title="Purchase Orders">
                        <a href="#">
                            <span class="menu_icon"><i class="fa-solid fa-book-bookmark"></i></span>
                            <span class="menu_title">Purchase Orders</span>
                        </a>
                        <ul>
                            <?php if ($Owner || $Admin || $GP['po_view']) { ?>
                                <li><a href="<?php echo base_url('admin/purchaseorder'); ?>">Purchase Order List</a></li>
                            <?php } ?>
                            <?php if ($Owner || $Admin || $GP['po_add']) { ?>
                                <li><a href="<?php echo base_url('admin/purchaseorder/add'); ?>">Add Purchase Order</a></li>
                            <?php } ?>
                        </ul>
                    </li> -->
                    <?php } ?>
                    <?php if ($Owner || $Admin || $GP['purchases-index'] || $GP['purchases-add'] || $GP['purchase_adj_view']) { ?>
                        <li title="Purchases">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-cart-shopping"></i></span>
                                <span class="menu_title">Purchases</span>
                            </a>
                            <ul>
                                <?php if ($Owner || $Admin || $GP['purchases-index']) { ?>
                                    <li><a href="<?php echo base_url('admin/purchases'); ?>">Purchase List</a></li>
                                <?php } ?>
                                <?php if ($Owner || $Admin || $GP['purchases-add']) { ?>
                                    <li><a href="<?php echo base_url('admin/purchases/add'); ?>">Add Purchase</a></li>
                                <?php } ?>
                                <?php if ($Owner || $Admin || $GP['purchase_adj_view']) { ?>
                                    <!-- <li><a href="<?php echo base_url('admin/purchases/adjustment'); ?>">Batch adjustments</a></li> -->
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($Owner || $Admin) { ?>
                        <li title="Bill of Materials">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-cart-shopping"></i></span>
                                <span class="menu_title">Bill of Materials</span>
                            </a>
                            <ul>
                                <li><a href="<?php echo base_url('admin/bill_of_materials'); ?>">BOM List</a></li>
                                <li><a href="<?php echo base_url('admin/bill_of_materials/add'); ?>">Add BOM</a></li>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($Owner || $Admin) { ?>
                        <li title="Productions">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-cart-shopping"></i></span>
                                <span class="menu_title">Productions</span>
                            </a>
                            <ul>
                                <li><a href="<?php echo base_url('admin/productions'); ?>">Production List</a></li>
                                <li><a href="<?php echo base_url('admin/productions/add'); ?>">Add Production</a></li>
                            </ul>
                        </li>
                    <?php } ?>




                    <?php if ($Owner || $Admin || $GP['sales-index'] || $GP['sales-add']) { ?>
                        <!-- <li title="POS">
                        <a href="<?php echo base_url('admin/pos'); ?>">
                            <span class="menu_icon"><i class="fa-solid fa-cart-plus"></i></span>
                            <span class="menu_title">Point of Sale</span>
                        </a>
                    </li> -->

                        <li title="Sales">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-money-bill-trend-up"></i></span>
                                <span class="menu_title">Sales</span>
                            </a>
                            <ul>
                                <?php if ($Owner || $Admin || $GP['sales-index']) { ?>
                                    <li><a href="<?php echo base_url('admin/sales'); ?>">Sales List</a></li>
                                <?php } ?>
                                <?php if ($Owner || $Admin || $GP['sales-add']) { ?>
                                    <li><a href="<?php echo base_url('admin/sales/add'); ?>">Add Sale</a></li>
                                <?php } ?>
                            </ul>
                        </li>

                    <?php } ?>



                    <?php if ($Owner || $Admin || $GP['so_view'] || $GP['so_add']) { ?>
                        <li title="Sale Orders">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-bookmark"></i></span>
                                <span class="menu_title">Sale Orders</span>
                            </a>
                            <ul>
                                <?php if ($Owner || $Admin || $GP['so_view']) { ?>
                                    <li><a href="<?php echo base_url('admin/salesorders'); ?>">Sale Order List</a></li>
                                <?php } ?>
                                <?php if ($Owner || $Admin || $GP['so_add']) { ?>
                                    <li><a href="<?php echo base_url('admin/salesorders/add'); ?>">Add Sale Order</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>








                    <?php if ($Owner || $Admin || $GP['purchases-expenses'] || $GP['purchases_add_expense']) { ?>
                        <li title="Expenses">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-money-bill-wave"></i></span>
                                <span class="menu_title">Expenses</span>
                            </a>
                            <ul>
                                <?php if ($Owner || $Admin || $GP['purchases-expenses']) { ?>
                                    <li><a href="<?php echo base_url('admin/purchases/expenses'); ?>">Expenses List</a></li>
                                <?php } ?>
                                <?php if ($Owner || $Admin || $GP['purchases_add_expense']) { ?>
                                    <li><a href="<?php echo base_url('admin/purchases/add_expense'); ?>">Add Expense</a></li>
                                    <li><a href="<?php echo base_url('admin/system_settings/expense_categories'); ?>">Categories</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($Owner || $Admin || $GP['wallet_view']) { ?>
                        <!-- <li title="Wallet">
                        <a href="<?php echo base_url('admin/system_settings/wallets'); ?>">
                            <span class="menu_icon"><i class="fa-solid fa-wallet"></i></span>
                            <span class="menu_title">Wallets</span>
                        </a>
                    </li> -->
                    <?php } ?>
                    <?php if ($Owner || $Admin || $GP['transfers-index'] || $GP['transfers-add']) { ?>
                        <!-- <li title="Transfers">
                        <a href="#">
                            <span class="menu_icon"><i class="fa-solid fa-dolly"></i></span>
                            <span class="menu_title">Transfers</span>
                        </a>
                        <ul>
                            <?php if ($Owner || $Admin || $GP['transfers-index']) { ?>
                                <li><a href="<?php echo base_url('admin/transfers'); ?>">Transfer List</a></li>
                            <?php } ?>
                            <?php if ($Owner || $Admin || $GP['transfers-add']) { ?>
                                <li><a href="<?php echo base_url('admin/transfers/add'); ?>">Add Transfer</a></li>
                            <?php } ?>
                        </ul>
                    </li> -->
                    <?php } ?>
                    <!-- <?php if ($Owner || $Admin) { ?>
                    <li title="Payment Collections">
                        <a href="<?php echo base_url('admin/paymentcollections'); ?>">
                            <span class="menu_icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
                            <span class="menu_title">Payment Collection</span>
                        </a>
                    </li>
                <?php } ?> -->
                    <?php if ($Owner || $Admin || $GP['store_view'] || $GP['store_add']) { ?>
                        <li title="Stores">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-store"></i></span>
                                <span class="menu_title">Ecommerce Stores</span>
                            </a>
                            <ul>
                                <?php if ($Owner || $Admin || $GP['store_view']) { ?>
                                    <li><a href="<?php echo base_url('admin/stores'); ?>">Store List</a></li>
                                <?php } ?>
                                <?php if ($Owner || $Admin || $GP['store_add']) { ?>
                                    <li><a href="<?php echo base_url('admin/stores/add'); ?>">Add Store</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <li title="Reports">
                        <a href="#">
                            <span class="menu_icon"><i class="fa-solid fa-clipboard-list"></i></span>
                            <span class="menu_title">Reports</span>
                        </a>
                        <ul>
                            <?php if ($Owner || $Admin || $GP['dc_report']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/dc_report'); ?>">DC Report</a></li>
                            <?php } ?>
                            <!-- <?php if ($Owner || $Admin || $GP['report_sales_summary']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/salessummary'); ?>">Sales Summary</a></li>
                            <?php } ?> -->
                            <!-- <?php if ($Owner || $Admin || $GP['reports-sales']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/salesreport'); ?>">Sales Report</a></li>
                            <?php } ?> -->
                            <!-- <?php if ($Owner || $Admin || $GP['reports-sales']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/salesreturnremarksreport'); ?>">SRIR Report</a></li>
                            <?php } ?> -->
                            <?php if ($Owner || $Admin || $GP['purchase_report']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/purchasereport'); ?>">Purchase Report</a></li>
                            <?php } ?>
                            <?php if ($Owner || $Admin || $GP['purchase_report']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/purchasereturn'); ?>">Purchase Return Report</a></li>
                            <?php } ?>
                            <?php if ($Owner || $Admin || $GP['batchwise_report']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/batchwise'); ?>">Batch wise</a></li>
                            <?php } ?>
                            <?php if ($Owner || $Admin || $GP['old_stock']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/old_stock_report'); ?>">Old Stuck</a></li>
                            <?php } ?>
                            <!-- <?php if ($Owner || $Admin || $GP['monthly_items_demand']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/monthly_items_demand'); ?>">Monthly Items Demand</a></li>
                            <?php } ?> -->
                            <!-- <?php if ($Owner || $Admin || $GP['ces_stock']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/expected_soldout'); ?>">CES Stock</a></li>
                            <?php } ?> -->
                            <!-- <?php if ($Owner || $Admin || $GP['short_expiry_stock']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/shortexpiry_stock'); ?>">Short Expiry Stock</a></li>
                            <?php } ?> -->
                            <!-- <?php if ($Owner || $Admin || $GP['expiry_stock']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/expired_stock'); ?>">Expired Stock</a></li>
                            <?php } ?> -->
                            <?php if ($Owner || $Admin || $GP['customers_ledger']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/customer_legder'); ?>">Customers Ledger</a></li>
                            <?php } ?>
                            <!-- <?php if ($Owner || $Admin || $GP['customers_wht_ledger']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/customer_wht_legder'); ?>">Customers WHT Ledger</a></li>
                            <?php } ?> -->
                            <?php if ($Owner || $Admin || $GP['legder_summary']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/ledger_summery'); ?>">Ledger Summary</a></li>
                            <?php } ?>
                            <?php if ($Owner || $Admin || $GP['supplier_ledger']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/supplier_legder'); ?>">Supplier Ledger</a></li>
                            <?php } ?>
                            <?php if ($Owner || $Admin || $GP['credit_report']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/creadits'); ?>">Creadit Report</a></li>
                            <?php } ?>
                            <!-- <?php if ($Owner || $Admin || $GP['due_invoice']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/due_invoices'); ?>">Due Invoices</a></li>
                            <?php } ?> -->
                            <?php if ($Owner || $Admin || $GP['so_items_wise']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/so_items_wise'); ?>">SO Items Wise</a></li>
                            <?php } ?>
                            <?php if ($Owner || $Admin || $GP['so_items_wise']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/po_items_wise'); ?>">PO Items Wise</a></li>
                            <?php } ?>
                            <?php if ($Owner || $Admin || $GP['product_ledger']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/products_ledger'); ?>">Products Ledger</a></li>
                            <?php } ?>
                            <!-- <?php if ($Owner || $Admin || $GP['customers_ledger']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/batch_wise_true_false'); ?>">True & False Batch Waise</a></li>
                            <?php } ?>
                            <?php if ($Owner || $Admin || $GP['customers_ledger']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/product_wise_true_false'); ?>">True & False Product Waise</a></li>
                            <?php } ?> -->
                            <?php if ($Owner || $Admin || $GP['customers_ledger']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/so_hold_quantity'); ?>">SO Hold Quantity</a></li>
                            <?php } ?>

                            <?php if ($Owner || $Admin || $GP['pnl_report']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/inventorysummary'); ?>">Inventory Summary Report</a></li>
                            <?php } ?>


                            <?php if ($Owner || $Admin || $GP['pnl_report']) { ?>
                                <li><a href="<?php echo base_url('admin/reports/profitandloss'); ?>">Profit and Loss</a></li>
                            <?php } ?>
                        </ul>
                    </li>




                    <?php if ($Owner) { ?>
                        <li title="Users">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-users"></i></span>
                                <span class="menu_title">Users</span>
                            </a>
                            <ul>
                                <li><a href="<?php echo base_url('admin/users'); ?>">User List</a></li>
                                <li><a href="<?php echo base_url('admin/users/create_user'); ?>">Add User</a></li>
                                <li><a href="<?php echo base_url('admin/system_settings/user_groups'); ?>">User Permission</a></li>
                            </ul>
                        </li>
                    <?php } ?>





                    <?php if ($Owner) { ?>
                        <li title="Users">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-truck-front"></i></span>
                                <span class="menu_title">Inventory Adjustments
                                </span>
                            </a>
                            <ul>
                                <li><a href="<?php echo base_url('admin/system_settings/list_inv_adj'); ?>">Inventory Adjustment List</a></li>
                                <li><a href="<?php echo base_url('admin/system_settings/add_inv_adj'); ?>">Add Inventory Adjustment</a></li>
                            </ul>
                        </li>
                    <?php } ?>




                    <?php if ($Owner) { ?>
                        <li title="Users">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-star"></i></span>
                                <span class="menu_title">Transfer Order
                                </span>
                            </a>
                            <ul>
                                <li><a href="<?php echo base_url('admin/system_settings/list_transfer'); ?>">Lists Transfers</a></li>
                                <li><a href="<?php echo base_url('admin/system_settings/add_transfer'); ?>">Add Transfers</a></li>
                            </ul>
                        </li>
                    <?php } ?>








                    <?php if ($Owner || $Admin || $GP['customers-index'] || $GP['customers-add']) { ?>
                        <li title="Customers">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-user-tie"></i></span>
                                <span class="menu_title">Customers</span>
                            </a>
                            <ul>
                                <?php if ($Owner || $Admin || $GP['customers-index']) { ?>
                                    <li><a href="<?php echo base_url('admin/customers'); ?>">Customer List</a></li>
                                <?php } ?>
                                <?php if ($Owner || $Admin || $GP['customers-add']) { ?>
                                    <li><a href="<?php echo base_url('admin/customers/add'); ?>">Add Customer</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <?php if ($Owner || $Admin || $GP['customers-index'] || $GP['customers-add']) { ?>
                        <li title="Suppliers">
                            <a href="#">
                                <span class="menu_icon"><i class="fa-solid fa-people-carry-box"></i></span>
                                <span class="menu_title">Suppliers</span>
                            </a>
                            <ul>
                                <?php if ($Owner || $Admin || $GP['suppliers-index']) { ?>
                                    <li><a href="<?php echo base_url('admin/suppliers'); ?>">Supplier List</a></li>
                                <?php } ?>
                                <?php if ($Owner || $Admin || $GP['suppliers-add']) { ?>
                                    <li><a href="<?php echo base_url('admin/suppliers/add'); ?>">Add Supplier</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                    <!-- <?php if ($Owner || $Admin) { ?>
                    <li title="Survey">
                        <a href="#">
                            <span class="menu_icon"><i class="fa-solid fa-list-check"></i></span>
                            <span class="menu_title">Survey</span>
                        </a>
                        <ul>
                            <li><a href="<?php echo base_url('admin/survey/questions'); ?>">Survey Questions</a></li>
                        </ul>
                    </li>
                <?php } ?> -->
                    <?php if ($Owner || $Admin || $GP['tax_rates']) { ?>
                        <!-- <li title="Tax Rate">
                        <a href="<?php echo base_url('admin/system_settings/tax_rates'); ?>">
                            <span class="menu_icon"><i class="fa-solid fa-file-invoice"></i></span>
                            <span class="menu_title">Tax Rate</span>
                        </a>
                    </li> -->
                    <?php } ?>

                    <?php if ($Owner) { ?>
                    <li title="Master Module">
                    <a href="#">
                        <span class="menu_icon"><i class="fa-sharp fa-solid fa-bars"></i></span>
                        <span class="menu_title">Master Module</span>
                    </a>
                    <ul>
                        <?php if ($Owner) { ?>
                            <li><a href="<?php echo base_url('admin/system_settings/currencies'); ?>">Currencies</a></li>
                        <?php } ?>
                        <?php if ($Owner) { ?>
                            <li><a href="<?php echo base_url('admin/system_settings/units'); ?>">Units</a></li>
                        <?php } ?>
                        <?php if ($Owner) { ?>
                            <li><a href="<?php echo base_url('admin/system_settings/own_companies'); ?>">Own Companies</a></li>
                        <?php } ?>
                        <?php if ($Owner) { ?>
                            <li><a href="<?php echo base_url('admin/system_settings/warehouses'); ?>">Warehouses</a></li>
                        <?php } ?>
                        <?php if ($Owner) { ?>
                            <li><a href="<?php echo base_url('admin/Manufacturers'); ?>">Manufacturers</a></li>
                        <?php } ?>
<?php if ($Owner) { ?>
        <li><a href="<?php echo base_url('admin/logs'); ?>">Activity Logs</a></li>
    <?php } ?>

    <?php if ($Owner || $Admin || $GP['bulk_discount']) { ?>
                        <li title="Bulk Discounts">
                            <a href="<?php echo base_url('admin/system_settings/bulk_discounts'); ?>">
                      <span class="menu_title">Bulk Discounts</span>
                            </a>
                        </li>
                    <?php } ?>
                      
                        </ul>
                    </li>
                <?php } ?>
                </ul>
            </div>
        </aside>