<?
	session_start();

	/* load settings */
	if (!isset($_CONFIG))
		require '../config.php';

	/* load external functions for setting up new game */
	require_once('../chessutils.php');
	
	/* connect to database */
	require '../connectdb.php';
	
	require '../dac_games.php';
	require '../bwc_players.php';
	
	/* check session status */
	require '../sessioncheck.php';
	
	$err = 1;
	$ToDo = isset($_POST['ToDo']) ? $_POST['ToDo']:Null;
	switch($ToDo)
	{
		
		case 'UpdateProfil':
			
			$err = updateProfil($_SESSION['playerID'], $_POST['pwdPassword'], $_POST['pwdOldPassword'], strip_tags($_POST['txtFirstName']), strip_tags($_POST['txtLastName']), $_POST['txtEmail'], strip_tags($_POST['txtProfil']), strip_tags($_POST['txtSituationGeo']), $_POST['txtAnneeNaissance'], $_POST['rdoTheme'], $_POST['txtEmailNotification'], $_POST['rdoSocialNetwork'], $_POST['txtSocialID']);
			break;
			
		case 'CreateVacation':
		
			$err = createVacation($_SESSION['playerID'], $_POST['nbDays'], $CFG_EXPIREGAME);	
			break;
			
	}

		
 	$titre_page = "Echecs en diff�r� (mobile) - Modifier votre profil";
 	$desc_page = "Jouer aux �checs en diff�r� sur votre smartphone. Modifier votre profil de joueur de la zone de jeu d'�checs en diff�r�";
    require 'page_header.php';
?>
<script type="text/javascript" src="http://www.capakaspa.info/javascript/formValidation.js">
 /* fonctions de validation des champs d'un formulaire */
</script>
<script type="text/javascript">
		function validatePersonalInfo()
		{
			var dayDate = new Date();
			var annee = dayDate.getFullYear();
			if (isEmpty(document.Profil.txtFirstName.value)
				|| isEmpty(document.Profil.txtLastName.value)
				|| isEmpty(document.Profil.txtSituationGeo.value)
				|| isEmpty(document.Profil.txtProfil.value)
				|| isEmpty(document.Profil.txtAnneeNaissance.value))
			{
				alert("Toutes les informations personnelles sont obligatoires.");
				return;
			}
			
			if (!isNumber(document.Profil.txtAnneeNaissance.value) || !isWithinRange(document.Profil.txtAnneeNaissance.value, 1900, annee))
			{
				alert("L'ann�e de naissance est un nombre � 4 chiffres compris entre 1900 et l'ann�e courante.");
				return;
			}
			
			if (!isEmpty(document.Profil.pwdPassword.value)
				&& isEmpty(document.Profil.pwdOldPassword.value))
			{
				alert("Vous devez saisir votre ancien mot de passe.");
				return;
			}
			
			if (!isEmpty(document.Profil.pwdPassword.value) && !isAlphaNumeric(document.Profil.pwdPassword.value))
			{
				alert("Le mot de passe doit �tre alphanum�rique.");
				return;
			}
			
			if (document.Profil.pwdPassword.value == document.Profil.pwdPassword2.value)
				document.Profil.submit();
			else
				alert("Vous avez fait une erreur de saisie de mot de passe.");
		}
		
		function validateVacation()
		{
			if (!isWithinRange(document.Vacation.nbDays.value, 1, 30))
			{
				alert("Le nombre de jours doit �tre compris entre 0 et 30.");
				return;
			}
			var vok=false;
			vok = confirm("L'ajout de cette absence ne peut �tre annul�e et toutes vos parties seront imm�diatement ajourn�es. Veuillez confirmer sa prise en compte ?");
			if (vok)
			{
				document.Vacation.submit();
			}
		}
	</script>
<?
    
    require 'page_body.php';
