</head>
<body <?echo($attribut_body)?>>
<?
$jour["Monday"] = "Lundi";
$jour["Tuesday"] = "Mardi";
$jour["Wednesday"] = "Mercredi";
$jour["Thursday"] = "Jeudi";
$jour["Friday"] = "Vendredi";
$jour["Saturday"] = "Samedi";
$jour["Sunday"] = "Dimanche";

function getJour($day) 
{
	return $jour[$day];
}

$mois["January"] = "Janvier";
$mois["February"] = "F�vrier";
$mois["March"] = "Mars";
$mois["April"] = "Avril";
$mois["May"] = "Mai";
$mois["June"] = "Juin";
$mois["July"] = "Juillet";
$mois["August"] = "Ao�t";
$mois["September"] = "Septembre";
$mois["October"] = "Octobre";
$mois["November"] = "Novembre";
$mois["December"] = "D�cembre";

function getMois($month)
{
	return $mois[$month];
}

$month = Date(F);
$day = Date(l);

getJour($day);
getMois($month);

?>

<div id="container">
	
  <div id="topbar">
  	<table cellpadding="0" cellspacing="0" width="900" style="background-image: url(./images/<?echo($image_bandeau)?>) ">
  		<tr height="60">
			<td width="430" height="60"><a href="http://www.capakaspa.info" style="display: block; height: 100%; width: 100%;">&nbsp;</a></td>
			<td width="470" colspan="4">
        	
          <!--/* CapaKaspa Bandeau Haut */-->
          
			
        	</td>
		</tr>
    	<tr height="20">
			<td width="400"><h1><?
			print "$jour[$day] ";
			print Date(d)." ";
			print "$mois[$month] ";
			print Date(Y);?></h1></td>
			<td width="125"><h2><img src="images/point.png"/>&nbsp;<a href="tableaubord.php">ZONE JEUX</a></h2></td>
			<td width="125"><h2><img src="images/point.png"/>&nbsp;<a href="http://forum.capakaspa.info">FORUM</a></h2></td>
			<td width="125"><h2><img src="images/point.png"/>&nbsp;<a href="http://blog.capakaspa.info">BLOG</a></h2></td>
			<td width="125"><h2><img src="images/point.png"/>&nbsp;<a href="http://www.facebook.com/capakaspa" target="_blank">FACEBOOK</a></h2></td>
		</tr>
    </table>
  </div>
  
	<div id="progressbar">
    
    <table width="100%" cellpadding="2" cellspacing="0">
	   <tr>
    	<td align="center" width="20">
        	<img src="images/puce.gif"/>
        </td>
    	<td width="400">
        	<? echo($barre_progression) ?>
        </td>
        <td align="right" width="490">
        
        	<form action="http://www.capakaspa.info/recherche.php" id="cse-search-box">
			  <div>
			    <input type="hidden" name="cx" value="partner-pub-8069368543432674:5914611902" />
			    <input type="hidden" name="cof" value="FORID:10" />
			    <input type="hidden" name="ie" value="UTF-8" />
			    <input type="text" name="q" size="31" />
			    <input type="submit" name="sa" value="Rechercher" />
			  </div>
			</form>
			
			<script type="text/javascript" src="http://www.google.fr/coop/cse/brand?form=cse-search-box&amp;lang=fr"></script>

    
    	</td>
	</tr>
	</table>
  </div>
  
  <div id="contentxlarge">
    <center>
      <script type="text/javascript"><!--
      google_ad_client = "ca-pub-8069368543432674";
      /* CapaKaspa Leaderboard */
      google_ad_slot = "6207727366";
      google_ad_width = 728;
      google_ad_height = 90;
      //-->
      </script>
      <script type="text/javascript"
      src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
      </script>
    </center>
    <br/>
    <div class="skyscraper">
	  	<script type="text/javascript"><!--
	    google_ad_client = "ca-pub-8069368543432674";
	    /* CapaKaspa Accueil Bandeau Droite */
	    google_ad_slot = "2254640927";
	    google_ad_width = 160;
	    google_ad_height = 600;
	    //-->
	    </script>
	    <script type="text/javascript"
	    src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	    </script>
  	</div>
  </div>
  
    
