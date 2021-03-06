<?php
include ("funcoes.php");
if (! authme ())
	logmein ();

$action = $_POST ['action'];

switch ($action) {
	case "adddata" :
		$tbdia = (! isset ( $_POST ['tbdia'] )) ? erro ( "Dia inválido", 4 ) : $_POST ['tbdia'];
		$tbmes = (! isset ( $_POST ['tbmes'] )) ? erro ( "Mes inválido", 4 ) : $_POST ['tbmes'];
		$tbano = (! isset ( $_POST ['tbano'] )) ? erro ( "Ano inválido", 4 ) : $_POST ['tbano'];
		
		$cmp = $_POST ['tbcmt'];
		if (strcmp ( $cmp, "" ) == 0)
			erro ( 'Voce deve entrar uma descrissão', 4 );
		
		$dia = strtotime ( "$tbmes/$tbdia/$tbano" );
		$fh = fopen ( $calfile, "a" );
		fwrite ( $fh, "$dia|$cmp\n" );
		fclose ( $fh );
		
		$datas = file ( $calfile );
		$fh = fopen ( $calfile, "w" );
		sort ( $datas );
		foreach ( $datas as $data )
			fwrite ( $fh, $data );
		fclose ( $fh );
		break;
	
	case 'deldata' :
		$d = array ();
		foreach ( $_POST as $i => $p )
			if (strncmp ( $i, "apaga_", 6 ) == 0)
				array_push($d, $p);
		
		if (count($d) === 0)
			erro ( "Nenhuma data informada para ser removida", 4 );

		if (!is_file ( $calfile ))
			erro ( "Não achei o arquivo $calfile", 4 );

		$in = file ( $calfile );
		$fh = fopen ( $calfile, "w" );
		
		$one = 0;
		foreach ( $in as $i ) {
			list ( $dia, $cmp ) = split ( "\|", rtrim ( $i ), 2 );
			if ( in_array($dia, $d) ) continue;
			fwrite ( $fh, "$i" );
			$one ++;
		}
		
		fclose ( $fh );
		if ($one == 0) unlink($calfile);
		break;
	
	default :
		erro ( 'Entrada inválida !', 4 );
		break;
}

aviso ( 'Edição de datas realizadas com sucesso !', 4 );
?>
