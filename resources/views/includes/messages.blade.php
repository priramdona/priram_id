<!-- Modal -->
<div class="modal fade" id="inboxModal" tabindex="-1" aria-labelledby="inboxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inboxModalLabel"> {{ __('menu.inbox_message') }}</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    @php
                        $low_quantity_products = \Modules\Product\Entities\Product::select('id', 'product_quantity', 'product_stock_alert', 'product_code', 'product_name')->whereColumn('product_quantity', '<=', 'product_stock_alert')->get();
                        $notifications = \App\Models\MessageNotification::orderBy('is_read', 'asc')->orderBy('created_at', 'desc')->get();;
                    @endphp
                    @foreach($low_quantity_products as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-start notification-item bg-light">
                        <div class="ms-2 me-auto">
                            <div> <span class="font-weight-bold">{{ $product->product_name }}</span></div>
                            {{ __('menu.low_quantity') }}
                        </div>
                        <a href="{{ route('products.show', $product->id) }}"
                            class="btn btn-sm btn-primary mark-as-read"
                            data-id="{{ $product->id }}">
                                {{ __('menu.view') }}
                            </a>

                    </li>

                    @endforeach
                </ul>
                <ul class="list-group" id="notificationList">
                    @foreach($notifications as $notification)
                    <li class="list-group-item d-flex justify-content-between align-items-start notification-item
                        {{ !$notification->is_read ? 'bg-light' : '' }}"
                        data-id="{{ $notification->id }}">
                        <div class="ms-2 me-auto">
                            <div> <span class="font-weight-bold">{{ $notification->subject }}</span></div>
                            {{ $notification->message }}
                        </div>
                        {{-- {{ route($notification->source_type . '.show', $notification->source_id) }} --}}
                        <a href="{{ route('notifications.show', $notification->id) }}"
                        class="btn btn-sm {{ !$notification->is_read ? 'btn-primary' : 'btn-secondary' }}"
                        data-id="{{ $notification->id }}">
                            {{ __('menu.view') }}
                        </a>
                        <a href=""
                            class="btn btn-sm {{ !$notification->is_read ? 'btn-success' : 'btn-secondary' }} mark-as-read"
                            data-id="{{ $notification->id }}">
                                {{ !$notification->is_read ? __('menu.mark_read') : __('menu.read') }}
                            </a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('menu.close') }}</button>

                {{-- <button id="closeModalCheckout" type="button" class="btn btn-secondary" onclick="$('#inboxModal').modal('hide');">{{  __('menu.close') }}</button> --}}
            </div>
        </div>
    </div>
</div>


@push('page_scripts')
<script>
    $('#closeModalCheckout').on('click', function() {
        $('#inboxModal').modal('hide');
        $('body').css('pointer-events', 'auto');
    });


    document.addEventListener('DOMContentLoaded', function () {
            const notificationList = document.getElementById('notificationList');

            notificationList.addEventListener('click', function (e) {
                if (e.target.classList.contains('mark-as-read')) {
                    e.preventDefault();

                    const notificationId = e.target.getAttribute('data-id');
                    const parentItem = e.target.closest('.notification-item');

                    fetch('/notifications/mark-as-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id: notificationId }),
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.success) {
                                parentItem.classList.remove('bg-light');
                                e.target.innerText = @json(__('menu.read'), JSON_UNESCAPED_UNICODE);
                                e.target.classList.remove('btn-success');
                                e.target.classList.add('btn-secondary');
                            }
                        })
                        .catch((error) => console.error('Error:', error));
                }
            });
        });
</script>
@endpush

@push('page_css')
<style>
    .notification-item.bg-light {
        border-left: 5px solid #007bff;
        /* font-weight: bold; */
    }
    .notification-item a {
        margin-left: 15px;
    }
</style>
@endpush
