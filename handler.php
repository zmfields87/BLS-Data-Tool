<?php

$jobTitleData = preg_split( "[\r\n]", trim($_POST['jobtitles']));

$vert = $_POST['vertical'];

$tableTypes = $_POST['tabletype'];

$fileName = 'BLSdata.zip';



// Headers to create the ZIP file


  	  header("Content-disposition: attachment; filename=".$fileName);
      header("Content-Type: application/force-download");
      header("Content-Transfer-Encoding: application/zip;\n");
      header("Pragma: no-cache");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0, public");
      header("Expires: 0");



if (in_array("local", $tableTypes))
{
	$createLocal = "
	CREATE TABLE localTable (
	  id int(10) unsigned NOT NULL AUTO_INCREMENT,
	  AREA_TITLE VARCHAR(255),
	  PRIM_STATE varchar(50) DEFAULT NULL,
	  PRIM_STATE2 varchar(50) DEFAULT NULL,
	  PRIM_STATE3 varchar(50) DEFAULT NULL,
	  PRIM_STATE4 varchar(50) DEFAULT NULL,
	  AREA_TYPE int(11) DEFAULT NULL,
	  OCC_TITLE varchar(255) DEFAULT NULL,
	  TOT_EMP varchar(255) DEFAULT NULL,
	  JOBS_1000 varchar(255) DEFAULT NULL,
	  A_MEAN varchar(255) DEFAULT NULL,
	  A_PCT90 varchar(255) DEFAULT NULL,
	  PRIMARY KEY (id)
	) ENGINE=InnoDB AUTO_INCREMENT=0
	";
			
			
			
			
					$link = mysql_connect('localhost','root','root') or die(mysql_error());
					mysql_select_db('BLSdata', $link);
			
				
					mysql_query($createLocal, $link);
				
				 
					
				
				$sqlLocal = "
					INSERT INTO localTable
					(AREA_TITLE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					SELECT DISTINCT AREA_TITLE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM BLSdata WHERE NAICS_TITLE = 'Cross-industry' AND `GROUP` = 'detail' AND AREA_TYPE = '4' AND OCC_TITLE IN ('";
					 foreach ($jobTitleData as $job)
					 {
						 $sqlLocal .= $job . "','";
					 }
					 
					 $sqlLocal .= "')";
					 
					 
					 $grabAreaData = "SELECT id,AREA_TITLE FROM localTable";
					 
					 
 					$link = mysql_connect('localhost','root','root') or die(mysql_error());
 					$db_selected2 = mysql_select_db('BLSdata', $link);
			
				
 					mysql_query($sqlLocal, $link);
					
							
							
							$link2 = mysql_connect('localhost','root','root') or die(mysql_error());
							mysql_select_db('BLSdata', $link2);
	
		
							$grabData = mysql_query($grabAreaData, $link2);							
							
							
					        while($row = mysql_fetch_array($grabData)) {
								
								
								
								
								$areaId = $row['id'];
								
								$area = explode(', ', $row['AREA_TITLE']);
								
								$areaTitle = $area[0];
								
								$states = explode('-', $area[1]);
								
								$statesCount = count($states);
								$countStates =1;
								

							$primState1 = ((isset($states[0])) ? $states[0] : NULL);
							$primState2 = ((isset($states[1])) ? $states[1] : NULL);
							$primState3 = ((isset($states[2])) ? $states[2] : NULL);
							$primState4 = ((isset($states[3])) ? $states[3] : NULL);



			
								
								
								
								
		   					 	$stateInsert = "UPDATE localTable set
									 `AREA_TITLE` = '$areaTitle',
							 `PRIM_STATE` = '$primState1',
							 `PRIM_STATE2` = '$primState2',
							 `PRIM_STATE3` = '$primState3',
							 `PRIM_STATE4` = '$primState4'
									WHERE id='$areaId'
								
								";
								
		
								$insertData = mysql_query($stateInsert, $link2);							
	
					        }
							
							
							
							
							//CLEAN UP PRIM_STATE4
							
							
							
							
							
							
							$sqlCreatePrimState4 = "CREATE TABLE primState4 (
					  	  		AREA_TITLE VARCHAR(255),
					  	  		PRIM_STATE varchar(50) DEFAULT NULL,
					  	  		PRIM_STATE2 varchar(50) DEFAULT NULL,
					  	  		PRIM_STATE3 varchar(50) DEFAULT NULL,
					  	  		PRIM_STATE4 varchar(50) DEFAULT NULL,
					  	  		AREA_TYPE int(11) DEFAULT NULL,
					  	  		OCC_TITLE varchar(255) DEFAULT NULL,
					  	  		TOT_EMP varchar(255) DEFAULT NULL,
					  	  		JOBS_1000 varchar(255) DEFAULT NULL,
					  	  		A_MEAN varchar(255) DEFAULT NULL,
					  	  		A_PCT90 varchar(255) DEFAULT NULL)";
								
								
								$link2 = mysql_connect('localhost','root','root') or die(mysql_error());
								mysql_select_db('BLSdata', $link2);
	
		
								$createPrim4 = mysql_query($sqlCreatePrimState4, $link2);
								
								$sqlInsertPrimState4 = "INSERT INTO primState4 (AREA_TITLE, PRIM_STATE, PRIM_STATE2, PRIM_STATE3, PRIM_STATE4, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
									SELECT DISTINCT AREA_TITLE, PRIM_STATE, PRIM_STATE2, PRIM_STATE3, PRIM_STATE4, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM localTable WHERE PRIM_STATE4 <> ''";
								$sqlInsertPrim4 = mysql_query($sqlInsertPrimState4, $link2);
								
								$sqlDeleteOldPrim4 = "DELETE FROM localTable WHERE PRIM_STATE4 <> ''";
								
								$sqlExecDeletePrim4 = mysql_query($sqlDeleteOldPrim4, $link2);
								
								$sqlCreateFinalPrim4 = "CREATE TABLE finalPrim4 (
					  	  		AREA_TITLE VARCHAR(255),
					  	  		PRIM_STATE varchar(50) DEFAULT NULL,
					  	  		AREA_TYPE int(11) DEFAULT NULL,
					  	  		OCC_TITLE varchar(255) DEFAULT NULL,
					  	  		TOT_EMP varchar(255) DEFAULT NULL,
					  	  		JOBS_1000 varchar(255) DEFAULT NULL,
					  	  		A_MEAN varchar(255) DEFAULT NULL,
					  	  		A_PCT90 varchar(255) DEFAULT NULL)";
								
								
								$createFinalPrim4 = mysql_query($sqlCreateFinalPrim4, $link2);
								
								$sqlInsertCleanPrim4 = "INSERT INTO finalPrim4 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
											 SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90
													FROM primState4";
													
										$updateCleanPrim4 = mysql_query($sqlInsertCleanPrim4, $link2);			
							
								$sqlInsertCleanPrim42 = "INSERT INTO finalPrim4 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
											SELECT AREA_TITLE, PRIM_STATE2, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState4";
							
										$updateCleanPrim42 = mysql_query($sqlInsertCleanPrim42, $link2);
										
								$sqlInsertCleanPrim43 = "INSERT INTO finalPrim4 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
											SELECT AREA_TITLE, PRIM_STATE3, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState4";
							
										$updateCleanPrim43 = mysql_query($sqlInsertCleanPrim43, $link2);
								
								$sqlInsertCleanPrim44 = "INSERT INTO finalPrim4 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
											SELECT AREA_TITLE, PRIM_STATE4, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState4";
							
										$updateCleanPrim44 = mysql_query($sqlInsertCleanPrim44, $link2);
										
										$sqlReturnPrim4 = "INSERT INTO localTable (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
											SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM finalPrim4";
										
										$sqlReturnPrim4Local = mysql_query($sqlReturnPrim4, $link2);
										
										$sqlDeletePrim4 = "DROP TABLE IF EXISTS finalPrim4, primState4";
										
										$sqlFinishPrim4 = mysql_query($sqlDeletePrim4, $link2);
										
										
										
										
										
										
										// CLEAN UP PRIM_STATE3
										
										
										
										
										
										
										
										$sqlCreatePrimState3 = "CREATE TABLE primState3 (
								  	  		AREA_TITLE VARCHAR(255),
								  	  		PRIM_STATE varchar(50) DEFAULT NULL,
								  	  		PRIM_STATE2 varchar(50) DEFAULT NULL,
								  	  		PRIM_STATE3 varchar(50) DEFAULT NULL,
								  	  		AREA_TYPE int(11) DEFAULT NULL,
								  	  		OCC_TITLE varchar(255) DEFAULT NULL,
								  	  		TOT_EMP varchar(255) DEFAULT NULL,
								  	  		JOBS_1000 varchar(255) DEFAULT NULL,
								  	  		A_MEAN varchar(255) DEFAULT NULL,
								  	  		A_PCT90 varchar(255) DEFAULT NULL)";
								
								
											$link2 = mysql_connect('localhost','root','root') or die(mysql_error());
											mysql_select_db('BLSdata', $link2);
	
		
											$createPrim3 = mysql_query($sqlCreatePrimState3, $link2);
								
											$sqlInsertPrimState3 = "INSERT INTO primState3 (AREA_TITLE, PRIM_STATE, PRIM_STATE2, PRIM_STATE3, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
												SELECT DISTINCT AREA_TITLE, PRIM_STATE, PRIM_STATE2, PRIM_STATE3, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM localTable WHERE PRIM_STATE3 <> ''";
											$sqlInsertPrim3 = mysql_query($sqlInsertPrimState3, $link2);
								
											$sqlDeleteOldPrim3 = "DELETE FROM localTable WHERE PRIM_STATE3 <> ''";
								
											$sqlExecDeletePrim3 = mysql_query($sqlDeleteOldPrim3, $link2);
								
											$sqlCreateFinalPrim3 = "CREATE TABLE finalPrim3 (
								  	  		AREA_TITLE VARCHAR(255),
								  	  		PRIM_STATE varchar(50) DEFAULT NULL,
								  	  		AREA_TYPE int(11) DEFAULT NULL,
								  	  		OCC_TITLE varchar(255) DEFAULT NULL,
								  	  		TOT_EMP varchar(255) DEFAULT NULL,
								  	  		JOBS_1000 varchar(255) DEFAULT NULL,
								  	  		A_MEAN varchar(255) DEFAULT NULL,
								  	  		A_PCT90 varchar(255) DEFAULT NULL)";
								
								
											$createFinalPrim3 = mysql_query($sqlCreateFinalPrim3, $link2);
								
											$sqlInsertCleanPrim3 = "INSERT INTO finalPrim3 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
														 SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90
																FROM primState3";
													
													$updateCleanPrim3 = mysql_query($sqlInsertCleanPrim3, $link2);			
							
											$sqlInsertCleanPrim32 = "INSERT INTO finalPrim3 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
														SELECT AREA_TITLE, PRIM_STATE2, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState3";
							
													$updateCleanPrim32 = mysql_query($sqlInsertCleanPrim32, $link2);
										
											$sqlInsertCleanPrim33 = "INSERT INTO finalPrim3 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
														SELECT AREA_TITLE, PRIM_STATE3, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState3";
							
													$updateCleanPrim33 = mysql_query($sqlInsertCleanPrim33, $link2);
								
										
													$sqlReturnPrim3 = "INSERT INTO localTable (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
														SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM finalPrim3";
										
													$sqlReturnPrim3Local = mysql_query($sqlReturnPrim3, $link2);
										
													$sqlDeletePrim3 = "DROP TABLE IF EXISTS finalPrim3, primState3";
										
													$sqlFinishPrim3 = mysql_query($sqlDeletePrim3, $link2);
													
													
													
													
													
													//CLEAN UP PRIM_STATE2
													
													
													
													
													
			 	$sqlCreatePrimState2 = "CREATE TABLE primState2 (
										AREA_TITLE VARCHAR(255),
										PRIM_STATE varchar(50) DEFAULT NULL,
										PRIM_STATE2 varchar(50) DEFAULT NULL,
										AREA_TYPE int(11) DEFAULT NULL,
										OCC_TITLE varchar(255) DEFAULT NULL,
										TOT_EMP varchar(255) DEFAULT NULL,
										JOBS_1000 varchar(255) DEFAULT NULL,
										A_MEAN varchar(255) DEFAULT NULL,
										A_PCT90 varchar(255) DEFAULT NULL)";
								
								
				$link2 = mysql_connect('localhost','root','root') or die(mysql_error());
				mysql_select_db('BLSdata', $link2);
	
		
				$createPrim2 = mysql_query($sqlCreatePrimState2, $link2);
								
				$sqlInsertPrimState2 = "INSERT INTO primState2 (AREA_TITLE, PRIM_STATE, PRIM_STATE2, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					SELECT DISTINCT AREA_TITLE, PRIM_STATE, PRIM_STATE2, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM localTable WHERE PRIM_STATE2 <> ''";
				$sqlInsertPrim2 = mysql_query($sqlInsertPrimState2, $link2);
								
				$sqlDeleteOldPrim2 = "DELETE FROM localTable WHERE PRIM_STATE2 <> ''";
								
				$sqlExecDeletePrim2 = mysql_query($sqlDeleteOldPrim2, $link2);
								
				$sqlCreateFinalPrim2 = "CREATE TABLE finalPrim2 (
								AREA_TITLE VARCHAR(255),
								PRIM_STATE varchar(50) DEFAULT NULL,
								AREA_TYPE int(11) DEFAULT NULL,
								OCC_TITLE varchar(255) DEFAULT NULL,
								TOT_EMP varchar(255) DEFAULT NULL,
								JOBS_1000 varchar(255) DEFAULT NULL,
								A_MEAN varchar(255) DEFAULT NULL,
								A_PCT90 varchar(255) DEFAULT NULL)";
								
								
				$createFinalPrim2 = mysql_query($sqlCreateFinalPrim2, $link2);
								
				$sqlInsertCleanPrim2 = "INSERT INTO finalPrim2 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
							 SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90
									FROM primState2";
													
				$updateCleanPrim2 = mysql_query($sqlInsertCleanPrim2, $link2);			
							
				$sqlInsertCleanPrim22 = "INSERT INTO finalPrim2 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
																	SELECT AREA_TITLE, PRIM_STATE2, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState2";
							
				$updateCleanPrim22 = mysql_query($sqlInsertCleanPrim22, $link2);
										
														
				$sqlReturnPrim2 = "INSERT INTO localTable (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM finalPrim2";
										
				$sqlReturnPrim2Local = mysql_query($sqlReturnPrim2, $link2);
										
				$sqlDeletePrim2 = "DROP TABLE IF EXISTS finalPrim2, primState2";
										
				$sqlFinishPrim2 = mysql_query($sqlDeletePrim2, $link2);
				
				
				
				//REMOVE EXTRANEOUS PRIM_STATE TABLES
				
				$sqlDropPrims = "ALTER TABLE localTable DROP PRIM_STATE2, DROP PRIM_STATE3, DROP PRIM_STATE4";
				
				$sqlRemovePrims = mysql_query($sqlDropPrims, $link2);
													
													
				// ADD VERTICAL COLUMN
													
				$sqlAddVertCol = "ALTER TABLE localTable ADD COLUMN `VERT` VARCHAR(50) FIRST";
				
				$sqlVertCol = mysql_query($sqlAddVertCol, $link2);
				
				// ADD AREA COLUMN
				
				$sqlAddAreaCol = "ALTER TABLE localTable ADD COLUMN `AREA` INT(100) AFTER id";
				
				$sqlAddArea = mysql_query($sqlAddAreaCol, $link2);
				
				// ADD OCC COLUMN
				
				$sqlAddOccCol = "ALTER TABLE localTable ADD COLUMN `OCC` INT(100) AFTER PRIM_STATE";
				
				$sqlAddOcc = mysql_query($sqlAddOccCol, $link2);
				
				// ADD STATENUM COLUMN
				
				$sqlAddStateNumCol = "ALTER TABLE localTable ADD COLUMN `StateNum` INT(100) AFTER PRIM_STATE";
				
				$sqlAddStateNum = mysql_query($sqlAddStateNumCol, $link2);
																
																
													
																				
													
													
													
													
													
													
							
							
							
							
							$createCSV = "
								SELECT 'AREA_TITLE', 'PRIM_STATE', 'AREA_TYPE', 'OCC_TITLE', 'TOT_EMP', 'JOBS_1000', 'A_MEAN', 'A_PCT90'
								UNION ALL
								SELECT  DISTINCT AREA_TITLE,
								PRIM_STATE,
								AREA_TYPE, 
								OCC_TITLE, 
								TOT_EMP, 
								JOBS_1000, 
								A_MEAN, 
								A_PCT90 FROM localTable INTO OUTFILE '/tmp/localTable.csv' 
								FIELDS TERMINATED BY ','
								OPTIONALLY ENCLOSED BY '\"'";

							$createquery = mysql_query($createCSV, $link2);
				
						
						
						
							
							
}

