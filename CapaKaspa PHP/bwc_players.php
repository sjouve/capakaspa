<?
if (!isset($_CONFIG))
		require 'config.php';
require_once('dac_players.php');
require_once('chessutils.php');

/* Cr�ation du joueur et de ses pr�f�rences */
function createPlayer()
{
	@mysql_query("BEGIN");
	
	if ($_POST['pwdPassword']=='') return FALSE;
	if ($_POST['pwdPassword']!=$_POST['pwdPassword2']) return FALSE;
	
	// Cr�e l'utilisateur
	$playerID = insertPlayer($_POST['pwdPassword'], $_POST['txtFirstName'], $_POST['txtLastName'], $_POST['txtNick'], $_POST['txtEmail'], $_POST['txtProfil'], $_POST['txtSituationGeo'], $_POST['txtAnneeNaissance']);	
	if (!$playerID)
	{
		@mysql_query("ROLLBACK");
		return FALSE;  
	}

	// set History format preference
	$res = insertPreference($playerID, 'history', $_POST['rdoHistory']);
	if (!$res)
	{
		@mysql_query("ROLLBACK");
		return FALSE;  
	}
	
	// set Theme preference
	$res = insertPreference($playerID, 'theme', $_POST['rdoTheme']);
	if (!$res)
	{
		@mysql_query("ROLLBACK");
		return FALSE;  
	}
	
	// set Email notification preference
	$res = insertPreference($playerID, 'emailnotification', $_POST['txtEmailNotification']);
	if (!$res)
	{
		@mysql_query("ROLLBACK");
		return FALSE;  
	}
	
	// Envoi du message de confirmation
	$mailSubject = "[CapaKaspa] Confirmation de votre inscription";
	$mailMsg = "Pour finaliser votre inscription veuillez cliquer sur le lien suivant (en cas de probl�me copier le lien dans la barre d'adresse de votre navigateur) :\n";
	$mailMsg .= "http://www.capakaspa.info/jouer-echecs-differe-inscription.php?ToDo=activer&playerID=".$playerID."&nick=".$_POST['txtNick'];
	$mailMsg .= "\n\nCe message a �t� envoy� automatiquement � partir du site CapaKaspa (http://www.capakaspa.info).\n";
	$res = sendMail($_POST['txtEmail'], $mailSubject, $mailMsg);
	
	if (!$res)
	{
		@mysql_query("ROLLBACK");
		return FALSE;
	}
			
	@mysql_query("COMMIT");
	return TRUE;
}

/* Mettre � jour le profil utilisateur */
function updateProfil($playerID, $pwdPassword, $pwdOldPassword, $firstName, $lastName, $email, $profil, $situationGeo, $anneeNaissance, $prefTheme, $prefEmailNotification, $socialNetwork, $socialID)
{
	$player = getPlayer($playerID);
	if (!$player)
	{
		return 0;
	}
	
	// Mauvais mot de passe
	if ($player['PASSWORD'] != $pwdOldPassword && $pwdOldPassword != "")
		return -1;
	
	@mysql_query("BEGIN");
		
	// Changement de mot de passe
	if (isset($pwdPassword) && $pwdPassword != "")
	{
		$res = updatePlayerWithSocial($playerID, $pwdPassword, $firstName, $lastName, $player['nick'], $email, $profil, $situationGeo, $anneeNaissance, $player['activate'], $socialNetwork, $socialID);
		if (!$res)
		{
			@mysql_query("ROLLBACK");
			return 0;  
		}
	}
	else
	{
		$res = updatePlayerWithSocial($playerID, $player['PASSWORD'], $firstName, $lastName, $player['nick'], $email, $profil, $situationGeo, $anneeNaissance, $player['activate'], $socialNetwork, $socialID);
		if (!$res)
		{
			@mysql_query("ROLLBACK");
			return 0;  
		}
	}
	
	// Pr�f�rences	
	// Th�me
	$res = updatePreference($playerID, 'theme', $prefTheme);
	if (!$res)
	{
		@mysql_query("ROLLBACK");
		return 0;  
	}
		
	// Email Notification
	$res = updatePreference($playerID, 'emailnotification', $prefEmailNotification);
	if (!$res)
	{
		@mysql_query("ROLLBACK");
		return 0;  
	}
	
	// Update current session
	$_SESSION['playerName'] = stripslashes(strip_tags($_POST['txtFirstName']))." ".stripslashes(strip_tags($_POST['txtLastName']));
	$_SESSION['firstName'] = stripslashes(strip_tags($_POST['txtFirstName']));
	$_SESSION['lastName'] = stripslashes(strip_tags($_POST['txtLastName']));
	$_SESSION['email'] = $_POST['txtEmail'];
	$_SESSION['situationGeo'] = stripslashes(strip_tags($_POST['txtSituationGeo']));
	$_SESSION['profil'] = stripslashes(strip_tags($_POST['txtProfil']));
	$_SESSION['anneeNaissance'] = $_POST['txtAnneeNaissance'];
	$_SESSION['pref_theme'] =  $_POST['rdoTheme'];
	$_SESSION['pref_emailnotification'] = $_POST['txtEmailNotification'];
	$_SESSION['socialID'] = $_POST['txtSocialID'];
	$_SESSION['socialNetwork'] = $_POST['rdoSocialNetwork'];
	
	@mysql_query("COMMIT");
	return 1;
}

