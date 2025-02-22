@extends('admin.layouts.template',['menu'=>'payment-setting'])

@section('css')

@endsection

@section('page-content')
    <div class="page-content">
        <div class="panel panel-boxed">
            <div class="panel-body">
                <h3 class="panel-header">Payment Visibility Settings</h3>
                <form method="post" action="{{url('admin/savePaymentVisibility')}}">
                    @csrf
                    <div class="form-group">
                        <label>Paypal</label>
                        <select class="form-control" name="show_paypal">
                            <option value="0" {{$show_paypal==0 ? 'selected' : ''}}>Hide</option>
                            <option value="1" {{$show_paypal==1 ? 'selected' : ''}}>Show</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Stripe</label>
                        <select class="form-control" name="show_stripe">
                            <option value="0" {{$show_stripe==0 ? 'selected' : ''}}>Hide</option>
                            <option value="1" {{$show_stripe==1 ? 'selected' : ''}}>Show</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mollie</label>
                        <select class="form-control" name="show_mollie">
                            <option value="0" {{$show_mollie==0 ? 'selected' : ''}}>Hide</option>
                            <option value="1" {{$show_mollie==1 ? 'selected' : ''}}>Show</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Bitcoin</label>
                        <select class="form-control" name="show_coin">
                            <option value="0" {{$show_coin==0 ? 'selected' : ''}}>Hide</option>
                            <option value="1" {{$show_coin==1 ? 'selected' : ''}}>Show</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

