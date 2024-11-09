<li class="c-sidebar-nav-item {{ request()->routeIs('home') ? 'c-active' : '' }}">
    <a class="c-sidebar-nav-link" href="{{ route('home') }}">
        <i class="c-sidebar-nav-icon bi bi-house" style="line-height: 1;"></i> {{ __('menu.home') }}
    </a>
</li>

@can('access_products')
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('products.*') || request()->routeIs('product-categories.*') ? 'c-show' : '' }}">
    <a class="c-sidebar-nav-dropdown-toggle">
        <i class="c-sidebar-nav-icon bi bi-journal-bookmark" style="line-height: 1;"></i> {{ __('menu.products') }}
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        @can('access_units')
        {{-- <ul class="c-sidebar-nav-dropdown-items"> --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('units*') ? 'c-active' : '' }}" href="{{ route('units.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-calculator" style="line-height: 1;"></i> {{ __('menu.units') }}
                </a>
            </li>
        {{-- </ul> --}}
        @endcan
        @can('access_product_categories')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('product-categories') ? 'c-active' : '' }}" href="{{ route('product-categories.index') }}">
                <i class="c-sidebar-nav-icon bi bi-collection" style="line-height: 1;"></i> {{ __('menu.categories') }}
            </a>
        </li>
        @endcan
        @can('create_products')
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('products.create') ? 'c-active' : '' }}" href="{{ route('products.create') }}">
                <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> {{ __('menu.create_product') }}
            </a>
        </li>
        @endcan
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->routeIs('products.index') ? 'c-active' : '' }}" href="{{ route('products.index') }}">
                <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> {{ __('menu.all_products') }}
            </a>
        </li>
        @can('print_barcodes')
           <li class="c-sidebar-nav-item">
               <a class="c-sidebar-nav-link {{ request()->routeIs('barcode.print') ? 'c-active' : '' }}" href="{{ route('barcode.print') }}">
                   <i class="c-sidebar-nav-icon bi bi-printer" style="line-height: 1;"></i> {{ __('menu.print_barcode') }}
               </a>
           </li>
        @endcan
    </ul>
</li>
@endcan

