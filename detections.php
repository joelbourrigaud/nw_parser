<?php
/**
* @brief
*   Encode given utf-8 string to ISO-8859-1 string. 
*
* @param theObjet
*	the given utf-8 string
*
**/
function encodeToBiblioNainwak($theObjet)
{
  //convert utf-8 to ISO-8859-1 (ie: Donut géant radioactif --> Donut+g%E9ant+radioactif)
  return rawurlencode ( utf8_decode($theObjet ) );
}
?>

<html>
	<head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" media="screen" type="text/css" title="Design" href="./stylesheet.css" />
    <script type="text/javascript">
      function infosCase(cellule){
        var chaine = cellule.id;
        document.getElementById('statsNains').innerHTML = document.getElementById("data_" + chaine.substring(5,chaine.length)).innerHTML;
        var regex=new RegExp("[_]+", "g");
        var tab_position = chaine.substring(5,chaine.length).split(regex);
        document.getElementById('case_info').innerHTML = "Information case "+tab_position[0]+","+tab_position[1];
      }
    </script>
	</head>
	
	<body style="background-color: rgb(255,255,255);">		

		<?php
		if ( isset($_POST["nDetect"]) && !empty($_POST["nDetect"]) ){

			if ( isset($_POST["sauvegarde"]) ){
			  echo "<p>Btn sauvegarde est actif</p>";
			}
			
			$detect_array = explode("<br />", nl2br(stripslashes( $_POST["nDetect"] ) ) );
			if ( FALSE == preg_match("/Position \(([0-9]+,[0-9]+)\) sur \"([0-9A-Z a-z(')é-ï°]+)\" /", $detect_array[0], $monde) ){
			  echo "<h1>Erreur sur le parsing du monde. On ne trouve pas la position de ton nain.</h1>\n";
			}
					
			/* Recuperer la liste des nains de la detection et mettre dans un tableau surement 2D NainX=>( nom, barbe , position) */
			$index=1;
			while (( 20 > $index ) ){
			  $ret = preg_match("/Vous voyez ([0-9]+) nain\(s\)/", trim($detect_array[$index]), $nbNains);
			if ( FALSE != $ret){
			  break;
			}
				$index++;
			}	
			
			if ( FALSE == $ret){
			  echo "<h1>Parsing sur le nombre de nain incorrect.</h1>\n";
			}
					
			for ($n=0; $n < $nbNains[1]; $n++){
			  $ret = preg_match("/(.*) \(Longueur barbe : ([0-9]+[,.]{0,1}[0-9]*cm) - (.*) - distance : ([0-9]+) - position : ([0-9]+,[0-9]+)\)/", trim($detect_array[$index +2 +($n*3)]), $infosNain);
			if (FALSE == $ret){
			  echo "<h1>Erreur sur la parsing des Nains. La description a changé donc le parseur ne fonctionne plus.</h1>\n";
				  break;
				}
				$nains_array[$n]=array("Nom"=>$infosNain[1], "Niveau"=>$infosNain[2], "Rang"=>$infosNain[3], "Distance"=>$infosNain[4], "Position"=>$infosNain[5]);
			}


			preg_match("/Vous voyez ([0-9]+) objet/", trim($detect_array[$index +1 +$nbNains[1]*3+4]), $nbObjs);
			/* Recuperer la liste des objets de la detection et mettre dans un tableau surement 2D ObjetX=>( nom, position) */
			for ($n=0; $n<$nbObjs[1]; $n++){
				preg_match("/(.*) \(distance : ([0-9]+) position : ([0-9]+, [0-9]+)\)/", trim($detect_array[$index +1 +$nbNains[1]*3+4 +2+($n*2)]), $infosObj);
				$objets_array[$n]=array("Nom"=>$infosObj[1], "Distance"=>$infosObj[2], "Position"=>$infosObj[3]);
			}
		}
		?>
		<?php include "menus.php"; ?>
	
		<div id="detection" class="divDetection" style="visibility: visible;" >
			<table cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
				<tr>
					<td><img src="./img/interface/coin_NO.gif" /></td>
					<td colspan="23" class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_NE.gif" /></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td colspan="23" align="center" style="vertical-align: middle;">
						<?php 
						  $today = getdate();
						  if ( isset($_POST["nDetect"]) ){
						    echo $monde[2]." le ".$today["mday"]."/".$today["mon"]."/".$today["year"]." - ".$today["hours"].":".$today["minutes"];
						  }
						?>
					</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td><img src="./img/interface/coin_OE.gif" /></td>
					<td colspan="23" class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_EO.gif" /></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<!--  data a traiter  -->
					<td class="detectionTopCellHeader" style="border-bottom: 1px solid gray;">X</td>
					<?php
						for( $i = 1; $i < 23; $i++ )
						{
							echo "\n<td class='detectionTopCellHeader'>".$i."</td>";
						}
					?>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<?php

					//boucle de traitement de la map-monde
					include("const_var.inc");
