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
