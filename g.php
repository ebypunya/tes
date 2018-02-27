<?php
include('config.inc.php'); include('shop.inc.php'); include('func.inc.php'); include('prod.func.inc.php'); include('session.inc.php'); include('secure.inc.php');
$menu = 'Produk';
$submenu = 2;
$backparam = '';
session_start();
if ($_SESSION['page'] == 'listprod') {
	$bvar = splitbackvar();
	$bvarname = array_keys($bvar);
	if (isset($bvar['pkat'])) {
		$bpk = explode(',',$bvar['pkat']);
	} else { $bpk = array(); }
} else {
	$bvar = array();
	$bvarname = array();
	$bpk = array();
}
$salah=array();
if (isset($_POST['Batal'])) { backprev(''); }

$qry = mysql_query('SELECT DISTINCT TAG FROM tags');
while ($produk_tags = mysql_fetch_assoc($qry)) {
    $all_tags[] = $produk_tags['TAG'];
}

if (isset($_POST['kode'])) { $kode = strtoupper($_POST['kode']); } else { $kode = ''; }
if (isset($_POST['kodebaru'])) { $kodebaru = strtoupper($_POST['kodebaru']); } else { $kodebaru = ''; }
if (isset($_POST['kodeasal'])) { $kodeasal = strtoupper($_POST['kodeasal']); } else { $kodeasal = ''; }
if (isset($_POST['nama'])) { $nama = $_POST['nama']; } else { $nama = ''; }
if (isset($_POST['merk'])) { $merk = $_POST['merk']; } else { $merk = ''; }
if (isset($_POST['tipe'])) { $tipe = $_POST['tipe']; } else { $tipe = ''; }
if (isset($_POST['mpn'])) { $mpn = $_POST['mpn']; } else { $mpn = ''; }
if (isset($_POST['status'])) { $status = $_POST['status']; } else { $status = ''; }
if (isset($_POST['stok'])) { $stok = $_POST['stok']; } else { $stok = ''; }
if (isset($_POST['status_lama'])) { $status_lama = $_POST['status_lama']; } else { $status_lama = ''; }
if (isset($_POST['stok_lama'])) { $stok_lama = $_POST['stok_lama']; } else { $stok_lama = ''; }
if (isset($_POST['statusstokhabis'])) { $statusstokhabis = $_POST['statusstokhabis']; } else { $statusstokhabis = ''; }
if (isset($_POST['berat'])) { $berat = $_POST['berat']; } else { $berat = ''; }
if (isset($_POST['harga'])) { $harga = $_POST['harga']; } else { $harga = ''; }
if (isset($_POST['harlist'])) { $harlist = $_POST['harlist']; } else { $harlist = ''; }
if (isset($_POST['specsumm'])) { $specsumm = $_POST['specsumm']; } else { $specsumm = ''; }
if (isset($_POST['spec'])) { $spec = $_POST['spec']; } else { $spec = ''; }
if (isset($_POST['sekilas'])) { $sekilas = $_POST['sekilas']; } else { $sekilas = ''; }
if (isset($_POST['tags'])) { $tags = $_POST['tags']; } else { $tags = array(); }
if (isset($_POST['gambar'])) { $gambar = $_POST['gambar']; } else { $gambar = ''; }
if (isset($_POST['il'])) { $il = $_POST['il']; } else { $il = 0; }
if (isset($_POST['meta_desc'])) { $meta_desc = $_POST['meta_desc']; } else { $meta_desc = ''; }
if (isset($_POST['du'])) { $du = $_POST['du']; } else { $du = ''; }
if (isset($_POST['show'])) { $show = $_POST['show']; } else { $show = 0; }
if (isset($_POST['show_feed'])) { $show_feed = $_POST['show_feed']; } else { $show_feed = 1; }
if (isset($_POST['pilihan'])) { $pilihan = $_POST['pilihan']; } else { $pilihan = 0; }
if (isset($_POST['scoremultiplier'])) { $scoremultiplier = $_POST['scoremultiplier']; } else { $scoremultiplier = 0; }
if (isset($_POST['ean'])) { $ean = $_POST['ean']; } else { $ean = ''; }
if (isset($_POST['cicilan'])) { $cicilan = $_POST['cicilan']; } else { $cicilan = array(); }
if (isset($_POST['pk1'])) { $pk1 = $_POST['pk1']; } else { $pk1 = array(); }
if (isset($_POST['pk2'])) { $pk2 = $_POST['pk2']; } else { $pk2 = array(); }
if (isset($_POST['pk3'])) { $pk3 = $_POST['pk3']; } else { $pk3 = array(); }
$stokpopgroup = '';
$pk = array_merge($pk1,$pk2,$pk3);
if (isset($_GET['prodid'])) {
	$prodid = $_GET['prodid'];
	if (prod_ada($_GET['prodid'])) {
		$sql = 'SELECT *,UNIX_TIMESTAMP(ADDON) AS AOUNIX,UNIX_TIMESTAMP(UPDATED) AS UPUNIX  FROM produk WHERE KODE = '. dbaman($prodid);
		$hsl = @mysql_query($sql);
		if ($hsl) { 
			$row = @mysql_fetch_array($hsl);
			$kode = $row['KODE'];
			$kodeasal = $kode;
			$nama = $row['NAMA'];
			$merk = $row['MERK'];
			$tipe = stripslashes($row['TIPE']);
			$mpn = $row['MPN'];
			$status = $row['STATUS'];
			$stok = $row['STOK'];
			$statusstokhabis = $row['IFSTOKHABIS'];
			$berat = $row['BERAT'];
			$harga = $row['HARGA'];
			$harlist = $row['HARLIST'];
			$specsumm = $row['SPECSUMM'];
			$spec = $row['SPEC'];
			$sekilas = $row['SEKILAS'];
			$gambar = $row['GAMBAR'];
            $il = $row['IL'];
            $meta_desc = $row['META_DESC'];
			$show = $row['SH'];
            $show_feed = $row['SHOW_FEED'];
			$pilihan = $row['PILIHAN'];
			$scoremultiplier = $row['MSCORE'];
			$ean = $row['EAN'];
			$addon = $row['AOUNIX'];
			$updated = $row['UPUNIX'];
            $du = $row['DU'];
			$sql2 = 'SELECT produkat.KAT,kat.LEVEL FROM produkat,kat WHERE produkat.KAT = kat.KAT AND KODE = "'.$row['KODE'].'"';
			$hsl2 = @mysql_query($sql2);
			if ($hsl2) {
				while ($row2 = mysql_fetch_array($hsl2)) {
					$level = $row2['LEVEL'];					
					$pk[$level-1] = $row2['KAT'];
					switch ($level) {
						case '1' : $pk1[] = $row2['KAT']; break;
						case '2' : $pk2[] = $row2['KAT']; break;
						case '3' : $pk3[] = $row2['KAT']; break;					
					}
				}
			}
			$sql3 = 'SELECT produkcicilan.CICILAN FROM produkcicilan WHERE KODE = "'.$row['KODE'].'"';		
			$hsl3 = @mysql_query($sql3);
			if ($hsl3) {
				while ($row3 = mysql_fetch_array($hsl3)) {
					$cicilan[] = $row3['CICILAN'];					
				}
			}

			$sql4 = @mysql_query('SELECT * FROM produk_referensi WHERE KODELAMA = '.dbaman($row['KODE']).' AND STATUS = "DC"');		
			$row4 = mysql_fetch_array($sql4);
			$kodebaru = $row4['KODEBARU'];

            $qry = mysql_query('SELECT * FROM produk_tags WHERE KODE = '. dbaman($row['KODE']));
            while ($produk_tags = mysql_fetch_assoc($qry)) {
                $tags[] = $produk_tags['TAG'];
            }

            // Stock Popgroup API
            $stokapi = getPopGroupStock($_GET['prodid']);
            if ($stokapi != false) { 
            	$stokpopgroup = json_decode($stokapi, true);
            } else { 
            	$stokpopgroup = '';
            }

            // Stock Transaksi
            $stoktransaksi = getStokProdukInvoice($_GET['prodid']);

            // Ambil Kode Bunding
            $kodebundling = getKodeBundling($_GET['prodid']);   
		}
	} else {
		backprev('salah=30');
	}	
} elseif (isset($_POST['Edit'])) {
 	if ((empty($kode)) or (!ctype_alnum($kode))) { $salah[]=1;}
	if (empty($kodeasal)) { $salah[]=1;}
	if ((prod_ada($kode)) and ($kode != $kodeasal)) {$salah[]=2;}
 	if (empty($nama)) { $salah[]=3; }
 	if (empty($merk))  { $salah[]=4; }
 	if (empty($tipe))  { $salah[]=5; }
    if (empty($status)) {
        $salah[] = 6;
    } else {
        if($status == 'KS' or $status == 'OB') {
            if ($stok != 0) {
                $salah[] = 16;
            }
            if ($status == 'OB' and $kodebaru != '') {
                if (!prod_ada($kodebaru) or $kodebaru == $kode) {
                    $salah[] = 17;
                }
            }
        } else {
            if ($stok == 0) {
                $salah[] = 16;
            }
        }
    }

 	if ($stok == "")  { $stok=-1; }
 	if (empty($statusstokhabis))  { $salah[]=15; }
 	if ((empty($berat)) or (!is_numeric($berat)))  { $salah[]=7; }
 	if ((empty($harga)) or (!is_numeric($harga)))  { $salah[]=8; }
 	if (empty($spec))  { $salah[]=9; }
	if (count($pk1) == 0 )  { $salah[]=11; }
	if (count($pk2) == 0 )  { $salah[]=12; }
	if (!is_numeric($harga)) { $salah[]=14; }
	//if (count($pk3) == 0 )  { $salah[]=13; }
	$sqlharlist = (empty($harlist)) ? ' produk.HARLIST = NULL ,' : '	produk.HARLIST = '. dbaman($harlist) .', ';
	if (count($salah) == 0) {
        // Notifikasi Produk Ready
        $sql_statuslama = @mysql_query('SELECT `STATUS` FROM `produk` WHERE KODE = ' . dbaman($kode));
        $statuslama = mysql_fetch_assoc($sql_statuslama);
        $unreadystatus = array("R1", "R2", "R3", "KF", "KS");
        if (in_array($statuslama['STATUS'], $unreadystatus) and $status == '24') {
            $hsl = @mysql_query("SELECT *
						 FROM produk_notifikasi
						 JOIN produk ON produk.KODE = produk_notifikasi.KODE
						 WHERE produk_notifikasi.KODE = " . dbaman($kode) . "
						 AND produk_notifikasi.STATUS = 0");
            while ($baris = mysql_fetch_assoc($hsl)) {
                $subject = 'Pemberitahuan Ketersediaan Produk - ' . deskbrg($baris['NAMA'], $baris['MERK'], $baris['TIPE'], 0);
                ob_start();
                include('email_produk_notifikasi.php');
                $contents = ob_get_clean();

                require_once('lib/PHPMailer-master/PHPMailerAutoload.php');
                $mail = new PHPMailer;

                include('config.smtp.php');
                $mail->AddReplyTo('noreply@perkakasku.com');
                $mail->SetFrom('noreply@perkakasku.com', 'Perkakasku.com');
                $mail->AddAddress($baris['EMAIL']);

                $mail->AddEmbeddedImage("image/perkakasku.png", "perkakasku-logo", "image/perkakasku.png");

                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $contents;
                if ($mail->send()) {
                    @mysql_query("UPDATE `produk_notifikasi` SET `STATUS` = '1', NOTIF_EMAIL_DATE = NOW() WHERE KODE = " . dbaman($kode) . " AND EMAIL = " . dbaman($baris['EMAIL']));
                }
            }
		}
		
		$updatekode = '';
		if ($kode != $kodeasal) {
			$updatekode = 'produk.KODE = '. dbaman($kode) .',';
		}

		$sql = 'UPDATE produk SET '.
				 '	'.$updatekode.' '.
				 '	produk.NAMA = '. dbaman($nama) .', '.
				 '	produk.MERK = '. dbaman($merk) .', '.
				 '	produk.TIPE = '. dbaman($tipe) .', '.
				 '	produk.MPN = '. dbaman($mpn). ', '.
				 '	produk.STATUS = '. dbaman($status) .', '.
				 '	produk.STOK = '. dbaman($stok). ', '.
				 '	produk.IFSTOKHABIS = '. dbaman($statusstokhabis) .', '.
				 '	produk.BERAT = '. dbaman($berat) .', '.$sqlharlist.
				 '	produk.HARGA = '. dbaman($harga) .', '.
				 '	produk.SPECSUMM = '. dbaman($specsumm) .', '.
				 '	produk.SPEC = '. dbaman($spec) .', '.
				 '	produk.SEKILAS = '. dbaman($sekilas) .', '.
				 '	produk.SH = '. dbaman($show) .', '.
                 '	produk.SHOW_FEED = '. dbaman($show_feed) .', '.
				 '	produk.PILIHAN = '. dbaman($pilihan).', '.
				 '	produk.MSCORE = '. dbaman($scoremultiplier).', '.
				 '	produk.EAN = '. dbaman($ean).', '.
				 '	produk.GAMBAR = '. dbaman($gambar) .', '.
                 '	IL = '.dbaman($il).','.
                 '	META_DESC = '.dbaman($meta_desc).','.
                 '	produk.DU = '. dbaman($du) .', '.
				 '	produk.UPDATED = NOW() '.
			   ' WHERE produk.KODE = '. dbaman($kodeasal);
		$hsl = @mysql_query($sql);
		if ($hsl) {
            // Hapus spec lama & insert spec baru
            $sqlspec = 'DELETE FROM `specprod` WHERE `KODE` = '.dbaman($kode).'';
            $queryspec = @mysql_query($sqlspec);
            if ($queryspec) {
            	$newspec = array();
		        $newspec = splitspec($spec);
		        foreach ($newspec as $key=>$val) {
		            mysql_query('INSERT INTO `specprod` SET `KODE` = '.dbaman($kode).', `SPEC` = '.dbaman($key).', `VALUE`= '.dbaman(trim($val)).'');
		        }
            }

            // buat log jika status / stok berubah
            if (($status_lama != $status) OR ($stok_lama != $stok)) {
                $logproduk = array('kode'=>$kode, 
                                   'update_stok'=> $stok,
                                   'update_status'=> $status,
                                   'stok_awal'=>$stok_lama,
                                   'status_awal'=>$status_lama,
                                   'note'=>'Update Stok / Status');
                insertLogProduk($logproduk);
            }

			if ($kode != $kodeasal) {
				$backparam = 'kode='. $kode;
				if(!coderefprod($kodeasal)) {
					$sqlref = "INSERT INTO produk_referensi (KODEBARU, KODELAMA, STATUS) VALUES (".dbaman($kode).",".dbaman($kodeasal).",'R')";
					@mysql_query($sqlref);
				} else {
					$sqlref = "UPDATE produk_referensi SET KODEBARU = ".dbaman($kode).", KODELAMA = ".dbaman($kodeasal)." WHERE KODELAMA = ".dbaman($kodeasal) .' AND STATUS = "R"';
					@mysql_query($sqlref);
				}
			}
            if ($status == 'OB') {
                if ($kodebaru != '') {
                    if (!discontinuecodeprod($kode)) {
                        $sqlref = "INSERT INTO produk_referensi (KODEBARU, KODELAMA, STATUS) VALUES (" . dbaman($kodebaru) . "," . dbaman($kodeasal) . ",'DC')";
                        @mysql_query($sqlref);
                    } else {
                        $sqlref = "UPDATE produk_referensi SET KODEBARU = " . dbaman($kodebaru) . ", KODELAMA = " . dbaman($kode) . " WHERE KODELAMA = " . dbaman($kodeasal) .' AND STATUS = "DC"';
                        @mysql_query($sqlref);
                    }
                } else {
                    mysql_query('DELETE FROM produk_referensi WHERE KODELAMA = '. dbaman($kodeasal) .' AND STATUS = "DC"');
                }
                $qry = @mysql_query("SELECT *
						 FROM produk_notifikasi
						 JOIN produk ON produk.KODE = produk_notifikasi.KODE
						 WHERE produk_notifikasi.KODE = " . dbaman($kode) . "
						 AND produk_notifikasi.STATUS = 0");
                while ($prod_notif = mysql_fetch_assoc($qry)) {
                    ob_start();
                    include('email_produk_notifikasi_disc.php');
                    $contents = ob_get_clean();

                    require_once('lib/PHPMailer-master/PHPMailerAutoload.php');
                    $mail = new PHPMailer;

                    include('config.smtp.php');
                    $mail->AddReplyTo('noreply@perkakasku.com');
                    $mail->SetFrom('noreply@perkakasku.com', 'Perkakasku.com');
                    $mail->AddAddress($prod_notif['EMAIL']);

                    $mail->AddEmbeddedImage("image/perkakasku.png", "perkakasku-logo", "image/perkakasku.png");

                    $mail->isHTML(true);
                    $mail->Subject = 'Pemberitahuan Produk Discontinue - ' . deskbrg($prod_notif['NAMA'], $prod_notif['MERK'], $prod_notif['TIPE'], 0);
                    $mail->Body = $contents;
                    if ($mail->send()) {
                        @mysql_query("UPDATE `produk_notifikasi` SET `STATUS` = '1', NOTIF_EMAIL_DATE = NOW() WHERE KODE = " . dbaman($kode) . " AND EMAIL = " . dbaman($prod_notif['EMAIL']));
                    }
                }
            }

            if ($kode != $kodeasal) {
	            mysql_query('UPDATE ads_tracking SET `value` = '. dbaman($kode) .' WHERE `value` = '. dbaman($kodeasal));
	            mysql_query('UPDATE evoucher SET policy = '. dbaman($kode) .' WHERE policy = '. dbaman($kodeasal));
	            mysql_query('UPDATE opsiproduk SET NILAI = '. dbaman($kode) .' WHERE NILAI = '. dbaman($kodeasal));
	            mysql_query('UPDATE prod_comments SET comment_prodid = '. dbaman($kode) .' WHERE comment_prodid = '. dbaman($kodeasal));
            	$qry = mysql_query('SELECT DISTINCT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE COLUMN_NAME IN ("KODE") AND TABLE_NAME NOT IN ("paygate", "perinfo", "pranala")');
	            while($tableEmail = mysql_fetch_array($qry)) {
	                mysql_query('UPDATE '. $tableEmail['TABLE_NAME'] .' SET '. $tableEmail['COLUMN_NAME'] .' = '.dbaman($kode).' WHERE '. $tableEmail['COLUMN_NAME'] .' = '.dbaman($kodeasal));
	            }
				$sql2 = 'DELETE FROM produkat WHERE KODE = '. dbaman($kode);
				@mysql_query($sql2);
				$pk = array_merge($pk1,$pk2,$pk3);
				foreach ($pk as $kateg) {
					$sql3 = 'INSERT INTO produkat SET '.
							'  KODE = '. dbaman($kode).', '.
							'  KAT = "'.$kateg.'";';
					@mysql_query($sql3);
				}
				$sql4 = 'DELETE FROM produkcicilan WHERE KODE = '. dbaman($kode);
				@mysql_query($sql4);
				foreach ($cicilan as $cil) {
					$sql5 = 'INSERT INTO produkcicilan SET '.
							'  KODE = '. dbaman($kode) .', '.
							'  CICILAN = "'.$cil.'";';
					@mysql_query($sql5);
				}

	            mysql_query('DELETE FROM produk_tags WHERE KODE = '. dbaman($kodeasal));
	            foreach ($tags as $tag) {
	                mysql_query('INSERT INTO produk_tags SET KODE = '. dbaman($kode) .', TAG = '. dbaman($tag));
	            }
	        }

			backprev($backparam);
		}
	}
} else { backprev(''); }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Perkakasku.Produk : Edit Produk</title>
    <link rel="shortcut icon" href="favicon.ico"/>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/jquery.fileupload.css">
    <link href="perkakasku.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="perkakasku.js"></script>
    <script type="text/javascript" src="getchild.js"></script>

    <script type="text/javascript" src="js/jquery-2.1.1.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>

    <script type="text/javascript" src="editor/ckeditor/ckeditor.js"></script>

    <link href="css/select2.min.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="js/select2.full.min.js"></script>

    <!--[if lt IE 7.]>
    <script defer type="text/javascript" src="pngfix.js"></script>
    <![endif]-->
    <script type="text/javascript">
        $(document).ready(function() {
            $("#tags").select2();

            $("#status").change(function () {
                if ($(this).val() == 'OB') {
                    $("#newcode").show();
                } else {
                    $("#newcode").hide();
                }
            });

            $('#all').click(function () {
                if($("#all").is(':checked')) {
                    $('.cil3').prop('checked', true);
                    $('.cil6').prop('checked', true);
                    $('.cil12').prop('checked', true);
                    $('#all3').prop('checked', true);
                    $('#all6').prop('checked', true);
                    $('#all12').prop('checked', true);
                } else {
                    $('.cil3').prop('checked', false);
                    $('.cil6').prop('checked', false);
                    $('.cil12').prop('checked', false);
                    $('#all3').prop('checked', false);
                    $('#all6').prop('checked', false);
                    $('#all12').prop('checked', false);
                }
            });
            $('#all3').click(function () {
                if($("#all3").is(':checked')) {
                    $('.cil3').prop('checked', true);
                } else {
                    $('.cil3').prop('checked', false);
                }
            });
            $('#all6').click(function () {
                if($("#all6").is(':checked')) {
                    $('.cil6').prop('checked', true);
                } else {
                    $('.cil6').prop('checked', false);
                }

            });
            $('#all12').click(function () {
                if($("#all12").is(':checked')) {
                    $('.cil12').prop('checked', true);
                } else {
                    $('.cil12').prop('checked', false);
                }
            });
            $('#status').change(function () {
                if ($(this).val() == 'KS' || $(this).val() == 'OB') {
                    if ($('#stok').val() != 0) {
                        $('#stok').val(0);
                    }
                } else {
                    if ($('#stok').val() == 0) {
                        $('#stok').val(-1);
                    }
                }
            });
        });
    </script>
</head>

<body>
<div id="bungkus">
<?php include ('topmenu.php'); ?>
<div id="isihal">
<div id="navkiri">
			<table width="170px" cellpadding="0" cellspacing="0" class="cat">
				<tr>
					<td class="catmenusubhead">Menu Produk</td>
				</tr>
				<tr>
					<td>
						<div id="leftmenu"><ul>
<?php for($i=0;$i<count($submenuref);$i++) {
echo '						<li><a href="';
if (($submenu != 0) and ($i==0)) { 
	echo ($_SESSION['page'] == 'listprod') ? backurlwosid('') : 'listprod.php';
} else {
	if ($i == 3) {
		echo 'hpprod.php?prodid='.$prodid;	
	} else {
		echo $submenuref[$i][1];
	}
}
echo '"';
echo ($i == $submenu) ? ' class="selected"' : '';
echo '>'.$submenuref[$i][0].'</a></li>'."\n";
} ?>
						</ul></div>
					</td>
				</tr>
				<tr>
					<td class="catmenusubhead">Cari Produk</td>
				</tr>
				<tr><td>
						<form action="listprod.php" method="post" autocomplete="off">
						<table width="100%" border="0"  cellpadding="0" cellspacing="0">
						  <tr>
							<td class="loc" style="font-size: x-small;padding: 0px 2px">Kode</td>
							<td class="fieldtext"><input name="kode" type="text" value="<?php if (in_array('kode',$bvarname)) { echo stripslashes(htmlspecialchars($bvar['kode'])); } ?>" class="fieldtext" size="15"></td>
						  </tr>
						  <tr>
							<td class="loc" style="font-size: x-small;padding: 0px 2px">Nama</td>
							<td class="fieldtext"><input name="nama" type="text" value="<?php if (in_array('nama',$bvarname)) { echo stripslashes(htmlspecialchars($bvar['nama'])); } ?>" class="fieldtext" size="15"></td>
						  </tr>
						  <tr>
							<td class="loc" style="font-size: x-small;padding: 0px 2px">Merk</td>
							<td class="fieldtext"><?php 
							if (isset($_SESSION['merklist'])) { 
								if (in_array('merk',$bvarname)) {
									merkmenubyarray('merk',$_SESSION['merklist'],$bvar['merk']); 
								} else {
									merkmenubyarray('merk',$_SESSION['merklist'],''); 
								}
							} else { merkmenu('merk','SELECT MERK FROM PRODUK',''); }?></td>
						  </tr>
						  <tr>
							<td class="loc" style="font-size: x-small;padding: 0px 2px">Tipe</td>
							<td class="fieldtext"><input name="tipe" type="text" value="<?php if (in_array('tipe',$bvarname)) { echo stripslashes(htmlspecialchars($bvar['tipe'])); } ?>" class="fieldtext" size="15"></td>
						  </tr>
						  <tr>
							<td class="loc" style="font-size: x-small;padding: 0px 2px">Show</td>
							<td class="fieldtext"><select name="show" class="fieldtext">
								<option value="0" <?php if ((in_array('show',$bvarname)) and ($bvar['show'] == 0)) { echo 'selected'; } ?>>Ya</option>
								<option value="1" <?php if ((in_array('show',$bvarname)) and ($bvar['show'] == 1)) { echo 'selected'; } ?>>Tidak</option>
								<option value="2" <?php if ((in_array('show',$bvarname)) and ($bvar['show'] == 2)) { echo 'selected'; } ?>>Semua</option>
							  </select></td>
						  </tr>
						  <tr>
							<td class="loc" style="font-size: x-small;padding: 0px 2px">Urutan</td>
							<td class="fieldtext"><select name="urutan" class="fieldtext">
								<option value="kode"<?php if ((in_array('urutan',$bvarname)) and ($bvar['urutan'] == 'kode')) { echo ' selected'; } ?>>Kode</option>
								<option value="nama"<?php if ((in_array('urutan',$bvarname)) and ($bvar['urutan'] == 'nama')) { echo ' selected'; } ?>>Nama</option>
								<option value="harga"<?php if ((in_array('urutan',$bvarname)) and ($bvar['urutan'] == 'harga')) { echo ' selected'; } ?>>Harga</option>
							  </select></td>
						  </tr>
						  <tr>
							<td class="loc" style="font-size: x-small;padding: 0px 2px">Ascdsc</td>
							<td class="fieldtext"> <select name="ascdesc" class="fieldtext">
							  <option value="asc"<?php if ((in_array('ascdesc',$bvarname)) and ($bvar['ascdesc'] == 'asc')) { echo ' selected'; } ?>>Ascending</option>
							  <option value="dsc"<?php if ((in_array('ascdesc',$bvarname)) and ($bvar['ascdesc'] == 'dsc')) { echo ' selected'; } ?>>Descending</option>
							</select></td>
						 </tr>
						  <tr>
								<td colspan="2" class="loc" style="font-size: x-small;padding: 0px 2px">Kategori 1 </td>
						 </tr>
						  <tr>
								<td colspan="2" class="fieldtext"><?php katmenu('pk',1,1,FALSE,$bpk); ?>	</td>   		
						  </tr>
						  <tr>
								<td colspan="2" class="loc" style="font-size: x-small;padding: 0px 2px">Kategori 2 </td>
						 </tr>
						  <tr>
								<td colspan="2" class="fieldtext"><?php katmenu('pk',2,1,FALSE,$bpk); ?></td>
						  </tr>
						  <tr>
								<td colspan="2" class="loc" style="font-size: x-small;padding: 0px 2px">Kategori 3 </td>
						 </tr>
						  <tr>
								<td colspan="2" class="fieldtext"><?php katmenu('pk',3,1,FALSE,$bpk); ?></td>
						  </tr>
						  <tr align="center">
							<td colspan="2" class="fieldtext"><input name="Cari" type="submit" class="button" value="Cari"></td>
						  </tr>
						</table>
						</form>
					</td>
				</tr>
			</table>
		</div>
<div id="isinya">
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" autocomplete="off">
			<table width="100%" border="0"  cellpadding="0" cellspacing="0">
			  <tr>
				<td colspan="2" class="profilhead">Edit Produk</td>
			  </tr>
              <tr>
                  <td colspan="2" style="text-align: right; font-size: small">
                      <a href="edprod_image.php?prodid=<?= $prodid ?>" class="reflink">Image</a> |
                      <a href="edprod_marketplace.php?prodid=<?= $prodid ?>" class="reflink">Marketplace</a>
                      <?php if (highAuthOnly($_SESSION['LN'])) { ?>
                          | <a href="statistic_product.php?prodid=<?= $prodid ?>" class="reflink">Statistik</a>
                      <?php } ?> 
                      <?php if (highAuthOnly($_SESSION['LN'])) { ?>
                          | <a href="edprod_harga.php?prodid=<?= $prodid ?>" class="reflink">Harga Kompetitor</a>
                      <?php } ?>
                      | <a href="edprod_log.php?prodid=<?= $prodid ?>" class="reflink">Log</a>
                  </td>
              </tr>
			  <tr>
				<td width="25%" class="profil" style="font-weight:bold">Kode Produk</td>
				<td width="75%" class="profil"><input name="kode" class="fieldtext" type="text" id="kode" value="<?php echo $kode; ?>" size="10" maxlength="10">
				<input name="kodeasal" type="hidden" value="<?php echo $kodeasal; ?>">
				<?php if ((in_array(1,$salah)) or (in_array(2,$salah))) { echo '*'; } ?></td>
			  </tr>
			  <tr>
				<td class="profil" style="font-weight:bold">Nama Produk</td>
				<td class="profil"><input name="nama" class="fieldtext" type="text" value="<?php echo stripslashes(htmlspecialchars($nama)); ?>" size="45">
				<?php if (in_array(3,$salah)) { echo '*'; } ?></td>
			  </tr>
			  <tr>
				<td class="profil" style="font-weight:bold">Merk</td>
				<td class="profil"><input name="merk" id="merk" class="fieldtext" type="text" value="<?php echo stripslashes(htmlspecialchars($merk)) ?>" size="25">
				<?php if (in_array(4,$salah)) { echo '*'; } ?></td>
			  </tr>  
			  <tr>
				<td class="profil" style="font-weight:bold">Tipe</td>
				<td class="profil"><input name="tipe" class="fieldtext" type="text" value="<?php echo stripslashes(htmlspecialchars($tipe)); ?>" size="25">
				<?php if (in_array(5,$salah)) { echo '*'; } ?></td>
			  </tr>  
			  <tr>
				<td class="profil" style="font-weight:bold">Mfg P/N</td>
				<td class="profil"><input name="mpn" class="fieldtext" type="text" value="<?php echo $mpn; ?>" size="25">
				</td>
			  </tr>
			  <tr>
				<td class="profil" style="font-weight:bold">EAN / Barcode</td>
				<td class="profil"><input name="ean" class="fieldtext" type="text"value="<?php echo stripslashes(htmlspecialchars($ean)); ?>" size="50">
				</td>
			  </tr>  
			  <tr>
				<td colspan="2" class="profil">
				<table width="100%">
					<tr align="left">
						<td style="font-weight:bold" width="33%">Kategori 1 <?php if (in_array(11,$salah)) { echo '*'; } ?></td>
						<td style="font-weight:bold" width="33%">Kategori 2 <?php if (in_array(12,$salah)) { echo '*'; } ?></td>
						<td style="font-weight:bold" width="33%">Kategori 3 <?php if (in_array(13,$salah)) { echo '*'; } ?></td>
					</tr>
					<tr valign="top" align="left">
						<td id="child1"><?php katmn('pk1',1,$pk); ?></td>
						<td id="child2"><?php if (count($pk1) != 0 )  { katmn('pk2',2,$pk); } ?></td>
						<td id="child3"><?php if (count($pk2) != 0 )  { katmn('pk3',3,$pk); } ?></td>		
					</tr>
				</table>
				</td>
			  </tr>
			  <tr>
				<td class="profil" style="font-weight:bold">Status</td>
				<td class="profil"><select name="status" class="fieldtext" id="status">
						<?php foreach ($kstatprod as $ksp) { ?>
						<option value="<?php echo $ksp; ?>" <?php if ($status == $ksp) { echo 'selected'; } ?>><?php echo $statprod[$ksp]; ?></option>
						<?php } ?>
					</select><?php if (in_array(6,$salah)) { echo '*'; } ?></td>
			  </tr>  
			  <tr id="newcode" <?php if ($status != 'OB') { ?> style="display:none;" <?php } ?>>
				<td class="profil" style="font-weight:bold">Kode Produk Pengganti</td>
				<td class="profil">
                    <input name="kodebaru" class="fieldtext" id="kodebaru" value="<?php echo isset($kodebaru) ? $kodebaru : ''; ?>">
                    <?php if (in_array(17,$salah)) { echo '*'; } ?>
                </td>
			  </tr>  
			  <tr>
				<td class="profil" style="font-weight:bold">Stok</td>
				<td class="profil">
					<input id="stok" name="stok" class="fieldtext" type="text" value="<?php echo $stok; ?>" size="10">
                    <?php if (in_array(16,$salah)) { echo '*'; } ?>
				</td>
			  </tr>
			  <?php if ($stoktransaksi != false) { ?>
			  	<tr>
					<td class="profil" style="font-weight:bold">Stok Transaksi</td>
					<td class="profil">
						<table width="55%" style="border: 1px solid #e6e6e6"> 
							<tr style="border: 1px solid #e6e6e6"> 
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Tunggu Bayar</td>
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Dalam Proses</td>
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Total</td>
							</tr>
							<tr style="border: 1px solid #e6e6e6"> 
								<td style="border: 1px solid #e6e6e6; text-align: center;">
									<?php
									if ($stoktransaksi['TUNGGU_BAYAR'] > 0) {
										echo '<a class="reflink" href="listcinv.php?email=&noinv=&status=B&from=&until=&kode='.$stoktransaksi['KODE'].'&search_refund=&urutan=tglinv&ascdesc=dsc&Cari=Cari">'.$stoktransaksi['TUNGGU_BAYAR'].'</a>';
									} else {
										echo $stoktransaksi['TUNGGU_BAYAR'];
									}
									?>
								</td>
								<td style="border: 1px solid #e6e6e6; text-align: center;">
									<?php
									if ($stoktransaksi['PROSES'] > 0) {
										echo '<a class="reflink" href="listcinv.php?email=&noinv=&status=P&from=&until=&kode='.$stoktransaksi['KODE'].'&search_refund=&urutan=tglinv&ascdesc=dsc&Cari=Cari">'.$stoktransaksi['PROSES'].'</a>';
									} else {
										echo $stoktransaksi['PROSES'];
									}
									?>
								</td>
								<td style="border: 1px solid #e6e6e6; text-align: center;"><?= ($stoktransaksi['PROSES'] + $stoktransaksi['TUNGGU_BAYAR']) ?></td>
							</tr>
						</table>
					</td>
			  	</tr> 
			  <?php } ?>
			  <tr>
				<td class="profil" style="font-weight:bold">Stok Pop Group</td>
				<td class="profil">
					<?php if ($stokpopgroup != '') { ?>
						<table width="55%" style="border: 1px solid #e6e6e6"> 
							<tr style="border: 1px solid #e6e6e6"> 
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Stok MKN</td>
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Stok PMC</td>
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Total</td>
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Stock Others</td>
							</tr>
							<tr style="border: 1px solid #e6e6e6"> 
								<td style="border: 1px solid #e6e6e6; text-align: center;"><?= $stokpopgroup[0]['data'][0]['STOCKMKN'] ?></td>
								<td style="border: 1px solid #e6e6e6; text-align: center;"><?= $stokpopgroup[0]['data'][0]['STOCKPMC'] ?></td>
								<td style="border: 1px solid #e6e6e6; text-align: center;"><?= $stokpopgroup[0]['data'][0]['STOCK'] ?></td>
								<td style="border: 1px solid #e6e6e6; text-align: center;"><?= $stokpopgroup[0]['data'][0]['STOCKOTH'] ?></td>
							</tr>
						</table>
					<?php } else { ?>
					- 
					<?php } ?>
				</td>
			  </tr> 
			  <tr>
				<td class="profil" style="font-weight:bold">Stok Pop Group (Bundling)</td>
				<td class="profil">
					<?php if ($kodebundling != false) { ?>
						<table width="55%" style="border: 1px solid #e6e6e6"> 
							<tr style="border: 1px solid #e6e6e6"> 
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Kode</td>
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Stok MKN</td>
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Stok PMC</td>
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Total</td>
								<td style="border: 1px solid #e6e6e6;text-align: center;font-weight: 700;">Stock Others</td>
							</tr>
							<?php $hargabundling = 0; $hargabundlingrata2 = 0; 
								foreach ($kodebundling as $kodeprod) { ?>
								<?php 
								$stokpopgroupbundlings = getPopGroupStock($kodeprod); 
								if ($stokpopgroupbundlings != false) { 
					            	$stokpopgroupbundling = json_decode($stokpopgroupbundlings, true);
					            } else { 
					            	$stokpopgroupbundling = '';
					            }
								?> 
                       
								<?php if ($stokpopgroupbundling != '') { $hargabundling += $stokpopgroupbundling[0]['data'][0]['HargaBeliAkhir']; $hargabundlingrata2 += $stokpopgroupbundling[0]['data'][0]['HargaBeliRataRata']; ?>
									<tr style="border: 1px solid #e6e6e6"> 
										<td style="border: 1px solid #e6e6e6; text-align: center;"><a href="edprod.php?prodid=<?= $kodeprod ?>"><?= $kodeprod ?></a></td>
										<td style="border: 1px solid #e6e6e6; text-align: center;"><?= $stokpopgroupbundling[0]['data'][0]['STOCKMKN'] ?></td>
										<td style="border: 1px solid #e6e6e6; text-align: center;"><?= $stokpopgroupbundling[0]['data'][0]['STOCKPMC'] ?></td>
										<td style="border: 1px solid #e6e6e6; text-align: center;"><?= $stokpopgroupbundling[0]['data'][0]['STOCK'] ?></td>
										<td style="border: 1px solid #e6e6e6; text-align: center;"><?= $stokpopgroupbundling[0]['data'][0]['STOCKOTH'] ?></td>
									</tr>
								<?php } else { ?>
									<tr style="border: 1px solid #e6e6e6"> 
										<td style="border: 1px solid #e6e6e6; text-align: center;"><a href="edprod.php?prodid=<?= $kodeprod ?>"><?= $kodeprod ?></a></td>
										<td style="border: 1px solid #e6e6e6; text-align: center;">-</td>
										<td style="border: 1px solid #e6e6e6; text-align: center;">-</td>
										<td style="border: 1px solid #e6e6e6; text-align: center;">-</td>
									</tr>
								<?php } ?>
							<?php } ?>
						</table>
					<?php } else { ?>
						-
					<?php } ?>
				</td>
			  </tr> 
			  <tr>
				<td class="profil" style="font-weight:bold">Status Jika Stok Habis</td>
				<td class="profil"><select name="statusstokhabis" class="fieldtext">
						<?php foreach ($kstatprod as $ksp) { ?>
						<option value="<?php echo $ksp; ?>" <?php if ($statusstokhabis == $ksp) { echo 'selected'; } ?>><?php echo $statprod[$ksp]; ?></option>
						<?php } ?>
					</select><?php if (in_array(15,$salah)) { echo '*'; } ?></td>
			  </tr> 
			  <tr>
				<td class="profil" style="font-weight:bold">Berat</td>
				<td class="profil"><input name="berat" class="fieldtext" type="text"value="<?php echo $berat; ?>" size="10"> kg 
				<?php if (in_array(7,$salah)) { echo ' *'; } ?></td>
			  </tr>  
			  <tr>
				<td class="profil" style="font-weight:bold">Harga</td>
				<td class="profil">Rp. <input name="harga" class="fieldtext" type="text"value="<?php echo $harga; ?>" size="15"> 
				<?php if (in_array(8,$salah)) { echo '*'; } ?></td>
			  </tr>
			  <tr>
				<td class="profil" style="font-weight:bold">Harga List</td>
				<td class="profil">Rp. <input name="harlist" class="fieldtext" type="text"value="<?php echo $harlist; ?>" size="15"> 
				<?php if (in_array(14,$salah)) { echo '*'; } ?>
				</td>
			  </tr>
			  <?php 
			  $otoritas = uo($_SESSION['LN']);
			  if ($otoritas == 'O' && $stokpopgroup != '') {
			  ?>
			  	<?php if ($hargabundling > 0) { ?>
			  	<tr>
					<td class="profil" style="font-weight:bold">Harga Beli Akhir (Bundling)</td>
					<td class="profil"><?= rupiah($hargabundling) ?></td>
			  	</tr>
			  	<tr>
					<td class="profil" style="font-weight:bold">Harga Beli Rata2 (Bundling)</td>
					<td class="profil"><?= rupiah($hargabundlingrata2) ?></td>
			  	</tr>
			  	<?php } else { ?>
			  	<tr>
					<td class="profil" style="font-weight:bold">Harga Beli Rata-Rata</td>
					<td class="profil"><?= rupiah($stokpopgroup[0]['data'][0]['HargaBeliRataRata']) ?></td>
			  	</tr>
			  	<tr>
					<td class="profil" style="font-weight:bold">Harga Beli Akhir</td>
					<td class="profil"><?= rupiah($stokpopgroup[0]['data'][0]['HargaBeliAkhir']) ?></td>
			  	</tr>
			  	<tr>
					<td class="profil" style="font-weight:bold">Harga Jual Set</td>
					<td class="profil"><?= rupiah($stokpopgroup[0]['data'][0]['HargaJualSet']) ?></td>
			  	</tr>
			  	<tr>
					<td class="profil" style="font-weight:bold">Harga Jual Modus</td>
					<td class="profil"><?= rupiah($stokpopgroup[0]['data'][0]['HargaJualModus']) ?></td>
			  	</tr>
			  <?php 
			  	}
			  }
			  ?>   
			  <tr>
				<td class="profil" style="font-weight:bold">Cicilan</td>
				<td class="profil">
                    <?php
                    foreach ($kcilprod as $kcp) {
                        $cilbank = substr($kcp, 0, 1);
                        $nilcil = substr($kcp, 1);
                        $cilarray[$cilbank][] = $kcp;
                        if (in_array($kcp,$cicilan)) {
                            $cilmonth[$nilcil]++;
                        }
                    }

                    ?>
                    <input type="checkbox" id="all" <?php if ($cilmonth[3]+$cilmonth[6]+$cilmonth[1] == count($kcilprod)) echo 'checked'; ?>>Semua payment 3, 6, 12 bulan
                    <br>
                    <input type="checkbox" id="all3" <?php if ($cilmonth[3] == count($cilpayment)) echo 'checked'; ?>>Semua payment 3 bulan
                    <br>
                    <input type="checkbox" id="all6" <?php if ($cilmonth[6] == count($cilpayment)) echo 'checked'; ?>>Semua payment 6 bulan
                    <br>
                    <input type="checkbox" id="all12" <?php if ($cilmonth[1] == count($cilpayment)) echo 'checked'; ?>>Semua payment 12 bulan
                    <br>

                    <?php
                    foreach ($cilarray as $key => $value) {
                        echo '<table width="75%" style="border-bottom: 1px solid #e6e6e6">';
                        echo '<tr>';
                        echo '<td width="30%">'.$cilpayment[$key].'</td>';
                        foreach ($value as $nilai) {
                            $cilselect = (in_array($nilai,$cicilan)) ? 'checked' : '';
                            echo '<td width="15%"><input class="cil'. $angkacilprod[$nilai] .'" type="checkbox" name="cicilan[]" '. $cilselect .' value="'.$nilai.'">'. $angkacilprod[$nilai] .' </td>';

                        }
                        echo '</tr></table>';
                    }
                    ?>
                </td>
			  </tr>  
			  <tr>
				<td class="profil" style="font-weight:bold">Ringkasan Spesifikasi</td>
				<td class="profil"><input name="specsumm" class="fieldtext" type="text"value="<?php echo stripslashes(htmlspecialchars($specsumm)); ?>" size="50">
				</td>
			  </tr>  
			  <tr valign="top">
				<td class="profil" style="font-weight:bold">Spesifikasi</td>
				<td class="profil"><textarea name="spec" cols="50" rows="5" class="fieldtext"><?php echo stripslashes(htmlspecialchars($spec)); ?></textarea> 
				<?php if (in_array(9,$salah)) { echo '*'; } ?></td>
			  </tr>  
			  <tr valign="top">
				<td class="profil" style="font-weight:bold">Sekilas Produk</td>
				<td class="profil">
                    <textarea name="sekilas" id="sekilas" class="fieldtext">
                        <?php echo stripslashes(htmlspecialchars($sekilas)); ?>
                    </textarea>
                    <script>CKEDITOR.replace('sekilas');</script>
				</td>
			  </tr>
                <tr valign="top">
                    <td class="profil" style="font-weight:bold">Tags</td>
                    <td class="profil">
                        <select id="tags" name="tags[]" class="form-control" multiple="multiple">
                            <?php
                            foreach ($all_tags as $all_tag) {
                                echo '<option value="'. $all_tag .'" '. (in_array($all_tag, $tags) ? 'selected="selected"' : '') .'>'.$all_tag.'</option>';
                            }
                            foreach ($tags as $tag) {
                                if (!in_array($tag, $all_tags)) {
                                    echo '<option value="'. $tag .'" selected="selected">'.$tag.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="profil" style="font-weight:bold">Teks Ilustrasi</td>
                    <td class="profil">
                        <select name="il" class="fieldtext">
                            <option value="1" <?php if ($il == 1) { echo 'selected'; } ?>>Ya</option>
                            <option value="0" <?php if ($il == 0) { echo 'selected'; } ?>>Tidak</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="profil" style="font-weight:bold">Meta Description</td>
                    <td class="profil">
                        <textarea name="meta_desc" rows="3" cols="35"><?= stripslashes(htmlspecialchars($meta_desc)) ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td class="profil" style="font-weight:bold">Darat/Udara</td>
                    <td class="profil"><select name="du" class="fieldtext">
                            <option value="U" <?php if ($du == 'U') { echo 'selected'; } ?>>Udara</option>
                            <option value="D" <?php if ($du == 'D') { echo 'selected'; } ?>>Darat</option>
                        </select></td>
                </tr>
			  <tr>
				<td class="profil" style="font-weight:bold">Show</td>
				<td class="profil"><select name="show" class="fieldtext">
					<option value="0" <?php if ($show == 0) { echo 'selected'; } ?>>Ya</option>
					<option value="1" <?php if ($show == 1) { echo 'selected'; } ?>>Tidak</option>
				</select></td>
			  </tr>
                <tr>
                    <td class="profil" style="font-weight:bold">Show Feed</td>
                    <td class="profil"><select name="show_feed" class="fieldtext">
                            <option value="1" <?php if ($show_feed == 1) { echo 'selected'; } ?>>Ya</option>
                            <option value="0" <?php if ($show_feed == 0) { echo 'selected'; } ?>>Tidak</option>
                        </select></td>
                </tr>
			  <tr>
				<td class="profil" style="font-weight:bold">Score Multiplier</td>
				<td class="profil"><input name="scoremultiplier" class="fieldtext" type="text"value="<?php echo stripslashes(htmlspecialchars($scoremultiplier)); ?>" size="50">
				</td>
			  </tr>  
			  <tr>
				<td class="profil" style="font-weight:bold">Pilihan</td>
				<td class="profil"><select name="pilihan" class="fieldtext">
					<option value="0" <?php if ($pilihan == 0) { echo 'selected'; } ?>>Tidak</option>
					<option value="1" <?php if ($pilihan == 1) { echo 'selected'; } ?>>Ya</option>
				</select></td>
			  </tr>
			  <tr>
				<td class="profil" style="font-weight:bold">Masuk Tanggal</td>
				<td class="profil"><?php echo $namahari[date('w',$addon)].', '.date('d',$addon).' '.$namabulan[date('n',$addon)].' '.date('Y',$addon); ?>
				</td>
			  </tr>  
			  <tr>
				<td class="profil" style="font-weight:bold">Last Update</td>
				<td class="profil"><?php echo $namahari[date('w',$updated)].', '.date('d',$updated).' '.$namabulan[date('n',$updated)].' '.date('Y',$updated); ?>
				</td>
			  </tr>  
			  <tr align="left">
				<td colspan="2" class="profil">
                    <input type="hidden" name="stok_lama" value="<?= $stok ?>">
                    <input type="hidden" name="status_lama" value="<?= $status ?>">
					<input id="submit" name="Edit" type="submit" class="button" value="Edit">
					<input name="Batal" type="submit" class="button" value="Batal">
					<input type="reset" class="button" value="Reset">
				</td>
			  </tr>
			</table>
        </form>
	</div>
</div>
<?php include('footer.php'); ?>
</div>

</body>
</html>
