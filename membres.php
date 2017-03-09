<?php

if (isset($_POST["nFiche"]) ){
					$nstr = ereg_replace("[\n\t]+"," ", $_POST["nFiche"]);
			/* Recuperer les champs PV PP PI PF */
      //Points de Vie 211/149 [125 +24] Force 381 [306 +75] Précision 32 [50 -18] Intelligence 83 [153 -70]
		  ereg(".* Points de Vie ([0-9/]+) .* Force ([0-9]+) .* Précision ([0-9]+) .* Intelligence ([0-9]+) .* Votre cible (.*) Longueur de la barbe", $nstr, $fiche);

}
?>

<html>
	<head>
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="./stylesheet.css" />
		<script language="Javascript">
			/* Placez ici le code de votre script */
			<!-- ce tag permet de masquer les scripts pour les anciens browser
			// affichage de la fiche perso du nain selectionné
			function infosUsr(cellule)
			{
				var nain = cellule.id;
				document.getElementById('fichePerso').style.display = "block";
				
			}
			// -->
		</script>
	</head>
	
	<body style="background-color: rgb(255,255,255);">		
		<?php include "menus.php"; ?>		
		
		<div id="listeMembres" class="divListeMembres">
			<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
				<tr>
					<td><img src="./img/interface/coin_NO.gif" /></td>
					<td colspan="3" class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_NE.gif" /></td>
				</tr>			
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td class="tabMembresCellHeader" width="40%">Nom du nain &nbsp;</td>
					<td class="tabMembresCellHeader" width="35%">Sa position &nbsp;</td>
					<td class="tabMembresCellHeader" width="20%">Dernière mise à jour &nbsp;</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td><img src="./img/interface/coin_OE.gif" /></td>
					<td colspan="3" class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_EO.gif" /></td>
				</tr>
				<?php
					/* On utilisera php et une bdd pour avoir la liste complete des informations sur les membres actifs de la guilde. */
				?>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td>
					  <div></div>
					</td><td id="usr_1" class="membresCell" onclick="infosUsr(this);">SuperNainNain &nbsp;</td>
					<td id="monde_24" class="membresCell" onclick="infosMonde(this);">Pipe-World - position:15,6 &nbsp;</td>
					<td class="membresCell">le 03/08/09 &nbsp;</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				
				<tr>
					<td><img src="./img/interface/coin_SO.gif" /></td>
					<td class="tabFiltreBande" colspan="3">&nbsp;</td>
					<td><img src="./img/interface/coin_SE.gif" /></td>
				</tr>
			</table>
		</div>
		
		<div id="chargeFichePerso" class="divUpdateFiche">
			<table cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
				<tr>
					<td><img src="./img/interface/coin_NO.gif" /></td>
					<td class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_NE.gif" /></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td align="center" style="vertical-align: middle;">Fiche Perso:</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td><img src="./img/interface/coin_OE.gif" /></td>
					<td class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_EO.gif" /></td>
				</tr>
				<!-- cadre formulaire detection -->
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td>
						<form action="membres.php" method="post">
							<textarea name="nFiche" cols="55" rows="10"></textarea>
							<input type="submit" value="Envoyer"/>
						</form>
					</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td><img src="./img/interface/coin_SO.gif" /></td>
					<td class="tabFiltreBande" width="100%">&nbsp;</td>
					<td><img src="./img/interface/coin_SE.gif" /></td>
				</tr>
			</table>
		</div>

		<div id="fichePerso" class="divFichePerso">
			<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
				<tr>
					<td><img src="./img/interface/coin_NO.gif" /></td>
					<td colspan="2" class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_NE.gif" /></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td class="tabFichePersoCellHeader" colspan="2">Fiche perso - le 03/08/09</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td><img src="./img/interface/coin_OE.gif" /></td>
					<td colspan="2" class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_EO.gif" /></td>
				</tr>
				<?php
					/* Parser la bdd pour avoir la fiche perso du nain - PF PV  PP PI et autres informations.... */
				?>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td class="fichePersoCell">PV :</td><td class="fichePersoCell"><?php echo $fiche[1]; ?></td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td class="fichePersoCell">Force :</td><td class="fichePersoCell"><?php echo $fiche[2]; ?></td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td class="fichePersoCell">Précision :</td><td class="fichePersoCell"><?php echo $fiche[3]; ?></td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td class="fichePersoCell">Intelligence :</td><td class="fichePersoCell"><?php echo $fiche[4]; ?></td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td colspan="2">&nbsp;</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td class="fichePersoCell">Cible :</td><td class="fichePersoCell"><?php echo $fiche[5]; ?></td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td class="fichePersoCell">Nombre de chasseurs :</td><td class="fichePersoCell">2 (Longueur moyenne de la barbe : 88.4cm)</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td><img src="./img/interface/coin_SO.gif" /></td>
					<td class="tabFiltreBande" colspan="2">&nbsp;</td>
					<td><img src="./img/interface/coin_SE.gif" /></td>
				</tr>
			</table>
		</div>
		
	</body>
</html>
