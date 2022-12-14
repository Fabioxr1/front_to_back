{*
* 2007-2022 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2022 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="panel">
	<h3><i class="icon icon-credit-card"></i> {l s='Quickly access the Backoffice' mod='fronttoback'}</h3>
	<p>
		<strong>{l s='To create links, you need to enter the name of your backoffice directory!' mod='fronttoback'}</strong><br />
		{l s='Add your secret Backoffice directory' mod='fronttoback'}<br />
		{l s='It only serves to create the link To the Backoffice.' mod='fronttoback'}
	</p>
	<br />
	<p>
		{l s='This module will speed up your changes!' mod='fronttoback'}
	</p>
</div>
{if isset($confirm)}
	{$confirm}
{/if}