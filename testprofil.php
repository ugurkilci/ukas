<?php
	session_start();
	include 'ayar.php';
	include 'ukas.php';

	echo '<strong>Session</strong><br />
	<!-- Giriş yapmadan bakarsanız, hata verir! -->';
	echo $_SESSION["uye_id"] . "<br />";
	echo $_SESSION["uye_adsoyad"] . "<br />";
	echo $_SESSION["uye_kadi"] . "<br />";
	echo $_SESSION["uye_sifre"] . "<br />";
	echo $_SESSION["uye_eposta"] . "<br />";
	echo $_SESSION["uye_onay"] . "<br />";

	echo '<hr><strong>Profil</strong><br />';
	ukas_profil("test1");
	
	/*
		ukas_profil e get ekleyerek urlden çekebilirsiniz.
	*/

	echo $ukas_profil_id . "<br />";
	echo $ukas_profil_adsoyad . "<br />";
	echo $ukas_profil_kadi . "<br />";
	echo $ukas_profil_eposta . "<br />";
?>