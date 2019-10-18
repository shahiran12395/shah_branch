        <div class="col-lg-3 col-md-6">
          <div class="left-area">
            <div class="filter-result-area">
            <div class="header-area">
              <h4 class="title">
                {{$langg->lang61}}
              </h4>
            </div>
            <div class="body-area">
              <input type="hidden" id="searchform" value="{{ route('front.search') }}">
              <form id="catalogform" action="" method="GET">
                <ul class="filter-list">
                  @foreach ($categories as $element)

                  <li>
                    <div class="content">
                        <div class="check-box">
                            <div class="form-check form-check-inline">
                              <input class="form-check-input check-cat" type="checkbox" id="{{ 'cb'.$element->id }}" value="{{ $element->id }}" {{ isset($cat_id) ? (in_array( $element->id , $cat_id) ? 'checked' : '') : ''}}>
                              <span class="checkmark"></span>
                              <label class="form-check-label" for="{{ 'cb'.$element->id }}">
                                  {{ $element->name }}
                              </label>
                            </div>
                        </div>
                    </div>
                  </li>

                  @endforeach

                </ul>
                <div class="price-range-block">
                    <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                    <div class="livecount">
                      <input type="number" min=0  name="min"  id="min_price" class="price-range-field" /> 
                      <span>{{$langg->lang62}}</span>
                      <input type="number" min=0  name="max" id="max_price" class="price-range-field" />
                    </div>
                  </div>
                  
                  <button class="filter-btn" type="submit">{{$langg->lang58}}</button>
              </form>
            </div>
            </div>

            @if(!isset($vendor))

            <div class="tags-area">
                <div class="header-area">
                    <h4 class="title">
                        {{$langg->lang63}}
                    </h4>
                  </div>
                  <div class="body-area">
                    <ul class="taglist">
                      @foreach(App\Models\Product::showTags() as $tag)
                      @if(!empty($tag))
                      <li>
                        <a class="{{ isset($tags) ? ($tag == $tags ? 'active' : '') : ''}}" href="{{ route('front.tag',$tag) }}">
                            {{ $tag }}
                        </a>
                      </li>
                      @endif
                      @endforeach
                    </ul>
                  </div>
            </div>


            @else 

            <div class="service-center">
              <div class="header-area">
                <h4 class="title">
                    {{ $langg->lang227 }}
                </h4>
              </div>
              <div class="body-area">
                <ul class="list">
                  <li>
                      <a href="javascript:;" data-toggle="modal" data-target="{{ Auth::guard('web')->check() ? '#vendorform1' : '#comment-log-reg' }}">
                          <i class="icofont-email"></i> <span class="service-text">{{ $langg->lang228 }}</span>
                      </a>
                  </li>
                  <li>
                        <a href="tel:+{{$vendor->shop_number}}">
                          <i class="icofont-phone"></i> <span class="service-text">{{$vendor->shop_number}}</span>
                        </a>
                  </li>
                </ul>
              <!-- Modal -->
              </div>

              <div class="footer-area">
                <p class="title">
                  {{ $langg->lang229 }}
                </p>
                <ul class="list">


              @if($vendor->f_check != 0)
              <li><a href="{{$vendor->f_url}}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
              @endif
              @if($vendor->g_check != 0)
              <li><a href="{{$vendor->g_url}}" target="_blank"><i class="fab fa-google"></i></a></li>
              @endif
              @if($vendor->t_check != 0)
              <li><a href="{{$vendor->t_url}}" target="_blank"><i class="fab fa-twitter"></i></a></li>
              @endif
              @if($vendor->l_check != 0)
              <li><a href="{{$vendor->l_url}}" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
              @endif


                </ul>
              </div>
            </div>


            @endif


          </div>
        </div>