/* Demande d'activation d'un compte */
function activationRequest($nick, $password, $email)
{
	
	// Contr�le format email
	if (!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email))
	{ 
		return -3;
	}
	
	// Contr�le unicit� email
	$player = getPlayerByEmail($email);
	
	if ($player && $player['nick'] != $nick )
	{
		return -2;
	}
	
	// Existence du compte
	$player = getPlayerByNickPassword($nick, $password);
	
	if ($player)
	{
		$res = updatePlayer ($player['playerID'], $player['PASSWORD'], $player['firstName'], $player['lastName'], $player['nick'], $email, $player['profil'], $player['situationGeo'], $player['anneeNaissance'], $player['activate']);
		
		if (!$res)
		{
		  	return -1;
		}		
	}
	else
	{
		return 0;
	}
	
	// Envoi du message de confirmation
	$mailSubject = "[CapaKaspa] Confirmation de votre inscription";
	$mailMsg = "Pour activer votre compte veuillez cliquer sur le lien suivant (en cas de probl�me copier le lien dans la barre d'adresse de votre navigateur) :\n";
	$mailMsg .= "http://www.capakaspa.info/jouer-echecs-differe-inscription.php?ToDo=activer&playerID=".$player['playerID']."&nick=".$player['nick'];
	$mailMsg .= "\n\nCe message a �t� envoy� automatiquement � partir du site CapaKaspa (http://www.capakaspa.info).\n";
	$res = sendMail($_POST['txtEmail'], $mailSubject, $mailMsg);
	
	if (!$res)
	{
		return -1;
	}
	
	return 1;
}

/* Activer le compte d'un joueur */
function activatePlayer($playerID, $nick)
{
	$player = getPlayer($playerID);
	
	if ($player)
	{
		
		$res = updatePlayer ($playerID, $player['PASSWORD'], $player['firstName'], $player['lastName'], $player['nick'], $player['email'], $player['profil'], $player['situationGeo'], $player['anneeNaissance'], 1);
		
		if (!$res)
		{
		  	return FALSE;
		}		
	}
	else
	{
		return FALSE;
	}
	
	return TRUE;
}

