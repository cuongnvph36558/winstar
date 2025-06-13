@extends('layouts.client')

@section('title', 'Checkout')

@section('content')
<section class="module">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
          <h1 class="module-title font-alt">Checkout</h1>
        </div>
      </div>
      <hr class="divider-w pt-20">
      <div class="row">
        <div class="col-sm-12">
          <table class="table table-striped table-border checkout-table">
            <tbody>
              <tr>
                <th class="hidden-xs">Item</th>
                <th>Description</th>
                <th class="hidden-xs">Price</th>
                <th>Quantity</th>
                <th>Total</th>
              </tr>
              <tr>
                <td class="hidden-xs"><a href="#"><img src="{{ asset('client/assets/images/shop/product-14.jpg') }}" alt="Accessories Pack"/></a></td>
                <td>
                  <h5 class="product-title font-alt">Accessories Pack</h5>
                </td>
                <td class="hidden-xs">
                  <h5 class="product-title font-alt">£20.00</h5>
                </td>
                <td>
                  <h5 class="product-title font-alt">1</h5>
                </td>
                <td>
                  <h5 class="product-title font-alt">£20.00</h5>
                </td>
              </tr>
              <tr>
                <td class="hidden-xs"><a href="#"><img src="{{ asset('client/assets/images/shop/product-13.jpg') }}" alt="Men's Casual Pack"/></a></td>
                <td>
                  <h5 class="product-title font-alt">Men's Casual Pack</h5>
                </td>
                <td class="hidden-xs">
                  <h5 class="product-title font-alt">£20.00</h5>
                </td>
                <td>
                  <h5 class="product-title font-alt">1</h5>
                </td>
                <td>
                  <h5 class="product-title font-alt">£20.00</h5>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <hr class="divider-w pt-20">
      <div class="row">
        <div class="col-sm-8">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="billing_first_name">First Name *</label>
                <input class="form-control" id="billing_first_name" type="text" name="billing_first_name" required/>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="billing_last_name">Last Name *</label>
                <input class="form-control" id="billing_last_name" type="text" name="billing_last_name" required/>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="billing_address">Address *</label>
            <input class="form-control" id="billing_address" type="text" name="billing_address" required/>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="billing_city">Town / City *</label>
                <input class="form-control" id="billing_city" type="text" name="billing_city" required/>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="billing_postcode">Postcode *</label>
                <input class="form-control" id="billing_postcode" type="text" name="billing_postcode" required/>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label for="billing_email">Email Address *</label>
                <input class="form-control" id="billing_email" type="email" name="billing_email" required/>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label for="billing_phone">Phone *</label>
                <input class="form-control" id="billing_phone" type="tel" name="billing_phone" required/>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="order_comments">Order Notes</label>
            <textarea class="form-control" id="order_comments" name="order_comments" rows="4"></textarea>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="shop-Cart-totalbox">
            <h4 class="font-alt">Your Order</h4>
            <table class="table table-striped table-border checkout-table">
              <tbody>
                <tr>
                  <th>Cart Subtotal :</th>
                  <td>£40.00</td>
                </tr>
                <tr>
                  <th>Shipping :</th>
                  <td>£2.00</td>
                </tr>
                <tr class="shop-Cart-totalprice">
                  <th>Total :</th>
                  <td>£42.00</td>
                </tr>
              </tbody>
            </table>
            <div class="payment-method">
              <div class="form-group">
                <div class="radio">
                  <label><input type="radio" name="payment" checked/> Direct Bank Transfer</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="payment"/> Check Payment</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="payment"/> PayPal</label>
                </div>
              </div>
            </div>
            <button class="btn btn-lg btn-block btn-round btn-d" type="submit">Place Order</button>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