if (in_array("msa", $tableTypes))
{
	$createMSA = 
			   "CREATE TABLE msaTable (
				id int(10) unsigned NOT NULL AUTO_INCREMENT,
				AREA_TITLE varchar(255),
		  	 	PRIM_STATE varchar(50) DEFAULT NULL,
		  	 	PRIM_STATE2 varchar(50) DEFAULT NULL,
		  	 	PRIM_STATE3 varchar(50) DEFAULT NULL,
		  	 	PRIM_STATE4 varchar(50) DEFAULT NULL,
			 	AREA_TYPE int(11) DEFAULT NULL,
			 	OCC_TITLE varchar(255) DEFAULT NULL,
				TOT_EMP varchar(255) DEFAULT NULL,
				JOBS_1000 varchar(255) DEFAULT NULL,
				A_MEAN varchar(255) DEFAULT NULL,
				A_PCT90 varchar(255) DEFAULT NULL,
				PRIMARY KEY (id)) AUTO_INCREMENT=0";
			
			
			
			
					$link = mysql_connect('localhost','root','root') or die(mysql_error());
					mysql_select_db('BLSdata', $link);
			
				
					mysql_query($createMSA, $link);
				
				 
					
				
				$sqlMSA = "
					INSERT INTO msaTable
					(AREA_TITLE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					SELECT DISTINCT AREA_TITLE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM BLSdata WHERE NAICS_TITLE = 'Cross-industry' AND `GROUP` = 'detail' AND AREA_TYPE = '5' AND OCC_TITLE IN ('";
					 foreach ($jobTitleData as $job)
					 {
						 $sqlMSA .= $job . "','";
					 }
					 
					 $sqlMSA .= "')";
					 
					 
					 
					 
					 
					 
 					$link = mysql_connect('localhost','root','root') or die(mysql_error());
 					$db_selected2 = mysql_select_db('BLSdata', $link);
			
				
 					mysql_query($sqlMSA, $link);
					
						
							
							$link2 = mysql_connect('localhost','root','root') or die(mysql_error());
							mysql_select_db('BLSdata', $link2);
							
							$sqlRemoveMetro = "UPDATE msaTable SET AREA_TITLE = REPLACE(AREA_TITLE, ' Metropolitan Division', '')";
							
							$sqlRemoveMet = mysql_query($sqlRemoveMetro, $link2);
							
							$sqlRemoveNecta = "UPDATE msaTable SET AREA_TITLE = REPLACE(AREA_TITLE, ' NECTA Division', '')";
							
							$sqlRemoveNec = mysql_query($sqlRemoveNecta, $link2);
							
							$grabAreaData2 = "SELECT id,AREA_TITLE FROM msaTable";
	
		
							$grabData2 = mysql_query($grabAreaData2, $link2);							
							
							
					        while($row = mysql_fetch_array($grabData2)) {
								
								
								
								
								$areaId = $row['id'];
								
								$area = explode(', ', $row['AREA_TITLE']);
								
								$areaTitle = $area[0];
								
								$states = explode('-', $area[1]);
								
								$statesCount = count($states);
								$countStates =1;
								

							$primState1 = ((isset($states[0])) ? $states[0] : NULL);
							$primState2 = ((isset($states[1])) ? $states[1] : NULL);
							$primState3 = ((isset($states[2])) ? $states[2] : NULL);
							$primState4 = ((isset($states[3])) ? $states[3] : NULL);



			
								
								
								
								
		   					 	$stateInsert = "UPDATE msaTable set
									 `AREA_TITLE` = '$areaTitle',
							 `PRIM_STATE` = '$primState1',
							 `PRIM_STATE2` = '$primState2',
							 `PRIM_STATE3` = '$primState3',
							 `PRIM_STATE4` = '$primState4'
									WHERE id='$areaId'
								
								";
								
		
								$insertData = mysql_query($stateInsert, $link2);							
	
					        }
							
							
							
							
							
							
							
								//CLEAN UP PRIM_STATE4






$sqlCreatePrimState4 = "CREATE TABLE primState4 (
					AREA_TITLE VARCHAR(255),
					PRIM_STATE varchar(50) DEFAULT NULL,
					PRIM_STATE2 varchar(50) DEFAULT NULL,
					PRIM_STATE3 varchar(50) DEFAULT NULL,
					PRIM_STATE4 varchar(50) DEFAULT NULL,
					AREA_TYPE int(11) DEFAULT NULL,
					OCC_TITLE varchar(255) DEFAULT NULL,
					TOT_EMP varchar(255) DEFAULT NULL,
					JOBS_1000 varchar(255) DEFAULT NULL,
					A_MEAN varchar(255) DEFAULT NULL,
					A_PCT90 varchar(255) DEFAULT NULL)";


	$link2 = mysql_connect('localhost','root','root') or die(mysql_error());
	mysql_select_db('BLSdata', $link2);


	$createPrim4 = mysql_query($sqlCreatePrimState4, $link2);

	$sqlInsertPrimState4 = "INSERT INTO primState4 (AREA_TITLE, PRIM_STATE, PRIM_STATE2, PRIM_STATE3, PRIM_STATE4, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
		SELECT DISTINCT AREA_TITLE, PRIM_STATE, PRIM_STATE2, PRIM_STATE3, PRIM_STATE4, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM msaTable WHERE PRIM_STATE4 <> ''";
	$sqlInsertPrim4 = mysql_query($sqlInsertPrimState4, $link2);

	$sqlDeleteOldPrim4 = "DELETE FROM msaTable WHERE PRIM_STATE4 <> ''";

	$sqlExecDeletePrim4 = mysql_query($sqlDeleteOldPrim4, $link2);

	$sqlCreateFinalPrim4 = "CREATE TABLE finalPrim4 (
					AREA_TITLE VARCHAR(255),
					PRIM_STATE varchar(50) DEFAULT NULL,
					AREA_TYPE int(11) DEFAULT NULL,
					OCC_TITLE varchar(255) DEFAULT NULL,
					TOT_EMP varchar(255) DEFAULT NULL,
					JOBS_1000 varchar(255) DEFAULT NULL,
					A_MEAN varchar(255) DEFAULT NULL,
					A_PCT90 varchar(255) DEFAULT NULL)";


	$createFinalPrim4 = mysql_query($sqlCreateFinalPrim4, $link2);

	$sqlInsertCleanPrim4 = "INSERT INTO finalPrim4 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
				 SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90
						FROM primState4";
					
			$updateCleanPrim4 = mysql_query($sqlInsertCleanPrim4, $link2);			

	$sqlInsertCleanPrim42 = "INSERT INTO finalPrim4 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
				SELECT AREA_TITLE, PRIM_STATE2, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState4";

			$updateCleanPrim42 = mysql_query($sqlInsertCleanPrim42, $link2);
		
	$sqlInsertCleanPrim43 = "INSERT INTO finalPrim4 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
				SELECT AREA_TITLE, PRIM_STATE3, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState4";

			$updateCleanPrim43 = mysql_query($sqlInsertCleanPrim43, $link2);

	$sqlInsertCleanPrim44 = "INSERT INTO finalPrim4 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
				SELECT AREA_TITLE, PRIM_STATE4, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState4";

			$updateCleanPrim44 = mysql_query($sqlInsertCleanPrim44, $link2);
		
			$sqlReturnPrim4 = "INSERT INTO msaTable (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
				SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM finalPrim4";
		
			$sqlReturnPrim4Local = mysql_query($sqlReturnPrim4, $link2);
		
			$sqlDeletePrim4 = "DROP TABLE IF EXISTS finalPrim4, primState4";
		
			$sqlFinishPrim4 = mysql_query($sqlDeletePrim4, $link2);
		
		
			
		
		
		
											// CLEAN UP PRIM_STATE3
		
		
		
		
		
		
		
	$sqlCreatePrimState3 = "CREATE TABLE primState3 (
						AREA_TITLE VARCHAR(255),
						PRIM_STATE varchar(50) DEFAULT NULL,
						PRIM_STATE2 varchar(50) DEFAULT NULL,
						PRIM_STATE3 varchar(50) DEFAULT NULL,
						AREA_TYPE int(11) DEFAULT NULL,
						OCC_TITLE varchar(255) DEFAULT NULL,
						TOT_EMP varchar(255) DEFAULT NULL,
						JOBS_1000 varchar(255) DEFAULT NULL,
						A_MEAN varchar(255) DEFAULT NULL,
						A_PCT90 varchar(255) DEFAULT NULL)";


		$link2 = mysql_connect('localhost','root','root') or die(mysql_error());
		mysql_select_db('BLSdata', $link2);


		$createPrim3 = mysql_query($sqlCreatePrimState3, $link2);

		$sqlInsertPrimState3 = "INSERT INTO primState3 (AREA_TITLE, PRIM_STATE, PRIM_STATE2, PRIM_STATE3, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
			SELECT DISTINCT AREA_TITLE, PRIM_STATE, PRIM_STATE2, PRIM_STATE3, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM msaTable WHERE PRIM_STATE3 <> ''";
		$sqlInsertPrim3 = mysql_query($sqlInsertPrimState3, $link2);

		$sqlDeleteOldPrim3 = "DELETE FROM msaTable WHERE PRIM_STATE3 <> ''";

		$sqlExecDeletePrim3 = mysql_query($sqlDeleteOldPrim3, $link2);

		$sqlCreateFinalPrim3 = "CREATE TABLE finalPrim3 (
						AREA_TITLE VARCHAR(255),
						PRIM_STATE varchar(50) DEFAULT NULL,
						AREA_TYPE int(11) DEFAULT NULL,
						OCC_TITLE varchar(255) DEFAULT NULL,
						TOT_EMP varchar(255) DEFAULT NULL,
						JOBS_1000 varchar(255) DEFAULT NULL,
						A_MEAN varchar(255) DEFAULT NULL,
						A_PCT90 varchar(255) DEFAULT NULL)";


		$createFinalPrim3 = mysql_query($sqlCreateFinalPrim3, $link2);

		$sqlInsertCleanPrim3 = "INSERT INTO finalPrim3 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					 SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90
							FROM primState3";
					
				$updateCleanPrim3 = mysql_query($sqlInsertCleanPrim3, $link2);			

		$sqlInsertCleanPrim32 = "INSERT INTO finalPrim3 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					SELECT AREA_TITLE, PRIM_STATE2, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState3";

				$updateCleanPrim32 = mysql_query($sqlInsertCleanPrim32, $link2);
		
		$sqlInsertCleanPrim33 = "INSERT INTO finalPrim3 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					SELECT AREA_TITLE, PRIM_STATE3, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState3";

				$updateCleanPrim33 = mysql_query($sqlInsertCleanPrim33, $link2);

		
				$sqlReturnPrim3 = "INSERT INTO msaTable (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM finalPrim3";
		
				$sqlReturnPrim3Local = mysql_query($sqlReturnPrim3, $link2);
		
				$sqlDeletePrim3 = "DROP TABLE IF EXISTS finalPrim3, primState3";
		
				$sqlFinishPrim3 = mysql_query($sqlDeletePrim3, $link2);
					
					
					
					
					
		//CLEAN UP PRIM_STATE2
					
				
					
					
					
	$sqlCreatePrimState2 = "CREATE TABLE primState2 (
						AREA_TITLE VARCHAR(255),
						PRIM_STATE varchar(50) DEFAULT NULL,
						PRIM_STATE2 varchar(50) DEFAULT NULL,
						AREA_TYPE int(11) DEFAULT NULL,
						OCC_TITLE varchar(255) DEFAULT NULL,
						TOT_EMP varchar(255) DEFAULT NULL,
						JOBS_1000 varchar(255) DEFAULT NULL,
						A_MEAN varchar(255) DEFAULT NULL,
						A_PCT90 varchar(255) DEFAULT NULL)";


		$link2 = mysql_connect('localhost','root','root') or die(mysql_error());
		mysql_select_db('BLSdata', $link2);


		$createPrim2 = mysql_query($sqlCreatePrimState2, $link2);

		$sqlInsertPrimState2 = "INSERT INTO primState2 (AREA_TITLE, PRIM_STATE, PRIM_STATE2, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
			SELECT DISTINCT AREA_TITLE, PRIM_STATE, PRIM_STATE2, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM msaTable WHERE PRIM_STATE2 <> ''";
		$sqlInsertPrim2 = mysql_query($sqlInsertPrimState2, $link2);

		$sqlDeleteOldPrim2 = "DELETE FROM msaTable WHERE PRIM_STATE2 <> ''";

		$sqlExecDeletePrim2 = mysql_query($sqlDeleteOldPrim2, $link2);

		$sqlCreateFinalPrim2 = "CREATE TABLE finalPrim2 (
						AREA_TITLE VARCHAR(255),
						PRIM_STATE varchar(50) DEFAULT NULL,
						AREA_TYPE int(11) DEFAULT NULL,
						OCC_TITLE varchar(255) DEFAULT NULL,
						TOT_EMP varchar(255) DEFAULT NULL,
						JOBS_1000 varchar(255) DEFAULT NULL,
						A_MEAN varchar(255) DEFAULT NULL,
						A_PCT90 varchar(255) DEFAULT NULL)";


		$createFinalPrim2 = mysql_query($sqlCreateFinalPrim2, $link2);

		$sqlInsertCleanPrim2 = "INSERT INTO finalPrim2 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					 SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90
							FROM primState2";
					
				$updateCleanPrim2 = mysql_query($sqlInsertCleanPrim2, $link2);			

		$sqlInsertCleanPrim22 = "INSERT INTO finalPrim2 (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
								SELECT AREA_TITLE, PRIM_STATE2, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM primState2";

		$updateCleanPrim22 = mysql_query($sqlInsertCleanPrim22, $link2);
		
						
		$sqlReturnPrim2 = "INSERT INTO msaTable (AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
			SELECT AREA_TITLE, PRIM_STATE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM finalPrim2";
		
		$sqlReturnPrim2Local = mysql_query($sqlReturnPrim2, $link2);
		
		$sqlDeletePrim2 = "DROP TABLE IF EXISTS finalPrim2, primState2";
		
		$sqlFinishPrim2 = mysql_query($sqlDeletePrim2, $link2);
								
								
								
		//REMOVE EXTRANEOUS PRIM_STATE TABLES
								
		$sqlDropPrims = "ALTER TABLE msaTable DROP PRIM_STATE2, DROP PRIM_STATE3, DROP PRIM_STATE4";
								
		$sqlRemovePrims = mysql_query($sqlDropPrims, $link2);
					
					
		// ADD VERTICAL COLUMN
					
		$sqlAddVertCol = "ALTER TABLE msaTable ADD COLUMN `VERT` VARCHAR(50) FIRST";
								
		$sqlVertCol = mysql_query($sqlAddVertCol, $link2);
								
		// ADD AREA COLUMN
								
		$sqlAddAreaCol = "ALTER TABLE msaTable ADD COLUMN `AREA` INT(100) AFTER id";
								
		$sqlAddArea = mysql_query($sqlAddAreaCol, $link2);
								
		// ADD OCC COLUMN
								
		$sqlAddOccCol = "ALTER TABLE msaTable ADD COLUMN `OCC` INT(100) AFTER PRIM_STATE";
								
		$sqlAddOcc = mysql_query($sqlAddOccCol, $link2);
								
		// ADD STATENUM COLUMN
								
		$sqlAddStateNumCol = "ALTER TABLE msaTable ADD COLUMN `StateNum` INT(100) AFTER PRIM_STATE";
								
		$sqlAddStateNum = mysql_query($sqlAddStateNumCol, $link2);
		
		// CLEAN OUT COMMAS AND SYMBOLS
		
		$sqlCommaTOT = "UPDATE msaTable SET TOT_EMP = REPLACE(TOT_EMP, ',', '')";
		
		$sqlFireTOT = mysql_query($sqlCommaTOT, $link2);
		
		$sqlCommaMEAN = "UPDATE msaTable SET A_MEAN = REPLACE(A_MEAN, ',', '')";
		
		$sqlFireMEAN = mysql_query($sqlCommaMEAN, $link2);
		
		$sqlComma90 = "UPDATE msaTable SET A_PCT90 = REPLACE(A_PCT90, ',', '')";
		
		$sqlFire90 = mysql_query($sqlComma90, $link2);
		
		$sql2StarTOT = "UPDATE msaTable SET TOT_EMP = REPLACE(TOT_EMP, '**', '')";
		
		$sqlFire2StarTOT = mysql_query($sql2StarTOT, $link2);
		
		$sql1StarMEAN = "UPDATE msaTable SET TOT_EMP = REPLACE(TOT_EMP, '*', '')";
		
		$sqlFire1StarMEAN = mysql_query($sql1StarMEAN, $link2);
		
		$sql2StarMEAN = "UPDATE msaTable SET TOT_EMP = REPLACE(TOT_EMP, '**', '')";
		
		$sqlFire2StarMEAN = mysql_query($sql2StarMEAN, $link2);
		
		$sqlHash90 = "UPDATE msaTable SET A_PCT90 = REPLACE(A_PCT90, '#', '')";
		
		$sqlFireHash90 = mysql_query($sqlHash90, $link2);
							
							
		// CREATE TEMP MSA TABLE
		
		$sqlCreateTempMSA = "CREATE TABLE msaTemp (
		  	 	PRIM_STATE varchar(50) DEFAULT NULL,
			 	OCC_TITLE varchar(255) DEFAULT NULL,
				TOT_EMP varchar(255) DEFAULT NULL,
				JOBS_1000 varchar(255) DEFAULT NULL,
				A_MEAN varchar(255) DEFAULT NULL,
				A_PCT90 varchar(255) DEFAULT NULL)";
				
				$createTempMSATable = mysql_query($sqlCreateTempMSA, $link2);					
							
					
		$sqlAvg = "SELECT DISTINCT PRIM_STATE, OCC_TITLE FROM msaTable";
		$activityList = mysql_query($sqlAvg, $link2) or die(mysql_error()); 
		$row_activityList = mysql_fetch_assoc($activityList); 
		
		do
			
			{
				
				$sqlAverage = "INSERT INTO msaTemp (PRIM_STATE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90) 
							SELECT PRIM_STATE, OCC_TITLE, SUM(TOT_EMP), AVG(JOBS_1000), AVG(A_MEAN), AVG(A_PCT90)
							FROM msaTable WHERE PRIM_STATE = '" . $row_activityList['PRIM_STATE'] . "' AND OCC_TITLE = '" . $row_activityList['OCC_TITLE'] . "';";

$sqlFireAverage = mysql_query($sqlAverage, $link2) or die(mysql_error()); 




				
			} while ($row_activityList = mysql_fetch_assoc($activityList));
			
			
			
			// ADD COMMAS BACK AND REPLACE ZEROES WITH STARS
			
			$sqlReturnCommasTOT = "UPDATE msaTemp SET TOT_EMP = (FORMAT(TOT_EMP, 0))";
			
			$sqlFireReturnCommaTOT = mysql_query($sqlReturnCommasTOT, $link2);
			
			$sqlReplaceZeroesTOT = "UPDATE msaTemp SET TOT_EMP = '*' WHERE TOT_EMP = '0'";
			
			$sqlFireReplaceZeroesTOT = mysql_query($sqlReplaceZeroesTOT, $link2);
			
			$sqlShortenJOBS = "UPDATE msaTemp SET JOBS_1000 = (FORMAT(JOBS_1000, 2))";
			
			$sqlFireShortenJOBS = mysql_query($sqlShortenJOBS, $link2);
			
			$sqlReplaceZeroesJOBS = "UPDATE msaTemp SET JOBS_1000 = '*' WHERE JOBS_1000 = '0'";
			
			$sqlFireReplaceZeroesJOBS = mysql_query($sqlReplaceZeroesJOBS, $link2);
			
			$sqlReturnCommasMEAN = "UPDATE msaTemp SET A_MEAN = (FORMAT(A_MEAN, 0))";
			
			$sqlFireReturnCommaMEAN = mysql_query($sqlReturnCommasMEAN, $link2);
			
			$sqlReturnCommas90 = "UPDATE msaTemp SET A_PCT90 = (FORMAT(A_PCT90, 0))";
			
			$sqlFireReturnComma90 = mysql_query($sqlReturnCommas90, $link2);
			
			$sqlReplaceZeroesMEAN = "UPDATE msaTemp SET A_MEAN = '*' WHERE A_MEAN = '0'";
			
			$sqlFireReplaceZeroesMEAN = mysql_query($sqlReplaceZeroesMEAN, $link2);
			
			$sqlReplaceZeroes90 = "UPDATE msaTemp SET A_PCT90 = '*' WHERE A_PCT90 = '0'";
			
			$sqlFireReplaceZeroes90 = mysql_query($sqlReplaceZeroes90, $link2);
			
			$sqlReplaceBlanksTOT = "UPDATE msaTemp SET TOT_EMP = '*' WHERE TOT_EMP = ''";
			
			$sqlFireReplaceBlanks = mysql_query($sqlReplaceBlanksTOT, $link2);
			
			// DELETE msaTable AND RENAME msaTemp TO msaTable
			
			$sqlDeleteMSA = "DROP TABLE msaTable";
			
			$sqlFireDelete = mysql_query($sqlDeleteMSA, $link2);
			
			$sqlRenameMSA = "RENAME TABLE msaTemp TO msaTable";
			
			$sqlFireRename = mysql_query($sqlRenameMSA, $link2);
			
							
					
							
							
							$createCSV1 = "SELECT 'PRIM_STATE', 'OCC_TITLE', 'TOT_EMP', 'JOBS_1000', 'A_MEAN', 'A_PCT90'
								UNION ALL
								SELECT DISTINCT PRIM_STATE,
								OCC_TITLE, 
								TOT_EMP, 
								JOBS_1000, 
								A_MEAN, 
								A_PCT90 FROM msaTable INTO OUTFILE '/tmp/msaTable.csv' 
								FIELDS TERMINATED BY ','
								OPTIONALLY ENCLOSED BY '\"'";

							$createquery = mysql_query($createCSV1, $link2);
							
}

