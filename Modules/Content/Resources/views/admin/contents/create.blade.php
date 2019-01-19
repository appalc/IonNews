@extends('layouts.master')

@section('content-header')
	<h1>{{ trans('content::contents.title.create content') }} </h1>
	<ol class="breadcrumb">
		<li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
		<li><a href="{{ route('admin.content.content.index') }}">{{ trans('content::contents.title.contents') }}</a></li>
		<li class="active">{{ trans('content::contents.title.create content') }}</li>
	</ol>
@stop

@section('styles')
    {!! Theme::script('js/vendor/ckeditor/ckeditor.js') !!}
@stop

@section('content')
	{!! Form::open(['route' => ['admin.content.content.store'], 'method' => 'post','enctype' => 'multipart/form-data', 'onsubmit'=>'return formValidator()']) !!}
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				@include('partials.form-tab-headers')
				<div class="tab-content">
					<?php $i = 0; ?>
					@foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
						<?php $i++; ?>
						<div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
							@include('content::admin.contents.partials.create-fields', ['lang' => $locale])
						</div>
					@endforeach
					<div class="box-footer">
						<button type="submit" class="btn btn-primary btn-flat"  >{{ trans('core::core.button.create') }}</button>
							<a class="btn btn-danger pull-right btn-flat" href="{{ route('admin.content.content.index')}}">
								<i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}
							</a>
					</div>
				</div>
			</div> {{-- end nav-tabs-custom --}}
		</div>
	</div>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="width: 100%">
		<div class ="modal-dialog" role="document">
			<div id='contain_textbox' class="container table-responsive" style="width: 100%;">
				<img id ="imgview" src="" style="width: 500px;height: 400px;"></img>
			</div>
		</div>
	</div>
	{!! Form::close() !!}
@stop

@section('footer')
	<a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop

@section('shortcuts')
	<dl class="dl-horizontal">
		<dt><code>b</code></dt>
		<dd>{{ trans('core::core.back to index') }}</dd>
	</dl>
@stop

@section('scripts')
	<script type="text/javascript">
		$(document).ready(function() {
			var all_users_info = "";
			$(".user-types").hide();
			$(".img-info").hide();
			// $(".custom_img").hide();
			dateRangePickerFunctions();

			$(document).keypressAction({
				actions: [
					{key: 'b', route: "<?= route('admin.content.content.index') ?>" }
				]
			});
		});
	</script>
	<script language="javascript">
		checkedArray       = [];
		var roles_data     = [];
		var category_check = [];
		var crawlUrl       = '{{ env('APP_URL') }}/contents/ajaxcall';
		var userUrl        = '{{ env('APP_URL') }}/users';
		$(".imageview").on("click",function () {
		});

	//	user_roles          = '<?php // echo json_encode($user_roles);?>';
	//	var all_users_roles = jQuery.parseJSON(user_roles);

		// $( "select.user_group" ).change( changeorder );
		// changeorder();

		function selectCategory(event)
		{
			if (!category_check.includes(event)) {
				category_check.push(event);
			} else {
				var a = category_check.indexOf(event);
				console.log(a);
				category_check.splice(a, 1);
			}
			// category_check=event;

			console.log(category_check);
		}

		function fullViews(event)
		{
			console.log(event.src);
			$('#imgview').attr('src', event.src);
		}

		$(".multiselect").css({"width":"20em", "height":"10em", "border":"solid 1px #c0c0c0", "overflow":"auto"});
		$(".multiselect label").css({"display":"block"});
		$(".multiselect-on").css({"color":"#ffffff","background-color":"#000099"});

		jQuery.fn.multiselect = function() {
			$(this).each(function() {
				var checkboxes = $(this).find("input:checkbox");
				checkboxes.each(function() {
					var checkbox = $(this);
					// Highlight pre-selected checkboxes
					console.log("h");
					if (checkbox.prop("checked"))
						checkbox.parent().addClass("multiselect-on");

						// Highlight checkboxes that the user selects
					checkbox.click(function() {
						if (checkbox.prop("checked"))
							checkbox.parent().addClass("multiselect-on");
						else
							checkbox.parent().removeClass("multiselect-on");
					});
				});
			});
		};

		function restImageView()
		{
			$(".select_img").attr("src", "");
			$("#img_changes").val("");
		}

		function previewFile()
		{
			$('input[name="image"]').prop('checked', false);
			var preview = document.querySelector('img.select_img'); //selects the query named img
			var file    = document.querySelector('input[type=file]').files[0]; //sames as here
			var reader  = new FileReader();

			reader.onloadend = function () {
				preview.src = reader.result;
			}

			if (file) {
				reader.readAsDataURL(file); //reads the data as a URL
			} else {
				preview.src = "";
			}
		}
	</script>
@stop

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ Module::asset('content:js/content_create.js') }}?rv={{ env('RV') }}"></script>
<script type="text/javascript" src="{{ Module::asset('content:js/datepicker.js') }}?rv={{ env('RV') }}"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".content-wrapper").css({"min-height":"100%"});
	});
</script>