@can('access_adjustments')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('adjustments.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-clipboard-check" style="line-height: 1;"></i> {{ __('menu.stock_adjustments') }}
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('create_adjustments')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('adjustments.create') ? 'c-active' : '' }}" href="{{ route('adjustments.create') }}">
                        <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> {{ __('menu.create_adjustment') }}
                    </a>
                </li>
            @endcan
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('adjustments.index') ? 'c-active' : '' }}" href="{{ route('adjustments.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> {{ __('menu.all_adjustments') }}
                </a>
            </li>
        </ul>
    </li>
@endcan

{{-- @can('access_incomes') --}}
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('selforder.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon" style="line-height: 1;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 73.76 122.88" width="18" height="18" fill="currentColor">
                    <path fill-rule="evenodd" d="M14.53,0H54.11A14.53,14.53,0,0,1,68.64,14.54v14h-4.3V17.13a2.74,2.74,0,0,0-2.73-2.73H7a2.74,2.74,0,0,0-2.73,2.73v84.29A2.74,2.74,0,0,0,7,104.15h54.6a2.74,2.74,0,0,0,2.73-2.73V98.06a15.78,15.78,0,0,0,4.3-2v12.29a14.53,14.53,0,0,1-14.53,14.53H14.53A14.54,14.54,0,0,1,0,108.35V14.52A14.51,14.51,0,0,1,14.53,0Zm1.3,35.63a2,2,0,1,1,0-4.09H17.4a9.49,9.49,0,0,1,5,1.29,7.62,7.62,0,0,1,3.33,4.73l.53,2.2H71.72a2,2,0,0,1,2,2,1.89,1.89,0,0,1-.1.63L68.37,63.84a2,2,0,0,1-2,1.55H33.07c.75,2.71,1.44,4.18,2.44,4.86,1.18.79,3.28.85,6.78.79H65.83a2,2,0,0,1,0,4.09H42.33c-4.3.07-7-.06-9.09-1.49s-3.31-3.93-4.45-8.48l-7-26.52a3.75,3.75,0,0,0-1.54-2.34,5.64,5.64,0,0,0-2.87-.67Zm44,41.87a5,5,0,1,1-5,5,5,5,0,0,1,5-5Zm-21.9,0a5,5,0,1,1-5,5,5,5,0,0,1,5-5Zm12.5-33.64v7.43H67.27l1.84-7.43Zm0,11.52v5.93H64.8l1.46-5.93Zm-4.08,5.93V55.38H30.42c.54,2,1.08,3.95,1.59,5.93Zm0-10V43.86h-19c.65,2.48,1.33,5,2,7.43Zm-12,56a6,6,0,1,1-6,6,6,6,0,0,1,6-6Z"/>
                </svg>
            </i> {{ __('menu.selforder') }}
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('selforder.*') ? 'c-active' : '' }}" href="{{ route('selforder.mobileorder') }}">
                    <i class="c-sidebar-nav-icon" style="line-height: 1;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 478 512.01" width="18" height="18" fill="currentColor">
                            <path fill-rule="evenodd" d="M69.7 55.96h32.12v265.57H69.7V55.96zm173.03 176.68c9.33.14 18.47 1.29 27.27 3.36V55.96h22.95v187.8c9.78 4.39 18.95 9.99 27.28 16.6V55.96h32.11v242.13c.49.87.96 1.74 1.43 2.62 7.6 14.39 12.58 30.51 14.2 47.77 1.19 12.63.5 25.03-1.84 36.85a126.657 126.657 0 0 1-10.19 30.48l54.37 49.11c2.5 2.26 2.69 6.13.42 8.63l-33.04 36.46a6.136 6.136 0 0 1-8.64.42L317.04 463a127.014 127.014 0 0 1-28 15.82c-11.32 4.62-23.52 7.68-36.34 8.89-17.24 1.62-34-.27-49.57-5.06-16.18-4.98-31.09-13.12-43.92-23.75a127.834 127.834 0 0 1-31.53-38.72c-7.61-14.38-12.59-30.51-14.21-47.76-1.62-17.23.26-34 5.06-49.57 2.65-8.63 6.21-16.91 10.56-24.7V55.96h9.76v227.28c1.11-1.46 2.25-2.9 3.42-4.31 6.94-8.39 14.96-15.9 23.85-22.3V55.96h32.13v183.9c8.66-3.04 17.79-5.19 27.27-6.32V55.96h17.21v176.68zm63.05 49.23c-20.9-17.31-47.61-25.53-74.62-22.99-40.99 3.85-75.76 32.04-87.97 71.57a101.788 101.788 0 0 0-4.03 39.56c2.53 27 15.62 51.72 36.51 69.01 10.24 8.48 22.14 14.98 35.04 18.95 12.44 3.83 25.83 5.33 39.58 4.04 13.73-1.29 26.61-5.27 38.11-11.36a102.106 102.106 0 0 0 30.9-25.15c8.48-10.24 14.98-22.14 18.95-35.04 3.83-12.44 5.33-25.83 4.04-39.58a101.63 101.63 0 0 0-11.35-38.1 102.205 102.205 0 0 0-25.16-30.91zM9.26 0H94.9v18.52H18.51V66.9H0V9.26C0 4.15 4.14 0 9.26 0zm9.25 310.59v48.38H94.9v18.52H9.26c-5.12 0-9.26-4.15-9.26-9.26v-57.64h18.51zM383.1 0h85.64c5.11 0 9.26 4.15 9.26 9.26V66.9h-18.51V18.52H383.1V0zM478 310.59v57.64c0 5.11-4.15 9.26-9.26 9.26H383.1v-18.52h76.39v-48.38H478zM379.62 55.96h28.68v265.57h-28.68V55.96z"/>
                        </svg>
                    </i> {{ __('menu.selforder_mobile') }}
                </a>
            </li>
            {{-- <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('income-categories.*') ? 'c-active' : '' }}" href="{{ route('income-categories.index') }}">
                    <i class="c-sidebar-nav-icon" style="line-height: 1;">
                       <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 108.39 122.88" width="18" height="18" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.05,0h92.28a8.07,8.07,0,0,1,8.06,8.06V85.74a8.08,8.08,0,0,1-8.06,8.05H62.57v-.16c0-1.35,0-2.67-.06-4.88h38.86a2.22,2.22,0,0,0,2.22-2.23V19.46H5.12V86.52a2.1,2.1,0,0,0,.07.56A7.34,7.34,0,0,0,5,88.67a9.65,9.65,0,0,0,.86,4.17,5.15,5.15,0,0,0,.36.7l0,0A8.09,8.09,0,0,1,0,85.74V8.06A8.07,8.07,0,0,1,8.05,0ZM26.88,69.07a3.36,3.36,0,0,1,3.59,3.34V93.83h2.17V83.32c0-4.25,6.7-4.12,6.7.24v10.5l.1,0h2.35V85.68c0-4.25,6.7-4.12,6.7.23v7.76h2.36V87.76c0-4.25,6.7-4.12,6.7.23,0,4.9.28,10.68.1,15.57-.19,5.37-1.3,11.42-5.19,14.8a18.18,18.18,0,0,1-15,4.27c-9.22-1.46-11.55-7.11-15.94-14.07L10.37,90.9c-.69-1.61-.6-2.7.09-3.4,3-1.92,7.78,2.16,13.12,7.93l.19,0V72.18a3.12,3.12,0,0,1,3.11-3.11ZM17.28,40.3a3.53,3.53,0,0,1,5.86-3.92l1.91,2.1,5.61-7a3.52,3.52,0,0,1,5.44,4.48L27.62,46.15a3.77,3.77,0,0,1-.84.8,3.53,3.53,0,0,1-4.9-1l-4.6-5.68ZM88.13,68.57a3.7,3.7,0,0,0,0-7.39H49.51a3.7,3.7,0,1,0,0,7.39Zm0-26a3.7,3.7,0,0,0,0-7.39H49.51a3.7,3.7,0,1,0,0,7.39ZM17.64,54.63H36a2.51,2.51,0,0,1,2.51,2.51V72.61A2.52,2.52,0,0,1,36,75.12h-.53V72.41a8,8,0,0,0-2-5.37V59.65H20.15v8.1a8,8,0,0,0-1.34,4.43v2.94H17.64a2.52,2.52,0,0,1-2.51-2.51V57.14a2.51,2.51,0,0,1,2.51-2.51ZM92.66,7a4,4,0,1,1-4,4,4,4,0,0,1,4-4ZM65.71,7a4,4,0,1,1-4,4,4,4,0,0,1,4-4ZM79.19,7a4,4,0,1,1-4,4,4,4,0,0,1,4-4Z"/>
                        </svg>
                    </i> {{ __('menu.selforder_stay') }}
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('income-categories.*') ? 'c-active' : '' }}" href="{{ route('income-categories.index') }}">
                    <i class="c-sidebar-nav-icon" style="line-height: 1;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 118.16 122.88" width="18" height="18" fill="currentColor">
                            <path fill-rule="evenodd" d="M0,26.39l14.83,6.28L29,6.27,14.19,0,0,26.39ZM69.69,77.57V73.69H61V106h53.59V73.69h-8.81v4a4.22,4.22,0,1,1-5.6-.11V73.69H75.3v3.9a4.22,4.22,0,1,1-5.61,0Zm0-7.46V66.33c-1.39-.43-2.75-.92-4.07-1.47l-3.5-1.63a50.26,50.26,0,0,1-8.8-5.82l-13-3c-5.2-1.83-9.18-4.5-11.11-8.59a51.81,51.81,0,0,0-3.34-5.92c-2.68-4.07-.5-1.77-4.5-4.22L18.39,33.9c2.21-6,10.73-20.22,13.8-25.93l10.12,3.21a199.54,199.54,0,0,0,29,7.41c5,1.51,8.35,3.65,9.6,6.57l7.3,14.18a17.67,17.67,0,0,1,1.35,3.2,22.4,22.4,0,0,1,5.35,1.09c6.13,2.09,10.91,6.9,10.91,14.45v12h9.58a2.81,2.81,0,0,1,2,.82l.15.17a2.79,2.79,0,0,1,.67,1.82v39.86a10.12,10.12,0,0,1-10.09,10.1H67.5a10.12,10.12,0,0,1-10.09-10.1V72.92a2.8,2.8,0,0,1,2.81-2.81ZM72.8,48.87a15.84,15.84,0,0,1,4.58-3.82,31.56,31.56,0,0,1-6-6.94c-1.92-2.87-1-2.33-4.64-2.86-1.86-.27-3.71-.5-5.57-.67a15.58,15.58,0,0,0-3.54,3.28,18.16,18.16,0,0,0,2.65,3.46c3.15,3.24,6.09,4,10.14,6.2.8.44,1.6.89,2.39,1.35Zm27.37,21.24v-12c0-4.74-3.12-7.79-7.1-9.15a16.92,16.92,0,0,0-5.34-.85,16.53,16.53,0,0,0-5.33.85A11.31,11.31,0,0,0,77.53,52a25.62,25.62,0,0,1,3.67,3.43c5,6.45,1.94,12.86-5.9,12.14v2.51Z"/>
                        </svg>
                    </i> {{ __('menu.selforder_delivery') }}
                </a>
            </li> --}}
        </ul>
    </li>
{{-- @endcan --}}
@can('access_quotations')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('quotations.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-cart-check" style="line-height: 1;"></i> {{ __('menu.quotations') }}
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('create_adjustments')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('quotations.create') ? 'c-active' : '' }}" href="{{ route('quotations.create') }}">
                        <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> {{ __('menu.create_quotation') }}
                    </a>
                </li>
            @endcan
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('quotations.index') ? 'c-active' : '' }}" href="{{ route('quotations.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> {{ __('menu.all_quotations') }}
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_purchases')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('purchases.*') || request()->routeIs('purchase-payments*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-bag" style="line-height: 1;"></i> {{ __('menu.purchases') }}
        </a>
        @can('create_purchase')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('purchases.create') ? 'c-active' : '' }}" href="{{ route('purchases.create') }}">
                        <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> {{ __('menu.create_purchase') }}
                    </a>
                </li>
            </ul>
        @endcan
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('purchases.index') ? 'c-active' : '' }}" href="{{ route('purchases.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> {{ __('menu.all_purchases') }}
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_purchase_returns')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('purchase-returns.*') || request()->routeIs('purchase-return-payments.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-arrow-return-right" style="line-height: 1;"></i> {{ __('menu.purchase_returns') }}
        </a>
        @can('create_purchase_returns')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('purchase-returns.create') ? 'c-active' : '' }}" href="{{ route('purchase-returns.create') }}">
                        <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> {{ __('menu.create_purchase_return') }}
                    </a>
                </li>
            </ul>
        @endcan
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('purchase-returns.index') ? 'c-active' : '' }}" href="{{ route('purchase-returns.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> {{ __('menu.all_purchase_returns') }}
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_sales')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('sales.*') || request()->routeIs('sale-payments*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-receipt" style="line-height: 1;"></i> {{ __('menu.sales') }}
        </a>
        @can('create_sales')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('sales.create') ? 'c-active' : '' }}" href="{{ route('sales.create') }}">
                        <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> {{ __('menu.create_sale') }}
                    </a>
                </li>
            </ul>
        @endcan
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('sales.index') ? 'c-active' : '' }}" href="{{ route('sales.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> {{ __('menu.all_sales') }}
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_sale_returns')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('sale-returns.*') || request()->routeIs('sale-return-payments.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-arrow-return-left" style="line-height: 1;"></i> {{ __('menu.sale_returns') }}
        </a>
        @can('create_sale_returns')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('sale-returns.create') ? 'c-active' : '' }}" href="{{ route('sale-returns.create') }}">
                        <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> {{ __('menu.create_sale_return') }}
                    </a>
                </li>
            </ul>
        @endcan
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('sale-returns.index') ? 'c-active' : '' }}" href="{{ route('sale-returns.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> {{ __('menu.all_sale_returns') }}
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_expenses')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('expenses.*') || request()->routeIs('expense-categories.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-wallet2" style="line-height: 1;"></i> {{ __('menu.expenses') }}
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('access_expense_categories')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('expense-categories.*') ? 'c-active' : '' }}" href="{{ route('expense-categories.index') }}">
                        <i class="c-sidebar-nav-icon bi bi-collection" style="line-height: 1;"></i> {{ __('menu.expense_categories') }}
                    </a>
                </li>
            @endcan
            @can('create_expenses')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('expenses.create') ? 'c-active' : '' }}" href="{{ route('expenses.create') }}">
                        <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> {{ __('menu.create_expense') }}
                    </a>
                </li>
            @endcan
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('expenses.index') ? 'c-active' : '' }}" href="{{ route('expenses.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> {{ __('menu.all_expenses') }}
                </a>
            </li>
        </ul>
    </li>
@endcan


@can('access_incomes')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('incomes.*') || request()->routeIs('income-categories.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-wallet" style="line-height: 1;"></i> {{ __('menu.incomes') }}
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('access_income_categories')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('income-categories.*') ? 'c-active' : '' }}" href="{{ route('income-categories.index') }}">
                        <i class="c-sidebar-nav-icon bi bi-collection" style="line-height: 1;"></i> {{ __('menu.income_categories') }}
                    </a>
                </li>
            @endcan
            @can('create_incomes')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('incomes.create') ? 'c-active' : '' }}" href="{{ route('incomes.create') }}">
                        <i class="c-sidebar-nav-icon bi bi-journal-plus" style="line-height: 1;"></i> {{ __('menu.create_income') }}
                    </a>
                </li>
            @endcan
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('incomes.index') ? 'c-active' : '' }}" href="{{ route('incomes.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-journals" style="line-height: 1;"></i> {{ __('menu.all_incomes') }}
                </a>
            </li>
        </ul>
    </li>
@endcan

@can('access_customers|access_suppliers')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('customers.*') || request()->routeIs('suppliers.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-people" style="line-height: 1;"></i> {{ __('menu.parties') }}
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            @can('access_customers')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('customers.*') ? 'c-active' : '' }}" href="{{ route('customers.index') }}">
                        <i class="c-sidebar-nav-icon bi bi-people-fill" style="line-height: 1;"></i> {{ __('menu.customers') }}
                    </a>
                </li>
            @endcan
            @can('access_suppliers')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('suppliers.*') ? 'c-active' : '' }}" href="{{ route('suppliers.index') }}">
                        <i class="c-sidebar-nav-icon bi bi-people-fill" style="line-height: 1;"></i> {{ __('menu.suppliers') }}
                    </a>
                </li>
            @endcan
        </ul>
    </li>
