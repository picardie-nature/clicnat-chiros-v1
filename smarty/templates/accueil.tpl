{include file="entete.tpl" titre="BD Chiros"}
<div class="container">
	{if !$utl}
	<div class="login">
		<form class="form-signin" role="form" method="post" action="index.php">
			<h2 class="form-signin-heading">Portail chiros</h2>
			<input name="clicnat_login" type="text" class="form-control" placeholder="Identifiant" required autofocus>
			<input name="clicnat_pwd" type="password" class="form-control" placeholder="Mot de passe" required>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Se connecter</button>
		</form>
	</div>
	{else}
		Bonjour {$utl}<br>
	{/if}
	<table class="table table-striped" width=50%>
	<thead><tr><th><b>Gîtes à prospecter</b></th></td></thead>
		{foreach from=$aprospecter item=foo}
			<tr><td>{$foo.reference}</tr></td>
		{/foreach}
	</table>
</div>
{include file="pied.tpl"}

