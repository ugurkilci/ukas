<?php
	session_start();
	include 'ayar.php';
	include 'ukas.php';

	$p = @$_GET["p"];
	
	$sorgu = $db->prepare("SELECT COUNT(*) FROM uyeler");
	$sorgu->execute();
	$say = $sorgu->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?php echo $base; ?>/uyelik.php?p=giris">
    <title>Başlık</title>
    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-grid.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-reboot.min.css">
    <link rel="stylesheet" type="text/css" href="css/uyelik.css">
    <meta name="MobileOptimized" content="450">
</head>
<body>
	<div class="container">
		<div class="row justify-content-md-center aciklama">
			<div class="col-sm-4">
				<br /><br />
				<?php

					switch ($p) {
						case 'cikis':
							if ($_SESSION) {
								ukas_cikis("ayar.php", "index.php");
							}else{
								header("LOCATION:404");
							}
							break;

						case 'kayit':
							if ($_SESSION) {
								header("LOCATION:404.html");
							}else{
								ukas_mail("ugurbocugu8@gmail.com", "Ukas", "Hoşgeldin brocan, harcanıyosun buralarda.. Sana ihtiyacım var.. :)");

								ukas_kayit("ayar.php", "<p class='text-warning'>Lütfen boş bırakmayınız!</p>", "<p class='text-danger'>Böyle bir eposta mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p class='text-warning'>Böyle bir kullanıcı adı mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p class='text-success'>Başarıyla kaydoldun! :)</p>", $devam, "<p class='text-danger'>Kullanıcı adı veya şifre hatalı!</p>", "<p class='text-danger'>Kayıt başarısız!</p>", "<p>Şifreniz bir birine eşleşmiyor!</p>", "<p>Lütfen gerçek bir eposta giriniz!</p>", true);
								echo '<strong>Sen de '.$say.' kayıtlı kişiden biri ol,</strong><h1 class="text-center"><strong>Şimdi Kayıt Ol!</strong></h1>
								<form action="" method="POST">
									<strong>Ad Soyad:</strong>
									<input type="text" class="form-control" name="adsoyad">
									<strong>Kullanıcı adı:</strong>
									<input type="text" class="form-control" name="kadi">
									<strong>Şifre:</strong>
									<input type="password" class="form-control" name="sifre">
									<strong>Şifre (Tekrar):</strong>
									<input type="password" class="form-control" name="sifret">
									<strong>E-Posta:</strong>
									<input type="text" class="form-control" name="eposta"><br />
									<input type="submit" class="btn btn-block btn-danger" name="kayit" value="Kayıt Ol">
								</form>
								<hr>
								<a href="uyelik?p=giris" class="btn btn-block btn-success" title="Toosba yı duydun mu?"><strong><em>Şimdi giriş yap!</em></strong></a><br />
								<a href="valide" class="text-dark" title=""><small>&larr; <strong><em>Anasayfaya dön</em></strong></small></a>';
							}
							break;
						
						default:
							if ($_SESSION) {
								header("LOCATION:404.html");
							}else{
								ukas_giris("ayar.php", "index.php", "<p class='text-warning'>Lütfen boş bırakmayınız!</p>", "<p class='text-danger'>Kullanıcı adı veya şifre hatalı!</p>");
								
								echo '<h1 class="text-center"><strong>Giriş Yap</strong></h1>
								<br /><form action="" method="POST">
									<strong>Kullanıcı Adı:</strong>
									<input type="text" class="form-control" name="kadi" placeholder="Kargadio">
									<strong>Şifre:</strong>
									<input type="password" class="form-control" name="sifre" placeholder="123"><br />
									<input type="submit" class="btn btn-block btn-success" name="giris" value="Giriş Yap">
								</form>
								<hr>
								<a href="uyelik?p=kayit" class="btn btn-block btn-danger" title=""><strong><em>Sen de '.$say.' kişiden biri ol ve şimdi kayıt ol!</em></strong></a><br />
								<a href="valide" class="text-dark" title=""><small>&larr; <strong><em>Anasayfaya dön</em></strong></small></a>';
								}
							break;
					}

				?>
			</div>
		</div><br /><center><small>Ukas &copy; <?php echo date("Y"); ?> - Bir Uğur KILCI ürünüdür.</small></center><br /><br />
	</div>

</body>
</html>