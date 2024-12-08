<button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
    <i class="bi bi-list" style="font-size: 2rem;"></i>
</button>

<button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
    <i class="bi bi-list" style="font-size: 2rem;"></i>
</button>

<ul class="c-header-nav ml-auto mr-4">
    @can('create_pos_sales')
    <li class="c-header-nav-item mr-3">
        <a class="btn btn-primary btn-pill {{ request()->routeIs('app.pos.index') ? 'disabled' : '' }}" href="{{ route('app.pos.index') }}">
            <i class="bi bi-cart mr-1"></i> {{ __('menu.pos') }}
        </a>
    </li>
    @endcan

    @can('show_notifications')
    <li class="c-header-nav-item mr-3">
        <a class="c-header-nav-link" href="#" role="button" data-toggle="modal" data-target="#inboxModal">
            <i class="bi bi-envelope" style="font-size: 20px;"></i>
            @php
                $low_quantity_products = \Modules\Product\Entities\Product::select('id', 'product_quantity', 'product_stock_alert', 'product_code', 'product_name')->whereColumn('product_quantity', '<=', 'product_stock_alert')->get();
                $message_notifications = \App\Models\MessageNotification::where('is_read', false)->get();
            @endphp
            @if($low_quantity_products->isNotEmpty() || $message_notifications->isNotEmpty())
               <span class="badge badge-pill badge-danger">
            @else
                <span class="badge badge-pill">
            @endif

            @php
                echo $low_quantity_products->count() + $message_notifications->count();
            @endphp
            </span>
        </a>

    </li>
    @endcan

    <!-- Modal -->

    {{-- <li class="c-header-nav-item dropdown">
        <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="bi bi-envelope" style="font-size: 20px;"></i>
            @php
                $low_quantity_products = \Modules\Product\Entities\Product::select('id', 'product_quantity', 'product_stock_alert', 'product_code', 'product_name')->whereColumn('product_quantity', '<=', 'product_stock_alert')->get();
                $message_notifications = \App\Models\MessageNotification::where('is_read', false)->get();
            @endphp
            @if($low_quantity_products->isNotEmpty() || $message_notifications->isNotEmpty())
               <span class="badge badge-pill badge-danger">
            @else
                <span class="badge badge-pill">
            @endif

            @php
                echo $low_quantity_products->count() + $message_notifications->count();
            @endphp
            </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg pt-0">
            <div class="dropdown-header bg-light">
                <strong>{{ $low_quantity_products->count() }} Inbox</strong>
            </div>

            @if($low_quantity_products->isNotEmpty() || $message_notifications->isNotEmpty())
                @foreach($low_quantity_products as $product)
                <a class="dropdown-item" href="{{ route('products.show', $product->id) }}">
                    <i class="bi bi-hash mr-1 text-primary"></i>
                    <span class="font-weight-bold">{{ $product->product_name }}</span> is low in quantity!
                </a>
                @endforeach
                @foreach($message_notifications as $messages)
                    <a class="dropdown-item" href="{{ route('notifications.show', $messages->id) }}">
                        <i class="bi bi-hash mr-1 text-primary"></i>
                        <span class="font-weight-bold">{{ $messages->subject }}</span> {{ $messages->message }}
                    </a>
                @endforeach
            @else
                <a class="dropdown-item" href="#">
                    <i class="bi bi-app-indicator mr-2"></i> No inboxes available.
                </a>
            @endif

        </div>
    </li> --}}
    <li class="c-header-nav-item dropdown">

        <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button"
           aria-haspopup="true" aria-expanded="false">
            <div class="c-avatar mr-2">
                <img class="c-avatar rounded-circle" src="{{ auth()->user()->image_url }}" alt="Profile Image">
            </div>
            <div class="d-flex flex-column">
                <span  style="font-size: 8px;"class="font-weight-bold">{{ auth()->user()->name }}</span>
                <span style="font-size: 8px;" class="font-italic">{{ auth()->user()->email }} <i class="bi bi-circle-fill text-success" style="font-size: 8px;"></i></span>
            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right pt-0">

            <div class="dropdown-header bg-light py-2"><strong> {{ __('menu.account') }}</strong></div>

            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                <i class="mfe-2  bi bi-person" style="font-size: 1.2rem;"></i> {{ __('menu.profile') }}
            </a>
            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="mfe-2  bi bi-box-arrow-left" style="font-size: 1.2rem;"></i> {{ __('menu.logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </li>
</ul>

