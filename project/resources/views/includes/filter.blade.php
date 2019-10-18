						<div class="item-filter">

							<ul class="filter-list">
								<li class="item-short-area">
									<p>{{$langg->lang64}} :</p>
									<select id="sortby" class="short-item">
		                                @if($sort == "new")
		                                    <option value="new" selected>{{$langg->lang65}}</option>
		                                @else
		                                    <option value="new">{{$langg->lang65}}</option>
		                                @endif
		                                @if($sort == "old")
		                                    <option value="old" selected>{{$langg->lang66}}</option>
		                                @else
		                                    <option value="old">{{$langg->lang66}}</option>
		                                @endif
		                                @if($sort == "low")
		                                    <option value="low" selected>{{$langg->lang67}}</option>
		                                @else
		                                    <option value="low">{{$langg->lang67}}</option>
		                                @endif
		                                @if($sort == "high")
		                                    <option value="high" selected>{{$langg->lang68}}</option>
		                                @else
		                                    <option value="high">{{$langg->lang68}}</option>
		                                @endif
									</select>
								</li>
							</ul>
						</div>