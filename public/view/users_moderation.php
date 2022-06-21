<?php $title = "Drive LBR"; ?>
<?php $stylesheets = "<link rel=\"stylesheet\" href=\"public/css/users_moderation.css\">" ?>
<?php $scripts = "" ?>

<?php require('public/view/banner-menu.php'); ?>

<?php ob_start(); ?>

<!-- Content -->
<article>

	<h2>Utilisateurs :</h2>

	<form action = "index.php?action=deleteUser" method= "post">
		<table>
			<tr>
				<th>
					<button title="Trier les fichiers par ordre alphabétique" onclick="">A-Z</button>
				</th>
				<th>
					<p>Rôle</p>
				</th>
			</tr>
			<?php
			foreach ($users_table as $user)
			{	?>
				<tr>
					<td class="checkbox">
						<input type="checkbox" name="<?= $user['email'];?>">
					</td>
					<td class="prenom">
						<?= $user['prenom']; ?>
					</td>
					<td class="nom">
						<?= $user['nom']; ?>
					</td>
					<td class="role">
						<?= $user['role']; ?>
					</td>
					<td class="email">
						<?= $user['email']; ?>
					</td>
					<td class="description">
						<?= $user['descriptif']; ?>
					</td>
					<td>
						<a title="Accède à la page pour modifier l’utilisateur <?= $user['prenom']." ".$user['nom']; ?>" href="index.php?action=editRights&for=<?= $user['email'];?>">🖉 Modifier</a>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<span>
			<a title="Accède à la page pour créer un utilisateur" href="index.php?action=addUserPage">+ Ajouter</a>
			<button type="submit">Supprimer</button>
		</span>
	</form>


</article>

<?php $content = ob_get_clean(); ?>

<?php require('layout.php') ?>