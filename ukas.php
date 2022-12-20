<?php

	/*
	 *
	 * Ukas
	 * 
	 * @author Uğur KILCI
	 * @version 1.2
	 * 
	 * @github ugurkilci
	 * @instagram ugur2nd
	 * @twitter ugur2nd
	 * 
	 *
	*/

	$base = "http://localhost/";

	function reptr($text) { // seo link yapısı
	    $text     = trim($text);
	    $search   = array(
	        'Ç',
	        'ç',
	        'Ğ',
	        'ğ',
	        'ı',
	        'İ',
	        'Ö',
	        'ö',
	        'Ş',
	        'ş',
	        'Ü',
	        'ü',
	        ' ',
	        '!',
	        '.',
	        ':',
	        ';',
	        '?',
	        ',',
	        ')',
	        '(',
	        ']',
	        '[',
	        '}',
	        '{',
	        "/",
	        "&"
	    );
	    $replace  = array(
	        'c',
	        'c',
	        'g',
	        'g',
	        'i',
	        'i',
	        'o',
	        'o',
	        's',
	        's',
	        'u',
	        'u',
	        '-',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        '',
	        "",
	        "-"
	    );
	    $new_text = str_replace($search, $replace, $text);
	    return strtolower($new_text);
	}

	function epostakontrol($mail) { // mail kontrol
	    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
	        return 1;
	    }else{
	        return 0;
	    }
	}

	function ukas_giris($ukas_yonlendir, $ukas_uyariyazisi, $ukas_hatayazisi) { 
		// Ayar dosyası, Giriş yapıldıktan sonra yönlendirilecek yer, Uyarı mesajı, Hata mesajı
		// "ayar.php", "index.php", "<p>Lütfen boş bırakmayınız!</p>", "<p>Kullanıcı adı veya şifre hatalı!</p>"

		global $db;

	    $uyecek = $db -> prepare("SELECT * FROM uyeler WHERE uye_kadi=? && uye_sifre=?");
	    
	    if (isset($_POST["giris"])) { // Giriş yapılmışsa

	        $kadi  = htmlspecialchars(trim(str_replace(" ", "", $_POST["kadi"]))); // Kullanıcı adı
	        $sifre = md5(sha1($_POST["sifre"])); // Şifre

	        if (empty($kadi) || empty($sifre)) { // Eğer boş bırakıldıysa
	            echo $ukas_uyariyazisi; // Uyarı mesajı
	        }else{ // Eğer boş bırakılmamışsa

	            $uyecek -> execute(array(
	                $kadi,
	                $sifre
	            ));
	            $fetch    = $uyecek -> fetch(PDO::FETCH_ASSOC);
	            $rowcount = $uyecek -> rowCount();
	            
	            if ($rowcount) { // Giriş yapılmışsa
	                // CSRF Kontrolü - Forma token eklemeyi unutma!
			/*if (!isset($_POST["_token"])) { die('Token bulunamadı!'); }
			if ($_POST["_token"] !== $_SESSION["_token"]) { die('Hak yeme hack yeme!'); }*/
			    
	                $_SESSION["uye_id"] 			= $fetch["uye_id"]; 		// Üye id
	                $_SESSION["uye_adsoyad"] 		= $fetch["uye_adsoyad"]; 	// Üye adı soyadı
	                $_SESSION["uye_kadi"] 			= $fetch["uye_kadi"]; 		// Üye kullanıcı adı
               		$_SESSION["uye_sifre"] 			= $fetch["uye_sifre"]; 		// Üye şifresi
	                $_SESSION["uye_eposta"] 		= $fetch["uye_eposta"]; 	// Üye epostası
	                $_SESSION["uye_onay"] 			= $fetch["uye_onay"]; 		// Üye onayı
	                
	                header("REFRESH:2;URL=" . $ukas_yonlendir); // Yönlendir

	            }else{ // Giriş yapılmamışsa
	                echo $ukas_hatayazisi; // Hata mesajı ver
	            }
	        }
	    }
	}

	function ukas_mail($ukas_benimmailim, $ukas_konu, $ukas_mesaj){
		// Bizim mailimiz, Konumuz, Mesajımız
		global $ukas_benimmailimx;
		global $ukas_konux;
		global $ukas_mesajx;

		$ukas_benimmailim 	= $ukas_benimmailimx;
		$ukas_konu 			= $ukas_konux;
		$ukas_mesaj 		= $ukas_mesajx;
	}

	function ukas_kayit($ukas_bosbirakilmauyarisi, $ukas_mailvarsamesaji, $ukas_kadivarmesaji, $ukas_kayitbasarili, $ukas_yonlendir, $ukas_kadisifrehatali, $ukas_kayitbasarisiz, $ukas_sifreeslesmiyor, $ukas_sahtemailuyarisi) {
		// Ayar dosyası, Boş bırakma uyarısı, Mail mevcutsa, Kullanıcı adı mevcutsa, Kayıt başarılıysa, Kayıt yaptıktan sonra yönlendirilecek adres, Kullanıcı adı veya şifre hatalıysa, Kayıt başarısızsa, Şifreler eşleşmiyorsa, Eğer mail gerçek değilse, Mail gönderilsin mi?
		// "ayar.php", "<p>Lütfen boş bırakmayınız!</p>", "<p>Böyle bir eposta mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p>Böyle bir kullanıcı adı mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p>Başarıyla kaydoldun! :)</p>", "index.php", "<p>Kullanıcı adı veya şifre hatalı!</p>", "<p>Kayıt başarısız!</p>", "<p>Şifreniz bir birine eşleşmiyor!</p>", "<p>Lütfen gerçek bir eposta giriniz!</p>", false

		global $ukas_benimmailimx;
		global $ukas_konux;
		global $ukas_mesajx;

		global $db; // Ayar dosyası

	    if (isset($_POST["kayit"])) { // Kayıt Ol

	        $isim  			= htmlspecialchars(trim($_POST["adsoyad"])); 	// Ad Soyad
	        $kadi  			= htmlspecialchars(trim(str_replace(" ", "", $_POST["kadi"]))); 		// Kullanıcı Adı
	        $sifre  		= htmlspecialchars(trim($_POST["sifre"])); 		// Şifre
	        $sifret  		= htmlspecialchars(trim($_POST["sifret"])); 	// Şifre Tekrar
	        $mail  			= htmlspecialchars(trim($_POST["eposta"])); 	// E-Posta

	        $sifrele 		= md5(sha1($sifre)); // Şifrele

	        if (empty($isim) || empty($kadi) || empty($sifre) || empty($sifret) || empty($mail)) { // Boş bırakılmışsa
				echo $ukas_bosbirakilmauyarisi; // Uyarı mesajı ver
	        }else{ // Boş bırakılmamışsa
			
			// CSRF Kontrolü - Forma token eklemeyi unutma!
			/*if (!isset($_POST["_token"])) { die('Token bulunamadı!'); }
			if ($_POST["_token"] !== $_SESSION["_token"]) { die('Hak yeme hack yeme!'); }*/

	            $kontrol_et = epostakontrol($mail); // Maili kontrol et
	            
	            if ($kontrol_et == "1") { // Gerçek bir mail kullanılmışsa

	                $epostakontrol = $db -> prepare("SELECT * FROM uyeler WHERE uye_eposta =:uye_eposta");
					$epostakontrol -> execute(array('uye_eposta'=>$mail));
					$epostasaydirma = $epostakontrol -> rowCount();
					 
					if($epostasaydirma > 0){ // Böyle bir mail varsa hata mesajı ver
						echo $ukas_mailvarsamesaji;
					}else{ // Böyle bir mail yoksa kayıt işlemine devam et

						$kadikontrol = $db -> prepare("SELECT * FROM uyeler WHERE uye_kadi =:uye_kadi");
						$kadikontrol -> execute(array('uye_kadi' => $kadi));
						$kadisaydirma = $kadikontrol -> rowCount();
						 
						if($kadisaydirma > 0){ // Böyle bir üye varsa hata mesajı ver
							echo $ukas_kadivarmesaji;
						}else{ // Böyle bir üye yoksa kayıt işlemine devam et
							if ($sifre == $sifret) { // Şifre ile şifre tekrar uyuşuyorsa
								$sql = "INSERT INTO uyeler SET uye_adsoyad=?, uye_kadi=?, uye_sifre=?, uye_eposta=?";
								$kayit = $db -> prepare($sql);
								$kayit -> execute(array(
								    $isim,
								    $kadi,
								    $sifrele,
								    $mail
								));

								$uyecek = $db -> prepare("SELECT * FROM uyeler WHERE uye_kadi=? && uye_sifre=?");

								if ($kayit) { // Kayıt başarılıysa
									$uyecek -> execute(array(
						                $kadi,
						                $sifrele
						            ));
						            $fetch    = $uyecek -> fetch(PDO::FETCH_ASSOC);
						            $rowcount = $uyecek -> rowCount();
						            
						            if ($rowcount) { // Kayıt yaptıktan sonra giriş yap
						                
						                $_SESSION["uye_id"] 			= $fetch["uye_id"]; // üye id
						                $_SESSION["uye_adsoyad"] 		= $fetch["uye_adsoyad"]; // üye adı soyadı
						                $_SESSION["uye_kadi"] 			= $fetch["uye_kadi"]; // üye kullanıcı adı
					               		$_SESSION["uye_sifre"] 			= $fetch["uye_sifre"]; // üye şifresi
						                $_SESSION["uye_eposta"] 		= $fetch["uye_eposta"]; // üye epostası
						                $_SESSION["uye_onay"] 			= $fetch["uye_onay"]; // üye onayı
						                
										echo $ukas_kayitbasarili; // Kayıt başarılıysa başarılı yazdır. :D
								  		header("REFRESH:2;URL=" . $ukas_yonlendir); // Kayıt yaptıktan sonra yönlendir

								  	}else{
						                echo $ukas_kadisifrehatali;
						            }
								}else{ // Kayıt başarısızsa
									echo $ukas_kayitbasarisiz;
								}
							}else{ // Şifre ile şifre tekrar uyuşmuyorsa
								echo $ukas_sifreeslesmiyor;
							}
						}
		            } 
		        }else{ // Sahte bir mail kullanılmışsa
		            echo $ukas_sahtemailuyarisi;
		        }
	        }
	    }
	}

	function ukas_profil($p){
		global $db; 					// database değişkeni
		global $ukas_profil_id; 		// üyenin idsi
		global $ukas_profil_adsoyad; 	// üyenin adı soyadı
		global $ukas_profil_kadi; 		// üyenin kullanıcı adı
		global $ukas_profil_eposta; 	// üyenin epostası

		// üye kontrol
		$uyekontrol 	= $db -> prepare("SELECT * FROM uyeler WHERE uye_kadi =:uye_kadi ");
		$uyekontrol		-> execute(array('uye_kadi'=>$p));
		$uyesaydirma 	= $uyekontrol -> rowCount();
		 
		if($uyesaydirma > 0){ // üye varsa

			$uyecek 	= $db -> prepare("SELECT * FROM uyeler WHERE uye_kadi=?");
			$uyecek 	-> execute(array($p));
			$uye_cek 	= $uyecek -> fetch(PDO::FETCH_ASSOC);

			// gerekli verileri değişkenlere depolayalım
				$ukas_profil_id 		= $uye_cek["uye_id"];
				$ukas_profil_adsoyad 	= $uye_cek["uye_adsoyad"];
				$ukas_profil_kadi 		= $uye_cek["uye_kadi"];
				$ukas_profil_eposta 	= $uye_cek["uye_eposta"];
		}
	}

	function ukas_cikis($ukas_cikisyonlendir){
		// Ayar dosyası, Çıkış yaptıktan sonra yönlendirilecek adres
		// "ayar.php", "test.php"

		global $db;
		session_destroy();
		header("REFRESH:2;URL=" . $ukas_cikisyonlendir);
	}