?>
 
	<div id="onglet">
	<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td><div class="ongletdisable"><a href="tableaubord.php">Parties</a></div></td>
		<td><div class="ongletdisable"><a href="invitation.php">Invitation</a></div></td>
		<td><div class="ongletenable">Mon profil</div></td>	
	</tr>
	</table>
	</div>
	
      	<?
      	if ($err == 0)
				echo("<div class='error'>Un probl�me technique a emp�ch� l'op�ration</div>");
		if ($ToDo == 'UpdateProfil')
      	{
			
			if ($err == -1)
				echo("<div class='error'>Votre ancien mot de passe n'est pas celui que vous avez saisi</div>");
			if ($err == 1)
				echo("<div class='success'>Les modifications de votre profil ont bien �t� enregistr�es</div>");
		}
		if ($ToDo == 'CreateVacation')
		{
			if ($err == -100)
				echo("<div class='error'>Le nombre de jours d'absence que vous demandez n'est pas valide</div>");
			if ($err == 1)
				echo("<div class='success'>Votre demande d'absence a bien �t� enregistr�e</div>");
		}
		?>
      <form name="Profil" action="profil.php" method="post">
	  <h3>Mes informations personnelles</h3>
        <table border="0" width="100%">
          <tr>
            <td width="30%"> Surnom : </td>
            <td width="70%"><? echo($_SESSION['nick']); ?> (<? echo($_SESSION['elo']); ?>)
            </td>
          </tr>
		  <tr>
            <td> Pr�nom : </td>
            <td><input name="txtFirstName" type="text" size="20" maxlength="20" value="<? echo($_SESSION['firstName']); ?>">
            </td>
          </tr>
          <tr>
            <td> Nom : </td>
            <td><input name="txtLastName" type="text" size="20" maxlength="20" value="<? echo($_SESSION['lastName']); ?>">
            </td>
          </tr>
		  <tr>
            <td> Email : </td>
            <td><? echo($_SESSION['email']); ?><input type="hidden" name="txtEmail" value="<? echo($_SESSION['email']); ?>">
            </td>
          </tr>
		  <tr>
            <td> Situation g�ographique : </td>
            <td><input name="txtSituationGeo" type="text" size="30" maxlength="50" value="<? echo($_SESSION['situationGeo']); ?>">
            </td>
          </tr>
		  <tr>
            <td> Ann�e de naissance : </td>
            <td><input name="txtAnneeNaissance" type="text" size="4" maxlength="4" value="<? echo($_SESSION['anneeNaissance']); ?>">
            </td>
          </tr>
		  <tr>
            <td> Profil : </td>
            <td><TEXTAREA NAME="txtProfil" COLS="30" ROWS="5"><? echo($_SESSION['profil']); ?></TEXTAREA>
            </td>
          </tr>
		  <tr>
            <td> Photo : </td>
            <td>
            	<img src="<?echo(getPicturePath($_SESSION['socialNetwork'], $_SESSION['socialID']));?>" width="50" height="50" style="float: left;margin-right: 5px;"/>
            	Afficher la photo de votre profil :
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input name="rdoSocialNetwork" type="radio" value="" <? if ($_SESSION['socialNetwork']=="") echo("checked");?>> Aucun
            	<input name="rdoSocialNetwork" type="radio" value="FB" <? if ($_SESSION['socialNetwork']=="FB") echo("checked");?>> Facebook<br/>
            	<input name="rdoSocialNetwork" type="radio" value="GP" <? if ($_SESSION['socialNetwork']=="GP") echo("checked");?>> Google+
            	<input name="rdoSocialNetwork" type="radio" value="TW" <? if ($_SESSION['socialNetwork']=="TW") echo("checked");?>> Twitter
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>ID r�seau : <input name="txtSocialID" type="text" size="20" maxlength="100" value="<? echo($_SESSION['socialID']); ?>"> 
            </td>
          </tr>
          <tr>
            <td colspan="2">&nbsp</td>
          </tr>
          <tr>
            <td> Mot de passe : </td>
            <td><input name="pwdOldPassword" size="30" type="password" value="">
            </td>
          </tr>
          <tr>
            <td> Nouveau : </td>
            <td><input name="pwdPassword" size="30" type="password" value="">
            </td>
          </tr>
          <tr>
            <td> Confirmation: </td>
            <td><input name="pwdPassword2" size="30" type="password" value="">
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">&nbsp            </td>
          </tr>
        </table>
        
      
      <h3>Mes pr�f�rences</h3>
      
        <table border="0" width="100%">
          <tr>
            <td width="30%">Notification par email :</td>
            <td width="70%"><?
					if ($_SESSION['pref_emailnotification'] == 'oui')
					{
				?>
              <input name="txtEmailNotification" type="radio" value="oui" checked>
              Oui 
              <input name="txtEmailNotification" type="radio" value="non">
              Non (Ev�nements partie, commentaires et messages)
              <?
					}
					else
					{
				?>
              <input name="txtEmailNotification" type="radio" value="oui">
              Oui 
              <input name="txtEmailNotification" type="radio" value="non" checked>
              Non (Ev�nements partie, commentaires et messages)
              <?	}
				?>
            </td>
          </tr>
          <tr>
            <td>Th�me :</td>
            <td><?
					if ($_SESSION['pref_theme'] == 'beholder')
					{
				?>
              <input name="rdoTheme" type="radio" value="beholder" checked>
              	<img src="images/beholder/white_king.gif" height="30" width="30"/>
				<img src="images/beholder/white_queen.gif" height="30" width="30"/>
				<img src="images/beholder/white_rook.gif" height="30" width="30"/>
				<img src="images/beholder/white_bishop.gif" height="30" width="30"/>
				<img src="images/beholder/white_knight.gif" height="30" width="30"/>
				<br>
              <input name="rdoTheme" type="radio" value="plain">
             	<img src="images/plain30x30/white_king.gif" />
				<img src="images/plain30x30/white_queen.gif" />
				<img src="images/plain30x30/white_rook.gif" />
				<img src="images/plain30x30/white_bishop.gif" />
				<img src="images/plain30x30/white_knight.gif" />
				
              <?
					}
					else
					{
				?>
              <input name="rdoTheme" type="radio" value="beholder">
              	<img src="images/beholder/white_king.gif" height="30" width="30"/>
				<img src="images/beholder/white_queen.gif" height="30" width="30"/>
				<img src="images/beholder/white_rook.gif" height="30" width="30"/>
				<img src="images/beholder/white_bishop.gif" height="30" width="30"/>
				<img src="images/beholder/white_knight.gif" height="30" width="30"/>
				 <br/>
              <input name="rdoTheme" type="radio" value="plain" checked>
				<img src="images/plain30x30/white_king.gif" />
				<img src="images/plain30x30/white_queen.gif" />
				<img src="images/plain30x30/white_rook.gif" />
				<img src="images/plain30x30/white_bishop.gif" />
				<img src="images/plain30x30/white_knight.gif" />
				
              <?	}
				?>
            </td>
          </tr>
          
          <tr>
            <td colspan="2" align="center"><input name="Update" type="button" value="Valider" onClick="validatePersonalInfo()">
            </td>
          </tr>
        </table>
		<input type="hidden" name="ToDo" value="UpdateProfil">
      </form>
      
      <!-- 
      Gestion des absences
      Le joueur saisie la dur�e de son cong� qui est effectif � partir du lendemain
      On demande confirmation car toute annulation est impossible
      La saisi du cong� n'est plus possible pendant la dur�e d'un cong�
      Le solde de cong� du joueur est d�cr�ment� du nombre de jour saisi
      Le syst�me enregistre la date de d�but du cong� (date du jour + 1), la dur�e et la date de fin (date de d�but + dur�e)
      
      Lors de la saisie du cong� il faut modifier la date du dernier des parties du joueur :
      Pour chaque partie
      	Si pas de cong� en cours pour l'adversaire on ajoute la dur�e du cong� saisi +1 � la date du dernier coup
      	Sinon on ajoute la dur�e du cong� saisi - (date de fin du cong� en cours de l'adversaire - date de d�but du cong� saisi)      
      
      Tant qu'un des joueurs d'une partie est en cong� la partie est gel�e (il est impossible de jouer un coup)
       -->
      
      <h3>Gestion des absences</h3>
      <p>Vous disposez encore de <b><?echo(countAvailableVacation($_SESSION['playerID']));?> jours</b> d'absence pour l'ann�e <?echo(date('Y'))?> (tous les jours d'une �ventuelle absence � cheval sur l'ann�e pr�c�dente sont d�compt�s en <?echo(date('Y'))?>).</p>
    
      <?	
      		$tmpVacations = getCurrentVacation($_SESSION['playerID']);
			$nbCurrentVacation = mysql_num_rows($tmpVacations);
			if ($nbCurrentVacation == 0)
				echo("<p>Vous n'avez pas d'absences en cours.</p>");
			else
			{
				$tmpVacation = mysql_fetch_array($tmpVacations, MYSQL_ASSOC);
				echo("<p>Votre avez un absence � prendre en compte du ");
				echo("<b>".$tmpVacation['beginDateF']."</b> ");
    			echo(" au " );
				echo("<b>".$tmpVacation['endDateF']."</b>.</p>");
			}
    	
      		if ($nbCurrentVacation == 0)
      	{
      		
      	?>
		<form name="Vacation" action="profil.php" method="post">
	  	<?	$tomorrow  = mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")); 
	  		$today = date("d/m/Y", $tomorrow);
	  	?> 
	        <p>Vous souhaitez vous absenter pour <input name="nbDays" size="2" maxlength="2" type="text" value=""> jour(s) <input name="Validate" type="button" value="Valider" onClick="validateVacation()"> � compter du <? echo($today)?> (vos parties seront ajourn�es imm�diatement).</p>
	      	<input type="hidden" name="ToDo" value="CreateVacation">
    	</form>
    	<? }?>
    	<br/>
    	
<?
    require 'page_footer.php';
    mysql_close();
?>