/************************ bloc case hidden ************************/
$bloc_case="\n";
/******************************************************************/
					for( $i=1; $i < 9; $i++ ) {
						echo "<tr>\n";
						echo "\t<td class='tabFiltreCoinO' height='100%'></td>\n";
						echo "\t<td class='detectionLeftCellHeader'>".$i."</td>\n";
						for ( $j=1; $j < 23; $j++ ) {
/************************ bloc case hidden ************************/
$bloc_nain ="";$bloc_obj="";
/******************************************************************/
							$td = "\t<td id='case_".$j."_".$i."' class='detectionCell'"; 
														
							// donner la liste des nains sur la case
							$nb_nain_case=0; $nbBraves=0; $nbSad=0; $nbRampant=0; $nbN1Deci=0;
							$nb_obj_case=0;
if ( isset($_POST["nDetect"]) ){
							foreach($nains_array as $nain){
								if ( strcmp("".$j.",".$i."", $nain['Position']) == 0 ){
									$nb_nain_case++;
									/* Detection du rang pour la couleur de la case */
									if ( FALSE != preg_match("/".addcslashes($nain['Rang'], '()')."/",LISTE_RANG_BRAVE ) ){
										$nbBraves++;
										$braves_array[] = $nain['Nom'];
										$balise_img="<img src='./img/avatar/brave.gif' />";
									}else if ( FALSE != preg_match("/".addcslashes($nain['Rang'], '()')."/",LISTE_RANG_SADIQUE) ) {
										$nbSad++;
										$sadiks_array[]=$nain['Nom'];
										$balise_img="<img src='./img/avatar/sadique.gif' />";
									}else if ( FALSE != preg_match("/".$nain['Rang']."/",LISTE_RANG_RAMPANT) ) {
										$nbRampant++;
										$balise_img="<img src='./img/avatar/rampant.gif'/>";
									}else {
										$nbN1Deci++;
										$balise_img="<img src='./img/avatar/naindeci.gif' />";										
									}
/************************ bloc case hidden ************************/
if ( 1 == $nb_nain_case ){
  $bloc_nain = "<tr><td class='statsDetectionNainsObjetsCellHeader' height='35px'>Le(s) nain(s) pr&eacute;sent(s)</td></tr>";
}
$bloc_nain .= "<tr><td>";
$bloc_nain .= "<table><tr>";
$bloc_nain .= "<td>".$balise_img."</td>";
$bloc_nain .= "<td>".$nain['Nom']." - Barbe:".$nain['Niveau']."</td>";
$bloc_nain .= "</tr></table>";
$bloc_nain .= "</td></tr>";
/******************************************************************/
								}
							}

							foreach($objets_array as $obj){
								if ( strcmp("".$j.", ".$i."", $obj['Position']) == 0){
									$nb_obj_case++;
									if (FALSE != preg_match("/".$obj['Nom']."/", LISTE_VEHICULES) ){
										$vh_present = 1;
									}
/************************ bloc case hidden ************************/
if ( 1 == $nb_obj_case ){
  $bloc_obj = "<tr><td class='statsDetectionNainsObjetsCellHeader' height='35px'>Le(s) objet(s) pr&eacute;sent(s)</td></tr>";
	$bloc_obj .= "<tr><td>";
	$bloc_obj .= "<table cellspacing='0' cellpadding='0' width='100%'><tr>";
	$bloc_obj .= "<td class='statsDetectionNainsObjetsCell'>";
	$bloc_obj .= "<img src='./img/objets/".$obj['Nom']."' />";
	$bloc_obj .= "</td>";
	$bloc_obj .= "<td class='statsDetectionNainsObjetsCell'><b>".$obj['Nom']."</b></td>";
	$bloc_obj .= "<td class='statsDetectionNainsObjetsCell' align='right'><a href='http://www.biblionainwak.com/rechercher/?objet=".encodeToBiblioNainwak($obj['Nom'])."' target='_blank'>biblionainwak&nbsp;</a></td>";
	$bloc_obj .= "</tr></table>";
	$bloc_obj .= "</td></tr>";
}
/******************************************************************/
								}
							}
}/* if isset(...) */
							$td .= " style='";
							if (0 != $nb_obj_case){
								if ( isset($vh_present) ){
									$td .= "background-image:url(./img/objetEtVehiculePresent.gif);";
								}else {
									$td .= "background-image:url(./img/objetPresent.gif);";
								}
								unset($vh_present);
							}/* if 'obj' */
							if ( @strcmp($monde[1],"".$j.",".$i."") == 0){
							  $td .= " background-color:rgb(0, 0, 0);";
							}else if (0 != $nb_nain_case){
								if ($nb_nain_case == $nbBraves){
									$td .= " background-color:rgb(85, 85, 255);";
								}else if ($nb_nain_case == $nbRampant){
									$td .= " background-color:rgb(85, 255, 85);";
								}else if ($nb_nain_case == $nbSad){
									$td .= " background-color:rgb(255, 85, 85);";
								}else if ($nb_nain_case == $nbN1Deci){
									$td .= " background-color:rgb(125, 115, 115);";
								}else {
									//gerer les cas entre... on peut encore plus detailler....
									if ( ($nbBraves >= $nbRampant) && ($nbBraves >= $nbSad) ){
										$td .= " background-color:rgb(165, 140, 255);";									  
									}else if ( ($nbSad >= $nbRampant) && ($nbSad >= $nbBraves) ){
										$td .= " background-color:rgb(255, 140, 240);";
									}else if ( ($nbRampant >= $nbBraves) && ($nbRampant >= $nbSad) ){
										$td .= " background-color:rgb(140, 255, 150);";
									}else if ( ($nbSad >= $nbBraves) && ($nbSad <= $nbRampant) ){
										$td .= " background-color:rgb(255, 200, 140);";
									}else if ( ($nbBraves >= $nbSad) && ($nbBraves <= $nbRampant) ){
										$td .= " background-color:rgb(140, 255, 255);";
									}else {
									  $td .= " background-color:rgb(84, 84, 84);";
									}
								}
							}/* if 'nain' */
							
							if ( (0 == $nb_obj_case) && (0 == $nb_nain_case) ){
  							$td .="'>";
							}else {
							  $td .= " cursor:pointer;' onclick=\"infosCase(this);\">";
							}
							
							if ( 0 != $nb_nain_case ){
							  $td .= $nb_nain_case;
							}
								
							$td .= "</td>\n";
							echo $td;
/************************ bloc case hidden ************************/
$bloc_case .= "\n<div id='data_".$j."_".$i."' style='overflow:auto; width:0px; height:0px; visibility:hidden; position:absolute; top:0px; left:0px;'>";
if ( (0 != $nb_obj_case) || (0 != $nb_nain_case) ){
  $bloc_case .= "\n<table border='0' cellpadding='0' cellspacing='0' width='100%'>";
  if ( $bloc_nain !="" ){
    $bloc_case .= $bloc_nain;  
  }
  if ( $bloc_obj !="" ){
    $bloc_case .= $bloc_obj;  
  }
  $bloc_case .= "</table>\n";
}
$bloc_case .= "</div>";
/******************************************************************/
						} /* for 'colonne' */
						echo "\t<td class='tabFiltreCoinE' height='100%'></td>\n";
						echo "</tr>\n";						
					} /* for 'ligne' */
				?>
				<tr>
					<td><img src="./img/interface/coin_SO.gif" /></td>
					<td class="tabFiltreBande" colspan="23" width="100%">&nbsp;</td>
					<td><img src="./img/interface/coin_SE.gif" /></td>
				</tr>
			</table>
		</div>
		

		<?php
		  //affichage ici du MEGA bloc hidden $bloc_case
		  echo "\n";
		  echo $bloc_case;
		  echo "\n";
		?>
		
		
		<div id="chargeDetection" class="divNouvelleDetect">
			<table cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
				<tr>
					<td><img src="./img/interface/coin_NO.gif" /></td>
					<td class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_NE.gif" /></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td align="center" style="vertical-align: middle;">Nouvelle D&eacute;tection:</td>
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
						<form action="detections.php" method="post">
						  <table>
						    <tr>
						      <td colspan="2">
							    <?php
									if (isset($_POST["nDetect"])) { 
										echo "<textarea name='nDetect' cols='55' rows='10' disabled='true'>".$_POST["nDetect"]."</textarea>";
									} else {
										echo "<textarea name='nDetect' cols='55' rows='10'></textarea>";
									}
								?>
							  </td>
						    </tr>
						    <tr>
						      <td align="left"><input name="sauvegarde" type="checkbox"/>Sauvegarder</td>
							  <?php if (isset($_POST["nDetect"])) { ?>
									<td align="right"><input type="submit" name="parse" value="Nouvelle D&eacute;tection"/></td>
							  <?php }else{ ?>
									<td align="right"><input type="submit" name="parse" value="Envoyer"/></td>
							  <?php } ?>
						    </tr>
						  </table>
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
			
