<?
/* load settings */
if (!isset($_CONFIG))
	require 'config.php';

require 'connectdb.php';
require 'bwc_players.php';
		
/* Traitement des actions */
$err=false;
$ToDo = isset($_POST['ToDo']) ? $_POST['ToDo']:$_GET['ToDo'];

switch($ToDo)
{
	case 'NewUser':

		// Contr�le serveur du nick vide
		if ($_POST['txtNick'] == "")
		{
		  	$err = 'emptyNick';
	    	break;
		}
		
		// Contr�le existence joueur avec m�me surnom ou email
		$player = getPlayerByNickEmail($_POST['txtNick'], $_POST['txtEmail']);
		
		if (strtolower($player['nick']) == strtolower($_POST['txtNick']))
		{
			$err = 'existNick';
			break;
		}
				
		if (strtolower($player['email']) == strtolower($_POST['txtEmail']))
		{
			$err = 'existEmail';
			break;
		}
		
		// Cr�ation du joueur et envoi message confirmation
		if (!createPlayer())
		{
		  	// Erreur technique
			$err = 'db';
			break;	
		}
		
		break;
		
	case 'activer':
		// On v�rifie si le compte n'est pas d�j� activ�
		$player = getPlayer($_GET['playerID']);
		if ($player && $player[activate] == 1)
		{
			header("Location: tableaubord.php");
			exit;
		}
		else if (!activatePlayer($_GET['playerID'], $_GET['nick']))
			$err = 'db';
		break;
		
}

$titre_page = "Echecs en diff�r� - Inscription � la zone de jeu";
$desc_page = "Jouer aux �checs en diff�r�. Inscrivez-vous � la zone de jeu en diff�r� et jouer des parties d'�checs � votre rythme.";
require 'page_header.php';
    
?>
<script type="text/javascript" src="javascript/formValidation.js">
 /* fonctions de validation des champs d'un formulaire */
</script>
<script type="text/javascript">
	function validateForm()
	{
		
		if (isEmpty(document.userdata.txtFirstName.value)
			|| isEmpty(document.userdata.txtLastName.value)
			|| isEmpty(document.userdata.txtNick.value)
			|| isEmpty(document.userdata.pwdPassword.value)
			|| isEmpty(document.userdata.txtEmail.value)
			|| isEmpty(document.userdata.txtProfil.value)
			|| isEmpty(document.userdata.txtSituationGeo.value)
			|| isEmpty(document.userdata.txtAnneeNaissance.value))
		{
			alert("Toutes les informations personnelles sont obligatoires.");
			return;
		}
		
		if (!isAlphaNumeric(document.userdata.txtNick.value))
		{
			alert("Le surnom doit �tre alphanum�rique.");
			return;
		}
		
		if (!isAlphaNumeric(document.userdata.pwdPassword.value))
		{
			alert("Le mot de passe doit �tre alphanum�rique.");
			return;
		}
		
		if (!isEmailAddress(document.userdata.txtEmail.value))
		{
			alert("L'adresse de messagerie n'est pas au bon format.");
			return;
		}
		
		if (!isNumber(document.userdata.txtAnneeNaissance.value) || !isWithinRange(document.userdata.txtAnneeNaissance.value, 1900, 2100))
		{
			alert("L'ann�e de naissance est un nombre � 4 chiffres compris entre 1900 et 2010.");
			return;
		}
		
		if (document.userdata.pwdPassword.value == document.userdata.pwdPassword2.value)
			document.userdata.submit();
		else
			alert("Vous avez fait une erreur de saisie de mot de passe.");
		
	}
</script>
<?
    $image_bandeau = 'bandeau_capakaspa_zone.jpg';
    $barre_progression = "<a href='/'>Accueil</a> > Echecs en diff�r� > Inscription";
    require 'page_body.php';
