@extends('layouts.master')

@section('content-header')
<h1> Company Board </h1>
<ol class="breadcrumb">
	<li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
	<li class="active">Companies</li>
</ol>
@stop

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
				<a href="{{ URL::route('admin.content.company.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
					<i class="fa fa-pencil"></i> New Company
				</a>
			</div>
		</div>
		<div class="box box box-primary">
			<div class="box-header"></div>
			<!-- /.box-header -->
			<div class="box-body">
				<table class="data-table table table-bordered table-hover">
					<thead>
						<tr>
							<td>Id</td>
							<th>Name</th>
							<th>Status</th>
							<th>User Limit</th>
							<th>Created At</th>
							<th data-sortable="false">{{ trans('user::users.table.actions') }}</th>
						</tr>
					</thead>
					<tbody>
					<?php if (isset($companies)): ?>
						<?php foreach ($companies as $company): ?>
							<tr>
								<td>
									<a href="{{ URL::route('admin.content.company.edit', [$company->id]) }}"> {{ $company->id }} </a>
								</td>
								<td>
									<a href="{{ URL::route('admin.content.company.edit', [$company->id]) }}"> {{ $company->name }} </a>
								</td>
								<td>
									<a href="{{ URL::route('admin.content.company.edit', [$company->id]) }}">
										{{ ($company->status) ? 'Enabled' : 'Disabled' }}
									</a>
								</td>
								<td>
									<a href="{{ URL::route('admin.content.company.edit', [$company->id]) }}"> {{ $company->user_limit }} </a>
								</td>
								<td>
									<a href="{{ URL::route('admin.content.company.edit', [$company->id]) }}"> {{ $company->created_at }} </a>
								</td>
								<td>
									<div class="btn-group">
										<a href="{{ route('admin.content.company.edit', [$company->id]) }}" class="btn btn-default btn-flat">
											<i class="fa fa-pencil"></i>
										</a>
										<button class ="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.content.company.destroy', [$company->id]) }}"><i class="fa fa-trash"></i></button>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
					<tfoot>
						<tr>
							<td>Id</td>
							<th>Name</th>
							<th>Status</th>
							<th>User Limit</th>
							<th>Created At</th>
							<th>{{ trans('user::users.table.actions') }}</th>
						</tr>
					</tfoot>
				</table>
				<!-- /.box-body -->
			</div>
		<!-- /.box -->
		</div>
	<!-- /.col (MAIN) -->
	</div>
</div>
	@include('core::partials.delete-modal')
@stop

@section('scripts')
	<?php $locale = App::getLocale(); ?>
	<script type="text/javascript">
	$( document ).ready(function() {
		$(document).keypressAction({
			actions: [
				{ key: 'c', route: "<?= route('admin.content.company.create') ?>" }
			]
		});
	});
	$(function () {
		$('.data-table').dataTable({
			"paginate"    : true,
			"lengthChange": true,
			"filter"      : true,
			"sort"        : true,
			"info"        : true,
			"autoWidth"   : true,
			"order"       : [[ 0, "desc" ]],
			"language"    : {
			"url"         : '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
			}
		});
	});
</script>
@stop
