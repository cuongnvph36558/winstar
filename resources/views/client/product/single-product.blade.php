@extends('layouts.client')

@section('title', 'Single Product')

@section('content')
<section class="module">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 mb-sm-40"><a class="gallery" href="{{ asset('client/assets/images/shop/product-7.jpg') }}"><img src="{{ asset('client/assets/images/shop/product-7.jpg') }}" alt="Single Product Image"/></a>
          <ul class="product-gallery">
            <li><a class="gallery" href="{{ asset('client/assets/images/shop/product-8.jpg') }}"></a><img src="{{ asset('client/assets/images/shop/product-8.jpg') }}" alt="Single Product"/></li>
            <li><a class="gallery" href="{{ asset('client/assets/images/shop/product-9.jpg') }}"></a><img src="{{ asset('client/assets/images/shop/product-9.jpg') }}" alt="Single Product"/></li>
            <li><a class="gallery" href="{{ asset('client/assets/images/shop/product-10.jpg') }}"></a><img src="{{ asset('client/assets/images/shop/product-10.jpg') }}" alt="Single Product"/></li>
          </ul>
        </div>
        <div class="col-sm-6">
          <div class="row">
            <div class="col-sm-12">
              <h1 class="product-title font-alt">Accessories Pack</h1>
            </div>
          </div>
          <div class="row mb-20">
            <div class="col-sm-12"><span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star-off"></i></span><a class="open-tab section-scroll" href="#reviews">-2customer reviews</a>
            </div>
          </div>
          <div class="row mb-20">
            <div class="col-sm-12">
              <div class="price font-alt"><span class="amount">£20.00</span></div>
            </div>
          </div>
          <div class="row mb-20">
            <div class="col-sm-12">
              <div class="description">
                <p>The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words.</p>
              </div>
            </div>
          </div>
          <div class="row mb-20">
            <div class="col-sm-4 mb-sm-20">
              <input class="form-control input-lg" type="number" name="" value="1" max="40" min="1" required="required"/>
            </div>
            <div class="col-sm-8"><a class="btn btn-lg btn-block btn-round btn-b" href="#">Add To Cart</a></div>
          </div>
          <div class="row mb-20">
            <div class="col-sm-12">
              <div class="product_meta">Categories:<a href="#"> Man, </a><a href="#">Clothing, </a><a href="#">T-shirts</a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-70">
        <div class="col-sm-12">
          <ul class="nav nav-tabs font-alt" role="tablist">
            <li class="active"><a href="#description" data-toggle="tab"><span class="icon-tools-2"></span>Description</a></li>
            <li><a href="#data-sheet" data-toggle="tab"><span class="icon-tools-2"></span>Data sheet</a></li>
            <li><a href="#reviews" data-toggle="tab"><span class="icon-tools-2"></span>Reviews (2)</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="description">
              <p>Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages.</p>
              <p>The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words.</p>
            </div>
            <div class="tab-pane" id="data-sheet">
              <table class="table table-striped ds-table table-responsive">
                <tbody>
                  <tr>
                    <th>Title</th>
                    <th>Info</th>
                  </tr>
                  <tr>
                    <td>Compositions</td>
                    <td>Jeans</td>
                  </tr>
                  <tr>
                    <td>Size</td>
                    <td>44, 46, 48</td>
                  </tr>
                  <tr>
                    <td>Color</td>
                    <td>Black</td>
                  </tr>
                  <tr>
                    <td>Brand</td>
                    <td>Somebrand</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="tab-pane" id="reviews">
              <div class="comments reviews">
                <div class="comment clearfix">
                  <div class="comment-avatar"><img src="" alt="avatar"/></div>
                  <div class="comment-content clearfix">
                    <div class="comment-author font-alt"><a href="#">John Doe</a></div>
                    <div class="comment-body">
                      <p>The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The European languages are members of the same family. Their separate existence is a myth.</p>
                    </div>
                    <div class="comment-meta font-alt">Today, 14:55 -<span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star-off"></i></span>
                    </div>
                  </div>
                </div>
                <div class="comment clearfix">
                  <div class="comment-avatar"><img src="" alt="avatar"/></div>
                  <div class="comment-content clearfix">
                    <div class="comment-author font-alt"><a href="#">Mark Stone</a></div>
                    <div class="comment-body">
                      <p>Europe uses the same vocabulary. The European languages are members of the same family. Their separate existence is a myth.</p>
                    </div>
                    <div class="comment-meta font-alt">Today, 14:59 -<span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star"></i></span><span><i class="fa fa-star star-off"></i></span><span><i class="fa fa-star star-off"></i></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="comment-form mt-30">
                <h4 class="comment-form-title font-alt">Add review</h4>
                <form method="post">
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="sr-only" for="name">Name</label>
                        <input class="form-control" id="name" type="text" name="name" placeholder="Name"/>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label class="sr-only" for="email">Name</label>
                        <input class="form-control" id="email" type="text" name="email" placeholder="E-mail"/>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <select class="form-control">
                          <option selected="true" disabled="">Rating</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <div class="form-group">
                        <textarea class="form-control" id="" name="" rows="4" placeholder="Review"></textarea>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <button class="btn btn-round btn-d" type="submit">Submit Review</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <hr class="divider-w">
  <section class="module-small">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
          <h2 class="module-title font-alt">Related Products</h2>
        </div>
      </div>
      <div class="row multi-columns-row">
        <div class="col-sm-6 col-md-3 col-lg-3">
          <div class="shop-item">
            <div class="shop-item-image"><img src="{{ asset('client/assets/images/shop/product-11.jpg') }}" alt="Accessories Pack"/>
              <div class="shop-item-detail"><a class="btn btn-round btn-b"><span class="icon-basket">Add To Cart</span></a></div>
            </div>
            <h4 class="shop-item-title font-alt"><a href="#">Accessories Pack</a></h4>£9.00
          </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
          <div class="shop-item">
            <div class="shop-item-image"><img src="{{ asset('client/assets/images/shop/product-12.jpg') }}" alt="Men's Casual Pack"/>
              <div class="shop-item-detail"><a class="btn btn-round btn-b"><span class="icon-basket">Add To Cart</span></a></div>
            </div>
            <h4 class="shop-item-title font-alt"><a href="#">Men's Casual Pack</a></h4>£12.00
          </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
          <div class="shop-item">
            <div class="shop-item-image"><img src="{{ asset('client/assets/images/shop/product-13.jpg') }}" alt="Men's Garb"/>
              <div class="shop-item-detail"><a class="btn btn-round btn-b"><span class="icon-basket">Add To Cart</span></a></div>
            </div>
            <h4 class="shop-item-title font-alt"><a href="#">Men's Garb</a></h4>£6.00
          </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
          <div class="shop-item">
            <div class="shop-item-image"><img src="{{ asset('client/assets/images/shop/product-14.jpg') }}" alt="Cold Garb"/>
              <div class="shop-item-detail"><a class="btn btn-round btn-b"><span class="icon-basket">Add To Cart</span></a></div>
            </div>
            <h4 class="shop-item-title font-alt"><a href="#">Cold Garb</a></h4>£14.00
          </div>
        </div>
      </div>
    </div>
  </section>
  <hr class="divider-w">
  <section class="module">
    <div class="container">
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
          <h2 class="module-title font-alt">Exclusive products</h2>
          <div class="module-subtitle font-serif">The languages only differ in their grammar, their pronunciation and their most common words.</div>
        </div>
      </div>
      <div class="row">
        <div class="owl-carousel text-center" data-items="5" data-pagination="false" data-navigation="false">
          <div class="owl-item">
            <div class="col-sm-12">
              <div class="ex-product"><a href="#"><img src="{{ asset('client/assets/images/shop/product-1.jpg') }}" alt="Leather belt"/></a>
                <h4 class="shop-item-title font-alt"><a href="#">Leather belt</a></h4>£12.00
              </div>
            </div>
          </div>
          <div class="owl-item">
            <div class="col-sm-12">
              <div class="ex-product"><a href="#"><img src="{{ asset('client/assets/images/shop/product-3.jpg') }}" alt="Derby shoes"/></a>
                <h4 class="shop-item-title font-alt"><a href="#">Derby shoes</a></h4>£54.00
              </div>
            </div>
          </div>
          <div class="owl-item">
            <div class="col-sm-12">
              <div class="ex-product"><a href="#"><img src="{{ asset('client/assets/images/shop/product-2.jpg') }}" alt="Leather belt"/></a>
                <h4 class="shop-item-title font-alt"><a href="#">Leather belt</a></h4>£19.00
              </div>
            </div>
          </div>
          <div class="owl-item">
            <div class="col-sm-12">
              <div class="ex-product"><a href="#"><img src="{{ asset('client/assets/images/shop/product-4.jpg') }}" alt="Leather belt"/></a>
                <h4 class="shop-item-title font-alt"><a href="#">Leather belt</a></h4>£14.00
              </div>
            </div>
          </div>
          <div class="owl-item">
            <div class="col-sm-12">
              <div class="ex-product"><a href="#"><img src="{{ asset('client/assets/images/shop/product-5.jpg') }}" alt="Chelsea boots"/></a>
                <h4 class="shop-item-title font-alt"><a href="#">Chelsea boots</a></h4>£44.00
              </div>
            </div>
          </div>
          <div class="owl-item">
            <div class="col-sm-12">
              <div class="ex-product"><a href="#"><img src="{{ asset('client/assets/images/shop/product-6.jpg') }}" alt="Leather belt"/></a>
                <h4 class="shop-item-title font-alt"><a href="#">Leather belt</a></h4>£19.00
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
