<?
/* Acc�s aux donn�es concernant les tables Players et Preferences */

/* Constantes du module */
define ("MAX_NB_JOUR_ABSENCE", 35);

/* Charger un utilisateur par son ID */
function getPlayer($playerID)
{
	$res_player = mysql_query("SELECT * FROM players WHERE playerID = ".$playerID);
    $player = mysql_fetch_array($res_player, MYSQL_ASSOC);
    return $player;
}

/* Charger un utilisateur pour email */
function getPlayerByEmail($email)
{
	$res_player = mysql_query("SELECT * FROM players WHERE email = '".$email."'");
    $player = mysql_fetch_array($res_player, MYSQL_ASSOC);
    return $player;
}

/* Charger un utilisateur pour un surnom et mot de passe */
function getPlayerByNickPassword($nick, $password)
{
	$res_player = mysql_query("SELECT * FROM players WHERE nick = '".$nick."' AND password = '".$password."'");
    $player = mysql_fetch_array($res_player, MYSQL_ASSOC);
    return $player;
}

/* Charger un utilisateur pour un surnom ou email */
function getPlayerByNickEmail($nick, $email)
{
	$res_player = mysql_query("SELECT playerID, nick, email FROM players WHERE nick = '".$nick."' OR email = '".$email."'");
    $player = mysql_fetch_array($res_player, MYSQL_ASSOC);
    return $player;
}

