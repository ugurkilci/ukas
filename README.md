# Ukas
Hızlı ve kolay üyelik sistemi

Version: 1.1

Her hangi bir projede mutlaka üyelik sistemi oluyor ve her seferinde üyelik sistemi kodlamaktan ciğerim soldu. Bu yüzden basit bir framework yazdım. Bunu sayesinde tonlarca kod yapmaktan kurtuluyorsunuz. Maksimum 5 dakika da sistemi kuruyorsunuz. Bu da muazzam bir şey tabii ki. :)

# [Kurulum Bilgileri]
Session ı başlatıp, "ukas.php" dosyasını yüklüyip, ayar dosyasını ilave ediyoruz.
<code><pre>session_start();
include 'ayar.php';
include 'ukas.php';</pre></code>

# Php Giriş Kodları
<pre>ukas_giris("ayar.php", "testprofil.php", "Lütfen boş bırakmayınız!", "Kullanıcı adı veya şifre hatalı!");
// Ayar dosyası, Giriş yapıldıktan sonra yönlendirilecek yer, Uyarı mesajı, Hata mesajı</pre>

# Html Giriş Formu
<img src="https://raw.githubusercontent.com/ugurkilci/ukas/master/giris.jpg">

# Php Kayıt Kodları
<pre>ukas_kayit("ayar.php", "Lütfen boş bırakmayınız!", "Böyle bir eposta mevcut! Lütfen başka bir tane deneyiniz!", "Böyle bir kullanıcı adı mevcut! Lütfen başka bir tane deneyiniz!", "Başarıyla kaydoldun! :)", "test.php", "Kullanıcı adı veya şifre hatalı!", "Kayıt başarısız!", "Şifreniz bir birine eşleşmiyor!", "Lütfen gerçek bir eposta giriniz!", true);
// Ayar dosyası, Boş bırakma uyarısı, Mail mevcutsa, Kullanıcı adı mevcutsa, Kayıt başarılıysa, Kayıt yaptıktan sonra yönlendirilecek adres, Kullanıcı adı veya şifre hatalıysa, Kayıt başarısızsa, Şifreler eşleşmiyorsa, Eğer mail gerçek değilse, Mail gönderilsin mi?</pre>

# Html Kayıt Formu
<img src="https://raw.githubusercontent.com/ugurkilci/ukas/master/kayit.jpg">

# Önemli Uyarı
Eğer "mail var (true)" yaparsanız "ukas_kayit();" dan önce "ukas_mail();" eklemeniz gerek aksi halde hata alırsınız.

Örnek:
<pre>ukas_mail("bizimmailimiz@gmail.com", "Konumuz", "Mesajımız");
// Bizim mailimiz, Konumuz, Mesajımız</pre>
