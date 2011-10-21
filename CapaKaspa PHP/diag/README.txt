Pour installer DIAGOL :

0) Editer le fichier config.inc.php.

1) Choisir la taille d�sir�e pour les figurines (29 ou 35 pixels).

2) D�placer le contenu du r�pertoire "pieces" correspondant dans le
r�pertoire de votre choix.

3) Indiquer le nom de ce r�pertoire dans le fichier
config.inc.php (variable $base_url). Modifier �ventuellement la valeur
de la variable $image_size.
Par exemple, si vous choisissez une taille de 35 pixels pour les figurines, vous pouvez copier le contenu du r�pertoire pieces35/ dans un nouveau r�pertoire pieces/, et d�finir
$base_url = "./pieces/";
$image_size = 35;

4) Toujours dans le fichier de configuration config.inc.php, d�finir les couleurs des cases, l'�paisseur de la bordure de l'�chiquier, l'affichage ou non des coordonn�es (le mieux consistant � effectuer des essais ...). La valeur de la variable $hdr sera "false" en g�n�ral.

5) Placer les fichiers diagol.php, fen2classic.php, includes.inc.php, sub_fonction.inc.php et config.inc.php dans le m�me r�pertoire.
Si votre h�bergeur en est rest� � la version 3 de PHP, il peut �tre utile de renommer diagol.php en diagol.php3

6) Le script s'appelle en fournissant une valeur valide � la variable "position". Exemple de code HTML correct :
<img src="http://ajec-echecs.org/forum/diagol.php?position=B:Rg1,Dd1,Ta1,e1,Pa2,f6/N:Rb8,Pa6,f5">
ou encore (position FEN) :
<img src="http://ajec-echecs.org/forum/diagol.php?position=r1bqkbnr/pp2pppp/2n5/2p1P3/3p4/2P2N2/PP1P1PPP/RNBQKB1R">

7) Les options disponibles sont d�taill�es � l'URL http://diagol.ajec-echecs.org/diagol.html

8) Si l'un des points pr�c�dents est obscur, contactez-moi � l'adresse
webmaster@ajec-echecs.org !