/* Ins�rer un joueur */	
function insertPlayer($password, $firstName, $lastName, $nick, $email, $profil, $situationGeo, $anneeNaissance)
{
	$res_player = mysql_query("INSERT INTO players (password, firstName, lastName, nick, email, profil, situationGeo, anneeNaissance, creationDate) 
	VALUES ('".$password."', '".addslashes(strip_tags($firstName))."', '".addslashes(strip_tags($lastName))."', '".$nick."', '".$email."', '".addslashes(strip_tags($profil))."', '".addslashes(strip_tags($situationGeo))."', '".$anneeNaissance."', now())");

	if ($res_player)	
		return mysql_insert_id();
	else
		return FALSE;
}

/* Mettre � jour un joueur */
function updatePlayer($playerID, $password, $firstName, $lastName, $nick, $email, $profil, $situationGeo, $anneeNaissance, $activate)
{ 		
	  $res_player = mysql_query("UPDATE players SET password='".$password."', firstName='".addslashes(strip_tags($firstName))."', lastName='".addslashes(strip_tags($lastName))."', nick='".$nick."', email='".$email."', profil='".addslashes(strip_tags($profil))."', situationGeo='".addslashes(strip_tags($situationGeo))."', anneeNaissance='".$anneeNaissance."', activate=".$activate." WHERE playerID = ".$playerID);
	  
	if ($res_player)	
		return TRUE;
	else
		return FALSE;
}

/* Mettre � jour un joueur avec donn�es r�seau social */
function updatePlayerWithSocial($playerID, $password, $firstName, $lastName, $nick, $email, $profil, $situationGeo, $anneeNaissance, $activate, $socialNetwork, $socialID)
{ 		
	  $res_player = mysql_query("UPDATE players 
	  							SET password='".$password."', firstName='".addslashes(strip_tags($firstName))."', lastName='".addslashes(strip_tags($lastName))."', nick='".$nick."', email='".$email."', profil='".addslashes(strip_tags($profil))."', situationGeo='".addslashes(strip_tags($situationGeo))."', anneeNaissance='".$anneeNaissance."', activate=".$activate.", socialID='".$socialID."', socialNetwork='".$socialNetwork."'  
	  							WHERE playerID = ".$playerID);
	  
	if ($res_player)	
		return TRUE;
	else
		return FALSE;
}

/* Liste tous les joueurs */
function listPlayers()
{
	$tmpQuery = "SELECT * FROM players ORDER BY email";
	
	return mysql_query($tmpQuery);  
}

/* Liste tous les joueurs */
function listPlayersForElo()
{
	$tmpQuery = "SELECT P.playerID playerID, E.elo elo, P.nick nick FROM players P, elo_history E WHERE P.playerID = E.playerID AND P.activate=1 AND E.eloDate > '2012-12-31' ORDER BY playerID";
	
	return mysql_query($tmpQuery);  
}

/* Liste les joueurs actifs */
function listPlayersActifs()
{
	$tmpQuery = "SELECT playerID, nick, anneeNaissance, profil, situationGeo, elo FROM players WHERE DATE_ADD(lastConnection, INTERVAL 14 DAY) >= NOW() AND playerID <> ".$_SESSION['playerID']." AND activate=1 ORDER BY lastConnection DESC";
	
	return mysql_query($tmpQuery);  
}

/* Liste les joueurs passifs */
function listPlayersPassifs()
{
	$tmpQuery = "SELECT playerID, nick, anneeNaissance, profil, situationGeo, elo FROM players WHERE DATE_ADD(lastConnection, INTERVAL 14 DAY) < NOW() AND playerID <> ".$_SESSION['playerID']." AND activate=1 ORDER BY lastConnection DESC";
	
  	return mysql_query($tmpQuery);  
}

function deletePlayer()
{
	// TODO suppression joueur ?
}

function countActivePlayers()
{
	$res_player = mysql_query("SELECT count(playerID) nbPlayers FROM players WHERE DATE_ADD(lastConnection, INTERVAL 14 DAY) >= NOW() AND activate = 1 ORDER BY lastConnection DESC");
	return mysql_fetch_array($res_player, MYSQL_ASSOC);
}

function countPassivePlayers()
{
	$res_player = mysql_query("SELECT count(playerID) nbPlayers FROM players WHERE DATE_ADD(lastConnection, INTERVAL 14 DAY) < NOW() AND activate = 1 ORDER BY lastConnection DESC");
	return mysql_fetch_array($res_player, MYSQL_ASSOC);
}
	
/* Pr�f�rences */
/* Ins�rer une pr�f�rence d'un joueur */
function insertPreference($playerID, $preference, $value)
{
	
	$res_preference = mysql_query("INSERT INTO preferences (playerID, preference, value) VALUES (".$playerID.", '".$preference."', '".$value."')");
	return $res_preference;
}

/* Mise � jour d'une pr�f�rence */
function updatePreference($playerID, $preference, $value)
{
	
	$res_pref = mysql_query("UPDATE preferences SET value = '".$value."' WHERE playerID = ".$playerID." AND preference = '".$preference."'");
	
	if ($res_pref)	
		return TRUE;
	else
		return FALSE;
}

/* Ins�rer un cong� */
/* Format date YYYY-MM-DD */
function insertVacation($playerID, $duration)
{
	$beginDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+1,  date("Y")));
	$endDate = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")+$duration,  date("Y")));
	
	$res_absence = mysql_query("INSERT INTO vacation (playerID, beginDate, endDate, duration) 
								VALUES (".$playerID.", '".$beginDate."', '".$endDate."', ".$duration.")");
	return $res_absence;
}

/* Compte le nombre de jours d'absence pour un joueur sur une ann�e */
/* Format de l'ann�e YYYY */
function countVacation($playerID, $year)
{
	// Nombre de jours pour cong�s compl�tement sur l'ann�e
	$res = mysql_query("SELECT SUM(duration) nbVacation FROM vacation WHERE playerID=".$playerID." AND YEAR(endDate)=".$year)  or die(mysql_error()."\n".$requete);
	$res_vacation = mysql_fetch_array($res, MYSQL_ASSOC);   
	
	return $res_vacation['nbVacation'] + $d;;
}

/* R�cup�re les vacances en cours d'un joueur */
function getCurrentVacation($playerID)
{
	$res_vacation = mysql_query("SELECT beginDate, DATE_FORMAT(beginDate, '%d/%m/%Y') beginDateF, endDate, DATE_FORMAT(endDate, '%d/%m/%Y') endDateF, duration 
								FROM vacation 
								WHERE playerID=".$playerID." 
								AND endDate >= NOW()");
	return $res_vacation;
}

/* Cr�er un favori joueur */
function insertFavPlayer($playerID, $favPlayerID)
{
	
	$res_favplayer = mysql_query("INSERT INTO fav_players (playerID, favPlayerID) 
								VALUES (".$playerID.", '".$favPlayerID."')");
	return $res_fav_player;
}

/* Supprimer un favori joueur */
function deleteFavPlayer($favoriteID)
{
	$res_fav_player = mysql_query("DELETE FROM fav_players WHERE favoriteID = ".$favoriteID);  
							
	return $res_fav_player;
}

/* Liste les favoris d'un joueur */
function listPlayersFavoris($playerID)
{
	$tmpQuery = "SELECT P.playerID, P.nick, P.anneeNaissance, P.profil, P.situationGeo, P.elo 
				FROM players P, fav_players F
				WHERE P.playerID = F.favPlayerID 
				AND F.playerID = ".$playerID." 
				AND P.playerID <> ".$playerID." 
				AND P.activate=1 
				ORDER BY P.lastConnection DESC";
	
	return mysql_query($tmpQuery); 
}

/* R�cup�re un favori */
function getPlayerFavorite($playerID, $favPlayerID)
{
	$res_favorite = mysql_query("SELECT favoriteID FROM fav_players WHERE playerID = ".$playerID." AND favPlayerID = ".$favPlayerID);
    $favorite = mysql_fetch_array($res_favorite, MYSQL_ASSOC);
    return $favorite;
}

/* Liste joueurs par tranche de Elo */
function listPlayersByLevel($level)
{
	switch ($level)
	{
		case "DEB":
			$levelCondition = "elo < 1300";
			break;
		
		case "MOY":
			$levelCondition = "elo = 1300";
			break;
			
		case "COF":
			$levelCondition = "elo > 1300 AND elo <= 1400";
			break;
			
		case "MAI":
			$levelCondition = "elo > 1400";
			break;
		
	}
	
	$tmpQuery = "SELECT playerID, nick, anneeNaissance, profil, situationGeo, elo 
				FROM players 
				WHERE playerID <> ".$_SESSION['playerID']." 
				AND ".$levelCondition." 
				AND activate=1 ORDER BY lastConnection DESC";
	
	return mysql_query($tmpQuery); 
}

function listEloProgress($playerID)
{
	$tmpQuery = "SELECT elo, DATE_FORMAT(eloDate, '%c/%y') eloDateF
				FROM elo_history 
				WHERE playerID = ".$playerID." 
				ORDER BY eloDate ASC";
	
	return mysql_query($tmpQuery);
}

/*
 * Recherche des utilisateurs
 * $mode : count = renvoi le nb de r�sultat de la recherche sinon le r�sultat
 * $debut :
 * $limit : nb r�sultat par page 
 * 
 */
function searchPlayers($mode, $debut, $limit, $critFavorite, $critStatus, $critEloStart, $critEloEnd)
{
	
	if ($mode=="count")
		$tmpQuery = "SELECT count(*) nbPlayers 
				FROM players P left join online_players O on O.playerID = P.playerID";
	else
		$tmpQuery = "SELECT P.playerID, P.nick, P.anneeNaissance, P.profil, P.situationGeo, P.elo, O.lastActionTime, P.creationDate 
				FROM players P left join online_players O on O.playerID = P.playerID";
	
	if ($critFavorite == "oui")
		$tmpQuery .= ", fav_players F";
	
	$tmpQuery .= " WHERE activate=1 
				AND P.playerID <> ".$_SESSION['playerID'];
	
	if ($critStatus == "actif")			 
		$tmpQuery .= " AND DATE_ADD(P.lastConnection, INTERVAL 14 DAY) >= NOW()"; 
	
	if ($critStatus == "passif")			
		$tmpQuery .= " AND DATE_ADD(P.lastConnection, INTERVAL 14 DAY) < NOW()";
	
	if ($critEloStart != '' and $critEloEnd != '')
		$tmpQuery .= " AND P.elo >= ".$critEloStart." AND P.elo <= ".$critEloEnd;
	if ($critEloStart != '' and $critEloEnd == '')		
		$tmpQuery .= " AND P.elo >= ".$critEloStart;
	if ($critEloStart == '' and $critEloEnd != '')	
		$tmpQuery .= " AND P.elo <= ".$critEloEnd;		
				
	if ($critFavorite == "oui")			
				$tmpQuery .= " AND P.playerID = F.favPlayerID 
				AND F.playerID = ".$_SESSION['playerID'];
				 
		$tmpQuery .= " ORDER BY O.lastActionTime DESC, P.nick ASC";
				
	if ($mode != "count")
		$tmpQuery .= " limit ".$debut.",".$limit;

	return mysql_query($tmpQuery);
}

/* Charger un utilisateur par son ID */
function getOnlinePlayer($playerID)
{
	$res_olplayer = mysql_query("SELECT * FROM online_players WHERE playerID = ".$playerID);
    $olplayer = mysql_fetch_array($res_olplayer, MYSQL_ASSOC);
    return $olplayer;
}

/* Ins�rer joueur en ligne */	
function insertOnlinePlayer($playerID)
{
	$res_olplayer = mysql_query("INSERT INTO online_players (playerID, lastActionTime) 
								VALUES (".$playerID.", now())");

	if ($res_olplayer)	
		return mysql_insert_id();
	else
		return FALSE;
}

/* Mettre � jour joueur en ligne*/
function updateOnlinePlayer($playerID)
{ 		
	  $res_olplayer = mysql_query("UPDATE online_players 
	  							SET lastActionTime = now() 
	  							WHERE playerID = ".$playerID);
	  
	if ($res_olplayer)	
		return TRUE;
	else
		return FALSE;
}

/* Supprime tous les joueurs hors ligne */
function deleteOnlinePlayers()
{
	$res_olplayer = mysql_query("DELETE FROM online_players 
	  							WHERE now() > DATE_ADD(lastActionTime, INTERVAL 10 MINUTE)");
	
	return $res_olplayer;
}

function countOnlinePlayers()
{
	$res_olplayer = mysql_query("SELECT count(playerID) nbPlayers FROM online_players");
	return mysql_fetch_array($res_olplayer, MYSQL_ASSOC);
}
?>