if (in_array("nmsa", $tableTypes))
{
	$createNMSA = "CREATE TABLE nmsaTable
		(AREA_TITLE varchar(255),
  	 	PRIM_STATE varchar(50) DEFAULT NULL,
	 	AREA_TYPE int(11) DEFAULT NULL,
	 	OCC_TITLE varchar(255) DEFAULT NULL,
		TOT_EMP varchar(255) DEFAULT NULL,
		JOBS_1000 varchar(255) DEFAULT NULL,
		A_MEAN varchar(255) DEFAULT NULL,
		A_PCT90 varchar(255) DEFAULT NULL)";
			
			$link = mysql_connect('localhost','root','root') or die(mysql_error());
			mysql_select_db('BLSdata', $link);
	
		
			mysql_query($createNMSA, $link);
			
			
		
	 
				$sqlNMSA = "
					INSERT INTO nmsaTable
					(AREA_TITLE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					SELECT DISTINCT AREA_TITLE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM BLSdata WHERE NAICS_TITLE = 'Cross-industry' AND `GROUP` = 'detail' AND AREA_TYPE = '6' AND OCC_TITLE IN ('";
					 foreach ($jobTitleData as $job)
					 {
						 $sqlNMSA .= $job . "','";
					 }
					 
					 $sqlNMSA .= "')";
					 
			
					 
		 			try 
		 			{
		 					$db = new PDO("mysql:host=localhost;dbname=BLSdata","root","root");
		 			}	
		 				catch (Exception $e) 
		 					{
		 						echo "Could not connect to database.";
		 						exit;
		 					}
							
							$db->query($sqlNMSA);
							
							$link2 = mysql_connect('localhost','root','root') or die(mysql_error());
							mysql_select_db('BLSdata', $link2);
							
							$sqlRemoveMetro = "UPDATE nmsaTable SET AREA_TITLE = REPLACE(AREA_TITLE, ' nonmetropolitan area', '')";
							
							$sqlRemoveMet = mysql_query($sqlRemoveMetro, $link2);
							
							// ADD ALABAMA PRIM_STATE
							
							$sqlChangeAL = "UPDATE nmsaTable SET PRIM_STATE = 'AL' WHERE AREA_TITLE LIKE '%Alabama%'";
							
							$sqlFireAL = mysql_query($sqlChangeAL, $link2);
							
							// ADD ALASKA PRIM_STATE
							
							$sqlChangeAK = "UPDATE nmsaTable SET PRIM_STATE = 'AK' WHERE AREA_TITLE LIKE '%Alaska%'";
							
							$sqlFireAK = mysql_query($sqlChangeAK, $link2);
							
							// ADD ARIZONA PRIM_STATE
							
							$sqlChangeAZ = "UPDATE nmsaTable SET PRIM_STATE = 'AZ' WHERE AREA_TITLE LIKE '%Arizona%'";
							
							$sqlFireAZ = mysql_query($sqlChangeAZ, $link2);
							
							// ADD ARKANSAS PRIM_STATE
							
							$sqlChangeAR = "UPDATE nmsaTable SET PRIM_STATE = 'AR' WHERE AREA_TITLE LIKE '%Arkansas%'";
							
							$sqlFireAR = mysql_query($sqlChangeAR, $link2);
							
							// ADD CALIFORNIA PRIM_STATE
							
							$sqlChangeCA = "UPDATE nmsaTable SET PRIM_STATE = 'CA' WHERE AREA_TITLE LIKE '%California%'";
							
							$sqlFireCA = mysql_query($sqlChangeCA, $link2);
							
							// ADD COLORADO PRIM_STATE
							
							$sqlChangeCO = "UPDATE nmsaTable SET PRIM_STATE = 'CO' WHERE AREA_TITLE LIKE '%Colorado%'";
							
							$sqlFireCO = mysql_query($sqlChangeCO, $link2);
							
							// ADD CONNECTICUT PRIM_STATE
							
							$sqlChangeCT = "UPDATE nmsaTable SET PRIM_STATE = 'CT' WHERE AREA_TITLE LIKE '%Connecticut%'";
							
							$sqlFireCT = mysql_query($sqlChangeCT, $link2);
							
							// ADD DELAWARE PRIM_STATE
							
							$sqlChangeDE = "UPDATE nmsaTable SET PRIM_STATE = 'DE' WHERE AREA_TITLE LIKE '%Delaware%'";
							
							$sqlFireDE = mysql_query($sqlChangeDE, $link2);
							
							// ADD FLORIDA PRIM_STATE
							
							$sqlChangeFL = "UPDATE nmsaTable SET PRIM_STATE = 'FL' WHERE AREA_TITLE LIKE '%Florida%'";
							
							$sqlFireFL = mysql_query($sqlChangeFL, $link2);
							
							// ADD GEORGIA PRIM_STATE
							
							$sqlChangeGA = "UPDATE nmsaTable SET PRIM_STATE = 'GA' WHERE AREA_TITLE LIKE '%Georgia%'";
							
							$sqlFireGA = mysql_query($sqlChangeGA, $link2);
							
							// ADD HAWAII PRIM_STATE
							
							$sqlChangeHI = "UPDATE nmsaTable SET PRIM_STATE = 'HI' WHERE AREA_TITLE LIKE '%Hawaii%'";
							
							$sqlFireHI = mysql_query($sqlChangeHI, $link2);
							
							// ADD IOWA PRIM_STATE
							
							$sqlChangeIA = "UPDATE nmsaTable SET PRIM_STATE = 'IA' WHERE AREA_TITLE LIKE '%Iowa%'";
							
							$sqlFireIA = mysql_query($sqlChangeIA, $link2);
							
							// ADD IDAHO PRIM_STATE
							
							$sqlChangeID = "UPDATE nmsaTable SET PRIM_STATE = 'ID' WHERE AREA_TITLE LIKE '%Idaho%'";
							
							$sqlFireID = mysql_query($sqlChangeID, $link2);
							
							// ADD ILLINOIS PRIM_STATE
							
							$sqlChangeIL = "UPDATE nmsaTable SET PRIM_STATE = 'IL' WHERE AREA_TITLE LIKE '%Illinois%'";
							
							$sqlFireIL = mysql_query($sqlChangeIL, $link2);
							
							// ADD INDIANA PRIM_STATE
							
							$sqlChangeIN = "UPDATE nmsaTable SET PRIM_STATE = 'IN' WHERE AREA_TITLE LIKE '%Indiana%'";
							
							$sqlFireIN = mysql_query($sqlChangeIN, $link2);
							
							// ADD KANSAS PRIM_STATE
							
							$sqlChangeKS = "UPDATE nmsaTable SET PRIM_STATE = 'KS' WHERE AREA_TITLE LIKE '%Kansas%'";
							
							$sqlFireKS = mysql_query($sqlChangeKS, $link2);
							
							// ADD KENTUCKY PRIM_STATE
							
							$sqlChangeKY = "UPDATE nmsaTable SET PRIM_STATE = 'KY' WHERE AREA_TITLE LIKE '%Kentucky%'";
							
							$sqlFireKY = mysql_query($sqlChangeKY, $link2);
							
							// ADD LOUISIANA PRIM_STATE
							
							$sqlChangeLA = "UPDATE nmsaTable SET PRIM_STATE = 'LA' WHERE AREA_TITLE LIKE '%Louisiana%'";
							
							$sqlFireLA = mysql_query($sqlChangeLA, $link2);
							
							// ADD MAINE PRIM_STATE
							
							$sqlChangeME = "UPDATE nmsaTable SET PRIM_STATE = 'ME' WHERE AREA_TITLE LIKE '%Maine%'";
							
							$sqlFireME = mysql_query($sqlChangeME, $link2);
							
							// ADD MARYLAND PRIM_STATE
							
							$sqlChangeMD = "UPDATE nmsaTable SET PRIM_STATE = 'MD' WHERE AREA_TITLE LIKE '%Maryland%'";
							
							$sqlFireMD = mysql_query($sqlChangeMD, $link2);
							
							// ADD MASSACHUSETTS PRIM_STATE
							
							$sqlChangeMA = "UPDATE nmsaTable SET PRIM_STATE = 'MA' WHERE AREA_TITLE LIKE '%Massachusetts%'";
							
							$sqlFireMA = mysql_query($sqlChangeMA, $link2);
							
							// ADD MICHIGAN PRIM_STATE
							
							$sqlChangeMI = "UPDATE nmsaTable SET PRIM_STATE = 'MI' WHERE AREA_TITLE LIKE '%Michigan%'";
							
							$sqlFireMI = mysql_query($sqlChangeMI, $link2);
							
							// ADD MINNESOTA PRIM_STATE
							
							$sqlChangeMN = "UPDATE nmsaTable SET PRIM_STATE = 'MN' WHERE AREA_TITLE LIKE '%Minnesota%'";
							
							$sqlFireMN = mysql_query($sqlChangeMN, $link2);
							
							// ADD MISSISSIPPI PRIM_STATE
							
							$sqlChangeMS = "UPDATE nmsaTable SET PRIM_STATE = 'MS' WHERE AREA_TITLE LIKE '%Mississippi%'";
							
							$sqlFireMS = mysql_query($sqlChangeMS, $link2);
							
							// ADD MISSOURI PRIM_STATE
							
							$sqlChangeMO = "UPDATE nmsaTable SET PRIM_STATE = 'MO' WHERE AREA_TITLE LIKE '%Missouri%'";
							
							$sqlFireMO = mysql_query($sqlChangeMO, $link2);
							
							// ADD MONTANA PRIM_STATE
							
							$sqlChangeMT = "UPDATE nmsaTable SET PRIM_STATE = 'MT' WHERE AREA_TITLE LIKE '%Montana%'";
							
							$sqlFireMT = mysql_query($sqlChangeMT, $link2);
							
							// ADD NEBRASKA PRIM_STATE
							
							$sqlChangeNE = "UPDATE nmsaTable SET PRIM_STATE = 'NE' WHERE AREA_TITLE LIKE '%Nebraska%'";
							
							$sqlFireNE = mysql_query($sqlChangeNE, $link2);
							
							// ADD NEVADA PRIM_STATE
							
							$sqlChangeNV = "UPDATE nmsaTable SET PRIM_STATE = 'NV' WHERE AREA_TITLE LIKE '%Nevada%'";
							
							$sqlFireNV = mysql_query($sqlChangeNV, $link2);
							
							// ADD NEW HAMPSHIRE PRIM_STATE
							
							$sqlChangeNH = "UPDATE nmsaTable SET PRIM_STATE = 'NH' WHERE AREA_TITLE LIKE '%New Hampshire%'";
							
							$sqlFireNH = mysql_query($sqlChangeNH, $link2);
							
							// ADD NEW JERSEY PRIM_STATE
							
							$sqlChangeNJ = "UPDATE nmsaTable SET PRIM_STATE = 'NJ' WHERE AREA_TITLE LIKE '%New Jersey%'";
							
							$sqlFireNJ = mysql_query($sqlChangeNJ, $link2);
							
							// ADD NEW MEXICO PRIM_STATE
							
							$sqlChangeNM = "UPDATE nmsaTable SET PRIM_STATE = 'NM' WHERE AREA_TITLE LIKE '%New Mexico%'";
							
							$sqlFireNM = mysql_query($sqlChangeNM, $link2);
							
							// ADD NEW YORK PRIM_STATE
							
							$sqlChangeNY = "UPDATE nmsaTable SET PRIM_STATE = 'NY' WHERE AREA_TITLE LIKE '%New York%'";
							
							$sqlFireNY = mysql_query($sqlChangeNY, $link2);
							
							// ADD NORTH CAROLINA PRIM_STATE
							
							$sqlChangeNC = "UPDATE nmsaTable SET PRIM_STATE = 'NC' WHERE AREA_TITLE LIKE '%North Carolina%'";
							
							$sqlFireNC = mysql_query($sqlChangeNC, $link2);
							
							// ADD NORTH DAKOTA PRIM_STATE
							
							$sqlChangeND = "UPDATE nmsaTable SET PRIM_STATE = 'ND' WHERE AREA_TITLE LIKE '%North Dakota%'";
							
							$sqlFireND = mysql_query($sqlChangeND, $link2);
							
							// ADD OHIO PRIM_STATE
							
							$sqlChangeOH = "UPDATE nmsaTable SET PRIM_STATE = 'OH' WHERE AREA_TITLE LIKE '%Ohio%'";
							
							$sqlFireOH = mysql_query($sqlChangeOH, $link2);
							
							// ADD OKLAHOMA PRIM_STATE
							
							$sqlChangeOK = "UPDATE nmsaTable SET PRIM_STATE = 'OK' WHERE AREA_TITLE LIKE '%Oklahoma%'";
							
							$sqlFireOK = mysql_query($sqlChangeOK, $link2);
							
							// ADD OREGON PRIM_STATE
							
							$sqlChangeOR = "UPDATE nmsaTable SET PRIM_STATE = 'OR' WHERE AREA_TITLE LIKE '%Oregon%'";
							
							$sqlFireOR = mysql_query($sqlChangeOR, $link2);
							
							// ADD PENNSYLVANIA PRIM_STATE
							
							$sqlChangePA = "UPDATE nmsaTable SET PRIM_STATE = 'PA' WHERE AREA_TITLE LIKE '%Pennsylvania%'";
							
							$sqlFirePA = mysql_query($sqlChangePA, $link2);
							
							// ADD RHODE ISLAND PRIM_STATE
							
							$sqlChangeRI = "UPDATE nmsaTable SET PRIM_STATE = 'RI' WHERE AREA_TITLE LIKE '%Rhode Island%'";
							
							$sqlFireRI = mysql_query($sqlChangeRI, $link2);
							
							// ADD SOUTH CAROLINA PRIM_STATE
							
							$sqlChangeSC = "UPDATE nmsaTable SET PRIM_STATE = 'SC' WHERE AREA_TITLE LIKE '%South Carolina%'";
							
							$sqlFireSC = mysql_query($sqlChangeSC, $link2);
							
							// ADD SOUTH DAKOTA PRIM_STATE
							
							$sqlChangeSD = "UPDATE nmsaTable SET PRIM_STATE = 'SD' WHERE AREA_TITLE LIKE '%South Dakota%'";
							
							$sqlFireSD = mysql_query($sqlChangeSD, $link2);
							
							// ADD TENNESSEE PRIM_STATE
							
							$sqlChangeTN = "UPDATE nmsaTable SET PRIM_STATE = 'TN' WHERE AREA_TITLE LIKE '%Tennessee%'";
							
							$sqlFireTN = mysql_query($sqlChangeTN, $link2);
							
							// ADD TEXAS PRIM_STATE
							
							$sqlChangeTX = "UPDATE nmsaTable SET PRIM_STATE = 'TX' WHERE AREA_TITLE LIKE '%Texas%'";
							
							$sqlFireTX = mysql_query($sqlChangeTX, $link2);
							
							// ADD UTAH PRIM_STATE
							
							$sqlChangeUT = "UPDATE nmsaTable SET PRIM_STATE = 'UT' WHERE AREA_TITLE LIKE '%Utah%'";
							
							$sqlFireUT = mysql_query($sqlChangeUT, $link2);
							
							// ADD VERMONT PRIM_STATE
							
							$sqlChangeVT = "UPDATE nmsaTable SET PRIM_STATE = 'VT' WHERE AREA_TITLE LIKE '%Vermont%'";
							
							$sqlFireVT = mysql_query($sqlChangeVT, $link2);
							
							// ADD VIRGINIA PRIM_STATE
							
							$sqlChangeVA = "UPDATE nmsaTable SET PRIM_STATE = 'VA' WHERE AREA_TITLE LIKE '%Virginia%'";
							
							$sqlFireVA = mysql_query($sqlChangeVA, $link2);
							
							// ADD WASHINGTON PRIM_STATE
							
							$sqlChangeWA = "UPDATE nmsaTable SET PRIM_STATE = 'WA' WHERE AREA_TITLE LIKE '%Washington%'";
							
							$sqlFireWA = mysql_query($sqlChangeWA, $link2);
							
							// ADD WEST VIRGINIA PRIM_STATE
							
							$sqlChangeWV = "UPDATE nmsaTable SET PRIM_STATE = 'WV' WHERE AREA_TITLE LIKE '%West Virginia%'";
							
							$sqlFireWV = mysql_query($sqlChangeWV, $link2);
							
							// ADD WISCONSIN PRIM_STATE
							
							$sqlChangeWI = "UPDATE nmsaTable SET PRIM_STATE = 'WI' WHERE AREA_TITLE LIKE '%Wisconsin%'";
							
							$sqlFireWI = mysql_query($sqlChangeWI, $link2);
							
							// ADD WYOMING PRIM_STATE
							
							$sqlChangeWY = "UPDATE nmsaTable SET PRIM_STATE = 'WY' WHERE AREA_TITLE LIKE '%Wyoming%'";
							
							$sqlFireWY = mysql_query($sqlChangeWY, $link2);
							
							// CLEAN OUT COMMAS AND SYMBOLS
		
							$sqlCommaTOT = "UPDATE nmsaTable SET TOT_EMP = REPLACE(TOT_EMP, ',', '')";
		
							$sqlFireTOT = mysql_query($sqlCommaTOT, $link2);
							
							$sqlCleanJOBS = "UPDATE nmsaTable SET JOBS_1000 = '' WHERE JOBS_1000 = '**'";
		
							$sqlFireCleanJOBS = mysql_query($sqlCleanJOBS, $link2);
		
							$sqlCommaMEAN = "UPDATE nmsaTable SET A_MEAN = REPLACE(A_MEAN, ',', '')";
		
							$sqlFireMEAN = mysql_query($sqlCommaMEAN, $link2);
		
							$sqlComma90 = "UPDATE nmsaTable SET A_PCT90 = REPLACE(A_PCT90, ',', '')";
		
							$sqlFire90 = mysql_query($sqlComma90, $link2);
		
							$sql2StarTOT = "UPDATE nmsaTable SET TOT_EMP = REPLACE(TOT_EMP, '**', '')";
		
							$sqlFire2StarTOT = mysql_query($sql2StarTOT, $link2);
		
							$sql1StarTOT = "UPDATE nmsaTable SET TOT_EMP = REPLACE(TOT_EMP, '*', '')";
		
							$sqlFire1StarTOT = mysql_query($sql1StarMEAN, $link2);
		
							$sql2StarMEAN = "UPDATE nmsaTable SET A_MEAN = REPLACE(A_MEAN, '**', '')";
		
							$sqlFire2StarMEAN = mysql_query($sql2StarMEAN, $link2);
							
							$sql1StarMEAN = "UPDATE nmsaTable SET A_MEAN = REPLACE(A_MEAN, '*', '')";
		
							$sqlFire1StarMEAN = mysql_query($sql1StarMEAN, $link2);
							
							$sql1Star90 = "UPDATE nmsaTable SET A_PCT90 = REPLACE(A_PCT90, '*', '')";
		
							$sqlFire1Star90 = mysql_query($sql1Star90, $link2);
		
							$sqlHash90 = "UPDATE nmsaTable SET A_PCT90 = REPLACE(A_PCT90, '#', '')";
		
							$sqlFireHash90 = mysql_query($sqlHash90, $link2);
							
							$sqlCreateTempNMSA = "CREATE TABLE nmsaTemp (
							  	 	PRIM_STATE varchar(50) DEFAULT NULL,
								 	OCC_TITLE varchar(255) DEFAULT NULL,
									TOT_EMP varchar(255) DEFAULT NULL,
									JOBS_1000 varchar(255) DEFAULT NULL,
									A_MEAN varchar(255) DEFAULT NULL,
									A_PCT90 varchar(255) DEFAULT NULL)";
				
									$createTempNMSATable = mysql_query($sqlCreateTempNMSA, $link2);
									
									
									$sqlAvg = "SELECT DISTINCT PRIM_STATE, OCC_TITLE FROM nmsaTable";
									$activityList = mysql_query($sqlAvg, $link2) or die(mysql_error()); 
									$row_activityList = mysql_fetch_assoc($activityList); 
		
									do{
											
									
				
											$sqlAverage = "INSERT INTO nmsaTemp (PRIM_STATE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90) 
							SELECT PRIM_STATE, OCC_TITLE, SUM(TOT_EMP), AVG(JOBS_1000), AVG(A_MEAN), AVG(A_PCT90)
							FROM nmsaTable WHERE PRIM_STATE = '" . $row_activityList['PRIM_STATE'] . "' AND OCC_TITLE = '" . $row_activityList['OCC_TITLE'] . "';";

$sqlFireAverage = mysql_query($sqlAverage, $link2) or die(mysql_error()); 
$row_sqlFireAverage = mysql_fetch_assoc($sqlFireAverage); 


				
										} while ($row_activityList = mysql_fetch_assoc($activityList));
										
										// ADD COMMAS BACK AND REPLACE ZEROES WITH STARS
			
										$sqlReturnCommasTOT = "UPDATE nmsaTemp SET TOT_EMP = (FORMAT(TOT_EMP, 0))";
			
										$sqlFireReturnCommaTOT = mysql_query($sqlReturnCommasTOT, $link2);
			
										$sqlReplaceZeroesTOT = "UPDATE nmsaTemp SET TOT_EMP = '*' WHERE TOT_EMP = '0'";
			
										$sqlFireReplaceZeroesTOT = mysql_query($sqlReplaceZeroesTOT, $link2);
			
										$sqlShortenJOBS = "UPDATE nmsaTemp SET JOBS_1000 = (FORMAT(JOBS_1000, 2))";
			
										$sqlFireShortenJOBS = mysql_query($sqlShortenJOBS, $link2);
			
										$sqlReturnCommasMEAN = "UPDATE nmsaTemp SET A_MEAN = (FORMAT(A_MEAN, 0))";
			
										$sqlFireReturnCommaMEAN = mysql_query($sqlReturnCommasMEAN, $link2);
			
										$sqlReturnCommas90 = "UPDATE nmsaTemp SET A_PCT90 = (FORMAT(A_PCT90, 0))";
			
										$sqlFireReturnComma90 = mysql_query($sqlReturnCommas90, $link2);
			
										$sqlReplaceZeroesMEAN = "UPDATE nmsaTemp SET A_MEAN = '*' WHERE A_MEAN = '0'";
			
										$sqlFireReplaceZeroesMEAN = mysql_query($sqlReplaceZeroesMEAN, $link2);
			
										$sqlReplaceZeroes90 = "UPDATE nmsaTemp SET A_PCT90 = '*' WHERE A_PCT90 = '0'";
			
										$sqlFireReplaceZeroes90 = mysql_query($sqlReplaceZeroes90, $link2);
										
										$sqlReplaceZeroesJOBS = "UPDATE nmsaTemp SET JOBS_1000 = '*' WHERE JOBS_1000 = '0.00'";
			
										$sqlFireReplaceZeroesJOBS = mysql_query($sqlReplaceZeroesJOBS, $link2);
			
										// DELETE msaTable AND RENAME msaTemp TO msaTable
			
										$sqlDeleteMSA = "DROP TABLE nmsaTable";
			
										$sqlFireDelete = mysql_query($sqlDeleteMSA, $link2);
			
										$sqlRenameMSA = "RENAME TABLE nmsaTemp TO nmsaTable";
			
										$sqlFireRename = mysql_query($sqlRenameMSA, $link2);
							
							
						
							
							
							$createCSV2 = "SELECT 'PRIM_STATE', 'OCC_TITLE', 'TOT_EMP', 'JOBS_1000', 'A_MEAN', 'A_PCT90'
								UNION ALL
								SELECT DISTINCT PRIM_STATE, 
								OCC_TITLE, 
								TOT_EMP, 
								JOBS_1000, 
								A_MEAN, 
								A_PCT90 FROM nmsaTable INTO OUTFILE '/tmp/nmsaTable.csv'
								FIELDS TERMINATED BY ','
								OPTIONALLY ENCLOSED BY '\"'";

							$createquery = mysql_query($createCSV2, $link2);

}

if (in_array("state", $tableTypes))
{
	$createState = "
				CREATE TABLE stateTable (
				AREA_TITLE varchar(255),
			 	AREA_TYPE int(11),
			 	OCC_TITLE varchar(255),
				TOT_EMP varchar(255),
				JOBS_1000 varchar(255),
				A_MEAN varchar(255),
				A_PCT90 varchar(255)
			)";
			
			try 
			{
					$db = new PDO("mysql:host=localhost;dbname=BLSdata","root","root");
			}	
				catch (Exception $e) 
					{
						echo "Could not connect to database.";
						exit;
					}
		
					$db->query($createState);
							
				 
				 
				 
				$sqlState = "
					INSERT INTO stateTable
					(AREA_TITLE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					SELECT DISTINCT AREA_TITLE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM BLSdata WHERE NAICS_TITLE = 'Cross-industry' AND `GROUP` = 'detail' AND AREA_TYPE = '2' AND OCC_TITLE IN ('";
					 foreach ($jobTitleData as $job)
					 {
						 $sqlState .= $job . "','";
					 }
					 
					 $sqlState .= "')";
					 
		 			try 
		 			{
		 					$db = new PDO("mysql:host=localhost;dbname=BLSdata","root","root");
		 			}	
		 				catch (Exception $e) 
		 					{
		 						echo "Could not connect to database.";
		 						exit;
		 					}
		
		 					$db->query($sqlState);
							
							$sqlAddPRIMCOL = "ALTER TABLE stateTable ADD PRIM_STATE VARCHAR(50) AFTER AREA_TITLE";
							
							$db->query($sqlAddPRIMCOL);
							
							
							$link2 = mysql_connect('localhost','root','root') or die(mysql_error());
							mysql_select_db('BLSdata', $link2);
							
							
							// ADD ALABAMA PRIM_STATE
							
							$sqlChangeAL = "UPDATE stateTable SET PRIM_STATE = 'AL' WHERE AREA_TITLE LIKE '%Alabama%'";
							
							$sqlFireAL = mysql_query($sqlChangeAL, $link2);
							
							// ADD ALASKA PRIM_STATE
							
							$sqlChangeAK = "UPDATE stateTable SET PRIM_STATE = 'AK' WHERE AREA_TITLE LIKE '%Alaska%'";
							
							$sqlFireAK = mysql_query($sqlChangeAK, $link2);
							
							// ADD ARIZONA PRIM_STATE
							
							$sqlChangeAZ = "UPDATE stateTable SET PRIM_STATE = 'AZ' WHERE AREA_TITLE LIKE '%Arizona%'";
							
							$sqlFireAZ = mysql_query($sqlChangeAZ, $link2);
							
							// ADD ARKANSAS PRIM_STATE
							
							$sqlChangeAR = "UPDATE stateTable SET PRIM_STATE = 'AR' WHERE AREA_TITLE LIKE '%Arkansas%'";
							
							$sqlFireAR = mysql_query($sqlChangeAR, $link2);
							
							// ADD CALIFORNIA PRIM_STATE
							
							$sqlChangeCA = "UPDATE stateTable SET PRIM_STATE = 'CA' WHERE AREA_TITLE LIKE '%California%'";
							
							$sqlFireCA = mysql_query($sqlChangeCA, $link2);
							
							// ADD COLORADO PRIM_STATE
							
							$sqlChangeCO = "UPDATE stateTable SET PRIM_STATE = 'CO' WHERE AREA_TITLE LIKE '%Colorado%'";
							
							$sqlFireCO = mysql_query($sqlChangeCO, $link2);
							
							// ADD CONNECTICUT PRIM_STATE
							
							$sqlChangeCT = "UPDATE stateTable SET PRIM_STATE = 'CT' WHERE AREA_TITLE LIKE '%Connecticut%'";
							
							$sqlFireCT = mysql_query($sqlChangeCT, $link2);
							
							// ADD DELAWARE PRIM_STATE
							
							$sqlChangeDE = "UPDATE stateTable SET PRIM_STATE = 'DE' WHERE AREA_TITLE LIKE '%Delaware%'";
							
							$sqlFireDE = mysql_query($sqlChangeDE, $link2);
							
							// ADD DISTRICT OF COLUMBIA PRIM_STATE
							
							$sqlChangeDC = "UPDATE stateTable SET PRIM_STATE = 'DC' WHERE AREA_TITLE LIKE '%District of Columbia%'";
							
							$sqlFireDC = mysql_query($sqlChangeDC, $link2);
							
							// ADD FLORIDA PRIM_STATE
							
							$sqlChangeFL = "UPDATE stateTable SET PRIM_STATE = 'FL' WHERE AREA_TITLE LIKE '%Florida%'";
							
							$sqlFireFL = mysql_query($sqlChangeFL, $link2);
							
							// ADD GEORGIA PRIM_STATE
							
							$sqlChangeGA = "UPDATE stateTable SET PRIM_STATE = 'GA' WHERE AREA_TITLE LIKE '%Georgia%'";
							
							$sqlFireGA = mysql_query($sqlChangeGA, $link2);
							
							// ADD HAWAII PRIM_STATE
							
							$sqlChangeHI = "UPDATE stateTable SET PRIM_STATE = 'HI' WHERE AREA_TITLE LIKE '%Hawaii%'";
							
							$sqlFireHI = mysql_query($sqlChangeHI, $link2);
							
							// ADD IOWA PRIM_STATE
							
							$sqlChangeIA = "UPDATE stateTable SET PRIM_STATE = 'IA' WHERE AREA_TITLE LIKE '%Iowa%'";
							
							$sqlFireIA = mysql_query($sqlChangeIA, $link2);
							
							// ADD IDAHO PRIM_STATE
							
							$sqlChangeID = "UPDATE stateTable SET PRIM_STATE = 'ID' WHERE AREA_TITLE LIKE '%Idaho%'";
							
							$sqlFireID = mysql_query($sqlChangeID, $link2);
							
							// ADD ILLINOIS PRIM_STATE
							
							$sqlChangeIL = "UPDATE stateTable SET PRIM_STATE = 'IL' WHERE AREA_TITLE LIKE '%Illinois%'";
							
							$sqlFireIL = mysql_query($sqlChangeIL, $link2);
							
							// ADD INDIANA PRIM_STATE
							
							$sqlChangeIN = "UPDATE stateTable SET PRIM_STATE = 'IN' WHERE AREA_TITLE LIKE '%Indiana%'";
							
							$sqlFireIN = mysql_query($sqlChangeIN, $link2);
							
							// ADD KANSAS PRIM_STATE
							
							$sqlChangeKS = "UPDATE stateTable SET PRIM_STATE = 'KS' WHERE AREA_TITLE LIKE '%Kansas%'";
							
							$sqlFireKS = mysql_query($sqlChangeKS, $link2);
							
							// ADD KENTUCKY PRIM_STATE
							
							$sqlChangeKY = "UPDATE stateTable SET PRIM_STATE = 'KY' WHERE AREA_TITLE LIKE '%Kentucky%'";
							
							$sqlFireKY = mysql_query($sqlChangeKY, $link2);
							
							// ADD LOUISIANA PRIM_STATE
							
							$sqlChangeLA = "UPDATE stateTable SET PRIM_STATE = 'LA' WHERE AREA_TITLE LIKE '%Louisiana%'";
							
							$sqlFireLA = mysql_query($sqlChangeLA, $link2);
							
							// ADD MAINE PRIM_STATE
							
							$sqlChangeME = "UPDATE stateTable SET PRIM_STATE = 'ME' WHERE AREA_TITLE LIKE '%Maine%'";
							
							$sqlFireME = mysql_query($sqlChangeME, $link2);
							
							// ADD MARYLAND PRIM_STATE
							
							$sqlChangeMD = "UPDATE stateTable SET PRIM_STATE = 'MD' WHERE AREA_TITLE LIKE '%Maryland%'";
							
							$sqlFireMD = mysql_query($sqlChangeMD, $link2);
							
							// ADD MASSACHUSETTS PRIM_STATE
							
							$sqlChangeMA = "UPDATE stateTable SET PRIM_STATE = 'MA' WHERE AREA_TITLE LIKE '%Massachusetts%'";
							
							$sqlFireMA = mysql_query($sqlChangeMA, $link2);
							
							// ADD MICHIGAN PRIM_STATE
							
							$sqlChangeMI = "UPDATE stateTable SET PRIM_STATE = 'MI' WHERE AREA_TITLE LIKE '%Michigan%'";
							
							$sqlFireMI = mysql_query($sqlChangeMI, $link2);
							
							// ADD MINNESOTA PRIM_STATE
							
							$sqlChangeMN = "UPDATE stateTable SET PRIM_STATE = 'MN' WHERE AREA_TITLE LIKE '%Minnesota%'";
							
							$sqlFireMN = mysql_query($sqlChangeMN, $link2);
							
							// ADD MISSISSIPPI PRIM_STATE
							
							$sqlChangeMS = "UPDATE stateTable SET PRIM_STATE = 'MS' WHERE AREA_TITLE LIKE '%Mississippi%'";
							
							$sqlFireMS = mysql_query($sqlChangeMS, $link2);
							
							// ADD MISSOURI PRIM_STATE
							
							$sqlChangeMO = "UPDATE stateTable SET PRIM_STATE = 'MO' WHERE AREA_TITLE LIKE '%Missouri%'";
							
							$sqlFireMO = mysql_query($sqlChangeMO, $link2);
							
							// ADD MONTANA PRIM_STATE
							
							$sqlChangeMT = "UPDATE stateTable SET PRIM_STATE = 'MT' WHERE AREA_TITLE LIKE '%Montana%'";
							
							$sqlFireMT = mysql_query($sqlChangeMT, $link2);
							
							// ADD NEBRASKA PRIM_STATE
							
							$sqlChangeNE = "UPDATE stateTable SET PRIM_STATE = 'NE' WHERE AREA_TITLE LIKE '%Nebraska%'";
							
							$sqlFireNE = mysql_query($sqlChangeNE, $link2);
							
							// ADD NEVADA PRIM_STATE
							
							$sqlChangeNV = "UPDATE stateTable SET PRIM_STATE = 'NV' WHERE AREA_TITLE LIKE '%Nevada%'";
							
							$sqlFireNV = mysql_query($sqlChangeNV, $link2);
							
							// ADD NEW HAMPSHIRE PRIM_STATE
							
							$sqlChangeNH = "UPDATE stateTable SET PRIM_STATE = 'NH' WHERE AREA_TITLE LIKE '%New Hampshire%'";
							
							$sqlFireNH = mysql_query($sqlChangeNH, $link2);
							
							// ADD NEW JERSEY PRIM_STATE
							
							$sqlChangeNJ = "UPDATE stateTable SET PRIM_STATE = 'NJ' WHERE AREA_TITLE LIKE '%New Jersey%'";
							
							$sqlFireNJ = mysql_query($sqlChangeNJ, $link2);
							
							// ADD NEW MEXICO PRIM_STATE
							
							$sqlChangeNM = "UPDATE stateTable SET PRIM_STATE = 'NM' WHERE AREA_TITLE LIKE '%New Mexico%'";
							
							$sqlFireNM = mysql_query($sqlChangeNM, $link2);
							
							// ADD NEW YORK PRIM_STATE
							
							$sqlChangeNY = "UPDATE stateTable SET PRIM_STATE = 'NY' WHERE AREA_TITLE LIKE '%New York%'";
							
							$sqlFireNY = mysql_query($sqlChangeNY, $link2);
							
							// ADD NORTH CAROLINA PRIM_STATE
							
							$sqlChangeNC = "UPDATE stateTable SET PRIM_STATE = 'NC' WHERE AREA_TITLE LIKE '%North Carolina%'";
							
							$sqlFireNC = mysql_query($sqlChangeNC, $link2);
							
							// ADD NORTH DAKOTA PRIM_STATE
							
							$sqlChangeND = "UPDATE stateTable SET PRIM_STATE = 'ND' WHERE AREA_TITLE LIKE '%North Dakota%'";
							
							$sqlFireND = mysql_query($sqlChangeND, $link2);
							
							// ADD OHIO PRIM_STATE
							
							$sqlChangeOH = "UPDATE stateTable SET PRIM_STATE = 'OH' WHERE AREA_TITLE LIKE '%Ohio%'";
							
							$sqlFireOH = mysql_query($sqlChangeOH, $link2);
							
							// ADD OKLAHOMA PRIM_STATE
							
							$sqlChangeOK = "UPDATE stateTable SET PRIM_STATE = 'OK' WHERE AREA_TITLE LIKE '%Oklahoma%'";
							
							$sqlFireOK = mysql_query($sqlChangeOK, $link2);
							
							// ADD OREGON PRIM_STATE
							
							$sqlChangeOR = "UPDATE stateTable SET PRIM_STATE = 'OR' WHERE AREA_TITLE LIKE '%Oregon%'";
							
							$sqlFireOR = mysql_query($sqlChangeOR, $link2);
							
							// ADD PENNSYLVANIA PRIM_STATE
							
							$sqlChangePA = "UPDATE stateTable SET PRIM_STATE = 'PA' WHERE AREA_TITLE LIKE '%Pennsylvania%'";
							
							$sqlFirePA = mysql_query($sqlChangePA, $link2);
							
							// ADD RHODE ISLAND PRIM_STATE
							
							$sqlChangeRI = "UPDATE stateTable SET PRIM_STATE = 'RI' WHERE AREA_TITLE LIKE '%Rhode Island%'";
							
							$sqlFireRI = mysql_query($sqlChangeRI, $link2);
							
							// ADD SOUTH CAROLINA PRIM_STATE
							
							$sqlChangeSC = "UPDATE stateTable SET PRIM_STATE = 'SC' WHERE AREA_TITLE LIKE '%South Carolina%'";
							
							$sqlFireSC = mysql_query($sqlChangeSC, $link2);
							
							// ADD SOUTH DAKOTA PRIM_STATE
							
							$sqlChangeSD = "UPDATE stateTable SET PRIM_STATE = 'SD' WHERE AREA_TITLE LIKE '%South Dakota%'";
							
							$sqlFireSD = mysql_query($sqlChangeSD, $link2);
							
							// ADD TENNESSEE PRIM_STATE
							
							$sqlChangeTN = "UPDATE stateTable SET PRIM_STATE = 'TN' WHERE AREA_TITLE LIKE '%Tennessee%'";
							
							$sqlFireTN = mysql_query($sqlChangeTN, $link2);
							
							// ADD TEXAS PRIM_STATE
							
							$sqlChangeTX = "UPDATE stateTable SET PRIM_STATE = 'TX' WHERE AREA_TITLE LIKE '%Texas%'";
							
							$sqlFireTX = mysql_query($sqlChangeTX, $link2);
							
							// ADD UTAH PRIM_STATE
							
							$sqlChangeUT = "UPDATE stateTable SET PRIM_STATE = 'UT' WHERE AREA_TITLE LIKE '%Utah%'";
							
							$sqlFireUT = mysql_query($sqlChangeUT, $link2);
							
							// ADD VERMONT PRIM_STATE
							
							$sqlChangeVT = "UPDATE stateTable SET PRIM_STATE = 'VT' WHERE AREA_TITLE LIKE '%Vermont%'";
							
							$sqlFireVT = mysql_query($sqlChangeVT, $link2);
							
							// ADD VIRGINIA PRIM_STATE
							
							$sqlChangeVA = "UPDATE stateTable SET PRIM_STATE = 'VA' WHERE AREA_TITLE LIKE '%Virginia%'";
							
							$sqlFireVA = mysql_query($sqlChangeVA, $link2);
							
							// ADD WASHINGTON PRIM_STATE
							
							$sqlChangeWA = "UPDATE stateTable SET PRIM_STATE = 'WA' WHERE AREA_TITLE LIKE '%Washington%'";
							
							$sqlFireWA = mysql_query($sqlChangeWA, $link2);
							
							// ADD WEST VIRGINIA PRIM_STATE
							
							$sqlChangeWV = "UPDATE stateTable SET PRIM_STATE = 'WV' WHERE AREA_TITLE LIKE '%West Virginia%'";
							
							$sqlFireWV = mysql_query($sqlChangeWV, $link2);
							
							// ADD WISCONSIN PRIM_STATE
							
							$sqlChangeWI = "UPDATE stateTable SET PRIM_STATE = 'WI' WHERE AREA_TITLE LIKE '%Wisconsin%'";
							
							$sqlFireWI = mysql_query($sqlChangeWI, $link2);
							
							// ADD WYOMING PRIM_STATE
							
							$sqlChangeWY = "UPDATE stateTable SET PRIM_STATE = 'WY' WHERE AREA_TITLE LIKE '%Wyoming%'";
							
							$sqlFireWY = mysql_query($sqlChangeWY, $link2);
							
							// DROP AREA_TITLE COLUMN
							
							$sqlDropArea = "ALTER TABLE stateTable DROP AREA_TITLE";
							
							$sqlFireDrop = mysql_query($sqlDropArea, $link2);
							
							// CLEAN OUT COMMAS
							
							$sqlCleanJOBS = "UPDATE stateTable SET JOBS_1000 = '' WHERE JOBS_1000 = '**'";
		
							$sqlFireCleanJOBS = mysql_query($sqlCleanJOBS, $link2);
		
							$sql2StarTOT = "UPDATE stateTable SET TOT_EMP = REPLACE(TOT_EMP, '**', '')";
		
							$sqlFire2StarTOT = mysql_query($sql2StarTOT, $link2);
		
							$sql1StarTOT = "UPDATE stateTable SET TOT_EMP = REPLACE(TOT_EMP, '*', '')";
		
							$sqlFire1StarTOT = mysql_query($sql1StarMEAN, $link2);
		
							$sql2StarMEAN = "UPDATE stateTable SET A_MEAN = REPLACE(A_MEAN, '**', '')";
		
							$sqlFire2StarMEAN = mysql_query($sql2StarMEAN, $link2);
							
							$sql1StarMEAN = "UPDATE stateTable SET A_MEAN = REPLACE(A_MEAN, '*', '')";
		
							$sqlFire1StarMEAN = mysql_query($sql1StarMEAN, $link2);
							
							$sql1Star90 = "UPDATE stateTable SET A_PCT90 = REPLACE(A_PCT90, '*', '')";
		
							$sqlFire1Star90 = mysql_query($sql1Star90, $link2);
		
							$sqlHash90 = "UPDATE stateTable SET A_PCT90 = REPLACE(A_PCT90, '#', '')";
		
							$sqlFireHash90 = mysql_query($sqlHash90, $link2);
							
							// ADD COMMAS BACK AND REPLACE ZEROES WITH STARS

							$sqlReturnCommasTOT = "UPDATE stateTable SET TOT_EMP = (FORMAT(TOT_EMP, 0))";

							$sqlFireReturnCommaTOT = mysql_query($sqlReturnCommasTOT, $link2);

							$sqlReplaceZeroesTOT = "UPDATE stateTable SET TOT_EMP = '*' WHERE TOT_EMP = '0'";

							$sqlFireReplaceZeroesTOT = mysql_query($sqlReplaceZeroesTOT, $link2);

							$sqlShortenJOBS = "UPDATE stateTable SET JOBS_1000 = (FORMAT(JOBS_1000, 2))";

							$sqlFireShortenJOBS = mysql_query($sqlShortenJOBS, $link2);

							$sqlReturnCommasMEAN = "UPDATE stateTable SET A_MEAN = (FORMAT(A_MEAN, 0))";

							$sqlFireReturnCommaMEAN = mysql_query($sqlReturnCommasMEAN, $link2);

							$sqlReturnCommas90 = "UPDATE stateTable SET A_PCT90 = (FORMAT(A_PCT90, 0))";

							$sqlFireReturnComma90 = mysql_query($sqlReturnCommas90, $link2);

							$sqlReplaceZeroesMEAN = "UPDATE stateTable SET A_MEAN = '*' WHERE A_MEAN = ''";

							$sqlFireReplaceZeroesMEAN = mysql_query($sqlReplaceZeroesMEAN, $link2);

							$sqlReplaceZeroes90 = "UPDATE stateTable SET A_PCT90 = '*' WHERE A_PCT90 = ''";

							$sqlFireReplaceZeroes90 = mysql_query($sqlReplaceZeroes90, $link2);
							
							$sqlReplaceZeroesJOBS = "UPDATE stateTable SET JOBS_1000 = '*' WHERE JOBS_1000 = '0.00'";

							$sqlFireReplaceZeroesJOBS = mysql_query($sqlReplaceZeroesJOBS, $link2);
							
							
							
							
							
							
							
							$createCSV = "SELECT 'PRIM_STATE', 'AREA_TYPE', 'OCC_TITLE', 'TOT_EMP', 'JOBS_1000', 'A_MEAN', 'A_PCT90'
								UNION ALL
								SELECT DISTINCT PRIM_STATE, 
								AREA_TYPE, 
								OCC_TITLE, 
								TOT_EMP, 
								JOBS_1000, 
								A_MEAN, 
								A_PCT90 FROM stateTable INTO OUTFILE '/tmp/stateTable.csv'
								FIELDS TERMINATED BY ','
								OPTIONALLY ENCLOSED BY '\"'";

							$createquery = mysql_query($createCSV, $link2);
}

if (in_array("nat", $tableTypes))
{
	$createNat = "
				CREATE TABLE natTable (
				AREA_TITLE varchar(255),
			 	AREA_TYPE int(11),
			 	OCC_TITLE varchar(255),
				TOT_EMP varchar(255),
				JOBS_1000 varchar(255),
				A_MEAN varchar(255),
				A_PCT90 varchar(255)
			)";
			
			try 
			{
					$db = new PDO("mysql:host=localhost;dbname=BLSdata","root","root");
			}	
				catch (Exception $e) 
					{
						echo "Could not connect to database.";
						exit;
					}
		
					$db->query($createNat);
							
				 
				 
				 
				$sqlNat = "
					INSERT INTO natTable
					(AREA_TITLE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90)
					SELECT DISTINCT AREA_TITLE, AREA_TYPE, OCC_TITLE, TOT_EMP, JOBS_1000, A_MEAN, A_PCT90 FROM BLSdata WHERE NAICS_TITLE = 'Cross-industry' AND `GROUP` = 'detail' AND AREA_TYPE = '1' AND OCC_TITLE IN ('";
					 foreach ($jobTitleData as $job)
					 {
						 $sqlNat .= $job . "','";
					 }
					 
					 $sqlNat .= "')";
					 
		 			try 
		 			{
		 					$db = new PDO("mysql:host=localhost;dbname=BLSdata","root","root");
		 			}	
		 				catch (Exception $e) 
		 					{
		 						echo "Could not connect to database.";
		 						exit;
		 					}
		
		 					$db->query($sqlNat);
							
							$createCSV = "SELECT 'AREA_TITLE', 'AREA_TYPE', 'OCC_TITLE', 'TOT_EMP', 'JOBS_1000', 'A_MEAN', 'A_PCT90'
								UNION ALL
								SELECT AREA_TITLE, 
								AREA_TYPE, 
								OCC_TITLE, 
								TOT_EMP, 
								JOBS_1000, 
								A_MEAN, 
								A_PCT90 FROM natTable INTO OUTFILE '/tmp/natTable.csv'
								FIELDS TERMINATED BY ','
								OPTIONALLY ENCLOSED BY '\"'";

							$createquery = mysql_query($createCSV, $link2);
}





// Create the ZIP file
$z = new ZipArchive(); 
    $z->open($fileName, ZipArchive::CREATE); 
    $z->addFile("/tmp/localTable.csv", "localTable.csv");
	$z->addFile("/tmp/msaTable.csv", "msaTable.csv");
	$z->addFile("/tmp/nmsaTable.csv", "nmsaTable.csv");
	$z->addFile("/tmp/stateTable.csv", "stateTable.csv");
	$z->addFile("/tmp/natTable.csv", "natTable.csv"); 
    $z->close(); 

	
	
	unlink("/tmp/localTable.csv");
	
	unlink("/tmp/msaTable.csv");
	
	unlink("/tmp/nmsaTable.csv");
	
	unlink("/tmp/stateTable.csv");
	
	unlink("/tmp/natTable.csv");
	
	
	
	readfile("BLSdata.zip");
	
	unlink("/tmp/BLSdata.zip");
	
	
	try 
	{
			$db = new PDO("mysql:host=localhost;dbname=BLSdata","root","root");
	}	
		catch (Exception $e) 
			{
				echo "Could not connect to database.";
				exit;
			}
			
			$sqlDropAll = "DROP TABLE IF EXISTS localTable, msaTable, nmsaTable, stateTable, natTable";
			
			$db->query($sqlDropAll);
	
	
	exit();
		
		
	

	
?>