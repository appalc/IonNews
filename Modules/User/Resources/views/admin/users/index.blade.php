<?php
use Illuminate\Support\Arr;
?>

@extends('layouts.master')

@section('content-header')
<h1>
	{{ trans('user::users.title.users') }}
</h1>
<ol class="breadcrumb">
	<li>
		<a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a>
	</li>
	<li class ="active">{{ trans('user::users.breadcrumb.users') }}</li>
</ol>
@stop

@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
				<a href="{{ URL::route('admin.user.user.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
					<i class="fa fa-pencil"></i> {{ trans('user::users.button.new-user') }}
				</a>
			</div>
		</div>
		<div class="box box-primary">
			<div class="box-header" style="padding:0;">
				<div class="col-md-8 text-left" style="padding: 1.5%;">
					<label> Show 
						<select class="input-sm">
							<?php
							foreach ([10, 25, 50, 100] as $count) {
								$redirctLoc = route('admin.content.content.index', ['count' => $count]);
								$selected   = ($recordCount == $count) ? "selected" : '';
							?>
							<option onclick="location.href='{{ $redirctLoc }}'" value="{{ $count }}" {{ $selected }} >
								{{ $count }}
							</option>
							<?php } ?>
						</select> records
					</label>
				</div>
				<div class="col-md-4 text-right">
					{{ $users->render() }}
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<table class="data-table table table-bordered table-hover">
					<thead>
						<tr>
							<th>Id</th>
							<th>{{ trans('user::users.table.first-name') }}</th>
							<th>{{ trans('user::users.table.last-name') }}</th>
							<th>{{ trans('user::users.table.email') }}</th>
							<th>User Group</th>
							<th>{{ trans('user::users.table.created-at') }}</th>
							<th data-sortable="false">{{ trans('user::users.table.actions') }}</th>
						</tr>
					</thead>
					<tbody>
					<?php if (isset($users)): ?>
						<?php foreach ($users as $user): ?>
							<tr>
								<td>
									<a href="{{ URL::route('admin.user.user.edit', [$user->id]) }}"> {{ $user->id }} </a>
								</td>
								<td>
									<a href="{{ URL::route('admin.user.user.edit', [$user->id]) }}"> {{ $user->first_name }} </a>
								</td>
								<td>
									<a href="{{ URL::route('admin.user.user.edit', [$user->id]) }}"> {{ $user->last_name }} </a>
								</td>
								<td>
									<a href="{{ URL::route('admin.user.user.edit', [$user->id]) }}"> {{ $user->email }} </a>
								</td>
								<td>
									<a href="{{ URL::route('admin.user.user.edit', [$user->id]) }}">
										{{ !empty($userGroups[$user->user_group_id]) ? $userGroups[$user->user_group_id] : '' }}
									</a>
								</td>
								<td>
									<a href="{{ URL::route('admin.user.user.edit', [$user->id]) }}"> {{ $user->created_at }} </a>
								</td>
								<td>
									<div class="btn-group">
										<a href="{{ route('admin.user.user.edit', [$user->id]) }}" class="btn btn-default btn-flat">
											<i class="fa fa-pencil"></i>
										</a>
										<?php if ($user->id != $currentUser->id): ?>
											<button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.user.user.destroy', [$user->id]) }}">
												<i class="fa fa-trash"></i>
											</button>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
					</tbody>
					<tfoot>
						<tr>
							<th>Id</th>
							<th>{{ trans('user::users.table.first-name') }}</th>
							<th>{{ trans('user::users.table.last-name') }}</th>
							<th>{{ trans('user::users.table.email') }}</th>
							<th>User Group</th>
							<th>{{ trans('user::users.table.created-at') }}</th>
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
	$(document).ready(function() {
		$(document).keypressAction({
			actions: [
				{ key: 'c', route: "<?= route('admin.user.user.create') ?>" }
			]
		});
	});
</script>
@stop
