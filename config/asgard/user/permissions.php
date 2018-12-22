<?php

return [
	'user.users' => [
		'index'   => 'user::users.list user',
		'create'  => 'user::users.create user',
		'edit'    => 'user::users.edit user',
		'destroy' => 'user::users.destroy user',
	],
	'user.roles' => [
		'index'   => 'user::roles.list resource',
		'create'  => 'user::roles.create resource',
		'edit'    => 'user::roles.edit resource',
		'destroy' => 'user::roles.destroy resource',
	],
	'user.companies' => [
		'index'   => 'user::companies.list resource',
		'create'  => 'user::companies.create resource',
		'edit'    => 'user::companies.edit resource',
		'destroy' => 'user::companies.destroy resource',
	],
	'account.api-keys' => [
		'index'   => 'user::users.list api key',
		'create'  => 'user::users.create api key',
		'destroy' => 'user::users.destroy api key',
	],
];