@endcan


{{-- @can('access_customers|access_suppliers') --}}
{{-- <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('customers.*') || request()->routeIs('suppliers.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-whatsapp" style="line-height: 1;"></i> {{ __('menu.whatsapp') }}
        </a>
        <ul class="c-sidebar-nav-dropdown-items"> --}}
            {{-- @can('access_customers') --}}
                {{-- <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('whatsapp.*') ? 'c-active' : '' }}" href="{{ route('whatsapp.index') }}">
                        <i class="c-sidebar-nav-icon bi bi-broadcast" style="line-height: 1;"></i> {{ __('menu.whatsapp_broadcast') }}
                    </a>
                </li> --}}
            {{-- @endcan
            @can('access_suppliers') --}}

            {{-- @endcan --}}
        {{-- </ul>
    </li> --}}

@can('access_payment_gateways')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('payment-gateways.*') || request()->routeIs('payment-gateways.*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-credit-card-2-front" style="line-height: 1;"></i> {{ __('menu.paymentgateway') }}
        </a>
        <ul class="c-sidebar-nav-dropdown-items">

                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('payment-gateways.*') ? 'c-active' : '' }}" href="{{ route('payment-gateways.index') }}">
                        <i class="c-sidebar-nav-icon bi bi-newspaper" style="line-height: 1;"></i> {{ __('menu.show_list') }}
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('payment-gateways.setting') ? 'c-active' : '' }}" href="{{ route('payment-gateways.setting') }}">
                        <i class="c-sidebar-nav-icon bi bi-gear-fill" style="line-height: 1;"></i> {{ __('menu.payment_setting') }}
                    </a>
                </li>
        </ul>
    </li>