/* Connexion d'un joueur */
function loginPlayer($nick, $password, $flagAuto)
{
	// check for a player with supplied nick and password
	$player = getPlayerByNickPassword($nick, $password);

	// Le joueur existe ?
	if (!$player)
	{
		return 0;
	}
	
	// Le joueur est-il activ� ?
	if ($player['activate'] == 0)
	{
		return -1; 
	}
	
	// if such a player exists, log him in
	$_SESSION['playerID'] = $player['playerID'];
	$_SESSION['lastInputTime'] = time();
	$_SESSION['playerName'] = stripslashes($player['firstName'])." ".stripslashes($player['lastName']);
	$_SESSION['firstName'] = stripslashes($player['firstName']);
	$_SESSION['lastName'] = stripslashes($player['lastName']);
	$_SESSION['nick'] = $player['nick'];
	$_SESSION['email'] = $player['email'];
	$_SESSION['situationGeo'] = stripslashes($player['situationGeo']);
	$_SESSION['profil'] = stripslashes($player['profil']);
	$_SESSION['anneeNaissance'] = $player['anneeNaissance'];
	$_SESSION['elo'] = $player['elo'];
	$_SESSION['socialNetwork'] = $player['socialNetwork'];
	$_SESSION['socialID'] = $player['socialID'];

	/* Mettre � jour la date de connexion */
	// TODO Requ�te dans DAC
	$tmpQuery = "UPDATE players SET lastConnection = now() WHERE playerID = ".$_SESSION['playerID'];
	$tmpPlayers = mysql_query($tmpQuery);

	/* load user preferences */
	// TODO Requ�te dans DAC
	$tmpQuery = "SELECT * FROM preferences WHERE playerID = ".$_SESSION['playerID'];
	$tmpPreferences = mysql_query($tmpQuery);

	$isPreferenceFound['history'] = false;
	$isPreferenceFound['theme'] = false;
	$isPreferenceFound['emailnotification'] = false;

	while($tmpPreference = mysql_fetch_array($tmpPreferences, MYSQL_ASSOC))
	{
		switch($tmpPreference['preference'])
		{
			case 'history':
			case 'theme':
				/* setup SESSION var of name pref_PREF, like pref_history */
				$_SESSION['pref_'.$tmpPreference['preference']] = $tmpPreference['value'];
				break;

			case 'emailnotification':
				$_SESSION['pref_emailnotification'] = $tmpPreference['value'];
				break;

		}

		$isPreferenceFound[$tmpPreference['preference']] = true;
	}
	
	// Si se souvenir de moi cr�ation du cookie
	if ($flagAuto == "on")
	{
		setcookie('capakaspacn[nick]', $nick, (time()+3600*24*30));
		setcookie('capakaspacn[password]', $password, (time()+3600*24*30));
		//echo("PLAYER : ".$nick."/".$password);
		//echo("COOKIE : ".$_COOKIE['capakaspacn']['nick']."/".$_COOKIE['capakaspacn']['password']);
	}
	
return 1;
}

/* Envoi mot de passe oubli� */
function sendPassword($email)
{
	$player = getPlayerByEmail($email);
	
	// Le joueur existe ?
	if (!$player)
	{
		return 0;
	}
	
	// Envoi du message avec mot de passe
	$mailSubject = "[CapaKaspa] Votre mot de passe";
	$mailMsg = "Voici les informations de votre compte :\n";
	$mailMsg .= "Surnom : ".$player['nick']."\n";
	$mailMsg .= "Passe : ".$player['PASSWORD']."\n";
	$mailMsg .= "\n\nCe message a �t� envoy� automatiquement � partir du site CapaKaspa (http://www.capakaspa.info).\n";
	$res = sendMail($email, $mailSubject, $mailMsg);
	
	if (!$res)
	{
		return -1;
	}
	 
	return 1; 
}

/* Compte le nombre de joueurs actifs sur le site */
function getNbActivePlayers()
{
	$res = countActivePlayers();
	return $res['nbPlayers'];
}

/* Compte le nombre de joueurs passifs sur le site */
function getNbPassivePlayers()
{
	$res = countPassivePlayers();
	return $res['nbPlayers'];
}

