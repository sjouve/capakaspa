<?
    /*commentaire*/
    session_start();

	/* load settings */
	if (!isset($_CONFIG))
		require 'config.php';

	/* load external functions for setting up new game */
	require 'bwc_games.php';
	
	/* connect to database */
	require 'connectdb.php';

	$errMsg = "";

	/* check session status */
	require 'sessioncheck.php';

	/* set default playing mode to different PCs (as opposed to both players sharing a PC) */
	$_SESSION['isSharedPC'] = false;
	
	$titre_page = 'Vos parties termin�es';
    require 'page_header.php';
?>
    <script type="text/javascript">

		function loadEndedGame(gameID)
		{
			document.endedGames.gameID.value = gameID;
			document.endedGames.submit();
		}

<? if ($CFG_USEEMAILNOTIFICATION) { ?>
		function testEmail()
		{
			document.preferences.ToDo.value = "TestEmail";
			document.preferences.submit();
		}
<? } ?>
	</script>
<?
    $image_bandeau = 'bandeau_capakaspa_zone.jpg';
    $barre_progression = "Echecs en diff�r� > Vos parties termin�es";
    require 'page_body.php';
?>
  <div id="contentlarge">
    <div class="blogbody">
	  <?
		if ($errMsg != "")
			echo("<div class='error'>".$errMsg."</div>");
		?>
		<table>
		<tr>
		<td valign="middle"><img src="images/ampoule.jpg"></td> 
		<td valign="middle">Analysez rapidement vos d�faites pour �viter de faire les m�mes erreurs !</td>
		
        </tr>
        </table>
    
      <form name="endedGames" action="partie.php" method="post">
        <?
    $tmpGames = mysql_query("SELECT G.gameID gameID, G.eco eco, E.name ecoName, W.playerID whitePlayerID, W.nick whiteNick, B.playerID blackPlayerID, B.nick blackNick, G.gameMessage gameMessage, G.messageFrom messageFrom, DATE_FORMAT(G.dateCreated, '%d/%m/%Y %T') dateCreated, DATE_FORMAT(G.lastMove, '%d/%m/%Y %T') lastMoveF
                                FROM games G, players W, players B, eco E 
								WHERE (G.gameMessage <> '' AND G.gameMessage <> 'playerInvited' AND G.gameMessage <> 'inviteDeclined')
                                AND (G.whitePlayer = ".$_SESSION['playerID']." OR G.blackPlayer = ".$_SESSION['playerID'].")
                                AND ((G.gameMessage = 'playerResigned' AND G.messageFrom = 'white' AND G.whitePlayer = ".$_SESSION['playerID'].")
                                    OR (G.gameMessage = 'playerResigned' AND G.messageFrom = 'black' AND G.blackPlayer = ".$_SESSION['playerID'].")
                                    OR (G.gameMessage = 'checkMate' AND G.messageFrom = 'black' AND G.whitePlayer = ".$_SESSION['playerID'].")
                                    OR (G.gameMessage = 'checkMate' AND G.messageFrom = 'white' AND G.blackPlayer = ".$_SESSION['playerID'].")) 
								AND W.playerID = G.whitePlayer AND B.playerID = G.blackPlayer
								AND G.eco = E.eco
                                ORDER BY G.eco ASC, G.lastMove DESC");
		?>
        
		<h3>D�faites (<?echo(mysql_num_rows($tmpGames));?>)</h3>
        <div id="tabliste">
          <table border="0" width="650">
            <tr>
              <th width="17%">Blancs</th>
              <th width="17%">Noirs</th>
              <th width="8%">R�sultat</th>
              <th width="8%">ECO</th>
              <th width="25%">D�but</th>
              <th width="25%">Dernier coup</th>
            </tr>
            
	<?
	if (mysql_num_rows($tmpGames) == 0)
		echo("<tr><td colspan='6'>Vous n'avez aucune d�faite</td></tr>\n");
	else
	{
		while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
		{
			/* White */
			echo("<tr><td>");
			echo("<a href='profil_consultation.php?playerID=".$tmpGame['whitePlayerID']."'>".$tmpGame['whiteNick']."</a>");
			
			/* Black */
			echo ("</td><td>");
			echo("<a href='profil_consultation.php?playerID=".$tmpGame['blackPlayerID']."'>".$tmpGame['blackNick']."</a>");
			
			/* Status */
			if (is_null($tmpGame['gameMessage']))
				echo("</td><td>&nbsp;");
			else
			{
				if (($tmpGame['gameMessage'] == "playerResigned") && ($tmpGame['messageFrom'] == "white"))
					echo("</td><td align=center><a href='javascript:loadEndedGame(".$tmpGame['gameID'].")'>0-1</a>");
				else if (($tmpGame['gameMessage'] == "playerResigned") && ($tmpGame['messageFrom'] == "black"))
					echo("</td><td align=center><a href='javascript:loadEndedGame(".$tmpGame['gameID'].")'>1-0</a>");
				else if (($tmpGame['gameMessage'] == "checkMate") && ($tmpGame['messageFrom'] == "white"))
					echo("</td><td align=center><a href='javascript:loadEndedGame(".$tmpGame['gameID'].")'>1-0</a>");
				else if ($tmpGame['gameMessage'] == "checkMate")
					echo("</td><td align=center><a href='javascript:loadEndedGame(".$tmpGame['gameID'].")'>0-1</a>");
				else
					echo("</td><td>&nbsp;");
			}
			
			/* ECO Code */
			echo ("</td><td align='center'><span title='".$tmpGame['ecoName']."'>".$tmpGame['eco']);
			
			/* Start Date */
			echo ("</span></td><td align='center'>".$tmpGame['dateCreated']);

			/* Last Move */
			echo ("</td><td align='center'>".$tmpGame['lastMoveF']."</td></tr>\n");
		}
	}
?>
          </table>
        </div>
        
        <?
	$tmpGames = mysql_query("SELECT G.gameID, G.eco eco, E.name ecoName, W.playerID whitePlayerID, W.nick whiteNick, B.playerID blackPlayerID, B.nick blackNick, G.gameMessage, G.messageFrom, DATE_FORMAT(G.dateCreated, '%d/%m/%Y %T') dateCreated, DATE_FORMAT(G.lastMove, '%d/%m/%Y %T') lastMoveF
                                FROM games G, players W, players B, eco E
                                WHERE (G.gameMessage <> '' AND G.gameMessage <> 'playerInvited' AND G.gameMessage <> 'inviteDeclined')
                                AND (G.whitePlayer = ".$_SESSION['playerID']." OR G.blackPlayer = ".$_SESSION['playerID'].")
                                AND G.gameMessage = 'draw'
                                AND W.playerID = G.whitePlayer AND B.playerID = G.blackPlayer
                                AND G.eco = E.eco
                                ORDER BY E.eco ASC, G.lastMove DESC");?>
		
		<br/><center><script type="text/javascript"><!--
        google_ad_client = "ca-pub-8069368543432674";
        /* CapaKaspa Parties terminees Bandeau Centre */
        google_ad_slot = "1022904258";
        google_ad_width = 468;
        google_ad_height = 60;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
		</center>
		<br/>
		
		<h3>Nulles (<?echo(mysql_num_rows($tmpGames));?>)</h3>
        <div id="tabliste">
          <table border="0" width="650">
            <tr>
              <th width="17%">Blancs</th>
              <th width="17%">Noirs</th>
              <th width="8%">R�sultat</th>
              <th width="8%">ECO</th>
              <th width="25%">D�but</th>
              <th width="25%">Dernier coup</th>
            </tr>
            
	<?
	if (mysql_num_rows($tmpGames) == 0)
		echo("<tr><td colspan='6'>Vous n'avez aucune partie nulle</td></tr>\n");
	else
	{
		while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
		{
			/* White */
			echo("<tr><td>");
			echo("<a href='profil_consultation.php?playerID=".$tmpGame['whitePlayerID']."'>".$tmpGame['whiteNick']."</a>");
			
			/* Black */
			echo ("</td><td>");
			echo("<a href='profil_consultation.php?playerID=".$tmpGame['blackPlayerID']."'>".$tmpGame['blackNick']."</a>");

			/* Status */
			if (is_null($tmpGame['gameMessage']))
				echo("</td><td>&nbsp;");
			else
			{

					echo("</td><td align=center><a href='javascript:loadEndedGame(".$tmpGame['gameID'].")'>1/2-1/2</a>");
			}
			/* ECO Code */
			echo ("</td><td align='center'><span title='".$tmpGame['ecoName']."'>".$tmpGame['eco']);
			/* Start Date */
			echo ("</td><td align='center'>".$tmpGame['dateCreated']);

			/* Last Move */
			echo ("</td><td align='center'>".$tmpGame['lastMoveF']."</td></tr>\n");
		}
	}
?>
          </table>
        </div>
         
          <?
	$tmpGames = mysql_query("SELECT G.gameID, G.eco eco, E.name ecoName, W.playerID whitePlayerID, W.nick whiteNick, B.playerID blackPlayerID, B.nick blackNick, G.gameMessage, G.messageFrom, DATE_FORMAT(G.dateCreated, '%d/%m/%Y %T') dateCreated, DATE_FORMAT(G.lastMove, '%d/%m/%Y %T') lastMoveF
                                FROM games G, players W, players B, eco E WHERE (G.gameMessage <> '' AND G.gameMessage <> 'playerInvited' AND G.gameMessage <> 'inviteDeclined')
                                AND (G.whitePlayer = ".$_SESSION['playerID']." OR G.blackPlayer = ".$_SESSION['playerID'].")
                                AND ((G.gameMessage = 'playerResigned' AND G.messageFrom = 'white' AND G.blackPlayer = ".$_SESSION['playerID'].")
                                    OR (G.gameMessage = 'playerResigned' AND G.messageFrom = 'black' AND G.whitePlayer = ".$_SESSION['playerID'].")
                                    OR (G.gameMessage = 'checkMate' AND G.messageFrom = 'black' AND G.blackPlayer = ".$_SESSION['playerID'].")
                                    OR (G.gameMessage = 'checkMate' AND G.messageFrom = 'white' AND G.whitePlayer = ".$_SESSION['playerID']."))
                                AND W.playerID = G.whitePlayer AND B.playerID = G.blackPlayer
                                AND G.eco = E.eco
                                ORDER BY E.eco ASC, G.lastMove DESC");?>
                                

	<br/>
	
	<h3>Victoires (<?echo(mysql_num_rows($tmpGames));?>)</h3>
        <div id="tabliste">
          <table border="0" width="650">
            <tr>
              <th width="17%">Blancs</th>
              <th width="17%">Noirs</th>
              <th width="8%">R�sultat</th>
              <th width="8%">ECO</th>
              <th width="25%">D�but</th>
              <th width="25%">Dernier coup</th>
            </tr>
           
	<?
	if (mysql_num_rows($tmpGames) == 0)
		echo("<tr><td colspan='6'>Vous n'avez aucune victoire</td></tr>\n");
	else
	{
		while($tmpGame = mysql_fetch_array($tmpGames, MYSQL_ASSOC))
		{
			/* White */
			echo("<tr><td>");
			echo("<a href='profil_consultation.php?playerID=".$tmpGame['whitePlayerID']."'>".$tmpGame['whiteNick']."</a>");
			
			/* Black */
			echo ("</td><td>");
			echo("<a href='profil_consultation.php?playerID=".$tmpGame['blackPlayerID']."'>".$tmpGame['blackNick']."</a>");

			/* Status */
			if (is_null($tmpGame['gameMessage']))
				echo("</td><td>&nbsp;");
			else
			{

                if (($tmpGame['gameMessage'] == "playerResigned") && ($tmpGame['messageFrom'] == "white"))
					echo("</td><td align=center><a href='javascript:loadEndedGame(".$tmpGame['gameID'].")'>0-1</a>");
				else if (($tmpGame['gameMessage'] == "playerResigned") && ($tmpGame['messageFrom'] == "black"))
					echo("</td><td align=center><a href='javascript:loadEndedGame(".$tmpGame['gameID'].")'>1-0</a>");
				else if (($tmpGame['gameMessage'] == "checkMate") && ($tmpGame['messageFrom'] == "white"))
					echo("</td><td align=center><a href='javascript:loadEndedGame(".$tmpGame['gameID'].")'>1-0</a>");
				else if ($tmpGame['gameMessage'] == "checkMate")
					echo("</td><td align=center><a href='javascript:loadEndedGame(".$tmpGame['gameID'].")'>0-1</a>");
				else
					echo("</td><td>&nbsp;");
			}
			/* ECO Code */
			echo ("</td><td align='center'><span title='".$tmpGame['ecoName']."'>".$tmpGame['eco']);
			/* Start Date */
			echo ("</td><td align='center'>".$tmpGame['dateCreated']);

			/* Last Move */
			echo ("</td><td align='center'>".$tmpGame['lastMoveF']."</td></tr>\n");
		}
	}
?>
          </table>
        </div>
        
        <input type="hidden" name="gameID" value="">
        <input type="hidden" name="sharePC" value="no">
        <input type="hidden" name="from" value="archive">
      </form>

    </div>
  </div>
<?
    require 'page_footer.php';
    mysql_close();
?>