?>
	<div id="contentlarge">
    <div class="blogbody">
	<?
		/* Traiter les erreurs */
		if ($err == 'existNick')
			echo("<div class='error'>Le surnom (".$_POST['txtNick'].") que vous avez choisi  est d�j� utilis�.  Essayez un autre surnom.</div>");
		if ($err == 'existEmail')
			echo("<div class='error'>L'email (".$_POST['txtEmail'].") que vous avez choisi  est d�j� utilis�.  Essayez un autre email.</div>");
		if ($err == 'emptyNick')
			echo("<div class='error'>Surnom vide</div>");
		if ($err == 'db')
			echo("<div class='error'>Une erreur technique s'est produite</div>");
		
	?>
	<?if ($ToDo == 'activer' && !$err) {?>
	<b>Votre compte vient d'�tre activ�.</b>
	<p>
	Vous pouvez maintenant vous connecter � la zone de jeu en diff�r�.
	</p>
	<?} else if ($ToDo == 'activer' && $err == 'db') {?>
	Une erreur s'est produite lors de l'activation !!!
	<?} else if (!$err && $ToDo == 'NewUser') {?>
	<b>Un message de confirmation d'inscription a �t� envoy� � l'adresse de messagerie que vous avez choisi.</b>
	<p>En attendant, vous pouvez consulter le <a href="../manuel-utilisateur-jouer-echecs-capakaspa.pdf" target="_blank">manuel utilisateur</a> de la zone de jeu en diff�r�.</p>
	<p>Si vous souhaitez discuter au sujet des �checs ou faire des remarques et suggestions concernant le site CapaKaspa, vous pouvez aussi vous <a href="http://forum.capakaspa.info/profile.php?mode=register">inscrire sur le forum</a> de CapaKaspa.</p><br/>
	<hr/>
	
	

<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
	<?} else  {?>
	
	<b>L'acc�s � la zone de jeu en diff�r� n�cessite une inscription.</b>
	<p><ul><li>Cette inscription est compl�tement gratuite</li></ul>La seule contrainte est de nous fournir toutes les informations ci-dessous. <i>Cependant votre nom, pr�nom et adresse de messagerie ne seront pas connus des autres joueurs</i>. Les informations restantes sont publi�es dans la liste des joueurs du site.</p>
	<p><ul><li>Cette inscription n�cessite une validation par messagerie �lectronique</li></ul>L'adresse de messagerie associ�e � votre compte doit donc �tre valide.</p>
	
	<form name="userdata" method="post" action="jouer-echecs-differe-inscription.php">
	<h3>Vos informations personnelles</h3>
	<table>
		
		<tr>
			<td width="250">
				Surnom :
			</td>

			<td width="450">
				<input name="txtNick" type="text" size="20" maxlength="20" value="<? echo($_POST['txtNick']); ?>">
			</td>
		</tr>

		<tr>
			<td>
				Mot de passe :
			</td>

			<td>
				<input name="pwdPassword" type="password" size="16" maxlength="16">
			</td>
		</tr>

		<tr>
			<td>
				Mot de passe confirmation:
			</td>

			<td>
				<input name="pwdPassword2" type="password" size="16" maxlength="16">
			</td>
		</tr>
		<tr>
			<td >
				Pr�nom :
			</td>
			
			<td>
				<input name="txtFirstName" type="text" size="20" maxlength="20" value="<? echo($_POST['txtFirstName']); ?>">
			</td>
		</tr>

		<tr>
			<td>
				Nom :
			</td>

			<td>
				<input name="txtLastName" type="text" size="20" maxlength="20" value="<? echo($_POST['txtLastName']); ?>">
			</td>
		</tr>
		<tr>
            <td> Email : </td>
            <td><input name="txtEmail" type="text" size="50" maxlength="50" value="<? echo($_POST['txtEmail']); ?>">
            </td>
          </tr>
		  <tr>
            <td> Situation g�ographique : </td>
            <td><input name="txtSituationGeo" type="text" size="50" maxlength="50" value="<? echo($_POST['txtSituationGeo']); ?>">
            </td>
          </tr>
		  <tr>
            <td> Ann�e de naissance : </td>
            <td><input name="txtAnneeNaissance" type="text" size="4" maxlength="4" value="<? echo($_POST['txtAnneeNaissance']); ?>">
            </td>
          </tr>
		  <tr>
            <td> Profil : </td>
            <td><TEXTAREA NAME="txtProfil" COLS="50" ROWS="5" ><? echo($_POST['txtProfil']); ?></TEXTAREA>
            </td>
          </tr>
		
		<tr>
			<td colspan="2">&nbsp</td>
		</tr>
		</table>
		<h3>Vos pr�f�rences</h3>
		<table>
		<tr valign="top">
			<td width="250">
				Notification par email :
			</td>

			<td width="450">
				
				<input name="txtEmailNotification" type="radio" value="oui" checked> Oui
				<br>
				<input name="txtEmailNotification" type="radio" value="non"> Non
			</td>
		</tr>

		<tr valign="top">
			<td>
				Th�mes :
			</td>

			<td>
				<input name="rdoTheme" type="radio" value="beholder" checked> <img src="images/beholder/white_king.gif" height="30" width="30"/>
																			<img src="images/beholder/white_queen.gif" height="30" width="30"/>
																			<img src="images/beholder/white_rook.gif" height="30" width="30"/>
																			<img src="images/beholder/white_bishop.gif" height="30" width="30"/>
																			<img src="images/beholder/white_knight.gif" height="30" width="30"/>
																			<img src="images/beholder/white_pawn.gif" height="30" width="30"/>
				<br>
				<input name="rdoTheme" type="radio" value="plain"> <img src="images/plain30x30/white_king.gif" />
																	<img src="images/plain30x30/white_queen.gif" />
																	<img src="images/plain30x30/white_rook.gif" />
																	<img src="images/plain30x30/white_bishop.gif" />
																	<img src="images/plain30x30/white_knight.gif" />
																	<img src="images/plain30x30/white_pawn.gif" />
				<br>
				
			</td>
		</tr>
		
		<tr>
			<td align="center" colspan="2">
				<input name="btnCreate" type="button" value="Valider" onClick="validateForm()">
			</td>
		</tr>
		</table>

		<input name="ToDo" value="NewUser" type="hidden">
	</form>
	<?}?>
	</div>
	</div>
<?
    require 'page_footer.php';
    mysql_close();
?>
