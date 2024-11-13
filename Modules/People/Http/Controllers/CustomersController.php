<?php

namespace Modules\People\Http\Controllers;

use App\Helpers\PhoneHelper;
use Exception;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;
use Modules\People\DataTables\CustomersDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\People\Entities\Customer;

use Illuminate\Support\Str;

class CustomersController extends Controller
{

    public function index(CustomersDataTable $dataTable) {
        abort_if(Gate::denies('access_customers'), 403);

        return $dataTable->render('people::customers.index');
    }


    public function getCustomerId(string $id)
    {
        $customers = Customer::find($id);

        return response()->json($customers);


    }

    public function customerSelforderUpdate(string $id, request $request)
    {
        $customer = Customer::find($id);

        $custId = Str::orderedUuid()->toString();

        $paymentGateway = new PaymentGatewayController();

        if (blank($customer->cust_id)){
            $registCustomer = $paymentGateway->createCustomer(
                reffId: $custId,
                firstName: $request->customer_first_name,
                lastName: $request->customer_last_name,
                dob: $request->dob,
                email: $request->customer_email,
                mobileNumber: $customer->customer_phone,
                gender: $request->gender,
                streetLine1: $request->address,
                city: $request->city,
                province: $request->province,
                postalCode: $request->postal_code,
                description: 'Registration From Selforder',
                businessId: $customer->business_id
            );
        }else{
            $registCustomer = $paymentGateway->updateCustomer(
                id: $customer->cust_id,
                refId: $customer->id,
                firstName: $request->customer_first_name,
                lastName: $request->customer_last_name,
                dob: $request->dob,
                email: $request->customer_email,
                mobileNumber: $customer->customer_phone,
                gender: $request->gender,
                streetLine1: $request->address,
                city: $request->city,
                province: $request->province,
                postalCode: $request->postal_code,
                description: 'Update From Selforder',
            );
        }

        $customer->update([
            'customer_name'  => $request->customer_first_name . ' ' . $request->customer_last_name,
            'customer_first_name'  => $request->customer_first_name,
            'customer_last_name'  => $request->customer_last_name,
            'customer_email' => $request->customer_email,
            'city'           => $request->city,
            'province'           => $request->province,
            'country'        => 'ID',
            'address'        => $request->address,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'postal_code' => $request->postal_code,
            'cust_id' => $registCustomer['id'],
        ]);
        return response()->json($customer);
    }

    public function create() {
        abort_if(Gate::denies('create_customers'), 403);

        return view('people::customers.create');
    }


    public function store(Request $request) {
        abort_if(Gate::denies('create_customers'), 403);

        $request->validate([
            'customer_first_name'  => 'required|string|max:255',
            'customer_last_name'  => 'required|string|max:255',
            'customer_phone' => 'required|max:255',
            'customer_email' => 'required|email|max:255',
            'city'           => 'required|string|max:255',
            'province'           => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'postalCode' => 'required|numeric|digits:5',
            'address'        => 'required|string|max:500',
        ]);

        $custId = Str::orderedUuid()->toString();

        try{

            $phoneNumber = $request->customer_phone;
            $formattedPhone = PhoneHelper::formatToE164Indonesia($phoneNumber);

            if ($formattedPhone) {
                $phoneNumber = $formattedPhone;
            } else {
                throw new \Exception('Error Phone Format');
            }
            $paymentGateway = new PaymentGatewayController();
            $registCustomer = $paymentGateway->createCustomer(
                reffId: $custId,
                firstName: $request->customer_first_name,
                lastName: $request->customer_last_name,
                dob: $request->dob,
                email: $request->customer_email,
                mobileNumber: $phoneNumber,
                gender: $request->gender,
                streetLine1: $request->address,
                city: $request->city,
                province: $request->province,
                postalCode: $request->postalCode,
                description: $request->postalCode,
            );

            // dd($registCustomer);
            $dataCustomer = [
                'id' => $custId,
                'customer_name'=> $request->customer_first_name . ' ' . $request->customer_last_name,
                'customer_first_name' => $request->customer_first_name,
                'customer_last_name' => $request->customer_last_name,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'customer_phone' => $phoneNumber,
                'customer_email' => $request->customer_email,
                'city' => $request->city,
                'province' => $request->province,
                'country' => $request->country,
                'address' => $request->address,
                'postal_code' => $request->postalCode,
                'cust_id' => $registCustomer['id'],
                'business_id' => $request->user()->business_id,
            ];

            $dataCustomer = Customer::create($dataCustomer);

        }
        catch(Exception $e){
            toast($e->getMessage(), 'error');
        }

        toast('Customer Created!', 'success');

        return redirect()->route('customers.index');
    }


    public function show(Customer $customer) {
        abort_if(Gate::denies('show_customers'), 403);

        return view('people::customers.show', compact('customer'));
    }


    public function edit(Customer $customer) {
        abort_if(Gate::denies('edit_customers'), 403);

        return view('people::customers.edit', compact('customer'));
    }


    public function update(Request $request, Customer $customer) {
        abort_if(Gate::denies('update_customers'), 403);

        $request->validate([
            'customer_first_name'  => 'required|string|max:255',
            'customer_last_name'  => 'required|string|max:255',
            'customer_phone' => 'required|max:255',
            'customer_email' => 'required|email|max:255',
            'city'           => 'required|string|max:255',
            'province'           => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'postalCode' => 'required|numeric|digits:5',
            'address'        => 'required|string|max:500',
        ]);
        try{
            $phoneNumber = $request->customer_phone;
            $formattedPhone = PhoneHelper::formatToE164Indonesia($phoneNumber);

            if ($formattedPhone) {
                $phoneNumber = $formattedPhone;
            } else {
                throw new \Exception('Error Phone Format');
            }

        $customer->update([
            'customer_name'  => $request->customer_first_name . ' ' . $request->customer_last_name,
            'customer_first_name'  => $request->customer_first_name,
            'customer_last_name'  => $request->customer_last_name,
            'customer_phone' => $phoneNumber,
            'customer_email' => $request->customer_email,
            'city'           => $request->city,
            'province'           => $request->province,
            'country'        => $request->country,
            'address'        => $request->address,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'postal_code' => $request->postalCode,
        ]);

        $paymentGateway = new PaymentGatewayController();
        $registCustomer = $paymentGateway->updateCustomer(
            id: $customer->cust_id,
            refId: $customer->id,
            firstName: $request->customer_first_name,
            lastName: $request->customer_last_name,
            dob: $request->dob,
            email: $request->customer_email,
            mobileNumber: $phoneNumber,
            gender: $request->gender,
            streetLine1: $request->address,
            city: $request->city,
            province: $request->province,
            postalCode: $request->postalCode,
            description: $request->postalCode,
        );
    }

        catch(Exception $e){
            toast($e->getMessage(), 'error');
        }

        toast('Customer Updated!', 'info');

        return redirect()->route('customers.index');
    }


    public function destroy(Customer $customer) {
        abort_if(Gate::denies('delete_customers'), 403);

        $customer->delete();

        toast('Customer Deleted!', 'warning');

        return redirect()->route('customers.index');
    }
}
