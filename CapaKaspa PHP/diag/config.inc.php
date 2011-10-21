<?
/* Chemin (relatif ou absolu) du r�pertoire o� sont stock�es les images */
/* Mettre un slash � la fin */

$base_url = "./pieces29/";

/* Taille des images en pixels (29 ou 35) */

$image_size = 29;

/* Couleur des cases "blanches" */
/* Couleurs pr�d�finies : */
/* white,black,grey,green,blue,brown,lightyellow,lightbrown */
/* Pour d�finir d'autres couleurs, �diter le fichier includes.inc.php */

$light = $white;

/* Couleur des cases "noires" */
/* Couleurs pr�d�finies : */
/* white,black,grey,green,blue,brown,lightyellow,lightbrown */
/* Pour d�finir d'autres couleurs, �diter le fichier includes.inc.php */

$dark = $blue;

/* Couleur de fond de la page web */

$background_color = $white;

/* Epaisseur de la bordure de l'�chiquier, en pixels */

$border_size = 2;

/* Code de la police utilis�e pour les coordonn�es */
/* Entier compris entre 1 et 5, � modifier �ventuellement */

$font = 5;

/* Faut-il retourner l'echiquier ?? */
/* Par defaut non ! 0 = pas de flip */

$flip = 0;

/* Mais au fait, veut-on afficher les coordonn�es ? (true ou false) */

$coords = true;

/* Faut-il envoyer un en-t�te (cas d'une image "nue"), ou l'image
est-elle destin�e � �tre incluse dans une page Web ? Laisser la
variable � "true" dans le premier cas, la mettre � "false" dans le
second */

$hdr = true;

/* C'est tout pour l'instant ... */
/* Ne modifier ce qui suit qu'� vos risques et p�rils ! */

/* ----------------------------------------------------------------*/

$board_size = $image_size*8;

/* Pour certaines installation de php-gd il peut �tre n�cessaire
   de d�commenter la ligne suivante */

/* dl("gd.so"); */

if (function_exists("imagepng")) {
   $suffix = ".png";
   $img_create = 'imagecreatefrompng';
   $header = "Content-type: image/png";
   $img = 'imagepng';
}
elseif (function_exists("imagegif")) {
   $suffix = ".gif";
   $img_create = 'imagecreatefromgif';
   $header = "Content-type: image/gif";
   $img = 'imagegif';
}
else {
   die("Il est impossible d'utiliser le script (fonctions graphiques
   absentes) !");
}

/* Le package php3-gd de la debian potato semble bugg� et n�cessite
   de remplacer le bloc pr�cedent par celui-ci */


/*  if (function_exists("imagegif")) { */
/*     $suffix = ".gif"; */
/*     $img_create = 'imagecreatefromgif'; */
/*     $header = "Content-type: image/gif"; */
/*     $img = 'imagegif'; */
/*  } */
/*  else { */
/*     die("Il est impossible d'utiliser le script (fonctions graphiques */
/*     absentes) !"); */
/*  } */


/* La fonction substr_count() n'�tant disponible que 
   depuis php4 >= 4.0RC2, le code suivant permet d'assurer la 
   compatibilit� avec les installations php3. */

if (!function_exists("substr_count")) {
  function substr_count($haystack, $needle) {
    $lh = strlen($haystack);
    $ln = strlen($needle);
    $count = 0;
    
    for ($i = 0; $i < ($lh-$ln+1) ; $i++)
      {
	if ( $needle == substr($haystack, $i, $ln))
	  {
	    $count++;
	  }
      }
    
    return $count;
  }
}
?>
