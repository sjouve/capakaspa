<?
session_start();
/* load settings */
if (!isset($_CONFIG))
	require 'config.php';

require 'connectdb.php';
require 'bwc_players.php';
require 'bwc_games.php';
require 'gui_rss.php';
	
/* Traitement des actions */
$err=1;
$ToDo = isset($_POST['ToDo']) ? $_POST['ToDo']:$_GET['ToDo'];

switch($ToDo)
{
	case 'Valider':
		$err = sendPassword($_POST['txtEmail']);
		break;
}	

$titre_page = "Echecs en diff�r� - Mot de passe oubli�";
$desc_page = "Jouer aux �checs en diff�r�. Retrouvez votre mot de passe afin d'accder � la zone de jeu en diff�r� et jouer des parties d'�checs � votre rythme.";
require 'page_header.php';
$image_bandeau = 'bandeau_capakaspa_global.jpg';
$barre_progression = "<a href='/'>Accueil</a> > Echecs en diff�r� > Mot de passe oubli�";
require 'page_body.php';
?>
  <div id="content">
    <div class="blogbody">
    <?/* Traiter les erreurs */
		if ($err == 0)
			echo("<div class='error'>Il n'y a aucun compte associ� � cette adresse de messagerie</div>");
		if ($err == -1)
			echo("<div class='error'>Un probl�me technique a emp�ch� l'envoi du message</div>");
			
	?>
	<? if ($err == 1 && $ToDo == 'Valider') {?>
		<div class='success'>Un message a �t� envoy� � l'adresse de messagerie indiqu�e.</div>
	<? } else {?>
	<h3>Mot de passe oubli�</h3>
    	Vous disposez d�j� d'un compte pour acc�der � la zone de jeu en diff�r� mais <b>vous avez oubli� votre mot de passe</b>.<br/>
    	<p>Saisissez l'adresse de messagerie que vous avez associ� � ce compte. Un message sera envoy� � cette adresse. Il contiendra les informations n�cessaires � la connexion.</p>
		<form name="userdata" method="post" action="passeoublie.php">
			<table align="center">
				<tr>
		            <td> Email : </td>
		            <td><input name="txtEmail" type="text" size="50" maxlength="50" value="<?echo($_POST['txtEmail']);?>">
		            </td>
		        </tr>
			</table>
	
			<center><input name="ToDo" value="Valider" type="submit"></center>
		</form>
      <?}?>
      <br/><br/><br/><br/>
		      
      
    </div>
  </div>
  <div id="rightbar">
    <div class="navlinks">
    	
      
      	<div class="title">Statistiques</div>
		  <ul>
			<li><img src="images/hand.gif" /> Parties en cours : <? echo(getNbActiveGameForAll())?></li>
			<li><img src="images/joueur_actif.gif" /> Joueurs actifs : <? echo(getNbActivePlayers())?></li>
			<li><img src="images/joueur_passif.gif" /> Joueurs passifs : <? echo(getNbPassivePlayers())?></li>
		  </ul>
		
		<br/><br/>
	

 	</div>
 	</div>
<?
    require 'page_footer.php';
    mysql_close();
?>
