<?
	session_start();
	$titre_page = "La boutique jeu d'�checs";
	$desc_page = "La boutique du jeu d'�checs, d�couvrez une s�lection de livres, logiciels et mat�riel.";
    require 'page_header.php';
    $image_bandeau = 'bandeau_capakaspa_global.jpg';
    $barre_progression = "<a href='/'>Accueil</a> > La boutique du jeu d'�checs";
    require 'page_body_no_menu.php';
?>
    <div id="contentxlarge">
    	<iframe src="http://astore.amazon.fr/capa0e-21" width="90%" height="4000" frameborder="0" scrolling="no"></iframe>
	</div>
<?
    require 'page_footer.php';
?>