<?php
if ( isset($_POST["nDetect"]) ){
?>
			<table cellspacing="0" cellpadding="0" style="border-collapse: collapse;">
				<tr>
					<td><img src="./img/interface/coin_NO.gif" /></td>
					<td class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_NE.gif" /></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td align="center" style="vertical-align: middle;">Liste des braves du monde:</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td>
						<?php
                            if ( !empty($braves_array) ) {
                                //affichage de la liste des braves et des sadiks du monde
                                $pattern='/\[.*\]/';
                                foreach($braves_array as $nain){
                                    echo htmlentities( preg_replace($pattern, "", $nain) , ENT_NOQUOTES, "UTF-8").", ";
                                }
                            }
						?>
					</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td><img src="./img/interface/coin_OE.gif" /></td>
					<td class="tabFiltreBande">&nbsp;</td>
					<td><img src="./img/interface/coin_EO.gif" /></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td align="center" style="vertical-align: middle;">Liste des sadiks du monde:</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td class="tabFiltreCoinO" height="100%"></td>
					<td>
						<?php
                            if ( !empty($sadiks_array) ) {
                                //affichage de la liste des braves et des sadiks du monde
                                $pattern='/\[.*\]/';
                                foreach($sadiks_array as $nain){
                                    echo htmlentities( preg_replace($pattern, "", $nain) , ENT_NOQUOTES, "UTF-8").", ";
                                }
                            }
						?>
					</td>
					<td class="tabFiltreCoinE" height="100%"></td>
				</tr>
				<tr>
					<td><img src="./img/interface/coin_SO.gif" /></td>
					<td class="tabFiltreBande" width="100%">&nbsp;</td>
					<td><img src="./img/interface/coin_SE.gif" /></td>
				</tr>
			</table>
<?php
} // end if ( isset($_POST["nDetect"]) ){
?>
		</div>
				
		<div id="filtres" class="divFiltre">
			<form>
				<table cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
					<tr>
						<td><img src="./img/interface/coin_NO.gif" /></td>
						<td class="tabFiltreBande" colspan="4" width="100%">&nbsp;</td>
						<td><img src="./img/interface/coin_NE.gif" /></td>
					</tr>
					<tr>
						<td class="tabFiltreCoinO" height="100%"></td>
						<td colspan="4" align="center">Filtres disponibles</td>
						<td class="tabFiltreCoinE" height="100%"></td>
					</tr>
					<tr>
						<td><img src="./img/interface/coin_OE.gif" /></td>
						<td class="tabFiltreBande" colspan="4" width="100%">&nbsp;</td>
						<td><img src="./img/interface/coin_EO.gif" /></td>
					</tr>
					<tr>
						<td class="tabFiltreCoinO" height="100%"></td>
						<td><input type="checkbox" name="fitres[]" value="checked">V&eacute;hicules</input></td>
						<td><input type="checkbox" name="fitres[]">Runes</input></td>
						<td><input type="checkbox" name="fitres[]">Bouffes</input></td>
						<td><input type="checkbox" name="fitres[]">D&eacute;tecteurs</input></td>
						<td class="tabFiltreCoinE" height="100%"></td>
					</tr>
					<tr>
						<td class="tabFiltreCoinO" height="100%"></td>
						<td colspan="4" align="center"><input type="submit" value="Filtrer" name="valideFiltre"/></td>
						<td class="tabFiltreCoinE" height="100%"></td>
					</tr>
					<tr>
						<td><img src="./img/interface/coin_SO.gif" /></td>
						<td class="tabFiltreBande" colspan="4" width="100%">&nbsp;</td>
						<td><img src="./img/interface/coin_SE.gif" /></td>
					</tr>
				</table>
			</form>
		</div>
					
		<div id="statsDetection" class="divStatsDetection">
		  <table border="0">
		    <tr>
		      <td align="center" style="padding-top:8px; padding-left:15px;">
		        <b id="case_info">Information Case</b> 
		      </td>
		    </tr>
		  </table>
		  <div style="border: 1px solid black; overflow: auto; position: absolute; top: 40px; left: 20px; width: 360px; height: 450px; background-color: white;" id="statsNains"></div>
		</div>
	</body>
</html>