@endcan

@can('access_reports')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('*-report.index') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-graph-up" style="line-height: 1;"></i> {{ __('menu.reports') }}
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('profit-loss-report.index') ? 'c-active' : '' }}" href="{{ route('profit-loss-report.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> {{ __('menu.profit_loss_report') }}
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('payments-report.index') ? 'c-active' : '' }}" href="{{ route('payments-report.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> {{ __('menu.payments_report') }}
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('sales-report.index') ? 'c-active' : '' }}" href="{{ route('sales-report.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> {{ __('menu.sales_report') }}
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('purchases-report.index') ? 'c-active' : '' }}" href="{{ route('purchases-report.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> {{ __('menu.purchases_report') }}
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('sales-return-report.index') ? 'c-active' : '' }}" href="{{ route('sales-return-report.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> {{ __('menu.sales_return_report') }}
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('purchases-return-report.index') ? 'c-active' : '' }}" href="{{ route('purchases-return-report.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-clipboard-data" style="line-height: 1;"></i> {{ __('menu.purchases_return_report') }}
                </a>
            </li>
        </ul>
    </li>
@endcan
{{-- @can('access_customers|access_suppliers') --}}
<li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('financial-management.*') ? 'c-show' : '' }}">
    <a class="c-sidebar-nav-dropdown-toggle">
        <i class="c-sidebar-nav-icon bi bi-wallet" style="line-height: 1;"></i> {{ __('menu.wallet') }}
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        {{-- @can('access_customers') --}}
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('financial-management-withdraw') ? 'c-active' : '' }}" href="{{ route('financial.management.withdraw') }}">
                    {{-- <i class="c-sidebar-nav-icon bi bi-wallet-fill" style="line-height: 1;"></i> {{ __('menu.wallet_withdraw') }} --}}
                    <i class="c-sidebar-nav-icon" style="line-height: 1;">
                        <svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 366.25" width="24" height="24">
                            <path fill-rule="nonzero" d="M86.43 91.28c9.87 0 17.89 8.01 17.89 17.89s-8.02 17.89-17.89 17.89H61.29c-29.38 0-48.33-16.95-56.68-38.6C1.55 80.52.02 71.92 0 63.35 0 54.8 1.52 46.2 4.58 38.27 12.88 16.77 31.76 0 61.29 0h389.42c29.51 0 48.4 16.8 56.7 38.32 3.07 7.93 4.59 16.53 4.59 25.09-.01 8.57-1.55 17.17-4.61 25.11-8.33 21.62-27.29 38.54-56.68 38.54h-32.64c-9.88 0-17.89-8.01-17.89-17.89s8.01-17.89 17.89-17.89h32.64c12.22 0 20.04-6.86 23.42-15.62 1.48-3.85 2.23-8.05 2.23-12.25 0-4.21-.73-8.4-2.21-12.23-3.34-8.65-11.12-15.4-23.44-15.4h-73.43v196.76H134.72V35.78H61.29c-12.34 0-20.12 6.73-23.45 15.35-1.47 3.82-2.2 8.01-2.2 12.22 0 4.2.75 8.4 2.24 12.25 3.39 8.79 11.21 15.68 23.41 15.68h25.14zm198.55 158.78v38.91h26.56c4.46.19 7.62 1.66 9.45 4.44 4.95 7.42-1.82 14.76-6.51 19.93-13.25 14.61-43.39 41.11-49.96 48.78-4.97 5.5-12.07 5.5-17.04 0-6.79-7.92-38.57-35.94-51.23-50.19-4.38-4.94-9.81-11.67-5.24-18.52 1.83-2.78 4.99-4.25 9.45-4.44h26.56v-38.91h57.96zm55.35-214.28H172.79v118.38c19.73 0 35.83 16.1 35.83 35.83h95.88c0-19.73 16.09-35.83 35.83-35.83V35.78zM256 45.62c23.89 0 43.26 19.37 43.26 43.26 0 23.89-19.37 43.26-43.26 43.26-23.89 0-43.26-19.37-43.26-43.26 0-23.89 19.37-43.26 43.26-43.26z"/>
                        </svg>
                    </i> {{ __('menu.wallet_withdraw') }}

                </a>
            </li>
        {{-- @endcan
       {{-- @can('access_customers') --}}  {{-- <i class="c-sidebar-nav-icon bi bi-wallet-fill" style="line-height: 1;"></i> {{ __('menu.wallet_topup') }} --}}

            {{-- <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('financial-management.topup') ? 'c-active' : '' }}" href="{{ route('financial.management.topup') }}">
                    <i class="c-sidebar-nav-icon" style="line-height: 1;">
                        <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 91.98" width="24" height="24">
                            <path d="M103.85,57.91a2.47,2.47,0,0,1,2.27,1.5l16.47,28.94c.45.87.25,2.62.23,3.63H.05l0-2.5a2.47,2.47,0,0,1,.37-1.27l16.7-29a2.45,2.45,0,0,1,2.17-1.31H50L43.21,46.19a12.27,12.27,0,0,1-1.07-1.54L41.08,42.8h0a1.5,1.5,0,0,1-.31.24l-1.76,1-.31.14a4.93,4.93,0,0,1-5.84-1.3,1.6,1.6,0,0,1-.47.43l-1.76,1a1.69,1.69,0,0,1-.31.13,5,5,0,0,1-6.15-1.64A1,1,0,0,1,24,43l-1.76,1-.31.14c-5.26,2-8-2.8-10.16-6.7l-.59-1L8.52,32l-.08-.14C6.42,28,6.5,24.63,6.59,20.79c0-.65,0-1.3,0-2.21,0,0,0,0,0-.06A12.51,12.51,0,0,1,7.68,13.1,8.08,8.08,0,0,1,11,9.49L26.18.7A4.61,4.61,0,0,1,30.85.57a11.76,11.76,0,0,1,3.92,3.54l17.31,8.07.07,0c.22.1.46.2.72.33L60.71,8,89.53,57.91ZM51,48.84L60,64.58a5.05,5.05,0,0,1,6.91,1.85l11.73-6.77a5.07,5.07,0,0,1,1.85-6.91L61.7,20.14a5.08,5.08,0,0,1-6.92-1.85l-9,5.21.36.61L53.44,36.8a10.44,10.44,0,0,1,2.77-2.32,10.08,10.08,0,1,1-3.68,13.77l-.07-.12-.28.16a6.16,6.16,0,0,1-1.23.55ZM42.78,18.35,49,14.74,32.93,8a1.39,1.39,0,0,1-.55-.46,10.19,10.19,0,0,0-3.07-3,1.85,1.85,0,0,0-1.9,0L12.83,13a5.21,5.21,0,0,0-2.11,2.34c-.53,1.1-.37,2.25-.37,3.92a.31.31,0,0,1,0,.09c0,.63,0,1.45,0,2.24-.08,3.43-.52,5.4,1.15,8.64l2.6,4.29a.57.57,0,0,1,.12.18c0,.06.27.47.58,1,1.63,2.89,3.63,6.47,6.52,5.39l1.29-.75c-.45-.82-.88-1.67-1.29-2.49s-.71-1.45-1.09-2.1a1.46,1.46,0,1,1,2.53-1.47c.38.66.78,1.45,1.19,2.27,1.4,2.81,3,6,5.37,5.16l1.68-1a2.71,2.71,0,0,1,.3-.13c-.58-1-1.11-2-1.61-3-.37-.74-.71-1.44-1.09-2.1A1.46,1.46,0,1,1,31.1,34c.38.65.77,1.45,1.19,2.27,1.39,2.8,3,6,5.36,5.16l1.69-1a1.25,1.25,0,0,1,.36-.15L36.85,35.4a1.46,1.46,0,0,1,2.53-1.47l5.35,9.26c1.28,2.22,3,3.11,4.5,3a3.33,3.33,0,0,0,1.51-.45,3,3,0,0,0,.64-.48h0c0-.12.11-.18.24-.25a2.33,2.33,0,0,0,.27-.35,4.79,4.79,0,0,0,0-4.71l0-.08c-.1-.21-.22-.43-.34-.65L40.35,20a1.47,1.47,0,0,1,2.43-1.64Zm10,44.49H20.72L6.54,87H116.38l-14-24.2H85.17L68.79,72.3H83.91a2.47,2.47,0,1,1,0,4.93H42.57a2.46,2.46,0,1,1,0-4.91H58.29l-5.47-9.46Z"/>
                        </svg>
                    </i> {{ __('menu.wallet_topup') }}
                </a>
            </li> --}}

        </ul>
    </li>
{{-- @endcan
    </ul>
</li>
{{-- @endcan --}}
@can('access_user_management')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('roles*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-people" style="line-height: 1;"></i> {{ __('menu.user_management') }}
        </a>
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('users.create') ? 'c-active' : '' }}" href="{{ route('users.create') }}">
                    <i class="c-sidebar-nav-icon bi bi-person-plus" style="line-height: 1;"></i> {{ __('menu.create_user') }}
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('users*') ? 'c-active' : '' }}" href="{{ route('users.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-person-lines-fill" style="line-height: 1;"></i> {{ __('menu.all_users') }}
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('roles*') ? 'c-active' : '' }}" href="{{ route('roles.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-key" style="line-height: 1;"></i> {{ __('menu.roles_and_permissions') }}
                </a>
            </li>
        </ul>
    </li>
@endcan

{{-- @can('access_currencies|access_settings')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('currencies*') || request()->routeIs('units*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-gear" style="line-height: 1;"></i> {{ __('menu.settings') }}
        </a>
        @can('access_units')
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->routeIs('units*') ? 'c-active' : '' }}" href="{{ route('units.index') }}">
                        <i class="c-sidebar-nav-icon bi bi-calculator" style="line-height: 1;"></i> {{ __('menu.units') }}
                    </a>
                </li>
            </ul>
        @endcan
        @can('access_currencies')
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('currencies*') ? 'c-active' : '' }}" href="{{ route('currencies.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-cash-stack" style="line-height: 1;"></i> {{ __('menu.currencies') }}
                </a>
            </li>
        </ul>
        @endcan
        @can('access_settings')
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('settings*') ? 'c-active' : '' }}" href="{{ route('settings.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-sliders" style="line-height: 1;"></i> {{ __('menu.system_settings') }}
                </a>
            </li>
        </ul>
        @endcan
    </li>
@endcan --}}
@can('access_settings')
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown {{ request()->routeIs('currencies*') || request()->routeIs('units*') ? 'c-show' : '' }}">
        <a class="c-sidebar-nav-dropdown-toggle">
            <i class="c-sidebar-nav-icon bi bi-gear" style="line-height: 1;"></i> {{ __('menu.settings') }}
        </a>


        @can('access_settings')
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item">
                <a class="c-sidebar-nav-link {{ request()->routeIs('settings*') ? 'c-active' : '' }}" href="{{ route('settings.index') }}">
                    <i class="c-sidebar-nav-icon bi bi-sliders" style="line-height: 1;"></i> {{ __('menu.system_settings') }}
                </a>
            </li>
        </ul>
        @endcan
    </li>
@endcan
