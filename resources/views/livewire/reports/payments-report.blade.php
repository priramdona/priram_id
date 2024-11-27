<div>
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form wire:submit="generateReport">
                        <div class="form-row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('report.start_date') }} <span class="text-danger">*</span></label>
                                    <input wire:model="start_date" type="date" class="form-control" name="start_date">
                                    @error('start_date')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('report.end_date') }} <span class="text-danger">*</span></label>
                                    <input wire:model="end_date" type="date" class="form-control" name="end_date">
                                    @error('end_date')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('report.payments') }}</label>
                                    <select wire:model.live="payments" class="form-control" name="payments"  id="payments" >
                                        <option value="">{{ __('report.select_payments') }}</option>
                                        <option value="sale">{{ __('report.sales') }}</option>
                                        <option value="pos">{{ __('report.sales_pos') }}</option>
                                        <option value="income">{{ __('report.income') }}</option>
                                        {{-- <option value="expense">{{ __('report.expense') }}</option> --}}
                                        <option value="selforder">{{ __('report.selforder') }}</option>
                                        {{-- <option value="quotation">{{ __('report.quotation') }}</option> --}}
                                        <option value="sale_return">{{ __('report.sale_returns') }}</option>
                                        <option value="purchase">{{ __('report.purchases') }}</option>
                                        <option value="purchase_return">{{ __('report.purchase_returns') }}</option>
                                    </select>
                                    @error('payments')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ __('report.payment_method') }}</label>
                                    <select wire:model="payment_method" class="form-control">
                                        <option value="">{{ __('report.select_payment_method') }}</option>
                                        @foreach($paymentMethods as $method)
                                            <option value="{{ $method['name'] }}">{{ $method['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_method')
                                    <span class="text-danger mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <span wire:target="generateReport" wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <i wire:target="generateReport" wire:loading.remove class="bi bi-shuffle"></i>
                                {{ __('report.filter_report') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($information->isNotEmpty())
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                            <table class="table table-bordered" style="table-layout: auto; width: 100%;" id="data-table">
                                <div wire:loading.flex class="col-12 position-absolute justify-content-center align-items-center" style="top:0;right:0;left:0;bottom:0;background-color: rgba(255,255,255,0.5);z-index: 99;">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">{{ __('report.loading') }}</span>
                                    </div>
                                </div>
                                <thead>
                                    <tr>
                                        <th>{{ __('report.date') }}</th>
                                        <th>{{ __('report.reference') }}</th>
                                        <th>{{ ucwords(str_replace('_', ' ', $payments)) }}</th>
                                        <th>{{ __('report.total') }}</th>
                                        <th>{{ __('report.payment_method') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($information as $data)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($data->date)->format('d M, Y') }}</td>
                                        <td>{{ $data->reference }}</td>
                                        <td>
                                            @if($payments == 'sale')
                                                {{ $data->sale->reference }}
                                            @elseif($payments == 'purchase')
                                                {{ $data->purchase->reference }}
                                            @elseif($payments == 'sale_return')
                                                {{ $data->saleReturn->reference }}
                                            @elseif($payments == 'purchase_return')
                                                {{ $data->purchaseReturn->reference }}
                                            @endif
                                        </td>
                                        <td>{{ format_currency($data->amount) }}</td>
                                        <td>{{ $data->payment_method }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <span class="text-danger">{{ __('report.no_data_available') }}</span>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div @class(['mt-3' => $information->hasPages()])>
                            {{ $information->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="alert alert-warning mb-0">
                            {{ __('report.no_data_available') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
