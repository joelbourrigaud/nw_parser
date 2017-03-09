<?php
    // redirige la page index.php vers la page detections directement.
    header('Location: ./detections.php');
    exit();
    /*
     * Le code ci-apres n'est plus éxécuté.
    */
?>

<html>
	<head>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="./stylesheet.css" />
		<script language="Javascript">
			/* Placez ici le code de votre script */
			<!-- ce tag permet de masquer les scripts pour les anciens browser
			
			// affichage ou non des bloc html DIV.
			function affichageBloc(bloc)
			{
				/** display: none;  autorise ou non affichage de la balise. @see http://www.alsacreations.com/actu/lire/111-display-vous-connaissez.html */
				if ('detection' == bloc)
				{
					document.getElementById('detection').style.display = "block";
					document.getElementById('listeMembres').style.display = "none";
					document.getElementById('fichePerso').style.display = "none";
				}
				else if ('membres' == bloc)
				{
					document.getElementById('listeMembres').style.display = "block";
					document.getElementById('detection').style.display = "none";
					document.getElementById('fichePerso').style.display = "none";
				}
				else if ('fichePerso' == bloc)
				{
					document.getElementById('fichePerso').style.display = "block";
					document.getElementById('listeMembres').style.display = "block";
				}
			}
			
			
			
		</script>
		<noscript>
			Cette page utilise du code javascript pour l'affichage des informations. Certaines informations ne seront pas affichéées correctement.
			Veuillez activer javascript dans votre navigateur.
		</noscript>
	</head>
	
	<body style="background-color: rgb(255,255,255);">		
	  <?php include "menus.php"; ?>
	</body>

</html>