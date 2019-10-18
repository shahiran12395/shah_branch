@extends('layouts.admin')


@section('styles')

    <style type="text/css">
        .img-upload #image-preview {
            background-size: unset !important;
        }
    </style>

@endsection

@section('content')

    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Payment Informations') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Payment Settings') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-gs-payments') }}">{{ __('Payment Informations') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content social-links-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="gocover"
                                 style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                            <form action="{{ route('admin-gs-update-payment') }}" id="geniusform" method="POST"
                                  enctype="multipart/form-data">
                                {{ csrf_field() }}

                                @include('includes.admin.form-both')


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Stripe') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select class="process select droplinks {{ $gs->stripe_check == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                        value="{{route('admin-gs-stripe',1)}}" {{ $gs->stripe_check == 1 ? 'selected' : '' }}>{{ __('Activated') }}</option>
                                                <option data-val="0"
                                                        value="{{route('admin-gs-stripe',0)}}" {{ $gs->stripe_check == 0 ? 'selected' : '' }}>{{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Stripe Key') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field" placeholder="{{ __('Stripe Key') }}"
                                               name="stripe_key" value="{{ $gs->stripe_key }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Stripe Secret') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field" placeholder="{{ __('Stripe Secret') }}"
                                               name="stripe_secret" value="{{ $gs->stripe_secret }}" required="">
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Stripe Text') }} *</h4>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <textarea class="input-field" name="stripe_text"
                                                  placeholder="{{ __('Stripe Text') }}">{{ $gs->stripe_text }}</textarea>

                                    </div>
                                </div>


                                <hr>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Paypal') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select class="process select droplinks {{ $gs->paypal_check == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                        value="{{route('admin-gs-paypal',1)}}" {{ $gs->paypal_check == 1 ? 'selected' : '' }}>{{ __('Activated') }}</option>
                                                <option data-val="0"
                                                        value="{{route('admin-gs-paypal',0)}}" {{ $gs->paypal_check == 0 ? 'selected' : '' }}>{{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Paypal User Name') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Paypal User Name') }}" name="paypal_username"
                                               value="{{ $gs->paypal_username }}" required="">
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Paypal Password') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field" placeholder="{{ __('Paypal Password') }}"
                                               name="paypal_password" value="{{ $gs->paypal_password }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Paypal Secret Key') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Paypal Secret Key') }}" name="paypal_secret"
                                               value="{{ $gs->paypal_secret }}" required="">
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Paypal Text') }} *</h4>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <textarea class="input-field" name="paypal_text"
                                                  placeholder="{{ __('Paypal Text') }}">{{ $gs->paypal_text }}</textarea>

                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Paypal Sandbox Check') }} *
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="switch">
                                            <input type="checkbox" name="paypal_mode"
                                                   value="1" {{ $gs->paypal_mode == 'sandbox' ? "checked":"" }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>

                                <hr>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Billplz') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select class="process select droplinks {{ $gs->billplz_check == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                        value="{{route('admin-gs-billplz',1)}}" {{ $gs->billplz_check == 1 ? 'selected' : '' }}>{{ __('Activated') }}</option>
                                                <option data-val="0"
                                                        value="{{route('admin-gs-billplz',0)}}" {{ $gs->billplz_check == 0 ? 'selected' : '' }}>{{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Billplz Key') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Billplz API Key') }}" name="billplz_key"
                                               value="{{ $gs->billplz_key }}" required="">
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Billplz x-signature') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field" placeholder="{{ __('Billplz x-signature') }}"
                                               name="billplz_x_signature" value="{{ $gs->billplz_x_signature }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Billplz Callback URL') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field" placeholder="{{ __('Billplz x-signature') }}"
                                               name="billplz_callback_url" value="{{ $gs->billplz_callback_url }}" required="">
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Billplz Sandbox Check') }} *
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="switch">
                                            <input type="checkbox" name="billplz_mode"
                                                   value="1" {{ $gs->paypal_mode == 'sandbox' ? "checked":"" }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>


                                <hr>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Instamojo') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select class="process select droplinks {{ $gs->is_instamojo == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                        value="{{route('admin-gs-instamojo',1)}}" {{ $gs->is_instamojo == 1 ? 'selected' : '' }}>{{ __('Activated') }}</option>
                                                <option data-val="0"
                                                        value="{{route('admin-gs-instamojo',0)}}" {{ $gs->is_instamojo == 0 ? 'selected' : '' }}>{{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Instamojo API Key ') }}*
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Instamojo API Key') }}" name="instamojo_key"
                                               value="{{ $gs->instamojo_key }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Instamojo Auth Token') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Instamojo Auth Token') }}" name="instamojo_token"
                                               value="{{ $gs->instamojo_token }}" required="">
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Instamojo Text') }} *</h4>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <textarea class="input-field" name="instamojo_text"
                                                  placeholder="{{ __('Instamojo Text') }}">{{ $gs->instamojo_text }}</textarea>

                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Instamojo Sandbox Check') }} *
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="switch">
                                            <input type="checkbox" name="instamojo_sandbox"
                                                   value="1" {{ $gs->instamojo_sandbox == 1 ? "checked":"" }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>


                                <hr>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Paystack') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select class="process select droplinks {{ $gs->is_paystack == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                        value="{{route('admin-gs-paystack',1)}}" {{ $gs->is_paystack == 1 ? 'selected' : '' }}>{{ __('Activated') }}</option>
                                                <option data-val="0"
                                                        value="{{route('admin-gs-paystack',0)}}" {{ $gs->is_paystack == 0 ? 'selected' : '' }}>{{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Paystack Public Key') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Paystack Public Key') }}" name="paystack_key"
                                               value="{{ $gs->paystack_key }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Paystack Business Email') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Paystack Business Email') }}" name="paystack_email"
                                               value="{{ $gs->paystack_email }}" required="">
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Paystack Text') }} *</h4>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <textarea class="input-field" name="paystack_text"
                                                  placeholder="{{ __('Paystack Text') }}">{{ $gs->paystack_text }}</textarea>

                                    </div>
                                </div>


                                <hr>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Guest Checkout') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select class="process select droplinks {{ $gs->guest_checkout == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                        value="{{route('admin-gs-guest',1)}}" {{ $gs->guest_checkout == 1 ? 'selected' : '' }}>{{ __('Activated') }}</option>
                                                <option data-val="0"
                                                        value="{{route('admin-gs-guest',0)}}" {{ $gs->guest_checkout == 0 ? 'selected' : '' }}>{{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">
                                                {{ __('Cash On Delivery') }}
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="action-list">
                                            <select class="process select droplinks {{ $gs->cod_check == 1 ? 'drop-success' : 'drop-danger' }}">
                                                <option data-val="1"
                                                        value="{{route('admin-gs-cod',1)}}" {{ $gs->cod_check == 1 ? 'selected' : '' }}>{{ __('Activated') }}</option>
                                                <option data-val="0"
                                                        value="{{route('admin-gs-cod',0)}}" {{ $gs->cod_check == 0 ? 'selected' : '' }}>{{ __('Deactivated') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Cash On Delivery Text') }} *</h4>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <textarea class="input-field" name="cod_text"
                                                  placeholder="{{ __('Cash On Delivery Text') }}">{{ $gs->cod_text }}</textarea>

                                    </div>
                                </div>
                                <hr>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Currency Format') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <select name="currency_format" required="">
                                            <option value="0" {{ $gs->currency_format == 0 ? 'selected' : '' }}>{{__('Before Price')}}</option>
                                            <option value="1" {{ $gs->currency_format == 1 ? 'selected' : '' }}>{{ __('After Price') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Withdraw Fee') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field" placeholder="{{ __('Withdraw Fee') }}"
                                               name="withdraw_fee" value="{{ $gs->withdraw_fee }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Withdraw Charge(%)') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Withdraw Charge(%)') }}" name="withdraw_charge"
                                               value="{{ $gs->withdraw_charge }}" required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Tax(%)') }} *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field" placeholder="{{ __('Tax(%)') }}"
                                               name="tax" value="{{ $gs->tax }}" required="">
                                    </div>
                                </div>

                                <hr>

                                <h4 class="text-center">{{ __('Vendor') }}</h4>

                                <hr>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Fixed Commission') }} *
                                            </h4>
                                            <p class="sub-heading">{{ __('Fixed Commission Charge(Product Price + Commission)') }}</p>
                                            <p class="sub-heading">{{ __("(If you don't want to add any fixed commission, just set it to 0)") }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Fixed Commission') }}" name="fixed_commission"
                                               value="{{ $gs->fixed_commission }}" required="">
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Percentage Commission(%)') }} *
                                            </h4>
                                            <p class="sub-heading">{{ __('Percentage Commission Charge(Product Price + Commission(%))') }}</p>
                                            <p class="sub-heading">{{ __("(If you don't want to add any percentage commission, just set it to 0)") }}</p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="input-field"
                                               placeholder="{{ __('Percentage Commission') }}"
                                               name="percentage_commission" value="{{ $gs->percentage_commission }}"
                                               required="">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Multiple Shipping') }} *
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="switch">
                                            <input type="checkbox" name="multiple_shipping"
                                                   value="1" {{ $gs->multiple_shipping == 1 ? "checked":"" }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Shipping Information For Vendor') }} *
                                            </h4>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="switch">
                                            <input type="checkbox" name="vendor_ship_info"
                                                   value="1" {{ $gs->vendor_ship_info == 1 ? "checked" : "" }}>
                                            <span class="slider round"></span>
                                        </label>
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection