<?
	session_start();
	$titre_page = 'Jouer aux �checs avec Crazy Chess';
    require 'page_header.php';
?>

<?
    $image_bandeau = 'bandeau_capakaspa_zone.jpg';
    $barre_progression = "Jeux en ligne > Jouer � Crazy Chess";
    require 'page_body.php';
?>
  <div id="contentlarge">
    <div class="blogbody">
    <table>
		<tr>
		<td valign="middle"><img src="images/ampoule.jpg"></td> 
		<td valign="middle">Ma�trisez les mouvements du Cavalier gr�ce � ce petit jeu un peu fou !</td>
        </tr>
        </table>
        <br/>
        <!-- AddThis Button BEGIN -->
      <div class="addthis_toolbox addthis_default_style ">
      <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
      <a class="addthis_button_tweet"></a>
      <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
      <a class="addthis_counter addthis_pill_style"></a>
      </div>
      <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7cb2a45be34669"></script>
      <!-- AddThis Button END -->
		<center>

			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" width="650" height="500">
			<param name=movie value="crazy-chess.swf">
			<param name=quality value=high>
			<embed src="crazy-chess.swf" quality=high pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="650" height="500">
			</embed>
			</object>

		</center>
		<br/>
		
		<center>
			<script type="text/javascript"><!--
			google_ad_client = "pub-8069368543432674";
			/* 468x60, Crazy Chess bandeau bas */
			google_ad_slot = "1352442587";
			google_ad_width = 468;
			google_ad_height = 60;
			//-->
			</script>
			<script type="text/javascript"
			src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
		</center>
		<br/>
		<br/>
		<br/>
		<br/>
	</div>
</div>


<?
    require 'page_footer.php';
?>