/* Creation des jours d'absence */
function createVacation($playerID, $nbDays, $delai_expiration)
{
	
	// Contr�ler le nombre de jours disponibles
	if ($nbDays > countAvailableVacation($playerID) || $nbDays < 1)
	{
		return -100;
	}
	
	@mysql_query("BEGIN");
	
	// Ins�rer l'absence
	$res = insertVacation($playerID, $nbDays);
	if (!$res) 
	{
		@mysql_query("ROLLBACK");
		return 0;
	} 
	
	$nbDays = $nbDays + 1;
	
	/*Lors de la saisie du cong� il faut modifier la date du dernier des parties du joueur :
      Pour chaque partie (non expir�e)
      	Si pas de cong� en cours pour l'adversaire on ajoute la dur�e du cong� saisi +1 � la date du dernier coup
      	Sinon on ajoute la dur�e du cong� saisi - (date de fin du cong� en cours de l'adversaire - date de d�but du cong� saisi)
      		si l'ajout reste positif      
	*/  
	$tmpGames = mysql_query("SELECT * 
                             FROM games
                             WHERE gameMessage = ''
                             AND (whitePlayer = ".$playerID." OR blackPlayer = ".$playerID.")
                             AND lastMove >= DATE_SUB(CURDATE(), INTERVAL ".$delai_expiration." DAY)  
                             ORDER BY dateCreated");

	// Ne pas modifier les parties expir�es pas encore termin�es
	while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
    {
    	if ($tmpGame['whitePlayer']==$playerID)
    		$res_adv_vacations = getCurrentVacation($tmpGame['blackPlayer']);
    	else
    		$res_adv_vacations = getCurrentVacation($tmpGame['whitePlayer']);
    	
    	if (mysql_num_rows($res_adv_vacations) == 0)
    	{	
    		$res = mysql_query("UPDATE games
    						SET  lastMove = DATE_ADD(lastMove, INTERVAL ".$nbDays." DAY)
    						WHERE gameID = ".$tmpGame['gameID']);
    		if (!$res)
    		{
    			@mysql_query("ROLLBACK");
    			return 0;
    		}
    	}
    	else
    	{
    		
    		$beginDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"),  date("Y")));
    		$res_adv_vacation = mysql_fetch_array($res_adv_vacations, MYSQL_ASSOC);
    		$nbDaysPlus = nbJours($beginDate, $res_adv_vacation['endDate']);
    		
    		if ($nbDaysPlus < $nbDays)
    		{
	    		
    			$nbDaysToAdd = $nbDays - $nbDaysPlus;
    			
    			mysql_query("UPDATE games
	    						SET  lastMove = DATE_ADD(lastMove, INTERVAL ".$nbDaysToAdd." DAY)
	    						WHERE gameID = ".$tmpGame['gameID']);
	    		if (!$res)
	    		{
	    			@mysql_query("ROLLBACK");
	    			return 0;
	    		}
    		}
    	}
    }
    
    @mysql_query("COMMIT");
	return 1;
}

/* Compte le nombre de jours d'absence disponible sur l'ann�e en cours */
function countAvailableVacation($playerID)
{
	
	$nbVacation = countVacation($playerID, date('Y'));
	return MAX_NB_JOUR_ABSENCE - $nbVacation;
}

/* R�cup�re le chemin de la photo du profil */
function getPicturePath($socialNetwork, $socialID)
{
	$picturePath = "images/default_avatar.jpg";
	switch($socialNetwork)
	{	
		case 'GP':
			$profil_googleplus_json = file_get_contents("https://www.googleapis.com/plus/v1/people/".$socialID."?key=AIzaSyDbsmnLMbP6QxydxzhqZlCwxOVG1ewIX0o");
			$profil_googleplus = json_decode($profil_googleplus_json);
			$picturePath = $profil_googleplus->image->url;
			break;
		
		case 'FB':
			$picturePath = "https://graph.facebook.com/".$socialID."/picture";
			break;
			
		case 'TW':
			$picturePath = "http://api.twitter.com/1/users/profile_image/".$socialID.".xml";
			break;
	}
	
	return $picturePath;
}

/* Compte le nombre de joueurs en ligne sur le site */
function getNbOnlinePlayers()
{
	$res = countOnlinePlayers();
	return $res['nbPlayers'];
}

/* Est un nouveau joueur en fonction date de cr�ation */
function isNewPlayer($creationDate)
{
	$maxDate = date('Y/m/d', strtotime('-7 day'));
	list($year, $month, $day) = explode("-", $creationDate);
	$creationDate = date("Y/m/d", mktime(0,0,0, $month, $day, $year));
	
	if ($creationDate > $maxDate)
		return true;
	else
		return false; 
}
?>
