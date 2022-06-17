<?php $title = "Drive LBR"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/usersmoderation.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>

<!-- Content -->
<article>

	<div class = toolbar>
		<h2>Utilisateurs :</h2>
		<div class = groupe2>
			<button title="Trier les fichiers par ordre alphabétique" onclick = "">A-Z</button>
			<p>Rôle</p>
		</div>
	</div>

	<div id="liste" >
		<form action = "index.php?action=usersModeration" method= "post">
			<table>
				<?php
				foreach ($users_table as $user)
				{	?>
					<tr>
						<td class="checkbox">
							<input type="checkbox" name="<?= $user['email'];?>">
						</td>
						<td>
							<?= $user['prenom']; ?>
						</td>
						<td>
							<?= $user['nom']; ?>
						</td>
						<td>
							<?= $user['role']; ?>
						</td>
						<td class="email">
							<?= $user['email']; ?>
						</td>
						<td class="description">
							<?= $user['descriptif']; ?>
						</td>
						<td>
							<a name="button" href="index.php?action=editRights&for=<?= $user['email'];?>">test</a>
						</td>
					</tr>
					<?php
				}
				?>
			</table>
			<span id="modifier">
				<input type="submit" name="button" value="ajouter">
				<input type="submit" name="button" value="supprimer">
			</span>
		</form>
	</div>

</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>