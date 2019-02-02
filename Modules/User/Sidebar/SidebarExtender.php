<?php

namespace Modules\User\Sidebar;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\User\Contracts\Authentication;

class SidebarExtender implements \Maatwebsite\Sidebar\SidebarExtender
{
	/**
	* @var Authentication
	*/
	protected $auth;
	
	/**
	* @param Authentication $auth
	*
	* @internal param Guard $guard
	*/
	public function __construct(Authentication $auth)
	{
		$this->auth = $auth;
	}

	/**
	* @param Menu $menu
	*
	* @return Menu
	*/
	public function extendWith(Menu $menu)
	{
		$menu->group('Company', function (Group $group) {
			$group->item('Company Board', function (Item $item) {
				$item->weight(0);
				$item->icon('fa fa-building');
				$item->authorize($this->auth->hasAccess('companies.index'));

				$item->item('Company', function (Item $item) {
					$item->weight(0);
					$item->icon('fa fa-building');
					$item->append('admin.content.company.create');
					$item->route('admin.content.company.index');
					$item->authorize($this->auth->hasAccess('companies.index'));
				});

				$item->item('Company Group', function (Item $item) {
					$item->weight(0);
					$item->icon('fa fa-sitemap');
					$item->append('admin.content.companygroup.create');
					$item->route('admin.content.companygroup.index');
					$item->authorize($this->auth->hasAccess('companygroups.index'));
				});

				$item->item('User Group', function (Item $item) {
					$item->weight(1);
					$item->icon('fa fa-group');
					$item->weight(0);
					$item->append('admin.content.usergroup.create');
					$item->route('admin.content.usergroup.index');
					$item->authorize($this->auth->hasAccess('usergroups.index'));
				});

				$item->item('Skin', function (Item $item) {
					$item->weight(1);
					$item->icon('fa fa-vcard-o');
					$item->weight(0);
					$item->append('admin.content.skin.create');
					$item->route('admin.content.skin.index');
					$item->authorize($this->auth->hasAccess('skins.index'));
				});

				$item->item(trans('content::feedback.title.feedback'), function (Item $item) {
					$item->icon('fa fa-question-circle-o');
					$item->weight(0);
					$item->append('admin.content.feedback.create');
					$item->route('admin.content.feedback.index');
					$item->authorize(
						$this->auth->hasAccess('content.feedback.index')
					);
				});

			});
		});

		$menu->group(trans('workshop::workshop.title'), function (Group $group) {
			$group->item(trans('user::users.title.users'), function (Item $item) {
				$item->weight(0);
				$item->icon('fa fa-users');
				$item->authorize(
					$this->auth->hasAccess('user.users.index') or $this->auth->hasAccess('user.roles.index')
				);

				$item->item(trans('user::users.title.users'), function (Item $item) {
					$item->weight(0);
					$item->icon('fa fa-users');
					$item->route('admin.user.user.index');
					$item->authorize(
						$this->auth->hasAccess('user.users.index')
					);
				});

				$item->item(trans('user::roles.title.roles'), function (Item $item) {
					$item->weight(1);
					$item->icon('fa fa-flag-o');
					$item->route('admin.user.role.index');
					$item->authorize(
						$this->auth->hasAccess('user.roles.index')
					);
				});
			});
		});

		$menu->group(trans('user::users.my account'), function (Group $group) {
			$group->weight(110);
			$group->item(trans('user::users.profile'), function (Item $item) {
				$item->weight(0);
				$item->icon('fa fa-user');
				$item->route('admin.account.profile.edit');
			});

			$group->item(trans('user::users.api-keys'), function (Item $item) {
				$item->weight(1);
				$item->icon('fa fa-key');
				$item->route('admin.account.api.index');
				$item->authorize(
					$this->auth->hasAccess('account.api-keys.index')
				);
			});
		});

		return $menu;
	}
}
