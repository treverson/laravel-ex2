@extends('admin.layouts.master')
@section('content')	
{{ HTML::script('assets/js/colorPicker.js') }}
<h2>{{trans('admin_texts.fee_buy_sell')}}</h2>
@if ( Session::get('error') )
      <div class="alert alert-error">{{{ Session::get('error') }}}</div>
	@endif
	@if ( Session::get('success') )
      <div class="alert alert-success">{{{ Session::get('success') }}}</div>
	@endif

	@if ( Session::get('notice') )
	      <div class="alert">{{{ Session::get('notice') }}}</div>
	@endif



	
	<a href="#" id="add_fee_market_link">{{trans('admin_texts.add_market_fee')}}</a>

<form class="form-horizontal" role="form" id="add_fee_market" method="POST" action="/admin/add-fee">
	<input type="hidden" name="_token" value="{{{ Session::token() }}}">
	<div class="form-group">
        <label for="fee_market_id" class="col-sm-2 control-label">Market</label>
        <div class="col-sm-10">
            <select class="form-control" name="fee_market_id" id="fee_market_id">
				<option value="">---Select market ---</option>
                @foreach ($market_list as $key => $val)
                <option value="{{{$key}}}">{{{$val}}}</option>
                @endforeach
            </select>
        </div>
    </div>  

	<div class="form-group">
	    <label for="add_buy_fee" class="col-sm-2 control-label">{{trans('admin_texts.buy_fee')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" class="form-control" name="add_buy_fee" id="add_buy_fee">
			  <span class="add-on">%</span>
			</div>	      	      
	    </div>
	</div>	
	<div class="form-group">
	    <label for="add_sell_fee" class="col-sm-2 control-label">{{trans('admin_texts.sell_fee')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" class="form-control" id="add_sell_fee" name="add_sell_fee">
			  <span class="add-on">%</span>
			</div>	      
	    </div>
	</div>
	
	<div class="form-group">		
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-primary" id="do_add">Add</button>
	    </div>
	</div>
</form>



	
<form class="form-horizontal" role="form" id="edit_fee_trade" method="POST" action="/admin/set-fee-trade">	
<input type="hidden" name="_token" value="{{{ Session::token() }}}">
	<label>{{trans('admin_texts.market')}}: <strong><span class="market_edit"></span></strong></label> 
	<div class="form-group">
	    <label for="buy_fee" class="col-sm-2 control-label">{{trans('admin_texts.buy_fee')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" class="form-control" name="buy_fee" id="buy_fee">
			  <span class="add-on">%</span>
			</div>	      	      
	    </div>
	</div>	
	<div class="form-group">
	    <label for="sell_fee" class="col-sm-2 control-label">{{trans('admin_texts.sell_fee')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" class="form-control" id="sell_fee" name="sell_fee">
			  <span class="add-on">%</span>
			</div>	      
	    </div>
	</div>
	  
	<div class="form-group">
		<input type="hidden" class="form-control" id="market_id" name="market_id">
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-primary" id="do_edit">{{trans('admin_texts.save')}}</button>
	    </div>
	</div>
</form>
<div id="messages"></div>
<table class="table table-striped" id="list-fees">
	<tr>
	 	<th>{{trans('admin_texts.market')}}</th>
	 	<th>{{trans('admin_texts.buy_fee')}}</th>
	 	<th>{{trans('admin_texts.sell_fee')}}</th>
	 	<th>{{trans('admin_texts.action')}}</th>
	</tr> 
	@foreach($fee_trades as $fee_trade)
	<tr>
		<td><strong>{{$markets[$fee_trade->market_id]['wallet_from'].'/'.$markets[$fee_trade->market_id]['wallet_to']}}</strong></td>
		<td>{{$fee_trade->fee_buy}}%</td>
		<td>{{$fee_trade->fee_sell}}%</td>
		<td><a href="#" class="edit_fee" data-market-id="{{$fee_trade->market_id}}" data-market="{{$markets[$fee_trade->market_id]['wallet_from'].'/'.$markets[$fee_trade->market_id]['wallet_to']}}" data-fee-sell="{{$fee_trade->fee_sell}}" data-fee-buy="{{$fee_trade->fee_buy}}">{{trans('admin_texts.edit')}}</a></td>
	</tr>
	@endforeach 
</table>
{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript">
(function($){ 
	$(document).ready(function(){
		$("#edit_fee_trade").validate({
                rules: {
                	sell_fee: {
				      required: true,
				      number: true
				    },
				    buy_fee: {
				      required: true,
				      number: true
				    }                   
                },
                messages: {
                    sell_fee: {
                        required: "Please enter sell fee.",
                        number: "Please enter a number."
                    },
                    buy_fee: {
                        required: "Please enter buy fee.",
                        number: "Please enter a number."
                    }                    
                }
             });   
		$('#edit_fee_trade').hide();
		$('#list-fees .edit_fee').click(function(e) {
			$('body,html').animate({scrollTop:0},800);
			$('#edit_fee_trade').show();
			var market = $(this).attr('data-market');
			var sell_fee = $(this).attr('data-fee-sell');
			var buy_fee = $(this).attr('data-fee-buy');
			var market_id = $(this).attr('data-market-id');
			$('#edit_fee_trade .market_edit').html(market);
			$('#edit_fee_trade #sell_fee').val(sell_fee);
			$('#edit_fee_trade #buy_fee').val(buy_fee);
			$('#edit_fee_trade #market_id').val(market_id);
		});
		/*$('#edit_fee_trade #do_edit').click(function(e) {
			$('#edit_fee_trade').hide();
			$('#messages').html("<p class='success'>Save successfully!</p>");
		});*/
		
		
		
///////Market fee
    	$('#add_fee_market').hide();
        $('#add_fee_market_link').click(function(event) {
        	$('#add_fee_market').toggle("slow");
        });
        $("#add_fee_market").validate({
            rules: {
                add_fee_buy: {
                    required: true,
					number: true
                },
                add_fee_sell: {
                    required: true,
					number: true
                },
				fee_market_id: {
                    required: true,
                },
            },
            messages: {
                add_fee_buy: {
                        required: "Please enter buy fee.",
                        number: "Please enter a number."
                },
                add_fee_sell: {
                        required: "Please enter sell fee.",
                        number: "Please enter a number."
                },
				fee_market_id: {
                    required: "Please provide a market to add the fee."
                },
            }
		});

   
	});
})(jQuery);
</script>
@stop