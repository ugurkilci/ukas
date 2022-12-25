<?php
          session_start();
          include 'ayar.php';
          include 'ukas.php';

          $p = @$_GET["p"];

          $sorgu = $db->prepare("SELECT COUNT(*) FROM uyeler");
          $sorgu->execute();
          $say = $sorgu->fetchColumn();

        switch ($p) {
           case 'sifremiunuttum':
            if ($_POST) {
                $eposta = htmlspecialchars( $_POST["eposta"] );

                if (
                    empty( $eposta )
                ) {
                    echo '<p class="alert alert-warning">Lütfen boş bırakmayınız!</p>';
                } else {
                    if (filter_var($eposta, FILTER_VALIDATE_EMAIL)){ // :)
                        $selectRow = $db -> prepare("SELECT * FROM uyeler WHERE
                            uye_eposta =:uye_eposta
                        ");
                        $selectRow -> execute([
                            'uye_eposta' => $eposta
                        ]);
                        $selectRow = $selectRow -> rowCount();
                        
                        if($selectRow > 0){ // Var
                            $yeniSifre  = time() . rand(111,999);
                            $Sifrele    = md5(sha1( $yeniSifre ));

                            $sifreyiGuncelle = $db->prepare("UPDATE uyeler SET uye_sifre=? WHERE uye_eposta=?");
                            $sifreyiGuncelle -> execute([
                                $Sifrele,
                                $eposta
                            ]);

                            if ($sifreyiGuncelle) {





                                require("emailotomasyonu/class.phpmailer.php"); // PHPMailer dosyamizi çagiriyoruz
                                
                                $mailim = "noreply@minik.link";
                                $mail = new PHPMailer(); // Sinifimizi $mail degiskenine atadik
                                $mail->IsSMTP(true);  // Mailimizin SMTP ile gönderilecegini belirtiyoruz
                                $mail->CharSet = 'UTF-8'; //Türkçe yazı karakterleri için CharSet  ayarını bu şekilde yapıyoruz.
                                $mail->From     = $mailim;//"admin@localhost"; //Gönderen kisminda yer alacak e-mail adresi
                                $mail->Sender   = $mailim;//"admin@localhost";//Gönderen Mail adresi
                                //$mail->ReplyTo  = ($mailim);//"admin@localhost";//Tekrar gönderimdeki mail adersi
                                $mail->AddReplyTo=($mailim);//"admin@localhost";//Tekrar gönderimdeki mail adersi
                                $mail->FromName = "Ugur KILCI";//"PHP Mailer";//gönderenin ismi
                                $mail->Host     = "mail.site.com";//"localhost"; //SMTP server adresi
                                $mail->SMTPAuth = true; //SMTP server'a kullanici adi ile baglanilcagini belirtiyoruz
                                $mail->SMTPSecure = false;
                                $mail->SMTPAutoTLS = false;
                                $mail->Port     = 587; //Natro SMPT Mail Portu
                                
                                $mail->Username = "noreply@site.com";//"admin@localhost"; //SMTP kullanici adi
                                $mail->Password = 'şifreniz';//""; //SMTP mailinizin sifresi
                                $mail->WordWrap = 50;
                                $mail->IsHTML(true); //Mailimizin HTML formatinda hazirlanacagini bildiriyoruz.
                                $mail->Subject  = "Başlık";//"Deneme Maili"; // Mailin Konusu Konu
                                //Mailimizin gövdesi: (HTML ile)
                                $body  = '<b>Site adınız:</b><br>Şifreniz yenilendi! Yeni şifreniz: ' . $yeniSifre . '<br><b>Not:</b> Eğer şifrenizi siz güncellemediyseniz lütfen info@site.com adresine eposta gönderiniz!<br><br><b>Tarih:</b> ' . date("m.d.Y") . ' <b>Saat:</b> ' . date("H:i:s");
                                
                                $textBody = $body;
                                $mail->Body = $body;
                                $mail->AltBody = $textBody;
                                $mail->ClearAddresses();
                                $mail->ClearAttachments();
                                
                                // Mail gönderilecek adresleri ekliyoruz. Birden fazla ekleme yapılabilir.
                                $mail->AddBCC( $eposta );
                                
                                $mail->Send();
                                $mail->ClearAddresses();
                                $mail->ClearAttachments();
                                error_reporting(0);
                                







                                echo '<p class="alert alert-success">Şifreniz başarıyla güncellendi! Lütfen eposta adresinizi kontrol ediniz. Yeni şifrenizi oraya gönderdik! :) [NOT: Spam kutunuza da bakmayı unutmayınız!]</p>';


                            } else {
                                echo '<p class="alert alert-danger">Şifreniz güncellenemedi! Lütfen tekrar deneyiniz!</p>';
                            }
                        }else{ // Yok
                            echo '<p class="alert alert-danger">Böyle bir eposta adresi bulunmamaktadır!</p>';
                        }
                    } else { // :(
                        echo '<p class="alert alert-danger">Lütfen gerçek bir eposta adresi yazınız!</p>';
                    }
                }
                
            }
            echo '
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <h1 class="mb-5 d-block">Şifremi Unuttum</h1>
                        <form action="" method="post">
                            <strong>Eposta:</strong>
                            <input type="email" name="eposta" class="form-kontrol" placeholder="sakip@sabanci.com">
                            <input type="hidden" name="_token" value="'.$_SESSION["_token"].'">
                            <input type="submit" name="sifremiunuttum" value="Yeni Şifremi Epostama Gönder" class="mavi-menu mt-3">
                        </form>
                        <a href="uyelik" class="d-block mt-2">Giriş Yap</a>
                    </div>
                </div>
            </div>';

            break;
            case 'cikis':
                if ($_SESSION["uye_id"]) {
                    ukas_cikis("index.php");
                }else{
                    header("LOCATION:index.php");
                }
                break;

            case 'kayit':
                if ($_SESSION["uye_id"]) {
                    header("LOCATION:index.php");
                }else{
                    ukas_kayit("<p class='text-warning'>Lütfen boş bırakmayınız!</p>", "<p class='text-danger'>Böyle bir eposta mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p class='text-warning'>Böyle bir kullanıcı adı mevcut! Lütfen başka bir tane deneyiniz!</p>", "<p class='text-success'>Başarıyla kaydoldun! :)</p>", $devam, "<p class='text-danger'>Kullanıcı adı veya şifre hatalı!</p>", "<p class='text-danger'>Kayıt başarısız!</p>", "<p>Şifreniz bir birine eşleşmiyor!</p>", "<p>Lütfen gerçek bir eposta giriniz!</p>");
                    echo '<h1 class="text-center"><strong>Şimdi Kayıt Ol!</strong></h1>
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
                        <input type="hidden" name="_token" value="'.$_SESSION["_token"].'">
                        <input type="submit" class="btn btn-block btn-dark" name="kayit" value="Kayıt Ol">
                    </form>
                    <hr>
                    <a href="uyelik.php?p=giris" class="btn btn-block btn-secondary">Şimdi giriş yap!</a><br />
                    <a href="index.php" class="text-dark"><small>&larr; Anasayfaya dön</small></a>';
                }
                break;

            default:
                if ($_SESSION["uye_id"]) {
                    header("LOCATION:index.php");
                }else{
                    ukas_giris("index.php", "<p class='text-warning'>Lütfen boş bırakmayınız!</p>", "<p class='text-danger'>Kullanıcı adı veya şifre hatalı!</p>");

                    echo '<h1 class="text-center"><strong>Giriş Yap</strong></h1>
                    <br /><form action="" method="POST">
                        <strong>Kullanıcı Adı:</strong>
                        <input type="text" class="form-control" name="kadi">
                        <strong>Şifre:</strong>
                        <input type="password" class="form-control" name="sifre"><br />
                        <input type="hidden" name="_token" value="'.$_SESSION["_token"].'">
                        <input type="submit" class="btn btn-block btn-dark" name="giris" value="Giriş Yap">
                    </form>
                    
                    <a href="uyelik.php?p=sifremiunuttum" class="btn btn-block btn-secondary mt-3">Şifremi unuttum</a><br>
                    <a href="uyelik.php?p=kayit" class="btn btn-block btn-secondary mt-3">Şimdi kayıt ol!</a>
                    <hr>
                    <a href="index.php" class="text-dark"><small>&larr; Anasayfaya dön</small></a>';
                    }
                break;
        }

      ?>
