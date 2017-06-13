<?php
	session_start();
	include 'ayar.php';
	include 'ukas.php';
?>

<?php
	ukas_giris("ayar.php", "testprofil.php", "<p>Lütfen boş bırakmayınız!</p>", "<p>Kullanıcı adı veya şifre hatalı!</p>");
	// Ayar dosyası, Giriş yapıldıktan sonra yönlendirilecek yer, Uyarı mesajı, Hata mesajı
?>
<strong>Giriş</strong><br />
<form action="" method="POST">
	<input type="text" name="kadi" placeholder="Kullanıcı adı"><br />
	<input type="password" name="sifre" placeholder="Şifre"><br />
	<input type="submit" name="giris" value="Giriş">
</form>

<hr>
<?php
	ukas_mail("bizimmailimiz@gmail.com", "Konumuz", "Mesajımız");
	// Bizim mailimiz, Konumuz, Mesajımız

	ukas_kayit("ayar.php", "<p>Lütfen boş bırakmayınız!</p>", "<p>Böyle bir eposta mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p>Böyle bir kullanıcı adı mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p>Başarıyla kaydoldun! :)</p>", "test.php", "<p>Kullanıcı adı veya şifre hatalı!</p>", "<p>Kayıt başarısız!</p>", "<p>Şifreniz bir birine eşleşmiyor!</p>", "<p>Lütfen gerçek bir eposta giriniz!</p>", true);
	// Ayar dosyası, Boş bırakma uyarısı, Mail mevcutsa, Kullanıcı adı mevcutsa, Kayıt başarılıysa, Kayıt yaptıktan sonra yönlendirilecek adres, Kullanıcı adı veya şifre hatalıysa, Kayıt başarısızsa, Şifreler eşleşmiyorsa, Eğer mail gerçek değilse, Mail gönderilsin mi?
?>
<strong>Kayıt</strong><br />
<form action="" method="POST">
	<input type="text" name="adsoyad" placeholder="Ad Soyad"><br />
	<input type="text" name="kadi" placeholder="Kullanıcı adı"><br />
	<input type="password" name="sifre" placeholder="Şifre"><br />
	<input type="password" name="sifret" placeholder="Şifre Tekrar"><br />
	<input type="text" name="eposta" placeholder="E Mail"><br />
	<input type="submit" name="kayit" value="Kayıt">
</form>